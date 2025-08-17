<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;


class ReportsController extends Controller
{
    public function __construct()
    {
        // Rate limiter untuk laporan
        $this->middleware('throttle:30,1')->only(['index', 'showClassReport', 'attendanceReport', 'exportAttendanceReport', 'exportUserAttendanceReport']); // 30 request per menit untuk generate laporan
    }

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

    public function attendanceReport(Request $request)
    {
        $attendances = Attendance::with(['user', 'scannedBy'])->latest()->paginate(15);
        $users = User::all();

        return view('admin.reports.attendance', compact('attendances', 'users'));
    }

    public function exportAttendanceReport(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $attendances = Attendance::with(['user', 'scannedBy'])
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->get();

        $pdf = PDF::loadView('admin.reports.attendance_pdf', compact('attendances', 'month'));
        return $pdf->download('laporan-absensi-' . $month . '.pdf');
    }

    public function exportUserAttendanceReport(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $userId = $request->input('user_id');

        $user = User::findOrFail($userId);

        $attendances = Attendance::with(['user', 'scannedBy'])
            ->where('user_id', $userId)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->get();

        $pdf = PDF::loadView('admin.reports.attendance_pdf', compact('attendances', 'month', 'user'));
        return $pdf->download('laporan-absensi-' . $user->name . '-' . $month . '.pdf');
    }
}