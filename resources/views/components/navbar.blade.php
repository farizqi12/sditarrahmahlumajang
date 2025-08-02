<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <!-- Sidebar Toggle and Brand -->
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-2 sidebar-toggler" id="sidebarToggler">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="navbar-brand m-0">Dashboard</h5>
        </div>

        <!-- Search Form (Visible on desktop) -->
        <form class="search-form d-none d-lg-flex ms-auto">
            <div class="position-relative w-100">
                <i class="bi bi-search search-icon"></i>
                <input class="form-control search-input" type="search" placeholder="Search courses..."
                    aria-label="Search">
            </div>
        </form>

        <!-- Right Side Items -->
        <div class="d-flex align-items-center ms-auto">
            <!-- Mobile Search Button -->
            <button class="btn btn-link text-dark me-2 d-lg-none" id="mobileSearchBtn">
                <i class="bi bi-search"></i>
            </button>

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

            <!-- User Dropdown with Admin User and Icon -->
            <div class="dropdown">
                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                    <div class="nav-user-info d-flex align-items-center">
                        @if(Auth::check())
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

<!-- Mobile Search Form (Hidden by default) -->
<div class="container-fluid mobile-search-form d-none" id="mobileSearchForm" style="background: #f8f9fa; padding: 10px;">
    <form class="d-flex">
        <div class="position-relative w-100">
            <i class="bi bi-search search-icon"></i>
            <input class="form-control search-input" type="search" placeholder="Search courses..." aria-label="Search">
            <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y" id="closeSearchBtn">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </form>
</div>

<style>
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

  .mobile-search-form {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

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
        font-size: 1rem; /* Perkecil font size dashboard */
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchForm = document.getElementById('mobileSearchForm');
    const closeSearchBtn = document.getElementById('closeSearchBtn');

    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', function () {
            mobileSearchForm.classList.toggle('d-none');
        });
    }

    if (closeSearchBtn) {
        closeSearchBtn.addEventListener('click', function () {
            mobileSearchForm.classList.add('d-none');
        });
    }
});
</script>

