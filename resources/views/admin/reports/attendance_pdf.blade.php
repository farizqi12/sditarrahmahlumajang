<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Absensi</h1>
    @if (isset($user))
        <h2>Nama: {{ $user->name }}</h2>
    @endif
    <h3>Bulan: {{ date('F Y', strtotime($month)) }}</h3>

    <table>
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
                <td colspan="6" style="text-align: center;">Tidak ada data absensi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
