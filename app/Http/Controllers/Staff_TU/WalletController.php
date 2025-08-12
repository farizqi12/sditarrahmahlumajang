<?php

namespace App\Http\Controllers\Staff_TU;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function __construct()
    {
        // Rate limiter serupa dengan admin
        $this->middleware('throttle:20,1')->only(['store', 'acceptTransaction', 'rejectTransaction']);
        $this->middleware('throttle:60,1')->only(['index', 'show', 'pending']);
    }

    /**
     * Menampilkan daftar dompet pengguna serta transaksi pending.
     */
    public function index(Request $request)
    {
        $userQuery = User::with(['wallet.transactions', 'student.enrollments.classModel'])
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['murid', 'guru']);
            });

        $pendingQuery = WalletTransaction::where('status', 'pending')
            ->with(['wallet.user.student.enrollments.classModel', 'wallet.user.role']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';

            $userQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });

            $pendingQuery->whereHas('wallet.user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        $users = $userQuery->latest()->get();
        $pendingTransactions = $pendingQuery->latest()->get();

        return view('staff_tu.wallet.index', compact('users', 'pendingTransactions'));
    }

    /**
     * Menampilkan riwayat transaksi untuk pengguna tertentu.
     */
    public function show(User $user)
    {
        $user->load('wallet.transactions');
        $transactions = $user->wallet ? $user->wallet->transactions()->latest()->paginate(10) : collect();

        return view('staff_tu.wallet.show', compact('user', 'transactions'));
    }

    /**
     * Menyimpan transaksi baru (setoran atau penarikan).
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'type'        => 'required|in:deposit,withdrawal',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        }

        if ($request->type === 'withdrawal' && $request->amount > $wallet->balance) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        DB::transaction(function () use ($request, $wallet) {
            $balance_before = $wallet->balance;

            $newBalance = $request->type === 'deposit'
                ? $wallet->balance + $request->amount
                : $wallet->balance - $request->amount;

            WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => $request->type,
                'amount'         => $request->amount,
                'balance_before' => $balance_before,
                'balance_after'  => $newBalance,
                'description'    => $request->description,
                'status'         => 'accepted',
                'created_by'     => Auth::id(),
            ]);

            $wallet->update(['balance' => $newBalance]);
        });

        return redirect()->route('staff_tu.tabungan.show', $user->id)->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan daftar transaksi yang masih pending.
     */
    public function pending(Request $request)
    {
        $transactions = WalletTransaction::with('wallet.user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('staff_tu.wallet.pending', compact('transactions'));
    }

    /**
     * Menerima transaksi yang pending.
     */
    public function acceptTransaction(WalletTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($transaction) {
                $wallet = $transaction->wallet;
                $balance_before = $wallet->balance;

                if (in_array($transaction->type, ['deposit', 'transfer_in'])) {
                    $newBalance = $wallet->balance + $transaction->amount;
                } else { // withdrawal, transfer_out
                    if ($transaction->amount > $wallet->balance) {
                        throw new \Exception('Saldo tidak mencukupi untuk melakukan transaksi ini.');
                    }
                    $newBalance = $wallet->balance - $transaction->amount;
                }

                $wallet->update(['balance' => $newBalance]);

                $transaction->update([
                    'status'         => 'accepted',
                    'balance_before' => $balance_before,
                    'balance_after'  => $newBalance,
                ]);
            });
        } catch (\Exception $e) {
            $transaction->update(['status' => 'rejected', 'description' => ($transaction->description ? $transaction->description . ' - ' : '') . 'Ditolak sistem: ' . $e->getMessage()]);
            return redirect()->route('staff_tu.tabungan.index')->with('error', 'Gagal menerima transaksi: ' . $e->getMessage());
        }

        return redirect()->route('staff_tu.tabungan.index')->with('success', 'Transaksi berhasil diterima.');
    }

    /**
     * Menolak transaksi yang pending.
     */
    public function rejectTransaction(Request $request, WalletTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $request->validate(['rejection_reason' => 'nullable|string|max:255']);

        $description = 'Ditolak oleh staff TU.';
        if ($request->filled('rejection_reason')) {
            $description = 'Alasan ditolak: ' . $request->rejection_reason;
        }

        $transaction->update([
            'status' => 'rejected',
            'description' => ($transaction->description ? $transaction->description . ' - ' : '') . $description
        ]);

        return redirect()->route('staff_tu.tabungan.index')->with('success', 'Transaksi berhasil ditolak.');
    }
}


