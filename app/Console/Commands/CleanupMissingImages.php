<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Teacher;
use App\Models\User;
use App\Models\GallerySubmission;

class CleanupMissingImages extends Command
{
    protected $signature = 'images:cleanup';
    protected $description = 'Remove database records for images that no longer exist in storage';

    public function handle()
    {
        $this->info('🔍 Scanning for missing images...');
        
        $basePath = $this->getBasePath();
        $deletedCount = 0;

        // Check Gallery images
        $galleries = Gallery::whereNotNull('image')->get();
        foreach ($galleries as $gallery) {
            if (!$this->fileExists($basePath, $gallery->image)) {
                $this->warn("❌ Gallery #{$gallery->id}: {$gallery->image} - NOT FOUND");
                $gallery->update(['image' => null]);
                $deletedCount++;
            }
        }

        // Check News images
        $news = News::whereNotNull('image')->get();
        foreach ($news as $item) {
            if (!$this->fileExists($basePath, $item->image)) {
                $this->warn("❌ News #{$item->id}: {$item->image} - NOT FOUND");
                $item->update(['image' => null]);
                $deletedCount++;
            }
        }

        // Check Teacher images
        $teachers = Teacher::whereNotNull('image')->get();
        foreach ($teachers as $teacher) {
            if (!$this->fileExists($basePath, $teacher->image)) {
                $this->warn("❌ Teacher #{$teacher->id}: {$teacher->image} - NOT FOUND");
                $teacher->update(['image' => null]);
                $deletedCount++;
            }
        }

        // Check User avatars
        $users = User::whereNotNull('avatar')->get();
        foreach ($users as $user) {
            if (!$this->fileExists($basePath, $user->avatar)) {
                $this->warn("❌ User #{$user->id}: {$user->avatar} - NOT FOUND");
                $user->update(['avatar' => null]);
                $deletedCount++;
            }
        }

        // Check Gallery Submissions
        $submissions = GallerySubmission::whereNotNull('image')->get();
        foreach ($submissions as $submission) {
            if (!$this->fileExists($basePath, $submission->image)) {
                $this->warn("❌ Submission #{$submission->id}: {$submission->image} - NOT FOUND");
                $submission->update(['image' => null]);
                $deletedCount++;
            }
        }

        $this->info("✅ Cleanup completed! Removed {$deletedCount} missing image references.");
    }

    private function getBasePath()
    {
        $isProduction = app()->environment('production') || 
                       getenv('RAILWAY_ENVIRONMENT') || 
                       getenv('RAILWAY_PROJECT_ID') ||
                       file_exists('/app/public');

        if ($isProduction) {
            return env('FILESYSTEM_ROOT', '/app/storage/app/public');
        }
        return storage_path('app/public');
    }

    private function fileExists($basePath, $relativePath)
    {
        if (!$relativePath) {
            return false;
        }
        
        $fullPath = $basePath . '/' . ltrim($relativePath, '/');
        return file_exists($fullPath) && is_file($fullPath);
    }
}
