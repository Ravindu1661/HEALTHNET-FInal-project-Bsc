@include('partials.header')
<style>
.pm-header{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:6rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.pm-header::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.07;z-index:0}
.pm-header .container{position:relative;z-index:1}
.pm-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.med-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:1rem}
.med-card{background:#fff;border-radius:12px;border:1.5px solid #e8f5e9;padding:1.1rem;transition:all .3s;position:relative}
.med-card:hover{box-shadow:0 6px 20px rgba(0,121,107,.12);transform:translateY(-2px);border-color:#a5d6a7}
.med-badge{position:absolute;top:.7rem;right:.7rem;padding:.2rem .6rem;border-radius:10px;font-size:.65rem;font-weight:700}
.med-badge.rx{background:#fff3e0;color:#e65100}
.med-badge.otc{background:#e8f5e9;color:#2e7d32}
.med-name{font-weight:700;color:#1a1a1a;font-size:.92rem;margin-bottom:.15rem;padding-right:2.5rem}
.med-generic{font-size:.75rem;color:#888;margin-bottom:.5rem}
.med-price{font-size:1.05rem;font-weight:700;color:#00796b}
.med-stock{font-size:.72rem;font-weight:600}
.filter-card{background:#fff;border-radius:14px;padding:1.2rem 1.5rem;box-shadow:0 4px 18px rgba(0,0,0,.07);margin:-2.5rem 0 2rem;position:relative;z-index:10}
.filter-input{width:100%;padding:.55rem .7rem;border:1.5px solid #e0f2f1;border-radius:8px;font-size:.85rem;transition:border .3s}
.filter-input:focus{border-color:#00796b;outline:none;box-shadow:0 0 0 3px rgba(0,121,107,.1)}
.cat-btn{padding:.35rem .9rem;border-radius:20px;border:1.5px solid #e0f2f1;background:#fff;color:#555;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .3s}
.cat-btn.active,.cat-btn:hover{background:#00796b;color:#fff;border-color:#00796b}
.ph-card{background:#fff;border-radius:14px;padding:1.5rem;box-shadow:0 4px 18px rgba(0,0,0,.07)}
</style>

<section class="pm-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}" style="color:rgba(255,255,255,.85);font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none"><i class="fas fa-arrow-left"></i> Back to Profile</a>
        <div class="d-flex align-items-center gap-3 mb-2">
            <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}" style="width:60px;height:60px;border-radius:12px;object-fit:cover;border:3px solid rgba(255,255,255,.8)" onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
            <div>
                <h1 style="font-size:1.8rem;font-weight:700;margin:0">{{ $pharmacy->name }}</h1>
                <p style="opacity:.85;font-size:.9rem;margin:0"><i class="fas fa-pills me-1"></i> Available Medicines Catalogue</p>
            </div>
        </div>
    </div>
</section>

<div class="container" style="padding-bottom:3rem">
    {{-- FILTER BAR --}}
    <div class="filter-card">
        <form action="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" method="GET">
            <div class="row g-3">
                <div class="col-md-5">
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#00796b"></i>
                        <input type="text" name="search" class="filter-input" style="padding-left:2rem" placeholder="Search medicine name or generic name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="filter-input">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="rx" class="filter-input">
                        <option value="">All Types</option>
                        <option value="rx" {{ request('rx')==='rx'?'selected':'' }}>Prescription (Rx)</option>
                        <option value="otc" {{ request('rx')==='otc'?'selected':'' }}>Over-the-Counter (OTC)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:8px;padding:.55rem 1.2rem;font-weight:700;font-size:.88rem;width:100%;cursor:pointer"><i class="fas fa-search me-1"></i>Search</button>
                </div>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div style="font-size:.88rem;color:#666">
            <strong style="color:#00796b">{{ $medicines->total() }}</strong> medicines found
            @if(request('search') || request('category') || request('rx'))
                <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" style="color:#dc2626;font-size:.8rem;margin-left:.8rem;text-decoration:none"><i class="fas fa-redo-alt"></i> Clear filters</a>
            @endif
        </div>
        <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}" style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;text-decoration:none;border-radius:8px;padding:.5rem 1.1rem;font-weight:700;font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem">
            <i class="fas fa-prescription"></i>Place Order
        </a>
    </div>

    @if($medicines->count())
    <div class="med-grid">
        @foreach($medicines as $med)
        <div class="med-card">
            <span class="med-badge {{ $med->requires_prescription?'rx':'otc' }}">{{ $med->requires_prescription?'Rx':'OTC' }}</span>
            <div class="med-name">{{ $med->name }}</div>
            @if($med->generic_name) <div class="med-generic">{{ $med->generic_name }}</div> @endif
            @if($med->dosage) <div class="med-generic" style="color:#0288d1">{{ $med->dosage }}</div> @endif
            @if($med->category) <div style="font-size:.7rem;color:#888;margin-bottom:.5rem"><span style="background:#e0f2f1;color:#00796b;padding:.1rem .5rem;border-radius:8px">{{ $med->category }}</span></div> @endif
            @if($med->manufacturer) <div style="font-size:.72rem;color:#aaa;margin-bottom:.5rem">{{ $med->manufacturer }}</div> @endif
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="med-price">LKR {{ number_format($med->price, 2) }}</span>
                <span class="med-stock {{ $med->stock_status==='instock'?'text-success':($med->stock_status==='lowstock'?'text-warning':'text-danger') }}">
                    <i class="fas fa-circle" style="font-size:.5rem"></i>
                    {{ $med->stock_status==='instock'?'In Stock':($med->stock_status==='lowstock'?'Low Stock':'Out of Stock') }}
                </span>
            </div>
            @if($med->description)
                <div style="font-size:.75rem;color:#888;margin-top:.5rem;border-top:1px solid #f0f4f0;padding-top:.5rem">{{ Str::limit($med->description, 80) }}</div>
            @endif
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">{{ $medicines->withQueryString()->links() }}</div>
    @else
    <div class="ph-card" style="text-align:center;padding:3rem">
        <i class="fas fa-pills" style="font-size:3rem;color:#b2dfdb;display:block;margin-bottom:1rem"></i>
        <h4 style="color:#00796b;font-weight:700">No medicines found</h4>
        <p style="color:#aaa;font-size:.9rem">Try different search criteria.</p>
        @if(request('search') || request('category') || request('rx'))
            <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" style="background:#00796b;color:#fff;padding:.7rem 1.8rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem"><i class="fas fa-redo-alt"></i>Show All Medicines</a>
        @endif
    </div>
    @endif
</div>
@include('partials.footer')
