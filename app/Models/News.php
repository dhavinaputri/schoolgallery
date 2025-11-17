<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'author',
        'is_published',
        'published_at',
        'admin_id',
        'news_category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the category that owns the news.
     */
    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    /**
     * Get the category that owns the news (alias for backward compatibility).
     */
    public function kategori()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    /**
     * Get the admin that owns the news.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class)->where('is_approved', true)->mainComments()->latest();
    }

    public function allComments()
    {
        return $this->hasMany(NewsComment::class)->where('is_approved', true)->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function booted()
    {
        static::saving(function ($news) {
            if (!$news->slug) {
                $news->slug = Str::slug($news->title) . '-' . time();
            }
            if ($news->isDirty('is_published') && $news->is_published && !$news->published_at) {
                $news->published_at = now();
            }
        });
    }
}
