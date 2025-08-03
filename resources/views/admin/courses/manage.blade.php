<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Kelas: {{ $course->name }} - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('css/admin/courses.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="card p-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Kelola Kelas: <strong>{{ $course->name }}</strong></h5>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="row">
                {{-- Kolom untuk menambahkan siswa --}}
                <div class="col-md-4">
                    <h6>Tambahkan Siswa</h6>
                    <form action="{{ route('admin.courses.addStudent', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="student_id" class="form-select">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->nis }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                    </form>
                </div>

                {{-- Kolom untuk daftar siswa yang terdaftar --}}
                <div class="col-md-8">
                    <h6>Siswa Terdaftar</h6>
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
                                @forelse ($course->enrollments as $index => $enrollment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $enrollment->student->nis }}</td>
                                        <td>{{ $enrollment->student->user->name }}</td>
                                        <td>
                                            <form action="{{ route('admin.courses.removeStudent', [$course->id, $enrollment->student->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini dari kelas?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada siswa yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
