{{-- resources/views/medical_centre/dashboard.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Dashboard')
@section('page-icon', 'th-large')
@section('page-title', 'Dashboard')

@section('page-actions')
    <a href="{{ route('medical_centre.appointments') }}" class="mc-btn-sm primary">
        <i class="fas fa-calendar-check"></i> View Appointments
    </a>
@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════
   WELCOME BANNER
══════════════════════════════════════════ */
.mc-welcome-banner {
    background: linear-gradient(135deg, var(--mc-primary) 0%, var(--mc-secondary) 100%);
    border-radius: var(--radius);
    padding: 1.4rem 1.6rem;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}
.mc-welcome-banner::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 160px; height: 160px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
}
.mc-welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -50px; right: 80px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
}
.mc-welcome-avatar {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #fff; flex-shrink: 0;
    overflow: hidden;
}
.mc-welcome-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mc-welcome-text h4 { font-size: 1.05rem; font-weight: 800; color: #fff; margin: 0 0 .2rem; }
.mc-welcome-text p  { font-size: .78rem; color: rgba(255,255,255,.8); margin: 0; }
.mc-welcome-badge {
    margin-left: auto; z-index: 1;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 10px;
    padding: .45rem .9rem;
    font-size: .72rem; font-weight: 700; color: #fff;
    display: flex; align-items: center; gap: .4rem;
    white-space: nowrap;
}

/* ══════════════════════════════════════════
   PENDING NOTICE
══════════════════════════════════════════ */
.mc-pending-notice {
    background: #fff3cd;
    border: 1.5px solid #ffc107;
    border-radius: var(--radius);
    padding: .85rem 1.1rem;
    margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: .7rem;
    font-size: .82rem; font-weight: 600; color: #664d03;
}

/* ══════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════ */
.mc-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .85rem;
    margin-bottom: 1.2rem;
}
.mc-stat-card {
    background: #fff;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    padding: 1rem 1.1rem;
    display: flex; align-items: center; gap: .85rem;
    position: relative; overflow: hidden;
    transition: var(--transition);
}
.mc-stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.mc-stat-card::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px; border-radius: 0 0 var(--radius) var(--radius);
}
.mc-stat-card.c1::after { background: linear-gradient(90deg,#16a085,#2ecc71); }
.mc-stat-card.c2::after { background: linear-gradient(90deg,#2969bf,#1abc9c); }
.mc-stat-card.c3::after { background: linear-gradient(90deg,#f39c12,#e67e22); }
.mc-stat-card.c4::after { background: linear-gradient(90deg,#27ae60,#1abc9c); }
.mc-stat-card.c5::after { background: linear-gradient(90deg,#8e44ad,#3498db); }
.mc-stat-card.c6::after { background: linear-gradient(90deg,#e74c3c,#e67e22); }
.mc-stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.mc-stat-val { font-size: 1.55rem; font-weight: 800; color: var(--text-dark); line-height: 1.1; }
.mc-stat-val.sm { font-size: 1.1rem; }
.mc-stat-lbl { font-size: .7rem; color: var(--text-muted); font-weight: 500; margin-top: .1rem; }

/* ══════════════════════════════════════════
   CONTENT GRID
══════════════════════════════════════════ */
.mc-dash-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1rem;
    margin-bottom: 1rem;
}
.mc-dash-grid-bottom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* ══════════════════════════════════════════
   CARDS
══════════════════════════════════════════ */
.mc-card {
    background: #fff;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.mc-card-head {
    padding: .85rem 1.1rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .6rem;
}
.mc-card-head h6 { font-size: .88rem; font-weight: 700; margin: 0; flex: 1; color: var(--text-dark); }
.mc-card-head .mc-btn-sm { flex-shrink: 0; }
.mc-card-body { padding: 1rem 1.1rem; }

/* ══════════════════════════════════════════
   APPOINTMENT STATUS BREAKDOWN
══════════════════════════════════════════ */
.mc-apt-breakdown { display: flex; flex-direction: column; gap: .5rem; padding: .9rem 1.1rem; }
.mc-breakdown-row { display: flex; align-items: center; gap: .65rem; }
.mc-breakdown-label { font-size: .75rem; font-weight: 600; color: var(--text-dark); width: 80px; flex-shrink: 0; }
.mc-breakdown-bar-wrap { flex: 1; height: 7px; background: #f0f4f8; border-radius: 99px; overflow: hidden; }
.mc-breakdown-bar { height: 100%; border-radius: 99px; transition: width .6s ease; }
.mc-breakdown-count { font-size: .72rem; font-weight: 700; color: var(--text-muted); width: 28px; text-align: right; }

/* ══════════════════════════════════════════
   CHART
══════════════════════════════════════════ */
.mc-chart-wrap { padding: .9rem 1.1rem 1rem; }
.mc-chart-bars {
    display: flex; align-items: flex-end; gap: .5rem;
    height: 120px; padding-bottom: 1.8rem;
    position: relative;
}
.mc-chart-bars::after {
    content: '';
    position: absolute; bottom: 1.8rem; left: 0; right: 0;
    height: 1px; background: var(--border);
}
.mc-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: .3rem; }
.mc-bar {
    width: 100%; border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, var(--mc-primary), var(--mc-secondary));
    min-height: 4px; transition: height .4s ease;
    position: relative;
}
.mc-bar:hover::before {
    content: attr(data-count);
    position: absolute; top: -22px; left: 50%;
    transform: translateX(-50%);
    background: var(--mc-primary); color: #fff;
    font-size: .65rem; font-weight: 700;
    padding: .15rem .4rem; border-radius: 5px;
    white-space: nowrap;
}
.mc-bar-day { font-size: .65rem; color: var(--text-muted); font-weight: 600; }

/* ══════════════════════════════════════════
   TODAY TABLE
══════════════════════════════════════════ */
.mc-table-wrap { overflow-x: auto; }
.mc-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
.mc-table th {
    padding: .55rem .85rem;
    font-size: .67rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .05em; color: var(--text-muted);
    border-bottom: 1.5px solid var(--border);
    white-space: nowrap; background: #fafcff;
    text-align: left;
}
.mc-table td {
    padding: .6rem .85rem;
    border-bottom: 1px solid #f5f7fa;
    color: var(--text-dark); vertical-align: middle;
}
.mc-table tr:last-child td { border-bottom: none; }
.mc-table tbody tr:hover { background: #f8fbff; }

/* ══════════════════════════════════════════
   BADGES
══════════════════════════════════════════ */
.mc-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .67rem; font-weight: 700;
    padding: .18rem .5rem; border-radius: 99px;
    white-space: nowrap;
}
.mc-badge.pending   { background: #fff3cd; color: #664d03; }
.mc-badge.confirmed { background: #cfe2ff; color: #084298; }
.mc-badge.completed { background: #d1e7dd; color: #0a3622; }
.mc-badge.cancelled { background: #f8d7da; color: #58151c; }
.mc-badge.noshow    { background: #e2e3e5; color: #383d41; }
.mc-badge.approved  { background: #d1e7dd; color: #0a3622; }
.mc-badge.pending-d { background: #fff3cd; color: #664d03; }
.mc-pay-badge {
    font-size: .65rem; font-weight: 700;
    padding: .15rem .45rem; border-radius: 5px;
    display: inline-block;
}
.mc-pay-badge.paid    { background: #d1e7dd; color: #0a3622; }
.mc-pay-badge.unpaid  { background: #f8d7da; color: #58151c; }
.mc-pay-badge.partial { background: #fff3cd; color: #664d03; }

/* ══════════════════════════════════════════
   DOCTORS GRID
══════════════════════════════════════════ */
.mc-doctors-list { display: flex; flex-direction: column; gap: 0; }
.mc-doctor-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .75rem 1.1rem;
    border-bottom: 1px solid #f5f7fa;
    transition: var(--transition);
}
.mc-doctor-row:last-child { border-bottom: none; }
.mc-doctor-row:hover { background: #f8fbff; }
.mc-doctor-avatar {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--mc-primary), var(--mc-secondary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .82rem; font-weight: 700;
    flex-shrink: 0; overflow: hidden;
}
.mc-doctor-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mc-doctor-info h6 { font-size: .82rem; font-weight: 700; margin: 0 0 .1rem; color: var(--text-dark); }
.mc-doctor-info p  { font-size: .7rem; color: var(--text-muted); margin: 0; }

/* ══════════════════════════════════════════
   RECENT ACTIVITY
══════════════════════════════════════════ */
.mc-activity-list { display: flex; flex-direction: column; gap: 0; }
.mc-activity-item {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .75rem 1.1rem;
    border-bottom: 1px solid #f5f7fa;
    transition: var(--transition);
}
.mc-activity-item:last-child { border-bottom: none; }
.mc-activity-item:hover { background: #f8fbff; }
.mc-activity-dot {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; flex-shrink: 0; margin-top: .1rem;
}
.mc-activity-info p { font-size: .8rem; font-weight: 600; color: var(--text-dark); margin: 0 0 .18rem; }
.mc-activity-info span { font-size: .7rem; color: var(--text-muted); }

/* ══════════════════════════════════════════
   RATING STARS
══════════════════════════════════════════ */
.mc-stars { color: #f39c12; font-size: .72rem; letter-spacing: .05em; }

/* ══════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════ */
.mc-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 2rem 1rem; gap: .5rem;
    color: var(--text-muted); font-size: .8rem; text-align: center;
}
.mc-empty i { font-size: 2rem; opacity: .3; }

/* ══════════════════════════════════════════
   QUICK LINKS
══════════════════════════════════════════ */
.mc-quick-links {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .5rem;
    padding: .9rem 1.1rem;
}
.mc-quick-link {
    display: flex; align-items: center; gap: .6rem;
    padding: .7rem .9rem;
    background: #f8fbff; border-radius: 10px;
    border: 1px solid var(--border);
    text-decoration: none;
    transition: var(--transition);
}
.mc-quick-link:hover { background: var(--mc-primary-light); border-color: var(--mc-primary); }
.mc-quick-link .ql-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; flex-shrink: 0;
}
.mc-quick-link .ql-text { font-size: .75rem; font-weight: 600; color: var(--text-dark); }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media(max-width:1199.98px) {
    .mc-stats-grid { grid-template-columns: repeat(3,1fr); }
    .mc-dash-grid  { grid-template-columns: 1fr; }
}
@media(max-width:991.98px) {
    .mc-stats-grid { grid-template-columns: repeat(2,1fr); }
    .mc-dash-grid-bottom { grid-template-columns: 1fr; }
}
@media(max-width:575.98px) {
    .mc-stats-grid { grid-template-columns: 1fr 1fr; gap: .6rem; }
    .mc-welcome-badge { display: none; }
    .mc-quick-links { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ══ FLASH MESSAGES ══ --}}
@if(session('success'))
<div style="background:#d1e7dd;color:#0a3622;border:1px solid #a3cfbb;border-radius:10px;
            padding:.75rem 1rem;margin-bottom:1rem;font-size:.82rem;font-weight:600;
            display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#f8d7da;color:#58151c;border:1px solid #f5c6cb;border-radius:10px;
            padding:.75rem 1rem;margin-bottom:1rem;font-size:.82rem;font-weight:600;
            display:flex;align-items:center;gap:.5rem;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

{{-- ══ PENDING NOTICE ══ --}}
@if($medicalCentre->status === 'pending')
<div class="mc-pending-notice">
    <i class="fas fa-clock" style="font-size:1.1rem;"></i>
    <div>
        <strong>Registration Pending Review</strong> —
        Your medical centre is awaiting admin approval. You'll be notified once verified.
    </div>
</div>
@elseif($medicalCentre->status === 'suspended')
<div style="background:#f8d7da;border:1.5px solid #e74c3c;border-radius:var(--radius);
            padding:.85rem 1.1rem;margin-bottom:1.2rem;display:flex;align-items:center;
            gap:.7rem;font-size:.82rem;font-weight:600;color:#58151c;">
    <i class="fas fa-ban" style="font-size:1.1rem;"></i>
    <div>
        <strong>Account Suspended</strong> —
        Your medical centre account has been suspended. Please contact support.
    </div>
</div>
@endif

{{-- ══ WELCOME BANNER ══ --}}
<div class="mc-welcome-banner">
    <div class="mc-welcome-avatar">
        @if($medicalCentre->profile_image)
            <img src="{{ asset('storage/'.$medicalCentre->profile_image) }}" alt="{{ $medicalCentre->name }}">
        @else
            <i class="fas fa-clinic-medical"></i>
        @endif
    </div>
    <div class="mc-welcome-text">
        <h4>{{ $medicalCentre->name }}</h4>
        <p>
            <i class="fas fa-map-marker-alt" style="margin-right:.3rem;"></i>
            {{ $medicalCentre->city ?? 'Sri Lanka' }}
            @if($medicalCentre->operating_hours)
                &nbsp;·&nbsp;
                <i class="fas fa-clock" style="margin-right:.3rem;"></i>
                {{ $medicalCentre->operating_hours }}
            @endif
        </p>
    </div>
    <div class="mc-welcome-badge">
        @php
            $statusColors = ['approved'=>'#27ae60','pending'=>'#e67e22','suspended'=>'#e74c3c','rejected'=>'#e74c3c'];
        @endphp
        <span style="width:7px;height:7px;border-radius:50%;background:{{ $statusColors[$medicalCentre->status] ?? '#aaa' }};display:inline-block;"></span>
        {{ ucfirst($medicalCentre->status) }}
    </div>
</div>

{{-- ══ STAT CARDS ══ --}}
<div class="mc-stats-grid">
    {{-- Today's Appointments --}}
    <div class="mc-stat-card c1">
        <div class="mc-stat-icon" style="background:#e8f8f5;color:#16a085;">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div>
            <div class="mc-stat-val">{{ $stats['today_appointments'] }}</div>
            <div class="mc-stat-lbl">Today's Appointments</div>
        </div>
    </div>

    {{-- Total Appointments --}}
    <div class="mc-stat-card c2">
        <div class="mc-stat-icon" style="background:#e8f0fe;color:#2969bf;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div>
            <div class="mc-stat-val">{{ $stats['total_appointments'] }}</div>
            <div class="mc-stat-lbl">Total Appointments</div>
        </div>
    </div>

    {{-- Active Doctors --}}
    <div class="mc-stat-card c3">
        <div class="mc-stat-icon" style="background:#fef8e7;color:#e67e22;">
            <i class="fas fa-user-md"></i>
        </div>
        <div>
            <div class="mc-stat-val">{{ $stats['active_doctors'] }}</div>
            <div class="mc-stat-lbl">
                Active Doctors
                @if($stats['pending_doctors'] > 0)
                    <span style="color:#e74c3c;font-weight:700;">({{ $stats['pending_doctors'] }} pending)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Monthly Revenue --}}
    <div class="mc-stat-card c4">
        <div class="mc-stat-icon" style="background:#d1e7dd;color:#27ae60;">
            <i class="fas fa-coins"></i>
        </div>
        <div>
            <div class="mc-stat-val sm">LKR {{ number_format($stats['monthly_revenue'], 2) }}</div>
            <div class="mc-stat-lbl">Monthly Revenue</div>
        </div>
    </div>

    {{-- Total Patients --}}
    <div class="mc-stat-card c5">
        <div class="mc-stat-icon" style="background:#f0e8fe;color:#8e44ad;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="mc-stat-val">{{ $stats['total_patients'] }}</div>
            <div class="mc-stat-lbl">Unique Patients</div>
        </div>
    </div>

    {{-- Rating --}}
    <div class="mc-stat-card c6">
        <div class="mc-stat-icon" style="background:#fef8e7;color:#f39c12;">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <div class="mc-stat-val">{{ $stats['avg_rating'] }}</div>
            <div class="mc-stat-lbl">
                Average Rating
                <div class="mc-stars" style="margin-top:.1rem;">
                    @for($i=1;$i<=5;$i++)
                        <i class="fa{{ $i <= round($stats['avg_rating']) ? 's' : 'r' }} fa-star"></i>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MAIN GRID ══ --}}
<div class="mc-dash-grid">

    {{-- LEFT — Today's Appointments Table --}}
    <div class="mc-card">
        <div class="mc-card-head">
            <div style="width:32px;height:32px;border-radius:9px;background:#e8f8f5;
                        color:#16a085;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h6>Today's Appointments</h6>
            <a href="{{ route('medical_centre.appointments') }}" class="mc-btn-sm" style="font-size:.7rem;padding:.28rem .65rem;">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($todayAppointments->isEmpty())
            <div class="mc-empty">
                <i class="fas fa-calendar-times"></i>
                No appointments scheduled for today
            </div>
        @else
            <div class="mc-table-wrap">
                <table class="mc-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Fee</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayAppointments as $apt)
                        <tr>
                            <td style="font-weight:700;white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($apt->appointment_time)->format('g:i A') }}
                            </td>
                            <td>
                                <div style="font-weight:700;font-size:.8rem;">{{ $apt->patient_name }}</div>
                                <div style="font-size:.69rem;color:var(--text-muted);">{{ $apt->patient_phone ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:.78rem;">Dr. {{ $apt->doctor_name }}</div>
                                <div style="font-size:.69rem;color:var(--text-muted);">{{ $apt->specialization ?? '' }}</div>
                            </td>
                            <td style="font-weight:700;font-size:.78rem;white-space:nowrap;">
                                {{ $apt->consultation_fee ? 'LKR '.number_format($apt->consultation_fee,2) : '–' }}
                            </td>
                            <td>
                                <span class="mc-pay-badge {{ $apt->payment_status ?? 'unpaid' }}">
                                    {{ ucfirst($apt->payment_status ?? 'unpaid') }}
                                </span>
                            </td>
                            <td>
                                <span class="mc-badge {{ $apt->status }}">
                                    {{ ucfirst($apt->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Appointment Status Breakdown --}}
        <div class="mc-card">
            <div class="mc-card-head">
                <div style="width:32px;height:32px;border-radius:9px;background:#cfe2ff;
                            color:#084298;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h6>Appointment Breakdown</h6>
            </div>
            @php
                $totalApts = array_sum($appointmentStats);
            @endphp
            <div class="mc-apt-breakdown">
                @php
                    $breakdown = [
                        'pending'   => ['color'=>'#e67e22','label'=>'Pending'],
                        'confirmed' => ['color'=>'#2969bf','label'=>'Confirmed'],
                        'completed' => ['color'=>'#27ae60','label'=>'Completed'],
                        'cancelled' => ['color'=>'#e74c3c','label'=>'Cancelled'],
                    ];
                @endphp
                @foreach($breakdown as $key => $info)
                @php $cnt = $appointmentStats[$key] ?? 0; $pct = $totalApts > 0 ? round($cnt/$totalApts*100) : 0; @endphp
                <div class="mc-breakdown-row">
                    <span class="mc-breakdown-label">{{ $info['label'] }}</span>
                    <div class="mc-breakdown-bar-wrap">
                        <div class="mc-breakdown-bar"
                             style="width:{{ $pct }}%;background:{{ $info['color'] }};"></div>
                    </div>
                    <span class="mc-breakdown-count">{{ $cnt }}</span>
                </div>
                @endforeach
                <div style="font-size:.7rem;color:var(--text-muted);text-align:right;margin-top:.2rem;">
                    Total: {{ $totalApts }}
                </div>
            </div>
        </div>

        {{-- Weekly Chart --}}
        <div class="mc-card">
            <div class="mc-card-head">
                <div style="width:32px;height:32px;border-radius:9px;background:#d1e7dd;
                            color:#27ae60;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h6>Last 7 Days</h6>
            </div>
            <div class="mc-chart-wrap">
                @php $maxCount = max(array_column($weeklyData, 'count') ?: [1]); @endphp
                <div class="mc-chart-bars">
                    @foreach($weeklyData as $day)
                    @php $h = $maxCount > 0 ? round(($day['count']/$maxCount)*90) : 4; @endphp
                    <div class="mc-bar-col">
                        <div class="mc-bar" style="height:{{ max($h,4) }}px;" data-count="{{ $day['count'] }}"></div>
                        <div class="mc-bar-day">{{ $day['date'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="mc-card">
            <div class="mc-card-head">
                <div style="width:32px;height:32px;border-radius:9px;background:#fef8e7;
                            color:#e67e22;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h6>Quick Access</h6>
            </div>
            <div class="mc-quick-links">
                <a href="{{ route('medical_centre.appointments') }}" class="mc-quick-link">
                    <div class="ql-icon" style="background:#e8f8f5;color:#16a085;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="ql-text">Appointments</span>
                </a>
                <a href="{{ route('medical_centre.doctors') }}" class="mc-quick-link">
                    <div class="ql-icon" style="background:#fef8e7;color:#e67e22;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <span class="ql-text">Doctors</span>
                </a>
                <a href="{{ route('medical_centre.notifications') }}" class="mc-quick-link">
                    <div class="ql-icon" style="background:#e8f0fe;color:#2969bf;">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span class="ql-text">
                        Notifications
                        @if($stats['unread_notifications'] > 0)
                            <span style="background:#e74c3c;color:#fff;border-radius:99px;
                                         padding:.05rem .35rem;font-size:.62rem;font-weight:800;
                                         margin-left:.25rem;">
                                {{ $stats['unread_notifications'] }}
                            </span>
                        @endif
                    </span>
                </a>
                <a href="{{ route('medical_centre.settings') }}" class="mc-quick-link">
                    <div class="ql-icon" style="background:#f0e8fe;color:#8e44ad;">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span class="ql-text">Settings</span>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ══ BOTTOM GRID ══ --}}
<div class="mc-dash-grid-bottom">

    {{-- Doctors List --}}
    <div class="mc-card">
        <div class="mc-card-head">
            <div style="width:32px;height:32px;border-radius:9px;background:#fef8e7;
                        color:#e67e22;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                <i class="fas fa-user-md"></i>
            </div>
            <h6>Doctors</h6>
            <a href="{{ route('medical_centre.doctors') }}" class="mc-btn-sm" style="font-size:.7rem;padding:.28rem .65rem;">
                Manage <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($doctors->isEmpty())
            <div class="mc-empty">
                <i class="fas fa-user-md"></i>
                No doctors associated yet
            </div>
        @else
            <div class="mc-doctors-list">
                @foreach($doctors->take(6) as $doc)
                <div class="mc-doctor-row">
                    <div class="mc-doctor-avatar">
                        @if($doc->profile_image)
                            <img src="{{ asset('storage/'.$doc->profile_image) }}" alt="{{ $doc->name }}">
                        @else
                            {{ strtoupper(substr($doc->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="mc-doctor-info" style="flex:1;">
                        <h6>Dr. {{ $doc->name }}</h6>
                        <p>{{ $doc->specialization ?? 'General' }}
                            @if($doc->employment_type)
                                · {{ ucfirst($doc->employment_type) }}
                            @endif
                        </p>
                    </div>
                    <span class="mc-badge {{ $doc->workplace_status === 'approved' ? 'approved' : 'pending-d' }}">
                        {{ ucfirst($doc->workplace_status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @if($doctors->count() > 6)
            <div style="padding:.6rem 1.1rem;text-align:center;border-top:1px solid var(--border);">
                <a href="{{ route('medical_centre.doctors') }}" style="font-size:.75rem;color:var(--mc-primary);font-weight:600;text-decoration:none;">
                    View all {{ $doctors->count() }} doctors <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endif
        @endif
    </div>

    {{-- Recent Activity --}}
    <div class="mc-card">
        <div class="mc-card-head">
            <div style="width:32px;height:32px;border-radius:9px;background:#f0e8fe;
                        color:#8e44ad;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
                <i class="fas fa-history"></i>
            </div>
            <h6>Recent Activity</h6>
        </div>

        @php
            $allActivity = collect();
            foreach($recentAppointments as $apt) {
                $allActivity->push([
                    'type'    => 'appointment',
                    'label'   => $apt->patient_name,
                    'sub'     => 'Appointment · ' . \Carbon\Carbon::parse($apt->appointment_date)->format('M j'),
                    'status'  => $apt->status,
                    'icon'    => 'fa-calendar-check',
                    'bg'      => '#e8f8f5', 'color' => '#16a085',
                    'created' => $apt->appointment_date,
                ]);
            }
            foreach($recentRatings as $rat) {
                $allActivity->push([
                    'type'    => 'rating',
                    'label'   => $rat->patient_name,
                    'sub'     => 'Rating · ' . number_format($rat->rating, 1) . ' ★',
                    'status'  => null,
                    'icon'    => 'fa-star',
                    'bg'      => '#fef8e7', 'color' => '#f39c12',
                    'created' => $rat->created_at,
                ]);
            }
        @endphp

        @if($allActivity->isEmpty())
            <div class="mc-empty">
                <i class="fas fa-history"></i>
                No recent activity
            </div>
        @else
            <div class="mc-activity-list">
                @foreach($allActivity->sortByDesc('created')->take(7) as $item)
                <div class="mc-activity-item">
                    <div class="mc-activity-dot"
                         style="background:{{ $item['bg'] }};color:{{ $item['color'] }};">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </div>
                    <div class="mc-activity-info" style="flex:1;">
                        <p>{{ $item['label'] }}</p>
                        <span>{{ $item['sub'] }}</span>
                    </div>
                    @if($item['status'])
                        <span class="mc-badge {{ $item['status'] }}">{{ ucfirst($item['status']) }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
// Bar hover tooltips ── already handled via CSS attr(data-count)
// Animate bars on load
document.addEventListener('DOMContentLoaded', function () {
    const bars = document.querySelectorAll('.mc-bar');
    bars.forEach(bar => {
        const h = bar.style.height;
        bar.style.height = '0px';
        setTimeout(() => { bar.style.height = h; }, 150);
    });
});
</script>
@endpush
