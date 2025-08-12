<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaksi Pending - Staff TU</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/wallet.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="card p-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-semibold">Transaksi Menunggu Persetujuan</h5>
                <a href="{{ route('staff_tu.tabungan.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Tabungan</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pengguna</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->wallet->user->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->type === 'deposit' ? 'info' : 'warning' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('staff_tu.tabungan.accept', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>
                                        <form action="{{ route('staff_tu.tabungan.reject', $transaction->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="rejection_reason" value="">
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada transaksi yang menunggu persetujuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


