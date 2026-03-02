{{-- resources/views/medical_centre/layouts/partials/sidebar.blade.php --}}
<aside class="mc-sidebar" id="mcSidebar">
<style>
.mc-brand {
    display: flex; align-items: center; gap: .75rem;
    padding: 1.1rem 1.1rem .9rem;
    border-bottom: 1px solid rgba(255,255,255,.10);
    min-height: var(--topbar-h); flex-shrink: 0; overflow: hidden;
}
.mc-brand-icon {
    width: 38px; height: 38px; background: rgba(255,255,255,.18);
    border-radius: 10px; display: flex; align-items: center;
    justify-content: center; color: #fff; font-size: .95rem; flex-shrink: 0;
}
.mc-brand-text { overflow: hidden; white-space: nowrap; }
.mc-brand-text h6 { font-size: .88rem; font-weight: 800; color: #fff; margin: 0; line-height: 1.2; }
.mc-brand-text span { font-size: .68rem; color: rgba(255,255,255,.65); font-weight: 500; }

.mc-centre-card {
    margin: .75rem .85rem; background: rgba(255,255,255,.10);
    border-radius: 10px; padding: .7rem .85rem;
    display: flex; align-items: center; gap: .65rem;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid rgba(255,255,255,.12);
}
.mc-centre-avatar {
    width: 36px; height: 36px; border-radius: 9px; object-fit: cover;
    flex-shrink: 0; border: 2px solid rgba(255,255,255,.3);
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .8rem; font-weight: 700; overflow: hidden;
}
.mc-centre-info { overflow: hidden; flex: 1; }
.mc-centre-info h6 {
    font-size: .75rem; font-weight: 700; color: #fff; margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.mc-centre-info span { font-size: .65rem; color: rgba(255,255,255,.65); display: flex; align-items: center; gap: .3rem; }
.mc-centre-info .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.dot-active    { background: #2ecc71; }
.dot-pending   { background: #f39c12; }
.dot-suspended { background: #e74c3c; }

.mc-nav-scroll {
    flex: 1; overflow-y: auto; overflow-x: hidden; padding: .5rem 0;
}
.mc-nav-scroll::-webkit-scrollbar { width: 3px; }
.mc-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 99px; }

.mc-nav-section {
    font-size: .62rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .08em; color: rgba(255,255,255,.4);
    padding: .9rem 1.1rem .35rem; white-space: nowrap; overflow: hidden;
}
.mc-sidebar.collapsed .mc-nav-section { opacity: 0; pointer-events: none; }

.mc-nav-link {
    display: flex; align-items: center; gap: .75rem;
    padding: .6rem 1.1rem; color: rgba(255,255,255,.75);
    font-size: .82rem; font-weight: 600; border: none;
    background: transparent; width: 100%; text-align: left;
    cursor: pointer; white-space: nowrap; overflow: hidden;
    transition: var(--transition); border-left: 3px solid transparent;
    position: relative; text-decoration: none;
}
.mc-nav-link:hover { background: rgba(255,255,255,.10); color: #fff; }
.mc-nav-link.active { background: rgba(255,255,255,.16); color: #fff; border-left-color: #2ecc71; }
.mc-nav-link .nav-icon { width: 20px; text-align: center; font-size: .85rem; flex-shrink: 0; color: inherit; }
.mc-nav-link .nav-label { flex: 1; overflow: hidden; text-overflow: ellipsis; }
.mc-nav-link .nav-badge {
    background: #e74c3c; color: #fff; font-size: .6rem; font-weight: 800;
    padding: .1rem .38rem; border-radius: 99px; flex-shrink: 0;
    min-width: 18px; text-align: center;
}
.mc-nav-link .nav-arrow { font-size: .65rem; flex-shrink: 0; transition: transform .25s; color: rgba(255,255,255,.45); }

.mc-nav-sub { display: none; background: rgba(0,0,0,.15); border-left: 3px solid rgba(255,255,255,.08); margin-left: 20px; }
.mc-nav-group.open > .mc-nav-sub { display: block; }
.mc-nav-group.open > .mc-nav-link .nav-arrow { transform: rotate(90deg); }
.mc-nav-sub .mc-nav-link { font-size: .78rem; padding: .5rem 1rem .5rem 1.5rem; border-left: none; }
.mc-nav-sub .mc-nav-link.active { border-left: none; background: rgba(255,255,255,.12); color: #fff; }

.mc-sidebar.collapsed .nav-label,
.mc-sidebar.collapsed .nav-badge,
.mc-sidebar.collapsed .nav-arrow,
.mc-sidebar.collapsed .mc-nav-sub,
.mc-sidebar.collapsed .mc-centre-info,
.mc-sidebar.collapsed .mc-brand-text { display: none; }

.mc-sidebar.collapsed .mc-nav-link { justify-content: center; border-left: none; padding: .65rem; }
.mc-sidebar.collapsed .mc-nav-link.active { border-left: none; }
.mc-sidebar.collapsed .mc-nav-link:hover::after {
    content: attr(data-tooltip);
    position: absolute; left: calc(var(--sidebar-w-col) + 8px);
    background: #1a2332; color: #fff; font-size: .75rem; font-weight: 600;
    padding: .35rem .75rem; border-radius: 8px; white-space: nowrap;
    pointer-events: none; z-index: 9999; box-shadow: var(--shadow-md);
}
.mc-sidebar.collapsed .mc-centre-card { justify-content: center; padding: .65rem; }
.mc-sidebar.collapsed .mc-brand { justify-content: center; padding: .9rem .65rem; }

.mc-sidebar-footer { border-top: 1px solid rgba(255,255,255,.10); padding: .75rem; flex-shrink: 0; overflow: hidden; }
.mc-logout-btn {
    display: flex; align-items: center; gap: .65rem;
    padding: .6rem .85rem; border-radius: 9px;
    color: rgba(255,255,255,.7); font-size: .8rem; font-weight: 600;
    cursor: pointer; width: 100%; background: transparent; border: none;
    transition: var(--transition); font-family: inherit; text-decoration: none;
}
.mc-logout-btn:hover { background: rgba(231,76,60,.25); color: #ff8a80; }
.mc-sidebar.collapsed .mc-logout-btn { justify-content: center; }
.mc-sidebar.collapsed .mc-logout-btn span { display: none; }
</style>

@php
    $sidebarMc   = auth()->user()->medicalCentre ?? null;
    $sidebarMcSt = $sidebarMc->status ?? 'pending';

    $sbNotifCount  = 0;
    $sbPendingApts = 0;
    $sbPendingDocs = 0;

    if ($sidebarMc) {
        $sbNotifCount = \App\Models\Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $sbPendingApts = \App\Models\Appointment::where('workplace_type', 'medical_centre')
            ->where('workplace_id', $sidebarMc->id)
            ->where('status', 'pending')
            ->count();

        $sbPendingDocs = \App\Models\DoctorWorkplace::where('workplace_type', 'medical_centre')
            ->where('workplace_id', $sidebarMc->id)
            ->where('status', 'pending')
            ->count();
    }
@endphp


    {{-- ── Brand ── --}}
    <div class="mc-brand">
        <div class="mc-brand-icon">
            <i class="fas fa-clinic-medical"></i>
        </div>
        <div class="mc-brand-text">
            <h6>HealthNet</h6>
            <span>Medical Centre</span>
        </div>
    </div>

    {{-- ── Centre Info Card ── --}}
    <div class="mc-centre-card">
        <div class="mc-centre-avatar">
            @if($sidebarMc && $sidebarMc->profile_image)
                <img src="{{ asset('storage/' . $sidebarMc->profile_image) }}"
                     alt="{{ $sidebarMc->name }}"
                     style="width:100%;height:100%;object-fit:cover;border-radius:7px;">
            @else
                {{ strtoupper(substr($sidebarMc->name ?? 'M', 0, 1)) }}
            @endif
        </div>
        <div class="mc-centre-info">
            <h6>{{ Str::limit($sidebarMc->name ?? 'Medical Centre', 22) }}</h6>
            <span>
                <span class="dot dot-{{ $sidebarMcSt }}"></span>
                {{ ucfirst($sidebarMcSt) }}
            </span>
        </div>
    </div>

    {{-- ── Nav ── --}}
    <nav class="mc-nav-scroll">

        <div class="mc-nav-section">Main</div>

        <a href="{{ route('medical_centre.dashboard') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.dashboard') ? 'active' : '' }}"
           data-tooltip="Dashboard">
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <span class="nav-label">Dashboard</span>
        </a>

        <div class="mc-nav-section">Appointments</div>

        <a href="{{ route('medical_centre.appointments') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.appointments*') ? 'active' : '' }}"
           data-tooltip="Appointments">
            <i class="fas fa-calendar-check nav-icon"></i>
            <span class="nav-label">Appointments</span>
            @if($sbPendingApts > 0)
                <span class="nav-badge">{{ $sbPendingApts > 99 ? '99+' : $sbPendingApts }}</span>
            @endif
        </a>

        <div class="mc-nav-section">Staff</div>

        <a href="{{ route('medical_centre.doctors') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.doctors*') ? 'active' : '' }}"
           data-tooltip="Doctors">
            <i class="fas fa-user-md nav-icon"></i>
            <span class="nav-label">Doctors</span>
            @if($sbPendingDocs > 0)
                <span class="nav-badge">{{ $sbPendingDocs > 99 ? '99+' : $sbPendingDocs }}</span>
            @endif
        </a>

        <div class="mc-nav-section">Content</div>

        <a href="{{ route('medical_centre.announcements') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.announcements*') ? 'active' : '' }}"
           data-tooltip="Announcements">
            <i class="fas fa-bullhorn nav-icon"></i>
            <span class="nav-label">Announcements</span>
        </a>

        <a href="{{ route('medical_centre.reviews') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.reviews*') ? 'active' : '' }}"
           data-tooltip="Reviews">
            <i class="fas fa-star nav-icon"></i>
            <span class="nav-label">Reviews</span>
        </a>

        <div class="mc-nav-section">Account</div>

        <a href="{{ route('medical_centre.notifications') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.notifications*') ? 'active' : '' }}"
           data-tooltip="Notifications">
            <i class="fas fa-bell nav-icon"></i>
            <span class="nav-label">Notifications</span>
            @if($sbNotifCount > 0)
                <span class="nav-badge">{{ $sbNotifCount > 99 ? '99+' : $sbNotifCount }}</span>
            @endif
        </a>

        <a href="{{ route('medical_centre.profile') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.profile*') ? 'active' : '' }}"
           data-tooltip="Profile">
            <i class="fas fa-hospital nav-icon"></i>
            <span class="nav-label">Centre Profile</span>
        </a>

        <a href="{{ route('medical_centre.settings') }}"
           class="mc-nav-link {{ request()->routeIs('medical_centre.settings*') ? 'active' : '' }}"
           data-tooltip="Settings">
            <i class="fas fa-cog nav-icon"></i>
            <span class="nav-label">Settings</span>
        </a>

    </nav>

    {{-- ── Footer / Logout ── --}}
    <div class="mc-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" id="mcLogoutForm">
            @csrf
            <button type="submit" class="mc-logout-btn">
                <i class="fas fa-sign-out-alt" style="width:20px;text-align:center;"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>

    {{-- NO JS FETCH — badges rendered server-side above --}}

</aside>
