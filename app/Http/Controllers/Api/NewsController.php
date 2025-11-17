<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    // Get all published news with pagination
    public function index(Request $request)
    {
        $query = News::with(['admin', 'newsCategory'])->latest();

        // Filter by search query
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('news_category_id', $request->category_id);
        }

        // Filter by published status
        if ($request->has('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        } else {
            $query->published();
        }

        $perPage = $request->get('per_page', 10);
        $news = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    // Get single news by slug
    public function show($slug)
    {
        $news = News::with(['admin', 'newsCategory'])
            ->where('slug', $slug)
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    // Create new news (admin only)
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'is_published' => 'boolean',
            'news_category_id' => 'required|exists:news_categories,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        // Set published_at if published
        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        // Set admin_id to the authenticated admin
        $validated['admin_id'] = $request->user()->id;

        // Create news
        $news = News::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'News created successfully',
            'data' => $news->load('newsCategory'),
        ], 201);
    }

    // Update news (admin only)
    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found',
            ], 404);
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'sometimes|string|max:255',
            'is_published' => 'boolean',
            'news_category_id' => 'sometimes|exists:news_categories,id',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
        }

        // Update slug if title changed
        if ($request->has('title') && $request->title !== $news->title) {
            $validated['slug'] = Str::slug($request->title) . '-' . time();
        }

        // Set published_at if published
        if ($request->has('is_published') && $request->boolean('is_published') && !$news->published_at) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'News updated successfully',
            'data' => $news->load('newsCategory'),
        ]);
    }

    // Delete news (admin only)
    public function destroy($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News not found',
            ], 404);
        }

        // Delete image if exists
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully',
        ]);
    }

    // Get all news categories
    public function categories()
    {
        $categories = NewsCategory::active()
            ->ordered()
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}