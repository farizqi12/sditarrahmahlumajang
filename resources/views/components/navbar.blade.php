<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <!-- Sidebar Toggle and Brand -->
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-2 sidebar-toggler" id="sidebarToggler">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>
            <h5 class="navbar-brand m-0">Dashboard</h5>
        </div>

        <!-- Search Form (Visible on desktop) -->
        <form class="search-form d-flex">
            <div class="position-relative w-100">
                <i class="bi bi-search search-icon"></i>
                <input class="form-control search-input" type="search" placeholder="Search courses..."
                    aria-label="Search">
            </div>
        </form>

        <!-- Right Side Items -->
        <div class="d-flex align-items-center">
            <!-- Mobile Search Button -->
            <button class="btn btn-link text-dark me-2 mobile-search-btn">
                <i class="bi bi-search"></i>
            </button>

            <!-- Notifications -->
            <div class="dropdown me-3 position-relative">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell" style="font-size: 1.25rem;"></i>
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

            <!-- User Dropdown with Admin User and Icon -->
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <div class="nav-user-info">
                        <span class="user-name me-3 d-none d-sm-inline">Admin User</span>
                        <i class="bi bi-person-circle fs-4"></i>
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

<!-- Mobile Search Form (Hidden by default) -->
<div class="container-fluid mobile-search-form" style="display: none; background: #f8f9fa; padding: 10px;">
    <form class="d-flex">
        <div class="position-relative w-100">
            <i class="bi bi-search search-icon"></i>
            <input class="form-control search-input" type="search" placeholder="Search courses..." aria-label="Search">
            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y close-search">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </form>
</div>
