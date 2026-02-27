@include('partials.header')

<style>
/* ═══════════════════════════════════
   PROFILE HEADER
═══════════════════════════════════ */
.profile-hero {
    background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%);
    padding-top: 80px;
    padding-bottom: 0;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.05;
}
.profile-hero .container { position: relative; z-index: 1; }

.back-link {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 1.2rem;
    transition: color 0.2s;
}
.back-link:hover { color: #fff; }

/* Doctor card (white card attached to header bottom) */
.doctor-main-card {
    background: #fff;
    border-radius: 20px 20px 0 0;
    padding: 2rem 2rem 0;
    box-shadow: 0 -6px 30px rgba(0,0,0,0.08);
    margin-top: 1.5rem;
}
.doctor-main-top {
    display: flex;
    gap: 1.8rem;
    align-items: flex-start;
    flex-wrap: wrap;
    padding-bottom: 1.5rem;
}

/* Avatar */
.doc-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.doc-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #42a649;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    display: block;
}

/* Info */
.doc-info { flex: 1; min-width: 200px; }
.doc-name  { font-size: 1.65rem; font-weight: 800; color: #1a5276; margin-bottom: 0.25rem; }
.doc-spec  { color: #42a649; font-weight: 600; font-size: 1rem; margin-bottom: 0.7rem; }

.badge-row { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.8rem; }
.badge-verified {
    background: #d4edda; color: #155724;
    padding: 0.28rem 0.85rem;
    border-radius: 20px; font-size: 0.76rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.badge-available {
    background: #d1ecf1; color: #0c5460;
    padding: 0.28rem 0.85rem;
    border-radius: 20px; font-size: 0.76rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.badge-available i { color: #28a745; font-size: 0.55rem; }

/* Stats */
.stats-row { display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 0.8rem; }
.stat-pill { display: flex; align-items: center; gap: 0.4rem; font-size: 0.83rem; color: #555; }
.stat-pill i { color: #42a649; }
.stat-num   { font-weight: 700; color: #1a5276; }

/* Stars */
.stars-row { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.2rem; }
.stars i { color: #f5a623; font-size: 0.95rem; }
.stars-sm i { color: #f5a623; font-size: 0.8rem; }
.rating-label { font-size: 0.85rem; color: #666; font-weight: 600; }

/* Fee + Book (right side) */
.doc-action-box { flex-shrink: 0; min-width: 175px; }
.fee-card {
    background: linear-gradient(135deg, rgba(66,166,73,0.07), rgba(66,166,73,0.14));
    border: 2px solid rgba(66,166,73,0.25);
    border-radius: 14px;
    padding: 1.1rem 1.2rem;
    text-align: center;
    margin-bottom: 0.9rem;
}
.fee-lbl    { font-size: 0.74rem; color: #777; font-weight: 600; margin-bottom: 0.3rem; }
.fee-amount { font-size: 1.7rem; font-weight: 800; color: #42a649; line-height: 1.1; }
.fee-sub    { font-size: 0.66rem; color: #aaa; }
.btn-book {
    display: flex; align-items: center; justify-content: center; gap: 0.45rem;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none;
    padding: 0.85rem 1.2rem;
    border-radius: 25px;
    font-size: 0.92rem; font-weight: 700;
    text-decoration: none; width: 100%;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(66,166,73,0.35);
    cursor: pointer;
}
.btn-book:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 6px 22px rgba(66,166,73,0.45); }

/* ═══════════════════════════════════
   BODY
═══════════════════════════════════ */
.profile-body { background: #f4f6f9; padding: 2.5rem 0 4rem; }

/* Section card */
.s-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.6rem;
    box-shadow: 0 3px 14px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
}
.s-title {
    font-size: 1rem; font-weight: 700; color: #1a5276;
    display: flex; align-items: center; gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(66,166,73,0.2);
    margin-bottom: 1.1rem;
}
.s-title i { color: #42a649; }

/* Qualifications */
.qual-list { list-style: none; padding: 0; margin: 0; }
.qual-list li {
    display: flex; align-items: flex-start; gap: 0.6rem;
    padding: 0.55rem 0; border-bottom: 1px solid #f5f5f5;
    font-size: 0.88rem; color: #444;
}
.qual-list li:last-child { border: none; }
.qual-list li i { color: #42a649; margin-top: 0.15rem; flex-shrink: 0; }

/* Workplace */
.wp-card {
    border: 1.5px solid #e9ecef; border-radius: 12px;
    padding: 1.1rem 1.3rem; margin-bottom: 0.85rem;
    transition: box-shadow 0.2s;
}
.wp-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.09); }
.wp-card:last-child { margin-bottom: 0; }
.wp-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem; }
.wp-name { font-size: 0.95rem; font-weight: 700; color: #1a5276; }
.wp-type-badge {
    background: #e3f2fd; color: #0d47a1;
    font-size: 0.7rem; font-weight: 700;
    padding: 0.22rem 0.65rem; border-radius: 10px;
    text-transform: capitalize; white-space: nowrap;
}
.wp-meta { font-size: 0.82rem; color: #666; display: flex; align-items: center; gap: 0.4rem; margin-top: 0.3rem; }
.wp-meta i { color: #42a649; width: 14px; text-align: center; }
.wp-link {
    font-size: 0.8rem; color: #42a649; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem; margin-top: 0.6rem;
}
.wp-link:hover { color: #1a5276; }

/* Reviews header */
.reviews-header {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(66,166,73,0.2);
    margin-bottom: 1.1rem;
}
.reviews-title { font-size: 1rem; font-weight: 700; color: #1a5276; display: flex; align-items: center; gap: 0.5rem; }
.reviews-title i { color: #42a649; }
.reviews-count { font-size: 0.78rem; color: #42a649; font-weight: 600; }

.btn-write-review {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none;
    padding: 0.45rem 1.1rem;
    border-radius: 18px; font-size: 0.8rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s; text-decoration: none;
    box-shadow: 0 3px 10px rgba(66,166,73,0.3);
}
.btn-write-review:hover { transform: translateY(-1px); color: #fff; }

/* Review card */
.review-card {
    border: 1px solid #f0f0f0; border-radius: 12px;
    padding: 1.1rem 1.2rem; margin-bottom: 0.85rem;
    background: #fafafa;
}
.review-card:last-child { margin-bottom: 0; }
.rv-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; flex-wrap: wrap; }
.rv-user { display: flex; gap: 0.7rem; align-items: center; }
.rv-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    overflow: hidden; border: 2px solid #42a649; flex-shrink: 0;
}
.rv-avatar img { width: 100%; height: 100%; object-fit: cover; }
.rv-name  { font-size: 0.88rem; font-weight: 700; color: #1a5276; }
.rv-date  { font-size: 0.74rem; color: #aaa; margin-top: 0.1rem; }
.rv-text  { font-size: 0.85rem; color: #555; line-height: 1.7; margin: 0.6rem 0 0; }

/* Empty reviews */
.no-reviews {
    text-align: center; padding: 2.5rem 1rem; color: #bbb;
}
.no-reviews i { font-size: 2.8rem; display: block; margin-bottom: 0.6rem; }
.no-reviews p  { font-size: 0.88rem; margin: 0; }

/* ═══════════════════════════════════
   SIDEBAR INFO
═══════════════════════════════════ */
.info-list { padding: 0; margin: 0; }
.info-item {
    display: flex; gap: 0.9rem; align-items: flex-start;
    padding: 0.75rem 0; border-bottom: 1px solid #f5f5f5;
}
.info-item:last-child { border: none; }
.info-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #1a5276, #2e86c1);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 0.82rem;
}
.info-lbl { font-size: 0.72rem; color: #999; font-weight: 600; margin-bottom: 0.1rem; }
.info-val { font-size: 0.88rem; color: #333; font-weight: 600; }
.info-val.available { color: #42a649; }
.info-val.unavailable { color: #dc3545; }

/* Rating summary */
.rating-summary { text-align: center; padding: 0.5rem 0 0.8rem; }
.rating-big { font-size: 3.2rem; font-weight: 800; color: #1a5276; line-height: 1; }
.rating-sub { font-size: 0.78rem; color: #aaa; margin-top: 0.4rem; }

/* ═══════════════════════════════════
   REVIEW MODAL
═══════════════════════════════════ */
.modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    align-items: center; justify-content: center;
    padding: 1rem;
}
.modal-overlay.active { display: flex; }

.modal-box {
    background: #fff;
    border-radius: 18px;
    padding: 2rem;
    width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: modalPop 0.3s ease;
    position: relative;
}
@keyframes modalPop {
    from { opacity: 0; transform: scale(0.92) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-close {
    position: absolute; top: 1rem; right: 1rem;
    background: #f5f5f5; border: none;
    width: 30px; height: 30px; border-radius: 50%;
    font-size: 0.85rem; color: #666; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.2s;
}
.modal-close:hover { background: #e0e0e0; }
.modal-title { font-size: 1.1rem; font-weight: 700; color: #1a5276; margin-bottom: 0.25rem; }
.modal-sub   { font-size: 0.82rem; color: #888; margin-bottom: 1.3rem; }

/* Star picker */
.star-picker { display: flex; gap: 0.3rem; margin-bottom: 0.5rem; }
.star-picker i {
    font-size: 2rem; cursor: pointer; color: #ddd;
    transition: color 0.15s, transform 0.1s;
}
.star-picker i:hover,
.star-picker i.lit { color: #f5a623; }
.star-picker i:hover { transform: scale(1.15); }

.form-lbl { font-size: 0.82rem; font-weight: 600; color: #1a5276; display: block; margin-bottom: 0.4rem; }
.form-lbl span { color: #dc3545; }
.form-ta {
    width: 100%; padding: 0.75rem 0.9rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.88rem; resize: vertical; min-height: 90px;
    transition: border-color 0.2s; font-family: inherit;
}
.form-ta:focus { border-color: #42a649; outline: none; }

.err-msg { font-size: 0.75rem; color: #dc3545; display: none; margin-top: 0.3rem; }

.modal-footer { display: flex; gap: 0.7rem; justify-content: flex-end; margin-top: 1.2rem; }
.btn-cancel {
    background: #fff; color: #666;
    border: 1.5px solid #ddd;
    padding: 0.5rem 1.3rem;
    border-radius: 20px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
    transition: all 0.2s;
}
.btn-cancel:hover { background: #f5f5f5; }
.btn-submit {
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none;
    padding: 0.5rem 1.6rem;
    border-radius: 20px; font-weight: 700; font-size: 0.85rem; cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 3px 10px rgba(66,166,73,0.35);
}
.btn-submit:hover { transform: translateY(-1px); }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 768px) {
    .doctor-main-top { flex-direction: column; }
    .doc-avatar { width: 85px; height: 85px; }
    .doc-name   { font-size: 1.3rem; }
    .doc-action-box { width: 100%; }
    .fee-card   { padding: 0.9rem; }
}
</style>

{{-- ═══════════════════════════════════
     PROFILE HERO
═══════════════════════════════════ --}}
<section class="profile-hero">
    <div class="container">
        <a href="{{ route('patient.doctors') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Doctors
        </a>

        <div class="doctor-main-card">
            <div class="doctor-main-top">

                {{-- ── Avatar ── --}}
                @php
                    $profileImage = $doctor->profile_image
                        ? asset('storage/' . $doctor->profile_image)
                        : asset('images/default-avatar.png');

                    $rating     = $doctor->rating ?? 0;
                    $fullStars  = floor($rating);
                    $halfStar   = ($rating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <div class="doc-avatar-wrap">
                    <img src="{{ $profileImage }}"
                         alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                         class="doc-avatar"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                </div>

                {{-- ── Info ── --}}
                <div class="doc-info">
                    <div class="doc-name">
                        Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? '' }}
                    </div>
                    <div class="doc-spec">
                        {{ $doctor->specialization ?? 'General Practitioner' }}
                    </div>

                    <div class="badge-row">
                        @if($doctor->status === 'approved')
                            <span class="badge-verified">
                                <i class="fas fa-check-circle"></i> Verified Doctor
                            </span>
                        @endif
                        @if($doctor->user && $doctor->user->status === 'active')
                            <span class="badge-available">
                                <i class="fas fa-circle"></i> Available
                            </span>
                        @endif
                    </div>

                    <div class="stats-row">
                        @if($doctor->experience_years)
                            <div class="stat-pill">
                                <i class="fas fa-briefcase-medical"></i>
                                <span><span class="stat-num">{{ $doctor->experience_years }}</span> {{ Str::plural('yr', $doctor->experience_years) }} exp.</span>
                            </div>
                        @endif
                        @if($totalAppointments > 0)
                            <div class="stat-pill">
                                <i class="fas fa-calendar-check"></i>
                                <span><span class="stat-num">{{ $totalAppointments }}</span> {{ Str::plural('appt', $totalAppointments) }}</span>
                            </div>
                        @endif
                        @if($doctor->total_ratings > 0)
                            <div class="stat-pill">
                                <i class="fas fa-star"></i>
                                <span><span class="stat-num">{{ $doctor->total_ratings }}</span> {{ Str::plural('review', $doctor->total_ratings) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="stars-row">
                        <div class="stars">
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @if($halfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <span class="rating-label">{{ number_format($rating, 1) }} / 5</span>
                    </div>
                </div>

                {{-- ── Fee + Book ── --}}
                <div class="doc-action-box">
                    <div class="fee-card">
                        <div class="fee-lbl">Consultation Fee</div>
                        <div class="fee-amount">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                        <div class="fee-sub">Sri Lankan Rupees</div>
                    </div>
                    <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                       class="btn-book">
                        <i class="fas fa-calendar-plus"></i> Book Appointment
                    </a>
                </div>

            </div>{{-- /doctor-main-top --}}
        </div>{{-- /doctor-main-card --}}
    </div>
</section>

{{-- ═══════════════════════════════════
     BODY
═══════════════════════════════════ --}}
<section class="profile-body">
    <div class="container">

        {{-- Flash messages --}}
        @foreach(['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $type)
            @if(session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show border-0 rounded-3 mb-3"
                     style="font-size: 0.88rem;">
                    <i class="fas fa-{{ $key === 'success' ? 'check-circle' : ($key === 'error' ? 'times-circle' : 'info-circle') }} me-2"></i>
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        <div class="row g-4">

            {{-- ══════════════════════════
                 LEFT COLUMN
            ══════════════════════════ --}}
            <div class="col-lg-8">

                {{-- About --}}
                @if($doctor->bio)
                <div class="s-card">
                    <h2 class="s-title"><i class="fas fa-user-circle"></i> About Doctor</h2>
                    <p style="font-size: 0.91rem; color: #555; line-height: 1.8; margin: 0;">
                        {{ $doctor->bio }}
                    </p>
                </div>
                @endif

                {{-- Qualifications --}}
                @if($doctor->qualifications)
                <div class="s-card">
                    <h2 class="s-title"><i class="fas fa-graduation-cap"></i> Education & Qualifications</h2>
                    <ul class="qual-list">
                        @foreach(explode(',', $doctor->qualifications) as $qual)
                            <li>
                                <i class="fas fa-certificate"></i>
                                <span>{{ trim($qual) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Workplaces --}}
                @if($workplaces->count() > 0)
                <div class="s-card">
                    <h2 class="s-title"><i class="fas fa-hospital"></i> Practice Locations</h2>

                    @foreach($workplaces as $wp)
                        @php
                            $wpName  = 'Not Available';
                            $wpAddr  = 'Address not available';
                            $wpCity  = null;
                            $wpPhone = null;
                            $wpLink  = null;
                            $wpType  = ucwords(str_replace('_', ' ', $wp->workplace_type));

                            if ($wp->workplace_type === 'hospital' && $wp->hospital) {
                                $wpName  = $wp->hospital->name;
                                $wpAddr  = $wp->hospital->address ?? 'N/A';
                                $wpCity  = $wp->hospital->city   ?? null;
                                $wpPhone = $wp->hospital->phone  ?? null;
                                $wpLink  = route('patient.hospitals.show', $wp->hospital->id);
                            } elseif ($wp->workplace_type === 'medical_centre' && $wp->medicalCentre) {
                                $wpName  = $wp->medicalCentre->name;
                                $wpAddr  = $wp->medicalCentre->address ?? 'N/A';
                                $wpCity  = $wp->medicalCentre->city   ?? null;
                                $wpPhone = $wp->medicalCentre->phone  ?? null;
                                $wpLink  = route('patient.medical-centres.show', $wp->medicalCentre->id);
                            }
                        @endphp

                        <div class="wp-card">
                            <div class="wp-head">
                                <div class="wp-name">{{ $wpName }}</div>
                                <span class="wp-type-badge">{{ $wpType }}</span>
                            </div>
                            <div class="wp-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $wpAddr }}</span>
                            </div>
                            @if($wpCity)
                                <div class="wp-meta">
                                    <i class="fas fa-city"></i>
                                    <span>{{ $wpCity }}</span>
                                </div>
                            @endif
                            @if($wpPhone)
                                <div class="wp-meta">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $wpPhone }}</span>
                                </div>
                            @endif
                            @if($wpLink)
                                <a href="{{ $wpLink }}" class="wp-link">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ─── REVIEWS SECTION ─── --}}
                <div class="s-card">
                    <div class="reviews-header">
                        <div class="reviews-title">
                            <i class="fas fa-comments"></i>
                            Patient Reviews
                            @if($doctor->total_ratings > 0)
                                <span class="reviews-count">({{ $doctor->total_ratings }})</span>
                            @endif
                        </div>

                        {{-- Write Review Button --}}
                        @auth
                            @if($canReview)
                                <button class="btn-write-review" onclick="openReviewModal()">
                                    <i class="fas fa-star"></i> Write a Review
                                </button>
                            @elseif($alreadyReviewed)
                                <span style="font-size: 0.78rem; color: #42a649; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> You've reviewed this doctor
                                </span>
                            @else
                                {{-- Logged in but no completed appointment --}}
                                <span style="font-size: 0.76rem; color: #aaa; font-style: italic;">
                                    Complete an appointment to review
                                </span>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               style="font-size: 0.78rem; color: #42a649; font-weight: 600; text-decoration: none;">
                                <i class="fas fa-sign-in-alt"></i> Login to review
                            </a>
                        @endauth
                    </div>

                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            @php
                                $rvImg = ($review->patient && $review->patient->user && $review->patient->user->profile_image)
                                    ? asset('storage/' . $review->patient->user->profile_image)
                                    : asset('images/default-avatar.png');

                                $rvName = ($review->patient && $review->patient->user)
                                    ? $review->patient->user->name
                                    : 'Anonymous Patient';

                                $rvStarFull  = $review->rating;
                                $rvStarEmpty = 5 - $review->rating;
                            @endphp
                            <div class="review-card">
                                <div class="rv-top">
                                    <div class="rv-user">
                                        <div class="rv-avatar">
                                            <img src="{{ $rvImg }}"
                                                 alt="{{ $rvName }}"
                                                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        </div>
                                        <div>
                                            <div class="rv-name">{{ $rvName }}</div>
                                            <div class="rv-date">
                                                <i class="far fa-clock" style="font-size: 0.7rem;"></i>
                                                {{ $review->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stars-sm">
                                        @for($i = 0; $i < $rvStarFull; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = 0; $i < $rvStarEmpty; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->review)
                                    <p class="rv-text">"{{ $review->review }}"</p>
                                @endif
                            </div>
                        @endforeach

                        {{-- Pagination --}}
                        @if($reviews->hasPages())
                            <div style="margin-top: 1rem;">
                                {{ $reviews->links() }}
                            </div>
                        @endif

                    @else
                        <div class="no-reviews">
                            <i class="far fa-comment-dots"></i>
                            <p>No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endif
                </div>

            </div>{{-- /col-lg-8 --}}

            {{-- ══════════════════════════
                 SIDEBAR
            ══════════════════════════ --}}
            <div class="col-lg-4">

                {{-- Professional Info --}}
                <div class="s-card">
                    <h2 class="s-title"><i class="fas fa-id-card"></i> Professional Info</h2>
                    <div class="info-list">

                        @if($doctor->slmc_number)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-id-badge"></i></div>
                            <div>
                                <div class="info-lbl">SLMC Registration</div>
                                <div class="info-val">{{ $doctor->slmc_number }}</div>
                            </div>
                        </div>
                        @endif

                        @if($doctor->specialization)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-stethoscope"></i></div>
                            <div>
                                <div class="info-lbl">Specialization</div>
                                <div class="info-val">{{ $doctor->specialization }}</div>
                            </div>
                        </div>
                        @endif

                        @if($doctor->experience_years)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-briefcase"></i></div>
                            <div>
                                <div class="info-lbl">Experience</div>
                                <div class="info-val">
                                    {{ $doctor->experience_years }} {{ Str::plural('year', $doctor->experience_years) }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($doctor->phone)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div class="info-lbl">Contact</div>
                                <div class="info-val">{{ $doctor->phone }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-user-check"></i></div>
                            <div>
                                <div class="info-lbl">Status</div>
                                @if($doctor->user && $doctor->user->status === 'active')
                                    <div class="info-val available">
                                        <i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle;"></i>
                                        Available
                                    </div>
                                @else
                                    <div class="info-val unavailable">Unavailable</div>
                                @endif
                            </div>
                        </div>

                        @if($completedCount > 0)
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                            <div>
                                <div class="info-lbl">Completed Appointments</div>
                                <div class="info-val">{{ $completedCount }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Book CTA --}}
                <div class="s-card"
                     style="background: linear-gradient(135deg, rgba(66,166,73,0.05), rgba(66,166,73,0.11));
                            border: 2px solid rgba(66,166,73,0.2);">
                    <h2 class="s-title"><i class="fas fa-calendar-check"></i> Book Appointment</h2>
                    <p style="font-size: 0.85rem; color: #555; margin-bottom: 1.2rem; line-height: 1.6;">
                        Get professional medical consultation from
                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.
                    </p>
                    <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                       class="btn-book">
                        <i class="fas fa-calendar-plus"></i> Book Now
                    </a>
                </div>

                {{-- Rating Summary --}}
                @if($doctor->total_ratings > 0)
                <div class="s-card">
                    <h2 class="s-title"><i class="fas fa-star"></i> Rating Overview</h2>
                    <div class="rating-summary">
                        <div class="rating-big">{{ number_format($doctor->rating, 1) }}</div>
                        <div class="stars" style="margin: 0.5rem 0; justify-content: center; display: flex; gap: 0.1rem;">
                            @for($i = 0; $i < floor($doctor->rating); $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            @if(($doctor->rating - floor($doctor->rating)) >= 0.5)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                            @for($i = ceil($doctor->rating); $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <div class="rating-sub">
                            Based on {{ $doctor->total_ratings }} {{ Str::plural('review', $doctor->total_ratings) }}
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- /col-lg-4 --}}

        </div>{{-- /row --}}
    </div>
</section>

{{-- ═══════════════════════════════════
     REVIEW MODAL
═══════════════════════════════════ --}}
@auth
@if($canReview)
<div class="modal-overlay" id="reviewModal">
    <div class="modal-box">

        <button class="modal-close" onclick="closeReviewModal()" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-title">
            <i class="fas fa-star" style="color: #f5a623;"></i>
            Leave a Review
        </div>
        <div class="modal-sub">
            Share your experience with Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
        </div>

        <form action="{{ route('patient.doctors.review.store', $doctor->id) }}"
              method="POST" id="reviewForm">
            @csrf

            {{-- Star Rating --}}
            <div style="margin-bottom: 1.1rem;">
                <label class="form-lbl">Your Rating <span>*</span></label>
                <div class="star-picker" id="starPicker">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="far fa-star"
                           data-val="{{ $s }}"
                           onmouseover="hoverStar({{ $s }})"
                           onmouseout="resetStars()"
                           onclick="selectStar({{ $s }})"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
                <div class="err-msg" id="starErr">Please select a star rating.</div>

                {{-- Labels --}}
                <div id="ratingLabel"
                     style="font-size: 0.78rem; color: #f5a623; font-weight: 600; margin-top: 0.3rem; min-height: 1rem;"></div>
            </div>

            {{-- Review Text --}}
            <div style="margin-bottom: 0.5rem;">
                <label class="form-lbl" for="reviewText">
                    Your Review
                    <span style="color: #aaa; font-weight: 400;">(optional)</span>
                </label>
                <textarea name="review"
                          id="reviewText"
                          class="form-ta"
                          placeholder="Describe your experience — how was the consultation, doctor's behaviour, waiting time, etc."
                          maxlength="1000"></textarea>
                <div style="font-size: 0.72rem; color: #aaa; text-align: right; margin-top: 0.2rem;">
                    <span id="charCount">0</span> / 1000
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane me-1"></i> Submit Review
                </button>
            </div>
        </form>

    </div>
</div>
@endif
@endauth

@include('partials.footer')

<script>
/* ════════════════════════════
   MODAL
════════════════════════════ */
function openReviewModal() {
    document.getElementById('reviewModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('reviewModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeReviewModal();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReviewModal();
});

/* ════════════════════════════
   STAR PICKER
════════════════════════════ */
let selectedRating = 0;
const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

function hoverStar(val) {
    document.querySelectorAll('#starPicker i').forEach((s, i) => {
        s.className = i < val ? 'fas fa-star' : 'far fa-star';
        if (i < val) s.classList.add('lit');
    });
    document.getElementById('ratingLabel').textContent = labels[val] || '';
}

function resetStars() {
    document.querySelectorAll('#starPicker i').forEach((s, i) => {
        if (i < selectedRating) {
            s.className = 'fas fa-star lit';
        } else {
            s.className = 'far fa-star';
        }
    });
    document.getElementById('ratingLabel').textContent = labels[selectedRating] || '';
}

function selectStar(val) {
    selectedRating = val;
    document.getElementById('ratingInput').value = val;
    document.getElementById('starErr').style.display = 'none';
    resetStars();
}

/* ════════════════════════════
   CHAR COUNTER
════════════════════════════ */
document.getElementById('reviewText')?.addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

/* ════════════════════════════
   FORM VALIDATION
════════════════════════════ */
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    if (selectedRating === 0) {
        e.preventDefault();
        document.getElementById('starErr').style.display = 'block';
        document.getElementById('starPicker').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    // Disable submit button to prevent double submit
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';
});

/* ════════════════════════════
   AUTO-DISMISS ALERTS
════════════════════════════ */
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity    = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);

/* ════════════════════════════
   AUTO-OPEN MODAL if validation error
════════════════════════════ */
@if($errors->has('rating'))
    window.addEventListener('DOMContentLoaded', () => openReviewModal());
@endif
</script>
