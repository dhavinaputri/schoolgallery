<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('submission_image_id')->nullable()->after('submission_id')->constrained('gallery_submission_images')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submission_image_id');
        });
    }
};
