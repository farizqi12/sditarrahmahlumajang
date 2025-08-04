<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang memiliki peran 'murid' atau 'guru'
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['murid', 'guru']);
        })->get();

        foreach ($users as $user) {
            // Buat wallet untuk setiap user jika belum ada
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            // Hanya buat transaksi jika wallet baru dibuat (balance awal 0)
            if ($wallet->wasRecentlyCreated) {
                // Buat 2-5 transaksi deposit acak untuk setiap user
                $numberOfTransactions = rand(2, 5);
                $currentBalance = 0;

                for ($i = 0; $i < $numberOfTransactions; $i++) {
                    $amount = rand(10000, 50000); // Jumlah acak antara 10,000 dan 50,000
                    $balance_before = $currentBalance;
                    $currentBalance += $amount;
                    $balance_after = $currentBalance;

                    WalletTransaction::create([
                        'wallet_id'      => $wallet->id,
                        'type'           => 'deposit',
                        'amount'         => $amount,
                        'balance_before' => $balance_before,
                        'balance_after'  => $currentBalance,
                        'description'    => 'Setoran awal oleh seeder',
                        'status'         => 'accepted',
                        'created_by'     => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first()->id, // Diasumsikan admin yang melakukan
                    ]);
                }

                // Update saldo akhir di wallet
                $wallet->update(['balance' => $currentBalance]);
            }
        }
    }
}
