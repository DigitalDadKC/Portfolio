<?php

namespace App\Http\Controllers\Admin\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Clause;
use App\Models\ClauseCategory;
use Illuminate\Http\Request;
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

        $nextOrder = (Clause::max('order') ?? 0) + 1;

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
        ]);
        
        $clause = Clause::findOrFail($id);

        $clause->update([
            'name' => $request->name,
            'content' => $request->content,
            'clause_category_id' => $request->category,
        ]);

        $this->sort(request()->merge(['order' => Clause::orderBy('order')->pluck('id')->toArray()]));

        return back()->with(['message' => 'Updated!', 'type' => 'info', 'description' => 'Clause updated']);
    }

    public function destroy(string $id)
    {
        $clause = Clause::findOrFail($id);
        $clause->delete();

        $this->sort(request()->merge(['order' => Clause::orderBy('order')->pluck('id')->toArray()]));

        return back()->with(['message' => 'Deleted!', 'type' => 'error', 'description' => 'Clause deleted']);
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Clause::where('id', $id)->update(['order' => $index + 1]);
        }

        return back()->with(['message' => 'Reordered!', 'type' => 'info', 'description' => 'Clause reordered']);
    }
}
