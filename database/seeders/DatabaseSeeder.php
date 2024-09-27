<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Model;
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


        Model::unguard();

        $this->call([
            UserSeeder::class,
            MembershipTypeSeeder::class,
            MembershipSeeder::class,
            CheckInSeeder::class,
            ExpenseSeeder::class,
            PaymentSeeder::class,
            ApiTokenSeeder::class,
        ]);

        Model::reguard();
    }
}
