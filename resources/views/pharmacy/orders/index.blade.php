{{-- resources/views/pharmacy/orders/index.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Orders')
@section('page-title', 'Orders')

@push('styles')
<style>
.status-tab { cursor:pointer; padding:.4rem .9rem; border-radius:20px; font-size:.78rem; font-weight:600; border:2px solid transparent; transition:all .15s; }
.status-tab.active, .status-tab:hover { border-color: currentColor; }
.order-row:hover { background:#f8fafc; }
.badge-pill { border-radius:20px; font-size:.72rem; font-weight:600; padding:.28rem .7rem; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Page Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Prescription Orders</h5>
        <small class="text-muted">Total {{ $orders->total() }} orders found</small>
    </div>
    {{-- ✅ route name: orders.create --}}
    <a href="{{ route('pharmacy.orders.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus-circle me-1"></i> New Order
    </a>
</div>

{{-- ── Status Quick Filters ── --}}
@php
    $statusColors = [
        'all'        => ['text-secondary', 'All'],
        'pending'    => ['text-warning',   'Pending'],
        'verified'   => ['text-info',      'Verified'],
        'processing' => ['text-primary',   'Processing'],
        'ready'      => ['text-success',   'Ready'],
        'dispatched' => ['text-secondary', 'Dispatched'],
        'delivered'  => ['text-success',   'Delivered'],
        'cancelled'  => ['text-danger',    'Cancelled'],
    ];
    $currentStatus = request('status', '');
@endphp
<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($statusColors as $st => [$cls, $lbl])
    <a href="{{ route('pharmacy.orders.index', array_merge(request()->except('status','page'), $st !== 'all' ? ['status'=>$st] : [])) }}"
       class="status-tab {{ $cls }} {{ ($currentStatus === $st || ($st === 'all' && !$currentStatus)) ? 'active' : '' }}">
        {{ $lbl }}
        @if($st !== 'all')
            <span class="ms-1 opacity-75">({{ $counts[$st] ?? 0 }})</span>
        @endif
    </a>
    @endforeach
</div>

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        {{-- ✅ route name: orders.index --}}
        <form action="{{ route('pharmacy.orders.index') }}" method="GET" class="row g-2 align-items-end">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Order # or patient name…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Payment</label>
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All Payments</option>
                    <option value="unpaid" {{ request('payment_status')=='unpaid' ? 'selected':'' }}>Unpaid</option>
                    <option value="paid"   {{ request('payment_status')=='paid'   ? 'selected':'' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Orders Table ── --}}
<div class="dashboard-card">
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.76rem;text-transform:uppercase;letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">Order #</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-center pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                @php
                    $badgeMap = [
                        'pending'    => 'warning',
                        'verified'   => 'info',
                        'processing' => 'primary',
                        'ready'      => 'success',
                        'dispatched' => 'secondary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                    ];
                    $bdg    = $badgeMap[$order->status] ?? 'secondary';
                    $payBdg = $order->payment_status === 'paid' ? 'success' : 'danger';
                @endphp
                <tr class="order-row">
                    <td class="ps-3">
                        <span class="fw-semibold text-primary" style="font-size:.85rem">
                            {{ $order->order_number }}
                        </span>
                        @if($order->tracking_number)
                        <br><small class="text-muted">
                            <i class="fas fa-truck me-1"></i>{{ $order->tracking_number }}
                        </small>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($order->patient_image)
                                <img src="{{ asset('storage/'.$order->patient_image) }}"
                                     class="rounded-circle" width="32" height="32"
                                     style="object-fit:cover" alt="patient">
                            @else
                                <div class="avatar-circle"
                                     style="width:32px;height:32px;font-size:.75rem">
                                    {{ strtoupper(substr($order->patient_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-size:.85rem;font-weight:500">
                                    {{ $order->patient_name ?? 'Unknown' }}
                                </div>
                                @if($order->patient_phone)
                                <small class="text-muted">{{ $order->patient_phone }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:.82rem">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                        </div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('h:i A') }}
                        </small>
                    </td>
                    <td>
                        @php
                            $itemCount = \Illuminate\Support\Facades\DB::table('prescription_order_items')
                                ->where('order_id', $order->id)->count();
                        @endphp
                        <span class="badge bg-light text-dark border" style="font-size:.75rem">
                            {{ $itemCount }} item{{ $itemCount != 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem">
                            Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                        </div>
                        @if($order->delivery_fee > 0)
                        <small class="text-muted">
                            + Rs. {{ number_format($order->delivery_fee, 2) }} del.
                        </small>
                        @endif
                    </td>
                    <td>
                        <span class="badge-pill bg-{{ $payBdg }} bg-opacity-15 text-{{ $payBdg }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <br>
                        <small class="text-muted" style="font-size:.7rem">
                            {{ str_replace('_', ' ', $order->payment_method) }}
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $bdg }} rounded-pill">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">

                            {{-- ✅ route: orders.show --}}
                            <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                               class="btn btn-sm btn-outline-primary rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye" style="font-size:.7rem"></i>
                            </a>

                            @if(!in_array($order->status, ['delivered','cancelled']))
                            <button class="btn btn-sm btn-outline-warning rounded-circle"
                                    style="width:30px;height:30px;padding:0;line-height:28px"
                                    data-bs-toggle="tooltip" title="Update Status"
                                    onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $order->order_number }}')">
                                <i class="fas fa-edit" style="font-size:.7rem"></i>
                            </button>
                            @endif

                            {{-- ✅ FIXED: orders.print-invoice (was orders.invoice) --}}
                            <a href="{{ route('pharmacy.orders.print-invoice', $order->id) }}"
                               class="btn btn-sm btn-outline-secondary rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Invoice" target="_blank">
                                <i class="fas fa-print" style="font-size:.7rem"></i>
                            </a>

                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                of {{ $orders->total() }} orders
            </small>
            {{ $orders->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
            <h6 class="fw-semibold">No orders found</h6>
            <p class="small mb-3">Try adjusting your filters or create a new order.</p>
            <a href="{{ route('pharmacy.orders.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus-circle me-1"></i> New Order
            </a>
        </div>
        @endif
    </div>
</div>

{{-- ── Status Update Modal ── --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:#f8fafc">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i>Update Order Status
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- ✅ action set dynamically via JS using orders.update-status --}}
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Order: <strong id="modalOrderNumber"></strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            New Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="modalStatus" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="processing">Processing</option>
                            <option value="ready">Ready</option>
                            <option value="dispatched">Dispatched</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3" id="trackingGroup" style="display:none">
                        <label class="form-label fw-semibold">Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control"
                               placeholder="e.g. TRK123456">
                    </div>
                    <div class="mb-3" id="cancelGroup" style="display:none">
                        <label class="form-label fw-semibold">
                            Cancellation Reason <span class="text-danger">*</span>
                        </label>
                        <textarea name="cancelled_reason" class="form-control" rows="2"
                                  placeholder="Reason for cancellation…"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pharmacist Notes</label>
                        <textarea name="pharmacist_notes" class="form-control" rows="2"
                                  placeholder="Optional notes…"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));

// ✅ Builds URL: /pharmacy/orders/{id}/update-status
function openStatusModal(orderId, currentStatus, orderNumber) {
    document.getElementById('modalOrderNumber').textContent = orderNumber;
    // ✅ FIXED: update-status route
    document.getElementById('statusForm').action =
        `/pharmacy/orders/${orderId}/update-status`;
    document.getElementById('modalStatus').value = currentStatus;
    toggleStatusFields(currentStatus);
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

document.getElementById('modalStatus').addEventListener('change', function () {
    toggleStatusFields(this.value);
});

function toggleStatusFields(status) {
    document.getElementById('trackingGroup').style.display =
        status === 'dispatched' ? '' : 'none';
    document.getElementById('cancelGroup').style.display =
        status === 'cancelled' ? '' : 'none';
}
</script>
@endpush
