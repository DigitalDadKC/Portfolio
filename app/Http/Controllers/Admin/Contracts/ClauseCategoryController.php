<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Models\ClauseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        DB::transaction(function () use ($clauseCategory) {
            $clauseCategory->delete();

            $this->resequence(
                ClauseCategory::orderBy('order')->pluck('id')->all()
            );
        });

        return back()->with(['message' => 'Success!', 'type' => 'error', 'description' => 'Clause category deleted']);
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'distinct', Rule::exists(ClauseCategory::class, 'id')],
        ]);

        DB::transaction(fn () => $this->resequence($data['ids']));

        return back()->with(['message' => 'Reordered!', 'type' => 'info', 'description' => 'Clause categories reordered']);
    }

    private function resequence(array $ids): void
    {
        foreach ($ids as $index => $id) {
            ClauseCategory::whereKey($id)->update(['order' => $index + 1]);
        }
    }
}