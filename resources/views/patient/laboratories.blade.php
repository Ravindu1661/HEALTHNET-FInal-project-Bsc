@include('partials.header')

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: '';
    position: absolute; top:0; left:0; right:0; bottom:0;
    background: url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.1; z-index: 0;
}
.page-header .container { position: relative; z-index: 1; }
.page-title { font-size:2.2rem; font-weight:700; margin-bottom:0.8rem; text-shadow:2px 2px 4px rgba(0,0,0,0.3); }
.page-subtitle { font-size:1rem; opacity:0.9; margin-bottom:1.5rem; }

.search-filters {
    background: white; padding:1.5rem; border-radius:12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    margin: -2.5rem 0 2.5rem; position:relative; z-index:10;
}
.filter-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:0.8rem; }
.filter-label { display:block; font-size:0.75rem; font-weight:600; color:var(--primary-color); margin-bottom:0.4rem; }
.filter-input, .filter-select {
    width:100%; padding:0.55rem 0.7rem;
    border:1.5px solid #e9ecef; border-radius:6px; font-size:0.8rem; transition:all 0.3s;
}
.filter-input:focus, .filter-select:focus {
    border-color:#7b1fa2; outline:none; box-shadow:0 0 0 2px rgba(123,31,162,0.1);
}
.search-btn {
    background:#7b1fa2; color:white; border:none;
    padding:0.55rem 1.5rem; border-radius:18px; font-size:0.8rem; font-weight:600;
    cursor:pointer; transition:all 0.3s; width:100%;
}
.search-btn:hover { background:var(--primary-color); transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,0.15); }

/* Stats */
.stats-bar {
    background:white; border-radius:10px; padding:1rem 1.5rem;
    box-shadow:0 2px 10px rgba(0,0,0,0.06); margin-bottom:1.5rem;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;
}
.stats-bar .stat { display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; color:#555; }
.stats-bar .stat i { color:#7b1fa2; }

/* Lab Grid */
.labs-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1.3rem; }
.lab-card {
    background:white; border-radius:14px; overflow:hidden;
    box-shadow:0 3px 15px rgba(0,0,0,0.07); transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
    border:1px solid rgba(0,0,0,0.04);
    opacity:0; animation:fadeInUp 0.5s ease forwards;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.lab-card:nth-child(1){animation-delay:.05s} .lab-card:nth-child(2){animation-delay:.1s}
.lab-card:nth-child(3){animation-delay:.15s} .lab-card:nth-child(4){animation-delay:.2s}
.lab-card:nth-child(5){animation-delay:.25s} .lab-card:nth-child(6){animation-delay:.3s}
.lab-card:hover { transform:translateY(-5px); box-shadow:0 10px 28px rgba(123,31,162,0.15); }

.lab-header {
    padding:1.4rem 1rem 1rem; text-align:center;
    background:linear-gradient(135deg,#f3e5f5 0%,#e1bee7 100%); position:relative;
}
.verified-badge-card {
    position:absolute; top:0.7rem; left:0.7rem;
    background:#28a745; color:white; padding:0.2rem 0.6rem;
    border-radius:10px; font-size:0.6rem; font-weight:600;
    display:flex; align-items:center; gap:0.2rem;
}
.home-collection-badge {
    position:absolute; top:0.7rem; right:0.7rem;
    background:#2196F3; color:white; padding:0.2rem 0.6rem;
    border-radius:10px; font-size:0.6rem; font-weight:600;
}
.lab-avatar {
    width:72px; height:72px; border-radius:50%; margin:0 auto 0.7rem;
    overflow:hidden; border:3px solid white; box-shadow:0 3px 10px rgba(0,0,0,0.1);
    transition:transform 0.3s;
}
.lab-card:hover .lab-avatar { transform:scale(1.08); }
.lab-avatar img { width:100%; height:100%; object-fit:cover; }
.lab-name { font-size:1rem; font-weight:700; color:#4a148c; margin-bottom:0.2rem; line-height:1.3; }
.lab-regno { font-size:0.68rem; color:#888; }

.lab-content { padding:1rem; }
.lab-info { display:flex; align-items:flex-start; margin-bottom:0.45rem; font-size:0.73rem; color:#555; }
.lab-info i { width:16px; margin-right:0.4rem; color:#7b1fa2; font-size:0.7rem; margin-top:2px; flex-shrink:0; }

.lab-rating { display:flex; align-items:center; padding:0.5rem 0; border-top:1px solid #f0f0f0; border-bottom:1px solid #f0f0f0; margin:0.5rem 0; }
.stars { color:#ffc107; margin-right:0.4rem; font-size:0.7rem; }
.rating-text { font-size:0.7rem; color:#666; }

.test-count-badge {
    display:inline-flex; align-items:center; gap:0.3rem;
    background:linear-gradient(135deg,rgba(123,31,162,0.1),rgba(123,31,162,0.15));
    color:#7b1fa2; padding:0.3rem 0.7rem; border-radius:12px;
    font-size:0.7rem; font-weight:600; margin-bottom:0.7rem;
}

.lab-services {
    background:linear-gradient(135deg,rgba(123,31,162,0.07),rgba(123,31,162,0.12));
    color:#5c0d7a; padding:0.6rem 0.8rem; border-radius:8px;
    font-size:0.7rem; line-height:1.6; margin-bottom:0.8rem;
    max-height:55px; overflow:hidden;
}

.lab-actions { display:flex; gap:0.4rem; }
.btn-view-lab {
    flex:1; background:#7b1fa2; color:white; border:none;
    padding:0.6rem; border-radius:8px; font-size:0.75rem;
    text-decoration:none; text-align:center; font-weight:600;
    transition:all 0.2s; display:inline-flex; align-items:center; justify-content:center; gap:0.3rem;
}
.btn-view-lab:hover { background:#6a1b9a; color:white; transform:translateY(-1px); box-shadow:0 3px 8px rgba(123,31,162,0.3); }
.btn-book-lab {
    background:linear-gradient(135deg,#43a047,#2e7d32); color:white; border:none;
    padding:0.6rem 0.9rem; border-radius:8px; font-size:0.75rem;
    text-decoration:none; text-align:center; font-weight:600;
    transition:all 0.2s; display:inline-flex; align-items:center; gap:0.3rem;
}
.btn-book-lab:hover { background:linear-gradient(135deg,#388e3c,#1b5e20); color:white; transform:translateY(-1px); }

.no-results { text-align:center; padding:3rem; color:#666; }
.no-results i { font-size:4rem; color:#ce93d8; margin-bottom:1rem; }

/* Featured section bg */
.featured-section { background:#faf4fc; padding:2.5rem 0; min-height:400px; }
.featured-title { text-align:center; font-size:1.3rem; font-weight:700; color:var(--primary-color); margin-bottom:1.5rem; }

/* Pagination */
.page-link { color:#7b1fa2; }
.page-link:hover { background:#7b1fa2; color:white; border-color:#7b1fa2; }
.page-item.active .page-link { background:#7b1fa2; border-color:#7b1fa2; }

@media(max-width:768px) {
    .page-header { padding:6rem 0 2.5rem; }
    .filter-row { grid-template-columns:1fr; }
    .labs-grid { grid-template-columns:1fr; }
    .stats-bar { flex-direction:column; align-items:flex-start; }
}
</style>

{{-- Header --}}
<section class="page-header">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">
                    <i class="fas fa-flask me-2" style="opacity:0.85;"></i> Find Laboratories
                </h1>
                <p class="page-subtitle">Discover accredited medical laboratories across Sri Lanka for your diagnostic needs</p>
            </div>
        </div>
    </div>
</section>

{{-- Filters --}}
<section>
    <div class="container">
        <form method="GET" action="{{ route('patient.laboratories') }}">
            <div class="search-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-search"></i> Search Laboratory</label>
                        <input type="text" class="filter-input" name="search"
                               value="{{ request('search') }}" placeholder="Name, city or address">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-map-marker-alt"></i> City</label>
                        <select class="filter-select" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group" style="align-self:end;">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search me-1"></i> Find Laboratories
                        </button>
                    </div>
                    @if(request()->hasAny(['search','city']))
                    <div class="filter-group" style="align-self:end;">
                        <a href="{{ route('patient.laboratories') }}" class="search-btn d-block text-center text-white text-decoration-none" style="background:#6c757d;">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Labs List --}}
<section class="featured-section">
    <div class="container">

        {{-- Stats Bar --}}
        <div class="stats-bar">
            <div class="stat">
                <i class="fas fa-flask"></i>
                <span><strong>{{ $laboratories->total() }}</strong> {{ Str::plural('Laboratory', $laboratories->total()) }}
                    {{ request()->hasAny(['search','city']) ? 'found' : 'available' }}
                </span>
            </div>
            @if(request()->hasAny(['search','city']))
            <div class="stat">
                <i class="fas fa-filter"></i>
                <span>Filtered results</span>
            </div>
            @endif
            <div class="stat">
                <i class="fas fa-shield-alt"></i>
                <span>All verified &amp; accredited</span>
            </div>
        </div>

        @if($laboratories->count() > 0)
            <div class="labs-grid">
                @foreach($laboratories as $lab)
                    @php
                        $profileImage = $lab->profile_image
                            ? asset('storage/' . $lab->profile_image)
                            : asset('images/default-lab.png');

                        $services = is_array($lab->services)
                            ? $lab->services
                            : (json_decode($lab->services, true) ?? []);

                        $servicesDisplay = is_array($services) && count($services)
                            ? implode(', ', array_slice($services, 0, 3)) . (count($services) > 3 ? '...' : '')
                            : 'General Lab Services';

                        $rating = $lab->rating ?? 0;
                        $fullStars = floor($rating);
                        $halfStar  = ($rating - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp

                    <div class="lab-card">
                        <div class="lab-header">
                            <div class="verified-badge-card">
                                <i class="fas fa-check-circle"></i> Verified
                            </div>

                            @if($lab->home_collection ?? false)
                            <div class="home-collection-badge">
                                <i class="fas fa-home"></i> Home
                            </div>
                            @endif

                            <div class="lab-avatar">
                                <img src="{{ $profileImage }}" alt="{{ $lab->name }}"
                                     onerror="this.src='{{ asset('images/default-lab.png') }}'">
                            </div>

                            <h3 class="lab-name">{{ $lab->name }}</h3>
                            <div class="lab-regno">Reg: {{ $lab->registration_number ?? 'N/A' }}</div>
                        </div>

                        <div class="lab-content">
                            <div class="lab-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $lab->city ?? 'N/A' }}, {{ $lab->province ?? '' }}</span>
                            </div>
                            <div class="lab-info">
                                <i class="fas fa-phone"></i>
                                <span>{{ $lab->phone ?? 'Not Available' }}</span>
                            </div>
                            @if($lab->operating_hours)
                            <div class="lab-info">
                                <i class="fas fa-clock"></i>
                                <span>{{ Str::limit($lab->operating_hours, 28) }}</span>
                            </div>
                            @endif

                            <div class="lab-rating">
                                <div class="stars">
                                    @for($i=0;$i<$fullStars;$i++)<i class="fas fa-star"></i>@endfor
                                    @if($halfStar)<i class="fas fa-star-half-alt"></i>@endif
                                    @for($i=0;$i<$emptyStars;$i++)<i class="far fa-star"></i>@endfor
                                </div>
                                <span class="rating-text">
                                    {{ number_format($rating,1) }} ({{ $lab->total_ratings ?? 0 }} reviews)
                                </span>
                            </div>

                            <div class="lab-services">
                                <strong>Services:</strong> {{ $servicesDisplay }}
                            </div>

                            <div class="lab-actions">
                                <a href="{{ route('patient.laboratories.show', $lab->id) }}" class="btn-view-lab">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                                @auth
                                    @if(auth()->user()->usertype === 'patient')
                                    <a href="{{ route('patient.laboratories.show', $lab->id) }}#book-test" class="btn-book-lab">
                                        <i class="fas fa-calendar-plus"></i> Book
                                    </a>
                                    @endif
                                @else
                                <a href="{{ route('login') }}" class="btn-book-lab">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                                @endauth
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
            <div class="no-results">
                <i class="fas fa-flask"></i>
                <h4>No laboratories found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search','city']))
                        Try adjusting your search criteria.
                    @else
                        No approved laboratories available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search','city']))
                    <a href="{{ route('patient.laboratories') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-redo me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

@include('partials.footer')
