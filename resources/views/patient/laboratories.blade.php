{{-- Include Header --}}
@include('partials.header')

<style>
/* Reuse the same styles from find-doctors page */
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
    background: url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
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

/* Laboratory Cards */
.labs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.2rem;
    margin-top: 1.5rem;
}

.lab-card {
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

.lab-card:nth-child(1) { animation-delay: 0.1s; }
.lab-card:nth-child(2) { animation-delay: 0.15s; }
.lab-card:nth-child(3) { animation-delay: 0.2s; }
.lab-card:nth-child(4) { animation-delay: 0.25s; }
.lab-card:nth-child(5) { animation-delay: 0.3s; }
.lab-card:nth-child(6) { animation-delay: 0.35s; }

.lab-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.lab-header {
    padding: 1.2rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    position: relative;
}

.lab-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin: 0 auto 0.7rem;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.lab-card:hover .lab-avatar {
    transform: scale(1.1);
}

.lab-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

.lab-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.2rem;
    line-height: 1.3;
}

.lab-regno {
    font-size: 0.7rem;
    color: #666;
    margin-bottom: 0.3rem;
}

.lab-content {
    padding: 1rem;
}

.lab-info {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.72rem;
    color: #555;
}

.lab-info i {
    width: 16px;
    margin-right: 0.4rem;
    color: #7b1fa2;
    font-size: 0.7rem;
}

.lab-info a {
    color: #555;
    text-decoration: none;
    transition: color 0.2s ease;
}

.lab-info a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.lab-rating {
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

.lab-services {
    background: linear-gradient(135deg, rgba(123, 31, 162, 0.08) 0%, rgba(123, 31, 162, 0.12) 100%);
    color: #7b1fa2;
    padding: 0.6rem;
    border-radius: 6px;
    margin: 0.7rem 0;
    font-size: 0.7rem;
    line-height: 1.5;
    max-height: 60px;
    overflow: hidden;
}

.lab-actions {
    display: flex;
    gap: 0.4rem;
    margin-top: 0.8rem;
}

.btn-view-lab {
    flex: 1;
    background: #7b1fa2;
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

.btn-view-lab:hover {
    background: #6a1b9a;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
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
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
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
    background-color: #7b1fa2;
    color: white;
    border-color: #7b1fa2;
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

    .labs-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">Find Laboratories</h1>
                <p class="page-subtitle">Discover accredited medical laboratories across Sri Lanka for your diagnostic needs</p>
            </div>
        </div>
    </div>
</section>

{{-- Search Filters --}}
<section>
    <div class="container">
        <form method="GET" action="{{ route('patient.laboratories') }}">
            <div class="search-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Search Laboratory
                        </label>
                        <input type="text"
                               class="filter-input"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or city">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-map-marker-alt"></i> City
                        </label>
                        <select class="filter-select" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group" style="align-self: end;">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search me-1"></i>Find Laboratories
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Laboratories List Section --}}
<section class="featured-section">
    <div class="container">
        <h2 class="featured-title">
            @if(request()->hasAny(['search', 'city']))
                Search Results
                @if($laboratories->total() > 0)
                    ({{ $laboratories->total() }} {{ Str::plural('laboratory', $laboratories->total()) }} found)
                @endif
            @else
                Accredited Medical Laboratories
            @endif
        </h2>

        @if($laboratories->count() > 0)
            <div class="labs-grid">
                @foreach($laboratories as $lab)
                    @php
                        // Profile image path
                        $profileImage = $lab->profile_image
                            ? asset('storage/' . $lab->profile_image)
                            : asset('images/default-lab.png');

                        // Services display
                        $services = is_array($lab->services) ? $lab->services : json_decode($lab->services, true);
                        $servicesDisplay = is_array($services) ? implode(', ', array_slice($services, 0, 3)) : 'General Lab Services';
                        if(is_array($services) && count($services) > 3) {
                            $servicesDisplay .= '...';
                        }
                    @endphp

                    <div class="lab-card">
                        <div class="lab-header">
                            {{-- Approved Badge --}}
                            @if($lab->status == 'approved')
                                <div class="approved-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified</span>
                                </div>
                            @endif

                            <div class="lab-avatar">
                                <img src="{{ $profileImage }}"
                                     alt="{{ $lab->name }}"
                                     onerror="this.src='{{ asset('images/default-lab.png') }}'">
                            </div>
                            <h3 class="lab-name">
                                {{ $lab->name ?? 'Laboratory' }}
                            </h3>
                            <div class="lab-regno">
                                Reg: {{ $lab->registration_number ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="lab-content">
                            <div class="lab-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $lab->city ?? 'Not Available' }}</span>
                            </div>

                            <div class="lab-info">
                                <i class="fas fa-phone"></i>
                                <span>{{ $lab->phone ?? 'Not Available' }}</span>
                            </div>

                            @if($lab->operating_hours)
                            <div class="lab-info">
                                <i class="fas fa-clock"></i>
                                <span>{{ Str::limit($lab->operating_hours, 25) }}</span>
                            </div>
                            @endif

                            <div class="lab-rating">
                                <div class="stars">
                                    @php
                                        $rating = $lab->rating ?? 0;
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
                                    {{ number_format($rating, 1) }} ({{ $lab->total_ratings ?? 0 }})
                                </span>
                            </div>

                            <div class="lab-services">
                                <strong>Services:</strong> {{ $servicesDisplay }}
                            </div>

                            <div class="lab-actions">
                                <a href="{{ route('patient.laboratories.show', $lab->id) }}"
                                   class="btn-view-lab">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($laboratories->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $laboratories->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="no-results text-center py-5">
                <i class="fas fa-flask"></i>
                <h4>No laboratories found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'city']))
                        Try adjusting your search criteria or browse all available laboratories.
                    @else
                        No approved laboratories are available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'city']))
                    <a href="{{ route('patient.laboratories') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- Include Footer --}}
@include('partials.footer')
