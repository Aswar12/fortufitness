<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\Membership;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $membership = Membership::inRandomOrder()->first();

        return [
            'membership_id' => $membership->id,
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'payment_method' => $this->faker->randomElement(['Cash', 'Credit Card', 'Debit Card']),
            'payment_date' => $this->faker->date(),
        ];
    }
}
