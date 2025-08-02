<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tabungan Siswa - Admin</title>
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <!-- Main Content -->
    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <!-- === TABEL TABUNGAN SAAT INI === -->
        <div class="card p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Data Tabungan Aktif</h4>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Tabungan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Tabungan</th>
                            <th>Tanggal Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Alya Putri</td>
                            <td>7A</td>
                            <td>Rp 120.000</td>
                            <td>02 Agustus 2025</td>
                        </tr>
                        <tr>
                            <td>Rizky Maulana</td>
                            <td>8B</td>
                            <td>Rp 95.000</td>
                            <td>31 Juli 2025</td>
                        </tr>
                        <tr>
                            <td>Dewi Anjani</td>
                            <td>9C</td>
                            <td>Rp 150.000</td>
                            <td>30 Juli 2025</td>
                        </tr>
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
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Nominal</th>
                            <th>Jenis</th>
                            <th>Tanggal</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dummy Row 1 -->
                        <tr>
                            <td>Salsa Nabila</td>
                            <td>7B</td>
                            <td>Rp 50.000</td>
                            <td>Setor</td>
                            <td>01 Agustus 2025</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">Lihat</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-circle"></i> Terima
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </td>
                        </tr>
                        <!-- Dummy Row 2 -->
                        <tr>
                            <td>Agus Firmansyah</td>
                            <td>8A</td>
                            <td>Rp 30.000</td>
                            <td>Tarik</td>
                            <td>31 Juli 2025</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">Lihat</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-circle"></i> Terima
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </td>
                        </tr>
                        <!-- Dummy Row 3 -->
                        <tr>
                            <td>Fahri Setiawan</td>
                            <td>9D</td>
                            <td>Rp 80.000</td>
                            <td>Setor</td>
                            <td>01 Agustus 2025</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">Lihat</a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-circle"></i> Terima
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
