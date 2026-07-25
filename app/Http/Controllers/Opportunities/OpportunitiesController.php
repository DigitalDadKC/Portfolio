<?php

namespace App\Http\Controllers\Opportunities;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Services\SamGovService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OpportunitiesController extends Controller
{
    public function index(Request $request, SamGovService $samGov) {
        // $filters = [
        //     'year' => $request->input('year', date('Y')),
        //     'state' => $request->input('state', NULL),
        //     'city' => $request->input('city', NULL),
        // ];

        // if($request->state != $filters['state']) {
        //     $filters['city'] = NULL;
        // }

        // $abbr = State::where('id', $filters['state'])->first()?->abbr;
        // $state_array = array_filter($samGov->searchLodging($filters['year']), fn($state) => $state['State'] === $abbr);
        // $cities = array_map(
        //     fn($city, $index) => [
        //         'id' => $index,
        //         'city' => $city,
        //     ],
        //     array_column($state_array, 'City'),
        //     array_keys(array_column($state_array, 'City'))
        // );
        // $city = $city = current(array_filter(
        //     $state_array,
        //     fn($s) => $s['City'] === $filters['city']
        // )) ?: null;

        $results = $samGov->getOpportunities();

        return Inertia::render('opportunities/Index', [
            // 'states' => State::orderBy('state')->get(),
            // 'cities' => fn() => $cities,
            'results' => fn() => $results,
            // 'filters' => fn() => $filters,
            // 'city' => fn() => $city,
        ]);
    }
}
