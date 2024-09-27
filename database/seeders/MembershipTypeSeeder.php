<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membershipTypes = [
            ['name' => 'Basic', 'description' => 'Basic membership'],
            ['name' => 'Premium', 'description' => 'Premium membership'],
            ['name' => 'Gold', 'description' => 'Gold membership'],
        ];

        foreach ($membershipTypes as $membershipType) {
            MembershipType::create($membershipType);
        }
    }
}
