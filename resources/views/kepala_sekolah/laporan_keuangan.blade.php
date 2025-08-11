<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Keuangan - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

    <style>
        /* Copy style dari halaman user agar konsisten */

        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #1e3a8a 100%);
            overflow-x: hidden;
            color: #212529;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            color: #212529;
        }

        /* Untuk responsif, margin-left dihilangkan */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }
            /* Buat canvas grafik responsif dan proporsional */
            #grafikKeuangan {
                width: 100% !important;
                height: auto !important;
                min-height: 250px;
            }
        }

        /* Navbar (gunakan kelas admin-navbar sama seperti di user) */
        .admin-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        /* Card style */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background-color: #fff;
            color: #212529;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-title {
            font-weight: 600;
            color: #4e73df;
        }

        /* Table style */
        .table thead {
            background-color: #f1f3f5;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            padding: 12px 16px;
            color: #212529;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Select box filter - diperbaiki agar teks tidak tumpuk dengan ikon */
        #filterPeriode {
            font-size: 14px;
            padding: 6px 30px 6px 10px; /* Tambah padding kanan agar teks tidak tumpuk dengan icon */
            border-radius: 8px;
            border: 1px solid #ddd;
            width: auto;
            min-width: 140px; /* Lebar minimum agar teks cukup luas */
            white-space: nowrap; /* Teks tidak pecah baris */
            overflow: hidden;
            text-overflow: ellipsis; /* Potong teks dengan ... bila terlalu panjang */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 8px 10px;
            cursor: pointer;
        }

        /* Nav tabs */
        .nav-tabs .nav-link {
            font-weight: 500;
            color: #4e73df;
        }

        .nav-tabs .nav-link.active {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
            border-radius: 12px;
        }

        /* Canvas */
        canvas {
            background: white;
            border-radius: 8px;
            padding: 10px;
        }

        /* Tambahan: tombol tab berdampingan */
        #laporanTab {
            display: flex !important;
            gap: 0.5rem;
        }

        #laporanTab .nav-item {
            flex: 1;
        }

        #laporanTab .nav-link {
            text-align: center;
            width: 100%;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar class="admin-navbar"></x-navbar>
        <x-notif></x-notif>

        <div class="card p-4 mt-4">
            <h4 class="card-title mb-4">Laporan Keuangan Sekolah</h4>

            <div class="d-flex justify-content-end mb-3">
                <select id="filterPeriode" class="form-select w-auto">
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>
            </div>

            <div class="mb-4">
                <!-- Hilangkan height attribute supaya Chart.js bisa mengatur ukuran canvas sendiri -->
                <canvas id="grafikKeuangan"></canvas>
            </div>

            <ul class="nav nav-tabs" id="laporanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tabunganMasuk-tab" data-bs-toggle="tab"
                        data-bs-target="#tabunganMasuk" type="button" role="tab">Tabungan Masuk</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tabunganKeluar-tab" data-bs-toggle="tab"
                        data-bs-target="#tabunganKeluar" type="button" role="tab">Tabungan Keluar</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="laporanTabContent">
                <div class="tab-pane fade show active" id="tabunganMasuk" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-08-10</td>
                                    <td>Budi Santoso</td>
                                    <td>Rp 50.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tabunganKeluar" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-08-09</td>
                                    <td>Siti Aminah</td>
                                    <td>Rp 30.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('grafikKeuangan').getContext('2d');

        // Data default (Tabungan Masuk)
        const dataTabunganMasuk = {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Transaksi Masuk',
                data: [50000, 30000, 40000, 20000, 60000],
                backgroundColor: '#4e73df'
            }]
        };

        // Data Tabungan Keluar
        const dataTabunganKeluar = {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Transaksi Keluar',
                data: [20000, 15000, 25000, 10000, 30000],
                backgroundColor: '#e74a3b'
            }]
        };

        // Buat chart dengan data Tabungan Masuk default
        let chart = new Chart(ctx, {
            type: 'bar',
            data: dataTabunganMasuk,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y || 0;
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Event listener untuk perubahan periode
        document.getElementById('filterPeriode').addEventListener('change', function () {
            const periode = this.value;

            let newLabels, newData;

            if (periode === 'harian') {
                newLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            } else if (periode === 'bulanan') {
                newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'];
            } else {
                newLabels = ['2023', '2024', '2025'];
            }

            const activeTabId = document.querySelector('#laporanTab .nav-link.active').id;

            if (activeTabId === 'tabunganMasuk-tab') {
                if (periode === 'harian') newData = [50000, 30000, 40000, 20000, 60000];
                else if (periode === 'bulanan') newData = [200000, 150000, 300000, 250000, 400000];
                else newData = [2500000, 3200000, 4100000];
                chart.data.datasets[0].label = 'Transaksi Masuk';
                chart.data.datasets[0].backgroundColor = '#4e73df';
            } else {
                if (periode === 'harian') newData = [20000, 15000, 25000, 10000, 30000];
                else if (periode === 'bulanan') newData = [100000, 80000, 120000, 90000, 110000];
                else newData = [1200000, 1100000, 1500000];
                chart.data.datasets[0].label = 'Transaksi Keluar';
                chart.data.datasets[0].backgroundColor = '#e74a3b';
            }

            chart.data.labels = newLabels;
            chart.data.datasets[0].data = newData;
            chart.update();
        });

        // Event listener saat tab berganti
        document.querySelectorAll('#laporanTab .nav-link').forEach(button => {
            button.addEventListener('shown.bs.tab', event => {
                const periode = document.getElementById('filterPeriode').value;

                let newLabels, newData;

                if (periode === 'harian') {
                    newLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                } else if (periode === 'bulanan') {
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'];
                } else {
                    newLabels = ['2023', '2024', '2025'];
                }

                if (event.target.id === 'tabunganMasuk-tab') {
                    if (periode === 'harian') newData = [50000, 30000, 40000, 20000, 60000];
                    else if (periode === 'bulanan') newData = [200000, 150000, 300000, 250000, 400000];
                    else newData = [2500000, 3200000, 4100000];
                    chart.data.datasets[0].label = 'Transaksi Masuk';
                    chart.data.datasets[0].backgroundColor = '#4e73df';
                } else {
                    if (periode === 'harian') newData = [20000, 15000, 25000, 10000, 30000];
                    else if (periode === 'bulanan') newData = [100000, 80000, 120000, 90000, 110000];
                    else newData = [1200000, 1100000, 1500000];
                    chart.data.datasets[0].label = 'Transaksi Keluar';
                    chart.data.datasets[0].backgroundColor = '#e74a3b';
                }

                chart.data.labels = newLabels;
                chart.data.datasets[0].data = newData;
                chart.update();
            });
        });

        // Optional: trigger resize on window resize
        window.addEventListener('resize', () => {
            chart.resize();
        });
    </script>
</body>

</html>
