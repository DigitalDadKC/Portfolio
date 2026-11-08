<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContractResource;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Service;
use App\Services\Pdf\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function __construct(protected PdfService $pdf) {}
    public function index()
    {
        return Inertia::render('admin/contracts/contracts/Index', [
            'contracts' => ContractResource::collection(Contract::with('client', 'employee', 'services')->get()),
            'clients' => Client::with('employees')->orderBy('company')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'client_id' => 'nullable|required',
        ]);

        $contract = Contract::create([
            'price' => $request->price,
            'status' => 'created',
            'client_id' => $request->client_id,
        ]);
        $service_ids = $request->services ? array_map(fn($service) => $service['id'], $request->services) : [];
        $contract->services()->sync($service_ids);

        $this->pdf->createContract($contract);

        return back()->with('message', 'Contract created');
    }

    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'price' => 'required',
            'client_id' => 'nullable|required',
        ]);

        $contract->update([
            'price' => $request->price,
            'client_id' => $request->client_id,
        ]);

        $service_ids = $request->services ? array_map(fn($service) => $service['id'], $request->services) : [];
        $contract->services()->sync($service_ids);

        $this->pdf->destroyContract($contract);
        $this->pdf->createContract($contract);
        
        return back()->with('message', 'Contract updated');
    }

    public function destroy(Contract $contract)
    {
        $this->pdf->destroyContract($contract);
        $contract->delete();

        return back()->with('message', 'Contract deleted');
    }

    public function browser(Request $request)
    {
        $contract = [
            'price' => 1000,
            'client' => [
                'company' => 'company',
                'email' => 'email',
                'address' => '123 Main Street',
                'city' => 'City',
                'state' => [
                    'abbr' => 'STATE',
                ],
                'zip' => '12345',
                'url' => 'https://example.com',
                'placeId' => NULL,
            ],
            'employee' => [
                'name' => 'John Doe'
            ],
            'services' => [
                [
                    'name' => 'Service 1',
                    'price' => 500,
                ],
                [
                    'name' => 'Service 2',
                    'price' => 1500,
                ],
                [
                    'name' => 'Service 3',
                    'price' => 2500,
                ],
            ]
        ];

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        $content = $pdf->output();

        if (Storage::disk('local')->exists('/contract.pdf')) {
            Storage::disk('local')->delete('/contract.pdf');
        }

        $fileName = 'contract.pdf';
        $filePath = Storage::disk('local')->put('/' . $fileName, $content);

        $pdf->save($filePath);
        return $pdf->stream();
    }

    public function send(Contract $contract)
    {
        $contract->load('client');
        dd($contract);
        $this->pdf->sendContract($contract);

        return back()->with('message', 'Contract sent');
    }
}
