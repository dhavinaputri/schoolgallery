<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'parent_id',
        'name',
        'email',
        'content',
        'is_approved',
        'depth',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'depth' => 'integer',
        ];
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    // Relasi untuk sistem reply
    public function parent()
    {
        return $this->belongsTo(GalleryComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(GalleryComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Scope untuk komentar utama (bukan reply)
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope untuk reply
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
