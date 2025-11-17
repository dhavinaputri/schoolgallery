<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GalleryFavorite;
use App\Models\GalleryLike;
use App\Models\GalleryComment;
use App\Models\NewsComment;
use App\Helpers\StorageHelper;

class UserProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'akun');

        $favorites = collect();
        $activities = collect();

        if ($tab === 'favorit') {
            $favorites = GalleryFavorite::with(['gallery' => function($q){
                $q->select('id','title','image','created_at');
            }])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->take(50)
            ->get();
        }

        if ($tab === 'aktivitas') {
            // Likes by user
            $likes = GalleryLike::with(['gallery' => function($q){
                $q->select('id','title','image','created_at');
            }])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get()
            ->map(function($like){
                return [
                    'type' => 'like',
                    'at' => $like->created_at,
                    'gallery' => $like->gallery,
                    'news' => null,
                ];
            });

            // Comments by user (identified by email)
            $comments = GalleryComment::with(['gallery' => function($q){
                $q->select('id','title','image','created_at');
            }])
            ->where('email', $user->email)
            ->orderByDesc('created_at')
            ->get()
            ->map(function($comment){
                return [
                    'type' => 'gallery_comment',
                    'at' => $comment->created_at,
                    'gallery' => $comment->gallery,
                    'news' => null,
                    'excerpt' => str($comment->content)->limit(80),
                ];
            });

            // News comments by user (identified by name)
            $newsComments = NewsComment::with(['news' => function($q){
                $q->select('id','title','slug','image','created_at');
            }])
            ->where('name', $user->name)
            ->orderByDesc('created_at')
            ->get()
            ->map(function($comment){
                return [
                    'type' => 'news_comment',
                    'at' => $comment->created_at,
                    'gallery' => null,
                    'news' => $comment->news,
                    'excerpt' => str($comment->content)->limit(80),
                ];
            });

            $activities = $likes->merge($comments)->merge($newsComments)->sortByDesc('at')->values();
        }

        return view('profile.edit', [
            'user' => $user,
            'tab' => $tab,
            'favorites' => $favorites,
            'activities' => $activities,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$user->id],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'current_password' => ['nullable','string'],
            'password' => ['nullable','string','min:6','confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                StorageHelper::deleteFile($user->avatar);
            }
            
            // Store new avatar
            $path = StorageHelper::storeFile($request->file('avatar'), 'avatars');
            $user->avatar = $path;
        }

        // If changing password, require current_password to match
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.'])->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // If user clicked the explicit save button with redirect instruction, go to home
        if ($request->input('redirect') === 'home') {
            return redirect()->route('home')->with('status', 'Profil berhasil diperbarui.');
        }

        // Default: stay on profile (useful for auto-submit avatar or partial edits)
        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}


