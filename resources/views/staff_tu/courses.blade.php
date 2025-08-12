<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Staff TU - Kelas</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin/courses.css') }}">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>

        <div class="card p-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h5 class="mb-0 fw-semibold d-flex align-items-center">
                    <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i> Daftar Kelas
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ request()->query('show') === 'active' ? route('staff_tu.courses.index') : route('staff_tu.courses.index', ['show' => 'active']) }}" class="btn btn-outline-secondary btn-sm">
                        {{ request()->query('show') === 'active' ? 'Tampilkan Semua' : 'Tampilkan Aktif' }}
                    </a>
                    <button class="btn btn-success d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        <i class="bi bi-plus-lg text-white"></i>
                        <span class="d-none d-sm-inline">Tambah</span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Guru</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->teacher->user->name }}</td>
                                <td>{{ $course->academicYear->name }}</td>
                                <td>
                                    @if ($course->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1">
                                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $course->id }}">
                                        <i class="bi bi-pencil-square text-white"></i>
                                        <span class="d-none d-sm-inline">Edit</span>
                                    </button>

                                    <form action="{{ route('staff_tu.courses.toggleStatus', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn {{ $course->is_active ? 'btn-warning' : 'btn-info' }} btn-sm d-flex align-items-center gap-1">
                                            <i class="bi bi-power text-white"></i>
                                            <span class="d-none d-sm-inline">{{ $course->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('staff_tu.courses.manage', $course->id) }}" class="btn btn-info btn-sm d-flex align-items-center gap-1">
                                        <i class="bi bi-people-fill text-white"></i>
                                        <span class="d-none d-sm-inline">Kelola</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Course --}}
    <div class="modal fade course-modal" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('staff_tu.courses.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Guru</label>
                        <select name="teacher_id" class="form-select" required>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <div class="input-group">
                            <select name="academic_year_id" class="form-select academic-year-select" required>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit per Kelas --}}
    @foreach ($courses as $course)
        <div class="modal fade course-modal" id="editCourseModal{{ $course->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('staff_tu.courses.update', $course->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Kelas</label>
                            <input type="text" name="name" class="form-control" value="{{ $course->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control">{{ $course->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Guru</label>
                            <select name="teacher_id" class="form-select" required>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <select name="academic_year_id" class="form-select academic-year-select" required>
                                @foreach ($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $course->academic_year_id == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


