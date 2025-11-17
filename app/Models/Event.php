<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug',
        'description',
        'location',
        'image',
        'start_at',
        'end_at',
        'is_published',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('end_at', '>=', now())
              ->orWhereNull('end_at')
              ->orWhere('start_at', '>=', now());
        });
    }

    /**
     * Delete events that have passed.
     * Expired = (end_at < now) OR (end_at IS NULL AND start_at < now)
     */
    public static function deleteExpired(): int
    {
        return static::where(function($q){
                $q->where('end_at', '<', now())
                  ->orWhere(function($qq){
                      $qq->whereNull('end_at')->where('start_at', '<', now());
                  });
            })->delete();
    }
}


