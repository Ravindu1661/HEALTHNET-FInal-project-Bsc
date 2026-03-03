<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthNet - Professional Healthcare Platform</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS - Using Laravel asset helper with correct paths -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-update.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <!-- Favicon Links -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Android Chrome Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- iOS Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HEALTHNET">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- MS Tile Icons -->
    <meta name="msapplication-TileColor" content="#3B82F6">
    <meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">

    @vite([ 'resources/js/app.js',])
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js',]) -->


    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('Home') }}">
                <i class="fas fa-heartbeat me-2"></i>HealthNet
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('Home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <!-- Translation Widget -->
                    <div class="translation-widget me-3">
                        <div class="gtranslate_wrapper"></div>
                    </div>

                    <!-- Login & Signup Buttons -->
                    <a href="{{ route('login') }}" class="btn-login me-2">
                    <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                   <a href="{{ route('signup') }}" class="btn-signup">
                    <i class="fas fa-user-plus me-1"></i>Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="spinner"></div>
    </div>

    <!-- Install App Floating Button -->
    <div id="installContainer" class="install-container hidden">
    <button id="installButton" class="install-btn" title="Install App">
        <i class="fas fa-download"></i>
    </button>
    </div>

    <!-- Hero Section with React-inspired Design -->
    <section class="hero-section" id="home">
        <div class="hero-content">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Content -->
                    <div class="col-lg-6">
                        <div class="hero-badge">
                            #1 Healthcare Management Platform
                    </div>

                    <h1 class="hero-title">
                        Your Complete<br>
                        <span class="hero-title-gradient">Healthcare Partner</span>
                    </h1>

                    <p class="hero-description">
                        Experience seamless medical care with our integrated platform.
                        Connect with top doctors, labs, and pharmacies instantly.
                    </p>

                    <div class="hero-buttons">
                        <a href="{{ route('signup') }}" class="btn-hero btn-primary-hero">
                            <span>Get Started</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn-hero btn-secondary-hero">
                            <i class="fas fa-search"></i>
                            <span>Find a Doctor</span>
                        </a>
                    </div>

                    <!-- Provider Links -->
                    <div class="provider-links">
                        <p class="provider-text">Are you a healthcare provider?</p>
                        <div class="provider-buttons">
                            <a href="{{ route('provider-signup') }}" class="provider-btn">
                                <i class="fas fa-user-md"></i> Doctor
                            </a>
                            <a href="{{ route('provider-signup') }}" class="provider-btn">
                                <i class="fas fa-hospital"></i> Hospital
                            </a>
                            <a href="{{ route('provider-signup') }}" class="provider-btn">
                                <i class="fas fa-flask"></i> Laboratory
                            </a>
                            <a href="{{ route('provider-signup') }}" class="provider-btn">
                                <i class="fas fa-pills"></i> Pharmacy
                            </a>
                            <a href="{{ route('provider-signup') }}" class="provider-btn">
                                <i class="fas fa-clinic-medical"></i> Medical Centre
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Floating Feature Cards with Central Pulse -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-features">
                        <!-- Central Pulse Effect -->
                        <div class="central-pulse"></div>

                        <!-- Floating Card 1 - Cardiology -->
                        <div class="feature-card feature-card-1">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon feature-icon-heart">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>Cardiology</h4>
                                <p>Top Specialists</p>
                            </div>
                        </div>

                        <!-- Floating Card 2 - Secure Data -->
                        <div class="feature-card feature-card-2">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon feature-icon-shield">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4>Secure Data</h4>
                                <p>Encrypted Records</p>
                            </div>
                        </div>

                        <!-- Floating Card 3 - Health Tracking -->
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

    <!-- Background with Image Overlay - React Style -->
    <div class="hero-background">
        <img src="{{ asset('images/Hero.jpg') }}" alt="Healthcare Professionals" class="hero-bg-image">
        <div class="hero-overlay-gradient-1"></div>
        <div class="hero-overlay-gradient-2"></div>
    </div>
    </section>

    <!-- Advanced Services Section - React Style -->
    <section id="services-advanced" class="services-advanced-section">
        <!-- Background Decor -->
        <div class="services-bg-decor">
            <div class="decor-circle decor-circle-1"></div>
            <div class="decor-circle decor-circle-2"></div>
        </div>

        <div class="container position-relative">
            <div class="text-center mx-auto section-intro">
                <span class="section-label">Centers of Excellence</span>
                <h2 class="section-heading-react">Specialized Care for You</h2>
                <p class="section-text-react">
                    We combine medical expertise with state-of-the-art technology to provide the best possible outcomes.
                </p>
            </div>

            <div class="row g-4">
                <!-- Service Card 1 - Cardiology -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card-advanced service-rose">
                        <div class="service-card-bg"></div>
                        <div class="service-card-content">
                            <div class="service-3d-icon">
                                <div class="icon-glow"></div>
                                <img src="{{ asset('images/generated_images/3d_icon_heart_cardiology.png') }}"
                                    alt="Cardiology"
                                    class="service-icon-img">
                            </div>

                            <h3 class="service-card-title">Cardiology Center</h3>
                            <p class="service-card-desc">
                                World-class heart care with advanced diagnostics and surgical solutions.
                            </p>

                            <a href="{{ route('login') }}" class="service-link service-link-rose">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Card 2 - Neurology -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card-advanced service-violet">
                        <div class="service-card-bg"></div>
                        <div class="service-card-content">
                            <div class="service-3d-icon">
                                <div class="icon-glow"></div>
                                <img src="{{ asset('images/generated_images/3d_icon_brain_neurology.png') }}"
                                    alt="Neurology"
                                    class="service-icon-img">
                            </div>

                            <h3 class="service-card-title">Neurology Institute</h3>
                            <p class="service-card-desc">
                                Comprehensive care for brain and nervous system disorders.
                            </p>

                            <a href="{{ route('login') }}" class="service-link service-link-violet">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Card 3 - Dental -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card-advanced service-blue">
                        <div class="service-card-bg"></div>
                        <div class="service-card-content">
                            <div class="service-3d-icon">
                                <div class="icon-glow"></div>
                                <img src="{{ asset('images/generated_images/3d_icon_tooth_dental.png') }}"
                                    alt="Dental Care"
                                    class="service-icon-img">
                            </div>

                            <h3 class="service-card-title">Dental Care</h3>
                            <p class="service-card-desc">
                                Advanced cosmetic and restorative dentistry for a perfect smile.
                            </p>

                            <a href="{{ route('login') }}" class="service-link service-link-blue">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Service Card 4 - Genetics -->
                <div class="col-lg-3 col-md-6">
                    <div class="service-card-advanced service-emerald">
                        <div class="service-card-bg"></div>
                        <div class="service-card-content">
                            <div class="service-3d-icon">
                                <div class="icon-glow"></div>
                                <img src="{{ asset('images/generated_images/3d_icon_dna_genetics.png') }}"
                                    alt="Genetics"
                                    class="service-icon-img">
                            </div>

                            <h3 class="service-card-title">Genetics & Research</h3>
                            <p class="service-card-desc">
                                Cutting-edge genetic testing and personalized medicine.
                            </p>

                            <a href="{{ route('login') }}" class="service-link service-link-emerald">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section - React Inspired -->
    <section id="about" class="about-section-react">
        <div class="container">
            <div class="row g-4 align-items-center">

                <!-- Image Side -->
                <div class="col-lg-6">
                    <div class="about-image-container">
                        <div class="about-image-wrapper">
                            <img src="{{ asset('images/generated_images/modern_hospital_building_exterior.png') }}"
                                alt="Modern Hospital Building"
                                class="about-main-image">
                            <div class="about-image-overlay"></div>

                            <!-- Floating Badge -->
                            <div class="about-floating-badge">
                                <div class="badge-content">
                                    <div class="badge-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <span class="badge-title">#1 Medical Center</span>
                                </div>
                                <p class="badge-text">Awarded for excellence in patient care and infrastructure.</p>
                            </div>
                        </div>

                        <!-- Background Pattern -->
                        <div class="about-bg-pattern"></div>
                    </div>
                </div>

                <!-- Content Side -->
                <div class="col-lg-6">
                    <div class="about-content-react">
                        <span class="section-label">About Us</span>
                        <h2 class="section-heading-react">
                            Setting the Standard in <br>
                            <span class="text-primary">Modern Healthcare</span>
                        </h2>
                        <p class="section-text-react">
                            At HealthNet, we believe in a patient-first approach. Our facility is designed to provide a healing environment, combining nature with advanced medical technology.
                        </p>

                        <div class="features-checklist">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="feature-check-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>24/7 Emergency Support</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature-check-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Advanced Lab Technologies</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature-check-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Expert Medical Team</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature-check-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Patient-Centric Care</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="about-stats-row">
                            <div class="stat-box">
                                <h4 class="stat-number">25+</h4>
                                <p class="stat-label">Years Experience</p>
                            </div>
                            <div class="stat-box">
                                <h4 class="stat-number">15k+</h4>
                                <p class="stat-label">Happy Patients</p>
                            </div>
                            <div class="stat-box">
                                <h4 class="stat-number">100+</h4>
                                <p class="stat-label">Expert Doctors</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Upcoming Events Section - React Style -->
    <section class="events-section-react">
        <div class="container">
            <div class="text-center mx-auto section-intro">
                <span class="section-label">Upcoming Events</span>
                <h2 class="section-heading-react">Health Awareness Programs</h2>
                <p class="section-text-react">
                    Join our community health events and workshops designed to promote wellness and preventive care.
                </p>
            </div>

            <div class="row g-4">
                <!-- Event Card 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="event-card-react">
                        <div class="event-date-badge">
                            <div class="event-day">15</div>
                            <div class="event-month">DEC</div>
                        </div>

                        <div class="event-image-wrapper">
                            <img src="{{ asset('images/generated_images/medical_conference_seminar.png') }}"
                                alt="Free Health Checkup"
                                class="event-image">
                            <div class="event-image-overlay"></div>
                        </div>

                        <div class="event-content">
                            <div class="event-meta">
                                <span class="event-time">
                                    <i class="fas fa-clock"></i> 9:00 AM - 5:00 PM
                                </span>
                                <span class="event-location">
                                    <i class="fas fa-map-marker-alt"></i> Main Hospital
                                </span>
                            </div>

                            <h3 class="event-title">Free Health Checkup Camp</h3>
                            <p class="event-description">
                                Comprehensive health screening including blood pressure, diabetes, and cholesterol tests for all age groups.
                            </p>

                            <a href="{{ route('login') }}" class="event-register-btn">
                                Register Now <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Event Card 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="event-card-react">
                        <div class="event-date-badge">
                            <div class="event-day">20</div>
                            <div class="event-month">DEC</div>
                        </div>

                        <div class="event-image-wrapper">
                            <img src="{{ asset('images/generated_images/diabetes_awareness.jpg') }}"
                                alt="Diabetes Awareness"
                                class="event-image">
                            <div class="event-image-overlay"></div>
                        </div>

                        <div class="event-content">
                            <div class="event-meta">
                                <span class="event-time">
                                    <i class="fas fa-clock"></i> 2:00 PM - 4:00 PM
                                </span>
                                <span class="event-location">
                                    <i class="fas fa-map-marker-alt"></i> Auditorium
                                </span>
                            </div>

                            <h3 class="event-title">Diabetes Awareness Workshop</h3>
                            <p class="event-description">
                                Learn about diabetes management, nutrition tips, and lifestyle changes with our expert endocrinologists.
                            </p>

                            <a href="{{ route('login') }}" class="event-register-btn">
                                Register Now <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Event Card 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="event-card-react">
                        <div class="event-date-badge">
                            <div class="event-day">28</div>
                            <div class="event-month">DEC</div>
                        </div>

                        <div class="event-image-wrapper">
                            <img src="{{ asset('images/generated_images/mental_health_seminar.jpg') }}"
                                alt="Mental Health"
                                class="event-image">
                            <div class="event-image-overlay"></div>
                        </div>

                        <div class="event-content">
                            <div class="event-meta">
                                <span class="event-time">
                                    <i class="fas fa-clock"></i> 10:00 AM - 12:00 PM
                                </span>
                                <span class="event-location">
                                    <i class="fas fa-map-marker-alt"></i> Online Webinar
                                </span>
                            </div>

                            <h3 class="event-title">Mental Health & Wellness Seminar</h3>
                            <p class="event-description">
                                Understanding stress, anxiety, and depression. Interactive session with psychiatrists and counselors.
                            </p>

                            <a href="{{ route('login') }}" class="event-register-btn">
                                Register Now <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-compact">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number-compact" data-count="5000">0</span>
                    <div class="stat-label-compact">Registered Patients</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number-compact" data-count="250">0</span>
                    <div class="stat-label-compact">Medical Professionals</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number-compact" data-count="150">0</span>
                    <div class="stat-label-compact">Healthcare Centers</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number-compact" data-count="98">0</span>
                    <div class="stat-label-compact">% Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Section - Horizontal Slider -->
    <section class="doctors-section" id="doctors">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header-doctors">
                <div>
                    <h2 class="doctors-title">Meet Our Top Specialists</h2>
                    <p class="doctors-subtitle">Highly qualified doctors ready to help you.</p>
                </div>
            </div>
            <!-- Doctor Cards Slider Container -->
            <div class="doctors-slider-wrapper">
                <div class="doctors-slider" id="doctorsSlider">
                @forelse(($featuredDoctors ?? collect()) as $doctor)
                    @php
                        $profileImage = $doctor->profile_image
                            ? asset('storage/' . $doctor->profile_image)
                            : asset('images/default-avatar.png');
                    @endphp

                    <div class="doctor-card">
                        <div class="doctor-image-wrapper">
                            <img
                                src="{{ $profileImage }}"
                                alt="Dr. {{ $doctor->first_name ?? '' }} {{ $doctor->last_name ?? '' }}"
                                class="doctor-image"
                                onerror="this.src='{{ asset('images/default-avatar.png') }}'">

                            <div class="doctor-rating-badge">
                                <i class="fas fa-star"></i>
                                <span>{{ number_format((float)($doctor->rating ?? 0), 1) }}</span>
                            </div>
                        </div>

                        <div class="doctor-info">
                            <h3 class="doctor-name">
                                {{ $doctor->full_name ?? ($doctor->first_name.' '.$doctor->last_name) }}
                            </h3>
                            <p class="doctor-specialty">{{ $doctor->specialization ?? 'General Practitioner' }}</p>
                            <p class="doctor-hospital">{{ $doctor->workplaces->first()?->workplace_name ?? 'Sri Lanka' }}</p>

                            <a href="{{ route('login') }}" class="doctor-book-btn">
                                Book Appointment
                            </a>
                        </div>
                    </div>
                @empty
                    <p style="margin:0; color:#64748b; font-weight:600;">No doctors available right now.</p>
                @endforelse
            </div>

            <!-- Navigation Arrows - Bottom Center -->
            <div class="carousel-navigation">
                <button type="button" class="carousel-btn carousel-prev" onclick="slideCarousel(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="carousel-btn carousel-next" onclick="slideCarousel(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

        </div>
    </section>

<style>
    /* Doctors slider ONLY (scoped) */
#doctorsSlider {
  display: flex;
  gap: 1.5rem;
  overflow-x: auto;
  overflow-y: hidden;
  scroll-snap-type: x mandatory;
  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 12px;
}

#doctorsSlider .doctor-card {
  flex: 0 0 auto;
  scroll-snap-align: start;
  width: 320px; /* keep your design size */
}

@media (max-width: 768px) {
  #doctorsSlider .doctor-card { width: 86vw; }
}

/* Optional scrollbar styling */
#doctorsSlider::-webkit-scrollbar { height: 8px; }
#doctorsSlider::-webkit-scrollbar-thumb {
  background: rgba(0, 102, 204, 0.25);
  border-radius: 999px;
}

</style>
    <!-- Health Tips Section - React Style -->
    <section class="health-tips-react">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div class="health-tips-header">
                    <span class="section-label">Health & Wellness</span>
                    <h2 class="section-heading-react">Latest Health Tips</h2>
                    <p class="section-text-react">
                        Expert advice to help you live a healthier, happier life.
                    </p>
                </div>
                <a href="{{ route('login') }}" class="view-all-link d-none d-md-block">View All Articles</a>
            </div>

            <div class="row g-4">
                <!-- Tip Card 1 - Nutrition -->
                <div class="col-md-6">
                    <div class="health-tip-card-react">
                        <img src="{{ asset('images/generated_images/healthy_food_bowl.png') }}"
                            alt="Balanced Diet"
                            class="tip-image">
                        <div class="tip-overlay"></div>

                        <div class="tip-content">
                            <span class="tip-category tip-category-green">Nutrition</span>
                            <h3 class="tip-title">Balanced Diet for a Healthy Life</h3>
                            <p class="tip-desc">
                                Discover the power of superfoods and how a balanced plate can boost your immunity.
                            </p>

                            <div class="tip-action">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                                <span>Watch Video</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tip Card 2 - Fitness -->
                <div class="col-md-6">
                    <div class="health-tip-card-react">
                        <img src="{{ asset('images/generated_images/person_jogging_in_park.png') }}"
                            alt="Morning Routine"
                            class="tip-image">
                        <div class="tip-overlay"></div>

                        <div class="tip-content">
                            <span class="tip-category tip-category-orange">Fitness</span>
                            <h3 class="tip-title">Morning Routine for Energy</h3>
                            <p class="tip-desc">
                                Simple exercises to kickstart your day and keep your metabolism active.
                            </p>

                            <div class="tip-action">
                                <div class="play-button">
                                    <i class="fas fa-play"></i>
                                </div>
                                <span>Watch Video</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Services Section -->
    <section class="emergency-section">
        <div class="container">
            <div class="emergency-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3><i class="fas fa-exclamation-triangle me-2"></i>Emergency Services</h3>
                        <p>In case of medical emergency, contact these services immediately</p>
                        <div class="emergency-numbers">
                            <a href="tel:119" class="emergency-btn">
                                <i class="fas fa-ambulance"></i>
                                <span>Ambulance: 119</span>
                            </a>
                            <a href="tel:110" class="emergency-btn">
                                <i class="fas fa-phone"></i>
                                <span>Police: 110</span>
                            </a>
                            <a href="tel:118" class="emergency-btn">
                                <i class="fas fa-fire-extinguisher"></i>
                                <span>Fire: 118</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="emergency-icon">
                            <i class="fas fa-hospital-symbol"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials Section - React Style -->
    <section class="testimonials-section-react">
        <!-- Background Decoration -->
        <div class="testimonials-bg-decor">
            <div class="decor-shape decor-shape-1"></div>
            <div class="decor-shape decor-shape-2"></div>
        </div>

        <div class="container position-relative">
            <div class="text-center mx-auto section-intro">
                <span class="section-label">Testimonials</span>
                <h2 class="section-heading-react">What Our Users Say</h2>
                <p class="section-text-react">
                    Real experiences from patients who trust us with their healthcare needs.
                </p>
            </div>

            <div class="row g-4">
                <!-- Testimonial Card 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p class="testimonial-text">
                            "HealthNet has transformed how I manage my health. The online appointment system is so convenient, and the doctors are highly professional. Highly recommended!"
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Shalini Fernando"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Shalini Fernando</h4>
                                <p class="author-title">Patient • Colombo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Card 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p class="testimonial-text">
                            "As a doctor, HealthNet has made patient management seamless. The digital records system and appointment scheduling save so much time. Excellent platform!"
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Dr. Kasun Perera"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Dr. Kasun Perera</h4>
                                <p class="author-title">Cardiologist • Kandy</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Card 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p class="testimonial-text">
                            "The lab report integration is amazing! I can access all my test results online instantly. HealthNet has made healthcare truly accessible for everyone."
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Nimal Silva"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Nimal Silva</h4>
                                <p class="author-title">Patient • Galle</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Card 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>

                        <p class="testimonial-text">
                            "Booking appointments has never been easier. The reminder notifications ensure I never miss a checkup. Great initiative for Sri Lanka's healthcare!"
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Amaya Jayasinghe"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Amaya Jayasinghe</h4>
                                <p class="author-title">Patient • Negombo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Card 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p class="testimonial-text">
                            "The pharmacy integration allows me to order medicines online and track delivery. Very convenient for elderly patients like my parents. Thank you HealthNet!"
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Rohan Wickramasinghe"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Rohan Wickramasinghe</h4>
                                <p class="author-title">Patient • Matara</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Card 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card-react">
                        <div class="testimonial-quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>

                        <p class="testimonial-text">
                            "Our hospital's visibility has increased significantly through HealthNet. The platform connects us with patients efficiently. A game-changer for healthcare providers!"
                        </p>

                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                alt="Dr. Nilmini Rajapaksha"
                                class="author-avatar">
                            <div class="author-info">
                                <h4 class="author-name">Dr. Nilmini Rajapaksha</h4>
                                <p class="author-title">Hospital Admin • Kurunegala</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section - React Enhanced -->
    <section id="contact-react" class="contact-section-react">
        <!-- Decorative Circle -->
        <div class="contact-decorative-circle"></div>

        <div class="container">
            <div class="row g-5">

                <!-- Contact Info -->
                <div class="col-lg-6">
                    <div class="contact-info-react">
                        <span class="section-label">Get in Touch</span>
                        <h2 class="section-heading-react">We're Here to Help</h2>
                        <p class="section-text-react">
                            Have questions about our services or need to book an appointment? Reach out to us anytime.
                        </p>

                        <div class="contact-items-list">
                            <!-- Location -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Our Location</h4>
                                    <p>123 Medical District, Health City, HC 10012</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Phone Number</h4>
                                    <p>+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Email Address</h4>
                                    <p>support@healthnet.com</p>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-info-text">
                                    <h4>Working Hours</h4>
                                    <p>Mon - Fri: 8:00 AM - 8:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map & Form -->
                <div class="col-lg-6">
                    <div class="contact-form-react-wrapper">
                        <!-- Map -->
                        <div class="contact-map-wrapper">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153168!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d3f11b439c0!2sFlinders%20St%20Station!5e0!3m2!1sen!2sau!4v1620000000000!5m2!1sen!2sau"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                class="contact-map"></iframe>
                        </div>

                        <!-- Form -->
                        <div class="contact-form-react">
                            <h3 class="form-title">Send us a Message</h3>
                            <form class="contact-form-inner" onsubmit="event.preventDefault();">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control-react" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control-react" placeholder="Email Address" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control-react" placeholder="Subject" required>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control-react textarea-react" rows="4" placeholder="How can we help you?" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-submit-react">
                                            Send Message <i class="fas fa-paper-plane ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <h5><i class="fas fa-heartbeat me-2"></i>HealthNet Sri Lanka</h5>
                    <p>Revolutionizing healthcare delivery in Sri Lanka through innovative digital solutions. Your health, our priority - connecting patients with quality healthcare services nationwide.</p>
                    <div class="social-links">
                        <a href="{{ route('login') }}" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ route('login') }}" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="{{ route('login') }}" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="{{ route('login') }}" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="{{ route('login') }}" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Home</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">About Us</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Services</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Doctors</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>For Patients</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('patient.doctors') }}">Find Doctors</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('patient.hospitals') }}">Find Hospitals</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('patient.laboratories') }}">Laboratories</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('patient.pharmacies') }}">Pharmacies</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Health Tips</a></li>
                    </ul>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>For Providers</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('provider-signup') }}">Register as Doctor</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('provider-signup') }}">Register Hospital</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('provider-signup') }}">Register Laboratory</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('provider-signup') }}">Register Pharmacy</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('provider-signup') }}">Register Centre</a></li>
                    </ul>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-12">
                    <h5>Emergency Services</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="tel:119">Ambulance: 119</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:110">Police: 110</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:118">Fire Service: 118</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:+94112345678">Hospital: +94 11 234 5678</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="{{ route('login') }}">Emergency Guide</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 HealthNet Sri Lanka. All rights reserved. | Designed for better healthcare delivery across the island.</p>
                <p style="color: rgba(255, 255, 255, 0.6); margin: 0.8rem 0 0 0; font-size: 0.9rem;">Connecting patients with quality healthcare - Your health, our mission.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Translation Widget -->
    <script>window.gtranslateSettings = {"default_language":"en","languages":["en","si","ta"],"wrapper_selector":".gtranslate_wrapper","flag_size":24,"switcher_horizontal_position":"inline","alt_flags":{"en":"usa"}}</script>
    <script src="https://cdn.gtranslate.net/widgets/latest/dwf.js" defer></script>

    <!-- AI Chatbot -->
    {{-- <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="4c49fd60-f7b6-4497-8a15-de5860603e59";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script> --}}

    {{-- <script>
    (function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="IbqKuCIci9h1OzBSxeWwx";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
    </script> --}}

    <!-- Custom JS - Using Laravel asset helper with correct path -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/doc_sec_Slider.js') }}"></script>
    <!-- PWA Registration Script -->
    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        console.log('✅ Service Worker registered:', registration.scope);
                    })
                    .catch(error => {
                        console.log('❌ Service Worker registration failed:', error);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installContainer = document.getElementById('installContainer');
        const installButton = document.getElementById('installButton');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installContainer) {
                installContainer.classList.remove('hidden');
            }
            console.log('💡 PWA install prompt ready');
        });

        if (installButton) {
            installButton.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`User response: ${outcome}`);
                    deferredPrompt = null;
                    if (installContainer) {
                        installContainer.classList.add('hidden');
                    }
                }
            });
        }

        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA installed successfully!');
            if (installContainer) {
                installContainer.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
