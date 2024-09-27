<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Test User ' . $i,
                'email' => 'test' . $i . '@example.com',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
