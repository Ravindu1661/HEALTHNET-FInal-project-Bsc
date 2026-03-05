{{-- resources/views/pharmacy/inventory/stock-history.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Stock History – '.$medicine->name)
@section('page-title', 'Inventory')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.inventory.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fw-bold fs-5">Stock History</span>
    </div>
    <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
       class="btn btn-outline-warning btn-sm rounded-pill px-3">
        <i class="fas fa-edit me-1"></i> Edit Medicine
    </a>
</div>

<div class="row g-3">

    {{-- ── Left: Medicine Info ── --}}
    <div class="col-lg-4">

        {{-- Medicine Details Card --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-pills me-2 text-primary"></i>Medicine Details</h6>
            </div>
            <div class="card-body">
                {{-- Stock Status Badge --}}
                <div class="text-center mb-4">
                    @php
                        $statusMap = [
                            'in_stock'    => ['success', 'In Stock',    '#dcfce7', '#16a34a'],
                            'low_stock'   => ['warning', 'Low Stock',   '#fffbeb', '#d97706'],
                            'out_of_stock'=> ['danger',  'Out of Stock','#fef2f2', '#dc2626'],
                        ];
                        [$cls, $lbl, $bg, $clr] = $statusMap[$medicine->stock_status] ?? ['secondary','Unknown','#f1f5f9','#64748b'];
                    @endphp
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2"
                         style="width:64px;height:64px;background:{{ $bg }};
                                border:3px solid {{ $clr }};font-size:1.4rem;
                                font-weight:700;color:{{ $clr }}">
                        {{ $medicine->stock_quantity }}
                    </div>
                    <span class="badge bg-{{ $cls }} rounded-pill px-3">{{ $lbl }}</span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Name</small>
                    <small class="fw-semibold text-end" style="max-width:160px">{{ $medicine->name }}</small>
                </div>
                @if($medicine->generic_name)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Generic</small>
                    <small class="fw-semibold">{{ $medicine->generic_name }}</small>
                </div>
                @endif
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Category</small>
                    <small class="fw-semibold">{{ $medicine->category ?? '–' }}</small>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Dosage</small>
                    <small class="fw-semibold">{{ $medicine->dosage ?? '–' }}</small>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Price</small>
                    <small class="fw-semibold text-primary">
                        Rs. {{ number_format($medicine->price, 2) }}
                    </small>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Manufacturer</small>
                    <small class="fw-semibold">{{ $medicine->manufacturer ?? '–' }}</small>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Rx Required</small>
                    <small>
                        @if($medicine->requires_prescription)
                        <span class="badge bg-info bg-opacity-15 text-info">Yes (Rx)</span>
                        @else
                        <span class="badge bg-light text-muted">No (OTC)</span>
                        @endif
                    </small>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <small class="text-muted">Active</small>
                    <small>
                        @if($medicine->is_active)
                        <span class="badge bg-success bg-opacity-15 text-success">Active</span>
                        @else
                        <span class="badge bg-secondary bg-opacity-15 text-secondary">Inactive</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>

        {{-- Movement Summary --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar me-2 text-info"></i>Movement Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Current Stock</small>
                    <span class="badge bg-{{ $cls }} rounded-pill fw-bold">
                        {{ $medicine->stock_quantity }}
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Total Dispensed</small>
                    <small class="fw-semibold text-danger">
                        {{ $dispensedTotal }} units
                    </small>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Returned (Cancelled)</small>
                    <small class="fw-semibold text-success">
                        {{ $cancelledRestored }} units
                    </small>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <small class="text-muted">Net Dispensed</small>
                    <small class="fw-bold text-primary">
                        {{ $dispensedTotal - $cancelledRestored }} units
                    </small>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Right: History Table ── --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-history me-2 text-info"></i>Dispensing History</h6>
                <span class="badge bg-light text-dark border">
                    {{ $orderHistory->count() }} records (last 50)
                </span>
            </div>
            <div class="card-body p-0">
                @if($orderHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;
                                      letter-spacing:.05em;color:#6b7280">
                            <tr>
                                <th class="ps-3 py-3">Order #</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orderHistory as $record)
                        @php
                            $oBadge = match($record->order_status) {
                                'pending'    => 'warning',
                                'verified'   => 'info',
                                'processing' => 'primary',
                                'ready'      => 'success',
                                'dispatched' => 'secondary',
                                'delivered'  => 'success',
                                'cancelled'  => 'danger',
                                default      => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <span class="fw-semibold text-primary" style="font-size:.83rem">
                                    {{ $record->order_number }}
                                </span>
                            </td>
                            <td>
                                <small class="fw-semibold">{{ $record->patient_name }}</small>
                            </td>
                            <td>
                                <div style="font-size:.8rem">
                                    {{ \Carbon\Carbon::parse($record->order_date)->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($record->order_date)->format('h:i A') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge fw-bold rounded-pill
                                      bg-{{ $record->order_status === 'cancelled' ? 'secondary' : 'danger' }}"
                                      style="font-size:.78rem;min-width:32px">
                                    {{ $record->order_status === 'cancelled' ? '+' : '-' }}{{ $record->quantity }}
                                </span>
                            </td>
                            <td class="text-end" style="font-size:.83rem">
                                Rs. {{ number_format($record->price, 2) }}
                            </td>
                            <td class="text-end fw-semibold" style="font-size:.83rem">
                                Rs. {{ number_format($record->subtotal, 2) }}
                            </td>
                            <td class="text-center pe-3">
                                <span class="badge bg-{{ $oBadge }} rounded-pill"
                                      style="font-size:.68rem">
                                    {{ ucfirst($record->order_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-history fa-3x mb-3 d-block opacity-40"></i>
                    <h6 class="fw-semibold">No Dispensing History</h6>
                    <p class="small">This medicine has not been used in any orders yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
