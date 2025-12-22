{{-- Include Header --}}
@include('partials.header')

<style>
/* Page Specific Styles */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.1;
    z-index: -1;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.search-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    margin: -2.5rem 0 2.5rem 0;
    position: relative;
    z-index: 10;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.8rem;
    margin-bottom: 0;
}

.filter-group {
    position: relative;
}

.filter-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.4rem;
}

.filter-label i {
    margin-right: 0.3rem;
    font-size: 0.7rem;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.55rem 0.7rem;
    border: 1.5px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.filter-input:focus,
.filter-select:focus {
    border-color: var(--accent-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(66, 166, 73, 0.1);
}

.search-btn {
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 0.55rem 1.5rem;
    border-radius: 18px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.search-btn:hover {
    background: var(--primary-color);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Compact Doctor Cards */
.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.2rem;
    margin-top: 1.5rem;
}

.doctor-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.04);
    position: relative;
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.doctor-card:nth-child(1) { animation-delay: 0.1s; }
.doctor-card:nth-child(2) { animation-delay: 0.15s; }
.doctor-card:nth-child(3) { animation-delay: 0.2s; }
.doctor-card:nth-child(4) { animation-delay: 0.25s; }
.doctor-card:nth-child(5) { animation-delay: 0.3s; }
.doctor-card:nth-child(6) { animation-delay: 0.35s; }

.doctor-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.doctor-header {
    padding: 1.2rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fffe 0%, #e8f8f5 100%);
    position: relative;
}

.doctor-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin: 0 auto 0.7rem;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.doctor-card:hover .doctor-avatar {
    transform: scale(1.1);
}

.doctor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-status {
    position: absolute;
    top: 0.7rem;
    right: 0.7rem;
    background: var(--accent-color);
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 10px;
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.doctor-status.busy {
    background: #dc3545;
}

.approved-badge {
    position: absolute;
    top: 0.7rem;
    left: 0.7rem;
    background: #28a745;
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 10px;
    font-size: 0.55rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.approved-badge i {
    font-size: 0.6rem;
}

.doctor-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.2rem;
    line-height: 1.3;
}

.doctor-specialty {
    font-size: 0.75rem;
    color: rgb(255, 255, 255);
    font-weight: 600;
    margin-bottom: 0.3rem;
    text-transform: capitalize;
}

.doctor-experience {
    font-size: 0.7rem;
    color: #666;
}

.doctor-content {
    padding: 1rem;
}

.doctor-info {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.72rem;
    color: #555;
}

.doctor-info i {
    width: 16px;
    margin-right: 0.4rem;
    color: var(--accent-color);
    font-size: 0.7rem;
}

.doctor-info a {
    color: #555;
    text-decoration: none;
    transition: color 0.2s ease;
}

.doctor-info a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.doctor-rating {
    display: flex;
    align-items: center;
    margin-bottom: 0.7rem;
    padding: 0.4rem 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
}

.stars {
    color: #ffc107;
    margin-right: 0.4rem;
    font-size: 0.7rem;
}

.rating-text {
    font-size: 0.7rem;
    color: #666;
}

.doctor-fee {
    background: linear-gradient(135deg, rgba(66, 166, 73, 0.08) 0%, rgba(66, 166, 73, 0.12) 100%);
    color: var(--accent-color);
    padding: 0.6rem;
    border-radius: 6px;
    margin: 0.7rem 0;
    font-size: 0.75rem;
    text-align: center;
}

.doctor-fee strong {
    font-size: 0.95rem;
    display: block;
    margin-bottom: 0.1rem;
    font-weight: 700;
}

.doctor-actions {
    display: flex;
    gap: 0.4rem;
    margin-top: 0.8rem;
}

.btn-appointment {
    flex: 1;
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 0.55rem;
    border-radius: 6px;
    font-size: 0.72rem;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    font-weight: 600;
    display: inline-block;
}

.btn-appointment:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
}

.btn-profile {
    flex: 1;
    background: transparent;
    color: var(--primary-color);
    border: 1.5px solid var(--primary-color);
    padding: 0.55rem;
    border-radius: 6px;
    font-size: 0.72rem;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    display: inline-block;
    font-weight: 600;
}

.btn-profile:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
}

.no-results {
    text-align: center;
    padding: 2.5rem;
    color: #666;
}

.no-results i {
    font-size: 3.5rem;
    color: #ccc;
    margin-bottom: 0.8rem;
}

.featured-section {
    background: linear-gradient(135deg, #f8fffe 0%, #e8f8f5 100%);
    padding: 2.5rem 0;
    margin-bottom: 2rem;
    min-height: 350px;
}

.featured-title {
    text-align: center;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

/* Pagination Styling */
.pagination {
    justify-content: center;
}

.page-link {
    color: var(--primary-color);
    border: 1px solid #dee2e6;
    padding: 0.4rem 0.65rem;
    font-size: 0.8rem;
}

.page-link:hover {
    background-color: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}

.page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }

    .page-header {
        padding: 6rem 0 2.5rem;
    }

    .search-filters {
        margin: -2rem 0 1.5rem 0;
        padding: 1.2rem;
    }

    .filter-row {
        grid-template-columns: 1fr;
    }

    .doctors-grid {
        grid-template-columns: 1fr;
    }

    .doctor-actions {
        flex-direction: column;
    }
}
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">Find Qualified Doctors</h1>
                <p class="page-subtitle">Connect with experienced medical professionals and specialists across Sri Lanka</p>
            </div>
        </div>
    </div>
</section>

{{-- Search Filters --}}
<section>
    <div class="container">
        <form method="GET" action="{{ route('patient.doctors') }}">
            <div class="search-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Doctor Name or Hospital
                        </label>
                        <input type="text"
                               class="filter-input"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or hospital">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-stethoscope"></i> Specialty
                        </label>
                        <select class="filter-select" name="specialty">
                            <option value="">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                                    {{ ucfirst($specialty) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-map-marker-alt"></i> Location
                        </label>
                        <select class="filter-select" name="location">
                            <option value="">All Locations</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group" style="align-self: end;">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search me-1"></i>Find Doctors
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Doctors List Section --}}
<section class="featured-section">
    <div class="container">
        <h2 class="featured-title">
            @if(request()->hasAny(['search', 'specialty', 'location']))
                Search Results
                @if($doctors->total() > 0)
                    ({{ $doctors->total() }} {{ Str::plural('doctor', $doctors->total()) }} found)
                @endif
            @else
                Featured Medical Professionals
            @endif
        </h2>

        @if($doctors->count() > 0)
            <div class="doctors-grid">
                @foreach($doctors as $doctor)
                    @php
                        // Get first approved workplace
                        $workplace = $doctor->workplaces->first();
                        $workplaceName = 'Not Available';
                        $workplaceCity = 'Not Available';
                        $workplaceLink = '#';
                        $workplaceType = null;

                        if ($workplace) {
                            if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                                $workplaceName = $workplace->hospital->name;
                                $workplaceCity = $workplace->hospital->city ?? 'Not Available';
                                $workplaceLink = route('patient.hospitals.show', $workplace->hospital->id);
                                $workplaceType = 'hospital';
                            } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                                $workplaceName = $workplace->medicalCentre->name;
                                $workplaceCity = $workplace->medicalCentre->city ?? 'Not Available';
                                $workplaceLink = route('patient.medical-centres.show', $workplace->medicalCentre->id);
                                $workplaceType = 'medical_centre';
                            }
                        }

                        // Profile image path
                        $profileImage = $doctor->profile_image
                            ? asset('storage/' . $doctor->profile_image)
                            : asset('images/default-avatar.png');
                    @endphp

                    <div class="doctor-card">
                        <div class="doctor-header">
                            {{-- Approved Badge --}}
                            @if($doctor->status == 'approved')
                                <div class="approved-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Approved</span>
                                </div>
                            @endif

                            {{-- Availability Status --}}
                            <div class="doctor-status {{ $doctor->user && $doctor->user->status == 'active' ? '' : 'busy' }}">
                                {{ $doctor->user && $doctor->user->status == 'active' ? 'Available' : 'Unavailable' }}
                            </div>

                            <div class="doctor-avatar">
                                <img src="{{ $profileImage }}"
                                     alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                            <h3 class="doctor-name">
                                Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? 'Doctor' }}
                            </h3>
                            <div class="doctor-specialty">
                                {{ $doctor->specialization ?? 'General Practitioner' }}
                            </div>
                            <div class="doctor-experience">
                                @if($doctor->experience_years)
                                    {{ $doctor->experience_years }} {{ Str::plural('year', $doctor->experience_years) }} exp.
                                @else
                                    Experienced
                                @endif
                            </div>
                        </div>

                        <div class="doctor-content">
                            {{-- Workplace with clickable link --}}
                            <div class="doctor-info">
                                <i class="fas fa-hospital"></i>
                                @if($workplaceType)
                                    <a href="{{ $workplaceLink }}" title="View {{ $workplaceName }}">
                                        {{ Str::limit($workplaceName, 30) }}
                                    </a>
                                @else
                                    <span>{{ $workplaceName }}</span>
                                @endif
                            </div>

                            <div class="doctor-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $workplaceCity }}</span>
                            </div>

                            <div class="doctor-rating">
                                <div class="stars">
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
                                <span class="rating-text">
                                    {{ number_format($rating, 1) }} ({{ $doctor->total_ratings ?? 0 }})
                                </span>
                            </div>

                            <div class="doctor-fee">
                                <strong>Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</strong>
                                <span>Consultation Fee</span>
                            </div>

                            <div class="doctor-actions">
                                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                                   class="btn-appointment">
                                    <i class="fas fa-calendar-plus"></i> Book
                                </a>
                                <a href="{{ route('patient.doctors.show', $doctor->id) }}"
                                   class="btn-profile">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($doctors->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $doctors->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="no-results text-center py-5">
                <i class="fas fa-user-md"></i>
                <h4>No doctors found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'specialty', 'location']))
                        Try adjusting your search criteria or browse all available doctors.
                    @else
                        No approved doctors are available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'specialty', 'location']))
                    <a href="{{ route('patient.doctors') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- Simple JavaScript for additional animations --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover animations to cards
    const cards = document.querySelectorAll('.doctor-card');

    cards.forEach((card, index) => {
        // Stagger animation delay
        card.style.animationDelay = `${index * 0.05}s`;

        // Add ripple effect on click
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a, button')) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);

                const x = e.clientX - this.offsetLeft;
                const y = e.clientY - this.offsetTop;

                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;

                setTimeout(() => ripple.remove(), 600);
            }
        });
    });

    // Smooth scroll for pagination
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
</script>

{{-- Include Footer --}}
@include('partials.footer')
