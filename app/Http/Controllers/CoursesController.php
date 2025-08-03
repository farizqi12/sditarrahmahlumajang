<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

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
}
