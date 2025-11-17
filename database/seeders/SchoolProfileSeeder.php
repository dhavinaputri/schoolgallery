<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        SchoolProfile::create([
            'school_name' => 'SMKN 4 Bogor',
            'address' => 'Jl. Raya Tajur No. 1, Kota Bogor',
            'phone' => '0251-8321374',
            'email' => 'info@smkn4bogor.sch.id',
            'description' => 'Sekolah Menengah Kejuruan Negeri 4 Bogor yang berfokus pada pengembangan kompetensi kejuruan dan karakter siswa.',
            'vision' => 'Menjadi sekolah kejuruan unggulan yang menghasilkan lulusan berkompeten, berkarakter, dan siap kerja di era global.',
            'mission' => 'Menyelenggarakan pendidikan kejuruan yang berkualitas dengan mengedepankan penguasaan teknologi dan kewirausahaan,',
            'operational_hours' => 'Senin - Jumat: 07:00 - 15:00 WIB',
        ]);
    }
}