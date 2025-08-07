<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kepala Sekolah Dashboard - E-Learning</title>
    <!-- Google Fonts Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/kepala_sekolah/dashboard.css') }}">
</head>

<body>
    <div class="content">
        <x-sidebar></x-sidebar>
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <!-- Stats Cards -->
        <div class="row mt-4 g-4">
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Absen Hari ini</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-clipboard-check-fill text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Ijin Hari ini</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-door-open-fill text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Tabungan Masuk</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-graph-up-arrow text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Tabungan keluar</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-graph-down-arrow text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2 g-4 justify-content-center">
            <div class="col-md-3 col-6 stats-card">
                <a href="{{ route('kepala_sekolah.absensi.index') }}" class="text-decoration-none">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Absensi</h6>
                            </div>
                            <i class="bi bi-building-check"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-6 stats-card">
                <a href="" class="text-decoration-none">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Ijin</h6>
                            </div>
                            <i class="bi bi-building-exclamation"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Recent Users Table -->
        <div class="card p-3 mt-4">
            <h5>Log Absen Hari ini</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
