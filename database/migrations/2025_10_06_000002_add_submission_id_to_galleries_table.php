<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('submission_id')->nullable()->after('kategori_id')->constrained('gallery_submissions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submission_id');
        });
    }
};
