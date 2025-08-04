<!-- resources/views/layouts/navbar.blade.php -->
<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <!-- Sidebar Toggle and Brand -->
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-2 sidebar-toggler d-lg-none" id="sidebarToggler">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="navbar-brand m-0">Dashboard</h5>
        </div>

        <!-- Dynamic Search Form -->
        @php
            $searchAction = '';
            $searchPlaceholder = 'Search...';
            if (request()->routeIs('admin.users.*')) {
                $searchAction = route('admin.users.index');
                $searchPlaceholder = 'Cari pengguna...';
            } elseif (request()->routeIs('admin.courses.*')) {
                $searchAction = route('admin.courses.index');
                $searchPlaceholder = 'Cari kelas...';
            } elseif (request()->routeIs('admin.tabungan.*')) {
                $searchAction = route('admin.tabungan.index');
                $searchPlaceholder = 'Cari pengguna...';
            }
        @endphp

        @if ($searchAction)
            <!-- Desktop Search Form -->
            <form action="{{ $searchAction }}" method="GET" class="search-form d-none d-lg-flex ms-auto">
                <div class="position-relative w-100">
                    <i class="bi bi-search search-icon"></i>
                    <input class="form-control search-input" type="search" name="search" placeholder="{{ $searchPlaceholder }}" value="{{ request('search') }}" aria-label="Search">
                </div>
            </form>
        @endif

        <!-- Right Side Items -->
        <div class="d-flex align-items-center ms-auto">
            <!-- Mobile Search Button -->
            @if ($searchAction)
            <button class="btn btn-link text-dark me-2 d-lg-none" id="mobileSearchBtn">
                <i class="bi bi-search"></i>
            </button>
            @endif

            <!-- Notifications -->
            <div class="dropdown me-2 position-relative">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger notification-badge">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">Notifications</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">New course enrollment</a></li>
                    <li><a class="dropdown-item" href="#">System update available</a></li>
                    <li><a class="dropdown-item" href="#">New message received</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-primary" href="#">View all</a></li>
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <div class="nav-user-info d-flex align-items-center">
                        @if (Auth::check())
                            <span class="user-name me-2 d-none d-sm-inline">{{ Auth::user()->name }}</span>
                        @endif
                        <i class="bi bi-person-circle"></i>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-question-circle me-2"></i>Help</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Search Form -->
@if ($searchAction)
<div class="container-fluid mobile-search-form" id="mobileSearchForm">
    <form action="{{ $searchAction }}" method="GET" class="d-flex w-100">
        <div class="position-relative w-100">
            <i class="bi bi-search search-icon"></i>
            <input class="form-control search-input" type="search" name="search" placeholder="{{ $searchPlaceholder }}" value="{{ request('search') }}" aria-label="Search">
            <button type="button" class="btn btn-link" id="closeSearchBtn">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </form>
</div>
@endif

<style>
    /* Navbar Layout */
    .admin-navbar .container-fluid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem;
    }

    .admin-navbar .search-form {
        width: 250px;
    }

    .admin-navbar .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .admin-navbar .search-input {
        padding-left: 35px;
        border-radius: 2rem;
    }

    .admin-navbar .nav-user-info .user-name {
        font-weight: 500;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.6rem;
        padding: 0.2em 0.4em;
    }

    /* Mobile Search Form */
    .mobile-search-form {
        display: none;
        backdrop-filter: blur(6px);
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 1rem;
        padding: 0.75rem 1rem;
        margin-top: 0.25rem;
        animation: slideDown 0.3s ease;
    }

    .mobile-search-form.show {
        display: block;
    }

    .mobile-search-form .search-input {
        border: 1px solid #ccc;
        border-radius: 2rem;
        padding-left: 2.5rem;
        padding-right: 2.5rem;
        font-size: 0.95rem;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .mobile-search-form .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    #closeSearchBtn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.2rem;
    }

    /* Responsive Tweaks */
    @media (max-width: 992px) {
        .admin-navbar .search-form {
            display: none;
        }

        .admin-navbar .d-lg-none {
            display: block !important;
        }
    }

    @media (max-width: 576px) {
        .admin-navbar .user-name {
            display: none;
        }

        .admin-navbar .container-fluid {
            padding: 0.5rem;
        }

        .admin-navbar .navbar-brand {
            font-size: 1rem;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10%);
        }

        to {
            opacity: 1;
            transform: translateY(0%);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileSearchBtn = document.getElementById('mobileSearchBtn');
        const mobileSearchForm = document.getElementById('mobileSearchForm');
        const closeSearchBtn = document.getElementById('closeSearchBtn');

        if (mobileSearchBtn) {
            mobileSearchBtn.addEventListener('click', function() {
                mobileSearchForm.classList.add('show');
            });
        }

        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', function() {
                mobileSearchForm.classList.remove('show');
            });
        }
    });
</script>
