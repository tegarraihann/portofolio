<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_title',
        'default_description',
        'default_og_image',
        'default_twitter_image',
        'robots_index',
        'sitemap_enabled',
    ];

    protected $casts = [
        'robots_index' => 'boolean',
        'sitemap_enabled' => 'boolean',
    ];
}
