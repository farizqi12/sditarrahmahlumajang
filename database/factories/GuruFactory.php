<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
use App\Models\User;

class GuruFactory extends Factory
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
            'nama' => $this->faker->name(),
            'nip' => $this->faker->unique()->numerify('##################'),
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'tanggal_lahir' => $this->faker->date(),
        ];
    }
}
