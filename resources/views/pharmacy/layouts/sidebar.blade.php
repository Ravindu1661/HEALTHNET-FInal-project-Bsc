<div class="sidebar" id="sidebar">

    {{-- ── Brand ── --}}
    <div class="sidebar-brand">
        <i class="fas fa-heartbeat" style="font-size:17px;color:#38bdf8;flex-shrink:0"></i>
        <div class="sb-brand-text">
            <span class="sb-name">HealthNet</span>
            <span class="sb-sub">Pharmacy Portal</span>
        </div>
    </div>

    {{-- ── User Card ── --}}
    @php $phImg = Auth::user()->pharmacy?->profile_image; @endphp
    <div class="sb-user">
        <div class="sb-avatar">
            @if($phImg)
                <img src="{{ asset('storage/'.$phImg) }}" alt=""
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span style="display:none"><i class="fas fa-store"></i></span>
            @else
                <i class="fas fa-store"></i>
            @endif
        </div>
        <div class="sb-user-info sb-text">
            <div class="sb-uname">
                {{ Str::limit(Auth::user()->pharmacy?->name ?? Auth::user()->name, 20) }}
            </div>
            <div class="sb-urole">
                <i class="fas fa-circle" style="font-size:.38rem"></i> Online
            </div>
        </div>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="sidebar-nav">

        {{-- Main --}}
        <div class="sb-label sb-text">Main</div>

        <a href="{{ route('pharmacy.dashboard') }}"
           class="nav-link {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span class="nav-text sb-text">Dashboard</span>
        </a>

        <a href="{{ route('pharmacy.profile.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.profile.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span class="nav-text sb-text">My Profile</span>
        </a>

        {{-- Management --}}
        <div class="sb-label sb-text">Management</div>

        <a href="{{ route('pharmacy.medicines.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
            <i class="fas fa-pills"></i>
            <span class="nav-text sb-text">Medicines</span>
        </a>

        <a href="{{ route('pharmacy.orders.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span class="nav-text sb-text">Orders</span>
            @php
                try {
                    $__po = \App\Models\PharmacyOrder::where('pharmacy_id', Auth::user()->pharmacy?->id ?? 0)
                                ->where('status','pending')->count();
                } catch(\Exception $e) { $__po = 0; }
            @endphp
            @if($__po > 0)
                <span class="sb-badge warn sb-text">{{ $__po }}</span>
            @endif
        </a>

        <a href="{{ route('pharmacy.inventory.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.inventory.*') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i>
            <span class="nav-text sb-text">Inventory</span>
        </a>

        <a href="{{ route('pharmacy.patients.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.patients.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="nav-text sb-text">Patients</span>
        </a>

        <a href="{{ route('pharmacy.reports.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span class="nav-text sb-text">Reports</span>
        </a>

        <a href="{{ route('pharmacy.ratings.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.ratings.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span class="nav-text sb-text">Ratings & Reviews</span>
        </a>

        {{-- System --}}
        <div class="sb-label sb-text">System</div>

        <a href="{{ route('pharmacy.notifications') }}"
           class="nav-link {{ request()->routeIs('pharmacy.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="nav-text sb-text">Notifications</span>
            @php
                try {
                    $__un = \DB::table('notifications')
                        ->where('notifiable_id', Auth::id())
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('is_read', false)->count();
                } catch(\Exception $e) { $__un = 0; }
            @endphp
            @if($__un > 0)
                <span class="sb-badge danger sb-text">{{ $__un }}</span>
            @endif
        </a>

        <a href="{{ route('pharmacy.settings') }}"
           class="nav-link {{ request()->routeIs('pharmacy.settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="nav-text sb-text">Settings</span>
        </a>

        <hr class="sidebar-divider" style="border-color:rgba(255,255,255,.1);margin:6px 0">

        <a href="#" class="nav-link sb-logout"
           onclick="event.preventDefault();document.getElementById('sb-logout-form').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text sb-text">Logout</span>
        </a>
        <form id="sb-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    </nav>
</div>

{{-- ══ Sidebar Styles ══════════════════════════════════════ --}}
<style>
/* ── Base ──────────────────────────────────── */
.sidebar {
    width: var(--sb-width, 220px);
    min-height: 100vh;
    background: #1a3c5e;
    position: fixed;
    top: 0; left: 0;
    z-index: 1040;
    display: flex;
    flex-direction: column;
    transition: width .3s ease;
    overflow: hidden;
}
.sidebar.collapsed { width: var(--sb-collapsed, 60px); }

/* ── Brand ─────────────────────────────────── */
.sidebar-brand {
    padding: 13px 14px;
    background: #122a42;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid rgba(255,255,255,.07);
    flex-shrink: 0;
    white-space: nowrap;
    overflow: hidden;
}
.sb-brand-text { overflow: hidden; }
.sb-name {
    display: block;
    font-size: 14px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
}
.sb-sub {
    display: block;
    font-size: 10px;
    color: #4a7899;
    font-weight: 500;
    letter-spacing: .4px;
    text-transform: uppercase;
}

/* ── User Card ─────────────────────────────── */
.sb-user {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 8px 8px 2px;
    padding: 8px 9px;
    background: rgba(255,255,255,.06);
    border-radius: 9px;
    border: 1px solid rgba(255,255,255,.07);
    overflow: hidden;
    flex-shrink: 0;
    white-space: nowrap;
}
.sb-avatar {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: #0d2033;
    border: 1.5px solid rgba(56,189,248,.3);
    display: flex; align-items: center; justify-content: center;
    color: #38bdf8;
    font-size: 13px;
    overflow: hidden;
    flex-shrink: 0;
}
.sb-avatar img { width:100%;height:100%;object-fit:cover; }
.sb-uname {
    font-size: 11.5px;
    font-weight: 700;
    color: #e0eaf5;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sb-urole {
    font-size: 10px;
    color: #38bdf8;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}
.sb-urole i { animation: sbBlink 2s infinite; }
@keyframes sbBlink { 0%,100%{opacity:1} 50%{opacity:.2} }

/* ── Nav ───────────────────────────────────── */
.sidebar-nav {
    padding: 6px 8px 16px;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #1e3a55 transparent;
}
.sidebar-nav::-webkit-scrollbar { width: 3px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: #1e3a55; border-radius: 3px; }

/* Section label */
.sb-label {
    font-size: 10px;
    font-weight: 700;
    color: #3a6080;
    text-transform: uppercase;
    letter-spacing: .9px;
    padding: 8px 6px 3px;
    white-space: nowrap;
    overflow: hidden;
}

/* Nav link */
.nav-link {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 9px;
    border-radius: 8px;
    color: #b0c4d8;
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    transition: background .2s, color .2s, transform .15s;
    position: relative;
    margin-bottom: 1px;
}
.nav-link i {
    font-size: 13.5px;
    flex-shrink: 0;
    width: 18px;
    text-align: center;
}
.nav-link:hover {
    background: rgba(56,189,248,.1);
    color: #e0f5ff;
    transform: translateX(2px);
}
.nav-link.active {
    background: #38bdf8;
    color: #fff;
    font-weight: 600;
}
.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0; top: 18%; bottom: 18%;
    width: 3px;
    background: rgba(255,255,255,.55);
    border-radius: 0 2px 2px 0;
}

/* Badges */
.sb-badge {
    margin-left: auto;
    padding: 2px 6px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    flex-shrink: 0;
    line-height: 1.5;
}
.sb-badge.warn   { background: #f59e0b; color: #1a1a1a; }
.sb-badge.danger { background: #ef4444; color: #fff; }

/* Logout */
.sb-logout { color: #f87171 !important; }
.sb-logout:hover {
    background: rgba(239,68,68,.12) !important;
    color: #fca5a5 !important;
    transform: none !important;
}

/* ── Collapsed state ───────────────────────── */
.sidebar.collapsed .sb-text,
.sidebar.collapsed .sb-label,
.sidebar.collapsed .sb-brand-text,
.sidebar.collapsed .sb-user-info {
    opacity: 0;
    width: 0;
    overflow: hidden;
    pointer-events: none;
}
.sidebar.collapsed .sb-user {
    justify-content: center;
    padding: 6px;
    margin: 8px 6px 2px;
}
.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 9px 6px;
    gap: 0;
}
.sidebar.collapsed .sidebar-brand {
    justify-content: center;
    padding: 13px 6px;
    gap: 0;
}
.sidebar.collapsed .sb-label { padding: 4px 0; }

/* ── Mobile ────────────────────────────────── */
@media (max-width: 991px) {
    /* Collapsed state override for mobile */
    .sidebar.collapsed .sb-text,
    .sidebar.collapsed .sb-label,
    .sidebar.collapsed .sb-brand-text,
    .sidebar.collapsed .sb-user-info {
        opacity: 1;
        width: auto;
        pointer-events: auto;
    }
    .sidebar.collapsed .sb-user  { justify-content: flex-start; padding: 8px 9px; }
    .sidebar.collapsed .nav-link { justify-content: flex-start; padding: 8px 9px; gap: 9px; }
    .sidebar.collapsed .sidebar-brand { justify-content: flex-start; padding: 13px 14px; gap: 10px; }
}
</style>
