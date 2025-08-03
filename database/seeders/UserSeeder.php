<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil peran (roles) dari database
        $roles = Role::pluck('id', 'name');

        // Data pengguna (users)
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'test@test.com',
                'password' => Hash::make('test'),
                'role_name' => 'admin',
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'kepala_sekolah',
            ],
            [
                'name' => 'Guru A',
                'email' => 'guru_a@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'guru',
                'nip' => '1234567890',
            ],
            [
                'name' => 'Murid B',
                'email' => 'murid_b@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'murid',
                'nis' => '1001',
                'class' => '1A',
            ],
            [
                'name' => 'Staff TU',
                'email' => 'staff_tu@example.com',
                'password' => Hash::make('password'),
                'role_name' => 'staff_tu',
            ],
        ];

        foreach ($users as $userData) {
            // Buat atau update user berdasarkan email
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role_id' => $roles[$userData['role_name']],
                ]
            );

            // Jika peran adalah guru, buat atau update data guru
            if ($userData['role_name'] === 'guru') {
                Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    ['nip' => $userData['nip']]
                );
            }

            // Jika peran adalah murid, buat atau update data siswa
            if ($userData['role_name'] === 'murid') {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nis' => $userData['nis'],
                        'class' => $userData['class'],
                    ]
                );
            }
        }
    }
}
