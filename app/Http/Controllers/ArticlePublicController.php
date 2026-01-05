<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

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
            'projects' => $projects,
            'cvUrl' => $this->getCvUrl(),
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
        if ($article->status !== 'published') {
            abort(404);
        }

        $article->load(['category', 'tags']);

        $descriptionSource = $article->meta_description ?: ($article->excerpt ?: $article->content);
        $description = trim(preg_replace('/\s+/', ' ', strip_tags($descriptionSource)));
        $description = mb_substr($description, 0, 160);

        $metaData = [
            'title' => $article->meta_title ?: $article->title,
            'description' => $description,
            'image' => $article->thumbnail_url,
            'url' => request()->url(),
            'type' => 'article',
            'site_name' => config('app.name'),
            'published_time' => optional($article->created_at)->toISOString(),
            'modified_time' => optional($article->updated_at)->toISOString(),
            'section' => optional($article->category)->name,
            'tags' => $article->tags->pluck('name')->all(),
        ];

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'metaData' => $metaData
        ])->withViewData([
            'meta' => $metaData,
        ]);

    }

    private function getCvUrl(): ?string
    {
        $disk = Storage::disk('public');
        foreach (['cv/cv.pdf', 'cv/cv.docx', 'cv/cv.doc'] as $candidate) {
            if ($disk->exists($candidate)) {
                return $disk->url($candidate);
            }
        }

        // Fallback ke file di public root jika ada
        if (file_exists(public_path('cv.pdf'))) {
            return asset('cv.pdf');
        }

        return null;
    }
}
