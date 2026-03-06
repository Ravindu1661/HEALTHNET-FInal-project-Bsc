{{-- resources/views/pharmacy/orders/show.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@push('styles')
<style>
.info-label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #9ca3af; margin-bottom: .2rem;
}
.info-value {
    font-size: .88rem; font-weight: 600; color: #1f2937;
}
.section-card {
    background: #fff; border: 1.5px solid #e5e7eb;
    border-radius: 12px; margin-bottom: 1.25rem;
}
.section-card .card-header-custom {
    padding: .75rem 1.1rem;
    border-bottom: 1.5px solid #f3f4f6;
    font-size: .78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: #6b7280; background: #f9fafb;
    border-radius: 10px 10px 0 0;
    display: flex; align-items: center; gap: .5rem;
}
.timeline-step {
    display: flex; align-items: flex-start; gap: .8rem;
    padding: .5rem 0; position: relative;
}
.timeline-step:not(:last-child)::after {
    content: ''; position: absolute;
    left: 14px; top: 30px;
    width: 2px; height: calc(100% - 10px);
    background: #e5e7eb;
}
.timeline-dot {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; flex-shrink: 0; z-index: 1;
}
.step-done   { background: #d1fae5; color: #059669; }
.step-active { background: #dbeafe; color: #2563eb; }
.step-todo   { background: #f3f4f6; color: #9ca3af; }

.item-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .55rem 0; border-bottom: 1px solid #f3f4f6; font-size: .84rem;
}
.item-row:last-child { border-bottom: none; }

.action-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .42rem .9rem; border-radius: 8px;
    font-size: .78rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .15s;
}
.badge-status {
    padding: .35rem .8rem; border-radius: 20px;
    font-size: .8rem; font-weight: 700;
}
</style>
@endpush

@section('content')

{{-- Alerts --}}
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
$statusFlow = ['pending','verified','processing','ready','dispatched','delivered'];

$statusColors = [
    'pending'    => ['bg'=>'#fef3c7','color'=>'#92400e','badge'=>'warning'],
    'verified'   => ['bg'=>'#e0f2fe','color'=>'#0c4a6e','badge'=>'info'],
    'processing' => ['bg'=>'#dbeafe','color'=>'#1e3a8a','badge'=>'primary'],
    'ready'      => ['bg'=>'#d1fae5','color'=>'#064e3b','badge'=>'success'],
    'dispatched' => ['bg'=>'#f3f4f6','color'=>'#374151','badge'=>'secondary'],
    'delivered'  => ['bg'=>'#d1fae5','color'=>'#064e3b','badge'=>'success'],
    'cancelled'  => ['bg'=>'#fee2e2','color'=>'#7f1d1d','badge'=>'danger'],
];
$sc = $statusColors[$order->status] ?? $statusColors['pending'];

$itemCnt = $order->items->count();
$isPickup = strtoupper(trim($order->delivery_address ?? '')) === 'PICKUP';
$grandTotal = ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0);
@endphp

{{-- ── Page Header ── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('pharmacy.orders.index') }}"
               class="btn btn-sm btn-outline-secondary rounded-pill"
               style="font-size:.75rem">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            <h5 class="fw-bold mb-0">Order #{{ $order->order_number }}</h5>
            <span class="badge-status"
                  style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            Created {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
            &nbsp;·&nbsp;
            <i class="fas fa-sync me-1"></i>
            Updated {{ \Carbon\Carbon::parse($order->updated_at)->diffForHumans() }}
        </small>
    </div>

    {{-- Quick Action Buttons --}}
    <div class="d-flex gap-2 flex-wrap">

        {{-- Print Invoice --}}
        <a href="{{ route('pharmacy.orders.print-invoice', $order->id) }}"
           target="_blank"
           class="action-btn btn-outline-secondary"
           style="border:1.5px solid #d1d5db;background:#fff;color:#374151">
            <i class="fas fa-print"></i> Print Invoice
        </a>

        {{-- Download Prescription --}}
        @if($order->prescription_file)
            <a href="{{ route('pharmacy.orders.download-prescription', $order->id) }}"
               class="action-btn"
               style="background:#e0f2fe;color:#0c4a6e">
                <i class="fas fa-file-download"></i> Download Rx
            </a>
        @endif

        {{-- Status-based action buttons --}}
        @if($order->status === 'verified')
            <form action="{{ route('pharmacy.orders.process', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="action-btn"
                        style="background:#dbeafe;color:#1e3a8a">
                    <i class="fas fa-cog"></i> Mark Processing
                </button>
            </form>
        @endif

        @if($order->status === 'processing')
            <form action="{{ route('pharmacy.orders.ready', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="action-btn"
                        style="background:#d1fae5;color:#064e3b">
                    <i class="fas fa-box-open"></i> Mark Ready
                </button>
            </form>
        @endif

        @if($order->status === 'ready')
            <button class="action-btn"
                    style="background:#f3f4f6;color:#374151"
                    onclick="document.getElementById('dispatchModal').style.display='flex'">
                <i class="fas fa-truck"></i> Dispatch
            </button>
        @endif

        @if($order->status === 'dispatched')
            <form action="{{ route('pharmacy.orders.deliver', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="action-btn"
                        style="background:#d1fae5;color:#064e3b">
                    <i class="fas fa-check-circle"></i> Mark Delivered
                </button>
            </form>
        @endif

        @if($order->canBeCancelled())
            <button class="action-btn"
                    style="background:#fee2e2;color:#7f1d1d"
                    onclick="document.getElementById('cancelModal').style.display='flex'">
                <i class="fas fa-times-circle"></i> Cancel Order
            </button>
        @endif

    </div>
</div>

<div class="row g-3">

    {{-- ═══ LEFT COLUMN ═══ --}}
    <div class="col-lg-8">

        {{-- ── Order Items ── --}}
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-pills text-primary"></i>
                Order Items
                @if($itemCnt > 0)
                    <span class="ms-auto badge bg-primary rounded-pill">{{ $itemCnt }}</span>
                @endif
            </div>
            <div class="p-3">

                @if($order->status === 'pending')
                    {{-- ── VERIFY FORM ── --}}
                    <div style="background:#fef9c3;border-left:3px solid #f59e0b;
                                padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem;
                                font-size:.8rem;color:#92400e">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This order is <strong>pending verification</strong>.
                        Review the prescription and add medicines below.
                    </div>

                    <form action="{{ route('pharmacy.orders.verify', $order->id) }}"
                          method="POST" id="verifyForm">
                        @csrf

                        <div id="itemsContainer">
                            @if($itemCnt > 0)
                                @foreach($order->items as $idx => $item)
                                <div class="item-entry border rounded p-3 mb-2"
                                     style="background:#f9fafb" data-index="{{ $idx }}">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label form-label-sm">Type</label>
                                            <select name="items[{{ $idx }}][type]"
                                                    class="form-select form-select-sm item-type-sel"
                                                    onchange="toggleMedSelect(this, {{ $idx }})">
                                                <option value="medicine" {{ $item->medication_id ? 'selected':'' }}>
                                                    Medicine (DB)
                                                </option>
                                                <option value="other" {{ !$item->medication_id ? 'selected':'' }}>
                                                    Other/Custom
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 med-select-col-{{ $idx }}"
                                             style="{{ !$item->medication_id ? 'display:none' : '' }}">
                                            <label class="form-label form-label-sm">Medicine</label>
                                            <select name="items[{{ $idx }}][medication_id]"
                                                    class="form-select form-select-sm">
                                                <option value="">Select medicine...</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}"
                                                        {{ $item->medication_id == $med->id ? 'selected':'' }}>
                                                        {{ $med->name }}
                                                        ({{ $med->stock_quantity }} in stock)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label form-label-sm">Medicine Name</label>
                                            <input type="text"
                                                   name="items[{{ $idx }}][medicine_name]"
                                                   class="form-control form-control-sm"
                                                   value="{{ $item->medication_name }}"
                                                   placeholder="Medicine name" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label form-label-sm">Qty</label>
                                            <input type="number"
                                                   name="items[{{ $idx }}][quantity]"
                                                   class="form-control form-control-sm"
                                                   value="{{ $item->quantity }}"
                                                   min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label form-label-sm">Price (LKR)</label>
                                            <input type="number"
                                                   name="items[{{ $idx }}][price]"
                                                   class="form-control form-control-sm"
                                                   value="{{ $item->price }}"
                                                   min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-circle"
                                                    onclick="removeItem(this)"
                                                    style="width:28px;height:28px;padding:0;line-height:26px">
                                                <i class="fas fa-times" style="font-size:.65rem"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary mb-3"
                                onclick="addItem()">
                            <i class="fas fa-plus me-1"></i>Add Item
                        </button>

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold">
                                    Delivery Fee (LKR)
                                </label>
                                <input type="number" name="delivery_fee"
                                       class="form-control form-control-sm"
                                       value="{{ $order->delivery_fee ?? 0 }}"
                                       min="0" step="0.01">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label form-label-sm fw-semibold">
                                    Pharmacist Notes
                                </label>
                                <textarea name="pharmacist_notes"
                                          class="form-control form-control-sm"
                                          rows="2"
                                          placeholder="Notes to patient...">{{ $order->pharmacist_notes }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-check-circle me-1"></i>
                            Verify Order & Send Invoice
                        </button>
                    </form>

                @else
                    {{-- ── Items Display (verified+) ── --}}
                    @if($itemCnt > 0)
                        @foreach($order->items as $item)
                        <div class="item-row">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:8px;
                                            background:#eff6ff;display:flex;align-items:center;
                                            justify-content:center;flex-shrink:0">
                                    <i class="fas fa-pills text-primary" style="font-size:.65rem"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.84rem">
                                        {{ $item->medication_name }}
                                    </div>
                                    <small class="text-muted" style="font-size:.7rem">
                                        LKR {{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                        @if($item->medication?->requires_prescription)
                                            &nbsp;<span style="background:#fff3e0;color:#e65100;
                                                               font-size:.65rem;padding:.1rem .4rem;
                                                               border-radius:5px;font-weight:700">Rx</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="fw-bold text-end" style="font-size:.88rem">
                                LKR {{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                        @endforeach

                        {{-- Totals --}}
                        <div style="background:#f9fafb;border-radius:8px;
                                    padding:.75rem 1rem;margin-top:.75rem">
                            <div class="d-flex justify-content-between mb-1"
                                 style="font-size:.83rem;color:#6b7280">
                                <span>Medicines Subtotal</span>
                                <span>LKR {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            @if(($order->delivery_fee ?? 0) > 0)
                            <div class="d-flex justify-content-between mb-1"
                                 style="font-size:.83rem;color:#6b7280">
                                <span>Delivery Fee</span>
                                <span>LKR {{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between pt-2"
                                 style="border-top:1.5px solid #e5e7eb;
                                        font-size:1rem;font-weight:800;color:#1f2937">
                                <span>Grand Total</span>
                                <span>LKR {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-medical fa-2x mb-2 d-block opacity-50"></i>
                            <div style="font-size:.85rem">Prescription only order — no cart items</div>
                        </div>
                        {{-- Show grand total if set --}}
                        @if(($order->total_amount ?? 0) > 0)
                        <div style="background:#f0fdf4;border:1.5px solid #a5d6a7;
                                    border-radius:8px;padding:.75rem 1rem;margin-top:.5rem">
                            <div class="d-flex justify-content-between"
                                 style="font-size:1rem;font-weight:800;color:#064e3b">
                                <span>Confirmed Amount</span>
                                <span>LKR {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>
                        @endif
                    @endif

                    {{-- Pharmacist Notes --}}
                    @if($order->pharmacist_notes)
                    <div style="background:#fef9c3;border-left:3px solid #f59e0b;
                                padding:.6rem .9rem;border-radius:7px;margin-top:.75rem;
                                font-size:.8rem;color:#92400e">
                        <div style="font-weight:700;margin-bottom:.2rem">
                            <i class="fas fa-comment-medical me-1"></i>Pharmacist Notes
                        </div>
                        {{ $order->pharmacist_notes }}
                    </div>
                    @endif
                @endif

            </div>
        </div>

        {{-- ── Prescription File ── --}}
        @if($order->prescription_file)
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-file-medical text-info"></i>Prescription File
            </div>
            <div class="p-3 text-center">
                @php $ext = strtolower(pathinfo($order->prescription_file, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext, ['jpg','jpeg','png']))
                    <img src="{{ asset('storage/'.$order->prescription_file) }}"
                         alt="Prescription"
                         style="max-width:100%;max-height:400px;border-radius:10px;
                                border:1.5px solid #e0f2fe;object-fit:contain;cursor:pointer"
                         onclick="this.requestFullscreen()">
                @else
                    <div style="padding:2rem">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3 d-block"></i>
                        <a href="{{ asset('storage/'.$order->prescription_file) }}"
                           target="_blank"
                           class="btn btn-outline-danger rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i>Open PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- ═══ RIGHT COLUMN ═══ --}}
    <div class="col-lg-4">

        {{-- ── Order Status Timeline ── --}}
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-stream text-primary"></i>Status Timeline
            </div>
            <div class="p-3">
                @php
                $statusIdx = array_search($order->status, $statusFlow);
                @endphp
                @foreach($statusFlow as $idx => $step)
                @php
                $stepIcons = [
                    'pending'    => 'fa-clock',
                    'verified'   => 'fa-check',
                    'processing' => 'fa-cog',
                    'ready'      => 'fa-box-open',
                    'dispatched' => 'fa-truck',
                    'delivered'  => 'fa-check-double',
                ];
                $stepLabels = [
                    'pending'    => 'Order Received',
                    'verified'   => 'Verified & Invoiced',
                    'processing' => 'Processing',
                    'ready'      => 'Ready for Delivery',
                    'dispatched' => 'Dispatched',
                    'delivered'  => 'Delivered',
                ];
                if ($order->status === 'cancelled') {
                    $dotClass = 'step-todo';
                } elseif ($idx < $statusIdx) {
                    $dotClass = 'step-done';
                } elseif ($idx === $statusIdx) {
                    $dotClass = 'step-active';
                } else {
                    $dotClass = 'step-todo';
                }
                @endphp
                <div class="timeline-step">
                    <div class="timeline-dot {{ $dotClass }}">
                        <i class="fas {{ $stepIcons[$step] }}"></i>
                    </div>
                    <div style="padding-top:.3rem">
                        <div style="font-size:.82rem;font-weight:{{ $dotClass==='step-active'?'700':'500' }};
                                    color:{{ $dotClass==='step-todo'?'#9ca3af':'#1f2937' }}">
                            {{ $stepLabels[$step] }}
                        </div>
                    </div>
                </div>
                @endforeach

                @if($order->status === 'cancelled')
                <div class="timeline-step">
                    <div class="timeline-dot" style="background:#fee2e2;color:#dc2626">
                        <i class="fas fa-times"></i>
                    </div>
                    <div style="padding-top:.3rem">
                        <div style="font-size:.82rem;font-weight:700;color:#dc2626">
                            Cancelled
                        </div>
                        @if($order->cancelled_reason)
                            <div style="font-size:.72rem;color:#6b7280;margin-top:.2rem">
                                {{ $order->cancelled_reason }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($order->tracking_number)
                <div style="background:#f0fdf4;border-radius:8px;padding:.6rem .8rem;
                            margin-top:.5rem;font-size:.78rem">
                    <i class="fas fa-truck me-1 text-success"></i>
                    <strong>Tracking:</strong> {{ $order->tracking_number }}
                </div>
                @endif
            </div>
        </div>

        {{-- ── Patient Info ── --}}
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-user text-success"></i>Patient
            </div>
            <div class="p-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    @if($order->patient?->profile_image)
                        <img src="{{ asset('storage/'.$order->patient->profile_image) }}"
                             class="rounded-circle"
                             style="width:40px;height:40px;object-fit:cover">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center
                                    fw-bold text-white"
                             style="width:40px;height:40px;background:#2563eb;font-size:.85rem">
                            {{ strtoupper(substr($order->patient?->first_name ?? 'P', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fw-bold" style="font-size:.9rem">
                            {{ $order->patient?->first_name }} {{ $order->patient?->last_name }}
                        </div>
                        <small class="text-muted" style="font-size:.72rem">
                            {{ $order->patient?->user?->email }}
                        </small>
                    </div>
                </div>
                @if($order->patient?->phone)
                <div class="mb-2">
                    <div class="info-label">Phone</div>
                    <div class="info-value">
                        <i class="fas fa-phone me-1 text-success" style="font-size:.75rem"></i>
                        {{ $order->patient->phone }}
                    </div>
                </div>
                @endif
                @if($order->patient?->date_of_birth)
                <div>
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($order->patient->date_of_birth)->format('d M Y') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Delivery & Payment ── --}}
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-truck text-warning"></i>Delivery & Payment
            </div>
            <div class="p-3">

                <div class="mb-3">
                    <div class="info-label">Delivery Address</div>
                    <div class="info-value">
                        @if($isPickup)
                            <span style="background:#e0f2f1;color:#00796b;
                                         padding:.2rem .6rem;border-radius:6px;
                                         font-size:.8rem">
                                <i class="fas fa-store me-1"></i>Pickup
                            </span>
                        @else
                            <i class="fas fa-map-marker-alt me-1 text-danger"
                               style="font-size:.75rem"></i>
                            {{ $order->delivery_address }}
                        @endif
                    </div>
                </div>

                @if($order->delivery_method && !$isPickup)
                <div class="mb-3">
                    <div class="info-label">Delivery Method</div>
                    <div class="info-value" style="text-transform:capitalize">
                        {{ str_replace('_', ' ', $order->delivery_method) }}
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">
                        @if($order->payment_method === 'cash_on_delivery')
                            <i class="fas fa-money-bill me-1" style="color:#43a047"></i>Cash on Delivery
                        @else
                            <i class="fas fa-credit-card me-1" style="color:#1565c0"></i>Online Payment
                        @endif
                    </div>
                </div>

                <div>
                    <div class="info-label">Payment Status</div>
                    @php $pBdg = $order->payment_status === 'paid' ? 'success' : 'danger'; @endphp
                    <span class="badge bg-{{ $pBdg }} bg-opacity-15 text-{{ $pBdg }} rounded-pill"
                          style="font-size:.78rem;font-weight:700;padding:.3rem .75rem">
                        <i class="fas fa-{{ $order->payment_status==='paid'?'check-circle':'clock' }} me-1"
                           style="font-size:.65rem"></i>
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- ── Payment Record ── --}}
        @if($order->payment)
        <div class="section-card">
            <div class="card-header-custom">
                <i class="fas fa-receipt text-success"></i>Payment Record
            </div>
            <div class="p-3">
                <div class="mb-2">
                    <div class="info-label">Transaction ID</div>
                    <div class="info-value" style="font-family:monospace">
                        {{ $order->payment->transaction_id ?? '—' }}
                    </div>
                </div>
                <div class="mb-2">
                    <div class="info-label">Amount Paid</div>
                    <div class="info-value">
                        LKR {{ number_format($order->payment->amount ?? 0, 2) }}
                    </div>
                </div>
                <div>
                    <div class="info-label">Paid At</div>
                    <div class="info-value">
                        {{ $order->payment->paid_at
                            ? \Carbon\Carbon::parse($order->payment->paid_at)->format('d M Y, h:i A')
                            : '—' }}
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     DISPATCH MODAL
══════════════════════════════════════════════════════════ --}}
<div id="dispatchModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
            z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:1.5rem;
                width:360px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
        <h6 class="fw-bold mb-3">
            <i class="fas fa-truck me-2 text-secondary"></i>Dispatch Order
        </h6>
        <form action="{{ route('pharmacy.orders.dispatch', $order->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold form-label-sm">
                    Tracking Number <span class="text-danger">*</span>
                </label>
                <input type="text" name="tracking_number"
                       class="form-control" placeholder="e.g. TRK-20260306-001" required>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light"
                        onclick="document.getElementById('dispatchModal').style.display='none'">
                    Cancel
                </button>
                <button type="submit" class="btn btn-secondary rounded-pill px-4">
                    <i class="fas fa-truck me-1"></i>Dispatch
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     CANCEL MODAL
══════════════════════════════════════════════════════════ --}}
<div id="cancelModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);
            z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:1.5rem;
                width:400px;box-shadow:0 20px 60px rgba(0,0,0,.15)">
        <h6 class="fw-bold mb-1 text-danger">
            <i class="fas fa-times-circle me-2"></i>Cancel Order
        </h6>
        <p class="text-muted small mb-3">
            Order #{{ $order->order_number }} will be cancelled and stock restored.
        </p>
        <form action="{{ route('pharmacy.orders.cancel', $order->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold form-label-sm">
                    Reason <span class="text-danger">*</span>
                </label>
                <textarea name="cancelled_reason" class="form-control"
                          rows="3" required
                          placeholder="Reason for cancellation..."></textarea>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light"
                        onclick="document.getElementById('cancelModal').style.display='none'">
                    Keep Order
                </button>
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-times me-1"></i>Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

@endsection


@push('scripts')
<script>
let itemIndex = {{ $order->items->count() > 0 ? $order->items->count() : 0 }};

// ── Add new item row ──────────────────────────────────────────────────────────
function addItem() {
    const container = document.getElementById('itemsContainer');
    const idx       = itemIndex++;
    const medOptions = `
        <option value="">Select medicine...</option>
        @foreach($medicines as $med)
        <option value="{{ $med->id }}">{{ addslashes($med->name) }} ({{ $med->stock_quantity }} in stock)</option>
        @endforeach
    `;

    container.insertAdjacentHTML('beforeend', `
        <div class="item-entry border rounded p-3 mb-2" style="background:#f9fafb" data-index="${idx}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Type</label>
                    <select name="items[${idx}][type]"
                            class="form-select form-select-sm item-type-sel"
                            onchange="toggleMedSelect(this, ${idx})">
                        <option value="medicine">Medicine (DB)</option>
                        <option value="other">Other/Custom</option>
                    </select>
                </div>
                <div class="col-md-4 med-select-col-${idx}">
                    <label class="form-label form-label-sm">Medicine</label>
                    <select name="items[${idx}][medication_id]" class="form-select form-select-sm">
                        ${medOptions}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-sm">Medicine Name</label>
                    <input type="text" name="items[${idx}][medicine_name]"
                           class="form-control form-control-sm"
                           placeholder="Medicine name" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Qty</label>
                    <input type="number" name="items[${idx}][quantity]"
                           class="form-control form-control-sm"
                           value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Price (LKR)</label>
                    <input type="number" name="items[${idx}][price]"
                           class="form-control form-control-sm"
                           min="0" step="0.01" placeholder="0.00" required>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger rounded-circle"
                            onclick="removeItem(this)"
                            style="width:28px;height:28px;padding:0;line-height:26px">
                        <i class="fas fa-times" style="font-size:.65rem"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
}

// ── Remove item row ───────────────────────────────────────────────────────────
function removeItem(btn) {
    btn.closest('.item-entry').remove();
}

// ── Toggle medicine select / name input ──────────────────────────────────────
function toggleMedSelect(sel, idx) {
    const medCol = document.querySelector(`.med-select-col-${idx}`);
    if (sel.value === 'medicine') {
        medCol.style.display = 'block';
    } else {
        medCol.style.display = 'none';
        const medSel = medCol.querySelector('select');
        if (medSel) medSel.value = '';
    }
}

// ── Close modals on backdrop click ───────────────────────────────────────────
['dispatchModal','cancelModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
@endpush
