<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = ProjectResource::collection(Project::with('skills')->orderBy('order')->get());
        $skills = SkillResource::collection(Skill::orderBy('name')->get());

        return Inertia::render('admin/projects/Index', compact('projects', 'skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'skills.*' => 'required',
            'url' => 'required'
        ]);

        $image = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'order' => Project::count() + 1,
            'active' => $request->active,
            'icon' => $request->icon,
            'image' => $image,
        ]);
        $project->skills()->sync(array_column($request->skills, 'id'));

        return back()->with('message', 'Project created successfully!');
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|min:3',
            'skills.*' => 'required',
        ]);

        $image = $project->image;
        if ($request->hasFile('image')) {
            if($image) {
                Storage::disk('public')->delete($project->image);
            }
            $image = $request->file('image')->store('projects', 'public');
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'image' => $image,
            'active' => $request->active,
            'icon' => $request->icon,
        ]);
        $project->skills()->sync(array_column($request->skills, 'id'));

        return back()->with('message', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        Storage::disk('public')->delete($project?->image ?? '');
        $project->delete();
        return back()->with('message', 'Project deleted');
    }

    public function sort(Request $request)
    {
        foreach ($request->projects as $project) {
            Project::where('id', $project['id'])
                ->update([
                    'order' => $project['order'],
                ]);
        }

        return back();
    }
}
