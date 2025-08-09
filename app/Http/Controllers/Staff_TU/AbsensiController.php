<?php

namespace App\Http\Controllers\Staff_TU;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendanceToday = Attendance::where('user_id', $user->id)
                                     ->whereDate('date', $today)
                                     ->first();

        $locations = AttendanceLocation::whereHas('roles', function ($query) use ($user) {
            $query->where('role_id', $user->role_id);
        })->get();

        $attendanceHistory = Attendance::where('user_id', $user->id)
                                       ->latest('date')
                                       ->paginate(10);

        return view('staff_tu.absensi', compact('attendanceToday', 'locations', 'attendanceHistory'));
    }

    public function checkIn(Request $request)
    {
        if (Carbon::today()->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.'], 400);
        }
        
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->whereDate('date', $today)
                                        ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-in hari ini.'], 409);
        }

        $location = AttendanceLocation::find($request->location_id);

        Attendance::create([
            'user_id'     => $user->id,
            'location_id' => $location->id,
            'date'        => $today,
            'check_in'    => $currentTime,
            'status'      => 'hadir',
        ]);

        return response()->json(['success' => true, 'message' => 'Check-in berhasil!']);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', $today)
                                ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan check-in hari ini.'], 404);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-out hari ini.'], 409);
        }

        $attendance->update([
            'check_out' => $currentTime,
        ]);

        return response()->json(['success' => true, 'message' => 'Check-out berhasil!']);
    }
}
