<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Models\ClauseCategory;
use Illuminate\Http\Request;

class ClauseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $nextOrder = (ClauseCategory::max('order') ?? 0) + 1;

        ClauseCategory::create([
            'name' => $request->name,
            'order' => $nextOrder,
        ]);

        return back()->with(['message' => 'Success!', 'type' => 'success', 'description' => 'Clause category created']);
    }

    public function update(Request $request, ClauseCategory $clauseCategory)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $clauseCategory->update([
            'name' => $request->name,
        ]);

        return back()->with(['message' => 'Updated!', 'type' => 'info', 'description' => 'Clause category updated']);
    }

    public function destroy(ClauseCategory $clauseCategory)
    {
        $clauseCategory->delete();

        $this->sort(request()->merge(['order' => ClauseCategory::orderBy('order')->pluck('id')->toArray()]));

        return back()->with(['message' => 'Success!', 'type' => 'error', 'description' => 'Clause category deleted']);
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $index => $id) {
            ClauseCategory::where('id', $id)->update(['order' => $index + 1]);
        }

        return back()->with(['message' => 'Reordered!', 'type' => 'info', 'description' => 'Clause categories reordered']);
    }
}
