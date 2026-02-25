@include('partials.header')

<style>
.lab-profile-header {
    background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 50%, #9c27b0 100%);
    padding: 7rem 0 3rem; color:white; position:relative; overflow:hidden;
}
.lab-profile-header::before {
    content:''; position:absolute; inset:0;
    background:url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity:0.08; z-index:0;
}
.lab-profile-header .container { position:relative; z-index:1; }

.profile-avatar {
    width:120px; height:120px; border-radius:50%; object-fit:cover;
    border:5px solid white; box-shadow:0 8px 20px rgba(0,0,0,0.25); margin-bottom:1rem;
}
.profile-name { font-size:1.9rem; font-weight:700; margin-bottom:0.4rem; }
.verified-pill {
    background:#28a745; color:white; padding:0.3rem 0.9rem;
    border-radius:20px; font-size:0.8rem; font-weight:600;
    display:inline-flex; align-items:center; gap:0.4rem; margin-left:0.5rem;
}
.home-pill {
    background:#2196F3; color:white; padding:0.3rem 0.9rem;
    border-radius:20px; font-size:0.8rem; font-weight:600;
    display:inline-flex; align-items:center; gap:0.4rem; margin-left:0.3rem;
}
.rating-hero {
    display:inline-flex; align-items:center; gap:0.8rem;
    background:rgba(255,255,255,0.12); backdrop-filter:blur(6px);
    padding:0.6rem 1.4rem; border-radius:30px; margin-top:0.8rem;
}
.rating-hero .stars { color:#ffc107; font-size:1rem; }
.rating-hero .num { font-size:1.3rem; font-weight:700; }
.rating-hero .cnt { font-size:0.82rem; opacity:0.85; }

/* Info Cards */
.info-card {
    background:white; border-radius:14px; padding:1.5rem;
    box-shadow:0 4px 16px rgba(0,0,0,0.07); margin-bottom:1.5rem;
    transition:all 0.3s;
}
.info-card:hover { box-shadow:0 6px 22px rgba(123,31,162,0.12); transform:translateY(-2px); }
.info-card h5 {
    color:#7b1fa2; font-weight:700; margin-bottom:1rem;
    padding-bottom:0.6rem; border-bottom:2px solid #f3e5f5;
    display:flex; align-items:center; gap:0.5rem; font-size:1rem;
}
.info-row { display:flex; padding:0.7rem 0; border-bottom:1px solid #f7f7f7; }
.info-row:last-child { border-bottom:none; }
.info-label {
    font-weight:600; color:#777; min-width:140px;
    display:flex; align-items:center; font-size:0.88rem;
}
.info-label i { width:20px; margin-right:0.5rem; color:#7b1fa2; }
.info-value { flex:1; color:#333; font-size:0.9rem; }

/* Services */
.services-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
    gap:0.7rem; margin-top:0.8rem;
}
.service-badge {
    background:linear-gradient(135deg,#f3e5f5,#e1bee7);
    color:#6a1b9a; padding:0.6rem 1rem; border-radius:8px;
    font-size:0.82rem; font-weight:600;
    display:flex; align-items:center; gap:0.5rem; transition:all 0.3s;
}
.service-badge:hover { background:linear-gradient(135deg,#e1bee7,#ce93d8); transform:translateX(4px); }

/* Tests Table */
.tests-table th { background:#f3e5f5; color:#7b1fa2; font-size:0.8rem; border:none; }
.tests-table td { font-size:0.85rem; vertical-align:middle; }

/* Packages */
.pkg-card {
    border:1.5px solid #e1bee7; border-radius:10px; padding:1rem;
    height:100%; transition:all 0.3s;
}
.pkg-card:hover { border-color:#7b1fa2; box-shadow:0 3px 10px rgba(123,31,162,0.1); }

/* Previous Orders */
.order-pill {
    display:inline-flex; align-items:center; gap:0.3rem;
    padding:0.25rem 0.7rem; border-radius:12px; font-size:0.72rem; font-weight:600;
}
.order-pill.pending          { background:#fff3e0; color:#e65100; }
.order-pill.sample_collected { background:#e3f2fd; color:#0d47a1; }
.order-pill.processing       { background:#e8eaf6; color:#283593; }
.order-pill.completed        { background:#e8f5e9; color:#1b5e20; }
.order-pill.cancelled        { background:#fce4ec; color:#880e4f; }

/* Action Buttons */
.action-btn {
    display:flex; align-items:center; justify-content:center; gap:0.6rem;
    padding:0.9rem 1.5rem; border-radius:10px; text-decoration:none;
    font-weight:700; font-size:0.9rem; margin-bottom:0.8rem;
    transition:all 0.3s; border:none; cursor:pointer; width:100%;
}
.action-btn:hover { transform:translateY(-2px); filter:brightness(1.08); }
.action-btn-purple {
    background:linear-gradient(135deg,#7b1fa2,#4a148c); color:white;
    box-shadow:0 4px 15px rgba(123,31,162,0.3);
}
.action-btn-purple:hover { color:white; }
.action-btn-blue {
    background:linear-gradient(135deg,#1565c0,#0d47a1); color:white;
    box-shadow:0 4px 15px rgba(21,101,192,0.3);
}
.action-btn-blue:hover { color:white; }
.action-btn-grey {
    background:#6c757d; color:white;
    box-shadow:0 4px 10px rgba(0,0,0,0.15);
}
.action-btn-grey:hover { color:white; background:#5a6268; }
.action-btn-outline {
    background:white; color:#7b1fa2;
    border:2px solid #7b1fa2 !important;
    box-shadow:none;
}
.action-btn-outline:hover { background:#7b1fa2; color:white; }

/* Action card highlight */
.action-highlight-card {
    background:linear-gradient(135deg,#f3e5f5 0%,#fce4ec 100%);
    border:none; border-radius:14px; padding:1.5rem;
    box-shadow:0 4px 16px rgba(123,31,162,0.1);
    margin-bottom:1.5rem;
}
.action-highlight-card h5 {
    color:#7b1fa2; font-weight:700; margin-bottom:0.5rem;
    padding-bottom:0.6rem; border-bottom:2px solid rgba(123,31,162,0.15);
    display:flex; align-items:center; gap:0.5rem; font-size:1rem;
}

/* Back btn header */
.btn-back-lab {
    display:inline-flex; align-items:center; gap:0.5rem;
    color:rgba(255,255,255,0.9); text-decoration:none;
    font-size:0.88rem; margin-bottom:1rem; transition:all 0.3s;
}
.btn-back-lab:hover { color:white; transform:translateX(-4px); }

@media(max-width:768px) {
    .profile-name { font-size:1.4rem; }
    .info-row { flex-direction:column; gap:0.4rem; }
    .info-label { min-width:auto; }
}
</style>

{{-- ═══ HEADER ═══ --}}
<section class="lab-profile-header">
    <div class="container">
        <a href="{{ route('patient.laboratories') }}" class="btn-back-lab">
            <i class="fas fa-arrow-left"></i> Back to Laboratories
        </a>
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <img src="{{ $laboratory->profile_image
                        ? asset('storage/'.$laboratory->profile_image)
                        : asset('images/default-lab.png') }}"
                     alt="{{ $laboratory->name }}" class="profile-avatar"
                     onerror="this.src='{{ asset('images/default-lab.png') }}'">

                <h1 class="profile-name">
                    {{ $laboratory->name }}
                    <span class="verified-pill"><i class="fas fa-check-circle"></i> Verified</span>
                    @if($laboratory->home_collection ?? false)
                        <span class="home-pill"><i class="fas fa-home"></i> Home Collection</span>
                    @endif
                </h1>

                <p style="opacity:0.88;font-size:0.95rem;margin:0.3rem 0;">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $laboratory->address }}, {{ $laboratory->city }}, {{ $laboratory->province }}
                </p>

                {{-- Rating --}}
                @php
                    $rating     = $laboratory->rating ?? 0;
                    $fullStars  = floor($rating);
                    $halfStar   = ($rating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp
                <div class="rating-hero">
                    <div class="stars">
                        @for($i=0;$i<$fullStars;$i++)<i class="fas fa-star"></i>@endfor
                        @if($halfStar)<i class="fas fa-star-half-alt"></i>@endif
                        @for($i=0;$i<$emptyStars;$i++)<i class="far fa-star"></i>@endfor
                    </div>
                    <span class="num">{{ number_format($rating,1) }}</span>
                    <span class="cnt">{{ $laboratory->total_ratings ?? 0 }} reviews</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ BODY ═══ --}}
<section style="background:#faf4fc; padding:2.5rem 0;">
    <div class="container">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">

            {{-- ═══ LEFT COLUMN ═══ --}}
            <div class="col-lg-8">

                {{-- Contact Info --}}
                <div class="info-card">
                    <h5><i class="fas fa-address-card"></i> Contact Information</h5>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-phone"></i> Phone</div>
                        <div class="info-value">{{ $laboratory->phone ?? 'Not Available' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                        <div class="info-value">{{ $laboratory->email ?? 'Not Available' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                        <div class="info-value">
                            {{ $laboratory->address }}, {{ $laboratory->city }},
                            {{ $laboratory->province }}
                            @if($laboratory->postal_code) – {{ $laboratory->postal_code }}@endif
                        </div>
                    </div>
                    @if($laboratory->operating_hours)
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-clock"></i> Hours</div>
                        <div class="info-value">{{ $laboratory->operating_hours }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-home"></i> Home Collection</div>
                        <div class="info-value">
                            @if($laboratory->home_collection ?? false)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Available
                                </span>
                            @else
                                <span class="badge bg-secondary">Not Available</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Services --}}
                @if(!empty($services) && count($services))
                <div class="info-card">
                    <h5><i class="fas fa-flask"></i> Services Offered</h5>
                    <div class="services-grid">
                        @foreach($services as $service)
                        <div class="service-badge">
                            <i class="fas fa-check-circle"></i> {{ trim($service) }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Available Tests --}}
                @if(isset($labTests) && $labTests->count() > 0)
                <div class="info-card">
                    <h5><i class="fas fa-vials"></i> Available Tests</h5>
                    <div class="table-responsive">
                        <table class="table table-hover tests-table mb-0">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Category</th>
                                    <th>Sample Type</th>
                                    <th>Duration</th>
                                    <th class="text-end">Price (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($labTests as $test)
                                <tr>
                                    <td><strong>{{ $test->test_name }}</strong></td>
                                    <td>
                                        <span class="badge" style="background:#f3e5f5;color:#7b1fa2;">
                                            {{ $test->test_category ?? 'General' }}
                                        </span>
                                    </td>
                                    <td>{{ $test->sample_type ?? 'N/A' }}</td>
                                    <td>{{ $test->duration_hours ? $test->duration_hours.'h' : 'N/A' }}</td>
                                    <td class="text-end">
                                        <strong style="color:#2e7d32;">
                                            {{ number_format($test->price ?? 0, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Packages --}}
                @if(isset($labPackages) && $labPackages->count() > 0)
                <div class="info-card">
                    <h5><i class="fas fa-box-open"></i> Test Packages</h5>
                    <div class="row g-3">
                        @foreach($labPackages as $pkg)
                        <div class="col-md-6">
                            <div class="pkg-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong style="color:#4a148c;font-size:0.88rem;">
                                        {{ $pkg->package_name }}
                                    </strong>
                                    @if(($pkg->discount_percentage ?? 0) > 0)
                                    <span class="badge bg-danger">
                                        {{ $pkg->discount_percentage }}% OFF
                                    </span>
                                    @endif
                                </div>
                                @if($pkg->description)
                                <p style="font-size:0.78rem;color:#666;margin-bottom:0.5rem;">
                                    {{ $pkg->description }}
                                </p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <div>
                                        @if(($pkg->discount_percentage ?? 0) > 0)
                                        <del style="font-size:0.75rem;color:#999;">
                                            Rs. {{ number_format($pkg->price, 2) }}
                                        </del>
                                        <strong style="color:#2e7d32;font-size:0.95rem;display:block;">
                                            Rs. {{ number_format($pkg->discounted_price ?? $pkg->price, 2) }}
                                        </strong>
                                        @else
                                        <strong style="color:#2e7d32;">
                                            Rs. {{ number_format($pkg->price, 2) }}
                                        </strong>
                                        @endif
                                    </div>
                                    <span style="font-size:0.72rem;color:#7b1fa2;font-weight:600;">
                                        {{ $pkg->tests->count() }} tests
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- About --}}
                @if($laboratory->description)
                <div class="info-card">
                    <h5><i class="fas fa-info-circle"></i> About</h5>
                    <p style="line-height:1.75;color:#444;margin:0;">
                        {{ $laboratory->description }}
                    </p>
                </div>
                @endif

                {{-- Previous Orders (patient only) --}}
                @if(isset($previousOrders) && $previousOrders && $previousOrders->count() > 0)
                <div class="info-card">
                    <h5><i class="fas fa-history"></i> Your Previous Orders Here</h5>
                    @foreach($previousOrders as $order)
                    <div style="border:1px solid #f3e5f5;border-radius:8px;padding:0.8rem;margin-bottom:0.7rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong style="font-size:0.85rem;color:#4a148c;">
                                    {{ $order->order_number }}
                                </strong>
                                <span class="order-pill {{ $order->status }} ms-2">
                                    {{ ucwords(str_replace('_',' ',$order->status)) }}
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span style="font-size:0.8rem;color:#666;">
                                {{ $order->items->count() }} test(s) ·
                                Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                            </span>
                            <a href="{{ route('patient.lab-orders.show', $order->id) }}"
                               style="font-size:0.78rem;color:#7b1fa2;text-decoration:none;font-weight:600;">
                                View <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center mt-2">
                        <a href="{{ route('patient.lab-orders.index') }}"
                           style="font-size:0.82rem;color:#7b1fa2;text-decoration:none;">
                            <i class="fas fa-list me-1"></i>View all my lab orders
                        </a>
                    </div>
                </div>
                @endif

            </div>

            {{-- ═══ RIGHT COLUMN ═══ --}}
            <div class="col-lg-4">

                {{-- Registration --}}
    <div class="info-card">
        <h5><i class="fas fa-certificate"></i> Registration</h5>
        <div class="info-row">
            <div class="info-label"><i class="fas fa-id-card"></i> Reg. No.</div>
            <div class="info-value">
                <span class="badge" style="background:#f3e5f5;color:#7b1fa2;font-size:0.8rem;">
                    {{ $laboratory->registration_number }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label"><i class="fas fa-check-circle"></i> Status</div>
            <div class="info-value">
                <span class="badge bg-success">{{ ucfirst($laboratory->status) }}</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════
         ACTION CARD
    ══════════════════════════════════ --}}
    @php
        $isPatient = Auth::check() && (
            Auth::user()->usertype === 'patient' ||
            Auth::user()->role    === 'patient'  ||
            Auth::user()->user_type === 'patient'
        );
    @endphp

    @if($isPatient)

    {{-- ✅ Patient — Full Action Panel --}}
    <div style="background:linear-gradient(135deg,#f3e5f5,#fce4ec);border-radius:14px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 4px 16px rgba(123,31,162,0.1);">

        <h5 style="color:#7b1fa2;font-weight:700;font-size:0.95rem;border-bottom:2px solid rgba(123,31,162,0.15);padding-bottom:0.6rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-hand-pointer"></i> Patient Actions
        </h5>

        <p style="font-size:0.78rem;color:#666;margin-bottom:1.2rem;line-height:1.6;">
            Submit your doctor's prescription, book a test slot or walk in, pay online & receive your report via the system, Email, or WhatsApp.
        </p>

        {{-- 1. Book Lab Test (with prescription) --}}
        <a href="{{ route('patient.lab-orders.create', $laboratory->id) }}"
           style="display:flex;align-items:center;gap:0.7rem;
                  background:linear-gradient(135deg,#7b1fa2,#4a148c);
                  color:white;padding:0.9rem 1.2rem;border-radius:10px;
                  text-decoration:none;font-weight:700;font-size:0.88rem;
                  margin-bottom:0.8rem;
                  box-shadow:0 4px 14px rgba(123,31,162,0.3);
                  transition:all 0.3s;">
            <i class="fas fa-calendar-plus" style="font-size:1rem;"></i>
            <div>
                <div>Book Lab Test</div>
                <div style="font-size:0.7rem;opacity:0.85;font-weight:400;">Upload prescription · Schedule slot</div>
            </div>
        </a>

        {{-- 2. My Lab Reports + Payment --}}
        <a href="{{ route('patient.lab-orders.index') }}"
           style="display:flex;align-items:center;gap:0.7rem;
                  background:linear-gradient(135deg,#1565c0,#0d47a1);
                  color:white;padding:0.9rem 1.2rem;border-radius:10px;
                  text-decoration:none;font-weight:700;font-size:0.88rem;
                  margin-bottom:0.8rem;
                  box-shadow:0 4px 14px rgba(21,101,192,0.3);
                  transition:all 0.3s;">
            <i class="fas fa-file-medical-alt" style="font-size:1rem;"></i>
            <div>
                <div>My Lab Reports</div>
                <div style="font-size:0.7rem;opacity:0.85;font-weight:400;">Download PDF · Online payment</div>
            </div>
        </a>

        {{-- 3. Back --}}
        <a href="{{ route('patient.laboratories') }}"
           style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                  background:#6c757d;color:white;padding:0.75rem 1.2rem;
                  border-radius:10px;text-decoration:none;font-weight:600;
                  font-size:0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Laboratories
        </a>
    </div>

    {{-- Contact Lab Options --}}
    <div class="info-card">
        <h5><i class="fas fa-headset"></i> Contact Lab Directly</h5>
        <p style="font-size:0.75rem;color:#888;margin-bottom:1rem;">
            Get your report via Email or WhatsApp, or call the lab.
        </p>

        @php
            $labPhone = $laboratory->phone ?? null;
            $labEmail = $laboratory->email ?? null;
            $waPhone  = '';
            if ($labPhone) {
                $raw = preg_replace('/[^0-9]/', '', $labPhone);
                $waPhone = str_starts_with($raw, '0') ? '94'.substr($raw,1) : $raw;
            }
            $waMsg = urlencode(
                'Hello '.($laboratory->name ?? 'Lab').
                ', I am a HealthNet patient. I would like to inquire about my lab tests and reports.'
            );
        @endphp

        <div style="display:flex;flex-direction:column;gap:0.6rem;">

            @if($labPhone)
            {{-- WhatsApp --}}
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}"
               target="_blank"
               style="display:flex;align-items:center;gap:0.7rem;
                      background:#e8f5e9;color:#1b5e20;padding:0.7rem 1rem;
                      border-radius:8px;text-decoration:none;font-weight:600;
                      font-size:0.82rem;transition:all 0.3s;border:1.5px solid #a5d6a7;">
                <i class="fab fa-whatsapp" style="font-size:1.2rem;color:#25D366;"></i>
                <div>
                    <div>WhatsApp Lab</div>
                    <div style="font-size:0.68rem;opacity:0.75;">Request report via WhatsApp</div>
                </div>
            </a>

            {{-- Call --}}
            <a href="tel:{{ $labPhone }}"
               style="display:flex;align-items:center;gap:0.7rem;
                      background:#f3e5f5;color:#4a148c;padding:0.7rem 1rem;
                      border-radius:8px;text-decoration:none;font-weight:600;
                      font-size:0.82rem;transition:all 0.3s;border:1.5px solid #ce93d8;">
                <i class="fas fa-phone" style="color:#7b1fa2;"></i>
                <div>
                    <div>Call Lab</div>
                    <div style="font-size:0.68rem;opacity:0.75;">{{ $labPhone }}</div>
                </div>
            </a>
            @endif

            @if($labEmail)
            {{-- Email --}}
            <a href="mailto:{{ $labEmail }}?subject=Lab Report Request – HealthNet Patient&body=Hello {{ $laboratory->name ?? '' }},%0D%0A%0D%0AI am a HealthNet patient and would like to request my lab report.%0D%0A%0D%0AThank you."
               style="display:flex;align-items:center;gap:0.7rem;
                      background:#e3f2fd;color:#0d47a1;padding:0.7rem 1rem;
                      border-radius:8px;text-decoration:none;font-weight:600;
                      font-size:0.82rem;transition:all 0.3s;border:1.5px solid #90caf9;">
                <i class="fas fa-envelope" style="color:#1565c0;"></i>
                <div>
                    <div>Email Lab</div>
                    <div style="font-size:0.68rem;opacity:0.75;">{{ Str::limit($labEmail, 28) }}</div>
                </div>
            </a>
            @endif

        </div>
    </div>

    @elseif(Auth::check())

    {{-- ✅ Logged in but NOT patient --}}
    <div style="background:white;border-radius:14px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 4px 16px rgba(0,0,0,0.07);text-align:center;">
        <i class="fas fa-info-circle" style="font-size:2rem;color:#7b1fa2;margin-bottom:0.8rem;display:block;"></i>
        <p style="font-size:0.85rem;color:#666;margin-bottom:1rem;">
            Only patient accounts can book lab tests.
        </p>
        <a href="{{ route('patient.laboratories') }}"
           style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                  background:#6c757d;color:white;padding:0.8rem 1.2rem;
                  border-radius:10px;text-decoration:none;font-weight:600;font-size:0.88rem;">
            <i class="fas fa-arrow-left"></i> Back to Laboratories
        </a>
    </div>

    @else

    {{-- ✅ Guest / Not logged in --}}
    <div style="background:linear-gradient(135deg,#f3e5f5,#fce4ec);border-radius:14px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 4px 16px rgba(123,31,162,0.1);">
        <h5 style="color:#7b1fa2;font-weight:700;font-size:0.95rem;margin-bottom:0.8rem;">
            <i class="fas fa-flask me-2"></i>Book a Lab Test
        </h5>
        <p style="font-size:0.8rem;color:#666;margin-bottom:1.2rem;line-height:1.6;">
            Login as a patient to book lab tests online, upload prescriptions, and receive your reports securely.
        </p>
        <a href="{{ route('login') }}"
           style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                  background:linear-gradient(135deg,#7b1fa2,#4a148c);color:white;
                  padding:0.9rem 1.2rem;border-radius:10px;text-decoration:none;
                  font-weight:700;font-size:0.9rem;margin-bottom:0.8rem;
                  box-shadow:0 4px 14px rgba(123,31,162,0.3);">
            <i class="fas fa-sign-in-alt"></i> Login to Book
        </a>
        <a href="{{ route('patient.laboratories') }}"
           style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                  background:#6c757d;color:white;padding:0.8rem 1.2rem;
                  border-radius:10px;text-decoration:none;font-weight:600;font-size:0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Laboratories
        </a>
    </div>

    @endif

</div>
{{-- ═══ END RIGHT COLUMN ═══ --}}
    </div>
</section>

@include('partials.footer')
