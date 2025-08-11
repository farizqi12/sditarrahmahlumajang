<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function __construct()
    {
        // Rate limiter untuk manajemen tahun ajaran
        $this->middleware('throttle.advanced:academic_year')->only(['store']); // 10 request per menit untuk operasi penting
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:academic_years,name',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'is_active'  => 'nullable|boolean',
        ]);

        try {
            $academicYear = DB::transaction(function () use ($request) {
                if ($request->boolean('is_active')) {
                    AcademicYear::where('is_active', true)->update(['is_active' => false]);
                }

                return AcademicYear::create([
                    'name'       => $request->name,
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                    'is_active'  => $request->boolean('is_active'),
                ]);
            });

            return response()->json([
                'success'      => true,
                'message'      => 'Tahun ajaran berhasil ditambahkan.',
                'academicYear' => $academicYear,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tahun ajaran: ' . $e->getMessage(),
            ], 500);
        }
    }
}
