@extends('laboratory.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Laboratory Dashboard')
@section('page-subtitle', 'Overview of laboratory activities')

@section('content')

{{-- ══ STAT CARDS ══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

    {{-- Today Orders --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <div class="bg-teal-100 p-2.5 rounded-xl">
                <i class="fas fa-calendar-day text-teal-600 text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-teal-500 bg-teal-50 px-2 py-1 rounded-full">Today</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['today_orders'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Today's Orders</p>
    </div>

    {{-- Pending --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <div class="bg-yellow-100 p-2.5 rounded-xl">
                <i class="fas fa-hourglass-half text-yellow-600 text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">Action Needed</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending Confirmations</p>
    </div>

    {{-- Completed --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <div class="bg-green-100 p-2.5 rounded-xl">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Done</span>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Completed</p>
    </div>

    {{-- Monthly Revenue --}}
    <div class="bg-teal-700 rounded-2xl p-5 shadow-sm hover:shadow-md transition col-span-2 lg:col-span-1">
        <div class="flex items-start justify-between mb-3">
            <div class="bg-white/20 p-2.5 rounded-xl">
                <i class="fas fa-coins text-white text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-teal-100 bg-white/10 px-2 py-1 rounded-full">This Month</span>
        </div>
        <p class="text-2xl font-bold text-white">Rs. {{ number_format($stats['monthly_revenue'], 0) }}</p>
        <p class="text-xs text-teal-200 mt-1">Monthly Revenue</p>
    </div>

</div>

{{-- Second row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 p-2.5 rounded-xl"><i class="fas fa-home text-blue-600"></i></div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ $stats['home_collection'] }}</p>
                <p class="text-xs text-gray-500">Home Collections</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="bg-purple-100 p-2.5 rounded-xl"><i class="fas fa-flask text-purple-600"></i></div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ $stats['active_tests'] }}</p>
                <p class="text-xs text-gray-500">Active Tests</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="bg-orange-100 p-2.5 rounded-xl"><i class="fas fa-box-open text-orange-600"></i></div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_packages'] }}</p>
                <p class="text-xs text-gray-500">Test Packages</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="bg-yellow-100 p-2.5 rounded-xl"><i class="fas fa-star text-yellow-500"></i></div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['avg_rating'], 1) }}<span class="text-base text-gray-400">/5</span></p>
                <p class="text-xs text-gray-500">Patient Rating ({{ $stats['total_ratings'] }})</p>
            </div>
        </div>
    </div>
</div>

{{-- ══ MAIN GRID ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-600"></i> Recent Orders
            </h3>
            <a href="{{ route('laboratory.orders.index') }}" class="text-xs text-teal-600 hover:underline font-medium">View All →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="bg-teal-100 w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-teal-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->patient->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->order_number }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-700">Rs. {{ number_format($order->total_amount, 0) }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        @if($order->status==='pending') bg-yellow-100 text-yellow-700
                        @elseif($order->status==='completed') bg-green-100 text-green-700
                        @elseif($order->status==='processing') bg-blue-100 text-blue-700
                        @elseif($order->status==='sample_collected') bg-teal-100 text-teal-700
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst(str_replace('_',' ',$order->status)) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <i class="fas fa-clipboard text-gray-300 text-4xl mb-3 block"></i>
                <p class="text-gray-400 text-sm">No orders yet</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Order Status Pie + Home Collections --}}
    <div class="space-y-5">

        {{-- Order Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-600"></i> Order Status
            </h3>
            <canvas id="statusChart" height="160"></canvas>
        </div>

        {{-- Upcoming Home Collections --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-home text-blue-500"></i> Upcoming Home Collections
                </h3>
                <a href="{{ route('laboratory.orders.index', ['home'=>1]) }}" class="text-xs text-teal-600 hover:underline">All →</a>
            </div>
            @forelse($homeCollections as $col)
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $col->patient->user->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($col->collection_date)->format('d M') }} @if($col->collection_time) · {{ $col->collection_time }} @endif</p>
                </div>
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">Home</span>
            </div>
            @empty
            <div class="py-6 text-center">
                <p class="text-gray-400 text-xs">No upcoming home collections</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Revenue Chart + Latest Reviews --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-chart-bar text-teal-600"></i> Monthly Revenue
            </h3>
            <span class="text-xs text-gray-400">Last 6 months</span>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Latest Reviews --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                <i class="fas fa-star text-yellow-400"></i> Recent Reviews
            </h3>
            <a href="{{ route('laboratory.reviews.index') }}" class="text-xs text-teal-600 hover:underline">All →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($latestReviews as $review)
            <div class="px-5 py-3">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-semibold text-gray-900">{{ $review->patient->user->name ?? 'Patient' }}</p>
                    <div class="flex text-yellow-400 text-xs gap-0.5">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i<=$review->rating ? '' : 'text-gray-200' }}"></i>@endfor
                    </div>
                </div>
                @if($review->review)
                <p class="text-xs text-gray-500 line-clamp-2">{{ $review->review }}</p>
                @endif
                <p class="text-xs text-gray-300 mt-1">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="py-8 text-center">
                <i class="fas fa-star text-gray-200 text-3xl mb-2 block"></i>
                <p class="text-gray-400 text-xs">No reviews yet</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
new Chart(document.getElementById('revenueChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: @json($monthlyRevenue->pluck('label')),
        datasets: [{
            label: 'Revenue (Rs.)',
            data: @json($monthlyRevenue->pluck('revenue')),
            backgroundColor: 'rgba(13,148,136,0.8)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rs.'+v.toLocaleString(), font: { size: 10 } },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Status Pie Chart
const statusData = @json($orderStatus);
const labels = Object.keys(statusData).map(k => k.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase()));
const colors = { pending:'#fbbf24', sample_collected:'#06b6d4', processing:'#6366f1', completed:'#10b981', cancelled:'#ef4444' };

new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels,
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(k => colors[k] || '#94a3b8'),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8, boxWidth: 10 } } }
    }
});
</script>
@endpush
