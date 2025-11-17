<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'target_type',
        'target_id',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin that performed the action.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the target model (polymorphic).
     */
    public function target()
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk filter berdasarkan admin.
     */
    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope untuk filter berdasarkan action.
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk aktivitas terbaru.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Static method untuk log aktivitas.
     */
    public static function log($action, $description, $adminId = null, $target = null, $metadata = [])
    {
        $adminId = $adminId ?? auth('admin')->id();
        
        return static::create([
            'admin_id' => $adminId,
            'action' => $action,
            'description' => $description,
            'target_type' => $target ? get_class($target) : null,
            'target_id' => $target ? $target->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get action icon based on action type.
     */
    public function getActionIconAttribute()
    {
        $icons = [
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
            'create' => 'plus',
            'update' => 'edit',
            'delete' => 'trash',
            'reset_password' => 'key',
            'view' => 'eye',
            'download' => 'download',
            'upload' => 'upload',
        ];

        return $icons[$this->action] ?? 'circle';
    }

    /**
     * Get action color based on action type.
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'login' => 'green',
            'logout' => 'gray',
            'create' => 'blue',
            'update' => 'yellow',
            'delete' => 'red',
            'reset_password' => 'purple',
            'view' => 'blue',
            'download' => 'indigo',
            'upload' => 'teal',
        ];

        return $colors[$this->action] ?? 'gray';
    }
}
