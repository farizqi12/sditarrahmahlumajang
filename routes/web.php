<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::get('/login', [Login::class, 'showLoginForm'])->name('login');
Route::post('/login', [Login::class, 'login']);
Route::post('/logout', [Login::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/courses', [CoursesController::class, 'index'])->name('courses');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    });

    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Kepala Sekolah Routes
    Route::get('/kepala_sekolah/dashboard', [DashboardController::class, 'index'])->name('kepala_sekolah.dashboard');
    // Guru Routes
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');

    // Murid Routes
    Route::get('/murid/dashboard', [DashboardController::class, 'index'])->name('murid.dashboard');

    // Staff TU Routes
    Route::get('/staff_tu/dashboard', [DashboardController::class, 'index'])->name('staff_tu.dashboard');
});
