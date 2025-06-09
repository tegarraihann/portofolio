<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Project;
use Inertia\Inertia;

class ArticlePublicController extends Controller
{
    public function landing()
    {
        // Ambil 3 artikel terbaru yang diterbitkan
        $articles = Article::with(['category', 'tags'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Ambil projects untuk landing page
        $projects = Project::orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Kirim data ke frontend
        return Inertia::render('LandingPage', [
            'articles' => $articles,
            'projects' => $projects
        ]);
    }

    public function latestArticles()
    {
        // Method terpisah jika diperlukan untuk API
        $articles = Article::with(['category', 'tags'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'articles' => $articles
        ]);
    }

    public function index()
    {
        $articles = Article::with(['category','tags'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }

    public function show(string $slug)
    {
        $article = Article::with(['category','tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return Inertia::render('Articles/Show', [
            'article' => $article
        ]);
    }
}
