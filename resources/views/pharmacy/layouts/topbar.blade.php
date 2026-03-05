<div class="topbar">
    {{-- Toggle --}}
    <button id="sidebarToggle" class="btn btn-sm btn-light p-1 me-1" style="line-height:1">
        <i class="fas fa-bars" style="font-size:14px"></i>
    </button>

    {{-- Title --}}
    <div class="me-auto">
        <p class="page-title">@yield('page-title', 'Dashboard')</p>
        <p class="page-subtitle">@yield('page-subtitle', '')</p>
    </div>

    {{-- Search --}}
    <div class="input-group input-group-sm me-2" style="width:180px">
        <span class="input-group-text bg-light border-0">
            <i class="fas fa-search" style="font-size:11px;color:#8898aa"></i>
        </span>
        <input type="text" class="form-control form-control-sm bg-light border-0"
               placeholder="Search..." style="font-size:12px">
    </div>

    {{-- Notification Bell --}}
    @php
        try {
            $__topbarNotifCount = \DB::table('notifications')
                ->where('notifiable_id', Auth::id())
                ->where('notifiable_type', 'App\Models\User')
                ->where('is_read', false)->count();
        } catch (\Exception $e) { $__topbarNotifCount = 0; }
    @endphp
    <button id="notifIcon" class="btn btn-sm btn-light position-relative me-2 p-2">
        <i class="fas fa-bell" style="font-size:14px;color:#1a3c5e"></i>
        @if($__topbarNotifCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size:9px">{{ $__topbarNotifCount > 9 ? '9+' : $__topbarNotifCount }}</span>
        @endif
    </button>

    {{-- User Dropdown --}}
    @php $__pharmacy = Auth::user()->pharmacy; @endphp
    <div class="dropdown">
        <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2 py-1 px-2"
                data-bs-toggle="dropdown" style="font-size:12px">
            <img src="{{ $__pharmacy?->profile_image ? asset('storage/'.$__pharmacy->profile_image) : asset('images/default-doctor.png') }}"
                 width="28" height="28" class="rounded-circle object-fit-cover"
                 onerror="this.src='{{ asset('images/default-doctor.png') }}'">
            <span class="d-none d-md-block" style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ $__pharmacy?->name ?? Auth::user()->email }}
            </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:12px;min-width:180px">
            <li>
                <div class="px-3 py-2 border-bottom">
                    <div class="fw-semibold text-truncate" style="font-size:12px">{{ $__pharmacy?->name ?? 'Pharmacy' }}</div>
                    <div class="text-muted text-truncate" style="font-size:11px">{{ Auth::user()->email }}</div>
                </div>
            </li>
            <li><a class="dropdown-item py-2" href="{{ route('pharmacy.profile.index') }}">
                <i class="fas fa-user me-2 text-primary" style="width:14px"></i>Profile
            </a></li>
            <li><a class="dropdown-item py-2" href="{{ route('pharmacy.settings') }}">
                <i class="fas fa-cog me-2 text-secondary" style="width:14px"></i>Settings
            </a></li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
                <a class="dropdown-item py-2 text-danger" href="#"
                   onclick="event.preventDefault();document.getElementById('logout-form-top').submit()">
                    <i class="fas fa-sign-out-alt me-2" style="width:14px"></i>Logout
                </a>
                <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
        </ul>
    </div>
</div>
