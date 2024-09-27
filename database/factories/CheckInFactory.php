<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\CheckIn;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckIn>
 */
class CheckInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()->id;
        return [
            'user_id' => $userId,
            'check_in_time' => now()->format('Y-m-d H:i:s'),
            'check_out_time' => now()->addHours(2)->format('Y-m-d H:i:s'),
            'check_in_method' => $this->faker->randomElement(['manual', 'auto']),
        ];
    }
}
