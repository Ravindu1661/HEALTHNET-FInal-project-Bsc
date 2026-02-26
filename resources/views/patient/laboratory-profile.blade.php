@include('partials.header')

<style>
/* ═══════════════════════════════════════════════════════
   LABORATORY PROFILE PAGE — Teal Theme
═══════════════════════════════════════════════════════ */

/* ── Page Header ── */
.lab-prof-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.lab-prof-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.07;
    z-index: 0;
}
.lab-prof-header .container { position: relative; z-index: 1; }
.lab-prof-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 45px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}

.lab-profile-img {
    width: 110px; height: 110px;
    border-radius: 16px; object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    margin-bottom: 1rem;
}
.lab-profile-img-placeholder {
    width: 110px; height: 110px;
    border-radius: 16px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; color: white;
    border: 4px solid rgba(255,255,255,0.4);
    margin-bottom: 1rem;
}
.lab-prof-name { font-size: 2rem; font-weight: 700; margin-bottom: 0.4rem; }
.lab-prof-reg  { font-size: 0.85rem; opacity: 0.85; margin-bottom: 0.6rem; }

.verified-pill-teal {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(6px);
    color: white;
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    font-size: 0.8rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.home-pill-teal {
    background: rgba(6,182,212,0.3);
    backdrop-filter: blur(6px);
    color: white;
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    font-size: 0.8rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 0.4rem;
    margin-left: 0.4rem;
}
.rating-hero {
    display: inline-flex; align-items: center; gap: 0.8rem;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(6px);
    padding: 0.6rem 1.4rem; border-radius: 30px; margin-top: 0.8rem;
}
.rating-hero .stars { color: #fbbf24; font-size: 1rem; }
.rating-hero .num   { font-size: 1.3rem; font-weight: 700; }
.rating-hero .cnt   { font-size: 0.82rem; opacity: 0.85; }

/* Quick Info Pills in Header */
.quick-info-row { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.8rem; }
.quick-pill {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(6px);
    color: white;
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    font-size: 0.8rem; font-weight: 500;
    display: inline-flex; align-items: center; gap: 0.4rem;
    border: 1px solid rgba(255,255,255,0.2);
}

/* Back Button */
.btn-back-lab {
    display: inline-flex; align-items: center; gap: 0.5rem;
    color: rgba(255,255,255,0.9); text-decoration: none;
    font-size: 0.88rem; margin-bottom: 1rem; transition: all 0.3s;
}
.btn-back-lab:hover { color: white; transform: translateX(-4px); }

/* ── Main ── */
.lab-prof-main {
    background: #f4f6f9;
    padding: 2rem 0 4rem;
}

/* ── Section Card ── */
.lab-section-card {
    background: white;
    border-radius: 14px;
    padding: 1.6rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
    transition: all 0.3s;
}
.lab-section-card:hover {
    box-shadow: 0 6px 24px rgba(8,145,178,0.12);
    transform: translateY(-2px);
}
.lab-section-title {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 1.1rem;
    padding-bottom: 0.7rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.lab-section-title i { color: #0891b2; }

/* ── Info Rows ── */
.lab-info-row {
    display: flex;
    padding: 0.7rem 0;
    border-bottom: 1px solid #f7f7f7;
}
.lab-info-row:last-child { border-bottom: none; }
.lab-info-label {
    font-weight: 600; color: #777;
    min-width: 150px;
    display: flex; align-items: center;
    font-size: 0.88rem;
}
.lab-info-label i { width: 20px; margin-right: 0.5rem; color: #0891b2; }
.lab-info-value { flex: 1; color: #333; font-size: 0.9rem; }

/* ── Services Grid ── */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 0.7rem;
}
.service-badge-teal {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    color: #0369a1;
    padding: 0.55rem 0.9rem;
    border-radius: 8px;
    font-size: 0.82rem; font-weight: 600;
    display: flex; align-items: center; gap: 0.45rem;
    transition: all 0.3s;
}
.service-badge-teal:hover {
    background: linear-gradient(135deg, #bae6fd, #7dd3fc);
    transform: translateX(3px);
}

/* ── Tests Table ── */
.tests-table thead th {
    background: #e0f2fe; color: #0c4a6e;
    font-size: 0.8rem; font-weight: 700; border: none;
}
.tests-table tbody td {
    font-size: 0.85rem; vertical-align: middle; border-color: #f0f9ff;
}
.tests-table tbody tr:hover { background: #f0f9ff; }

/* ── Packages ── */
.pkg-card {
    border: 2px solid #e0f2fe;
    border-radius: 12px;
    padding: 1.2rem;
    height: 100%;
    transition: all 0.3s;
}
.pkg-card:hover {
    border-color: #0891b2;
    box-shadow: 0 4px 14px rgba(8,145,178,0.12);
}
.pkg-name { font-weight: 700; color: #0c4a6e; margin-bottom: 0.5rem; font-size: 0.95rem; }
.pkg-price { font-size: 1.3rem; font-weight: 700; color: #0891b2; line-height: 1; }
.pkg-original { text-decoration: line-through; color: #aaa; font-size: 0.85rem; margin-left: 0.4rem; }
.pkg-discount {
    background: #dcfce7; color: #166534;
    padding: 0.15rem 0.5rem; border-radius: 8px;
    font-size: 0.72rem; font-weight: 700; margin-left: 0.4rem;
}
.pkg-tests-list { margin-top: 0.8rem; display: flex; flex-direction: column; gap: 0.3rem; }
.pkg-test-item {
    font-size: 0.78rem; color: #555;
    display: flex; align-items: center; gap: 0.4rem;
}
.pkg-test-item i { color: #0891b2; font-size: 0.7rem; }

/* ── Previous Orders ── */
.order-pill {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.25rem 0.7rem; border-radius: 12px;
    font-size: 0.72rem; font-weight: 600;
}
.order-pill.pending          { background: #fef3c7; color: #92400e; }
.order-pill.sample_collected { background: #e0f2fe; color: #0369a1; }
.order-pill.processing       { background: #ede9fe; color: #4c1d95; }
.order-pill.completed        { background: #dcfce7; color: #166534; }
.order-pill.cancelled        { background: #fee2e2; color: #991b1b; }

/* ── Action Card (Right Column) ── */
.action-card {
    background: white;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.action-card h5 {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 0.8rem;
    padding-bottom: 0.6rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.action-card h5 i { color: #0891b2; }

.btn-action-primary {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.85rem 1.2rem; border-radius: 10px; text-decoration: none;
    font-weight: 700; font-size: 0.9rem; margin-bottom: 0.7rem;
    transition: all 0.3s; border: none; cursor: pointer; width: 100%;
    background: linear-gradient(135deg, #0891b2, #0c4a6e); color: white;
    box-shadow: 0 4px 14px rgba(8,145,178,0.3);
}
.btn-action-primary:hover { color: white; filter: brightness(1.1); transform: translateY(-2px); }

.btn-action-green {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.85rem 1.2rem; border-radius: 10px; text-decoration: none;
    font-weight: 700; font-size: 0.9rem; margin-bottom: 0.7rem;
    transition: all 0.3s; border: none; cursor: pointer; width: 100%;
    background: linear-gradient(135deg, #059669, #047857); color: white;
    box-shadow: 0 4px 14px rgba(5,150,105,0.3);
}
.btn-action-green:hover { color: white; filter: brightness(1.1); transform: translateY(-2px); }

.btn-action-outline {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.8rem 1.2rem; border-radius: 10px; text-decoration: none;
    font-weight: 600; font-size: 0.88rem; margin-bottom: 0.7rem;
    transition: all 0.3s; cursor: pointer; width: 100%;
    background: white; color: #0891b2; border: 2px solid #0891b2;
}
.btn-action-outline:hover { background: #0891b2; color: white; }

.btn-action-grey {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.8rem 1.2rem; border-radius: 10px; text-decoration: none;
    font-weight: 600; font-size: 0.88rem; margin-bottom: 0.7rem;
    transition: all 0.3s; border: none; cursor: pointer; width: 100%;
    background: #6c757d; color: white;
}
.btn-action-grey:hover { background: #5a6268; color: white; transform: translateY(-2px); }

/* ── Contact Buttons ── */
.contact-btn {
    display: flex; align-items: center; gap: 0.7rem;
    padding: 0.7rem 1rem; border-radius: 10px;
    text-decoration: none; font-weight: 600; font-size: 0.82rem;
    margin-bottom: 0.6rem; transition: all 0.3s;
}
.contact-btn:hover { transform: translateX(4px); }
.contact-btn-wa    { background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; }
.contact-btn-phone { background: #e0f2fe; color: #0c4a6e; border: 1.5px solid #bae6fd; }
.contact-btn-email { background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; }

/* ── Tabs ── */
.lab-tabs {
    background: white;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.lab-tabs .nav-tabs {
    border-bottom: 2px solid #e0f2fe;
    padding: 0 1rem;
    background: white;
}
.lab-tabs .nav-link {
    color: #888; font-weight: 600; font-size: 0.88rem;
    border: none; border-bottom: 3px solid transparent;
    padding: 1rem 1.2rem; transition: all 0.3s;
    display: flex; align-items: center; gap: 0.4rem;
}
.lab-tabs .nav-link:hover { color: #0891b2; }
.lab-tabs .nav-link.active { color: #0891b2; border-bottom-color: #0891b2; }
.lab-tabs .tab-content { padding: 1.5rem; }

/* ── Alert ── */
.lab-prof-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.8rem;
    font-size: 0.9rem; font-weight: 500;
}
.lab-prof-alert.success { background: #dcfce7; color: #166534; border-left: 5px solid #059669; }
.lab-prof-alert.error   { background: #fee2e2; color: #991b1b; border-left: 5px solid #dc2626; }
.lab-prof-alert.info    { background: #e0f2fe; color: #0c4a6e; border-left: 5px solid #0891b2; }

/* ═══════════════════════════════════════════
   REVIEWS & RATINGS STYLES
═══════════════════════════════════════════ */

/* Rating Summary Box */
.rating-summary-box {
    display: flex; gap: 2rem; align-items: center;
    padding: 1.4rem;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-radius: 12px;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.rating-big-number {
    text-align: center; min-width: 90px;
}
.rating-big-number .num {
    font-size: 3.8rem; font-weight: 800;
    color: #0891b2; line-height: 1;
}
.rating-big-number .stars {
    color: #fbbf24; font-size: 1.1rem; margin: 0.3rem 0;
}
.rating-big-number .cnt {
    font-size: 0.75rem; color: #888;
}
.rating-bars { flex: 1; min-width: 180px; }
.rating-bar-row {
    display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.4rem;
}
.rating-bar-row .star-label {
    font-size: 0.75rem; font-weight: 600; color: #555; min-width: 12px;
}
.rating-bar-row .bar-wrap {
    flex: 1; background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;
}
.rating-bar-row .bar-fill {
    height: 100%; border-radius: 4px; transition: width 1s ease;
}
.rating-bar-row .bar-count {
    font-size: 0.72rem; color: #888; min-width: 25px; text-align: right;
}

/* Individual Review Card */
.review-item {
    padding: 1.2rem 0;
    border-bottom: 1px solid #f0f9ff;
}
.review-item:last-child { border-bottom: none; }
.review-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0891b2, #0c4a6e);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 1rem;
    flex-shrink: 0;
}
.review-name   { font-weight: 700; font-size: 0.9rem; color: #0c4a6e; }
.review-date   { font-size: 0.75rem; color: #aaa; }
.review-stars  { color: #fbbf24; font-size: 0.82rem; margin: 0.3rem 0; }
.review-text   { font-size: 0.86rem; color: #555; line-height: 1.65; font-style: italic; margin: 0; }
.review-empty-star { color: #d1d5db; }

/* Write Review Form */
.write-review-card {
    background: white;
    border-radius: 14px;
    padding: 1.6rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
    border-top: 4px solid #0891b2;
}
.write-review-title {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 1rem;
    padding-bottom: 0.7rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.write-review-title i { color: #fbbf24; }

/* Interactive Stars */
.star-selector { display: flex; gap: 0.2rem; margin-bottom: 0.4rem; }
.star-btn {
    font-size: 2rem; cursor: pointer;
    color: #d1d5db; transition: color 0.15s, transform 0.15s;
}
.star-btn.hovered,
.star-btn.selected { color: #fbbf24; }
.star-btn:hover    { transform: scale(1.2); }

.review-input-label {
    font-size: 0.85rem; font-weight: 700; color: #0c4a6e;
    display: block; margin-bottom: 0.5rem;
}
.review-textarea {
    width: 100%; padding: 0.75rem 1rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.88rem; color: #333;
    resize: vertical; transition: border-color 0.3s;
    font-family: inherit;
}
.review-textarea:focus {
    border-color: #0891b2; outline: none;
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
}
.btn-submit-review {
    background: linear-gradient(135deg, #0891b2, #0c4a6e);
    color: white; border: none;
    padding: 0.75rem 2rem; border-radius: 10px;
    font-weight: 700; font-size: 0.9rem;
    cursor: pointer; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(8,145,178,0.3);
}
.btn-submit-review:hover:not(:disabled) {
    filter: brightness(1.1); transform: translateY(-2px);
}
.btn-submit-review:disabled { opacity: 0.5; cursor: not-allowed; }

/* No Reviews Empty */
.no-reviews-box {
    text-align: center; padding: 2.5rem 1rem;
}
.no-reviews-box i { font-size: 2.5rem; color: #bae6fd; display: block; margin-bottom: 0.8rem; }
.no-reviews-box p { font-size: 0.85rem; color: #aaa; margin: 0; }

/* Pagination */
.lab-pagination .page-link {
    border-radius: 8px !important;
    border: 2px solid #e9ecef;
    color: #0891b2; font-weight: 600;
    padding: 0.45rem 0.85rem; font-size: 0.82rem;
    transition: all 0.2s;
}
.lab-pagination .page-link:hover,
.lab-pagination .page-item.active .page-link {
    background: #0891b2; border-color: #0891b2; color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .lab-prof-name  { font-size: 1.4rem; }
    .lab-info-row   { flex-direction: column; gap: 0.3rem; }
    .lab-info-label { min-width: auto; }
    .lab-prof-header { padding: 5rem 0 2.5rem; }
    .rating-summary-box { flex-direction: column; text-align: center; }
    .rating-bars { width: 100%; }
}
</style>

{{-- ══════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════ --}}
<section class="lab-prof-header">
    <div class="container">

        <a href="{{ route('patient.laboratories') }}" class="btn-back-lab">
            <i class="fas fa-arrow-left"></i> Back to Laboratories
        </a>

        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">

                    {{-- Logo / Placeholder --}}
                    @if($laboratory->profile_image)
                        <img src="{{ asset('storage/'.$laboratory->profile_image) }}"
                             alt="{{ $laboratory->name }}"
                             class="lab-profile-img"
                             onerror="this.style.display='none'">
                    @else
                        <div class="lab-profile-img-placeholder">
                            <i class="fas fa-flask"></i>
                        </div>
                    @endif

                    <div>
                        <h1 class="lab-prof-name">{{ $laboratory->name }}</h1>

                        @if($laboratory->registration_number)
                        <div class="lab-prof-reg">
                            <i class="fas fa-id-badge me-1"></i>
                            Reg. No: {{ $laboratory->registration_number }}
                        </div>
                        @endif

                        <div class="d-flex flex-wrap gap-1 mt-1">
                            <span class="verified-pill-teal">
                                <i class="fas fa-check-circle"></i> Certified Lab
                            </span>
                            @if(!empty($laboratory->home_collection))
                            <span class="home-pill-teal">
                                <i class="fas fa-home"></i> Home Collection
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Pills --}}
                <div class="quick-info-row">
                    @if($laboratory->city)
                    <span class="quick-pill">
                        <i class="fas fa-map-marker-alt"></i> {{ $laboratory->city }}
                    </span>
                    @endif
                    @if($laboratory->phone)
                    <span class="quick-pill">
                        <i class="fas fa-phone"></i> {{ $laboratory->phone }}
                    </span>
                    @endif
                    @if($labTests->count() > 0)
                    <span class="quick-pill">
                        <i class="fas fa-vial"></i> {{ $labTests->count() }} Tests
                    </span>
                    @endif
                    @if($labPackages->count() > 0)
                    <span class="quick-pill">
                        <i class="fas fa-box"></i> {{ $labPackages->count() }} Packages
                    </span>
                    @endif
                    @if($laboratory->total_ratings > 0)
                    <span class="quick-pill">
                        <i class="fas fa-star"></i> {{ number_format($laboratory->rating, 1) }} ({{ $laboratory->total_ratings }} reviews)
                    </span>
                    @endif
                </div>

                {{-- Rating Hero --}}
                @php $rating = floatval($laboratory->rating ?? 0); @endphp
                <div class="rating-hero">
                    <div class="stars">
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= floor($rating))
                                <i class="fas fa-star"></i>
                            @elseif($s == ceil($rating) && fmod($rating,1) >= 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="num">{{ number_format($rating, 1) }}</span>
                    <span class="cnt">{{ $laboratory->total_ratings ?? 0 }} reviews</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════ --}}
<section class="lab-prof-main">
    <div class="container">

        {{-- Session Alerts --}}
        @if(session('success'))
        <div class="lab-prof-alert success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="lab-prof-alert error">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="lab-prof-alert info">
            <i class="fas fa-info-circle fa-lg"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        <div class="row g-4">

            {{-- ═══════════════════════════
                 LEFT COLUMN
            ═══════════════════════════ --}}
            <div class="col-lg-8">

                {{-- ── Lab Information ── --}}
                <div class="lab-section-card">
                    <div class="lab-section-title">
                        <i class="fas fa-info-circle"></i> Laboratory Information
                    </div>

                    @if($laboratory->description)
                    <p style="font-size:0.9rem;color:#555;line-height:1.7;margin-bottom:1.2rem;">
                        {{ $laboratory->description }}
                    </p>
                    @endif

                    @if($laboratory->address)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                        <div class="lab-info-value">{{ $laboratory->address }}</div>
                    </div>
                    @endif

                    @if($laboratory->city)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-city"></i> City</div>
                        <div class="lab-info-value">
                            {{ $laboratory->city }}
                            @if($laboratory->province), {{ $laboratory->province }}@endif
                            @if($laboratory->postal_code) – {{ $laboratory->postal_code }}@endif
                        </div>
                    </div>
                    @endif

                    @if($laboratory->phone)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-phone"></i> Phone</div>
                        <div class="lab-info-value">
                            <a href="tel:{{ $laboratory->phone }}"
                               style="color:#0891b2;font-weight:600;text-decoration:none;">
                                {{ $laboratory->phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($laboratory->email)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-envelope"></i> Email</div>
                        <div class="lab-info-value">
                            <a href="mailto:{{ $laboratory->email }}"
                               style="color:#0891b2;text-decoration:none;">
                                {{ $laboratory->email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($laboratory->operating_hours)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-clock"></i> Hours</div>
                        <div class="lab-info-value" style="white-space:pre-line;">
                            {{ $laboratory->operating_hours }}
                        </div>
                    </div>
                    @endif

                    @if($laboratory->registration_number)
                    <div class="lab-info-row">
                        <div class="lab-info-label"><i class="fas fa-id-badge"></i> Reg. Number</div>
                        <div class="lab-info-value">{{ $laboratory->registration_number }}</div>
                    </div>
                    @endif
                </div>

                {{-- ── Services Offered ── --}}
                @if(!empty($services))
                <div class="lab-section-card">
                    <div class="lab-section-title">
                        <i class="fas fa-list-check"></i> Services Offered
                    </div>
                    <div class="services-grid">
                        @foreach($services as $svc)
                        <div class="service-badge-teal">
                            <i class="fas fa-check-circle"></i>
                            {{ is_array($svc) ? ($svc['name'] ?? ($svc[0] ?? '')) : $svc }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── Tests & Packages (Tabs) ── --}}
                @if($labTests->count() > 0 || $labPackages->count() > 0)
                <div class="lab-tabs" id="book-test">
                    <ul class="nav nav-tabs" role="tablist">
                        @if($labTests->count() > 0)
                        <li class="nav-item">
                            <button class="nav-link active"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-tests">
                                <i class="fas fa-vial"></i>
                                Individual Tests
                                <span style="background:#e0f2fe;color:#0369a1;padding:0.1rem 0.5rem;
                                             border-radius:8px;font-size:0.75rem;margin-left:0.3rem;">
                                    {{ $labTests->count() }}
                                </span>
                            </button>
                        </li>
                        @endif

                        @if($labPackages->count() > 0)
                        <li class="nav-item">
                            <button class="nav-link {{ $labTests->count() == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-packages">
                                <i class="fas fa-box"></i>
                                Packages
                                <span style="background:#dcfce7;color:#166534;padding:0.1rem 0.5rem;
                                             border-radius:8px;font-size:0.75rem;margin-left:0.3rem;">
                                    {{ $labPackages->count() }}
                                </span>
                            </button>
                        </li>
                        @endif
                    </ul>

                    <div class="tab-content">

                        {{-- Individual Tests --}}
                        @if($labTests->count() > 0)
                        <div class="tab-pane fade show active" id="tab-tests">
                            @php $categories = $labTests->groupBy('test_category'); @endphp

                            @foreach($categories as $cat => $catTests)
                            @if($cat)
                            <div style="font-size:0.82rem;font-weight:700;color:#0891b2;
                                        margin-bottom:0.5rem;margin-top:{{ !$loop->first ? '1.4rem' : '0' }};">
                                <i class="fas fa-tag me-1"></i> {{ $cat }}
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table tests-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Duration</th>
                                            <th>Requirements</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($catTests as $test)
                                        <tr>
                                            <td>
                                                <div style="font-weight:600;color:#0c4a6e;">
                                                    {{ $test->test_name }}
                                                </div>
                                                @if($test->description)
                                                <div style="font-size:0.75rem;color:#aaa;">
                                                    {{ Str::limit($test->description, 60) }}
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($test->duration_hours)
                                                <span style="font-size:0.8rem;color:#666;">
                                                    <i class="fas fa-clock" style="color:#0891b2;"></i>
                                                    {{ $test->duration_hours }}h
                                                </span>
                                                @else
                                                <span style="color:#ccc;font-size:0.75rem;">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($test->requirements)
                                                <span style="font-size:0.78rem;color:#666;">
                                                    {{ Str::limit($test->requirements, 40) }}
                                                </span>
                                                @else
                                                <span style="color:#ccc;font-size:0.75rem;">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span style="font-weight:700;color:#0891b2;font-size:0.95rem;">
                                                    Rs.&nbsp;{{ number_format($test->price, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Packages --}}
                        @if($labPackages->count() > 0)
                        <div class="tab-pane fade {{ $labTests->count() == 0 ? 'show active' : '' }}"
                             id="tab-packages">
                            <div class="row g-3">
                                @foreach($labPackages as $pkg)
                                @php
                                    $discountedPrice = $pkg->discount_percentage
                                        ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                                        : null;
                                    $finalPrice = $discountedPrice ?? $pkg->price;
                                @endphp
                                <div class="col-md-6">
                                    <div class="pkg-card">
                                        <div class="pkg-name">{{ $pkg->package_name }}</div>

                                        @if($pkg->description)
                                        <div style="font-size:0.78rem;color:#888;margin-bottom:0.6rem;">
                                            {{ Str::limit($pkg->description, 80) }}
                                        </div>
                                        @endif

                                        <div style="display:flex;align-items:center;flex-wrap:wrap;
                                                    gap:0.3rem;margin-bottom:0.8rem;">
                                            <span class="pkg-price">
                                                Rs.&nbsp;{{ number_format($finalPrice, 2) }}
                                            </span>
                                            @if($discountedPrice)
                                            <span class="pkg-original">
                                                Rs.&nbsp;{{ number_format($pkg->price, 2) }}
                                            </span>
                                            <span class="pkg-discount">
                                                {{ $pkg->discount_percentage }}% OFF
                                            </span>
                                            @endif
                                        </div>

                                        @if($pkg->tests && $pkg->tests->count() > 0)
                                        <div class="pkg-tests-list">
                                            @foreach($pkg->tests->take(4) as $pt)
                                            <div class="pkg-test-item">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $pt->test_name }}
                                            </div>
                                            @endforeach
                                            @if($pkg->tests->count() > 4)
                                            <div class="pkg-test-item" style="color:#0891b2;">
                                                <i class="fas fa-plus"></i>
                                                {{ $pkg->tests->count() - 4 }} more tests included
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- ── Previous Orders ──
                @if($previousOrders && $previousOrders->count() > 0)
                <div class="lab-section-card">
                    <div class="lab-section-title">
                        <i class="fas fa-history"></i> Your Previous Orders
                    </div>

                    @foreach($previousOrders as $prev)
                    <div style="display:flex;justify-content:space-between;align-items:center;
                                padding:0.8rem 0;border-bottom:1px solid #f0f9ff;">
                        <div>
                            <div style="font-weight:700;font-size:0.88rem;color:#0c4a6e;">
                                #{{ $prev->order_number }}
                            </div>
                            <div style="font-size:0.78rem;color:#888;">
                                <i class="fas fa-calendar me-1" style="color:#0891b2;"></i>
                                {{ \Carbon\Carbon::parse($prev->order_date)->format('d M Y') }}
                                &middot; {{ $prev->items->count() }} item(s)
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
                            <span class="order-pill {{ $prev->status }}">
                                {{ ucwords(str_replace('_', ' ', $prev->status)) }}
                            </span>
                            <span style="font-weight:700;color:#0891b2;font-size:0.9rem;">
                                Rs.&nbsp;{{ number_format($prev->total_amount, 2) }}
                            </span>
                            <a href="{{ route('patient.lab-orders.show', $prev->id) }}"
                               style="background:#e0f2fe;color:#0369a1;padding:0.3rem 0.7rem;
                                      border-radius:8px;font-size:0.75rem;font-weight:600;
                                      text-decoration:none;">
                                View
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <div style="text-align:right;margin-top:0.8rem;">
                        <a href="{{ route('patient.lab-orders.index') }}"
                           style="font-size:0.82rem;color:#0891b2;text-decoration:none;font-weight:600;">
                            <i class="fas fa-list-alt me-1"></i> All My Lab Orders →
                        </a>
                    </div>
                </div>
                @endif --}}

                {{-- ══════════════════════════════════════════════
                     WRITE A REVIEW SECTION
                ══════════════════════════════════════════════ --}}
                @if($canReview && $reviewableOrder)
                <div class="write-review-card" id="write-review">
                    <div class="write-review-title">
                        <i class="fas fa-star"></i> Write a Review
                    </div>

                    {{-- Order Info Banner --}}
                    <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:10px;
                                padding:0.85rem 1.1rem;margin-bottom:1.3rem;font-size:0.84rem;
                                color:#0369a1;display:flex;align-items:center;gap:0.6rem;">
                        <i class="fas fa-info-circle" style="flex-shrink:0;"></i>
                        <span>
                            Reviewing your order <strong>#{{ $reviewableOrder->order_number }}</strong>
                            completed at <strong>{{ $laboratory->name }}</strong>
                        </span>
                    </div>

                    <form action="{{ route('patient.lab-orders.review.store', $reviewableOrder->id) }}"
                          method="POST">
                        @csrf

                        {{-- Star Rating Selector --}}
                        <div style="margin-bottom:1.3rem;">
                            <label class="review-input-label">
                                Your Rating <span style="color:#dc2626;">*</span>
                            </label>
                            <div class="star-selector" id="starSelector">
                                @for($s = 1; $s <= 5; $s++)
                                <i class="far fa-star star-btn" data-value="{{ $s }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="">
                            <div id="ratingLabel"
                                 style="font-size:0.8rem;color:#888;margin-top:0.3rem;height:1.1rem;
                                        font-weight:600;transition:color 0.2s;"></div>
                            @error('rating')
                            <div style="color:#dc2626;font-size:0.8rem;margin-top:0.2rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Review Text --}}
                        <div style="margin-bottom:1.3rem;">
                            <label class="review-input-label">
                                Your Review
                                <span style="color:#888;font-weight:400;">(optional)</span>
                            </label>
                            <textarea name="review"
                                      id="reviewTextarea"
                                      class="review-textarea"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Share your experience — sample collection, staff, report delivery time, accuracy...">{{ old('review') }}</textarea>
                            <div style="text-align:right;font-size:0.75rem;color:#aaa;margin-top:0.3rem;">
                                <span id="charCount">0</span>/1000
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <button type="submit"
                                    id="reviewSubmitBtn"
                                    class="btn-submit-review"
                                    disabled>
                                <i class="fas fa-paper-plane"></i> Submit Review
                            </button>
                            <span style="font-size:0.78rem;color:#aaa;">
                                <i class="fas fa-lock me-1"></i> Your review is public
                            </span>
                        </div>
                    </form>
                </div>
                @endif

                {{-- ══════════════════════════════════════════════
                     REVIEWS LIST SECTION
                ══════════════════════════════════════════════ --}}
                @if($laboratory->total_ratings > 0)
                <div class="lab-section-card">
                    <div class="lab-section-title">
                        <i class="fas fa-comments"></i>
                        Patient Reviews
                        <span style="background:#e0f2fe;color:#0369a1;padding:0.1rem 0.55rem;
                                     border-radius:8px;font-size:0.78rem;margin-left:0.3rem;">
                            {{ $laboratory->total_ratings }}
                        </span>
                    </div>

                    {{-- Rating Summary --}}
                    <div class="rating-summary-box">
                        {{-- Big Number --}}
                        <div class="rating-big-number">
                            <div class="num">{{ number_format($laboratory->rating, 1) }}</div>
                            <div class="stars">
                                @php $r = floatval($laboratory->rating); @endphp
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= floor($r))<i class="fas fa-star"></i>
                                    @elseif($s == ceil($r) && fmod($r,1) >= 0.5)<i class="fas fa-star-half-alt"></i>
                                    @else<i class="far fa-star" style="color:#d1d5db;"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="cnt">out of 5 &middot; {{ $laboratory->total_ratings }} reviews</div>
                        </div>

                        {{-- Progress Bars --}}
                        <div class="rating-bars">
                            @foreach([5,4,3,2,1] as $star)
                            @php
                                $cnt = $ratingBreakdown[$star] ?? 0;
                                $pct = $laboratory->total_ratings > 0
                                    ? round(($cnt / $laboratory->total_ratings) * 100)
                                    : 0;
                                $color = match($star) {
                                    5 => '#059669', 4 => '#10b981',
                                    3 => '#f59e0b', 2 => '#f97316',
                                    default => '#dc2626'
                                };
                            @endphp
                            <div class="rating-bar-row">
                                <span class="star-label">{{ $star }}</span>
                                <i class="fas fa-star" style="color:#fbbf24;font-size:0.68rem;"></i>
                                <div class="bar-wrap">
                                    <div class="bar-fill"
                                         style="width:{{ $pct }}%;background:{{ $color }};"></div>
                                </div>
                                <span class="bar-count">{{ $cnt }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Individual Reviews --}}
                    @forelse($ratings as $rev)
                    @php
                        $initial = strtoupper(substr($rev->first_name ?? 'U', 0, 1));
                        $name    = ($rev->first_name ?? 'Anonymous') . ' '
                                 . strtoupper(substr($rev->last_name ?? '', 0, 1)) . '.';
                        $ago     = \Carbon\Carbon::parse($rev->created_at)->diffForHumans();
                    @endphp
                    <div class="review-item">
                        <div style="display:flex;align-items:flex-start;gap:0.9rem;">
                            {{-- Avatar --}}
                            <div class="review-avatar">{{ $initial }}</div>

                            <div style="flex:1;min-width:0;">
                                {{-- Name & Date --}}
                                <div style="display:flex;justify-content:space-between;
                                            align-items:center;flex-wrap:wrap;gap:0.3rem;">
                                    <span class="review-name">{{ $name }}</span>
                                    <span class="review-date">
                                        <i class="fas fa-clock me-1"></i>{{ $ago }}
                                    </span>
                                </div>

                                {{-- Stars --}}
                                <div class="review-stars">
                                    @for($s = 1; $s <= 5; $s++)
                                        @if($s <= $rev->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star review-empty-star"></i>
                                        @endif
                                    @endfor
                                    <span style="font-size:0.75rem;color:#888;margin-left:0.3rem;">
                                        {{ $rev->rating }}/5
                                    </span>
                                </div>

                                {{-- Review Text --}}
                                @if($rev->review)
                                <p class="review-text">"{{ $rev->review }}"</p>
                                @else
                                <p style="font-size:0.82rem;color:#bbb;font-style:italic;margin:0;">
                                    No written review provided.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="no-reviews-box">
                        <i class="fas fa-star"></i>
                        <p>No reviews yet. Be the first to review!</p>
                    </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($ratings->hasPages())
                    <div class="d-flex justify-content-center mt-3 lab-pagination">
                        {{ $ratings->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
                @else
                {{-- No Reviews State --}}
                <div class="lab-section-card">
                    <div class="lab-section-title">
                        <i class="fas fa-comments"></i> Patient Reviews
                    </div>
                    <div class="no-reviews-box">
                        <i class="fas fa-star"></i>
                        <div style="font-weight:600;color:#aaa;font-size:0.95rem;margin-bottom:0.4rem;">
                            No Reviews Yet
                        </div>
                        <p>Complete a lab order to leave the first review!</p>
                    </div>
                </div>
                @endif

            </div>
            {{-- END LEFT COLUMN --}}

            {{-- ═══════════════════════════
                 RIGHT COLUMN
            ═══════════════════════════ --}}
            <div class="col-lg-4">

                {{-- ── Book / Action Card ── --}}
                @auth
                    @if(auth()->user()->user_type === 'patient')

                    <div class="action-card" id="book-test-action">
                        <h5><i class="fas fa-calendar-plus"></i> Book Lab Test</h5>

                        @if($labTests->count() > 0 || $labPackages->count() > 0)
                        <p style="font-size:0.82rem;color:#888;margin-bottom:1rem;line-height:1.6;">
                            Choose from <strong>{{ $labTests->count() }}</strong> tests and
                            <strong>{{ $labPackages->count() }}</strong> packages.
                            Upload prescription, select collection type, and submit your order.
                        </p>
                        <a href="{{ route('patient.lab-orders.create', $laboratory->id) }}"
                           class="btn-action-primary">
                            <i class="fas fa-flask"></i> Book Now
                        </a>
                        @else
                        <p style="font-size:0.82rem;color:#888;margin-bottom:1rem;">
                            No tests listed yet. Contact the lab directly to inquire.
                        </p>
                        @endif

                        <a href="{{ route('patient.lab-orders.index') }}" class="btn-action-outline">
                            <i class="fas fa-list-alt"></i> My Lab Orders
                        </a>
                    </div>

                    @elseif(auth()->user()->user_type !== 'patient')
                    <div class="action-card" style="text-align:center;">
                        <i class="fas fa-info-circle"
                           style="font-size:2rem;color:#0891b2;margin-bottom:0.8rem;display:block;"></i>
                        <p style="font-size:0.85rem;color:#666;margin-bottom:1rem;">
                            Only patient accounts can book lab tests.
                        </p>
                        <a href="{{ route('patient.laboratories') }}" class="btn-action-grey">
                            <i class="fas fa-arrow-left"></i> Back to Laboratories
                        </a>
                    </div>
                    @endif

                @else
                {{-- Guest --}}
                <div class="action-card">
                    <h5><i class="fas fa-flask"></i> Book a Lab Test</h5>
                    <p style="font-size:0.82rem;color:#666;margin-bottom:1.2rem;line-height:1.6;">
                        Login as a patient to book tests, upload prescriptions, and receive reports securely.
                    </p>
                    <a href="{{ route('login') }}" class="btn-action-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Book
                    </a>
                    <a href="{{ route('patient.laboratories') }}" class="btn-action-grey">
                        <i class="fas fa-arrow-left"></i> Back to Laboratories
                    </a>
                </div>
                @endauth

                {{-- ── Lab Overview Stats ── --}}
                <div class="action-card">
                    <h5><i class="fas fa-chart-bar"></i> Lab Overview</h5>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;">
                        <div style="background:#e0f2fe;border-radius:10px;padding:0.9rem;text-align:center;">
                            <div style="font-size:1.5rem;font-weight:700;color:#0891b2;">
                                {{ $labTests->count() }}
                            </div>
                            <div style="font-size:0.72rem;color:#555;font-weight:600;">Tests</div>
                        </div>
                        <div style="background:#dcfce7;border-radius:10px;padding:0.9rem;text-align:center;">
                            <div style="font-size:1.5rem;font-weight:700;color:#059669;">
                                {{ $labPackages->count() }}
                            </div>
                            <div style="font-size:0.72rem;color:#555;font-weight:600;">Packages</div>
                        </div>
                        <div style="background:#fef3c7;border-radius:10px;padding:0.9rem;text-align:center;">
                            <div style="font-size:1.5rem;font-weight:700;color:#d97706;">
                                {{ number_format($laboratory->rating, 1) }}
                            </div>
                            <div style="font-size:0.72rem;color:#555;font-weight:600;">Rating</div>
                        </div>
                        <div style="background:#ede9fe;border-radius:10px;padding:0.9rem;text-align:center;">
                            <div style="font-size:1.5rem;font-weight:700;color:#7c3aed;">
                                {{ $laboratory->total_ratings ?? 0 }}
                            </div>
                            <div style="font-size:0.72rem;color:#555;font-weight:600;">Reviews</div>
                        </div>
                    </div>
                </div>

                {{-- ── User's Own Rating ── --}}
                @if($userRating)
                <div class="action-card">
                    <h5><i class="fas fa-star"></i> Your Review</h5>
                    <div style="display:flex;gap:0.3rem;margin-bottom:0.5rem;">
                        @for($s = 1; $s <= 5; $s++)
                            <i class="{{ $s <= $userRating->rating ? 'fas' : 'far' }} fa-star"
                               style="color:{{ $s <= $userRating->rating ? '#fbbf24' : '#d1d5db' }};font-size:1.1rem;"></i>
                        @endfor
                        <span style="font-weight:700;color:#0891b2;margin-left:0.3rem;">
                            {{ $userRating->rating }}/5
                        </span>
                    </div>
                    @if($userRating->review)
                    <p style="font-size:0.82rem;color:#666;font-style:italic;
                               line-height:1.6;margin-bottom:0.5rem;">
                        "{{ Str::limit($userRating->review, 100) }}"
                    </p>
                    @endif
                    <div style="font-size:0.75rem;color:#aaa;">
                        <i class="fas fa-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($userRating->created_at)->diffForHumans() }}
                    </div>
                </div>
                @endif

                {{-- ── Contact ── --}}
                @if($laboratory->phone || $laboratory->email)
                <div class="action-card">
                    <h5><i class="fas fa-headset"></i> Contact Lab</h5>
                    <p style="font-size:0.78rem;color:#aaa;margin-bottom:0.8rem;">
                        Inquire about tests, home collection, or reports.
                    </p>

                    @php
                        $labPhone = $laboratory->phone ?? null;
                        $labEmail = $laboratory->email ?? null;
                        $waPhone  = '';
                        if ($labPhone) {
                            $raw = preg_replace('/[^0-9]/', '', $labPhone);
                            $waPhone = str_starts_with($raw, '0')
                                ? '94' . substr($raw, 1)
                                : $raw;
                        }
                        $waMsg     = urlencode('Hello ' . ($laboratory->name ?? 'Lab') . ', I am a HealthNet patient and would like to inquire about lab tests and booking.');
                        $emailSub  = urlencode('Lab Test Inquiry – HealthNet Patient');
                        $emailBody = urlencode("Hello " . ($laboratory->name ?? 'Lab') . ",\n\nI am a HealthNet patient and would like to inquire about your lab tests.\n\nThank you.");
                    @endphp

                    @if($labPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}"
                       target="_blank" class="contact-btn contact-btn-wa">
                        <i class="fab fa-whatsapp" style="font-size:1.2rem;color:#25D366;"></i>
                        <div>
                            <div>WhatsApp</div>
                            <div style="font-size:0.7rem;opacity:0.7;">Chat with the lab</div>
                        </div>
                    </a>

                    <a href="tel:{{ $labPhone }}" class="contact-btn contact-btn-phone">
                        <i class="fas fa-phone" style="color:#0891b2;"></i>
                        <div>
                            <div>Call Lab</div>
                            <div style="font-size:0.7rem;opacity:0.7;">{{ $labPhone }}</div>
                        </div>
                    </a>
                    @endif

                    @if($labEmail)
                    <a href="mailto:{{ $labEmail }}?subject={{ $emailSub }}&body={{ $emailBody }}"
                       class="contact-btn contact-btn-email">
                        <i class="fas fa-envelope" style="color:#d97706;"></i>
                        <div>
                            <div>Email Lab</div>
                            <div style="font-size:0.7rem;opacity:0.7;">
                                {{ Str::limit($labEmail, 28) }}
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                {{-- ── Back Button ── --}}
                <a href="{{ route('patient.laboratories') }}" class="btn-action-grey">
                    <i class="fas fa-arrow-left"></i> Back to Laboratories
                </a>

            </div>
            {{-- END RIGHT COLUMN --}}

        </div>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Star Rating Interactive ───────────────────────────── */
    const stars      = document.querySelectorAll('.star-btn');
    const ratingInp  = document.getElementById('ratingInput');
    const ratingLbl  = document.getElementById('ratingLabel');
    const submitBtn  = document.getElementById('reviewSubmitBtn');
    const labels     = ['', 'Poor 😞', 'Fair 😐', 'Good 😊', 'Very Good 😄', 'Excellent 🌟'];
    const labelColors = ['', '#dc2626', '#f97316', '#f59e0b', '#10b981', '#059669'];
    let   selected   = 0;

    function paintStars(upTo, permanent = false) {
        stars.forEach((s, i) => {
            const filled = i < upTo;
            s.classList.toggle('fas', filled);
            s.classList.toggle('far', !filled);
            s.style.color     = filled ? '#fbbf24' : '#d1d5db';
            s.style.transform = 'scale(1)';
        });
    }

    stars.forEach(star => {
        star.addEventListener('mouseenter', function () {
            const val = parseInt(this.dataset.value);
            paintStars(val);
            stars[val - 1].style.transform = 'scale(1.25)';
        });
        star.addEventListener('mouseleave', () => paintStars(selected));
        star.addEventListener('click', function () {
            selected = parseInt(this.dataset.value);
            ratingInp.value       = selected;
            ratingLbl.textContent = labels[selected];
            ratingLbl.style.color = labelColors[selected];
            paintStars(selected);
            if (submitBtn) {
                submitBtn.disabled      = false;
                submitBtn.style.opacity = '1';
            }
        });
    });

    /* ── Character Counter ─────────────────────────────────── */
    const textarea  = document.getElementById('reviewTextarea');
    const charCount = document.getElementById('charCount');
    if (textarea && charCount) {
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length;
        });
    }

    /* ── Auto-hide Alerts ──────────────────────────────────── */
    setTimeout(() => {
        document.querySelectorAll('.lab-prof-alert').forEach(el => {
            el.style.transition = 'opacity 0.6s ease';
            el.style.opacity    = '0';
            setTimeout(() => el.remove(), 600);
        });
    }, 5000);

    /* ── Animate Rating Bars on Load ──────────────────────── */
    const bars = document.querySelectorAll('.bar-fill');
    setTimeout(() => {
        bars.forEach(bar => {
            const target = bar.style.width;
            bar.style.width = '0';
            requestAnimationFrame(() => {
                setTimeout(() => { bar.style.width = target; }, 100);
            });
        });
    }, 300);

});
</script>
