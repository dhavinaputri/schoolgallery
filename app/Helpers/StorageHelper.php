<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Get the correct storage path based on environment
     */
    public static function getStoragePath($path = '')
    {
        // NOTE: This helper is now mainly used for legacy storage paths.
        // For new uploads we prefer public/images via storeFile().
        // Check if we're on Railway
        if (self::isRailwayEnvironment()) {
            $basePath = env('FILESYSTEM_ROOT', '/app/storage/app/public');
            return $path ? $basePath . '/' . ltrim($path, '/') : $basePath;
        }

        // Local environment - use public_path
        return public_path('storage/' . ltrim($path, '/'));
    }

    /**
     * Get the correct public path for images
     */
    public static function getPublicPath($path = '')
    {
        // Check if we're on Railway
        if (self::isRailwayEnvironment()) {
            $basePath = '/app/public';
            return $path ? $basePath . '/' . ltrim($path, '/') : $basePath;
        }
        
        // Local environment - use public_path
        return public_path(ltrim($path, '/'));
    }

    /**
     * Get the URL for accessing stored files
     */
    public static function getStorageUrl($path)
    {
        // Normalize path separators and trim leading slashes
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        // Strip legacy 'public/' prefix if present
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        
        // Get APP_URL from config or environment
        $appUrl = function_exists('config') ? config('app.url') : getenv('APP_URL');
        if (!$appUrl) {
            $appUrl = 'http://localhost';
        }
        
        // If path already targets public images, serve directly
        if (str_starts_with($path, 'images/')) {
            return $appUrl . '/' . $path;
        }

        // Prefer file inside public/images if it exists
        try {
            if (\Illuminate\Support\Facades\Storage::disk('public_images')->exists($path)) {
                return $appUrl . '/images/' . $path;
            }
        } catch (\Throwable $e) {
            // ignore and fall back
        }

        // Fallback to classic storage path
        return $appUrl . '/storage/' . $path;
    }

    /**
     * Store a file and return the relative path
     */
    public static function storeFile($file, $directory)
    {
        $fileName = uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Unified behavior: always store via Laravel public disk (storage/app/public)
        // This works both locally and on Railway when FILESYSTEM_ROOT/volume is configured.
        $storedPath = $file->storeAs($directory, $fileName, 'public');
        $storedPath = str_replace('\\', '/', $storedPath);
        return $storedPath;
    }

    /**
     * Delete a file
     */
    public static function deleteFile($path)
    {
        if (empty($path)) {
            return false;
        }

        // Always delete from public disk; keep public_images fallback for legacy local files
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        if (Storage::disk('public_images')->exists($path)) {
            return Storage::disk('public_images')->delete($path);
        }
        
        return false;
    }

    /**
     * Check if file exists
     */
    public static function fileExists($path)
    {
        if (empty($path)) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

    /**
     * Check if we're running on Railway
     */
    private static function isRailwayEnvironment()
    {
        // Check if Laravel app is available
        if (function_exists('app') && app()->bound('env')) {
            $isProduction = app()->environment('production');
        } else {
            $isProduction = (getenv('APP_ENV') === 'production');
        }
        
        return $isProduction || 
               getenv('RAILWAY_ENVIRONMENT') || 
               getenv('RAILWAY_PROJECT_ID') ||
               file_exists('/app/public');
    }
}
