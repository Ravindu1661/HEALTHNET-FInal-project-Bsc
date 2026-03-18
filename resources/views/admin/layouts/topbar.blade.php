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

        {{-- ── Notification Bell ── --}}
        <div class="notification-icon" id="notificationIcon" style="position:relative;cursor:pointer;">
            <i class="fas fa-bell"></i>
            @php
                try {
                    // ✅ FIX: user_id → notifiable_type + notifiable_id
                    $adminUnreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('notifiable_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                } catch (\Exception $e) {
                    $adminUnreadCount = 0;
                }
            @endphp
            <span class="badge" id="notificationCount"
                  style="{{ $adminUnreadCount > 0 ? '' : 'display:none;' }}">
                {{ $adminUnreadCount > 0 ? $adminUnreadCount : '' }}
            </span>
        </div>

        <div class="user-profile dropdown">
            <button class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin">
                <span>{{ Auth::user()->email }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.notifications.index') }}">
                        <i class="fas fa-bell"></i> Notifications
                        @if($adminUnreadCount > 0)
                            <span class="badge bg-danger ms-1" style="font-size:.65rem;">{{ $adminUnreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
// ── Auto-refresh notification badge every 60 seconds ──────────
(function () {
    const CSRF  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const badge = document.getElementById('notificationCount');

    function refreshBadge() {
        fetch('{{ route("admin.notifications.count") }}', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(d => {
            if (!badge) return;
            const cnt = parseInt(d.unread_count ?? 0);
            if (cnt > 0) {
                badge.textContent   = cnt;
                badge.style.display = 'flex';
            } else {
                badge.textContent   = '';
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
    }

    // Refresh every 60 s
    setInterval(refreshBadge, 60000);

    // Also refresh when notification panel "Mark all read" fires
    window.addEventListener('adminNotifUpdated', refreshBadge);
})();
</script>
