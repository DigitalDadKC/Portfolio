<?php

namespace App\Http\Controllers\Dropzone;

use App\Http\Controllers\Controller;
use App\Imports\DropzoneImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DropzoneController extends Controller
{
    public function index(Request $request) {
        return Inertia::render('dropzone/Index');
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        $import = new DropzoneImport();
        Excel::import($import, $request->file);

        if(!empty($import->errors)) {
            return back()->withErrors($import->errors);
        }

        // $import->imported->each(fn($model) => $model->load('material_unit_size', 'material_category'));
        return back()->with('imported', $import->imported);
    }
}
