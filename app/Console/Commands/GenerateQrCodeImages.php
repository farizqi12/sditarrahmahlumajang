<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCodeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:generate-images {--user-id= : Generate for specific user ID} {--all : Generate for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR code images for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $all = $this->option('all');

        if ($userId) {
            $this->generateForUser($userId);
        } elseif ($all) {
            $this->generateForAllUsers();
        } else {
            $this->error('Please specify --user-id or --all option');
            return 1;
        }

        return 0;
    }

    private function generateForUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        if (!$user->qr_code) {
            $this->error("User {$user->name} doesn't have QR code generated");
            return;
        }

        $this->generateQrCodeImage($user);
        $this->info("QR code image generated for user: {$user->name}");
    }

    private function generateForAllUsers()
    {
        $users = User::whereNotNull('qr_code')->get();
        
        if ($users->isEmpty()) {
            $this->error('No users with QR codes found');
            return;
        }

        $this->info("Generating QR code images for {$users->count()} users...");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $this->generateQrCodeImage($user);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All QR code images generated successfully!');
    }

    private function generateQrCodeImage($user)
    {
        // Generate QR code as SVG
        $qrCodeSvg = QrCode::format('svg')
                          ->size(300)
                          ->margin(10)
                          ->generate($user->qr_code);
        
        // Save QR code SVG
        $fileName = 'qr_codes/' . $user->id . '_' . time() . '.svg';
        Storage::disk('public')->put($fileName, $qrCodeSvg);
        
        // Update user with QR code path
        $user->update(['qr_code_path' => $fileName]);
    }
}
