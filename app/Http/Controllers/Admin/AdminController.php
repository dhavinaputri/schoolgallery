<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\News;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): Response
    {
        $stats = [
            'newsCount' => News::count(),
            'galleryCount' => Gallery::count(),
            'publishedNews' => News::where('is_published', true)->count(),
            'publishedGalleries' => Gallery::where('is_published', true)->count(),
        ];

        $recentNews = News::latest()
            ->take(5)
            ->get()
            ->map(fn($news) => [
                'id' => $news->id,
                'title' => $news->title,
                'is_published' => $news->is_published,
                'created_at' => $news->created_at->toDateTimeString(),
                'updated_at' => $news->updated_at->toDateTimeString(),
            ]);

        $recentGalleries = Gallery::latest()
            ->take(5)
            ->get()
            ->map(fn($gallery) => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'is_published' => $gallery->is_published,
                'created_at' => $gallery->created_at->toDateTimeString(),
                'updated_at' => $gallery->updated_at->toDateTimeString(),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentNews' => $recentNews,
            'recentGalleries' => $recentGalleries,
        ]);
    }
}
