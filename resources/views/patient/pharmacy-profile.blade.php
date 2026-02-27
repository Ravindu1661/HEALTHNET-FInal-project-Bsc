@include('partials.header')
<style>
/* ── HERO ── */
.ph-hero{background:linear-gradient(135deg,#004d40 0%,#00796b 60%,#00897b 100%);padding:7rem 0 3rem;color:#fff;position:relative;overflow:hidden}
.ph-hero::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.08;z-index:0}
.ph-hero::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:45px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.ph-hero .container{position:relative;z-index:1}
.ph-avatar{width:110px;height:110px;border-radius:16px;object-fit:cover;border:4px solid #fff;box-shadow:0 8px 25px rgba(0,0,0,.25)}
.ph-name{font-size:2rem;font-weight:700;margin-bottom:.3rem}
.ph-reg{font-size:.85rem;opacity:.85;margin-bottom:.6rem}
.ph-pill{background:rgba(255,255,255,.2);backdrop-filter:blur(6px);color:#fff;padding:.3rem .9rem;border-radius:20px;font-size:.8rem;font-weight:600;display:inline-flex;align-items:center;gap:.4rem;margin:.15rem}
.ph-pill.green{background:rgba(76,175,80,.35)}
.ph-pill.blue{background:rgba(2,136,209,.35)}
.ph-rating-hero{display:inline-flex;align-items:center;gap:.8rem;background:rgba(255,255,255,.12);backdrop-filter:blur(6px);padding:.6rem 1.4rem;border-radius:30px;margin-top:.8rem}
.ph-rating-hero .stars{color:#fbbf24;font-size:1rem}
/* ── TABS NAV ── */
.ph-tabs-nav{background:#fff;border-bottom:2px solid #e0f2f1;position:sticky;top:0;z-index:100;box-shadow:0 2px 10px rgba(0,121,107,.07)}
.ph-tabs-nav .nav-link{color:#666;font-weight:600;font-size:.88rem;padding:.95rem 1.2rem;border:none;border-bottom:3px solid transparent;transition:all .2s;display:flex;align-items:center;gap:.4rem}
.ph-tabs-nav .nav-link:hover,.ph-tabs-nav .nav-link.active{color:#00796b;border-bottom-color:#00796b}
/* ── CARDS ── */
.ph-card{background:#fff;border-radius:14px;padding:1.6rem;box-shadow:0 4px 18px rgba(0,0,0,.07);margin-bottom:1.5rem;border:1px solid #f0f4f0}
.ph-card-title{font-size:1rem;font-weight:700;color:#00796b;padding-bottom:.7rem;border-bottom:2px solid #e0f2f1;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.info-row{display:flex;padding:.6rem 0;border-bottom:1px solid #f5f5f5;font-size:.9rem}
.info-row:last-child{border-bottom:none}
.info-lbl{font-weight:600;color:#666;min-width:140px;display:flex;align-items:center;gap:.4rem}
.info-lbl i{color:#00796b;width:18px}
.info-val{color:#333;flex:1}
/* ── CONTACT BTNS ── */
.btn-contact{display:flex;align-items:center;gap:.7rem;padding:.8rem 1.1rem;border-radius:10px;font-weight:600;text-decoration:none;transition:all .3s;font-size:.88rem;border:none;cursor:pointer;width:100%;margin-bottom:.6rem}
.btn-call{background:#0288d1;color:#fff}.btn-call:hover{background:#01579b;color:#fff;transform:translateY(-2px)}
.btn-whatsapp{background:#25D366;color:#fff}.btn-whatsapp:hover{background:#128C7E;color:#fff;transform:translateY(-2px)}
.btn-email{background:#fff;color:#00796b;border:2px solid #00796b}.btn-email:hover{background:#00796b;color:#fff}
/* ── QUICK-NAV PILLS ── */
.quick-nav-row{display:flex;flex-wrap:wrap;gap:.6rem;margin:-1.5rem 0 2rem}
.quick-nav-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.1rem;border-radius:20px;background:#e0f2f1;color:#00796b;font-weight:600;font-size:.82rem;text-decoration:none;transition:all .3s}
.quick-nav-btn:hover,.quick-nav-btn.active{background:#00796b;color:#fff;transform:translateY(-1px)}
/* ── RATING BARS ── */
.rating-bar{height:8px;border-radius:4px;background:#e0f2f1;overflow:hidden}
.rating-bar-fill{height:100%;background:linear-gradient(90deg,#00796b,#4db6ac);border-radius:4px;transition:width .8s}
/* ── REVIEWS ── */
.review-card{border:1px solid #e8f5e9;border-radius:10px;padding:1rem;margin-bottom:.9rem}
.review-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover}
/* ── ORDER PILLS ── */
.o-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:14px;font-size:.72rem;font-weight:700}
.o-pill.pending{background:#fef3c7;color:#92400e}
.o-pill.verified{background:#e0f2fe;color:#0369a1}
.o-pill.processing{background:#ede9fe;color:#4c1d95}
.o-pill.ready{background:#dcfce7;color:#166534}
.o-pill.dispatched{background:#e0f2f1;color:#004d40}
.o-pill.delivered{background:#bbf7d0;color:#14532d}
.o-pill.cancelled{background:#fee2e2;color:#991b1b}
/* STAR RATING INPUT */
.star-inp{display:flex;flex-direction:row-reverse;gap:.3rem}.star-inp input{display:none}
.star-inp label{font-size:1.6rem;color:#d1d5db;cursor:pointer;transition:color .2s}
.star-inp input:checked ~ label,.star-inp label:hover,.star-inp label:hover ~ label{color:#fbbf24}
@media(max-width:768px){.ph-name{font-size:1.4rem}.ph-avatar{width:80px;height:80px}.ph-tabs-nav .nav-link{font-size:.75rem;padding:.7rem .6rem}}
</style>

{{-- HERO --}}
<section class="ph-hero">
    <div class="container">
        <a href="{{ route('patient.pharmacies') }}" style="color:rgba(255,255,255,.85);font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Pharmacies
        </a>
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                         alt="{{ $pharmacy->name }}" class="ph-avatar"
                         onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
                    <div>
                        <h1 class="ph-name">{{ $pharmacy->name }}</h1>
                        <div class="ph-reg"><i class="fas fa-id-badge me-1"></i> Reg: {{ $pharmacy->registration_number ?? 'N/A' }}</div>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="ph-pill"><i class="fas fa-check-circle"></i> Verified Pharmacy</span>
                            @if($pharmacy->delivery_available)
                                <span class="ph-pill blue"><i class="fas fa-truck"></i> Home Delivery</span>
                            @endif
                            @if($pharmacy->operating_hours)
                                <span class="ph-pill green"><i class="fas fa-clock"></i> {{ Str::limit($pharmacy->operating_hours, 28) }}</span>
                            @endif
                        </div>
                        <div class="ph-rating-hero">
                            <span class="stars">
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i<=round($pharmacy->rating??0)?'':' opacity-25' }}"></i>@endfor
                            </span>
                            <strong style="font-size:1.3rem;font-weight:700">{{ number_format($pharmacy->rating??0,1) }}</strong>
                            <span style="font-size:.82rem;opacity:.85">({{ $pharmacy->total_ratings??0 }} reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STICKY TABS --}}
<nav class="ph-tabs-nav">
    <div class="container">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link active" href="#overview"><i class="fas fa-info-circle"></i>Overview</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"><i class="fas fa-pills"></i>Medicines</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}"><i class="fas fa-prescription"></i>Place Order</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('patient.pharmacies.track', $pharmacy->id) }}"><i class="fas fa-map-marker-alt"></i>Track Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="#reviews"><i class="fas fa-star"></i>Reviews</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="padding:2rem 0 3rem">
    {{-- ALERTS --}}
    @foreach(['success','error','info'] as $t)
        @if(session($t))
            <div class="ph-card" style="background:{{ $t==='success'?'#dcfce7':($t==='error'?'#fee2e2':'#e0f2fe') }};border-left:5px solid {{ $t==='success'?'#059669':($t==='error'?'#dc2626':'#0891b2') }};padding:1rem 1.3rem;display:flex;align-items:center;gap:.8rem;font-weight:500">
                <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }}" style="font-size:1.2rem;flex-shrink:0"></i>
                {{ session($t) }}
            </div>
        @endif
    @endforeach

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- OVERVIEW --}}
            <div id="overview" class="ph-card">
                <div class="ph-card-title"><i class="fas fa-info-circle"></i> Pharmacy Information</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-user-md"></i>Pharmacist</span><span class="info-val">{{ $pharmacy->pharmacist_name??'N/A' }}</span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-certificate"></i>License</span><span class="info-val">{{ $pharmacy->pharmacist_license??'N/A' }}</span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-clock"></i>Hours</span><span class="info-val">{{ $pharmacy->operating_hours??'N/A' }}</span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-truck"></i>Delivery</span><span class="info-val">
                            @if($pharmacy->delivery_available) <span class="badge bg-success">Available</span>
                            @else <span class="badge bg-secondary">Pickup Only</span> @endif
                        </span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-phone"></i>Phone</span><span class="info-val"><a href="tel:{{ $pharmacy->phone }}" style="color:#00796b;font-weight:600;text-decoration:none">{{ $pharmacy->phone??'N/A' }}</a></span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-envelope"></i>Email</span><span class="info-val"><a href="mailto:{{ $pharmacy->email }}" style="color:#00796b;text-decoration:none">{{ $pharmacy->email??'N/A' }}</a></span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-map-pin"></i>City</span><span class="info-val">{{ $pharmacy->city??'N/A' }}{{ $pharmacy->province?', '.$pharmacy->province:'' }}</span></div>
                        <div class="info-row"><span class="info-lbl"><i class="fas fa-map-marker-alt"></i>Address</span><span class="info-val" style="font-size:.85rem">{{ $pharmacy->address??'N/A' }}</span></div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}" class="quick-nav-btn"><i class="fas fa-pills"></i>Browse Medicines</a>
                    <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}" class="quick-nav-btn"><i class="fas fa-prescription"></i>Place Order</a>
                    <a href="{{ route('patient.pharmacies.track', $pharmacy->id) }}" class="quick-nav-btn"><i class="fas fa-map-marker-alt"></i>Track Orders</a>
                </div>
            </div>

            {{-- RECENT ORDERS (mini) --}}
            @if($previousOrders->count())
            <div class="ph-card">
                <div class="ph-card-title"><i class="fas fa-history"></i> Your Recent Orders</div>
                @foreach($previousOrders as $o)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color:#f0f4f0!important">
                    <div>
                        <div style="font-weight:700;font-size:.88rem">{{ $o->order_number }}</div>
                        <div style="font-size:.76rem;color:#888">{{ optional($o->created_at)->format('d M Y') }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="o-pill {{ $o->status }}">{{ ucfirst($o->status) }}</span>
                        <a href="{{ route('patient.pharmacies.track', ['id'=>$pharmacy->id,'order'=>$o->id]) }}" style="font-size:.78rem;color:#00796b;text-decoration:none;font-weight:600"><i class="fas fa-eye me-1"></i>Track</a>
                    </div>
                </div>
                @endforeach
                <a href="{{ route('patient.pharmacies.track', $pharmacy->id) }}" style="display:block;text-align:center;color:#00796b;font-weight:600;font-size:.85rem;margin-top:.8rem;text-decoration:none">View All Orders →</a>
            </div>
            @endif

            {{-- REVIEWS --}}
            <div id="reviews" class="ph-card">
                <div class="ph-card-title"><i class="fas fa-star"></i> Customer Reviews</div>
                <div class="row align-items-center mb-4">
                    <div class="col-auto text-center">
                        <div style="font-size:3.5rem;font-weight:800;color:#00796b;line-height:1">{{ number_format($pharmacy->rating??0,1) }}</div>
                        <div style="color:#fbbf24">@for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i<=round($pharmacy->rating??0)?'':' text-muted' }}"></i>@endfor</div>
                        <div style="font-size:.78rem;color:#888">{{ $pharmacy->total_ratings??0 }} reviews</div>
                    </div>
                    <div class="col">
                        @foreach($ratingBreakdown as $star => $data)
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="width:20px;font-size:.78rem;color:#888">{{ $star }}★</span>
                            <div class="rating-bar flex-grow-1"><div class="rating-bar-fill" style="width:{{ $data['percentage'] }}%"></div></div>
                            <span style="width:24px;font-size:.76rem;color:#888">{{ $data['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Review Form --}}
                @auth
                @if($canReview)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:1.2rem;margin-bottom:1.2rem">
                    <div style="font-weight:700;color:#00796b;margin-bottom:.8rem;font-size:.95rem"><i class="fas fa-pen me-1"></i> Write Your Review</div>
                    <form action="{{ route('patient.pharmacies.review', $pharmacy->id) }}" method="POST">
                        @csrf
                        @if($reviewableOrder) <input type="hidden" name="related_order_id" value="{{ $reviewableOrder->id }}"> @endif
                        <label style="font-size:.82rem;font-weight:600;color:#555;display:block;margin-bottom:.4rem">Your Rating</label>
                        <div class="star-inp mb-2">
                            @for($i=5;$i>=1;$i--)
                                <input type="radio" id="s{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating')==$i?'checked':'' }}>
                                <label for="s{{ $i }}">★</label>
                            @endfor
                        </div>
                        <textarea name="review" style="width:100%;border:1.5px solid #e0f2f1;border-radius:8px;padding:.7rem;font-size:.88rem;resize:vertical" rows="2" placeholder="Share your experience...">{{ old('review') }}</textarea>
                        <button type="submit" style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:8px;padding:.6rem 1.4rem;font-weight:700;font-size:.88rem;margin-top:.6rem;cursor:pointer"><i class="fas fa-paper-plane me-1"></i>Submit Review</button>
                    </form>
                </div>
                @endif
                @endauth
                @forelse($ratings as $rating)
                <div class="review-card">
                    <div class="d-flex gap-2">
                        <img src="{{ $rating->patient?->profile_image ? asset('storage/'.$rating->patient->profile_image) : asset('images/default-avatar.png') }}"
                             class="review-avatar" onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong style="font-size:.88rem">{{ $rating->patient?->firstname??'Patient' }} {{ $rating->patient?->lastname??'' }}</strong>
                                <small style="color:#aaa">{{ optional($rating->created_at)->format('d M Y') }}</small>
                            </div>
                            <div style="color:#fbbf24;font-size:.8rem;margin:.2rem 0">
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i<=$rating->rating?'':' text-muted' }}"></i>@endfor
                            </div>
                            @if($rating->review)<p style="font-size:.85rem;color:#666;margin:0">{{ $rating->review }}</p>@endif
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:2rem;color:#aaa"><i class="fas fa-star fa-2x d-block mb-2"></i>No reviews yet.</div>
                @endforelse
                @if($ratings->hasPages()) <div class="mt-3">{{ $ratings->links() }}</div> @endif
            </div>
        </div>

       {{-- SIDEBAR --}}
<div class="col-lg-4">

    {{-- Contact --}}
    <div class="ph-card">
        <div class="ph-card-title"><i class="fas fa-address-book"></i> Contact</div>
        @if($pharmacy->phone)
        <a href="tel:{{ $pharmacy->phone }}" class="btn-contact btn-call">
            <i class="fas fa-phone-alt fa-lg"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">Call Us</div>
                <div style="font-size:.78rem">{{ $pharmacy->phone }}</div>
            </div>
        </a>
        <button class="btn-contact btn-whatsapp"
            onclick="window.open('https://wa.me/94{{ ltrim($pharmacy->phone??'','0') }}?text=Hello+{{ urlencode($pharmacy->name) }}','_blank')">
            <i class="fab fa-whatsapp fa-lg"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">WhatsApp</div>
                <div style="font-size:.78rem">Chat with pharmacy</div>
            </div>
        </button>
        @endif
        @if($pharmacy->email)
        <a href="mailto:{{ $pharmacy->email }}" class="btn-contact btn-email">
            <i class="fas fa-envelope fa-lg"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">Email Us</div>
                <div style="font-size:.78rem">{{ Str::limit($pharmacy->email, 28) }}</div>
            </div>
        </a>
        @endif
        @if($pharmacy->latitude && $pharmacy->longitude)
        <a href="https://www.google.com/maps?q={{ $pharmacy->latitude }},{{ $pharmacy->longitude }}"
           target="_blank" class="btn-contact" style="background:#34a853;color:#fff">
            <i class="fas fa-directions fa-lg"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">Get Directions</div>
                <div style="font-size:.78rem">Open in Google Maps</div>
            </div>
        </a>
        @endif
    </div>

    {{-- Delivery Partners --}}
    @if($pharmacy->delivery_available)
    <div class="ph-card" style="background:linear-gradient(135deg,#e0f2f1,#b2dfdb)">
        <div class="ph-card-title"><i class="fas fa-truck"></i> Delivery Partners</div>

        {{-- PickMe --}}
        <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-2 mb-2">
            <img src="{{ asset('images/pick_me.png') }}"
                 alt="PickMe"
                 style="width:55px;height:55px;object-fit:contain;flex-shrink:0"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'">
            <i class="fas fa-motorcycle fa-lg" style="width:28px;color:#00796b;display:none"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">PickMe</div>
                <small style="color:#888">Fast delivery</small>
            </div>
            <span class="badge bg-success ms-auto">Available</span>
        </div>

        {{-- Uber --}}
        <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-2 mb-2">
            <img src="{{ asset('images/Uber.png') }}"
                 alt="Uber"
                 style="width:55px;height:55px;object-fit:contain;flex-shrink:0"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block'">
            <i class="fas fa-car fa-lg" style="width:28px;color:#000;display:none"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">Uber</div>
                <small style="color:#888">Reliable</small>
            </div>
            <span class="badge bg-success ms-auto">Available</span>
        </div>

        {{-- Own Delivery --}}
        <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-2 mb-2">
            <i class="fas fa-truck fa-lg" style="width:28px;color:#00796b;flex-shrink:0"></i>
            <div>
                <div style="font-weight:700;font-size:.85rem">Own Delivery</div>
                <small style="color:#888">By pharmacy</small>
            </div>
            <span class="badge bg-success ms-auto">Available</span>
        </div>

    </div>
    @endif

    {{-- Pharmacist --}}
    <div class="ph-card" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9)">
        <div class="ph-card-title"><i class="fas fa-user-md"></i> Pharmacist</div>
        <div class="d-flex align-items-center gap-3">
            <div style="width:55px;height:55px;border-radius:50%;background:#00796b;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;flex-shrink:0">
                <i class="fas fa-user-md"></i>
            </div>
            <div>
                <div style="font-weight:700">{{ $pharmacy->pharmacist_name ?? 'N/A' }}</div>
                <div style="font-size:.8rem;color:#666">Reg. Pharmacist</div>
                <div style="font-size:.78rem;color:#888">Lic: {{ $pharmacy->pharmacist_license ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    </div>
</div>
</div>
@include('partials.footer')
