<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'meta_image')) {
                $table->string('meta_image')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('articles', 'noindex')) {
                $table->boolean('noindex')->default(false)->after('meta_image');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('projects', 'meta_description')) {
                $table->string('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('projects', 'meta_image')) {
                $table->string('meta_image')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('projects', 'noindex')) {
                $table->boolean('noindex')->default(false)->after('meta_image');
            }
        });

        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('default_title')->nullable();
            $table->string('default_description')->nullable();
            $table->string('default_og_image')->nullable();
            $table->string('default_twitter_image')->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('sitemap_enabled')->default(true);
            $table->timestamps();
        });

        // Seed one default row to avoid empty state
        DB::table('seo_settings')->insert([
            'default_title' => config('app.name', 'Portfolio'),
            'default_description' => null,
            'default_og_image' => null,
            'default_twitter_image' => null,
            'robots_index' => true,
            'sitemap_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'meta_image')) {
                $table->dropColumn('meta_image');
            }
            if (Schema::hasColumn('articles', 'noindex')) {
                $table->dropColumn('noindex');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            $drops = ['meta_title', 'meta_description', 'meta_image', 'noindex'];
            foreach ($drops as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('seo_settings');
    }
};
