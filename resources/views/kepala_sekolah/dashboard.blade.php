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
                            <h6 class="mb-1">Total Users</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-people text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Murid</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-person-fill text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Guru</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-person-video3 text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Kelas</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-door-open text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="card p-3 mt-4">
            <h5>User Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
