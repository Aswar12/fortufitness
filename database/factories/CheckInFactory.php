<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\Membership;
use Carbon\Carbon;

class CheckInFactory extends Factory
{
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $membership = Membership::where('user_id', $user->id)->first();

        if (!$membership) {
            // Jika user tidak memiliki membership, buat check-in untuk hari ini
            $checkInDate = now();
        } else {
            // Jika user memiliki membership, buat check-in acak dalam rentang membership
            $startDate = Carbon::parse($membership->start_date);
            $endDate = Carbon::parse($membership->end_date);

            // Validasi bahwa startDate harus lebih awal dari endDate
            if ($startDate->greaterThanOrEqualTo($endDate)) {
                // Jika tidak valid, gunakan tanggal hari ini
                $checkInDate = now();
            } else {
                // Buat check-in dalam rentang yang valid
                $checkInDate = $this->faker->dateTimeBetween($startDate, $endDate);
            }
        }

        $checkInTime = Carbon::parse($checkInDate)->setTime(
            $this->faker->numberBetween(6, 22),
            $this->faker->numberBetween(0, 59),
            $this->faker->numberBetween(0, 59)
        );

        $checkOutTime = (clone $checkInTime)->addMinutes($this->faker->numberBetween(30, 180));

        return [
            'user_id' => $user->id,
            'check_in_time' => $checkInTime->format('Y-m-d H:i:s'),
            'check_out_time' => $checkOutTime->format('Y-m-d H:i:s'),
            'check_in_method' => $this->faker->randomElement(['manual', 'auto']),
        ];
    }
}
