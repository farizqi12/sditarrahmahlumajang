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
    //admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/courses', [CoursesController::class, 'index'])->name('admin.courses');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/reports', [ReportsController::class, 'index'])->name('admin.reports');
    //kepala sekolah
    Route::get('/kepala_sekolah/dashboard', [DashboardController::class, 'index'])->name('kepala_sekolah.dashboard');
    //guru
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
    //murid
    Route::get('/murid/dashboard', [DashboardController::class, 'index'])->name('murid.dashboard');
    //staff tu
    Route::get('/staff_tu/dashboard', [DashboardController::class, 'index'])->name('staff_tu.dashboard');
});
