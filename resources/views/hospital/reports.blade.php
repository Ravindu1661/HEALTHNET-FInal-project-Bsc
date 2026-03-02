{{-- resources/views/hospital/reports.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.css">
<style>
/* ══════════════════════════════════════════
   PAGE
══════════════════════════════════════════ */
.rep-page { animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }

/* ══════════════════════════════════════════
   YEAR / PERIOD SELECTOR BAR
══════════════════════════════════════════ */
.rep-toolbar {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    padding: .85rem 1.3rem;
    margin-bottom: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
}
.toolbar-left {
    display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
}
.toolbar-title {
    font-size: .93rem; font-weight: 700; color: #2c3e50;
    display: flex; align-items: center; gap: .5rem;
}
.toolbar-title i { color: #2969bf; }

.year-select {
    border: 1.5px solid #e5ecf0; border-radius: 9px;
    padding: .42rem .85rem; font-size: .83rem;
    color: #2c3e50; outline: none; background: #fafcff;
    font-family: inherit; cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.year-select:focus {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41,105,191,.1);
}

.btn-rep {
    padding: .42rem 1rem; border-radius: 9px;
    font-size: .82rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: .4rem;
    white-space: nowrap;
}
.btn-rep.primary { background: #2969bf; color: #fff; }
.btn-rep.primary:hover { background: #1a4f9a; box-shadow: 0 4px 12px rgba(41,105,191,.3); }
.btn-rep.outline {
    background: #fff; color: #2969bf;
    border: 1.5px solid #2969bf;
}
.btn-rep.outline:hover { background: #e8f0fe; }

/* ══════════════════════════════════════════
   SUMMARY STAT CARDS
══════════════════════════════════════════ */
.rep-stat {
    background: #fff;
    border-radius: 14px;
    padding: 1.2rem 1.4rem;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.06);
    position: relative; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.rep-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(44,62,80,.1); }
.rep-stat::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    border-radius: 14px 14px 0 0;
}
.rep-stat.total::before     { background: linear-gradient(90deg,#2969bf,#5b9bd5); }
.rep-stat.completed::before { background: linear-gradient(90deg,#27ae60,#6fcf97); }
.rep-stat.cancelled::before { background: linear-gradient(90deg,#e74c3c,#f1948a); }
.rep-stat.pending::before   { background: linear-gradient(90deg,#f39c12,#f7c04a); }
.rep-stat.confirmed::before { background: linear-gradient(90deg,#3498db,#74b9e7); }
.rep-stat.revenue::before   { background: linear-gradient(90deg,#9b59b6,#c39bd3); }

.rep-stat-top {
    display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: .65rem;
}
.rep-stat-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.rep-stat.total .rep-stat-icon     { background: #e8f0fe; color: #2969bf; }
.rep-stat.completed .rep-stat-icon { background: #e9f7ee; color: #27ae60; }
.rep-stat.cancelled .rep-stat-icon { background: #fdecea; color: #e74c3c; }
.rep-stat.pending .rep-stat-icon   { background: #fef8e7; color: #f39c12; }
.rep-stat.confirmed .rep-stat-icon { background: #eaf4fd; color: #3498db; }
.rep-stat.revenue .rep-stat-icon   { background: #f5eef8; color: #9b59b6; }

.rep-stat-trend {
    font-size: .68rem; font-weight: 700;
    padding: .18rem .5rem; border-radius: 99px;
}
.trend-up   { background: #e9f7ee; color: #27ae60; }
.trend-down { background: #fdecea; color: #e74c3c; }
.trend-flat { background: #f0f4f8; color: #888; }

.rep-stat-num {
    font-size: 1.75rem; font-weight: 900;
    color: #2c3e50; line-height: 1; margin-bottom: .25rem;
}
.rep-stat-label { font-size: .75rem; color: #888; margin: 0; }
.rep-stat-sub   { font-size: .7rem; color: #aab4be; margin-top: .2rem; }

/* ══════════════════════════════════════════
   CHART CARDS
══════════════════════════════════════════ */
.chart-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    overflow: hidden;
}
.chart-card-header {
    padding: .9rem 1.3rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.chart-card-header h6 {
    font-size: .93rem; font-weight: 700;
    color: #2c3e50; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.chart-card-header h6 i { color: #2969bf; }
.chart-card-body { padding: 1rem 1.3rem 1.3rem; }

/* Chart legend pills */
.chart-legend {
    display: flex; flex-wrap: wrap; gap: .5rem; align-items: center;
}
.legend-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .72rem; font-weight: 600; color: #555;
}
.legend-dot {
    width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
}

/* ══════════════════════════════════════════
   TOP DOCTORS TABLE
══════════════════════════════════════════ */
.top-doc-table { width: 100%; border-collapse: collapse; }
.top-doc-table thead tr {
    background: #f8fbff; border-bottom: 2px solid #edf2f7;
}
.top-doc-table thead th {
    padding: .65rem 1rem; font-size: .7rem; font-weight: 700;
    color: #64748b; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap;
}
.top-doc-table tbody tr {
    border-bottom: 1px solid #f5f7fa; transition: background .15s;
}
.top-doc-table tbody tr:last-child { border-bottom: none; }
.top-doc-table tbody tr:hover { background: #f8fbff; }
.top-doc-table td {
    padding: .75rem 1rem; font-size: .83rem; color: #374151; vertical-align: middle;
}

.rank-badge {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 800;
}
.rank-1 { background: linear-gradient(135deg,#FFD700,#FFA500); color: #fff; }
.rank-2 { background: linear-gradient(135deg,#C0C0C0,#A8A8A8); color: #fff; }
.rank-3 { background: linear-gradient(135deg,#CD7F32,#A0522D); color: #fff; }
.rank-n { background: #f0f4f8; color: #888; }

.person-cell { display: flex; align-items: center; gap: .6rem; }
.person-avatar {
    width: 34px; height: 34px; border-radius: 9px;
    background: linear-gradient(135deg,#2969bf,#5b9bd5);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.person-avatar img { width: 34px; height: 34px; border-radius: 9px; object-fit: cover; }
.person-name { font-weight: 600; font-size: .83rem; color: #2c3e50; line-height: 1.2; }
.person-sub  { font-size: .72rem; color: #888; }

/* Progress bar for top doctors */
.perf-bar-wrap {
    display: flex; align-items: center; gap: .6rem;
}
.perf-bar-track {
    flex: 1; height: 6px; background: #f0f4f8; border-radius: 99px; overflow: hidden;
}
.perf-bar-fill {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg,#2969bf,#5b9bd5);
    transition: width .6s ease;
}
.perf-bar-pct { font-size: .7rem; font-weight: 700; color: #555; white-space: nowrap; }

/* ══════════════════════════════════════════
   MONTHLY BREAKDOWN TABLE
══════════════════════════════════════════ */
.monthly-table { width: 100%; border-collapse: collapse; }
.monthly-table thead tr {
    background: #f8fbff; border-bottom: 2px solid #edf2f7;
}
.monthly-table thead th {
    padding: .65rem 1rem; font-size: .7rem; font-weight: 700;
    color: #64748b; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap;
}
.monthly-table tbody tr {
    border-bottom: 1px solid #f5f7fa; transition: background .15s;
}
.monthly-table tbody tr:last-child { border-bottom: none; }
.monthly-table tbody tr:hover { background: #f8fbff; }
.monthly-table td {
    padding: .65rem 1rem; font-size: .82rem; color: #374151; vertical-align: middle;
}
.monthly-table tfoot td {
    padding: .7rem 1rem; font-size: .82rem; font-weight: 700;
    color: #2c3e50; border-top: 2px solid #e5ecf0;
    background: #f8fbff;
}

/* Status mini badges */
.mini-badge {
    display: inline-flex; align-items: center;
    font-size: .7rem; font-weight: 700;
    padding: .18rem .5rem; border-radius: 6px;
}
.mini-completed { background: #d1e7dd; color: #0f5132; }
.mini-cancelled { background: #f8d7da; color: #842029; }
.mini-pending   { background: #fff3cd; color: #856404; }
.mini-confirmed { background: #cfe2ff; color: #084298; }

/* ══════════════════════════════════════════
   EMPTY / LOADING
══════════════════════════════════════════ */
.empty-state {
    text-align: center; padding: 3rem 1rem;
}
.empty-state i { font-size: 2.5rem; color: #d0dae8; margin-bottom: .8rem; display: block; }
.empty-state h6 { color: #888; font-size: .92rem; margin: 0 0 .3rem; }
.empty-state p  { color: #aab4be; font-size: .78rem; margin: 0; }

@keyframes shimmer {
    0%{background-position:-600px 0}100%{background-position:600px 0}
}
.skeleton-line {
    height: 13px; border-radius: 6px;
    background: linear-gradient(90deg,#f0f4f8 25%,#e4eaf0 50%,#f0f4f8 75%);
    background-size: 1200px 100%; animation: shimmer 1.4s infinite linear;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 991.98px) {
    .hide-md { display: none !important; }
}
@media (max-width: 767.98px) {
    .rep-toolbar { flex-direction: column; align-items: flex-start; }
    .toolbar-left { width: 100%; }
    .rep-stat-num { font-size: 1.4rem; }
    .chart-card-body { padding: .75rem; }
    .top-doc-table thead { display: none; }
    .top-doc-table, .top-doc-table tbody,
    .top-doc-table tr, .top-doc-table td { display: block; width: 100%; }
    .top-doc-table tr {
        margin-bottom: .5rem;
        border: 1px solid #f0f4f8; border-radius: 10px; overflow: hidden;
    }
    .top-doc-table td {
        display: flex; align-items: center; justify-content: space-between;
        padding: .5rem .85rem; border-bottom: 1px solid #f5f7fa; font-size: .8rem;
    }
    .top-doc-table td:last-child { border-bottom: none; }
    .top-doc-table td::before {
        content: attr(data-label); font-size: .68rem; font-weight: 700;
        color: #888; text-transform: uppercase; min-width: 100px;
    }
    .top-doc-table td.no-label::before { content: none; }
}
@media (max-width: 575.98px) {
    .monthly-table { font-size: .75rem; }
    .monthly-table td, .monthly-table th { padding: .5rem .6rem; }
}
</style>
@endpush

@section('content')
<div class="rep-page">

    {{-- ══ TOOLBAR ══ --}}
    <div class="rep-toolbar">
        <div class="toolbar-left">
            <span class="toolbar-title">
                <i class="fas fa-chart-bar"></i>
                Analytics Overview
            </span>
            <select class="year-select" id="yearSelect" onchange="loadReports()">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <span id="lastUpdated"
                  style="font-size:.73rem;color:#aab4be;display:flex;align-items:center;gap:.3rem;">
                <i class="far fa-clock"></i> Loading...
            </span>
            <button class="btn-rep outline" onclick="loadReports()">
                <i class="fas fa-sync-alt" id="refreshIcon"></i>
                Refresh
            </button>
        </div>
    </div>

    {{-- ══ SUMMARY STAT CARDS ══ --}}
    <div class="row g-3 mb-4" id="summaryCards">
        @foreach([
            ['total',     'fa-calendar-alt',   'Total Appointments', '—', 'this year'],
            ['completed', 'fa-check-double',   'Completed',          '—', 'this year'],
            ['confirmed', 'fa-check-circle',   'Confirmed',          '—', 'this year'],
            ['pending',   'fa-clock',          'Pending',            '—', 'this year'],
            ['cancelled', 'fa-times-circle',   'Cancelled',          '—', 'this year'],
            ['revenue',   'fa-money-bill-wave','Total Revenue',      '—', 'this year'],
        ] as [$cls, $icon, $label, $val, $sub])
        <div class="col-6 col-sm-4 col-xl-2">
            <div class="rep-stat {{ $cls }}">
                <div class="rep-stat-top">
                    <div class="rep-stat-icon">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <span class="rep-stat-trend trend-flat" id="trend-{{ $cls }}">—</span>
                </div>
                <div class="rep-stat-num" id="sum-{{ $cls }}">
                    <div class="skeleton-line" style="width:60px;height:28px;"></div>
                </div>
                <p class="rep-stat-label">{{ $label }}</p>
                <p class="rep-stat-sub" id="sub-{{ $cls }}">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ ROW 1 : Appointment Chart + Donut ══ --}}
    <div class="row g-3 mb-3">

        {{-- Appointments Line/Bar Chart --}}
        <div class="col-12 col-xl-8">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6>
                        <i class="fas fa-chart-line"></i>
                        Monthly Appointments
                    </h6>
                    <div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;">
                        <div class="chart-legend">
                            <span class="legend-pill">
                                <span class="legend-dot" style="background:#2969bf;"></span>Total
                            </span>
                            <span class="legend-pill">
                                <span class="legend-dot" style="background:#27ae60;"></span>Completed
                            </span>
                            <span class="legend-pill">
                                <span class="legend-dot" style="background:#e74c3c;"></span>Cancelled
                            </span>
                        </div>
                        {{-- Chart type toggle --}}
                        <div style="display:flex;gap:.3rem;">
                            <button onclick="setChartType('bar')" id="btnBar"
                                    style="padding:.25rem .6rem;border-radius:7px;border:1.5px solid #e5ecf0;
                                           background:#e8f0fe;color:#2969bf;font-size:.72rem;
                                           font-weight:600;cursor:pointer;">
                                <i class="fas fa-chart-bar me-1"></i>Bar
                            </button>
                            <button onclick="setChartType('line')" id="btnLine"
                                    style="padding:.25rem .6rem;border-radius:7px;border:1.5px solid #e5ecf0;
                                           background:#fff;color:#888;font-size:.72rem;
                                           font-weight:600;cursor:pointer;">
                                <i class="fas fa-chart-line me-1"></i>Line
                            </button>
                        </div>
                    </div>
                </div>
                <div class="chart-card-body">
                    <div id="appointmentChart" style="min-height:300px;"></div>
                </div>
            </div>
        </div>

        {{-- Status Donut --}}
        <div class="col-12 col-xl-4">
            <div class="chart-card" style="height:100%;">
                <div class="chart-card-header">
                    <h6>
                        <i class="fas fa-chart-pie"></i>
                        Status Distribution
                    </h6>
                </div>
                <div class="chart-card-body">
                    <div id="statusDonut" style="min-height:260px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 2 : Revenue Chart + Top Doctors ══ --}}
    <div class="row g-3 mb-3">

        {{-- Revenue Area Chart --}}
        <div class="col-12 col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6>
                        <i class="fas fa-money-bill-trend-up" style="color:#9b59b6;"></i>
                        Monthly Revenue (LKR)
                    </h6>
                    <span id="totalRevLabel"
                          style="font-size:.78rem;font-weight:700;color:#9b59b6;
                                 background:#f5eef8;padding:.25rem .75rem;
                                 border-radius:99px;border:1px solid #e8d5f0;">
                        LKR 0
                    </span>
                </div>
                <div class="chart-card-body">
                    <div id="revenueChart" style="min-height:280px;"></div>
                </div>
            </div>
        </div>

        {{-- Top Doctors --}}
        <div class="col-12 col-lg-6">
            <div class="chart-card" style="height:100%;">
                <div class="chart-card-header">
                    <h6>
                        <i class="fas fa-trophy" style="color:#f39c12;"></i>
                        Top Performing Doctors
                    </h6>
                </div>
                <div style="overflow-x:auto;">
                    <table class="top-doc-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doctor</th>
                                <th class="hide-md">Specialization</th>
                                <th>Appointments</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody id="topDoctorsBody">
                            @for($i=0;$i<5;$i++)
                            <tr>
                                <td><div class="skeleton-line" style="width:28px;height:28px;border-radius:8px;"></div></td>
                                <td><div class="skeleton-line" style="width:130px;"></div></td>
                                <td class="hide-md"><div class="skeleton-line" style="width:90px;"></div></td>
                                <td><div class="skeleton-line" style="width:40px;"></div></td>
                                <td><div class="skeleton-line" style="width:100px;height:6px;border-radius:99px;"></div></td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 3 : Monthly Breakdown Table ══ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6>
                        <i class="fas fa-table"></i>
                        Monthly Breakdown
                    </h6>
                    <span id="yearLabel"
                          style="font-size:.78rem;font-weight:700;color:#2969bf;
                                 background:#e8f0fe;padding:.25rem .75rem;
                                 border-radius:99px;border:1px solid #c9dcf7;">
                    </span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="monthly-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total</th>
                                <th>Completed</th>
                                <th>Confirmed</th>
                                <th>Pending</th>
                                <th>Cancelled</th>
                                <th>Completion %</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyTableBody">
                            @for($i=0;$i<6;$i++)
                            <tr>
                                @for($j=0;$j<7;$j++)
                                <td><div class="skeleton-line" style="width:{{ rand(40,80) }}px;"></div></td>
                                @endfor
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot id="monthlyTableFoot"></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script>
// ════════════════════════════════════════════════
// CHART INSTANCES
// ════════════════════════════════════════════════
let aptChart     = null;
let donutChart   = null;
let revChart     = null;
let currentChartType = 'bar';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// ════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    initCharts();
    loadReports();
});

// ════════════════════════════════════════════════
// INIT EMPTY CHARTS
// ════════════════════════════════════════════════
function initCharts() {
    // ── Appointment Bar/Line Chart ──
    aptChart = new ApexCharts(document.getElementById('appointmentChart'), {
        series: [
            { name: 'Total',     data: Array(12).fill(0) },
            { name: 'Completed', data: Array(12).fill(0) },
            { name: 'Cancelled', data: Array(12).fill(0) },
        ],
        chart: {
            type: 'bar', height: 300,
            toolbar: { show: false },
            fontFamily: 'Poppins, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 },
        },
        plotOptions: {
            bar: { borderRadius: 5, columnWidth: '55%', borderRadiusApplication: 'end' }
        },
        colors: ['#2969bf', '#27ae60', '#e74c3c'],
        dataLabels: { enabled: false },
        stroke: { show: true, width: [0,0,0], curve: 'smooth' },
        xaxis: {
            categories: MONTHS,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { fontSize: '11px', fontFamily: 'Poppins' } }
        },
        yaxis: {
            labels: {
                style: { fontSize: '11px', fontFamily: 'Poppins' },
                formatter: v => Math.floor(v)
            }
        },
        grid: { borderColor: '#f0f4f8', strokeDashArray: 4 },
        legend: { show: false },
        tooltip: {
            theme: 'light',
            style: { fontFamily: 'Poppins' },
            y: { formatter: v => v + ' appointments' }
        },
    });
    aptChart.render();

    // ── Status Donut ──
    donutChart = new ApexCharts(document.getElementById('statusDonut'), {
        series: [0, 0, 0, 0],
        chart: {
            type: 'donut', height: 260,
            fontFamily: 'Poppins, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 },
        },
        labels: ['Completed', 'Confirmed', 'Pending', 'Cancelled'],
        colors: ['#27ae60', '#3498db', '#f39c12', '#e74c3c'],
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true, label: 'Total',
                            fontSize: '13px', fontWeight: 700,
                            fontFamily: 'Poppins',
                            formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0)
                        },
                        value: { fontSize: '18px', fontWeight: 800, fontFamily: 'Poppins' }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom', fontSize: '11px', fontFamily: 'Poppins',
            markers: { width: 9, height: 9, radius: 5 }
        },
        tooltip: {
            theme: 'light', style: { fontFamily: 'Poppins' },
            y: { formatter: v => v + ' appointments' }
        },
    });
    donutChart.render();

    // ── Revenue Area Chart ──
    revChart = new ApexCharts(document.getElementById('revenueChart'), {
        series: [{ name: 'Revenue (LKR)', data: Array(12).fill(0) }],
        chart: {
            type: 'area', height: 280,
            toolbar: { show: false },
            fontFamily: 'Poppins, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 },
        },
        colors: ['#9b59b6'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: .02, stops: [0,100] }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: MONTHS,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { fontSize: '11px', fontFamily: 'Poppins' } }
        },
        yaxis: {
            labels: {
                style: { fontSize: '10px', fontFamily: 'Poppins' },
                formatter: v => 'LKR ' + fmtNum(v)
            }
        },
        grid: { borderColor: '#f0f4f8', strokeDashArray: 4 },
        tooltip: {
            theme: 'light', style: { fontFamily: 'Poppins' },
            y: { formatter: v => 'LKR ' + Number(v).toLocaleString() }
        },
        markers: { size: 4, colors: ['#9b59b6'], strokeColors: '#fff', strokeWidth: 2 }
    });
    revChart.render();
}

// ════════════════════════════════════════════════
// LOAD REPORTS DATA
// ════════════════════════════════════════════════
function loadReports() {
    const year = document.getElementById('yearSelect').value;
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.style.animation = 'spin 1s linear infinite';

    setText('yearLabel', year + ' Data');

    fetch(`{{ route("hospital.reports.data") }}?year=${year}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        credentials: 'same-origin'
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        if (icon) icon.style.animation = '';

        const now = new Date();
        setText('lastUpdated', '');
        document.getElementById('lastUpdated').innerHTML =
            `<i class="far fa-clock"></i> Updated ${now.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'})}`;

        updateSummary(data.summary ?? {});
        updateAptChart(data.monthly_data ?? []);
        updateDonut(data.summary ?? {});
        updateRevenueChart(data.revenue_data ?? []);
        renderTopDoctors(data.top_doctors ?? []);
        renderMonthlyTable(data.monthly_data ?? [], data.year ?? year);
    })
    .catch(err => {
        if (icon) icon.style.animation = '';
        console.error('Reports Error:', err);
        showToast('Failed to load report data.', 'error');
    });
}

// ════════════════════════════════════════════════
// UPDATE SUMMARY CARDS
// ════════════════════════════════════════════════
function updateSummary(s) {
    const total     = parseInt(s.total     ?? 0);
    const completed = parseInt(s.completed ?? 0);
    const cancelled = parseInt(s.cancelled ?? 0);
    const pending   = parseInt(s.pending   ?? 0);
    const confirmed = parseInt(s.confirmed ?? 0);

    setText('sum-total',     total.toLocaleString());
    setText('sum-completed', completed.toLocaleString());
    setText('sum-cancelled', cancelled.toLocaleString());
    setText('sum-pending',   pending.toLocaleString());
    setText('sum-confirmed', confirmed.toLocaleString());
    setText('sum-revenue',   'LKR —');

    // Completion rate trend
    const compRate = total > 0 ? Math.round((completed/total)*100) : 0;
    const cancRate = total > 0 ? Math.round((cancelled/total)*100) : 0;

    setTrend('trend-total',     total > 0,     `${total} total`);
    setTrend('trend-completed', compRate >= 60, `${compRate}% rate`);
    setTrend('trend-cancelled', cancRate <= 15, `${cancRate}% rate`, true);
    setTrend('trend-pending',   pending === 0,  pending === 0 ? 'Clear' : `${pending} open`);
    setTrend('trend-confirmed', confirmed > 0,  `${confirmed}`);
}

function setTrend(id, isGood, label, invertLogic = false) {
    const el = document.getElementById(id);
    if (!el) return;
    const good = invertLogic ? !isGood : isGood;
    el.textContent = label;
    el.className = 'rep-stat-trend ' + (good ? 'trend-up' : 'trend-down');
}

// ════════════════════════════════════════════════
// UPDATE APPOINTMENT CHART
// ════════════════════════════════════════════════
function updateAptChart(monthlyData) {
    const total     = Array(12).fill(0);
    const completed = Array(12).fill(0);
    const cancelled = Array(12).fill(0);

    monthlyData.forEach(row => {
        const idx = parseInt(row.month) - 1;
        if (idx >= 0 && idx < 12) {
            total[idx]     = parseInt(row.total     ?? 0);
            completed[idx] = parseInt(row.completed ?? 0);
            cancelled[idx] = parseInt(row.cancelled ?? 0);
        }
    });

    aptChart.updateSeries([
        { name: 'Total',     data: total },
        { name: 'Completed', data: completed },
        { name: 'Cancelled', data: cancelled },
    ]);
}

// ════════════════════════════════════════════════
// UPDATE DONUT
// ════════════════════════════════════════════════
function updateDonut(s) {
    donutChart.updateSeries([
        parseInt(s.completed ?? 0),
        parseInt(s.confirmed ?? 0),
        parseInt(s.pending   ?? 0),
        parseInt(s.cancelled ?? 0),
    ]);
}

// ════════════════════════════════════════════════
// UPDATE REVENUE CHART
// ════════════════════════════════════════════════
function updateRevenueChart(revenueData) {
    const rev   = Array(12).fill(0);
    let totalRev = 0;

    revenueData.forEach(row => {
        const idx = parseInt(row.month) - 1;
        if (idx >= 0 && idx < 12) {
            const amount = parseFloat(row.revenue ?? 0);
            rev[idx]  = amount;
            totalRev += amount;
        }
    });

    revChart.updateSeries([{ name: 'Revenue (LKR)', data: rev }]);
    setText('totalRevLabel', 'LKR ' + Number(totalRev).toLocaleString());
    setText('sum-revenue', 'LKR ' + fmtNum(totalRev));

    const hasRev = totalRev > 0;
    setTrend('trend-revenue', hasRev, hasRev ? fmtNum(totalRev) : 'No data');
}

// ════════════════════════════════════════════════
// RENDER TOP DOCTORS
// ════════════════════════════════════════════════
function renderTopDoctors(doctors) {
    const tbody = document.getElementById('topDoctorsBody');
    if (!doctors.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="no-label" style="padding:0;">
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h6>No doctor data</h6>
                        <p>No appointment data available for this year.</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    const max = parseInt(doctors[0]?.total_appointments ?? 1);

    tbody.innerHTML = doctors.map((doc, i) => {
        const rank  = i + 1;
        const rCls  = rank <= 3 ? `rank-${rank}` : 'rank-n';
        const inits = initials(doc.doctor_name);
        const total = parseInt(doc.total_appointments ?? 0);
        const done  = parseInt(doc.completed ?? 0);
        const pct   = max > 0 ? Math.round((total/max)*100) : 0;
        const compPct = total > 0 ? Math.round((done/total)*100) : 0;

        const avatarHtml = doc.profile_image
            ? `<img src="/storage/${doc.profile_image}"
                    onerror="this.style.display='none'">`
            : `<span style="font-size:.72rem;font-weight:700;color:#fff;">${inits}</span>`;

        return `
        <tr>
            <td data-label="Rank" class="no-label">
                <div class="rank-badge ${rCls}">${rank}</div>
            </td>
            <td data-label="Doctor">
                <div class="person-cell">
                    <div class="person-avatar">${avatarHtml}</div>
                    <div>
                        <div class="person-name">${doc.doctor_name ?? '—'}</div>
                        <div class="person-sub">${compPct}% completion</div>
                    </div>
                </div>
            </td>
            <td data-label="Specialization" class="hide-md">
                <span style="font-size:.75rem;color:#2969bf;font-weight:600;">
                    ${doc.specialization ?? '—'}
                </span>
            </td>
            <td data-label="Appointments">
                <span style="font-weight:700;color:#2c3e50;">${total}</span>
                <span style="font-size:.7rem;color:#888;"> / ${done} done</span>
            </td>
            <td data-label="Performance" class="no-label">
                <div class="perf-bar-wrap">
                    <div class="perf-bar-track">
                        <div class="perf-bar-fill" style="width:${pct}%;"></div>
                    </div>
                    <span class="perf-bar-pct">${pct}%</span>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ════════════════════════════════════════════════
// RENDER MONTHLY TABLE
// ════════════════════════════════════════════════
function renderMonthlyTable(monthlyData, year) {
    const tbody = document.getElementById('monthlyTableBody');
    const tfoot = document.getElementById('monthlyTableFoot');

    if (!monthlyData.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:#aab4be;font-size:.83rem;">
                    No monthly data available for ${year}.
                </td>
            </tr>`;
        tfoot.innerHTML = '';
        return;
    }

    // Build a full 12-month map
    const map = {};
    monthlyData.forEach(r => { map[parseInt(r.month)] = r; });

    let totals = { total:0, completed:0, confirmed:0, pending:0, cancelled:0 };
    let html   = '';

    const currentYear  = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1;

    for (let m = 1; m <= 12; m++) {
        const r = map[m] ?? { total:0, completed:0, confirmed:0, pending:0, cancelled:0, month:m };
        const total     = parseInt(r.total     ?? 0);
        const completed = parseInt(r.completed ?? 0);
        const confirmed = parseInt(r.confirmed ?? 0);
        const pending   = parseInt(r.pending   ?? 0);
        const cancelled = parseInt(r.cancelled ?? 0);
        const compPct   = total > 0 ? Math.round((completed/total)*100) : 0;

        totals.total     += total;
        totals.completed += completed;
        totals.confirmed += confirmed;
        totals.pending   += pending;
        totals.cancelled += cancelled;

        const isCurrent = parseInt(year) === currentYear && m === currentMonth;
        const isEmpty   = total === 0 && parseInt(year) <= currentYear;

        html += `
        <tr style="${isCurrent ? 'background:#f0f6ff;' : ''}">
            <td>
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <span style="font-weight:${isCurrent?800:600};
                                 color:${isCurrent?'#2969bf':'#2c3e50'};">
                        ${MONTHS[m-1]}
                    </span>
                    ${isCurrent
                        ? `<span style="font-size:.6rem;font-weight:700;
                                        background:#2969bf;color:#fff;
                                        padding:.1rem .4rem;border-radius:99px;">
                               Current
                           </span>` : ''}
                </div>
            </td>
            <td>
                <span style="font-weight:700;color:${total>0?'#2c3e50':'#ccc'};">
                    ${total || (isEmpty?'—':0)}
                </span>
            </td>
            <td>
                ${total > 0
                    ? `<span class="mini-badge mini-completed">${completed}</span>`
                    : `<span style="color:#ccc;">—</span>`}
            </td>
            <td>
                ${total > 0
                    ? `<span class="mini-badge mini-confirmed">${confirmed}</span>`
                    : `<span style="color:#ccc;">—</span>`}
            </td>
            <td>
                ${total > 0
                    ? `<span class="mini-badge mini-pending">${pending}</span>`
                    : `<span style="color:#ccc;">—</span>`}
            </td>
            <td>
                ${total > 0
                    ? `<span class="mini-badge mini-cancelled">${cancelled}</span>`
                    : `<span style="color:#ccc;">—</span>`}
            </td>
            <td>
                ${total > 0
                    ? `<div style="display:flex;align-items:center;gap:.5rem;">
                           <div style="flex:1;height:5px;background:#f0f4f8;border-radius:99px;overflow:hidden;min-width:50px;">
                               <div style="height:100%;border-radius:99px;
                                           background:${compPct>=70?'#27ae60':compPct>=40?'#f39c12':'#e74c3c'};
                                           width:${compPct}%;transition:width .6s;"></div>
                           </div>
                           <span style="font-size:.72rem;font-weight:700;
                                        color:${compPct>=70?'#27ae60':compPct>=40?'#856404':'#842029'};">
                               ${compPct}%
                           </span>
                       </div>`
                    : `<span style="color:#ccc;">—</span>`}
            </td>
        </tr>`;
    }
    tbody.innerHTML = html;

    // Footer totals
    const totalCompPct = totals.total > 0
        ? Math.round((totals.completed/totals.total)*100) : 0;

    tfoot.innerHTML = `
        <tr>
            <td>Total (${year})</td>
            <td>${totals.total.toLocaleString()}</td>
            <td>${totals.completed.toLocaleString()}</td>
            <td>${totals.confirmed.toLocaleString()}</td>
            <td>${totals.pending.toLocaleString()}</td>
            <td>${totals.cancelled.toLocaleString()}</td>
            <td>${totalCompPct}%</td>
        </tr>`;
}

// ════════════════════════════════════════════════
// CHART TYPE TOGGLE
// ════════════════════════════════════════════════
function setChartType(type) {
    currentChartType = type;
    const btnBar  = document.getElementById('btnBar');
    const btnLine = document.getElementById('btnLine');

    btnBar.style.background  = type === 'bar'  ? '#e8f0fe' : '#fff';
    btnBar.style.color       = type === 'bar'  ? '#2969bf' : '#888';
    btnLine.style.background = type === 'line' ? '#e8f0fe' : '#fff';
    btnLine.style.color      = type === 'line' ? '#2969bf' : '#888';

    if (type === 'bar') {
        aptChart.updateOptions({
            chart: { type: 'bar' },
            plotOptions: { bar: { borderRadius: 5, columnWidth: '55%' } },
            stroke: { show: true, width: [0,0,0] },
        });
    } else {
        aptChart.updateOptions({
            chart: { type: 'line' },
            stroke: { show: true, width: [2.5,2.5,2.5], curve: 'smooth' },
            plotOptions: { bar: { borderRadius: 0, columnWidth: '0%' } },
        });
    }
}

// ════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════
function showToast(msg, type = 'success') {
    const ex = document.getElementById('repToast');
    if (ex) ex.remove();
    const c = {
        success: { bg:'#d1e7dd', color:'#0f5132', icon:'fa-check-circle' },
        error:   { bg:'#f8d7da', color:'#842029', icon:'fa-exclamation-circle' },
    }[type] ?? { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' };

    const t = document.createElement('div');
    t.id = 'repToast';
    t.style.cssText = `
        position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${c.bg};color:${c.color};
        border-radius:12px;padding:.8rem 1.2rem;
        display:flex;align-items:center;gap:.6rem;
        font-size:.83rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        animation:slideUp .3s ease;max-width:320px;
        border:1px solid ${c.color}33;
    `;
    t.innerHTML = `<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

// ════════════════════════════════════════════════
// UTILITIES
// ════════════════════════════════════════════════
function initials(name) {
    return (name || 'D').split(' ').map(w => w[0] || '').join('').slice(0,2).toUpperCase();
}
function setText(id, v) { const e = document.getElementById(id); if(e) e.textContent = v; }
function fmtNum(n) {
    n = parseFloat(n);
    if (n >= 1000000) return (n/1000000).toFixed(1) + 'M';
    if (n >= 1000)    return (n/1000).toFixed(1) + 'K';
    return n.toLocaleString();
}

// Inject animations
const s = document.createElement('style');
s.textContent = `
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes slideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
`;
document.head.appendChild(s);
</script>
@endpush
