<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateToPublicImages extends Command
{
    protected $signature = 'files:migrate-to-public-images {--dry-run : Only show what would be copied}';

    protected $description = 'Copy files from storage/app/public to public/images, preserving relative subpaths (galleries, news, teachers, avatars, etc.)';

    public function handle(): int
    {
        $this->info('Starting migration from storage/app/public -> public/images');

        $storageRoot = storage_path('app/public');
        $publicImagesRoot = public_path('images');

        if (!File::exists($storageRoot)) {
            $this->error("Source not found: {$storageRoot}");
            return self::FAILURE;
        }

        // Ensure destination base exists
        if (!$this->option('dry-run')) {
            File::ensureDirectoryExists($publicImagesRoot);
        }

        // Directories commonly used by the app. If empty, we still walk all files.
        $this->line('- Scanning all files under storage/app/public ...');
        $files = File::allFiles($storageRoot);
        $migrated = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $relPath = ltrim(str_replace($storageRoot, '', $file->getPathname()), DIRECTORY_SEPARATOR);
            $destPath = $publicImagesRoot . DIRECTORY_SEPARATOR . $relPath; // goes under public/images/{rel}
            $destDir = dirname($destPath);

            // Skip if destination already exists with same size (best-effort)
            if (File::exists($destPath) && File::size($destPath) === $file->getSize()) {
                $skipped++;
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("  [dry] copy {$file->getPathname()} -> {$destPath}");
                $migrated++;
                continue;
            }

            try {
                File::ensureDirectoryExists($destDir);
                File::copy($file->getPathname(), $destPath);
                $migrated++;
            } catch (\Throwable $e) {
                $this->warn("  Failed: {$relPath} (" . $e->getMessage() . ")");
                $skipped++;
            }
        }

        $this->info("Done. Migrated: {$migrated}, Skipped: {$skipped}");
        $this->info('Tip: Re-run with --dry-run to preview without copying.');
        $this->info('After migration, update any hard-coded asset URLs to use StorageHelper::getStorageUrl().');
        return self::SUCCESS;
    }
}
