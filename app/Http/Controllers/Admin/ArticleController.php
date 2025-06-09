<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ArticleRequest;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Articles/Index', [
            'articles' => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Articles/Create', [
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $data = $request->validated();

        // Clean HTML content dengan Purifier
        $data['content'] = Purifier::clean($data['content']);

        // Generate unique slug
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        // Simpan thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')
                                          ->store('articles', 'public');
        }

        // Create article
        $article = Article::create($data);

        // Sync tags
        if (!empty($data['tags'] ?? [])) {
            $article->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::with(['category', 'tags'])->findOrFail($id);

        return Inertia::render('Admin/Articles/Show', [
            'article' => $article
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        // Eager load tags agar bisa pre-select di form
        $article->load('tags');

        return Inertia::render('Admin/Articles/Edit', [
            'article'    => $article,
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();

        // Clean HTML content dengan Purifier
        $data['content'] = Purifier::clean($data['content']);

        // Jika judul berubah, regenerate unique slug
        if ($article->title !== $data['title']) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $article->id);
        }

        // Handle thumbnail baru
        if ($request->hasFile('thumbnail')) {
            // Hapus file lama jika ada
            if ($article->thumbnail_path) {
                Storage::disk('public')->delete($article->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')
                                          ->store('articles', 'public');
        }

        // Update artikel
        $article->update($data);

        // Sync tags (jika tidak ada input tags, akan clear pivot)
        $article->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            // Hapus thumbnail
            if ($article->thumbnail_path) {
                Storage::disk('public')->delete($article->thumbnail_path);
            }

            // Hapus relasi tags (pivot table)
            $article->tags()->detach();

            // Hapus artikel
            $article->delete();

            return back()->with('success', 'Artikel berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus artikel: ' . $e->getMessage());
        }
    }

    /**
     * Display drafts articles
     */
    public function drafts()
    {
        $drafts = Article::with(['category', 'tags'])
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Articles/Drafts', [
            'drafts' => $drafts,
        ]);
    }

    /**
     * Preview article
     */
    public function preview(Article $article)
    {
        $article->load('category', 'tags');

        return Inertia::render('Articles/Show', [
            'article' => $article
        ]);
    }

    /**
     * Upload image for article content (untuk rich text editor)
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('uploads/articles', $filename, 'public');

        return response()->json([
            'url' => Storage::url($path)
        ]);
    }
    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Article::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Bulk actions for articles
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,draft',
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id'
        ]);

        $articles = Article::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'delete':
                // Hapus thumbnail files
                $articlesToDelete = $articles->get();
                foreach ($articlesToDelete as $article) {
                    if ($article->thumbnail_path) {
                        Storage::disk('public')->delete($article->thumbnail_path);
                    }
                }

                $articles->delete();
                $message = 'Artikel terpilih berhasil dihapus.';
                break;

            case 'publish':
                $articles->update(['status' => 'published']);
                $message = 'Artikel terpilih berhasil dipublikasi.';
                break;

            case 'draft':
                $articles->update(['status' => 'draft']);
                $message = 'Artikel terpilih berhasil dijadikan draft.';
                break;
        }

        return back()->with('success', $message);
    }
}
