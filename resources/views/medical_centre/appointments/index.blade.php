{{-- resources/views/medical_centre/appointments/index.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Appointments')
@section('page-icon', 'calendar-check')
@section('page-title', 'Appointments')

@section('page-actions')
    <a href="{{ route('medical_centre.appointments.export', request()->only(['status','date_from','date_to'])) }}"
       class="mc-btn-sm">
        <i class="fas fa-file-csv"></i> Export CSV
    </a>
@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════ */
.mc-btn-sm {
    padding:.38rem .85rem; border-radius:8px;
    font-size:.78rem; font-weight:600;
    border:1.5px solid var(--border);
    background:#fff; color:var(--text-dark);
    cursor:pointer; font-family:inherit;
    display:inline-flex; align-items:center; gap:.4rem;
    transition:var(--transition); text-decoration:none;
}
.mc-btn-sm:hover { background:var(--mc-primary-light); border-color:var(--mc-primary); color:var(--mc-primary); }
.mc-btn-sm.primary { background:var(--mc-primary); color:#fff; border-color:var(--mc-primary); }
.mc-btn-sm.primary:hover { background:var(--mc-primary-dark); }
.mc-btn-sm.danger  { background:#e74c3c; color:#fff; border-color:#e74c3c; }
.mc-btn-sm.danger:hover  { background:#c0392b; }
.mc-btn-sm.warning { background:#f39c12; color:#fff; border-color:#f39c12; }
.mc-btn-sm.success { background:#27ae60; color:#fff; border-color:#27ae60; }

/* ══════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════ */
.apt-stats-grid {
    display:grid; grid-template-columns:repeat(5,1fr);
    gap:.85rem; margin-bottom:1.2rem;
}
.apt-stat {
    background:#fff; border-radius:var(--radius);
    border:1px solid var(--border); box-shadow:var(--shadow-sm);
    padding:.9rem 1rem; display:flex; align-items:center; gap:.75rem;
    transition:var(--transition); position:relative; overflow:hidden;
}
.apt-stat:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
.apt-stat::after {
    content:''; position:absolute; bottom:0; left:0; right:0;
    height:3px; border-radius:0 0 var(--radius) var(--radius);
}
.apt-stat.s1::after { background:linear-gradient(90deg,#16a085,#2ecc71); }
.apt-stat.s2::after { background:linear-gradient(90deg,#2969bf,#1abc9c); }
.apt-stat.s3::after { background:linear-gradient(90deg,#f39c12,#e67e22); }
.apt-stat.s4::after { background:linear-gradient(90deg,#27ae60,#1abc9c); }
.apt-stat.s5::after { background:linear-gradient(90deg,#e74c3c,#8e44ad); }
.apt-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.apt-stat-val  { font-size:1.45rem; font-weight:800; color:var(--text-dark); line-height:1.1; }
.apt-stat-lbl  { font-size:.7rem; color:var(--text-muted); font-weight:500; margin-top:.1rem; }

/* ══════════════════════════════════════════
   STATUS TABS
══════════════════════════════════════════ */
.apt-status-tabs { display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:1rem; }
.apt-tab {
    padding:.35rem .85rem; border-radius:8px;
    font-size:.75rem; font-weight:700;
    border:1.5px solid var(--border);
    background:#fff; color:var(--text-muted);
    cursor:pointer; transition:var(--transition);
    display:inline-flex; align-items:center; gap:.35rem;
    text-decoration:none;
}
.apt-tab:hover { border-color:var(--mc-primary); color:var(--mc-primary); background:var(--mc-primary-light); }
.apt-tab.active { background:var(--mc-primary); color:#fff; border-color:var(--mc-primary); }
.apt-tab .tab-count {
    background:rgba(255,255,255,.25); border-radius:99px;
    padding:.05rem .4rem; font-size:.65rem; font-weight:800;
    min-width:18px; text-align:center;
}
.apt-tab:not(.active) .tab-count { background:#f0f4f8; color:var(--text-dark); }

/* ══════════════════════════════════════════
   FILTER BAR
══════════════════════════════════════════ */
.apt-filter-bar {
    background:#fff; border-radius:var(--radius);
    border:1px solid var(--border); box-shadow:var(--shadow-sm);
    padding:.85rem 1.1rem; margin-bottom:1rem;
    display:flex; flex-wrap:wrap; gap:.65rem; align-items:flex-end;
}
.apt-filter-bar .fld { display:flex; flex-direction:column; gap:.25rem; min-width:130px; flex:1; }
.apt-filter-bar .fld label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:var(--text-muted); }
.apt-filter-bar input,
.apt-filter-bar select {
    height:34px; border:1.5px solid var(--border); border-radius:8px;
    padding:0 .65rem; font-size:.78rem; font-family:inherit;
    color:var(--text-dark); background:#fff; outline:none;
    transition:var(--transition); width:100%;
}
.apt-filter-bar input:focus,
.apt-filter-bar select:focus { border-color:var(--mc-primary); box-shadow:0 0 0 3px rgba(22,160,133,.12); }
.apt-search-wrap { position:relative; flex:2; min-width:200px; }
.apt-search-wrap input { padding-left:2.1rem; }
.apt-search-wrap i { position:absolute; left:.7rem; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.8rem; pointer-events:none; }

/* ══════════════════════════════════════════
   TABLE CARD
══════════════════════════════════════════ */
.mc-card { background:#fff; border-radius:var(--radius); border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; }
.mc-card-head { padding:.85rem 1.1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:.6rem; }
.mc-card-head h6 { font-size:.88rem; font-weight:700; margin:0; flex:1; color:var(--text-dark); }
.mc-table-wrap { overflow-x:auto; }
.mc-table { width:100%; border-collapse:collapse; font-size:.8rem; }
.mc-table th {
    padding:.6rem .85rem; text-align:left;
    font-size:.68rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.05em; color:var(--text-muted);
    border-bottom:1.5px solid var(--border);
    white-space:nowrap; background:#fafcff;
}
.mc-table td { padding:.65rem .85rem; border-bottom:1px solid #f5f7fa; color:var(--text-dark); vertical-align:middle; }
.mc-table tr:last-child td { border-bottom:none; }
.mc-table tbody tr:hover { background:#f8fbff; }

/* ══════════════════════════════════════════
   BADGES
══════════════════════════════════════════ */
.apt-badge { display:inline-flex; align-items:center; gap:.28rem; font-size:.68rem; font-weight:700; padding:.2rem .55rem; border-radius:99px; white-space:nowrap; }
.apt-badge.pending   { background:#fff3cd; color:#664d03; }
.apt-badge.confirmed { background:#cfe2ff; color:#084298; }
.apt-badge.completed { background:#d1e7dd; color:#0a3622; }
.apt-badge.cancelled { background:#f8d7da; color:#58151c; }
.apt-badge.noshow    { background:#e2e3e5; color:#383d41; }
.pay-badge { display:inline-flex; align-items:center; gap:.25rem; font-size:.68rem; font-weight:700; padding:.18rem .5rem; border-radius:6px; }
.pay-badge.unpaid  { background:#f8d7da; color:#58151c; }
.pay-badge.partial { background:#fff3cd; color:#664d03; }
.pay-badge.paid    { background:#d1e7dd; color:#0a3622; }

/* ══════════════════════════════════════════
   ACTION BUTTONS
══════════════════════════════════════════ */
.apt-actions { display:flex; gap:.3rem; flex-wrap:wrap; }
.apt-act-btn {
    width:28px; height:28px; border-radius:7px; border:none;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; cursor:pointer; transition:var(--transition);
}
.apt-act-btn.view     { background:#e8f8f5; color:#16a085; }
.apt-act-btn.confirm  { background:#cfe2ff; color:#084298; }
.apt-act-btn.complete { background:#d1e7dd; color:#0a3622; }
.apt-act-btn.cancel   { background:#f8d7da; color:#58151c; }
.apt-act-btn.noshow   { background:#e2e3e5; color:#383d41; }
.apt-act-btn.payment  { background:#fef8e7; color:#e67e22; }
.apt-act-btn:hover    { filter:brightness(.9); transform:scale(1.08); }

/* ══════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════ */
.mc-pagination { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-top:1px solid var(--border); flex-wrap:wrap; gap:.5rem; }
.mc-pagination-info { font-size:.75rem; color:var(--text-muted); font-weight:500; }

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.mc-modal-overlay {
    position:fixed; inset:0; background:rgba(0,0,0,.5);
    z-index:2000; display:none; align-items:center;
    justify-content:center; padding:1rem; backdrop-filter:blur(3px);
}
.mc-modal-overlay.show { display:flex; }
.mc-modal {
    background:#fff; border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    width:100%; max-width:680px; max-height:90vh; overflow-y:auto;
    animation:modalIn .25s ease;
}
.mc-modal.sm { max-width:440px; }
@keyframes modalIn { from{opacity:0;transform:translateY(-20px) scale(.97)} to{opacity:1;transform:none} }
.mc-modal-head {
    padding:1.1rem 1.3rem; border-bottom:1px solid var(--border);
    display:flex; align-items:center; gap:.75rem;
    position:sticky; top:0; background:#fff; z-index:1;
}
.mc-modal-head h5 { font-size:.95rem; font-weight:800; margin:0; flex:1; }
.mc-modal-close {
    width:32px; height:32px; border-radius:8px; border:none;
    background:#f4f7fb; cursor:pointer; display:flex;
    align-items:center; justify-content:center;
    color:var(--text-muted); font-size:.85rem; transition:var(--transition);
}
.mc-modal-close:hover { background:#f8d7da; color:#e74c3c; }
.mc-modal-body { padding:1.3rem; }
.mc-modal-foot { padding:.9rem 1.3rem; border-top:1px solid var(--border); display:flex; gap:.5rem; justify-content:flex-end; flex-wrap:wrap; }

/* Detail */
.apt-detail-section { margin-bottom:1.1rem; }
.apt-detail-section h6 {
    font-size:.72rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.06em; color:var(--mc-primary);
    margin:0 0 .65rem; padding-bottom:.4rem;
    border-bottom:1.5px solid var(--mc-primary-light);
    display:flex; align-items:center; gap:.4rem;
}
.apt-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:.55rem; }
.apt-detail-grid.col3 { grid-template-columns:1fr 1fr 1fr; }
.apt-detail-item label { display:block; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:var(--text-muted); margin-bottom:.18rem; }
.apt-detail-item span  { font-size:.8rem; font-weight:600; color:var(--text-dark); display:block; }
.apt-person-card { display:flex; align-items:center; gap:.75rem; background:#f8fbff; border-radius:10px; padding:.75rem; border:1px solid var(--border); margin-bottom:.75rem; }
.apt-person-avatar { width:44px; height:44px; border-radius:11px; flex-shrink:0; background:linear-gradient(135deg,var(--mc-primary),var(--mc-secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:.9rem; font-weight:700; overflow:hidden; }
.apt-person-avatar img { width:100%; height:100%; object-fit:cover; }
.apt-person-info h6 { font-size:.85rem; font-weight:700; margin:0 0 .1rem; }
.apt-person-info p  { font-size:.73rem; color:var(--text-muted); margin:0; }

/* Pay option */
.pay-option { display:flex; align-items:center; gap:.65rem; padding:.65rem .85rem; border-radius:10px; border:2px solid var(--border); cursor:pointer; transition:var(--transition); margin-bottom:.45rem; }
.pay-option:hover, .pay-option.selected { border-color:var(--mc-primary); background:var(--mc-primary-light); }
.pay-option input[type=radio] { accent-color:var(--mc-primary); }
.pay-option label { font-size:.82rem; font-weight:600; cursor:pointer; margin:0; }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media(max-width:1199.98px){ .apt-stats-grid{grid-template-columns:repeat(3,1fr);} }
@media(max-width:991.98px) { .apt-stats-grid{grid-template-columns:repeat(2,1fr);} }
@media(max-width:767.98px) { .apt-filter-bar .fld{min-width:calc(50% - .35rem);} .apt-detail-grid.col3{grid-template-columns:1fr 1fr;} }
@media(max-width:575.98px) { .apt-stats-grid{grid-template-columns:1fr 1fr;gap:.6rem;} .apt-filter-bar .fld{min-width:100%;} .apt-detail-grid{grid-template-columns:1fr;} }
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

{{-- ══ STAT CARDS ══ --}}
<div class="apt-stats-grid">
    <div class="apt-stat s1">
        <div class="apt-stat-icon" style="background:#e8f8f5;color:#16a085;"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="apt-stat-val">{{ $stats['today'] }}</div>
            <div class="apt-stat-lbl">Today</div>
        </div>
    </div>
    <div class="apt-stat s2">
        <div class="apt-stat-icon" style="background:#e8f0fe;color:#2969bf;"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="apt-stat-val">{{ $stats['this_month'] }}</div>
            <div class="apt-stat-lbl">This Month</div>
        </div>
    </div>
    <div class="apt-stat s3">
        <div class="apt-stat-icon" style="background:#fff3cd;color:#e67e22;"><i class="fas fa-clock"></i></div>
        <div>
            <div class="apt-stat-val">{{ $stats['pending'] }}</div>
            <div class="apt-stat-lbl">Pending</div>
        </div>
    </div>
    <div class="apt-stat s4">
        <div class="apt-stat-icon" style="background:#d1e7dd;color:#27ae60;"><i class="fas fa-coins"></i></div>
        <div>
            <div class="apt-stat-val" style="font-size:1.05rem;">LKR {{ number_format($stats['revenue'],2) }}</div>
            <div class="apt-stat-lbl">Monthly Revenue</div>
        </div>
    </div>
    <div class="apt-stat s5">
        <div class="apt-stat-icon" style="background:#f8d7da;color:#e74c3c;"><i class="fas fa-exclamation-circle"></i></div>
        <div>
            <div class="apt-stat-val">{{ $stats['unpaid'] }}</div>
            <div class="apt-stat-lbl">Unpaid</div>
        </div>
    </div>
</div>

{{-- ══ STATUS TABS ══ --}}
<div class="apt-status-tabs">
    @php
        $tabs = [
            ''          => ['icon'=>'fa-list',        'label'=>'All',       'count'=> $summary->total     ?? 0],
            'pending'   => ['icon'=>'fa-clock',       'label'=>'Pending',   'count'=> $summary->pending   ?? 0],
            'confirmed' => ['icon'=>'fa-check-circle','label'=>'Confirmed', 'count'=> $summary->confirmed ?? 0],
            'completed' => ['icon'=>'fa-check-double','label'=>'Completed', 'count'=> $summary->completed ?? 0],
            'cancelled' => ['icon'=>'fa-times-circle','label'=>'Cancelled', 'count'=> $summary->cancelled ?? 0],
            'noshow'    => ['icon'=>'fa-user-slash',  'label'=>'No-Show',   'count'=> $summary->noshow    ?? 0],
        ];
    @endphp
    @foreach($tabs as $key => $tab)
        <a href="{{ route('medical_centre.appointments', array_merge(request()->except('status','page'), ['status'=>$key])) }}"
           class="apt-tab {{ $filters['status'] === $key ? 'active' : '' }}">
            <i class="fas {{ $tab['icon'] }}"></i>
            {{ $tab['label'] }}
            <span class="tab-count">{{ $tab['count'] }}</span>
        </a>
    @endforeach
</div>

{{-- ══ FILTER BAR ══ --}}
<form method="GET" action="{{ route('medical_centre.appointments') }}" class="apt-filter-bar" id="filterForm">
    <input type="hidden" name="status" value="{{ $filters['status'] }}">
    <div class="fld apt-search-wrap" style="flex:3;min-width:220px;">
        <label>Search</label>
        <i class="fas fa-search"></i>
        <input type="text" name="search"
               value="{{ $filters['search'] }}"
               placeholder="Name, phone, appointment no…">
    </div>
    <div class="fld">
        <label>Doctor</label>
        <select name="doctor_id">
            <option value="">All Doctors</option>
            @foreach($doctors as $doc)
                <option value="{{ $doc->id }}" {{ $filters['doctorId'] == $doc->id ? 'selected' : '' }}>
                    Dr. {{ $doc->name }}{{ $doc->specialization ? ' — '.$doc->specialization : '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="fld">
        <label>Payment</label>
        <select name="payment_status">
            <option value="">All</option>
            @foreach(['unpaid'=>'Unpaid','partial'=>'Partial','paid'=>'Paid'] as $val=>$lbl)
                <option value="{{ $val }}" {{ $filters['paymentStatus'] === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
    <div class="fld">
        <label>Date From</label>
        <input type="date" name="date_from" value="{{ $filters['dateFrom'] }}">
    </div>
    <div class="fld">
        <label>Date To</label>
        <input type="date" name="date_to" value="{{ $filters['dateTo'] }}">
    </div>
    <div class="fld" style="max-width:90px;">
        <label>Per Page</label>
        <select name="per_page" onchange="this.form.submit()">
            @foreach([15,25,50] as $pp)
                <option value="{{ $pp }}" {{ $filters['perPage'] == $pp ? 'selected' : '' }}>{{ $pp }}</option>
            @endforeach
        </select>
    </div>
    <div style="display:flex;gap:.4rem;align-self:flex-end;">
        <button type="submit" class="mc-btn-sm primary">
            <i class="fas fa-search"></i> Filter
        </button>
        <a href="{{ route('medical_centre.appointments') }}" class="mc-btn-sm">
            <i class="fas fa-times"></i> Clear
        </a>
    </div>
</form>

{{-- ══ TABLE CARD ══ --}}
<div class="mc-card">
    <div class="mc-card-head">
        <div style="width:32px;height:32px;border-radius:9px;background:#e8f8f5;
                    color:#16a085;display:flex;align-items:center;justify-content:center;font-size:.78rem;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <h6>Appointments List</h6>
        <span style="font-size:.72rem;color:var(--text-muted);">
            {{ $appointments->total() }} result{{ $appointments->total() !== 1 ? 's' : '' }}
        </span>
    </div>

    @if($appointments->isEmpty())
        <div style="display:flex;flex-direction:column;align-items:center;
                    padding:3rem 1rem;gap:.6rem;color:var(--text-muted);font-size:.82rem;text-align:center;">
            <i class="fas fa-calendar-times" style="font-size:2.5rem;opacity:.3;"></i>
            No appointments found
            @if($filters['search'] || $filters['status'])
                <a href="{{ route('medical_centre.appointments') }}" class="mc-btn-sm" style="margin-top:.5rem;">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
            @endif
        </div>
    @else
        <div class="mc-table-wrap">
            <table class="mc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Appointment No</th>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Fee</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $i => $apt)
                    @php
                        $statusColors = [
                            'pending'   => ['bg'=>'#fff3cd','color'=>'#664d03','icon'=>'fa-clock'],
                            'confirmed' => ['bg'=>'#cfe2ff','color'=>'#084298','icon'=>'fa-check-circle'],
                            'completed' => ['bg'=>'#d1e7dd','color'=>'#0a3622','icon'=>'fa-check-double'],
                            'cancelled' => ['bg'=>'#f8d7da','color'=>'#58151c','icon'=>'fa-times-circle'],
                            'noshow'    => ['bg'=>'#e2e3e5','color'=>'#383d41','icon'=>'fa-user-slash'],
                        ];
                        $payColors = [
                            'unpaid'  => ['bg'=>'#f8d7da','color'=>'#58151c'],
                            'partial' => ['bg'=>'#fff3cd','color'=>'#664d03'],
                            'paid'    => ['bg'=>'#d1e7dd','color'=>'#0a3622'],
                        ];
                        $sc = $statusColors[$apt->status] ?? $statusColors['pending'];
                        $pc = $payColors[$apt->payment_status] ?? $payColors['unpaid'];
                        $canConfirm  = $apt->status === 'pending';
                        $canComplete = in_array($apt->status, ['pending','confirmed']);
                        $canCancel   = !in_array($apt->status, ['completed','cancelled']);
                        $canNoShow   = in_array($apt->status, ['pending','confirmed']);
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-size:.72rem;">{{ $appointments->firstItem() + $i }}</td>
                        <td>
                            <span style="font-size:.78rem;font-weight:700;font-family:monospace;color:var(--mc-primary);">
                                {{ $apt->appointment_number }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:700;font-size:.8rem;">
                                {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M j, Y') }}
                            </div>
                            <div style="font-size:.72rem;color:var(--text-muted);">
                                {{ \Carbon\Carbon::parse($apt->appointment_date.' '.$apt->appointment_time)->format('g:i A') }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700;font-size:.8rem;">{{ $apt->patient_name }}</div>
                            <div style="font-size:.7rem;color:var(--text-muted);">{{ $apt->patient_phone ?? '' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.8rem;">Dr. {{ $apt->doctor_name }}</div>
                            <div style="font-size:.7rem;color:var(--text-muted);">{{ $apt->specialization ?? '' }}</div>
                        </td>
                        <td style="font-weight:700;font-size:.8rem;white-space:nowrap;">
                            {{ $apt->consultation_fee ? 'LKR '.number_format($apt->consultation_fee,2) : '–' }}
                        </td>
                       {{-- Payment column --}}
                        <td>
                            @if($apt->payment_status === 'paid')
                                <span class="pay-badge paid">
                                    <i class="fas fa-check-circle" style="font-size:.6rem;"></i> Paid
                                </span>
                            @elseif($apt->payment_status === 'partial')
                                <span class="pay-badge partial">
                                    <i class="fas fa-adjust" style="font-size:.6rem;"></i> Advance Paid
                                </span>
                                @if($apt->advance_payment)
                                <div style="font-size:.67rem;color:#664d03;margin-top:.2rem;line-height:1.4;">
                                    Adv: LKR {{ number_format($apt->advance_payment, 2) }}<br>
                                    <span style="color:#58151c;">
                                        Bal: LKR {{ number_format(($apt->consultation_fee ?? 0) - $apt->advance_payment, 2) }}
                                    </span>
                                </div>
                                @endif
                            @else
                                <span class="pay-badge unpaid">
                                    <i class="fas fa-times-circle" style="font-size:.6rem;"></i> Unpaid
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="apt-badge {{ $apt->status }}">
                                <i class="fas {{ $sc['icon'] }}" style="font-size:.6rem;"></i>
                                {{ ucfirst($apt->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="apt-actions">
                                {{-- View --}}
                                <button class="apt-act-btn view" title="View Detail"
                                    onclick="openDetailModal({{ $apt->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- Confirm --}}
                                @if($canConfirm)
                                <form method="POST"
                                    action="{{ route('medical_centre.appointments.confirm', $apt->id) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Confirm this appointment?')">
                                    @csrf
                                    <button type="submit" class="apt-act-btn confirm" title="Confirm">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Complete --}}
                                @if($canComplete)
                                <form method="POST"
                                    action="{{ route('medical_centre.appointments.complete', $apt->id) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Mark as completed?')">
                                    @csrf
                                    <button type="submit" class="apt-act-btn complete" title="Complete">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Payment --}}
                                <button class="apt-act-btn payment" title="Update Payment"
                                    onclick="openPayModal(
                                        {{ $apt->id }},
                                        '{{ addslashes($apt->appointment_number) }}',
                                        {{ $apt->consultation_fee ?? 0 }},
                                        '{{ $apt->payment_status ?? 'unpaid' }}',
                                        {{ $apt->advance_payment ?? 0 }}
                                    )">
                                    <i class="fas fa-credit-card"></i>
                                </button>

                                {{-- No-Show --}}
                                @if($canNoShow)
                                <form method="POST"
                                    action="{{ route('medical_centre.appointments.noshow', $apt->id) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Mark as no-show?')">
                                    @csrf
                                    <button type="submit" class="apt-act-btn noshow" title="No-Show">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Cancel --}}
                                @if($canCancel)
                                <button class="apt-act-btn cancel" title="Cancel"
                                    onclick="openCancelModal({{ $apt->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div class="mc-pagination">
        <div class="mc-pagination-info">
            Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} of {{ $appointments->total() }}
        </div>
        <div>{{ $appointments->withQueryString()->links('vendor.pagination.simple-bootstrap-4') }}</div>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════
     DETAIL MODAL (hidden data islands)
══════════════════════════════════════════ --}}
{{-- Hidden appointment data for JS modal --}}
@foreach($appointments as $apt)
@php
    $statusIcons = ['pending'=>'fa-clock','confirmed'=>'fa-check-circle','completed'=>'fa-check-double','cancelled'=>'fa-times-circle','noshow'=>'fa-user-slash'];
@endphp
<div id="apt-data-{{ $apt->id }}" style="display:none;"
    data-id="{{ $apt->id }}"
    data-num="{{ $apt->appointment_number }}"
    data-date="{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M j, Y') }}"
    data-time="{{ \Carbon\Carbon::parse($apt->appointment_date.' '.$apt->appointment_time)->format('g:i A') }}"
    data-status="{{ $apt->status }}"
    data-status-label="{{ ucfirst($apt->status) }}"
    data-status-icon="{{ $statusIcons[$apt->status] ?? 'fa-clock' }}"
    data-payment="{{ $apt->payment_status ?? 'unpaid' }}"
    data-fee="{{ $apt->consultation_fee ? 'LKR '.number_format($apt->consultation_fee,2) : '–' }}"
    data-advance="{{ $apt->advance_payment ? 'LKR '.number_format($apt->advance_payment,2) : 'LKR 0.00' }}"
    data-patient="{{ $apt->patient_name }}"
    data-patient-phone="{{ $apt->patient_phone ?? '' }}"
    data-doctor="{{ $apt->doctor_name }}"
    data-specialization="{{ $apt->specialization ?? 'General' }}"
    data-reason="{{ $apt->reason ?? '' }}"
    data-notes="{{ $apt->notes ?? '' }}"
    data-cancel-reason="{{ $apt->cancellation_reason ?? '' }}"
    data-created="{{ \Carbon\Carbon::parse($apt->created_at)->format('M j, Y g:i A') }}"
    data-can-confirm="{{ $apt->status === 'pending' ? 1 : 0 }}"
    data-can-complete="{{ in_array($apt->status,['pending','confirmed']) ? 1 : 0 }}"
    data-can-cancel="{{ !in_array($apt->status,['completed','cancelled']) ? 1 : 0 }}"
    data-confirm-url="{{ route('medical_centre.appointments.confirm', $apt->id) }}"
    data-complete-url="{{ route('medical_centre.appointments.complete', $apt->id) }}"
    data-cancel-url="{{ route('medical_centre.appointments.cancel', $apt->id) }}"
    data-payment-url="{{ route('medical_centre.appointments.payment', $apt->id) }}"
></div>
@endforeach

{{-- DETAIL MODAL --}}
<div class="mc-modal-overlay" id="detailModal">
    <div class="mc-modal">
        <div class="mc-modal-head">
            <div style="width:34px;height:34px;border-radius:9px;background:#e8f8f5;
                        color:#16a085;display:flex;align-items:center;justify-content:center;font-size:.82rem;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h5 id="dm-num">Appointment Detail</h5>
            <button class="mc-modal-close" onclick="closeModal('detailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mc-modal-body" id="dm-body"></div>
        <div class="mc-modal-foot" id="dm-foot"></div>
    </div>
</div>

{{-- CANCEL MODAL --}}
<div class="mc-modal-overlay" id="cancelModal">
    <div class="mc-modal sm">
        <div class="mc-modal-head">
            <div style="width:34px;height:34px;border-radius:9px;background:#f8d7da;
                        color:#e74c3c;display:flex;align-items:center;justify-content:center;font-size:.82rem;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h5>Cancel Appointment</h5>
            <button class="mc-modal-close" onclick="closeModal('cancelModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mc-modal-body">
            <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1rem;">
                Please provide a reason for cancellation. The patient will be notified.
            </p>
            <form method="POST" id="cancelForm" action="">
                @csrf
                <label style="font-size:.75rem;font-weight:700;color:var(--text-dark);display:block;margin-bottom:.4rem;">
                    Cancellation Reason <span style="color:#e74c3c;">*</span>
                </label>
                <textarea name="cancellation_reason" id="cancel-reason" rows="4"
                    style="width:100%;border:1.5px solid var(--border);border-radius:9px;
                           padding:.65rem .85rem;font-size:.82rem;font-family:inherit;
                           resize:vertical;outline:none;"
                    placeholder="Enter reason…"
                    onfocus="this.style.borderColor='var(--mc-primary)'"
                    onblur="this.style.borderColor='var(--border)'"></textarea>
                @error('cancellation_reason')
                <div style="font-size:.72rem;color:#e74c3c;margin-top:.25rem;">{{ $message }}</div>
                @enderror
                <div class="mc-modal-foot" style="padding:.9rem 0 0;">
                    <button type="button" class="mc-btn-sm" onclick="closeModal('cancelModal')">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </button>
                    <button type="submit" class="mc-btn-sm danger">
                        <i class="fas fa-times-circle"></i> Cancel Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- PAYMENT MODAL --}}
<div class="mc-modal-overlay" id="paymentModal">
    <div class="mc-modal sm">
        <div class="mc-modal-head">
            <div style="width:34px;height:34px;border-radius:9px;background:#fef8e7;
                        color:#e67e22;display:flex;align-items:center;justify-content:center;font-size:.82rem;">
                <i class="fas fa-credit-card"></i>
            </div>
            <h5>Update Payment</h5>
            <button class="mc-modal-close" onclick="closeModal('paymentModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mc-modal-body">
            <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:1rem;">
                Appointment: <strong id="pm-num">–</strong><br>
                Consultation Fee: <strong id="pm-fee">–</strong>
            </p>
            <form method="POST" id="paymentForm" action="">
                @csrf
                <div class="pay-option selected" onclick="selectPay(this,'unpaid')">
                    <input type="radio" name="payment_status" value="unpaid" id="po-unpaid" checked>
                    <label for="po-unpaid"><i class="fas fa-times-circle" style="color:#e74c3c;"></i> Unpaid</label>
                </div>
                <div class="pay-option" onclick="selectPay(this,'partial')">
                    <input type="radio" name="payment_status" value="partial" id="po-partial">
                    <label for="po-partial"><i class="fas fa-adjust" style="color:#e67e22;"></i> Partial Payment</label>
                </div>
                <div class="pay-option" onclick="selectPay(this,'paid')">
                    <input type="radio" name="payment_status" value="paid" id="po-paid">
                    <label for="po-paid"><i class="fas fa-check-circle" style="color:#27ae60;"></i> Fully Paid</label>
                </div>
                <div id="advance-wrap" style="margin-top:.85rem;display:none;">
                    <label style="font-size:.75rem;font-weight:700;color:var(--text-dark);display:block;margin-bottom:.35rem;">
                        Advance Amount (LKR)
                    </label>
                    <input type="number" name="advance_payment" id="pm-advance" min="0" step="0.01"
                        style="width:100%;height:36px;border:1.5px solid var(--border);
                               border-radius:8px;padding:0 .75rem;font-size:.82rem;
                               font-family:inherit;outline:none;"
                        placeholder="0.00"
                        onfocus="this.style.borderColor='var(--mc-primary)'"
                        onblur="this.style.borderColor='var(--border)'">
                </div>
                <div class="mc-modal-foot" style="padding:.9rem 0 0;">
                    <button type="button" class="mc-btn-sm" onclick="closeModal('paymentModal')">Cancel</button>
                    <button type="submit" class="mc-btn-sm primary">
                        <i class="fas fa-save"></i> Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

// ══════════════════════════════════════════
// DETAIL MODAL — reads from hidden data islands
// ══════════════════════════════════════════
const PAY_LABELS = { unpaid: 'Unpaid', partial: 'Advance Paid', paid: 'Paid' };
const PAY_BG     = { unpaid:'#f8d7da', partial:'#fff3cd', paid:'#d1e7dd' };
const PAY_COLOR  = { unpaid:'#58151c', partial:'#664d03', paid:'#0a3622' };
const ST_BG      = { pending:'#fff3cd', confirmed:'#cfe2ff', completed:'#d1e7dd', cancelled:'#f8d7da', noshow:'#e2e3e5' };
const ST_COLOR   = { pending:'#664d03', confirmed:'#084298', completed:'#0a3622', cancelled:'#58151c', noshow:'#383d41' };

function openDetailModal(id) {
    const el = document.getElementById('apt-data-' + id);
    if (!el) return;
    const d = el.dataset;

    document.getElementById('dm-num').textContent = d.num;

    const fee = parseFloat((d.fee || '0').replace(/[^0-9.]/g, ''));
    const adv = parseFloat((d.advance || '0').replace(/[^0-9.]/g, ''));
    const bal = fee - adv;

    const payLabel = { unpaid: 'Unpaid', partial: 'Advance Paid', paid: 'Paid' };
    const payBgMap = { unpaid: '#f8d7da', partial: '#fff3cd', paid: '#d1e7dd' };
    const payClrMap = { unpaid: '#58151c', partial: '#664d03', paid: '#0a3622' };

    let advanceRow = '';
    let balanceRow = '';
    if (d.payment === 'partial') {
        advanceRow = `
        <div class="apt-detail-item">
            <label>Advance Paid</label>
            <span style="color:#e67e22;font-weight:800;">
                LKR ${adv.toLocaleString('en-US',{minimumFractionDigits:2})}
            </span>
        </div>`;
        balanceRow = `
        <div class="apt-detail-item">
            <label>Balance Due</label>
            <span style="color:#e74c3c;font-weight:800;">
                LKR ${bal.toLocaleString('en-US',{minimumFractionDigits:2})}
            </span>
        </div>`;
    }

    document.getElementById('dm-body').innerHTML = `
        <div class="apt-person-card">
            <div class="apt-person-avatar">${d.patient.charAt(0).toUpperCase()}</div>
            <div class="apt-person-info">
                <h6>${d.patient}</h6>
                <p>${d.patientPhone || '–'}</p>
            </div>
            <div style="margin-left:auto;"><div style="font-size:.68rem;color:var(--text-muted);">Patient</div></div>
        </div>
        <div class="apt-person-card">
            <div class="apt-person-avatar"><i class="fas fa-user-md"></i></div>
            <div class="apt-person-info">
                <h6>Dr. ${d.doctor}</h6>
                <p>${d.specialization}</p>
            </div>
            <div style="margin-left:auto;"><div style="font-size:.68rem;color:var(--text-muted);">Doctor</div></div>
        </div>
        <div class="apt-detail-section">
            <h6><i class="fas fa-calendar-check"></i> Appointment Details</h6>
            <div class="apt-detail-grid col3">
                <div class="apt-detail-item"><label>Date</label><span>${d.date}</span></div>
                <div class="apt-detail-item"><label>Time</label><span>${d.time}</span></div>
                <div class="apt-detail-item"><label>Status</label>
                    <span><span class="apt-badge ${d.status}">
                        <i class="fas ${d.statusIcon}" style="font-size:.6rem;"></i>
                        ${d.statusLabel}
                    </span></span>
                </div>
                <div class="apt-detail-item">
                    <label>Consultation Fee</label>
                    <span style="font-weight:800;color:var(--mc-primary);">${d.fee}</span>
                </div>
                ${advanceRow}
                ${balanceRow}
                <div class="apt-detail-item">
                    <label>Payment Status</label>
                    <span>
                        <span style="background:${payBgMap[d.payment]||'#e2e3e5'};
                                     color:${payClrMap[d.payment]||'#383d41'};
                                     padding:.2rem .5rem;border-radius:6px;
                                     font-size:.72rem;font-weight:700;">
                            ${payLabel[d.payment] ?? d.payment}
                        </span>
                    </span>
                </div>
            </div>
        </div>`;

    // Footer buttons
    let foot = `<button class="mc-btn-sm" onclick="closeModal('detailModal')">
                    <i class="fas fa-times"></i> Close
                </button>`;
    if (d.canConfirm == 1) foot += `
        <form method="POST" action="${d.confirmUrl}" style="display:inline;"
              onsubmit="return confirm('Confirm this appointment?')">
            <input type="hidden" name="_token" value="${CSRF}">
            <button type="submit" class="mc-btn-sm" style="background:#cfe2ff;color:#084298;border-color:#9ec5fe;">
                <i class="fas fa-check"></i> Confirm
            </button>
        </form>`;
    if (d.canComplete == 1) foot += `
        <form method="POST" action="${d.completeUrl}" style="display:inline;"
              onsubmit="return confirm('Mark as completed?')">
            <input type="hidden" name="_token" value="${CSRF}">
            <button type="submit" class="mc-btn-sm success">
                <i class="fas fa-check-double"></i> Complete
            </button>
        </form>`;
    foot += `<button class="mc-btn-sm warning"
                onclick="closeModal('detailModal');openPayModal(
                    ${d.id},'${d.num}',
                    ${d.fee.replace(/[^0-9.]/g,'') || 0},
                    '${d.payment}',0)">
                <i class="fas fa-credit-card"></i> Payment
             </button>`;
    if (d.canCancel == 1) foot += `
        <button class="mc-btn-sm danger"
            onclick="closeModal('detailModal');openCancelModal(${d.id},'${d.cancelUrl}')">
            <i class="fas fa-times-circle"></i> Cancel
        </button>`;

    document.getElementById('dm-foot').innerHTML = foot;
    openModal('detailModal');
}

// ══════════════════════════════════════════
// CANCEL MODAL
// ══════════════════════════════════════════
function openCancelModal(id, url) {
    const el = document.getElementById('apt-data-' + id);
    const actionUrl = url || (el ? el.dataset.cancelUrl : '');
    document.getElementById('cancelForm').action = actionUrl;
    document.getElementById('cancel-reason').value = '';
    openModal('cancelModal');
}

// ══════════════════════════════════════════
// PAYMENT MODAL
// ══════════════════════════════════════════
function openPayModal(id, num, fee, payStatus, advance) {
    const el = document.getElementById('apt-data-' + id);
    document.getElementById('paymentForm').action = el ? el.dataset.paymentUrl : '';
    document.getElementById('pm-num').textContent  = num;
    document.getElementById('pm-fee').textContent  = fee ? 'LKR ' + Number(fee).toLocaleString() : '–';
    document.getElementById('pm-advance').value    = advance || '';

    document.querySelectorAll('.pay-option').forEach(e => e.classList.remove('selected'));
    const radio = document.getElementById('po-' + (payStatus ?? 'unpaid'));
    if (radio) { radio.checked = true; radio.closest('.pay-option').classList.add('selected'); }
    document.getElementById('advance-wrap').style.display = payStatus === 'partial' ? 'block' : 'none';
    openModal('paymentModal');
}
function selectPay(el, val) {
    document.querySelectorAll('.pay-option').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type=radio]').checked = true;
    document.getElementById('advance-wrap').style.display = val === 'partial' ? 'block' : 'none';
}

// ══════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════
function openModal(id)  { document.getElementById(id).classList.add('show');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow=''; }

document.querySelectorAll('.mc-modal-overlay').forEach(ov => {
    ov.addEventListener('click', e => { if(e.target===ov) closeModal(ov.id); });
});
document.addEventListener('keydown', e => {
    if(e.key==='Escape') document.querySelectorAll('.mc-modal-overlay.show').forEach(m=>closeModal(m.id));
});
</script>
@endpush
