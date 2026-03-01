{{-- resources/views/hospital/layouts/notifications.blade.php --}}
{{-- This is the slide-in notification panel (optional side panel) --}}
{{-- Called via @include('hospital.layouts.notifications') in master.blade.php --}}

@php
    try {
        $slideNotifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $slideUnread = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', auth()->id())
            ->where('is_read', false)
            ->count();
    } catch (\Exception $e) {
        $slideNotifications = collect();
        $slideUnread = 0;
    }
@endphp

{{-- ══════════════════════════════════════════════
     SLIDE-IN NOTIFICATION PANEL
══════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div class="notif-overlay" id="notifOverlay" onclick="closeNotifPanel()"></div>

{{-- Side Panel --}}
<div class="notif-panel" id="notifPanel">

    {{-- Panel Header --}}
    <div class="notif-panel-header">
        <div class="notif-panel-title">
            <i class="fas fa-bell me-2"></i>
            Notifications
            @if($slideUnread > 0)
                <span class="notif-panel-badge">{{ $slideUnread }}</span>
            @endif
        </div>
        <div class="notif-panel-actions">
            @if($slideUnread > 0)
                <button class="panel-mark-all-btn" onclick="panelMarkAllRead()" title="Mark all as read">
                    <i class="fas fa-check-double"></i>
                </button>
            @endif
            <button class="panel-close-btn" onclick="closeNotifPanel()" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="notif-panel-tabs">
        <button class="panel-tab active" data-filter="all" onclick="filterPanelNotif(this, 'all')">
            All
        </button>
        <button class="panel-tab" data-filter="appointment" onclick="filterPanelNotif(this, 'appointment')">
            <i class="fas fa-calendar-check me-1"></i>Appointments
        </button>
        <button class="panel-tab" data-filter="payment" onclick="filterPanelNotif(this, 'payment')">
            <i class="fas fa-credit-card me-1"></i>Payments
        </button>
        <button class="panel-tab" data-filter="general" onclick="filterPanelNotif(this, 'general')">
            <i class="fas fa-bell me-1"></i>General
        </button>
    </div>

    {{-- Notification List --}}
    <div class="notif-panel-list" id="notifPanelList">
        @forelse($slideNotifications as $n)
            @php
                $iconMap = [
                    'appointment'  => ['icon' => 'calendar-check', 'bg' => '#42a649'],
                    'payment'      => ['icon' => 'credit-card',    'bg' => '#f39c12'],
                    'prescription' => ['icon' => 'pills',          'bg' => '#3498db'],
                    'labreport'    => ['icon' => 'flask',          'bg' => '#9b59b6'],
                    'general'      => ['icon' => 'bell',           'bg' => '#95a5a6'],
                ];
                $ic = $iconMap[$n->type] ?? $iconMap['general'];
            @endphp

            <div class="panel-notif-item {{ !$n->is_read ? 'unread' : '' }}"
                 data-id="{{ $n->id }}"
                 data-type="{{ $n->type }}">

                <div class="panel-notif-icon" style="background: {{ $ic['bg'] }}">
                    <i class="fas fa-{{ $ic['icon'] }}"></i>
                </div>

                <div class="panel-notif-body">
                    <div class="panel-notif-top">
                        <p class="panel-notif-title">{{ $n->title }}</p>
                        @if(!$n->is_read)
                            <span class="panel-unread-dot"></span>
                        @endif
                    </div>
                    <p class="panel-notif-msg">{{ Str::limit($n->message, 80) }}</p>
                    <div class="panel-notif-footer">
                        <span class="panel-notif-time">
                            <i class="far fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                        </span>
                        <span class="panel-notif-type-badge type-{{ $n->type }}">
                            {{ ucfirst($n->type) }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="panel-notif-actions">
                    @if(!$n->is_read)
                        <button class="panel-action-btn read-btn"
                                onclick="panelMarkRead({{ $n->id }}, this)"
                                title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="panel-empty">
                <div class="panel-empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h6>No Notifications</h6>
                <p>You're all caught up!</p>
            </div>
        @endforelse
    </div>

    {{-- Panel Footer --}}
    <div class="notif-panel-footer">
        <a href="{{ route('hospital.notifications') }}" class="view-all-btn">
            <i class="fas fa-list me-2"></i>View All Notifications
        </a>
    </div>

</div>


{{-- ══════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════ --}}
<style>
/* ── Overlay ── */
.notif-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.35);
    backdrop-filter: blur(2px);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: opacity .3s, visibility .3s;
}
.notif-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* ── Panel ── */
.notif-panel {
    position: fixed;
    top: 0;
    right: 0;
    width: 380px;
    height: 100vh;
    background: #fff;
    z-index: 1060;
    display: flex;
    flex-direction: column;
    box-shadow: -8px 0 40px rgba(44, 62, 80, .15);
    transform: translateX(100%);
    transition: transform .32s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 1px solid #e5ecee;
}
.notif-panel.open {
    transform: translateX(0);
}

/* ── Header ── */
.notif-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem;
    background: linear-gradient(135deg, #2969bf, #1a4f9a);
    color: #fff;
    flex-shrink: 0;
}
.notif-panel-title {
    font-size: 1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: .3rem;
}
.notif-panel-badge {
    background: #e74c3c;
    color: #fff;
    border-radius: 99px;
    font-size: .65rem;
    font-weight: 700;
    padding: 2px 7px;
    margin-left: 4px;
}
.notif-panel-actions {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.panel-mark-all-btn,
.panel-close-btn {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: .85rem;
    transition: background .2s;
}
.panel-mark-all-btn:hover,
.panel-close-btn:hover {
    background: rgba(255,255,255,.3);
}

/* ── Tabs ── */
.notif-panel-tabs {
    display: flex;
    gap: .3rem;
    padding: .75rem 1rem;
    background: #f6f8fd;
    border-bottom: 1px solid #e5ecee;
    overflow-x: auto;
    flex-shrink: 0;
    scrollbar-width: none;
}
.notif-panel-tabs::-webkit-scrollbar { display: none; }

.panel-tab {
    background: #fff;
    border: 1.5px solid #dce3ea;
    color: #6c7a8d;
    padding: .3rem .75rem;
    border-radius: 20px;
    font-size: .73rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
    flex-shrink: 0;
}
.panel-tab:hover,
.panel-tab.active {
    background: #2969bf;
    color: #fff;
    border-color: #2969bf;
}

/* ── List ── */
.notif-panel-list {
    flex: 1;
    overflow-y: auto;
    padding: .5rem 0;
}
.notif-panel-list::-webkit-scrollbar { width: 4px; }
.notif-panel-list::-webkit-scrollbar-thumb {
    background: #dce3ea;
    border-radius: 4px;
}

/* ── Notification Item ── */
.panel-notif-item {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f0f4f8;
    transition: background .18s;
    cursor: default;
}
.panel-notif-item:last-child { border-bottom: none; }
.panel-notif-item:hover { background: #f8fafc; }
.panel-notif-item.unread { background: #eef6ff; }
.panel-notif-item.unread:hover { background: #e4f0ff; }

.panel-notif-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .9rem;
    flex-shrink: 0;
}

.panel-notif-body { flex: 1; min-width: 0; }

.panel-notif-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .5rem;
    margin-bottom: .25rem;
}
.panel-notif-title {
    font-size: .83rem;
    font-weight: 700;
    color: #1a2332;
    margin: 0;
    line-height: 1.3;
}
.panel-unread-dot {
    width: 8px;
    height: 8px;
    background: #2969bf;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 4px;
}
.panel-notif-msg {
    font-size: .76rem;
    color: #6c7a8d;
    margin: 0 0 .4rem;
    line-height: 1.45;
}
.panel-notif-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.panel-notif-time {
    font-size: .68rem;
    color: #aab4be;
}
.panel-notif-type-badge {
    font-size: .62rem;
    font-weight: 700;
    padding: .15rem .5rem;
    border-radius: 99px;
    text-transform: capitalize;
}
.type-appointment  { background: #e9f7ee; color: #27ae60; }
.type-payment      { background: #fef8e7; color: #f39c12; }
.type-prescription { background: #eaf4fb; color: #2980b9; }
.type-labreport    { background: #f4ecfb; color: #8e44ad; }
.type-general      { background: #f0f0f0; color: #777; }

/* Mark read button */
.panel-notif-actions { flex-shrink: 0; }
.panel-action-btn {
    background: transparent;
    border: 1px solid #dce3ea;
    color: #aab4be;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: .72rem;
    transition: all .2s;
}
.panel-action-btn.read-btn:hover {
    background: #e9f7ee;
    color: #27ae60;
    border-color: #27ae60;
}

/* ── Empty state ── */
.panel-empty {
    text-align: center;
    padding: 3rem 1.5rem;
}
.panel-empty-icon {
    width: 70px;
    height: 70px;
    background: #f0f4f8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.8rem;
    color: #aab4be;
}
.panel-empty h6 {
    font-size: .9rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: .3rem;
}
.panel-empty p {
    font-size: .78rem;
    color: #aab4be;
    margin: 0;
}

/* ── Footer ── */
.notif-panel-footer {
    padding: .85rem 1.25rem;
    border-top: 1px solid #e5ecee;
    background: #f6f8fd;
    flex-shrink: 0;
}
.view-all-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #2969bf;
    color: #fff;
    text-decoration: none;
    padding: .65rem 1rem;
    border-radius: 9px;
    font-size: .82rem;
    font-weight: 600;
    transition: background .2s, box-shadow .2s;
}
.view-all-btn:hover {
    background: #1a4f9a;
    color: #fff;
    box-shadow: 0 4px 12px rgba(41,105,191,.3);
}

/* ── Responsive ── */
@media (max-width: 480px) {
    .notif-panel { width: 100vw; }
}
</style>


{{-- ══════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════ --}}
<script>
(function () {

    // ── Open / Close panel ──
    window.openNotifPanel = function () {
        document.getElementById('notifPanel').classList.add('open');
        document.getElementById('notifOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeNotifPanel = function () {
        document.getElementById('notifPanel').classList.remove('open');
        document.getElementById('notifOverlay').classList.remove('show');
        document.body.style.overflow = '';
    };

    // ── Filter tabs ──
    window.filterPanelNotif = function (btn, type) {
        document.querySelectorAll('.panel-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.panel-notif-item').forEach(item => {
            if (type === 'all' || item.dataset.type === type) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    };

    // ── Mark single as read (panel) ──
    window.panelMarkRead = function (id, btn) {
        fetch(`/hospital/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.panel-notif-item[data-id="${id}"]`);
                if (item) {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.panel-unread-dot');
                    if (dot) dot.remove();
                    if (btn) btn.closest('.panel-notif-actions').innerHTML = '';
                }
                // Also update topbar badge
                const badge = document.getElementById('notifBadge');
                if (badge) {
                    let count = parseInt(badge.textContent) || 1;
                    count--;
                    if (count <= 0) badge.remove();
                    else badge.textContent = count > 9 ? '9+' : count;
                }
            }
        })
        .catch(console.error);
    };

    // ── Mark all as read (panel) ──
    window.panelMarkAllRead = function () {
        fetch('/hospital/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.panel-notif-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.panel-unread-dot');
                    if (dot) dot.remove();
                    const actions = item.querySelector('.panel-notif-actions');
                    if (actions) actions.innerHTML = '';
                });
                // Update topbar badge
                const badge = document.getElementById('notifBadge');
                if (badge) badge.remove();
                // Remove panel badge
                const panelBadge = document.querySelector('.notif-panel-badge');
                if (panelBadge) panelBadge.remove();
                // Remove mark-all button
                const markAllBtn = document.querySelector('.panel-mark-all-btn');
                if (markAllBtn) markAllBtn.remove();
            }
        })
        .catch(console.error);
    };

    // ── ESC key to close ──
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNotifPanel();
    });

})();
</script>
