<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MuridWithPendingTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $muridRole = Role::where('name', 'murid')->first();

        if (!$muridRole) {
            $this->command->error("Role 'murid' not found. Please seed the roles table first.");
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            // 1. Create User
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role_id' => $muridRole->id,
            ]);

            // 2. Create Student Profile
            $currentYear = date('Y');
            $lastStudent = Student::where('nis', 'like', $currentYear . '%')->latest('nis')->first();
            $nextSequence = $lastStudent ? ((int) substr($lastStudent->nis, 4)) + 1 : 1;
            $newNis = $currentYear . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            $user->student()->create([
                'nis'   => $newNis,
                'class' => 'Unassigned'
            ]);

            // 3. Create Wallet
            $wallet = $user->wallet()->create(['balance' => 50000]); // Start with some balance

            // 4. Create Pending Deposit Transaction
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => $faker->numberBetween(10000, 50000),
                'status' => 'pending',
                'description' => 'Setoran tabungan awal.',
                'balance_before' => $wallet->balance,
                'balance_after' => null,
            ]);

            // 5. Create Pending Withdrawal Transaction
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => $faker->numberBetween(5000, 20000),
                'status' => 'pending',
                'description' => 'Penarikan sebagian tabungan.',
                'balance_before' => $wallet->balance,
                'balance_after' => null,
            ]);
        }

        $this->command->info('Successfully seeded 5 new student users with pending transactions.');
    }
}
