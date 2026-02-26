@include('partials.header')

<style>
/* ═══════════════════════════════════════════
   LABORATORIES PAGE — Teal Theme
═══════════════════════════════════════════ */

/* Page Header */
.labs-page-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.labs-page-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.07;
    z-index: 0;
}
.labs-page-header .container { position: relative; z-index: 1; }
.labs-page-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 45px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.labs-page-header h1 { font-size: 2.4rem; font-weight: 700; margin-bottom: 0.4rem; }
.labs-page-header p  { opacity: 0.9; font-size: 1rem; margin: 0; }

/* Main */
.labs-main {
    background: #f4f6f9;
    padding: 2rem 0 4rem;
    min-height: 600px;
}

/* ── Stats ── */
.labs-stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    display: flex;
    align-items: center;
    gap: 1.2rem;
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 1.5rem;
}
.labs-stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
.labs-stat-icon {
    width: 55px; height: 55px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.4rem; flex-shrink: 0;
}
.labs-stat-icon.total   { background: linear-gradient(135deg, #0c4a6e, #0891b2); }
.labs-stat-icon.teal    { background: linear-gradient(135deg, #0891b2, #06b6d4); }
.labs-stat-icon.green   { background: linear-gradient(135deg, #059669, #10b981); }
.labs-stat-icon.orange  { background: linear-gradient(135deg, #d97706, #f59e0b); }
.labs-stat-label { font-size: 0.82rem; color: #888; font-weight: 500; margin-bottom: 0.2rem; }
.labs-stat-value { font-size: 1.9rem; font-weight: 700; color: #0c4a6e; line-height: 1; }

/* ── Search & Filter Bar ── */
.labs-filter-card {
    background: white;
    border-radius: 15px;
    padding: 1.4rem 1.6rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
}
.labs-filter-card form { display: flex; gap: 0.8rem; flex-wrap: wrap; align-items: flex-end; }
.labs-filter-label {
    font-size: 0.82rem; font-weight: 600;
    color: #0c4a6e; margin-bottom: 0.35rem; display: block;
}
.labs-filter-input,
.labs-filter-select {
    padding: 0.65rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 0.88rem;
    color: #333;
    transition: all 0.3s;
    background: white;
}
.labs-filter-input:focus,
.labs-filter-select:focus {
    border-color: #0891b2;
    outline: none;
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
}
.labs-filter-input  { min-width: 240px; flex: 1; }
.labs-filter-select { min-width: 160px; }
.labs-filter-btn {
    background: linear-gradient(135deg, #0891b2, #0c4a6e);
    color: white; border: none;
    padding: 0.65rem 1.4rem;
    border-radius: 10px;
    font-size: 0.88rem; font-weight: 600;
    cursor: pointer; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 0.4rem;
    box-shadow: 0 3px 10px rgba(8,145,178,0.3);
    white-space: nowrap;
}
.labs-filter-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(8,145,178,0.4); }
.labs-filter-reset {
    background: white; color: #e74c3c;
    border: 2px solid #e74c3c;
    padding: 0.65rem 1.2rem;
    border-radius: 10px;
    font-size: 0.85rem; font-weight: 600;
    cursor: pointer; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 0.4rem;
    text-decoration: none; white-space: nowrap;
}
.labs-filter-reset:hover { background: #e74c3c; color: white; }

/* ── Lab Cards Grid ── */
.labs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }

.lab-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex; flex-direction: column;
    border-top: 4px solid #0891b2;
}
.lab-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(8,145,178,0.15);
}

/* Card Top */
.lab-card-top {
    padding: 1.4rem 1.4rem 0;
    display: flex; gap: 1rem; align-items: flex-start;
}
.lab-avatar {
    width: 70px; height: 70px;
    border-radius: 12px; object-fit: cover;
    border: 2.5px solid #e0f2fe;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    flex-shrink: 0;
}
.lab-avatar-placeholder {
    width: 70px; height: 70px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #0891b2;
    border: 2.5px solid #e0f2fe;
    flex-shrink: 0;
}
.lab-card-info { flex: 1; min-width: 0; }
.lab-name {
    font-size: 1.05rem; font-weight: 700;
    color: #0c4a6e; margin-bottom: 0.25rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.lab-rating-row {
    display: flex; align-items: center; gap: 0.5rem;
    margin-bottom: 0.5rem;
}
.lab-stars { color: #f59e0b; font-size: 0.78rem; }
.lab-rating-num { font-weight: 700; font-size: 0.88rem; color: #333; }
.lab-rating-cnt { font-size: 0.75rem; color: #aaa; }
.lab-meta {
    display: flex; flex-direction: column; gap: 0.3rem;
    font-size: 0.8rem; color: #666;
}
.lab-meta-item { display: flex; align-items: center; gap: 0.4rem; }
.lab-meta-item i { color: #0891b2; width: 14px; font-size: 0.75rem; }

/* Card Badges */
.lab-card-badges { padding: 0.8rem 1.4rem 0; display: flex; flex-wrap: wrap; gap: 0.4rem; }
.lab-badge {
    padding: 0.25rem 0.65rem; border-radius: 10px;
    font-size: 0.72rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 0.3rem;
}
.lab-badge-teal    { background: #e0f2fe; color: #0369a1; }
.lab-badge-green   { background: #dcfce7; color: #166534; }
.lab-badge-orange  { background: #fef3c7; color: #92400e; }
.lab-badge-verified{ background: #d1fae5; color: #065f46; }

/* Services Preview */
.lab-services-preview {
    padding: 0.8rem 1.4rem 0;
    display: flex; flex-wrap: wrap; gap: 0.35rem;
}
.lab-service-pill {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    color: #0369a1; padding: 0.2rem 0.6rem;
    border-radius: 8px; font-size: 0.72rem; font-weight: 500;
}

/* Card Footer */
.lab-card-footer {
    margin-top: auto;
    padding: 1rem 1.4rem;
    border-top: 1px solid #f0f9ff;
    display: flex; gap: 0.6rem; align-items: center;
}
.btn-lab-view {
    flex: 1;
    background: linear-gradient(135deg, #0891b2, #0c4a6e);
    color: white; border: none;
    padding: 0.6rem 1rem; border-radius: 10px;
    font-size: 0.84rem; font-weight: 600;
    text-decoration: none; transition: all 0.3s;
    display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    box-shadow: 0 3px 10px rgba(8,145,178,0.3);
}
.btn-lab-view:hover { color: white; filter: brightness(1.1); transform: translateY(-1px); }
.btn-lab-book {
    background: #059669; color: white; border: none;
    padding: 0.6rem 1rem; border-radius: 10px;
    font-size: 0.84rem; font-weight: 600;
    text-decoration: none; transition: all 0.3s;
    display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    box-shadow: 0 3px 10px rgba(5,150,105,0.3);
}
.btn-lab-book:hover { color: white; background: #047857; transform: translateY(-1px); }
.btn-lab-login {
    flex: 1;
    background: white; color: #0891b2;
    border: 2px solid #0891b2;
    padding: 0.55rem 1rem; border-radius: 10px;
    font-size: 0.84rem; font-weight: 600;
    text-decoration: none; transition: all 0.3s;
    display: flex; align-items: center; justify-content: center; gap: 0.4rem;
}
.btn-lab-login:hover { background: #0891b2; color: white; }

/* ── Empty State ── */
.labs-empty {
    background: white; border-radius: 15px;
    padding: 4rem 2rem; text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    grid-column: 1/-1;
}
.labs-empty i  { font-size: 4rem; color: #bae6fd; margin-bottom: 1rem; display: block; }
.labs-empty h4 { color: #aaa; font-weight: 600; margin-bottom: 0.5rem; }
.labs-empty p  { color: #bbb; font-size: 0.9rem; margin-bottom: 1.5rem; }

/* ── Result Info ── */
.labs-result-info {
    font-size: 0.88rem; color: #777; font-weight: 500;
    margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;
}
.labs-result-info i { color: #0891b2; }

/* ── Alert ── */
.labs-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.8rem;
    font-size: 0.9rem; font-weight: 500;
}
.labs-alert.success { background: #dcfce7; color: #166534; border-left: 5px solid #059669; }
.labs-alert.error   { background: #fee2e2; color: #991b1b; border-left: 5px solid #dc2626; }
.labs-alert.info    { background: #e0f2fe; color: #0c4a6e; border-left: 5px solid #0891b2; }

/* ── Pagination ── */
.labs-pagination { display: flex; justify-content: center; margin-top: 1.5rem; }
.labs-pagination .page-link {
    border-radius: 8px !important;
    border: 2px solid #e9ecef;
    color: #0891b2; font-weight: 600;
    padding: 0.5rem 0.9rem; font-size: 0.85rem;
    transition: all 0.2s;
}
.labs-pagination .page-link:hover,
.labs-pagination .page-item.active .page-link {
    background: #0891b2; border-color: #0891b2; color: white;
}

/* ── Home Collection Badge ── */
.home-collection-badge {
    position: absolute; top: 1rem; right: 1rem;
    background: linear-gradient(135deg, #0891b2, #06b6d4);
    color: white; padding: 0.25rem 0.65rem;
    border-radius: 10px; font-size: 0.7rem; font-weight: 700;
    display: flex; align-items: center; gap: 0.3rem;
}

/* Responsive */
@media (max-width: 768px) {
    .labs-page-header { padding: 5rem 0 2.5rem; }
    .labs-page-header h1 { font-size: 1.7rem; }
    .labs-grid { grid-template-columns: 1fr; }
    .labs-filter-card form { flex-direction: column; }
    .labs-filter-input, .labs-filter-select { min-width: 100%; }
}
</style>

{{-- ═══════════ PAGE HEADER ═══════════ --}}
<section class="labs-page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.15);padding:0.4rem 0.9rem;border-radius:20px;font-size:0.82rem;margin-bottom:1rem;">
                    <i class="fas fa-flask"></i> Medical Laboratories
                </div>
                <h1><i class="fas fa-microscope me-2" style="opacity:0.85;"></i>Find Laboratories</h1>
                <p>Discover certified labs, book tests, and get your reports delivered securely online.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                @auth
                    @if(auth()->user()->user_type === 'patient')
                    <a href="{{ route('patient.lab-orders.index') }}"
                       style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.2);
                              backdrop-filter:blur(8px);color:white;padding:0.8rem 1.6rem;
                              border-radius:25px;text-decoration:none;font-weight:600;font-size:0.9rem;
                              border:1.5px solid rgba(255,255,255,0.35);transition:all 0.3s;">
                        <i class="fas fa-list-alt"></i> My Lab Orders
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ MAIN CONTENT ═══════════ --}}
<section class="labs-main">
    <div class="container">

        {{-- Session Alerts --}}
        @if(session('success'))
        <div class="labs-alert success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="labs-alert error">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- ── STAT CARDS ── --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="labs-stat-card">
                    <div class="labs-stat-icon total"><i class="fas fa-flask"></i></div>
                    <div>
                        <div class="labs-stat-label">Total Labs</div>
                        <div class="labs-stat-value">{{ $laboratories->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="labs-stat-card">
                    <div class="labs-stat-icon teal"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="labs-stat-label">Cities</div>
                        <div class="labs-stat-value">{{ $cities->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="labs-stat-card">
                    <div class="labs-stat-icon green"><i class="fas fa-home"></i></div>
                    <div>
                        <div class="labs-stat-label">Home Collection</div>
                        <div class="labs-stat-value">
                            {{ $laboratories->getCollection()->where('home_collection', true)->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="labs-stat-card">
                    <div class="labs-stat-icon orange"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="labs-stat-label">Top Rated</div>
                        <div class="labs-stat-value">
                            {{ $laboratories->getCollection()->where('rating', '>=', 4)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SEARCH & FILTER ── --}}
        <div class="labs-filter-card">
            <form method="GET" action="{{ route('patient.laboratories') }}">
                <div style="flex:1;min-width:240px;">
                    <label class="labs-filter-label"><i class="fas fa-search me-1"></i> Search</label>
                    <input type="text"
                           name="search"
                           class="labs-filter-input"
                           placeholder="Search by lab name, city, address..."
                           value="{{ request('search') }}">
                </div>

                <div>
                    <label class="labs-filter-label"><i class="fas fa-map-marker-alt me-1"></i> City</label>
                    <select name="city" class="labs-filter-select">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="labs-filter-btn">
                    <i class="fas fa-search"></i> Search
                </button>

                @if(request()->hasAny(['search', 'city']))
                <a href="{{ route('patient.laboratories') }}" class="labs-filter-reset">
                    <i class="fas fa-redo-alt"></i> Reset
                </a>
                @endif
            </form>
        </div>

        {{-- ── RESULT INFO ── --}}
        @if($laboratories->total() > 0)
        <div class="labs-result-info">
            <i class="fas fa-info-circle"></i>
            Showing <strong>{{ $laboratories->firstItem() }}–{{ $laboratories->lastItem() }}</strong>
            of <strong>{{ $laboratories->total() }}</strong> laboratories
            @if(request('search')) matching "<em>{{ request('search') }}</em>" @endif
            @if(request('city')) in <em>{{ request('city') }}</em> @endif
        </div>
        @endif

        {{-- ── LAB CARDS ── --}}
        <div class="labs-grid">
            @forelse($laboratories as $lab)
            @php
                $img      = $lab->profile_image ? asset('storage/' . $lab->profile_image) : null;
                $services = is_array($lab->services) ? $lab->services : (json_decode($lab->services, true) ?? []);
                $rating   = floatval($lab->rating ?? 0);
                $stars    = round($rating * 2) / 2;
            @endphp

            <div class="lab-card" style="position:relative;">

                {{-- Home Collection Badge --}}
                @if(!empty($lab->home_collection))
                <div class="home-collection-badge">
                    <i class="fas fa-home"></i> Home Collection
                </div>
                @endif

                {{-- Card Top --}}
                <div class="lab-card-top">
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $lab->name }}" class="lab-avatar"
                             onerror="this.parentElement.innerHTML='<div class=\'lab-avatar-placeholder\'><i class=\'fas fa-flask\'></i></div>'">
                    @else
                        <div class="lab-avatar-placeholder"><i class="fas fa-flask"></i></div>
                    @endif

                    <div class="lab-card-info">
                        <div class="lab-name">{{ $lab->name }}</div>

                        {{-- Star Rating --}}
                        <div class="lab-rating-row">
                            <div class="lab-stars">
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= floor($stars))
                                        <i class="fas fa-star"></i>
                                    @elseif($s == ceil($stars) && fmod($stars, 1) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="lab-rating-num">{{ number_format($rating, 1) }}</span>
                            <span class="lab-rating-cnt">({{ $lab->total_ratings ?? 0 }} reviews)</span>
                        </div>

                        <div class="lab-meta">
                            @if($lab->city)
                            <div class="lab-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $lab->city }}{{ $lab->province ? ', '.$lab->province : '' }}
                            </div>
                            @endif
                            @if($lab->phone)
                            <div class="lab-meta-item">
                                <i class="fas fa-phone"></i> {{ $lab->phone }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Status Badges --}}
                <div class="lab-card-badges">
                    <span class="lab-badge lab-badge-verified">
                        <i class="fas fa-check-circle"></i> Certified
                    </span>
                    @if($lab->registration_number)
                    <span class="lab-badge lab-badge-teal">
                        <i class="fas fa-id-badge"></i> Reg: {{ $lab->registration_number }}
                    </span>
                    @endif
                </div>

                {{-- Services Preview --}}
                @if(!empty($services))
                <div class="lab-services-preview">
                    @foreach(array_slice($services, 0, 3) as $svc)
                        <span class="lab-service-pill">{{ is_array($svc) ? ($svc['name'] ?? $svc[0] ?? '') : $svc }}</span>
                    @endforeach
                    @if(count($services) > 3)
                        <span class="lab-service-pill" style="background:#f0fdf4;color:#166534;">
                            +{{ count($services) - 3 }} more
                        </span>
                    @endif
                </div>
                @endif

                {{-- Address --}}
                @if($lab->address)
                <div style="padding:0.5rem 1.4rem 0;font-size:0.78rem;color:#888;display:flex;align-items:flex-start;gap:0.4rem;">
                    <i class="fas fa-location-dot" style="color:#0891b2;margin-top:2px;flex-shrink:0;"></i>
                    {{ Str::limit($lab->address, 70) }}
                </div>
                @endif

                {{-- Card Footer --}}
                <div class="lab-card-footer">
                    <a href="{{ route('patient.laboratories.show', $lab->id) }}" class="btn-lab-view">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    @auth
                        @if(auth()->user()->user_type === 'patient')
                        <a href="{{ route('patient.lab-orders.create', $lab->id) }}" class="btn-lab-book">
                            <i class="fas fa-calendar-plus"></i> Book
                        </a>
                        @endif
                    @else
                    <a href="{{ route('login') }}" class="btn-lab-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    @endauth
                </div>

            </div>
            @empty
            <div class="labs-empty">
                <i class="fas fa-flask"></i>
                <h4>No laboratories found</h4>
                <p>
                    @if(request()->hasAny(['search', 'city']))
                        No labs match your search. Try different filters.
                    @else
                        No approved laboratories available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'city']))
                <a href="{{ route('patient.laboratories') }}"
                   style="display:inline-flex;align-items:center;gap:0.5rem;background:linear-gradient(135deg,#0891b2,#0c4a6e);
                          color:white;padding:0.8rem 1.6rem;border-radius:25px;text-decoration:none;
                          font-weight:600;font-size:0.9rem;">
                    <i class="fas fa-redo-alt"></i> Clear Filters
                </a>
                @endif
            </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ── --}}
        @if($laboratories->hasPages())
        <div class="labs-pagination">
            {{ $laboratories->withQueryString()->links() }}
        </div>
        @endif

    </div>
</section>

@include('partials.footer')

<script>
// Auto-hide alerts after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.labs-alert').forEach(el => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
