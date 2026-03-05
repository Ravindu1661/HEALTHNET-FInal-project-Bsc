{{-- resources/views/pharmacy/dashboard.blade.php --}}
@extends('pharmacy.layouts.master')

@section('title', 'Pharmacy Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ── Stat Summary Cards ── */
.stat-summary {
    display: flex;
    align-items: center;
    padding: 1.1rem 1.25rem;
    background: #fff;
    border-radius: 12px;
    border-left: 4px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    transition: transform .15s, box-shadow .15s;
    height: 100%;
}
.stat-summary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,.1);
}
.stat-summary .stat-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    margin-right: 1rem;
    flex-shrink: 0;
}
.stat-summary .stat-details h6 { margin: 0; font-size: .8rem; color: #6b7280; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }
.stat-summary .stat-details h4 { margin: .15rem 0 .25rem; font-size: 1.6rem; font-weight: 700; color: #111827; line-height: 1; }
.stat-summary .stat-details a  { font-size: .75rem; }

/* Colour variants */
.stat-summary-primary   { border-color: #2563eb; }
.stat-summary-primary   .stat-icon { background: #dbeafe; color: #1d4ed8; }
.stat-summary-success   { border-color: #16a34a; }
.stat-summary-success   .stat-icon { background: #dcfce7; color: #15803d; }
.stat-summary-warning   { border-color: #f59e0b; }
.stat-summary-warning   .stat-icon { background: #fffbeb; color: #d97706; }
.stat-summary-info      { border-color: #0891b2; }
.stat-summary-info      .stat-icon { background: #cffafe; color: #0e7490; }
.stat-summary-danger    { border-color: #dc2626; }
.stat-summary-danger    .stat-icon { background: #fee2e2; color: #b91c1c; }
.stat-summary-secondary { border-color: #64748b; }
.stat-summary-secondary .stat-icon { background: #f1f5f9; color: #475569; }

/* ── Order status mini-cards ── */
.status-mini-card {
    border-radius: 12px;
    padding: 1rem .75rem;
    text-align: center;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
    height: 100%;
    border-top: 3px solid transparent;
    transition: transform .15s;
}
.status-mini-card:hover { transform: translateY(-2px); }
.status-mini-card .mini-count { font-size: 1.6rem; font-weight: 700; line-height: 1; }
.status-mini-card .mini-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin-top: .25rem; }

/* ── Dashboard card ── */
.dashboard-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    border: none;
    overflow: hidden;
}
.dashboard-card .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    padding: .85rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dashboard-card .card-header h6 { margin: 0; font-weight: 600; font-size: .9rem; color: #374151; }
.dashboard-card .card-body { padding: 1.25rem; }

/* ── Today's order row ── */
.today-order-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem .85rem;
    border-radius: 8px;
    background: #f8fafc;
    margin-bottom: .5rem;
    border: 1px solid #e5e7eb;
    transition: background .15s;
}
.today-order-item:hover { background: #eff6ff; }

/* ── Avatar circle ── */
.avatar-circle {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 600;
    background: #dbeafe; color: #1d4ed8;
    flex-shrink: 0; overflow: hidden;
}
.avatar-circle img { width: 100%; height: 100%; object-fit: cover; }

/* ── Rating stars ── */
.star-filled { color: #f59e0b; }
.star-empty  { color: #d1d5db; }

/* ── Chart card ── */
#salesChart { max-height: 300px; }

/* ── Welcome gradient ── */
.welcome-card {
    background: linear-gradient(135deg, #1d4ed8 0%, #0891b2 100%);
    border-radius: 12px;
    color: #fff;
    padding: 1.5rem;
    box-shadow: 0 4px 16px rgba(29,78,216,.25);
}
.welcome-card p { opacity: .85; }
</style>
@endpush

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Welcome Card ── --}}
<div class="welcome-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="fas fa-pills me-2 opacity-75"></i>
                Welcome back, {{ $pharmacy->name ?? 'Pharmacy' }}!
            </h4>
            <p class="mb-0">Monitor your pharmacy performance and manage orders efficiently.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('pharmacy.orders.create') }}" class="btn btn-light fw-semibold px-4">
                <i class="fas fa-plus-circle me-1 text-primary"></i> New Order
            </a>
        </div>
    </div>
</div>

{{-- ── Row 1: Primary Stats ── --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-primary">
            <div class="stat-icon"><i class="fas fa-pills"></i></div>
            <div class="stat-details">
                <h6>Total Medicines</h6>
                <h4>{{ number_format($totalMedicines ?? 0) }}</h4>
                <a href="{{ route('pharmacy.medicines.index') }}" class="text-primary">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-success">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-details">
                <h6>Total Orders</h6>
                <h4>{{ number_format($totalOrders ?? 0) }}</h4>
                <a href="{{ route('pharmacy.orders.index') }}" class="text-success">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-details">
                <h6>Pending Orders</h6>
                <h4>{{ number_format($pendingOrders ?? 0) }}</h4>
                <a href="{{ route('pharmacy.orders.index', ['status' => 'pending']) }}" class="text-warning">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-info">
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
            <div class="stat-details">
                <h6>Total Revenue</h6>
                <h4 style="font-size:1.25rem">Rs.&nbsp;{{ number_format($totalRevenue ?? 0, 2) }}</h4>
                <a href="{{ route('pharmacy.reports.sales') }}" class="text-info">
                    View Report <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 2: Secondary Stats ── --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-secondary">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-details">
                <h6>Total Patients</h6>
                <h4>{{ number_format($totalPatients ?? 0) }}</h4>
                <a href="{{ route('pharmacy.patients.index') }}" class="text-secondary">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-danger">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-details">
                <h6>Out of Stock</h6>
                <h4>{{ number_format($outOfStockCount ?? 0) }}</h4>
                <a href="{{ route('pharmacy.inventory.out-of-stock') }}" class="text-danger">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-details">
                <h6>Low Stock</h6>
                <h4>{{ number_format($lowStockCount ?? 0) }}</h4>
                <a href="{{ route('pharmacy.inventory.low-stock') }}" class="text-warning">
                    View All <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-info">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-details">
                <h6>Average Rating</h6>
                <h4>{{ number_format($avgRating ?? 0, 1) }} <small class="text-muted fw-normal" style="font-size:.9rem">/ 5.0</small></h4>
                <a href="{{ route('pharmacy.ratings.index') }}" class="text-info">
                    View Reviews <i class="fas fa-arrow-right ms-1" style="font-size:.65rem"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Order Status Mini Cards ── --}}
@php
    $statusCards = [
        ['key' => 'verified',   'label' => 'Verified',   'color' => '#0891b2', 'bg' => '#cffafe', 'icon' => 'fa-check-circle'],
        ['key' => 'processing', 'label' => 'Processing', 'color' => '#2563eb', 'bg' => '#dbeafe', 'icon' => 'fa-cog'],
        ['key' => 'ready',      'label' => 'Ready',      'color' => '#16a34a', 'bg' => '#dcfce7', 'icon' => 'fa-check-double'],
        ['key' => 'dispatched', 'label' => 'Dispatched', 'color' => '#64748b', 'bg' => '#f1f5f9', 'icon' => 'fa-truck'],
        ['key' => 'delivered',  'label' => 'Delivered',  'color' => '#15803d', 'bg' => '#bbf7d0', 'icon' => 'fa-box-open'],
        ['key' => 'cancelled',  'label' => 'Cancelled',  'color' => '#b91c1c', 'bg' => '#fee2e2', 'icon' => 'fa-times-circle'],
    ];
@endphp
<div class="row g-3 mb-4">
    @foreach($statusCards as $sc)
    <div class="col-xl-2 col-md-4 col-6">
        <div class="status-mini-card" style="border-top-color: {{ $sc['color'] }}">
            <div style="width:36px;height:36px;border-radius:8px;background:{{ $sc['bg'] }};display:flex;align-items:center;justify-content:center;margin:0 auto .5rem;">
                <i class="fas {{ $sc['icon'] }}" style="color:{{ $sc['color'] }};font-size:.95rem"></i>
            </div>
            <div class="mini-count" style="color:{{ $sc['color'] }}">
                {{ $orderStats[$sc['key']] ?? 0 }}
            </div>
            <div class="mini-label">{{ $sc['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Recent Orders + Low Stock ── --}}
<div class="row g-3 mb-4">

    {{-- Recent Orders Table --}}
    <div class="col-lg-8">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-shopping-cart me-2 text-primary"></i>Recent Orders</h6>
                <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8fafc;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280">
                            <tr>
                                <th class="ps-3 py-3">Order #</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-center pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            @php
                                $badgeMap = [
                                    'pending'    => ['warning',   'Pending'],
                                    'verified'   => ['info',      'Verified'],
                                    'processing' => ['primary',   'Processing'],
                                    'ready'      => ['success',   'Ready'],
                                    'dispatched' => ['secondary', 'Dispatched'],
                                    'delivered'  => ['success',   'Delivered'],
                                    'cancelled'  => ['danger',    'Cancelled'],
                                ];
                                [$bdg, $lbl] = $badgeMap[$order->status ?? ''] ?? ['secondary', ucfirst($order->status ?? 'N/A')];
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-semibold text-primary" style="font-size:.85rem">
                                        {{ $order->order_number ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            @if(!empty($order->patient_image))
                                                <img src="{{ asset('storage/'.$order->patient_image) }}" alt="patient">
                                            @else
                                                {{ strtoupper(substr($order->patient_name ?? 'U', 0, 1)) }}
                                            @endif
                                        </div>
                                        <span style="font-size:.85rem">{{ $order->patient_name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php $dt = \Carbon\Carbon::parse($order->created_at); @endphp
                                    <span style="font-size:.82rem">{{ $dt->format('d M Y') }}</span><br>
                                    <span class="text-muted" style="font-size:.75rem">{{ $dt->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold" style="font-size:.85rem">
                                        Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $bdg }} rounded-pill">{{ $lbl }}</span>
                                </td>
                                <td class="text-center pe-3">
                                    <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-info rounded-pill px-3"
                                       data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block text-muted opacity-50"></i>
                    <h6 class="fw-semibold">No recent orders</h6>
                    <p class="small mb-0">Orders will appear here once customers place them.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    <div class="col-lg-4">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</h6>
                <a href="{{ route('pharmacy.inventory.low-stock') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if(isset($lowStockMedicines) && $lowStockMedicines->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($lowStockMedicines as $med)
                    <li class="list-group-item px-3 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div style="min-width:0">
                                <div class="fw-semibold text-truncate" style="font-size:.87rem">
                                    {{ $med->name ?? 'N/A' }}
                                </div>
                                <small class="text-muted">{{ $med->generic_name ?? '' }}</small>
                            </div>
                            <span class="badge ms-2 rounded-pill bg-{{ ($med->stock_quantity ?? 0) == 0 ? 'danger' : 'warning' }}">
                                {{ $med->stock_quantity ?? 0 }} units
                            </span>
                        </div>
                        <a href="{{ route('pharmacy.medicines.edit', $med->id) }}"
                           class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-plus-circle me-1"></i> Update Stock
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-3x mb-3 d-block text-success opacity-75"></i>
                    <p class="mb-0 small fw-semibold">All medicines are well stocked</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Today's Orders ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6>
            <i class="fas fa-calendar-day me-2 text-primary"></i>Today's Orders
            <span class="badge bg-primary rounded-pill ms-2" style="font-size:.72rem">
                {{ isset($todayOrders) ? $todayOrders->count() : 0 }}
            </span>
        </h6>
        <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body" style="max-height:400px;overflow-y:auto">
        @forelse(isset($todayOrders) ? $todayOrders : [] as $order)
        @php
            $todayStatusMap = [
                'pending'    => ['warning',   'fa-clock',        'Pending'],
                'verified'   => ['info',      'fa-check-circle', 'Verified'],
                'processing' => ['primary',   'fa-cog',          'Processing'],
                'ready'      => ['success',   'fa-check-double', 'Ready'],
                'dispatched' => ['secondary', 'fa-truck',        'Dispatched'],
                'delivered'  => ['success',   'fa-box-open',     'Delivered'],
                'cancelled'  => ['danger',    'fa-times-circle', 'Cancelled'],
            ];
            [$tcls, $ticon, $tlbl] = $todayStatusMap[$order->status ?? 'pending'] ?? ['secondary','fa-circle','Unknown'];
            $pName = $order->patient_name ?? 'Unknown Patient';
        @endphp
        <div class="today-order-item">
            {{-- Status Badge --}}
            <div class="flex-shrink-0">
                <span class="badge bg-{{ $tcls }} rounded-pill" style="font-size:.72rem;min-width:88px;text-align:center">
                    <i class="fas {{ $ticon }} me-1"></i>{{ $tlbl }}
                </span>
            </div>

            {{-- Avatar + Name --}}
            <div class="avatar-circle flex-shrink-0">
                @if(!empty($order->patient_image))
                    <img src="{{ asset('storage/'.$order->patient_image) }}" alt="patient">
                @else
                    {{ strtoupper(substr($pName, 0, 1)) }}
                @endif
            </div>

            {{-- Order Info --}}
            <div class="flex-grow-1" style="min-width:0">
                <div class="fw-semibold text-truncate" style="font-size:.85rem">
                    {{ $order->order_number ?? 'N/A' }}
                </div>
                <div class="text-muted text-truncate" style="font-size:.76rem">{{ $pName }}</div>
            </div>

            {{-- Amount + Time --}}
            <div class="flex-shrink-0 text-end">
                <div class="fw-semibold" style="font-size:.85rem">
                    Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                </div>
                <div class="text-muted" style="font-size:.73rem">
                    {{ \Carbon\Carbon::parse($order->order_date)->format('h:i A') }}
                </div>
            </div>

            {{-- Action --}}
            <a href="{{ route('pharmacy.orders.show', $order->id) }}"
               class="btn btn-sm btn-outline-secondary rounded-pill flex-shrink-0 px-3"
               style="font-size:.73rem">
                View
            </a>
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-50"></i>
            <p class="mb-0 fw-semibold">No orders today</p>
            <small>New orders will appear here automatically.</small>
        </div>
        @endforelse
    </div>
</div>

{{-- ── Monthly Sales Chart ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-chart-line me-2 text-primary"></i>Monthly Sales Overview ({{ date('Y') }})</h6>
        <span class="badge bg-light text-dark border" style="font-size:.75rem">
            <i class="fas fa-calendar-alt me-1 text-primary"></i>{{ date('Y') }}
        </span>
    </div>
    <div class="card-body">
        <canvas id="salesChart"></canvas>
    </div>
</div>

{{-- ── Recent Reviews ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-star me-2 text-warning"></i>Recent Reviews</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-warning" style="font-size:.95rem">
                {{ number_format($avgRating ?? 0, 1) }}
                <small class="text-muted fw-normal" style="font-size:.75rem">/ 5.0 ({{ $totalRatings ?? 0 }})</small>
            </span>
            <a href="{{ route('pharmacy.ratings.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(isset($recentRatings) && $recentRatings->count() > 0)
        <div class="row g-3">
            @foreach($recentRatings as $rating)
            <div class="col-md-6">
                <div class="p-3 rounded-3 border" style="background:#fafbfc">
                    <div class="d-flex align-items-start gap-3">
                        {{-- Avatar --}}
                        <div class="avatar-circle flex-shrink-0" style="width:40px;height:40px;font-size:.85rem">
                            @if(!empty($rating->patient_image))
                                <img src="{{ asset('storage/'.$rating->patient_image) }}" alt="patient">
                            @else
                                {{ strtoupper(substr($rating->patient_name ?? 'A', 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong style="font-size:.87rem">
                                    {{ $rating->patient_name ?? 'Anonymous' }}
                                </strong>
                                <small class="text-muted ms-2 flex-shrink-0">
                                    {{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}
                                </small>
                            </div>
                            {{-- Stars --}}
                            <div class="my-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($rating->rating ?? 0) ? 'star-filled' : 'star-empty' }}"
                                       style="font-size:.75rem"></i>
                                @endfor
                                <span class="ms-1 text-muted" style="font-size:.75rem">
                                    ({{ $rating->rating ?? 0 }}/5)
                                </span>
                            </div>
                            {{-- Review text — column: 'review' per migration ✅ --}}
                            @if(!empty($rating->review))
                            <p class="text-muted mb-0" style="font-size:.8rem;line-height:1.5">
                                "{{ \Illuminate\Support\Str::limit($rating->review, 100) }}"
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-comments fa-3x mb-3 d-block opacity-50"></i>
            <p class="mb-0 fw-semibold">No reviews yet</p>
            <small>Customer reviews will appear here once submitted.</small>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));

    // ── Sales + Orders Dual-Axis Chart ──
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    @php
        $chartSalesData  = $salesData    ?? array_fill(0, 12, 0);
        $chartOrdersData = $monthlyOrders ?? array_fill(0, 12, 0);
    @endphp

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
                {
                    label: 'Revenue (Rs.)',
                    data: @json($chartSalesData),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    yAxisID: 'y',
                },
                {
                    label: 'Orders',
                    data: @json($chartOrdersData),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,.06)',
                    tension: 0.4,
                    fill: false,
                    pointBackgroundColor: '#16a34a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    yAxisID: 'y1',
                    borderDash: [5, 3],
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 3.5,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? ' Revenue: Rs. ' + ctx.parsed.y.toLocaleString()
                            : ' Orders: ' + ctx.parsed.y
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,.04)' },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    type: 'linear', position: 'left', beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.04)' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => 'Rs. ' + (v >= 1000 ? (v/1000).toFixed(1)+'k' : v)
                    }
                },
                y1: {
                    type: 'linear', position: 'right', beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { stepSize: 1, font: { size: 11 } }
                }
            }
        }
    });
});
</script>
@endpush
