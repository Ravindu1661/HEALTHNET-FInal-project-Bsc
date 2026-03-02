@extends('admin.layouts.master')

@section('title', 'Prescription Order Details')
@section('page-title', 'Prescription Order Details')

@section('content')
@php
    use App\Models\PharmacyOrder;

    // id route එකෙන් compact වෙලා පැමිණෙන variable
    $order = PharmacyOrder::with('patient.user', 'pharmacy', 'items.medicine')
        ->find($id);

    if (!$order) {
        // Order නෑනම් simple fallback message
        echo '<div class="alert alert-warning">Prescription order not found.</div>';
        return;
    }

    $statusMap = [
        'pending'    => ['bg-warning text-dark', 'fa-clock',         'Pending'],
        'verified'   => ['bg-info text-white',   'fa-check',         'Verified'],
        'processing' => ['bg-primary text-white','fa-cogs',          'Processing'],
        'ready'      => ['bg-success text-white','fa-box-open',      'Ready'],
        'dispatched' => ['bg-dark text-white',   'fa-shipping-fast', 'Dispatched'],
        'delivered'  => ['bg-success text-white','fa-check-double',  'Delivered'],
        'cancelled'  => ['bg-danger text-white', 'fa-times-circle',  'Cancelled'],
    ];
    $payMap = [
        'paid'   => ['bg-success text-white', 'Paid'],
        'unpaid' => ['bg-danger text-white',  'Unpaid'],
    ];

    [$stClass, $stIcon, $stLabel] = $statusMap[$order->status] ?? ['bg-secondary text-white','fa-circle','Unknown'];
    [$pyClass, $pyLabel]          = $payMap[$order->payment_status] ?? ['bg-secondary text-white','Unknown'];

    $patient = $order->patient;
    $pName   = $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'N/A';
    $pImg    = ($patient && $patient->profile_image)
        ? asset('storage/'.$patient->profile_image)
        : asset('images/default-avatar.png');
@endphp

{{-- Session messages --}}
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

<div class="row">
    <div class="col-lg-11 mx-auto">

        {{-- Header --}}
        <div class="dashboard-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="fas fa-prescription me-2 text-primary"></i>
                            Order #{{ $order->order_number }}
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge {{ $stClass }}">
                                <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                            </span>
                            <span class="badge {{ $pyClass }}">
                                <i class="fas fa-money-bill-wave me-1"></i>{{ $pyLabel }}
                            </span>
                            @if($order->prescription_file)
                                <span class="badge bg-info">
                                    <i class="fas fa-file-medical me-1"></i>Has Prescription
                                </span>
                            @endif
                            @if($order->delivery_address === 'PICKUP')
                                <span class="badge bg-secondary">
                                    <i class="fas fa-store me-1"></i>Pickup
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="fas fa-truck me-1"></i>Delivery
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end mt-3 mt-md-0">
                        @if($order->prescription_file)
                            <a href="{{ asset('storage/' . $order->prescription_file) }}"
                               target="_blank"
                               class="btn btn-danger btn-sm mb-2 w-100">
                                <i class="fas fa-file-pdf me-1"></i> View Prescription
                            </a>
                        @endif
                        <a href="{{ route('admin.prescriptions.index') }}"
                           class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Patient --}}
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Patient Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $pImg }}"
                                 alt="{{ $pName }}"
                                 class="rounded-circle"
                                 width="80" height="80"
                                 style="object-fit:cover;"
                                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            <div>
                                <h5 class="mb-1">{{ $pName }}</h5>
                                @if($patient && $patient->user)
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $patient->user->email }}
                                    </p>
                                @endif
                                @if($patient && $patient->phone)
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                                    </p>
                                @endif
                                @if($patient && $patient->nic)
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-id-card me-1"></i>NIC: {{ $patient->nic }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pharmacy --}}
                @if($order->pharmacy)
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-pills me-2"></i>Pharmacy Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $phImg = $order->pharmacy->profile_image
                                    ? asset('storage/'.$order->pharmacy->profile_image)
                                    : asset('images/default-pharmacy.png');
                            @endphp
                            <img src="{{ $phImg }}"
                                 alt="{{ $order->pharmacy->name }}"
                                 class="rounded-circle"
                                 width="60" height="60"
                                 style="object-fit:cover;"
                                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
                            <div>
                                <h6 class="mb-1">{{ $order->pharmacy->name }}</h6>
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $order->pharmacy->address ?? '' }}
                                    {{ $order->pharmacy->city ? ', '.$order->pharmacy->city : '' }}
                                </p>
                                @if($order->pharmacy->phone)
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $order->pharmacy->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Items --}}
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-capsules me-2"></i>Order Items
                            <span class="badge bg-secondary ms-2">{{ $order->items->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Medicine</th>
                                        <th>Dosage</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $i => $item)
                                        @php
                                            $unit = $item->unit_price ?? $item->price ?? 0;
                                            $sub  = $unit * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->medicine->name ?? $item->medicine_name ?? 'N/A' }}</strong>
                                                @if($item->medicine && $item->medicine->generic_name)
                                                    <br><small class="text-muted">{{ $item->medicine->generic_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $item->medicine->dosage ?? '—' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">
                                                LKR {{ number_format($unit, 2) }}
                                            </td>
                                            <td class="text-end">
                                                <strong>LKR {{ number_format($sub, 2) }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                No items found for this order.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($order->items->count())
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end fw-semibold">Subtotal</td>
                                            <td class="text-end">
                                                LKR {{ number_format($order->total_amount ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        @if(($order->delivery_fee ?? 0) > 0)
                                            <tr>
                                                <td colspan="5" class="text-end fw-semibold">Delivery Fee</td>
                                                <td class="text-end">
                                                    LKR {{ number_format($order->delivery_fee, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="5" class="text-end fw-bold text-success">Grand Total</td>
                                            <td class="text-end fw-bold text-success">
                                                LKR {{ number_format(($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if($order->notes)
                <div class="dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Order Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0" style="white-space: pre-line;">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th width="130">Order #</th>
                                <td><span class="badge bg-info">{{ $order->order_number }}</span></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $stClass }}">
                                        <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment</th>
                                <td><span class="badge {{ $pyClass }}">{{ $pyLabel }}</span></td>
                            </tr>
                            @if($order->payment_method)
                                <tr>
                                    <th>Method</th>
                                    <td>{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Subtotal</th>
                                <td>LKR {{ number_format($order->total_amount ?? 0, 2) }}</td>
                            </tr>
                            @if(($order->delivery_fee ?? 0) > 0)
                                <tr>
                                    <th>Delivery Fee</th>
                                    <td>LKR {{ number_format($order->delivery_fee, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Grand Total</th>
                                <td class="fw-bold text-success">
                                    LKR {{ number_format(($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Placed On</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}<br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <th>Updated</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}<br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($order->updated_at)->format('h:i A') }}
                                    </small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($order->delivery_address && $order->delivery_address !== 'PICKUP')
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Delivery Details</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                @if($order->delivery_method)
                                    <tr>
                                        <th width="110">Method</th>
                                        <td>{{ ucfirst($order->delivery_method) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $order->delivery_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-store me-2"></i>Pickup Order</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Customer will pick up from pharmacy.
                            </p>
                        </div>
                    </div>
                @endif

                @if($order->prescription_file)
                    <div class="dashboard-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-file-medical me-2"></i>Prescription File</h6>
                            <a href="{{ asset('storage/' . $order->prescription_file) }}"
                               target="_blank"
                               class="btn btn-danger btn-sm">
                                <i class="fas fa-external-link-alt"></i> Open
                            </a>
                        </div>
                        <div class="card-body text-center">
                            @php $ext = pathinfo($order->prescription_file, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                                <img src="{{ asset('storage/' . $order->prescription_file) }}"
                                     alt="Prescription"
                                     class="img-fluid rounded"
                                     style="max-height:220px;object-fit:cover;">
                            @else
                                <div class="py-3">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2 d-block"></i>
                                    <small class="text-muted">PDF Prescription File</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
