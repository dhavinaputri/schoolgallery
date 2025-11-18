<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryComment;
use App\Models\GalleryLike;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\NewsBookmark;

class InteractionController extends Controller
{
    /**
     * Generate unique visitor fingerprint
     */
    private function getVisitorFingerprint(Request $request)
    {
        $fingerprint = $request->session()->get('visitor_fingerprint');
        
        if (!$fingerprint) {
            $fingerprint = Str::random(32) . '_' . time();
            $request->session()->put('visitor_fingerprint', $fingerprint);
        }
        
        return $fingerprint;
    }

    /**
     * Like/Unlike gallery (only for authenticated users)
     */
    public function toggleLike(Request $request, $galleryId)
    {
        \Log::info('Toggle like called for gallery: ' . $galleryId);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            \Log::info('User not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk dapat like foto'
            ], 401);
        }

        $gallery = Gallery::findOrFail($galleryId);
        $userId = auth()->id();
        
        $existingLike = GalleryLike::where('gallery_id', $galleryId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
        } else {
            // Like
            GalleryLike::create([
                'gallery_id' => $galleryId,
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $liked = true;
        }

        $likeCount = GalleryLike::where('gallery_id', $galleryId)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
            'message' => $liked ? 'Berhasil menyukai foto!' : 'Berhasil menghapus like!'
        ]);
    }

    /**
     * Add comment (only for authenticated users)
     */
    public function addComment(Request $request, $galleryId)
    {
        \Log::info('Add comment called for gallery: ' . $galleryId);
        \Log::info('Request data: ' . json_encode($request->all()));
        
        // Check if user is authenticated
        if (!auth()->check()) {
            \Log::info('User not authenticated for comment');
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk dapat berkomentar'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:gallery_comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $gallery = Gallery::findOrFail($galleryId);
        $user = auth()->user();
        
        // Hitung depth untuk reply
        $depth = 0;
        if ($request->parent_id) {
            $parentComment = GalleryComment::find($request->parent_id);
            $depth = $parentComment ? $parentComment->depth + 1 : 0;
        }

        $comment = GalleryComment::create([
            'gallery_id' => $galleryId,
            'parent_id' => $request->parent_id,
            'name' => $user->name,
            'email' => $user->email,
            'content' => $request->content,
            'depth' => $depth,
            'is_approved' => true, // Auto approve untuk user yang login
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan!',
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'depth' => $comment->depth,
                'created_at' => $comment->created_at->format('d M Y H:i'),
                'created_at_iso' => $comment->created_at->toIso8601String(),
                'is_reply' => $comment->parent_id ? true : false,
                'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
            ]
        ]);
    }

    /**
     * Get comments with replies
     */
    public function getComments($galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        
        $comments = $gallery->comments()->with('replies')->get()->map(function ($comment) {
            $commentUser = $comment->email ? User::where('email', $comment->email)->first() : null;
            return [
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'depth' => $comment->depth,
                'created_at' => $comment->created_at->format('d M Y H:i'),
                'created_at_iso' => $comment->created_at->toIso8601String(),
                'avatar_url' => $commentUser && $commentUser->avatar_url ? $commentUser->avatar_url : null,
                'replies' => $comment->replies->map(function ($reply) {
                    $replyUser = $reply->email ? User::where('email', $reply->email)->first() : null;
                    return [
                        'id' => $reply->id,
                        'name' => $reply->name,
                        'content' => $reply->content,
                        'depth' => $reply->depth,
                        'created_at' => $reply->created_at->format('d M Y H:i'),
                        'created_at_iso' => $reply->created_at->toIso8601String(),
                        'avatar_url' => $replyUser && $replyUser->avatar_url ? $replyUser->avatar_url : null,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    /**
     * Send contact message
     */
    public function sendContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare contact data
        $contactData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'created_at' => now()
        ];

        // Send email notification to school admin
        try {
            $recipientEmail = config('services.contact_email');
            
            Notification::route('mail', $recipientEmail)
                ->notify(new ContactMessageNotification($contactData));
            
            Log::info('Contact form submitted', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim! Terima kasih atas feedback Anda.'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage(),
                'contact_data' => $contactData
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Submit photo (untuk guest yang ingin submit foto)
     */
    public function submitPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // TODO: Implementasi upload foto dan simpan ke database
        // Untuk sementara, kita simpan info ke session
        
        $photoData = [
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'description' => $request->description,
            'photo_name' => $request->file('photo')->getClientOriginalName(),
            'ip_address' => $request->ip(),
            'created_at' => now()
        ];

        // Simpan ke session untuk sementara (bisa diganti dengan database)
        $submissions = $request->session()->get('guest_photo_submissions', []);
        $submissions[] = $photoData;
        $request->session()->put('guest_photo_submissions', $submissions);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dikirim! Foto akan direview oleh admin sebelum dipublikasikan.'
        ]);
    }

    /**
     * Check if user has liked a gallery (only for authenticated users)
     */
    public function checkLikeStatus(Request $request, $galleryId)
    {
        $likeCount = GalleryLike::where('gallery_id', $galleryId)->count();

        if (!auth()->check()) {
            return response()->json([
                'success' => true,
                'liked' => false,
                'like_count' => $likeCount,
            ]);
        }

        $userId = auth()->id();
        $liked = GalleryLike::where('gallery_id', $galleryId)
            ->where('user_id', $userId)
            ->exists();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
        ]);
    }

    /**
     * Toggle bookmark news (auth required)
     */
    public function toggleNewsBookmark(Request $request, $newsId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $userId = auth()->id();
        $existing = NewsBookmark::where('user_id', $userId)->where('news_id', $newsId)->first();
        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            NewsBookmark::create(['user_id' => $userId, 'news_id' => $newsId]);
            $bookmarked = true;
        }

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked,
        ]);
    }

    /**
     * Check bookmark status for a news (auth required)
     */
    public function checkNewsBookmark(Request $request, $newsId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => true,
                'bookmarked' => false,
            ]);
        }

        $userId = auth()->id();
        $bookmarked = NewsBookmark::where('user_id', $userId)->where('news_id', $newsId)->exists();
        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked,
        ]);
    }

    /**
     * Add comment to news (only for authenticated users)
     */
    public function addNewsComment(Request $request, $newsId)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk dapat berkomentar'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:news_comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $news = \App\Models\News::findOrFail($newsId);
        $user = auth()->user();
        
        // Hitung depth untuk reply
        $depth = 0;
        if ($request->parent_id) {
            $parentComment = \App\Models\NewsComment::find($request->parent_id);
            $depth = $parentComment ? $parentComment->depth + 1 : 0;
        }

        $comment = \App\Models\NewsComment::create([
            'news_id' => $newsId,
            'parent_id' => $request->parent_id,
            'name' => $user->name,
            'content' => $request->content,
            'depth' => $depth,
            'is_approved' => true, // Auto approve untuk user yang login
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan!',
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'depth' => $comment->depth,
                'created_at' => $comment->created_at->format('d M Y H:i'),
                'created_at_iso' => $comment->created_at->toIso8601String(),
                'is_reply' => $comment->parent_id ? true : false
            ]
        ]);
    }

    /**
     * Get news comments with replies
     */
    public function getNewsComments($newsId)
    {
        $news = \App\Models\News::findOrFail($newsId);
        
        $comments = $news->comments()->with('replies')->get()->map(function ($comment) {
            $commentUser = User::where('name', $comment->name)->first();
            return [
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'depth' => $comment->depth,
                'created_at' => $comment->created_at->format('d M Y H:i'),
                'created_at_iso' => $comment->created_at->toIso8601String(),
                'avatar_url' => $commentUser && $commentUser->avatar ? asset('storage/'.$commentUser->avatar) : null,
                'replies' => $comment->replies->map(function ($reply) {
                    $replyUser = User::where('name', $reply->name)->first();
                    return [
                        'id' => $reply->id,
                        'name' => $reply->name,
                        'content' => $reply->content,
                        'depth' => $reply->depth,
                        'created_at' => $reply->created_at->format('d M Y H:i'),
                        'created_at_iso' => $reply->created_at->toIso8601String(),
                        'avatar_url' => $replyUser && $replyUser->avatar ? asset('storage/'.$replyUser->avatar) : null,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}