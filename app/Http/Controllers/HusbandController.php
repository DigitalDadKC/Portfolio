<?php

namespace App\Http\Controllers;

use App\Models\Favor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HusbandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Husband', [
            'favors' => Favor::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'favor' => 'required',
            'date' => 'required',
        ]);

        Favor::create([
            'favor' => $request->favor,
            'date' => $request->date,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
