<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiControllerKepsek extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $locations = $role->attendanceLocations;
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        return view('kepala_sekolah.absensi', compact('locations', 'attendance'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();

        // Cek apakah sudah check-in hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'location_id' => $request->location_id,
            'check_in' => now(),
            'status' => 'present',
        ]);

        return back()->with('success', 'Berhasil check-in.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', $today)
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum check-in atau sudah check-out.');
        }

        $attendance->update([
            'check_out' => now(),
        ]);

        return back()->with('success', 'Berhasil check-out.');
    }
}
