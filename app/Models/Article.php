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
    ];

    protected $appends = ['thumbnail_url'];
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . ltrim($this->thumbnail_path, '/')) : null;
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
