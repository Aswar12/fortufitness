<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $membershipTypes = MembershipType::all();

        foreach ($users as $user) {
            $membershipType = $membershipTypes->random();
            $membership = new Membership();
            $membership->user_id = $user->id;
            $membership->membership_type_id = $membershipType->id;
            $membership->start_date = now();
            $membership->end_date = now()->addYear();
            $membership->save();
        }
    }
}
