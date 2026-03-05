{{-- resources/views/pharmacy/orders/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice – {{ $order->order_number }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Segoe UI', Arial, sans-serif; font-size:13px; color:#1f2937; background:#fff; }
.page { max-width:750px; margin:0 auto; padding:30px; }
.header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #2563eb; padding-bottom:16px; margin-bottom:20px; }
.pharmacy-name { font-size:20px; font-weight:700; color:#2563eb; }
.pharmacy-sub  { font-size:12px; color:#6b7280; margin-top:2px; }
.invoice-badge { background:#2563eb; color:#fff; padding:6px 18px; border-radius:20px; font-size:12px; font-weight:700; letter-spacing:.05em; }
.meta { display:flex; gap:24px; margin-bottom:20px; }
.meta-box { flex:1; background:#f8fafc; border-radius:8px; padding:12px 16px; border:1px solid #e5e7eb; }
.meta-box label { font-size:10px; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; font-weight:700; display:block; margin-bottom:4px; }
.meta-box .val  { font-weight:600; font-size:13px; }
table { width:100%; border-collapse:collapse; margin-bottom:16px; }
th { background:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; padding:8px 10px; text-align:left; border-bottom:1px solid #e5e7eb; }
td { padding:9px 10px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
.totals { margin-left:auto; width:240px; }
.totals td { border:none; padding:4px 0; font-size:13px; }
.totals td:last-child { text-align:right; }
.grand { font-size:15px; font-weight:700; color:#2563eb; border-top:2px solid #2563eb; padding-top:6px !important; }
.status-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.s-pending    { background:#fef3c7; color:#d97706; }
.s-verified   { background:#cffafe; color:#0891b2; }
.s-processing { background:#dbeafe; color:#1d4ed8; }
.s-ready      { background:#dcfce7; color:#16a34a; }
.s-dispatched { background:#f1f5f9; color:#64748b; }
.s-delivered  { background:#dcfce7; color:#15803d; }
.s-cancelled  { background:#fee2e2; color:#b91c1c; }
.footer { text-align:center; color:#9ca3af; font-size:11px; margin-top:30px; border-top:1px dashed #e5e7eb; padding-top:14px; }
@media print {
    body { background:#fff; }
    .no-print { display:none; }
    .page { padding:15px; }
}
</style>
</head>
<body>
<div class="page">

    {{-- Print / Close buttons --}}
    <div class="no-print" style="text-align:right;margin-bottom:16px">
        <button onclick="window.print()" style="background:#2563eb;color:#fff;border:none;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:13px;margin-right:8px">
            🖨 Print Invoice
        </button>
        <button onclick="window.close()" style="background:#f1f5f9;border:none;padding:8px 20px;border-radius:20px;cursor:pointer;font-size:13px">
            ✕ Close
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="pharmacy-name">{{ $pharmacy->name }}</div>
            <div class="pharmacy-sub">{{ $pharmacy->address }}, {{ $pharmacy->city }}</div>
            <div class="pharmacy-sub">{{ $pharmacy->phone }} | {{ $pharmacy->email }}</div>
            <div class="pharmacy-sub">Reg: {{ $pharmacy->registration_number }}</div>
        </div>
        <div style="text-align:right">
            <div class="invoice-badge">INVOICE</div>
            <div style="font-size:11px;color:#6b7280;margin-top:8px">
                Date: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}
            </div>
        </div>
    </div>

    {{-- Meta --}}
    <div class="meta">
        <div class="meta-box">
            <label>Order Number</label>
            <div class="val">{{ $order->order_number }}</div>
        </div>
        <div class="meta-box">
            <label>Status</label>
            <div class="val">
                <span class="status-badge s-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="meta-box">
            <label>Payment</label>
            <div class="val">{{ str_replace('_', ' ', $order->payment_method) }}</div>
        </div>
        <div class="meta-box">
            <label>Patient</label>
            <div class="val">{{ $patient->first_name }} {{ $patient->last_name }}</div>
            <div style="font-size:11px;color:#9ca3af">{{ $patient->phone ?? $patient->email }}</div>
        </div>
    </div>

    {{-- Delivery --}}
    <div style="background:#f8fafc;border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:12px;border:1px solid #e5e7eb">
        <strong>Delivery Address:</strong> {{ $order->delivery_address }}
        @if($order->delivery_method)
        &nbsp;|&nbsp; <strong>Method:</strong> {{ str_replace('_', ' ', $order->delivery_method) }}
        @endif
        @if($order->tracking_number)
        &nbsp;|&nbsp; <strong>Tracking:</strong> {{ $order->tracking_number }}
        @endif
    </div>

    {{-- Items Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:right">Unit Price</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $i => $item)
        <tr>
            <td style="color:#9ca3af">{{ $i+1 }}</td>
            <td><strong>{{ $item->medication_name }}</strong></td>
            <td style="color:#6b7280">{{ $item->dosage ?? '–' }}</td>
            <td style="text-align:center">{{ $item->quantity }}</td>
            <td style="text-align:right">Rs. {{ number_format($item->price, 2) }}</td>
            <td style="text-align:right"><strong>Rs. {{ number_format($item->subtotal, 2) }}</strong></td>
        </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals" style="margin-left:auto">
        <tr>
            <td style="color:#6b7280">Medicines Subtotal</td>
            <td>Rs. {{ number_format($items->sum('subtotal'), 2) }}</td>
        </tr>
        @if($order->delivery_fee > 0)
        <tr>
            <td style="color:#6b7280">Delivery Fee</td>
            <td>Rs. {{ number_format($order->delivery_fee, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="grand">TOTAL</td>
            <td class="grand">Rs. {{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;font-size:12px">Payment Status</td>
            <td style="font-weight:700;color:{{ $order->payment_status === 'paid' ? '#15803d' : '#dc2626' }}">
                {{ strtoupper($order->payment_status) }}
            </td>
        </tr>
    </table>

    @if($order->pharmacist_notes)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;margin-top:16px;font-size:12px">
        <strong>Pharmacist Notes:</strong> {{ $order->pharmacist_notes }}
    </div>
    @endif

    <div class="footer">
        <p>Thank you for choosing <strong>{{ $pharmacy->name }}</strong> — {{ $pharmacy->city }}, Sri Lanka</p>
        <p style="margin-top:4px">Generated: {{ now()->format('d M Y, h:i A') }} | HEALTHNET Platform</p>
    </div>
</div>
</body>
</html>
