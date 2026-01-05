<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'thumbnail_path',
        'thumbnail_media_id',
        'meta_title',
        'meta_description',
        'meta_image',
        'noindex',
    ];

    protected $appends = ['thumbnail_url'];

    protected $casts = [
        'noindex' => 'boolean',
    ];

    public function getThumbnailUrlAttribute()
    {
        $mediaUrl = optional($this->thumbnailMedia)->url;
        if ($mediaUrl) {
            return url($mediaUrl);
        }

        return $this->thumbnail_path ? asset('storage/' . ltrim($this->thumbnail_path, '/')) : null;
    }

    public function thumbnailMedia()
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
