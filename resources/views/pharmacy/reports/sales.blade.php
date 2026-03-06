{{-- resources/views/pharmacy/reports/sales.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title','Sales Report')
@section('page-title','Reports')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.reports.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <span class="fw-bold fs-5">Sales Report</span>
    </div>
    <a href="{{ route('pharmacy.reports.export', ['type'=>'sales',
        'start_date'=>$startDate->toDateString(),
        'end_date'  =>$endDate->toDateString()]) }}"
       class="btn btn-outline-success btn-sm rounded-pill px-3">
        <i class="fas fa-download me-1"></i>Export CSV
    </a>
</div>

{{-- Date Range Filter --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.reports.sales') }}" method="GET"
              class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">From</label>
                <input type="date" name="start_date" class="form-control form-control-sm"
                       value="{{ $startDate->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">To</label>
                <input type="date" name="end_date" class="form-control form-control-sm"
                       value="{{ $endDate->toDateString() }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-4 text-end">
                {{-- Quick ranges --}}
                @php
                    $ranges = [
                        'This Month'  => [now()->startOfMonth()->toDateString(), today()->toDateString()],
                        'Last Month'  => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
                        'Last 7 Days' => [now()->subDays(6)->toDateString(), today()->toDateString()],
                        'This Year'   => [now()->startOfYear()->toDateString(), today()->toDateString()],
                    ];
                @endphp
                @foreach($ranges as $label => [$s,$e])
                <a href="{{ route('pharmacy.reports.sales', ['start_date'=>$s,'end_date'=>$e]) }}"
                   class="btn btn-outline-secondary btn-sm rounded-pill px-2 me-1"
                   style="font-size:.72rem">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </form>
    </div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.8rem;font-weight:700;color:#2563eb">{{ $totalOrders }}</div>
            <small class="text-muted">Total Orders</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.5rem;font-weight:700;color:#16a34a">
                Rs. {{ number_format($totalRevenue, 0) }}
            </div>
            <small class="text-muted">Revenue (Paid)</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.5rem;font-weight:700;color:#7c3aed">
                Rs. {{ number_format($avgOrderValue, 0) }}
            </div>
            <small class="text-muted">Avg Order Value</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.5rem;font-weight:700;color:#d97706">
                Rs. {{ number_format($totalDeliveryFee, 0) }}
            </div>
            <small class="text-muted">Delivery Fees</small>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Trend Chart --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-line me-2 text-primary"></i>Daily Sales Trend</h6>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Payment Method --}}
    <div class="col-lg-4">
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-credit-card me-2 text-info"></i>Payment Methods</h6>
            </div>
            <div class="card-body p-0">
                @foreach($byPaymentMethod as $method => $data)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last?'border-bottom':'' }}">
                    <div>
                        <div class="fw-semibold" style="font-size:.82rem">
                            {{ ucwords(str_replace('_',' ', $method ?? 'N/A')) }}
                        </div>
                        <small class="text-muted">{{ $data['count'] }} orders</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success" style="font-size:.82rem">
                            Rs. {{ number_format($data['revenue'],0) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Top Medicines --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-pills me-2 text-warning"></i>Top Selling Medicines</h6>
    </div>
    <div class="card-body p-0">
        @if($topMedicines->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.74rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">#</th>
                        <th>Medicine</th>
                        <th class="text-center">Order Count</th>
                        <th class="text-center">Qty Dispensed</th>
                        <th class="text-end pe-3">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($topMedicines as $i => $med)
                <tr>
                    <td class="ps-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:26px;height:26px;background:#eff6ff;
                                    color:#2563eb;font-weight:700;font-size:.75rem">
                            {{ $i+1 }}
                        </div>
                    </td>
                    <td class="fw-semibold" style="font-size:.83rem">{{ $med->medication_name }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"
                              style="font-size:.7rem">
                            {{ $med->order_count }}
                        </span>
                    </td>
                    <td class="text-center fw-bold" style="color:#16a34a;font-size:.83rem">
                        {{ number_format($med->total_qty) }}
                    </td>
                    <td class="text-end pe-3 fw-semibold" style="font-size:.83rem">
                        Rs. {{ number_format($med->total_revenue, 2) }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted"><small>No data in this range.</small></div>
        @endif
    </div>
</div>

{{-- Orders Table --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-list me-2 text-primary"></i>Orders in Range</h6>
        <span class="badge bg-light text-dark border">{{ $orders->count() }}</span>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.74rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">Order #</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Method</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Payment</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders->take(100) as $order)
                @php
                    $b = match($order->status){
                        'pending'=>'warning','verified'=>'info','processing'=>'primary',
                        'ready'=>'success','dispatched'=>'secondary',
                        'delivered'=>'success','cancelled'=>'danger',default=>'secondary'
                    };
                @endphp
                <tr>
                    <td class="ps-3">
                        <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                           class="fw-semibold text-primary text-decoration-none"
                           style="font-size:.82rem">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td style="font-size:.8rem">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                    </td>
                    <td style="font-size:.82rem">
                        {{ optional($order->patient)->first_name }}
                        {{ optional($order->patient)->last_name }}
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-size:.7rem">
                            {{ ucwords(str_replace('_',' ',$order->payment_method ?? '–')) }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.82rem">
                        Rs. {{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $b }} rounded-pill" style="font-size:.7rem">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-center pe-3">
                        <span class="badge bg-{{ $order->payment_status==='paid'?'success':'danger' }}
                              rounded-pill" style="font-size:.7rem">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-40"></i>
            <h6>No orders in selected date range.</h6>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const trend   = @json($dailyTrend);
const tLabels = Object.keys(trend);
const tCounts = tLabels.map(d => trend[d].count);
const tRev    = tLabels.map(d => trend[d].revenue);

new Chart(document.getElementById('trendChart'), {
    type:'line',
    data:{
        labels:tLabels,
        datasets:[
            {label:'Orders', data:tCounts, borderColor:'#2563eb',
             backgroundColor:'rgba(37,99,235,.1)', borderWidth:2,
             pointRadius:3, fill:true, yAxisID:'y'},
            {label:'Revenue', data:tRev, borderColor:'#16a34a',
             backgroundColor:'rgba(22,163,74,.08)', borderWidth:2,
             pointRadius:3, fill:true, yAxisID:'y1'}
        ]
    },
    options:{
        responsive:true,
        plugins:{legend:{position:'bottom'}},
        scales:{
            y: {beginAtZero:true, position:'left'},
            y1:{beginAtZero:true, position:'right', grid:{drawOnChartArea:false},
                ticks:{callback:v=>'Rs.'+v.toLocaleString()}}
        }
    }
});
</script>
@endpush
