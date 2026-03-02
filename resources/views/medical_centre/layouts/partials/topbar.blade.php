{{-- resources/views/medical_centre/layouts/partials/topbar.blade.php --}}

@php
    $sbMc = auth()->user()->medicalCentre ?? null;

    $topbarNotifications = collect();
    $topbarUnreadCount   = 0;

    if ($sbMc) {
        $topbarNotifications = \App\Models\Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $topbarUnreadCount = \App\Models\Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    $typeMap = [
        'appointment' => ['icon' => 'fa-calendar-check', 'bg' => '#e8f0fe', 'color' => '#2969bf'],
        'payment'     => ['icon' => 'fa-credit-card',    'bg' => '#d1e7dd', 'color' => '#0a3622'],
        'doctor'      => ['icon' => 'fa-user-md',        'bg' => '#f0ebff', 'color' => '#8e44ad'],
        'system'      => ['icon' => 'fa-cog',            'bg' => '#fff3cd', 'color' => '#856404'],
        'general'     => ['icon' => 'fa-info-circle',    'bg' => '#f4f7fb', 'color' => '#555'   ],
    ];

    $mc = $sbMc;
@endphp

<style>
.topbar-toggle {
    width: 38px; height: 38px; border-radius: 9px;
    background: transparent; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); font-size: .95rem;
    transition: var(--transition); flex-shrink: 0;
}
.topbar-toggle:hover { background: var(--mc-primary-light); color: var(--mc-primary); }

.mc-breadcrumb {
    display: flex; align-items: center; gap: .4rem;
    flex: 1; overflow: hidden;
}
.mc-breadcrumb span {
    font-size: .78rem; color: var(--text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.mc-breadcrumb span.sep { color: #d0d8e4; flex-shrink: 0; }
.mc-breadcrumb span.current { font-weight: 700; color: var(--text-dark); }

.topbar-right { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }

.tb-icon-btn {
    width: 38px; height: 38px; border-radius: 9px; border: none;
    background: #f4f7fb; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); font-size: .85rem;
    transition: var(--transition); position: relative;
    text-decoration: none;
}
.tb-icon-btn:hover { background: var(--mc-primary-light); color: var(--mc-primary); }

.tb-badge {
    position: absolute; top: 4px; right: 4px;
    width: 16px; height: 16px;
    background: #e74c3c; color: #fff;
    border-radius: 50%; font-size: .55rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff; pointer-events: none;
}

.tb-notif-dropdown {
    position: absolute;
    top: calc(var(--topbar-h) + 4px); right: 0;
    width: 340px; background: #fff;
    border-radius: 14px; box-shadow: var(--shadow-lg);
    border: 1px solid var(--border); z-index: 1050;
    display: none; overflow: hidden;
    animation: dropIn .2s ease;
}
@keyframes dropIn {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}
.tb-notif-dropdown.show { display: block; }

.tb-notif-head {
    padding: .85rem 1.1rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.tb-notif-head h6 { font-size: .88rem; font-weight: 700; margin: 0; }

.tb-notif-list { max-height: 320px; overflow-y: auto; }

.tb-notif-item {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .8rem 1.1rem; border-bottom: 1px solid #f5f7fa;
    transition: background .15s; color: inherit;
}
.tb-notif-item:last-child { border-bottom: none; }
.tb-notif-item:hover { background: #f8fbff; }
.tb-notif-item.unread { background: #f0faf7; }

.tb-notif-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
}
.tb-notif-body { flex: 1; min-width: 0; }
.tb-notif-body h6 {
    font-size: .8rem; font-weight: 700; color: var(--text-dark);
    margin: 0 0 .15rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.tb-notif-body p {
    font-size: .73rem; color: var(--text-muted); margin: 0;
    overflow: hidden;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.tb-notif-body time { font-size: .67rem; color: #b0bec5; display: block; margin-top: .2rem; }
.tb-notif-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--mc-primary); flex-shrink: 0; margin-top: 5px;
}
.tb-notif-footer {
    padding: .7rem 1.1rem; border-top: 1px solid var(--border); text-align: center;
}
.tb-notif-footer a {
    font-size: .78rem; font-weight: 600;
    color: var(--mc-primary); text-decoration: none;
}
.tb-notif-footer a:hover { text-decoration: underline; }
.tb-notif-empty {
    padding: 2rem 1rem; text-align: center;
    color: var(--text-muted); font-size: .82rem;
}
.tb-notif-empty i { font-size: 1.8rem; display: block; margin-bottom: .5rem; opacity: .4; }

.tb-user-btn {
    display: flex; align-items: center; gap: .55rem;
    background: #f4f7fb; border: none; border-radius: 10px;
    padding: .35rem .75rem .35rem .4rem;
    cursor: pointer; transition: var(--transition); font-family: inherit;
}
.tb-user-btn:hover { background: var(--mc-primary-light); }
.tb-user-avatar {
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg, var(--mc-primary), var(--mc-secondary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .75rem; font-weight: 700;
    overflow: hidden; flex-shrink: 0;
}
.tb-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.tb-user-name {
    font-size: .78rem; font-weight: 700; color: var(--text-dark);
    max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.tb-user-arrow { font-size: .6rem; color: var(--text-muted); }

.tb-user-dropdown {
    position: absolute;
    top: calc(var(--topbar-h) + 4px); right: 0;
    width: 220px; background: #fff;
    border-radius: 12px; box-shadow: var(--shadow-lg);
    border: 1px solid var(--border); z-index: 1050;
    display: none; overflow: hidden; animation: dropIn .2s ease;
}
.tb-user-dropdown.show { display: block; }
.tb-user-dd-head {
    padding: .9rem 1rem; border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #f0faf7, #e8f8f5);
}
.tb-user-dd-head h6 { font-size: .85rem; font-weight: 700; margin: 0 0 .1rem; color: var(--text-dark); }
.tb-user-dd-head span { font-size: .72rem; color: var(--text-muted); }
.tb-user-dd-item {
    display: flex; align-items: center; gap: .7rem;
    padding: .6rem 1rem; font-size: .8rem; font-weight: 600;
    color: var(--text-dark); text-decoration: none;
    transition: background .15s; border: none; background: transparent;
    width: 100%; cursor: pointer; font-family: inherit;
}
.tb-user-dd-item:hover { background: #f4f7fb; }
.tb-user-dd-item i { width: 16px; text-align: center; color: var(--text-muted); font-size: .8rem; }
.tb-user-dd-divider { height: 1px; background: var(--border); }
.tb-user-dd-item.danger { color: #e74c3c; }
.tb-user-dd-item.danger i { color: #e74c3c; }
.tb-user-dd-item.danger:hover { background: #fdecea; }

@media (max-width: 575.98px) {
    .tb-user-name { display: none; }
    .tb-notif-dropdown { right: .5rem; width: calc(100vw - 1rem); }
    .tb-user-dropdown  { right: .5rem; }
    .mc-breadcrumb span:not(.current):not(.sep) { display: none; }
}
</style>

<header class="mc-topbar">

    <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div class="mc-breadcrumb">
        <span>Medical Centre</span>
        <span class="sep"><i class="fas fa-chevron-right" style="font-size:.6rem;"></i></span>
        <span class="current">@yield('page-title', 'Dashboard')</span>
    </div>

    <div class="topbar-right">

        {{-- ════ NOTIFICATION BELL ════ --}}
        <div style="position:relative;">
            <button class="tb-icon-btn" id="tbNotifBtn"
                    onclick="toggleNotifDropdown(event)" title="Notifications">
                <i class="fas fa-bell"></i>
                @if($topbarUnreadCount > 0)
                    <span class="tb-badge">
                        {{ $topbarUnreadCount > 99 ? '99+' : $topbarUnreadCount }}
                    </span>
                @endif
            </button>

            <div class="tb-notif-dropdown" id="tbNotifDropdown">

                <div class="tb-notif-head">
                    <h6>
                        <i class="fas fa-bell me-2" style="color:var(--mc-primary);"></i>
                        Notifications
                        @if($topbarUnreadCount > 0)
                            <span style="background:var(--mc-primary);color:#fff;
                                border-radius:99px;font-size:.65rem;
                                padding:.05rem .4rem;margin-left:.3rem;font-weight:800;">
                                {{ $topbarUnreadCount }}
                            </span>
                        @endif
                    </h6>
                    @if($topbarUnreadCount > 0)
                        <form method="POST"
                              action="{{ route('medical_centre.notifications.mark-all-read') }}"
                              style="margin:0;">
                            @csrf
                            <button type="submit"
                                    style="background:none;border:none;cursor:pointer;
                                           font-size:.72rem;color:var(--mc-primary);
                                           font-weight:600;font-family:inherit;padding:0;">
                                Mark all read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="tb-notif-list">
                    @forelse($topbarNotifications as $notif)
                        @php $t = $typeMap[$notif->type] ?? $typeMap['general']; @endphp
                        <div class="tb-notif-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="tb-notif-icon"
                                 style="background:{{ $t['bg'] }};color:{{ $t['color'] }};">
                                <i class="fas {{ $t['icon'] }}"></i>
                            </div>
                            <div class="tb-notif-body">
                                <h6>{{ $notif->title }}</h6>
                                <p>{{ $notif->message }}</p>
                                <time>{{ $notif->created_at->diffForHumans() }}</time>
                            </div>
                            @if(!$notif->is_read)
                                <div style="display:flex;flex-direction:column;align-items:center;gap:.3rem;flex-shrink:0;">
                                    <div class="tb-notif-dot"></div>
                                    <form method="POST"
                                          action="{{ route('medical_centre.notifications.read', $notif->id) }}"
                                          style="margin:0;">
                                        @csrf
                                        <button type="submit" title="Mark as read"
                                                style="background:none;border:none;cursor:pointer;
                                                       color:#b0bec5;font-size:.65rem;padding:0;line-height:1;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="tb-notif-empty">
                            <i class="fas fa-bell-slash"></i>
                            No notifications yet
                        </div>
                    @endforelse
                </div>

                <div class="tb-notif-footer">
                    <a href="{{ route('medical_centre.notifications') }}">
                        View all notifications
                        @if($topbarUnreadCount > 0)
                            ({{ $topbarUnreadCount }} unread)
                        @endif
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ════ USER MENU ════ --}}
        <div style="position:relative;">
            <button class="tb-user-btn" id="tbUserBtn"
                    onclick="toggleUserDropdown(event)">
                <div class="tb-user-avatar">
                    @if($mc && $mc->profile_image)
                        <img src="{{ asset('storage/' . $mc->profile_image) }}" alt="">
                    @else
                        {{ strtoupper(substr($mc->name ?? auth()->user()->email ?? 'M', 0, 1)) }}
                    @endif
                </div>
                <span class="tb-user-name">
                    {{ Str::limit($mc->name ?? auth()->user()->email ?? 'Medical Centre', 16) }}
                </span>
                <i class="fas fa-chevron-down tb-user-arrow"></i>
            </button>

            <div class="tb-user-dropdown" id="tbUserDropdown">
                <div class="tb-user-dd-head">
                    <h6>{{ Str::limit($mc->name ?? 'Medical Centre', 22) }}</h6>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <a href="{{ route('medical_centre.profile') }}" class="tb-user-dd-item">
                    <i class="fas fa-hospital"></i> Centre Profile
                </a>
                <a href="{{ route('medical_centre.settings') }}" class="tb-user-dd-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="{{ route('medical_centre.dashboard') }}" class="tb-user-dd-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <div class="tb-user-dd-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="tb-user-dd-item danger">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

<script>
function toggleNotifDropdown(e) {
    e.stopPropagation();
    document.getElementById('tbNotifDropdown').classList.toggle('show');
    document.getElementById('tbUserDropdown')?.classList.remove('show');
}
function toggleUserDropdown(e) {
    e.stopPropagation();
    document.getElementById('tbUserDropdown')?.classList.toggle('show');
    document.getElementById('tbNotifDropdown')?.classList.remove('show');
}
document.addEventListener('click', () => {
    document.getElementById('tbNotifDropdown')?.classList.remove('show');
    document.getElementById('tbUserDropdown')?.classList.remove('show');
});
</script>
