
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Staff TU - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm">
                        <h6 class="text-muted">Total Siswa</h6>
                        <h3>{{ $totalStudents }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm">
                        <h6 class="text-muted">Transaksi Tertunda</h6>
                        <h3>{{ $pendingTransactions }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm">
                        <h6 class="text-muted">Pemasukan Hari Ini</h6>
                        <h3>Rp {{ number_format($todaysIncome, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            

            <!-- Recent Transactions Table -->
            <div class="card p-3 mt-4">
                <h5>Transaksi Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td>#{{ $transaction->id }}</td>
                                    <td>{{ $transaction->wallet->user->name }}</td>
                                    <td>
                                        @if ($transaction->type == 'credit')
                                            <span class="badge bg-success">Credit</span>
                                        @else
                                            <span class="badge bg-danger">Debit</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($transaction->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif ($transaction->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at->format('d Agu Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada transaksi terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
