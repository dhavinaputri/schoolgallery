<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'parent_id',
        'name',
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

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    // Relasi untuk sistem reply
    public function parent()
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->orderBy('created_at', 'asc');
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


