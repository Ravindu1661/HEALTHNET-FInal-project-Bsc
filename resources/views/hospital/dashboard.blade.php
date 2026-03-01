{{-- resources/views/hospital/dashboard.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ══ STAT CARDS ══ */
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.3rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 16px rgba(44,62,80,.07);
    border: 1px solid #f0f4f8;
    transition: transform .25s, box-shadow .25s;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 16px 16px 0 0;
}
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(44,62,80,.12); }
.stat-card.blue::before   { background: linear-gradient(90deg,#2969bf,#5b9bd5); }
.stat-card.green::before  { background: linear-gradient(90deg,#27ae60,#6fcf97); }
.stat-card.orange::before { background: linear-gradient(90deg,#f39c12,#f7c04a); }
.stat-card.purple::before { background: linear-gradient(90deg,#8e44ad,#c39bd3); }

.stat-icon {
    width: 56px; height: 56px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.45rem; flex-shrink: 0;
}
.stat-icon.blue   { background:rgba(41,105,191,.1);  color:#2969bf; }
.stat-icon.green  { background:rgba(39,174,96,.1);   color:#27ae60; }
.stat-icon.orange { background:rgba(243,156,18,.1);  color:#f39c12; }
.stat-icon.purple { background:rgba(142,68,173,.1);  color:#8e44ad; }

.stat-info h3 { font-size:1.9rem; font-weight:700; margin:0; line-height:1; }
.stat-info p  { font-size:.78rem; color:#888; margin:0; margin-top:4px; }

/* ══ CARDS ══ */
.dashboard-card {
    background:#fff; border-radius:16px;
    border:1px solid #f0f4f8;
    box-shadow:0 2px 16px rgba(44,62,80,.06);
    margin-bottom:1.5rem; overflow:hidden;
}
.card-header {
    padding:.85rem 1.3rem; background:#fff;
    border-bottom:1px solid #f0f4f8;
    display:flex; align-items:center; justify-content:space-between;
}
.card-header h6 {
    margin:0; font-weight:700; font-size:.93rem;
    display:flex; align-items:center; gap:.5rem; color:#2c3e50;
}
.card-header h6 i { color:#2969bf; }
.card-body { padding:1.2rem 1.3rem; }

/* ══ STATUS BADGES ══ */
.status-badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:50px;
    font-size:.72rem; font-weight:600;
}
.status-badge.pending   { background:#fff3cd; color:#856404; }
.status-badge.confirmed { background:#cfe2ff; color:#084298; }
.status-badge.completed { background:#d1e7dd; color:#0f5132; }
.status-badge.cancelled { background:#f8d7da; color:#842029; }

/* ══ APPOINTMENT ITEMS ══ */
.apt-item {
    display:flex; align-items:center; gap:.9rem;
    padding:.75rem 0; border-bottom:1px solid #f5f7fa;
}
.apt-item:last-child { border-bottom:none; }
.apt-time {
    min-width:55px; text-align:center;
    background:#f0f6ff; border-radius:10px;
    padding:.35rem .3rem; font-size:.72rem;
    font-weight:700; color:#2969bf; line-height:1.4;
}
.apt-avatar {
    width:36px; height:36px; border-radius:50%;
    background:linear-gradient(135deg,#e8f0fe,#d0e4ff);
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:700; color:#2969bf; flex-shrink:0;
}
.apt-info { flex:1; min-width:0; }
.apt-info .name { font-weight:600; font-size:.85rem; color:#2c3e50; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.apt-info .sub  { font-size:.73rem; color:#888; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* ══ MINI BARS ══ */
.mini-stat { display:flex; align-items:center; gap:.7rem; padding:.55rem 0; border-bottom:1px solid #f5f7fa; }
.mini-stat:last-child { border-bottom:none; }
.mini-stat-label { font-size:.78rem; color:#555; font-weight:500; min-width:72px; }
.mini-stat-bar   { flex:1; height:8px; background:#f0f4f8; border-radius:4px; overflow:hidden; }
.mini-stat-fill  { height:100%; border-radius:4px; }
.mini-stat-count { font-size:.78rem; font-weight:700; min-width:24px; text-align:right; }

/* ══ REVIEWS ══ */
.review-item { padding:.8rem 0; border-bottom:1px solid #f5f7fa; }
.review-item:last-child { border-bottom:none; }
.review-stars { color:#f39c12; font-size:.72rem; }
.review-text  { font-size:.8rem; color:#555; margin:.3rem 0; line-height:1.5; }
.review-meta  { font-size:.7rem; color:#aaa; }

/* ══ BANNER ══ */
.hospital-banner {
    background:linear-gradient(135deg,#1a3a6b 0%,#2969bf 60%,#4a90d9 100%);
    border-radius:16px; padding:1.4rem 1.6rem;
    color:#fff; display:flex; align-items:center; gap:1.2rem;
    margin-bottom:1.5rem; position:relative; overflow:hidden;
}
.hospital-banner::after {
    content:''; position:absolute; right:-40px; top:-40px;
    width:200px; height:200px; border-radius:50%;
    background:rgba(255,255,255,.06);
}
.hospital-banner-logo {
    width:66px; height:66px; border-radius:14px;
    object-fit:cover; border:3px solid rgba(255,255,255,.3); flex-shrink:0;
}
.hospital-banner-placeholder {
    width:66px; height:66px; border-radius:14px;
    background:rgba(255,255,255,.15); border:3px solid rgba(255,255,255,.25);
    display:flex; align-items:center; justify-content:center;
    font-size:1.9rem; flex-shrink:0;
}
.hb-badge {
    background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.3);
    color:#fff; font-size:.7rem; font-weight:600;
    padding:2px 10px; border-radius:50px; display:inline-block; margin-top:5px;
}

/* ══ QUICK ACTIONS ══ */
.quick-action {
    display:flex; flex-direction:column; align-items:center;
    gap:.45rem; padding:.9rem .4rem; border-radius:14px;
    border:1.5px solid #e8f0fe; text-decoration:none;
    transition:all .25s; background:#fafcff; color:#2969bf; text-align:center;
}
.quick-action:hover {
    background:#2969bf; color:#fff; border-color:#2969bf;
    transform:translateY(-3px); box-shadow:0 6px 20px rgba(41,105,191,.2);
}
.quick-action i    { font-size:1.3rem; }
.quick-action span { font-size:.7rem; font-weight:600; line-height:1.3; }

/* ══ RESPONSIVE ══ */
@media (max-width:575.98px) {
    .stat-card       { padding:1rem; }
    .stat-icon       { width:44px; height:44px; font-size:1.1rem; }
    .stat-info h3    { font-size:1.5rem; }
    .hospital-banner { flex-direction:column; text-align:center; padding:1.2rem; }
    .hospital-banner .ms-auto { margin-left:0 !important; margin-top:.5rem; }
}
@media (max-width:767.98px) {
    .card-header { flex-wrap:wrap; gap:.5rem; }
}
</style>
@endpush

@section('content')

{{-- ══ BANNER ══ --}}
<div class="hospital-banner">
    @if($hospital && $hospital->profile_image)
        <img src="{{ asset('storage/'.$hospital->profile_image) }}"
             alt="{{ $hospital->name }}" class="hospital-banner-logo">
    @else
        <div class="hospital-banner-placeholder"><i class="fas fa-hospital"></i></div>
    @endif
    <div style="flex:1;min-width:0;position:relative;z-index:1;">
        <h5 style="font-weight:700;margin:0;font-size:1.1rem;">
            {{ $hospital->name ?? 'Hospital Dashboard' }}
        </h5>
        <p style="margin:3px 0 0;opacity:.85;font-size:.8rem;">
            <i class="fas fa-map-marker-alt me-1"></i>
            {{ $hospital->city ?? 'N/A' }}{{ $hospital && $hospital->province ? ', '.$hospital->province : '' }}
            &nbsp;·&nbsp;
            <i class="fas fa-phone me-1"></i>{{ $hospital->phone ?? 'N/A' }}
        </p>
        <span class="hb-badge">
            <i class="fas fa-circle me-1"
               style="font-size:.4rem;vertical-align:middle;
               color:{{ ($hospital->status??'')=='approved' ? '#6fcf97' : '#f7c04a' }};"></i>
            {{ ucfirst($hospital->status ?? 'N/A') }}
        </span>
        &nbsp;
        <span class="hb-badge">
            <i class="fas fa-hospital-alt me-1"></i>
            {{ ucfirst($hospital->type ?? 'N/A') }}
        </span>
    </div>
    <div class="ms-auto d-none d-md-block" style="position:relative;z-index:1;">
        <a href="{{ route('hospital.profile') }}"
           class="btn btn-sm"
           style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);">
            <i class="fas fa-edit me-1"></i> Edit Profile
        </a>
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue h-100">
            <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <h3>{{ $todayAppointments }}</h3>
                <p>Today's Appointments</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green h-100">
            <div class="stat-icon green"><i class="fas fa-user-md"></i></div>
            <div class="stat-info">
                <h3>{{ $totalDoctors }}</h3>
                <p>Active Doctors</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card orange h-100">
            <div class="stat-icon orange"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-info">
                <h3>{{ $appointmentStats['total'] }}</h3>
                <p>This Month ({{ now()->format('M Y') }})</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card purple h-100">
            <div class="stat-icon purple"><i class="fas fa-star"></i></div>
            <div class="stat-info">
                <h3>{{ $avgRating }}</h3>
                <p>Average Rating</p>
                <div style="color:#f39c12;font-size:.68rem;margin-top:3px;">
                    @for($i=1;$i<=5;$i++)
                        <i class="fa{{ $i <= round($avgRating) ? 's' : 'r' }} fa-star"></i>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ROW 2 — Appointments + Overview ══ --}}
<div class="row g-3 mb-3">

    {{-- Today Appointments --}}
    <div class="col-12 col-lg-7">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-calendar-day"></i> Today's Appointments</h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">{{ $todayAppointments }}</span>
                    <a href="{{ route('hospital.appointments') }}"
                       class="btn btn-sm btn-outline-primary" style="font-size:.73rem;">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body" style="max-height:360px;overflow-y:auto;">
                @forelse($todayAptList as $apt)
                @php
                    $initials = collect(explode(' ', $apt->patient_name))
                        ->map(fn($w) => strtoupper(substr($w,0,1)))
                        ->take(2)->join('');
                    $statusMap = [
                        'pending'   => ['pending',  'fa-clock'],
                        'confirmed' => ['confirmed','fa-check-circle'],
                        'completed' => ['completed','fa-check-double'],
                        'cancelled' => ['cancelled','fa-times-circle'],
                    ];
                    [$cls,$icon] = $statusMap[$apt->status] ?? ['pending','fa-clock'];
                @endphp
                <div class="apt-item">
                    <div class="apt-time">{{ $apt->apt_time ?? '--' }}</div>
                    <div class="apt-avatar">{{ $initials }}</div>
                    <div class="apt-info">
                        <div class="name">{{ $apt->patient_name }}</div>
                        <div class="sub">
                            <i class="fas fa-user-md me-1"></i>{{ $apt->doctor_name }}
                            <span class="ms-1">({{ $apt->specialization }})</span>
                        </div>
                    </div>
                    <div class="ms-auto">
                        <span class="status-badge {{ $cls }}">
                            <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($apt->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">No appointments today</h6>
                    <p class="text-muted small">Enjoy a quiet day!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Monthly Overview --}}
    <div class="col-12 col-lg-5">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-chart-pie"></i> Monthly Overview</h6>
                <small class="text-muted">{{ now()->format('F Y') }}</small>
            </div>
            <div class="card-body">
                {{-- 4 Numbers --}}
                <div class="row g-2 mb-3 text-center">
                    <div class="col-3">
                        <div style="font-size:1.5rem;font-weight:700;color:#2969bf;">
                            {{ $appointmentStats['total'] }}
                        </div>
                        <div style="font-size:.68rem;color:#888;">Total</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.5rem;font-weight:700;color:#27ae60;">
                            {{ $appointmentStats['completed'] }}
                        </div>
                        <div style="font-size:.68rem;color:#888;">Completed</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.5rem;font-weight:700;color:#f39c12;">
                            {{ $appointmentStats['pending'] }}
                        </div>
                        <div style="font-size:.68rem;color:#888;">Pending</div>
                    </div>
                    <div class="col-3">
                        <div style="font-size:1.5rem;font-weight:700;color:#e74c3c;">
                            {{ $appointmentStats['cancelled'] }}
                        </div>
                        <div style="font-size:.68rem;color:#888;">Cancelled</div>
                    </div>
                </div>

                {{-- Mini Bars --}}
                @php
                    $bars = [
                        'confirmed' => ['#cfe2ff','#084298'],
                        'completed' => ['#d1e7dd','#0f5132'],
                        'pending'   => ['#fff3cd','#856404'],
                        'cancelled' => ['#f8d7da','#842029'],
                    ];
                    $total = max($appointmentStats['total'], 1);
                @endphp
                @foreach($bars as $key => [$bg,$color])
                @php $val = $appointmentStats[$key] ?? 0;
                     $pct = round(($val/$total)*100); @endphp
                <div class="mini-stat">
                    <span class="mini-stat-label">{{ ucfirst($key) }}</span>
                    <div class="mini-stat-bar">
                        <div class="mini-stat-fill"
                             style="width:{{ $pct }}%;background:{{ $bg }};border:1px solid {{ $color }};"></div>
                    </div>
                    <span class="mini-stat-count" style="color:{{ $color }};">{{ $val }}</span>
                </div>
                @endforeach

                {{-- Completion Rate --}}
                @php
                    $compRate = $appointmentStats['total']
                        ? round(($appointmentStats['completed'] / $appointmentStats['total']) * 100)
                        : 0;
                @endphp
                <div class="mt-3 p-3 rounded-3" style="background:#f8fbff;">
                    <div class="d-flex justify-content-between mb-1">
                        <small style="font-weight:600;color:#555;">Completion Rate</small>
                        <small style="font-weight:700;color:#27ae60;">{{ $compRate }}%</small>
                    </div>
                    <div style="height:10px;background:#e8f0fe;border-radius:5px;overflow:hidden;">
                        <div style="height:100%;background:linear-gradient(90deg,#27ae60,#6fcf97);
                                    border-radius:5px;width:{{ $compRate }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ROW 3 — Reviews + Quick Actions + Info ══ --}}
<div class="row g-3">

    {{-- Recent Reviews --}}
    <div class="col-12 col-lg-7">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-star"></i> Recent Reviews</h6>
                <a href="{{ route('hospital.reviews') }}"
                   class="btn btn-sm btn-outline-primary" style="font-size:.73rem;">
                    All Reviews <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @forelse($recentReviews as $rev)
                @php
                    $rInitials = collect(explode(' ', $rev->patient_name))
                        ->map(fn($w) => strtoupper(substr($w,0,1)))
                        ->take(2)->join('');
                @endphp
                <div class="review-item">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="apt-avatar" style="background:linear-gradient(135deg,#ffeaa7,#fdcb6e);color:#d68910;">
                            {{ $rInitials }}
                        </div>
                        <strong style="font-size:.82rem;">{{ $rev->patient_name }}</strong>
                        <div class="review-stars ms-auto">
                            @for($i=1;$i<=5;$i++)
                                <i class="fa{{ $i <= $rev->rating ? 's' : 'r' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="review-text mb-1">
                        {{ $rev->comment ? Str::limit($rev->comment, 120) : 'No comment' }}
                    </p>
                    <div class="review-meta">
                        <i class="fas fa-clock me-1"></i>
                        {{ Carbon\Carbon::parse($rev->created_at)->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-star fa-3x text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">No reviews yet</h6>
                    <p class="text-muted small">Reviews from patients will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">

        {{-- Quick Actions --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([
                        ['hospital.appointments', 'fa-calendar-check', 'Appointments'],
                        ['hospital.doctors',      'fa-user-md',        'Doctors'],
                        ['hospital.reports',      'fa-chart-bar',      'Reports'],
                        ['hospital.reviews',      'fa-star',           'Reviews'],
                        ['hospital.profile',      'fa-hospital',       'Profile'],
                        ['hospital.settings',     'fa-cog',            'Settings'],
                    ] as [$route, $icon, $label])
                    <div class="col-4">
                        <a href="{{ route($route) }}" class="quick-action">
                            <i class="fas {{ $icon }}"></i>
                            <span>{{ $label }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Hospital Info --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Hospital Info</h6>
                <a href="{{ route('hospital.profile') }}"
                   class="btn btn-sm btn-light" style="font-size:.72rem;">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="font-size:.82rem;">
                    @foreach([
                        ['fa-envelope',        'Email',    $hospital->email           ?? 'N/A'],
                        ['fa-phone',           'Phone',    $hospital->phone           ?? 'N/A'],
                        ['fa-map-marker-alt',  'Address',  $hospital->address         ?? 'N/A'],
                        ['fa-clock',           'Hours',    $hospital->operating_hours ?? 'N/A'],
                    ] as [$icon, $label, $value])
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="text-muted">
                            <i class="fas {{ $icon }} me-2"></i>{{ $label }}
                        </span>
                        <span class="text-truncate ms-2" style="max-width:155px;">{{ $value }}</span>
                    </li>
                    @endforeach

                    {{-- Website --}}
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="text-muted"><i class="fas fa-globe me-2"></i>Website</span>
                        @if($hospital && $hospital->website)
                            <a href="{{ $hospital->website }}" target="_blank"
                               class="text-primary text-truncate ms-2" style="max-width:155px;">
                                {{ $hospital->website }}
                            </a>
                        @else
                            <span class="text-muted ms-2">N/A</span>
                        @endif
                    </li>

                    {{-- Specializations --}}
                    @if(!empty($specializations))
                    <li class="list-group-item px-3 py-2">
                        <span class="text-muted d-block mb-2">
                            <i class="fas fa-stethoscope me-2"></i>Specializations
                        </span>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($specializations as $spec)
                                <span class="badge bg-light text-primary border"
                                      style="font-size:.68rem;">{{ $spec }}</span>
                            @endforeach
                        </div>
                    </li>
                    @endif

                    {{-- Facilities --}}
                    @if(!empty($facilities))
                    <li class="list-group-item px-3 py-2">
                        <span class="text-muted d-block mb-2">
                            <i class="fas fa-building me-2"></i>Facilities
                        </span>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($facilities as $fac)
                                <span class="badge bg-light text-success border"
                                      style="font-size:.68rem;">{{ $fac }}</span>
                            @endforeach
                        </div>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
