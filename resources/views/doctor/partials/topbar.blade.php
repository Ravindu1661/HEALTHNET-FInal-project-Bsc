@php
    $tbUser   = Auth::user();
    $tbDoctor = $tbUser?->doctor;
    $tbAvatar = ($tbDoctor && $tbDoctor->profile_image)
        ? asset('storage/' . $tbDoctor->profile_image)
        : asset('images/default-avatar.png');
    $tbFirst = $tbDoctor->firstname ?? strtok($tbUser->email, '@');
    $tbLast  = $tbDoctor->lastname  ?? '';
@endphp

<header class="doc-topbar" id="docTopbar">

    <button class="topbar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>

    <div class="topbar-actions">

        {{-- Notifications --}}
        <div style="position:relative">
            <button class="tb-icon-btn" id="tbNotifBtn" onclick="toggleNotifPanel(event)">
                <i class="fas fa-bell"></i>
                <span class="tb-notif-badge" id="notifCountBadge" style="display:none">0</span>
            </button>

            <div class="notif-panel" id="notifPanel">
                <div class="np-header">
                    <span class="np-title"><i class="fas fa-bell text-primary me-1"></i> Notifications</span>
                    <button class="np-mark-all" onclick="markAllRead()">
                        <i class="fas fa-check-double me-1"></i> Mark all read
                    </button>
                </div>
                <div class="np-list" id="npList">
                    <div class="np-loading"><i class="fas fa-spinner fa-spin me-1"></i> Loading...</div>
                </div>
                <div class="np-footer">
                    <a href="{{ route('doctor.notifications') }}">
                        View All Notifications <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Avatar Dropdown --}}
        <div class="dropdown">
            <div class="tb-avatar-wrap" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ $tbAvatar }}" class="tb-avatar" alt="{{ $tbFirst }}"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                <span class="tb-online"></span>
            </div>

            <ul class="dropdown-menu dropdown-menu-end tb-dropdown">
                <li>
                    <div class="tb-dh">
                        <img src="{{ $tbAvatar }}" alt="{{ $tbFirst }}"
                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        <div>
                            <div class="tb-dh-name">Dr. {{ $tbFirst }} {{ $tbLast }}</div>
                            <div class="tb-dh-email">{{ $tbUser->email }}</div>
                            @if($tbDoctor)
                            <span class="tb-dh-badge {{ $tbDoctor->status === 'approved' ? 'approved' : 'pending' }}">
                                {{ ucfirst($tbDoctor->status ?? 'pending') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item tb-menu-item" href="{{ route('doctor.profile.show') }}">
                    <i class="fas fa-user-circle"></i> My Profile
                </a></li>
                <li><a class="dropdown-item tb-menu-item" href="{{ route('doctor.appointments.index') }}">
                    <i class="fas fa-calendar-check"></i> Appointments
                </a></li>
                <li><a class="dropdown-item tb-menu-item" href="{{ route('doctor.schedule.index') }}">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </a></li>
                <li><a class="dropdown-item tb-menu-item" href="{{ route('doctor.earnings.index') }}">
                    <i class="fas fa-wallet"></i> Earnings
                </a></li>
                <li><a class="dropdown-item tb-menu-item" href="{{ route('doctor.settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item tb-menu-item logout" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('tbLogoutForm').submit()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
            <form id="tbLogoutForm" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>

    </div>
</header>

<script>
// ── Notification Panel ──
function toggleNotifPanel(e) {
    e.stopPropagation();
    const panel = document.getElementById('notifPanel');
    const wasOpen = panel.classList.contains('open');
    panel.classList.toggle('open');
    if (!wasOpen) loadNotifItems();
}

document.addEventListener('click', function(e) {
    const panel = document.getElementById('notifPanel');
    const btn   = document.getElementById('tbNotifBtn');
    if (panel?.classList.contains('open') && !panel.contains(e.target) && !btn.contains(e.target)) {
        panel.classList.remove('open');
    }
});

// ── Load Count ──
function loadNotifCount() {
    fetch('{{ route("doctor.notifications.count") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        const count   = d.count ?? 0;
        const badge   = document.getElementById('notifCountBadge');
        const sbBadge = document.getElementById('sidebarNotifBadge');
        if (count > 0) {
            const label = count > 99 ? '99+' : count;
            badge.textContent = label; badge.style.display = 'flex';
            if (sbBadge) { sbBadge.textContent = label; sbBadge.style.display = 'inline'; }
        } else {
            badge.style.display = 'none';
            if (sbBadge) sbBadge.style.display = 'none';
        }
    }).catch(() => {});
}

// ── Load Items ──
function loadNotifItems() {
    const list = document.getElementById('npList');
    list.innerHTML = '<div class="np-loading"><i class="fas fa-spinner fa-spin me-1"></i> Loading...</div>';

    fetch('{{ route("doctor.notifications") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        const items = d.data ?? d.notifications ?? [];
        if (!items.length) {
            list.innerHTML = '<div class="np-empty"><i class="fas fa-bell-slash"></i>No notifications</div>';
            return;
        }
        const iconMap = { appointment:'calendar-check', payment:'money-bill-wave', system:'cog', general:'info-circle' };
        list.innerHTML = items.slice(0, 10).map(n => {
            const type = n.type ?? 'general';
            const icon = iconMap[type] ?? 'bell';
            const unread = !n.is_read;
            return `<div class="np-item ${unread ? 'unread' : ''}" onclick="markOneRead(${n.id}, this)">
                <div class="np-icon ${type}"><i class="fas fa-${icon}"></i></div>
                <div class="np-body">
                    <div class="np-body-title">${esc(n.title ?? 'Notification')}</div>
                    <div class="np-body-msg">${esc(n.message ?? '')}</div>
                    <div class="np-body-time">${relTime(n.created_at)}</div>
                </div>
                ${unread ? '<div class="np-dot"></div>' : ''}
            </div>`;
        }).join('');
    })
    .catch(() => {
        list.innerHTML = '<div class="np-loading text-danger"><i class="fas fa-exclamation-circle me-1"></i>Failed to load</div>';
    });
}

// ── Mark One Read ──
function markOneRead(id, el) {
    el.classList.remove('unread');
    el.querySelector('.np-dot')?.remove();
    fetch(`{{ url('doctor/notifications') }}/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => loadNotifCount()).catch(() => {});
}

// ── Mark All Read ──
function markAllRead() {
    document.querySelectorAll('.np-item.unread').forEach(el => {
        el.classList.remove('unread');
        el.querySelector('.np-dot')?.remove();
    });
    fetch('{{ route("doctor.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => loadNotifCount()).catch(() => {});
}

// ── Helpers ──
function esc(s) { return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function relTime(d) {
    if (!d) return '';
    const s = Math.floor((new Date() - new Date(d)) / 1000);
    if (s < 60)   return 'Just now';
    if (s < 3600) return Math.floor(s/60) + 'm ago';
    if (s < 86400)return Math.floor(s/3600) + 'h ago';
    return Math.floor(s/86400) + 'd ago';
}

// ── Init ──
document.addEventListener('DOMContentLoaded', function() {
    loadNotifCount();
    setInterval(loadNotifCount, 60000);
});
</script>
