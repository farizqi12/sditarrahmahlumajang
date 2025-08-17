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

            if (!$attendance) {
                // Create new attendance record for check-in
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'scanned_by' => $scannerUser->id,
                    'date' => $currentTime->toDateString(),
                    'check_in' => $currentTime->toTimeString(),
                    'status' => 'hadir'
                ]);

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
            } elseif (!$attendance->check_out) {
                // Update existing record for check-out
                $attendance->update([
                    'check_out' => $currentTime->toTimeString(),
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
            } else {
                // User has already checked in and out
                return response()->json([
                    'success' => false,
                    'message' => $user->name . ' sudah melakukan check-in dan check-out hari ini.'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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