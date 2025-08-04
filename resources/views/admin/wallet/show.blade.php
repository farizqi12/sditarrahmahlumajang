<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Tabungan: {{ $user->name }} - E-Learning</title>
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
                <h5 class="mb-0">Detail Tabungan: <strong>{{ $user->name }}</strong></h5>
                <a href="{{ route('admin.tabungan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="row">
                {{-- Kolom untuk menambah transaksi --}}
                <div class="col-md-4">
                    <h6>Tambah Transaksi</h6>
                    <form action="{{ route('admin.tabungan.store', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Jenis Transaksi</label>
                            <select name="type" class="form-select" required>
                                <option value="deposit">Setoran</option>
                                <option value="withdrawal">Penarikan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah (Rp)</label>
                            <input type="number" name="amount" class="form-control" required min="1">
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </form>
                </div>

                {{-- Kolom untuk riwayat transaksi --}}
                <div class="col-md-8">
                    <h6>Riwayat Transaksi</h6>
                    <p><strong>Saldo Saat Ini:</strong> Rp {{ number_format($user->wallet->balance ?? 0, 2, ',', '.') }}</p>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Saldo Setelah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 'danger' }}">
                                                {{ ucfirst($transaction->type) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($transaction->balance_after, 2, ',', '.') }}</td>
                                        <td>{{ $transaction->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada riwayat transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
