<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = DB::table('roles')->pluck('id', 'name');

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['admin'],
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['kepala_sekolah'],
            ],
            [
                'name' => 'Guru A',
                'email' => 'guru_a@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['guru'],
            ],
            [
                'name' => 'Murid B',
                'email' => 'murid_b@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['murid'],
            ],
            [
                'name' => 'Staff TU',
                'email' => 'staff_tu@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_tu'],
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore($user);
        }
    }
}
