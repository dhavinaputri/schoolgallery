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
        Schema::table('admins', function (Blueprint $table) {
            // tambahin kolom tambahan di tabel admins
            if (!Schema::hasColumn('admins', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('admins', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            if (!Schema::hasColumn('admins', 'password')) {
                $table->string('password')->after('email');
            }

            // kalau mau tambahin kolom remember_token
            if (!Schema::hasColumn('admins', 'remember_token')) {
                $table->rememberToken()->after('password');
            }

            // timestamps (created_at & updated_at)
            if (!Schema::hasColumn('admins', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'password', 'remember_token', 'created_at', 'updated_at']);
        });
    }
};
