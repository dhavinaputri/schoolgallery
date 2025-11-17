<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\News;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $staticUrls = [
            URL::to('/'),
            URL::to('/gallery'),
            URL::to('/news'),
            URL::to('/about'),
            URL::to('/contact'),
        ];

        $news = News::published()->latest()->get(['slug','updated_at']);
        $galleries = Gallery::published()->latest()->get(['id','updated_at']);

        return response()->view('sitemap.index', [
            'staticUrls' => $staticUrls,
            'news' => $news,
            'galleries' => $galleries,
        ])->header('Content-Type', 'application/xml');
    }
}


