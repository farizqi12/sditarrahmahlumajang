<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'location_id' => \App\Models\AttendanceLocation::factory(),
            'date' => $this->faker->date(),
            'check_in' => $this->faker->time(),
            'check_out' => $this->faker->time(),
            'status' => $this->faker->randomElement(['hadir', 'sakit', 'izin', 'alpa']),
        ];
    }
}