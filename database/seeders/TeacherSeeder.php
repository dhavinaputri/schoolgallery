<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'Drs. Mulya Murprihartono, M.Si',
                'position' => 'Kepala Sekolah',
                'description' => 'Menjabat sebagai kepala sekolah di SMKN 4 Kota Bogor pada tahun ajaran 2022-2023 dan telah menyampaikan materi mengenai kepemimpinan dan jati diri pada kegiatan di sekolah tersebut.',
                'order' => 1,
                'is_active' => true,
                'facebook' => 'https://facebook.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
            ],
            [
                'name' => 'MULYADIH, S.PD',
                'position' => 'Wakil Kepala Sekolah',
                'description' => 'Spesialis kurikulum dengan pengalaman mengajar selama 15 tahun di berbagai jenjang pendidikan',
                'order' => 2,
                'is_active' => true,
                'facebook' => 'https://facebook.com',
                'instagram' => 'https://instagram.com',
            ],
            [
                'name' => 'Novita Wandasari, S.Pd',
                'position' => 'Kesiswaan',
                'description' => 'Berpengalaman menjadi kaprodi jurusan PPLG di SMK Negeri 4 Kota Bogor',
                'order' => 3,
                'is_active' => true,
                'instagram' => 'https://instagram.com',
                'linkedin' => 'https://linkedin.com',
            ],
            [
                'name' => 'Yunita Indrasari, St, M. Kom',
                'position' => 'Kejuruan TJKT',
                'description' => 'Berpengalaman mengajar di bidang jaringan komputer dan telekomunikasi serta mengajar di bidang kewirausahaan',
                'order' => 4,
                'is_active' => true,
                'twitter' => 'https://twitter.com',
                'linkedin' => 'https://linkedin.com',
            ],
            [
                'name' => 'Ahmad Fauzi, S.Kom',
                'position' => 'Guru Produktif RPL',
                'description' => 'Ahli dalam pemrograman web dan mobile dengan pengalaman lebih dari 10 tahun di industri',
                'order' => 5,
                'is_active' => true,
                'linkedin' => 'https://linkedin.com',
            ],
            [
                'name' => 'Dewi Sartika, M.Pd',
                'position' => 'Guru Bahasa Inggris',
                'description' => 'Berkecimpung di dunia pendidikan bahasa Inggris selama 12 tahun dengan sertifikasi internasional',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso, S.T',
                'position' => 'Guru Produktif TKJ',
                'description' => 'Bersertifikasi CCNA dengan pengalaman di jaringan komputer dan keamanan jaringan',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahayu, S.Pd',
                'position' => 'Guru Matematika',
                'description' => 'Spesialis dalam mengajar matematika terapan dengan pendekatan yang mudah dipahami',
                'order' => 8,
                'is_active' => true,
            ]
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
