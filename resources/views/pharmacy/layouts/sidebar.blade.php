{{-- resources/views/pharmacy/layouts/sidebar.blade.php --}}
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>HEALTHNET</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('pharmacy.dashboard') }}" class="nav-link {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('pharmacy.profile.index') }}" class="nav-link {{ request()->routeIs('pharmacy.profile.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>My Profile</span>
        </a>

        <a href="{{ route('pharmacy.medicines.index') }}" class="nav-link {{ request()->routeIs('pharmacy.medicines.*') ? 'active' : '' }}">
            <i class="fas fa-pills"></i>
            <span>Medicines</span>
        </a>

        <a href="{{ route('pharmacy.orders.index') }}" class="nav-link {{ request()->routeIs('pharmacy.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>

        <a href="{{ route('pharmacy.inventory.index') }}" class="nav-link {{ request()->routeIs('pharmacy.inventory.*') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i>
            <span>Inventory</span>
        </a>

        <a href="{{ route('pharmacy.patients.index') }}" class="nav-link {{ request()->routeIs('pharmacy.patients.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Patients</span>
        </a>

        <a href="{{ route('pharmacy.reports.index') }}" class="nav-link {{ request()->routeIs('pharmacy.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>

        <a href="{{ route('pharmacy.ratings.index') }}" class="nav-link {{ request()->routeIs('pharmacy.ratings.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Ratings & Reviews</span>
        </a>

        <a href="{{ route('pharmacy.settings') }}" class="nav-link {{ request()->routeIs('pharmacy.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>

        <a href="{{ route('logout') }}"
           class="nav-link text-danger"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</div>
