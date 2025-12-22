{{-- Include Header --}}
@include('partials.header')

<style>
/* Profile Page Styles */
.profile-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 3rem 0 2rem;
    color: white;
}

.back-btn {
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.back-btn:hover {
    color: white;
    transform: translateX(-5px);
}

.doctor-profile-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-top: -2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.doctor-profile-top {
    display: flex;
    gap: 2rem;
    align-items: start;
    margin-bottom: 2rem;
}

.doctor-profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid var(--accent-color);
    flex-shrink: 0;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.doctor-profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-profile-info {
    flex: 1;
}

.doctor-profile-name {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.doctor-profile-specialty {
    font-size: 1.2rem;
    color: var(--accent-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-transform: capitalize;
}

.doctor-profile-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge-approved,
.badge-available {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.badge-approved {
    background: #d4edda;
    color: #155724;
}

.badge-available {
    background: #d1ecf1;
    color: #0c5460;
}

.doctor-profile-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-item i {
    color: var(--accent-color);
    font-size: 1.1rem;
}

.stat-value {
    font-weight: 700;
    color: var(--primary-color);
    margin-right: 0.3rem;
}

.doctor-rating-large {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(66, 166, 73, 0.1);
    border-radius: 10px;
    display: inline-flex;
}

.stars-large {
    color: #ffc107;
    font-size: 1.2rem;
}

.rating-text-large {
    font-size: 0.95rem;
    color: #333;
    font-weight: 600;
}

.consultation-fee-box {
    background: linear-gradient(135deg, rgba(66, 166, 73, 0.1) 0%, rgba(66, 166, 73, 0.15) 100%);
    padding: 1.2rem;
    border-radius: 12px;
    text-align: center;
    border: 2px solid rgba(66, 166, 73, 0.3);
}

.fee-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.3rem;
}

.fee-amount {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--accent-color);
}

.book-appointment-btn {
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    margin-top: 1rem;
    box-shadow: 0 4px 15px rgba(66, 166, 73, 0.3);
}

.book-appointment-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66, 166, 73, 0.4);
}

.content-section {
    padding: 2rem 0;
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 1.8rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.section-title i {
    font-size: 1.1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.2rem;
}

.info-item {
    display: flex;
    align-items: start;
    gap: 1rem;
}

.info-icon {
    width: 45px;
    height: 45px;
    background: rgba(66, 166, 73, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-color);
    flex-shrink: 0;
}

.info-content h4 {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.3rem;
    font-weight: 500;
}

.info-content p {
    font-size: 1rem;
    color: #333;
    margin: 0;
    font-weight: 600;
}

.qualifications-list {
    list-style: none;
    padding: 0;
}

.qualifications-list li {
    padding: 0.8rem 1rem;
    background: rgba(66, 166, 73, 0.05);
    border-radius: 8px;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.qualifications-list li i {
    color: var(--accent-color);
}

.workplace-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.2rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.workplace-card:hover {
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.workplace-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.8rem;
}

.workplace-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.3rem;
}

.workplace-type {
    font-size: 0.75rem;
    padding: 0.25rem 0.7rem;
    background: var(--accent-color);
    color: white;
    border-radius: 12px;
    text-transform: capitalize;
}

.workplace-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.4rem;
}

.workplace-info i {
    color: var(--accent-color);
    width: 16px;
}

.view-workplace-link {
    color: var(--accent-color);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.5rem;
}

.view-workplace-link:hover {
    text-decoration: underline;
}

.review-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.2rem;
    margin-bottom: 1rem;
    border-left: 4px solid var(--accent-color);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.8rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--accent-color);
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.reviewer-name {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 0.95rem;
}

.review-date {
    font-size: 0.75rem;
    color: #999;
}

.review-rating {
    display: flex;
    gap: 0.2rem;
}

.review-rating i {
    color: #ffc107;
    font-size: 0.85rem;
}

.review-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .doctor-profile-top {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .doctor-profile-avatar {
        width: 120px;
        height: 120px;
    }

    .doctor-profile-name {
        font-size: 1.5rem;
    }

    .doctor-profile-stats {
        justify-content: center;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Profile Header --}}
<section class="profile-header">
    <div class="container">
        <a href="{{ route('patient.doctors') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Doctors
        </a>
    </div>
</section>

{{-- Doctor Profile --}}
<section>
    <div class="container">
        <div class="doctor-profile-card">
            <div class="doctor-profile-top">
                <div class="doctor-profile-avatar">
                    @php
                        $profileImage = $doctor->profile_image
                            ? asset('storage/' . $doctor->profile_image)
                            : asset('images/default-avatar.png');
                    @endphp
                    <img src="{{ $profileImage }}"
                         alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                </div>

                <div class="doctor-profile-info">
                    <h1 class="doctor-profile-name">
                        Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? 'Doctor' }}
                    </h1>

                    <div class="doctor-profile-specialty">
                        {{ $doctor->specialization ?? 'General Practitioner' }}
                    </div>

                    <div class="doctor-profile-badges">
                        @if($doctor->status == 'approved')
                            <span class="badge-approved">
                                <i class="fas fa-check-circle"></i>
                                Verified Doctor
                            </span>
                        @endif

                        @if($doctor->user && $doctor->user->status == 'active')
                            <span class="badge-available">
                                <i class="fas fa-circle"></i>
                                Available
                            </span>
                        @endif
                    </div>

                    <div class="doctor-profile-stats">
                        @if($doctor->experience_years)
                            <div class="stat-item">
                                <i class="fas fa-briefcase-medical"></i>
                                <span>
                                    <span class="stat-value">{{ $doctor->experience_years }}</span>
                                    {{ Str::plural('year', $doctor->experience_years) }} experience
                                </span>
                            </div>
                        @endif

                        @if($totalAppointments > 0)
                            <div class="stat-item">
                                <i class="fas fa-calendar-check"></i>
                                <span>
                                    <span class="stat-value">{{ $totalAppointments }}</span>
                                    {{ Str::plural('appointment', $totalAppointments) }}
                                </span>
                            </div>
                        @endif

                        @if($doctor->total_ratings > 0)
                            <div class="stat-item">
                                <i class="fas fa-star"></i>
                                <span>
                                    <span class="stat-value">{{ $doctor->total_ratings }}</span>
                                    {{ Str::plural('review', $doctor->total_ratings) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="doctor-rating-large">
                        <div class="stars-large">
                            @php
                                $rating = $doctor->rating ?? 0;
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

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
                        <span class="rating-text-large">
                            {{ number_format($rating, 1) }} out of 5
                        </span>
                    </div>
                </div>

                <div>
                    <div class="consultation-fee-box">
                        <div class="fee-label">Consultation Fee</div>
                        <div class="fee-amount">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                    </div>
                    <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                       class="book-appointment-btn">
                        <i class="fas fa-calendar-plus"></i>
                        Book Appointment
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Doctor Details --}}
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                {{-- About Section --}}
                @if($doctor->bio)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-user-circle"></i>
                            About Doctor
                        </h2>
                        <p style="line-height: 1.8; color: #555;">
                            {{ $doctor->bio }}
                        </p>
                    </div>
                @endif

                {{-- Qualifications --}}
                @if($doctor->qualifications)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-graduation-cap"></i>
                            Education & Qualifications
                        </h2>
                        <ul class="qualifications-list">
                            @foreach(explode(',', $doctor->qualifications) as $qualification)
                                <li>
                                    <i class="fas fa-certificate"></i>
                                    <span>{{ trim($qualification) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Workplaces --}}
                @if($workplaces->count() > 0)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-hospital"></i>
                            Practice Locations
                        </h2>
                        @foreach($workplaces as $workplace)
                            @php
                                $workplaceName = 'Not Available';
                                $workplaceAddress = 'Address not available';
                                $workplaceCity = 'N/A';
                                $workplaceLink = '#';
                                $workplaceType = $workplace->workplace_type;

                                if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                                    $workplaceName = $workplace->hospital->name;
                                    $workplaceAddress = $workplace->hospital->address ?? 'Address not available';
                                    $workplaceCity = $workplace->hospital->city ?? 'N/A';
                                    $workplaceLink = route('patient.hospitals.show', $workplace->hospital->id);
                                } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                                    $workplaceName = $workplace->medicalCentre->name;
                                    $workplaceAddress = $workplace->medicalCentre->address ?? 'Address not available';
                                    $workplaceCity = $workplace->medicalCentre->city ?? 'N/A';
                                    $workplaceLink = route('patient.medical-centres.show', $workplace->medicalCentre->id);
                                }
                            @endphp

                            <div class="workplace-card">
                                <div class="workplace-header">
                                    <div>
                                        <h3 class="workplace-name">{{ $workplaceName }}</h3>
                                    </div>
                                    <span class="workplace-type">{{ str_replace('_', ' ', $workplaceType) }}</span>
                                </div>
                                <div class="workplace-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $workplaceAddress }}</span>
                                </div>
                                <div class="workplace-info">
                                    <i class="fas fa-city"></i>
                                    <span>{{ $workplaceCity }}</span>
                                </div>
                                @if($workplaceLink != '#')
                                    <a href="{{ $workplaceLink }}" class="view-workplace-link">
                                        View Details
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Reviews --}}
                @if($reviews->count() > 0)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-comments"></i>
                            Patient Reviews ({{ $reviews->count() }})
                        </h2>
                        @foreach($reviews as $review)
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            @php
                                                $reviewerImage = $review->patient && $review->patient->user && $review->patient->user->profile_image
                                                    ? asset('storage/' . $review->patient->user->profile_image)
                                                    : asset('images/default-avatar.png');
                                            @endphp
                                            <img src="{{ $reviewerImage }}"
                                                 alt="Reviewer"
                                                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        </div>
                                        <div>
                                            <div class="reviewer-name">
                                                {{ $review->patient && $review->patient->user
                                                    ? $review->patient->user->name
                                                    : 'Anonymous Patient' }}
                                            </div>
                                            <div class="review-date">
                                                {{ $review->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = $review->rating; $i < 5; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->review)
                                    <p class="review-text">{{ $review->review }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="section-card">
                    <h2 class="section-title">
                        <i class="fas fa-id-card"></i>
                        Professional Information
                    </h2>
                    <div class="info-grid" style="grid-template-columns: 1fr;">
                        @if($doctor->slmc_number)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="info-content">
                                    <h4>SLMC Registration</h4>
                                    <p>{{ $doctor->slmc_number }}</p>
                                </div>
                            </div>
                        @endif

                        @if($doctor->phone)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Contact Number</h4>
                                    <p>{{ $doctor->phone }}</p>
                                </div>
                            </div>
                        @endif

                        @if($doctor->experience_years)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Experience</h4>
                                    <p>{{ $doctor->experience_years }} {{ Str::plural('year', $doctor->experience_years) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="info-content">
                                <h4>Status</h4>
                                <p>{{ $doctor->user && $doctor->user->status == 'active' ? 'Available' : 'Unavailable' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card" style="background: linear-gradient(135deg, rgba(66, 166, 73, 0.05) 0%, rgba(66, 166, 73, 0.1) 100%); border: 2px solid rgba(66, 166, 73, 0.2);">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-check"></i>
                        Book Your Appointment
                    </h2>
                    <p style="font-size: 0.9rem; color: #555; margin-bottom: 1.5rem;">
                        Get professional medical consultation from Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.
                    </p>
                    <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                       class="book-appointment-btn"
                       style="width: 100%; justify-content: center; margin-top: 0;">
                        <i class="fas fa-calendar-plus"></i>
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Include Footer --}}
@include('partials.footer')
