<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SeoController extends Controller
{
    public function index(): Response
    {
        $setting = SeoSetting::first();

        return Inertia::render('Admin/Seo/Index', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'default_title' => ['nullable', 'string', 'max:255'],
            'default_description' => ['nullable', 'string', 'max:255'],
            'robots_index' => ['nullable', 'boolean'],
            'sitemap_enabled' => ['nullable', 'boolean'],
            'default_og_image' => ['nullable', 'image', 'max:3072'],
            'default_twitter_image' => ['nullable', 'image', 'max:3072'],
        ]);

        /** @var SeoSetting $setting */
        $setting = SeoSetting::first() ?: SeoSetting::create();

        // Upload images jika ada
        foreach (['default_og_image', 'default_twitter_image'] as $imageField) {
            if ($request->hasFile($imageField)) {
                if ($setting->{$imageField}) {
                    Storage::disk('public')->delete($setting->{$imageField});
                }
                $data[$imageField] = $request->file($imageField)->store('seo', 'public');
            } else {
                unset($data[$imageField]); // jangan timpa jika tidak ada upload
            }
        }

        $setting->fill([
            'default_title' => $data['default_title'] ?? null,
            'default_description' => $data['default_description'] ?? null,
            'robots_index' => $request->boolean('robots_index'),
            'sitemap_enabled' => $request->boolean('sitemap_enabled'),
        ]);

        if (isset($data['default_og_image'])) {
            $setting->default_og_image = $data['default_og_image'];
        }
        if (isset($data['default_twitter_image'])) {
            $setting->default_twitter_image = $data['default_twitter_image'];
        }

        $setting->save();

        return redirect()
            ->route('admin.seo.index')
            ->with('status', 'Pengaturan SEO berhasil disimpan.');
    }
}
