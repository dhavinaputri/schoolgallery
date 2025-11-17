<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'user_id',
        'visitor_fingerprint',
        'ip_address',
        'user_agent',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
