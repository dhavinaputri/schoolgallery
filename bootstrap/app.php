<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,
            'track.visits' => \App\Http\Middleware\TrackVisits::class,
            'admin.guest' => \App\Http\Middleware\AdminGuest::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            // Add any routes that should be excluded from CSRF verification
        ]);

        // Serve storage files directly (for Railway compatibility)
        $middleware->prepend([
            \App\Http\Middleware\ServeStorageFiles::class,
        ]);

        // Global security headers
        $middleware->append([
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
