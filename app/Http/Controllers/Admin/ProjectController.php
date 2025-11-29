<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
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
        return Inertia::render('Admin/Project/Create');
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        // Handle file upload SEBELUM membuat project
        if ($request->hasFile('thumbnail')) {
            \Log::info('File uploaded: ' . $request->file('thumbnail')->getClientOriginalName());
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        // Buat project dengan data yang sudah termasuk thumbnail
        $project = Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Project/Edit', compact('project'));
    }

    public function show(Project $project)
    {
        return Inertia::render('Admin/Project/Show', compact('project'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        // Handle file upload for thumbnail
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        } else {
            // Jangan timpa thumbnail lama jika tidak ada upload baru
            unset($data['thumbnail']);
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
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
