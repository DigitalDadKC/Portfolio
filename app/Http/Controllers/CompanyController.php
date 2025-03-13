<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\State;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\StateResource;
use App\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = (Company::count()) ? CompanyResource::collection(Company::all())->first() : new CompanyResource(new Company([
            'id' => NULL,
            'name' => '',
            'logo' => NULL,
            'address' => NULL,
            'city' => NULL,
            'state' => NULL
        ]));
        return Inertia::render('Estimating/Company', [
            'new' => TRUE,
            'company' => $company,
            'states' => StateResource::collection(State::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string:255',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'logo' => 'required|image'
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('company');
            Company::create([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'state_id' => $request->state_id,
                'logo' => $logo
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
