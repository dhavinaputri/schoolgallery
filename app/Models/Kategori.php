<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function berita()
    {
        return $this->hasMany(News::class, 'kategori_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama')) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }
}
