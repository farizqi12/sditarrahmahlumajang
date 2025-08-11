<?php

namespace App\Http\Controllers\Staff_TU;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Rate limiter untuk dashboard staff TU
        $this->middleware('throttle:60,1')->only(['index']); // 60 request per menit untuk dashboard
    }

    public function index()
    {
        // Data for Stats Cards
        $totalStudents = Student::count();
        $pendingTransactions = WalletTransaction::where('status', 'pending')->count();
        $todaysIncome = WalletTransaction::where('status', 'completed')
            ->where('type', 'credit')
            ->whereDate('created_at', today())
            ->sum('amount');

        // Data for Recent Transactions Table
        $recentTransactions = WalletTransaction::with('wallet.user')->latest()->take(5)->get();

        return view('staff_tu.dashboard', compact(
            'totalStudents',
            'pendingTransactions',
            'todaysIncome',
            'recentTransactions'
        ));
    }
}
