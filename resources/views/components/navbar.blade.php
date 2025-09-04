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

        <!-- Right Side Items -->
        <div class="d-flex align-items-center ms-auto">
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
                        <span class="user-name me-2 d-none d-sm-inline">Dummy User</span>
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
                        <form action="#" method="POST" class="d-inline w-100">
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

<style>
    /* Navbar Layout */
    .admin-navbar .container-fluid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem;
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

    /* Responsive Tweaks */
    @media (max-width: 992px) {
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
</style>