<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\Admin;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = Admin::all();
        
        if ($admins->isEmpty()) {
            $this->command->info('No admins found. Please run AdminSeeder first.');
            return;
        }

        $activities = [
            [
                'action' => 'login',
                'description' => 'Login ke sistem admin',
                'admin_id' => $admins->first()->id,
                'created_at' => now()->subHours(1),
            ],
            [
                'action' => 'create',
                'description' => 'Membuat berita baru',
                'admin_id' => $admins->first()->id,
                'created_at' => now()->subHours(2),
            ],
            [
                'action' => 'update',
                'description' => 'Memperbarui profil sekolah',
                'admin_id' => $admins->first()->id,
                'created_at' => now()->subHours(3),
            ],
            [
                'action' => 'create',
                'description' => 'Membuat galeri baru',
                'admin_id' => $admins->first()->id,
                'created_at' => now()->subHours(4),
            ],
            [
                'action' => 'view',
                'description' => 'Melihat statistik dashboard',
                'admin_id' => $admins->first()->id,
                'created_at' => now()->subHours(5),
            ],
        ];

        // Tambahkan aktivitas untuk super admin jika ada
        $superAdmin = $admins->where('role', 'super_admin')->first();
        if ($superAdmin) {
            $superAdminActivities = [
                [
                    'action' => 'reset_password',
                    'description' => 'Reset password untuk admin lain',
                    'admin_id' => $superAdmin->id,
                    'created_at' => now()->subHours(6),
                ],
                [
                    'action' => 'create',
                    'description' => 'Membuat admin baru',
                    'admin_id' => $superAdmin->id,
                    'created_at' => now()->subHours(7),
                ],
                [
                    'action' => 'update',
                    'description' => 'Memperbarui data admin',
                    'admin_id' => $superAdmin->id,
                    'created_at' => now()->subHours(8),
                ],
            ];
            
            $activities = array_merge($activities, $superAdminActivities);
        }

        foreach ($activities as $activity) {
            ActivityLog::create([
                'admin_id' => $activity['admin_id'],
                'action' => $activity['action'],
                'description' => $activity['description'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => [
                    'seeded' => true,
                    'created_by' => 'seeder'
                ],
                'created_at' => $activity['created_at'],
                'updated_at' => $activity['created_at'],
            ]);
        }

        $this->command->info('Activity logs seeded successfully!');
    }
}