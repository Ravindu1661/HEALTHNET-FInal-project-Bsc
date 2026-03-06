@include('partials.header')

<style>
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
.cat-pill:hover, .cat-pill.active { background: #00796b; color: #fff; border-color: #00796b; }
.cat-pill.all-pill.active { background: #004d40; border-color: #004d40; }
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
.med-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
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
.med-card.out-of-stock-card { opacity: .7; border-color: #fecaca; }
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
    margin-top: .5rem;
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
.med-qty-badge {
    display: inline-block;
    font-size: .68rem;
    font-weight: 700;
    padding: .12rem .5rem;
    border-radius: 8px;
    margin-top: .3rem;
}
.med-qty-badge.qty-in  { background: #dcfce7; color: #166534; }
.med-qty-badge.qty-low { background: #fef9c3; color: #854d0e; }
.med-qty-badge.qty-out { background: #fee2e2; color: #991b1b; }
.btn-add-cart {
    width: 100%;
    margin-top: .75rem;
    padding: .5rem;
    border: none;
    border-radius: 9px;
    font-size: .82rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    transition: all .22s;
}
.btn-add-cart.can-order {
    background: linear-gradient(135deg, #00796b, #004d40);
    color: #fff;
    box-shadow: 0 3px 8px rgba(0,121,107,.2);
}
.btn-add-cart.can-order:hover { filter: brightness(1.1); transform: translateY(-1px); }
.btn-add-cart.out-btn { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
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
    border: none;
    cursor: pointer;
}
.btn-place-order:hover { filter: brightness(1.08); transform: translateY(-1px); color: #fff; }
.ph-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
}
.pagination .page-link {
    border-radius: 8px !important;
    margin: 0 2px;
    color: #00796b;
    border-color: #e0f2f1;
    font-size: .83rem;
}
.pagination .page-item.active .page-link { background: #00796b; border-color: #00796b; }
.cart-fab {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 999;
    background: linear-gradient(135deg, #00796b, #004d40);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: .75rem 1.4rem;
    font-weight: 700;
    font-size: .9rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    box-shadow: 0 6px 20px rgba(0,121,107,.35);
    cursor: pointer;
    transition: all .25s;
}
.cart-fab:hover { filter: brightness(1.08); transform: translateY(-2px); }
.cart-fab .cart-count {
    background: #fbbf24;
    color: #78350f;
    border-radius: 50px;
    padding: .05rem .5rem;
    font-size: .78rem;
    font-weight: 800;
    min-width: 22px;
    text-align: center;
}
.cart-item-row {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    padding: .75rem 0;
    border-bottom: 1px solid #f0f4f0;
}
.cart-item-row:last-child { border-bottom: none; }
.cart-qty-ctrl { display: flex; align-items: center; gap: .3rem; margin-top: .3rem; }
.qty-btn {
    width: 24px; height: 24px;
    border-radius: 6px;
    border: 1.5px solid #e0f2f1;
    background: #fff;
    color: #00796b;
    font-weight: 700;
    font-size: .85rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.qty-btn:hover { background: #00796b; color: #fff; border-color: #00796b; }
.qty-val { min-width: 28px; text-align: center; font-weight: 700; font-size: .85rem; color: #1a1a1a; }
@media (max-width: 576px) {
    .cat-pills { flex-wrap: nowrap; overflow-x: auto; padding-bottom: .3rem; }
    .cat-pills::-webkit-scrollbar { height: 3px; }
    .cat-pills::-webkit-scrollbar-thumb { background: #b2dfdb; border-radius: 3px; }
    .med-grid { grid-template-columns: 1fr 1fr; gap: .75rem; }
    .stats-bar { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 400px) { .med-grid { grid-template-columns: 1fr; } }
@keyframes slideDown {
    from { opacity:0; transform:translateY(-10px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>


{{-- HEADER --}}
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
                <h1 style="font-size:1.75rem;font-weight:800;margin:0;line-height:1.2">{{ $pharmacy->name }}</h1>
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


{{-- BODY --}}
<div class="container" style="padding-bottom:5rem">

    @foreach(['success','error','info'] as $t)
        @if(session($t))
            <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 shadow-sm mt-3" style="border-radius:10px">
                <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }} me-2"></i>
                {{ session($t) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- FILTER BAR --}}
    <div class="filter-card">
        <form action="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
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
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit"
                            style="flex:1;background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:8px;padding:.6rem .5rem;font-weight:700;font-size:.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.4rem">
                        <i class="fas fa-search"></i>
                        <span class="d-none d-md-inline">Search</span>
                    </button>
                    @if(request('search') || request('category') || request('rx'))
                        <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                           style="background:#fee2e2;color:#dc2626;border:none;border-radius:8px;padding:.6rem .7rem;display:flex;align-items:center;justify-content:center;text-decoration:none"
                           title="Clear filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
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

    {{-- STATS BAR --}}
    @php
        $inStockCount  = $medicines->getCollection()->where('stock_status', 'in_stock')->count();
        $lowStockCount = $medicines->getCollection()->where('stock_status', 'low_stock')->count();
        $outStockCount = $medicines->getCollection()->where('stock_status', 'out_of_stock')->count();
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
                        <i class="fas fa-check-circle" style="font-size:.65rem"></i> {{ $inStockCount }} In Stock
                    </span>
                @endif
                @if($lowStockCount)
                    <span class="chip chip-low">
                        <i class="fas fa-exclamation-triangle" style="font-size:.65rem"></i> {{ $lowStockCount }} Low Stock
                    </span>
                @endif
                @if($outStockCount)
                    <span class="chip chip-out">
                        <i class="fas fa-times-circle" style="font-size:.65rem"></i> {{ $outStockCount }} Out of Stock
                    </span>
                @endif
            </div>
            <button class="btn-place-order" onclick="openCart()" type="button">
                <i class="fas fa-shopping-basket"></i>
                View Cart
                <span id="cartCountBadge" style="background:#fbbf24;color:#78350f;border-radius:50px;padding:.05rem .45rem;font-size:.75rem;font-weight:800;display:none">0</span>
            </button>
        </div>
    </div>

    {{-- MEDICINES GRID --}}
    @if($medicines->count())
        <div class="med-grid">
            @foreach($medicines as $med)
                @php
                    $stockCfg = match($med->stock_status) {
                        'in_stock'     => ['dot'=>'#22c55e','text'=>'#16a34a','label'=>'In Stock',    'qtyCls'=>'qty-in'],
                        'low_stock'    => ['dot'=>'#f59e0b','text'=>'#b45309','label'=>'Low Stock',   'qtyCls'=>'qty-low'],
                        'out_of_stock' => ['dot'=>'#ef4444','text'=>'#b91c1c','label'=>'Out of Stock','qtyCls'=>'qty-out'],
                        default        => ['dot'=>'#22c55e','text'=>'#16a34a','label'=>'In Stock',    'qtyCls'=>'qty-in'],
                    };
                    $canOrder = $med->stock_status !== 'out_of_stock';
                @endphp
                <div class="med-card {{ !$canOrder ? 'out-of-stock-card' : '' }}">

                    <span class="med-badge {{ $med->requires_prescription ? 'rx' : 'otc' }}">
                        {{ $med->requires_prescription ? 'Rx' : 'OTC' }}
                    </span>

                    <div class="med-name">{{ $med->name }}</div>

                    @if($med->generic_name)
                        <div class="med-generic">{{ $med->generic_name }}</div>
                    @endif

                    @if($med->dosage)
                        <div class="med-generic" style="color:#0288d1;font-weight:600">
                            <i class="fas fa-capsules me-1" style="font-size:.65rem"></i>{{ $med->dosage }}
                        </div>
                    @endif

                    @if($med->category)
                        <span class="med-cat-tag">{{ $med->category }}</span>
                    @endif

                    @if($med->manufacturer)
                        <div class="med-manufacturer">
                            <i class="fas fa-industry me-1" style="font-size:.6rem"></i>{{ $med->manufacturer }}
                        </div>
                    @endif

                    <div class="med-price-row">
                        <span class="med-price">LKR {{ number_format($med->price, 2) }}</span>
                        <span class="med-stock" style="color:{{ $stockCfg['text'] }}">
                            <span class="med-stock-dot" style="background:{{ $stockCfg['dot'] }}"></span>
                            {{ $stockCfg['label'] }}
                        </span>
                    </div>

                    @if($med->stock_status === 'low_stock')
                        <span class="med-qty-badge qty-low">
                            <i class="fas fa-exclamation-triangle me-1" style="font-size:.6rem"></i>
                            Only {{ $med->stock_quantity }} units left
                        </span>
                    @elseif($med->stock_status === 'in_stock')
                        <span class="med-qty-badge qty-in">
                            <i class="fas fa-boxes me-1" style="font-size:.6rem"></i>
                            {{ $med->stock_quantity }} units available
                        </span>
                    @elseif($med->stock_status === 'out_of_stock')
                        <span class="med-qty-badge qty-out">
                            <i class="fas fa-times-circle me-1" style="font-size:.6rem"></i>
                            Out of Stock
                        </span>
                    @endif

                    @if($med->description)
                        <div class="med-desc">{{ Str::limit($med->description, 85) }}</div>
                    @endif

                    @if($canOrder)
                        <button
                            class="btn-add-cart can-order"
                            onclick="addToCart(
                                {{ $med->id }},
                                '{{ addslashes($med->name) }}',
                                {{ $med->price }},
                                {{ $med->stock_quantity }},
                                {{ $med->requires_prescription ? 'true' : 'false' }}
                            )">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    @else
                        <button class="btn-add-cart out-btn" disabled>
                            <i class="fas fa-ban"></i> Out of Stock
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        @if($medicines->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <small style="color:#888;font-size:.78rem">
                    Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines
                </small>
                {{ $medicines->withQueryString()->links() }}
            </div>
        @endif
    @else
        <div class="ph-card" style="text-align:center;padding:3.5rem 2rem">
            <i class="fas fa-pills" style="font-size:3.5rem;color:#b2dfdb;display:block;margin-bottom:1.2rem"></i>
            <h4 style="color:#00796b;font-weight:700;margin-bottom:.5rem">No medicines found</h4>
            <p style="color:#aaa;font-size:.9rem;margin-bottom:1.5rem">
                @if(request('search') || request('category') || request('rx'))
                    No results match your current filters.
                @else
                    This pharmacy hasn't listed any medicines yet.
                @endif
            </p>
            @if(request('search') || request('category') || request('rx'))
                <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                   style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:.75rem 2rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem">
                    <i class="fas fa-redo-alt"></i> Show All Medicines
                </a>
            @else
                <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
                   style="background:#f0f4f8;color:#374151;padding:.75rem 2rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
            @endif
        </div>
    @endif

    {{-- BOTTOM CTA --}}
    @if($medicines->total() > 0)
        <div style="margin-top:2.5rem;background:linear-gradient(135deg,#e0f2f1,#f0fdf4);border-radius:14px;padding:1.5rem 1.8rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
            <div>
                <div style="font-weight:700;color:#004d40;font-size:.95rem;margin-bottom:.25rem">
                    <i class="fas fa-prescription-bottle-alt me-2"></i>Ready to order?
                </div>
                <div style="font-size:.82rem;color:#555;line-height:1.5">
                    Add medicines to cart and proceed, or place a prescription-only order.
                </div>
            </div>
            <div style="display:flex;gap:.6rem;flex-wrap:wrap">
                <button onclick="openCart()" class="btn-place-order" style="font-size:.88rem;padding:.65rem 1.3rem" type="button">
                    <i class="fas fa-shopping-basket"></i> View Cart & Order
                </button>
                <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}" class="btn-place-order"
                   style="font-size:.88rem;padding:.65rem 1.3rem;background:linear-gradient(135deg,#1565c0,#0d47a1)">
                    <i class="fas fa-file-medical"></i> Prescription Only Order
                </a>
            </div>
        </div>
    @endif
</div>


{{-- FLOATING CART FAB --}}
<button class="cart-fab" id="cartFab" onclick="openCart()" style="display:none" type="button">
    <i class="fas fa-shopping-basket"></i>
    My Cart
    <span class="cart-count" id="cartFabCount">0</span>
</button>


{{-- CART OVERLAY --}}
<div id="cartOverlay"
     onclick="closeCart()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1040;backdrop-filter:blur(2px)">
</div>

{{-- CART PANEL --}}
<div id="cartPanel"
     style="position:fixed;right:-440px;top:0;width:min(420px,100vw);height:100vh;background:#fff;z-index:1050;
            display:flex;flex-direction:column;box-shadow:-6px 0 30px rgba(0,0,0,.15);
            transition:right .32s cubic-bezier(.4,0,.2,1);border-left:1px solid #e0f2f1">

    {{-- Cart Header --}}
    <div style="padding:1.1rem 1.25rem;background:linear-gradient(135deg,#004d40,#00796b);color:#fff;display:flex;align-items:center;justify-content:space-between;flex-shrink:0">
        <div style="display:flex;align-items:center;gap:.5rem">
            <i class="fas fa-shopping-basket" style="font-size:1rem"></i>
            <span style="font-size:1rem;font-weight:700">My Cart</span>
            <span id="cartHeaderCount" style="background:rgba(255,255,255,.25);border-radius:50px;padding:.05rem .5rem;font-size:.75rem;font-weight:800">0 items</span>
        </div>
        <button onclick="closeCart()" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;width:30px;height:30px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Rx Notice --}}
    <div id="rxNotice" style="display:none;background:#fff3e0;border-bottom:1px solid #ffcc80;padding:.65rem 1.1rem;font-size:.78rem;color:#e65100;flex-shrink:0">
        <i class="fas fa-exclamation-triangle me-1"></i>
        Cart-ල <strong>Prescription (Rx)</strong> medicines ඇත. Checkout-ල valid prescription upload කරන්නට සිදුවේ.
    </div>

    {{-- Cart Items --}}
    <div id="cartItems" style="flex:1;overflow-y:auto;padding:.5rem 1.1rem;display:none"></div>

    {{-- Empty --}}
    <div id="cartEmpty" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#aaa;padding:2rem">
        <i class="fas fa-shopping-basket" style="font-size:3rem;margin-bottom:1rem;opacity:.25"></i>
        <p style="font-size:.9rem;margin:0">Your cart is empty</p>
        <p style="font-size:.78rem;margin:.3rem 0 0">Add medicines to get started</p>
    </div>

    {{-- Footer --}}
    <div id="cartFooter" style="display:none;border-top:1px solid #e0f2f1;padding:1rem 1.1rem;flex-shrink:0;background:#f9fffe">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem">
            <span style="font-size:.85rem;color:#555;font-weight:600">Estimated Subtotal</span>
            <span id="cartTotal" style="font-size:1.2rem;font-weight:800;color:#00796b">LKR 0.00</span>
        </div>
        <div style="font-size:.72rem;color:#888;margin-bottom:.6rem;text-align:center">
            <i class="fas fa-info-circle me-1"></i>Final amount confirmed by pharmacy after prescription review.
        </div>
        <div style="display:flex;gap:.6rem">
            <button onclick="clearCart()" type="button"
                    style="flex:0;background:#fee2e2;color:#dc2626;border:none;border-radius:9px;padding:.6rem .9rem;font-weight:700;font-size:.8rem;cursor:pointer"
                    title="Clear cart">
                <i class="fas fa-trash"></i>
            </button>
            <button onclick="proceedToOrder()" type="button"
                    style="flex:1;background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:9px;padding:.65rem;font-weight:700;font-size:.88rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem">
                <i class="fas fa-prescription-bottle-alt"></i> Proceed to Order
            </button>
        </div>
    </div>
</div>


<script>
const PHARMACY_ID = '{{ $pharmacy->id }}';
const CART_KEY    = 'phCart_' + PHARMACY_ID;
const ORDER_URL   = '{{ route('patient.pharmacies.order.form', $pharmacy->id) }}';

let cart = JSON.parse(sessionStorage.getItem(CART_KEY) || '[]');

function saveCart() {
    sessionStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function addToCart(id, name, price, stock, requiresRx) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            showToast('<i class="fas fa-exclamation-circle me-1"></i>Maximum available stock reached!', 'warning');
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, stock, requiresRx, qty: 1 });
    }
    saveCart();
    renderCart();
    showToast('<i class="fas fa-check-circle me-1"></i> <strong>' + name + '</strong> added to cart', 'success');
    openCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== id);
    } else if (item.qty > item.stock) {
        item.qty = item.stock;
        showToast('Maximum stock limit reached', 'warning');
    }
    saveCart();
    renderCart();
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
    renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    if (!confirm('Clear all items from cart?')) return;
    cart = [];
    saveCart();
    renderCart();
}

function renderCart() {
    const total  = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const count  = cart.reduce((s, i) => s + i.qty, 0);
    const hasRx  = cart.some(i => i.requiresRx);
    const isEmpty = cart.length === 0;

    // FAB
    const fab = document.getElementById('cartFab');
    fab.style.display = count > 0 ? 'flex' : 'none';
    document.getElementById('cartFabCount').textContent = count;

    // Header
    document.getElementById('cartHeaderCount').textContent = count + ' item' + (count !== 1 ? 's' : '');

    // Stats badge
    const badge = document.getElementById('cartCountBadge');
    if (badge) { badge.style.display = count > 0 ? 'inline' : 'none'; badge.textContent = count; }

    // Rx notice
    document.getElementById('rxNotice').style.display = hasRx ? 'block' : 'none';

    // Visibility
    document.getElementById('cartEmpty').style.display  = isEmpty ? 'flex' : 'none';
    document.getElementById('cartFooter').style.display = isEmpty ? 'none' : 'block';
    document.getElementById('cartItems').style.display  = isEmpty ? 'none' : 'block';

    // Total
    document.getElementById('cartTotal').textContent =
        'LKR ' + total.toLocaleString('en-LK', {minimumFractionDigits:2, maximumFractionDigits:2});

    // Items
    document.getElementById('cartItems').innerHTML = cart.map(item => `
        <div class="cart-item-row">
            <div style="flex:1;min-width:0">
                <div style="font-weight:700;font-size:.85rem;color:#1a1a1a;margin-bottom:.1rem">${item.name}</div>
                <div style="font-size:.75rem;color:#00796b;font-weight:700">
                    LKR ${item.price.toLocaleString('en-LK',{minimumFractionDigits:2})} each
                </div>
                ${item.requiresRx
                    ? '<span style="font-size:.65rem;background:#fff3e0;color:#e65100;padding:.1rem .4rem;border-radius:6px;font-weight:700">Rx</span>'
                    : '<span style="font-size:.65rem;background:#e8f5e9;color:#2e7d32;padding:.1rem .4rem;border-radius:6px;font-weight:700">OTC</span>'
                }
                <div class="cart-qty-ctrl">
                    <button class="qty-btn" onclick="changeQty(${item.id}, -1)" type="button">
                        <i class="fas fa-minus" style="font-size:.65rem"></i>
                    </button>
                    <span class="qty-val">${item.qty}</span>
                    <button class="qty-btn" onclick="changeQty(${item.id}, 1)" type="button">
                        <i class="fas fa-plus" style="font-size:.65rem"></i>
                    </button>
                    <span style="font-size:.7rem;color:#aaa;margin-left:.3rem">max ${item.stock}</span>
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <div style="font-weight:800;color:#004d40;font-size:.92rem;margin-bottom:.4rem">
                    LKR ${(item.price * item.qty).toLocaleString('en-LK',{minimumFractionDigits:2})}
                </div>
                <button onclick="removeItem(${item.id})" type="button"
                        style="background:#fee2e2;color:#dc2626;border:none;border-radius:6px;padding:.22rem .5rem;font-size:.72rem;cursor:pointer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function openCart() {
    document.getElementById('cartPanel').style.right    = '0';
    document.getElementById('cartOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeCart() {
    document.getElementById('cartPanel').style.right    = '-440px';
    document.getElementById('cartOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

function proceedToOrder() {
    if (cart.length === 0) {
        showToast('<i class="fas fa-exclamation-circle me-1"></i> Your cart is empty!', 'warning');
        return;
    }
    // Save cart to sessionStorage & navigate
    saveCart();
    window.location.href = ORDER_URL;
}

function showToast(msg, type = 'success') {
    const colors = {
        success : { bg:'#dcfce7', color:'#166534', border:'#86efac' },
        warning : { bg:'#fef9c3', color:'#854d0e', border:'#fde68a' },
        error   : { bg:'#fee2e2', color:'#991b1b', border:'#fca5a5' },
    };
    const c = colors[type] || colors.success;
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;top:1.2rem;right:1.2rem;z-index:9999;
        background:${c.bg};color:${c.color};border:1px solid ${c.border};
        border-radius:12px;padding:.7rem 1.1rem;font-size:.83rem;font-weight:600;
        box-shadow:0 6px 20px rgba(0,0,0,.12);animation:slideDown .3s ease;max-width:320px`;
    t.innerHTML = msg;
    document.body.appendChild(t);
    setTimeout(() => {
        t.style.opacity = '0';
        t.style.transition = 'opacity .3s';
        setTimeout(() => t.remove(), 300);
    }, 2800);
}

document.addEventListener('DOMContentLoaded', renderCart);
</script>

@include('partials.footer')
