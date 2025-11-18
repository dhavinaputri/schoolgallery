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
        
        if (self::isRailwayEnvironment()) {
            // Railway environment - store directly under public/images
            // so files are served as static assets without /storage
            $targetPath = self::getPublicPath('images/' . trim($directory, '/'));

            // Ensure directory exists
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0755, true);
            }

            $file->move($targetPath, $fileName);
            // Return relative path under images (e.g. submissions/uuid/file.jpg)
            return trim($directory, '/') . '/' . $fileName;
        } else {
            // Local environment - store into public/images using the public_images disk
            // Ensure directory exists and put the file
            $storedPath = $file->storeAs($directory, $fileName, 'public_images');
            // Normalize to forward slashes for URL building and DB storage consistency
            $storedPath = str_replace('\\', '/', $storedPath);
            // Return relative path (e.g., galleries/xxx.jpg); URL builder will map to /images or /storage
            return $storedPath;
        }
    }

    /**
     * Delete a file
     */
    public static function deleteFile($path)
    {
        if (empty($path)) {
            return false;
        }

        if (self::isRailwayEnvironment()) {
            // Railway environment - delete from public/images
            $fullPath = self::getPublicPath('images/' . ltrim($path, '/'));
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
        } else {
            // Local environment: attempt delete in both disks
            if (Storage::disk('public_images')->exists($path)) {
                return Storage::disk('public_images')->delete($path);
            }
            return Storage::disk('public')->delete($path);
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

        if (self::isRailwayEnvironment()) {
            // Railway: check in public/images
            return file_exists(self::getPublicPath('images/' . ltrim($path, '/')));
        } else {
            return Storage::disk('public')->exists($path);
        }
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
