{{-- resources/views/pharmacy/orders/index.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Orders')
@section('page-title', 'Orders')

@push('styles')
<style>
.status-tab {
    cursor: pointer; padding: .4rem .9rem; border-radius: 20px;
    font-size: .78rem; font-weight: 600; border: 2px solid transparent;
    transition: all .15s; text-decoration: none; white-space: nowrap;
}
.status-tab:hover { border-color: currentColor; }
.order-row:hover { background: #f8fafc; }
.badge-pill { border-radius: 20px; font-size: .72rem; font-weight: 600; padding: .28rem .7rem; }
.order-type-tag {
    display: inline-flex; align-items: center;
    font-size: .65rem; font-weight: 700;
    padding: .1rem .45rem; border-radius: 6px; margin-top: .2rem;
}
.tag-rx    { background:#fff3e0; color:#e65100; }
.tag-otc   { background:#e8f5e9; color:#2e7d32; }
.tag-presc { background:#e0f2fe; color:#0c4a6e; }
.tag-mixed { background:#f3e8ff; color:#6b21a8; }
.presc-thumb {
    width:38px; height:38px; border-radius:8px; object-fit:cover;
    border:1.5px solid #e0f2f1; cursor:pointer; transition:transform .15s;
}
.presc-thumb:hover { transform: scale(1.1); }
.stat-mini {
    background:#fff; border:1.5px solid #e8f5e9; border-radius:10px;
    padding:.65rem 1rem; text-align:center; flex:1; min-width:85px;
}
.stat-mini .val { font-size:1.2rem; font-weight:800; line-height:1; }
.stat-mini .lbl { font-size:.68rem; color:#888; margin-top:.2rem; }
</style>
@endpush

@section('content')

@foreach(['success','error','info'] as $t)
    @if(session($t))
        <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 shadow-sm mb-3"
             style="border-radius:10px">
            <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }} me-2"></i>
            {{ session($t) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

@php
$statusConfig = [
    'all'        => ['#6b7280', 'All'],
    'pending'    => ['#f59e0b', 'Pending'],
    'verified'   => ['#0891b2', 'Verified'],
    'processing' => ['#2563eb', 'Processing'],
    'ready'      => ['#16a34a', 'Ready'],
    'dispatched' => ['#6b7280', 'Dispatched'],
    'delivered'  => ['#15803d', 'Delivered'],
    'cancelled'  => ['#dc2626', 'Cancelled'],
];
$badgeMap = [
    'pending'    => 'warning',
    'verified'   => 'info',
    'processing' => 'primary',
    'ready'      => 'success',
    'dispatched' => 'secondary',
    'delivered'  => 'success',
    'cancelled'  => 'danger',
];
$currentStatus = request('status', '');
@endphp

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Prescription Orders</h5>
        <small class="text-muted">{{ $orders->total() }} total orders</small>
    </div>
</div>

{{-- Mini Stats --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['pending','verified','processing','ready','dispatched','delivered','cancelled'] as $st)
        <div class="stat-mini">
            <div class="val" style="color:{{ $statusConfig[$st][0] }}">{{ $counts[$st] ?? 0 }}</div>
            <div class="lbl">{{ $statusConfig[$st][1] }}</div>
        </div>
    @endforeach
</div>

{{-- Status Tabs --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($statusConfig as $st => $cfg)
        @php [$color, $label] = $cfg; @endphp
        <a href="{{ route('pharmacy.orders.index', array_merge(
                request()->except('status','page'),
                $st !== 'all' ? ['status' => $st] : []
            )) }}"
           class="status-tab"
           style="color:{{ $color }};
                  {{ ($currentStatus===$st || ($st==='all' && !$currentStatus))
                     ? 'border-color:'.$color.';background:'.$color.'18' : '' }}">
            {{ $label }}
            @if($st !== 'all')
                <span class="ms-1 opacity-75">({{ $counts[$st] ?? 0 }})</span>
            @endif
        </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.orders.index') }}" method="GET" class="row g-2 align-items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Order # or patient name" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Order Type</label>
                <select name="order_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="has_items"  {{ request('order_type')==='has_items'  ? 'selected':'' }}>Cart Orders</option>
                    <option value="presc_only" {{ request('order_type')==='presc_only' ? 'selected':'' }}>Prescription Only</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Payment</label>
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All Payments</option>
                    <option value="unpaid" {{ request('payment_status')==='unpaid' ? 'selected':'' }}>Unpaid</option>
                    <option value="paid"   {{ request('payment_status')==='paid'   ? 'selected':'' }}>Paid</option>
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
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('pharmacy.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Orders Table --}}
<div class="dashboard-card">
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.74rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3" style="min-width:140px">Order</th>
                        <th style="min-width:160px">Patient</th>
                        <th style="min-width:110px">Date</th>
                        <th class="text-center" style="min-width:80px">Prescription</th>
                        <th class="text-center" style="min-width:70px">Items</th>
                        <th class="text-end" style="min-width:120px">Amount</th>
                        <th class="text-center" style="min-width:90px">Payment</th>
                        <th class="text-center" style="min-width:90px">Delivery</th>
                        <th class="text-center" style="min-width:100px">Status</th>
                        <th class="text-center pe-3" style="min-width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                @php
                    $bdg      = $badgeMap[$order->status] ?? 'secondary';
                    $payBdg   = $order->payment_status === 'paid' ? 'success' : 'danger';
                    $itemCnt  = $order->items->count();
                    $hasPresc = !empty($order->prescription_file) && $order->prescription_file !== '';
                    $isPickup = strtoupper(trim($order->delivery_address ?? '')) === 'PICKUP';

                    if ($itemCnt > 0) {
                        $rxCnt  = $order->items->where('requires_prescription', true)->count();
                        $otcCnt = $order->items->where('requires_prescription', false)->count();
                        if ($rxCnt > 0 && $otcCnt > 0) {
                            $typeLabel = 'Mixed (Rx+OTC)'; $tagClass = 'tag-mixed'; $typeIcon = 'fas fa-layer-group';
                        } elseif ($rxCnt > 0) {
                            $typeLabel = 'Cart + Rx';      $tagClass = 'tag-rx';    $typeIcon = 'fas fa-prescription';
                        } else {
                            $typeLabel = 'Cart OTC';       $tagClass = 'tag-otc';   $typeIcon = 'fas fa-pills';
                        }
                    } else {
                        $typeLabel = 'Prescription Only'; $tagClass = 'tag-presc'; $typeIcon = 'fas fa-file-medical';
                    }

                    // JS-ලට pass කරන item data JSON
                    $itemsJson = $order->items->map(fn($i) => [
                        'name'     => $i->medication_name,
                        'qty'      => $i->quantity,
                        'price'    => (float)$i->price,
                        'subtotal' => (float)$i->subtotal,
                        'isRx'     => (bool)optional($i->medication)->requires_prescription,
                    ])->toJson();
                @endphp

                <tr class="order-row">
                    {{-- Order # + Type --}}
                    <td class="ps-3">
                        <div class="fw-semibold text-primary" style="font-size:.85rem">
                            {{ $order->order_number }}
                        </div>
                        <span class="order-type-tag {{ $tagClass }}">
                            <i class="{{ $typeIcon }} me-1" style="font-size:.58rem"></i>{{ $typeLabel }}
                        </span>
                        @if($order->tracking_number)
                            <div class="mt-1">
                                <small class="text-muted" style="font-size:.68rem">
                                    <i class="fas fa-truck me-1"></i>{{ $order->tracking_number }}
                                </small>
                            </div>
                        @endif
                    </td>

                    {{-- Patient --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($order->patient?->profile_image)
                                <img src="{{ asset('storage/'.$order->patient->profile_image) }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:32px;height:32px;object-fit:cover">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center
                                            fw-bold text-white flex-shrink-0"
                                     style="width:32px;height:32px;background:#2563eb;font-size:.7rem">
                                    {{ strtoupper(substr($order->patient?->first_name ?? 'P', 0, 1)) }}
                                </div>
                            @endif
                            <div style="min-width:0">
                                <div style="font-size:.84rem;font-weight:600;white-space:nowrap;
                                            overflow:hidden;text-overflow:ellipsis;max-width:115px">
                                    {{ $order->patient?->first_name }} {{ $order->patient?->last_name }}
                                </div>
                                @if($order->patient?->phone)
                                    <small class="text-muted" style="font-size:.7rem">
                                        <i class="fas fa-phone me-1" style="font-size:.6rem"></i>
                                        {{ $order->patient->phone }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Date --}}
                    <td>
                        <div style="font-size:.82rem">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                        </div>
                        <small class="text-muted" style="font-size:.7rem">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                        </small>
                    </td>

                    {{-- Prescription --}}
                    <td class="text-center">
                        @if($hasPresc)
                            @php $ext = strtolower(pathinfo($order->prescription_file, PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                                <img src="{{ asset('storage/'.$order->prescription_file) }}"
                                     class="presc-thumb"
                                     onclick="viewPrescription('{{ asset('storage/'.$order->prescription_file) }}')"
                                     title="View Prescription" alt="Prescription">
                            @else
                                <a href="{{ asset('storage/'.$order->prescription_file) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-info rounded-pill"
                                   style="font-size:.7rem;padding:.2rem .55rem">
                                    <i class="fas fa-file-pdf me-1"></i>PDF
                                </a>
                            @endif
                        @else
                            <span class="text-muted" style="font-size:.75rem">
                                <i class="fas fa-minus-circle me-1"></i>None
                            </span>
                        @endif
                    </td>

                    {{-- Items --}}
                    <td class="text-center">
                        @if($itemCnt > 0)
                            <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                               class="badge text-decoration-none"
                               style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;
                                      border-radius:20px;font-size:.73rem;padding:.3rem .65rem">
                                <i class="fas fa-pills me-1" style="font-size:.6rem"></i>
                                {{ $itemCnt }} item{{ $itemCnt !== 1 ? 's' : '' }}
                            </a>
                        @else
                            <span class="badge"
                                  style="background:#f0f9ff;color:#0c4a6e;border:1px solid #bae6fd;
                                         border-radius:20px;font-size:.72rem">
                                <i class="fas fa-file-medical me-1" style="font-size:.6rem"></i>Presc.
                            </span>
                        @endif
                    </td>

                    {{-- Amount --}}
                    <td class="text-end">
                        <div class="fw-semibold" style="font-size:.88rem">
                            LKR {{ number_format($order->total_amount ?? 0, 2) }}
                        </div>
                        @if(($order->delivery_fee ?? 0) > 0)
                            <small class="text-muted" style="font-size:.7rem">
                                + LKR {{ number_format($order->delivery_fee, 2) }} del.
                            </small>
                        @endif
                        @if(($order->total_amount ?? 0) == 0 && $itemCnt === 0)
                            <small class="text-warning" style="font-size:.68rem">
                                <i class="fas fa-clock me-1"></i>Pending review
                            </small>
                        @endif
                    </td>

                    {{-- Payment --}}
                    <td class="text-center">
                        <span class="badge-pill bg-{{ $payBdg }} bg-opacity-15 text-{{ $payBdg }}">
                            <i class="fas fa-{{ $order->payment_status==='paid'?'check-circle':'clock' }} me-1"
                               style="font-size:.6rem"></i>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <div class="mt-1">
                            <small class="text-muted" style="font-size:.68rem">
                                @if($order->payment_method === 'cash_on_delivery')
                                    <i class="fas fa-money-bill me-1" style="color:#43a047"></i>COD
                                @else
                                    <i class="fas fa-credit-card me-1" style="color:#1565c0"></i>Online
                                @endif
                            </small>
                        </div>
                    </td>

                    {{-- Delivery --}}
                    <td class="text-center">
                        @if($isPickup)
                            <span style="font-size:.72rem;font-weight:600;color:#00796b;
                                         background:#e0f2f1;padding:.2rem .55rem;
                                         border-radius:20px;display:inline-flex;
                                         align-items:center;gap:.3rem">
                                <i class="fas fa-store" style="font-size:.6rem"></i>Pickup
                            </span>
                        @else
                            <span style="font-size:.72rem;font-weight:600;color:#1565c0;
                                         background:#e3f2fd;padding:.2rem .55rem;
                                         border-radius:20px;display:inline-flex;
                                         align-items:center;gap:.3rem">
                                <i class="fas fa-truck" style="font-size:.6rem"></i>Delivery
                            </span>
                            @if($order->delivery_method)
                                <div style="font-size:.67rem;color:#888;margin-top:.2rem;text-transform:capitalize">
                                    {{ str_replace('_', ' ', $order->delivery_method) }}
                                </div>
                            @endif
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        <span class="badge bg-{{ $bdg }} rounded-pill" style="font-size:.74rem">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">

                            {{-- View --}}
                            <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                               class="btn btn-sm btn-outline-primary rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye" style="font-size:.7rem"></i>
                            </a>

                            {{-- Update Status --}}
                            @if(!in_array($order->status, ['delivered','cancelled']))
                                <button class="btn btn-sm btn-outline-warning rounded-circle"
                                        style="width:30px;height:30px;padding:0;line-height:28px"
                                        data-bs-toggle="tooltip" title="Update Status"
                                        onclick="openStatusModal(
                                            {{ $order->id }},
                                            '{{ $order->status }}',
                                            '{{ $order->order_number }}',
                                            '{{ addslashes($order->tracking_number ?? '') }}',
                                            '{{ addslashes($order->pharmacist_notes ?? '') }}',
                                            {{ $itemCnt }},
                                            {{ floatval($order->total_amount ?? 0) }},
                                            {{ floatval($order->delivery_fee ?? 0) }},
                                            '{{ $tagClass }}',
                                            {{ $itemsJson }}
                                        )">
                                    <i class="fas fa-edit" style="font-size:.7rem"></i>
                                </button>
                            @endif

                            {{-- Set Amount --}}
                            @if(in_array($order->status, ['pending','verified']) && (($order->total_amount ?? 0) == 0 || $itemCnt === 0))
                                <button class="btn btn-sm btn-outline-success rounded-circle"
                                        style="width:30px;height:30px;padding:0;line-height:28px"
                                        data-bs-toggle="tooltip" title="Set Amount"
                                        onclick="openAmountModal(
                                            {{ $order->id }},
                                            '{{ $order->order_number }}',
                                            {{ floatval($order->total_amount ?? 0) }},
                                            {{ floatval($order->delivery_fee ?? 0) }}
                                        )">
                                    <i class="fas fa-tag" style="font-size:.7rem"></i>
                                </button>
                            @endif

                            {{-- Print --}}
                            <a href="{{ route('pharmacy.orders.print-invoice', $order->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-secondary rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Print Invoice">
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
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top flex-wrap gap-2">
            <small class="text-muted">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                of {{ $orders->total() }} orders
            </small>
            {{ $orders->withQueryString()->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
            <h6 class="fw-semibold">No orders found</h6>
            <p class="small mb-0">Try adjusting your filters.</p>
        </div>
        @endif
    </div>
</div>


{{-- ══ STATUS UPDATE MODAL ══ --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:#f8fafc">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2 text-warning"></i>Update Order Status
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- POST only — NO @method directive --}}
            <form id="statusForm" method="POST">
                @csrf
                {{-- active total_amount hidden input — JS updates this --}}
                <input type="hidden" name="total_amount" id="hiddenTotalAmount">
                <input type="hidden" name="delivery_fee" id="hiddenDeliveryFee" value="0">

                <div class="modal-body">

                    <div style="background:#fef9c3;border-left:3px solid #f59e0b;
                                padding:.6rem .9rem;border-radius:7px;
                                margin-bottom:1rem;font-size:.8rem;color:#92400e">
                        <i class="fas fa-info-circle me-1"></i>
                        Order: <strong id="modalOrderNumber"></strong>
                        &nbsp;|&nbsp;
                        <span id="modalTypeLabel" style="font-weight:700"></span>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold form-label-sm">
                            New Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="modalStatus" class="form-select" required>
                            <option value="pending">⏳ Pending</option>
                            <option value="verified">✅ Verified</option>
                            <option value="processing">⚙️ Processing</option>
                            <option value="ready">📦 Ready</option>
                            <option value="dispatched">🚚 Dispatched</option>
                            <option value="delivered">🎉 Delivered</option>
                            <option value="cancelled">❌ Cancelled</option>
                        </select>
                    </div>

                    {{-- Amount Section --}}
                    <div id="amountSection" style="display:none">

                        {{-- OTC: auto-calculated display --}}
                        <div id="amountOtcBlock" style="display:none">
                            <div style="background:#f0fdf4;border:1.5px solid #a5d6a7;
                                        border-radius:9px;padding:.75rem 1rem;margin-bottom:.9rem">
                                <div style="font-size:.75rem;font-weight:700;
                                            color:#00796b;margin-bottom:.4rem">
                                    <i class="fas fa-pills me-1"></i>Cart Items — Auto Calculated
                                </div>
                                <div id="otcItemsList"
                                     style="font-size:.78rem;color:#374151;line-height:1.9"></div>
                                <div style="border-top:1.5px solid #c8e6c9;margin-top:.5rem;
                                            padding-top:.5rem;display:flex;
                                            justify-content:space-between;align-items:center">
                                    <span style="font-size:.78rem;font-weight:600;color:#555">
                                        Medicines Subtotal
                                    </span>
                                    <span id="otcSubtotal"
                                          style="font-size:1rem;font-weight:800;color:#00796b"></span>
                                </div>
                            </div>
                            <div style="background:#e0f2f1;border-radius:8px;
                                        padding:.55rem .8rem;font-size:.78rem;
                                        color:#00796b;font-weight:600;margin-bottom:.9rem">
                                <i class="fas fa-lock me-1"></i>
                                Amount auto-set from cart. No manual entry needed.
                            </div>
                        </div>

                        {{-- Prescription / Rx: free entry --}}
                        <div id="amountPrescBlock" style="display:none">
                            <div style="background:#fff3e0;border:1.5px solid #ffcc80;
                                        border-radius:9px;padding:.7rem .9rem;margin-bottom:.9rem">
                                <div style="font-size:.76rem;font-weight:700;
                                            color:#e65100;margin-bottom:.2rem">
                                    <i class="fas fa-prescription me-1"></i>
                                    Prescription Verified — Enter Confirmed Amount
                                </div>
                                <div style="font-size:.72rem;color:#78350f">
                                    Manually enter the total medicine cost after reviewing the prescription.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold form-label-sm">
                                    Total Amount (LKR) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="prescAmountInput"
                                       class="form-control" min="0" step="0.01"
                                       placeholder="Enter confirmed medicine total...">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1 text-warning"></i>
                                    Patient will be notified with this amount.
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Fee --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold form-label-sm">
                                Delivery Fee (LKR)
                            </label>
                            <input type="number" id="deliveryFeeDisplay"
                                   class="form-control" min="0" step="0.01"
                                   value="0" placeholder="0.00">
                        </div>

                    </div>{{-- /amountSection --}}

                    {{-- Tracking --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold form-label-sm">Tracking Number</label>
                        <input type="text" name="tracking_number" id="modalTracking"
                               class="form-control" placeholder="Optional">
                    </div>

                    {{-- Notes --}}
                    <div class="mb-1">
                        <label class="form-label fw-semibold form-label-sm">Pharmacist Notes</label>
                        <textarea name="pharmacist_notes" id="modalNotes"
                                  class="form-control" rows="2"
                                  placeholder="Notes to patient..."></textarea>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══ SET AMOUNT MODAL ══ --}}
<div class="modal fade" id="amountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:#f0fdf4">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-tag me-2 text-success"></i>Set Order Amount
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- POST only — NO @method directive --}}
            <form id="amountForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Order: <strong id="amountModalOrderNum"></strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold form-label-sm">
                            Medicine Total (LKR) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="total_amount" id="amountInput"
                               class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold form-label-sm">
                            Delivery Fee (LKR)
                        </label>
                        <input type="number" name="delivery_fee" id="deliveryFeeInput"
                               class="form-control" min="0" step="0.01" value="0">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fas fa-check me-1"></i>Confirm Amount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══ PRESCRIPTION VIEW MODAL ══ --}}
<div class="modal fade" id="prescModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-file-medical me-2 text-info"></i>Prescription
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="prescImgPreview" src="" alt="Prescription"
                     style="max-width:100%;border-radius:8px;
                            max-height:75vh;object-fit:contain">
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ─── Status Modal ────────────────────────────────────────────────────────────
function openStatusModal(orderId, currentStatus, orderNumber, tracking, notes,
                         itemCnt, total, deliveryFee, tagClass, itemsData) {

    // Form action — POST
    document.getElementById('statusForm').action =
        `{{ url('pharmacy/orders') }}/${orderId}/update-status`;

    // Basic fields
    document.getElementById('modalOrderNumber').textContent = orderNumber;
    document.getElementById('modalTracking').value          = tracking || '';
    document.getElementById('modalNotes').value             = notes    || '';
    document.getElementById('deliveryFeeDisplay').value     = deliveryFee > 0 ? deliveryFee : '0';
    document.getElementById('hiddenDeliveryFee').value      = deliveryFee > 0 ? deliveryFee : '0';

    // Type label
    const typeLabels = {
        'tag-otc':   '🟢 Cart OTC',
        'tag-rx':    '🟠 Cart + Rx',
        'tag-mixed': '🟣 Mixed (Rx+OTC)',
        'tag-presc': '🔵 Prescription Only',
    };
    document.getElementById('modalTypeLabel').textContent = typeLabels[tagClass] || '';

    const isOtcCart = (tagClass === 'tag-otc');

    // Build OTC items list & calculate total
    let otcCalcTotal = 0;
    if (isOtcCart && itemsData && itemsData.length > 0) {
        let html = '';
        itemsData.forEach(item => {
            html += `<div style="display:flex;justify-content:space-between">
                <span><i class="fas fa-pills me-1"
                         style="color:#00796b;font-size:.65rem"></i>
                      ${item.name} × ${item.qty}</span>
                <span style="font-weight:600">LKR ${parseFloat(item.subtotal).toFixed(2)}</span>
            </div>`;
            otcCalcTotal += parseFloat(item.subtotal);
        });
        document.getElementById('otcItemsList').innerHTML = html;
        document.getElementById('otcSubtotal').textContent =
            'LKR ' + otcCalcTotal.toFixed(2);
    }

    // Status select — clone to remove old listeners
    const oldSel = document.getElementById('modalStatus');
    const newSel = oldSel.cloneNode(true);
    oldSel.parentNode.replaceChild(newSel, oldSel);
    newSel.value = currentStatus;

    const amtSection  = document.getElementById('amountSection');
    const otcBlock    = document.getElementById('amountOtcBlock');
    const prescBlock  = document.getElementById('amountPrescBlock');

    const syncHiddenFields = () => {
        // Delivery fee
        const fee = parseFloat(document.getElementById('deliveryFeeDisplay').value || 0);
        document.getElementById('hiddenDeliveryFee').value = isNaN(fee) ? 0 : fee;

        // Total amount
        if (isOtcCart) {
            document.getElementById('hiddenTotalAmount').value = otcCalcTotal.toFixed(2);
        } else {
            const v = document.getElementById('prescAmountInput').value;
            document.getElementById('hiddenTotalAmount').value = v || '';
        }
    };

    const updateVisibility = () => {
        const st = newSel.value;
        const showAmt = ['verified', 'processing', 'ready'].includes(st);
        amtSection.style.display = showAmt ? 'block' : 'none';

        if (showAmt) {
            if (isOtcCart) {
                otcBlock.style.display   = 'block';
                prescBlock.style.display = 'none';
                // Auto-set hidden amount for OTC
                document.getElementById('hiddenTotalAmount').value = otcCalcTotal.toFixed(2);
            } else {
                otcBlock.style.display   = 'none';
                prescBlock.style.display = 'block';
                // Pre-fill existing total
                if (total > 0) {
                    document.getElementById('prescAmountInput').value = total.toFixed(2);
                    document.getElementById('hiddenTotalAmount').value = total.toFixed(2);
                }
            }
        } else {
            // Clear hidden amount when not needed
            document.getElementById('hiddenTotalAmount').value = '';
        }
    };

    newSel.addEventListener('change', updateVisibility);

    // Sync delivery fee when changed
    document.getElementById('deliveryFeeDisplay').addEventListener('input', syncHiddenFields);

    // Sync presc amount when typed
    document.getElementById('prescAmountInput').addEventListener('input', () => {
        document.getElementById('hiddenTotalAmount').value =
            document.getElementById('prescAmountInput').value || '';
    });

    updateVisibility();

    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

// ─── Amount Modal ─────────────────────────────────────────────────────────────
function openAmountModal(orderId, orderNumber, currentTotal, currentFee) {
    document.getElementById('amountForm').action =
        `{{ url('pharmacy/orders') }}/${orderId}/set-amount`;
    document.getElementById('amountModalOrderNum').textContent = orderNumber;
    document.getElementById('amountInput').value      = currentTotal > 0 ? currentTotal : '';
    document.getElementById('deliveryFeeInput').value = currentFee   > 0 ? currentFee   : '0';
    new bootstrap.Modal(document.getElementById('amountModal')).show();
}

// ─── Prescription Preview ─────────────────────────────────────────────────────
function viewPrescription(url) {
    document.getElementById('prescImgPreview').src = url;
    new bootstrap.Modal(document.getElementById('prescModal')).show();
}

// ─── Tooltips ─────────────────────────────────────────────────────────────────
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
