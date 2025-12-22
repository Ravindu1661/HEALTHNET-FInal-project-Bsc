<!-- Top Navigation -->
<nav class="top-navbar">
    <div class="nav-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
    </div>
    <div class="nav-right">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search anything...">
        </div>
        <div class="notification-icon" id="notificationIcon">
            <i class="fas fa-bell"></i>
            <span class="badge" id="notificationCount">
                @php
                    try {
                        $notificationCount = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                        echo $notificationCount;
                    } catch (\Exception $e) {
                        echo 0;
                    }
                @endphp
            </span>
        </div>
        <div class="user-profile dropdown">
            <button class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin">
                <span>{{ Auth::user()->email }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                    <i class="fas fa-user"></i> Profile
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
            </ul>
        </div>
    </div>
</nav>
