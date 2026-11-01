<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureResource;

class FeatureController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/features/Index', [
            'features' => FeatureResource::collection(Feature::orderBy('order')->get())
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $count = Feature::count();
        Feature::create([
            'name' => $request->name,
            'order' => $count+1,
        ]);

        return back();
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $feature->update([
            'name' => $request->name,
        ]);

        return back();
    }

    public function destroy(Request $request, Feature $feature)
    {
        $feature->delete();
        $this->sort($request);
        return back();
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'features' => ['required', 'array'],
            'features.*.id' => ['required', 'exists:features,id'],
            'features.*.order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['features'] as $item) {
            Feature::whereKey($item['id'])->update(['order' => $item['order']]);
        }

        return back();
    }
}
