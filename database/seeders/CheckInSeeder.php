<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CheckIn::factory()->count(10)->create();
    }
}
