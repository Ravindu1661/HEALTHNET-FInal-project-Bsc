@include('partials.header')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap');

:root {
    --green:      #42a649;
    --green-dk:   #2d7a32;
    --green-lt:   rgba(66,166,73,0.08);
    --green-glow: rgba(66,166,73,0.22);
    --navy:       #1a3a5c;
    --navy-lt:    #2e5f8a;
    --bg:         #f0f2f5;
    --card:       #ffffff;
    --border:     #e4e8ed;
    --text:       #1e2a35;
    --muted:      #7a8795;
    --danger:     #e74c3c;
    --warn-bg:    #fffbeb;
    --warn-border:#f59e0b;
    --radius:     14px;
    --radius-sm:  9px;
    --shadow:     0 2px 12px rgba(0,0,0,0.07);
    --shadow-md:  0 6px 24px rgba(0,0,0,0.10);
}

*, *::before, *::after { box-sizing: border-box; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); }

/* ── Hero ── */
.ah-hero {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-lt) 100%);
    padding: 72px 0 2.2rem;
    position: relative; overflow: hidden;
}
.ah-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 80% at 80% 50%, rgba(66,166,73,0.12), transparent);
}
.ah-hero::after {
    content: '';
    position: absolute; bottom: -1px; left: 0; right: 0; height: 32px;
    background: var(--bg);
    clip-path: ellipse(52% 100% at 50% 100%);
}
.ah-back {
    color: rgba(255,255,255,0.7); text-decoration: none;
    font-size: 0.78rem; font-weight: 500;
    display: inline-flex; align-items: center; gap: 0.35rem;
    margin-bottom: 0.9rem; transition: color 0.2s;
}
.ah-back:hover { color: #fff; }
.ah-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.75rem; color: #fff; margin: 0 0 0.2rem; line-height: 1.2;
}
.ah-sub { font-size: 0.83rem; color: rgba(255,255,255,0.65); margin: 0; }

/* ── Layout ── */
.ah-body { padding: 1.6rem 0 3.5rem; background: var(--bg); }

/* ── Card ── */
.ah-card {
    background: var(--card); border-radius: var(--radius);
    box-shadow: var(--shadow); margin-bottom: 1rem;
    border: 1px solid var(--border); overflow: hidden;
}

/* ── Section Header ── */
.ah-sec {
    display: flex; align-items: center; gap: 0.55rem;
    padding: 0.9rem 1.2rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(to right, rgba(26,58,92,0.03), transparent);
}
.ah-sec-num {
    width: 22px; height: 22px; border-radius: 50%;
    background: var(--green); color: #fff;
    font-size: 0.68rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.ah-sec-title { font-size: 0.83rem; font-weight: 700; color: var(--navy); flex: 1; }
.ah-sec-req   { font-size: 0.68rem; color: var(--danger); font-weight: 400; }

/* ── Doctor Strip ── */
.doc-strip {
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem 1.2rem;
    background: linear-gradient(135deg, rgba(66,166,73,0.05), rgba(66,166,73,0.10));
    flex-wrap: wrap;
}
.doc-ava {
    width: 54px; height: 54px; border-radius: 50%;
    object-fit: cover; border: 2.5px solid var(--green); flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.12);
}
.doc-info { flex: 1; min-width: 0; }
.doc-name { font-size: 0.95rem; font-weight: 700; color: var(--navy); line-height: 1.2; }
.doc-spec { font-size: 0.75rem; color: var(--green); font-weight: 600; margin-top: 0.1rem; }
.doc-meta { display: flex; gap: 0.8rem; flex-wrap: wrap; margin-top: 0.35rem; }
.doc-meta span { font-size: 0.72rem; color: var(--muted); display: flex; align-items: center; gap: 0.25rem; }
.doc-meta i { color: var(--green); font-size: 0.68rem; }
.doc-fee {
    background: var(--card); border: 1.5px solid rgba(66,166,73,0.25);
    border-radius: var(--radius-sm); padding: 0.5rem 0.9rem;
    text-align: center; box-shadow: var(--shadow);
}
.doc-fee-lbl { font-size: 0.62rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
.doc-fee-amt { font-size: 1.25rem; font-weight: 800; color: var(--green); line-height: 1.15; }
.doc-fee-cur { font-size: 0.62rem; color: #bbb; }

/* ── Card Body ── */
.ah-pad { padding: 1rem 1.2rem; }

/* ── Workplace Options ── */
.wp-list { display: flex; flex-direction: column; gap: 0.5rem; }
.wp-item {
    display: flex; align-items: center; gap: 0.75rem;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    padding: 0.75rem 1rem; cursor: pointer;
    transition: all 0.18s; position: relative;
}
.wp-item:hover { border-color: var(--green); background: var(--green-lt); }
.wp-item.sel   { border-color: var(--green); background: rgba(66,166,73,0.07); }
.wp-item input[type="radio"] { display: none; }
.wp-dot {
    width: 17px; height: 17px; border-radius: 50%;
    border: 1.8px solid #ccc; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; transition: all 0.18s;
}
.wp-item.sel .wp-dot { background: var(--green); border-color: var(--green); }
.wp-item.sel .wp-dot::after { content:''; width:6px; height:6px; background:#fff; border-radius:50%; }
.wp-ico {
    width: 34px; height: 34px; border-radius: 8px;
    background: linear-gradient(135deg, var(--navy), var(--navy-lt));
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 0.82rem; flex-shrink: 0;
}
.wp-item.sel .wp-ico { background: linear-gradient(135deg, var(--green), var(--green-dk)); }
.wp-label { flex: 1; min-width: 0; }
.wp-name  { font-size: 0.84rem; font-weight: 700; color: var(--navy); }
.wp-addr  { font-size: 0.72rem; color: var(--muted); margin-top: 0.1rem; }
.wp-tag {
    font-size: 0.62rem; font-weight: 700;
    background: #e3f2fd; color: #1565c0;
    padding: 0.18rem 0.5rem; border-radius: 5px; white-space: nowrap;
}
.wp-item.sel .wp-tag { background: rgba(66,166,73,0.15); color: var(--green-dk); }

/* ── Date Grid ── */
.date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(82px, 1fr));
    gap: 0.45rem; margin-top: 0.7rem;
    max-height: 220px; overflow-y: auto; padding-right: 2px;
}
.date-btn {
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    padding: 0.5rem 0.3rem; text-align: center; cursor: pointer;
    transition: all 0.17s; user-select: none; background: var(--card);
}
.date-btn:hover { border-color: var(--green); background: var(--green-lt); }
.date-btn.sel {
    background: var(--green); border-color: var(--green);
    box-shadow: 0 3px 10px var(--green-glow); color: #fff;
}
.date-dname { font-size: 0.65rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
.date-num   { font-size: 1.05rem; font-weight: 800; color: var(--navy); line-height: 1.1; margin: 0.05rem 0; }
.date-mon   { font-size: 0.62rem; color: var(--muted); }
.date-btn.sel .date-dname,
.date-btn.sel .date-num,
.date-btn.sel .date-mon { color: #fff; }

/* ── Time Slots ── */
.time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
    gap: 0.4rem; margin-top: 0.7rem;
}
.time-slot {
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    padding: 0.5rem 0.4rem; text-align: center; cursor: pointer;
    font-size: 0.78rem; font-weight: 600; color: var(--text);
    background: var(--card); transition: all 0.17s; user-select: none;
}
.time-slot:hover { border-color: var(--green); background: var(--green-lt); color: var(--green-dk); }
.time-slot.sel   { background: var(--green); border-color: var(--green); color: #fff; box-shadow: 0 3px 10px var(--green-glow); }
.time-slot.full  { background: #fef2f2; border-color: #fecaca; color: #ef4444; cursor: not-allowed; text-decoration: line-through; font-size: 0.72rem; }

/* ── Textarea ── */
.ah-textarea {
    width: 100%; padding: 0.65rem 0.85rem;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: 0.83rem; font-family: 'DM Sans', sans-serif;
    color: var(--text); resize: vertical; min-height: 80px;
    transition: border-color 0.2s; background: var(--card); line-height: 1.5;
}
.ah-textarea:focus { border-color: var(--green); outline: none; box-shadow: 0 0 0 3px var(--green-lt); }

/* ── Labels ── */
.ah-label { display: block; font-size: 0.77rem; font-weight: 600; color: var(--navy); margin-bottom: 0.35rem; }
.ah-label .req { color: var(--danger); }
.char-cnt { font-size: 0.67rem; color: var(--muted); text-align: right; margin-top: 0.2rem; }

/* ── Info / Warn Chips ── */
.chip {
    display: flex; align-items: flex-start; gap: 0.45rem;
    border-radius: var(--radius-sm); padding: 0.55rem 0.75rem;
    font-size: 0.75rem; margin-top: 0.65rem;
}
.chip.info { background: #e8f5e9; color: #2d6a31; border-left: 3px solid var(--green); }
.chip.warn { background: var(--warn-bg); color: #78350f; border-left: 3px solid var(--warn-border); }
.chip i { flex-shrink: 0; margin-top: 1px; }

/* ── Error text ── */
.f-err { font-size: 0.73rem; color: var(--danger); margin-top: 0.28rem; display: none; }
.f-err.show { display: block; }

/* ── Alerts ── */
.f-alert {
    border-radius: var(--radius-sm); padding: 0.75rem 1rem;
    margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.55rem; font-size: 0.83rem;
}
.f-alert.error   { background: #fef2f2; color: #991b1b; border-left: 3.5px solid var(--danger); }
.f-alert.success { background: #f0fdf4; color: #166534; border-left: 3.5px solid var(--green); }
.f-alert.info    { background: #eff6ff; color: #1e40af; border-left: 3.5px solid #3b82f6; }

/* ── Loading / Empty ── */
.ah-loading { text-align: center; padding: 1.8rem; color: var(--muted); font-size: 0.8rem; }
.ah-empty   { text-align: center; padding: 1.5rem; color: #ccc; font-size: 0.8rem; }
.ah-empty i { font-size: 1.6rem; display: block; margin-bottom: 0.5rem; }

/* ── Terms ── */
.terms-box {
    background: #f8f9fa; border-radius: var(--radius-sm);
    padding: 0.75rem 0.9rem; font-size: 0.76rem; color: #555;
    line-height: 1.65; margin-bottom: 0.8rem;
}
.terms-box ul { margin: 0; padding-left: 1.1rem; }
.terms-check { display: flex; align-items: flex-start; gap: 0.55rem; cursor: pointer; font-size: 0.8rem; color: #444; }
.terms-check input[type="checkbox"] { width: 16px; height: 16px; margin-top: 2px; accent-color: var(--green); flex-shrink: 0; }

/* ── Sidebar Summary ── */
.sum-card {
    background: var(--card); border-radius: var(--radius);
    box-shadow: var(--shadow-md); border: 1px solid var(--border);
    overflow: hidden; position: sticky; top: 76px;
}
.sum-head {
    background: linear-gradient(135deg, var(--navy), var(--navy-lt));
    color: #fff; padding: 0.85rem 1.1rem;
    font-size: 0.83rem; font-weight: 700;
    display: flex; align-items: center; gap: 0.45rem;
}
.sum-body { padding: 1rem 1.1rem; }
.sum-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 0.45rem 0; border-bottom: 1px solid #f3f4f6; font-size: 0.78rem; gap: 0.5rem;
}
.sum-row:last-child { border: none; }
.sum-lbl { color: var(--muted); display: flex; align-items: center; gap: 0.35rem; flex-shrink: 0; }
.sum-lbl i { color: var(--green); width: 13px; font-size: 0.72rem; }
.sum-val { color: var(--text); font-weight: 600; text-align: right; word-break: break-word; font-size: 0.78rem; }
.sum-fee {
    background: linear-gradient(135deg, rgba(66,166,73,0.07), rgba(66,166,73,0.13));
    border: 1.5px solid rgba(66,166,73,0.22); border-radius: var(--radius-sm);
    padding: 0.8rem; text-align: center; margin: 0.7rem 0;
}
.sum-fee-lbl { font-size: 0.65rem; color: #888; font-weight: 600; margin-bottom: 0.15rem; text-transform: uppercase; letter-spacing: 0.05em; }
.sum-fee-amt { font-size: 1.55rem; font-weight: 800; color: var(--green); line-height: 1; }
.sum-fee-cur { font-size: 0.6rem; color: #bbb; margin-top: 0.15rem; }
.btn-submit {
    display: flex; align-items: center; justify-content: center; gap: 0.45rem;
    background: linear-gradient(135deg, var(--green), var(--green-dk));
    color: #fff; border: none; width: 100%;
    padding: 0.85rem; border-radius: 25px;
    font-size: 0.9rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: all 0.25s;
    box-shadow: 0 4px 14px var(--green-glow); margin-top: 0.4rem;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(66,166,73,0.4); }
.btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }
.btn-back-link {
    display: block; text-align: center; margin-top: 0.65rem;
    font-size: 0.75rem; color: var(--muted); text-decoration: none; transition: color 0.18s;
}
.btn-back-link:hover { color: var(--navy); }

/* ── Hidden sections ── */
#scheduleDateSection, #scheduleTimeSection { display: none; }

@media (max-width: 768px) {
    .doc-strip { gap: 0.75rem; }
    .doc-fee   { margin-left: 0; width: 100%; }
    .ah-pad    { padding: 0.85rem; }
    .sum-card  { position: static; margin-top: 1rem; }
    .date-grid { grid-template-columns: repeat(auto-fill, minmax(72px, 1fr)); }
    .time-grid { grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); }
}
</style>

{{-- ── HERO ── --}}
<section class="ah-hero">
    <div class="container" style="position:relative;z-index:1;">
        <a href="{{ route('patient.doctors.show', $doctor->id) }}" class="ah-back">
            <i class="fas fa-arrow-left"></i> Back to Doctor Profile
        </a>
        <h1 class="ah-title">
            <i class="fas fa-calendar-plus" style="font-size:1.4rem;opacity:0.8;margin-right:0.4rem;"></i>Book Appointment
        </h1>
        <p class="ah-sub">Schedule your consultation in just a few steps</p>
    </div>
</section>

{{-- ── BODY ── --}}
<section class="ah-body">
<div class="container">

    {{-- Flash alerts --}}
    @foreach(['error'=>'error','success'=>'success','info'=>'info'] as $sk=>$st)
        @if(session($sk))
            <div class="f-alert {{$st}}">
                <i class="fas fa-{{ $st==='error'?'times-circle':($st==='success'?'check-circle':'info-circle') }}"></i>
                <span>{{ session($sk) }}</span>
            </div>
        @endif
    @endforeach

    @if($errors->any())
        <div class="f-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please fix the following:</strong>
                <ul style="margin:0.3rem 0 0;padding-left:1.1rem;font-size:0.8rem;">
                    @foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('patient.appointments.store', $doctor->id) }}" method="POST" id="aForm">
        @csrf

        {{-- Hidden fields filled by JS --}}
        <input type="hidden" name="workplace_id"     id="hWpId" value="{{ old('workplace_id') }}">
        <input type="hidden" name="appointment_date" id="hDate" value="{{ old('appointment_date') }}">
        <input type="hidden" name="appointment_time" id="hTime" value="{{ old('appointment_time') }}">

        <div class="row g-3">

        {{-- ══ LEFT COLUMN ══ --}}
        <div class="col-lg-8">

            {{-- Doctor Strip --}}
            <div class="ah-card">
                @php
                    $docImg = $doctor->profile_image
                        ? asset('storage/'.$doctor->profile_image)
                        : asset('images/default-avatar.png');
                @endphp
                <div class="doc-strip">
                    <img src="{{ $docImg }}" class="doc-ava" alt=""
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <div class="doc-info">
                        <div class="doc-name">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
                        <div class="doc-spec">{{ $doctor->specialization ?? 'General Practitioner' }}</div>
                        <div class="doc-meta">
                            @if($doctor->experience_years)
                                <span><i class="fas fa-briefcase-medical"></i> {{ $doctor->experience_years }} yrs exp</span>
                            @endif
                            @if($doctor->slmc_number)
                                <span><i class="fas fa-id-badge"></i> SLMC {{ $doctor->slmc_number }}</span>
                            @endif
                            @if($doctor->phone)
                                <span><i class="fas fa-phone"></i> {{ $doctor->phone }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="doc-fee">
                        <div class="doc-fee-lbl">Consult Fee</div>
                        <div class="doc-fee-amt">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                        <div class="doc-fee-cur">Sri Lankan Rupees</div>
                    </div>
                </div>
            </div>

            {{-- ── STEP 1: Practice Location ── --}}
            <div class="ah-card">
                <div class="ah-sec">
                    <div class="ah-sec-num">1</div>
                    <div class="ah-sec-title">Select Practice Location</div>
                    <span class="ah-sec-req">* required</span>
                </div>
                <div class="ah-pad">
                    @if($workplaces->count() > 0)
                        <div class="wp-list" id="wpList">
                            @foreach($workplaces as $wp)
                                @php
                                    $wn = 'Unknown'; $wa = ''; $wc = '';
                                    $wtype = ucwords(str_replace('_',' ',$wp->workplace_type));
                                    $wicon = $wp->workplace_type === 'hospital' ? 'fa-hospital' : 'fa-clinic-medical';
                                    if ($wp->workplace_type === 'hospital' && $wp->hospital) {
                                        $wn = $wp->hospital->name;
                                        $wa = $wp->hospital->address ?? '';
                                        $wc = $wp->hospital->city ?? '';
                                    } elseif ($wp->workplace_type === 'medical_centre' && $wp->medicalCentre) {
                                        $wn = $wp->medicalCentre->name;
                                        $wa = $wp->medicalCentre->address ?? '';
                                        $wc = $wp->medicalCentre->city ?? '';
                                    }
                                    $isSel = old('workplace_id') == $wp->id;
                                @endphp
                                <label class="wp-item {{ $isSel ? 'sel' : '' }}"
                                       for="wp_{{ $wp->id }}"
                                       data-wp-id="{{ $wp->id }}"
                                       data-wp-name="{{ addslashes($wn) }}"
                                       data-wp-type="{{ $wp->workplace_type }}"
                                       data-actual-id="{{ $wp->workplace_id }}"
                                       onclick="pickWp(this, {{ $wp->id }}, '{{ addslashes($wn) }}', '{{ addslashes($wtype) }}')">
                                    <input type="radio" id="wp_{{ $wp->id }}" value="{{ $wp->id }}" {{ $isSel ? 'checked' : '' }}>
                                    <div class="wp-dot"></div>
                                    <div class="wp-ico"><i class="fas {{ $wicon }}"></i></div>
                                    <div class="wp-label">
                                        <div class="wp-name">{{ $wn }}</div>
                                        @if($wa || $wc)
                                            <div class="wp-addr">
                                                <i class="fas fa-map-marker-alt" style="font-size:0.65rem;color:var(--green);"></i>
                                                {{ trim($wa . ($wc ? ', '.$wc : '')) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="wp-tag">{{ $wtype }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if($errors->has('workplace_id'))
                            <div class="f-err show" style="margin-top:0.4rem;">{{ $errors->first('workplace_id') }}</div>
                        @endif
                    @else
                        <div class="ah-empty">
                            <i class="fas fa-hospital-alt"></i>
                            No approved practice locations available for this doctor.
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── STEP 2: Date ── --}}
            <div class="ah-card" id="scheduleDateSection">
                <div class="ah-sec">
                    <div class="ah-sec-num">2</div>
                    <div class="ah-sec-title">Select Appointment Date</div>
                    <span class="ah-sec-req">* required</span>
                </div>
                <div class="ah-pad">
                    <div id="dateLoading" class="ah-loading" style="display:none;">
                        <i class="fas fa-spinner fa-spin" style="color:var(--green);font-size:1.2rem;display:block;margin-bottom:0.4rem;"></i>
                        Checking available dates…
                    </div>
                    <div id="dateEmpty" class="ah-empty" style="display:none;">
                        <i class="fas fa-calendar-times"></i>
                        No schedule found for this location. Try another.
                    </div>
                    <div id="dateWrap" style="display:none;">
                        <div class="chip info" id="schedInfo">
                            <i class="fas fa-info-circle"></i>
                            <span id="schedInfoTxt">Doctor's available days are highlighted below.</span>
                        </div>
                        <div class="date-grid" id="dateGrid"></div>
                        <div class="f-err" id="dateErr">Please select an appointment date.</div>
                    </div>
                </div>
            </div>

            {{-- ── STEP 3: Time ── --}}
            <div class="ah-card" id="scheduleTimeSection">
                <div class="ah-sec">
                    <div class="ah-sec-num">3</div>
                    <div class="ah-sec-title">Select Time Slot</div>
                    <span class="ah-sec-req">* required</span>
                </div>
                <div class="ah-pad">
                    <div id="timeLoading" class="ah-loading" style="display:none;">
                        <i class="fas fa-spinner fa-spin" style="color:var(--green);font-size:1.2rem;display:block;margin-bottom:0.4rem;"></i>
                        Fetching available slots…
                    </div>
                    <div id="timeEmpty" class="ah-empty" style="display:none;">
                        <i class="fas fa-clock"></i>
                        All slots fully booked for this date. Please choose another date.
                    </div>
                    <div id="timeWrap" style="display:none;">
                        <div style="font-size:0.72rem;color:var(--muted);margin-bottom:0.4rem;">
                            <i class="fas fa-circle" style="color:var(--green);font-size:0.5rem;"></i> Available &nbsp;
                            <i class="fas fa-circle" style="color:#ef4444;font-size:0.5rem;"></i> Fully booked
                        </div>
                        <div class="time-grid" id="timeGrid"></div>
                        <div class="f-err" id="timeErr">Please select a time slot.</div>
                    </div>
                </div>
            </div>

            {{-- ── STEP 4: Details ── --}}
            <div class="ah-card">
                <div class="ah-sec">
                    <div class="ah-sec-num">4</div>
                    <div class="ah-sec-title">Appointment Details</div>
                </div>
                <div class="ah-pad">
                    <div style="margin-bottom:0.9rem;">
                        <label class="ah-label" for="reason">
                            Reason for Visit <span class="req">*</span>
                        </label>
                        <textarea name="reason" id="reason" class="ah-textarea"
                            placeholder="Describe your symptoms or reason (e.g., fever for 3 days, routine check-up…)"
                            maxlength="1000"
                            oninput="cnt('reason','rCount',1000)"
                            required>{{ old('reason') }}</textarea>
                        <div class="char-cnt"><span id="rCount">0</span> / 1000</div>
                        @error('reason')<div class="f-err show">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="ah-label" for="notes">
                            Additional Notes <span style="color:var(--muted);font-weight:400;">(optional)</span>
                        </label>
                        <textarea name="notes" id="notes" class="ah-textarea" style="min-height:65px;"
                            placeholder="Allergies, current medications, past history…"
                            maxlength="1000"
                            oninput="cnt('notes','nCount',1000)">{{ old('notes') }}</textarea>
                        <div class="char-cnt"><span id="nCount">0</span> / 1000</div>
                        @error('notes')<div class="f-err show">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ── Terms ── --}}
            <div class="ah-card">
                <div class="ah-sec">
                    <div class="ah-sec-num" style="background:var(--navy);font-size:0.75rem;">✓</div>
                    <div class="ah-sec-title">Terms & Conditions</div>
                </div>
                <div class="ah-pad">
                    <div class="terms-box">
                        <ul>
                            <li>Appointments are subject to doctor availability and confirmation.</li>
                            <li>A {{ config('app.advance_payment_percent', 50) }}% advance payment is required to confirm booking.</li>
                            <li>Cancellations less than 24 hours before may not qualify for a refund.</li>
                            <li>Please arrive 10 minutes early and bring relevant medical records.</li>
                        </ul>
                    </div>
                    <label class="terms-check">
                        <input type="checkbox" id="agreeTerms">
                        <span>I understand and agree to the terms and conditions.</span>
                    </label>
                    <div class="f-err" id="termsErr">You must agree to continue.</div>
                </div>
            </div>

        </div>{{-- /col-lg-8 --}}

        {{-- ══ SIDEBAR SUMMARY ══ --}}
        <div class="col-lg-4">
            <div class="sum-card">
                <div class="sum-head">
                    <i class="fas fa-receipt"></i> Booking Summary
                </div>
                <div class="sum-body">

                    <div class="sum-row">
                        <span class="sum-lbl"><i class="fas fa-user-md"></i> Doctor</span>
                        <span class="sum-val">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-lbl"><i class="fas fa-stethoscope"></i> Specialty</span>
                        <span class="sum-val">{{ $doctor->specialization ?? 'General' }}</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-lbl"><i class="fas fa-hospital"></i> Location</span>
                        <span class="sum-val" id="sLoc">—</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-lbl"><i class="fas fa-calendar"></i> Date</span>
                        <span class="sum-val" id="sDate">—</span>
                    </div>
                    <div class="sum-row">
                        <span class="sum-lbl"><i class="fas fa-clock"></i> Time</span>
                        <span class="sum-val" id="sTime">—</span>
                    </div>

                    <div class="sum-fee">
                        <div class="sum-fee-lbl">Consultation Fee</div>
                        <div class="sum-fee-amt">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                        <div class="sum-fee-cur">Sri Lankan Rupees</div>
                    </div>

                    @if(($doctor->consultation_fee ?? 0) > 0)
                        @php $adv = round(($doctor->consultation_fee ?? 0) * 0.5, 2); @endphp
                        <div class="chip warn" style="margin-bottom:0.7rem;">
                            <i class="fas fa-info-circle"></i>
                            <span>Advance of <strong>Rs. {{ number_format($adv, 2) }}</strong> (50%) required to confirm.</span>
                        </div>
                    @endif

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-calendar-check"></i> Confirm Appointment
                    </button>
                    <a href="{{ route('patient.doctors.show', $doctor->id) }}" class="btn-back-link">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>

                </div>
            </div>
        </div>

        </div>{{-- /row --}}
    </form>
</div>
</section>

@include('partials.footer')

<script>
/* ══ CONFIG ══ */
const DOCTOR_ID = {{ $doctor->id }};
const URL_DAYS  = '{{ route("patient.appointments.getScheduleDays") }}';
const URL_SLOTS = '{{ route("patient.appointments.getAvailableSlots") }}';

const WP_NAMES = {
    @foreach($workplaces as $wp)
    @php
        $n = '';
        if ($wp->workplace_type === 'hospital' && $wp->hospital) $n = $wp->hospital->name;
        elseif ($wp->medicalCentre) $n = $wp->medicalCentre->name;
    @endphp
    {{ $wp->id }}: "{{ addslashes($n) }}",
    @endforeach
};

/* ══ STATE ══ */
let selWpId = null, selDate = null, selTime = null;

/* ══ UTILS ══ */
function show(id) { const e = document.getElementById(id); if (e) e.style.display = ''; }
function hide(id) { const e = document.getElementById(id); if (e) e.style.display = 'none'; }
function cnt(fId, cId, max) {
    const l  = document.getElementById(fId).value.length;
    const el = document.getElementById(cId);
    el.textContent = l;
    el.style.color = l > max * 0.9 ? 'var(--danger)' : 'var(--muted)';
}

/* ══ STEP 1 — PICK WORKPLACE ══ */
function pickWp(el, wpId, name, type) {
    document.querySelectorAll('.wp-item').forEach(o => o.classList.remove('sel'));
    el.classList.add('sel');
    selWpId = wpId;
    document.getElementById('hWpId').value = wpId;
    document.getElementById('sLoc').textContent = WP_NAMES[wpId] || name;
    resetDT();
    fetchDays(wpId);
}

/* ══ FETCH — Schedule Days ══ */
function fetchDays(wpId) {
    const ds = document.getElementById('scheduleDateSection');
    const ts = document.getElementById('scheduleTimeSection');
    ds.style.display = 'block';
    ts.style.display = 'none';
    show('dateLoading'); hide('dateEmpty'); hide('dateWrap');

    fetch(`${URL_DAYS}?doctor_id=${DOCTOR_ID}&workplace_id=${wpId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        hide('dateLoading');
        if (!d.success || !d.days?.length) { show('dateEmpty'); return; }
        buildDateGrid(d.days);
        if (d.schedule_info) document.getElementById('schedInfoTxt').textContent = d.schedule_info;
        show('dateWrap');
        ds.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    })
    .catch(() => { hide('dateLoading'); show('dateEmpty'); });
}

/* ══ BUILD — Date Buttons (next 90 days, schedule days only) ══ */
function buildDateGrid(days) {
    const grid = document.getElementById('dateGrid');
    grid.innerHTML = '';
    const dayNums = { sunday:0,monday:1,tuesday:2,wednesday:3,thursday:4,friday:5,saturday:6 };
    const allowed = days.map(d => dayNums[d]);
    const DN = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    const MN = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const today = new Date(); today.setHours(0,0,0,0);
    let n = 0;
    for (let i = 0; i <= 90 && n < 30; i++) {
        const d = new Date(today); d.setDate(today.getDate() + i);
        if (!allowed.includes(d.getDay())) continue;
        n++;
        const yyyy = d.getFullYear();
        const mm   = String(d.getMonth()+1).padStart(2,'0');
        const dd   = String(d.getDate()).padStart(2,'0');
        const ds   = `${yyyy}-${mm}-${dd}`;
        const el   = document.createElement('div');
        el.className    = 'date-btn';
        el.dataset.date = ds;
        el.innerHTML = `
            <div class="date-dname">${DN[d.getDay()]}</div>
            <div class="date-num">${dd}</div>
            <div class="date-mon">${MN[d.getMonth()]}</div>`;
        el.addEventListener('click', () => pickDate(el, ds));
        grid.appendChild(el);
    }
}

/* ══ PICK — Date ══ */
function pickDate(el, ds) {
    document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('sel'));
    el.classList.add('sel');
    selDate = ds;
    document.getElementById('hDate').value = ds;
    document.getElementById('dateErr').classList.remove('show');

    const d  = new Date(ds + 'T00:00:00');
    const DN = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const MN = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    document.getElementById('sDate').textContent =
        `${DN[d.getDay()]}, ${String(d.getDate()).padStart(2,'0')} ${MN[d.getMonth()]} ${d.getFullYear()}`;

    selTime = null;
    document.getElementById('hTime').value = '';
    document.getElementById('sTime').textContent = '—';
    fetchSlots(selWpId, ds);
}

/* ══ FETCH — Time Slots ══ */
function fetchSlots(wpId, date) {
    const ts = document.getElementById('scheduleTimeSection');
    ts.style.display = 'block';
    show('timeLoading'); hide('timeEmpty'); hide('timeWrap');

    fetch(`${URL_SLOTS}?doctor_id=${DOCTOR_ID}&workplace_id=${wpId}&date=${date}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        hide('timeLoading');
        if (!d.success || !d.slots?.length) { show('timeEmpty'); return; }
        buildTimeGrid(d.slots);
        show('timeWrap');
        ts.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    })
    .catch(() => { hide('timeLoading'); show('timeEmpty'); });
}

/* ══ BUILD — Time Slot Buttons ══ */
function buildTimeGrid(slots) {
    const grid = document.getElementById('timeGrid');
    grid.innerHTML = '';
    slots.forEach(s => {
        const el = document.createElement('div');
        el.className    = 'time-slot' + (s.booked ? ' full' : '');
        el.dataset.time = s.time;
        el.textContent  = s.label;
        if (!s.booked) el.addEventListener('click', () => pickTime(el, s.time));
        grid.appendChild(el);
    });
}

/* ══ PICK — Time ══ */
function pickTime(el, time) {
    document.querySelectorAll('.time-slot:not(.full)').forEach(b => b.classList.remove('sel'));
    el.classList.add('sel');
    selTime = time;
    document.getElementById('hTime').value = time;
    document.getElementById('timeErr').classList.remove('show');
    const [h, m] = time.split(':');
    const hr  = parseInt(h);
    const ap  = hr >= 12 ? 'PM' : 'AM';
    const h12 = hr % 12 || 12;
    document.getElementById('sTime').textContent =
        `${String(h12).padStart(2,'0')}:${m} ${ap}`;
}

/* ══ RESET Date & Time ══ */
function resetDT() {
    selDate = null; selTime = null;
    document.getElementById('hDate').value = '';
    document.getElementById('hTime').value = '';
    document.getElementById('sDate').textContent = '—';
    document.getElementById('sTime').textContent = '—';
    document.getElementById('scheduleTimeSection').style.display = 'none';
}

/* ══ INIT ══ */
window.addEventListener('DOMContentLoaded', () => {
    cnt('reason','rCount',1000);
    cnt('notes','nCount',1000);

    @if(old('workplace_id'))
        const ow = document.querySelector('[data-wp-id="{{ old('workplace_id') }}"]');
        if (ow) {
            pickWp(ow, {{ old('workplace_id') }}, ow.dataset.wpName, ow.dataset.wpType);
            @if(old('appointment_date'))
                setTimeout(() => {
                    const od = document.querySelector('.date-btn[data-date="{{ old('appointment_date') }}"]');
                    if (od) od.click();
                    @if(old('appointment_time'))
                        setTimeout(() => {
                            const ot = document.querySelector('.time-slot[data-time="{{ old('appointment_time') }}"]');
                            if (ot && !ot.classList.contains('full')) ot.click();
                        }, 1100);
                    @endif
                }, 900);
            @endif
        }
    @endif
});

/* ══ FORM VALIDATION ══ */
document.getElementById('aForm').addEventListener('submit', function(e) {
    let ok = true;

    if (!document.getElementById('hWpId').value) {
        ok = false;
        alert('Please select a practice location.');
    }
    if (!document.getElementById('hDate').value) {
        document.getElementById('dateErr').classList.add('show');
        ok = false;
    }
    if (!document.getElementById('hTime').value) {
        const te = document.getElementById('timeErr');
        if (te) te.classList.add('show');
        ok = false;
    }
    if (!document.getElementById('agreeTerms').checked) {
        document.getElementById('termsErr').classList.add('show');
        ok = false;
    } else {
        document.getElementById('termsErr').classList.remove('show');
    }
    if (!document.getElementById('reason').value.trim()) {
        document.getElementById('reason').style.borderColor = 'var(--danger)';
        ok = false;
    }

    if (!ok) {
        e.preventDefault();
        window.scrollTo({ top: 180, behavior: 'smooth' });
        return;
    }

    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
});

document.getElementById('reason').addEventListener('input', function() {
    this.style.borderColor = '';
});
</script>
