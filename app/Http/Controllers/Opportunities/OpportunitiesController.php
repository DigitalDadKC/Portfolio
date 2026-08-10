<?php

namespace App\Http\Controllers\Opportunities;

use App\Http\Controllers\Controller;
use App\Services\SamGovService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OpportunitiesController extends Controller
{
    public function index(SamGovService $samGov) {

        $results = $samGov->getOpportunities();

        return Inertia::render('opportunities/Index', [
            'results' => fn() => $results,
        ]);
    }
}
