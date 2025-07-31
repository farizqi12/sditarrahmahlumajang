<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = ClassModel::with(['teacher.user', 'academicYear'])->latest()->paginate(10);
        $teachers = Teacher::with('user')->get();
        $academicYears = AcademicYear::where('is_active', true)->get();
        return view('admin.courses', compact('courses', 'teachers', 'academicYears'));
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
