<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'harian');
        $data = $this->prepareFinancialData($period);

        return view('kepala_sekolah.laporan_keuangan', array_merge($data, ['period' => $period]));
    }

    public function getFinancialData(Request $request)
    {
        $period = $request->input('period', 'harian');
        $data = $this->prepareFinancialData($period);

        return response()->json($data);
    }

    private function prepareFinancialData(string $period)
    {
        // Data untuk Tabel
        $tabunganMasuk = WalletTransaction::where('type', 'deposit')
            ->where('status', 'accepted')
            ->with('wallet.user')
            ->latest()
            ->get();

        $tabunganKeluar = WalletTransaction::where('type', 'withdrawal')
            ->where('status', 'accepted')
            ->with('wallet.user')
            ->latest()
            ->get();

        // Data untuk Grafik
        $chartData = $this->getChartData($period);

        return [
            'tabunganMasuk' => $tabunganMasuk,
            'tabunganKeluar' => $tabunganKeluar,
            'chartData' => $chartData,
        ];
    }

    private function getChartData($period)
    {
        $selectClause = '';
        $groupByClause = '';
        $orderByClause = '';
        $now = Carbon::now();

        switch ($period) {
            case 'harian':
                $selectClause = "DAYNAME(created_at) as label, SUM(amount) as total";
                $groupByClause = "DAYOFWEEK(created_at), DAYNAME(created_at)";
                $orderByClause = "DAYOFWEEK(created_at)";
                $startDate = $now->startOfWeek()->format('Y-m-d H:i:s');
                $endDate = $now->endOfWeek()->format('Y-m-d H:i:s');
                break;
            case 'bulanan':
                $selectClause = "DATE_FORMAT(created_at, '%b') as label, SUM(amount) as total";
                $groupByClause = "YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')";
                $orderByClause = "YEAR(created_at), MONTH(created_at)";
                $startDate = $now->startOfYear()->format('Y-m-d H:i:s');
                $endDate = $now->endOfYear()->format('Y-m-d H:i:s');
                break;
            case 'tahunan':
                $selectClause = "YEAR(created_at) as label, SUM(amount) as total";
                $groupByClause = "YEAR(created_at)";
                $orderByClause = "YEAR(created_at)";
                $startDate = $now->copy()->subYears(4)->startOfYear()->format('Y-m-d H:i:s');
                $endDate = $now->endOfYear()->format('Y-m-d H:i:s');
                break;
        }

        $incomeQuery = WalletTransaction::select(DB::raw($selectClause))
            ->where('type', 'deposit')
            ->where('status', 'accepted')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupByClause))
            ->orderBy(DB::raw($orderByClause))
            ->get()
            ->pluck('total', 'label');

        $outcomeQuery = WalletTransaction::select(DB::raw($selectClause))
            ->where('type', 'withdrawal')
            ->where('status', 'accepted')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($groupByClause))
            ->orderBy(DB::raw($orderByClause))
            ->get()
            ->pluck('total', 'label');

        $labels = $incomeQuery->keys()->merge($outcomeQuery->keys())->unique()->sort();

        $incomeData = $labels->map(function ($label) use ($incomeQuery) {
            return $incomeQuery->get($label, 0);
        });

        $outcomeData = $labels->map(function ($label) use ($outcomeQuery) {
            return $outcomeQuery->get($label, 0);
        });

        return [
            'labels' => $labels->values(),
            'income' => $incomeData->values(),
            'outcome' => $outcomeData->values(),
        ];
    }
}
