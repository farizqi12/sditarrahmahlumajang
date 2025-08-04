<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class PendingTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Ambil 3 murid secara acak untuk dibuatkan transaksi pending
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'murid');
        })->inRandomOrder()->limit(3)->get();

        // Ambil user admin pertama untuk digunakan sebagai created_by
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first();

        foreach ($students as $student) {
            // Pastikan murid memiliki wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $student->id],
                ['balance' => 0]
            );

            // Buat satu transaksi deposit dengan status pending
            WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => 'deposit',
                'amount'         => rand(5000, 25000), // Jumlah acak
                'balance_before' => 0, // Saldo belum berubah
                'balance_after'  => 0, // Saldo belum berubah
                'description'    => 'Permintaan setoran dari murid',
                'status'         => 'pending', // Status pending
                'created_by'     => $admin->id, // Diasumsikan admin yang memproses
            ]);
        }
    }
}
