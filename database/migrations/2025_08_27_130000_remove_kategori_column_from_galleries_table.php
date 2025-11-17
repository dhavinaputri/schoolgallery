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
        // Periksa apakah kolom 'kategori' masih ada
        if (Schema::hasColumn('galleries', 'kategori')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika perlu rollback, tambahkan kembali kolom kategori
        if (!Schema::hasColumn('galleries', 'kategori')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->string('kategori')->nullable()->after('admin_id');
            });
        }
    }
};
