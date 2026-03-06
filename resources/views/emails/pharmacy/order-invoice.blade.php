<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice — {{ $order->order_number }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#f0f4f8; color:#1f2937; }
        .wrapper { max-width:620px; margin:30px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }

        /* Header */
        .header { background:linear-gradient(135deg,#1d4ed8,#6d28d9); padding:30px 36px; color:#fff; }
        .header h1 { font-size:22px; font-weight:700; margin-bottom:4px; }
        .header p  { font-size:13px; opacity:.85; }

        /* Status Banner */
        .status-banner { padding:12px 36px; font-size:13px; font-weight:600; }
        .status-verified  { background:#eff6ff; color:#1d4ed8; border-left:4px solid #2563eb; }
        .status-delivered { background:#f0fdf4; color:#15803d; border-left:4px solid #16a34a; }

        /* Section */
        .section { padding:24px 36px; border-bottom:1px solid #f1f5f9; }
        .section:last-child { border:none; }
        .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; margin-bottom:14px; }

        /* Info Grid */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px 20px; }
        .info-item label { font-size:11px; color:#9ca3af; display:block; margin-bottom:2px; }
        .info-item span  { font-size:13px; color:#1f2937; font-weight:500; }

        /* Table */
        table { width:100%; border-collapse:collapse; font-size:13px; }
        thead tr { background:#f8fafc; }
        thead th { padding:10px 12px; text-align:left; font-size:11px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
        tbody td { padding:10px 12px; border-bottom:1px solid #f1f5f9; color:#374151; }
        tbody tr:last-child td { border:none; }
        .text-right { text-align:right; }
        .fw-bold { font-weight:700; }

        /* Totals */
        .totals { padding:16px 36px; background:#f8fafc; }
        .total-row { display:flex; justify-content:space-between; font-size:13px; padding:4px 0; color:#6b7280; }
        .total-grand { display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#1d4ed8; padding-top:10px; margin-top:6px; border-top:2px solid #e5e7eb; }

        /* Footer */
        .footer { padding:24px 36px; text-align:center; background:#f8fafc; }
        .footer p { font-size:12px; color:#9ca3af; line-height:1.6; }
        .footer a { color:#2563eb; text-decoration:none; }

        /* Badge */
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
        .badge-paid   { background:#dcfce7; color:#15803d; }
        .badge-unpaid { background:#fef2f2; color:#dc2626; }
        .badge-cod    { background:#fef9c3; color:#92400e; }

        /* Button */
        .btn { display:inline-block; padding:10px 28px; background:#2563eb; color:#fff; text-decoration:none; border-radius:8px; font-weight:600; font-size:13px; margin-top:8px; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="header">
        <table style="width:100%">
            <tr>
                <td>
                    <h1>HEALTHNET Pharmacy</h1>
                    <p>Order Invoice / Receipt</p>
                </td>
                <td style="text-align:right">
                    <div style="font-size:20px;font-weight:700;opacity:.9">#{{ $order->order_number }}</div>
                    <div style="font-size:12px;opacity:.7;margin-top:2px">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== STATUS BANNER ===== --}}
    <div class="status-banner status-{{ $eventType }}">
        @if($eventType === 'verified')
            ✅ Your prescription has been verified. Please complete payment to proceed.
        @elseif($eventType === 'delivered')
            🎉 Your order has been delivered successfully. Thank you!
        @endif
    </div>

    {{-- ===== ORDER INFO ===== --}}
    <div class="section">
        <div class="section-title">Order Details</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Order Number</label>
                <span style="color:#2563eb;font-weight:700">{{ $order->order_number }}</span>
            </div>
            <div class="info-item">
                <label>Order Status</label>
                <span>{{ ucfirst($order->status) }}</span>
            </div>
            <div class="info-item">
                <label>Payment Method</label>
                <span>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-item">
                <label>Payment Status</label>
                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
            @if($order->delivery_method)
            <div class="info-item">
                <label>Delivery Method</label>
                <span>{{ ucwords(str_replace('_', ' ', $order->delivery_method)) }}</span>
            </div>
            @endif
            @if($order->tracking_number)
            <div class="info-item">
                <label>Tracking Number</label>
                <span style="color:#2563eb">{{ $order->tracking_number }}</span>
            </div>
            @endif
            <div class="info-item" style="grid-column:1/-1">
                <label>Delivery Address</label>
                <span>{{ $order->delivery_address }}</span>
            </div>
        </div>
    </div>

    {{-- ===== PATIENT INFO ===== --}}
    <div class="section">
        <div class="section-title">Patient</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Name</label>
                <span>{{ $order->patient?->first_name }} {{ $order->patient?->last_name }}</span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span>{{ $order->patient?->user?->email }}</span>
            </div>
            @if($order->patient?->phone)
            <div class="info-item">
                <label>Phone</label>
                <span>{{ $order->patient->phone }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ===== PHARMACY INFO ===== --}}
    <div class="section">
        <div class="section-title">Pharmacy</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Pharmacy Name</label>
                <span>{{ $order->pharmacy?->name }}</span>
            </div>
            @if($order->pharmacy?->phone)
            <div class="info-item">
                <label>Phone</label>
                <span>{{ $order->pharmacy->phone }}</span>
            </div>
            @endif
            @if($order->pharmacy?->address)
            <div class="info-item" style="grid-column:1/-1">
                <label>Address</label>
                <span>{{ $order->pharmacy->address }}@if($order->pharmacy->city), {{ $order->pharmacy->city }}@endif</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ===== MEDICINES ===== --}}
    @if($order->items->count() > 0)
    <div class="section">
        <div class="section-title">Medicines / Items</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                <tr>
                    <td style="color:#9ca3af">{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item->medication_name }}</strong>
                        @if($item->medication?->dosage)
                            <br><span style="font-size:11px;color:#9ca3af">{{ $item->medication->dosage }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">LKR {{ number_format($item->price, 2) }}</td>
                    <td class="text-right fw-bold">LKR {{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ===== TOTALS ===== --}}
    <div class="totals">
        <div class="total-row">
            <span>Medicines Total</span>
            <span>LKR {{ number_format($order->total_amount ?? 0, 2) }}</span>
        </div>
        @if($order->delivery_fee > 0)
        <div class="total-row">
            <span>Delivery Fee</span>
            <span>LKR {{ number_format($order->delivery_fee, 2) }}</span>
        </div>
        @endif
        <div class="total-grand">
            <span>Grand Total</span>
            <span>LKR {{ number_format(($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2) }}</span>
        </div>
    </div>

    {{-- ===== PHARMACIST NOTES ===== --}}
    @if($order->pharmacist_notes)
    <div class="section" style="background:#fffbeb">
        <div class="section-title">Pharmacist Notes</div>
        <p style="font-size:13px;color:#374151;line-height:1.6">{{ $order->pharmacist_notes }}</p>
    </div>
    @endif

    {{-- ===== PAYMENT NOTE ===== --}}
    @if($eventType === 'verified' && $order->payment_status === 'unpaid')
    <div class="section" style="background:#fef2f2;text-align:center">
        <p style="font-size:13px;color:#dc2626;font-weight:600;margin-bottom:4px">
            ⚠️ Payment Pending
        </p>
        <p style="font-size:12px;color:#6b7280">
            @if($order->payment_method === 'cash_on_delivery')
                Payment will be collected upon delivery (Cash on Delivery).
            @else
                Please complete your online payment to confirm the order.
            @endif
        </p>
    </div>
    @endif

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        <p style="margin-bottom:8px">
            Thank you for choosing <strong>HEALTHNET Pharmacy</strong>.
        </p>
        <p>
            For support contact us at
            @if($order->pharmacy?->email)
                <a href="mailto:{{ $order->pharmacy->email }}">{{ $order->pharmacy->email }}</a>
            @else
                <a href="mailto:support@healthnet.lk">support@healthnet.lk</a>
            @endif
        </p>
        <p style="margin-top:12px;font-size:11px">
            This is a system-generated invoice. Please do not reply to this email.
        </p>
    </div>

</div>
</body>
</html>
