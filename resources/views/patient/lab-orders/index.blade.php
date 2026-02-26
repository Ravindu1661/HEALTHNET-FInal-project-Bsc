@include('partials.header')

<style>
/* ══════════════════════════════════════════
   MY LAB ORDERS — Teal Theme
══════════════════════════════════════════ */
.lo-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.lo-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.06;
}
.lo-header .container { position: relative; z-index: 1; }
.lo-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 45px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.lo-header h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.4rem; }
.lo-header p  { opacity: 0.9; font-size: 1rem; margin: 0; }
.lo-main { background: #f4f6f9; padding: 2rem 0 4rem; min-height: 600px; }

/* Stat Cards */
.lo-stat {
    background: white;
    border-radius: 14px;
    padding: 1.4rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    display: flex; align-items: center; gap: 1.1rem;
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 1.5rem;
}
.lo-stat:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
.lo-stat-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.3rem; flex-shrink: 0;
}
.lo-stat-icon.total      { background: linear-gradient(135deg,#0c4a6e,#0891b2); }
.lo-stat-icon.pending    { background: linear-gradient(135deg,#d97706,#f59e0b); }
.lo-stat-icon.processing { background: linear-gradient(135deg,#7c3aed,#8b5cf6); }
.lo-stat-icon.completed  { background: linear-gradient(135deg,#059669,#10b981); }
.lo-stat-icon.cancelled  { background: linear-gradient(135deg,#dc2626,#ef4444); }
.lo-stat-label { font-size: 0.8rem; color: #888; font-weight: 500; margin-bottom: 0.15rem; }
.lo-stat-value { font-size: 1.85rem; font-weight: 700; color: #0c4a6e; line-height: 1; }

/* Filter Bar */
.lo-filter-bar {
    background: white;
    border-radius: 14px;
    padding: 1.1rem 1.4rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.7rem; flex-wrap: wrap;
}
.lo-filter-label {
    font-size: 0.85rem; font-weight: 700; color: #0c4a6e;
    display: flex; align-items: center; gap: 0.4rem; white-space: nowrap;
}
.lo-fbtn {
    padding: 0.45rem 1.1rem;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: white;
    font-size: 0.83rem; font-weight: 600; color: #666;
    cursor: pointer; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 0.4rem;
    text-decoration: none;
}
.lo-fbtn:hover { border-color: #0891b2; color: #0891b2; }
.lo-fbtn.active {
    background: #0891b2; color: white; border-color: #0891b2;
}

/* Order Card */
.lo-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.4rem;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 5px solid #e0f2fe;
}
.lo-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(8,145,178,0.12); }
.lo-card.status-pending          { border-left-color: #f59e0b; }
.lo-card.status-sample_collected { border-left-color: #0891b2; }
.lo-card.status-processing       { border-left-color: #8b5cf6; }
.lo-card.status-completed        { border-left-color: #10b981; }
.lo-card.status-cancelled        { border-left-color: #ef4444; }

.lo-card-body {
    padding: 1.3rem 1.5rem;
    display: flex; gap: 1.2rem; align-items: flex-start;
}
.lo-icon-box {
    width: 52px; height: 52px;
    border-radius: 12px;
    background: linear-gradient(135deg,#e0f2fe,#bae6fd);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.lo-icon-box i { color: #0891b2; font-size: 1.4rem; }

.lo-order-num { font-size: 0.72rem; color: #999; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
.lo-lab-name  { font-size: 1rem; font-weight: 700; color: #0c4a6e; margin-bottom: 0.2rem; }
.lo-meta      { display: flex; gap: 1rem; flex-wrap: wrap; }
.lo-meta-item { display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #666; }
.lo-meta-item i { color: #0891b2; font-size: 0.75rem; }

.lo-card-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; flex-shrink: 0; }

/* Status & Payment Pills */
.s-pill {
    padding: 0.3rem 0.8rem; border-radius: 15px;
    font-size: 0.73rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.s-pill.pending          { background: #fef3c7; color: #92400e; }
.s-pill.sample_collected { background: #e0f2fe; color: #0369a1; }
.s-pill.processing       { background: #ede9fe; color: #4c1d95; }
.s-pill.completed        { background: #dcfce7; color: #166534; }
.s-pill.cancelled        { background: #fee2e2; color: #991b1b; }

.p-pill {
    padding: 0.25rem 0.7rem; border-radius: 12px;
    font-size: 0.7rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.p-pill.paid   { background: #dcfce7; color: #166534; }
.p-pill.unpaid { background: #fee2e2; color: #991b1b; }

/* Card Footer */
.lo-card-footer {
    background: #f8fafc;
    padding: 0.85rem 1.5rem;
    border-top: 1px solid #e0f2fe;
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 0.7rem;
}
.lo-amount { font-size: 1.05rem; font-weight: 700; color: #0891b2; }
.lo-amount-label { font-size: 0.78rem; color: #888; }

/* Buttons */
.lo-btn {
    padding: 0.45rem 1rem; border-radius: 20px;
    font-size: 0.8rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 0.4rem;
    text-decoration: none; border: none; cursor: pointer;
    transition: all 0.3s;
}
.lo-btn-view   { background: #0891b2; color: white; }
.lo-btn-view:hover { background: #0c4a6e; color: white; transform: translateY(-2px); }
.lo-btn-pay    { background: #059669; color: white; box-shadow: 0 3px 8px rgba(5,150,105,0.3); animation: payPulse 2s infinite; }
.lo-btn-pay:hover  { background: #047857; color: white; transform: translateY(-2px); }
.lo-btn-report { background: #0369a1; color: white; }
.lo-btn-report:hover { background: #0c4a6e; color: white; transform: translateY(-2px); }
.lo-btn-review { background: white; color: #f59e0b; border: 2px solid #f59e0b; }
.lo-btn-review:hover { background: #f59e0b; color: white; }

@keyframes payPulse {
    0%,100% { box-shadow: 0 3px 8px rgba(5,150,105,0.3); }
    50%      { box-shadow: 0 3px 16px rgba(5,150,105,0.55); }
}

/* Alert */
.lo-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.8rem;
    font-size: 0.9rem; font-weight: 500;
}
.lo-alert.success { background: #dcfce7; color: #166534; border-left: 5px solid #059669; }
.lo-alert.error   { background: #fee2e2; color: #991b1b; border-left: 5px solid #dc2626; }
.lo-alert.info    { background: #e0f2fe; color: #0c4a6e; border-left: 5px solid #0891b2; }

/* Empty */
.lo-empty {
    background: white; border-radius: 14px; padding: 4rem 2rem;
    text-align: center; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
}
.lo-empty i { font-size: 4rem; color: #bae6fd; display: block; margin-bottom: 1rem; }

/* Pagination */
.lo-pagination .page-link {
    border-radius: 8px !important; border: 2px solid #e9ecef;
    color: #0891b2; font-weight: 600;
    padding: 0.45rem 0.85rem; font-size: 0.82rem; transition: all 0.2s;
}
.lo-pagination .page-link:hover,
.lo-pagination .page-item.active .page-link {
    background: #0891b2; border-color: #0891b2; color: white;
}

@media (max-width:768px) {
    .lo-header { padding: 5rem 0 2.5rem; }
    .lo-card-body { flex-direction: column; }
    .lo-card-right { flex-direction: row; align-items: center; }
}
</style>

{{-- PAGE HEADER --}}
<section class="lo-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="fas fa-flask me-2" style="opacity:0.85;"></i> My Lab Orders</h1>
                <p>Track your test orders, collection status and download reports</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('patient.laboratories') }}"
                   style="background:rgba(255,255,255,0.2);backdrop-filter:blur(6px);color:white;
                          padding:0.8rem 1.8rem;border-radius:25px;text-decoration:none;
                          font-weight:700;font-size:0.9rem;display:inline-flex;align-items:center;gap:0.5rem;
                          border:2px solid rgba(255,255,255,0.3);transition:all 0.3s;">
                    <i class="fas fa-plus"></i> Book New Test
                </a>
            </div>
        </div>
    </div>
</section>

<section class="lo-main">
    <div class="container">

        {{-- Alerts --}}
        @if(session('success'))
        <div class="lo-alert success"><i class="fas fa-check-circle fa-lg"></i><span>{{ session('success') }}</span></div>
        @endif
        @if(session('error'))
        <div class="lo-alert error"><i class="fas fa-exclamation-circle fa-lg"></i><span>{{ session('error') }}</span></div>
        @endif

        {{-- STATS --}}
        <div class="row g-3 mb-1">
            <div class="col-6 col-md-2">
                <div class="lo-stat">
                    <div class="lo-stat-icon total"><i class="fas fa-flask"></i></div>
                    <div><div class="lo-stat-label">Total</div><div class="lo-stat-value">{{ $counts->total }}</div></div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="lo-stat">
                    <div class="lo-stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div><div class="lo-stat-label">Pending</div><div class="lo-stat-value">{{ $counts->pending }}</div></div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="lo-stat">
                    <div class="lo-stat-icon processing"><i class="fas fa-microscope"></i></div>
                    <div><div class="lo-stat-label">Processing</div><div class="lo-stat-value">{{ $counts->processing }}</div></div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="lo-stat">
                    <div class="lo-stat-icon completed"><i class="fas fa-check-circle"></i></div>
                    <div><div class="lo-stat-label">Completed</div><div class="lo-stat-value">{{ $counts->completed }}</div></div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="lo-stat">
                    <div class="lo-stat-icon cancelled"><i class="fas fa-times-circle"></i></div>
                    <div><div class="lo-stat-label">Cancelled</div><div class="lo-stat-value">{{ $counts->cancelled }}</div></div>
                </div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="lo-filter-bar">
            <span class="lo-filter-label"><i class="fas fa-filter"></i> Filter:</span>
            <a href="{{ route('patient.lab-orders.index') }}"
               class="lo-fbtn {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> All
            </a>
            @foreach(['pending'=>['clock','Pending'],'sample_collected'=>['vial','Sample Collected'],'processing'=>['microscope','Processing'],'completed'=>['check-circle','Completed'],'cancelled'=>['times-circle','Cancelled']] as $st=>[$ic,$lbl])
            <a href="{{ route('patient.lab-orders.index', ['status'=>$st]) }}"
               class="lo-fbtn {{ request('status')==$st ? 'active' : '' }}">
                <i class="fas fa-{{ $ic }}"></i> {{ $lbl }}
            </a>
            @endforeach
            @if(request('status'))
            <a href="{{ route('patient.lab-orders.index') }}"
               class="lo-fbtn" style="color:#dc2626;border-color:#dc2626;">
                <i class="fas fa-redo-alt"></i> Reset
            </a>
            @endif
        </div>

        {{-- ORDER CARDS --}}
        @forelse($orders as $order)
        @php
            $statusIcons = [
                'pending'          => 'clock',
                'sample_collected' => 'vial',
                'processing'       => 'microscope',
                'completed'        => 'check-circle',
                'cancelled'        => 'times-circle',
            ];
            $statusIcon = $statusIcons[$order->status] ?? 'flask';
            $statusLabel = ucwords(str_replace('_', ' ', $order->status));

            $canPay    = $order->payment_status === 'unpaid' && ($order->total_amount ?? 0) > 0 && $order->status !== 'cancelled';
            $hasReport = $order->status === 'completed' && $order->report_file && $order->payment_status === 'paid';

            // Review: completed + paid + not yet reviewed
            $alreadyReviewed = \Illuminate\Support\Facades\DB::table('ratings')
                ->where('patient_id',   auth()->user()->patient->id ?? 0)
                ->where('ratable_type', 'laboratory')
                ->where('ratable_id',   $order->laboratory_id)
                ->where('related_type', 'lab_order')
                ->where('related_id',   $order->id)
                ->exists();
            $canReview = $order->status === 'completed' && !$alreadyReviewed;
        @endphp

        <div class="lo-card status-{{ $order->status }}">
            <div class="lo-card-body">
                <div class="lo-icon-box">
                    <i class="fas fa-{{ $statusIcon }}"></i>
                </div>

                <div style="flex:1;min-width:0;">
                    <div class="lo-order-num">#{{ $order->order_number }}</div>
                    <div class="lo-lab-name">{{ $order->laboratory->name ?? 'Laboratory' }}</div>
                    <div class="lo-meta">
                        <div class="lo-meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                        </div>
                        @if($order->collection_date)
                        <div class="lo-meta-item">
                            <i class="fas fa-calendar-check"></i>
                            Collection: {{ \Carbon\Carbon::parse($order->collection_date)->format('d M Y') }}
                        </div>
                        @endif
                        <div class="lo-meta-item">
                            <i class="fas fa-vial"></i>
                            {{ $order->items->count() }} item(s)
                        </div>
                        @if($order->home_collection)
                        <div class="lo-meta-item" style="color:#0891b2;">
                            <i class="fas fa-home"></i> Home Collection
                        </div>
                        @endif
                    </div>
                </div>

                <div class="lo-card-right">
                    <span class="s-pill {{ $order->status }}">
                        <i class="fas fa-{{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                    <span class="p-pill {{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="lo-card-footer">
                <div>
                    <div class="lo-amount-label"><i class="fas fa-receipt me-1"></i> Total</div>
                    <div class="lo-amount">Rs. {{ number_format($order->total_amount ?? 0, 2) }}</div>
                </div>

                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                    @if($canPay)
                    <a href="{{ route('patient.lab-orders.payment', $order->id) }}" class="lo-btn lo-btn-pay">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                    @endif

                    @if($hasReport)
                    <a href="{{ route('patient.lab-orders.report', $order->id) }}" class="lo-btn lo-btn-report">
                        <i class="fas fa-file-pdf"></i> Report
                    </a>
                    @endif

                    @if($canReview)
                    <a href="{{ route('patient.laboratories.show', $order->laboratory_id) }}#write-review"
                       class="lo-btn lo-btn-review">
                        <i class="fas fa-star"></i> Review
                    </a>
                    @endif

                    <a href="{{ route('patient.lab-orders.show', $order->id) }}" class="lo-btn lo-btn-view">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="lo-empty">
            <i class="fas fa-flask"></i>
            <h4 style="color:#0c4a6e;font-weight:700;margin-bottom:0.5rem;">No Lab Orders Found</h4>
            <p style="color:#aaa;font-size:0.9rem;margin-bottom:1.5rem;">
                @if(request('status')) No orders match this filter. @else You haven't placed any lab orders yet. @endif
            </p>
            <a href="{{ route('patient.laboratories') }}"
               style="background:linear-gradient(135deg,#0891b2,#0c4a6e);color:white;
                      padding:0.85rem 2rem;border-radius:25px;text-decoration:none;
                      font-weight:700;font-size:0.9rem;display:inline-flex;align-items:center;gap:0.5rem;
                      box-shadow:0 4px 14px rgba(8,145,178,0.3);">
                <i class="fas fa-search"></i> Find Laboratories
            </a>
        </div>
        @endforelse

        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-3 lo-pagination">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif

    </div>
</section>

@include('partials.footer')

<script>
setTimeout(() => {
    document.querySelectorAll('.lo-alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
