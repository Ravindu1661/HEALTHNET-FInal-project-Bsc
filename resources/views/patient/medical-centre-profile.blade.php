@include('partials.header')
<style>
/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
.mc-hero{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding-top:80px;padding-bottom:0;position:relative;overflow:hidden;color:#fff}
.mc-hero::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.08;z-index:0}
.mc-hero .container{position:relative;z-index:1}
.mc-hero::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f0f4f8;clip-path:ellipse(55% 100% at 50% 100%)}

.mc-avatar{width:90px;height:90px;border-radius:16px;object-fit:cover;border:4px solid rgba(255,255,255,.85);box-shadow:0 4px 18px rgba(0,0,0,.2);flex-shrink:0}
.mc-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:20px;font-size:.75rem;font-weight:700;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);backdrop-filter:blur(4px)}
.mc-stat{text-align:center;padding:.6rem 1rem;background:rgba(255,255,255,.12);border-radius:10px;backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,.2)}
.mc-stat-num{font-size:1.3rem;font-weight:800;line-height:1}
.mc-stat-lbl{font-size:.7rem;opacity:.85;margin-top:.2rem}

/* ══ NAV TABS ══ */
.mc-tabs{background:#fff;border-bottom:2px solid #e0f2f1;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,.05)}
.mc-tab{padding:.75rem 1.3rem;font-size:.83rem;font-weight:700;color:#555;border-bottom:3px solid transparent;cursor:pointer;white-space:nowrap;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;transition:all .25s}
.mc-tab:hover{color:#00796b}
.mc-tab.active{color:#00796b;border-bottom-color:#00796b}

/* ══ BODY ══ */
.mc-body{background:#f0f4f8;padding:2rem 0 3rem}

/* ══ CARDS ══ */
.mc-card{background:#fff;border-radius:14px;padding:1.4rem 1.5rem;box-shadow:0 3px 14px rgba(0,0,0,.06);margin-bottom:1.2rem}
.mc-card-title{font-size:.9rem;font-weight:700;color:#00796b;padding-bottom:.6rem;border-bottom:2px solid #e0f2f1;margin-bottom:1.1rem;display:flex;align-items:center;gap:.5rem}

/* ══ PILLS / TAGS ══ */
.tag-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .8rem;border-radius:20px;font-size:.75rem;font-weight:600;background:#e0f2f1;color:#00796b;margin:.2rem}
.facility-pill{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:8px;padding:.3rem .75rem;font-size:.76rem;font-weight:600;display:inline-flex;align-items:center;gap:.35rem;margin:.2rem}

/* ══ INFO ROWS ══ */
.info-row{display:flex;align-items:flex-start;gap:.7rem;padding:.5rem 0;border-bottom:1px solid #f0f4f0;font-size:.84rem;color:#555}
.info-row:last-child{border-bottom:none}
.info-row i{width:18px;color:#00796b;flex-shrink:0;margin-top:.1rem}

/* ══ CONTACT BUTTONS ══ */
.btn-contact{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-radius:10px;font-size:.84rem;font-weight:600;text-decoration:none;cursor:pointer;border:none;width:100%;margin-bottom:.5rem;transition:all .25s}
.btn-call{background:#e0f2f1;color:#004d40}.btn-call:hover{background:#b2dfdb;color:#004d40}
.btn-whatsapp{background:#dcfce7;color:#166534}.btn-whatsapp:hover{background:#bbf7d0;color:#166534}
.btn-email{background:#e0f2fe;color:#0c4a6e}.btn-email:hover{background:#bae6fd;color:#0c4a6e}
.btn-maps{background:#fce8d5;color:#92400e}.btn-maps:hover{background:#fed7aa;color:#92400e}
.btn-appt{background:linear-gradient(135deg,#00796b,#004d40);color:#fff;justify-content:center;padding:.85rem;border-radius:10px;font-weight:700;font-size:.88rem}.btn-appt:hover{filter:brightness(1.08);color:#fff}

/* ══ DOCTOR CARDS ══ */
.doc-card{background:#fff;border-radius:12px;padding:1rem 1.1rem;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid #00796b;transition:all .25s;margin-bottom:.8rem}
.doc-card:hover{box-shadow:0 5px 18px rgba(0,121,107,.13);transform:translateY(-2px)}
.doc-avatar{width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid #e0f2f1;flex-shrink:0}
.doc-name{font-weight:700;font-size:.9rem;color:#1a1a1a}
.doc-spec{font-size:.76rem;color:#00796b;font-weight:600}
.doc-meta{font-size:.73rem;color:#888;display:flex;align-items:center;gap:.35rem}

/* ══ STAR RATING ══ */
.stars{color:#f59e0b;font-size:.8rem;letter-spacing:.05rem}
.rating-bar-wrap{display:flex;align-items:center;gap:.6rem;margin-bottom:.3rem}
.rating-bar{flex:1;height:7px;background:#f0f4f0;border-radius:4px;overflow:hidden}
.rating-bar-fill{height:100%;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:4px;transition:width .5s}

/* ══ REVIEW CARDS ══ */
.review-card{background:#f8fafc;border-radius:10px;padding:1rem;margin-bottom:.8rem;border-left:3px solid #a5d6a7}
.review-author{font-weight:700;font-size:.83rem;color:#1a1a1a}
.review-date{font-size:.72rem;color:#aaa}
.review-text{font-size:.82rem;color:#555;margin-top:.3rem;line-height:1.6}

/* ══ MAP ══ */
.map-frame{width:100%;height:220px;border-radius:10px;border:2px solid #e0f2f1;overflow:hidden}

/* ══ ALERT ══ */
.mc-alert{border-radius:9px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;font-weight:500}
.mc-alert.info{background:#e0f2fe;color:#0c4a6e;border-left:3px solid #0891b2}
.mc-alert.success{background:#dcfce7;color:#166534;border-left:3px solid #22c55e}
.mc-alert.error{background:#fee2e2;color:#991b1b;border-left:3px solid #ef4444}

/* ══ RESPONSIVE ══ */
@media(max-width:768px){
    .mc-avatar{width:68px;height:68px}
    .mc-stat-num{font-size:1rem}
}
</style>

{{-- ══════════════════ HERO ══════════════════ --}}
<section class="mc-hero">
    <div class="container py-4">
        <a href="{{ route('patient.medical-centres') }}"
           style="color:rgba(255,255,255,.8);font-size:.82rem;display:inline-flex;align-items:center;gap:.35rem;margin-bottom:1rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Medical Centres
        </a>

        <div class="d-flex align-items-start gap-3 flex-wrap">
            {{-- Logo --}}
            <img src="{{ $medicalCentre->profile_image ? asset('storage/'.$medicalCentre->profile_image) : asset('images/default-medical.png') }}"
                 class="mc-avatar"
                 onerror="this.src='{{ asset('images/default-medical.png') }}'">

            <div class="flex-grow-1">
                {{-- Name & Status --}}
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <h1 style="font-size:1.7rem;font-weight:800;margin:0">{{ $medicalCentre->name }}</h1>
                    @if($medicalCentre->status === 'approved')
                    <span class="mc-badge"><i class="fas fa-check-circle"></i> Verified</span>
                    @endif
                </div>

                {{-- Location --}}
                <div style="opacity:.85;font-size:.86rem;margin-bottom:.75rem">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $medicalCentre->address ?? '' }}
                    @if($medicalCentre->city) &bull; {{ $medicalCentre->city }} @endif
                </div>

                {{-- Type tags --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if($medicalCentre->type)
                    <span class="mc-badge"><i class="fas fa-hospital-alt"></i> {{ ucfirst(str_replace('_',' ',$medicalCentre->type)) }}</span>
                    @endif
                    @if($medicalCentre->emergency_available)
                    <span class="mc-badge" style="background:rgba(239,68,68,.2);border-color:rgba(239,68,68,.4)">
                        <i class="fas fa-ambulance"></i> 24/7 Emergency
                    </span>
                    @endif
                    @if($medicalCentre->pharmacy_available)
                    <span class="mc-badge"><i class="fas fa-pills"></i> In-house Pharmacy</span>
                    @endif
                    @if($medicalCentre->lab_available)
                    <span class="mc-badge"><i class="fas fa-flask"></i> Laboratory</span>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="d-flex gap-2 flex-wrap">
                    <div class="mc-stat">
                        <div class="mc-stat-num">{{ number_format($medicalCentre->rating ?? 0, 1) }}</div>
                        <div class="mc-stat-lbl"><i class="fas fa-star"></i> Rating</div>
                    </div>
                    <div class="mc-stat">
                        <div class="mc-stat-num">{{ $medicalCentre->total_ratings ?? 0 }}</div>
                        <div class="mc-stat-lbl">Reviews</div>
                    </div>
                    <div class="mc-stat">
                        <div class="mc-stat-num">{{ $doctors->count() }}</div>
                        <div class="mc-stat-lbl">Doctors</div>
                    </div>
                    @if($medicalCentre->bed_count)
                    <div class="mc-stat">
                        <div class="mc-stat-num">{{ $medicalCentre->bed_count }}</div>
                        <div class="mc-stat-lbl">Beds</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab spacer --}}
        <div style="height:1.5rem"></div>
    </div>
</section>

{{-- ══════════════════ TABS ══════════════════ --}}
<div class="mc-tabs">
    <div class="container">
        <div style="display:flex;overflow-x:auto;gap:.2rem;scrollbar-width:none">
            <a href="#overview"  class="mc-tab active" onclick="switchTab(event,'overview')"><i class="fas fa-info-circle"></i> Overview</a>
            <a href="#doctors"   class="mc-tab"        onclick="switchTab(event,'doctors')"><i class="fas fa-user-md"></i> Doctors <span style="background:#00796b;color:#fff;border-radius:10px;padding:.1rem .5rem;font-size:.68rem;margin-left:.2rem">{{ $doctors->count() }}</span></a>
            <a href="#facilities" class="mc-tab"       onclick="switchTab(event,'facilities')"><i class="fas fa-building"></i> Facilities</a>
            <a href="#reviews"   class="mc-tab"        onclick="switchTab(event,'reviews')"><i class="fas fa-star"></i> Reviews</a>
            <a href="#location"  class="mc-tab"        onclick="switchTab(event,'location')"><i class="fas fa-map-marker-alt"></i> Location</a>
        </div>
    </div>
</div>

{{-- ══════════════════ BODY ══════════════════ --}}
<section class="mc-body">
    <div class="container">

        {{-- Flash --}}
        @foreach(['success','error','info'] as $t)
            @if(session($t))
            <div class="mc-alert {{ $t }}">
                <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }}" style="flex-shrink:0;margin-top:.1rem"></i>
                <span>{{ session($t) }}</span>
            </div>
            @endif
        @endforeach

        <div class="row g-3">

            {{-- ══════════ MAIN CONTENT ══════════ --}}
            <div class="col-lg-8">

                {{-- ── TAB: OVERVIEW ── --}}
                <div id="tab-overview">

                    {{-- About --}}
                    @if($medicalCentre->description)
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-info-circle"></i> About</div>
                        <p style="font-size:.86rem;color:#555;line-height:1.8;margin:0">{{ $medicalCentre->description }}</p>
                    </div>
                    @endif

                    {{-- Specializations --}}
                    @if(count($specializations))
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-stethoscope"></i> Specializations</div>
                        <div>
                            @foreach($specializations as $spec)
                            <span class="tag-pill"><i class="fas fa-check-circle"></i> {{ $spec }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Services / Departments --}}
                    @if($medicalCentre->departments || $medicalCentre->services)
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-list-alt"></i> Departments & Services</div>
                        @php
                            $depts = is_array($medicalCentre->departments)
                                ? $medicalCentre->departments
                                : json_decode($medicalCentre->departments, true) ?? [];
                            $services = is_array($medicalCentre->services)
                                ? $medicalCentre->services
                                : json_decode($medicalCentre->services, true) ?? [];
                            $allServices = array_merge($depts, $services);
                        @endphp
                        @if(count($allServices))
                        <div class="row g-2">
                            @foreach($allServices as $svc)
                            <div class="col-6 col-md-4">
                                <div style="background:#f0fdf4;border-radius:8px;padding:.5rem .75rem;font-size:.8rem;font-weight:600;color:#166534;display:flex;align-items:center;gap:.4rem">
                                    <i class="fas fa-circle" style="font-size:.4rem;color:#00796b"></i> {{ $svc }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Quick Info Table --}}
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-clipboard-list"></i> Quick Info</div>
                        <div class="row g-0">
                            <div class="col-md-6">
                                @if($medicalCentre->registration_number)
                                <div class="info-row">
                                    <i class="fas fa-id-card"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">Registration No.</div>{{ $medicalCentre->registration_number }}</div>
                                </div>
                                @endif
                                @if($medicalCentre->operating_hours)
                                <div class="info-row">
                                    <i class="fas fa-clock"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">Operating Hours</div>{{ $medicalCentre->operating_hours }}</div>
                                </div>
                                @endif
                                @if($medicalCentre->established_year)
                                <div class="info-row">
                                    <i class="fas fa-calendar"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">Established</div>{{ $medicalCentre->established_year }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <i class="fas fa-procedures"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">Emergency</div>{{ $medicalCentre->emergency_available ? '✅ Available 24/7' : '❌ Not available' }}</div>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-pills"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">In-house Pharmacy</div>{{ $medicalCentre->pharmacy_available ? '✅ Available' : '❌ Not available' }}</div>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-flask"></i>
                                    <div><div style="font-size:.73rem;color:#aaa;font-weight:600">Laboratory</div>{{ $medicalCentre->lab_available ? '✅ Available' : '❌ Not available' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Owner / Chief Doctor --}}
                    @if($medicalCentre->ownerDoctor)
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-user-tie"></i> Medical Director</div>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $medicalCentre->ownerDoctor->profile_image ? asset('storage/'.$medicalCentre->ownerDoctor->profile_image) : asset('images/default-doctor.png') }}"
                                 style="width:55px;height:55px;border-radius:50%;object-fit:cover;border:2px solid #e0f2f1"
                                 onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                            <div>
                                <div style="font-weight:700;font-size:.92rem">Dr. {{ $medicalCentre->ownerDoctor->user->name ?? $medicalCentre->ownerDoctor->full_name ?? 'N/A' }}</div>
                                <div style="font-size:.78rem;color:#00796b;font-weight:600">{{ $medicalCentre->ownerDoctor->specialization ?? '' }}</div>
                                @if($medicalCentre->ownerDoctor->license_number)
                                <div style="font-size:.74rem;color:#888">License: {{ $medicalCentre->ownerDoctor->license_number }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- ── TAB: DOCTORS ── --}}
                <div id="tab-doctors" style="display:none">
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-user-md"></i> Doctors at {{ $medicalCentre->name }}</div>
                        @forelse($doctors as $doctor)
                        @php
                            $wp = $doctor->workplaces->where('workplace_type','medical_centre')->where('workplace_id',$medicalCentre->id)->first();
                        @endphp
                        <div class="doc-card">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $doctor->profile_image ? asset('storage/'.$doctor->profile_image) : asset('images/default-doctor.png') }}"
                                     class="doc-avatar"
                                     onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                                <div class="flex-grow-1">
                                    <div class="doc-name">Dr. {{ $doctor->user->name ?? $doctor->full_name ?? 'N/A' }}</div>
                                    <div class="doc-spec">{{ $doctor->specialization ?? 'General Practitioner' }}</div>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @if($doctor->experience_years)
                                        <span class="doc-meta"><i class="fas fa-briefcase-medical"></i> {{ $doctor->experience_years }}y exp</span>
                                        @endif
                                        @if($doctor->rating)
                                        <span class="doc-meta"><i class="fas fa-star" style="color:#f59e0b"></i> {{ number_format($doctor->rating,1) }}</span>
                                        @endif
                                        @if($wp && $wp->consultation_fee)
                                        <span class="doc-meta"><i class="fas fa-tag"></i> LKR {{ number_format($wp->consultation_fee,0) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('patient.doctors.show', $doctor->id) }}"
                                       style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:.45rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700;text-decoration:none;white-space:nowrap">
                                        <i class="fas fa-calendar-plus me-1"></i> Book
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;padding:2rem;color:#aaa">
                            <i class="fas fa-user-md" style="font-size:2rem;display:block;margin-bottom:.6rem;color:#b2dfdb"></i>
                            <div style="font-weight:600;color:#00796b">No doctors listed yet</div>
                            <div style="font-size:.82rem">Doctors will appear here once assigned.</div>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── TAB: FACILITIES ── --}}
                <div id="tab-facilities" style="display:none">
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-building"></i> Facilities & Equipment</div>
                        @if(count($facilities))
                        <div style="margin-bottom:1rem">
                            @foreach($facilities as $facility)
                            <span class="facility-pill">
                                <i class="fas fa-check-circle"></i> {{ $facility }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <div style="text-align:center;padding:1.5rem;color:#aaa;font-size:.85rem">
                            <i class="fas fa-building" style="font-size:1.8rem;display:block;margin-bottom:.5rem;color:#b2dfdb"></i>
                            Facilities information not available yet.
                        </div>
                        @endif

                        {{-- Insurance --}}
                        @if($medicalCentre->insurance_accepted)
                        <div style="background:#fef3c7;border-left:3px solid #f59e0b;border-radius:8px;padding:.75rem 1rem;font-size:.82rem;color:#92400e;margin-top:1rem">
                            <i class="fas fa-shield-alt me-1"></i>
                            <strong>Insurance Accepted:</strong> {{ $medicalCentre->insurance_accepted }}
                        </div>
                        @endif

                        {{-- Parking / Ambulance --}}
                        <div class="row g-2 mt-2">
                            @if($medicalCentre->parking_available)
                            <div class="col-6 col-md-4">
                                <div class="facility-pill" style="width:100%;justify-content:center">
                                    <i class="fas fa-parking"></i> Parking Available
                                </div>
                            </div>
                            @endif
                            @if($medicalCentre->ambulance_available)
                            <div class="col-6 col-md-4">
                                <div class="facility-pill" style="width:100%;justify-content:center">
                                    <i class="fas fa-ambulance"></i> Ambulance Service
                                </div>
                            </div>
                            @endif
                            @if($medicalCentre->wheelchair_accessible)
                            <div class="col-6 col-md-4">
                                <div class="facility-pill" style="width:100%;justify-content:center">
                                    <i class="fas fa-wheelchair"></i> Wheelchair Accessible
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── TAB: REVIEWS ── --}}
                <div id="tab-reviews" style="display:none">
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-star"></i> Patient Reviews</div>

                        {{-- Rating Summary --}}
                        <div class="d-flex gap-4 align-items-center mb-3 flex-wrap">
                            <div style="text-align:center">
                                <div style="font-size:3rem;font-weight:800;color:#00796b;line-height:1">
                                    {{ number_format($medicalCentre->rating ?? 0, 1) }}
                                </div>
                                <div class="stars">
                                    @for($s=1;$s<=5;$s++)
                                        <i class="fas fa-star{{ $s <= round($medicalCentre->rating ?? 0) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <div style="font-size:.74rem;color:#aaa">{{ $medicalCentre->total_ratings ?? 0 }} reviews</div>
                            </div>
                            <div style="flex:1;min-width:160px">
                                @for($r=5;$r>=1;$r--)
                                @php
                                    $cnt = \App\Models\Rating::where('ratable_type','medical_centre')
                                        ->where('ratable_id',$medicalCentre->id)->where('rating',$r)->count();
                                    $pct = ($medicalCentre->total_ratings ?? 0) > 0
                                        ? round(($cnt / $medicalCentre->total_ratings) * 100) : 0;
                                @endphp
                                <div class="rating-bar-wrap">
                                    <div style="font-size:.72rem;color:#555;width:12px">{{ $r }}</div>
                                    <i class="fas fa-star" style="color:#f59e0b;font-size:.65rem"></i>
                                    <div class="rating-bar">
                                        <div class="rating-bar-fill" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <div style="font-size:.7rem;color:#aaa;width:28px">{{ $cnt }}</div>
                                </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Review List --}}
                        @php
                            $reviews = \App\Models\Rating::where('ratable_type','medical_centre')
                                ->where('ratable_id',$medicalCentre->id)
                                ->with('patient.user')->latest()->limit(10)->get();
                        @endphp
                        @forelse($reviews as $review)
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="review-author">
                                        {{ $review->patient->user->name ?? 'Anonymous' }}
                                    </div>
                                    <div class="stars" style="font-size:.72rem">
                                        @for($s=1;$s<=5;$s++)
                                        <i class="fas fa-star{{ $s <= $review->rating ? '' : '' }}"
                                           style="color:{{ $s <= $review->rating ? '#f59e0b' : '#ddd' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="review-date">{{ optional($review->created_at)->format('d M Y') }}</div>
                            </div>
                            @if($review->review)
                            <div class="review-text">{{ $review->review }}</div>
                            @endif
                        </div>
                        @empty
                        <div style="text-align:center;padding:1.5rem;color:#aaa;font-size:.85rem">
                            <i class="fas fa-star" style="font-size:1.8rem;display:block;margin-bottom:.5rem;color:#b2dfdb"></i>
                            No reviews yet. Be the first to review!
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── TAB: LOCATION ── --}}
                <div id="tab-location" style="display:none">
                    <div class="mc-card">
                        <div class="mc-card-title"><i class="fas fa-map-marker-alt"></i> Location</div>
                        @if($medicalCentre->latitude && $medicalCentre->longitude)
                        <div class="map-frame">
                            <iframe
                                src="https://www.google.com/maps?q={{ $medicalCentre->latitude }},{{ $medicalCentre->longitude }}&z=15&output=embed"
                                width="100%" height="100%" style="border:0" allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                        <a href="https://www.google.com/maps?q={{ $medicalCentre->latitude }},{{ $medicalCentre->longitude }}"
                           target="_blank" class="btn-maps mt-2" style="display:flex;align-items:center;gap:.5rem;padding:.7rem 1rem;border-radius:9px;text-decoration:none;font-size:.82rem;font-weight:600">
                            <i class="fas fa-directions"></i> Get Directions in Google Maps
                        </a>
                        @else
                        <div style="text-align:center;padding:2rem;color:#aaa">
                            <i class="fas fa-map-marked-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#b2dfdb"></i>
                            Location not available.
                        </div>
                        @endif
                        @if($medicalCentre->address)
                        <div class="info-row mt-2">
                            <i class="fas fa-map-pin"></i>
                            <span>{{ $medicalCentre->address }}@if($medicalCentre->city), {{ $medicalCentre->city }}@endif</span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ══════════ SIDEBAR ══════════ --}}
            <div class="col-lg-4">

                {{-- Book Appointment CTA --}}
                @if($doctors->count())
                <div class="mc-card" style="background:linear-gradient(135deg,#e0f2f1,#b2dfdb)">
                    <div style="text-align:center;margin-bottom:.8rem">
                        <i class="fas fa-calendar-plus" style="font-size:1.5rem;color:#00796b;display:block;margin-bottom:.3rem"></i>
                        <div style="font-weight:700;font-size:.9rem;color:#004d40">Book an Appointment</div>
                        <div style="font-size:.76rem;color:#555;margin-top:.2rem">{{ $doctors->count() }} doctor{{ $doctors->count()>1?'s':'' }} available</div>
                    </div>
                    <a href="#doctors" onclick="switchTab(event,'doctors')"
                       class="btn-appt" style="display:flex;align-items:center;justify-content:center;gap:.5rem;text-decoration:none">
                        <i class="fas fa-user-md"></i> View Available Doctors
                    </a>
                </div>
                @endif

                {{-- Contact --}}
                <div class="mc-card">
                    <div class="mc-card-title"><i class="fas fa-address-book"></i> Contact</div>
                    @if($medicalCentre->phone)
                    <a href="tel:{{ $medicalCentre->phone }}" class="btn-contact btn-call">
                        <i class="fas fa-phone-alt fa-lg"></i>
                        <div>
                            <div style="font-weight:700;font-size:.84rem">Call Us</div>
                            <div style="font-size:.76rem">{{ $medicalCentre->phone }}</div>
                        </div>
                    </a>
                    <button class="btn-contact btn-whatsapp"
                        onclick="window.open('https://wa.me/94{{ ltrim($medicalCentre->phone??'','0') }}?text=Hello+{{ urlencode($medicalCentre->name) }}','_blank')">
                        <i class="fab fa-whatsapp fa-lg"></i>
                        <div>
                            <div style="font-weight:700;font-size:.84rem">WhatsApp</div>
                            <div style="font-size:.76rem">Chat with us</div>
                        </div>
                    </button>
                    @endif
                    @if($medicalCentre->email)
                    <a href="mailto:{{ $medicalCentre->email }}" class="btn-contact btn-email">
                        <i class="fas fa-envelope fa-lg"></i>
                        <div>
                            <div style="font-weight:700;font-size:.84rem">Email Us</div>
                            <div style="font-size:.76rem">{{ Str::limit($medicalCentre->email, 28) }}</div>
                        </div>
                    </a>
                    @endif
                    @if($medicalCentre->latitude && $medicalCentre->longitude)
                    <a href="https://www.google.com/maps?q={{ $medicalCentre->latitude }},{{ $medicalCentre->longitude }}"
                       target="_blank" class="btn-contact btn-maps" style="text-decoration:none">
                        <i class="fas fa-directions fa-lg"></i>
                        <div>
                            <div style="font-weight:700;font-size:.84rem">Get Directions</div>
                            <div style="font-size:.76rem">Open in Google Maps</div>
                        </div>
                    </a>
                    @endif
                </div>

                {{-- Emergency --}}
                @if($medicalCentre->emergency_available && $medicalCentre->emergency_phone)
                <div class="mc-card" style="background:#fee2e2;border-left:4px solid #ef4444">
                    <div class="mc-card-title" style="color:#dc2626;border-color:#fca5a5">
                        <i class="fas fa-ambulance"></i> Emergency
                    </div>
                    <a href="tel:{{ $medicalCentre->emergency_phone }}"
                       style="display:flex;align-items:center;justify-content:center;gap:.5rem;background:#dc2626;color:#fff;padding:.8rem;border-radius:9px;text-decoration:none;font-weight:700;font-size:.9rem">
                        <i class="fas fa-phone-alt"></i> {{ $medicalCentre->emergency_phone }}
                    </a>
                    <div style="text-align:center;font-size:.73rem;color:#991b1b;margin-top:.4rem">Available 24/7</div>
                </div>
                @endif

                {{-- Operating Hours --}}
                @if($medicalCentre->operating_hours)
                <div class="mc-card">
                    <div class="mc-card-title"><i class="fas fa-clock"></i> Operating Hours</div>
                    <p style="font-size:.83rem;color:#555;line-height:1.8;margin:0">{{ $medicalCentre->operating_hours }}</p>
                </div>
                @endif

                {{-- Quick Facts --}}
                <div class="mc-card" style="background:linear-gradient(135deg,#f0fdf4,#e0f2f1)">
                    <div class="mc-card-title"><i class="fas fa-info-circle"></i> At a Glance</div>
                    <div style="font-size:.8rem;color:#555;line-height:2">
                        @if($medicalCentre->bed_count)
                        <div><i class="fas fa-bed me-2" style="color:#00796b;width:15px"></i><strong>Beds:</strong> {{ $medicalCentre->bed_count }}</div>
                        @endif
                        @if($medicalCentre->icu_available ?? false)
                        <div><i class="fas fa-heartbeat me-2" style="color:#dc2626;width:15px"></i><strong>ICU:</strong> Available</div>
                        @endif
                        <div><i class="fas fa-pills me-2" style="color:#00796b;width:15px"></i><strong>Pharmacy:</strong> {{ $medicalCentre->pharmacy_available ? 'Yes' : 'No' }}</div>
                        <div><i class="fas fa-flask me-2" style="color:#00796b;width:15px"></i><strong>Lab:</strong> {{ $medicalCentre->lab_available ? 'Yes' : 'No' }}</div>
                        <div><i class="fas fa-ambulance me-2" style="color:#dc2626;width:15px"></i><strong>Emergency:</strong> {{ $medicalCentre->emergency_available ? '24/7' : 'No' }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
// ── Tab switching ──────────────────────────────────────
const tabIds = ['overview','doctors','facilities','reviews','location'];

function switchTab(e, tab) {
    e.preventDefault();
    tabIds.forEach(t => {
        document.getElementById('tab-' + t).style.display = t === tab ? 'block' : 'none';
    });
    document.querySelectorAll('.mc-tab').forEach(el => el.classList.remove('active'));
    e.currentTarget.classList.add('active');
    window.scrollTo({ top: document.querySelector('.mc-tabs').offsetTop - 10, behavior: 'smooth' });
}

// ── Auto-dismiss flash messages ─────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.mc-alert').forEach(el => {
        setTimeout(() => { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 4000);
    });
});
</script>

@include('partials.footer')

