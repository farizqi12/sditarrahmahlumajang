<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
use App\Models\Kelas;

class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'nama' => $this->faker->name(),
            'nis' => $this->faker->unique()->numerify('##########'),
            'alamat' => $this->faker->address(),
            'tanggal_lahir' => $this->faker->date(),
            'nama_orang_tua' => $this->faker->name(),
            'telepon_orang_tua' => $this->faker->phoneNumber(),
        ];
    }
}
