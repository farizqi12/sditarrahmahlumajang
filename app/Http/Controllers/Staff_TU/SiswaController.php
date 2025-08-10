<?php

namespace App\Http\Controllers\Staff_TU;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        // Fetch users with the 'murid' role, along with their student details.
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'murid');
        })->with('student')->latest()->paginate(15);

        return view('staff_tu.siswa.index', compact('students'));
    }
}
