<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class PruneExpiredEvents extends Command
{
    protected $signature = 'events:prune-expired {--dry-run : Only show how many would be deleted}';
    protected $description = 'Delete events that already passed (based on start/end time).';

    public function handle(): int
    {
        if ($this->option('dry-run')) {
            $count = Event::where(function($q){
                $q->where('end_at', '<', now())
                  ->orWhere(function($qq){
                      $qq->whereNull('end_at')->where('start_at', '<', now());
                  });
            })->count();
            $this->info("[DRY RUN] Expired events to delete: {$count}");
            return self::SUCCESS;
        }

        $deleted = Event::deleteExpired();
        $this->info("Deleted expired events: {$deleted}");
        return self::SUCCESS;
    }
}
