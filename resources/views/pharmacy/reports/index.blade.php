{{-- resources/views/pharmacy/reports/index.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@push('styles')
<style>
.report-nav-card {
    border-radius:14px; border:1.5px solid #e5e7eb;
    padding:20px; cursor:pointer; transition:all .2s;
    text-decoration:none; color:inherit; display:block;
}
.report-nav-card:hover {
    border-color:var(--color); box-shadow:0 4px 20px rgba(0,0,0,.08);
    transform:translateY(-2px); color:inherit;
}
.kpi-card {
    border-radius:12px; padding:18px;
    border:none; position:relative; overflow:hidden;
}
.kpi-card::after {
    content:''; position:absolute; right:-15px; bottom:-15px;
    width:70px; height:70px; border-radius:50%;
    background:currentColor; opacity:.08;
}
</style>
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Reports & Analytics</h5>
        <small class="text-muted">Pharmacy performance overview & detailed reports</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.reports.export', ['type'=>'sales',
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date'   => now()->toDateString()]) }}"
           class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="fas fa-download me-1"></i>Export CSV
        </a>
    </div>
</div>

{{-- ── KPI Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card" style="background:#eff6ff;color:#2563eb">
            <div style="font-size:.74rem;font-weight:600;text-transform:uppercase;
                        letter-spacing:.05em;opacity:.7">Total Orders</div>
            <div style="font-size:2rem;font-weight:700;line-height:1.2">{{ $totalOrders }}</div>
            <small style="opacity:.7">All time</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card" style="background:#f0fdf4;color:#16a34a">
            <div style="font-size:.74rem;font-weight:600;text-transform:uppercase;
                        letter-spacing:.05em;opacity:.7">Total Revenue</div>
            <div style="font-size:1.7rem;font-weight:700;line-height:1.2">
                Rs. {{ number_format($totalRevenue, 0) }}
            </div>
            <small style="opacity:.7">Paid orders</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card" style="background:#faf5ff;color:#7c3aed">
            <div style="font-size:.74rem;font-weight:600;text-transform:uppercase;
                        letter-spacing:.05em;opacity:.7">Total Patients</div>
            <div style="font-size:2rem;font-weight:700;line-height:1.2">{{ $totalPatients }}</div>
            <small style="opacity:.7">Unique customers</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card" style="background:#fff7ed;color:#d97706">
            <div style="font-size:.74rem;font-weight:600;text-transform:uppercase;
                        letter-spacing:.05em;opacity:.7">Today's Revenue</div>
            <div style="font-size:1.7rem;font-weight:700;line-height:1.2">
                Rs. {{ number_format($todayRevenue, 0) }}
            </div>
            <small style="opacity:.7">{{ $todayOrders }} orders today</small>
        </div>
    </div>
</div>

{{-- ── This Month vs Last Month ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-calendar-alt me-2 text-primary"></i>
                    This Month — {{ now()->format('F Y') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="fw-bold" style="font-size:1.5rem;color:#2563eb">
                            {{ $thisMonthOrders }}
                        </div>
                        <small class="text-muted">Orders</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold" style="font-size:1.4rem;color:#16a34a">
                            Rs. {{ number_format($thisMonthRevenue, 0) }}
                        </div>
                        <small class="text-muted">Revenue</small>
                    </div>
                </div>
                @php
                    $growth = $lastMonthRevenue > 0
                        ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                        : ($thisMonthRevenue > 0 ? 100 : 0);
                @endphp
                <div class="mt-3 text-center">
                    <span class="badge rounded-pill {{ $growth >= 0 ? 'bg-success' : 'bg-danger' }}
                          bg-opacity-15 {{ $growth >= 0 ? 'text-success' : 'text-danger' }}"
                          style="font-size:.8rem;padding:6px 14px">
                        <i class="fas fa-arrow-{{ $growth >= 0 ? 'up' : 'down' }} me-1"></i>
                        {{ abs(round($growth, 1)) }}% vs last month
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Generate Report Form ── --}}
    <div class="col-md-6">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-sliders-h me-2 text-info"></i>Generate Report</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacy.reports.generate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Report Type</label>
                        <select name="report_type" id="reportType" class="form-select form-select-sm"
                                onchange="toggleReportFields()">
                            <option value="daily">Daily Report</option>
                            <option value="monthly">Monthly Report</option>
                            <option value="sales">Sales Report (Date Range)</option>
                            <option value="inventory">Inventory Report</option>
                        </select>
                    </div>
                    <div id="fieldDaily" class="mb-3">
                        <label class="form-label form-label-sm">Date</label>
                        <input type="date" name="date" class="form-control form-control-sm"
                               value="{{ today()->toDateString() }}">
                    </div>
                    <div id="fieldMonthly" class="mb-3 d-none">
                        <label class="form-label form-label-sm">Month</label>
                        <input type="month" name="month" class="form-control form-control-sm"
                               value="{{ now()->format('Y-m') }}">
                    </div>
                    <div id="fieldSales" class="d-none">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label form-label-sm">From</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="{{ now()->startOfMonth()->toDateString() }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label form-label-sm">To</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="{{ today()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">
                        <i class="fas fa-chart-bar me-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Report Navigation Cards ── --}}
<div class="row g-3 mb-4">
    @php
        $navCards = [
            ['route'=>'pharmacy.reports.daily', 'params'=>['date'=>today()->toDateString()],
             'icon'=>'fas fa-calendar-day', 'color'=>'#2563eb',
             'bg'=>'#eff6ff', 'title'=>'Daily Report',
             'desc'=>"Today's orders, revenue, and breakdowns"],
            ['route'=>'pharmacy.reports.monthly', 'params'=>['month'=>now()->format('Y-m')],
             'icon'=>'fas fa-calendar-alt', 'color'=>'#16a34a',
             'bg'=>'#f0fdf4', 'title'=>'Monthly Report',
             'desc'=>'Month-wise order & revenue trends'],
            ['route'=>'pharmacy.reports.sales',
             'params'=>['start_date'=>now()->startOfMonth()->toDateString(),
                        'end_date'  =>today()->toDateString()],
             'icon'=>'fas fa-chart-line', 'color'=>'#7c3aed',
             'bg'=>'#faf5ff', 'title'=>'Sales Report',
             'desc'=>'Custom date range sales analysis'],
            ['route'=>'pharmacy.reports.inventory', 'params'=>[],
             'icon'=>'fas fa-boxes', 'color'=>'#d97706',
             'bg'=>'#fff7ed', 'title'=>'Inventory Report',
             'desc'=>'Stock levels, values & dispensing history'],
        ];
    @endphp
    @foreach($navCards as $card)
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route($card['route'], $card['params']) }}"
           class="report-nav-card" style="--color:{{ $card['color'] }}">
            <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                 style="width:46px;height:46px;background:{{ $card['bg'] }}">
                <i class="{{ $card['icon'] }}" style="color:{{ $card['color'] }};font-size:1.1rem"></i>
            </div>
            <div class="fw-semibold mb-1">{{ $card['title'] }}</div>
            <small class="text-muted" style="font-size:.78rem">{{ $card['desc'] }}</small>
        </a>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">

    {{-- ── Monthly Revenue Chart ── --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar me-2 text-primary"></i>
                    Monthly Revenue — {{ now()->year }}
                </h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Order Status Pie ── --}}
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-pie me-2 text-purple" style="color:#7c3aed"></i>
                    Order Status
                </h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="160"></canvas>
                <div class="mt-3">
                    @php
                        $statusColors = [
                            'pending'    => '#f59e0b',
                            'verified'   => '#0891b2',
                            'processing' => '#2563eb',
                            'ready'      => '#16a34a',
                            'dispatched' => '#6b7280',
                            'delivered'  => '#10b981',
                            'cancelled'  => '#dc2626',
                        ];
                    @endphp
                    @foreach($ordersByStatus as $status => $count)
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle"
                                 style="width:10px;height:10px;
                                        background:{{ $statusColors[$status] ?? '#6b7280' }}">
                            </div>
                            <small class="text-capitalize">{{ $status }}</small>
                        </div>
                        <span class="badge bg-light text-dark border"
                              style="font-size:.72rem">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Top Medicines ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-pills me-2 text-warning"></i>Top Selling Medicines (All Time)</h6>
    </div>
    <div class="card-body p-0">
        @if($topMedicines->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.74rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">#</th>
                        <th>Medicine Name</th>
                        <th class="text-center">Total Orders</th>
                        <th class="text-center">Total Qty Dispensed</th>
                        <th class="text-end pe-3">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($topMedicines as $i => $med)
                <tr>
                    <td class="ps-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:26px;height:26px;background:#eff6ff;color:#2563eb;
                                    font-weight:700;font-size:.75rem">
                            {{ $i+1 }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem">
                            {{ $med->medication_name }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"
                              style="font-size:.72rem">
                            {{ $med->order_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="fw-semibold" style="font-size:.85rem;color:#16a34a">
                            {{ number_format($med->total_qty) }}
                        </span>
                    </td>
                    <td class="text-end pe-3 fw-semibold" style="font-size:.85rem">
                        Rs. {{ number_format($med->total_revenue, 2) }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted">
            <small>No sales data available yet.</small>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
// Monthly Revenue Bar Chart
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const salesData = @json(array_values($monthlySales));

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (Rs.)',
            data: salesData,
            backgroundColor: 'rgba(37,99,235,.15)',
            borderColor: '#2563eb',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rs.' + v.toLocaleString()
                }
            }
        }
    }
});

// Status Pie Chart
const statusLabels = @json($ordersByStatus->keys());
const statusData   = @json($ordersByStatus->values());
const statusColors = {
    pending:'#f59e0b', verified:'#0891b2', processing:'#2563eb',
    ready:'#16a34a', dispatched:'#6b7280', delivered:'#10b981', cancelled:'#dc2626'
};
const colors = statusLabels.map(s => statusColors[s] ?? '#9ca3af');

if (statusData.some(v => v > 0)) {
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{ data: statusData, backgroundColor: colors, borderWidth: 0 }]
        },
        options: {
            cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });
}

// Generate Report type toggle
function toggleReportFields() {
    const t = document.getElementById('reportType').value;
    document.getElementById('fieldDaily').classList.toggle('d-none',   t !== 'daily');
    document.getElementById('fieldMonthly').classList.toggle('d-none', t !== 'monthly');
    document.getElementById('fieldSales').classList.toggle('d-none',   t !== 'sales');
}
</script>
@endpush
