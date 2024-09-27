<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $memberships = Membership::all();

        foreach ($users as $user) {
            $membership = $memberships->random();
            $payment = new Payment();
            $payment->user_id = $user->id;
            $payment->membership_id = $membership->id;
            $payment->payment_method = 'Transfer Bank';
            $payment->payment_date = now();
            $payment->amount = 100000;
            $payment->status = 'success';
            $payment->save();
        }
    }
}
