<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'kepala_sekolah'],
            ['name' => 'guru'],
            ['name' => 'murid'],
            ['name' => 'staff_tu'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
    }
}
