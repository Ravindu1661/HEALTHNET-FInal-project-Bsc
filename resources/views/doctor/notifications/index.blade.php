@extends('doctor.layouts.master')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@push('styles')
<style>
/* ══════════════════════════════════════
   NOTIFICATIONS INDEX
══════════════════════════════════════ */
.notif-wrap { max-width: 880px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #0d6efd, #6f42c1);
    border-radius: 16px; padding: 1.3rem 1.5rem;
    color: #fff; margin-bottom: 1.4rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
}
.ph-icon {
    width: 50px; height: 50px; border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0; position: relative;
}
.ph-badge {
    position: absolute; top: -5px; right: -5px;
    min-width: 18px; height: 18px; border-radius: 9px;
    background: #ef4444; color: #fff;
    font-size: .62rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    padding: 0 .3rem; border: 2px solid rgba(255,255,255,.3);
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .82; margin-top: .18rem; }

/* ── Email Verify Banner ── */
.verify-banner {
    background: linear-gradient(135deg, #fff7ed, #fff3cd);
    border: 1.5px solid #fde68a;
    border-radius: 14px; padding: 1rem 1.2rem;
    margin-bottom: 1.2rem;
    display: flex; align-items: flex-start; gap: .9rem; flex-wrap: wrap;
}
.verify-banner-icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: #fef3c7;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #d97706; flex-shrink: 0;
}
.verify-banner-title {
    font-size: .82rem; font-weight: 700; color: #92400e;
}
.verify-banner-sub {
    font-size: .75rem; color: #b45309; margin-top: .18rem; line-height: 1.5;
}

/* ── Stat Cards ── */
.stat-card {
    background: #fff; border-radius: 14px;
    padding: .85rem 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; align-items: center; gap: .7rem; height: 100%;
}
.stat-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.stat-num { font-size: 1.15rem; font-weight: 800; line-height: 1; }
.stat-lbl {
    font-size: .65rem; color: #94a3b8; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; margin-top: .12rem;
}

/* ── Filter Bar ── */
.filter-bar {
    background: #fff; border-radius: 14px;
    padding: .8rem 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; gap: .45rem; flex-wrap: wrap;
    align-items: center; margin-bottom: 1.1rem;
}
.tab-pill {
    padding: .25rem .72rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600;
    border: 1.5px solid #e2e8f0; background: #fff;
    cursor: pointer; transition: all .15s; color: #64748b;
    white-space: nowrap; text-decoration: none;
    display: inline-flex; align-items: center; gap: .22rem;
}
.tab-pill:hover  { border-color: #0d6efd; color: #0d6efd; }
.tab-pill.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }
.tab-pill .pill-count {
    background: rgba(255,255,255,.3);
    border-radius: 10px; padding: .04rem .32rem;
    font-size: .6rem; font-weight: 700;
}
.tab-pill:not(.active) .pill-count { background: #f0f3f8; color: #64748b; }

/* ── Notification Card ── */
.notif-card {
    background: #fff; border-radius: 14px;
    border: 1.5px solid #f0f3f8;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    margin-bottom: .6rem;
    transition: all .2s; overflow: hidden;
    display: flex; align-items: stretch;
}
.notif-card:hover {
    box-shadow: 0 5px 18px rgba(0,0,0,.09);
    transform: translateY(-1px);
}
.notif-card.unread {
    border-left: 4px solid #0d6efd;
    background: #fafcff;
}

/* Unread dot */
.unread-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #0d6efd; flex-shrink: 0; margin-top: .3rem;
}

/* Icon col */
.notif-icon-col {
    width: 50px; flex-shrink: 0;
    display: flex; align-items: flex-start;
    justify-content: center; padding: .9rem 0 .9rem .8rem;
}
.notif-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; flex-shrink: 0;
}

/* Body */
.notif-body { flex: 1; padding: .8rem .85rem; min-width: 0; }
.notif-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a; line-height: 1.3;
}
.notif-card.unread .notif-title { color: #0d6efd; }
.notif-message {
    font-size: .75rem; color: #555; line-height: 1.5; margin-top: .18rem;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.notif-meta {
    display: flex; align-items: center; gap: .45rem;
    flex-wrap: wrap; margin-top: .4rem;
}
.notif-time { font-size: .67rem; color: #94a3b8; font-weight: 500; }
.notif-cat-badge {
    font-size: .62rem; font-weight: 700;
    padding: .1rem .42rem; border-radius: 20px;
}

/* Action col */
.notif-action-col {
    flex-shrink: 0; padding: .8rem .8rem .8rem .3rem;
    display: flex; align-items: flex-start;
}
.btn-mark-read {
    width: 28px; height: 28px; border-radius: 8px;
    border: 1.5px solid #e2e8f0; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; color: #94a3b8; cursor: pointer;
    transition: all .15s;
}
.btn-mark-read:hover { border-color: #0d6efd; color: #0d6efd; background: #f0f5ff; }

/* Category color maps */
.cat-appointment   { background:#e8f4fd; color:#1a6fa8; }
.cat-payment       { background:#e8f8f0; color:#1a7a4a; }
.cat-prescription  { background:#f3e8fe; color:#6a1aa8; }
.cat-lab_report    { background:#fff3cd; color:#856404; }
.cat-general       { background:#f0f3f8; color:#64748b; }
.ico-appointment   { background:#e8f4fd; color:#1a6fa8; }
.ico-payment       { background:#e8f8f0; color:#1a7a4a; }
.ico-prescription  { background:#f3e8fe; color:#6a1aa8; }
.ico-lab_report    { background:#fff3cd; color:#856404; }
.ico-general       { background:#f0f5ff; color:#0d6efd; }

/* ── Empty State ── */
.empty-state {
    text-align: center; padding: 3.5rem 1rem;
}
.es-icon {
    width: 68px; height: 68px; border-radius: 50%;
    background: #f0f5ff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; color: #0d6efd;
    margin: 0 auto .8rem;
}
.empty-state h6 { font-size: .9rem; font-weight: 700; margin-bottom: .3rem; }
.empty-state p  { font-size: .78rem; color: #94a3b8; margin: 0; }

/* ── Pagination ── */
.pagination .page-link {
    border-radius: 8px !important; margin: 0 2px;
    font-size: .75rem; color: #0d6efd; border-color: #e2e8f0;
}
.pagination .page-item.active .page-link {
    background: #0d6efd; border-color: #0d6efd;
}

@media (max-width:576px) {
    .notif-icon-col { width: 40px; padding-left: .6rem; }
    .notif-action-col { padding: .65rem .5rem .65rem .2rem; }
}
</style>
@endpush

@section('content')
<div class="notif-wrap">

    {{-- ══ Success / Error Alert ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════
         EMAIL VERIFICATION BANNER
         — show කරන්නේ email_verified_at null වූ විට
    ══════════════════════════════════════════════════ --}}
    @if(!$user->email_verified_at)
    <div class="verify-banner" id="verifyBanner">
        <div class="verify-banner-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div class="flex-grow-1">
            <div class="verify-banner-title">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Email Not Verified
            </div>
            <div class="verify-banner-sub">
                Your email address <strong>{{ $user->email }}</strong> has not been verified yet.
                Please check your inbox or resend the verification email.
            </div>
            {{-- Resend result message --}}
            <div id="resendResult" style="display:none;margin-top:.5rem;
                 font-size:.75rem;font-weight:600"></div>
        </div>
        <div class="flex-shrink-0 d-flex align-items-center">
            <button type="button"
                    class="btn btn-warning btn-sm"
                    id="resendBtn"
                    onclick="resendVerification()">
                <i class="fas fa-paper-plane me-1"></i>
                Resend Verification
            </button>
        </div>
    </div>
    @endif

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon">
            <i class="fas fa-bell"></i>
            @if($unreadCount > 0)
            <span class="ph-badge">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
            @endif
        </div>
        <div>
            <div class="ph-title">Notifications</div>
            <div class="ph-sub" id="headerSub">
                @if($unreadCount > 0)
                    You have <strong>{{ $unreadCount }}</strong>
                    unread notification{{ $unreadCount > 1 ? 's' : '' }}.
                @else
                    All caught up — no unread notifications.
                @endif
            </div>
        </div>

        {{-- Mark All Read button --}}
        @if($unreadCount > 0)
        <button type="button"
                class="btn btn-sm ms-auto"
                id="markAllBtn"
                style="background:rgba(255,255,255,.2);color:#fff;
                       border:1.5px solid rgba(255,255,255,.35)"
                onclick="markAllRead()">
            <i class="fas fa-check-double me-1"></i>Mark All Read
        </button>
        @endif
    </div>

    {{-- ══ Stats Row ══ --}}
    <div class="row g-3 mb-3">
        @foreach([
            ['Total',       $totalCount,                                            '#0d6efd','fa-bell',             'linear-gradient(135deg,#0d6efd22,#0d6efd55)'],
            ['Unread',      $unreadCount,                                           '#ef4444','fa-bell',             'linear-gradient(135deg,#ef444422,#ef444455)'],
            ['Read',        $totalCount - $unreadCount,                             '#198754','fa-check-circle',     'linear-gradient(135deg,#19875422,#19875455)'],
            ['Appt.',       $categoryCounts->get('appointment')->total  ?? 0,       '#1a6fa8','fa-calendar-check',   'linear-gradient(135deg,#1a6fa822,#1a6fa855)'],
            ['Payments',    $categoryCounts->get('payment')->total      ?? 0,       '#1a7a4a','fa-money-bill-wave',  'linear-gradient(135deg,#1a7a4a22,#1a7a4a55)'],
            ['Lab',         $categoryCounts->get('lab_report')->total   ?? 0,       '#856404','fa-flask',            'linear-gradient(135deg,#85640422,#85640455)'],
        ] as [$lbl,$val,$clr,$ico,$bg])
        <div class="col-6 col-sm-4 col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $bg }}">
                    <i class="fas {{ $ico }}" style="color:{{ $clr }}"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:{{ $clr }}">{{ $val }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ Filter Bar ══ --}}
    <div class="filter-bar">

        {{-- Read / Unread filter --}}
        <div class="d-flex gap-1 flex-wrap">
            @foreach([
                ['all',   'All',    $totalCount],
                ['unread','Unread', $unreadCount],
                ['read',  'Read',   $totalCount - $unreadCount],
            ] as [$val,$lbl,$cnt])
            <a href="{{ route('doctor.notifications', array_merge(request()->except('read'), ['read' => $val])) }}"
               class="tab-pill {{ $filterType === $val ? 'active' : '' }}">
                {{ $lbl }}
                <span class="pill-count">{{ $cnt }}</span>
            </a>
            @endforeach
        </div>

        <div style="width:1px;height:20px;background:#e2e8f0"></div>

        {{-- Type / Category filter --}}
        <div class="d-flex gap-1 flex-wrap">
            <a href="{{ route('doctor.notifications', array_merge(request()->except('type'), ['type' => 'all'])) }}"
               class="tab-pill {{ $filterCat === 'all' ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> All Types
            </a>
            @foreach([
                ['appointment',  'Appointments', 'fa-calendar-check'],
                ['payment',      'Payments',     'fa-money-bill-wave'],
                ['prescription', 'Prescriptions','fa-prescription-bottle-alt'],
                ['lab_report',   'Lab Reports',  'fa-flask'],
                ['general',      'General',      'fa-info-circle'],
            ] as [$val,$lbl,$ico])
            @if(isset($categoryCounts[$val]) && $categoryCounts[$val]->total > 0)
            <a href="{{ route('doctor.notifications', array_merge(request()->except('type'), ['type' => $val])) }}"
               class="tab-pill {{ $filterCat === $val ? 'active' : '' }}">
                <i class="fas {{ $ico }}"></i> {{ $lbl }}
                <span class="pill-count">{{ $categoryCounts[$val]->total }}</span>
            </a>
            @endif
            @endforeach
        </div>

        <div class="ms-auto" style="font-size:.7rem;color:#94a3b8;white-space:nowrap">
            {{ $notifications->total() }} result{{ $notifications->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    {{-- ══ Notification Cards ══ --}}
    @forelse($notifications as $notif)
    @php
        $cat      = $notif->type ?? 'general';
        $isUnread = !$notif->is_read;
        $iconMap  = [
            'appointment'  => ['fa-calendar-check',        'ico-appointment'],
            'payment'      => ['fa-money-bill-wave',        'ico-payment'],
            'prescription' => ['fa-prescription-bottle-alt','ico-prescription'],
            'lab_report'   => ['fa-flask',                  'ico-lab_report'],
            'general'      => ['fa-info-circle',            'ico-general'],
        ];
        [$icon, $iconCls] = $iconMap[$cat] ?? ['fa-bell','ico-general'];
    @endphp

    <div class="notif-card {{ $isUnread ? 'unread' : '' }}"
         id="notif-{{ $notif->id }}">

        {{-- Unread indicator ── --}}
        <div style="display:flex;align-items:flex-start;
                    padding:{{ $isUnread ? '.9rem .3rem .9rem .72rem' : '0' }}">
            @if($isUnread)
            <div class="unread-dot"></div>
            @else
            <div style="width:16px"></div>
            @endif
        </div>

        {{-- Icon ── --}}
        <div class="notif-icon-col">
            <div class="notif-icon {{ $iconCls }}">
                <i class="fas {{ $icon }}"></i>
            </div>
        </div>

        {{-- Body ── --}}
        <div class="notif-body">
            <div class="notif-title">{{ $notif->title }}</div>
            <div class="notif-message">{{ $notif->message }}</div>
            <div class="notif-meta">
                <span class="notif-time">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </span>
                @if($notif->type)
                <span class="notif-cat-badge cat-{{ $cat }}">
                    <i class="fas {{ $icon }} me-1"></i>
                    {{ ucfirst(str_replace('_',' ',$cat)) }}
                </span>
                @endif
                @if(!$isUnread && $notif->read_at)
                <span class="notif-time" style="color:#22c55e">
                    <i class="fas fa-check-double me-1"></i>
                    Read {{ \Carbon\Carbon::parse($notif->read_at)->diffForHumans() }}
                </span>
                @endif
            </div>
        </div>

        {{-- Action ── --}}
        <div class="notif-action-col">
            @if($isUnread)
            <button class="btn-mark-read"
                    title="Mark as read"
                    onclick="markOne({{ $notif->id }}, this)">
                <i class="fas fa-check"></i>
            </button>
            @else
            <div style="width:28px;height:28px;border-radius:8px;
                        display:flex;align-items:center;justify-content:center;
                        background:#f0fdf4">
                <i class="fas fa-check-double"
                   style="font-size:.7rem;color:#22c55e"></i>
            </div>
            @endif
        </div>

    </div>
    @empty

    {{-- Empty State --}}
    <div class="empty-state">
        <div class="es-icon">
            <i class="fas fa-bell-slash"></i>
        </div>
        <h6>
            @if($filterType !== 'all' || $filterCat !== 'all')
                No notifications found
            @else
                No notifications yet
            @endif
        </h6>
        <p>
            @if($filterType === 'unread')
                You're all caught up!
            @elseif($filterCat !== 'all')
                No {{ str_replace('_',' ',$filterCat) }} notifications found.
            @else
                Notifications about appointments, payments and more will appear here.
            @endif
        </p>
        @if($filterType !== 'all' || $filterCat !== 'all')
        <a href="{{ route('doctor.notifications') }}"
           class="btn btn-primary btn-sm mt-2">
            <i class="fas fa-list me-1"></i>View All
        </a>
        @endif
    </div>

    @endforelse

    {{-- ══ Pagination ══ --}}
    @if($notifications->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $notifications->links() }}
    </div>
    @endif

</div>{{-- /.notif-wrap --}}
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ══════════════════════════════════════════
// MARK SINGLE — AJAX
// ══════════════════════════════════════════
function markOne(id, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/doctor/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        const card = document.getElementById('notif-' + id);
        if (card) {
            card.classList.remove('unread');
            // Remove dot wrapper
            const dotWrap = card.querySelector('.unread-dot');
            if (dotWrap) dotWrap.parentElement.remove();
            // Replace action btn
            const ac = card.querySelector('.notif-action-col');
            if (ac) ac.innerHTML = `
                <div style="width:28px;height:28px;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;
                            background:#f0fdf4">
                    <i class="fas fa-check-double"
                       style="font-size:.7rem;color:#22c55e"></i>
                </div>`;
            const title = card.querySelector('.notif-title');
            if (title) title.style.color = '#1a1a1a';
        }
        updateBadges(data.unread_count);
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i>';
    });
}

// ══════════════════════════════════════════
// MARK ALL — AJAX
// ══════════════════════════════════════════
function markAllRead() {
    const btn = document.getElementById('markAllBtn');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing…';

    fetch('{{ route("doctor.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        document.querySelectorAll('.notif-card.unread').forEach(card => {
            card.classList.remove('unread');
            const dot = card.querySelector('.unread-dot');
            if (dot) dot.parentElement.remove();
            const ac = card.querySelector('.notif-action-col');
            if (ac) ac.innerHTML = `
                <div style="width:28px;height:28px;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;
                            background:#f0fdf4">
                    <i class="fas fa-check-double"
                       style="font-size:.7rem;color:#22c55e"></i>
                </div>`;
            const t = card.querySelector('.notif-title');
            if (t) t.style.color = '#1a1a1a';
        });
        btn.style.display = 'none';
        updateBadges(0);
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-double me-1"></i>Mark All Read';
    });
}

// ══════════════════════════════════════════
// RESEND EMAIL VERIFICATION — AJAX
// Only on email_verified_at notification banner
// ══════════════════════════════════════════
function resendVerification() {
    const btn    = document.getElementById('resendBtn');
    const result = document.getElementById('resendResult');
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending…';

    fetch('{{ route("doctor.notifications.resend-verification") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Email Sent!';
            btn.classList.replace('btn-warning', 'btn-success');
            if (result) {
                result.style.display = 'block';
                result.style.color   = '#065f46';
                result.innerHTML =
                    '<i class="fas fa-check-circle me-1"></i>' + data.message;
            }
            // Disable resend for 60 seconds to prevent spam
            let sec = 60;
            const interval = setInterval(() => {
                sec--;
                btn.innerHTML =
                    `<i class="fas fa-clock me-1"></i>Resend in ${sec}s`;
                if (sec <= 0) {
                    clearInterval(interval);
                    btn.disabled = false;
                    btn.classList.replace('btn-success', 'btn-warning');
                    btn.innerHTML =
                        '<i class="fas fa-paper-plane me-1"></i>Resend Verification';
                }
            }, 1000);
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Resend Verification';
            if (result) {
                result.style.display = 'block';
                result.style.color   = '#dc2626';
                result.innerHTML =
                    '<i class="fas fa-exclamation-circle me-1"></i>' + data.message;
            }
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Resend Verification';
    });
}

// ══════════════════════════════════════════
// UPDATE BADGE COUNTS
// ══════════════════════════════════════════
function updateBadges(count) {
    // Page header badge
    const phBadge = document.querySelector('.ph-badge');
    if (phBadge) {
        phBadge.textContent  = count > 99 ? '99+' : count;
        phBadge.style.display = count > 0 ? '' : 'none';
    }
    // Header sub text
    const sub = document.getElementById('headerSub');
    if (sub) {
        sub.innerHTML = count > 0
            ? `You have <strong>${count}</strong> unread notification${count > 1 ? 's' : ''}.`
            : 'All caught up — no unread notifications.';
    }
    // Global navbar badge (if present in layout)
    document.querySelectorAll('[data-notif-badge], #notifNavBadge').forEach(el => {
        el.textContent    = count > 99 ? '99+' : count;
        el.style.display  = count > 0 ? '' : 'none';
    });
}
</script>
@endpush
