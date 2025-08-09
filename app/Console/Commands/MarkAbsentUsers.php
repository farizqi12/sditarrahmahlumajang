<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class MarkAbsentUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users who did not check in on a weekday as absent (alpa)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();

        // Hanya berjalan di hari kerja
        if ($today->isWeekend()) {
            $this->info('Skipping attendance check on a weekend.');
            return 0;
        }

        $this->info('Running attendance check for ' . $today->toDateString());

        // Dapatkan semua user yang wajib absen (bukan admin)
        $usersToCheck = User::whereHas('role', function ($query) {
            $query->where('name', '!=', 'admin');
        })->get();

        // Dapatkan ID user yang sudah punya catatan absensi hari ini
        $presentUserIds = Attendance::where('date', $today)->pluck('user_id')->toArray();

        $absentCount = 0;

        foreach ($usersToCheck as $user) {
            if (!in_array($user->id, $presentUserIds)) {
                // Jika user tidak ada di daftar hadir, tandai sebagai alpa
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'status' => 'alpa',
                    'check_in' => null,
                    'check_out' => null,
                    // location_id bisa null karena mereka tidak check-in di mana pun
                ]);
                $this->warn('User ' . $user->name . ' (ID: ' . $user->id . ') marked as absent.');
                $absentCount++;
            }
        }

        $this->info('Finished. Marked ' . $absentCount . ' user(s) as absent.');
        return 0;
    }
}
