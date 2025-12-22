<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-heartbeat"></i>
            <span>HealthNet</span>
        </div>
        {{-- <span class="role-badge">Doctor Panel</span> --}}
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <img src="{{ auth()->user()->doctor->profile_image ? asset('storage/' . auth()->user()->doctor->profile_image) : asset('images/default-doctor.png') }}" alt="Profile">
            <span class="status-dot"></span>
        </div>
        <div class="user-info">
            <h6>Dr. {{ auth()->user()->doctor->first_name }} {{ auth()->user()->doctor->last_name }}</h6>
            <p>{{ auth()->user()->doctor->specialization ?? 'Medical Professional' }}</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
    <ul class="nav-list">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('doctor.dashboard') }}"
               class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- Appointments -->
        <li class="nav-item">
            <a href="{{ route('doctor.appointments.index') }}"
               class="nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
            </a>
        </li>
        <!-- Schedule -->
        <li class="nav-item">
            <a href="{{ route('doctor.schedule.index') }}"
               class="nav-link {{ request()->routeIs('doctor.schedule.*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>My Schedule</span>
            </a>
        </li>
        <!-- Patients -->
        <li class="nav-item">
            <a href="{{ route('doctor.patients.index') }}"
               class="nav-link {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>My Patients</span>
            </a>
        </li>
        <!-- Workplaces -->
        <li class="nav-item">
            <a href="{{ route('doctor.workplaces.index') }}"
               class="nav-link {{ request()->routeIs('doctor.workplaces.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Workplaces</span>
            </a>
        </li>
        <!-- Earnings -->
        <li class="nav-item">
            <a href="{{ route('doctor.earnings.index') }}"
               class="nav-link {{ request()->routeIs('doctor.earnings.*') ? 'active' : '' }}">
                <i class="fas fa-dollar-sign"></i>
                <span>Earnings</span>
            </a>
        </li>
        <!-- Reviews -->
        <li class="nav-item">
            <a href="{{ route('doctor.reviews.index') }}"
               class="nav-link {{ request()->routeIs('doctor.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                <span>Reviews & Ratings</span>
            </a>
        </li>

        <!-- Divider -->
        <li class="nav-divider"></li>

        <!-- Profile -->
        <li class="nav-item">
            <a href="{{ route('doctor.profile.edit') }}"
               class="nav-link {{ request()->routeIs('doctor.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i>
                <span>Profile Settings</span>
            </a>
        </li>
        <!-- Notifications -->
        <li class="nav-item">
            <a href="{{ route('doctor.notifications') }}"
               class="nav-link {{ request()->routeIs('doctor.notifications') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>
        <!-- Settings -->
        <li class="nav-item">
            <a href="{{ route('doctor.settings') }}"
               class="nav-link {{ request()->routeIs('doctor.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        <!-- Divider -->
        <li class="nav-divider"></li>
        <!-- Logout -->
        <li class="nav-item">
            <a href="{{ route('logout') }}"
               class="nav-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
</aside>

<style>
   :root {
  --sidebar-width: 280px;
  --sidebar-bg: linear-gradient(135deg, #0f4c75 0%, #1a5c8a 100%);
  --sidebar-radius: 20px;
  --sidebar-primary: #2969bf;
  --sidebar-accent: #1a5c8a;
  --success: #42a649;
  --danger: #e74c3c;
}

/* Sidebar Container */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: var(--sidebar-width);
  height: 100vh;
  background: var(--sidebar-bg);
  color: #fff;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 0 var(--sidebar-radius) var(--sidebar-radius) 0;
  box-shadow: 0 8px 24px rgba(44,62,80,0.13);
  transition: transform 0.3s cubic-bezier(.4,2,.5,1), left 0.33s;
}

/* Scrollbar */
.sidebar::-webkit-scrollbar { width: 5px;}
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius:10px;}

/* Header */
.sidebar-header { padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.11);}
.sidebar-header .logo { display: flex; align-items: center; gap: 0.7rem; font-size: 1.18rem; font-weight: 700;}
.sidebar-header .logo i { font-size: 1.45rem; color: white;}
.logo span { letter-spacing: 1px; }
.role-badge {
  display: inline-block; background: rgba(66,166,73,0.17); color: #42a649;
  padding: 0.28rem 0.85rem; border-radius: 13px; font-size: 0.7rem; font-weight: 600; margin-top: 0.5rem;
}

/* User */
.sidebar-user {
  padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.11); display: flex; align-items: center; gap: 1rem;
}
.user-avatar {
  position: relative; width: 50px; height: 50px; border-radius: 50%; overflow: hidden;
  border: 3px solid rgba(66,166,73,0.21);
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover;}
.status-dot {
  position: absolute; bottom: 2px; right: 7px; width: 12px; height: 12px;
  background: #42a649; border: 2px solid #0f4c75; border-radius: 50%;
}
.user-info h6 { font-size: 0.91rem; font-weight: 600; margin: 0; color: #fff;}
.user-info p { font-size: 0.74rem; color: rgba(255,255,255,0.75); margin: 0.2rem 0 0 0;}

/* Menu */
.sidebar-nav { padding: 1rem 0;}
.nav-list { list-style: none; padding: 0; margin: 0;}
.nav-item { margin: 0.2rem 0;}
.nav-link {
  display: flex; align-items: center; padding: 0.7rem 1.2rem;
  color: rgba(255,255,255,0.84); text-decoration: none; transition: all 0.3s;
  font-size: 0.86rem; font-weight: 500; border-radius: 12px; position: relative;
}
.nav-link i { width: 20px; margin-right: 0.8rem; font-size: 0.97rem;}
.nav-link:hover {
  background: rgba(255,255,255,0.13);
  color: #fff;
  padding-left: 1.5rem;
}
.nav-link.active {
  background: linear-gradient(90deg, #ffffffc2 40%, #c8e6ff33 100%);
  color: #194f93 !important;
  font-weight: 700;
  border-left: 4px solid #3399ff;
  box-shadow: 0 1px 6px 0 rgba(31,78,121,0.10);
  transition: background 0.13s, color 0.14s;
  position: relative;
}
.nav-link.active i {
  color: #3399ff !important;
}

.nav-divider { height: 1px; background: rgba(255,255,255,0.14); margin: 0.8rem 1.2rem;}

.nav-link.text-danger { color: var(--danger) !important;}
.nav-link.text-danger:hover { background: rgba(231,76,60,0.12); color:var(--danger) !important;}
.nav-link.text-danger i { color: var(--danger) !important;}

/* Mobile Responsive */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.show { transform: translateX(0);}
}

</style>
