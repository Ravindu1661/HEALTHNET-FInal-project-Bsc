@include('partials.header')
{{-- Hero Section - Authenticated Users Only --}}
<section class="hero-section-auth" id="home" style="background-image: url('{{ asset('images/Hero.jpg') }}');">
    {{-- Dark Overlay --}}
    <div class="hero-overlay-auth"></div>

    {{-- Animated Particles --}}
    <div class="hero-particles-minimal">
        <div class="particle-dot dot-1"></div>
        <div class="particle-dot dot-2"></div>
        <div class="particle-dot dot-3"></div>
        <div class="particle-dot dot-4"></div>
    </div>

    <div class="hero-content-container">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                {{-- Left Content Column --}}
                <div class="col-lg-7">
                    <div class="hero-text-block">
                        {{-- Welcome Badge --}}
                        <div class="hero-badge-welcome">
                            <i class="fas fa-check-circle"></i>
                            <span>Welcome Back!</span>
                        </div>

                        {{-- Main Title --}}
                        <h1 class="hero-heading-main">
                            Hello, <span class="highlight-name">{{ auth()->user()->patient->first_name }}</span>! 👋
                        </h1>

                        {{-- Subtitle --}}
                        <p class="hero-subheading">
                            Your health journey continues with <strong class="email-highlight">{{ Auth::user()->email }}</strong>
                        </p>

                        {{-- Description --}}
                        <p class="hero-description">
                            Access your dashboard to manage appointments, view medical records, and connect with healthcare providers across Sri Lanka.
                        </p>

                        {{-- Action Buttons --}}
                        <div class="hero-buttons-group">
                            <a href="{{ route('patient.dashboard') }}" class="hero-btn hero-btn-primary">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Go to Dashboard</span>
                            </a>
                            <a href="#services" class="hero-btn hero-btn-secondary">
                                <i class="fas fa-stethoscope"></i>
                                <span>Explore Services</span>
                            </a>
                        </div>

                        {{-- Quick Access Features --}}
                        <div class="hero-features-quick">
                            <div class="quick-feature">
                                <i class="fas fa-calendar-check"></i>
                                <span>Appointments</span>
                            </div>
                            <div class="quick-feature">
                                <i class="fas fa-file-medical"></i>
                                <span>Medical Records</span>
                            </div>
                            <div class="quick-feature">
                                <i class="fas fa-pills"></i>
                                <span>Pharmacy</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Visual Column with Glassmorphism Cards --}}
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="hero-visual-area">
                        {{-- Floating Feature Card 1 --}}
                        <div class="feature-card feature-card-1">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon feature-icon-heart">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>24/7 Healthcare</h4>
                                <p>Always Available</p>
                            </div>
                        </div>

                        {{-- Floating Feature Card 2 --}}
                        <div class="feature-card feature-card-2">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon feature-icon-shield">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>Secure & Private</h4>
                                <p>Data Protected</p>
                            </div>
                        </div>

                        {{-- Floating Feature Card 3 --}}
                        <div class="feature-card feature-card-3">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon feature-icon-activity">
                                    <i class="fas fa-activity"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>Health Tracking</h4>
                                <p>Real-time Updates</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="hero-scroll-hint">
        <div class="scroll-mouse">
            <div class="scroll-wheel"></div>
        </div>
        <p class="scroll-text">Scroll Down</p>
    </div>
</section>


{{-- Services Section - Center Custom Image Design --}}
<section class="services-center-design" id="services">
    <div class="container">
        {{-- Section Header --}}
        <div class="services-header-center">
            <h2 class="services-main-title">Our Services</h2>
            <div class="title-underline"></div>
            <p class="services-main-desc">
                Learn more about the various services offered by Our Clinic and how we provide you with world-class care.
            </p>
        </div>

        {{-- Services Layout Grid --}}
        <div class="services-layout-wrapper">

            {{-- Left Column Services --}}
            <div class="services-column services-left">

                {{-- Service Item 1 - Find Doctors --}}
                <div class="service-item-center" data-animate="fade-right">
                    <div class="service-content-box service-align-right">
                        <h3 class="service-heading">Find Doctors</h3>
                        <p class="service-description">
                            Connect with qualified specialists and general practitioners in your area with our comprehensive doctor directory.
                        </p>
                        <a href="{{ route('patient.doctors') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-icon-circle icon-blue">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>

                {{-- Service Item 2 - Book Appointments --}}
                <div class="service-item-center" data-animate="fade-right" data-delay="200">
                    <div class="service-content-box service-align-right">
                        <h3 class="service-heading">Book Appointments</h3>
                        <p class="service-description">
                            Schedule appointments with doctors online seamlessly. Sign up today to access our booking system.
                        </p>
                        <a href="{{ route('signup') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-icon-circle icon-orange">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>

                {{-- Service Item 3 - Health Records --}}
                <div class="service-item-center" data-animate="fade-right" data-delay="400">
                    <div class="service-content-box service-align-right">
                        <h3 class="service-heading">Health Records</h3>
                        <p class="service-description">
                            Maintain digital medical records securely with our advanced health information management system.
                        </p>
                        <a href="{{ route('patient.health-portfolio') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-icon-circle icon-indigo">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>
            </div>

            {{-- Center Doctor Image - Custom Background Removed --}}
            <div class="services-center-image">
                <div class="doctor-image-wrapper-custom">
                    {{-- Replace this src with your background-removed image path --}}
                    <img src="{{ asset('images/center-doctor2.png') }}"
                         alt="Healthcare Professional"
                         class="doctor-custom-image">

                    {{-- Decorative Elements --}}
                    <div class="image-glow-effect-custom"></div>
                    <div class="circular-gradient-bg"></div>
                </div>
            </div>

            {{-- Right Column Services --}}
            <div class="services-column services-right">

                {{-- Service Item 4 - Hospitals & Clinics --}}
                <div class="service-item-center" data-animate="fade-left">
                    <div class="service-icon-circle icon-green">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="service-content-box service-align-left">
                        <h3 class="service-heading">Hospitals & Clinics</h3>
                        <p class="service-description">
                            Locate nearby hospitals, dispensaries and healthcare centers with our integrated facility finder.
                        </p>
                        <a href="{{ route('patient.hospitals') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Service Item 5 - Laboratory Testing --}}
                <div class="service-item-center" data-animate="fade-left" data-delay="200">
                    <div class="service-icon-circle icon-teal">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="service-content-box service-align-left">
                        <h3 class="service-heading">Laboratory Testing</h3>
                        <p class="service-description">
                            Access laboratories and medical test reports online with real-time result notifications.
                        </p>
                        <a href="{{ route('patient.laboratories') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Service Item 6 - Online Pharmacy --}}
                <div class="service-item-center" data-animate="fade-left" data-delay="400">
                    <div class="service-icon-circle icon-red">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="service-content-box service-align-left">
                        <h3 class="service-heading">Online Pharmacy</h3>
                        <p class="service-description">
                            Order medicines online and get home delivery service with prescription verification.
                        </p>
                        <a href="{{ route('patient.pharmacies') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Quick Dashboard Section - After Hero --}}
<section class="quick-dashboard-section" >
    <div class="container">
        <div class="dashboard-cards-grid">
            {{-- Card 1: Book Appointment --}}
            <div class="dashboard-quick-card card-blue" data-aos="fade-up">
                <div class="card-icon-large">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="card-info">
                    <h3>Book Appointment</h3>
                    <p>Schedule with specialists</p>
                </div>
                <a href="{{ route('patient.appointments.create') }}" class="card-arrow-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 2: Find Doctors --}}
            <div class="dashboard-quick-card card-green" data-aos="fade-up" data-aos-delay="100">
                <div class="card-icon-large">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="card-info">
                    <h3>Find Doctors</h3>
                    <p>Search by specialization</p>
                </div>
                <a href="{{ route('patient.doctors') }}" class="card-arrow-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 3: Lab Tests --}}
            <div class="dashboard-quick-card card-purple" data-aos="fade-up" data-aos-delay="200">
                <div class="card-icon-large">
                    <i class="fas fa-vial"></i>
                </div>
                <div class="card-info">
                    <h3>Lab Tests</h3>
                    <p>Book diagnostic tests</p>
                </div>
                <a href="{{ route('patient.laboratories') }}" class="card-arrow-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 4: Order Medicine --}}
            <div class="dashboard-quick-card card-orange" data-aos="fade-up" data-aos-delay="300">
                <div class="card-icon-large">
                    <i class="fas fa-capsules"></i>
                </div>
                <div class="card-info">
                    <h3>Order Medicine</h3>
                    <p>Online pharmacy delivery</p>
                </div>
                <a href="{{ route('patient.pharmacies') }}" class="card-arrow-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Featured Doctors Slider Section --}}
<section class="featured-doctors-section" id="featured-doctors">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="section-label">Expert Doctors</span>
                <h2 class="section-title">Meet Our Specialists</h2>
                <p class="section-desc">Consult with highly qualified and experienced doctors</p>
            </div>
            <a href="{{ route('patient.doctors') }}" class="btn-view-all-link">
                <span>View All Doctors</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="carousel-wrap" data-carousel="doctors">
            <button type="button" class="carousel-btn carousel-prev" data-carousel-prev="doctors" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="doctors-slider-wrapper" id="carousel-doctors">
                <div class="doctors-slider carousel-track">
                    @forelse(($featuredDoctors ?? collect()) as $doctor)
                        @php
                            $profileImage = $doctor->profile_image
                                ? asset('storage/' . $doctor->profile_image)
                                : asset('images/default-avatar.png');
                        @endphp

                        <div class="doctor-card carousel-slide" data-aos="fade-up">
                            <div class="doctor-image-wrapper">
                                <img
                                    src="{{ $profileImage }}"
                                    alt="Dr. {{ $doctor->first_name ?? '' }} {{ $doctor->last_name ?? '' }}"
                                    onerror="this.src='{{ asset('images/default-avatar.png') }}'">

                                <div class="doctor-status-badge">
                                    <span class="status-dot"></span>
                                    Available
                                </div>
                            </div>

                            <div class="doctor-card-content">
                                <h3>Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? 'Doctor' }}</h3>
                                <p class="doctor-specialty">{{ $doctor->specialization ?? 'General Practitioner' }}</p>

                                <div class="doctor-meta">
                                    <span>
                                        <i class="fas fa-briefcase"></i>
                                        {{ $doctor->experience_years ?? 0 }} Years
                                    </span>
                                </div>

                                <div class="doctor-rating">
                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <span class="rating-text">{{ number_format((float)($doctor->rating ?? 0), 1) }}</span>
                                    </div>
                                </div>

                                <div class="doctor-fee">
                                    <span class="fee-label">Consultation</span>
                                    <span class="fee-amount">LKR {{ number_format((float)($doctor->consultation_fee ?? 0)) }}</span>
                                </div>

                                <a href="{{ route('patient.doctors.show', $doctor->id) }}" class="btn-book-doctor">
                                    <i class="fas fa-calendar-check"></i>
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @empty
                        <p style="color:#64748b; font-weight:600;">No doctors found.</p>
                    @endforelse
                </div>
            </div>

            <button type="button" class="carousel-btn carousel-next" data-carousel-next="doctors" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>


{{-- Healthcare Facilities Section --}}
<section class="facilities-section">
    <div class="container">
        <div class="section-header-center">
            <span class="section-label">Healthcare Network</span>
            <h2 class="section-title">Trusted Healthcare Facilities</h2>
            <p class="section-desc">Access quality healthcare services across Sri Lanka</p>
        </div>

        <div class="facilities-tabs">
            <button class="facility-tab active" data-tab="hospitals">
                <i class="fas fa-hospital"></i><span>Hospitals</span>
            </button>
            <button class="facility-tab" data-tab="medical-centres">
                <i class="fas fa-clinic-medical"></i><span>Medical Centres</span>
            </button>
        </div>

        {{-- Hospitals carousel --}}
        <div class="carousel-wrap tab-panel active" data-tab-panel="hospitals" data-carousel="hospitals">
            <button type="button" class="carousel-btn carousel-prev" data-carousel-prev="hospitals" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-hospitals">
                @forelse(($featuredHospitals ?? collect()) as $hospital)
                    <div class="facility-card carousel-slide" data-aos="fade-up">
                        <div class="facility-image">
                            <img src="{{ $hospital->image_url ?? asset('images/default-hospital.png') }}" alt="Hospital">
                            <div class="facility-type-badge">
                                {{ ucfirst($hospital->hospital_type ?? 'Hospital') }}
                            </div>
                        </div>
                        <div class="facility-content">
                            <h3>{{ $hospital->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $hospital->address ?? 'Sri Lanka' }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ number_format((float)$hospital->rating, 1) }}</span>
                                </div>
                                <a href="{{ route('patient.hospitals.show', $hospital->id) }}" class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="color:#fff;">No hospitals found.</p>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next" data-carousel-next="hospitals" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- Medical centres carousel --}}
        <div class="carousel-wrap tab-panel" data-tab-panel="medical-centres" data-carousel="medical-centres">
            <button type="button" class="carousel-btn carousel-prev" data-carousel-prev="medical-centres" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-medical-centres">
                @forelse(($featuredMedicalCentres ?? collect()) as $mc)
                    <div class="facility-card carousel-slide" data-aos="fade-up">
                        <div class="facility-image">
                            <img src="{{ $mc->image_url ?? asset('images/default-medical-centre.png') }}" alt="Medical Centre">
                            <div class="facility-type-badge">Medical Centre</div>
                        </div>
                        <div class="facility-content">
                            <h3>{{ $mc->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $mc->address ?? 'Sri Lanka' }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ number_format((float)$mc->rating, 1) }}</span>
                                </div>
                                <a href="{{ route('patient.medical-centres.show', $mc->id) }}" class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="color:#fff;">No medical centres found.</p>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next" data-carousel-next="medical-centres" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="facilities-view-all">
            <a href="{{ route('patient.hospitals') }}" class="btn-view-all-facilities">
                <span>Explore All Facilities</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- Health Events & Campaigns Section --}}
<section class="health-events-section">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="section-label">What's Happening</span>
                <h2 class="section-title">Health Events & Campaigns</h2>
                <p class="section-desc">Join health camps and awareness programs near you</p>
            </div>
        </div>

        <div class="events-grid-modern">
            @php
                $announcements = $activeAnnouncements ?? [];
            @endphp

            @if(count($announcements) > 0)
                @foreach($announcements as $announcement)
                    @if($announcement && is_object($announcement))
                        @php
                            // (keeping original logic unchanged)
                            $announcementTitle = $announcement->title ?? 'Untitled Event';
                            $announcementContent = $announcement->content ?? 'No description available';
                            $announcementType = $announcement->announcement_type ?? 'general';
                            $imagePath = $announcement->image_path ?? null;
                            $startDate = $announcement->start_date ?? null;
                            $endDate = $announcement->end_date ?? null;
                            $publisherType = $announcement->publisher_type ?? 'admin';

                            if ($startDate) {
                                try {
                                    $displayDate = \Carbon\Carbon::parse($startDate);
                                } catch (\Exception $e) {
                                    $displayDate = now();
                                }
                            } else {
                                $displayDate = now();
                            }

                            $badgeColorClass = 'date-badge-blue';
                            if (in_array($announcementType, ['healthcamp', 'awareness'])) {
                                $badgeColorClass = 'date-badge-blue';
                            } elseif (in_array($announcementType, ['specialoffer', 'newservice'])) {
                                $badgeColorClass = 'date-badge-purple';
                            } elseif ($announcementType === 'emergency') {
                                $badgeColorClass = 'date-badge-red';
                            }

                            $typeBadgeClass = 'event-type-blue';
                            if (in_array($announcementType, ['healthcamp', 'awareness'])) {
                                $typeBadgeClass = 'event-type-blue';
                            } elseif (in_array($announcementType, ['specialoffer', 'newservice'])) {
                                $typeBadgeClass = 'event-type-purple';
                            } elseif ($announcementType === 'emergency') {
                                $typeBadgeClass = 'event-type-red';
                            } elseif ($announcementType === 'general') {
                                $typeBadgeClass = 'event-type-gray';
                            }

                            $placeholderColors = [
                                'healthcamp' => '10b981',
                                'awareness' => '3b82f6',
                                'specialoffer' => '8b5cf6',
                                'emergency' => 'ef4444'
                            ];
                            $placeholderColor = $placeholderColors[$announcementType] ?? '4299e1';

                            $typeDisplayName = ucwords(str_replace('_', ' ', $announcementType));

                            $modalData = [
                                'title' => str_replace("'", "\\'", $announcementTitle),
                                'content' => str_replace("'", "\\'", $announcementContent),
                                'image' => $imagePath ? asset('storage/' . $imagePath) : '',
                                'date' => $displayDate->format('M d, Y'),
                                'type' => $typeDisplayName
                            ];
                        @endphp

                        <div class="event-card-modern" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            {{-- Card Image --}}
                            <div class="event-card-image">
                                @if($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}"
                                         alt="{{ $announcementTitle }}"
                                         onerror="this.src='https://via.placeholder.com/400x250/{{ $placeholderColor }}/ffffff?text=Health+Event'">
                                @else
                                    <img src="https://via.placeholder.com/400x250/{{ $placeholderColor }}/ffffff?text={{ urlencode(substr($announcementTitle, 0, 20)) }}"
                                         alt="{{ $announcementTitle }}">
                                @endif

                                <div class="event-date-badge-overlay {{ $badgeColorClass }}">
                                    <div class="badge-day">{{ $displayDate->format('d') }}</div>
                                    <div class="badge-month">{{ strtoupper($displayDate->format('M')) }}</div>
                                </div>

                                {{-- Event Type Badge --}}
                                <div class="event-type-badge {{ $typeBadgeClass }}">
                                    @if($announcementType === 'healthcamp')
                                        <i class="fas fa-heartbeat"></i>
                                    @elseif($announcementType === 'awareness')
                                        <i class="fas fa-info-circle"></i>
                                    @elseif($announcementType === 'specialoffer')
                                        <i class="fas fa-tag"></i>
                                    @elseif($announcementType === 'newservice')
                                        <i class="fas fa-star"></i>
                                    @elseif($announcementType === 'emergency')
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @else
                                        <i class="fas fa-bullhorn"></i>
                                    @endif
                                    <span>{{ $typeDisplayName }}</span>
                                </div>
                            </div>

                            {{-- Card Content --}}
                            <div class="event-card-content">
                                <h3 class="event-title-modern">{{ $announcementTitle }}</h3>
                                <p class="event-description-modern">
                                    {{ strlen($announcementContent) > 100 ? substr($announcementContent, 0, 100) . '...' : $announcementContent }}
                                </p>

                                <div class="event-info-modern">
                                    @if($startDate && $endDate)
                                        <div class="event-info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>
                                                {{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                                                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                                            </span>
                                        </div>
                                    @elseif($startDate)
                                        <div class="event-info-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <button type="button"
                                        class="btn-register-modern"
                                        data-title="{{ $modalData['title'] }}"
                                        data-content="{{ $modalData['content'] }}"
                                        data-image="{{ $modalData['image'] }}"
                                        data-date="{{ $modalData['date'] }}"
                                        data-type="{{ $modalData['type'] }}"
                                        onclick="openEventModal(this)">
                                    View Details
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted">No Events Available</h5>
                    <p class="text-muted">No health events or campaigns are currently scheduled.</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Event Details Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">
                    <i class="fas fa-bullhorn"></i>
                    <span id="modalTitle">Event Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalImageContainer" style="display: none; margin-bottom: 1.5rem;">
                    <img id="modalImage" src="" alt="Event" style="width: 100%; border-radius: 12px; max-height: 350px; object-fit: cover;">
                </div>

                {{-- Event Meta Info --}}
                <div class="modal-event-meta" style="margin-bottom: 1.5rem;">
                    <div id="modalTypeContainer" style="margin-bottom: 1rem;">
                        <span id="modalTypeBadge" class="badge" style="font-size: 0.85rem; padding: 0.5rem 1rem;"></span>
                    </div>

                    <div style="color: #64748b; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar" style="width: 20px;"></i>
                        <span id="modalDateText"></span>
                    </div>
                </div>

                <div id="modalContent" style="line-height: 1.8; color: #475569; white-space: pre-wrap;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Event modal script loaded');
});

function openEventModal(button) {
    try {
        const title = button.getAttribute('data-title') || 'Event Details';
        const content = button.getAttribute('data-content') || 'No description available';
        const imagePath = button.getAttribute('data-image') || '';
        const date = button.getAttribute('data-date') || '';
        const type = button.getAttribute('data-type') || '';

        console.log('Opening modal with:', {title, content, imagePath, date, type});

        // Set modal content
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const modalDateText = document.getElementById('modalDateText');
        const modalTypeBadge = document.getElementById('modalTypeBadge');
        const modalImageContainer = document.getElementById('modalImageContainer');
        const modalImage = document.getElementById('modalImage');

        if (modalTitle) modalTitle.textContent = title;
        if (modalContent) modalContent.textContent = content;
        if (modalDateText) modalDateText.textContent = date;

        // Set type badge with color
        if (modalTypeBadge && type) {
            modalTypeBadge.textContent = type;

            // Set badge color based on type
            const typeLower = type.toLowerCase();
            if (typeLower.includes('health') || typeLower.includes('awareness')) {
                modalTypeBadge.style.background = 'linear-gradient(135deg, #0066cc, #0052a3)';
                modalTypeBadge.style.color = 'white';
            } else if (typeLower.includes('offer') || typeLower.includes('service')) {
                modalTypeBadge.style.background = 'linear-gradient(135deg, #8b5cf6, #7c3aed)';
                modalTypeBadge.style.color = 'white';
            } else if (typeLower.includes('emergency')) {
                modalTypeBadge.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
                modalTypeBadge.style.color = 'white';
            } else {
                modalTypeBadge.style.background = '#64748b';
                modalTypeBadge.style.color = 'white';
            }
        }

        // Handle image
        if (modalImage && modalImageContainer) {
            if (imagePath && imagePath.trim() !== '') {
                modalImage.src = imagePath;
                modalImageContainer.style.display = 'block';
            } else {
                modalImageContainer.style.display = 'none';
            }
        }

        // Show modal
        const modalElement = document.getElementById('eventModal');
        if (modalElement) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal opened successfully (Bootstrap 5)');
            } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                jQuery('#eventModal').modal('show');
                console.log('Modal opened successfully (Bootstrap 4)');
            } else {
                console.error('Bootstrap Modal library not found');
                alert('Modal system not available. Please refresh the page.');
            }
        } else {
            console.error('Modal element #eventModal not found in DOM');
        }

    } catch (error) {
        console.error('Error opening modal:', error);
        alert('Error displaying event details. Please try again.');
    }
}
</script>



{{-- Health Tips Section --}}
<section class="health-tips-section" id="health-tips">
    <div class="container">
        <div class="section-header-center">
            <span class="section-label">Stay Informed</span>
            <h2 class="section-title">Health Tips & Wellness</h2>
            <p class="section-desc">Expert advice for a healthier lifestyle</p>
        </div>

        <div class="tips-grid">
            {{-- Tip Card 1 --}}
            <div class="tip-card" data-aos="flip-left">
                <div class="tip-icon tip-icon-red">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3>Heart Health</h3>
                <p>Regular exercise and a balanced diet can reduce heart disease risk by 30%</p>
                <a href="#" class="tip-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>

            {{-- Tip Card 2 --}}
            <div class="tip-card" data-aos="flip-left" data-aos-delay="100">
                <div class="tip-icon tip-icon-blue">
                    <i class="fas fa-tint"></i>
                </div>
                <h3>Stay Hydrated</h3>
                <p>Drink 8-10 glasses of water daily to maintain optimal body functions</p>
                <a href="#" class="tip-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>

            {{-- Tip Card 3 --}}
            <div class="tip-card" data-aos="flip-left" data-aos-delay="200">
                <div class="tip-icon tip-icon-green">
                    <i class="fas fa-bed"></i>
                </div>
                <h3>Quality Sleep</h3>
                <p>Get 7-8 hours of sleep each night for better mental and physical health</p>
                <a href="#" class="tip-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>

            {{-- Tip Card 4 --}}
            <div class="tip-card" data-aos="flip-left" data-aos-delay="300">
                <div class="tip-icon tip-icon-orange">
                    <i class="fas fa-apple-alt"></i>
                </div>
                <h3>Healthy Diet</h3>
                <p>Include fruits, vegetables, and whole grains in your daily meals</p>
                <a href="#" class="tip-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- Emergency Help Section --}}
<section class="emergency-help-section" id="emergency-help">
    <div class="container">
        <div class="emergency-help-card" data-aos="zoom-in">
            <div class="emergency-icon-pulse">
                <div class="pulse-circle"></div>
                <div class="pulse-circle pulse-delay"></div>
                <i class="fas fa-ambulance"></i>
            </div>
            <div class="emergency-help-text">
                <h2>Need Emergency Medical Help?</h2>
                <p>Our 24/7 emergency services are ready to assist you anytime, anywhere</p>
            </div>
            <div class="emergency-help-buttons">
                <a href="tel:119" class="btn-emergency-primary">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <span>Call Ambulance</span>
                        <strong>119</strong>
                    </div>
                </a>
                <a href="tel:+94112345678" class="btn-emergency-secondary">
                    <i class="fas fa-headset"></i>
                    <div>
                        <span>HealthNet Hotline</span>
                        <strong>+94 11 234 5678</strong>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {

  function scrollCarousel(trackEl, direction = 1) {
    if (!trackEl) return;

    // slide by ~1 card + gap
    const firstSlide = trackEl.querySelector('.carousel-slide');
    const gap = 18;
    const step = firstSlide ? (firstSlide.getBoundingClientRect().width + gap) : 320;

    trackEl.scrollBy({ left: direction * step, behavior: 'smooth' });
  }

  // Prev/Next handlers
  document.querySelectorAll('[data-carousel-prev]').forEach(btn => {
    btn.addEventListener('click', () => {
      const key = btn.getAttribute('data-carousel-prev');
      const track = document.getElementById('carousel-' + key);
      scrollCarousel(track, -1);
    });
  });

  document.querySelectorAll('[data-carousel-next]').forEach(btn => {
    btn.addEventListener('click', () => {
      const key = btn.getAttribute('data-carousel-next');
      const track = document.getElementById('carousel-' + key);
      scrollCarousel(track, 1);
    });
  });

  // Tabs (Hospitals / Medical Centres)
  const tabs = document.querySelectorAll('.facility-tab');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.getAttribute('data-tab');

      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      panels.forEach(p => {
        p.classList.toggle('active', p.getAttribute('data-tab-panel') === target);
      });
    });
  });

});
</script>

@include('partials.footer')
{{-- before </body> --}}
{{-- @include('partials.chatbot-widget') --}}
