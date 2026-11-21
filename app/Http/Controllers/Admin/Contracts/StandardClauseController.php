<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Models\ClauseCategory;
use App\Models\StandardClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StandardClauseController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/contracts/template/Index', [
            'clause-categories' => ClauseCategory::orderBy('order')->get(),
            'standard-clauses' => StandardClause::orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'category' => 'required|exists:clause_categories,id',
        ]);

        $nextOrder = (StandardClause::where('clause_category_id', $request->category)->max('order') ?? 0) + 1;

        StandardClause::create([
            'name' => $request->name,
            'content' => $request->content,
            'order' => $nextOrder,
            'clause_category_id' => $request->category,
        ]);

        return back()->with(['message' => 'Saved!', 'type' => 'success', 'description' => 'Clause created']);
    }

    public function update(Request $request, StandardClause $standardClause)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'category' => 'sometimes|exists:clause_categories,id',
        ]);

        $oldCategoryId = $standardClause->clause_category_id;

        DB::transaction(function () use ($request, $standardClause, $oldCategoryId) {
            $attributes = [
                'name' => $request->name,
                'content' => $request->content,
            ];

            // If the clause moved to another category, put it at the end of the new one
            $moved = $request->filled('category') && (int) $request->category !== (int) $oldCategoryId;

            if ($moved) {
                $attributes['clause_category_id'] = $request->category;
                $attributes['order'] = (StandardClause::where('clause_category_id', $request->category)->max('order') ?? 0) + 1;
            }

            $standardClause->update($attributes);

            // Close the gap it left behind in the old category
            if ($moved) {
                $this->resequence($oldCategoryId);
            }
        });

        return back()->with(['message' => 'Updated!', 'type' => 'info', 'description' => 'Clause updated']);
    }

    public function destroy(StandardClause $standardClause)
    {
        $categoryId = $standardClause->clause_category_id;

        DB::transaction(function () use ($standardClause, $categoryId) {
            $standardClause->delete();

            // Close the gap left by the deleted clause
            $this->resequence($categoryId);
        });

        return back()->with(['message' => 'Deleted!', 'type' => 'error', 'description' => 'Clause deleted']);
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', Rule::exists(ClauseCategory::class, 'id')],
            'ids' => ['required', 'array'],
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists(StandardClause::class, 'id')->where('clause_category_id', $request->input('category_id')),
            ],
        ]);

        DB::transaction(fn () => $this->resequence($data['category_id'], $data['ids']));

        return back()->with(['message' => 'Reordered!', 'type' => 'info', 'description' => 'Clause reordered']);
    }

    private function resequence(int|string $categoryId, array $orderedIds = []): void
    {
        $remaining = StandardClause::where('clause_category_id', $categoryId)
            ->whereNotIn('id', $orderedIds)
            ->orderBy('order')
            ->pluck('id')
            ->all();

        foreach (array_merge($orderedIds, $remaining) as $index => $id) {
            StandardClause::whereKey($id)->update(['order' => $index + 1]);
        }
    }
}
