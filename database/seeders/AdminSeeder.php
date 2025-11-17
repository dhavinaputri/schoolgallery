<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus admin yang sudah ada dengan email yang sama (force delete karena model pakai SoftDeletes)
        Admin::withTrashed()->where('email', 'admin@sekolah.com')->forceDelete();

        // Buat admin baru
        $admin = Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',    
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Admin berhasil dibuat!');
        $this->command->info('Email: admin@sekolah.com');
        $this->command->info('Password: password123');
    }
}