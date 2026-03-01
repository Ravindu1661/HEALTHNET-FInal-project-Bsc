{{-- resources/views/hospital/layouts/sidebar.blade.php --}}

@php
    try {
        $sidebarHospital = DB::table('hospitals')
            ->where('user_id', auth()->id())
            ->first();

        $pendingAppointments = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $sidebarHospital->id ?? 0)
            ->where('status', 'pending')
            ->count();

        $todayAppointments = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $sidebarHospital->id ?? 0)
            ->whereDate('appointment_date', \Carbon\Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $pendingDoctors = DB::table('doctor_workplaces')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $sidebarHospital->id ?? 0)
            ->where('status', 'pending')
            ->count();

        $unreadNotifCount = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->count();

    } catch (\Exception $e) {
        $sidebarHospital     = null;
        $pendingAppointments = 0;
        $todayAppointments   = 0;
        $pendingDoctors      = 0;
        $unreadNotifCount    = 0;
    }
@endphp

<aside class="sidebar" id="sidebar">

    {{-- ══════════ LOGO ══════════ --}}
    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="logo-text">
            <span class="logo-main">Health</span><span class="logo-accent">Net</span>
        </div>
        <button class="sidebar-close-btn d-lg-none" onclick="closeSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ══════════ HOSPITAL PROFILE CARD ══════════ --}}
    <div class="sidebar-profile">
        <div class="profile-avatar-wrap">
            @if($sidebarHospital && $sidebarHospital->profile_image)
                <img src="{{ asset('storage/' . $sidebarHospital->profile_image) }}"
                     alt="Hospital"
                     class="sidebar-avatar"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="sidebar-avatar-fallback" style="display:none;">
                    <i class="fas fa-hospital"></i>
                </div>
            @else
                <div class="sidebar-avatar-fallback">
                    <i class="fas fa-hospital"></i>
                </div>
            @endif
            <span class="avatar-status {{ ($sidebarHospital->status ?? 'pending') === 'approved' ? 'online' : 'offline' }}"></span>
        </div>
        <div class="profile-details">
            <h6 class="profile-name">
                {{ Str::limit($sidebarHospital->name ?? 'Hospital', 20) }}
            </h6>
            <span class="profile-badge badge-{{ $sidebarHospital->status ?? 'pending' }}">
                <i class="fas fa-circle me-1" style="font-size:.42rem;vertical-align:middle;"></i>
                {{ ucfirst($sidebarHospital->status ?? 'Pending') }}
            </span>
        </div>
    </div>

    {{-- ══════════ NAVIGATION ══════════ --}}
    <nav class="sidebar-nav">
        <ul class="nav-list">

            {{-- ── MAIN ── --}}
            <li class="nav-section-label">Main</li>

            <li class="nav-item">
                <a href="{{ route('hospital.dashboard') }}"
                   class="nav-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- ── APPOINTMENTS ── --}}
            <li class="nav-item">
                <a href="{{ route('hospital.appointments') }}"
                   class="nav-link {{ request()->routeIs('hospital.appointments*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                    <span class="nav-text">Appointments</span>
                    @if($todayAppointments > 0)
                        <span class="nav-badge">
                            {{ $todayAppointments > 99 ? '99+' : $todayAppointments }}
                        </span>
                    @endif
                </a>
            </li>

            {{-- ── DOCTORS ── --}}
            <li class="nav-item">
                <a href="{{ route('hospital.doctors') }}"
                   class="nav-link {{ request()->routeIs('hospital.doctors*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-user-md"></i></span>
                    <span class="nav-text">Doctors</span>
                    @if($pendingDoctors > 0)
                        <span class="nav-badge nav-badge-warning">
                            {{ $pendingDoctors > 99 ? '99+' : $pendingDoctors }}
                        </span>
                    @endif
                </a>
            </li>

            {{-- ── ANALYTICS ── --}}
            <li class="nav-section-label">Analytics</li>

            <li class="nav-item">
                <a href="{{ route('hospital.reviews') }}"
                   class="nav-link {{ request()->routeIs('hospital.reviews*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-star"></i></span>
                    <span class="nav-text">Reviews & Ratings</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('hospital.reports') }}"
                   class="nav-link {{ request()->routeIs('hospital.reports*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                    <span class="nav-text">Reports</span>
                </a>
            </li>

            {{-- ── ACCOUNT ── --}}
            <li class="nav-section-label">Account</li>

            <li class="nav-item">
                <a href="{{ route('hospital.profile') }}"
                   class="nav-link {{ request()->routeIs('hospital.profile*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-hospital"></i></span>
                    <span class="nav-text">Hospital Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('hospital.notifications') }}"
                   class="nav-link {{ request()->routeIs('hospital.notifications*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-bell"></i></span>
                    <span class="nav-text">Notifications</span>
                    @if($unreadNotifCount > 0)
                        <span class="nav-badge nav-badge-danger">
                            {{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}
                        </span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('hospital.settings') }}"
                   class="nav-link {{ request()->routeIs('hospital.settings*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span class="nav-text">Settings</span>
                </a>
            </li>

            {{-- ── DIVIDER + LOGOUT ── --}}
            <li class="nav-divider"></li>

            <li class="nav-item">
                <a href="{{ route('logout') }}"
                   class="nav-link nav-link-logout"
                   onclick="event.preventDefault();
                            document.getElementById('sidebar-logout-form').submit();">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="nav-text">Logout</span>
                </a>
                <form id="sidebar-logout-form"
                      action="{{ route('logout') }}"
                      method="POST"
                      class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </nav>

    {{-- ══════════ SIDEBAR FOOTER ══════════ --}}
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            <i class="fas fa-shield-alt me-2"></i>
            <span>HealthNet v1.0</span>
        </div>
    </div>

</aside>

{{-- Mobile Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>


{{-- ══════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════ --}}
<style>
:root {
    --sidebar-width  : 260px;
    --sidebar-bg     : #0f2544;
    --sidebar-hover  : rgba(255,255,255,.07);
    --sidebar-active : rgba(41,105,191,.45);
    --sidebar-border : rgba(255,255,255,.08);
    --sidebar-text   : #c8d8f0;
    --sidebar-muted  : rgba(200,216,240,.45);
    --accent         : #42a649;
    --primary        : #2969bf;
}

/* ── Shell ── */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    display: flex;
    flex-direction: column;
    z-index: 1020;
    transition: transform .3s cubic-bezier(.4,0,.2,1),
                width .3s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
    box-shadow: 4px 0 24px rgba(0,0,0,.25);
}

/* ── Collapsed (desktop) ── */
.sidebar.collapsed { width: 68px; }
.sidebar.collapsed .nav-text,
.sidebar.collapsed .nav-badge,
.sidebar.collapsed .nav-section-label,
.sidebar.collapsed .profile-details,
.sidebar.collapsed .logo-text,
.sidebar.collapsed .sidebar-footer-info span {
    opacity: 0;
    pointer-events: none;
}
.sidebar.collapsed .sidebar-profile { justify-content: center; padding: .9rem 0; }
.sidebar.collapsed .nav-link        { justify-content: center; padding: .75rem 0; }
.sidebar.collapsed .nav-icon        { margin: 0; }
.sidebar.collapsed .sidebar-logo    { justify-content: center; padding: 1.1rem 0; }
.sidebar.collapsed .logo-text       { width: 0; overflow: hidden; }

/* ── Logo ── */
.sidebar-logo {
    display: flex;
    align-items: center;
    gap: .7rem;
    padding: 1.1rem 1.2rem;
    border-bottom: 1px solid var(--sidebar-border);
    flex-shrink: 0;
    min-height: 62px;
}
.logo-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(41,105,191,.5);
}
.logo-text { display: flex; align-items: baseline; gap: 1px; transition: opacity .25s; }
.logo-main   { font-size: 1.05rem; font-weight: 800; color: #fff; }
.logo-accent { font-size: 1.05rem; font-weight: 800; color: var(--accent); }

.sidebar-close-btn {
    margin-left: auto;
    background: transparent; border: none;
    color: var(--sidebar-muted); font-size: .9rem;
    cursor: pointer; padding: .3rem .5rem; border-radius: 6px;
    transition: color .2s, background .2s; line-height: 1;
}
.sidebar-close-btn:hover { color: #fff; background: var(--sidebar-hover); }

/* ── Profile Card ── */
.sidebar-profile {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .9rem 1.2rem;
    border-bottom: 1px solid var(--sidebar-border);
    background: rgba(0,0,0,.18);
    flex-shrink: 0;
    transition: padding .25s, justify-content .25s;
}
.profile-avatar-wrap { position: relative; flex-shrink: 0; }
.sidebar-avatar {
    width: 42px; height: 42px;
    border-radius: 10px; object-fit: cover;
    border: 2px solid rgba(255,255,255,.15);
    display: block;
}
.sidebar-avatar-fallback {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(41,105,191,.4), rgba(66,166,73,.3));
    border: 2px solid rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.7); font-size: 1.1rem;
}
.avatar-status {
    position: absolute; bottom: -2px; right: -2px;
    width: 12px; height: 12px; border-radius: 50%;
    border: 2px solid var(--sidebar-bg);
}
.avatar-status.online  { background: var(--accent); }
.avatar-status.offline { background: #e74c3c; }

.profile-details { min-width: 0; transition: opacity .25s; }
.profile-name {
    font-size: .82rem; font-weight: 700;
    color: #fff; margin: 0 0 .22rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.profile-badge {
    font-size: .62rem; font-weight: 700;
    padding: .18rem .6rem; border-radius: 99px;
    display: inline-flex; align-items: center; gap: 3px;
}
.badge-approved  { background: rgba(66,166,73,.2);   color: #7ddd84; }
.badge-pending   { background: rgba(243,156,18,.2);  color: #f8c471; }
.badge-suspended { background: rgba(231,76,60,.2);   color: #f1948a; }
.badge-rejected  { background: rgba(149,165,166,.2); color: #bdc3c7; }

/* ── Nav ── */
.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: .5rem 0;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.08) transparent;
}
.sidebar-nav::-webkit-scrollbar       { width: 3px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

.nav-list { list-style: none; margin: 0; padding: 0; }

.nav-section-label {
    font-size: .61rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--sidebar-muted);
    padding: .85rem 1.3rem .3rem;
    white-space: nowrap; overflow: hidden;
    transition: opacity .25s;
}

.nav-item { margin: 1px 8px; }

.nav-link {
    display: flex; align-items: center; gap: .75rem;
    padding: .62rem .85rem;
    border-radius: 9px;
    text-decoration: none;
    color: var(--sidebar-text);
    font-size: .83rem; font-weight: 500;
    transition: background .2s, color .2s;
    position: relative; white-space: nowrap;
    overflow: hidden;
}
.nav-link:hover  { background: var(--sidebar-hover); color: #fff; }
.nav-link.active {
    background: var(--sidebar-active);
    color: #fff; font-weight: 700;
    box-shadow: inset 3px 0 0 var(--primary);
}
.nav-link.active .nav-icon { color: var(--accent); }
.nav-link:hover .nav-icon  { color: #fff; }

.nav-icon {
    width: 22px; text-align: center;
    font-size: .88rem; color: var(--sidebar-muted);
    flex-shrink: 0; transition: color .2s;
}

.nav-text { flex: 1; transition: opacity .25s; }

/* Badges */
.nav-badge {
    margin-left: auto;
    background: var(--primary);
    color: #fff; font-size: .6rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 99px;
    min-width: 20px; text-align: center;
    flex-shrink: 0; transition: opacity .25s; line-height: 1.4;
}
.nav-badge-danger  { background: #e74c3c !important; }
.nav-badge-warning { background: #f39c12 !important; }

.nav-divider {
    height: 1px;
    background: var(--sidebar-border);
    margin: .5rem 1rem;
}

.nav-link-logout         { color: #f1948a; }
.nav-link-logout:hover   { background: rgba(231,76,60,.15); color: #e74c3c; }
.nav-link-logout .nav-icon { color: #f1948a; }
.nav-link-logout:hover .nav-icon { color: #e74c3c; }

/* ── Footer ── */
.sidebar-footer {
    padding: .75rem 1.2rem;
    border-top: 1px solid var(--sidebar-border);
    flex-shrink: 0;
}
.sidebar-footer-info {
    display: flex; align-items: center;
    font-size: .7rem; color: var(--sidebar-muted);
    white-space: nowrap; overflow: hidden;
    transition: opacity .25s;
}

/* ── Overlay ── */
.sidebar-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,23,42,.55);
    z-index: 1015;
    backdrop-filter: blur(3px);
}
.sidebar-overlay.show { display: block; }

/* ══ RESPONSIVE ══ */
@media (max-width: 991.98px) {
    .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width) !important;
        box-shadow: none;
    }
    .sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 6px 0 32px rgba(0,0,0,.4);
    }
    /* Main content — no offset on mobile */
    body .main-wrapper { margin-left: 0 !important; }
}

@media (max-width: 575.98px) {
    .sidebar { max-width: 280px; }
}

/* ── Tooltip for collapsed state ── */
.sidebar.collapsed .nav-link[data-tooltip]::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 72px; top: 50%;
    transform: translateY(-50%);
    background: #1e3a5f;
    color: #fff;
    font-size: .75rem; font-weight: 600;
    padding: .3rem .75rem;
    border-radius: 7px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity .2s;
    box-shadow: 0 4px 12px rgba(0,0,0,.3);
    z-index: 9999;
}
.sidebar.collapsed .nav-link[data-tooltip]:hover::after { opacity: 1; }
</style>


{{-- ══════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════ --}}
<script>
(function () {

    // ── Desktop collapse / Mobile slide ──
    window.toggleSidebar = function () {
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        const wrapper  = document.querySelector('.main-wrapper');

        if (window.innerWidth <= 991) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
            document.body.style.overflow =
                sidebar.classList.contains('mobile-open') ? 'hidden' : '';
        } else {
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            if (wrapper) {
                wrapper.style.marginLeft = isCollapsed ? '68px' : '260px';
            }
            // Persist state
            localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');
        }
    };

    // ── Close mobile sidebar ──
    window.closeSidebar = function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    };

    // ── Restore collapse state on desktop ──
    document.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth > 991) {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
            const sidebar  = document.getElementById('sidebar');
            const wrapper  = document.querySelector('.main-wrapper');
            if (isCollapsed && sidebar) {
                sidebar.classList.add('collapsed');
                if (wrapper) wrapper.style.marginLeft = '68px';
            }
        }

        // ── Add tooltips for collapsed nav ──
        document.querySelectorAll('.nav-link').forEach(link => {
            const text = link.querySelector('.nav-text');
            if (text) {
                link.setAttribute('data-tooltip', text.textContent.trim());
            }
        });
    });

    // ── Close on ESC ──
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // ── Close on resize to desktop ──
    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) {
            closeSidebar();
        }
    });

})();
</script>
