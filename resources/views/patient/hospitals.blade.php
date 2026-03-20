{{-- Include Header --}}
@include('partials.header')

<style>
/* Page Specific Styles */
.page-header {
    background: linear-gradient(135deg, #0056a3 0%, #003d7a 100%);
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
    background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1600') center/cover;
    opacity: 0.15;
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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.8rem;
}

.filter-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #0056a3;
    margin-bottom: 0.4rem;
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
    border-color: #0056a3;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 86, 163, 0.1);
}

.search-btn {
    background: #0056a3;
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
    background: #003d7a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Hospital Cards */
.hospitals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.3rem;
    margin-top: 1.5rem;
}

.hospital-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.04);
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

.hospital-card:nth-child(1) { animation-delay: 0.1s; }
.hospital-card:nth-child(2) { animation-delay: 0.15s; }
.hospital-card:nth-child(3) { animation-delay: 0.2s; }
.hospital-card:nth-child(4) { animation-delay: 0.25s; }
.hospital-card:nth-child(5) { animation-delay: 0.3s; }
.hospital-card:nth-child(6) { animation-delay: 0.35s; }

.hospital-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.hospital-image {
    height: 160px;
    overflow: hidden;
    position: relative;
}

.hospital-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.hospital-card:hover .hospital-image img {
    transform: scale(1.1);
}

.hospital-badge {
    position: absolute;
    top: 0.7rem;
    right: 0.7rem;
    background: #28a745;
    color: white;
    padding: 0.25rem 0.7rem;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.hospital-type-badge {
    position: absolute;
    top: 0.7rem;
    left: 0.7rem;
    background: rgba(0, 86, 163, 0.9);
    color: white;
    padding: 0.25rem 0.7rem;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: capitalize;
}

.hospital-content {
    padding: 1.2rem;
}

.hospital-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0056a3;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.hospital-info {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
    color: #555;
}

.hospital-info i {
    width: 16px;
    margin-right: 0.4rem;
    color: #0056a3;
    font-size: 0.7rem;
}

.hospital-rating {
    display: flex;
    align-items: center;
    margin: 0.7rem 0;
    padding: 0.5rem 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
}

.stars {
    color: #ffc107;
    margin-right: 0.4rem;
    font-size: 0.75rem;
}

.rating-text {
    font-size: 0.7rem;
    color: #666;
}

.hospital-footer {
    padding: 0 1.2rem 1.2rem;
}

.view-hospital-btn {
    display: block;
    background: #0056a3;
    color: white;
    text-align: center;
    padding: 0.65rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-hospital-btn:hover {
    background: #003d7a;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.no-results {
    text-align: center;
    padding: 3rem;
    color: #666;
}

.no-results i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.content-section {
    background: #f8f9fa;
    padding: 2.5rem 0;
    min-height: 400px;
}

.section-title {
    text-align: center;
    font-size: 1.4rem;
    font-weight: 700;
    color: #0056a3;
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination {
    justify-content: center;
}

.page-link {
    color: #0056a3;
    border: 1px solid #dee2e6;
    padding: 0.4rem 0.65rem;
    font-size: 0.8rem;
}

.page-link:hover {
    background-color: #0056a3;
    color: white;
    border-color: #0056a3;
}

.page-item.active .page-link {
    background-color: #0056a3;
    border-color: #0056a3;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }

    .hospitals-grid {
        grid-template-columns: 1fr;
    }

    .filter-row {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">Find Hospitals</h1>
                <p class="page-subtitle">Discover trusted healthcare facilities across Sri Lanka</p>
            </div>
        </div>
    </div>
</section>

{{-- Search Filters --}}
<section>
    <div class="container">
        <form method="GET" action="{{ route('patient.hospitals') }}">
            <div class="search-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Search Hospital
                        </label>
                        <input type="text"
                               class="filter-input"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or location">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-hospital"></i> Hospital Type
                        </label>
                        <select class="filter-select" name="type">
                            <option value="">All Types</option>
                            <option value="government" {{ request('type') == 'government' ? 'selected' : '' }}>Government</option>
                            <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>Private</option>
                        </select>
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
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Hospitals List --}}
<section class="content-section">
    <div class="container">
        <h2 class="section-title">
            @if(request()->hasAny(['search', 'type', 'city']))
                Search Results
                @if($hospitals->total() > 0)
                    ({{ $hospitals->total() }} {{ Str::plural('hospital', $hospitals->total()) }} found)
                @endif
            @else
                Available Hospitals
            @endif
        </h2>

        @if($hospitals->count() > 0)
            <div class="hospitals-grid">
                @foreach($hospitals as $hospital)
                    <div class="hospital-card">
                        <div class="hospital-image">
                           <img src="{{ $hospital->profile_image ? asset('storage/' . $hospital->profile_image) : asset('images/default-hospital.png') }}"
                                alt="{{ $hospital->name }}"
                                onerror="this.src='{{ asset('images/default-hospital.png') }}'">

                            @if($hospital->status == 'approved')
                                <div class="hospital-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified</span>
                                </div>
                            @endif

                            @if($hospital->type)
                                <div class="hospital-type-badge">
                                    {{ ucfirst($hospital->type) }}
                                </div>
                            @endif
                        </div>

                        <div class="hospital-content">
                            <h3 class="hospital-name">{{ $hospital->name }}</h3>

                            <div class="hospital-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $hospital->city ?? 'N/A' }}</span>
                            </div>

                            <div class="hospital-info">
                                <i class="fas fa-map-signs"></i>
                                <span>{{ Str::limit($hospital->address ?? 'Address not available', 40) }}</span>
                            </div>

                            <div class="hospital-info">
                                <i class="fas fa-phone"></i>
                                <span>{{ $hospital->phone ?? 'N/A' }}</span>
                            </div>

                            <div class="hospital-rating">
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
                                <span class="rating-text">
                                    {{ number_format($rating, 1) }} ({{ $hospital->total_ratings ?? 0 }})
                                </span>
                            </div>
                        </div>

                        <div class="hospital-footer">
                            <a href="{{ route('patient.hospitals.show', $hospital->id) }}"
                               class="view-hospital-btn">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($hospitals->hasPages())
                <div class="mt-4">
                    {{ $hospitals->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="no-results">
                <i class="fas fa-hospital"></i>
                <h4>No hospitals found</h4>
                <p>Try adjusting your search filters</p>
                @if(request()->hasAny(['search', 'type', 'city']))
                    <a href="{{ route('patient.hospitals') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.hospital-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
    });
});
</script>

{{-- Include Footer --}}
@include('partials.footer')
