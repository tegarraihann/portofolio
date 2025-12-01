<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\ArticlePublicController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryProjectController;
use App\Http\Controllers\Admin\CvController;
use App\Http\Middleware\TrackArticleView;

Route::middleware(['auth','verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {
        // PENTING: Route drafts harus didefinisikan SEBELUM resource route
        Route::get('articles/drafts', [ArticleController::class, 'drafts'])
            ->name('articles.drafts');

        // Route untuk upload image - DIPERBAIKI: hapus prefix admin berlebih
        Route::post('articles/upload-image', [ArticleController::class, 'uploadImage'])
            ->name('articles.uploadImage');

        // Route untuk preview
        Route::get('articles/preview/{article}', [ArticleController::class, 'preview'])
            ->name('articles.preview');

        // Route untuk bulk action - DIPERBAIKI: hapus prefix admin berlebih
        Route::post('articles/bulk-action', [ArticleController::class, 'bulkAction'])
            ->name('articles.bulk-action');

        // Resource route
        Route::resource('articles', ArticleController::class)
            ->except(['show']);

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)
            ->except(['show']);

        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)
            ->except(['show']);

        // Projects routes
        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);

        // CV download management
        Route::get('cv', [CvController::class, 'index'])->name('cv.index');
        Route::post('cv', [CvController::class, 'store'])->name('cv.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Landing Page Route - Gabungkan projects dan articles
Route::get('/', [ArticlePublicController::class, 'landing'])->name('landing');

Route::resource('categories', CategoryController::class);

Route::get('/api/projects', [ProjectController::class, 'apiIndex'])->name('api.projects');

Route::get('/articles', [ArticlePublicController::class, 'index'])
     ->name('articles.index');

Route::get('/articles/{article:slug}', [ArticlePublicController::class, 'show'])
     ->middleware(TrackArticleView::class)
     ->name('articles.show');

Route::get('/test-purifier', function () {
    $dirty_html = '<script>alert("XSS Attack!")</script><p>This is clean content</p><img src="x" onerror="alert(\'hack\')">';

    try {
        $clean_html = clean($dirty_html);

        return response()->json([
            'status' => 'success',
            'original' => $dirty_html,
            'cleaned' => $clean_html
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});

require __DIR__.'/auth.php';

// Fallback untuk semua route yang tidak terdaftar -> 404
Route::fallback(function () {
    abort(404);
});
