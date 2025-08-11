<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiControllerKepsek extends Controller
{
    public function __construct()
    {
        // Rate limiter untuk absensi kepala sekolah
        $this->middleware('throttle:30,1')->only(['checkIn', 'checkOut']); // 30 request per menit
        $this->middleware('throttle:60,1')->only(['index']); // 60 request per menit untuk view
        $this->middleware('throttle:20,1')->only(['scan']); // 20 request per menit untuk scan QR
    }

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Get today's attendance for the current user
        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Get locations available for the user's role
        $locations = $user->role->attendanceLocations()->get();

        // Get attendance history for the current user
        $attendanceHistory = Attendance::where('user_id', $user->id)
            ->with('location')
            ->latest('date')
            ->paginate(10);

        return view('kepala_sekolah.absensi', compact('attendanceToday', 'locations', 'attendanceHistory'));
    }

    public function checkIn(Request $request)
    {
        if (Carbon::today()->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.'], 400);
        }
        $request->validate([
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $location = AttendanceLocation::find($request->location_id);

        // Server-side distance validation
        $distance = $this->haversineGreatCircleDistance(
            $request->latitude,
            $request->longitude,
            $location->latitude,
            $location->longitude
        );

        if ($distance > $location->radius_meter) {
            return response()->json(['success' => false, 'message' => 'Anda berada di luar jangkauan lokasi.'], 400);
        }

        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        // Check if user already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-in hari ini.'], 409);
        }

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
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'location_id' => 'required|exists:attendance_locations,id',
        ]);

        $user = Auth::user();
        $location = AttendanceLocation::find($request->location_id);

        // Server-side distance validation
        $distance = $this->haversineGreatCircleDistance(
            $request->latitude,
            $request->longitude,
            $location->latitude,
            $location->longitude
        );

        if ($distance > $location->radius_meter) {
            return response()->json(['success' => false, 'message' => 'Anda berada di luar jangkauan lokasi.'], 400);
        }

        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan check-in hari ini.'], 404);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-out hari ini.'], 409);
        }

        $attendance->update(['check_out' => $currentTime]);

        return response()->json(['success' => true, 'message' => 'Check-out berhasil!']);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @return float Distance between points in meters
     */
    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371000; // meters
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function scan(Request $request)
    {
        if (Carbon::today()->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.'], 400);
        }

        $request->validate([
            'location_id' => 'required|exists:attendance_locations,id',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $user = Auth::user();
        $location = AttendanceLocation::find($request->location_id);

        // Check if the user's role is allowed to access this location
        if (!$user->role->attendanceLocations->contains($location)) {
            return response()->json(['success' => false, 'message' => 'Anda tidak diizinkan untuk absen di lokasi ini.'], 403);
        }

        // Server-side distance validation
        $distance = $this->haversineGreatCircleDistance(
            $request->latitude,
            $request->longitude,
            $location->latitude,
            $location->longitude
        );

        if ($distance > $location->radius_meter) {
            return response()->json(['success' => false, 'message' => 'Anda berada di luar jangkauan lokasi.'], 400);
        }

        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');

        // Check if user already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            // If an attendance record exists and it's a check-in (check_out is null)
            if ($existingAttendance->check_in && !$existingAttendance->check_out) {
                // If the scanned location is different from the check-in location, prevent check-in
                if ($existingAttendance->location_id != $location->id) {
                    return response()->json(['success' => false, 'message' => 'Anda sudah check-in di lokasi lain hari ini.'], 400);
                }
                // Otherwise, it's a check-out
                $existingAttendance->update([
                    'check_out' => $currentTime,
                ]);
                return response()->json(['success' => true, 'message' => 'Check-out berhasil!']);
            } else if ($existingAttendance->check_in && $existingAttendance->check_out) {
                // Already checked in and out
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan check-in dan check-out hari ini.'], 409);
            }
        } else {
            // No existing attendance, perform check-in
            Attendance::create([
                'user_id'     => $user->id,
                'location_id' => $location->id,
                'date'        => $today,
                'check_in'    => $currentTime,
                'status'      => 'hadir',
            ]);
            return response()->json(['success' => true, 'message' => 'Check-in berhasil!']);
        }
    }
}
