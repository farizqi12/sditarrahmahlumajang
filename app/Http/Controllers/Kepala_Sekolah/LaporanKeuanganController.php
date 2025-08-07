<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index()
    {
        // Logic to fetch financial report data will go here
        return view('kepala_sekolah.laporan_keuangan');
    }
}
