<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role->name) {
            case 'admin':
                $totalUsers = User::count();
                $totalStudents = Student::count();
                $totalTeachers = Teacher::count();
                $totalClasses = ClassModel::count();
                $recentUsers = User::with('role')->latest()->take(5)->get();

                return view('admin.dashboard', compact(
                    'totalUsers',
                    'totalStudents',
                    'totalTeachers',
                    'totalClasses',
                    'recentUsers'
                ));
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
