<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with(['admin', 'newsCategory'])
            ->latest()
            ->paginate(10);
            
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $categories = NewsCategory::active()->ordered()->get();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'author' => 'required|string|max:255',
            'is_published' => 'boolean',
            'news_category_id' => 'required|exists:news_categories,id',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'is_published' => $request->boolean('is_published'),
            'news_category_id' => $request->news_category_id,
            'admin_id' => auth('admin')->id(),
        ];

        if ($request->boolean('is_published')) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = StorageHelper::storeFile($request->file('image'), 'news');
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function show(News $news)
    {
        $news->load(['comments', 'newsCategory', 'admin']);
        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::active()->ordered()->get();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'author' => 'required|string|max:255',
            'is_published' => 'boolean',
            'news_category_id' => 'required|exists:news_categories,id',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'is_published' => $request->boolean('is_published'),
            'news_category_id' => $request->news_category_id,
        ];

        if ($request->boolean('is_published') && !$news->is_published) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                StorageHelper::deleteFile($news->image);
            }
            $data['image'] = StorageHelper::storeFile($request->file('image'), 'news');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            StorageHelper::deleteFile($news->image);
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    public function togglePublish(News $news)
    {
        $news->update([
            'is_published' => !$news->is_published,
            'published_at' => !$news->is_published ? now() : null
        ]);

        $status = $news->is_published ? 'published' : 'unpublished';
        return redirect()->route('admin.news.index')->with('success', "News {$status} successfully.");
    }

    public function removeImage(News $news)
    {
        if ($news->image) {
            StorageHelper::deleteFile($news->image);
            $news->update(['image' => null]);
            return redirect()->back()->with('success', 'Image removed successfully.');
        }

        return redirect()->back()->with('error', 'No image to remove.');
    }
}