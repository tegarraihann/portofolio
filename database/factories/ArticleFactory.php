<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

class ArticleFactory extends Factory
{
    protected $model = \App\Models\Article::class;

    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'category_id'    => Category::factory(),
            'title'          => $title,
            'slug'           => Str::slug($title),
            'excerpt'        => $this->faker->paragraph(),
            'content'        => $this->faker->paragraphs(3, true),
            'status'         => $this->faker->randomElement(['draft','published']),
            'thumbnail_path' => null,
        ];
    }
}
