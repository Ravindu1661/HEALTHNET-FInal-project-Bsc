@php
    $sbUser   = Auth::user();
    $sbDoctor = $sbUser?->doctor;
    $sbAvatar = ($sbDoctor && $sbDoctor->profile_image)
        ? asset('storage/' . $sbDoctor->profile_image)
        : asset('images/default-avatar.png');
    $sbName  = trim(($sbDoctor->firstname ?? '') . ' ' . ($sbDoctor->lastname ?? ''))
               ?: strtok($sbUser->email, '@');
    $cr = request()->route()?->getName() ?? '';
@endphp

<aside class="doc-sidebar" id="docSidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <div class="sb-logo"><i class="fas fa-heartbeat"></i></div>
        <span class="sb-brand-text">HealthNet</span>
    </div>

    {{-- Navigation --}}
    <nav class="sb-nav">

        <div class="sb-section">Main</div>
        <a href="{{ route('doctor.dashboard') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            <span class="sb-link-text">Dashboard</span>
        </a>

        <div class="sb-section">Appointments</div>
        <a href="{{ route('doctor.appointments.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.appointments') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span class="sb-link-text">Appointments</span>
        </a>
        <a href="{{ route('doctor.schedule.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.schedule') ? 'active' : '' }}">
            <i class="fas fa-clock"></i>
            <span class="sb-link-text">My Schedule</span>
        </a>

        <div class="sb-section">Patients</div>
        <a href="{{ route('doctor.patients.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.patients') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="sb-link-text">Patients</span>
        </a>

        <div class="sb-section">Finance</div>
        <a href="{{ route('doctor.earnings.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.earnings') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span class="sb-link-text">Earnings</span>
        </a>

        <div class="sb-section">Network</div>
        <a href="{{ route('doctor.workplaces.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.workplaces') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span class="sb-link-text">Workplaces</span>
        </a>

        <div class="sb-section">Feedback</div>
        <a href="{{ route('doctor.reviews.index') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.reviews') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span class="sb-link-text">Reviews</span>
        </a>

        <div class="sb-section">Account</div>
        <a href="{{ route('doctor.notifications') }}"
           class="sb-link {{ $cr === 'doctor.notifications' ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="sb-link-text">Notifications</span>
            <span class="sb-badge" id="sidebarNotifBadge" style="display:none">0</span>
        </a>
        <a href="{{ route('doctor.profile.show') }}"
           class="sb-link {{ str_starts_with($cr, 'doctor.profile') ? 'active' : '' }}">
            <i class="fas fa-user-edit"></i>
            <span class="sb-link-text">Profile</span>
        </a>
        <a href="{{ route('doctor.settings') }}"
           class="sb-link {{ $cr === 'doctor.settings' ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="sb-link-text">Settings</span>
        </a>
        <a href="{{ route('logout') }}" class="sb-link"
           onclick="event.preventDefault(); document.getElementById('sbLogoutForm').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <span class="sb-link-text">Logout</span>
        </a>
        <form id="sbLogoutForm" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    </nav>

    {{-- User Info --}}
    <div class="sb-user">
        <img src="{{ $sbAvatar }}" alt="{{ $sbName }}"
             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
        <div class="sb-user-info">
            <div class="sb-user-name">Dr. {{ $sbDoctor->firstname ?? $sbName }}</div>
            <div class="sb-user-role">{{ $sbDoctor->specialization ?? 'Doctor' }}</div>
        </div>
    </div>

</aside>
