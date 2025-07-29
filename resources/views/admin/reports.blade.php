<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin corses - E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <x-navbar></x-navbar>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Sidebar -->
    <x-sidebar></x-sidebar>

    <!-- Main Content -->
    <div class="content">
        <!-- Stats Cards -->
        <div class="row mt-4 g-4">
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Total Users</h6>
                            <h3 class="mb-0">1,234</h3>
                        </div>
                        <i class="bi bi-people text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Active Courses</h6>
                            <h3 class="mb-0">56</h3>
                        </div>
                        <i class="bi bi-book text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Enrollments</h6>
                            <h3 class="mb-0">2,890</h3>
                        </div>
                        <i class="bi bi-person-plus text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 stats-card">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Revenue</h6>
                            <h3 class="mb-0">$12,450</h3>
                        </div>
                        <i class="bi bi-currency-dollar text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Table -->
        <div class="card p-3 mt-4">
            <h5>Courses Overview</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="course-name">Course Name</th>
                            <th class="d-none d-md-table-cell">Instructor</th>
                            <th class="enrolled">Enrolled</th>
                            <th class="status">Status</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="course-name">Introduction to Python</td>
                            <td class="d-none d-md-table-cell">John Doe</td>
                            <td class="enrolled">150</td>
                            <td class="status"><span class="badge bg-success rounded-pill">Active</span></td>
                            <td class="actions">
                                <div class="btn-group btn-group-sm" role="group">

                                </div>
                            </td>
                        </tr>
                        <!-- Baris lainnya sama -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
    <script></script>
</body>

</html>
