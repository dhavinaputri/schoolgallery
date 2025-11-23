<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryLike;
use App\Models\GalleryComment;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Event;
use App\Models\NewsComment;
use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index()
    {
        $schoolProfile = SchoolProfile::getProfile();
        
        // Eager load relationships to prevent N+1 queries
        $latestNews = News::with(['newsCategory', 'admin'])
            ->published()
            ->latest()
            ->take(6)
            ->get();
        
        $featuredGalleries = Gallery::with(['kategori', 'admin'])
            ->withCount(['likes', 'comments', 'favorites'])
            ->published()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('schoolProfile', 'latestNews', 'featuredGalleries'));
    }

    public function gallery(Request $request)
    {
        $query = Gallery::with(['kategori', 'admin'])
            ->withCount([
                'likes', 
                'comments' => function($q){ $q->where('is_approved', true); },
                'favorites'
            ])
            ->published()
            ->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $galleries = $query->paginate(20);
        
        // Tambahkan informasi apakah user sudah like setiap galeri
        if (auth()->check()) {
            $galleries->getCollection()->transform(function ($gallery) {
                $gallery->user_has_liked = $gallery->likes()->where('user_id', auth()->id())->exists();
                $gallery->user_has_favorited = $gallery->favorites()->where('user_id', auth()->id())->exists();
                return $gallery;
            });
        }
        
        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery', compact('galleries', 'schoolProfile'));
    }

    public function galleryByCategory($slug)
    {
        // Cari kategori berdasarkan slug
        $kategori = \App\Models\Kategori::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        // Jika kategori tidak ditemukan, redirect ke halaman galeri
        if (!$kategori) {
            return redirect()->route('gallery');
        }

        // Ambil galeri yang terkait dengan kategori tersebut - with eager loading
        $galleries = Gallery::with(['kategori', 'admin'])
            ->where('kategori_id', $kategori->id)
            ->published()
            ->withCount(['likes', 'comments' => function($q){ $q->where('is_approved', true); }, 'favorites'])
            ->latest()
            ->paginate(20);

        // Tambahkan informasi apakah user sudah like setiap galeri
        if (auth()->check()) {
            $galleries->getCollection()->transform(function ($gallery) {
                $gallery->user_has_liked = $gallery->likes()->where('user_id', auth()->id())->exists();
                $gallery->user_has_favorited = $gallery->favorites()->where('user_id', auth()->id())->exists();
                return $gallery;
            });
        }

        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery', [
            'galleries' => $galleries,
            'schoolProfile' => $schoolProfile,
            'title' => $kategori->nama,
            'activeCategory' => $slug
        ]);
    }

    public function galleryDetail($id)
    {
        $gallery = Gallery::with([
                'kategori',
                'admin',
                'likes',
                'favorites',
                'comments' => function($q){ 
                    $q->where('is_approved', true)->mainComments()->with(['replies']); 
                }
            ])
            ->withCount(['likes', 'comments', 'favorites'])
            ->published()
            ->findOrFail($id);
        
        $relatedGalleries = Gallery::with(['kategori'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->whereNotNull('image')
            ->where('image', '<>', '')
            ->where('id', '!=', $gallery->id)
            ->latest()
            ->take(4)
            ->get();
        $schoolProfile = SchoolProfile::getProfile();

        // Increment per-gallery view count once per session (use 'views' column)
        $sessionKey = 'viewed_gallery_' . $gallery->id;
        if (!session()->has($sessionKey)) {
            $gallery->increment('views');
            session([$sessionKey => true]);
        }

        // Log page view once per day per session for period reporting
        $pvKey = 'pv_gallery_' . $gallery->id . '_' . now()->format('Ymd');
        if (!session()->has($pvKey)) {
            \DB::table('page_views')->insert([
                'content_type' => 'gallery',
                'content_id' => $gallery->id,
                'session_id' => session()->getId(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'occurred_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            session([$pvKey => true]);
        }

        return view('gallery-detail', compact('gallery', 'relatedGalleries', 'schoolProfile'));
    }

    public function likeGallery(Request $request, $id)
    {
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function unlikeGallery(Request $request, $id)
    {
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function commentGallery(Request $request, $id)
    {
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function news(Request $request)
    {
        $query = News::with(['newsCategory', 'admin'])
            ->withCount('comments')
            ->published()
            ->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $category = NewsCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            }
        }

        $news = $query->paginate(10);
        $schoolProfile = SchoolProfile::getProfile();
        $categories = NewsCategory::active()->ordered()->get();

        return view('news', compact('news', 'schoolProfile', 'categories'));
    }

    public function newsDetail($slug)
    {
        $news = News::with(['newsCategory', 'admin', 'comments' => function($q){
                $q->where('is_approved', true)->latest();
            }])
            ->withCount('comments')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $relatedNews = News::with(['newsCategory'])
            ->published()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();
        $schoolProfile = SchoolProfile::getProfile();

        // Increment per-news view count once per session (use 'views' column)
        $sessionKey = 'viewed_news_' . $news->id;
        if (!session()->has($sessionKey)) {
            $news->increment('views');
            session([$sessionKey => true]);
        }

        // Log page view once per day per session for period reporting
        $pvKey = 'pv_news_' . $news->id . '_' . now()->format('Ymd');
        if (!session()->has($pvKey)) {
            \DB::table('page_views')->insert([
                'content_type' => 'news',
                'content_id' => $news->id,
                'session_id' => session()->getId(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'occurred_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            session([$pvKey => true]);
        }

        return view('news-detail', compact('news', 'relatedNews', 'schoolProfile'));
    }

    public function commentNews(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'content' => 'required|string|max:1000',
        ]);

        NewsComment::create([
            'news_id' => $news->id,
            'name' => $validated['name'] ?? 'Pengunjung',
            'content' => $validated['content'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'Komentar terkirim.');
    }

    public function about()
    {
        $schoolProfile = SchoolProfile::getProfile();
        $teachers = Teacher::active()->ordered()->get();
        
        // Ambil galeri fasilitas dari kategori "Fasilitas Sekolah"
        $fasilitasKategori = \App\Models\Kategori::where('slug', 'fasilitas-sekolah')->first();
        $facilities = collect();
        
        if ($fasilitasKategori) {
            $facilities = Gallery::where('kategori_id', $fasilitasKategori->id)
                ->published()
                ->latest()
                ->take(4)
                ->get();
        }
        
        return view('about', compact('schoolProfile', 'teachers', 'facilities'));
    }

    public function teachers(Request $request)
    {
        $schoolProfile = SchoolProfile::getProfile();
        $query = Teacher::active()->ordered();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $teachers = $query->get();
        return view('teachers', compact('schoolProfile', 'teachers'));
    }

    public function contact()
    {
        $schoolProfile = SchoolProfile::getProfile();
        return view('contact', compact('schoolProfile'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can add logic to send email or save to database
        // For now, just return success message
        
        return back()->with('success', 'Pesan Anda telah terkirim. Terima kasih telah menghubungi kami!');
    }

    /**
     * Download a gallery image
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Check if the image path exists
        if (empty($gallery->image)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        // Check if file exists using StorageHelper
        if (!StorageHelper::fileExists($gallery->image)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        // Get the correct file path based on environment
        $filePath = StorageHelper::getStoragePath($gallery->image);
        
        // Generate a safe filename for download
        $fileName = 'galeri-' . $gallery->id . '-' . Str::slug($gallery->title) . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Return the file as a download response
        return response()->download($filePath, $fileName, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => filesize($filePath),
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}