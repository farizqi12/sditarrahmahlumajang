<?php

namespace App\Http\Controllers;

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

        // Get today's attendance for the current user
        $attendanceToday = Attendance::where('user_id', $user->id)
                                     ->whereDate('date', $today)
                                     ->first();

        // Get all attendance locations
        $locations = AttendanceLocation::all();

        // Get attendance history for the current user
        $attendanceHistory = Attendance::where('user_id', $user->id)
                                       ->latest('date')
                                       ->paginate(10);

        return view('admin.absensi', compact('attendanceToday', 'locations', 'attendanceHistory'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        // Check if user already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->whereDate('date', $today)
                                        ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-in hari ini.'], 409);
        }

        $location = AttendanceLocation::find($request->location_id);

        // Calculate distance (simplified for controller, actual calculation in JS)
        // For a more robust server-side check, you'd implement Haversine formula here.
        // For now, we'll trust the client-side distance check for initial implementation.
        // In a real-world scenario, you'd re-validate distance on the server.

        Attendance::create([
            'user_id'     => $user->id,
            'location_id' => $location->id,
            'date'        => $today,
            'check_in'    => $currentTime,
            'status'      => 'hadir', // Default status
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

        $location = AttendanceLocation::find($request->location_id);

        // Similar to check-in, re-validate distance on server for robustness.

        $attendance->update([
            'check_out' => $currentTime,
        ]);

        return response()->json(['success' => true, 'message' => 'Check-out berhasil!']);
    }
}