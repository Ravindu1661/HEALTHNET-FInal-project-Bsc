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
                           <a href="{{ route('patient.health-portfolio') }}" class="hero-btn hero-btn-primary">
                                <i class="fas fa-notes-medical"></i>
                                <span>Health Portfolio</span>
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
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>Health Tracking</h4>
                                <p>Real-time Vitals</p>
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

                {{-- Service Item 2 - Find Medical Centres --}}
                <div class="service-item-center" data-animate="fade-right" data-delay="200">
                    <div class="service-content-box service-align-right">
                        <h3 class="service-heading">Find Medical Centres</h3>
                        <p class="service-description">
                            Discover trusted medical centres near you and book appointments with ease through our platform.
                        </p>
                        <a href="{{ route('patient.medical-centres') }}" class="service-learn-more">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="service-icon-circle icon-orange">
                        <i class="fas fa-hospital-alt"></i>
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

            <div class="doctors-slider-wrapper">
                <div class="doctors-slider carousel-track" id="carousel-doctors">
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

{{-- ════════════════════════════════════════════════
     Healthcare Facilities Section — Main_Home.blade.php
     Providers: Hospitals | Medical Centres | Labs | Pharmacies
════════════════════════════════════════════════ --}}
<section class="facilities-section">
    <div class="container">
        <div class="section-header-center">
            <span class="section-label">Healthcare Network</span>
            <h2 class="section-title">Trusted Healthcare Facilities</h2>
            <p class="section-desc">Access quality healthcare services across Sri Lanka</p>
        </div>

        {{-- ── Tabs ── --}}
        <div class="facilities-tabs">
            <button class="facility-tab active" data-tab="hospitals">
                <i class="fas fa-hospital"></i><span>Hospitals</span>
            </button>
            <button class="facility-tab" data-tab="medical-centres">
                <i class="fas fa-clinic-medical"></i><span>Medical Centres</span>
            </button>
            <button class="facility-tab" data-tab="laboratories">
                <i class="fas fa-flask"></i><span>Laboratories</span>
            </button>
            <button class="facility-tab" data-tab="pharmacies">
                <i class="fas fa-pills"></i><span>Pharmacies</span>
            </button>
        </div>

        {{-- ══ HOSPITALS ══ --}}
        <div class="carousel-wrap tab-panel active" data-tab-panel="hospitals" data-carousel="hospitals">
            <button type="button" class="carousel-btn carousel-prev"
                    data-carousel-prev="hospitals" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-hospitals">
                @forelse(($featuredHospitals ?? collect()) as $hospital)
                    @php
                        $hImg = $hospital->profile_image
                            ? asset('storage/' . $hospital->profile_image)
                            : asset('images/default-hospital.png');

                        $hType    = ucfirst($hospital->type ?? 'Hospital');
                        $hCity    = $hospital->city ?? ($hospital->address ?? 'Sri Lanka');
                        $hRating  = number_format((float)($hospital->rating ?? 0), 1);
                    @endphp
                    <div class="facility-card carousel-slide">
                        <div class="facility-image">
                            <img src="{{ $hImg }}"
                                 alt="{{ $hospital->name }}"
                                 onerror="this.src='{{ asset('images/default-hospital.png') }}'">
                            <div class="facility-type-badge">{{ $hType }}</div>
                        </div>
                        <div class="facility-content">
                            <h3>{{ $hospital->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $hCity }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $hRating }}</span>
                                </div>
                                <a href="{{ route('patient.hospitals.show', $hospital->id) }}"
                                   class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem;color:rgba(255,255,255,0.7);font-size:0.88rem;">
                        <i class="fas fa-hospital-alt" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.5;"></i>
                        No hospitals available at the moment.
                    </div>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next"
                    data-carousel-next="hospitals" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- ══ MEDICAL CENTRES ══ --}}
        <div class="carousel-wrap tab-panel" data-tab-panel="medical-centres" data-carousel="medical-centres">
            <button type="button" class="carousel-btn carousel-prev"
                    data-carousel-prev="medical-centres" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-medical-centres">
                @forelse(($featuredMedicalCentres ?? collect()) as $mc)
                    @php
                        $mcImg   = $mc->profile_image
                            ? asset('storage/' . $mc->profile_image)
                            : asset('images/default-medical-centre.png');

                        $mcCity   = $mc->city ?? ($mc->address ?? 'Sri Lanka');
                        $mcRating = number_format((float)($mc->rating ?? 0), 1);
                    @endphp
                    <div class="facility-card carousel-slide">
                        <div class="facility-image">
                            <img src="{{ $mcImg }}"
                                 alt="{{ $mc->name }}"
                                 onerror="this.src='{{ asset('images/default-medical-centre.png') }}'">
                            <div class="facility-type-badge">Medical Centre</div>
                        </div>
                        <div class="facility-content">
                            <h3>{{ $mc->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $mcCity }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $mcRating }}</span>
                                </div>
                                <a href="{{ route('patient.medical-centres.show', $mc->id) }}"
                                   class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem;color:rgba(255,255,255,0.7);font-size:0.88rem;">
                        <i class="fas fa-clinic-medical" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.5;"></i>
                        No medical centres available at the moment.
                    </div>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next"
                    data-carousel-next="medical-centres" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- ══ LABORATORIES ══ --}}
        <div class="carousel-wrap tab-panel" data-tab-panel="laboratories" data-carousel="laboratories">
            <button type="button" class="carousel-btn carousel-prev"
                    data-carousel-prev="laboratories" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-laboratories">
                @forelse(($featuredLaboratories ?? collect()) as $lab)
                    @php
                        $labImg   = $lab->profile_image
                            ? asset('storage/' . $lab->profile_image)
                            : asset('images/default-lab.png');

                        $labCity   = $lab->city ?? ($lab->address ?? 'Sri Lanka');
                        $labRating = number_format((float)($lab->rating ?? 0), 1);
                    @endphp
                    <div class="facility-card carousel-slide">
                        <div class="facility-image">
                            <img src="{{ $labImg }}"
                                 alt="{{ $lab->name }}"
                                 onerror="this.src='{{ asset('images/default-lab.png') }}'">
                            <div class="facility-type-badge" style="background:rgba(139,92,246,0.85);">
                                Laboratory
                            </div>
                        </div>
                        <div class="facility-content">
                            <h3>{{ $lab->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $labCity }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $labRating }}</span>
                                </div>
                                <a href="{{ route('patient.laboratories.show', $lab->id) }}"
                                   class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem;color:rgba(255,255,255,0.7);font-size:0.88rem;">
                        <i class="fas fa-flask" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.5;"></i>
                        No laboratories available at the moment.
                    </div>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next"
                    data-carousel-next="laboratories" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- ══ PHARMACIES ══ --}}
        <div class="carousel-wrap tab-panel" data-tab-panel="pharmacies" data-carousel="pharmacies">
            <button type="button" class="carousel-btn carousel-prev"
                    data-carousel-prev="pharmacies" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="facilities-grid carousel-track" id="carousel-pharmacies">
                @forelse(($featuredPharmacies ?? collect()) as $pharmacy)
                    @php
                        $phImg   = $pharmacy->profile_image
                            ? asset('storage/' . $pharmacy->profile_image)
                            : asset('images/default-pharmacy.png');

                        $phCity   = $pharmacy->city ?? ($pharmacy->address ?? 'Sri Lanka');
                        $phRating = number_format((float)($pharmacy->rating ?? 0), 1);
                        $phDelivery = $pharmacy->delivery_available ?? false;
                    @endphp
                    <div class="facility-card carousel-slide">
                        <div class="facility-image">
                            <img src="{{ $phImg }}"
                                 alt="{{ $pharmacy->name }}"
                                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
                            <div class="facility-type-badge" style="background:rgba(20,184,166,0.85);">
                                Pharmacy
                            </div>
                            @if($phDelivery)
                            <div style="position:absolute;top:0.6rem;left:0.6rem;
                                        background:rgba(34,197,94,0.9);color:#fff;
                                        font-size:0.6rem;font-weight:700;
                                        padding:0.18rem 0.55rem;border-radius:8px;">
                                <i class="fas fa-motorcycle"></i> Delivery
                            </div>
                            @endif
                        </div>
                        <div class="facility-content">
                            <h3>{{ $pharmacy->name }}</h3>
                            <p class="facility-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $phCity }}
                            </p>
                            <div class="facility-footer">
                                <div class="facility-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $phRating }}</span>
                                </div>
                                <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
                                   class="btn-facility-view">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem;color:rgba(255,255,255,0.7);font-size:0.88rem;">
                        <i class="fas fa-pills" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.5;"></i>
                        No pharmacies available at the moment.
                    </div>
                @endforelse
            </div>

            <button type="button" class="carousel-btn carousel-next"
                    data-carousel-next="pharmacies" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        {{-- ── View All ── --}}
        <div class="facilities-view-all">
            <a href="{{ route('patient.hospitals') }}" class="btn-view-all-facilities">
                <span>Explore All Facilities</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>
{{-- ════════════════════════════════════════════════
     Health Events & Campaigns Section
     Main_Home.blade.php ලෙකින් replace කරන්න:
     <section class="health-events-section"> ... </section>
     + modal + script
════════════════════════════════════════════════ --}}

<style>
/* ── Health Events Section ── */
.health-events-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #0d1b2e 0%, #1a3a5c 60%, #1f5fa6 100%);
    position: relative; overflow: hidden;
}
.health-events-section::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 60% at 90% 20%, rgba(66,166,73,.10), transparent),
        radial-gradient(ellipse 40% 40% at 10% 80%, rgba(42,100,200,.08), transparent);
    pointer-events: none;
}
.health-events-section .container { position: relative; z-index: 1; }

/* Header */
.section-header-flex {
    display: flex; align-items: flex-end;
    justify-content: space-between; flex-wrap: wrap;
    gap: 1rem; margin-bottom: 2.5rem;
}
.section-header-flex .section-label {
    display: inline-block;
    font-size: .72rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: #42a649;
    background: rgba(66,166,73,.15); border-radius: 20px;
    padding: .2rem .75rem; margin-bottom: .5rem;
}
.section-header-flex .section-title {
    font-size: 1.9rem; font-weight: 800; color: #fff;
    margin: 0 0 .3rem; line-height: 1.2;
}
.section-header-flex .section-desc {
    font-size: .88rem; color: rgba(255,255,255,.65); margin: 0;
}
.he-view-all {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .8rem; font-weight: 700; color: rgba(255,255,255,.75);
    text-decoration: none; border: 1.5px solid rgba(255,255,255,.2);
    padding: .45rem 1rem; border-radius: 20px;
    transition: all .2s; white-space: nowrap; flex-shrink: 0;
}
.he-view-all:hover { color: #fff; border-color: #fff; background: rgba(255,255,255,.1); }

/* Grid */
.events-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 1.3rem;
}

/* Card */
.event-card-modern {
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 18px; overflow: hidden;
    transition: all .25s; cursor: pointer;
    display: flex; flex-direction: column;
}
.event-card-modern:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,.1);
    border-color: rgba(255,255,255,.22);
    box-shadow: 0 16px 40px rgba(0,0,0,.3);
}

/* Card image */
.event-card-image {
    position: relative; overflow: hidden;
    height: 185px; background: #1a3a5c;
}
.event-card-image img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .35s;
}
.event-card-modern:hover .event-card-image img { transform: scale(1.06); }

/* Date badge overlay */
.event-date-badge-overlay {
    position: absolute; top: .8rem; left: .8rem;
    border-radius: 10px; padding: .35rem .55rem;
    text-align: center; min-width: 46px;
    backdrop-filter: blur(8px);
}
.badge-day   { font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1.1; }
.badge-month { font-size: .6rem; font-weight: 700; color: rgba(255,255,255,.85);
               text-transform: uppercase; letter-spacing: .05em; }
.date-badge-blue   { background: rgba(37,99,235,.75); }
.date-badge-green  { background: rgba(22,163,74,.75); }
.date-badge-purple { background: rgba(124,58,237,.75); }
.date-badge-red    { background: rgba(220,38,38,.75); }
.date-badge-amber  { background: rgba(217,119,6,.75); }
.date-badge-gray   { background: rgba(75,85,99,.75); }

/* Type badge overlay */
.event-type-badge {
    position: absolute;  right: .75rem;
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .65rem; font-weight: 700;
    padding: .22rem .65rem; border-radius: 20px;
    backdrop-filter: blur(8px);
    max-width: calc(100% - 1.5rem);
    white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis;
    z-index: 2;
}
.event-type-blue   { background: rgba(37,99,235,.8);  color: #fff; }
.event-type-green  { background: rgba(22,163,74,.8);  color: #fff; }
.event-type-purple { background: rgba(124,58,237,.8); color: #fff; }
.event-type-red    { background: rgba(220,38,38,.85); color: #fff;
                     animation: emergPulse 1.5s ease-in-out infinite; }
.event-type-amber  { background: rgba(217,119,6,.8);  color: #fff; }
.event-type-gray   { background: rgba(75,85,99,.8);   color: #fff; }
@keyframes emergPulse { 0%,100%{opacity:1} 50%{opacity:.75} }

/* Card content */
.event-card-content {
    padding: 1.1rem 1.2rem 1.2rem;
    display: flex; flex-direction: column; flex: 1;
}
.event-title-modern {
    font-size: .95rem; font-weight: 700; color: #fff;
    margin: 0 0 .5rem; line-height: 1.35;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.event-description-modern {
    font-size: .8rem; color: rgba(255,255,255,.65);
    line-height: 1.65; margin: 0 0 .8rem;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    flex: 1;
}
.event-info-modern { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .9rem; }
.event-info-item {
    display: flex; align-items: center; gap: .3rem;
    font-size: .73rem; color: rgba(255,255,255,.6);
}
.event-info-item i { color: #42a649; font-size: .68rem; }

/* CTA button */
.btn-register-modern {
    display: inline-flex; align-items: center; gap: .4rem;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none; padding: .55rem 1.1rem;
    border-radius: 20px; font-size: .8rem; font-weight: 700;
    cursor: pointer; font-family: inherit;
    transition: all .2s; align-self: flex-start;
    box-shadow: 0 3px 10px rgba(66,166,73,.35);
}
.btn-register-modern:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(66,166,73,.45); }

/* Emergency card highlight */
.event-card-modern.is-emergency {
    border-color: rgba(220,38,38,.4);
    background: rgba(220,38,38,.07);
}

/* Empty state */
.he-empty {
    grid-column: 1/-1; text-align: center;
    padding: 4rem 1rem; color: rgba(255,255,255,.35);
}
.he-empty i { font-size: 3rem; display: block; margin-bottom: 1rem; }
.he-empty h4 { font-size: 1rem; font-weight: 700; color: rgba(255,255,255,.5); margin-bottom: .4rem; }
.he-empty p  { font-size: .85rem; margin: 0; }

/* ── Event Detail Modal ── */
.he-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.6); z-index: 9999;
    align-items: center; justify-content: center;
    padding: 1rem;
}
.he-modal-overlay.open { display: flex; }
.he-modal-box {
    background: #fff; border-radius: 20px;
    width: 100%; max-width: 600px; overflow: hidden;
    box-shadow: 0 25px 70px rgba(0,0,0,.3);
    animation: mPop .25s ease;
    max-height: 90vh; overflow-y: auto;
    position: relative;
}
@keyframes mPop {
    from { opacity:0; transform:scale(.93) translateY(16px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
.hem-close {
    position: absolute; top: .9rem; right: .9rem; z-index: 10;
    width: 30px; height: 30px; border-radius: 50%;
    background: rgba(0,0,0,.1); border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .85rem; color: #fff;
    transition: background .2s;
}
.hem-close:hover { background: rgba(0,0,0,.25); }
.hem-img { width: 100%; max-height: 240px; object-fit: cover; display: none; }
.hem-body { padding: 1.4rem 1.5rem 1.8rem; }
.hem-type {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .7rem; font-weight: 700; padding: .22rem .75rem;
    border-radius: 20px; margin-bottom: .85rem;
}
.hem-title { font-size: 1.2rem; font-weight: 800; color: #1a3a5c; margin-bottom: .5rem; line-height: 1.3; }
.hem-meta  { display: flex; flex-wrap: wrap; gap: .75rem; margin-bottom: 1rem; }
.hem-meta-item { display: flex; align-items: center; gap: .35rem; font-size: .8rem; color: #6b7a8d; }
.hem-meta-item i { color: #42a649; font-size: .75rem; }
.hem-content { font-size: .88rem; color: #4a5568; line-height: 1.8; white-space: pre-line; }

@media (max-width: 768px) {
    .events-grid-modern { grid-template-columns: 1fr; }
    .section-header-flex { flex-direction: column; align-items: flex-start; }
}
</style>

{{-- ══ SECTION ══ --}}
<section class="health-events-section">
    <div class="container">

        <div class="section-header-flex">
            <div>
                <span class="section-label">What's Happening</span>
                <h2 class="section-title">Health Events & Campaigns</h2>
                <p class="section-desc">Join health camps and awareness programs near you</p>
            </div>
            {{-- Optional "view all" if you have a dedicated announcements page --}}
            {{-- <a href="#" class="he-view-all">View All <i class="fas fa-arrow-right"></i></a> --}}
        </div>

        <div class="events-grid-modern">
        @php
            // ── Type config — DB values: health_camp | awareness | special_offer | new_service | emergency | general
            $typeConfig = [
                'health_camp'   => ['icon'=>'fa-hospital-user',       'label'=>'Health Camp',   'badge'=>'date-badge-green',  'typeCls'=>'event-type-green',  'color'=>'#16a34a'],
                'awareness'     => ['icon'=>'fa-info-circle',          'label'=>'Awareness',     'badge'=>'date-badge-blue',   'typeCls'=>'event-type-blue',   'color'=>'#2563eb'],
                'special_offer' => ['icon'=>'fa-tag',                  'label'=>'Special Offer', 'badge'=>'date-badge-purple', 'typeCls'=>'event-type-purple', 'color'=>'#7c3aed'],
                'new_service'   => ['icon'=>'fa-star',                 'label'=>'New Service',   'badge'=>'date-badge-amber',  'typeCls'=>'event-type-amber',  'color'=>'#d97706'],
                'emergency'     => ['icon'=>'fa-exclamation-triangle', 'label'=>'Emergency',     'badge'=>'date-badge-red',    'typeCls'=>'event-type-red',    'color'=>'#dc2626'],
                'general'       => ['icon'=>'fa-bullhorn',             'label'=>'General',       'badge'=>'date-badge-gray',   'typeCls'=>'event-type-gray',   'color'=>'#4b5563'],
            ];

            // Placeholder SVG colors (no external service)
            $svgColors = [
                'health_camp'   => ['bg'=>'#dcfce7','fg'=>'#16a34a','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16'],
                'awareness'     => ['bg'=>'#dbeafe','fg'=>'#2563eb','icon'=>'M13 16h-1v-4h-1m1-4h.01'],
                'special_offer' => ['bg'=>'#ede9fe','fg'=>'#7c3aed','icon'=>'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z'],
                'new_service'   => ['bg'=>'#fef3c7','fg'=>'#d97706','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                'emergency'     => ['bg'=>'#fee2e2','fg'=>'#dc2626','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                'general'       => ['bg'=>'#f3f4f6','fg'=>'#4b5563','icon'=>'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
            ];
        @endphp

        @forelse($activeAnnouncements ?? [] as $ann)
        @php
            $type    = $ann->announcement_type ?? 'general';
            $cfg     = $typeConfig[$type] ?? $typeConfig['general'];
            $svg     = $svgColors[$type]  ?? $svgColors['general'];

            $title   = $ann->title   ?? 'Untitled Event';
            $content = $ann->content ?? '';
            $imgPath = $ann->image_path ?? null;

            $startDate = $ann->start_date
                ? \Carbon\Carbon::parse($ann->start_date)
                : \Carbon\Carbon::parse($ann->created_at);
            $endDate   = $ann->end_date
                ? \Carbon\Carbon::parse($ann->end_date)
                : null;

            // Image src
            if ($imgPath) {
                $imgSrc = asset('storage/'.$imgPath);
            } else {
                // Inline SVG data URI — no external service needed
                $imgSrc = null;
            }

            // Escape for data attributes
            $safeTitle   = htmlspecialchars($title, ENT_QUOTES);
            $safeContent = htmlspecialchars($content, ENT_QUOTES);
            $safeImg     = $imgPath ? asset('storage/'.$imgPath) : '';
            $safeDateStr = $startDate->format('d M Y');
            $safeType    = $cfg['label'];
            $safeDateRange = $endDate
                ? $startDate->format('d M').' – '.$endDate->format('d M Y')
                : $startDate->format('d M Y');
        @endphp

        <div class="event-card-modern {{ $type === 'emergency' ? 'is-emergency' : '' }}"
             onclick="heOpenModal(this)"
             data-title="{{ $safeTitle }}"
             data-content="{{ $safeContent }}"
             data-image="{{ $safeImg }}"
             data-date="{{ $safeDateRange }}"
             data-type="{{ $safeType }}"
             data-type-key="{{ $type }}"
             data-icon="{{ $cfg['icon'] }}"
             data-color="{{ $cfg['color'] }}">

            {{-- Image --}}
            <div class="event-card-image">
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $title }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    {{-- fallback --}}
                    <div style="display:none;position:absolute;inset:0;background:{{ $svg['bg'] }};
                                align-items:center;justify-content:center;">
                        <i class="fas {{ $cfg['icon'] }}" style="font-size:3rem;color:{{ $cfg['color'] }};opacity:.35;"></i>
                    </div>
                @else
                    <div style="position:absolute;inset:0;background:{{ $svg['bg'] }};
                                display:flex;align-items:center;justify-content:center;">
                        <i class="fas {{ $cfg['icon'] }}" style="font-size:3.5rem;color:{{ $cfg['color'] }};opacity:.4;"></i>
                    </div>
                @endif

                {{-- Date badge --}}
                <div class="event-date-badge-overlay {{ $cfg['badge'] }}">
                    <div class="badge-day">{{ $startDate->format('d') }}</div>
                    <div class="badge-month">{{ strtoupper($startDate->format('M')) }}</div>
                </div>

                {{-- Type badge --}}
                <div class="event-type-badge {{ $cfg['typeCls'] }}">
                    <i class="fas {{ $cfg['icon'] }}"></i>
                    <span>{{ $cfg['label'] }}</span>
                </div>
            </div>

            {{-- Content --}}
            <div class="event-card-content">
                <h3 class="event-title-modern">{{ $title }}</h3>
                <p class="event-description-modern">
                    {{ \Illuminate\Support\Str::limit($content, 120) }}
                </p>

                <div class="event-info-modern">
                    <div class="event-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ $safeDateRange }}</span>
                    </div>
                    @if($ann->publisher_type && $ann->publisher_type !== 'admin')
                    <div class="event-info-item">
                        <i class="fas fa-building"></i>
                        <span>{{ ucfirst(str_replace('_',' ',$ann->publisher_type)) }}</span>
                    </div>
                    @endif
                </div>

                <button type="button" class="btn-register-modern">
                    View Details <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="he-empty">
            <i class="fas fa-calendar-times"></i>
            <h4>No Events Available</h4>
            <p>No health events or campaigns are currently scheduled.</p>
        </div>
        @endforelse

        </div>{{-- /events-grid-modern --}}
    </div>
</section>

{{-- ══ EVENT DETAIL MODAL ══ --}}
<div class="he-modal-overlay" id="heModal">
    <div class="he-modal-box">
        <button class="hem-close" onclick="heCloseModal()">
            <i class="fas fa-times"></i>
        </button>

        <img id="hemImg" class="hem-img" src="" alt="Event">

        <div class="hem-body">
            <span id="hemType" class="hem-type"></span>
            <h2 id="hemTitle" class="hem-title"></h2>
            <div class="hem-meta">
                <div class="hem-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="hemDate"></span>
                </div>
            </div>
            <div id="hemContent" class="hem-content"></div>
        </div>
    </div>
</div>

<script>
/* ── Type badge colors ── */
const HE_TYPE_COLORS = {
    'health_camp'   : { bg:'#dcfce7', color:'#16a34a' },
    'awareness'     : { bg:'#dbeafe', color:'#2563eb' },
    'special_offer' : { bg:'#ede9fe', color:'#7c3aed' },
    'new_service'   : { bg:'#fef3c7', color:'#d97706' },
    'emergency'     : { bg:'#fee2e2', color:'#dc2626' },
    'general'       : { bg:'#f3f4f6', color:'#4b5563' },
};

function heOpenModal(card) {
    const title   = card.dataset.title   || 'Event Details';
    const content = card.dataset.content || '';
    const img     = card.dataset.image   || '';
    const date    = card.dataset.date    || '';
    const type    = card.dataset.type    || '';
    const typeKey = card.dataset.typeKey || 'general';
    const icon    = card.dataset.icon    || 'fa-bullhorn';
    const color   = HE_TYPE_COLORS[typeKey] || HE_TYPE_COLORS['general'];

    document.getElementById('hemTitle').textContent   = title;
    document.getElementById('hemContent').textContent = content;
    document.getElementById('hemDate').textContent    = date;

    // Type badge
    const typeBadge = document.getElementById('hemType');
    typeBadge.innerHTML = `<i class="fas ${icon}" style="font-size:.75rem;"></i> ${type}`;
    typeBadge.style.background = color.bg;
    typeBadge.style.color      = color.color;

    // Image
    const hemImg = document.getElementById('hemImg');
    if (img && img.trim() !== '') {
        hemImg.src   = img;
        hemImg.style.display = 'block';
        hemImg.onerror = () => { hemImg.style.display = 'none'; };
    } else {
        hemImg.style.display = 'none';
    }

    document.getElementById('heModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function heCloseModal() {
    document.getElementById('heModal').classList.remove('open');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('heModal').addEventListener('click', function(e) {
    if (e.target === this) heCloseModal();
});

// Close on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') heCloseModal(); });
</script>

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
