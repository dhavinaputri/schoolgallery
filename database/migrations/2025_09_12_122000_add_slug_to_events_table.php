<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug column if missing
        if (!Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('slug')->unique()->after('title');
            });
        }

        // Backfill slug for existing rows that might be null/empty
        $eventsTable = DB::table('events');
        $eventsTable->whereNull('slug')->orWhere('slug', '')->orderBy('id')->chunkById(200, function ($rows) use ($eventsTable) {
            foreach ($rows as $row) {
                $base = Str::slug($row->title ?: 'event');
                $slug = $base;
                $i = 0;
                while (DB::table('events')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                    $i++;
                    $slug = $base.'-'.$i;
                }
                $eventsTable->where('id', $row->id)->update(['slug' => $slug]);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};


