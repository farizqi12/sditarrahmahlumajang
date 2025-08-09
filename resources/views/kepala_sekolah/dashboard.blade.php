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
                            <h6 class="mb-1">Absen Hari ini</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-clipboard-check-fill text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Ijin Hari ini</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-door-open-fill text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Tabungan Masuk</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-graph-up-arrow text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Tabungan keluar</h6>
                            <h3 class="mb-0"></h3>
                        </div>
                        <i class="bi bi-graph-down-arrow text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2 g-4 justify-content-center">
            <div class="col-md-3 col-6 stats-card">
                <a href="{{ route('kepala_sekolah.absensi.index') }}" class="text-decoration-none">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Absensi</h6>
                            </div>
                            <i class="bi bi-building-check"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-6 stats-card">
                <a href="" class="text-decoration-none">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Ijin</h6>
                            </div>
                            <i class="bi bi-building-exclamation"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Recent Users Table -->
        <div class="container-fluid">
            <div class="card p-4 mt-4">
                <h4 class="card-title mb-4">Laporan Akademik Sekolah</h4>

                <!-- Filter Section -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="academic_year" class="form-label">Tahun Ajaran</label>
                        <select id="academic_year" class="form-select">
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class_filter" class="form-label">Kelas</label>
                        <select id="class_filter" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="subject_filter" class="form-label">Mata Pelajaran</label>
                        <select id="subject_filter" class="form-select">
                            <option value="">Semua Mapel</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card text-center p-3 shadow-sm">
                            <h6 class="text-muted">Rata-Rata Nilai</h6>
                            <h3>{{ number_format($averageSchoolGrade, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 shadow-sm">
                            <h6 class="text-muted">Jumlah Siswa</h6>
                            <h3>{{ $totalStudents }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 shadow-sm">
                            <h6 class="text-muted">Tingkat Kehadiran</h6>
                            <h3>{{ number_format($attendanceRate, 2) }}%</h3>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm">
                            <h5 class="mb-3">Rata-Rata Nilai per Kelas</h5>
                            <canvas id="avgGradeByClassChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm">
                            <h5 class="mb-3">Rata-Rata Nilai per Mata Pelajaran</h5>
                            <canvas id="avgGradeBySubjectChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Student Rankings -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm">
                            <h5 class="mb-3">Peringkat Siswa Teratas</h5>
                            <ul class="list-group list-group-flush">
                                @forelse($topStudents as $student)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $student->user->name }}
                                        <span
                                            class="badge bg-success rounded-pill">{{ number_format($student->average_grade, 2) }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item">Data tidak tersedia.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3 shadow-sm">
                            <h5 class="mb-3">Siswa Perlu Perhatian</h5>
                            <ul class="list-group list-group-flush">
                                @forelse($bottomStudents as $student)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $student->user->name }}
                                        <span
                                            class="badge bg-danger rounded-pill">{{ number_format($student->average_grade, 2) }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item">Data tidak tersedia.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart: Rata-Rata Nilai per Kelas
            const avgGradeByClassCtx = document.getElementById('avgGradeByClassChart').getContext('2d');
            new Chart(avgGradeByClassCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($avgGradeByClass->keys()) !!},
                    datasets: [{
                        label: 'Rata-Rata Nilai',
                        data: {!! json_encode($avgGradeByClass->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Chart: Rata-Rata Nilai per Mata Pelajaran
            const avgGradeBySubjectCtx = document.getElementById('avgGradeBySubjectChart').getContext('2d');
            new Chart(avgGradeBySubjectCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($avgGradeBySubject->keys()) !!},
                    datasets: [{
                        label: 'Rata-Rata Nilai',
                        data: {!! json_encode($avgGradeBySubject->values()) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
