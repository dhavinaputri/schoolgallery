<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gallery_likes', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate likes from same user
            $table->unique(['gallery_id', 'user_id'], 'gallery_user_unique_like');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_likes', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('gallery_user_unique_like');
        });
    }
};
