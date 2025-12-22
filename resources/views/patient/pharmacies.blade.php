{{-- Include Header --}}
@include('partials.header')

<style>
/* Same base styles as laboratories, but with pharmacy-specific colors */
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
    background: url('https://images.unsplash.com/photo-1576602976047-174e57a47881?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
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

/* Pharmacy Cards */
.pharmacies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.2rem;
    margin-top: 1.5rem;
}

.pharmacy-card {
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

.pharmacy-card:nth-child(1) { animation-delay: 0.1s; }
.pharmacy-card:nth-child(2) { animation-delay: 0.15s; }
.pharmacy-card:nth-child(3) { animation-delay: 0.2s; }
.pharmacy-card:nth-child(4) { animation-delay: 0.25s; }
.pharmacy-card:nth-child(5) { animation-delay: 0.3s; }
.pharmacy-card:nth-child(6) { animation-delay: 0.35s; }

.pharmacy-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.pharmacy-header {
    padding: 1.2rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
    position: relative;
}

.pharmacy-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin: 0 auto 0.7rem;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.pharmacy-card:hover .pharmacy-avatar {
    transform: scale(1.1);
}

.pharmacy-avatar img {
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

.delivery-badge {
    position: absolute;
    top: 0.7rem;
    right: 0.7rem;
    background: #00796b;
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 10px;
    font-size: 0.55rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.approved-badge i,
.delivery-badge i {
    font-size: 0.6rem;
}

.pharmacy-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.2rem;
    line-height: 1.3;
}

.pharmacy-regno {
    font-size: 0.7rem;
    color: #666;
    margin-bottom: 0.3rem;
}

.pharmacy-content {
    padding: 1rem;
}

.pharmacy-info {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.72rem;
    color: #555;
}

.pharmacy-info i {
    width: 16px;
    margin-right: 0.4rem;
    color: #00796b;
    font-size: 0.7rem;
}

.pharmacy-info a {
    color: #555;
    text-decoration: none;
    transition: color 0.2s ease;
}

.pharmacy-info a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.pharmacy-rating {
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

.pharmacy-pharmacist {
    background: linear-gradient(135deg, rgba(0, 121, 107, 0.08) 0%, rgba(0, 121, 107, 0.12) 100%);
    color: #00796b;
    padding: 0.6rem;
    border-radius: 6px;
    margin: 0.7rem 0;
    font-size: 0.7rem;
}

.pharmacy-pharmacist strong {
    display: block;
    margin-bottom: 0.2rem;
}

.pharmacy-actions {
    display: flex;
    gap: 0.4rem;
    margin-top: 0.8rem;
}

.btn-view-pharmacy {
    flex: 1;
    background: #00796b;
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

.btn-view-pharmacy:hover {
    background: #00695c;
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
    background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
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
    background-color: #00796b;
    color: white;
    border-color: #00796b;
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

    .pharmacies-grid {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">Find Pharmacies</h1>
                <p class="page-subtitle">Locate trusted pharmacies across Sri Lanka with home delivery options</p>
            </div>
        </div>
    </div>
</section>

{{-- Search Filters --}}
<section>
    <div class="container">
        <form method="GET" action="{{ route('patient.pharmacies') }}">
            <div class="search-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Search Pharmacy
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
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-truck"></i> Delivery
                        </label>
                        <select class="filter-select" name="delivery">
                            <option value="">All Pharmacies</option>
                            <option value="yes" {{ request('delivery') == 'yes' ? 'selected' : '' }}>With Delivery</option>
                            <option value="no" {{ request('delivery') == 'no' ? 'selected' : '' }}>No Delivery</option>
                        </select>
                    </div>
                    <div class="filter-group" style="align-self: end;">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search me-1"></i>Find Pharmacies
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Pharmacies List Section --}}
<section class="featured-section">
    <div class="container">
        <h2 class="featured-title">
            @if(request()->hasAny(['search', 'city', 'delivery']))
                Search Results
                @if($pharmacies->total() > 0)
                    ({{ $pharmacies->total() }} {{ Str::plural('pharmacy', $pharmacies->total()) }} found)
                @endif
            @else
                Trusted Pharmacies
            @endif
        </h2>

        @if($pharmacies->count() > 0)
            <div class="pharmacies-grid">
                @foreach($pharmacies as $pharmacy)
                    @php
                        // Profile image path
                        $profileImage = $pharmacy->profile_image
                            ? asset('storage/' . $pharmacy->profile_image)
                            : asset('images/default-pharmacy.png');
                    @endphp

                    <div class="pharmacy-card">
                        <div class="pharmacy-header">
                            {{-- Approved Badge --}}
                            @if($pharmacy->status == 'approved')
                                <div class="approved-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified</span>
                                </div>
                            @endif

                            {{-- Delivery Badge --}}
                            @if($pharmacy->delivery_available)
                                <div class="delivery-badge">
                                    <i class="fas fa-truck"></i>
                                    <span>Delivery</span>
                                </div>
                            @endif

                            <div class="pharmacy-avatar">
                                <img src="{{ $profileImage }}"
                                     alt="{{ $pharmacy->name }}"
                                     onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
                            </div>
                            <h3 class="pharmacy-name">
                                {{ $pharmacy->name ?? 'Pharmacy' }}
                            </h3>
                            <div class="pharmacy-regno">
                                Reg: {{ $pharmacy->registration_number ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="pharmacy-content">
                            <div class="pharmacy-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $pharmacy->city ?? 'Not Available' }}</span>
                            </div>

                            <div class="pharmacy-info">
                                <i class="fas fa-phone"></i>
                                <span>{{ $pharmacy->phone ?? 'Not Available' }}</span>
                            </div>

                            @if($pharmacy->operating_hours)
                            <div class="pharmacy-info">
                                <i class="fas fa-clock"></i>
                                <span>{{ Str::limit($pharmacy->operating_hours, 25) }}</span>
                            </div>
                            @endif

                            <div class="pharmacy-rating">
                                <div class="stars">
                                    @php
                                        $rating = $pharmacy->rating ?? 0;
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
                                    {{ number_format($rating, 1) }} ({{ $pharmacy->total_ratings ?? 0 }})
                                </span>
                            </div>

                            @if($pharmacy->pharmacist_name)
                            <div class="pharmacy-pharmacist">
                                <strong>Pharmacist:</strong> {{ $pharmacy->pharmacist_name }}
                                @if($pharmacy->pharmacist_license)
                                    <br><small>License: {{ $pharmacy->pharmacist_license }}</small>
                                @endif
                            </div>
                            @endif

                            <div class="pharmacy-actions">
                                <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
                                   class="btn-view-pharmacy">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pharmacies->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $pharmacies->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="no-results text-center py-5">
                <i class="fas fa-pills"></i>
                <h4>No pharmacies found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'city', 'delivery']))
                        Try adjusting your search criteria or browse all available pharmacies.
                    @else
                        No approved pharmacies are available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'city', 'delivery']))
                    <a href="{{ route('patient.pharmacies') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- Include Footer --}}
@include('partials.footer')
