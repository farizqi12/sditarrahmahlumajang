<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\Login;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;

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
    Route::resource('users', UserController::class)->names('users');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::post('academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
});

// Kepala Sekolah Routes
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepala_sekolah')->name('kepala_sekolah.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
