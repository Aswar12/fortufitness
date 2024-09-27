<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\MembershipType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'membership_type_id' => MembershipType::factory(),
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['1', '0']),
        ];
    }
}
