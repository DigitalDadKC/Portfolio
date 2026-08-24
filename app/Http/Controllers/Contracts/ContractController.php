<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use App\Services\SamGovService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function index(SamGovService $samGov) {

        $results = $samGov->getContractAwards();

        return Inertia::render('contracts/Index', [
            'results' => fn() => $results,
        ]);
    }
}
