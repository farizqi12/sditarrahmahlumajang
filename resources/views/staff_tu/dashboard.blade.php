
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
                        <h3>350</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm">
                        <h6 class="text-muted">Transaksi Tertunda</h6>
                        <h3>15</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3 shadow-sm">
                        <h6 class="text-muted">Pemasukan Hari Ini</h6>
                        <h3>Rp 1.500.000</h3>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-4 mt-4">
                <h5 class="card-title mb-3">Aksi Cepat</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-primary w-100 p-3">Manajemen Pembayaran SPP</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-info w-100 p-3">Lihat Data Siswa</a>
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
                            <tr>
                                <td>#12345</td>
                                <td>Ahmad Abdullah</td>
                                <td><span class="badge bg-success">Credit</span></td>
                                <td>Rp 250.000</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>07 Agu 2025 10:30</td>
                            </tr>
                            <tr>
                                <td>#12346</td>
                                <td>Budi Santoso</td>
                                <td><span class="badge bg-success">Credit</span></td>
                                <td>Rp 250.000</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>07 Agu 2025 09:15</td>
                            </tr>
                            <tr>
                                <td>#12347</td>
                                <td>Citra Lestari</td>
                                <td><span class="badge bg-danger">Debit</span></td>
                                <td>Rp 50.000</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>06 Agu 2025 14:00</td>
                            </tr>
                            <tr>
                                <td>#12348</td>
                                <td>Dewi Anggraini</td>
                                <td><span class="badge bg-success">Credit</span></td>
                                <td>Rp 250.000</td>
                                <td><span class="badge bg-danger">Rejected</span></td>
                                <td>06 Agu 2025 11:45</td>
                            </tr>
                            <tr>
                                <td>#12349</td>
                                <td>Eko Prasetyo</td>
                                <td><span class="badge bg-success">Credit</span></td>
                                <td>Rp 250.000</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>05 Agu 2025 16:20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
