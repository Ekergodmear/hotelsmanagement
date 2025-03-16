<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Quản Lý Khách Sạn') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-hotel me-2"></i>Admin</h3>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-home"></i>
                        <span>Trang chủ</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tổng quan</span>
                    </a>
                </li>

                <div class="menu-label">Quản lý khách sạn</div>

                <li>
                    <a href="{{ route('admin.hotels.index') }}" class="{{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                        <i class="fas fa-hotel"></i>
                        <span>Khách sạn</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.rooms.index') }}" class="{{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <i class="fas fa-bed"></i>
                        <span>Phòng</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.room-types.index') }}" class="{{ request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
                        <i class="fas fa-th-list"></i>
                        <span>Loại phòng</span>
                    </a>
                </li>

                <div class="menu-label">Quản lý đặt phòng</div>

                <li>
                    <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Đặt phòng</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tag"></i>
                        <span>Khách hàng</span>
                    </a>
                </li>

                <div class="menu-label">Quản lý hệ thống</div>

                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Quản lý người dùng</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt</span>
                    </a>
                </li>

                <div class="menu-label">Tài khoản</div>

                <li>
                    <a href="{{ route('user.profile') }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Hồ sơ</span>
                    </a>
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="toggle-sidebar" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="top-navbar-links">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                </div>

                <div class="user-dropdown">
                    <button class="dropdown-toggle" id="userDropdown">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3498db&color=fff" alt="User Avatar">
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdownMenu">
                        <a href="{{ route('user.profile') }}">
                            <i class="fas fa-user-circle me-2"></i>Hồ sơ
                        </a>
                        <a href="{{ route('settings.index') }}">
                            <i class="fas fa-cog me-2"></i>Cài đặt
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
        // Kiểm tra trạng thái sidebar từ localStorage khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            const sidebarState = localStorage.getItem('sidebar-state');

            // Nếu có trạng thái đã lưu, áp dụng nó
            if (sidebarState === 'hidden') {
                sidebar.classList.add('hidden');
                content.classList.add('full');
            } else {
                // Mặc định là hiển thị
                sidebar.classList.add('sidebar-visible');
                content.classList.add('content-with-sidebar');
            }
        });

        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');

            // Thay đổi cách toggle để sử dụng class mới
            if (sidebar.classList.contains('sidebar-visible') || !sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('sidebar-visible');
                sidebar.classList.add('hidden');
                content.classList.remove('content-with-sidebar');
                content.classList.add('full');
                localStorage.setItem('sidebar-state', 'hidden');
            } else {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('sidebar-visible');
                content.classList.remove('full');
                content.classList.add('content-with-sidebar');
                localStorage.setItem('sidebar-state', 'visible');
            }
        });

        // User dropdown
        document.getElementById('userDropdown').addEventListener('click', function() {
            document.getElementById('userDropdownMenu').classList.toggle('show');
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!document.getElementById('userDropdown').contains(e.target)) {
                document.getElementById('userDropdownMenu').classList.remove('show');
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
