<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QrCodeScannerController extends Controller
{
    /**
     * Show QR code scanner interface
     */
    public function showScanner()
    {
        return view('scanner.index');
    }

    /**
     * Process QR code scan for attendance
     */
    public function scanQrCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
            'device_id' => 'nullable|string',
            'action' => 'required|in:checkin,checkout',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Find user by QR code
            $user = User::where('qr_code', $request->qr_code)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau user tidak ditemukan'
                ], 404);
            }

            $today = Carbon::today();
            $currentTime = Carbon::now();
            $scannerUser = Auth::user();

            // Check if attendance record exists for today
            $attendance = Attendance::where('user_id', $user->id)
                                  ->where('date', $today)
                                  ->first();

            if ($request->action === 'checkin') {
                return $this->processCheckIn($user, $attendance, $currentTime, $scannerUser, $request->device_id);
            } else {
                return $this->processCheckOut($user, $attendance, $currentTime, $scannerUser, $request->device_id);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process check-in
     */
    private function processCheckIn($user, $attendance, $currentTime, $scannerUser, $deviceId)
    {
        if ($attendance && $attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah melakukan check-in hari ini pada ' . $attendance->check_in
            ], 400);
        }

        if (!$attendance) {
            // Create new attendance record
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'scanned_by' => $scannerUser->id,
                'device_id' => $deviceId,
                'date' => $currentTime->toDateString(),
                'check_in' => $currentTime->toTimeString(),
                'status' => 'hadir'
            ]);
        } else {
            // Update existing record
            $attendance->update([
                'check_in' => $currentTime->toTimeString(),
                'scanned_by' => $scannerUser->id,
                'device_id' => $deviceId,
                'status' => 'hadir'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil untuk ' . $user->name,
            'data' => [
                'user_name' => $user->name,
                'check_in_time' => $currentTime->format('H:i:s'),
                'date' => $currentTime->format('Y-m-d'),
                'scanned_by' => $scannerUser->name
            ]
        ]);
    }

    /**
     * Process check-out
     */
    private function processCheckOut($user, $attendance, $currentTime, $scannerUser, $deviceId)
    {
        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' belum melakukan check-in hari ini'
            ], 400);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah melakukan check-out hari ini pada ' . $attendance->check_out
            ], 400);
        }

        $attendance->update([
            'check_out' => $currentTime->toTimeString(),
            'scanned_by' => $scannerUser->id,
            'device_id' => $deviceId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil untuk ' . $user->name,
            'data' => [
                'user_name' => $user->name,
                'check_in_time' => $attendance->check_in,
                'check_out_time' => $currentTime->format('H:i:s'),
                'date' => $currentTime->format('Y-m-d'),
                'scanned_by' => $scannerUser->name
            ]
        ]);
    }

    /**
     * Generate QR code for user
     */
    public function generateQrCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::find($request->user_id);
        $qrCode = $user->generateQrCode();

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dibuat',
            'data' => [
                'qr_code' => $qrCode,
                'user_name' => $user->name
            ]
        ]);
    }

    /**
     * Get attendance statistics
     */
    public function getAttendanceStats()
    {
        $today = Carbon::today();
        
        $stats = [
            'total_today' => Attendance::where('date', $today)->count(),
            'checked_in' => Attendance::where('date', $today)->whereNotNull('check_in')->count(),
            'checked_out' => Attendance::where('date', $today)->whereNotNull('check_out')->count(),
            'present' => Attendance::where('date', $today)->where('status', 'hadir')->count(),
            'absent' => Attendance::where('date', $today)->where('status', 'alpa')->count(),
            'sick' => Attendance::where('date', $today)->where('status', 'sakit')->count(),
            'permit' => Attendance::where('date', $today)->where('status', 'izin')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
