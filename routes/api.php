<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\SchoolProfileController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::prefix('v1')->group(function () {
    // School Profile
    Route::get('/school-profile', [SchoolProfileController::class, 'index']);
    
    // Gallery
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::get('/galleries/{gallery}', [GalleryController::class, 'show']);
    
    // News - Public routes
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/categories', [NewsController::class, 'categories']);
    Route::get('/news/{slug}', [NewsController::class, 'show']);
    
    // Teachers - Public routes
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show']);
    
    // Protected routes (admin only)
    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        // News routes
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);
        
        // Teacher routes
        Route::post('/teachers', [TeacherController::class, 'store']);
        Route::put('/teachers/{teacher}', [TeacherController::class, 'update']);
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy']);
    });
    
    // Admin Authentication
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
