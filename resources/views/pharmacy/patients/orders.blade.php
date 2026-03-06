{{-- resources/views/pharmacy/patients/orders.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', $patient->first_name.' Orders')
@section('page-title', 'Patients')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.patients.show', $patient->id) }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fw-bold fs-5">
            {{ $patient->first_name }} {{ $patient->last_name }} — Orders
        </span>
    </div>
    <a href="{{ route('pharmacy.patients.prescriptions', $patient->id) }}"
       class="btn btn-outline-info btn-sm rounded-pill px-3">
        <i class="fas fa-file-prescription me-1"></i> Prescriptions
    </a>
</div>

{{-- ── Summary ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.8rem;font-weight:700;color:#2563eb">{{ $totalOrders }}</div>
            <div class="text-muted" style="font-size:.8rem">Total Orders</div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.6rem;font-weight:700;color:#16a34a">
                Rs. {{ number_format($totalSpent, 2) }}
            </div>
            <div class="text-muted" style="font-size:.8rem">Total Spent (Paid)</div>
        </div>
    </div>
</div>

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.patients.orders', $patient->id) }}"
              method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Order Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['pending','verified','processing','ready','dispatched','delivered','cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status')==$st ? 'selected':'' }}>
                        {{ ucfirst($st) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Payment</label>
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="unpaid" {{ request('payment_status')=='unpaid' ? 'selected':'' }}>Unpaid</option>
                    <option value="paid"   {{ request('payment_status')=='paid'   ? 'selected':'' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.patients.orders', $patient->id) }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Orders Table ── --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-shopping-bag me-2 text-primary"></i>All Orders</h6>
        <span class="badge bg-light text-dark border">{{ $orders->total() }} found</span>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">Order #</th>
                        <th>Date</th>
                        <th>Medicines</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                @php
                    $bdg = match($order->status) {
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
                            {{ $order->order_number }}
                        </span>
                        @if($order->tracking_number)
                        <br><small class="text-muted" style="font-size:.7rem">
                            <i class="fas fa-truck me-1"></i>{{ $order->tracking_number }}
                        </small>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:.8rem">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                        </div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                        </small>
                    </td>
                    <td style="max-width:180px">
                        @foreach($order->items->take(2) as $item)
                        <div style="font-size:.78rem" class="text-truncate">
                            {{ $item->medication_name }}
                            <span class="text-muted">×{{ $item->quantity }}</span>
                        </div>
                        @endforeach
                        @if($order->items->count() > 2)
                        <small class="text-muted">
                            +{{ $order->items->count() - 2 }} more
                        </small>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border" style="font-size:.72rem">
                            {{ $order->items->count() }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.85rem">
                        Rs. {{ number_format($order->total_amount, 2) }}
                        @if($order->delivery_fee > 0)
                        <br><small class="text-muted fw-normal">
                            +{{ number_format($order->delivery_fee, 2) }} del.
                        </small>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $bdg }} rounded-pill" style="font-size:.7rem">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $order->payment_status==='paid'?'success':'danger' }}
                              rounded-pill" style="font-size:.7rem">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="text-center pe-3">
                        <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                           class="btn btn-sm btn-outline-primary rounded-circle"
                           style="width:30px;height:30px;padding:0;line-height:28px"
                           data-bs-toggle="tooltip" title="View Order">
                            <i class="fas fa-eye" style="font-size:.7rem"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                of {{ $orders->total() }}
            </small>
            {{ $orders->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-40"></i>
            <h6 class="fw-semibold">No Orders Found</h6>
            <p class="small">No orders match the selected filters.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
