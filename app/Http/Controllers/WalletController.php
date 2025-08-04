<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Menampilkan daftar semua dompet pengguna (murid dan guru).
     */
    public function index(Request $request)
    {
        $query = User::with('wallet.transactions')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['murid', 'guru']);
            });

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->latest()->get(); // get all untuk tabel pertama

        $pendingTransactions = WalletTransaction::where('status', 'pending')
            ->with('wallet.user')
            ->latest()
            ->get(); // untuk tabel kedua

        return view('admin.wallet.index', compact('users', 'pendingTransactions'));
    }


    /**
     * Menampilkan riwayat transaksi untuk pengguna tertentu.
     */
    public function show(User $user)
    {
        $user->load('wallet.transactions');
        $transactions = $user->wallet ? $user->wallet->transactions()->latest()->paginate(10) : collect();

        return view('admin.wallet.show', compact('user', 'transactions'));
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

        // Jika pengguna belum punya wallet, buatkan
        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        }

        // Validasi jika penarikan melebihi saldo
        if ($request->type === 'withdrawal' && $request->amount > $wallet->balance) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::transaction(function () use ($request, $wallet) {
            $balance_before = $wallet->balance;

            if ($request->type === 'deposit') {
                $newBalance = $wallet->balance + $request->amount;
            } else { // withdrawal
                $newBalance = $wallet->balance - $request->amount;
            }

            // Buat catatan transaksi
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

            // Update saldo di wallet
            $wallet->update(['balance' => $newBalance]);
        });

        return redirect()->route('admin.tabungan.show', $user->id)->with('success', 'Transaksi berhasil disimpan.');
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

        return view('admin.wallet.pending', compact('transactions'));
    }

    /**
     * Menerima transaksi yang pending.
     */
    public function acceptTransaction(WalletTransaction $transaction)
    {
        // Pastikan transaksi masih pending
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($transaction) {
                $wallet = $transaction->wallet;
                $balance_before = $wallet->balance;

                if (in_array($transaction->type, ['deposit', 'tabungan_in', 'transfer_in'])) {
                    $newBalance = $wallet->balance + $transaction->amount;
                } else { // withdrawal, tabungan_out, transfer_out
                    // Validasi saldo saat approval
                    if ($transaction->amount > $wallet->balance) {
                        // Batalkan transaksi dan lempar exception
                        throw new \Exception('Saldo tidak mencukupi untuk melakukan transaksi ini.');
                    }
                    $newBalance = $wallet->balance - $transaction->amount;
                }

                // Update saldo di wallet
                $wallet->update(['balance' => $newBalance]);

                // Update status transaksi dan saldo sebelum/sesudah
                $transaction->update([
                    'status'         => 'accepted',
                    'balance_before' => $balance_before,
                    'balance_after'  => $newBalance,
                ]);
            });
        } catch (\Exception $e) {
            // Jika terjadi error (misal saldo tidak cukup), batalkan dan beri pesan error
            $transaction->update(['status' => 'rejected', 'description' => ($transaction->description ? $transaction->description . ' - ' : '') . 'Ditolak sistem: ' . $e->getMessage()]);
            return redirect()->route('admin.tabungan.index')->with('error', 'Gagal menerima transaksi: ' . $e->getMessage());
        }


        return redirect()->route('admin.tabungan.index')->with('success', 'Transaksi berhasil diterima.');
    }

    /**
     * Menolak transaksi yang pending.
     */
    public function rejectTransaction(Request $request, WalletTransaction $transaction)
    {
        // Pastikan transaksi masih pending
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $request->validate(['rejection_reason' => 'nullable|string|max:255']);

        $description = 'Ditolak oleh admin.';
        if ($request->filled('rejection_reason')) {
            $description = 'Alasan ditolak: ' . $request->rejection_reason;
        }

        $transaction->update([
            'status' => 'rejected',
            'description' => ($transaction->description ? $transaction->description . ' - ' : '') . $description
        ]);

        return redirect()->route('admin.tabungan.index')->with('success', 'Transaksi berhasil ditolak.');
    }
}
