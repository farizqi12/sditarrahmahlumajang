<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display the main reports page.
     * It shows a list of classes and the report for a selected class.
     */
    public function index(Request $request)
    {
        // In the original controller, the variable for all classes was $class.
        // Let's stick to that for compatibility with the existing view.
        $class = ClassModel::with('teacher.user')->where('is_active', true)->orderBy('name')->get();

        $currentClass = null;

        // If a class ID is provided in the request, load that class and its students
        if ($request->filled('class_id')) {
            $currentClass = ClassModel::with('enrollments.student.user', 'teacher.user')
                ->find($request->input('class_id'));
        }

        // The view will show the class selector and, if a class is selected,
        // it will display the list of enrolled students.
        // We pass $class (list of all classes) and $currentClass (the selected one with students).
        return view("admin.reports.reports", compact('class', 'currentClass'));
    }

    /**
     * Display the report for a specific class.
     * This is useful for direct links to a class report.
     */
    public function showClassReport(ClassModel $class)
    {
        // Fetch all active classes for the selector. The view expects this variable to be named 'class'.
        $allClasses = ClassModel::with('teacher.user')->where('is_active', true)->orderBy('name')->get();

        // Eager load the students and teacher for the given class
        $class->load('enrollments.student.user', 'teacher.user');

        // We reuse the main reports view, passing the necessary data.
        // The view gets 'class' (all classes) and 'currentClass' (the selected one).
        return view('admin.reports.reports', [
            'class' => $allClasses,
            'currentClass' => $class,
        ]);
    }
}
