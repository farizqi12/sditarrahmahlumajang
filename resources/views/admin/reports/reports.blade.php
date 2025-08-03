<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Reports - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <div class="card p-3 mt-4">
    <h5>Laporan Kelas</h5>

    {{-- Form untuk memilih kelas --}}
    <form action="{{ route('admin.reports') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="class_id" class="form-select">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($class as $classItem)
                        <option value="{{ $classItem->id }}" {{ (isset($currentClass) && $currentClass->id == $classItem->id) ? 'selected' : '' }}>
                            {{ $classItem->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </div>
    </form>

    {{-- Jika kelas sudah dipilih, tampilkan detail dan daftar siswa --}}
    @if (isset($currentClass))
        <hr>
        <h6>Detail Kelas</h6>
        <p><strong>Kelas:</strong> {{ $currentClass->name }}</p>
        <p><strong>Wali Kelas:</strong> {{ $currentClass->teacher->user->name ?? 'Belum ditentukan' }}</p>

        <h6 class="mt-3">Daftar Siswa</h6>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currentClass->enrollments as $index => $enrollment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $enrollment->student->nis }}</td>
                            <td>{{ $enrollment->student->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada siswa yang terdaftar di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center p-4">
            <p>Silakan pilih kelas untuk menampilkan laporan.</p>
        </div>
    @endif
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>