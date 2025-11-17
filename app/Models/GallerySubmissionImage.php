<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GallerySubmissionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'path',
        'original_name',
        'size',
    ];

    public function submission()
    {
        return $this->belongsTo(GallerySubmission::class, 'submission_id');
    }
}
