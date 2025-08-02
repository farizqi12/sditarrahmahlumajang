<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Courses - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin/courses.css') }}">
    <style>
        .btn-success i {
            font-size: 1.1rem;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 575.98px) {
            .btn-success i {
                font-size: 1.2rem;
            }
        }

        .btn-sm i {
            font-size: 1rem;
        }

        @media (max-width: 575.98px) {
            .btn-sm {
                padding: 0.3rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>
    <x-notif></x-notif>
    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <div class="card p-3 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h5 class="mb-0 fw-semibold d-flex align-items-center">
                    <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i> Daftar Mata Pelajaran
                </h5>
                <button class="btn btn-success d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="bi bi-plus-lg text-white"></i>
                    <span class="d-none d-sm-inline">Tambah</span>
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->teacher->user->name }}</td>
                                <td>{{ $course->students->count() }}</td>
                                <td>
                                    <!-- Tombol Edit dengan Icon -->
                                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $course->id }}">
                                        <i class="bi bi-pencil-square text-white"></i>
                                        <span class="d-none d-sm-inline">Edit</span>
                                    </button>

                                    <!-- Tombol Hapus dengan Icon -->
                                    <button class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#deleteCourseModal{{ $course->id }}">
                                        <i class="bi bi-trash-fill text-white"></i>
                                        <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data mata pelajaran.</td>
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
            <form action="{{ route('admin.courses.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Mata Pelajaran</label>
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
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                data-bs-target="#addAcademicYearModal">
                                <i class="bi bi-plus-circle"></i>
                            </button>
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

    {{-- Modal Tambah Tahun Ajaran --}}
    <div class="modal fade" id="addAcademicYearModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="academicYearForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Tahun Ajaran</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: 2024/2025"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                            value="1">
                        <label class="form-check-label" for="is_active">Tandai sebagai tahun ajaran aktif</label>
                    </div>
                    <div id="academicYearError" class="text-danger small"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- =================== Modal Edit & Delete (DILUAR TABLE) =================== --}}
    @foreach ($courses as $course)
        {{-- Modal Edit --}}
        <div class="modal fade course-modal" id="editCourseModal{{ $course->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST"
                    class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Mata Pelajaran</label>
                            <input type="text" name="name" class="form-control" value="{{ $course->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control">{{ $course->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Guru</label>
                            <select name="teacher_id" class="form-select" required>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <div class="input-group">
                                <select name="academic_year_id" class="form-select academic-year-select" required>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ $course->academic_year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addAcademicYearModal">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Delete --}}
        <div class="modal fade delete-course-modal" id="deleteCourseModal{{ $course->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST"
                    class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <p>Yakin ingin menghapus <strong>{{ $course->name }}</strong>?</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('academicYearForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const errorBox = document.getElementById('academicYearError');
            errorBox.innerHTML = '';

            fetch("{{ route('admin.academic-years.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;

                    document.querySelectorAll('.academic-year-select').forEach(select => {
                        const option = new Option(data.academicYear.name, data.academicYear.id,
                            true, true);
                        select.appendChild(option);
                        select.value = data.academicYear.id;
                    });

                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('addAcademicYearModal')).hide();
                })
                .catch(err => {
                    if (err.message) {
                        errorBox.innerHTML = err.message;
                    } else if (err.errors) {
                        errorBox.innerHTML = Object.values(err.errors).flat().join('<br>');
                    } else {
                        errorBox.innerHTML = "Terjadi kesalahan saat menyimpan.";
                    }
                });
        });
    </script>
</body>

</html>
