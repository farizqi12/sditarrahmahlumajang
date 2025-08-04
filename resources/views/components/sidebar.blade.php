<style>
    /* Sidebar Styles */
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(to bottom, #343a40, #495057);
        /* gelap lembut */
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

    /* Sidebar Overlay (untuk mobile) */
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

    /* Responsive Sidebar */
    @media (max-width: 768px) {
        .sidebar {
            left: -260px;
        }

        .sidebar.active {
            left: 0;
        }
    }
</style>
<div class="sidebar" id="sidebar">
    <h4 class="text-white text-center mb-4">E-Learning Admin</h4>

    <a href="{{ url('/admin/dashboard') }}"
        class="{{ Request::is('dashboard') || Request::is('*/dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>

    <a href="{{ url('/admin/courses') }}"
        class="{{ Request::is('courses') || Request::is('*/courses') || Request::is('*/courses/*') ? 'active' : '' }}">
        <i class="bi bi-book me-2"></i> Courses
    </a>

    <a href="{{ url('/admin/users') }}" class="{{ Request::is('users') || Request::is('*/users') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Users
    </a>

    <a href="{{ url('/admin/absensi') }}"
        class="{{ Request::is('absensi') || Request::is('*/absensi') ? 'active' : '' }}">
        <i class="bi bi-floppy me-2"></i> Absensi
    </a>

    <a href="{{ url('/admin/reports') }}"
        class="{{ Request::is('reports') || Request::is('*/reports') ? 'active' : '' }}">
        <i class="bi bi-bar-chart me-2"></i> Reports
    </a>

    <a href="{{ url('/admin/tabungan') }}"
        class="{{ Request::is('tabungan') || Request::is('*/tabungan') ? 'active' : '' }}">
        <i class="bi bi-wallet2 me-2"></i> Tabungan
    </a>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
