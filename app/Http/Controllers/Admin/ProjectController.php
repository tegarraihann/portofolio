<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Project;
use Inertia\Inertia;
use App\Http\Requests\StoreProjectRequest;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::All();
        return Inertia::render('Admin/Project/Index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Project/Create', [
            'media' => Media::where('type', 'image')
                ->latest()
                ->get(['id', 'original_name', 'path', 'disk']),
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        // Buat project dengan data yang sudah termasuk thumbnail
        $project = Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Project/Edit', [
            'project' => $project,
            'media' => Media::where('type', 'image')
                ->latest()
                ->get(['id', 'original_name', 'path', 'disk']),
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('Admin/Project/Show', compact('project'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Project deleted.');
    }

    public function landing()
    {
        $projects = Project::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('LandingPage', [
            'projects' => $projects
        ]);
    }
}
