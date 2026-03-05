<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>HealthNet</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('pharmacy.dashboard') }}"
           class="nav-link {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        <hr class="sidebar-divider">

        <a href="{{ route('pharmacy.profile.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.profile.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span class="nav-text">My Profile</span>
        </a>

        <a href="{{ route('pharmacy.medicines.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
            <i class="fas fa-pills"></i>
            <span class="nav-text">Medicines</span>
        </a>

        <a href="{{ route('pharmacy.orders.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span class="nav-text">Orders</span>
            @php
                try {
                    $__pendingOrders = \App\Models\PharmacyOrder::where('pharmacy_id', Auth::user()->pharmacy?->id ?? 0)
                        ->where('status', 'pending')->count();
                } catch (\Exception $e) { $__pendingOrders = 0; }
            @endphp
            @if($__pendingOrders > 0)
                <span class="badge bg-warning text-dark">{{ $__pendingOrders }}</span>
            @endif
        </a>

        <a href="{{ route('pharmacy.inventory.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.inventory.*') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i>
            <span class="nav-text">Inventory</span>
        </a>

        <a href="{{ route('pharmacy.patients.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.patients.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="nav-text">Patients</span>
        </a>

        <a href="{{ route('pharmacy.reports.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span class="nav-text">Reports</span>
        </a>

        <a href="{{ route('pharmacy.ratings.index') }}"
           class="nav-link {{ request()->routeIs('pharmacy.ratings.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span class="nav-text">Ratings</span>
        </a>

        <hr class="sidebar-divider">

        <a href="{{ route('pharmacy.notifications') }}"
           class="nav-link {{ request()->routeIs('pharmacy.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="nav-text">Notifications</span>
            @php
                try {
                    $__unreadNotif = \DB::table('notifications')
                        ->where('notifiable_id', Auth::id())
                        ->where('notifiable_type', 'App\Models\User')
                        ->where('is_read', false)->count();
                } catch (\Exception $e) { $__unreadNotif = 0; }
            @endphp
            @if($__unreadNotif > 0)
                <span class="badge bg-danger">{{ $__unreadNotif }}</span>
            @endif
        </a>

        <a href="{{ route('pharmacy.settings') }}"
           class="nav-link {{ request()->routeIs('pharmacy.settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="nav-text">Settings</span>
        </a>

        <a href="#" class="nav-link text-danger"
           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text">Logout</span>
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </nav>
</div>
