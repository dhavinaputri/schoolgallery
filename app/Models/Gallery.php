<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'is_published',
        'admin_id',
        'kategori_id',
        'submission_id',
        'submission_image_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function submission()
    {
        return $this->belongsTo(GallerySubmission::class, 'submission_id');
    }

    public function submissionImage()
    {
        return $this->belongsTo(GallerySubmissionImage::class, 'submission_image_id');
    }

    public function likes()
    {
        return $this->hasMany(GalleryLike::class);
    }

    public function favorites()
    {
        return $this->hasMany(GalleryFavorite::class);
    }

    public function comments()
    {
        return $this->hasMany(GalleryComment::class)->mainComments()->latest();
    }

    public function allComments()
    {
        return $this->hasMany(GalleryComment::class)->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('kategori_id', $categoryId);
    }
}
