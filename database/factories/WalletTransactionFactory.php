<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['deposit', 'withdrawal', 'tabungan_in', 'tabungan_out', 'transfer_in', 'transfer_out']);
        $amount = $this->faker->randomFloat(2, 1000, 100000);
        $balanceBefore = $this->faker->randomFloat(2, 0, 1000000);
        $balanceAfter = $balanceBefore + ($type === 'deposit' || $type === 'tabungan_in' || $type === 'transfer_in' ? $amount : -$amount);

        return [
            'wallet_id' => \App\Models\Wallet::factory(),
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $this->faker->sentence(),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}