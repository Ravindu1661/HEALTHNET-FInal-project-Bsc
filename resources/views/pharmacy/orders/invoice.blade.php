<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice — {{ $order->order_number }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Segoe UI',Arial,sans-serif;
            color:#1f2937; background:#fff; padding:20px;
        }
        .invoice-box { max-width:760px; margin:0 auto; }

        /* ── Header ── */
        .inv-header {
            display:flex; justify-content:space-between;
            align-items:flex-start; padding-bottom:20px;
            border-bottom:2.5px solid #2563eb; margin-bottom:20px;
        }
        .inv-title h1 { font-size:26px; font-weight:800; color:#2563eb; letter-spacing:.02em; }
        .inv-title p  { font-size:12px; color:#6b7280; margin-top:2px; }
        .inv-number   { text-align:right; }
        .inv-number .num { font-size:18px; font-weight:700; color:#1f2937; }
        .inv-number .dt  { font-size:12px; color:#6b7280; margin-top:2px; }

        /* ── Status Badge ── */
        .inv-status {
            display:inline-block; padding:4px 14px;
            border-radius:20px; font-size:12px; font-weight:700;
            margin-bottom:6px;
        }
        .status-pending    { background:#fef9c3; color:#92400e; }
        .status-verified   { background:#dbeafe; color:#1d4ed8; }
        .status-processing { background:#ede9fe; color:#5b21b6; }
        .status-ready      { background:#dcfce7; color:#15803d; }
        .status-dispatched { background:#f1f5f9; color:#475569; }
        .status-delivered  { background:#dcfce7; color:#15803d; }
        .status-cancelled  { background:#fee2e2; color:#b91c1c; }

        /* ── Order Type Tag ── */
        .order-type-tag {
            display:inline-block; padding:3px 11px; border-radius:20px;
            font-size:11px; font-weight:700; margin-left:6px; margin-bottom:18px;
        }
        .tag-otc   { background:#e8f5e9; color:#2e7d32; }
        .tag-rx    { background:#fff3e0; color:#e65100; }
        .tag-mixed { background:#f3e8ff; color:#6b21a8; }
        .tag-presc { background:#e0f2fe; color:#0c4a6e; }

        /* ── Info Grid ── */
        .info-section {
            display:grid; grid-template-columns:1fr 1fr;
            gap:24px; margin-bottom:24px;
        }
        .info-box { background:#f8fafc; border-radius:8px; padding:14px 16px; }
        .info-box h3 {
            font-size:10px; text-transform:uppercase;
            letter-spacing:.08em; color:#6b7280;
            font-weight:700; margin-bottom:10px;
        }
        .info-row { display:flex; gap:8px; margin-bottom:6px; font-size:12.5px; }
        .info-lbl { color:#9ca3af; min-width:110px; flex-shrink:0; }
        .info-val { color:#1f2937; font-weight:500; }

        /* ── Items Table ── */
        table { width:100%; border-collapse:collapse; }
        .tbl-head th {
            background:#1d4ed8; color:#fff; padding:9px 12px;
            font-size:11px; text-align:left;
            text-transform:uppercase; letter-spacing:.05em;
        }
        .tbl-head th:last-child,
        .tbl-head th:nth-last-child(-n+2) { text-align:right; }
        tbody td {
            padding:10px 12px; font-size:13px;
            border-bottom:1px solid #f1f5f9;
        }
        tbody tr:last-child td { border:none; }
        tbody td:last-child,
        tbody td:nth-last-child(-n+2) { text-align:right; }
        tbody tr:nth-child(even) { background:#f8fafc; }

        /* ── Rx Badge (inline table) ── */
        .rx-badge {
            background:#fff3e0; color:#e65100;
            font-size:10px; padding:1px 6px;
            border-radius:5px; font-weight:700;
            vertical-align:middle; margin-left:4px;
        }

        /* ── Prescription Only Amount Box ── */
        .presc-amount-box {
            background:#e0f7fa; border:1.5px solid #80deea;
            border-radius:10px; padding:18px 22px; margin-bottom:16px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .presc-amount-box .pa-label {
            font-size:13px; color:#006064; font-weight:600;
        }
        .presc-amount-box .pa-label small {
            display:block; font-size:11px;
            color:#00838f; font-weight:400; margin-top:2px;
        }
        .presc-amount-box .pa-amount {
            font-size:22px; font-weight:800; color:#00695c;
        }

        /* ── Totals ── */
        .t-row {
            display:flex; justify-content:space-between;
            padding:5px 0; font-size:13px; color:#6b7280;
            border-bottom:1px solid #f1f5f9;
        }
        .t-row:last-child { border:none; }
        .t-grand {
            display:flex; justify-content:space-between;
            padding:10px 0 0; font-size:16px; font-weight:700;
            color:#1d4ed8; border-top:2px solid #e5e7eb; margin-top:6px;
        }

        /* ── Notice Boxes ── */
        .notice-orange {
            background:#fff3e0; border:1px solid #ffcc80;
            border-radius:8px; padding:10px 14px;
            margin-bottom:16px; font-size:12px; color:#e65100;
        }
        .notice-blue {
            background:#e0f2fe; border:1px solid #81d4fa;
            border-radius:8px; padding:10px 14px;
            margin-bottom:16px; font-size:12px; color:#0c4a6e;
        }
        .notice-yellow {
            background:#fef9c3; border:1px solid #fde68a;
            border-radius:8px; padding:14px 18px;
            margin-bottom:16px; font-size:13px; color:#92400e;
        }

        /* ── Notes Box ── */
        .notes-box {
            background:#fffbeb; border:1px solid #fde68a;
            border-radius:8px; padding:12px 16px; margin-top:18px;
        }
        .notes-box h4 {
            font-size:11px; color:#92400e; font-weight:700;
            text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px;
        }
        .notes-box p { font-size:12.5px; color:#374151; line-height:1.6; }

        /* ── Footer ── */
        .inv-footer {
            margin-top:28px; padding-top:14px;
            border-top:1px solid #e5e7eb;
            display:flex; justify-content:space-between; align-items:flex-end;
        }
        .inv-footer p { font-size:11px; color:#9ca3af; line-height:1.7; }

        /* ── Print ── */
        @media print {
            body { padding:0; }
            .no-print { display:none !important; }
            .invoice-box { max-width:100%; }
        }
    </style>
</head>
<body>

@php
// ── Order type detection ──────────────────────────────────────────────────
$itemCnt    = $order->items->count();
$grandTotal = ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0);
$isPickup   = strtoupper(trim($order->delivery_address ?? '')) === 'PICKUP';

if ($itemCnt > 0) {
    $rxCnt  = $order->items->filter(fn($i) =>
                  optional($i->medication)->requires_prescription)->count();
    $otcCnt = $order->items->filter(fn($i) =>
                  !optional($i->medication)->requires_prescription)->count();

    if ($rxCnt > 0 && $otcCnt > 0) {
        $orderTypeLabel = 'Mixed (Rx + OTC)';
        $orderTypeClass = 'tag-mixed';
        $orderTypeIcon  = '🟣';
        $isPrescOnly    = false;
    } elseif ($rxCnt > 0) {
        $orderTypeLabel = 'Cart + Rx';
        $orderTypeClass = 'tag-rx';
        $orderTypeIcon  = '🟠';
        $isPrescOnly    = false;
    } else {
        $orderTypeLabel = 'Cart OTC';
        $orderTypeClass = 'tag-otc';
        $orderTypeIcon  = '🟢';
        $isPrescOnly    = false;
    }
} else {
    $orderTypeLabel = 'Prescription Only';
    $orderTypeClass = 'tag-presc';
    $orderTypeIcon  = '🔵';
    $isPrescOnly    = true;
}
@endphp

{{-- ── Print / Close Buttons ── --}}
<div class="no-print"
     style="max-width:760px;margin:0 auto 16px;display:flex;gap:10px">
    <button onclick="window.print()"
            style="background:#2563eb;color:#fff;border:none;
                   padding:8px 22px;border-radius:8px;
                   font-size:13px;font-weight:600;cursor:pointer">
        🖨️ Print Invoice
    </button>
    <button onclick="window.close()"
            style="background:#f1f5f9;color:#374151;border:none;
                   padding:8px 18px;border-radius:8px;
                   font-size:13px;cursor:pointer">
        ✕ Close
    </button>
</div>

<div class="invoice-box">

    {{-- ── Header ── --}}
    <div class="inv-header">
        <div class="inv-title">
            <h1>HEALTHNET</h1>
            <p>Pharmacy Invoice / Receipt</p>
            @if($order->pharmacy?->name)
                <p style="margin-top:3px;font-size:12px;
                           font-weight:600;color:#374151">
                    {{ $order->pharmacy->name }}
                </p>
            @endif
            @if($order->pharmacy?->address)
                <p style="margin-top:2px;font-size:11px;color:#9ca3af">
                    {{ $order->pharmacy->address }}
                </p>
            @endif
        </div>
        <div class="inv-number">
            <div class="num">{{ $order->order_number }}</div>
            <div class="dt">
                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
            </div>
            @if($order->pharmacy?->phone)
                <div class="dt" style="margin-top:4px">
                    📞 {{ $order->pharmacy->phone }}
                </div>
            @endif
        </div>
    </div>

    {{-- ── Status + Order Type ── --}}
    <div style="margin-bottom:18px">
        <span class="inv-status status-{{ $order->status }}">
            {{ strtoupper($order->status) }}
        </span>
        <span class="order-type-tag {{ $orderTypeClass }}">
            {{ $orderTypeIcon }} {{ $orderTypeLabel }}
        </span>
    </div>

    {{-- ── Info Grid ── --}}
    <div class="info-section">

        {{-- Patient --}}
        <div class="info-box">
            <h3>👤 Patient</h3>
            <div class="info-row">
                <span class="info-lbl">Name</span>
                <span class="info-val">
                    {{ $order->patient?->first_name }}
                    {{ $order->patient?->last_name }}
                </span>
            </div>
            @if($order->patient?->user?->email)
            <div class="info-row">
                <span class="info-lbl">Email</span>
                <span class="info-val">{{ $order->patient->user->email }}</span>
            </div>
            @endif
            @if($order->patient?->phone)
            <div class="info-row">
                <span class="info-lbl">Phone</span>
                <span class="info-val">{{ $order->patient->phone }}</span>
            </div>
            @endif
        </div>

        {{-- Order Meta --}}
        <div class="info-box">
            <h3>📦 Order Info</h3>
            <div class="info-row">
                <span class="info-lbl">Payment</span>
                <span class="info-val">
                    {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Pay Status</span>
                <span class="info-val"
                      style="color:{{ $order->payment_status==='paid'
                                       ?'#15803d':'#dc2626' }};font-weight:700">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
            @if($order->delivery_method && !$isPickup)
            <div class="info-row">
                <span class="info-lbl">Delivery Via</span>
                <span class="info-val">
                    {{ ucwords(str_replace('_', ' ', $order->delivery_method)) }}
                </span>
            </div>
            @endif
            @if($order->tracking_number)
            <div class="info-row">
                <span class="info-lbl">Tracking #</span>
                <span class="info-val" style="color:#2563eb">
                    {{ $order->tracking_number }}
                </span>
            </div>
            @endif
            <div class="info-row" style="align-items:flex-start">
                <span class="info-lbl">Address</span>
                <span class="info-val">
                    {{ $isPickup ? '🏪 Pickup from pharmacy' : $order->delivery_address }}
                </span>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════
         PRESCRIPTION ONLY — No items, show amount box
    ════════════════════════════════════════════════════ --}}
    @if($isPrescOnly)

        <div class="notice-blue">
            📋 <strong>Prescription Only Order</strong> —
            Placed with a prescription file.
            Amount confirmed by the pharmacist after review.
        </div>

        @if(($order->total_amount ?? 0) > 0)

            <div class="presc-amount-box">
                <div class="pa-label">
                    Confirmed Medicine Amount
                    <small>Set by pharmacist after prescription review</small>
                </div>
                <div class="pa-amount">
                    LKR {{ number_format($order->total_amount, 2) }}
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-bottom:20px">
                <div style="min-width:280px">
                    <div class="t-row">
                        <span>Medicine Amount</span>
                        <span>LKR {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if(($order->delivery_fee ?? 0) > 0)
                    <div class="t-row">
                        <span>Delivery Fee</span>
                        <span>LKR {{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="t-grand">
                        <span>Grand Total</span>
                        <span>LKR {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

        @else
            <div class="notice-yellow">
                ⏳ <strong>Amount Pending</strong> —
                The pharmacist has not yet confirmed the medicine
                amount for this order.
            </div>
        @endif

    {{-- ════════════════════════════════════════════════════
         CART ORDERS — Items table
    ════════════════════════════════════════════════════ --}}
    @else

        {{-- Rx / Mixed notice --}}
        @if(in_array($orderTypeClass, ['tag-rx','tag-mixed']))
        <div class="notice-orange">
            🟠 This order contains <strong>prescription medicines</strong>.
            A valid prescription was verified before dispensing.
        </div>
        @endif

        {{-- Items Table --}}
        <table>
            <thead class="tbl-head">
                <tr>
                    <th style="width:32px">#</th>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td style="color:#9ca3af">{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item->medication_name }}</strong>
                        @if(optional($item->medication)->requires_prescription)
                            <span class="rx-badge">Rx</span>
                        @endif
                    </td>
                    <td style="color:#6b7280">
                        {{ $item->medication?->dosage ?? '—' }}
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>LKR {{ number_format($item->price, 2) }}</td>
                    <td>
                        <strong>LKR {{ number_format($item->subtotal, 2) }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div style="display:flex;justify-content:flex-end;
                    margin-top:12px;margin-bottom:20px">
            <div style="min-width:280px">
                <div class="t-row">
                    <span>Medicines Total</span>
                    <span>LKR {{ number_format($order->total_amount ?? 0, 2) }}</span>
                </div>
                @if(($order->delivery_fee ?? 0) > 0)
                <div class="t-row">
                    <span>Delivery Fee</span>
                    <span>LKR {{ number_format($order->delivery_fee, 2) }}</span>
                </div>
                @endif
                <div class="t-grand">
                    <span>Grand Total</span>
                    <span>LKR {{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>

    @endif
    {{-- /order type --}}

    {{-- ── Pharmacist Notes ── --}}
    @if($order->pharmacist_notes)
    <div class="notes-box">
        <h4>📋 Pharmacist Notes</h4>
        <p>{{ $order->pharmacist_notes }}</p>
    </div>
    @endif

    {{-- ── Cancellation Reason ── --}}
    @if($order->status === 'cancelled' && $order->cancelled_reason)
    <div style="background:#fef2f2;border:1px solid #fca5a5;
                border-radius:8px;padding:12px 16px;margin-top:14px">
        <h4 style="font-size:11px;color:#b91c1c;font-weight:700;
                   text-transform:uppercase;margin-bottom:6px">
            ❌ Cancellation Reason
        </h4>
        <p style="font-size:12.5px;color:#374151">
            {{ $order->cancelled_reason }}
        </p>
    </div>
    @endif

    {{-- ── Footer ── --}}
    <div class="inv-footer">
        <p>
            Generated: {{ now()->format('d M Y, h:i A') }}<br>
            HEALTHNET Healthcare Platform
        </p>
        <div style="text-align:right">
            <div style="border-top:1.5px solid #e5e7eb;
                        padding-top:6px;min-width:180px;text-align:center">
                <p style="font-size:11px;color:#6b7280">Authorized Signature</p>
                @if($order->pharmacy?->name)
                    <p style="font-size:12px;font-weight:600;
                               color:#1f2937;margin-top:4px">
                        {{ $order->pharmacy->name }}
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>
</body>
</html>
