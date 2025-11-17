<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\SchoolProfileSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus data lama
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Jalankan seeder dengan urutan yang benar
        $this->call([
            AdminSeeder::class,          // Buat admin terlebih dahulu
            UserSeeder::class,           // Buat user guest
            KategoriSeeder::class,       // Buat kategori gallery
            NewsCategorySeeder::class,   // Buat kategori berita
            SchoolProfileSeeder::class,  // Terakhir buat profil sekolah
        ]);
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Database telah diisi dengan data contoh!');
        $this->command->info('Email admin: admin@sekolah.com');
        $this->command->info('Password admin: password123');
        $this->command->info('Email guest: john@example.com, jane@example.com, admin@example.com');
        $this->command->info('Password guest: password123');
    }
}
