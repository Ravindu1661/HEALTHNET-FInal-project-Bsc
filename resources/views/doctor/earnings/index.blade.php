@extends('doctor.layouts.master')

@section('title', 'My Earnings')
@section('page-title', 'My Earnings')

@push('styles')
<style>
/* ══════════════════════════════════════
   EARNINGS PAGE — FULL STYLES
══════════════════════════════════════ */

.earnings-wrap {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.5rem 1rem;
}

/* ── Period Tabs ── */
.period-tabs {
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}
.pt-btn {
    padding: .3rem .9rem;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    font-size: .76rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
}
.pt-btn:hover { border-color: #0d6efd; color: #0d6efd; text-decoration: none; }
.pt-btn.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }

/* ── Date Label ── */
.date-label {
    font-size: .75rem;
    color: #94a3b8;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .3rem;
}

/* ── Summary Cards ── */
.earn-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.2rem 1.3rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
    transition: transform .2s, box-shadow .2s;
}
.earn-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,.09);
}
.earn-icon {
    width: 50px;
    height: 50px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.earn-num {
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.1;
}
.earn-lbl {
    font-size: .7rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-top: .2rem;
}
.earn-sub {
    font-size: .68rem;
    font-weight: 600;
    margin-top: .2rem;
}

/* ── Section Card ── */
.sec-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.3rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    margin-bottom: 1.2rem;
}
.sec-title {
    font-size: .83rem;
    font-weight: 700;
    color: #1a1a1a;
    padding-bottom: .65rem;
    border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.sec-title i { color: #0d6efd; font-size: .85rem; }
.sec-badge {
    margin-left: auto;
    font-size: .67rem;
    color: #aaa;
    font-weight: 400;
}

/* ── Trend Chart ── */
.trend-bars {
    display: flex;
    align-items: flex-end;
    gap: .45rem;
    height: 110px;
    padding-bottom: 0;
}
.trend-bar-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .25rem;
    height: 100%;
    justify-content: flex-end;
}
.trend-bar {
    width: 100%;
    border-radius: 6px 6px 0 0;
    background: linear-gradient(180deg, #0d6efd, #6f42c1);
    min-height: 4px;
    transition: height .5s ease;
    cursor: pointer;
    position: relative;
}
.trend-bar:hover { opacity: .8; }
.trend-bar-val {
    font-size: .62rem;
    color: #0d6efd;
    font-weight: 700;
    white-space: nowrap;
}
.trend-bar-lbl {
    font-size: .62rem;
    color: #aaa;
    font-weight: 600;
    white-space: nowrap;
}
.trend-count-row {
    display: flex;
    gap: .45rem;
    margin-top: .75rem;
}
.trend-count-cell {
    flex: 1;
    text-align: center;
    font-size: .65rem;
    color: #0d6efd;
    font-weight: 700;
    background: #f0f5ff;
    border-radius: 6px;
    padding: .22rem .05rem;
}

/* ── Workplace Rows ── */
.wp-row {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: .55rem 0;
    border-bottom: 1px solid #f0f3f8;
}
.wp-row:last-child { border-bottom: none; }
.wp-name { min-width: 110px; font-size: .78rem; font-weight: 600; color: #1a1a1a; }
.wp-sub  { font-size: .65rem; color: #aaa; font-weight: 400; }
.wp-bar  { flex: 1; height: 7px; background: #eef0f5; border-radius: 10px; overflow: hidden; }
.wp-fill { height: 100%; border-radius: 10px;
    background: linear-gradient(90deg, #0d6efd, #6f42c1); }
.wp-amt  { font-size: .78rem; font-weight: 700; color: #198754;
    white-space: nowrap; min-width: 85px; text-align: right; }

/* ── Type Cards ── */
.type-card {
    background: #f8f9fb;
    border-radius: 12px;
    padding: .85rem .5rem;
    text-align: center;
    border: 1.5px solid transparent;
    transition: all .2s;
}
.type-card:hover { border-color: #0d6efd22; background: #f0f5ff; }
.type-num { font-size: 1.1rem; font-weight: 800; color: #0d6efd; }
.type-lbl { font-size: .7rem; font-weight: 600; color: #555;
    text-transform: capitalize; margin: .1rem 0 .25rem; }
.type-amt { font-size: .72rem; font-weight: 700; color: #198754; }

/* ── Transaction Table ── */
.tx-wrap { overflow-x: auto; }
.tx-table {
    width: 100%;
    border-collapse: collapse;
}
.tx-table thead th {
    font-size: .69rem;
    font-weight: 700;
    color: #94a3b8;
    padding: .5rem .8rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-bottom: 2px solid #f0f3f8;
    white-space: nowrap;
    background: #fafbfc;
}
.tx-table tbody tr {
    border-bottom: 1px solid #f8f9fb;
    transition: background .15s;
}
.tx-table tbody tr:hover { background: #f8faff; }
.tx-table tbody td {
    padding: .7rem .8rem;
    font-size: .8rem;
    vertical-align: middle;
}

/* ── Payment Status Badges ── */
.pay-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    white-space: nowrap;
}
.pay-paid    { background: #d4edda; color: #155724; }
.pay-unpaid  { background: #fff3cd; color: #856404; }
.pay-partial { background: #d1ecf1; color: #0c5460; }

/* ── Workplace Type Pill ── */
.wtype-pill {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .18rem .55rem;
    border-radius: 20px;
    font-size: .67rem;
    font-weight: 600;
    white-space: nowrap;
}
.wtype-hospital        { background: #e8f4fd; color: #1a6fa8; }
.wtype-medical_centre  { background: #e8f8f0; color: #1a7a4a; }
.wtype-private         { background: #f3e8fd; color: #6a1a9a; }

/* ── Empty State ── */
.empty-state {
    text-align: center;
    padding: 3.5rem 1rem;
    color: #c0c8d4;
}
.empty-state i  { font-size: 2.8rem; display: block; margin-bottom: .6rem; }
.empty-state p  { font-size: .84rem; font-weight: 600; margin: .2rem 0 0; color: #94a3b8; }
.empty-state p.sub { font-size: .75rem; font-weight: 400; color: #c0c8d4; }

/* ── Custom Date Filter ── */
.filter-card {
    background: #fff;
    border-radius: 14px;
    padding: 1rem 1.2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    margin-bottom: 1rem;
}

/* ── Pagination tweak ── */
.pagination { margin: 0; }
.page-item .page-link { font-size: .78rem; padding: .35rem .7rem; }

/* ── Responsive ── */
@media (max-width: 576px) {
    .earn-num { font-size: 1rem; }
    .trend-bars { height: 80px; }
    .pt-btn { font-size: .7rem; padding: .25rem .7rem; }
}
</style>
@endpush

@section('content')
<div class="earnings-wrap">

    {{-- ══════════════════════════════════
         PERIOD TABS
    ══════════════════════════════════ --}}
    <div class="period-tabs">
        @foreach([
            'today'      => ['Today',      'fa-sun'],
            'this_week'  => ['This Week',  'fa-calendar-week'],
            'this_month' => ['This Month', 'fa-calendar-alt'],
            'last_month' => ['Last Month', 'fa-history'],
            'this_year'  => ['This Year',  'fa-calendar'],
            'custom'     => ['Custom',     'fa-sliders-h'],
        ] as $key => [$label, $icon])
        <a href="{{ route('doctor.earnings.index',
               array_merge(request()->except('period','date_from','date_to','page'),
                           ['period' => $key])) }}"
           class="pt-btn {{ $period === $key ? 'active' : '' }}">
            <i class="fas {{ $icon }}"></i>
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- ══════════════════════════════════
         CUSTOM DATE PICKER
    ══════════════════════════════════ --}}
    @if($period === 'custom')
    <div class="filter-card">
        <form method="GET" action="{{ route('doctor.earnings.index') }}"
              class="row g-2 align-items-end">
            <input type="hidden" name="period" value="custom">
            <div class="col-sm-4">
                <label class="form-label mb-1"
                       style="font-size:.74rem;font-weight:600;color:#555">
                    <i class="fas fa-calendar-day me-1"></i>From
                </label>
                <input type="date" name="date_from"
                       class="form-control form-control-sm"
                       value="{{ $dateFrom }}">
            </div>
            <div class="col-sm-4">
                <label class="form-label mb-1"
                       style="font-size:.74rem;font-weight:600;color:#555">
                    <i class="fas fa-calendar-day me-1"></i>To
                </label>
                <input type="date" name="date_to"
                       class="form-control form-control-sm"
                       value="{{ $dateTo }}">
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search me-1"></i>Apply Filter
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Date Range Label --}}
    <div class="date-label">
        <i class="fas fa-calendar-alt"></i>
        {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
        &nbsp;–&nbsp;
        {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
    </div>

    {{-- ══════════════════════════════════
         SUMMARY CARDS
    ══════════════════════════════════ --}}
    <div class="row g-3 mb-3">

        {{-- Period Earnings --}}
        <div class="col-6 col-md-3">
            <div class="earn-card">
                <div class="earn-icon"
                     style="background:linear-gradient(135deg,#0d6efd22,#0d6efd55)">
                    <i class="fas fa-coins" style="color:#0d6efd"></i>
                </div>
                <div>
                    <div class="earn-num" style="color:#0d6efd">
                        LKR {{ number_format($summary->total_earnings ?? 0, 2) }}
                    </div>
                    <div class="earn-lbl">Period Earnings</div>
                    <div class="earn-sub" style="color:#0d6efd88">
                        {{ $summary->total_appointments ?? 0 }} appointments
                    </div>
                </div>
            </div>
        </div>

        {{-- All Time --}}
        <div class="col-6 col-md-3">
            <div class="earn-card">
                <div class="earn-icon"
                     style="background:linear-gradient(135deg,#6f42c122,#6f42c155)">
                    <i class="fas fa-trophy" style="color:#6f42c1"></i>
                </div>
                <div>
                    <div class="earn-num" style="color:#6f42c1">
                        LKR {{ number_format($allTime ?? 0, 2) }}
                    </div>
                    <div class="earn-lbl">All Time</div>
                </div>
            </div>
        </div>

        {{-- Avg Per Visit --}}
        <div class="col-6 col-md-3">
            <div class="earn-card">
                <div class="earn-icon"
                     style="background:linear-gradient(135deg,#fd7e1422,#fd7e1455)">
                    <i class="fas fa-chart-line" style="color:#fd7e14"></i>
                </div>
                <div>
                    <div class="earn-num" style="color:#fd7e14">
                        LKR {{ number_format($summary->avg_fee ?? 0, 0) }}
                    </div>
                    <div class="earn-lbl">Avg Per Visit</div>
                    <div class="earn-sub" style="color:#fd7e1488">
                        Max: LKR {{ number_format($summary->max_fee ?? 0, 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="col-6 col-md-3">
            <div class="earn-card">
                <div class="earn-icon"
                     style="background:linear-gradient(135deg,#dc354522,#dc354555)">
                    <i class="fas fa-clock" style="color:#dc3545"></i>
                </div>
                <div>
                    <div class="earn-num" style="color:#dc3545">
                        LKR {{ number_format($pendingAmount ?? 0, 2) }}
                    </div>
                    <div class="earn-lbl">Pending Payments</div>
                    <div class="earn-sub" style="color:#dc354588">
                        Unpaid / Partial
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /summary row --}}

    <div class="row g-3 mb-1">

        {{-- ══════════════════════════════════
             MONTHLY TREND
        ══════════════════════════════════ --}}
        <div class="col-lg-7">
            <div class="sec-card">
                <div class="sec-title">
                    <i class="fas fa-chart-bar"></i>
                    Monthly Trend
                    <span class="sec-badge">Last 6 months</span>
                </div>
                @php
                    $maxEarn = max(array_column($monthlyTrend, 'earnings') ?: [1]);
                    $maxEarn = $maxEarn ?: 1;
                @endphp
                <div class="trend-bars">
                    @foreach($monthlyTrend as $mt)
                    @php
                        $barH = $maxEarn > 0
                            ? max(4, (int) round(($mt['earnings'] / $maxEarn) * 96))
                            : 4;
                    @endphp
                    <div class="trend-bar-wrap">
                        <div class="trend-bar-val">
                            {{ $mt['earnings'] > 0
                                ? number_format($mt['earnings']/1000, 1).'k'
                                : '0' }}
                        </div>
                        <div class="trend-bar"
                             style="height:{{ $barH }}px"
                             title="{{ $mt['month'] }}: LKR {{ number_format($mt['earnings'], 2) }}">
                        </div>
                        <div class="trend-bar-lbl">{{ $mt['month'] }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="trend-count-row">
                    @foreach($monthlyTrend as $mt)
                    <div class="trend-count-cell">{{ $mt['count'] }} apts</div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════
             BY WORKPLACE
        ══════════════════════════════════ --}}
        <div class="col-lg-5">
            <div class="sec-card">
                <div class="sec-title">
                    <i class="fas fa-hospital-alt"></i>
                    By Workplace
                </div>
                @if($byWorkplace->count() > 0)
                @php $maxWp = $byWorkplace->max('earnings') ?: 1; @endphp
                @foreach($byWorkplace as $wp)
                <div class="wp-row">
                    <div class="wp-name">
                        {{ Str::limit($wp->workplace, 22) }}
                        <div class="wp-sub">{{ $wp->count }} appointments</div>
                    </div>
                    <div class="wp-bar">
                        <div class="wp-fill"
                             style="width:{{ round(($wp->earnings/$maxWp)*100) }}%">
                        </div>
                    </div>
                    <div class="wp-amt">
                        LKR {{ number_format($wp->earnings, 0) }}
                    </div>
                </div>
                @endforeach
                @else
                <div class="empty-state" style="padding:1.5rem">
                    <i class="fas fa-hospital" style="font-size:1.8rem"></i>
                    <p>No workplace data</p>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- /trend + workplace row --}}

    {{-- ══════════════════════════════════
         BY WORKPLACE TYPE
    ══════════════════════════════════ --}}
    @if($byType->count() > 0)
    <div class="sec-card">
        <div class="sec-title">
            <i class="fas fa-layer-group"></i>
            By Workplace Type
        </div>
        <div class="row g-2">
            @foreach($byType as $bt)
            <div class="col-6 col-sm-4 col-md-3">
                <div class="type-card">
                    <div class="type-num">{{ $bt->count }}</div>
                    <div class="type-lbl">
                        {{ str_replace('_', ' ', $bt->type) }}
                    </div>
                    <div class="type-amt">
                        LKR {{ number_format($bt->earnings, 0) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════
         TRANSACTIONS TABLE
    ══════════════════════════════════ --}}
    <div class="sec-card">
        <div class="sec-title">
            <i class="fas fa-receipt"></i>
            Transactions
            <span class="sec-badge">{{ $transactions->total() }} records found</span>
        </div>

        @if($transactions->count() > 0)
        <div class="tx-wrap">
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Workplace</th>
                        <th>Type</th>
                        <th>Fee</th>
                        <th>Advance</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr>
                        {{-- Appointment No --}}
                        <td>
                            <span style="font-size:.7rem;color:#aaa;font-weight:600">
                                {{ $tx->appointment_number ?? '#'.$tx->id }}
                            </span>
                        </td>

                        {{-- Patient --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <div style="width:30px;height:30px;border-radius:50%;
                                     background:#e8f0fe;display:flex;align-items:center;
                                     justify-content:center;font-size:.72rem;
                                     font-weight:700;color:#0d6efd;flex-shrink:0">
                                    {{ strtoupper(substr($tx->patient_name ?? 'P', 0, 1)) }}
                                </div>
                                <span style="font-weight:600;color:#1a1a1a;font-size:.8rem">
                                    {{ trim($tx->patient_name) ?: 'Unknown' }}
                                </span>
                            </div>
                        </td>

                        {{-- Date & Time --}}
                        <td>
                            <div style="font-weight:600;color:#1a1a1a;font-size:.8rem">
                                {{ \Carbon\Carbon::parse($tx->appointment_date)->format('d M Y') }}
                            </div>
                            <div style="font-size:.68rem;color:#aaa">
                                {{ \Carbon\Carbon::parse($tx->appointment_time)->format('h:i A') }}
                            </div>
                        </td>

                        {{-- Workplace --}}
                        <td>
                            <div style="font-size:.78rem;color:#555;font-weight:500">
                                {{ Str::limit($tx->workplace, 25) }}
                            </div>
                            <span class="wtype-pill wtype-{{ $tx->workplace_type }}">
                                <i class="fas fa-{{ $tx->workplace_type === 'hospital'
                                    ? 'hospital'
                                    : ($tx->workplace_type === 'medical_centre'
                                        ? 'clinic-medical'
                                        : 'user-md') }}"></i>
                                {{ str_replace('_', ' ', ucfirst($tx->workplace_type)) }}
                            </span>
                        </td>

                        {{-- Reason / Type --}}
                        <td>
                            <span style="font-size:.72rem;font-weight:600;
                                 background:#f0f5ff;color:#0d6efd;
                                 padding:.15rem .5rem;border-radius:6px">
                                {{ $tx->reason
                                    ? Str::limit($tx->reason, 20)
                                    : 'Consultation' }}
                            </span>
                        </td>

                        {{-- Fee --}}
                        <td>
                            <span style="font-weight:800;color:#198754;font-size:.84rem">
                                LKR {{ number_format($tx->consultation_fee ?? 0, 2) }}
                            </span>
                        </td>

                        {{-- Advance --}}
                        <td>
                            <span style="font-size:.78rem;color:#555">
                                LKR {{ number_format($tx->advance_payment ?? 0, 2) }}
                            </span>
                        </td>

                        {{-- Payment Status --}}
                        <td>
                            @php $ps = $tx->payment_status ?? 'unpaid'; @endphp
                            <span class="pay-badge pay-{{ $ps }}">
                                <i class="fas fa-{{
                                    $ps === 'paid'    ? 'check-circle' :
                                    ($ps === 'partial' ? 'adjust'       : 'clock')
                                }}" style="font-size:.6rem"></i>
                                {{ ucfirst($ps) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="d-flex justify-content-between align-items-center
                    mt-3 pt-2 border-top">
            <div style="font-size:.74rem;color:#94a3b8">
                Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                of {{ $transactions->total() }} records
            </div>
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        {{-- Empty --}}
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <p>No transactions found</p>
            <p class="sub">No completed appointments in this period</p>
        </div>
        @endif

    </div>{{-- /transactions --}}

</div>{{-- /earnings-wrap --}}
@endsection
