{{-- resources/views/hospital/layouts/topbar.blade.php --}}

@php
    try {
        $topHospital = DB::table('hospitals')
            ->where('user_id', auth()->id())
            ->first();

        $unread = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $topNotifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

    } catch (\Exception $e) {
        $topHospital      = null;
        $unread           = 0;
        $topNotifications = collect();
    }
@endphp

<header class="topbar" id="topbar">

    {{-- ── LEFT ── --}}
    <div class="topbar-left">
        <button class="toggle-btn" id="sidebarToggleBtn"
                onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-breadcrumb">
            <span class="breadcrumb-icon">
                <i class="fas fa-th-large"></i>
            </span>
            <h5 class="page-title-text">@yield('page-title', 'Dashboard')</h5>
        </div>
    </div>

    {{-- ── RIGHT ── --}}
    <div class="topbar-right">

        {{-- Current Date/Time --}}
        <div class="topbar-datetime d-none d-lg-flex">
            <i class="far fa-calendar-alt me-1"></i>
            <span id="topbarDate"></span>
        </div>

        {{-- ══ NOTIFICATION BELL ══ --}}
        <div class="tb-dropdown-wrap" id="notifWrap">
            <button class="tb-icon-btn" id="notifBtn" title="Notifications">
                <i class="fas fa-bell"></i>
                @if($unread > 0)
                    <span class="tb-badge" id="notifBadge">
                        {{ $unread > 9 ? '9+' : $unread }}
                    </span>
                @endif
            </button>

            <div class="tb-dropdown notif-dropdown" id="notifDropdown">
                {{-- Header --}}
                <div class="td-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-bell text-primary"></i>
                        <span class="fw-700">Notifications</span>
                        @if($unread > 0)
                            <span class="td-count-badge">{{ $unread }}</span>
                        @endif
                    </div>
                    @if($unread > 0)
                        <button class="td-action-btn" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-1"></i>Mark all
                        </button>
                    @endif
                </div>

                {{-- List --}}
                <div class="td-list" id="notifList">
                    @php
                        $iconMap = [
                            'appointment'  => ['icon' => 'calendar-check', 'color' => '#42a649'],
                            'payment'      => ['icon' => 'credit-card',    'color' => '#f39c12'],
                            'prescription' => ['icon' => 'pills',          'color' => '#3498db'],
                            'labreport'    => ['icon' => 'flask',          'color' => '#9b59b6'],
                            'general'      => ['icon' => 'bell',           'color' => '#95a5a6'],
                        ];
                    @endphp

                    @forelse($topNotifications as $n)
                        @php $ic = $iconMap[$n->type] ?? $iconMap['general']; @endphp
                        <div class="td-item {{ !$n->is_read ? 'unread' : '' }}"
                             onclick="markAsRead({{ $n->id }}, this)">
                            <div class="td-item-icon" style="background:{{ $ic['color'] }}">
                                <i class="fas fa-{{ $ic['icon'] }}"></i>
                            </div>
                            <div class="td-item-body">
                                <p class="td-item-title">{{ $n->title ?? 'Notification' }}</p>
                                <p class="td-item-msg">
                                    {{ Str::limit($n->message ?? '', 60) }}
                                </p>
                                <span class="td-item-time">
                                    <i class="far fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            @if(!$n->is_read)
                                <span class="td-unread-dot"></span>
                            @endif
                        </div>
                    @empty
                        <div class="td-empty">
                            <i class="fas fa-bell-slash"></i>
                            <p>No notifications yet</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="td-footer">
                    <a href="{{ route('hospital.notifications') }}">
                        View All Notifications
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ PROFILE DROPDOWN ══ --}}
        <div class="tb-dropdown-wrap" id="profileWrap">
            <button class="profile-btn" id="profileBtn">
                @if($topHospital && $topHospital->profile_image)
                    <img src="{{ asset('storage/'.$topHospital->profile_image) }}"
                         alt="Profile" class="profile-thumb"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <span class="profile-thumb-fallback" style="display:none;">
                        <i class="fas fa-hospital"></i>
                    </span>
                @else
                    <span class="profile-thumb-fallback">
                        <i class="fas fa-hospital"></i>
                    </span>
                @endif
                <div class="profile-btn-info d-none d-md-block">
                    <span class="profile-btn-name">
                        {{ Str::limit($topHospital->name ?? 'Hospital', 18) }}
                    </span>
                    <span class="profile-btn-role">Hospital Panel</span>
                </div>
                <i class="fas fa-chevron-down profile-chevron" id="profileChevron"></i>
            </button>

            <div class="tb-dropdown profile-dropdown" id="profileDropdown">
                {{-- Header --}}
                <div class="pd-header">
                    @if($topHospital && $topHospital->profile_image)
                        <img src="{{ asset('storage/'.$topHospital->profile_image) }}"
                             alt="Profile" class="pd-avatar"
                             onerror="this.style.display='none'">
                    @else
                        <div class="pd-avatar-fallback">
                            <i class="fas fa-hospital"></i>
                        </div>
                    @endif
                    <div class="pd-info">
                        <h6>{{ Str::limit($topHospital->name ?? 'Hospital', 22) }}</h6>
                        <p>{{ Str::limit(auth()->user()->email ?? '', 26) }}</p>
                        @php $st = $topHospital->status ?? 'pending'; @endphp
                        <span class="pd-status-pill pd-status-{{ $st }}">
                            <i class="fas fa-circle" style="font-size:.38rem;vertical-align:middle;"></i>
                            {{ ucfirst($st) }}
                        </span>
                    </div>
                </div>

                <div class="pd-divider"></div>

                <a href="{{ route('hospital.profile') }}" class="pd-item">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a href="{{ route('hospital.notifications') }}" class="pd-item">
                    <i class="fas fa-bell"></i> Notifications
                    @if($unread > 0)
                        <span class="ms-auto badge bg-danger rounded-pill"
                              style="font-size:.6rem;">{{ $unread }}</span>
                    @endif
                </a>
                <a href="{{ route('hospital.settings') }}" class="pd-item">
                    <i class="fas fa-cog"></i> Settings
                </a>

                <div class="pd-divider"></div>

                <a href="{{ route('logout') }}" class="pd-item pd-item-danger"
                   onclick="event.preventDefault();
                            document.getElementById('topbar-logout').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="topbar-logout" action="{{ route('logout') }}"
                      method="POST" class="d-none">@csrf</form>
            </div>
        </div>

    </div>{{-- /topbar-right --}}
</header>


{{-- ══════════════════════════════════════════════
     TOPBAR STYLES
══════════════════════════════════════════════ --}}
<style>
/* ── Shell ── */
.topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    right: 0;
    height: var(--topbar-height);
    background: #fff;
    border-bottom: 1px solid #e5ecf0;
    box-shadow: 0 2px 14px rgba(44,62,80,.07);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.4rem 0 1.1rem;
    z-index: 1010;
    transition: left var(--transition);
}
.topbar.sidebar-collapsed { left: var(--sidebar-collapsed); }

/* ── Left ── */
.topbar-left { display: flex; align-items: center; gap: .75rem; min-width: 0; }

.toggle-btn {
    background: transparent; border: none;
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem; color: var(--primary);
    cursor: pointer; flex-shrink: 0;
    transition: background .2s, color .2s;
}
.toggle-btn:hover { background: #e8f0fe; }

.page-breadcrumb {
    display: flex; align-items: center; gap: .5rem; min-width: 0;
}
.breadcrumb-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: .75rem; flex-shrink: 0;
}
.page-title-text {
    font-size: .92rem; font-weight: 700;
    color: var(--primary-dark); margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ── Right ── */
.topbar-right { display: flex; align-items: center; gap: .75rem; }

.topbar-datetime {
    display: flex; align-items: center;
    font-size: .75rem; color: #8a9ab0; font-weight: 500;
    background: #f6f8fd; padding: .3rem .75rem;
    border-radius: 8px; border: 1px solid #e5ecf0;
    white-space: nowrap;
}

/* ── Shared icon btn ── */
.tb-icon-btn {
    position: relative;
    width: 38px; height: 38px; border-radius: 9px;
    background: #f6f8fd; border: 1px solid #e5ecf0;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; color: var(--primary);
    cursor: pointer;
    transition: background .2s, box-shadow .2s, color .2s;
}
.tb-icon-btn:hover {
    background: var(--primary); color: #fff;
    box-shadow: 0 4px 12px rgba(41,105,191,.25);
}

/* Badge */
.tb-badge {
    position: absolute; top: 2px; right: 2px;
    min-width: 17px; height: 17px;
    background: #e74c3c; color: #fff;
    font-size: .57rem; font-weight: 700;
    border-radius: 99px; padding: 0 3px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff; pointer-events: none;
}

/* ══ Shared Dropdown ══ */
.tb-dropdown-wrap { position: relative; }

.tb-dropdown {
    position: absolute;
    top: calc(100% + 10px); right: 0;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 12px 40px rgba(44,62,80,.14);
    border: 1px solid #e8edf2;
    opacity: 0; visibility: hidden;
    transform: translateY(-8px);
    transition: opacity .22s, transform .22s, visibility .22s;
    z-index: 1100; overflow: hidden;
}
.tb-dropdown.show {
    opacity: 1; visibility: visible; transform: translateY(0);
}

/* Dropdown Header */
.td-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.1rem .7rem;
    border-bottom: 1px solid #f0f4f8;
    font-size: .88rem; font-weight: 700; color: var(--primary);
}
.td-count-badge {
    background: #e74c3c; color: #fff;
    font-size: .6rem; font-weight: 700;
    padding: 1px 6px; border-radius: 99px;
}
.td-action-btn {
    background: transparent; border: 1px solid #dce3ea;
    color: #555; font-size: .7rem; padding: .22rem .6rem;
    border-radius: 6px; cursor: pointer;
    transition: background .2s, color .2s;
    white-space: nowrap;
}
.td-action-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

/* Notification List */
.notif-dropdown { width: 340px; }
.td-list { max-height: 330px; overflow-y: auto; }
.td-list::-webkit-scrollbar { width: 3px; }
.td-list::-webkit-scrollbar-thumb { background: #dce3ea; border-radius: 3px; }

.td-item {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .8rem 1.1rem; border-bottom: 1px solid #f5f7fa;
    cursor: pointer; transition: background .18s; position: relative;
}
.td-item:last-child { border-bottom: none; }
.td-item:hover     { background: #f8fafc; }
.td-item.unread    { background: #eef6ff; }
.td-item.unread:hover { background: #e4f0ff; }

.td-item-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .8rem; flex-shrink: 0;
}
.td-item-body { flex: 1; min-width: 0; }
.td-item-title {
    font-size: .8rem; font-weight: 600;
    color: #1a2332; margin: 0 0 .18rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.td-item-msg {
    font-size: .73rem; color: #6c7a8d;
    margin: 0 0 .22rem; line-height: 1.4;
}
.td-item-time { font-size: .67rem; color: #aab4be; }
.td-unread-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--primary); flex-shrink: 0; margin-top: 5px;
}
.td-empty {
    text-align: center; padding: 2.2rem 1rem; color: #aab4be;
}
.td-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; }
.td-empty p { font-size: .8rem; margin: 0; }
.td-footer {
    text-align: center; padding: .6rem;
    border-top: 1px solid #f0f4f8;
}
.td-footer a {
    font-size: .77rem; font-weight: 600;
    color: var(--primary); text-decoration: none;
}
.td-footer a:hover { color: var(--primary-dark); }

/* ══ Profile Button ══ */
.profile-btn {
    display: flex; align-items: center; gap: .6rem;
    background: #f6f8fd; border: 1px solid #e5ecf0;
    border-radius: 10px; padding: .3rem .7rem .3rem .35rem;
    cursor: pointer;
    transition: background .2s, box-shadow .2s;
}
.profile-btn:hover {
    background: #edf2fb;
    box-shadow: 0 3px 10px rgba(41,105,191,.12);
}
.profile-thumb {
    width: 32px; height: 32px; border-radius: 8px;
    object-fit: cover; border: 2px solid #dce6f7;
}
.profile-thumb-fallback {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg,#e8f0fe,#d0e4ff);
    border: 2px solid #dce6f7;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: .82rem;
}
.profile-btn-info {
    display: flex; flex-direction: column; align-items: flex-start; line-height: 1.2;
}
.profile-btn-name {
    font-size: .78rem; font-weight: 700; color: #1a2332;
    max-width: 130px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
}
.profile-btn-role { font-size: .65rem; color: #8a9ab0; }
.profile-chevron {
    font-size: .68rem; color: #8a9ab0;
    transition: transform .25s; margin-left: 2px;
}
.profile-chevron.rotated { transform: rotate(180deg); }

/* Profile Dropdown */
.profile-dropdown { width: 240px; }
.pd-header {
    display: flex; align-items: center; gap: .75rem;
    padding: .95rem 1.1rem .85rem;
    background: linear-gradient(135deg,#f0f6ff,#e8f0fb);
    border-bottom: 1px solid #dce6f7;
}
.pd-avatar {
    width: 44px; height: 44px; border-radius: 10px;
    object-fit: cover; border: 2px solid #c8d8f0; flex-shrink: 0;
}
.pd-avatar-fallback {
    width: 44px; height: 44px; border-radius: 10px;
    background: linear-gradient(135deg,#e8f0fe,#d0e4ff);
    border: 2px solid #c8d8f0;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 1.1rem; flex-shrink: 0;
}
.pd-info { min-width: 0; }
.pd-info h6 {
    font-size: .82rem; font-weight: 700;
    color: #1a2332; margin: 0 0 .12rem;
}
.pd-info p {
    font-size: .68rem; color: #8a9ab0;
    margin: 0 0 .28rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 145px;
}
.pd-status-pill {
    font-size: .6rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 99px;
    display: inline-flex; align-items: center; gap: 3px;
}
.pd-status-approved  { background:#e9f7ee; color:#27ae60; }
.pd-status-pending   { background:#fef8e7; color:#f39c12; }
.pd-status-suspended { background:#fdecea; color:#e74c3c; }
.pd-status-rejected  { background:#f0f0f0; color:#777;    }

.pd-divider { height: 1px; background: #f0f4f8; margin: .25rem 0; }

.pd-item {
    display: flex; align-items: center; gap: .65rem;
    padding: .6rem 1.1rem; font-size: .8rem;
    color: #374151; text-decoration: none;
    transition: background .18s, color .18s;
}
.pd-item:hover { background: #f0f6ff; color: var(--primary); }
.pd-item i { width: 16px; text-align: center; color: #8a9ab0; font-size: .82rem; }
.pd-item:hover i { color: var(--primary); }
.pd-item-danger { color: #e74c3c !important; }
.pd-item-danger i { color: #e74c3c !important; }
.pd-item-danger:hover { background: #fdecea !important; }

/* ── Responsive ── */
@media (max-width: 991.98px) {
    .topbar { left: 0 !important; }
}
@media (max-width: 575.98px) {
    .topbar { padding: 0 .85rem; }
    .notif-dropdown { width: calc(100vw - 1.5rem); right: -60px; }
    .page-title-text { font-size: .82rem; }
}
</style>


{{-- ══════════════════════════════════════════════
     TOPBAR SCRIPTS
══════════════════════════════════════════════ --}}
<script>
(function () {
    const notifBtn        = document.getElementById('notifBtn');
    const notifDropdown   = document.getElementById('notifDropdown');
    const profileBtn      = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const profileChevron  = document.getElementById('profileChevron');

    function closeAll() {
        notifDropdown?.classList.remove('show');
        profileDropdown?.classList.remove('show');
        profileChevron?.classList.remove('rotated');
    }

    notifBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        const wasOpen = notifDropdown.classList.contains('show');
        closeAll();
        if (!wasOpen) notifDropdown.classList.add('show');
    });

    profileBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        const wasOpen = profileDropdown.classList.contains('show');
        closeAll();
        if (!wasOpen) {
            profileDropdown.classList.add('show');
            profileChevron?.classList.add('rotated');
        }
    });

    document.addEventListener('click', closeAll);
    [notifDropdown, profileDropdown].forEach(el =>
        el?.addEventListener('click', e => e.stopPropagation())
    );

    // ── Mark single read ──
    window.markAsRead = function (id, el) {
        fetch(`/hospital/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            el?.classList.remove('unread');
            el?.querySelector('.td-unread-dot')?.remove();
            const badge = document.getElementById('notifBadge');
            if (badge) {
                let c = parseInt(badge.textContent) || 1;
                c--;
                if (c <= 0) badge.remove();
                else badge.textContent = c > 9 ? '9+' : c;
            }
        })
        .catch(console.error);
    };

    // ── Mark all read ──
    window.markAllAsRead = function () {
        fetch('/hospital/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            document.querySelectorAll('.td-item.unread').forEach(el => {
                el.classList.remove('unread');
                el.querySelector('.td-unread-dot')?.remove();
            });
            document.getElementById('notifBadge')?.remove();
            document.querySelector('.td-action-btn')?.remove();
            document.querySelector('.td-count-badge')?.remove();
        })
        .catch(console.error);
    };

    // ── Toggle Sidebar ──
    window.toggleSidebar = function () {
        const sidebar  = document.getElementById('sidebar');
        const mainArea = document.getElementById('mainArea');
        const topbar   = document.getElementById('topbar');
        const overlay  = document.getElementById('sidebarOverlay');

        if (window.innerWidth <= 991) {
            sidebar?.classList.toggle('mobile-open');
            overlay?.classList.toggle('show');
            document.body.style.overflow =
                sidebar?.classList.contains('mobile-open') ? 'hidden' : '';
        } else {
            sidebar?.classList.toggle('collapsed');
            const isCollapsed = sidebar?.classList.contains('collapsed');
            mainArea?.classList.toggle('sidebar-collapsed', isCollapsed);
            topbar?.classList.toggle('sidebar-collapsed', isCollapsed);
            localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');
        }
    };

    // ── Restore collapse state ──
    document.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth > 991 && localStorage.getItem('sidebarCollapsed') === '1') {
            const sidebar  = document.getElementById('sidebar');
            const mainArea = document.getElementById('mainArea');
            const topbar   = document.getElementById('topbar');
            sidebar?.classList.add('collapsed');
            mainArea?.classList.add('sidebar-collapsed');
            topbar?.classList.add('sidebar-collapsed');
        }

        // Live clock
        function updateClock() {
            const el = document.getElementById('topbarDate');
            if (!el) return;
            const now = new Date();
            el.textContent = now.toLocaleDateString('en-US', {
                weekday:'short', month:'short', day:'numeric', year:'numeric'
            });
        }
        updateClock();
        setInterval(updateClock, 60000);
    });

    // Close on ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeAll();
    });

})();
</script>
