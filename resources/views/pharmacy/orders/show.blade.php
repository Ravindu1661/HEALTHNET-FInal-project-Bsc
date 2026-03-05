{{-- resources/views/pharmacy/orders/show.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Order '.$order->order_number)
@section('page-title', 'Order Details')

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

@php
    $statusFlow = ['pending','verified','processing','ready','dispatched','delivered'];
    $currentIdx = array_search($order->status, $statusFlow);
    $badgeMap = [
        'pending'    => 'warning',
        'verified'   => 'info',
        'processing' => 'primary',
        'ready'      => 'success',
        'dispatched' => 'secondary',
        'delivered'  => 'success',
        'cancelled'  => 'danger',
    ];
    $bdg = $badgeMap[$order->status] ?? 'secondary';
@endphp

{{-- ── Top Bar ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        {{-- ✅ route: orders.index --}}
        <a href="{{ route('pharmacy.orders.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fw-bold fs-5">{{ $order->order_number }}</span>
        <span class="badge bg-{{ $bdg }} ms-2 rounded-pill">{{ ucfirst($order->status) }}</span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        {{-- ✅ FIXED: orders.print-invoice (was orders.invoice) --}}
        <a href="{{ route('pharmacy.orders.print-invoice', $order->id) }}" target="_blank"
           class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-print me-1"></i> Invoice
        </a>
        @if($order->prescription_file)
        {{-- ✅ FIXED: orders.download-prescription (was orders.prescription) --}}
        <a href="{{ route('pharmacy.orders.download-prescription', $order->id) }}"
           class="btn btn-outline-info btn-sm rounded-pill px-3">
            <i class="fas fa-file-download me-1"></i> Prescription
        </a>
        @endif
    </div>
</div>

{{-- ── Progress Tracker ── --}}
@if($order->status !== 'cancelled')
<div class="dashboard-card mb-4 px-4 py-3">
    <div class="d-flex justify-content-between align-items-center position-relative">
        <div class="progress position-absolute w-100"
             style="height:3px;top:18px;left:0;z-index:0">
            <div class="progress-bar bg-primary"
                 style="width:{{ $currentIdx !== false
                    ? ($currentIdx / (count($statusFlow)-1) * 100) : 0 }}%"></div>
        </div>
        @foreach($statusFlow as $idx => $st)
        @php
            $done   = ($currentIdx !== false && $idx <= $currentIdx);
            $active = ($order->status === $st);
            $stIcons = [
                'pending'    => 'fa-clock',
                'verified'   => 'fa-check-circle',
                'processing' => 'fa-cog',
                'ready'      => 'fa-check-double',
                'dispatched' => 'fa-truck',
                'delivered'  => 'fa-box-open',
            ];
        @endphp
        <div class="d-flex flex-column align-items-center position-relative" style="z-index:1">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:36px;height:36px;font-size:.8rem;
                        background:{{ $done ? '#2563eb' : '#e5e7eb' }};
                        color:{{ $done ? '#fff' : '#9ca3af' }};
                        border:3px solid {{ $active ? '#2563eb' : ($done ? '#2563eb' : '#e5e7eb') }}">
                <i class="fas {{ $stIcons[$st] ?? 'fa-circle' }}"></i>
            </div>
            <small style="font-size:.65rem;margin-top:.3rem;
                          color:{{ $done ? '#2563eb' : '#9ca3af' }};
                          font-weight:{{ $active ? '700' : '500' }}">
                {{ ucfirst($st) }}
            </small>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="row g-3">

    {{-- ── Left Column ── --}}
    <div class="col-lg-8">

        {{-- Order Items --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-pills me-2 text-primary"></i>Ordered Medicines</h6>
                <span class="badge bg-light text-dark border">{{ $items->count() }} item(s)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background:#f8fafc;font-size:.76rem;text-transform:uppercase;
                                      letter-spacing:.04em;color:#6b7280">
                            <tr>
                                <th class="ps-3 py-3">#</th>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $i => $item)
                        <tr>
                            <td class="ps-3 text-muted" style="font-size:.8rem">{{ $i+1 }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.87rem">
                                    {{ $item->medication_name }}
                                </div>
                                @if($item->category)
                                <small class="badge bg-light text-dark border"
                                       style="font-size:.68rem">{{ $item->category }}</small>
                                @endif
                                @if($item->requires_prescription)
                                <small class="badge bg-info bg-opacity-15 text-info"
                                       style="font-size:.68rem">Rx</small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $item->dosage ?? '–' }}</small></td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border fw-semibold">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="text-end" style="font-size:.85rem">
                                Rs. {{ number_format($item->price, 2) }}
                            </td>
                            <td class="text-end pe-3 fw-semibold" style="font-size:.88rem">
                                Rs. {{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot style="background:#f8fafc">
                            <tr>
                                <td colspan="5" class="text-end fw-semibold pe-3"
                                    style="font-size:.85rem">Subtotal</td>
                                <td class="text-end pe-3 fw-semibold">
                                    Rs. {{ number_format($items->sum('subtotal'), 2) }}
                                </td>
                            </tr>
                            @if($order->delivery_fee > 0)
                            <tr>
                                <td colspan="5" class="text-end pe-3"
                                    style="font-size:.85rem">Delivery Fee</td>
                                <td class="text-end pe-3">
                                    Rs. {{ number_format($order->delivery_fee, 2) }}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="5" class="text-end fw-bold pe-3 py-2"
                                    style="font-size:.95rem">Total</td>
                                <td class="text-end pe-3 fw-bold text-primary"
                                    style="font-size:.95rem">
                                    Rs. {{ number_format($order->total_amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Prescription File --}}
        @if($order->prescription_file)
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-file-prescription me-2 text-info"></i>Prescription File</h6>
            </div>
            <div class="card-body text-center py-4">
                @php $ext = pathinfo($order->prescription_file, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ asset('storage/'.$order->prescription_file) }}"
                         class="img-fluid rounded shadow-sm"
                         style="max-height:350px" alt="Prescription">
                @else
                    <div class="py-3">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3 d-block"></i>
                        <p class="text-muted mb-3">PDF Prescription</p>
                    </div>
                @endif
                {{-- ✅ FIXED: orders.download-prescription --}}
                <a href="{{ route('pharmacy.orders.download-prescription', $order->id) }}"
                   class="btn btn-outline-primary rounded-pill px-4 mt-2">
                    <i class="fas fa-download me-1"></i> Download Prescription
                </a>
            </div>
        </div>
        @endif

        {{-- Pharmacist Notes --}}
        @if($order->pharmacist_notes)
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Pharmacist Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0" style="font-size:.9rem">{{ $order->pharmacist_notes }}</p>
            </div>
        </div>
        @endif

        {{-- Cancel Reason --}}
        @if($order->status === 'cancelled' && $order->cancelled_reason)
        <div class="alert alert-danger border-0">
            <i class="fas fa-times-circle me-2"></i>
            <strong>Cancelled:</strong> {{ $order->cancelled_reason }}
        </div>
        @endif

    </div>

    {{-- ── Right Column ── --}}
    <div class="col-lg-4">

        {{-- Patient Info --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-user me-2 text-primary"></i>Patient</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($patient->profile_image)
                        <img src="{{ asset('storage/'.$patient->profile_image) }}"
                             class="rounded-circle" width="48" height="48"
                             style="object-fit:cover" alt="patient">
                    @else
                        <div class="avatar-circle"
                             style="width:48px;height:48px;font-size:1rem">
                            {{ strtoupper(substr($patient->first_name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fw-semibold">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </div>
                        <small class="text-muted">{{ $patient->email ?? '' }}</small>
                    </div>
                </div>
                <hr class="my-2">
                @if($patient->phone)
                <div class="d-flex justify-content-between py-1">
                    <small class="text-muted">Phone</small>
                    <small class="fw-semibold">{{ $patient->phone }}</small>
                </div>
                @endif
                @if($patient->gender)
                <div class="d-flex justify-content-between py-1">
                    <small class="text-muted">Gender</small>
                    <small class="fw-semibold">{{ ucfirst($patient->gender) }}</small>
                </div>
                @endif
                @if($patient->blood_group)
                <div class="d-flex justify-content-between py-1">
                    <small class="text-muted">Blood Group</small>
                    <small class="fw-semibold">{{ $patient->blood_group }}</small>
                </div>
                @endif
            </div>
        </div>

        {{-- Order Info --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-info-circle me-2 text-info"></i>Order Info</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Order Date</small>
                    <small class="fw-semibold">
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}
                    </small>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Payment Method</small>
                    <small class="fw-semibold">
                        {{ str_replace('_', ' ', $order->payment_method) }}
                    </small>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Payment Status</small>
                    <span class="badge bg-{{ $order->payment_status==='paid' ? 'success':'danger' }}
                                 rounded-pill" style="font-size:.72rem">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                @if($order->delivery_method)
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Delivery</small>
                    <small class="fw-semibold">
                        {{ str_replace('_',' ', $order->delivery_method) }}
                    </small>
                </div>
                @endif
                @if($order->tracking_number)
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Tracking</small>
                    <small class="fw-semibold text-info">{{ $order->tracking_number }}</small>
                </div>
                @endif
                <div class="py-1 mt-1">
                    <small class="text-muted d-block mb-1">Delivery Address</small>
                    <small class="fw-semibold">{{ $order->delivery_address }}</small>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        @if(!in_array($order->status, ['cancelled','delivered']))
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">

                @if($order->status === 'pending')
                {{-- ✅ route: orders.verify --}}
                <form method="POST" action="{{ route('pharmacy.orders.verify', $order->id) }}">
                    @csrf
                    <button class="btn btn-info w-100 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i> Verify Prescription
                    </button>
                </form>

                @elseif($order->status === 'verified')
                {{-- ✅ route: orders.process --}}
                <form method="POST" action="{{ route('pharmacy.orders.process', $order->id) }}">
                    @csrf
                    <button class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-cog me-1"></i> Start Processing
                    </button>
                </form>

                @elseif($order->status === 'processing')
                {{-- ✅ route: orders.ready --}}
                <form method="POST" action="{{ route('pharmacy.orders.ready', $order->id) }}">
                    @csrf
                    <button class="btn btn-success w-100 rounded-pill">
                        <i class="fas fa-check-double me-1"></i> Mark as Ready
                    </button>
                </form>

                @elseif($order->status === 'ready')
                <button class="btn btn-secondary w-100 rounded-pill"
                        onclick="showDispatchModal()">
                    <i class="fas fa-truck me-1"></i> Dispatch Order
                </button>

                @elseif($order->status === 'dispatched')
                {{-- ✅ route: orders.deliver --}}
                <form method="POST" action="{{ route('pharmacy.orders.deliver', $order->id) }}">
                    @csrf
                    <button class="btn btn-success w-100 rounded-pill">
                        <i class="fas fa-box-open me-1"></i> Mark as Delivered
                    </button>
                </form>
                @endif

                {{-- ✅ route: orders.cancel --}}
                <button class="btn btn-outline-danger w-100 rounded-pill"
                        onclick="showCancelModal()">
                    <i class="fas fa-times-circle me-1"></i> Cancel Order
                </button>

            </div>
        </div>
        @endif

        {{-- Payment Record --}}
        @if($payment)
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-credit-card me-2 text-success"></i>Payment Record</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Amount</small>
                    <small class="fw-bold text-success">
                        Rs. {{ number_format($payment->amount, 2) }}
                    </small>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <small class="text-muted">Method</small>
                    <small class="fw-semibold">{{ ucfirst($payment->payment_method) }}</small>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <small class="text-muted">Status</small>
                    <span class="badge bg-{{ $payment->payment_status==='completed' ? 'success':'warning' }}
                                 rounded-pill" style="font-size:.7rem">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>
                @if($payment->transaction_id)
                <div class="d-flex justify-content-between py-1 border-top mt-1">
                    <small class="text-muted">TXN ID</small>
                    <small class="fw-semibold text-info">{{ $payment->transaction_id }}</small>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ── Dispatch Modal ── --}}
<div class="modal fade" id="dispatchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:#f8fafc">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-truck me-2 text-secondary"></i>Dispatch Order
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- ✅ route: orders.dispatch --}}
            <form method="POST" action="{{ route('pharmacy.orders.dispatch', $order->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Tracking Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="tracking_number" class="form-control"
                               placeholder="e.g. TRK-12345678" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary rounded-pill px-4">
                        <i class="fas fa-truck me-1"></i> Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Cancel Modal ── --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger bg-opacity-10">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="fas fa-times-circle me-2"></i>Cancel Order
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- ✅ route: orders.cancel --}}
            <form method="POST" action="{{ route('pharmacy.orders.cancel', $order->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning border-0 mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Stock will be automatically restored upon cancellation.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Reason for Cancellation <span class="text-danger">*</span>
                        </label>
                        <textarea name="cancelled_reason" class="form-control"
                                  rows="3" placeholder="Provide reason…" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">No, Keep It</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-times-circle me-1"></i> Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showDispatchModal() {
    new bootstrap.Modal(document.getElementById('dispatchModal')).show();
}
function showCancelModal() {
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
