<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tabungan - Staff TU</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/wallet.css') }}">
    <style>
        .btn-xs { padding: 0.25rem 0.4rem; font-size: 0.75rem; line-height: 1.5; border-radius: 0.2rem; }
        .btn-xs i.bi { font-size: 1rem; }
        .btn-xs .small { font-size: 1rem; }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <!-- === TABEL TABUNGAN SAAT INI === -->
        <div class="card p-4 mt-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Total Tabungan</th>
                            <th>Tanggal Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if ($user->role->name === 'murid' && $user->student && $user->student->enrollments->isNotEmpty())
                                        {{ $user->student->enrollments->first()->classModel->name ?? 'Tidak terdaftar' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp {{ number_format($user->wallet->balance ?? 0, 0, ',', '.') }}</td>
                                <td>{{ optional($user->wallet?->transactions?->sortByDesc('created_at')->first())->created_at?->format('d F Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data tabungan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- === TABEL TRANSAKSI PENDING === -->
        <div class="card p-4 mt-4">
            <h4 class="mb-3">Transaksi Menunggu Konfirmasi</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Nominal</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingTransactions as $trx)
                            <tr>
                                <td>{{ $trx->wallet->user->name }}</td>
                                <td>
                                    @if ($trx->wallet->user->role->name === 'murid' && $trx->wallet->user->student && $trx->wallet->user->student->enrollments->isNotEmpty())
                                        {{ $trx->wallet->user->student->enrollments->first()->classModel->name ?? 'Tidak terdaftar' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                                <td>{{ $trx->type === 'deposit' ? 'Setor' : 'Tarik' }}</td>
                                <td>{{ $trx->created_at->format('d F Y') }}</td>
                                <td>
                                    @if (!empty($trx->proof_url))
                                        <a href="{{ $trx->proof_url }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <form method="POST" action="{{ route('staff_tu.tabungan.accept', $trx->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-xs btn-success me-1" title="Terima Transaksi">
                                            <i class="bi bi-check-circle"></i> <span class="d-none d-sm-inline">Terima</span>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $trx->id }}" title="Tolak Transaksi">
                                        <i class="bi bi-x-circle"></i> <span class="d-none d-sm-inline">Tolak</span>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Penolakan -->
                            <div class="modal fade" id="rejectModal-{{ $trx->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $trx->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel-{{ $trx->id }}">Tolak Transaksi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="{{ route('staff_tu.tabungan.reject', $trx->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Anda yakin ingin menolak transaksi dari <strong>{{ $trx->wallet->user->name }}</strong> sebesar <strong>Rp {{ number_format($trx->amount, 0, ',', '.') }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label for="rejection_reason-{{ $trx->id }}" class="form-label">Alasan Penolakan (Opsional)</label>
                                                    <textarea class="form-control" id="rejection_reason-{{ $trx->id }}" name="rejection_reason" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Tolak Transaksi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada transaksi menunggu konfirmasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


