<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>HEALTHNET</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('admin.doctors.index') }}" class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i>
            <span>Doctors</span>
        </a>
        <a href="{{ route('admin.hospitals.index') }}" class="nav-link {{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
            <i class="fas fa-hospital"></i>
            <span>Hospitals</span>
        </a>
        <a href="{{ route('admin.laboratories.index') }}" class="nav-link {{ request()->routeIs('admin.laboratories.*') ? 'active' : '' }}">
            <i class="fas fa-flask"></i>
            <span>Laboratories</span>
        </a>
        <a href="{{ route('admin.pharmacies.index') }}" class="nav-link {{ request()->routeIs('admin.pharmacies.*') ? 'active' : '' }}">
            <i class="fas fa-pills"></i>
            <span>Pharmacies</span>
        </a>
        <a href="{{ route('admin.medical-centres.index') }}" class="nav-link {{ request()->routeIs('admin.medical-centres.*') ? 'active' : '' }}">
            <i class="fas fa-clinic-medical"></i>
            <span>Medical Centres</span>
        </a>
        <a href="{{ route('admin.patients.index') }}" class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
            <i class="fas fa-user-injured"></i>
            <span>Patients</span>
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Appointments</span>
        </a>
        <a href="{{ route('admin.lab-orders.index') }}" class="nav-link {{ request()->routeIs('admin.lab-orders.*') ? 'active' : '' }}">
            <i class="fas fa-vials"></i>
            <span>Lab Orders</span>
        </a>
        <a href="{{ route('admin.prescriptions.index') }}" class="nav-link {{ request()->routeIs('admin.prescriptions.*') ? 'active' : '' }}">
            <i class="fas fa-prescription"></i>
            <span>Prescriptions</span>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Payments</span>
        </a>
        {{-- NEW: Reports --}}
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Reports</span>
        </a>

        <a href="{{ route('admin.chatbot.index') }}"
        class="nav-link {{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}">
            <i class="fas fa-robot"></i>
            <span>Chatbot</span>
            @php try {
                $pendingChatContacts = \Illuminate\Support\Facades\DB::table('chatbot_admin_contacts')
                    ->where('status','pending')->count();
            } catch(\Exception $e) { $pendingChatContacts = 0; } @endphp
            @if($pendingChatContacts > 0)
            <span class="badge bg-danger ms-auto">{{ $pendingChatContacts }}</span>
            @endif
        </a>


        {{-- NEW: System Logs --}}
        <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i>
            <span>System Logs</span>
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <i class="fas fa-bullhorn"></i>
            <span>Announcements</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </nav>
</div>
