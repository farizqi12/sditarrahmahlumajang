<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalPelajaran>
 */
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;

class JadwalPelajaranFactory extends Factory
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
            'guru_id' => Guru::factory(),
            'mata_pelajaran_id' => MataPelajaran::factory(),
            'hari' => $this->faker->dayOfWeek(),
            'jam_mulai' => $this->faker->time('H:i:s'),
            'jam_selesai' => $this->faker->time('H:i:s'),
        ];
    }
}
