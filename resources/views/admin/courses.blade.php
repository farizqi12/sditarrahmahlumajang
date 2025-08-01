<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Manajemen Kelas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- For AJAX -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body>
    <x-navbar />
    <x-sidebar />

    <div class="content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Daftar Kelas</h4>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="bi bi-plus"></i> Tambah Kelas
                </button>
            </div>

            <x-notif />

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->teacher->user->name ?? '-' }}</td>
                            <td>{{ $course->academicYear->name ?? '-' }}</td>
                            <td>{{ Str::limit($course->description, 50) }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseModal-{{ $course->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCourseModal-{{ $course->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header"><h5>Edit Kelas</h5></div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Kelas</label>
                                                <input name="name" value="{{ old('name', $course->name) }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="3">{{ old('description', $course->description) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Wali Kelas</label>
                                                <select name="teacher_id" class="form-select" required>
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                            {{ $teacher->user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tahun Ajaran</label>
                                                <select name="academic_year_id" class="form-select" required>
                                                    @foreach ($academicYears as $year)
                                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $course->academic_year_id) == $year->id ? 'selected' : '' }}>
                                                            {{ $year->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $courses->links() }}
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addCourseForm" action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5>Tambah Kelas</h5></div>
                    <div class="modal-body">
                        <div id="course-error-container"></div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            <select name="teacher_id" class="form-select" required>
                                <option selected disabled>Pilih Wali Kelas</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <div class="input-group">
                                <select id="academicYearSelect" name="academic_year_id" class="form-select" required>
                                    <option selected disabled>Pilih Tahun Ajaran</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">
                                    <i class="bi bi-plus"></i> Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Academic Year Modal -->
    <div class="modal fade" id="addAcademicYearModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header"><h5>Tambah Tahun Ajaran</h5></div>
                <div class="modal-body">
                    <div id="year-error-container"></div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" id="yearName" class="form-control" placeholder="e.g., 2024/2025 Ganjil">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" id="yearStartDate" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" id="yearEndDate" class="form-control">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="yearIsActive">
                        <label class="form-check-label" for="yearIsActive">Jadikan Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#addCourseModal" data-bs-toggle="modal">Kembali</button>
                    <button type="button" id="saveAcademicYear" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('saveAcademicYear').addEventListener('click', function() {
            const name = document.getElementById('yearName').value;
            const startDate = document.getElementById('yearStartDate').value;
            const endDate = document.getElementById('yearEndDate').value;
            const isActive = document.getElementById('yearIsActive').checked;
            const errorContainer = document.getElementById('year-error-container');
            errorContainer.innerHTML = '';

            fetch('{{ route("admin.academic-years.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    name: name, 
                    start_date: startDate, 
                    end_date: endDate, 
                    is_active: isActive 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('academicYearSelect');
                    const newOption = new Option(data.academicYear.name, data.academicYear.id, true, true);
                    select.add(newOption);
                    bootstrap.Modal.getInstance(document.getElementById('addAcademicYearModal')).hide();
                    bootstrap.Modal.getInstance(document.getElementById('addCourseModal')).show();
                } else {
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    if (typeof data.message === 'object') {
                        for (const key in data.message) {
                            errorHtml += `<li>${data.message[key][0]}</li>`;
                        }
                    } else {
                        errorHtml += `<li>${data.message}</li>`;
                    }
                    errorHtml += '</ul></div>';
                    errorContainer.innerHTML = errorHtml;
                }
            })
            .catch(error => {
                errorContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>';
            });
        });

        // Show main modal if validation fails on course creation
        @if($errors->any())
            const addCourseModal = new bootstrap.Modal(document.getElementById('addCourseModal'));
            const errorContainer = document.getElementById('course-error-container');
            let errorHtml = '<div class="alert alert-danger"><ul>';
            @foreach ($errors->all() as $error)
                errorHtml += `<li>{{ $error }}</li>`;
            @endforeach
            errorHtml += '</ul></div>';
            errorContainer.innerHTML = errorHtml;
            addCourseModal.show();
        @endif
    </script>
</body>
</html>