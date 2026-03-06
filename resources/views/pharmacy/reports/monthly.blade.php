{{-- resources/views/pharmacy/reports/monthly.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Monthly Report')
@section('page-title', 'Reports')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.reports.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <span class="fw-bold fs-5">Monthly Report — {{ $dt->format('F Y') }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.reports.export', ['type'=>'monthly',
            'start_date' => $dt->copy()->startOfMonth()->toDateString(),
            'end_date'   => $dt->copy()->endOfMonth()->toDateString()]) }}"
           class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="fas fa-download me-1"></i>Export CSV
        </a>
    </div>
</div>

{{-- Month Selector --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.reports.monthly') }}" method="GET"
              class="d-flex gap-2 align-items-center">
            <input type="month" name="month" class="form-control form-control-sm"
                   style="max-width:200px" value="{{ $month }}"
                   onchange="this.form.submit()">
            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-search me-1"></i>View
            </button>
            <a href="{{ route('pharmacy.reports.monthly', ['month'=>$prevMonth]) }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-chevron-left me-1"></i>Prev
            </a>
        </form>
    </div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    @php
        $kpis = [
            ['label'=>'Total Orders',     'value'=>$totalOrders,     'color'=>'#2563eb'],
            ['label'=>'Revenue',          'value'=>'Rs. '.number_format($totalRevenue,0), 'color'=>'#16a34a'],
            ['label'=>'Avg Order Value',  'value'=>'Rs. '.number_format($averageOrderValue,0),'color'=>'#7c3aed'],
            ['label'=>'Paid Orders',      'value'=>$paidOrders,      'color'=>'#10b981'],
            ['label'=>'Unpaid Orders',    'value'=>$unpaidOrders,    'color'=>'#d97706'],
            ['label'=>'Cancelled',        'value'=>$cancelledOrders, 'color'=>'#dc2626'],
        ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-sm-6 col-xl-2">
        <div class="dashboard-card text-center py-3">
            <div class="fw-bold" style="font-size:1.4rem;color:{{ $k['color'] }}">{{ $k['value'] }}</div>
            <small class="text-muted">{{ $k['label'] }}</small>
        </div>
    </div>
    @endforeach
</div>

{{-- Prev Month Compare --}}
<div class="alert border-0 mb-4 d-flex align-items-center gap-3"
     style="background:#f8fafc;border-radius:10px">
    <i class="fas fa-chart-line text-primary fa-lg"></i>
    <div>
        <strong>vs Last Month:</strong>
        Revenue
        <span class="fw-bold {{ $totalRevenue >= $prevRevenue ? 'text-success':'text-danger' }}">
            {{ $totalRevenue >= $prevRevenue ? '▲' : '▼' }}
            Rs. {{ number_format(abs($totalRevenue - $prevRevenue), 0) }}
        </span>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Orders
        <span class="fw-bold {{ $totalOrders >= $prevOrders ? 'text-success':'text-danger' }}">
            {{ $totalOrders >= $prevOrders ? '▲' : '▼' }}
            {{ abs($totalOrders - $prevOrders) }}
        </span>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Daily Chart --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar me-2 text-primary"></i>Daily Breakdown</h6>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" height="100"></canvas>
            </div>
        </div>
    </div>
    {{-- Top Medicines --}}
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-pills me-2 text-warning"></i>Top Medicines</h6>
            </div>
            <div class="card-body p-0">
                @forelse($topMedicines as $i => $med)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last?'border-bottom':'' }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:22px;height:22px;background:#eff6ff;
                                    color:#2563eb;font-size:.68rem;font-weight:700">
                            {{ $i+1 }}
                        </div>
                        <small class="fw-semibold" style="font-size:.8rem">
                            {{ Str::limit($med->medication_name, 22) }}
                        </small>
                    </div>
                    <small class="text-muted" style="font-size:.75rem">{{ $med->total_qty }} qty</small>
                </div>
                @empty
                <div class="text-center py-3 text-muted"><small>No data.</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Orders Table --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-list me-2 text-primary"></i>All Orders This Month</h6>
        <span class="badge bg-light text-dark border">{{ $totalOrders }}</span>
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
                        <th class="text-end">Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Payment</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders->take(50) as $order)
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
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M, h:i A') }}
                    </td>
                    <td style="font-size:.82rem">
                        {{ optional($order->patient)->first_name }}
                        {{ optional($order->patient)->last_name }}
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.82rem">
                        Rs. {{ number_format($order->total_amount,2) }}
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
        @if($orders->count() > 50)
        <div class="text-center py-3 border-top">
            <small class="text-muted">
                Showing 50 of {{ $orders->count() }}.
                <a href="{{ route('pharmacy.reports.export', ['type'=>'monthly',
                    'start_date' => $dt->copy()->startOfMonth()->toDateString(),
                    'end_date'   => $dt->copy()->endOfMonth()->toDateString()]) }}">
                    Export CSV
                </a> to see all.
            </small>
        </div>
        @endif
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-40"></i>
            <h6>No orders in {{ $dt->format('F Y') }}</h6>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const dailyChart = @json($dailyChart);
const dLabels  = Object.keys(dailyChart).map(d => 'Day '+d);
const dCounts  = Object.values(dailyChart).map(v => v.count);
const dRevenue = Object.values(dailyChart).map(v => v.revenue);

new Chart(document.getElementById('dailyChart'), {
    type:'bar',
    data:{
        labels:dLabels,
        datasets:[
            {label:'Orders', data:dCounts, backgroundColor:'rgba(37,99,235,.2)',
             borderColor:'#2563eb', borderWidth:1.5, borderRadius:4, yAxisID:'y'},
            {label:'Revenue', data:dRevenue, type:'line', borderColor:'#16a34a',
             backgroundColor:'rgba(22,163,74,.08)', borderWidth:2,
             pointRadius:2.5, fill:true, yAxisID:'y1'}
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
