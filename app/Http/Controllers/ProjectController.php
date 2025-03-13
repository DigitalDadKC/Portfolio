<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = ProjectResource::collection(Project::orderBy('project_order')->get());
        return Inertia::render('Projects/Index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skills = Skill::all();
        return Inertia::render('Projects/Create', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image'],
            'name' => ['required', 'min:3'],
            'skills.*' => ['required'],
            'project_url' => ['required']
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('projects');
            $project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $image,
                'project_url' => $request->project_url
            ]);
            $project->skills()->sync($request->skills, 'id');

            return Redirect::route('projects.index')->with('message', 'Project created successfully!');
        }

        return Redirect::back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $skills = Skill::all();
        $project->skills;
        return Inertia::render('Projects/Edit', compact('project', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $image = $project->image;
        $request->validate([
            'name' => ['required', 'min:3'],
            'skills.*' => ['required'],
        ]);

        if ($request->hasFile('image')) {
            Storage::delete($project->image);
            $image = $request->file('image')->store('projects');
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'image' => $image
        ]);
        $project->skills()->sync($request->skills, 'id');

        return Redirect::route('projects.index')->with('message', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Storage::delete($project->image);
        $project->delete();
        return Redirect::back()->with('message', 'Project deleted');
    }

    public function sort(Request $request)
    {
        $current = Project::get();
        $current->map(function ($project) use ($request) {
            $order = $request->projects[array_search($project->id, array_column($request->projects, 'id'))]['project_order'];
            $project->update(['project_order' => $order]);
        });
    }
}
