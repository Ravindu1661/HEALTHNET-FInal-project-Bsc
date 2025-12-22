{{-- Include Header --}}
@include('partials.header')

<style>
/* Profile Page Styles */
.profile-header {
    background: linear-gradient(135deg, #42a649 0%, #2d8636 100%);
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

.centre-profile-header {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-top: -2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.centre-main-info {
    display: flex;
    gap: 2rem;
    align-items: start;
}

.centre-logo {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    border: 3px solid #42a649;
    flex-shrink: 0;
}

.centre-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.centre-details {
    flex: 1;
}

.centre-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #42a649;
    margin-bottom: 0.5rem;
}

.centre-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge-verified {
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: #d4edda;
    color: #155724;
}

.centre-meta {
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
    color: #42a649;
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
    color: #42a649;
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
    background: rgba(66, 166, 73, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #42a649;
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
    background: rgba(66, 166, 73, 0.05);
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
    color: #42a649;
    font-size: 0.8rem;
}

.owner-doctor-card {
    background: linear-gradient(135deg, rgba(66, 166, 73, 0.05) 0%, rgba(66, 166, 73, 0.1) 100%);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1.2rem;
    align-items: center;
    border: 2px solid rgba(66, 166, 73, 0.2);
}

.owner-doctor-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #42a649;
    flex-shrink: 0;
}

.owner-doctor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.owner-doctor-info h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #42a649;
    margin-bottom: 0.3rem;
}

.owner-doctor-info p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.view-doctor-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: #42a649;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.4rem 1rem;
    background: white;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.view-doctor-btn:hover {
    background: #42a649;
    color: white;
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
    border: 2px solid #42a649;
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
    color: #42a649;
    margin-bottom: 0.2rem;
}

.doctor-mini-info p {
    font-size: 0.75rem;
    color: #666;
    margin: 0;
}

.view-doctor-link {
    color: #42a649;
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
    .centre-main-info {
        flex-direction: column;
    }

    .centre-logo {
        width: 100px;
        height: 100px;
    }

    .centre-title {
        font-size: 1.4rem;
    }

    .info-grid,
    .specializations-list,
    .facilities-list,
    .doctors-grid {
        grid-template-columns: 1fr;
    }

    .owner-doctor-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

{{-- Profile Header --}}
<section class="profile-header">
    <div class="container">
        <a href="{{ route('patient.medical-centres') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Medical Centres
        </a>
    </div>
</section>

{{-- Medical Centre Profile --}}
<section>
    <div class="container">
        <div class="centre-profile-header">
            <div class="centre-main-info">
                <div class="centre-logo">
                    <img src="{{ $medicalCentre->image_url }}"
                         alt="{{ $medicalCentre->name }}"
                         onerror="this.src='{{ asset('images/default-medical-centre.png') }}'">
                </div>

                <div class="centre-details">
                    <h1 class="centre-title">{{ $medicalCentre->name }}</h1>

                    <div class="centre-badges">
                        @if($medicalCentre->status == 'approved')
                            <span class="badge-verified">
                                <i class="fas fa-check-circle"></i>
                                Verified Medical Centre
                            </span>
                        @endif
                    </div>

                    <div class="centre-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $medicalCentre->city ?? 'N/A' }}</span>
                        </div>

                        <div class="meta-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $medicalCentre->phone ?? 'N/A' }}</span>
                        </div>

                        <div class="rating-display">
                            <div class="stars">
                                @php
                                    $rating = $medicalCentre->rating ?? 0;
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
                            <span>{{ number_format($rating, 1) }} ({{ $medicalCentre->total_ratings ?? 0 }} reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Medical Centre Information --}}
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                {{-- About Section --}}
                <div class="section-card">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        About Medical Centre
                    </h2>
                    <p style="line-height: 1.8; color: #555;">
                        {{ $medicalCentre->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Owner Doctor --}}
                @if($medicalCentre->ownerDoctor)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-user-md"></i>
                            Medical Director
                        </h2>
                        <div class="owner-doctor-card">
                            <div class="owner-doctor-avatar">
                                <img src="{{ $medicalCentre->ownerDoctor->image_url }}"
                                     alt="Dr. {{ $medicalCentre->ownerDoctor->first_name }}"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                            <div class="owner-doctor-info">
                                <h3>Dr. {{ $medicalCentre->ownerDoctor->first_name }} {{ $medicalCentre->ownerDoctor->last_name }}</h3>
                                <p>{{ $medicalCentre->ownerDoctor->specialization ?? 'General Practitioner' }}</p>
                                @if($medicalCentre->ownerDoctor->experience_years)
                                    <p style="font-size: 0.8rem; color: #999;">
                                        <i class="fas fa-briefcase"></i>
                                        {{ $medicalCentre->ownerDoctor->experience_years }} years experience
                                    </p>
                                @endif
                                <a href="{{ route('patient.doctors.show', $medicalCentre->ownerDoctor->id) }}"
                                   class="view-doctor-btn">
                                    View Profile
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

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
                                <div class="doctor-mini-card">
                                    <div class="doctor-mini-avatar">
                                        <img src="{{ $doctor->image_url }}"
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
                                <p>{{ $medicalCentre->address ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-city"></i>
                            </div>
                            <div class="info-content">
                                <h4>City</h4>
                                <p>{{ $medicalCentre->city ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h4>Phone</h4>
                                <p>{{ $medicalCentre->phone ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($medicalCentre->email)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Email</h4>
                                    <p>{{ $medicalCentre->email }}</p>
                                </div>
                            </div>
                        @endif

                        @if($medicalCentre->website)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Website</h4>
                                    <p>
                                        <a href="{{ $medicalCentre->website }}"
                                           target="_blank"
                                           style="color: #42a649;">
                                            Visit Website
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if($medicalCentre->registration_number)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="info-content">
                                    <h4>Registration No.</h4>
                                    <p>{{ $medicalCentre->registration_number }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Operating Hours --}}
                @if($medicalCentre->operatinghours)
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-clock"></i>
                            Operating Hours
                        </h2>
                        <div style="color: #555; line-height: 1.8;">
                            {{ $medicalCentre->operatinghours }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Include Footer --}}
@include('partials.footer')
