<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiControllerKepsek
{
    public function index()
    {
        return view('kepala_sekolah.absensi');
    }
}
