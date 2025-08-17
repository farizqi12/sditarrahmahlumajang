<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNull('qr_code')->get();
        
        foreach ($users as $user) {
            $user->generateQrCode();
            $this->command->info("QR Code generated for user: {$user->name}");
        }
        
        $this->command->info("Total QR codes generated: " . $users->count());
    }
}
