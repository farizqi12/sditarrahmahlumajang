<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanAkademikController extends Controller
{
    public function index(Request $request)
    {
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

        return view('kepala_sekolah.laporan_akademik', compact(
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
    }
}
