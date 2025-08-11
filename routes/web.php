<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Login;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Kepala_Sekolah\AbsensiControllerKepsek;
use App\Http\Controllers\Kepala_Sekolah\LaporanAkademikController;
use App\Http\Controllers\Kepala_Sekolah\LaporanKeuanganController;
use App\Http\Controllers\Staff_TU\AbsensiController as StaffTUAbsensiController;
use App\Http\Controllers\Staff_TU\DashboardController as StaffTUDashboardController;
use App\Http\Controllers\Staff_TU\SiswaController as StaffTUSiswaController;
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
    Route::resource('courses', CoursesController::class);
    Route::patch('courses/{course}/toggle-status', [CoursesController::class, 'toggleStatus'])->name('courses.toggleStatus');
    Route::get('courses/{course}/manage', [CoursesController::class, 'manage'])->name('courses.manage');
    Route::post('courses/{course}/add-student', [CoursesController::class, 'addStudent'])->name('courses.addStudent');
    Route::delete('courses/{course}/remove-student/{student}', [CoursesController::class, 'removeStudent'])->name('courses.removeStudent');
    Route::resource('users', UserController::class)->names('users');
    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('reports/{class}', [ReportsController::class, 'showClassReport'])->name('reports.class');
    Route::post('academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
    Route::resource('tabungan', WalletController::class)->names('tabungan')->except(['create', 'edit', 'update', 'destroy']);
    Route::post('tabungan/{user}', [WalletController::class, 'store'])->name('tabungan.store');
    Route::get('tabungan/pending', [WalletController::class, 'pending'])->name('tabungan.pending');
    Route::patch('tabungan/pending/{transaction}/accept', [WalletController::class, 'acceptTransaction'])->name('tabungan.accept');
    Route::post('tabungan/pending/{transaction}/reject', [WalletController::class, 'rejectTransaction'])->name('tabungan.reject');
    Route::resource('absensi', AbsensiController::class)->names('absensi');
    Route::post('/absensi/checkin', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/checkout', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
    Route::post('absensi/locations', [AbsensiController::class, 'storeLocation'])->name('absensi.locations.store');
    Route::delete('absensi/locations/{location}', [AbsensiController::class, 'destroyLocation'])->name('absensi.locations.destroy');
});

// Kepala Sekolah Routes
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepala_sekolah')->name('kepala_sekolah.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/absensi', AbsensiControllerKepsek::class)->names('absensi');
    Route::post('/absensi/checkin', [AbsensiControllerKepsek::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/checkout', [AbsensiControllerKepsek::class, 'checkOut'])->name('absensi.checkout');
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan_keuangan');
    Route::get('/laporan-keuangan/data', [LaporanKeuanganController::class, 'getFinancialData'])->name('laporan_keuangan.data');
    Route::resource('laporan-keuangan', LaporanKeuanganController::class)->names('laporan_keuangan');
}); 

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Murid Routes
Route::middleware(['auth', 'role:murid'])->prefix('murid')->name('murid.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Staff TU Routes
Route::middleware(['auth', 'role:staff_tu'])->prefix('staff_tu')->name('staff_tu.')->group(function () {
    Route::get('/dashboard', [StaffTUDashboardController::class, 'index'])->name('dashboard');
    Route::get('/absensi', [StaffTUAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/checkin', [StaffTUAbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::post('/absensi/checkout', [StaffTUAbsensiController::class, 'checkOut'])->name('absensi.checkout');
    Route::get('/siswa', [StaffTUSiswaController::class, 'index'])->name('siswa.index');
});
