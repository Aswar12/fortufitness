<?php

namespace Database\Factories;

use App\Models\MembershipType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MembershipType>
 */
class MembershipTypeFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'duration' => $this->faker->randomNumber(2),
            'price' => $this->faker->randomNumber(5),
            'description' => $this->faker->paragraph,
        ];
    }
}
