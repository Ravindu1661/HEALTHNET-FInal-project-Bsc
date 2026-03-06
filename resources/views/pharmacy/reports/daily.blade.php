{{-- resources/views/pharmacy/reports/daily.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Daily Report')
@section('page-title', 'Reports')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.reports.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <span class="fw-bold fs-5">Daily Report</span>
    </div>
    <a href="{{ route('pharmacy.reports.export', ['type'=>'daily',
        'start_date' => $date->toDateString(),
        'end_date'   => $date->toDateString()]) }}"
       class="btn btn-outline-success btn-sm rounded-pill px-3">
        <i class="fas fa-download me-1"></i>Export CSV
    </a>
</div>

{{-- ── Date Navigator ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('pharmacy.reports.daily', ['date'=>$prevDate]) }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-chevron-left me-1"></i>
                {{ \Carbon\Carbon::parse($prevDate)->format('d M Y') }}
            </a>
            <form action="{{ route('pharmacy.reports.daily') }}" method="GET"
                  class="d-flex gap-2 align-items-center">
                <input type="date" name="date" class="form-control form-control-sm"
                       value="{{ $date->toDateString() }}"
                       onchange="this.form.submit()">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Go</button>
            </form>
            @if($date->toDateString() < today()->toDateString())
            <a href="{{ route('pharmacy.reports.daily', ['date'=>$nextDate]) }}"
               class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                {{ \Carbon\Carbon::parse($nextDate)->format('d M Y') }}
                <i class="fas fa-chevron-right ms-1"></i>
            </a>
            @else
            <span class="btn btn-outline-secondary btn-sm rounded-pill px-3 disabled">Today</span>
            @endif
        </div>
    </div>
</div>

{{-- ── KPIs ── --}}
<div class="row g-3 mb-4">
    @php
        $kpis = [
            ['label'=>'Total Orders',    'value'=>$totalOrders,     'color'=>'#2563eb', 'bg'=>'#eff6ff', 'icon'=>'fas fa-shopping-bag'],
            ['label'=>'Revenue (Paid)',   'value'=>'Rs. '.number_format($totalRevenue,2), 'color'=>'#16a34a','bg'=>'#f0fdf4', 'icon'=>'fas fa-rupee-sign'],
            ['label'=>'Pending',         'value'=>$pendingOrders,   'color'=>'#d97706', 'bg'=>'#fffbeb', 'icon'=>'fas fa-clock'],
            ['label'=>'Delivered',       'value'=>$deliveredOrders, 'color'=>'#10b981', 'bg'=>'#ecfdf5', 'icon'=>'fas fa-check-circle'],
            ['label'=>'Cancelled',       'value'=>$cancelledOrders, 'color'=>'#dc2626', 'bg'=>'#fef2f2', 'icon'=>'fas fa-times-circle'],
            ['label'=>'Total Items',     'value'=>$totalItems,      'color'=>'#7c3aed', 'bg'=>'#faf5ff', 'icon'=>'fas fa-pills'],
        ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-sm-6 col-xl-2">
        <div class="dashboard-card text-center py-3">
            <i class="{{ $k['icon'] }} mb-2 d-block" style="color:{{ $k['color'] }};font-size:1.2rem"></i>
            <div class="fw-bold" style="font-size:1.3rem;color:{{ $k['color'] }}">{{ $k['value'] }}</div>
            <small class="text-muted">{{ $k['label'] }}</small>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Hourly Chart + Status Breakdown ── --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-chart-line me-2 text-primary"></i>Hourly Orders</h6>
            </div>
            <div class="card-body">
                <canvas id="hourlyChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-layer-group me-2" style="color:#7c3aed"></i>Status Breakdown</h6>
            </div>
            <div class="card-body p-0">
                @php
                    $bdgMap = ['pending'=>'warning','verified'=>'info','processing'=>'primary',
                               'ready'=>'success','dispatched'=>'secondary',
                               'delivered'=>'success','cancelled'=>'danger'];
                @endphp
                @forelse($statusBreakdown as $status => $count)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last?'border-bottom':'' }}">
                    <span class="badge bg-{{ $bdgMap[$status]??'secondary' }} rounded-pill"
                          style="font-size:.72rem;min-width:80px">
                        {{ ucfirst($status) }}
                    </span>
                    <span class="fw-bold" style="font-size:.9rem">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center py-3 text-muted"><small>No orders.</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Orders Table ── --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-list me-2 text-primary"></i>
            Orders on {{ $date->format('d F Y') }}
        </h6>
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
                        <th>Time</th>
                        <th>Patient</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Payment</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                @php
                    $b = match($order->status) {
                        'pending'=>'warning','verified'=>'info','processing'=>'primary',
                        'ready'=>'success','dispatched'=>'secondary',
                        'delivered'=>'success','cancelled'=>'danger',default=>'secondary'
                    };
                @endphp
                <tr>
                    <td class="ps-3">
                        <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                           class="fw-semibold text-primary text-decoration-none"
                           style="font-size:.83rem">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td style="font-size:.8rem">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                    </td>
                    <td style="font-size:.82rem">
                        {{ optional($order->patient)->first_name }}
                        {{ optional($order->patient)->last_name }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border" style="font-size:.7rem">
                            {{ $order->items->count() }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.83rem">
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
            <h6>No orders on {{ $date->format('d F Y') }}</h6>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const hourlyData = @json($hourlyOrders);
const labels  = Array.from({length:24}, (_,i) => String(i).padStart(2,'0')+':00');
const counts  = labels.map((_,i) => (hourlyData[String(i).padStart(2,'0')] ?? {count:0}).count);
const revenue = labels.map((_,i) => (hourlyData[String(i).padStart(2,'0')] ?? {revenue:0}).revenue);

new Chart(document.getElementById('hourlyChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label:'Orders', data:counts, backgroundColor:'rgba(37,99,235,.2)',
              borderColor:'#2563eb', borderWidth:1.5, borderRadius:4, yAxisID:'y' },
            { label:'Revenue', data:revenue, type:'line', borderColor:'#16a34a',
              backgroundColor:'rgba(22,163,74,.1)', borderWidth:2,
              pointRadius:3, fill:true, yAxisID:'y1' }
        ]
    },
    options: {
        responsive:true,
        plugins:{ legend:{ position:'bottom' } },
        scales:{
            y:  { beginAtZero:true, position:'left',  title:{display:true,text:'Orders'} },
            y1: { beginAtZero:true, position:'right', title:{display:true,text:'Revenue'},
                  grid:{ drawOnChartArea:false },
                  ticks:{ callback: v => 'Rs.'+v.toLocaleString() } }
        }
    }
});
</script>
@endpush
