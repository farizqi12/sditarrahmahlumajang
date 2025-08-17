<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Absensi - E-Learning</title>
    <!-- Google Fonts Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Sidebar -->
    <x-sidebar></x-sidebar>

    <!-- Main Content -->
    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="card p-3 mt-4">
            <h5>Laporan Absensi</h5>

            <div class="row mt-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.reports.attendance.export') }}" method="GET">
                        <div class="input-group">
                            <input type="month" class="form-control" name="month" value="{{ date('Y-m') }}">
                            <button class="btn btn-primary" type="submit">Ekspor Semua (PDF)</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.reports.attendance.export-user') }}" method="GET">
                        <div class="input-group">
                            <select class="form-select" name="user_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <input type="month" class="form-control" name="month" value="{{ date('Y-m') }}">
                            <button class="btn btn-success" type="submit">Ekspor per User (PDF)</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Di-scan oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>{{ $attendance->check_in }}</td>
                            <td>{{ $attendance->check_out }}</td>
                            <td>{{ $attendance->status }}</td>
                            <td>{{ $attendance->scannedBy->name ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   </body>

</html>
