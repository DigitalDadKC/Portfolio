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
            'favors' => Favor::orderBy('date')->get(),
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

        return back();
    }

    public function update(Request $request, string $id)
    {      
        $favor = Favor::findOrFail($id);

        $request->validate([
            'favor' => 'required',
            'date' => 'required',
        ]);

        $favor->update([
            'favor' => $request->favor,
            'date' => $request->date,
        ]);

        return back();
    }

    public function destroy(string $id)
    {
        $favor = Favor::findOrFail($id);
        $favor->delete();

        return back();
    }
}
