{{-- resources/views/laboratory/orders/show.blade.php --}}
@extends('laboratory.layouts.app')

@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')
@section('page-subtitle', 'Full details for order ' . $order->order_number)

@section('content')

{{-- Back + Action Buttons --}}
<div class="flex items-center justify-between mb-5">
    <a href="{{ route('laboratory.orders.index') }}"
       class="flex items-center gap-2 text-sm text-gray-500 hover:text-teal-700 transition">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
    <div class="flex gap-2">
        {{-- Status Actions --}}
        @if($order->status === 'pending')
            <form method="POST" action="{{ route('laboratory.orders.collect', $order) }}">
                @csrf
                <button class="flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-teal-700 transition shadow-sm">
                    <i class="fas fa-vial"></i> Mark Sample Collected
                </button>
            </form>
        @elseif($order->status === 'sample_collected')
            <form method="POST" action="{{ route('laboratory.orders.process', $order) }}">
                @csrf
                <button class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-microscope"></i> Mark Processing
                </button>
            </form>
        @endif

        @if(in_array($order->status, ['processing', 'sample_collected']))
            <button onclick="document.getElementById('completeModal').classList.remove('hidden')"
                    class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-green-700 transition shadow-sm">
                <i class="fas fa-check-circle"></i> Complete & Upload Report
            </button>
        @endif

        @if($order->status === 'completed' && !$order->report_file)
            <button onclick="document.getElementById('uploadReportModal').classList.remove('hidden')"
                    class="flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-purple-700 transition shadow-sm">
                <i class="fas fa-upload"></i> Upload Report
            </button>
        @endif

        @if($order->report_file)
            <a href="{{ asset('storage/' . $order->report_file) }}" target="_blank"
               class="flex items-center gap-2 bg-purple-50 border border-purple-200 text-purple-700 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-purple-100 transition">
                <i class="fas fa-file-pdf"></i> View Report
            </a>
        @endif

        @if(!in_array($order->status, ['completed', 'cancelled']))
            <form method="POST" action="{{ route('laboratory.orders.cancel', $order) }}"
                  onsubmit="return confirm('Cancel this order?')">
                @csrf
                <button class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-red-100 transition">
                    <i class="fas fa-times-circle"></i> Cancel Order
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Status Progress Tracker --}}
@php
    $steps = [
        'pending'          => ['label' => 'Order Received',    'icon' => 'fa-clipboard-check'],
        'sample_collected' => ['label' => 'Sample Collected',  'icon' => 'fa-vial'],
        'processing'       => ['label' => 'Processing',        'icon' => 'fa-microscope'],
        'completed'        => ['label' => 'Report Ready',      'icon' => 'fa-check-circle'],
    ];
    $stepOrder = array_keys($steps);
    $currentIdx = array_search($order->status, $stepOrder);
    $currentIdx = $currentIdx === false ? 0 : $currentIdx;
@endphp

@if($order->status !== 'cancelled')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5 mb-5">
    <div class="flex items-center justify-between relative">
        {{-- Progress Line --}}
        <div class="absolute left-0 right-0 top-5 h-0.5 bg-gray-100 mx-12 z-0"></div>
        <div class="absolute left-0 top-5 h-0.5 bg-teal-500 z-0 transition-all"
             style="width: {{ $currentIdx > 0 ? (($currentIdx / (count($steps)-1)) * 100) : 0 }}%; margin-left: 3rem; margin-right: 3rem;"></div>

        @foreach($steps as $key => $step)
            @php
                $idx = array_search($key, $stepOrder);
                $done = $idx <= $currentIdx;
                $active = $idx === $currentIdx;
            @endphp
            <div class="flex flex-col items-center relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all
                    {{ $done ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-gray-200 text-gray-300' }}
                    {{ $active ? 'ring-4 ring-teal-100' : '' }}">
                    <i class="fas {{ $step['icon'] }} text-sm"></i>
                </div>
                <span class="text-xs mt-2 font-medium text-center max-w-20
                    {{ $active ? 'text-teal-700' : ($done ? 'text-gray-700' : 'text-gray-400') }}">
                    {{ $step['label'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-3 mb-5 flex items-center gap-3">
    <i class="fas fa-times-circle text-red-500 text-lg"></i>
    <span class="text-red-700 font-semibold text-sm">This order has been cancelled.</span>
</div>
@endif

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT: Order + Patient + Items --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Order Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-teal-600"></i> Order Information
                </h3>
                {{-- Status Badge --}}
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($order->status === 'sample_collected') bg-teal-100 text-teal-700
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-700
                    @elseif($order->status === 'completed') bg-green-100 text-green-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-0 divide-x divide-gray-50">
                @php
                    $infoRows = [
                        ['icon' => 'fa-hashtag',       'label' => 'Order Number',    'value' => $order->order_number],
                        ['icon' => 'fa-calendar-alt',  'label' => 'Order Date',      'value' => \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A')],
                        ['icon' => 'fa-credit-card',   'label' => 'Payment Status',  'value' => ucfirst($order->payment_status), 'badge' => $order->payment_status === 'paid' ? 'green' : 'red'],
                        ['icon' => 'fa-money-bill',    'label' => 'Total Amount',    'value' => 'Rs. ' . number_format($order->total_amount, 2)],
                        ['icon' => 'fa-calendar-day',  'label' => 'Collection Date', 'value' => $order->collection_date ? \Carbon\Carbon::parse($order->collection_date)->format('d M Y') : 'Walk-in'],
                        ['icon' => 'fa-clock',         'label' => 'Collection Time', 'value' => $order->collection_time ?? '—'],
                    ];
                @endphp
                @foreach($infoRows as $row)
                <div class="px-6 py-3.5 flex items-center gap-3">
                    <div class="bg-teal-50 p-2 rounded-lg flex-shrink-0">
                        <i class="fas {{ $row['icon'] }} text-teal-600 text-sm w-4 text-center"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">{{ $row['label'] }}</p>
                        @if(isset($row['badge']))
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                {{ $row['badge'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $row['value'] }}
                            </span>
                        @else
                            <p class="text-sm font-semibold text-gray-900">{{ $row['value'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Home Collection Address --}}
            @if($order->home_collection && $order->collection_address)
            <div class="px-6 py-3.5 border-t border-gray-50 flex items-start gap-3">
                <div class="bg-blue-50 p-2 rounded-lg flex-shrink-0 mt-0.5">
                    <i class="fas fa-home text-blue-600 text-sm w-4 text-center"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Home Collection Address</p>
                    <p class="text-sm text-gray-900">{{ $order->collection_address }}</p>
                </div>
            </div>
            @endif

            @if($order->notes)
            <div class="px-6 py-3.5 border-t border-gray-50 flex items-start gap-3">
                <div class="bg-gray-50 p-2 rounded-lg flex-shrink-0 mt-0.5">
                    <i class="fas fa-sticky-note text-gray-500 text-sm w-4 text-center"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Notes</p>
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Test Items Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-flask text-teal-600"></i> Ordered Tests & Packages
                </h3>
                <span class="text-xs bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full font-semibold">
                    {{ $order->items->count() }} item(s)
                </span>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($order->items as $item)
                <div class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                            {{ $item->package_id ? 'bg-purple-100' : 'bg-teal-100' }}">
                            <i class="fas {{ $item->package_id ? 'fa-box-open text-purple-600' : 'fa-vial text-teal-600' }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->item_name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $item->package_id ? 'Package' : 'Test' }}
                                @if($item->test && $item->test->test_category)
                                    &bull; {{ $item->test->test_category }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900 text-sm">Rs. {{ number_format($item->price, 2) }}</p>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-gray-400 text-sm">No items found.</div>
                @endforelse
            </div>
            {{-- Total Row --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <span class="font-bold text-gray-700 text-sm">Total Amount</span>
                <span class="font-bold text-teal-700 text-lg">Rs. {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Prescription / Referral --}}
        @if($order->prescription_file)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
            <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                <i class="fas fa-file-medical text-teal-600"></i> Prescription / Referral
            </h3>
            <a href="{{ asset('storage/' . $order->prescription_file) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-teal-50 border border-teal-200 text-teal-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-teal-100 transition">
                <i class="fas fa-file-pdf"></i> View Prescription
            </a>
        </div>
        @endif

    </div>

    {{-- RIGHT: Patient + Report + Timeline --}}
    <div class="space-y-5">

        {{-- Patient Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                    <i class="fas fa-user text-teal-600"></i> Patient Details
                </h3>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center font-bold text-teal-700 text-lg flex-shrink-0">
                        {{ strtoupper(substr($order->patient->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm">{{ $order->patient->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->patient->user->email ?? '' }}</p>
                    </div>
                </div>
                <div class="space-y-2.5 text-sm">
                    @if($order->patient->phone ?? false)
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-phone text-teal-500 w-4 text-center text-xs"></i>
                        {{ $order->patient->phone }}
                    </div>
                    @endif
                    @if($order->patient->city ?? false)
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-map-marker-alt text-teal-500 w-4 text-center text-xs"></i>
                        {{ $order->patient->city }}
                    </div>
                    @endif
                    @if($order->patient->date_of_birth ?? false)
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-birthday-cake text-teal-500 w-4 text-center text-xs"></i>
                        {{ \Carbon\Carbon::parse($order->patient->date_of_birth)->format('d M Y') }}
                    </div>
                    @endif
                    @if($order->patient->blood_group ?? false)
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-tint text-teal-500 w-4 text-center text-xs"></i>
                        Blood Group: <span class="font-semibold">{{ $order->patient->blood_group }}</span>
                    </div>
                    @endif
                    @if($order->home_collection)
                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full font-semibold">
                            <i class="fas fa-home text-xs"></i> Home Collection
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Report Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                    <i class="fas fa-file-medical-alt text-teal-600"></i> Lab Report
                </h3>
            </div>
            <div class="p-5">
                @if($order->report_file)
                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl border border-green-200 mb-3">
                        <i class="fas fa-file-pdf text-green-600 text-2xl"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-green-800">Report Uploaded</p>
                            <p class="text-xs text-green-600">
                                {{ $order->report_uploaded_at ? \Carbon\Carbon::parse($order->report_uploaded_at)->format('d M Y, h:i A') : '' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $order->report_file) }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 bg-purple-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-purple-700 transition">
                        <i class="fas fa-download"></i> Download Report
                    </a>
                    {{-- Re-upload --}}
                    <button onclick="document.getElementById('uploadReportModal').classList.remove('hidden')"
                            class="w-full mt-2 flex items-center justify-center gap-2 bg-gray-100 text-gray-600 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-redo"></i> Re-upload Report
                    </button>
                @elseif($order->status === 'completed')
                    <div class="text-center py-4">
                        <i class="fas fa-file-upload text-gray-200 text-4xl mb-3 block"></i>
                        <p class="text-gray-400 text-sm mb-3">No report uploaded yet</p>
                        <button onclick="document.getElementById('uploadReportModal').classList.remove('hidden')"
                                class="bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-teal-700 transition">
                            <i class="fas fa-upload mr-1"></i> Upload Report
                        </button>
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-clock text-gray-200 text-3xl mb-2 block"></i>
                        <p class="text-gray-400 text-sm">Report will be uploaded after order is completed.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Referred Doctor --}}
        @if($order->doctor)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
            <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm mb-3">
                <i class="fas fa-user-md text-teal-600"></i> Referred By
            </h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center font-bold text-teal-700 flex-shrink-0">
                    {{ strtoupper(substr($order->doctor->first_name ?? 'D', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Dr. {{ $order->doctor->first_name ?? '' }} {{ $order->doctor->last_name ?? '' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $order->doctor->specialization ?? '' }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Complete & Upload Report Modal --}}
<div id="completeModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i> Complete Order
            </h3>
            <button onclick="document.getElementById('completeModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('laboratory.orders.complete', $order) }}"
              enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Upload Lab Report <span class="text-gray-400 font-normal">(Optional)</span>
                </label>
                <input type="file" name="reportfile" accept=".pdf"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">PDF only. Max 10MB. Can be uploaded later.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-green-600 text-white py-2.5 rounded-xl font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-check mr-2"></i> Mark Complete
                </button>
                <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
{{-- Upload Report Modal --}}
<div id="uploadReportModal" class="fixed inset-0 bg-black/40 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Upload Lab Report</h3>
            <button type="button"
                    onclick="document.getElementById('uploadReportModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('laboratory.orders.upload-report', $order) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Report File (PDF)
                </label>
                <input type="file"
                       name="report_file"
                       accept=".pdf"
                       required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 outline-none">
                @error('report_file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Max 10MB. PDF only.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-purple-600 text-white py-2.5 rounded-xl font-semibold hover:bg-purple-700 transition">
                    <i class="fas fa-upload mr-2"></i> Upload Report
                </button>
                <button type="button"
                        onclick="document.getElementById('uploadReportModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
