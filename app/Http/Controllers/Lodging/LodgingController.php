<?php

namespace App\Http\Controllers\Lodging;

use App\Http\Controllers\Controller;
use App\Services\Sam\SamGovService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LodgingController extends Controller
{
    public function index(Request $request, SamGovService $sam) {
        return Inertia::render('lodging/Index', [
            'data' => $sam->searchLodging(2026),
        ]);
    }
}
