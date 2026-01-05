<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'tech_stack',
        'thumbnail',
        'thumbnail_media_id',
        'live_demo_url',
        'github_url',
        'is_featured',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_image',
        'noindex',
    ];

    protected $appends = ['thumbnail_url'];

    protected $casts = [
        'tech_stack' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'noindex' => 'boolean',
    ];

    public function getThumbnailUrlAttribute()
    {
        $mediaUrl = optional($this->thumbnailMedia)->url;
        if ($mediaUrl) {
            return url($mediaUrl);
        }

        return $this->thumbnail ? asset('storage/' . ltrim($this->thumbnail, '/')) : null;
    }

    public function thumbnailMedia()
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }
}
