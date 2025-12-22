{{-- resources/views/pharmacy/layouts/topbar.blade.php --}}
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
            @php
                try {
                    $notificationCount = \DB::table('notifications')
                        ->where('notifiable_id', auth()->id())
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('is_read', false)
                        ->count();
                    echo '<span class="badge" id="notificationCount">' . $notificationCount . '</span>';
                } catch (\Exception $e) {
                    echo '<span class="badge" id="notificationCount">0</span>';
                }
            @endphp
        </div>

        <div class="user-profile dropdown">
            <button class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                         <img src="{{ auth()->user()->pharmacy->profile_image ? asset('storage/' . auth()->user()->pharmacy->profile_image) : asset('images/default-doctor.png') }}"
                             alt="{{ isset($pharmacy) ? $pharmacy->name : 'Pharmacy' }}" class="profile-img">
                <span>{{ Auth::user()->email }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('pharmacy.profile.index') }}"><i class="fas fa-user"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('pharmacy.settings') }}"><i class="fas fa-cog"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form-top" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
