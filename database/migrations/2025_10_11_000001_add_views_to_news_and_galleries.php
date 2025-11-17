<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('is_published');
            }
        });

        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('is_published');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'views')) {
                $table->dropColumn('views');
            }
        });

        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
};
