{{-- resources/views/patient/pharmacy-medicines.blade.php --}}
@include('partials.header')

<style>
/* ===== HEADER ===== */
.pm-header {
    background: linear-gradient(135deg, #004d40 0%, #00796b 100%);
    padding: 5rem 0 3rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.pm-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: .07;
    z-index: 0;
}
.pm-header .container { position: relative; z-index: 1; }
.pm-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.pm-meta-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px;
    padding: .3rem .85rem;
    font-size: .78rem;
    font-weight: 600;
    backdrop-filter: blur(4px);
}

/* ===== FILTER BAR ===== */
.filter-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.2rem 1.5rem;
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
    margin: -2.5rem 0 2rem;
    position: relative;
    z-index: 10;
}
.filter-input {
    width: 100%;
    padding: .55rem .75rem;
    border: 1.5px solid #e0f2f1;
    border-radius: 8px;
    font-size: .85rem;
    transition: border .25s, box-shadow .25s;
    background: #fafafa;
}
.filter-input:focus {
    border-color: #00796b;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,121,107,.1);
    background: #fff;
}

/* ===== CATEGORY PILLS ===== */
.cat-pills {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    margin-top: .85rem;
    padding-top: .85rem;
    border-top: 1px solid #f0f0f0;
}
.cat-pill {
    padding: .25rem .8rem;
    border-radius: 20px;
    border: 1.5px solid #e0f2f1;
    background: #fff;
    color: #555;
    font-size: .76rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.cat-pill:hover, .cat-pill.active {
    background: #00796b;
    color: #fff;
    border-color: #00796b;
}
.cat-pill.all-pill.active {
    background: #004d40;
    border-color: #004d40;
}

/* ===== STATS BAR ===== */
.stats-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
    margin-bottom: 1.2rem;
}
.stats-chips { display: flex; gap: .5rem; flex-wrap: wrap; }
.chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .22rem .75rem;
    border-radius: 20px;
    font-size: .73rem;
    font-weight: 700;
}
.chip-in  { background: #dcfce7; color: #166534; }
.chip-low { background: #fef9c3; color: #854d0e; }
.chip-out { background: #fee2e2; color: #991b1b; }

/* ===== MEDICINE GRID ===== */
.med-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1rem;
}
.med-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #e8f5e9;
    padding: 1.1rem 1.1rem 1rem;
    transition: all .25s;
    position: relative;
    display: flex;
    flex-direction: column;
}
.med-card:hover {
    box-shadow: 0 8px 24px rgba(0,121,107,.13);
    transform: translateY(-3px);
    border-color: #80cbc4;
}
.med-badge {
    position: absolute;
    top: .7rem; right: .7rem;
    padding: .18rem .55rem;
    border-radius: 10px;
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .03em;
    text-transform: uppercase;
}
.med-badge.rx  { background: #fff3e0; color: #e65100; }
.med-badge.otc { background: #e8f5e9; color: #2e7d32; }

.med-stock-dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    margin-right: 4px;
    vertical-align: middle;
    flex-shrink: 0;
}
.med-name {
    font-weight: 700;
    color: #1a1a1a;
    font-size: .92rem;
    margin-bottom: .15rem;
    padding-right: 2.8rem;
    line-height: 1.35;
}
.med-generic { font-size: .76rem; color: #888; margin-bottom: .4rem; }
.med-price   { font-size: 1.08rem; font-weight: 800; color: #00796b; }
.med-stock   { font-size: .72rem; font-weight: 700; display: flex; align-items: center; }
.med-cat-tag {
    display: inline-block;
    background: #e0f2f1;
    color: #00796b;
    border-radius: 8px;
    padding: .1rem .5rem;
    font-size: .67rem;
    font-weight: 700;
    margin-bottom: .45rem;
}
.med-manufacturer { font-size: .71rem; color: #b0b0b0; margin-bottom: .4rem; }
.med-desc {
    font-size: .75rem;
    color: #888;
    margin-top: auto;
    padding-top: .6rem;
    border-top: 1px solid #f0f4f0;
    line-height: 1.5;
}
.med-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: .6rem;
}

/* ===== STOCK QUANTITY BADGE ===== */
.med-qty-badge {
    display: inline-block;
    font-size: .68rem;
    font-weight: 700;
    padding: .12rem .5rem;
    border-radius: 8px;
    margin-top: .3rem;
}
.med-qty-badge.qty-in   { background: #dcfce7; color: #166534; }
.med-qty-badge.qty-low  { background: #fef9c3; color: #854d0e; }

/* ===== PLACE ORDER BUTTON ===== */
.btn-place-order {
    background: linear-gradient(135deg, #00796b, #004d40);
    color: #fff;
    text-decoration: none;
    border-radius: 9px;
    padding: .55rem 1.2rem;
    font-weight: 700;
    font-size: .85rem;
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    transition: all .25s;
    box-shadow: 0 3px 10px rgba(0,121,107,.25);
    white-space: nowrap;
}
.btn-place-order:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
    color: #fff;
}
.ph-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
}

/* ===== PAGINATION ===== */
.pagination .page-link {
    border-radius: 8px !important;
    margin: 0 2px;
    color: #00796b;
    border-color: #e0f2f1;
    font-size: .83rem;
}
.pagination .page-item.active .page-link {
    background: #00796b;
    border-color: #00796b;
}

/* ===== MOBILE ===== */
@media (max-width: 576px) {
    .cat-pills { flex-wrap: nowrap; overflow-x: auto; padding-bottom: .3rem; }
    .cat-pills::-webkit-scrollbar { height: 3px; }
    .cat-pills::-webkit-scrollbar-thumb { background: #b2dfdb; border-radius: 3px; }
    .med-grid { grid-template-columns: 1fr 1fr; gap: .75rem; }
    .stats-bar { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 400px) {
    .med-grid { grid-template-columns: 1fr; }
}
</style>

{{-- ===== HEADER ===== --}}
<section class="pm-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
           style="color:rgba(255,255,255,.85);font-size:.83rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>

        <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                 style="width:62px;height:62px;border-radius:12px;object-fit:cover;border:3px solid rgba(255,255,255,.75)"
                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
            <div>
                <h1 style="font-size:1.75rem;font-weight:800;margin:0;line-height:1.2">
                    {{ $pharmacy->name }}
                </h1>
                <p style="opacity:.82;font-size:.88rem;margin:.3rem 0 .6rem">
                    <i class="fas fa-pills me-1"></i> Medicines Catalogue
                </p>
            </div>
        </div>

        <div style="display:flex;gap:.5rem;flex-wrap:wrap">
            @if($pharmacy->city)
                <span class="pm-meta-badge">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $pharmacy->city }}{{ $pharmacy->province ? ', '.$pharmacy->province : '' }}
                </span>
            @endif
            @if($pharmacy->delivery_available)
                <span class="pm-meta-badge"><i class="fas fa-truck"></i> Delivery Available</span>
            @endif
            @if($pharmacy->phone)
                <a href="tel:{{ $pharmacy->phone }}" class="pm-meta-badge" style="color:#fff;text-decoration:none">
                    <i class="fas fa-phone"></i> {{ $pharmacy->phone }}
                </a>
            @endif
        </div>
    </div>
</section>

{{-- ===== BODY ===== --}}
<div class="container" style="padding-bottom:3.5rem">

    {{-- Flash Alerts --}}
    @foreach(['success','error','info'] as $t)
        @if(session($t))
            <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 shadow-sm mt-3" style="border-radius:10px">
                <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }} me-2"></i>
                {{ session($t) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-card">
        <form action="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-md-5">
                    <label style="font-size:.74rem;font-weight:700;color:#374151;margin-bottom:.3rem;display:block">
                        <i class="fas fa-search me-1 text-muted"></i>Search
                    </label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#00796b;font-size:.8rem"></i>
                        <input type="text" name="search" class="filter-input" style="padding-left:2rem"
                               placeholder="Medicine name, generic name..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Category --}}
                <div class="col-md-3">
                    <label style="font-size:.74rem;font-weight:700;color:#374151;margin-bottom:.3rem;display:block">
                        <i class="fas fa-tag me-1 text-muted"></i>Category
                    </label>
                    <select name="category" class="filter-input">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rx / OTC --}}
                <div class="col-md-2">
                    <label style="font-size:.74rem;font-weight:700;color:#374151;margin-bottom:.3rem;display:block">
                        <i class="fas fa-prescription-bottle me-1 text-muted"></i>Type
                    </label>
                    <select name="rx" class="filter-input">
                        <option value="">All Types</option>
                        <option value="rx"  {{ request('rx') === 'rx'  ? 'selected' : '' }}>Prescription (Rx)</option>
                        <option value="otc" {{ request('rx') === 'otc' ? 'selected' : '' }}>Over-the-Counter (OTC)</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit"
                            style="flex:1;background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:8px;padding:.6rem .5rem;font-weight:700;font-size:.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.4rem">
                        <i class="fas fa-search"></i>
                        <span class="d-none d-md-inline">Search</span>
                    </button>
                    @if(request('search') || request('category') || request('rx'))
                        <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                           style="flex:0;background:#fee2e2;color:#dc2626;border:none;border-radius:8px;padding:.6rem .7rem;font-weight:700;font-size:.83rem;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none"
                           title="Clear filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Category Pills --}}
            @if($categories->count() > 0)
                <div class="cat-pills">
                    <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                       class="cat-pill all-pill {{ !request('category') ? 'active' : '' }}">
                        <i class="fas fa-th-large me-1"></i>All
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}?{{ http_build_query(array_merge(request()->except(['category','page']), ['category' => $cat])) }}"
                           class="cat-pill {{ request('category') == $cat ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </form>
    </div>

    {{-- ===== STATS BAR ===== --}}
    @php
        $inStockCount  = $medicines->getCollection()->where('stock_status', 'instock')->count();
        $lowStockCount = $medicines->getCollection()->where('stock_status', 'lowstock')->count();
    @endphp

    <div class="stats-bar">
        <span style="font-size:.88rem;color:#555">
            Showing <strong style="color:#00796b;font-size:1rem">{{ $medicines->total() }}</strong>
            medicine{{ $medicines->total() !== 1 ? 's' : '' }}
            @if(request('search')) for <em>"{{ request('search') }}"</em>@endif
            @if(request('category')) in <strong>{{ request('category') }}</strong>@endif
            @if(request('rx')) &mdash; <strong>{{ request('rx') === 'rx' ? 'Rx Only' : 'OTC Only' }}</strong>@endif
        </span>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="stats-chips">
                @if($inStockCount)
                    <span class="chip chip-in">
                        <i class="fas fa-check-circle" style="font-size:.65rem"></i>
                        {{ $inStockCount }} In Stock
                    </span>
                @endif
                @if($lowStockCount)
                    <span class="chip chip-low">
                        <i class="fas fa-exclamation-triangle" style="font-size:.65rem"></i>
                        {{ $lowStockCount }} Low Stock
                    </span>
                @endif
            </div>

            <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}" class="btn-place-order">
                <i class="fas fa-prescription-bottle-alt"></i> Place Order
            </a>
        </div>
    </div>

    {{-- ===== MEDICINES GRID ===== --}}
    @if($medicines->count())
        <div class="med-grid">
            @foreach($medicines as $med)
               @php
                $stockCfg = match($med->stock_status) {
                    'in_stock'    => ['dot' => '#22c55e', 'text' => '#16a34a', 'label' => 'In Stock',    'qtyCls' => 'qty-in'],
                    'low_stock'   => ['dot' => '#f59e0b', 'text' => '#b45309', 'label' => 'Low Stock',   'qtyCls' => 'qty-low'],
                    'out_of_stock'=> ['dot' => '#ef4444', 'text' => '#b91c1c', 'label' => 'Out of Stock','qtyCls' => ''],
                    default       => ['dot' => '#22c55e', 'text' => '#16a34a', 'label' => 'In Stock',    'qtyCls' => 'qty-in'],
                };
            @endphp
                <div class="med-card">

                    {{-- Rx / OTC badge --}}
                    <span class="med-badge {{ $med->requires_prescription ? 'rx' : 'otc' }}">
                        {{ $med->requires_prescription ? 'Rx' : 'OTC' }}
                    </span>

                    {{-- Name --}}
                    <div class="med-name">{{ $med->name }}</div>

                    {{-- Generic name --}}
                    @if($med->generic_name)
                        <div class="med-generic">{{ $med->generic_name }}</div>
                    @endif

                    {{-- Dosage --}}
                    @if($med->dosage)
                        <div class="med-generic" style="color:#0288d1;font-weight:600">
                            <i class="fas fa-capsules me-1" style="font-size:.65rem"></i>{{ $med->dosage }}
                        </div>
                    @endif

                    {{-- Category --}}
                    @if($med->category)
                        <span class="med-cat-tag">{{ $med->category }}</span>
                    @endif

                    {{-- Manufacturer --}}
                    @if($med->manufacturer)
                        <div class="med-manufacturer">
                            <i class="fas fa-industry me-1" style="font-size:.6rem"></i>{{ $med->manufacturer }}
                        </div>
                    @endif

                    {{-- Price & Stock Status --}}
                    <div class="med-price-row">
                        <span class="med-price">LKR {{ number_format($med->price, 2) }}</span>

                        <span class="med-stock" style="color:{{ $stockCfg['text'] }}">
                            <span class="med-stock-dot" style="background:{{ $stockCfg['dot'] }}"></span>
                            {{ $stockCfg['label'] }}
                        </span>
                    </div>

                    {{-- ✅ Stock Quantity — actual units display --}}
                    @if($med->stock_status === 'lowstock')
                        <span class="med-qty-badge qty-low">
                            <i class="fas fa-exclamation-triangle me-1" style="font-size:.6rem"></i>
                            Only {{ $med->stock_quantity }} units left
                        </span>
                    @elseif($med->stock_status === 'instock')
                        <span class="med-qty-badge qty-in">
                            <i class="fas fa-boxes me-1" style="font-size:.6rem"></i>
                            {{ $med->stock_quantity }} units available
                        </span>
                    @endif

                    {{-- Description --}}
                    @if($med->description)
                        <div class="med-desc">
                            {{ Str::limit($med->description, 85) }}
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($medicines->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <small style="color:#888;font-size:.78rem">
                    Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines
                </small>
                {{ $medicines->withQueryString()->links() }}
            </div>
        @endif

    @else
        {{-- Empty State --}}
        <div class="ph-card" style="text-align:center;padding:3.5rem 2rem">
            <i class="fas fa-pills" style="font-size:3.5rem;color:#b2dfdb;display:block;margin-bottom:1.2rem"></i>
            <h4 style="color:#00796b;font-weight:700;margin-bottom:.5rem">No medicines found</h4>
            <p style="color:#aaa;font-size:.9rem;margin-bottom:1.5rem">
                @if(request('search') || request('category') || request('rx'))
                    No results match your current filters. Try adjusting your search.
                @else
                    This pharmacy hasn't listed any medicines yet.
                @endif
            </p>
            @if(request('search') || request('category') || request('rx'))
                <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                   style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:.75rem 2rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem">
                    <i class="fas fa-redo-alt"></i>Show All Medicines
                </a>
            @else
                <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
                   style="background:#f0f4f8;color:#374151;padding:.75rem 2rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem">
                    <i class="fas fa-arrow-left"></i>Back to Profile
                </a>
            @endif
        </div>
    @endif

    {{-- ===== BOTTOM CTA ===== --}}
    @if($medicines->total() > 0)
        <div style="margin-top:2.5rem;background:linear-gradient(135deg,#e0f2f1,#f0fdf4);border-radius:14px;padding:1.5rem 1.8rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
            <div>
                <div style="font-weight:700;color:#004d40;font-size:.95rem;margin-bottom:.25rem">
                    <i class="fas fa-prescription-bottle-alt me-2"></i>Ready to order?
                </div>
                <div style="font-size:.82rem;color:#555;line-height:1.5">
                    Upload your prescription and the pharmacy will verify the medicines for you.
                </div>
            </div>
            <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}" class="btn-place-order" style="font-size:.9rem;padding:.7rem 1.6rem">
                <i class="fas fa-prescription"></i> Place Prescription Order
            </a>
        </div>
    @endif

</div>

@include('partials.footer')
