<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->paginate(10);
        return Inertia::render('Admin/Tags/Index', compact('tags'));
    }

    public function create()
    {
        return Inertia::render('Admin/Tags/Create');
    }

    public function store(TagRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Tag::create($data);

        return redirect()->route('admin.tags.index')
                         ->with('success','Tag berhasil dibuat.');
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Edit', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return redirect()->route('admin.tags.index')
                         ->with('success','Tag berhasil diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success','Tag berhasil dihapus.');
    }
}
