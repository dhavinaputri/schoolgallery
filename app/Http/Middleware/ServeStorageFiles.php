<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServeStorageFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only handle storage requests
        if (!$request->path() || !str_starts_with($request->path(), 'storage/')) {
            return $next($request);
        }

        // Extract the file path from the request
        $filePath = str_replace('storage/', '', $request->path());
        
        // Determine the actual file location
        if ($this->isRailwayEnvironment()) {
            // On Railway, files are in the volume mount
            $storagePath = env('FILESYSTEM_ROOT', '/app/storage/app/public');
            $fullPath = $storagePath . '/' . $filePath;
        } else {
            // Locally, files are in storage/app/public
            $fullPath = storage_path('app/public/' . $filePath);
        }

        // Check if file exists
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            return response('File not found', 404);
        }

        // Serve the file
        return response()->file($fullPath);
    }

    /**
     * Check if we're running on Railway
     */
    private function isRailwayEnvironment(): bool
    {
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
