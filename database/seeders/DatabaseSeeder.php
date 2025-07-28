<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        \App\Models\Guru::factory(5)->create();
        
        \App\Models\MataPelajaran::factory(10)->create();

        \App\Models\Kelas::factory(3)->create();

        \App\Models\Siswa::factory(20)->create();

        \App\Models\JadwalPelajaran::factory(15)->create();
    }
}
