<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class RailwayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure filesystem for Railway environment
        // Check for Railway-specific environment variables
        if (app()->environment('production') || env('RAILWAY_ENVIRONMENT') || env('RAILWAY_PROJECT_ID')) {
            
            // Check if we're running on Railway (has the mount path)
            $railwayStoragePath = '/app/public/storage';
            if (file_exists('/app/public') || env('RAILWAY_ENVIRONMENT')) {
                // Override default filesystem disk for Railway
                config(['filesystems.default' => 'railway']);
                
                // Ensure storage directory exists
                if (!file_exists($railwayStoragePath)) {
                    mkdir($railwayStoragePath, 0755, true);
                }
                
                // Create necessary subdirectories
                $subdirs = ['galleries', 'news', 'teachers', 'avatars', 'submissions'];
                foreach ($subdirs as $subdir) {
                    $path = $railwayStoragePath . '/' . $subdir;
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                }
            }
        }
    }
}
