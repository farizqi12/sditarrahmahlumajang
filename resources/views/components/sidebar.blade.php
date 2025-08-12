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
    @auth
        <h4 class="text-white text-center mb-4">E-Learning {{ ucfirst(str_replace('_', ' ', Auth::user()->role->name)) }}</h4>

        {{-- Menu untuk semua role --}}
        <a href="{{ route(Auth::user()->role->name . '.dashboard') }}"
            class="{{ Request::is(Auth::user()->role->name . '/dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>

        {{-- Menu khusus Admin --}}
        @if (Auth::user()->role->name == 'admin')
            <a href="{{ route('admin.courses.index') }}"
                class="{{ Request::is('admin/courses*') ? 'active' : '' }}">
                <i class="bi bi-book me-2"></i> Courses
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Users
            </a>
            <a href="{{ route('admin.absensi.index') }}"
                class="{{ Request::is('admin/absensi*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Absensi
            </a>
            <a href="{{ route('admin.reports') }}"
                class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart me-2"></i> Reports
            </a>
            <a href="{{ route('admin.tabungan.index') }}"
                class="{{ Request::is('admin/tabungan*') ? 'active' : '' }}">
                <i class="bi bi-wallet2 me-2"></i> Tabungan
            </a>
        @endif

        {{-- Menu khusus Kepala Sekolah --}}
        @if (Auth::user()->role->name == 'kepala_sekolah')
            <a href="{{ route('kepala_sekolah.absensi.index') }}" class="{{ Request::is('kepala_sekolah/absensi*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Absensi
            </a>
            <a href="{{ route('kepala_sekolah.laporan_keuangan.index') }}" class="{{ Request::is('kepala_sekolah/laporan-keuangan*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin me-2"></i> Laporan Keuangan
            </a>
        @endif

        {{-- Menu khusus Guru --}}
        @if (Auth::user()->role->name == 'guru')
            <a href="#">
                <i class="bi bi-book-half me-2"></i> Kelas Saya
            </a>
            <a href="#">
                <i class="bi bi-journal-check me-2"></i> Materi & Tugas
            </a>
            <a href="#">
                <i class="bi bi-person-check me-2"></i> Absensi Siswa
            </a>
        @endif

        {{-- Menu khusus Murid --}}
        @if (Auth::user()->role->name == 'murid')
            <a href="#">
                <i class="bi bi-book-reader me-2"></i> Jadwal Pelajaran
            </a>
            <a href="#">
                <i class="bi bi-pen me-2"></i> Tugas
            </a>
            <a href="#">
                <i class="bi bi-wallet me-2"></i> Tabungan Saya
            </a>
        @endif

        {{-- Menu khusus Staff TU --}}
        @if (Auth::user()->role->name == 'staff_tu')
            <a href="{{ route('staff_tu.absensi.index') }}" class="{{ Request::is('staff_tu/absensi*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Absensi
            </a>
            <a href="{{ route('staff_tu.siswa.index') }}" class="{{ Request::is('staff_tu/siswa*') ? 'active' : '' }}">
                <i class="bi bi-person-rolodex me-2"></i> Data Siswa
            </a>
            <a href="{{ route('staff_tu.tabungan.index') }}" class="{{ Request::is('staff_tu/tabungan*') ? 'active' : '' }}">
                <i class="bi bi-wallet2 me-2"></i> Tabungan
            </a>
        @endif

    @endauth
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
