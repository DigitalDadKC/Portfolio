<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Resources\SkillResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class SkillController extends Controller
{
    public function index()
    {
        $skills = SkillResource::collection(Skill::all());
        return Inertia::render('admin/skills/Index', compact('skills'));
    }

    public function create()
    {
        return Inertia::render('admin/skills/Skill', [
            'new' => true,
        ]);
    }

    public function store(Request $request)
    {
        $image = $request->image;
        $request->validate([
            'name' => 'required|min:3'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('skills');
        }

        Skill::create([
            'name' => $request->name,
            'image' => $image
        ]);

        return back()->with('message', 'Skill created successfully!');
    }

    public function update(Request $request, Skill $skill)
    {
        $image = $skill->image;
        $request->validate([
            'name' => 'required|min:3'
        ]);

        if ($request->hasFile('image')) {
            if($skill->image) {
                Storage::delete($skill?->image);
            }
            $image = $request->file('image')->store('skills');
        }

        $skill->update([
            'name' => $request->name,
            'image' => $image
        ]);

        return back()->with('message', 'Skill updated successfully!');
    }

    public function destroy(Skill $skill)
    {
        Storage::delete($skill->image);
        $skill->delete();

        return back()->with('message', 'Skill deleted');
    }
}
