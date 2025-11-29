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
        $articles = Article::with(['category', 'tags'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }

    public function show(Article $article)
    {
        $article->load(['category', 'tags']);

        $metaData = [
            'title' => $article->title,
            'description' => $article->excerpt ?: strip_tags(substr($article->content, 0, 160)),
            'image' => $article->thumbnail_path ? asset('storage/' . ltrim($article->thumbnail_path, '/')) : null,
            'url' => request()->url(),
            'type' => 'article',
            'site_name' => config('app.name')
        ];

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'metaData' => $metaData
        ]);

    }
}
