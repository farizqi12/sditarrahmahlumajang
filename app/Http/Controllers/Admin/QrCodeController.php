<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Display QR code management page
     */
    public function index()
    {
        $users = User::with('role')->orderBy('name')->get();
        return view('admin.qr-codes.index', compact('users'));
    }

    /**
     * Generate QR code for user
     */
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Generate unique QR code
        $qrCode = $user->generateQrCode();
        
        // Generate QR code as SVG
        $qrCodeSvg = QrCode::format('svg')
                          ->size(300)
                          ->margin(10)
                          ->generate($qrCode);
        
        // Save QR code SVG
        $fileName = 'qr_codes/' . $user->id . '_' . time() . '.svg';
        Storage::disk('public')->put($fileName, $qrCodeSvg);
        
        // Update user with QR code path
        $user->update(['qr_code_path' => $fileName]);
        
        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil dibuat untuk ' . $user->name,
            'data' => [
                'qr_code' => $qrCode,
                'qr_code_path' => Storage::url($fileName),
                'user_name' => $user->name
            ]
        ]);
    }

    /**
     * Download QR code as PDF
     */
    public function downloadQrCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        if (!$user->qr_code) {
            return back()->with('error', 'QR Code belum dibuat untuk user ini');
        }

        // Generate QR code if not exists
        if (!$user->qr_code_path) {
            $qrCodeSvg = QrCode::format('svg')
                              ->size(300)
                              ->margin(10)
                              ->generate($user->qr_code);
            
            $fileName = 'qr_codes/' . $user->id . '_' . time() . '.svg';
            Storage::disk('public')->put($fileName, $qrCodeSvg);
            $user->update(['qr_code_path' => $fileName]);
        }

        return response()->download(Storage::disk('public')->path($user->qr_code_path));
    }

    /**
     * Bulk generate QR codes
     */
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $generated = 0;
        $errors = [];

        foreach ($request->user_ids as $userId) {
            try {
                $user = User::find($userId);
                
                if (!$user->qr_code) {
                    $qrCode = $user->generateQrCode();
                    
                    $qrCodeSvg = QrCode::format('svg')
                                      ->size(300)
                                      ->margin(10)
                                      ->generate($qrCode);
                    
                    $fileName = 'qr_codes/' . $user->id . '_' . time() . '.svg';
                    Storage::disk('public')->put($fileName, $qrCodeSvg);
                    
                    $user->update(['qr_code_path' => $fileName]);
                    $generated++;
                }
            } catch (\Exception $e) {
                $errors[] = 'Error untuk ' . $user->name . ': ' . $e->getMessage();
            }
        }

        $message = "Berhasil membuat {$generated} QR Code";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'generated' => $generated,
            'errors' => $errors
        ]);
    }

    /**
     * Show QR code preview
     */
    public function preview($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->qr_code) {
            return back()->with('error', 'QR Code belum dibuat untuk user ini');
        }

        return view('admin.qr-codes.preview', compact('user'));
    }

    /**
     * Print QR code for ID card
     */
    public function printQrCode($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->qr_code) {
            return back()->with('error', 'QR Code belum dibuat untuk user ini');
        }

        return view('admin.qr-codes.print', compact('user'));
    }
}
