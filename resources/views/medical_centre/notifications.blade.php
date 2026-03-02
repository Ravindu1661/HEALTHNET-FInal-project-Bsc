{{-- resources/views/medical_centre/notifications.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

<style>
.mc-page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
}
.mc-page-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin: 0 0 .2rem; }
.mc-page-sub   { font-size: .82rem; color: var(--text-muted); margin: 0; }

.mc-btn {
    display: inline-flex; align-items: center;
    padding: .5rem 1rem; border-radius: 9px;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    border: none; transition: var(--transition); font-family: inherit;
    text-decoration: none;
}
.mc-btn-outline {
    background: transparent;
    border: 1.5px solid var(--mc-primary);
    color: var(--mc-primary);
}
.mc-btn-outline:hover { background: var(--mc-primary-light); }

/* ── Email Verification Banner ── */
.verify-banner {
    display: flex; align-items: center; gap: 1rem;
    background: #fffbeb; border: 1.5px solid #fde68a;
    border-radius: 12px; padding: .9rem 1.1rem;
    margin-bottom: 1.25rem; flex-wrap: wrap;
}
.verify-banner-icon {
    width: 38px; height: 38px; border-radius: 9px;
    background: #fef3c7; color: #d97706;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.verify-banner-body { flex: 1; min-width: 0; }
.verify-banner-body h6 { font-size: .85rem; font-weight: 700; color: #92400e; margin: 0 0 .15rem; }
.verify-banner-body p  { font-size: .78rem; color: #a16207; margin: 0; }
.verify-banner-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .45rem .9rem; border-radius: 8px;
    background: #d97706; color: #fff; border: none;
    font-size: .78rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: var(--transition);
    white-space: nowrap; flex-shrink: 0;
}
.verify-banner-btn:hover { background: #b45309; }

/* ── Filter Bar ── */
.notif-filter-bar {
    display: flex; align-items: center; gap: .5rem;
    background: #fff; border-radius: 12px;
    padding: .6rem .75rem; margin-bottom: 1.25rem;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
}
.notif-filter-btn {
    padding: .4rem .9rem; border-radius: 8px; border: none;
    font-size: .78rem; font-weight: 600; cursor: pointer;
    background: transparent; color: var(--text-muted);
    transition: var(--transition); font-family: inherit;
    text-decoration: none;
}
.notif-filter-btn:hover { background: #f4f7fb; color: var(--text-dark); }
.notif-filter-btn.active { background: var(--mc-primary-light); color: var(--mc-primary); }

.notif-type-select {
    padding: .38rem .75rem; border-radius: 8px;
    border: 1.5px solid var(--border); font-size: .78rem;
    font-weight: 600; color: var(--text-dark);
    background: #f8fbff; cursor: pointer; font-family: inherit;
    outline: none; margin-left: auto;
}
.notif-type-select:focus { border-color: var(--mc-primary); }

/* ── Notification Card ── */
.notif-card {
    background: #fff; border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden; margin-bottom: 1rem;
}
.notif-item {
    display: flex; align-items: flex-start; gap: .85rem;
    padding: 1rem 1.1rem; border-bottom: 1px solid #f5f7fa;
    transition: background .15s; position: relative;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f8fbff; }
.notif-item.unread { background: #f0faf7; }
.notif-item.unread::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--mc-primary); border-radius: 0 2px 2px 0;
}

.notif-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.notif-body { flex: 1; min-width: 0; }
.notif-body h6 {
    font-size: .85rem; font-weight: 700; color: var(--text-dark);
    margin: 0 0 .2rem;
}
.notif-body p {
    font-size: .78rem; color: var(--text-muted); margin: 0 0 .3rem;
    line-height: 1.5;
}
.notif-body time { font-size: .68rem; color: #b0bec5; }

.notif-actions {
    display: flex; flex-direction: column; align-items: flex-end;
    gap: .4rem; flex-shrink: 0;
}
.notif-dot {
    width: 9px; height: 9px; border-radius: 50%;
    background: var(--mc-primary); flex-shrink: 0;
}
.notif-action-btn {
    background: none; border: none; cursor: pointer;
    font-size: .7rem; color: #b0bec5; padding: .2rem .35rem;
    border-radius: 6px; transition: var(--transition); font-family: inherit;
}
.notif-action-btn:hover { background: #f4f7fb; color: var(--text-dark); }
.notif-action-btn.del:hover { background: #fdecea; color: #e74c3c; }

/* ── Empty State ── */
.notif-empty {
    padding: 3.5rem 1rem; text-align: center;
    color: var(--text-muted);
}
.notif-empty i { font-size: 3rem; display: block; margin-bottom: .75rem; opacity: .3; }
.notif-empty h5 { font-size: 1rem; font-weight: 700; margin-bottom: .35rem; }
.notif-empty p { font-size: .82rem; }

/* ── Pagination ── */
.notif-pagination {
    display: flex; justify-content: space-between; align-items: center;
    padding: .85rem 1.1rem; border-top: 1px solid var(--border);
    font-size: .78rem; color: var(--text-muted);
    background: #fff; border-radius: 0 0 12px 12px;
    flex-wrap: wrap; gap: .5rem;
}
.notif-pagination .pagination { margin: 0; }
.notif-pagination .page-link {
    font-size: .75rem; padding: .3rem .6rem; color: var(--text-dark);
    border-color: var(--border);
}
.notif-pagination .page-item.active .page-link {
    background: var(--mc-primary); border-color: var(--mc-primary);
}
</style>

{{-- ── Page Header ── --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-bell me-2" style="color:var(--mc-primary);"></i>
            Notifications
        </h4>
        <p class="mc-page-sub">Manage your alerts and updates</p>
    </div>
    @if($unreadCount > 0)
        <form method="POST" action="{{ route('medical_centre.notifications.mark-all-read') }}" style="margin:0;">
            @csrf
            <button type="submit" class="mc-btn mc-btn-outline">
                <i class="fas fa-check-double me-2"></i>Mark all as read
                <span class="ms-1" style="background:var(--mc-primary);color:#fff;
                    border-radius:99px;font-size:.65rem;padding:.1rem .4rem;font-weight:800;">
                    {{ $unreadCount }}
                </span>
            </button>
        </form>
    @endif
</div>

{{-- ── Email Verification Banner ── --}}
@if(!auth()->user()->hasVerifiedEmail())
    <div class="verify-banner">
        <div class="verify-banner-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div class="verify-banner-body">
            <h6><i class="fas fa-exclamation-triangle me-1"></i>Email Not Verified</h6>
            <p>
                Your email address <strong>{{ auth()->user()->email }}</strong> is not verified.
                Please verify your email to receive notifications.
            </p>
        </div>
        <form method="POST" action="{{ route('medical_centre.resend-verification') }}" style="margin:0;">
            @csrf
            <button type="submit" class="verify-banner-btn">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
        </form>
    </div>
@endif

{{-- ── Alert Messages ── --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#dbeafe;color:#1e40af;">
        <i class="fas fa-info-circle"></i>
        {{ session('info') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Filter Bar ── --}}
<div class="notif-filter-bar">
    <a href="{{ route('medical_centre.notifications', ['type' => $type]) }}"
       class="notif-filter-btn {{ $filter === 'all' ? 'active' : '' }}">
        All
        @if($notifications->total() > 0)
            <span style="background:#e9ecef;border-radius:99px;font-size:.65rem;
                padding:.05rem .35rem;margin-left:.25rem;font-weight:800;color:var(--text-muted);">
                {{ $notifications->total() }}
            </span>
        @endif
    </a>
    <a href="{{ route('medical_centre.notifications', ['filter' => 'unread', 'type' => $type]) }}"
       class="notif-filter-btn {{ $filter === 'unread' ? 'active' : '' }}">
        Unread
        @if($unreadCount > 0)
            <span style="background:var(--mc-primary);color:#fff;border-radius:99px;
                font-size:.65rem;padding:.05rem .35rem;margin-left:.25rem;font-weight:800;">
                {{ $unreadCount }}
            </span>
        @endif
    </a>
    <a href="{{ route('medical_centre.notifications', ['filter' => 'read', 'type' => $type]) }}"
       class="notif-filter-btn {{ $filter === 'read' ? 'active' : '' }}">
        Read
    </a>

    <select class="notif-type-select" onchange="filterByType(this.value)">
        <option value="">All Types</option>
        <option value="appointment" {{ $type === 'appointment' ? 'selected' : '' }}>Appointments</option>
        <option value="doctor"      {{ $type === 'doctor'      ? 'selected' : '' }}>Doctors</option>
        <option value="payment"     {{ $type === 'payment'     ? 'selected' : '' }}>Payments</option>
        <option value="system"      {{ $type === 'system'      ? 'selected' : '' }}>System</option>
        <option value="general"     {{ $type === 'general'     ? 'selected' : '' }}>General</option>
    </select>
</div>

@php
$typeMap = [
    'appointment' => ['icon' => 'fa-calendar-check', 'bg' => '#e8f0fe', 'color' => '#2969bf'],
    'payment'     => ['icon' => 'fa-credit-card',    'bg' => '#d1e7dd', 'color' => '#0a3622'],
    'doctor'      => ['icon' => 'fa-user-md',        'bg' => '#f0ebff', 'color' => '#8e44ad'],
    'system'      => ['icon' => 'fa-cog',            'bg' => '#fff3cd', 'color' => '#856404'],
    'general'     => ['icon' => 'fa-info-circle',    'bg' => '#f4f7fb', 'color' => '#555'   ],
];
@endphp

{{-- ── Notification List ── --}}
<div class="notif-card">
    @forelse($notifications as $notif)
        @php $t = $typeMap[$notif->type] ?? $typeMap['general']; @endphp
        <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">

            <div class="notif-icon"
                 style="background:{{ $t['bg'] }};color:{{ $t['color'] }};">
                <i class="fas {{ $t['icon'] }}"></i>
            </div>

            <div class="notif-body">
                <h6>{{ $notif->title }}</h6>
                <p>{{ $notif->message }}</p>
                <time>
                    <i class="fas fa-clock me-1" style="font-size:.6rem;"></i>
                    {{ $notif->created_at->diffForHumans() }}
                    &nbsp;·&nbsp;
                    {{ $notif->created_at->format('M d, Y · h:i A') }}
                </time>
            </div>

            <div class="notif-actions">
                @if(!$notif->is_read)
                    <div class="notif-dot" title="Unread"></div>
                    <form method="POST"
                          action="{{ route('medical_centre.notifications.read', $notif->id) }}"
                          style="margin:0;">
                        @csrf
                        <button type="submit" class="notif-action-btn" title="Mark as read">
                            <i class="fas fa-check me-1"></i>Read
                        </button>
                    </form>
                @else
                    <span style="font-size:.68rem;color:#b0bec5;">
                        <i class="fas fa-check-double me-1"></i>Read
                    </span>
                @endif

                <form method="POST"
                      action="{{ route('medical_centre.notifications.delete', $notif->id) }}"
                      style="margin:0;"
                      onsubmit="return confirm('Delete this notification?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="notif-action-btn del" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="notif-empty">
            <i class="fas fa-bell-slash"></i>
            <h5>No notifications found</h5>
            <p>
                @if($filter === 'unread')
                    You have no unread notifications.
                @elseif($filter === 'read')
                    You have no read notifications.
                @else
                    You have no notifications yet.
                @endif
            </p>
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="notif-pagination">
            <span>
                Showing {{ $notifications->firstItem() }} – {{ $notifications->lastItem() }}
                of {{ $notifications->total() }} notifications
            </span>
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script>
function filterByType(val) {
    const url = new URL(window.location.href);
    if (val) url.searchParams.set('type', val);
    else     url.searchParams.delete('type');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
@endsection
