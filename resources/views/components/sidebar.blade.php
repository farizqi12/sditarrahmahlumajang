<div class="sidebar" id="sidebar">
    <h4 class="text-white text-center mb-4">E-Learning Admin</h4>

    <a href="{{ url('/admin/dashboard') }}"
        class="{{ Request::is('dashboard') || Request::is('*/dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>

    <a href="{{ url('/admin/courses') }}"
        class="{{ Request::is('courses') || Request::is('*/courses') ? 'active' : '' }}">
        <i class="bi bi-book me-2"></i> Courses
    </a>

    <a href="{{ url('/admin/users') }}" class="{{ Request::is('users') || Request::is('*/users') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Users
    </a>

    <a href="{{ url('/admin/reports') }}"
        class="{{ Request::is('reports') || Request::is('*/reports') ? 'active' : '' }}">
        <i class="bi bi-bar-chart me-2"></i> Reports
    </a>
</div>
