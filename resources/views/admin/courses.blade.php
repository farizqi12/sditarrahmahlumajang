<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Courses - E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        /* Base Styles */
        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #1e3a8a 100%);
            overflow-x: hidden;
            color: #212529;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Main Content Styles */
        .content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            color: #212529;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #fff;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            color: #212529;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 600;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
            background-color: #fff;
            color: #212529;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card i {
            font-size: 2.2rem;
            opacity: 0.85;
            color: #212529;
        }

        /* Table Styles */
        .table thead {
            background-color: #f1f3f5;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            padding: 12px 16px;
            color: #212529;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Button & Badge Styles */
        .btn-sm {
            padding: 5px 10px;
            border-radius: 8px;
        }

        .badge {
            padding: 6px 10px;
            font-size: 0.75rem;
        }

        /* Admin Navbar Styles */
        .admin-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .search-form {
            max-width: 400px;
            margin: 0 auto;
        }

        .search-input {
            border-radius: 20px;
            padding-left: 40px;
            border: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .nav-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
        }

        .mobile-search-btn {
            display: none;
        }

        /* Actions container and buttons */
        /* Tombol aksi vertikal untuk semua layar */
        .actions {
            display: flex !important;
            flex-direction: column !important; /* vertikal turun ke bawah */
            gap: 8px;
            flex-wrap: nowrap !important;
            align-items: flex-start; /* agar tombol tidak stretch */
        }

        /* Tombol aksi: ukuran minimal, padding, dan jarak icon-teks yang ringkas */
        .actions .btn {
            min-width: auto;
            width: auto;
            max-width: 140px;
            padding: 0.15rem 0.3rem; /* padding diperkecil agar mepet */
            font-size: 0.85rem;
            justify-content: center;
            gap: 0.2rem; /* jarak icon dan teks diperkecil */
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        /* Ukuran icon tombol */
        .actions .btn i {
            font-size: 1rem;
        }

        /* Hover dan warna khusus untuk btn-success */
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.15);
        }

        /* Responsive untuk mobile - samakan ukuran tombol aksi dan padat */
        @media (max-width: 575.98px) {
            .actions .btn {
                min-width: auto;
                max-width: 120px;
                padding: 0.15rem 0.3rem;
                font-size: 0.85rem;
                gap: 0.15rem;
            }

            .actions .btn i {
                font-size: 0.95rem;
            }

            /* Sembunyikan teks tombol supaya tombol lebih ringkas */
            .actions .btn span.d-none.d-sm-inline {
                display: none !important;
            }
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }

            .stats-card {
                margin-bottom: 15px;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th,
            .table td {
                padding: 8px 10px;
            }

            .course-name {
                min-width: 120px;
                word-break: break-word;
            }

            .enrolled,
            .status {
                white-space: nowrap;
            }

            .actions {
                flex-wrap: nowrap;
                overflow-x: auto;
            }
        }

        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 6px 8px;
            }

            .badge {
                padding: 4px 6px;
                font-size: 0.7rem;
            }

            .actions .btn {
                max-width: 100px;
                padding: 0.1rem 0.25rem;
                font-size: 0.8rem;
                gap: 0.1rem;
            }

            .actions .btn i {
                font-size: 0.9rem;
            }
        }

        /* Tablet Navbar Adjustments */
        @media (max-width: 992px) {
            .search-form {
                display: none !important;
            }

            .mobile-search-btn {
                display: block;
            }
        }

        /* Small Screen Navbar Adjustments */
        @media (max-width: 576px) {
            .admin-navbar {
                padding: 0.5rem 1rem;
            }

            .user-name {
                display: none;
            }
        }

        /* === Perubahan untuk tata letak tombol Tampilkan & Tambah === */
        /* Membuat header flex agar semua sejajar secara horizontal dan rata tengah */
        .card > .d-flex.justify-content-between.align-items-center {
            align-items: center !important; /* pastikan rata tengah vertikal */
            gap: 10px;
            flex-wrap: nowrap !important;
        }

        /* Container tombol agar tetap kanan dan sejajar */
        .header-buttons {
            display: flex;
            gap: 15px;
            align-items: center; /* agar tombol vertikal rata tengah */
            flex-wrap: nowrap;
        }

        /* Atur tombol "Tambah" agar ukurannya pas */
        .header-buttons .btn-success {
            min-width: auto !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
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
            <!-- HEADER & TOMBOL -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h5 class="mb-0 fw-semibold d-flex align-items-center">
                    <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i> Daftar Kelas
                </h5>

                <div class="header-buttons">
                    <a href="{{ request()->query('show') === 'active' ? route('admin.courses.index') : route('admin.courses.index', ['show' => 'active']) }}"
                        class="btn btn-outline-secondary btn-sm">
                        {{ request()->query('show') === 'active' ? 'Tampilkan Semua' : 'Tampilkan Hanya Aktif' }}
                    </a>

                    <button class="btn btn-success d-flex align-items-center gap-2 px-2 py-1 rounded-pill shadow-sm"
                        style="min-width: 40px; font-size: 0.9rem;"
                        data-bs-toggle="modal" data-bs-target="#addCourseModal" title="Tambah">
                        <i class="bi bi-plus-lg text-white" style="font-size: 1.2rem;"></i>
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
                                <td>
                                    <div class="actions">
                                        <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                            data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $course->id }}">
                                            <i class="bi bi-pencil-square text-white"></i>
                                            <span class="d-none d-sm-inline">Edit</span>
                                        </button>

                                        <form action="{{ route('admin.courses.toggleStatus', $course->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $course->is_active ? 'btn-warning' : 'btn-info' }} btn-sm d-flex align-items-center gap-1">
                                                @if ($course->is_active)
                                                    <i class="bi bi-power text-white"></i>
                                                    <span class="d-none d-sm-inline">Nonaktifkan</span>
                                                @else
                                                    <i class="bi bi-power text-white"></i>
                                                    <span class="d-none d-sm-inline">Aktifkan</span>
                                                @endif
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.courses.manage', $course->id) }}" class="btn btn-info btn-sm d-flex align-items-center gap-1">
                                            <i class="bi bi-people-fill text-white"></i>
                                            <span class="d-none d-sm-inline">Kelola</span>
                                        </a>
                                    </div>
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
    <div class="modal fade course-modal" id="addCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Mata Pelajaran</label>
                        <input type="text" name="name" class="form-control" required />
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
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah Tahun Ajaran --}}
    <div class="modal fade" id="addAcademicYearModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="academicYearForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Tahun Ajaran</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: 2024/2025" required />
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required />
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" />
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

    {{-- Modal Edit & Delete --}}
    @foreach ($courses as $course)
        {{-- Modal Edit --}}
        <div class="modal fade course-modal" id="editCourseModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Mata Pelajaran</label>
                            <input type="text" name="name" class="form-control" value="{{ $course->name }}" required />
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
                            <div class="input-group">
                                <select name="academic_year_id" class="form-select academic-year-select" required>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ $course->academic_year_id == $year->id ? 'selected' : '' }}>
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
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive{{ $course->id }}" value="1" {{ $course->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="editIsActive{{ $course->id }}">Aktifkan</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Anda bisa menambahkan JS khusus jika diperlukan, misal untuk validasi form addAcademicYear
    </script>
</body>

</html>
