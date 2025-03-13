<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Job;
use App\Models\Line;
use Inertia\Inertia;
use App\Models\Scope;
use App\Models\State;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnitOfMeasurement;
use App\Http\Resources\JobResource;

class EstimatingController extends Controller
{
    private $ref_state;
    private $ref_uom;
    private $ref_line;
    private $ref_scope;
    private $ref_job;

    public function __construct()
    {
        $this->ref_state = collect([['id' => NULL, 'abbr' => '', 'state' => '']])->mapInto(State::class)->first();
        $this->ref_uom = collect([['id' => NULL, 'UOM' => '']])->mapInto(UnitOfMeasurement::class)->first();
        $this->ref_line = collect([['id' => NULL, 'description' => '', 'unit_of_measurement' => $this->ref_uom, 'price' => NULL, 'formatted_total' => "", 'quantity' => NULL, 'total' => NULL, 'days' => NULL]])->mapInto(Line::class);
        $this->ref_scope = collect([['id' => NULL, 'name' => '', 'area' => NULL, 'days' => NULL, 'total' => NULL, 'formatted_total' => "", 'lines' => $this->ref_line]])->mapInto(Scope::class);
        $this->ref_job = collect([['id' => NULL, 'number' => NULL, 'address' => '', 'city' => '', 'state' => $this->ref_state, 'total' => NULL, 'formatted_total' => "", 'days' => NULL, 'start_date' => NULL, 'notes' => NULL, 'scopes' => $this->ref_scope]])->mapInto(Job::class)->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];
        $filters['search'] = $request->search;
        $filters['pages'] = $request->pages ? $request->pages : 25;
        $jobs = Job::query()->with(['state', 'user:id,name,email', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement'])
        ->when($request->search, fn($query, $search) => $query->where('number', 'like', "%{$search}%")->orWhere('address', 'like', "%{$search}%")->orWhere('city', 'like', "%{$search}%"))
        ->orderBy('created_at', 'desc');
        $jobs = ($jobs->paginate($filters['pages'])->isEmpty()) ? JobResource::collection($jobs->paginate($filters['pages'], ['*'], 'page', 1)->withQueryString()) : JobResource::collection($jobs->paginate($filters['pages'])->withQueryString());
        return Inertia::render('Estimating/Index', [
            'jobs' => fn() => $jobs,
            'filters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Estimating/Proposal', [
            'new' => true,
            'job' => $this->ref_job,
            'company' => CompanyResource::collection(Company::all())->first(),
            'states' => State::get(),
            'unit_of_measurements' => UnitOfMeasurement::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job.number' => 'required',
            'job.address' => 'required',
            'job.city' => 'required',
            'job.state.id' => 'required',
            'job.scopes.*.name' => '',
            'job.scopes.*.area' => '',
            'job.scopes.*.lines.*.description' => 'required',
            'job.scopes.*.lines.*.price' => 'required',
            'job.scopes.*.lines.*.quantity' => 'required',
            'job.scopes.*.lines.*.unit_of_measurement.id' => 'required'
        ]);

        $job = Job::create([
            'number' => $request->job['number'],
            'address' => $request->job['address'],
            'city' => $request->job['city'],
            'state_id' => $request->job['state']['id'],
            'total' => $request->job['total'],
            'formatted_total' => $request->job['formatted_total'],
            'days' => $request->job['days'],
            'start_date' => $request->job['start_date'],
            'notes' => $request->job['notes']
        ]);

        foreach ($request->job['scopes'] as $scope) {
            $newScope = Scope::create(
                [
                    'name' => $scope['name'],
                    'area' => $scope['area'],
                    'days' => $scope['days'],
                    'total' => $scope['total'],
                    'formatted_total' => $scope['formatted_total'],
                    'job_id' => $job->id
                ]
            );

            foreach ($scope['lines'] as $key=>$line) {
                Line::create(
                    [
                        'order' => $key,
                        'description' => $line['description'],
                        'unit_of_measurement_id' => $line['unit_of_measurement']['id'],
                        'days' => $line['days'],
                        'price' => $line['price'],
                        'quantity' => $line['quantity'],
                        'total' => $line['total'],
                        'formatted_total' => $line['formatted_total'],
                        'scope_id' => $newScope->id
                    ]
                );
            }
        }

        return to_route('estimating.index');
    }

    public function edit(Job $job)
    {
        $job = JobResource::collection(Job::where('id', $job->id)->with('scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement')->get())->first();
        if (!$job->scopes->count()) {
            $job['scopes']->push($this->ref_scope[0]);
        }
        $job->scopes->each(function ($scope) {
            if (!$scope->lines->count()) {
                $scope->lines->push($this->ref_line[0]);
            }
        });
        return Inertia::render('Estimating/Proposal', [
            'job' => $job,
            'states' => State::get(),
            'unit_of_measurements' => UnitOfMeasurement::get(),
            'new' => false
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'job.number' => 'required',
            'job.address' => 'required',
            'job.city' => 'required',
            'job.state.id' => 'required',
            'job.scopes.*.name' => '',
            'job.scopes.*.area' => '',
            'job.scopes.*.lines.*.description' => 'required',
            'job.scopes.*.lines.*.price' => 'required',
            'job.scopes.*.lines.*.quantity' => 'required',
            'job.scopes.*.lines.*.unit_of_measurement.id' => 'required'
        ]);

        $job->update([
            'number' => $request->job['number'],
            'address' => $request->job['address'],
            'city' => $request->job['city'],
            'state_id' => $request->job['state']['id'],
            'total' => $request->job['total'],
            'formatted_total' => $request->job['formatted_total'],
            'days' => $request->job['days'],
            'start_date' => $request->job['start_date'],
            'notes' => $request->job['notes']
        ]);

        Scope::where('id', array_diff(Scope::where('job_id', $job->id)->pluck('id')->toArray(), array_column($request->job['scopes'], 'scope_id')))->delete();
        foreach ($request->job['scopes'] as $scope) {
            $newScope = Scope::updateOrCreate(
                ['id' => $scope['id']],
                [
                    'name' => $scope['name'],
                    'area' => $scope['area'],
                    'days' => $scope['days'],
                    'total' => $scope['total'],
                    'formatted_total' => $scope['formatted_total'],
                    'job_id' => $job->id
                ]
            );

            Line::whereIn('id', array_diff(Line::where(['scope_id' => $scope['id']])->pluck('id')->toArray(), array_column($scope['lines'], 'id')))->delete();
            foreach ($scope['lines'] as $key=>$line) {
                Line::updateOrCreate(
                    ['id' => $line['id']],
                    [
                        'order' => $key,
                        'description' => $line['description'],
                        'unit_of_measurement_id' => $line['unit_of_measurement']['id'],
                        'days' => $line['days'],
                        'price' => $line['price'],
                        'quantity' => $line['quantity'],
                        'total' => $line['total'],
                        'formatted_total' => $line['formatted_total'],
                        'scope_id' => $newScope->id
                    ]
                );
            }
        }

        if ($request->pdf === 'false') {
            return to_route('estimating.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function report(Request $request)
    {
        return Inertia::render('Estimating/Report', [
            'jobs' => Job::with(['state', 'user:id,name,email', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement'])->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function downloadPDF(Request $request, Job $job)
    {
        $job->load('scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement');
        $data = [
            'job' => $job,
            'scopes' => $job->scopes,
        ];
        $pdf = Pdf::loadView('exports.estimate-export', $data);
        return $pdf->download('Estimate.pdf');
    }

    public function browserPDF(Request $request, Job $job)
    {
        $job->load('scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement');
        $data = [
            'job' => $job,
            'scopes' => $job->scopes,
        ];
        $pdf = Pdf::loadView('exports.estimate-export', $data);
        return $pdf->stream('estimating-in-browser.pdf');
    }
}
