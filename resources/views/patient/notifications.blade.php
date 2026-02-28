{{-- resources/views/patient/notifications.blade.php --}}
@include('partials.header')

<style>
/* ══ Hero ══ */
.notif-hero {
    background: linear-gradient(135deg, #004d40 0%, #00796b 100%);
    padding: 6rem 0 2.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.notif-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: .06;
    z-index: 0;
}
.notif-hero .container { position: relative; z-index: 1; }
.notif-hero::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}

/* ══ Page bg ══ */
body { background: #f4f6f9; }

/* ══ Stats Strip ══ */
.stats-strip {
    background: #fff;
    border-radius: 14px;
    padding: 1.2rem 1.5rem;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    margin: -2.5rem 0 1.8rem;
    position: relative;
    z-index: 10;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 1rem;
}
.stat-item {
    text-align: center;
    padding: .5rem;
    border-radius: 10px;
    transition: background .2s;
}
.stat-item:hover { background: #f0fdf9; }
.stat-item .s-num {
    font-size: 1.6rem;
    font-weight: 800;
    line-height: 1;
    color: #00796b;
}
.stat-item .s-lbl {
    font-size: .72rem;
    color: #888;
    margin-top: .25rem;
    font-weight: 500;
}
.stat-divider {
    width: 1px;
    background: #e8f5e9;
    align-self: stretch;
}

/* ══ Filter Card ══ */
.filter-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.1rem 1.4rem;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    margin-bottom: 1.4rem;
}
.filter-input {
    width: 100%;
    padding: .52rem .75rem;
    border: 1.5px solid #e0f2f1;
    border-radius: 8px;
    font-size: .84rem;
    transition: border .25s;
    background: #fff;
}
.filter-input:focus {
    border-color: #00796b;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,121,107,.08);
}

/* ══ Type filter tabs ══ */
.type-tabs { display: flex; gap: .4rem; flex-wrap: wrap; }
.type-tab {
    padding: .32rem .9rem;
    border-radius: 20px;
    border: 1.5px solid #e0f2f1;
    background: #fff;
    color: #555;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
}
.type-tab:hover, .type-tab.active {
    background: #00796b;
    color: #fff;
    border-color: #00796b;
}
.type-tab .tc {
    background: rgba(0,0,0,.1);
    border-radius: 10px;
    padding: .05rem .38rem;
    font-size: .68rem;
}
.type-tab.active .tc { background: rgba(255,255,255,.25); }

/* ══ Notification Card ══ */
.notif-main {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    overflow: hidden;
    margin-bottom: 3rem;
}
.notif-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.4rem;
    border-bottom: 1px solid #e8f5e9;
    background: #f9fffe;
    flex-wrap: wrap;
    gap: .5rem;
}
.notif-list-header span { font-size: .83rem; color: #666; }
.notif-list-header strong { color: #00796b; }

/* Action buttons */
.btn-teal-sm {
    background: #e0f2f1;
    color: #00695c;
    border: none;
    border-radius: 8px;
    padding: .38rem .85rem;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
}
.btn-teal-sm:hover { background: #00796b; color: #fff; }
.btn-red-sm {
    background: #fef2f2;
    color: #dc2626;
    border: none;
    border-radius: 8px;
    padding: .38rem .85rem;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
}
.btn-red-sm:hover { background: #dc2626; color: #fff; }

/* ══ Notification Item ══ */
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.4rem;
    border-bottom: 1px solid #f1f5f1;
    transition: background .15s;
    cursor: pointer;
    position: relative;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f9fffe; }
.notif-item.unread { background: #f0fdf9; }
.notif-item.unread:hover { background: #e8faf6; }
.notif-item.unread::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #00796b, #004d40);
    border-radius: 0 2px 2px 0;
}

/* Icon bubble */
.n-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: .95rem;
}
.ni-appointment  { background: #dbeafe; color: #1d4ed8; }
.ni-payment      { background: #dcfce7; color: #15803d; }
.ni-prescription { background: #f5f3ff; color: #6d28d9; }
.ni-lab_report   { background: #fff7ed; color: #c2410c; }
.ni-announcement { background: #fefce8; color: #a16207; }
.ni-general      { background: #e0f2f1; color: #00695c; }

.n-body { flex: 1; min-width: 0; }
.n-title {
    font-size: .88rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 .18rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-item:not(.unread) .n-title { font-weight: 600; color: #374151; }
.n-msg {
    font-size: .81rem;
    color: #6b7280;
    margin: 0 0 .4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.45;
}
.n-meta {
    display: flex;
    align-items: center;
    gap: .7rem;
    font-size: .72rem;
    color: #9ca3af;
    flex-wrap: wrap;
}
.n-type-badge {
    background: #e0f2f1;
    color: #00695c;
    border-radius: 10px;
    padding: .1rem .5rem;
    font-size: .68rem;
    font-weight: 700;
    text-transform: capitalize;
    display: inline-flex;
    align-items: center;
    gap: .25rem;
}

/* Item side actions */
.n-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: .35rem;
    flex-shrink: 0;
}
.unread-dot {
    width: 9px;
    height: 9px;
    background: #00796b;
    border-radius: 50%;
}
.btn-del {
    background: none;
    border: none;
    color: #d1d5db;
    cursor: pointer;
    padding: .22rem .3rem;
    font-size: .82rem;
    border-radius: 6px;
    transition: all .15s;
    line-height: 1;
}
.btn-del:hover { background: #fee2e2; color: #dc2626; }

/* ══ Empty State ══ */
.notif-empty {
    text-align: center;
    padding: 4rem 2rem;
}
.notif-empty .e-icon {
    width: 80px; height: 80px;
    background: #f0fdf9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.2rem;
    font-size: 2rem;
    color: #b2dfdb;
}
.notif-empty h5 { font-weight: 700; color: #374151; font-size: 1.05rem; margin-bottom: .4rem; }
.notif-empty p  { color: #9ca3af; font-size: .85rem; margin: 0; }

/* ══ Pagination ══ */
.pagination .page-link { color: #00796b; border-radius: 8px !important; margin: 0 2px; font-size: .82rem; }
.pagination .page-item.active .page-link { background: #00796b; border-color: #00796b; }

/* ══ Toast ══ */
#n-toast {
    position: fixed;
    bottom: 1.6rem;
    right: 1.6rem;
    background: #1a1a1a;
    color: #fff;
    padding: .7rem 1.2rem;
    border-radius: 10px;
    font-size: .83rem;
    z-index: 9999;
    display: none;
    align-items: center;
    gap: .55rem;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    animation: toastUp .25s ease;
}
@keyframes toastUp {
    from { transform: translateY(14px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

@media (max-width: 576px) {
    .notif-hero { padding: 5rem 0 2rem; }
    .notif-item { padding: .85rem 1rem; gap: .7rem; }
    .stats-strip { grid-template-columns: repeat(3, 1fr); }
    .stat-divider { display: none; }
}
</style>

{{-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ --}}
<section class="notif-hero">
    <div class="container">
        <a href="{{ route('patient.dashboard') }}"
           style="color:rgba(255,255,255,.8);font-size:.84rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="d-flex align-items-center gap-3 mb-2">
            <div style="width:60px;height:60px;background:rgba(255,255,255,.15);border-radius:14px;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.6rem;color:#fff;border:2px solid rgba(255,255,255,.3);flex-shrink:0">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h1 style="font-size:1.8rem;font-weight:800;margin:0;letter-spacing:-.02em">
                    My Notifications
                </h1>
                <p style="opacity:.82;font-size:.88rem;margin:.2rem 0 0;display:flex;align-items:center;gap:.5rem">
                    <i class="fas fa-info-circle"></i>
                    @php $heroUnread = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($heroUnread > 0)
                        You have <strong style="color:#a7f3d0;margin:0 .3rem">{{ $heroUnread }}</strong>
                        unread notification{{ $heroUnread > 1 ? 's' : '' }}
                    @else
                        All caught up — no unread notifications
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ --}}
<div class="container" style="padding-bottom:3rem">

    @php
        $totalCount  = $notifications->total();
        $unreadCount = $heroUnread;
        $readCount   = $totalCount - $unreadCount;

        $typeCounts = auth()->user()->notifications()
            ->selectRaw('type, count(*) as cnt')
            ->groupBy('type')
            ->pluck('cnt','type');
    @endphp

    {{-- ── Stats Strip ── --}}
    <div class="stats-strip">
        <div class="stat-item">
            <div class="s-num">{{ $totalCount }}</div>
            <div class="s-lbl"><i class="fas fa-list me-1" style="color:#00796b"></i>Total</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#dc2626">{{ $unreadCount }}</div>
            <div class="s-lbl"><i class="fas fa-circle me-1" style="color:#dc2626;font-size:.55rem"></i>Unread</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#16a34a">{{ $readCount }}</div>
            <div class="s-lbl"><i class="fas fa-check me-1" style="color:#16a34a"></i>Read</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#1d4ed8">{{ $typeCounts->get('appointment',0) }}</div>
            <div class="s-lbl"><i class="fas fa-calendar-check me-1" style="color:#1d4ed8"></i>Appointments</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#15803d">{{ $typeCounts->get('payment',0) }}</div>
            <div class="s-lbl"><i class="fas fa-credit-card me-1" style="color:#15803d"></i>Payments</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#c2410c">{{ $typeCounts->get('lab_report',0) }}</div>
            <div class="s-lbl"><i class="fas fa-flask me-1" style="color:#c2410c"></i>Lab Reports</div>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
            <div class="s-num" style="color:#6d28d9">{{ $typeCounts->get('prescription',0) }}</div>
            <div class="s-lbl"><i class="fas fa-pills me-1" style="color:#6d28d9"></i>Prescriptions</div>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="filter-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="type-tabs" id="typeTabs">
                <button class="type-tab active" data-filter="all">
                    <i class="fas fa-list"></i> All
                    <span class="tc">{{ $totalCount }}</span>
                </button>
                <button class="type-tab" data-filter="unread">
                    <i class="fas fa-circle" style="font-size:.45rem"></i> Unread
                    <span class="tc">{{ $unreadCount }}</span>
                </button>
                <button class="type-tab" data-filter="appointment">
                    <i class="fas fa-calendar-check"></i> Appointments
                    <span class="tc">{{ $typeCounts->get('appointment',0) }}</span>
                </button>
                <button class="type-tab" data-filter="payment">
                    <i class="fas fa-credit-card"></i> Payments
                    <span class="tc">{{ $typeCounts->get('payment',0) }}</span>
                </button>
                <button class="type-tab" data-filter="lab_report">
                    <i class="fas fa-flask"></i> Lab Reports
                    <span class="tc">{{ $typeCounts->get('lab_report',0) }}</span>
                </button>
                <button class="type-tab" data-filter="prescription">
                    <i class="fas fa-pills"></i> Prescriptions
                    <span class="tc">{{ $typeCounts->get('prescription',0) }}</span>
                </button>
                <button class="type-tab" data-filter="announcement">
                    <i class="fas fa-bullhorn"></i> Announcements
                    <span class="tc">{{ $typeCounts->get('announcement',0) }}</span>
                </button>
            </div>
        </div>

        {{-- Search --}}
        <div class="row g-2 align-items-center">
            <div class="col-md-8">
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#00796b;font-size:.82rem"></i>
                    <input type="text" id="notifSearch" class="filter-input"
                           style="padding-left:2rem"
                           placeholder="Search notifications by title or message...">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                @if($unreadCount > 0)
                <button class="btn-teal-sm" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i> Mark all read
                </button>
                @endif
                @if($totalCount > 0)
                <button class="btn-red-sm" onclick="confirmClearAll()">
                    <i class="fas fa-trash-alt"></i> Clear all
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Notification List Card ── --}}
    <div class="notif-main">

        {{-- List header --}}
        <div class="notif-list-header">
            <span>
                Showing <strong>{{ $notifications->count() }}</strong>
                of <strong>{{ $totalCount }}</strong> notifications
                @if($unreadCount > 0)
                    &nbsp;·&nbsp; <span style="color:#dc2626;font-weight:600">{{ $unreadCount }} unread</span>
                @endif
            </span>
            <span style="font-size:.75rem;color:#aaa">
                <i class="far fa-clock me-1"></i>Latest first
            </span>
        </div>

        {{-- Items --}}
        <div id="notifList">
            @forelse($notifications as $notification)
            @php
                $iconMap = [
                    'appointment'  => ['icon'=>'calendar-check', 'cls'=>'ni-appointment'],
                    'payment'      => ['icon'=>'credit-card',    'cls'=>'ni-payment'],
                    'prescription' => ['icon'=>'pills',          'cls'=>'ni-prescription'],
                    'lab_report'   => ['icon'=>'flask',          'cls'=>'ni-lab_report'],
                    'announcement' => ['icon'=>'bullhorn',       'cls'=>'ni-announcement'],
                ];
                $ic = $iconMap[$notification->type] ?? ['icon'=>'bell','cls'=>'ni-general'];
            @endphp
            <div class="notif-item {{ !$notification->is_read ? 'unread' : '' }}"
                 id="notif-{{ $notification->id }}"
                 data-type="{{ $notification->type }}"
                 data-read="{{ $notification->is_read ? '1' : '0' }}"
                 data-title="{{ strtolower($notification->title) }}"
                 data-msg="{{ strtolower($notification->message) }}"
                 onclick="markAsRead({{ $notification->id }}, this)">

                {{-- Icon --}}
                <div class="n-icon {{ $ic['cls'] }}">
                    <i class="fas fa-{{ $ic['icon'] }}"></i>
                </div>

                {{-- Body --}}
                <div class="n-body">
                    <p class="n-title">{{ $notification->title }}</p>
                    <p class="n-msg">{{ $notification->message }}</p>
                    <div class="n-meta">
                        <span><i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                        <span><i class="far fa-calendar-alt me-1"></i>{{ $notification->created_at->format('d M Y, h:i A') }}</span>
                        <span class="n-type-badge">
                            <i class="fas fa-{{ $ic['icon'] }}"></i>
                            {{ ucwords(str_replace('_', ' ', $notification->type)) }}
                        </span>
                        @if($notification->is_read && $notification->read_at)
                            <span style="color:#b2dfdb">
                                <i class="fas fa-eye me-1"></i>Read {{ \Carbon\Carbon::parse($notification->read_at)->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="n-actions" onclick="event.stopPropagation()">
                    @if(!$notification->is_read)
                        <span class="unread-dot" id="dot-{{ $notification->id }}"></span>
                    @endif
                    <button class="btn-del"
                            title="Delete notification"
                            onclick="deleteNotif({{ $notification->id }})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="notif-empty" id="emptyState">
                <div class="e-icon"><i class="fas fa-bell-slash"></i></div>
                <h5>No Notifications Yet</h5>
                <p>You're all caught up! Notifications will appear here when there's activity on your account.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div style="padding:1rem 1.4rem;border-top:1px solid #e8f5e9;display:flex;justify-content:center">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Toast --}}
<div id="n-toast">
    <i class="fas fa-check-circle" style="color:#4ade80"></i>
    <span id="toast-txt">Done!</span>
</div>

<script>
const _csrf = document.querySelector('meta[name="csrf-token"]').content;

/* ── Toast ── */
function toast(msg, err = false) {
    const t = document.getElementById('n-toast');
    document.getElementById('toast-txt').textContent = msg;
    t.querySelector('i').style.color = err ? '#f87171' : '#4ade80';
    t.querySelector('i').className   = err ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
    t.style.display = 'flex';
    clearTimeout(t._tid);
    t._tid = setTimeout(() => t.style.display = 'none', 3000);
}

/* ── Mark single as read ── */
function markAsRead(id, el) {
    if (el && el.dataset.read === '1') return;
    fetch(`/patient/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrf, 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        const item = document.getElementById(`notif-${id}`);
        if (item) {
            item.classList.remove('unread');
            item.dataset.read = '1';
            document.getElementById(`dot-${id}`)?.remove();
        }
        nudgeBadge(-1);
    }).catch(() => toast('Failed to mark as read', true));
}

/* ── Mark all as read ── */
function markAllAsRead() {
    fetch('/patient/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrf, 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        document.querySelectorAll('.notif-item.unread').forEach(el => {
            el.classList.remove('unread');
            el.dataset.read = '1';
            el.querySelector('.unread-dot')?.remove();
        });
        nudgeBadge(0, true);
        document.querySelector('.btn-teal-sm')?.remove();
        toast('All notifications marked as read ✓');
    }).catch(() => toast('Failed', true));
}

/* ── Delete single ── */
function deleteNotif(id) {
    fetch(`/patient/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': _csrf }
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        const el = document.getElementById(`notif-${id}`);
        if (el) {
            el.style.transition = 'opacity .22s, transform .22s';
            el.style.opacity = '0';
            el.style.transform = 'translateX(20px)';
            setTimeout(() => { el.remove(); checkEmpty(); }, 230);
        }
        toast('Notification deleted');
    }).catch(() => toast('Failed to delete', true));
}

/* ── Clear all ── */
function confirmClearAll() {
    if (!confirm('Clear all notifications? This cannot be undone.')) return;
    const ids = [...document.querySelectorAll('.notif-item')]
        .map(el => el.id.replace('notif-',''));
    Promise.all(ids.map(id => fetch(`/patient/notifications/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': _csrf }
    }))).then(() => {
        document.querySelectorAll('.notif-item').forEach(el => el.remove());
        nudgeBadge(0, true);
        checkEmpty();
        toast('All notifications cleared');
    }).catch(() => toast('Failed to clear all', true));
}

/* ── Filter tabs ── */
document.querySelectorAll('.type-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.type-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        applyFilters();
    });
});

/* ── Live search ── */
document.getElementById('notifSearch').addEventListener('input', applyFilters);

function applyFilters() {
    const filter = document.querySelector('.type-tab.active')?.dataset.filter || 'all';
    const q      = document.getElementById('notifSearch').value.toLowerCase().trim();

    document.querySelectorAll('.notif-item').forEach(item => {
        let show = true;
        if (filter === 'unread')         show = item.dataset.read === '0';
        else if (filter !== 'all')       show = item.dataset.type === filter;
        if (show && q)                   show = item.dataset.title.includes(q) || item.dataset.msg.includes(q);
        item.style.display = show ? 'flex' : 'none';
    });
    checkEmpty();
}

/* ── Empty state ── */
function checkEmpty() {
    const visible = [...document.querySelectorAll('.notif-item')]
        .filter(el => el.style.display !== 'none').length;
    let es = document.getElementById('emptyState');
    if (visible === 0 && !es) {
        const d = document.createElement('div');
        d.id = 'emptyState';
        d.className = 'notif-empty';
        d.innerHTML = `<div class="e-icon"><i class="fas fa-bell-slash"></i></div>
            <h5>No Notifications Found</h5>
            <p>No notifications match the selected filter.</p>`;
        document.getElementById('notifList').appendChild(d);
    } else if (visible > 0 && es) {
        es.remove();
    }
}

/* ── Update navbar bell badge ── */
function nudgeBadge(delta, reset = false) {
    const badge = document.querySelector('.notification-badge');
    if (!badge) return;
    let n = reset ? 0 : Math.max(0, (parseInt(badge.textContent) || 0) + delta);
    badge.textContent   = n > 9 ? '9+' : n;
    badge.style.display = n > 0 ? 'flex' : 'none';
}
</script>

@include('partials.footer')
