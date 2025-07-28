<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\DashboardController;
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
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/kepala_sekolah/dashboard', [DashboardController::class, 'index'])->name('kepala_sekolah.dashboard');
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/murid/dashboard', [DashboardController::class, 'index'])->name('murid.dashboard');
    Route::get('/staff_tu/dashboard', [DashboardController::class, 'index'])->name('staff_tu.dashboard');
});
