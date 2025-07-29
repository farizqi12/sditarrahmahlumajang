<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role->name) {
            case 'admin':
                return view('admin.dashboard');
            case 'kepala_sekolah':
                return view('kepala_sekolah.dashboard');
            case 'guru':
                return view('guru.dashboard');
            case 'murid':
                return view('murid.dashboard');
            case 'staff_tu':
                return view('staff_tu.dashboard');
        }
    }
}