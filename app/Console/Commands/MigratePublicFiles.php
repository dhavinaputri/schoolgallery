<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigratePublicFiles extends Command
{
    protected $signature = 'files:migrate-to-public {--dry-run : Only show what would be copied}';

    protected $description = 'Copy files from storage/app/public to public/ preserving relative paths (galleries, submissions, avatars, etc.)';

    public function handle(): int
    {
        $this->info('Starting migration from storage/app/public -> public');

        $storageRoot = storage_path('app/public');
        $publicRoot = public_path();

        if (!File::exists($storageRoot)) {
            $this->error("Source not found: {$storageRoot}");
            return self::FAILURE;
        }

        // Folders to migrate (copy if present)
        $candidates = [
            'galleries',
            'submissions',
            'news',
            'teachers',
            'avatars',
            'users',
            'profile',
        ];

        $migrated = 0;
        $skipped = 0;

        foreach ($candidates as $dir) {
            $srcDir = $storageRoot . DIRECTORY_SEPARATOR . $dir;
            if (!File::isDirectory($srcDir)) {
                $this->line("- Skip (not found): {$dir}");
                continue;
            }

            $this->line("- Scanning: {$dir}");
            // Recursively iterate files
            $files = File::allFiles($srcDir);
            foreach ($files as $file) {
                $relPath = ltrim(str_replace($storageRoot, '', $file->getPathname()), DIRECTORY_SEPARATOR);
                $destPath = $publicRoot . DIRECTORY_SEPARATOR . $relPath;
                $destDir = dirname($destPath);

                if (!File::isDirectory($destDir)) {
                    if ($this->option('dry-run')) {
                        $this->line("  [dry] mkdir -p " . $destDir);
                    } else {
                        File::ensureDirectoryExists($destDir);
                    }
                }

                if ($this->option('dry-run')) {
                    $this->line("  [dry] copy {$file->getPathname()} -> {$destPath}");
                    $migrated++;
                    continue;
                }

                try {
                    File::copy($file->getPathname(), $destPath);
                    $migrated++;
                } catch (\Throwable $e) {
                    $this->warn("  Failed: {$relPath} (" . $e->getMessage() . ")");
                    $skipped++;
                }
            }
        }

        $this->info("Done. Migrated: {$migrated}, Skipped: {$skipped}");
        $this->info('Tip: Re-run with --dry-run to preview without copying.');
        return self::SUCCESS;
    }
}
