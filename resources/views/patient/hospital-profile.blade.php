{{-- Include Header --}}
@include('partials.header')

<style>
    /* ===== HERO HEADER (laboratory-profile style) ===== */
    .hosp-hero {
        background: linear-gradient(135deg, #0056a3 0%, #003d7a 50%, #1a5276 100%);
        padding: 6rem 0 3rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .hosp-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
        opacity: 0.07;
        z-index: 0;
    }
    .hosp-hero .container { position: relative; z-index: 1; }

    .btn-back-hosp {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(255,255,255,0.88);
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 1.2rem;
        transition: all 0.3s;
    }
    .btn-back-hosp:hover { color: white; transform: translateX(-4px); }

    .hosp-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        margin-bottom: 0.8rem;
    }
    .hosp-hero-name {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0 0.2rem;
    }
    .pill-green  { background: #28a745; color: white; }
    .pill-blue   { background: rgba(255,255,255,0.18); backdrop-filter: blur(4px); color: white; }

    .hosp-hero-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.2rem;
        flex-wrap: wrap;
        font-size: 0.83rem;
        opacity: 0.9;
        margin-top: 0.5rem;
    }
    .hosp-hero-meta i { margin-right: 0.3rem; opacity: 0.8; }

    /* Rating hero */
    .rating-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(6px);
        padding: 0.45rem 1.1rem;
        border-radius: 25px;
        margin-top: 0.8rem;
    }
    .rating-hero .stars { color: #ffc107; font-size: 0.88rem; display: flex; gap: 0.1rem; }
    .rating-hero .num  { font-size: 1.1rem; font-weight: 700; }
    .rating-hero .cnt  { font-size: 0.78rem; opacity: 0.85; }

    /* Hero stats row */
    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .hero-stat {
        text-align: center;
        background: rgba(255,255,255,0.1);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        min-width: 70px;
    }
    .hero-stat strong { display: block; font-size: 1.3rem; font-weight: 700; }
    .hero-stat span   { font-size: 0.7rem; opacity: 0.8; }

    /* ===== BODY ===== */
    .hosp-body { background: #faf4fb; padding: 2rem 0 3.5rem; }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.3rem 1.4rem;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        margin-bottom: 1.2rem;
        transition: all 0.3s;
    }
    .info-card:hover { box-shadow: 0 5px 18px rgba(0,86,163,0.1); transform: translateY(-2px); }
    .info-card h5 {
        color: #0056a3;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.9rem;
        padding-bottom: 0.6rem;
        border-bottom: 2px solid #eef3fb;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .info-row {
        display: flex;
        padding: 0.55rem 0;
        border-bottom: 1px solid #f7f7f7;
        font-size: 0.85rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label {
        font-weight: 600;
        color: #777;
        min-width: 130px;
        display: flex;
        align-items: center;
    }
    .info-label i { width: 18px; margin-right: 0.4rem; color: #0056a3; font-size: 0.78rem; }
    .info-value { flex: 1; color: #333; font-weight: 500; }

    /* Tags (Specializations / Facilities) */
    .tags-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .tag-spec {
        padding: 0.3rem 0.8rem;
        background: rgba(0,86,163,0.07);
        border-left: 3px solid #0056a3;
        border-radius: 0 6px 6px 0;
        font-size: 0.78rem;
        font-weight: 600;
        color: #003d7a;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .tag-fac {
        padding: 0.3rem 0.8rem;
        background: #f1f8f1;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #155724;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid #c3e6cb;
    }
    .tag-spec i, .tag-fac i { font-size: 0.7rem; }

    /* Doctors mini */
    .doctors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 0.8rem;
    }
    .doc-mini {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.8rem;
        display: flex;
        gap: 0.8rem;
        align-items: center;
        transition: all 0.25s;
        border: 1px solid #eee;
    }
    .doc-mini:hover {
        background: white;
        box-shadow: 0 4px 14px rgba(0,86,163,0.1);
        transform: translateY(-2px);
    }
    .doc-mini-av {
        width: 48px; height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #0056a3;
        flex-shrink: 0;
    }
    .doc-mini-av img { width: 100%; height: 100%; object-fit: cover; }
    .doc-mini-info h6 { font-size: 0.8rem; font-weight: 700; color: #0056a3; margin: 0 0 0.1rem; }
    .doc-mini-info p  { font-size: 0.7rem; color: #888; margin: 0 0 0.25rem; }
    .doc-mini-info a  {
        font-size: 0.7rem;
        color: #0056a3;
        font-weight: 600;
        text-decoration: none;
    }
    .doc-mini-info a:hover { text-decoration: underline; }

    /* ===== REVIEW SECTION ===== */
    .review-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.8rem;
        border-left: 3px solid #0056a3;
        transition: all 0.25s;
    }
    .review-card:hover { background: white; box-shadow: 0 3px 10px rgba(0,86,163,0.08); }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .reviewer-info { display: flex; align-items: center; gap: 0.7rem; }
    .reviewer-av {
        width: 34px; height: 34px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #0056a3;
        background: #e8f0fb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #0056a3;
        flex-shrink: 0;
    }
    .reviewer-av img { width: 100%; height: 100%; object-fit: cover; }
    .reviewer-name { font-weight: 600; color: #0056a3; font-size: 0.85rem; }
    .review-date   { font-size: 0.72rem; color: #999; }
    .review-stars  { display: flex; gap: 0.1rem; color: #ffc107; font-size: 0.78rem; }
    .review-text   { font-size: 0.82rem; color: #555; line-height: 1.6; margin: 0; }

    /* Review Form */
    .review-form-card {
        background: linear-gradient(135deg, rgba(0,86,163,0.04), rgba(0,86,163,0.08));
        border: 1px solid rgba(0,86,163,0.15);
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.2rem;
    }
    .star-input i {
        font-size: 1.6rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.15s;
    }
    .star-input i:hover,
    .star-input i.active { color: #ffc107; }

    /* Sidebar quick info */
    .quick-info-card {
        background: white;
        border-radius: 12px;
        padding: 1.2rem 1.3rem;
        box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        margin-bottom: 1.2rem;
    }
    .quick-info-card h5 {
        color: #0056a3;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.85rem;
        padding-bottom: 0.55rem;
        border-bottom: 2px solid #eef3fb;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .qi-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        padding: 0.4rem 0;
        border-bottom: 1px solid #f7f7f7;
    }
    .qi-row:last-child { border-bottom: none; }
    .qi-label { color: #888; }
    .qi-value { font-weight: 700; color: #333; }

    /* Empty */
    .empty-box {
        text-align: center;
        padding: 2rem;
        color: #aaa;
    }
    .empty-box i { font-size: 2.2rem; color: #ddd; display: block; margin-bottom: 0.5rem; }

    /* Responsive */
    @media (max-width: 768px) {
        .hosp-hero { padding: 5rem 0 2.5rem; }
        .hosp-hero-name { font-size: 1.3rem; }
        .hosp-hero-meta { gap: 0.7rem; }
        .hero-stats { gap: 0.7rem; }
        .doctors-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ===== HERO SECTION ===== --}}
<section class="hosp-hero">
    <div class="container">
        <a href="{{ route('patient.hospitals') }}" class="btn-back-hosp">
            <i class="fas fa-arrow-left"></i> Back to Hospitals
        </a>

        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                {{-- Logo --}}
                <img src="{{ $hospital->profile_image ? asset('storage/'.$hospital->profile_image) : asset('images/default-hospital.png') }}"
                    alt="{{ $hospital->name }}"
                    class="hosp-avatar"
                    onerror="this.src='{{ asset('images/default-hospital.png') }}'">

                {{-- Name + Pills --}}
                <h1 class="hosp-hero-name">
                    {{ $hospital->name }}
                    @if($hospital->status === 'approved')
                        <span class="pill pill-green"><i class="fas fa-check-circle"></i> Verified</span>
                    @endif
                    @if($hospital->type)
                        <span class="pill pill-blue">{{ ucfirst($hospital->type) }}</span>
                    @endif
                </h1>

                {{-- Meta --}}
                <div class="hosp-hero-meta">
                    @if($hospital->city)
                    <span><i class="fas fa-map-marker-alt"></i>{{ $hospital->city }}{{ $hospital->province ? ', '.$hospital->province : '' }}</span>
                    @endif
                    @if($hospital->phone)
                    <span><i class="fas fa-phone"></i>{{ $hospital->phone }}</span>
                    @endif
                    @if($hospital->email)
                    <span><i class="fas fa-envelope"></i>{{ $hospital->email }}</span>
                    @endif
                </div>

                {{-- Rating --}}
                @php
                    $rating    = $hospital->rating ?? 0;
                    $full      = floor($rating);
                    $half      = ($rating - $full) >= 0.5;
                    $empty     = 5 - $full - ($half ? 1 : 0);
                @endphp
                <div class="d-flex justify-content-center">
                    <div class="rating-hero">
                        <div class="stars">
                            @for($i=0;$i<$full;$i++)<i class="fas fa-star"></i>@endfor
                            @if($half)<i class="fas fa-star-half-alt"></i>@endif
                            @for($i=0;$i<$empty;$i++)<i class="far fa-star"></i>@endfor
                        </div>
                        <span class="num">{{ number_format($rating,1) }}</span>
                        <span class="cnt">{{ $hospital->total_ratings ?? 0 }} reviews</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="hero-stats">
                    @if($doctors->count() > 0)
                    <div class="hero-stat">
                        <strong>{{ $doctors->count() }}</strong>
                        <span>Doctors</span>
                    </div>
                    @endif
                    @if(count($specializations) > 0)
                    <div class="hero-stat">
                        <strong>{{ count($specializations) }}</strong>
                        <span>Specializations</span>
                    </div>
                    @endif
                    @if(count($facilities) > 0)
                    <div class="hero-stat">
                        <strong>{{ count($facilities) }}</strong>
                        <span>Facilities</span>
                    </div>
                    @endif
                    <div class="hero-stat">
                        <strong>{{ number_format($rating,1) }}</strong>
                        <span>Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== BODY ===== --}}
<section class="hosp-body">
    <div class="container">

        {{-- Alerts --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">

            {{-- ===== LEFT ===== --}}
            <div class="col-lg-8">

                {{-- About --}}
                <div class="info-card">
                    <h5><i class="fas fa-info-circle"></i> About Hospital</h5>
                    <p style="font-size:0.88rem; line-height:1.8; color:#555; margin:0;">
                        {{ $hospital->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Specializations --}}
                @if(count($specializations) > 0)
                <div class="info-card">
                    <h5><i class="fas fa-stethoscope"></i> Medical Specializations</h5>
                    <div class="tags-grid">
                        @foreach($specializations as $spec)
                        <div class="tag-spec"><i class="fas fa-check-circle"></i>{{ $spec }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Facilities --}}
                @if(count($facilities) > 0)
                <div class="info-card">
                    <h5><i class="fas fa-building"></i> Facilities & Services</h5>
                    <div class="tags-grid">
                        @foreach($facilities as $facility)
                        <div class="tag-fac"><i class="fas fa-check"></i>{{ $facility }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Doctors --}}
                <div class="info-card">
                    <h5>
                        <i class="fas fa-user-md"></i> Our Doctors
                        @if($doctors->count() > 0)
                        <span style="background:#e8f0fb;color:#0056a3;padding:0.15rem 0.6rem;border-radius:10px;font-size:0.72rem;margin-left:0.3rem;">
                            {{ $doctors->count() }}
                        </span>
                        @endif
                    </h5>
                    @if($doctors->count() > 0)
                    <div class="doctors-grid">
                        @foreach($doctors as $doctor)
                        @php
                            $dImg = $doctor->profile_image
                                ? asset('storage/'.$doctor->profile_image)
                                : null;
                        @endphp
                        <div class="doc-mini">
                            <div class="doc-mini-av">
                                @if($dImg)
                                    <img src="{{ $dImg }}" alt="Dr." onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                @else
                                    <span style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:#e8f0fb;color:#0056a3;font-weight:700;font-size:0.9rem;">
                                        {{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="doc-mini-info">
                                <h6>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h6>
                                <p>{{ $doctor->specialization ?? 'General' }}</p>
                                @if($doctor->consultation_fee)
                                <p style="color:#28a745;font-weight:700;font-size:0.72rem;margin-bottom:0.25rem;">
                                    Rs. {{ number_format($doctor->consultation_fee, 0) }}
                                </p>
                                @endif
                                <a href="{{ route('patient.doctors.show', $doctor->id) }}">
                                    View Profile <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-box">
                        <i class="fas fa-user-md"></i>
                        <p style="font-size:0.82rem;margin:0;">No doctors listed yet.</p>
                    </div>
                    @endif
                </div>

                {{-- ===== REVIEWS SECTION ===== --}}
                <div class="info-card">
                    <h5><i class="fas fa-star"></i> Patient Reviews
                        @if(isset($reviews) && $reviews->count() > 0)
                        <span style="background:#e8f0fb;color:#0056a3;padding:0.15rem 0.6rem;border-radius:10px;font-size:0.72rem;margin-left:0.3rem;">
                            {{ $reviews->count() }}
                        </span>
                        @endif
                    </h5>

                    {{-- Review Submit Form --}}
                    @auth
                        @if(Auth::user()->user_type === 'patient' || Auth::user()->role === 'patient')
                        <div class="review-form-card">
                            <p style="font-size:0.82rem;color:#0056a3;font-weight:600;margin-bottom:0.8rem;">
                                <i class="fas fa-edit me-1"></i> Leave a Review
                            </p>
                            <form method="POST" action="{{ route('patient.hospitals.review', $hospital->id) }}">
                                @csrf
                                {{-- Star Rating --}}
                                <div class="star-input mb-2" id="starInput">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fas fa-star" data-val="{{ $s }}" onclick="setRating({{ $s }})" onmouseover="hoverRating({{ $s }})" onmouseout="resetRating()"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingVal" value="0">
                                <textarea name="review" rows="2"
                                    style="width:100%;border:1.5px solid #e0e9f7;border-radius:8px;padding:0.6rem 0.8rem;font-size:0.82rem;resize:vertical;outline:none;margin-bottom:0.7rem;"
                                    placeholder="Share your experience with this hospital..."></textarea>
                                <button type="submit"
                                    style="background:linear-gradient(135deg,#0056a3,#003d7a);color:white;border:none;padding:0.5rem 1.3rem;border-radius:20px;font-size:0.82rem;font-weight:700;cursor:pointer;transition:all 0.2s;">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Review
                                </button>
                            </form>
                        </div>
                        @endif
                    @else
                    <div style="background:rgba(0,86,163,0.04);border-radius:8px;padding:0.8rem;margin-bottom:1rem;font-size:0.8rem;color:#666;text-align:center;">
                        <i class="fas fa-lock me-1" style="color:#0056a3;"></i>
                        <a href="{{ route('login') }}" style="color:#0056a3;font-weight:600;">Login</a> to leave a review.
                    </div>
                    @endauth

                    {{-- Review List --}}
                    @if(isset($reviews) && $reviews->count() > 0)
                        @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-av">
                                        @if(optional(optional($review->patient)->user)->profile_image)
                                            <img src="{{ asset('storage/'.optional(optional($review->patient)->user)->profile_image) }}" alt="">
                                        @else
                                            {{ strtoupper(substr(optional(optional($review->patient)->user)->name ?? 'P', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="reviewer-name">{{ optional(optional($review->patient)->user)->name ?? 'Patient' }}</div>
                                        <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="review-stars">
                                    @for($i=0; $i < $review->rating; $i++)<i class="fas fa-star"></i>@endfor
                                    @for($i=$review->rating; $i < 5; $i++)<i class="far fa-star"></i>@endfor
                                </div>
                            </div>
                            @if($review->review)
                            <p class="review-text">{{ $review->review }}</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                    <div class="empty-box">
                        <i class="fas fa-star"></i>
                        <p style="font-size:0.82rem;margin:0;">No reviews yet. Be the first!</p>
                    </div>
                    @endif
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-4">

                {{-- Contact Info --}}
                <div class="quick-info-card">
                    <h5><i class="fas fa-address-card"></i> Contact Information</h5>
                    @if($hospital->address)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i>Address</span>
                        <span class="info-value" style="font-size:0.8rem;">{{ $hospital->address }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-city"></i>City</span>
                        <span class="info-value">{{ $hospital->city ?? 'N/A' }}{{ $hospital->province ? ', '.$hospital->province : '' }}</span>
                    </div>
                    @if($hospital->postal_code)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-mail-bulk"></i>Postal</span>
                        <span class="info-value">{{ $hospital->postal_code }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-phone"></i>Phone</span>
                        <span class="info-value">{{ $hospital->phone ?? 'N/A' }}</span>
                    </div>
                    @if($hospital->email)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-envelope"></i>Email</span>
                        <span class="info-value" style="font-size:0.78rem;">{{ $hospital->email }}</span>
                    </div>
                    @endif
                    @if($hospital->website)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-globe"></i>Website</span>
                        <span class="info-value">
                            <a href="{{ $hospital->website }}" target="_blank"
                               style="color:#0056a3;font-weight:600;font-size:0.82rem;text-decoration:none;">
                                <i class="fas fa-external-link-alt" style="font-size:0.68rem;"></i> Visit
                            </a>
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Operating Hours --}}
                @if($hospital->operatinghours)
                <div class="quick-info-card">
                    <h5><i class="fas fa-clock"></i> Operating Hours</h5>
                    <p style="font-size:0.83rem;color:#555;line-height:1.7;margin:0;">
                        {{ $hospital->operatinghours }}
                    </p>
                </div>
                @endif
                {{-- Hospital Quick Stats --}}
                <div class="quick-info-card">
                    <h5><i class="fas fa-hospital"></i> Hospital Details</h5>
                    @if($hospital->type)
                    <div class="qi-row">
                        <span class="qi-label">Type</span>
                        <span class="qi-value" style="text-transform:capitalize;">{{ $hospital->type }}</span>
                    </div>
                    @endif
                    <div class="qi-row">
                        <span class="qi-label">Rating</span>
                        <span class="qi-value" style="color:#f39c12;">
                            ★ {{ number_format($hospital->rating ?? 0, 1) }} / 5.0
                        </span>
                    </div>
                    <div class="qi-row">
                        <span class="qi-label">Reviews</span>
                        <span class="qi-value">{{ $hospital->total_ratings ?? 0 }}</span>
                    </div>
                    <div class="qi-row">
                        <span class="qi-label">Status</span>
                        @if($hospital->status === 'approved')
                        <span style="background:#d4edda;color:#155724;padding:0.18rem 0.65rem;border-radius:10px;font-size:0.72rem;font-weight:700;">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                        @else
                        <span style="background:#fff3cd;color:#856404;padding:0.18rem 0.65rem;border-radius:10px;font-size:0.72rem;font-weight:700;">
                            Pending
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Find a Doctor CTA --}}
                @if($doctors->count() > 0)
                <div style="background:linear-gradient(135deg,rgba(0,86,163,0.05),rgba(0,86,163,0.1));border:1px solid rgba(0,86,163,0.15);border-radius:12px;padding:1.2rem;text-align:center;">
                    <i class="fas fa-user-md" style="font-size:1.8rem;color:#0056a3;margin-bottom:0.5rem;display:block;"></i>
                    <p style="font-size:0.83rem;color:#555;margin-bottom:1rem;line-height:1.5;">
                        Book an appointment with one of our <strong>{{ $doctors->count() }} doctors</strong>.
                    </p>
                    <a href="{{ route('patient.doctors') }}"
                       style="display:inline-flex;align-items:center;gap:0.4rem;background:linear-gradient(135deg,#0056a3,#003d7a);color:white;padding:0.6rem 1.3rem;border-radius:20px;text-decoration:none;font-size:0.82rem;font-weight:700;box-shadow:0 4px 12px rgba(0,86,163,0.3);">
                        <i class="fas fa-calendar-plus"></i> Book Appointment
                    </a>
                </div>
                @endif

            </div>{{-- end col-lg-4 --}}
        </div>
    </div>
</section>

@include('partials.footer')

<script>
// Star Rating
let selectedRating = 0;
function hoverRating(val) {
    document.querySelectorAll('#starInput i').forEach((s, i) => {
        s.classList.toggle('active', i < val);
    });
}
function resetRating() {
    document.querySelectorAll('#starInput i').forEach((s, i) => {
        s.classList.toggle('active', i < selectedRating);
    });
}
function setRating(val) {
    selectedRating = val;
    document.getElementById('ratingVal').value = val;
    resetRating();
}

// Fade-in animation
document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.info-card, .quick-info-card, .doc-mini, .review-card');
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.08 });
    els.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        el.style.transition = `opacity 0.35s ease ${i * 0.04}s, transform 0.35s ease ${i * 0.04}s`;
        obs.observe(el);
    });
});
</script>
