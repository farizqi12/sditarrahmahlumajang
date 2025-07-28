<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceLocation>
 */
class AttendanceLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'latitude' => $this->faker->latitude( -7.9, -8.1),
            'longitude' => $this->faker->longitude(112.9, 113.1),
            'radius_meter' => $this->faker->numberBetween(50, 200),
        ];
    }
}