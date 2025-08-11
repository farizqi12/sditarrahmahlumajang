<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassModel::with(['teacher.user', 'academicYear']);

        // Default: show all. Filter to active if requested.
        if ($request->query('show') === 'active') {
            $query->where('is_active', true);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('name', 'like', $searchTerm);
        }

        $courses = $query->latest()->paginate(10)->withQueryString();
        $teachers = Teacher::with('user')->get();
        $academicYears = AcademicYear::all();

        return view('admin.courses', compact('courses', 'teachers', 'academicYears'));
    }

    public function toggleStatus(ClassModel $course)
    {
        $course->update(['is_active' => !$course->is_active]);

        $message = $course->is_active ? 'Kelas berhasil diaktifkan.' : 'Kelas berhasil dinonaktifkan.';

        return redirect()->route('admin.courses.index')->with('success', $message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'teacher_id'       => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        ClassModel::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, ClassModel $course)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'teacher_id'       => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassModel $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function manage(ClassModel $course)
    {
        $course->load(['enrollments.student.user', 'teacher.user', 'academicYear']);

        $enrolledStudentIds = $course->enrollments->pluck('student.id')->toArray();

        $students = Student::with(['user', 'enrollments.classModel'])->whereNotIn('id', $enrolledStudentIds)->get();

        return view('admin.courses.manage', compact('course', 'students'));
    }

    public function addStudents(Request $request, ClassModel $course)
    {
        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        foreach ($request->student_ids as $student_id) {
            // Check if the student is already enrolled
            $isEnrolled = Enrollment::where('class_id', $course->id)
                ->where('student_id', $student_id)
                ->exists();

            if (!$isEnrolled) {
                Enrollment::create([
                    'class_id'         => $course->id,
                    'student_id'       => $student_id,
                    'academic_year_id' => $course->academic_year_id,
                    'enrollment_date'  => now(),
                    'status'           => 'active',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function removeStudent(Request $request, ClassModel $course, Student $student)
    {
        $enrollment = Enrollment::where('class_id', $course->id)
            ->where('student_id', $student->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
        }

        return redirect()->back()->with('error', 'Siswa tidak ditemukan di kelas ini.');
    }
}
