<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Guest\AuthController as GuestAuthController;
use App\Http\Controllers\Guest\GallerySubmissionController as GuestGallerySubmissionController;
use App\Http\Controllers\Guest\InteractionController;
use App\Http\Controllers\Guest\FavoriteController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\SitemapController;

// Storage file serving route (must be before other routes)
Route::get('/storage/{path}', function ($path) {
    // Determine the actual file location
    $isProduction = app()->environment('production') || 
                   getenv('RAILWAY_ENVIRONMENT') || 
                   getenv('RAILWAY_PROJECT_ID') ||
                   file_exists('/app/public');
    
    if ($isProduction) {
        // On Railway, files are in the volume mount
        $storagePath = env('FILESYSTEM_ROOT', '/app/storage/app/public');
        $fullPath = $storagePath . '/' . $path;
    } else {
        // Locally, files are in storage/app/public
        $fullPath = storage_path('app/public/' . $path);
    }

    // Check if file exists
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404, 'File not found');
    }

    // Serve the file
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.file');

// Public Routes (tracked visits)
Route::middleware('track.visits')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
    // Kirim Foto harus didefinisikan sebelum /gallery/{id} agar tidak tertangkap oleh route dinamis tersebut
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/gallery/submit', [GuestGallerySubmissionController::class, 'create'])->name('gallery.submit');
        Route::post('/gallery/submit', [GuestGallerySubmissionController::class, 'store'])->name('gallery.submit.store');
    });
    Route::get('/gallery/category/{category}', [HomeController::class, 'galleryByCategory'])->name('gallery.category');
    Route::get('/gallery/{id}', [HomeController::class, 'galleryDetail'])->name('gallery.detail');
    Route::get('/gallery/download/{id}', [HomeController::class, 'download'])->name('gallery.download');
    Route::get('/news', [HomeController::class, 'news'])->name('news');
    Route::get('/news/{slug}', [HomeController::class, 'newsDetail'])->name('news.detail');
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index'); // Tambahkan route ini
    Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/teachers', [HomeController::class, 'teachers'])->name('teachers');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});

// Guest Interaction Routes (no login required)
Route::middleware('track.visits')->group(function () {
    // Gallery interactions - comments can be viewed by everyone
    Route::get('/gallery/{id}/comments', [InteractionController::class, 'getComments'])->name('gallery.comments');
    
    // News interactions - comments can be viewed by everyone
    Route::get('/news/{id}/comments', [InteractionController::class, 'getNewsComments'])->name('news.comments');

    // Favorites status can be read by everyone (returns favorited=false for guests)
    Route::get('/gallery/{id}/favorite-status', [FavoriteController::class, 'status'])->name('gallery.favorite-status');
    // Like status can be read by everyone (returns liked=false for guests)
    Route::get('/gallery/{id}/like-status', [InteractionController::class, 'checkLikeStatus'])->name('gallery.like-status');
});

// Authenticated User Interaction Routes (login required + email verified)
Route::middleware(['auth', 'verified', 'track.visits'])->group(function () {
    // Gallery interactions for authenticated users
    Route::post('/gallery/{id}/like', [InteractionController::class, 'toggleLike'])->name('gallery.like');
    Route::post('/gallery/{id}/comment', [InteractionController::class, 'addComment'])->name('gallery.comment');

    // Favorites (MVP)
    Route::post('/gallery/{id}/favorite', [FavoriteController::class, 'toggle'])->name('gallery.favorite');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    
    // News interactions for authenticated users
    Route::post('/news/{id}/comment', [InteractionController::class, 'addNewsComment'])->name('news.comment.store');
    
    // Contact form submission (require login + verified email + rate limited)
    Route::post('/contact', [InteractionController::class, 'sendContact'])
        ->middleware('throttle:2,30') // Max 2 requests per 30 minutes
        ->name('contact.submit');
    
    // Photo submission for authenticated users
    Route::post('/submit-photo', [InteractionController::class, 'submitPhoto'])->name('photo.submit');
});

// Protected Routes (require login + email verified)
Route::middleware(['auth', 'verified', 'track.visits'])->group(function () {
    // News interactions
    Route::post('/news/{slug}/comment', [HomeController::class, 'commentNews'])->name('news.comment');

    // User profile
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    // (dipindah ke atas sebelum /gallery/{id})
});

// Guest Authentication Routes
Route::prefix('guest')->name('guest.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [GuestAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [GuestAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [GuestAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [GuestAuthController::class, 'register'])->name('register.submit');

        // Forgot + Reset Password (Guest)
        Route::get('/forgot-password', [GuestAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [GuestAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [GuestAuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [GuestAuthController::class, 'resetPassword'])->name('password.update');
    });
    
    // Email Verification Routes
    Route::get('/verify-email', [GuestAuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [GuestAuthController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/resend-verification', [GuestAuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
    
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [GuestAuthController::class, 'logout'])->name('logout');
    });
});

// Alias login untuk default Laravel (redirect ke guest login)
Route::get('/login', function () {
    return redirect()->route('guest.login');
})->name('login');

// Alias route name expected by Laravel's password reset notification for users
// This ensures links generated by Password::sendResetLink use a valid route
Route::get('/reset-password/{token}', [GuestAuthController::class, 'showResetPasswordForm'])->name('password.reset');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login when unauthenticated
    Route::get('/', function(){
        return redirect()->route('admin.login');
    })->name('root');

    // Guest (Belum login) - use custom admin.guest to prevent redirect to public home
    Route::middleware('admin.guest')->group(function () {
        // Login
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
       
        // Forgot + Reset Password
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    // Authenticated (Sudah login)
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Statistics Detail (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/statistics/{type}', [DashboardController::class, 'statisticsDetail'])->name('statistics.detail');
        });

        // Gallery Management
        Route::get('galleries', [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('galleries/kategori/{kategoriSlug?}', [GalleryController::class, 'index'])->name('galleries.kategori');
        Route::get('galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
        Route::post('galleries', [GalleryController::class, 'store'])->name('galleries.store');
        Route::get('galleries/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');
        Route::get('galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
        Route::put('galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::patch('galleries/{gallery}/toggle-publish', [GalleryController::class, 'togglePublish'])->name('galleries.toggle-publish');
        Route::delete('galleries/{gallery}/remove-image', [GalleryController::class, 'removeImage'])->name('galleries.remove-image');

        // Gallery Submissions (User uploads) Management
        Route::get('/gallery-submissions', [\App\Http\Controllers\Admin\GallerySubmissionController::class, 'index'])->name('gallery-submissions.index');
        Route::get('/gallery-submissions/{submission}', [\App\Http\Controllers\Admin\GallerySubmissionController::class, 'show'])->name('gallery-submissions.show');
        // Allow both GET and POST for approve to avoid 404s due to method issues in some environments
        Route::match(['POST'], '/gallery-submissions/{submission}/approve', [\App\Http\Controllers\Admin\GallerySubmissionController::class, 'approve'])->name('gallery-submissions.approve');
        Route::post('/gallery-submissions/{submission}/reject', [\App\Http\Controllers\Admin\GallerySubmissionController::class, 'reject'])->name('gallery-submissions.reject');
        Route::post('/gallery-submissions/{submission}/publish/{image}', [\App\Http\Controllers\Admin\GallerySubmissionController::class, 'publishImage'])->name('gallery-submissions.publish');

        // Gallery Comments moderation
        Route::delete('galleries/comments/{comment}', function(App\Models\GalleryComment $comment){
            $comment->delete();
            return back()->with('success', 'Komentar dihapus');
        })->name('galleries.comments.destroy');

        // News Management
        Route::resource('news', NewsController::class);
        Route::patch('news/{news}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');
        Route::delete('news/{news}/remove-image', [NewsController::class, 'removeImage'])->name('news.remove-image');

        // Events Management
        Route::resource('events', EventsController::class)->except(['show']);
        Route::patch('events/{event}/toggle-publish', [EventsController::class, 'togglePublish'])->name('events.toggle-publish');

        // Comments Management
        Route::get('comments', [\App\Http\Controllers\Admin\CommentsController::class, 'index'])->name('comments.index');
        Route::patch('comments/{type}/{id}/approve', [\App\Http\Controllers\Admin\CommentsController::class, 'approve'])->name('comments.approve');
        Route::delete('comments/{type}/{id}', [\App\Http\Controllers\Admin\CommentsController::class, 'destroy'])->name('comments.destroy');

        // School Profile Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
            Route::put('/school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
        });

        // Admin Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
            Route::post('admins/{admin}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('admins.reset-password');
            Route::patch('admins/{admin}/toggle-active', [AdminManagementController::class, 'toggleActive'])->name('admins.toggle-active');

            // Users Management (Super Admin Only)
            Route::get('users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
            Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'update'])->name('users.update');
            Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UsersController::class, 'toggleActive'])->name('users.toggle-active');
        });

        // Teacher Management
        Route::resource('teachers', TeacherController::class);

        // Reports & Export
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('reports/export-visitor-stats', [ReportController::class, 'exportVisitorStats'])->name('reports.export-visitor-stats');
        Route::get('reports/export-visitor-stats', [ReportController::class, 'exportVisitorStats'])->name('reports.export-visitor-stats.quick');
        Route::post('reports/export-content-stats', [ReportController::class, 'exportContentStats'])->name('reports.export-content-stats');
        Route::get('reports/export-content-stats', [ReportController::class, 'exportContentStats'])->name('reports.export-content-stats.quick');
        Route::post('reports/export-admin-activity', [ReportController::class, 'exportAdminActivity'])->name('reports.export-admin-activity');
        Route::get('reports/export-admin-activity', [ReportController::class, 'exportAdminActivity'])->name('reports.export-admin-activity.quick');

        // Activity logs (read-only)
        Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.index');
    });

});

// Chatbot endpoint - protected by auth and verified middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
});

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
