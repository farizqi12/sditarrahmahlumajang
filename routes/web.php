<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Login;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\QrCodeScannerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    // This assumes the user model has a 'role' attribute with the role name as a string.
    // e.g., 'admin', 'guru', etc.
    // If 'role' is a relationship, you might need to use $user->role->name
    $role = $user->role->name;

    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'kepala_sekolah':
            return redirect()->route('kepala_sekolah.dashboard');
        case 'guru':
            return redirect()->route('guru.dashboard');
        case 'murid':
            return redirect()->route('murid.dashboard');
        case 'staff_tu':
            return redirect()->route('staff_tu.dashboard');
        default:
            // If role is not recognized, log out and redirect to login
            Auth::logout();
            return redirect()->route('login')->with('error', 'Peran tidak valid.');
    }
});

// Authentication routes
Route::get('/login', [Login::class, 'showLoginForm'])->name('login');
Route::post('/login', [Login::class, 'login']);
Route::post('/logout', [Login::class, 'logout'])->name('logout');


// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', UserController::class)->names('users');
    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('reports/{class}', [ReportsController::class, 'showClassReport'])->name('reports.class');
    Route::get('reports/attendance', [ReportsController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('reports/attendance/export', [ReportsController::class, 'exportAttendanceReport'])->name('reports.attendance.export');
    Route::get('reports/attendance/export-user', [ReportsController::class, 'exportUserAttendanceReport'])->name('reports.attendance.export-user');
    
    
    Route::resource('absensi', AbsensiController::class)->names('absensi');
    Route::post('/absensi/checkin', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/checkout', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
    Route::post('absensi/locations', [AbsensiController::class, 'storeLocation'])->name('absensi.locations.store');
    Route::delete('absensi/locations/{location}', [AbsensiController::class, 'destroyLocation'])->name('absensi.locations.destroy');
    Route::get('absensi/locations/{location}/qrcode', [AbsensiController::class, 'showQrCode'])->name('absensi.locations.qrcode');
    
    // QR Code Management
    Route::get('qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');
    Route::post('qr-codes/generate', [QrCodeController::class, 'generateQrCode'])->name('qr-codes.generate');
    Route::post('qr-codes/bulk-generate', [QrCodeController::class, 'bulkGenerate'])->name('qr-codes.bulk-generate');
    Route::get('qr-codes/download', [QrCodeController::class, 'downloadQrCode'])->name('qr-codes.download');
    Route::get('qr-codes/{user}/preview', [QrCodeController::class, 'preview'])->name('qr-codes.preview');
    Route::get('qr-codes/{user}/print', [QrCodeController::class, 'printQrCode'])->name('qr-codes.print');

    // QR Code Scanner Routes
    Route::get('/scanner', [QrCodeScannerController::class, 'showScanner'])->name('scanner.index');
    Route::post('/scanner/scan', [QrCodeScannerController::class, 'scanQrCode'])->name('scanner.scan');
    Route::get('/scanner/stats', [QrCodeScannerController::class, 'getAttendanceStats'])->name('scanner.stats');
});


