<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class MediaController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Media::query()->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $media = $query->paginate(20)->through(function (Media $media) {
            return [
                'id' => $media->id,
                'original_name' => $media->original_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'type' => $media->type,
                'url' => $media->url,
                'path' => $media->path,
                'created_at' => $media->created_at?->toDateTimeString(),
            ];
        });

        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
            'filters' => [
                'q' => $request->get('q'),
                'type' => $request->get('type'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB
        ]);

        $file = $data['file'];

        $type = $this->detectType($file->getMimeType());
        $path = $file->store('media/' . date('Y/m'), 'public');

        Media::create([
            'user_id' => Auth::id(),
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type,
        ]);

        return redirect()
            ->route('admin.media.index')
            ->with('status', 'File berhasil diunggah.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        Storage::disk($media->disk ?? 'public')->delete($media->path);
        $media->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('status', 'File berhasil dihapus.');
    }

    private function detectType(?string $mime): string
    {
        if (!$mime) {
            return 'other';
        }

        if (str_contains($mime, 'image')) {
            return 'image';
        }

        if (str_contains($mime, 'video')) {
            return 'video';
        }

        if (str_contains($mime, 'pdf') || str_contains($mime, 'msword') || str_contains($mime, 'wordprocessingml')) {
            return 'document';
        }

        return 'other';
    }
}
