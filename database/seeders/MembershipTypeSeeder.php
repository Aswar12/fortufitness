<?php

namespace Database\Seeders;

use App\Models\MembershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Bronze', 'duration' => 30, 'price' => 300000, 'description' => 'Akses dasar ke fasilitas gym selama 1 bulan.'],
            ['name' => 'Silver', 'duration' => 90, 'price' => 800000, 'description' => 'Akses penuh ke fasilitas gym dan 1 sesi konsultasi gratis selama 3 bulan.'],
            ['name' => 'Gold', 'duration' => 180, 'price' => 1500000, 'description' => 'Akses penuh ke fasilitas gym, 3 sesi konsultasi gratis, dan akses ke kelas-kelas khusus selama 6 bulan.'],
            ['name' => 'Platinum', 'duration' => 365, 'price' => 2500000, 'description' => 'Akses VIP ke semua fasilitas gym, konsultasi tak terbatas, akses prioritas ke semua kelas, dan pelatih pribadi selama 1 tahun.'],
        ];

        foreach ($types as $type) {
            MembershipType::factory()->create($type);
        }
    }
}
