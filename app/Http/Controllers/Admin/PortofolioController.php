<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;

class PortofolioController extends Controller
{
        public function home()
    {
        // 1. Ambil data proyek yang dipublikasikan
        // Urutkan berdasarkan yang terbaru, atau mungkin ada kolom 'featured'
        $projects = Project::where('is_published', true)
                            ->latest()
                            ->get()
                            // Kita transform data agar sesuai dengan kebutuhan frontend
                            ->map(function ($project) {
                                return [
                                    'id' => $project->id,
                                    'title' => $project->title,
                                    'slug' => $project->slug,
                                    'description' => $project->description,
                                    // PENTING: Buat URL gambar yang bisa diakses publik
                                    'image' => $project->image ? asset('storage/' . $project->image) : null,
                                    'category' => $project->category, // Asumsi ada kolom category
                                    'technologies' => $project->technologies,
                                    'github' => $project->repo_url,
                                    'demo' => $project->project_url,
                                    'featured' => $project->featured, // Asumsi ada kolom featured
                                ];
                            });

        // 2. Ambil 3 artikel terbaru
        $blogs = Article::where('is_published', true)
                        ->latest()
                        ->take(3)
                        ->get(['id', 'title', 'slug', 'content']); // Ambil kolom yang perlu saja

        // 3. Kirim data ke komponen Vue menggunakan Inertia
        return Inertia::render('LandingPage', [
            'projects' => $projects,
            'blogs' => $blogs,
        ]);
    }
}
