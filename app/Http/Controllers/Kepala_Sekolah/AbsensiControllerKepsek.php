<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiControllerKepsek extends Controller
{
    public function index()
    {
        return view('kepala_sekolah.absensi');
    }
}
