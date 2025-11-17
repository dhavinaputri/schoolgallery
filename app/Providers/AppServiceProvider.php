<?php

namespace App\Providers;

use App\Models\SchoolProfile;
use App\Helpers\StorageHelper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        
        // Share schoolProfile to all views
        View::composer('*', function ($view) {
            $view->with('schoolProfile', SchoolProfile::getProfile());
        });

        // Register Blade directive for storage URLs
        Blade::directive('storageUrl', function ($expression) {
            return "<?php echo " . StorageHelper::class . "::getStorageUrl($expression); ?>";
        });
    }
}
