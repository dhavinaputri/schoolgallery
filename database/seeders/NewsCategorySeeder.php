<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pengumuman',
                'description' => 'Info resmi sekolah (libur, ujian, daftar ulang, dsb.)',
                'order' => 1,
            ],
            [
                'name' => 'Kegiatan Sekolah',
                'description' => 'Berita kegiatan harian/agenda (MPLS, Classmeet, LDKS, dsb.)',
                'order' => 2,
            ],
            [
                'name' => 'Prestasi',
                'description' => 'Liputan siswa/guru juara lomba, penghargaan sekolah',
                'order' => 3,
            ],
            [
                'name' => 'Artikel / Edukasi',
                'description' => 'Tulisan guru/siswa (tips belajar, karya tulis, artikel pendidikan)',
                'order' => 4,
            ]
        ];

        foreach ($categories as $category) {
            DB::table('news_categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'order' => $category['order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
