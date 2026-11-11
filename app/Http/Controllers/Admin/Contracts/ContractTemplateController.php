<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Clause;
use App\Models\ClauseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ContractTemplateController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/contracts/template/Index', [
            'categories' => ClauseCategory::orderBy('order')->get(),
            'clauses' => Clause::orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'category' => 'required|exists:clause_categories,id',
        ]);

        // Order is scoped to the category: new clauses go to the end of THEIR category
        $nextOrder = (Clause::where('clause_category_id', $request->category)->max('order') ?? 0) + 1;

        Clause::create([
            'name' => $request->name,
            'content' => $request->content,
            'order' => $nextOrder,
            'clause_category_id' => $request->category,
        ]);

        return back()->with(['message' => 'Saved!', 'type' => 'success', 'description' => 'Clause created']);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
            'category' => 'sometimes|exists:clause_categories,id',
        ]);

        $clause = Clause::findOrFail($id);
        $oldCategoryId = $clause->clause_category_id;

        DB::transaction(function () use ($request, $clause, $oldCategoryId) {
            $attributes = [
                'name' => $request->name,
                'content' => $request->content,
            ];

            // If the clause moved to another category, put it at the end of the new one
            $moved = $request->filled('category') && (int) $request->category !== (int) $oldCategoryId;

            if ($moved) {
                $attributes['clause_category_id'] = $request->category;
                $attributes['order'] = (Clause::where('clause_category_id', $request->category)->max('order') ?? 0) + 1;
            }

            $clause->update($attributes);

            // Close the gap it left behind in the old category
            if ($moved) {
                $this->resequence($oldCategoryId);
            }
        });

        return back()->with(['message' => 'Updated!', 'type' => 'info', 'description' => 'Clause updated']);
    }

    public function destroy(string $id)
    {
        $clause = Clause::findOrFail($id);
        $categoryId = $clause->clause_category_id;

        DB::transaction(function () use ($clause, $categoryId) {
            $clause->delete();

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
            // Every ID must be a clause that actually belongs to this category
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists(Clause::class, 'id')->where('clause_category_id', $request->input('category_id')),
            ],
        ]);

        DB::transaction(fn () => $this->resequence($data['category_id'], $data['ids']));

        return back()->with(['message' => 'Reordered!', 'type' => 'info', 'description' => 'Clause reordered']);
    }

    /**
     * Renumber a category's clauses as 1..n.
     *
     * $orderedIds go first, in the order given. Any clauses in the category that
     * weren't in the list (e.g. added by another admin since the page loaded) are
     * kept after them in their existing order, so numbers never collide.
     * With no $orderedIds this simply closes gaps (used by update/destroy).
     */
    private function resequence(int|string $categoryId, array $orderedIds = []): void
    {
        $remaining = Clause::where('clause_category_id', $categoryId)
            ->whereNotIn('id', $orderedIds)
            ->orderBy('order')
            ->pluck('id')
            ->all();

        foreach (array_merge($orderedIds, $remaining) as $index => $id) {
            Clause::whereKey($id)->update(['order' => $index + 1]);
        }
    }
}