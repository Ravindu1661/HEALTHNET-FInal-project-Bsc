{{-- Include Header --}}
@include('partials.header')

<style>
/* Profile Page Styles */
.profile-header {
    background: linear-gradient(135deg, #0056a3 0%, #003d7a 100%);
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

.hospital-profile-header {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-top: -2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.hospital-main-info {
    display: flex;
    gap: 2rem;
    align-items: start;
}

.hospital-logo {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    border: 3px solid #0056a3;
    flex-shrink: 0;
}

.hospital-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hospital-details {
    flex: 1;
}

.hospital-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #0056a3;
    margin-bottom: 0.5rem;
}

.hospital-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge-verified,
.badge-type {
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.badge-verified {
    background: #d4edda;
    color: #155724;
}

.badge-type {
    background: #d1ecf1;
    color: #0c5460;
}

.hospital-meta {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #555;
}

.meta-item i {
    color: #0056a3;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars {
    color: #ffc107;
}

.content-section {
    padding: 2rem 0;
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #0056a3;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    font-size: 1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: start;
    gap: 0.7rem;
}

.info-icon {
    width: 35px;
    height: 35px;
    background: rgba(0, 86, 163, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0056a3;
    flex-shrink: 0;
}

.info-content h4 {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.2rem;
    font-weight: 500;
}

.info-content p {
    font-size: 0.9rem;
    color: #333;
    margin: 0;
    font-weight: 600;
}

.specializations-list,
.facilities-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.8rem;
}

.spec-item,
.facility-item {
    background: rgba(0, 86, 163, 0.05);
    padding: 0.7rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.spec-item i,
.facility-item i {
    color: #0056a3;
    font-size: 0.8rem;
}

.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.doctor-mini-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    gap: 1rem;
    align-items: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.doctor-mini-card:hover {
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.doctor-mini-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #0056a3;
    flex-shrink: 0;
}

.doctor-mini-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-mini-info h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #0056a3;
    margin-bottom: 0.2rem;
}

.doctor-mini-info p {
    font-size: 0.75rem;
    color: #666;
    margin: 0;
}

.view-doctor-link {
    color: #0056a3;
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.3rem;
    display: inline-block;
}

.view-doctor-link:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .hospital-main-info {
        flex-direction: column;
    }

    .hospital-logo {
        width: 100px;
        height: 100px;
    }

    .hospital-title {
        font-size: 1.4rem;
    }

    .info-grid,
    .specializations-list,
    .facilities-list,
    .doctors-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Profile Header --}}
<section class="profile-header">
    <div class="container">
        <a href="{{ route('patient.hospitals') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Hospitals
        </a>
    </div>
</section>

{{-- Hospital Profile --}}
<section>
    <div class="container">
        <div class="hospital-profile-header">
            <div class="hospital-main-info">
                <div class="hospital-logo">
                    {{-- Use the accessor from model --}}
                    <img src="{{ $hospital->image_url }}"
                        alt="{{ $hospital->name }}"
                        onerror="this.src='{{ asset('images/default-hospital.png') }}'">
                </div>


                <div class="hospital-details">
                    <h1 class="hospital-title">{{ $hospital->name }}</h1>

                    <div class="hospital-badges">
                        @if($hospital->status == 'approved')
                            <span class="badge-verified">
                                <i class="fas fa-check-circle"></i>
                                Verified Hospital
                            </span>
                        @endif

                        @if($hospital->type)
                            <span class="badge-type">
                                {{ ucfirst($hospital->type) }} Hospital
                            </span>
                        @endif
                    </div>

                    <div class="hospital-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $hospital->city ?? 'N/A' }}</span>
                        </div>

                        <div class="meta-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $hospital->phone ?? 'N/A' }}</span>
                        </div>

                        <div class="rating-display">
                            <div class="stars">
                                @php
                                    $rating = $hospital->rating ?? 0;
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
                            <span>{{ number_format($rating, 1) }} ({{ $hospital->total_ratings ?? 0 }} reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hospital Information --}}
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                {{-- About Section --}}
                <div class="section-card">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        About Hospital
                    </h2>
                    <p style="line-height: 1.8; color: #555;">
                        {{ $hospital->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Specializations --}}
                @if(count($specializations) > 0)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-stethoscope"></i>
                            Medical Specializations
                        </h2>
                        <div class="specializations-list">
                            @foreach($specializations as $spec)
                                <div class="spec-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $spec }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Facilities --}}
                @if(count($facilities) > 0)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-building"></i>
                            Facilities & Services
                        </h2>
                        <div class="facilities-list">
                            @foreach($facilities as $facility)
                                <div class="facility-item">
                                    <i class="fas fa-check"></i>
                                    <span>{{ $facility }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Doctors --}}
                @if($doctors->count() > 0)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-user-md"></i>
                            Our Doctors ({{ $doctors->count() }})
                        </h2>
                        <div class="doctors-grid">
                            @foreach($doctors as $doctor)
                                @php
                                    $profileImage = $doctor->profile_image
                                        ? asset('storage/' . $doctor->profile_image)
                                        : asset('images/default-avatar.png');
                                @endphp
                                <div class="doctor-mini-card">
                                    <div class="doctor-mini-avatar">
                                        <img src="{{ $profileImage }}"
                                             alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div class="doctor-mini-info">
                                        <h4>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h4>
                                        <p>{{ $doctor->specialization ?? 'General' }}</p>
                                        <a href="{{ route('patient.doctors.show', $doctor->id) }}"
                                           class="view-doctor-link">
                                            View Profile →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="section-card">
                    <h2 class="section-title">
                        <i class="fas fa-address-card"></i>
                        Contact Information
                    </h2>
                    <div class="info-grid" style="grid-template-columns: 1fr;">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h4>Address</h4>
                                <p>{{ $hospital->address ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-city"></i>
                            </div>
                            <div class="info-content">
                                <h4>City</h4>
                                <p>{{ $hospital->city ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h4>Phone</h4>
                                <p>{{ $hospital->phone ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($hospital->email)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Email</h4>
                                    <p>{{ $hospital->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($hospital->website)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Website</h4>
                                    <p>
                                        <a href="{{ $hospital->website }}"
                                           target="_blank"
                                           style="color: #0056a3;">
                                            Visit Website
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Include Footer --}}
@include('partials.footer')
