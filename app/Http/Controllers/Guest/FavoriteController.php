<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryFavorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $galleryId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $gallery = Gallery::findOrFail($galleryId);
        $userId = auth()->id();

        $existing = GalleryFavorite::where('user_id', $userId)
            ->where('gallery_id', $gallery->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            GalleryFavorite::create([
                'user_id' => $userId,
                'gallery_id' => $gallery->id,
            ]);
            $favorited = true;
        }

        $count = GalleryFavorite::where('gallery_id', $gallery->id)->count();

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'favorite_count' => $count,
            'message' => $favorited ? 'Disimpan ke favorit' : 'Dihapus dari favorit',
        ]);
    }

    public function status(Request $request, $galleryId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => true,
                'favorited' => false,
                'favorite_count' => GalleryFavorite::where('gallery_id', $galleryId)->count(),
            ]);
        }

        $favorited = GalleryFavorite::where('user_id', auth()->id())
            ->where('gallery_id', $galleryId)
            ->exists();

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'favorite_count' => GalleryFavorite::where('gallery_id', $galleryId)->count(),
        ]);
    }

    public function index(Request $request)
    {
        $favorites = GalleryFavorite::with(['gallery'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }
}
