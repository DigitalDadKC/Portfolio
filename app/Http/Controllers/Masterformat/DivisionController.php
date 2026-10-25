<?php

namespace App\Http\Controllers\Masterformat;

use Inertia\Inertia;
use App\Models\CsiSection;
use App\Models\CsiDivision;
use App\Http\Controllers\Controller;
use App\Models\CsiSubsection;

class DivisionController extends Controller
{
    public function index()
    {
        return Inertia::render('masterformat/Index', [
            'divisions' => CsiDivision::select('id', 'code', 'name')->get(),
            'sections' => CsiSection::select('id', 'code', 'name', 'csi_division_id')->get(),
            'subsections' => CsiSubsection::select('id', 'code', 'name', 'csi_section_id')->get(),
        ]);
    }
}
