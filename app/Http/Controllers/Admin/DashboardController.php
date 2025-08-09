<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

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
                // Ambil data untuk filter
                $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
                $classes = ClassModel::all();
                $subjects = Subject::all();

                // --- Kalkulasi Statistik Utama ---
                $averageSchoolGrade = Submission::avg('grade');
                $totalStudents = Student::count();
                $attendanceRate = DB::table('attendances')->where('status', 'present')->count() / (DB::table('attendances')->count() ?: 1) * 100;

                // --- Kalkulasi untuk Grafik ---
                $avgGradeByClass = ClassModel::withAvg('submissions', 'grade')->get()->pluck('submissions_avg_grade', 'name');
                $avgGradeBySubject = Subject::withAvg('submissions', 'grade')->get()->pluck('submissions_avg_grade', 'name');

                // --- Kalkulasi untuk Tabel Peringkat Siswa ---
                $topStudents = Student::withAvg('submissions', 'grade')->orderByDesc('submissions_avg_grade')->limit(5)->get();
                $bottomStudents = Student::withAvg('submissions', 'grade')->orderBy('submissions_avg_grade')->limit(5)->get();
                return view('kepala_sekolah.dashboard', compact(    
                    'user',             
                    'academicYears',
                    'classes',
                    'subjects',
                    'averageSchoolGrade',
                    'totalStudents',
                    'attendanceRate',
                    'avgGradeByClass',
                    'avgGradeBySubject',
                    'topStudents',
                    'bottomStudents'
                ));
            case 'guru':
                return view('guru.dashboard');
            case 'murid':
                return view('murid.dashboard');
            
        }
    }
}
