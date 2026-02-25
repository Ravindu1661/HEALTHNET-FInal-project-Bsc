@include('partials.header')

<style>
.lo-header {
    background: linear-gradient(135deg,#4a148c 0%,#7b1fa2 100%);
    padding: 6rem 0 2.5rem; color: white; position: relative; overflow: hidden;
}
.lo-header::before {
    content:''; position:absolute; inset:0; opacity:.08;
    background:url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=2070&q=80') center/cover;
}
.lo-header .container { position:relative; z-index:1; }

.stat-box {
    background:white; border-radius:12px; padding:1.2rem 1rem;
    box-shadow:0 4px 15px rgba(0,0,0,.07); text-align:center;
    border-top:4px solid #7b1fa2; transition:all .3s;
}
.stat-box:hover { transform:translateY(-3px); }
.stat-num { font-size:1.8rem; font-weight:700; }
.stat-lbl { font-size:.72rem; color:#888; font-weight:600; text-transform:uppercase; margin-top:.2rem; }

.filter-bar { background:white; border-radius:10px; padding:1rem 1.2rem; box-shadow:0 2px 10px rgba(0,0,0,.06); margin-bottom:1.2rem; }

.order-row {
    background:white; border-radius:12px; padding:1.2rem 1.5rem;
    box-shadow:0 3px 12px rgba(0,0,0,.06); margin-bottom:.9rem;
    border-left:4px solid #e1bee7; transition:all .3s;
}
.order-row:hover { border-left-color:#7b1fa2; box-shadow:0 5px 18px rgba(123,31,162,.12); transform:translateX(2px); }
.order-row.completed  { border-left-color:#1565c0; }
.order-row.has-report { border-left-color:#43a047; }
.order-row.cancelled  { border-left-color:#c62828; }

.s-pill { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .8rem; border-radius:12px; font-size:.7rem; font-weight:700; }
.s-pill.pending          { background:#fff3e0; color:#e65100; }
.s-pill.sample_collected { background:#e3f2fd; color:#0d47a1; }
.s-pill.processing       { background:#e8eaf6; color:#283593; }
.s-pill.completed        { background:#e8f5e9; color:#1b5e20; }
.s-pill.cancelled        { background:#fce4ec; color:#880e4f; }
.p-pill { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .6rem; border-radius:10px; font-size:.68rem; font-weight:700; }
.p-pill.unpaid { background:#fce4ec; color:#c62828; }
.p-pill.paid   { background:#e8f5e9; color:#1b5e20; }

.xbtn { border-radius:8px; font-size:.75rem; font-weight:600; padding:.4rem .9rem; border:none; cursor:pointer; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:.3rem; }
.xbtn-purple { background:#7b1fa2; color:white; }
.xbtn-purple:hover { background:#4a148c; color:white; }
.xbtn-green  { background:#43a047; color:white; }
.xbtn-green:hover  { background:#2e7d32; color:white; }
.xbtn-blue   { background:#1565c0; color:white; }
.xbtn-blue:hover   { background:#0d47a1; color:white; }
.xbtn-outline { background:transparent; color:#7b1fa2; border:1.5px solid #7b1fa2; }
.xbtn-outline:hover { background:#7b1fa2; color:white; }
</style>

{{-- Header --}}
<section class="lo-header">
    <div class="container">
        <a href="{{ route('patient.laboratories') }}"
           style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:1rem;">
            <i class="fas fa-arrow-left"></i> Laboratories
        </a>
        <h1 style="font-size:1.9rem;font-weight:700;margin-bottom:.3rem;">
            <i class="fas fa-flask me-2"></i> My Lab Orders
        </h1>
        <p style="opacity:.85;font-size:.9rem;">Track tests, payments &amp; download reports</p>
    </div>
</section>

<section style="background:#faf4fc;padding:2.5rem 0;min-height:600px;">
    <div class="container">

        @foreach(['success','error','info'] as $t)
        @if(session($t))
        <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 rounded-3 mb-3">
            {{ session($t) }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @endforeach

        {{-- Stats --}}
        <div class="row g-3 mb-4">
            @php
                $statItems = [
                    ['pending',          '⏳ Pending',    '#f57c00'],
                    ['sample_collected', '🧫 Collected',  '#1565c0'],
                    ['processing',       '🔬 Processing', '#6a1b9a'],
                    ['completed',        '✅ Completed',  '#43a047'],
                ];
            @endphp
            @foreach($statItems as [$key, $lbl, $color])
            <div class="col-6 col-md-3">
                <div class="stat-box" style="border-top-color:{{ $color }};">
                    <div class="stat-num" style="color:{{ $color }};">{{ $statusCounts[$key] }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="filter-bar">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label style="font-size:.72rem;font-weight:600;color:#7b1fa2;display:block;margin-bottom:3px;">Status</label>
                    <select name="status" class="form-select form-select-sm" style="min-width:140px;">
                        <option value="">All Status</option>
                        @foreach(['pending','sample_collected','processing','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>
                            {{ ucwords(str_replace('_',' ',$s)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:.72rem;font-weight:600;color:#7b1fa2;display:block;margin-bottom:3px;">Payment</label>
                    <select name="payment" class="form-select form-select-sm" style="min-width:120px;">
                        <option value="">All</option>
                        <option value="unpaid" {{ request('payment')==='unpaid'?'selected':'' }}>Unpaid</option>
                        <option value="paid"   {{ request('payment')==='paid'  ?'selected':'' }}>Paid</option>
                    </select>
                </div>
                <button type="submit" class="xbtn xbtn-purple"><i class="fas fa-filter"></i> Filter</button>
                @if(request()->hasAny(['status','payment']))
                <a href="{{ route('patient.lab-orders.index') }}" class="xbtn xbtn-outline">
                    <i class="fas fa-times"></i> Clear
                </a>
                @endif
                <div class="ms-auto">
                    <a href="{{ route('patient.laboratories') }}" class="xbtn xbtn-green">
                        <i class="fas fa-plus"></i> New Order
                    </a>
                </div>
            </form>
        </div>

        {{-- Orders List --}}
        @forelse($orders as $order)
        @php
            $rowClass = match(true) {
                $order->status === 'completed' && $order->report_file => 'has-report',
                $order->status === 'completed'  => 'completed',
                $order->status === 'cancelled'  => 'cancelled',
                default => '',
            };
        @endphp
        <div class="order-row {{ $rowClass }}">
            <div class="row align-items-center g-2">
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#f3e5f5,#e1bee7);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-flask" style="color:#7b1fa2;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#4a148c;font-size:.88rem;">
                                {{ $order->order_number }}
                            </div>
                            <div style="font-size:.78rem;color:#666;">
                                {{ $order->laboratory->name ?? 'Lab' }}
                            </div>
                            <div style="font-size:.7rem;color:#999;">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                @if($order->collection_date)
                                · <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($order->collection_date)->format('d M Y') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <span class="s-pill {{ $order->status }}">
                        {{ ucwords(str_replace('_',' ',$order->status)) }}
                    </span>
                    <span class="p-pill {{ $order->payment_status }} ms-1">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    @if($order->home_collection)
                    <span class="badge" style="background:#e3f2fd;color:#0d47a1;font-size:.65rem;margin-left:.2rem;">
                        <i class="fas fa-home me-1"></i>Home
                    </span>
                    @endif
                </div>
                <div class="col-md-2">
                    <div style="font-weight:700;color:#2e7d32;font-size:.95rem;">
                        Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                    </div>
                    <div style="font-size:.7rem;color:#999;">
                        {{ $order->items->count() }} item(s)
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <div class="d-flex flex-column gap-1 align-items-end">
                        <a href="{{ route('patient.lab-orders.show', $order->id) }}"
                           class="xbtn xbtn-purple">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if($order->payment_status === 'unpaid' && ($order->total_amount ?? 0) > 0)
                        <a href="{{ route('patient.lab-orders.payment', $order->id) }}"
                           class="xbtn xbtn-green">
                            <i class="fas fa-credit-card"></i> Pay
                        </a>
                        @endif
                        @if($order->status === 'completed' && $order->report_file && $order->payment_status === 'paid')
                        <a href="{{ route('patient.lab-orders.report', $order->id) }}"
                           class="xbtn xbtn-blue">
                            <i class="fas fa-file-pdf"></i> Report
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-flask" style="font-size:4rem;color:#ce93d8;display:block;margin-bottom:1rem;"></i>
            <h5 style="color:#4a148c;">No lab orders found</h5>
            <p class="text-muted">You haven't placed any lab orders yet.</p>
            <a href="{{ route('patient.laboratories') }}" class="xbtn xbtn-purple" style="display:inline-flex;margin-top:.5rem;">
                <i class="fas fa-search"></i> Find Laboratories
            </a>
        </div>
        @endforelse

        @if($orders->hasPages())
        <div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </div>
</section>

@include('partials.footer')
