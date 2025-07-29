<div class="sidebar" id="sidebar">
    <h4 class="text-white text-center mb-4">E-Learning Admin</h4>

    <a href="{{ url('/dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>

    <a href="{{ url('/courses') }}" class="{{ Request::is('courses') ? 'active' : '' }}">
        <i class="bi bi-book me-2"></i> Courses
    </a>

    <a href="{{ url('/users') }}" class="{{ Request::is('users') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Users
    </a>

    <a href="{{ url('/reports') }}" class="{{ Request::is('reports') ? 'active' : '' }}">
        <i class="bi bi-bar-chart me-2"></i> Reports
    </a>

    <a href="{{ url('/settings') }}" class="{{ Request::is('settings') ? 'active' : '' }}">
        <i class="bi bi-gear me-2"></i> Settings
    </a>
</div>
