<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'SD IT Ar Rahmah Lumajang',
                'latitude' => -8.133333,
                'longitude' => 113.216667,
                'radius_meter' => 100,
            ],
            // Tambahkan lokasi lain jika diperlukan
        ];

        foreach ($locations as $location) {
            DB::table('attendance_locations')->insertOrIgnore($location);
        }
    }
}
