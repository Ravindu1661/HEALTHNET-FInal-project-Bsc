@include('partials.header')

<style>
/* ══════════════════════════════════════════
   LAB ORDER DETAIL — Teal Theme
══════════════════════════════════════════ */
.los-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3rem; color: white;
    position: relative; overflow: hidden;
}
.los-header::before {
    content:''; position:absolute; inset:0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.06;
}
.los-header .container { position:relative; z-index:1; }
.los-header::after {
    content:''; position:absolute; bottom:-1px; left:0; right:0;
    height:45px; background:#f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.los-main { background:#f4f6f9; padding:2rem 0 4rem; }

/* ══ Status Tracker ══ */
.status-tracker {
    background: white; border-radius: 14px;
    padding: 1.6rem 1.8rem; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.tracker-steps {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 0.4rem 0 0;
}
.tracker-step {
    display: flex; flex-direction: column; align-items: center;
    flex: 0 0 auto; width: 70px; z-index: 1;
}
.tracker-circle {
    width: 46px; height: 46px; border-radius: 50%;
    border: 3px solid #e2e8f0; background: #f8fafc;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;   /* ← increase */
    color: #cbd5e1;
    transition: all 0.4s;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    /* ❌ overflow:hidden remove — icon clip වෙනවා */
}

.tracker-circle.done {
    background: linear-gradient(135deg,#059669,#10b981);
    border-color: #059669; color: white;
    box-shadow: 0 4px 14px rgba(5,150,105,0.35);
}
.tracker-circle.active {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    border-color: #0891b2; color: white;
    box-shadow: 0 4px 14px rgba(8,145,178,0.45);
    animation: trackerPulse 2s infinite;
}
.tracker-circle.cancelled {
    background: #fee2e2; border-color: #fca5a5; color: #dc2626;
}
@keyframes trackerPulse {
    0%,100% { box-shadow: 0 4px 12px rgba(8,145,178,0.4); }
    50%      { box-shadow: 0 4px 22px rgba(8,145,178,0.75); }
}
.tracker-label {
    font-size: 0.68rem; font-weight: 600; color: #94a3b8;
    margin-top: 0.55rem; text-align: center; line-height: 1.3;
    white-space: nowrap;
}
.tracker-label.done   { color: #059669; }
.tracker-label.active { color: #0891b2; font-weight: 700; }

/* Connector line — sits between circles at center height */
.tracker-connector-wrap {
    flex: 1; display: flex;
    align-items: flex-start;
    padding-top: 22px; /* half of circle height (46/2 - border) */
}
.tracker-connector {
    flex: 1; height: 3px;
    background: #e2e8f0;
    border-radius: 2px;
    transition: background 0.5s;
}
.tracker-connector.done {
    background: linear-gradient(90deg,#059669,#10b981);
}

/* ══ Section Card ══ */
.los-card {
    background: white; border-radius: 14px;
    padding: 1.5rem; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.los-card-title {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 1rem; padding-bottom: 0.7rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.los-card-title i { color: #0891b2; }

/* Info rows */
.los-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.65rem 0; border-bottom: 1px solid #f0f9ff; font-size: 0.88rem;
}
.los-info-row:last-child { border-bottom: none; }
.los-info-label { color: #888; display:flex; align-items:center; gap:0.5rem; flex-shrink:0; }
.los-info-label i { color: #0891b2; width: 16px; }
.los-info-value { font-weight: 600; color: #333; text-align: right; }

/* Items Table */
.items-table thead th {
    background: #e0f2fe; color: #0c4a6e;
    font-size: 0.8rem; font-weight: 700; border: none; padding: 0.75rem 1rem;
}
.items-table tbody td {
    font-size: 0.85rem; vertical-align: middle;
    border-color: #f0f9ff; padding: 0.7rem 1rem;
}
.items-table tbody tr:hover { background: #f0f9ff; }

/* Total Row */
.lo-total-row {
    background: linear-gradient(135deg,#e0f2fe,#f0f9ff);
    border-radius: 10px; padding: 1rem 1.3rem;
    display: flex; justify-content: space-between; align-items: center;
}

/* Status Pills */
.s-pill {
    padding: 0.3rem 0.85rem; border-radius: 20px;
    font-size: 0.74rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.35rem;
    white-space: nowrap;
}
.s-pill.pending          { background:#fef3c7; color:#92400e; }
.s-pill.sample_collected { background:#e0f2fe; color:#0369a1; }
.s-pill.processing       { background:#ede9fe; color:#4c1d95; }
.s-pill.completed        { background:#dcfce7; color:#166534; }
.s-pill.cancelled        { background:#fee2e2; color:#991b1b; }
.p-pill {
    padding: 0.28rem 0.75rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.35rem;
}
.p-pill.paid   { background:#dcfce7; color:#166534; }
.p-pill.unpaid { background:#fee2e2; color:#991b1b; }

/* Buttons */
.los-btn {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.8rem 1.2rem; border-radius: 10px; text-decoration: none;
    font-weight: 700; font-size: 0.88rem; margin-bottom: 0.7rem;
    transition: all 0.3s; border: none; cursor: pointer; width: 100%;
}
.los-btn-primary { background: linear-gradient(135deg,#0891b2,#0c4a6e); color: white; box-shadow: 0 4px 12px rgba(8,145,178,0.3); }
.los-btn-primary:hover { color: white; filter: brightness(1.08); transform: translateY(-2px); }
.los-btn-green   { background: linear-gradient(135deg,#059669,#047857); color: white; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
.los-btn-green:hover { color: white; filter: brightness(1.08); transform: translateY(-2px); }
.los-btn-orange  { background: linear-gradient(135deg,#f59e0b,#d97706); color: white; box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
.los-btn-orange:hover { color: white; filter: brightness(1.08); transform: translateY(-2px); }
.los-btn-outline { background: white; color: #0891b2; border: 2px solid #0891b2; }
.los-btn-outline:hover { background: #0891b2; color: white; transform: translateY(-2px); }
.los-btn-red     { background: white; color: #dc2626; border: 2px solid #dc2626; }
.los-btn-red:hover { background: #dc2626; color: white; }
.los-btn-grey    { background: #64748b; color: white; }
.los-btn-grey:hover { background: #475569; color: white; transform: translateY(-2px); }

/* Contact Buttons */
.los-contact-btn {
    display: flex; align-items: center; gap: 0.7rem;
    padding: 0.65rem 1rem; border-radius: 10px;
    text-decoration: none; font-weight: 600; font-size: 0.82rem;
    margin-bottom: 0.6rem; transition: all 0.3s;
}
.los-contact-btn:hover { transform: translateX(4px); }
.cwa { background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; }
.cph { background: #e0f2fe; color: #0c4a6e; border: 1.5px solid #bae6fd; }
.cem { background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; }

/* Alerts */
.los-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.8rem;
    font-size: 0.9rem; font-weight: 500;
}
.los-alert.success { background:#dcfce7; color:#166534; border-left:5px solid #059669; }
.los-alert.error   { background:#fee2e2; color:#991b1b; border-left:5px solid #dc2626; }
.los-alert.info    { background:#e0f2fe; color:#0c4a6e; border-left:5px solid #0891b2; }

/* Write Review */
.write-review-card {
    background: white; border-radius: 14px;
    padding: 1.5rem; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem; border-top: 4px solid #0891b2;
}
.star-btn {
    font-size: 1.9rem; cursor: pointer; color: #d1d5db;
    transition: color 0.15s, transform 0.15s;
}
.star-btn:hover { transform: scale(1.2); }

@media (max-width: 768px) {
    .los-header { padding: 5rem 0 2.5rem; }
    .tracker-step { width: 52px; }
    .tracker-circle { width: 38px; height: 38px; font-size: 0.85rem; }
    .tracker-connector-wrap { padding-top: 18px; }
    .tracker-label { font-size: 0.6rem; }
}
@media (max-width: 480px) {
    .tracker-step { width: 42px; }
    .tracker-label { display: none; }
}
</style>

{{-- ══════════════ PAGE HEADER ══════════════ --}}
<section class="los-header">
    <div class="container">
        <a href="{{ route('patient.lab-orders.index') }}"
           style="color:rgba(255,255,255,0.9);text-decoration:none;font-size:0.88rem;
                  display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1rem;transition:all 0.3s;">
            <i class="fas fa-arrow-left"></i> My Lab Orders
        </a>
        <div class="row">
            <div class="col-lg-8">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-flask me-2" style="opacity:0.85;"></i>
                    Order #{{ $order->order_number }}
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">
                    {{ $order->laboratory->name ?? 'Laboratory' }} &middot;
                    {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>
</section>

<section class="los-main">
    <div class="container">

        @if(session('success'))
        <div class="los-alert success">
            <i class="fas fa-check-circle fa-lg" style="flex-shrink:0;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="los-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="los-alert info">
            <i class="fas fa-info-circle fa-lg" style="flex-shrink:0;"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        <div class="row g-4">

            {{-- ══════════ LEFT COLUMN ══════════ --}}
            <div class="col-lg-8">

                {{-- ── STATUS TRACKER ── --}}
                {{-- ── STATUS TRACKER ── --}}
@php
    $statusSteps = [
        'pending'          => ['label' => 'Submitted',        'icon' => 'fa-paper-plane'],
        'sample_collected' => ['label' => 'Sample Collected', 'icon' => 'fa-vial'],
        'processing'       => ['label' => 'Processing',       'icon' => 'fa-microscope'],
        'completed'        => ['label' => 'Report Ready',     'icon' => 'fa-check-circle'],
    ];
    $stepKeys  = array_keys($statusSteps);
    $curIdx    = array_search($order->status, $stepKeys);
    if ($curIdx === false) $curIdx = 0;
    $cancelled = $order->status === 'cancelled';
@endphp

<div class="d-card" style="padding:1.1rem 1.3rem;">
    <h5><i class="fas fa-route"></i> Order Progress</h5>

    @if($cancelled)
        <div style="display:flex;flex-direction:column;align-items:center;padding:.6rem 0 .3rem;">
            <div style="width:44px;height:44px;border-radius:50%;background:#fce4ec;
                        border:2px solid #ef9a9a;display:flex;align-items:center;
                        justify-content:center;margin-bottom:.6rem;">
                <i class="fas fa-times" style="color:#c62828;font-size:.95rem;"></i>
            </div>
            <div style="font-weight:700;color:#880e4f;font-size:.85rem;">Order Cancelled</div>
        </div>
    @else
        <div style="display:flex;align-items:flex-start;justify-content:space-between;
                    width:100%;margin-top:.5rem;">
            @foreach($statusSteps as $key => $step)
                @php
                    $idx   = array_search($key, $stepKeys);
                    $done  = $idx < $curIdx;
                    $active = $idx === $curIdx;
                @endphp

                {{-- Step --}}
                <div style="display:flex;flex-direction:column;align-items:center;flex:0 0 auto;width:60px;">
                    <div style="
                        width:38px;height:38px;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;
                        font-size:.82rem;
                        {{ $done   ? 'background:linear-gradient(135deg,#6a1b9a,#9c27b0);color:white;box-shadow:0 3px 10px rgba(106,27,154,.35);' : '' }}
                        {{ $active ? 'background:linear-gradient(135deg,#4a148c,#7b1fa2);color:white;box-shadow:0 3px 12px rgba(74,20,140,.45);animation:tpulse 2s infinite;' : '' }}
                        {{ !$done && !$active ? 'background:#f3e5f5;color:#ce93d8;border:2px solid #e1bee7;' : '' }}
                    ">
                        <i class="fas {{ $done ? 'fa-check' : $step['icon'] }}"></i>
                    </div>
                    <div style="
                        font-size:.62rem;font-weight:600;margin-top:.4rem;
                        text-align:center;line-height:1.3;width:60px;
                        color:{{ $done ? '#6a1b9a' : ($active ? '#4a148c' : '#bdbdbd') }};
                        {{ $active ? 'font-weight:700;' : '' }}
                    ">{{ $step['label'] }}</div>
                </div>

                {{-- Connector (not after last) --}}
                @if(!$loop->last)
                <div style="
                    flex:1;height:2px;margin-bottom:26px;
                    background:{{ $done ? 'linear-gradient(90deg,#6a1b9a,#9c27b0)' : '#e0e0e0' }};
                    border-radius:2px;min-width:6px;
                "></div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<style>
@keyframes tpulse {
    0%,100% { box-shadow: 0 3px 10px rgba(74,20,140,.4); }
    50%      { box-shadow: 0 3px 20px rgba(74,20,140,.7); }
}
</style>


                {{-- ── ORDER INFORMATION ── --}}
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-info-circle"></i> Order Information
                    </div>

                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-hashtag"></i> Order No.</span>
                        <span class="los-info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-barcode"></i> Reference</span>
                        <span class="los-info-value">{{ $order->reference_number }}</span>
                    </div>
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-flask"></i> Laboratory</span>
                        <span class="los-info-value">
                            <a href="{{ route('patient.laboratories.show', $order->laboratory_id) }}"
                               style="color:#0891b2;font-weight:700;text-decoration:none;">
                                {{ $order->laboratory->name ?? '—' }}
                            </a>
                        </span>
                    </div>
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-calendar"></i> Order Date</span>
                        <span class="los-info-value">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('D, d M Y') }}
                        </span>
                    </div>
                    @if($order->collection_date)
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-calendar-check"></i> Collection Date</span>
                        <span class="los-info-value">
                            {{ \Carbon\Carbon::parse($order->collection_date)->format('D, d M Y') }}
                            @if($order->collection_time)
                                at {{ \Carbon\Carbon::parse($order->collection_time)->format('h:i A') }}
                            @endif
                        </span>
                    </div>
                    @endif
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-truck"></i> Collection Type</span>
                        <span class="los-info-value">
                            @if($order->home_collection)
                                <span style="background:#e0f2fe;color:#0369a1;padding:0.2rem 0.65rem;
                                             border-radius:20px;font-size:0.78rem;font-weight:700;">
                                    <i class="fas fa-home me-1"></i> Home Collection
                                </span>
                            @else
                                <span style="background:#dcfce7;color:#166534;padding:0.2rem 0.65rem;
                                             border-radius:20px;font-size:0.78rem;font-weight:700;">
                                    <i class="fas fa-walking me-1"></i> Walk-In
                                </span>
                            @endif
                        </span>
                    </div>
                    @if($order->home_collection && $order->collection_address)
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-map-marker-alt"></i> Address</span>
                        <span class="los-info-value" style="max-width:60%;">{{ $order->collection_address }}</span>
                    </div>
                    @endif
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-circle-notch"></i> Status</span>
                        <span class="los-info-value">
                            <span class="s-pill {{ $order->status }}">
                                <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </span>
                    </div>
                    <div class="los-info-row">
                        <span class="los-info-label"><i class="fas fa-credit-card"></i> Payment</span>
                        <span class="los-info-value">
                            <span class="p-pill {{ $order->payment_status }}">
                                <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </span>
                    </div>
                </div>

                {{-- ── ORDERED ITEMS ── --}}
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-list-alt"></i> Ordered Items
                    </div>
                    <div class="table-responsive">
                        <table class="table items-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Item Name</th>
                                    <th style="width:90px;">Type</th>
                                    <th class="text-end" style="width:110px;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $i => $item)
                                <tr>
                                    <td style="color:#0891b2;font-weight:700;">{{ $i + 1 }}</td>
                                    <td>
                                        <div style="font-weight:600;color:#0c4a6e;">
                                            {{ $item->item_name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->test_id)
                                            <span style="background:#e0f2fe;color:#0369a1;padding:0.2rem 0.55rem;border-radius:8px;font-size:0.72rem;font-weight:700;">
                                                <i class="fas fa-vial me-1"></i>Test
                                            </span>
                                        @elseif($item->package_id)
                                            <span style="background:#dcfce7;color:#166534;padding:0.2rem 0.55rem;border-radius:8px;font-size:0.72rem;font-weight:700;">
                                                <i class="fas fa-box me-1"></i>Package
                                            </span>
                                        @else
                                            <span style="background:#f3f4f6;color:#666;padding:0.2rem 0.55rem;border-radius:8px;font-size:0.72rem;font-weight:700;">
                                                Service
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span style="font-weight:700;color:#0891b2;">
                                            Rs. {{ number_format($item->price, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="color:#aaa;padding:1.5rem;">
                                        No items found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="lo-total-row mt-3">
                        <span style="font-weight:700;font-size:0.95rem;color:#0c4a6e;">
                            <i class="fas fa-receipt me-2" style="color:#0891b2;"></i> Total Amount
                        </span>
                        <span style="font-size:1.5rem;font-weight:800;color:#0891b2;">
                            Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                {{-- ── PRESCRIPTION ── --}}
                @if($order->prescription_file)
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-file-medical"></i> Prescription
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                        <div style="background:#e0f2fe;border-radius:10px;padding:0.8rem 1rem;
                                    display:flex;align-items:center;gap:0.7rem;
                                    font-size:0.85rem;color:#0369a1;font-weight:600;flex:1;">
                            <i class="fas fa-file-pdf" style="font-size:1.5rem;color:#0891b2;flex-shrink:0;"></i>
                            Prescription uploaded
                        </div>
                        <a href="{{ asset('storage/'.$order->prescription_file) }}" target="_blank"
                           style="background:#0891b2;color:white;padding:0.65rem 1.2rem;border-radius:10px;
                                  text-decoration:none;font-weight:700;font-size:0.85rem;
                                  display:inline-flex;align-items:center;gap:0.5rem;white-space:nowrap;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
                @endif

                {{-- ── NOTES ── --}}
                @if($order->notes)
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-sticky-note"></i> Notes
                    </div>
                    <p style="font-size:0.9rem;color:#555;line-height:1.8;margin:0;">
                        {{ $order->notes }}
                    </p>
                </div>
                @endif

                {{-- ── WRITE A REVIEW ── --}}
                @php
                    $alreadyReviewed = \Illuminate\Support\Facades\DB::table('ratings')
                        ->where('patient_id',   auth()->user()->patient->id ?? 0)
                        ->where('ratable_type', 'laboratory')
                        ->where('ratable_id',   $order->laboratory_id)
                        ->where('related_type', 'lab_order')
                        ->where('related_id',   $order->id)
                        ->exists();
                @endphp

                @if($order->status === 'completed' && !$alreadyReviewed)
                <div class="write-review-card" id="write-review">
                    <div class="los-card-title" style="border-bottom:2px solid #e0f2fe;">
                        <i class="fas fa-star" style="color:#fbbf24;"></i> Write a Review
                    </div>
                    <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:10px;
                                padding:0.8rem 1rem;margin-bottom:1.2rem;font-size:0.83rem;color:#0369a1;
                                display:flex;align-items:center;gap:0.5rem;">
                        <i class="fas fa-info-circle" style="flex-shrink:0;"></i>
                        Share your experience with <strong class="ms-1">{{ $order->laboratory->name ?? 'this lab' }}</strong>
                    </div>
                    <form action="{{ route('patient.lab-orders.review.store', $order->id) }}" method="POST">
                        @csrf
                        <div style="margin-bottom:1.2rem;">
                            <label style="font-size:0.85rem;font-weight:700;color:#0c4a6e;display:block;margin-bottom:0.6rem;">
                                Your Rating <span style="color:#dc2626;">*</span>
                            </label>
                            <div id="starSelector" style="display:flex;gap:0.3rem;align-items:center;">
                                @for($s = 1; $s <= 5; $s++)
                                <i class="far fa-star star-btn" data-value="{{ $s }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="">
                            <div id="ratingLabel"
                                 style="font-size:0.8rem;color:#888;margin-top:0.4rem;font-weight:600;min-height:1.1rem;">
                            </div>
                        </div>
                        <div style="margin-bottom:1.2rem;">
                            <label style="font-size:0.85rem;font-weight:700;color:#0c4a6e;display:block;margin-bottom:0.5rem;">
                                Your Review
                                <span style="color:#888;font-weight:400;">(optional)</span>
                            </label>
                            <textarea name="review" rows="3" maxlength="1000"
                                      placeholder="Share your experience with this lab..."
                                      style="width:100%;padding:0.75rem 1rem;border:2px solid #e9ecef;
                                             border-radius:10px;font-size:0.88rem;resize:vertical;
                                             font-family:inherit;transition:border-color 0.3s;outline:none;"
                                      onfocus="this.style.borderColor='#0891b2';this.style.boxShadow='0 0 0 3px rgba(8,145,178,0.1)';"
                                      onblur="this.style.borderColor='#e9ecef';this.style.boxShadow='none';">{{ old('review') }}</textarea>
                        </div>
                        <button type="submit" id="reviewBtn" disabled
                                style="background:linear-gradient(135deg,#0891b2,#0c4a6e);
                                       color:white;border:none;padding:0.75rem 2rem;
                                       border-radius:10px;font-weight:700;font-size:0.9rem;
                                       cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;
                                       box-shadow:0 4px 12px rgba(8,145,178,0.3);
                                       transition:all 0.3s;opacity:0.6;">
                            <i class="fas fa-paper-plane"></i> Submit Review
                        </button>
                    </form>
                </div>

                @elseif($alreadyReviewed)
                <div class="los-card" style="border-left:4px solid #059669;">
                    <div style="display:flex;align-items:center;gap:0.9rem;color:#166534;font-weight:700;">
                        <i class="fas fa-check-circle fa-lg" style="color:#059669;flex-shrink:0;"></i>
                        <span>Review Submitted — Thank you for your feedback!</span>
                    </div>
                </div>
                @endif

            </div>
            {{-- END LEFT --}}

            {{-- ══════════ RIGHT COLUMN ══════════ --}}
            <div class="col-lg-4">

                {{-- ── Lab Report Card ── --}}
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-file-medical-alt"></i> Lab Report
                    </div>

                    @if($order->status === 'completed' && $order->report_file && $order->payment_status === 'paid')
                    {{-- Report Ready --}}
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:64px;height:64px;border-radius:50%;
                                    background:linear-gradient(135deg,#dcfce7,#bbf7d0);
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 0.75rem;">
                            <i class="fas fa-file-pdf" style="font-size:1.8rem;color:#059669;"></i>
                        </div>
                        <div style="font-weight:700;color:#166534;font-size:0.92rem;margin-bottom:1rem;">
                            <i class="fas fa-check-circle me-1"></i> Report is Ready!
                        </div>
                        <a href="{{ route('patient.lab-orders.report', $order->id) }}"
                           class="los-btn los-btn-primary">
                            <i class="fas fa-download"></i> Download PDF Report
                        </a>
                        @if($order->report_uploaded_at)
                        <div style="font-size:0.72rem;color:#aaa;margin-top:0.5rem;">
                            Uploaded: {{ \Carbon\Carbon::parse($order->report_uploaded_at)->format('d M Y') }}
                        </div>
                        @endif
                    </div>

                    @elseif($order->status === 'completed' && !$order->report_file)
                    {{-- Completed but no report yet --}}
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:64px;height:64px;border-radius:50%;
                                    background:linear-gradient(135deg,#ede9fe,#ddd6fe);
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 0.75rem;">
                            <i class="fas fa-hourglass-half" style="font-size:1.6rem;color:#7c3aed;"></i>
                        </div>
                        <div style="font-weight:700;color:#4c1d95;font-size:0.88rem;margin-bottom:0.4rem;">
                            Tests Completed
                        </div>
                        <p style="font-size:0.78rem;color:#888;margin:0;">
                            Lab is preparing your report. Please check back soon.
                        </p>
                    </div>

                    @elseif($order->payment_status === 'unpaid' && ($order->total_amount ?? 0) > 0 && $order->status !== 'cancelled')
                    {{-- Payment required --}}
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:64px;height:64px;border-radius:50%;
                                    background:linear-gradient(135deg,#fef3c7,#fde68a);
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 0.75rem;">
                            <i class="fas fa-credit-card" style="font-size:1.6rem;color:#d97706;"></i>
                        </div>
                        <div style="font-weight:700;color:#92400e;font-size:0.88rem;margin-bottom:0.9rem;">
                            Payment Required
                        </div>
                        <a href="{{ route('patient.lab-orders.payment', $order->id) }}"
                           class="los-btn los-btn-green">
                            <i class="fas fa-credit-card"></i>
                            Pay Rs. {{ number_format($order->total_amount, 2) }}
                        </a>
                    </div>

                    @elseif($order->status === 'cancelled')
                    {{-- Cancelled --}}
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:64px;height:64px;border-radius:50%;
                                    background:#fee2e2;
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 0.75rem;">
                            <i class="fas fa-times" style="font-size:1.6rem;color:#dc2626;"></i>
                        </div>
                        <div style="font-weight:700;color:#991b1b;font-size:0.88rem;">Order Cancelled</div>
                    </div>

                    @else
                    {{-- Processing --}}
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:64px;height:64px;border-radius:50%;
                                    background:linear-gradient(135deg,#e0f2fe,#bae6fd);
                                    display:flex;align-items:center;justify-content:center;
                                    margin:0 auto 0.75rem;">
                            <i class="fas fa-microscope" style="font-size:1.6rem;color:#0891b2;"></i>
                        </div>
                        <div style="font-weight:700;color:#0c4a6e;font-size:0.88rem;margin-bottom:0.3rem;">
                            Processing Samples
                        </div>
                        <p style="font-size:0.78rem;color:#888;margin:0;">
                            We'll notify you when your report is ready.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- ── Quick Actions ── --}}
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </div>

                    <a href="{{ route('patient.laboratories.show', $order->laboratory_id) }}"
                       class="los-btn los-btn-outline">
                        <i class="fas fa-flask"></i> View Lab Profile
                    </a>

                    @if($order->status === 'completed' && isset($alreadyReviewed) && !$alreadyReviewed)
                    <a href="#write-review" class="los-btn los-btn-orange">
                        <i class="fas fa-star"></i> Write a Review
                    </a>
                    @endif

                    @if($order->payment_status === 'unpaid' && ($order->total_amount ?? 0) > 0 && $order->status !== 'cancelled')
                    <a href="{{ route('patient.lab-orders.payment', $order->id) }}"
                       class="los-btn los-btn-green">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                    @endif

                    @if($order->status === 'completed' && $order->report_file && $order->payment_status === 'paid')
                    <a href="{{ route('patient.lab-orders.report', $order->id) }}"
                       class="los-btn los-btn-primary">
                        <i class="fas fa-download"></i> Download Report
                    </a>
                    @endif

                    @if($order->status === 'pending')
                    <form action="{{ route('patient.lab-orders.cancel', $order->id) }}"
                          method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="los-btn los-btn-red">
                            <i class="fas fa-times-circle"></i> Cancel Order
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('patient.lab-orders.index') }}" class="los-btn los-btn-grey">
                        <i class="fas fa-list-alt"></i> All Orders
                    </a>
                </div>

                {{-- ── Contact Lab ── --}}
                @if(($order->laboratory->phone ?? null) || ($order->laboratory->email ?? null))
                <div class="los-card">
                    <div class="los-card-title">
                        <i class="fas fa-headset"></i> Contact Lab
                    </div>
                    <p style="font-size:0.75rem;color:#aaa;margin-bottom:0.9rem;line-height:1.5;">
                        Contact the lab for inquiries about your order or report.
                    </p>

                    @php
                        $labPhone = $order->laboratory->phone ?? null;
                        $labEmail = $order->laboratory->email ?? null;
                        $labName  = $order->laboratory->name  ?? 'Lab';
                        $waPhone  = '';
                        if ($labPhone) {
                            $raw     = preg_replace('/[^0-9]/', '', $labPhone);
                            $waPhone = str_starts_with($raw, '0') ? '94' . substr($raw, 1) : $raw;
                        }
                        $waMsg    = urlencode("Hello {$labName}, I am a HealthNet patient. Order #{$order->order_number} — I would like to inquire about my lab order.");
                        $emailSub = urlencode("Lab Order Inquiry – Order #{$order->order_number}");
                        $emailBody= urlencode("Hello {$labName},\n\nI am a HealthNet patient.\nOrder: {$order->order_number}\n\nPlease provide an update on my lab order.\n\nThank you.");
                    @endphp

                    @if($labPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}"
                       target="_blank" class="los-contact-btn cwa">
                        <i class="fab fa-whatsapp" style="font-size:1.2rem;color:#25D366;flex-shrink:0;"></i>
                        <div>
                            <div>WhatsApp Lab</div>
                            <div style="font-size:0.7rem;opacity:0.75;">Chat about your order</div>
                        </div>
                    </a>
                    <a href="tel:{{ $labPhone }}" class="los-contact-btn cph">
                        <i class="fas fa-phone" style="color:#0891b2;flex-shrink:0;"></i>
                        <div>
                            <div>Call Lab</div>
                            <div style="font-size:0.7rem;opacity:0.75;">{{ $labPhone }}</div>
                        </div>
                    </a>
                    @endif

                    @if($labEmail)
                    <a href="mailto:{{ $labEmail }}?subject={{ $emailSub }}&body={{ $emailBody }}"
                       class="los-contact-btn cem">
                        <i class="fas fa-envelope" style="color:#d97706;flex-shrink:0;"></i>
                        <div>
                            <div>Email Lab</div>
                            <div style="font-size:0.7rem;opacity:0.75;">{{ Str::limit($labEmail, 28) }}</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

            </div>
            {{-- END RIGHT --}}

        </div>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Star Rating ──
    const stars  = document.querySelectorAll('.star-btn');
    const inp    = document.getElementById('ratingInput');
    const lbl    = document.getElementById('ratingLabel');
    const btn    = document.getElementById('reviewBtn');
    const labels = ['', 'Poor 😞', 'Fair 😐', 'Good 😊', 'Very Good 😄', 'Excellent 🌟'];
    const colors = ['', '#dc2626', '#f97316', '#f59e0b', '#10b981', '#059669'];
    let sel = 0;

    function paint(n) {
        stars.forEach((s, i) => {
            if (i < n) {
                s.classList.remove('far'); s.classList.add('fas');
                s.style.color = '#fbbf24';
            } else {
                s.classList.remove('fas'); s.classList.add('far');
                s.style.color = '#d1d5db';
            }
        });
    }

    stars.forEach(s => {
        s.addEventListener('mouseenter', function () {
            paint(+this.dataset.value);
            this.style.transform = 'scale(1.25)';
        });
        s.addEventListener('mouseleave', function () {
            paint(sel);
            this.style.transform = 'scale(1)';
        });
        s.addEventListener('click', function () {
            sel = +this.dataset.value;
            inp.value          = sel;
            lbl.textContent    = labels[sel];
            lbl.style.color    = colors[sel];
            paint(sel);
            if (btn) {
                btn.disabled      = false;
                btn.style.opacity = '1';
            }
        });
    });

    // ── Auto-dismiss alerts ──
    setTimeout(() => {
        document.querySelectorAll('.los-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);

});
</script>
