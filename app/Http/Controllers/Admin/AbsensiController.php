<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;

class AbsensiController extends Controller
{
    public function __construct()
    {
        // Rate limiter untuk method yang sering diakses
        $this->middleware('throttle:30,1')->only(['checkIn', 'checkOut']); // 30 request per menit
        $this->middleware('throttle:60,1')->only(['index', 'storeLocation', 'destroyLocation']); // 60 request per menit
        $this->middleware('throttle:10,1')->only(['showQrCode']); // 10 request per menit untuk generate QR
    }

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

        $roles = Role::all();

        return view('admin.absensi', compact('attendanceToday', 'locations', 'attendanceHistory', 'roles'));
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

    public function storeLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.absensi.index')
                ->withErrors($validator)
                ->withInput();
        }

        $location = AttendanceLocation::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => $request->radius,
        ]);

        $qrCodePath = 'qrcodes/' . $location->id . '.png';
        QrCode::generate($location->id, storage_path('app/public/' . $qrCodePath));

        $location->update(['qrcode_path' => $qrCodePath]);

        $location->roles()->sync($request->roles);

        return redirect()->route('admin.absensi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function destroyLocation(AttendanceLocation $location)
    {
        $location->delete();
        return redirect()->route('admin.absensi.index')->with('success', 'Lokasi berhasil dihapus.');
    }

    public function showQrCode(AttendanceLocation $location)
    {
        $qrCodePath = storage_path('app/public/' . $location->qrcode_path);

        if (!file_exists($qrCodePath)) {
            abort(404);
        }

        $qrCodeImage = base64_encode(file_get_contents($qrCodePath));

        $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <title>QR Code</title>
                <style>
                    body { text-align: center; }
                    img { width: 300px; height: 300px; margin-top: 50px; }
                    h1 { font-family: sans-serif; }
                </style>
            </head>
            <body>
                <h1>QR Code for ' . $location->name . '</h1>
                <img src="data:image/png;base64,' . $qrCodeImage . '">
            </body>
            </html>
        ';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('qrcode_' . $location->id . '.pdf');
    }
}