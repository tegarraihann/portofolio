<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CvController extends Controller
{
    public function index(): Response
    {
        $cvUrl = $this->getCvUrl();

        return Inertia::render('Admin/Cv/Index', [
            'cvUrl' => $cvUrl,
            'hasCv' => (bool) $cvUrl,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // max 5MB
        ]);

        $file = $validated['cv'];
        $disk = Storage::disk('public');

        // Hapus file lama agar tidak menumpuk
        $disk->delete(['cv/cv.pdf', 'cv/cv.doc', 'cv/cv.docx']);

        $extension = $file->getClientOriginalExtension();
        $filename = "cv/cv.{$extension}";

        $file->storeAs('cv', "cv.{$extension}", 'public');

        return redirect()
            ->route('admin.cv.index')
            ->with('status', 'CV berhasil diperbarui.');
    }

    private function getCvUrl(): ?string
    {
        $disk = Storage::disk('public');
        foreach (['cv/cv.pdf', 'cv/cv.docx', 'cv/cv.doc'] as $candidate) {
            if ($disk->exists($candidate)) {
                return $disk->url($candidate);
            }
        }

        if (file_exists(public_path('cv.pdf'))) {
            return asset('cv.pdf');
        }

        return null;
    }
}
