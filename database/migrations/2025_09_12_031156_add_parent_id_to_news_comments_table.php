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
        Schema::table('news_comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('news_comments')->cascadeOnDelete();
            $table->integer('depth')->default(0); // untuk indentasi reply
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'depth']);
        });
    }
};
