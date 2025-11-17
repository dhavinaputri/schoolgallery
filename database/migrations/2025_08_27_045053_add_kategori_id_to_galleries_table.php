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
        Schema::table('galleries', function (Blueprint $table) {
            // Pastikan kolom belum ada sebelumnya
            if (!Schema::hasColumn('galleries', 'kategori_id')) {
                $table->foreignId('kategori_id')
                      ->after('is_published')
                      ->nullable()
                      ->constrained('kategoris')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }
};
