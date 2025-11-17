<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus semua kategori yang ada
        Kategori::truncate();

        $kategoris = [
            [
                'id' => 1,
                'nama' => 'Kegiatan Sekolah',
                'slug' => 'kegiatan-sekolah',
                'deskripsi' => 'Dokumentasi event (MPLS, upacara, pentas seni, classmeeting)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'nama' => 'Fasilitas Sekolah',
                'slug' => 'fasilitas-sekolah',
                'deskripsi' => 'Foto ruang kelas, lab, perpustakaan, lapangan dll',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'nama' => 'Prestasi',
                'slug' => 'prestasi',
                'deskripsi' => 'Dokumentasi lomba, piala, kegiatan juara',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Gunakan insert untuk menghindari mass assignment
        \DB::table('kategoris')->insert($kategoris);
        
        // Aktifkan kembali foreign key check
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
