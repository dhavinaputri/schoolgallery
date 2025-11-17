<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Get all galleries with pagination
    public function index(Request $request)
    {
        $query = Gallery::with(['admin', 'kategori'])->latest();

        // Filter by search query
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('kategori_id', $request->category_id);
        }

        // Filter by published status
        if ($request->has('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        } else {
            $query->published();
        }

        $perPage = $request->get('per_page', 12);
        $galleries = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }

    // Get single gallery by ID
    public function show($id)
    {
        $gallery = Gallery::with(['admin', 'kategori'])->find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $gallery,
        ]);
    }

    // Create new gallery (admin only)
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        // Handle image upload to public/galleries
        if ($request->hasFile('image')) {
            $uploaded = $request->file('image');
            $targetPath = 'galleries';
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path($targetPath));
            $fileName = uniqid().'_'.str_replace(' ', '_', $uploaded->getClientOriginalName());
            $uploaded->move(public_path($targetPath), $fileName);
            $validated['image'] = $targetPath.'/'.$fileName;
        }

        // Set admin ID
        $validated['admin_id'] = auth('sanctum')->id() ?? 1; // Fallback to admin ID 1 if not authenticated

        // Create gallery
        $gallery = Gallery::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gallery created successfully',
            'data' => $gallery,
        ], 201);
    }

    // Update gallery (admin only)
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found',
            ], 404);
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'kategori_id' => 'sometimes|exists:kategoris,id',
        ]);

        // Handle image update in public/galleries
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($gallery->image) {
                $old = public_path($gallery->image);
                if (file_exists($old)) @unlink($old);
            }
            $uploaded = $request->file('image');
            $targetPath = 'galleries';
            \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path($targetPath));
            $fileName = uniqid().'_'.str_replace(' ', '_', $uploaded->getClientOriginalName());
            $uploaded->move(public_path($targetPath), $fileName);
            $validated['image'] = $targetPath.'/'.$fileName;
        }

        $gallery->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gallery updated successfully',
            'data' => $gallery,
        ]);
    }

    // Delete gallery (admin only)
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found',
            ], 404);
        }

        // Delete image if exists in public
        if ($gallery->image) {
            $old = public_path($gallery->image);
            if (file_exists($old)) @unlink($old);
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery deleted successfully',
        ]);
    }

    // Get all categories
    public function categories()
    {
        $categories = Kategori::all();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}