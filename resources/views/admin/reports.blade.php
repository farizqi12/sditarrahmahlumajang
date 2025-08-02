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
    <style>
        /* Base Styles */
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #1e3a8a 100%);
            overflow-x: hidden;
            color: #212529;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(to bottom, #343a40, #495057); /* gelap lembut */
            padding: 20px;
            width: 250px;
            border-radius: 0 15px 15px 0;
            transition: 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            color: #f8f9fa;
        }

        .sidebar h4 {
            font-weight: bold;
        }

        .sidebar a,
        .sidebar form button {
            color: #f8f9fa;
            padding: 12px 18px;
            display: block;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 12px;
            background-color: transparent;
            border: none;
            text-align: left;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .sidebar a:hover,
        .sidebar form button:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .sidebar a.active {
            background-color: #ffffff33;
            color: #fff;
            font-weight: bold;
            border-left: 4px solid #fff;
        }

        .sidebar form button {
            width: 100%;
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

        /* Overlay Styles */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1029;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
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

        /* Animation for toast entrance */
        @keyframes toastSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Animation for toast exit */
        @keyframes toastSlideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Apply animations to toast */
        .toast.show {
            animation: toastSlideIn 0.3s forwards;
        }

        .toast.hiding {
            animation: toastSlideOut 0.3s forwards;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

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

            .actions .btn-group {
                flex-wrap: nowrap;
            }

            .actions .btn {
                padding: 0.25rem 0.4rem;
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
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <x-sidebar></x-sidebar>

    <div class="content">
        <x-navbar></x-navbar>
        <x-notif></x-notif>
        <div class="card p-3 mt-4">
            <h5>Laporan</h5>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Jenis Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada laporan yang tersedia.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarToggler = document.getElementById("sidebarToggler");
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");

            function toggleSidebar() {
                sidebar.classList.toggle("active");
                sidebarOverlay.classList.toggle("active");
            }

            sidebarToggler.addEventListener("click", toggleSidebar);
            sidebarOverlay.addEventListener("click", toggleSidebar);
        });
    </script>
</body>

</html>