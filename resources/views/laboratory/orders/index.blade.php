@extends('laboratory.layouts.app')
@section('title', 'Orders')
@section('page-title', 'Lab Orders')
@section('page-subtitle', 'Manage all laboratory orders')

@section('content')

{{-- Filter Tabs --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden">
    <div class="flex overflow-x-auto">
        @php
            $tabs = [
                ''                => ['label'=>'All Orders',    'count'=>$counts['all'],              'icon'=>'fa-list'],
                'pending'         => ['label'=>'Pending',       'count'=>$counts['pending'],           'icon'=>'fa-hourglass-half'],
                'sample_collected'=> ['label'=>'Collected',     'count'=>$counts['sample_collected'],  'icon'=>'fa-vial'],
                'processing'      => ['label'=>'Processing',    'count'=>$counts['processing'],        'icon'=>'fa-microscope'],
                'completed'       => ['label'=>'Completed',     'count'=>$counts['completed'],         'icon'=>'fa-check-circle'],
                'cancelled'       => ['label'=>'Cancelled',     'count'=>$counts['cancelled'],         'icon'=>'fa-times-circle'],
            ];
            $activeTab = request('status','');
        @endphp
        @foreach($tabs as $key => $tab)
        <a href="{{ route('laboratory.orders.index', array_merge(request()->except('status','page'), $key ? ['status'=>$key] : [])) }}"
           class="flex items-center gap-2 px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition
           {{ $activeTab === $key ? 'border-teal-600 text-teal-700 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            <i class="fas {{ $tab['icon'] }}"></i>
            {{ $tab['label'] }}
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full {{ $activeTab === $key ? 'bg-teal-100 text-teal-700' : '' }}">{{ $tab['count'] }}</span>
        </a>
        @endforeach
        <a href="{{ route('laboratory.orders.index', array_merge(request()->except('home','page'), ['home'=>1])) }}"
           class="flex items-center gap-2 px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition
           {{ request('home') ? 'border-blue-600 text-blue-700 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-home"></i> Home Collection
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $counts['home'] }}</span>
        </a>
    </div>
</div>

{{-- Search + Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" action="{{ route('laboratory.orders.index') }}" class="flex flex-wrap gap-3 items-end">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        @if(request('home'))<input type="hidden" name="home" value="1">@endif

        <div class="flex-1 min-w-48">
            <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Order number, patient name..."
                    class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Payment</label>
            <select name="payment" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">All</option>
                <option value="unpaid"  {{ request('payment')=='unpaid'  ? 'selected':'' }}>Unpaid</option>
                <option value="paid"    {{ request('payment')=='paid'    ? 'selected':'' }}>Paid</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Collection Date</label>
            <input type="date" name="date" value="{{ request('date') }}"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        </div>

        <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        <a href="{{ route('laboratory.orders.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
            Reset
        </a>
    </form>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Order</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Patient</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tests / Items</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Collection</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Payment</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
                        @if($order->home_collection)
                        <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded font-medium">🏠 Home</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-900">{{ $order->patient->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->patient->phone ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-700">{{ $order->items->count() }} item(s)</p>
                        <p class="text-xs text-gray-400 truncate max-w-[160px]">
                            {{ $order->items->pluck('item_name')->implode(', ') }}
                        </p>
                    </td>
                    <td class="px-5 py-4">
                        @if($order->collection_date)
                        <p class="text-gray-700">{{ \Carbon\Carbon::parse($order->collection_date)->format('d M Y') }}</p>
                        @if($order->collection_time)<p class="text-xs text-gray-400">{{ $order->collection_time }}</p>@endif
                        @else
                        <span class="text-gray-400 text-xs">Walk-in</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-900">Rs. {{ number_format($order->total_amount, 0) }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($order->status==='pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status==='sample_collected') bg-teal-100 text-teal-700
                            @elseif($order->status==='processing') bg-blue-100 text-blue-700
                            @elseif($order->status==='completed') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst(str_replace('_',' ',$order->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $order->payment_status==='paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div x-data="{open:false}" class="relative inline-block">
                            <button @click="open=!open"
                                class="text-gray-500 hover:text-teal-700 p-1.5 hover:bg-teal-50 rounded-lg transition">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div x-show="open" @click.away="open=false" x-cloak
                                 class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-20">
                                <a href="{{ route('laboratory.orders.show', $order) }}"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-eye text-gray-400 w-4"></i> View Details
                                </a>

                                @if($order->status === 'pending')
                                <form method="POST" action="{{ route('laboratory.orders.collect', $order) }}">
                                    @csrf
                                    <button class="w-full flex items-center gap-2 px-4 py-2 text-sm text-teal-700 hover:bg-teal-50">
                                        <i class="fas fa-vial text-teal-500 w-4"></i> Mark Sample Collected
                                    </button>
                                </form>
                                @endif

                                @if($order->status === 'sample_collected')
                                <form method="POST" action="{{ route('laboratory.orders.process', $order) }}">
                                    @csrf
                                    <button class="w-full flex items-center gap-2 px-4 py-2 text-sm text-blue-700 hover:bg-blue-50">
                                        <i class="fas fa-microscope text-blue-500 w-4"></i> Mark Processing
                                    </button>
                                </form>
                                @endif

                                @if(in_array($order->status, ['processing','sample_collected']))
                                <button onclick="openCompleteModal({{ $order->id }}, '{{ $order->order_number }}')"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                    <i class="fas fa-check-circle text-green-500 w-4"></i> Complete + Upload Report
                                </button>
                                @endif

                                @if($order->report_file)
                                <a href="{{ asset('storage/'.$order->report_file) }}" target="_blank"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-purple-700 hover:bg-purple-50">
                                    <i class="fas fa-file-pdf text-purple-500 w-4"></i> View Report
                                </a>
                                @endif

                                @if(!in_array($order->status, ['completed','cancelled']))
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('laboratory.orders.cancel', $order) }}"
                                          onsubmit="return confirm('Cancel this order?')">
                                        @csrf
                                        <button class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-times-circle text-red-400 w-4"></i> Cancel Order
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16">
                        <i class="fas fa-clipboard-list text-gray-200 text-5xl mb-3 block"></i>
                        <p class="text-gray-400 font-medium">No orders found</p>
                        <p class="text-gray-300 text-sm mt-1">Try adjusting your filters</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Complete + Upload Report Modal --}}
<div id="completeModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Complete Order & Upload Report</h3>
            <button onclick="closeCompleteModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="completeForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Lab Report (PDF)</label>
                <input type="file" name="report_file" accept=".pdf"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Max 10MB. PDF only. (Optional — can upload later)</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 text-white py-2.5 rounded-xl font-semibold hover:bg-teal-700 transition">
                    <i class="fas fa-check mr-2"></i> Complete Order
                </button>
                <button type="button" onclick="closeCompleteModal()"
                    class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCompleteModal(orderId, orderNum) {
    document.getElementById('completeForm').action = `/laboratory/orders/${orderId}/complete`;
    document.getElementById('completeModal').classList.remove('hidden');
}
function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}
</script>
@endpush
