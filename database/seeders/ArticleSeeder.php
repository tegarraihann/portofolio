<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 12; $i++) {
            Article::create([
                'title' => 'Contoh Artikel ke-' . $i,
                'slug' => Str::slug('Contoh Artikel ke-' . $i),
                'excerpt' => 'Ini adalah cuplikan dari artikel contoh ke-' . $i,
                'content' => 'Isi lengkap dari artikel contoh ke-' . $i . '. Ini adalah konten dummy.',
                'thumbnail' => null,
                'category_id' => $categories[array_rand($categories)],
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}
