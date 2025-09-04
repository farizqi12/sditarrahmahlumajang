<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Absensi - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/admin/absensi.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <div class="card p-3 mt-4">
            <h5>Data Absensi</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dummy User 1</td>
                            <td>01 Jan 2025</td>
                            <td><span class="badge bg-success">Hadir</span></td>
                            <td>08:00 - 17:00</td>
                        </tr>
                        <tr>
                            <td>Dummy User 2</td>
                            <td>01 Jan 2025</td>
                            <td><span class="badge bg-danger">Alpha</span></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>