{{-- resources/views/pharmacy/dashboard.blade.php --}}
@extends('pharmacy.layouts.master')

@section('title', 'Pharmacy Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Success/Error Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Welcome Card --}}
<div class="dashboard-card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-2">
                    <i class="fas fa-pills me-2 text-primary"></i>
                    Welcome back, <strong>{{ isset($pharmacy) ? $pharmacy->name : 'Pharmacy' }}</strong>!
                </h4>
                <p class="text-muted mb-0">Monitor your pharmacy performance and manage orders efficiently</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('pharmacy.orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> New Order
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Summary Cards --}}
<div class="row g-3 mb-4">
    {{-- Total Medicines --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-primary">
            <div class="stat-icon">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-details">
                <h6>Total Medicines</h6>
                <h4>{{ isset($totalMedicines) ? number_format($totalMedicines) : 0 }}</h4>
                <a href="{{ route('pharmacy.medicines.index') }}" class="small text-primary">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total Orders --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-success">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-details">
                <h6>Total Orders</h6>
                <h4>{{ isset($totalOrders) ? number_format($totalOrders) : 0 }}</h4>
                <a href="{{ route('pharmacy.orders.index') }}" class="small text-success">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Pending Orders --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h6>Pending Orders</h6>
                <h4>{{ isset($pendingOrders) ? number_format($pendingOrders) : 0 }}</h4>
                <a href="{{ route('pharmacy.orders.index', ['status' => 'pending']) }}" class="small text-warning">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total Revenue --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-info">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-details">
                <h6>Total Revenue</h6>
                <h4>Rs. {{ isset($totalRevenue) ? number_format($totalRevenue, 2) : '0.00' }}</h4>
                <a href="{{ route('pharmacy.reports.sales') }}" class="small text-info">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Total Patients --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-secondary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h6>Total Patients</h6>
                <h4>{{ isset($totalPatients) ? number_format($totalPatients) : 0 }}</h4>
                <a href="{{ route('pharmacy.patients.index') }}" class="small text-secondary">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Out of Stock --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-danger">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-details">
                <h6>Out of Stock</h6>
                <h4>{{ isset($outOfStockMedicines) ? number_format($outOfStockMedicines) : 0 }}</h4>
                <a href="{{ route('pharmacy.inventory.out-of-stock') }}" class="small text-danger">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-details">
                <h6>Low Stock</h6>
                <h4>{{ isset($lowStockMedicines) ? $lowStockMedicines->count() : 0 }}</h4>
                <a href="{{ route('pharmacy.inventory.low-stock') }}" class="small text-warning">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Average Rating --}}
    <div class="col-xl-3 col-md-6">
        <div class="stat-summary stat-summary-info">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-details">
                <h6>Average Rating</h6>
                <h4>
                    {{ isset($pharmacy) && isset($pharmacy->rating) ? number_format($pharmacy->rating, 1) : '0.0' }}
                    <small class="text-muted">/ 5.0</small>
                </h4>
                <a href="{{ route('pharmacy.ratings.index') }}" class="small text-info">
                    View Reviews <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders & Low Stock Grid --}}
<div class="row g-3 mb-4">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-shopping-cart me-2"></i>Recent Orders</h6>
                <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="data-table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ isset($order->order_number) ? $order->order_number : 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                <img src="{{ asset('images/default-avatar.png') }}"
                                                     alt="Patient"
                                                     class="rounded-circle"
                                                     width="30"
                                                     height="30">
                                            </div>
                                            <div>
                                                <strong>
                                                    @if(isset($order->patient))
                                                        {{ isset($order->patient->first_name) ? $order->patient->first_name : 'N/A' }}
                                                        {{ isset($order->patient->last_name) ? $order->patient->last_name : '' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if(isset($order->created_at))
                                            <small>{{ $order->created_at->format('d M Y') }}</small><br>
                                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                        @else
                                            <small class="text-muted">N/A</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Rs. {{ isset($order->total_amount) ? number_format($order->total_amount, 2) : '0.00' }}</strong>
                                    </td>
                                    <td>
                                        @if(isset($order->status))
                                            @php
                                                $statusBadges = [
                                                    'pending' => 'warning',
                                                    'verified' => 'info',
                                                    'processing' => 'primary',
                                                    'ready' => 'success',
                                                    'dispatched' => 'info',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $badge = isset($statusBadges[$order->status]) ? $statusBadges[$order->status] : 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($order->id))
                                            <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="View Details"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <h5>No recent orders</h5>
                        <p>Orders will appear here once customers place them</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Low Stock Medicines --}}
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-exclamation-circle me-2"></i>Low Stock Alert</h6>
                <a href="{{ route('pharmacy.inventory.low-stock') }}" class="btn btn-sm btn-outline-warning">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                @if(isset($lowStockMedicines) && $lowStockMedicines->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockMedicines as $medicine)
                        <div class="list-group-item px-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="d-block">{{ isset($medicine->name) ? $medicine->name : 'N/A' }}</strong>
                                    <small class="text-muted">{{ isset($medicine->generic_name) ? $medicine->generic_name : 'N/A' }}</small>
                                </div>
                                <span class="badge bg-{{ isset($medicine->stock_quantity) && $medicine->stock_quantity == 0 ? 'danger' : 'warning' }}">
                                    {{ isset($medicine->stock_quantity) ? $medicine->stock_quantity : 0 }} units
                                </span>
                            </div>
                            @if(isset($medicine->id))
                                <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
                                   class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-plus-circle"></i> Update Stock
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                        <p class="mb-0">All medicines are well stocked</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Monthly Sales Chart --}}
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-chart-line me-2"></i>Monthly Sales Overview ({{ date('Y') }})</h6>
    </div>
    <div class="card-body">
        <canvas id="salesChart" height="80"></canvas>
    </div>
</div>

{{-- Recent Ratings --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-star me-2"></i>Recent Reviews</h6>
        <a href="{{ route('pharmacy.ratings.index') }}" class="btn btn-sm btn-outline-primary">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        @if(isset($recentRatings) && $recentRatings->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($recentRatings as $rating)
                <div class="list-group-item px-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle fa-2x text-muted me-2"></i>
                            <div>
                                <strong>
                                    @if(isset($rating->patient))
                                        {{ isset($rating->patient->first_name) ? $rating->patient->first_name : 'Anonymous' }}
                                        {{ isset($rating->patient->last_name) ? $rating->patient->last_name : '' }}
                                    @else
                                        Anonymous
                                    @endif
                                </strong>
                                <div class="text-warning">
                                    @if(isset($rating->rating))
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rating->rating ? '' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="text-dark ms-1">({{ $rating->rating }}/5)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(isset($rating->created_at))
                            <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                        @endif
                    </div>
                    @if(isset($rating->comment) && !empty($rating->comment))
                        <p class="text-muted mb-0 small">{{ Str::limit($rating->comment, 120) }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-comments fa-3x mb-2 d-block"></i>
                <p class="mb-0">No reviews yet</p>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Sales Chart
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        @php
            $defaultSalesData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $chartSalesData = isset($salesData) ? $salesData : $defaultSalesData;
        @endphp

        const salesData = @json($chartSalesData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales (Rs.)',
                    data: salesData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'Sales: Rs. ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rs. ' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
});
</script>
@endpush
