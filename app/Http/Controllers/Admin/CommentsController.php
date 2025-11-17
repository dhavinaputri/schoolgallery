<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use App\Models\GalleryComment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, news, gallery
        $status = $request->get('status', 'all'); // all, approved, pending
        
        $newsComments = collect();
        $galleryComments = collect();
        
        // Fetch News Comments
        if ($type === 'all' || $type === 'news') {
            $newsQuery = NewsComment::with(['news', 'parent'])
                ->orderBy('created_at', 'desc');
            
            if ($status === 'approved') {
                $newsQuery->where('is_approved', true);
            } elseif ($status === 'pending') {
                $newsQuery->where('is_approved', false);
            }
            
            $newsComments = $newsQuery->get()->map(function($comment) {
                // Try to resolve user by name (NewsComment doesn't store email)
                $user = User::where('name', $comment->name)->first();
                return [
                    'id' => $comment->id,
                    'type' => 'news',
                    'name' => $comment->name,
                    'email' => null,
                    'content' => $comment->content,
                    'is_approved' => $comment->is_approved,
                    'created_at' => $comment->created_at,
                    'parent_id' => $comment->parent_id,
                    'item_title' => $comment->news->title ?? 'Berita Terhapus',
                    'item_slug' => $comment->news->slug ?? null,
                    'model' => $comment,
                    'user_avatar_url' => $user && $user->avatar ? asset('storage/'.$user->avatar) : null,
                ];
            });
        }
        
        // Fetch Gallery Comments
        if ($type === 'all' || $type === 'gallery') {
            $galleryQuery = GalleryComment::with(['gallery', 'parent'])
                ->orderBy('created_at', 'desc');
            
            if ($status === 'approved') {
                $galleryQuery->where('is_approved', true);
            } elseif ($status === 'pending') {
                $galleryQuery->where('is_approved', false);
            }
            
            $galleryComments = $galleryQuery->get()->map(function($comment) {
                // Resolve user by email for gallery comments
                $user = $comment->email ? User::where('email', $comment->email)->first() : null;
                return [
                    'id' => $comment->id,
                    'type' => 'gallery',
                    'name' => $comment->name,
                    'email' => $comment->email,
                    'content' => $comment->content,
                    'is_approved' => $comment->is_approved,
                    'created_at' => $comment->created_at,
                    'parent_id' => $comment->parent_id,
                    'item_title' => $comment->gallery->title ?? 'Galeri Terhapus',
                    'item_slug' => $comment->gallery->slug ?? null,
                    'model' => $comment,
                    'user_avatar_url' => $user && $user->avatar ? asset('storage/'.$user->avatar) : null,
                ];
            });
        }
        
        // Merge and sort
        $comments = $newsComments->merge($galleryComments)
            ->sortByDesc('created_at')
            ->values();
        
        // Paginate manually
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $total = $comments->count();
        $comments = $comments->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $comments,
            $total,
            $perPage,
            $currentPage,
            ['path' => route('admin.comments.index'), 'query' => $request->query()]
        );
        
        $stats = [
            'total' => NewsComment::count() + GalleryComment::count(),
            'pending' => NewsComment::where('is_approved', false)->count() + GalleryComment::where('is_approved', false)->count(),
            'approved' => NewsComment::where('is_approved', true)->count() + GalleryComment::where('is_approved', true)->count(),
            'news' => NewsComment::count(),
            'gallery' => GalleryComment::count(),
        ];
        
        return view('admin.comments.index', [
            'comments' => $paginator,
            'stats' => $stats,
            'type' => $type,
            'status' => $status,
        ]);
    }
    
    public function approve(Request $request, $type, $id)
    {
        $comment = $this->findComment($type, $id);
        
        if (!$comment) {
            return back()->with('error', 'Komentar tidak ditemukan.');
        }
        
        $comment->update(['is_approved' => true]);
        
        return back()->with('success', 'Komentar berhasil disetujui.');
    }
    
    public function destroy($type, $id)
    {
        $comment = $this->findComment($type, $id);
        
        if (!$comment) {
            return back()->with('error', 'Komentar tidak ditemukan.');
        }
        
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
    
    private function findComment($type, $id)
    {
        if ($type === 'news') {
            return NewsComment::find($id);
        } elseif ($type === 'gallery') {
            return GalleryComment::find($id);
        }
        
        return null;
    }
}
