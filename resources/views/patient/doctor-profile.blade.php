@include('partials.header')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --green:      #42a649;
    --green-dk:   #2d7a32;
    --green-lt:   rgba(66,166,73,0.08);
    --green-glow: rgba(66,166,73,0.25);
    --navy:       #1a3a5c;
    --navy-lt:    #2760a0;
    --bg:         #f0f2f6;
    --card:       #ffffff;
    --border:     #e4e8ed;
    --text:       #1e2a35;
    --muted:      #6b7a8d;
    --gold:       #f5a623;
    --danger:     #e74c3c;
    --radius:     16px;
    --radius-sm:  10px;
    --shadow:     0 2px 16px rgba(0,0,0,0.07);
    --shadow-md:  0 6px 28px rgba(0,0,0,0.10);
}

*,*::before,*::after { box-sizing: border-box; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); }

/* ══ HERO ══════════════════════════════════ */
.ph-hero {
    background: linear-gradient(150deg, #0d2137 0%, #1a3a5c 45%, #1f5fa6 100%);
    padding: 80px 0 0;
    position: relative; overflow: hidden;
}
.ph-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 60% at 80% 40%, rgba(66,166,73,0.12), transparent),
        radial-gradient(ellipse 40% 40% at 10% 80%, rgba(42,100,200,0.10), transparent);
}
.ph-hero::after {
    content: '';
    position: absolute; bottom: -1px; left: 0; right: 0; height: 48px;
    background: var(--bg);
    clip-path: ellipse(54% 100% at 50% 100%);
}
.ph-hero .container { position: relative; z-index: 1; }

.back-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: rgba(255,255,255,0.7); text-decoration: none;
    font-size: 0.78rem; font-weight: 500;
    margin-bottom: 1.2rem; transition: color 0.2s;
}
.back-btn:hover { color: #fff; }

/* Profile card attached to hero */
.ph-card {
    background: var(--card);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 1.8rem 2rem 0;
    box-shadow: 0 -8px 32px rgba(0,0,0,0.10);
    margin-top: 1.5rem;
}
.ph-top {
    display: flex; gap: 1.6rem; align-items: flex-start;
    flex-wrap: wrap; padding-bottom: 1.4rem;
    border-bottom: 1px solid var(--border);
}

/* Avatar */
.ph-ava-wrap { position: relative; flex-shrink: 0; }
.ph-ava {
    width: 108px; height: 108px; border-radius: 50%;
    object-fit: cover;
    border: 3.5px solid var(--green);
    box-shadow: 0 6px 20px rgba(0,0,0,0.18);
}
.ph-ava-badge {
    position: absolute; bottom: 4px; right: 4px;
    width: 22px; height: 22px; border-radius: 50%;
    background: var(--green); border: 2.5px solid #fff;
    display: flex; align-items: center; justify-content: center;
}
.ph-ava-badge i { color: #fff; font-size: 0.6rem; }

/* Name block */
.ph-info { flex: 1; min-width: 180px; }
.ph-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; color: var(--navy);
    margin: 0 0 0.2rem;
}
.ph-spec { font-size: 0.88rem; font-weight: 600; color: var(--green); margin-bottom: 0.6rem; }

.ph-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.65rem; }
.badge-pill {
    padding: 0.22rem 0.75rem; border-radius: 20px;
    font-size: 0.7rem; font-weight: 700;
    display: inline-flex; align-items: center; gap: 0.25rem;
}
.bp-green  { background: #e6f9ea; color: #1a6b22; }
.bp-blue   { background: #e3f0ff; color: #1a5276; }
.bp-gray   { background: #eef0f3; color: #4a5568; }

.ph-stats { display: flex; gap: 1.3rem; flex-wrap: wrap; margin-bottom: 0.65rem; }
.ph-stat  { font-size: 0.82rem; color: var(--muted); display: flex; align-items: center; gap: 0.35rem; }
.ph-stat strong { color: var(--navy); font-weight: 700; }
.ph-stat i { color: var(--green); font-size: 0.78rem; }

.stars-row { display: flex; align-items: center; gap: 0.35rem; }
.star      { color: var(--gold); font-size: 0.9rem; }
.star.off  { color: #dde3ea; }
.star-lbl  { font-size: 0.82rem; color: var(--muted); font-weight: 600; }

/* Fee + Book */
.ph-action { flex-shrink: 0; min-width: 180px; }
.fee-box {
    background: linear-gradient(135deg, rgba(66,166,73,0.07), rgba(66,166,73,0.14));
    border: 1.5px solid rgba(66,166,73,0.22);
    border-radius: var(--radius-sm); padding: 1rem 1.1rem;
    text-align: center; margin-bottom: 0.8rem;
}
.fee-lbl { font-size: 0.67rem; color: var(--muted); font-weight: 600;
           text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem; }
.fee-amt { font-size: 1.5rem; font-weight: 800; color: var(--green); line-height: 1.1; }
.fee-cur { font-size: 0.62rem; color: #bbb; }

.btn-book {
    display: flex; align-items: center; justify-content: center; gap: 0.45rem;
    background: linear-gradient(135deg, var(--green), var(--green-dk));
    color: #fff; border: none; width: 100%;
    padding: 0.85rem 1rem; border-radius: 25px;
    font-size: 0.88rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    text-decoration: none; transition: all 0.25s;
    box-shadow: 0 4px 14px var(--green-glow);
    cursor: pointer;
}
.btn-book:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 6px 22px rgba(66,166,73,0.4); }

/* Tabs */
.ph-tabs {
    display: flex; gap: 0; padding: 0;
    border-top: 1px solid var(--border);
    overflow-x: auto;
}
.ph-tab {
    padding: 0.85rem 1.2rem; font-size: 0.8rem; font-weight: 600;
    color: var(--muted); text-decoration: none; border: none;
    background: transparent; cursor: pointer;
    border-bottom: 2.5px solid transparent;
    white-space: nowrap; transition: all 0.18s;
    display: inline-flex; align-items: center; gap: 0.35rem;
}
.ph-tab:hover  { color: var(--navy); }
.ph-tab.active { color: var(--green); border-bottom-color: var(--green); }

/* ══ BODY ══════════════════════════════════ */
.ph-body { padding: 2rem 0 4rem; }

/* Section card */
.sc {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow); margin-bottom: 1rem;
    overflow: hidden;
}
.sc-head {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(to right, rgba(26,58,92,0.03), transparent);
}
.sc-title {
    font-size: 0.88rem; font-weight: 700; color: var(--navy);
    display: flex; align-items: center; gap: 0.45rem; margin: 0;
}
.sc-title i { color: var(--green); }
.sc-body { padding: 1.2rem; }

/* About */
.about-text { font-size: 0.9rem; color: #4a5568; line-height: 1.8; margin: 0; }

/* Quals */
.qual-list { list-style: none; padding: 0; margin: 0; }
.qual-item {
    display: flex; align-items: flex-start; gap: 0.7rem;
    padding: 0.6rem 0; border-bottom: 1px solid #f5f7fa;
    font-size: 0.86rem; color: #4a5568;
}
.qual-item:last-child { border: none; padding-bottom: 0; }
.qual-item i { color: var(--green); margin-top: 0.15rem; flex-shrink: 0; }

/* Schedule table */
.sched-wrap { overflow-x: auto; }
.sched-table {
    width: 100%; border-collapse: collapse; font-size: 0.82rem;
}
.sched-table th {
    background: var(--bg); padding: 0.6rem 0.9rem;
    text-align: left; font-size: 0.7rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border);
}
.sched-table td {
    padding: 0.75rem 0.9rem; border-bottom: 1px solid #f5f7fa;
    vertical-align: middle;
}
.sched-table tr:last-child td { border-bottom: none; }
.sched-table tr:hover td { background: #fafbfc; }

.day-tag {
    display: inline-flex; align-items: center;
    font-size: 0.72rem; font-weight: 700;
    padding: 0.18rem 0.6rem; border-radius: 6px;
    background: #e8f0fb; color: var(--navy-lt);
}
.loc-tag {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-size: 0.72rem; font-weight: 700;
    padding: 0.18rem 0.6rem; border-radius: 6px;
}
.lt-hosp { background: #ffe8d6; color: #c0440a; }
.lt-mc   { background: #d6f0e8; color: #0a7a4a; }

.time-range {
    font-size: 0.82rem; font-weight: 600; color: var(--text);
    white-space: nowrap;
}
.max-apts { font-size: 0.78rem; color: var(--muted); }
.fee-cell { font-size: 0.82rem; font-weight: 700; color: var(--green); }

/* No schedule */
.no-data {
    text-align: center; padding: 2.5rem 1rem; color: #c0c8d4;
}
.no-data i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.no-data p { font-size: 0.82rem; margin: 0; }

/* Workplace cards */
.wp-card {
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    padding: 1rem 1.1rem; margin-bottom: 0.75rem; transition: all 0.18s;
}
.wp-card:hover { border-color: var(--green); box-shadow: 0 3px 12px rgba(66,166,73,0.12); }
.wp-card:last-child { margin-bottom: 0; }
.wp-top   { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem; }
.wp-name  { font-size: 0.9rem; font-weight: 700; color: var(--navy); }
.wp-type-badge {
    font-size: 0.65rem; font-weight: 700; padding: 0.18rem 0.55rem;
    border-radius: 6px; white-space: nowrap;
}
.wtb-h  { background: #ffe8d6; color: #c0440a; }
.wtb-mc { background: #d6f0e8; color: #0a7a4a; }
.wp-meta-row { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 0.3rem; }
.wp-meta {
    font-size: 0.77rem; color: var(--muted);
    display: flex; align-items: center; gap: 0.3rem;
}
.wp-meta i { color: var(--green); font-size: 0.72rem; }
.wp-link {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-size: 0.76rem; color: var(--green); font-weight: 600;
    text-decoration: none; margin-top: 0.5rem; transition: color 0.18s;
}
.wp-link:hover { color: var(--navy); }

/* Reviews */
.rv-card {
    border: 1px solid #f0f2f5; border-radius: var(--radius-sm);
    padding: 1rem 1.1rem; margin-bottom: 0.75rem;
    background: #fafbfd;
}
.rv-card:last-child { margin-bottom: 0; }
.rv-top  { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem; }
.rv-user { display: flex; gap: 0.65rem; align-items: center; }
.rv-ava  {
    width: 38px; height: 38px; border-radius: 50%;
    overflow: hidden; border: 2px solid var(--green); flex-shrink: 0;
}
.rv-ava img { width: 100%; height: 100%; object-fit: cover; }
.rv-name  { font-size: 0.84rem; font-weight: 700; color: var(--navy); }
.rv-date  { font-size: 0.7rem; color: #bbb; margin-top: 0.08rem; }
.rv-text  { font-size: 0.83rem; color: #4a5568; line-height: 1.7; margin: 0.6rem 0 0; font-style: italic; }
.rv-text::before { content: '"'; }
.rv-text::after  { content: '"'; }

.btn-review {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: linear-gradient(135deg, var(--green), var(--green-dk));
    color: #fff; border: none;
    padding: 0.4rem 1rem; border-radius: 18px;
    font-size: 0.76rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.18s; box-shadow: 0 3px 10px var(--green-glow);
    text-decoration: none;
}
.btn-review:hover { transform: translateY(-1px); color: #fff; }

/* ══ SIDEBAR ══════════════════════════════ */
.sidebar-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow);
    overflow: hidden; margin-bottom: 1rem;
}
.sidebar-card:last-child { margin-bottom: 0; }

.info-list { padding: 0; margin: 0; }
.info-item {
    display: flex; gap: 0.8rem; align-items: flex-start;
    padding: 0.8rem 1.1rem; border-bottom: 1px solid #f5f7fa;
}
.info-item:last-child { border: none; }
.info-ico {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--navy), var(--navy-lt));
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem;
}
.info-lbl { font-size: 0.68rem; color: var(--muted); font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.1rem; }
.info-val { font-size: 0.85rem; color: var(--text); font-weight: 600; }
.info-val.av  { color: var(--green); }
.info-val.una { color: var(--danger); }

/* Rating summary */
.rating-sum { text-align: center; padding: 1rem 1.1rem 0.5rem; }
.rating-big { font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700; color: var(--navy); line-height: 1; }
.rating-stars-row { display: flex; justify-content: center; gap: 0.15rem; margin: 0.4rem 0; }
.rating-sub { font-size: 0.75rem; color: var(--muted); }
.rating-bar { padding: 0 1.1rem 1rem; }
.rb-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem; }
.rb-lbl { font-size: 0.7rem; color: var(--muted); width: 28px; text-align: right; flex-shrink: 0; }
.rb-bar { flex: 1; height: 6px; background: #f0f2f5; border-radius: 3px; overflow: hidden; }
.rb-fill { height: 100%; background: var(--gold); border-radius: 3px; transition: width 0.6s; }
.rb-cnt { font-size: 0.68rem; color: var(--muted); width: 20px; }

/* Book sidebar card */
.book-sidebar {
    padding: 1.2rem 1.1rem;
    background: linear-gradient(135deg, rgba(66,166,73,0.05), rgba(66,166,73,0.11));
    border-top: 3px solid var(--green);
}
.book-sidebar p { font-size: 0.82rem; color: #4a5568; line-height: 1.65; margin-bottom: 1rem; }

/* ══ REVIEW MODAL ═════════════════════════ */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.5); z-index: 9999;
    align-items: center; justify-content: center; padding: 1rem;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: #fff; border-radius: 18px; padding: 1.8rem;
    width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: mPop 0.25s ease; position: relative;
}
@keyframes mPop {
    from { opacity:0; transform: scale(0.93) translateY(18px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.modal-close {
    position: absolute; top: 1rem; right: 1rem;
    width: 28px; height: 28px; border-radius: 50%;
    background: #f5f5f5; border: none; cursor: pointer; font-size: 0.82rem;
    display: flex; align-items: center; justify-content: center; color: #666;
    transition: background 0.18s;
}
.modal-close:hover { background: #e0e0e0; }
.modal-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--navy); margin-bottom: 0.2rem; }
.modal-sub   { font-size: 0.8rem; color: var(--muted); margin-bottom: 1.3rem; }
.m-label { font-size: 0.8rem; font-weight: 600; color: var(--navy); display: block; margin-bottom: 0.4rem; }
.m-label .req { color: var(--danger); }
.star-picker { display: flex; gap: 0.25rem; margin-bottom: 0.4rem; }
.star-picker i { font-size: 2rem; cursor: pointer; color: #dde3ea; transition: all 0.13s; }
.star-picker i:hover, .star-picker i.lit { color: var(--gold); }
.star-picker i:hover { transform: scale(1.15); }
.m-textarea {
    width: 100%; padding: 0.7rem 0.9rem;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: 0.85rem; resize: vertical; min-height: 90px;
    font-family: 'DM Sans', sans-serif; color: var(--text);
    transition: border-color 0.2s;
}
.m-textarea:focus { border-color: var(--green); outline: none; }
.err-msg { font-size: 0.73rem; color: var(--danger); display: none; margin-top: 0.25rem; }
.m-footer { display: flex; gap: 0.6rem; justify-content: flex-end; margin-top: 1.2rem; }
.btn-cancel-m {
    background: #fff; color: var(--muted); border: 1.5px solid var(--border);
    padding: 0.5rem 1.2rem; border-radius: 20px;
    font-size: 0.83rem; font-weight: 600; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: all 0.18s;
}
.btn-cancel-m:hover { background: #f5f5f5; }
.btn-submit-m {
    background: linear-gradient(135deg, var(--green), var(--green-dk));
    color: #fff; border: none; padding: 0.5rem 1.5rem;
    border-radius: 20px; font-size: 0.83rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    box-shadow: 0 3px 10px var(--green-glow); transition: all 0.18s;
}
.btn-submit-m:hover { transform: translateY(-1px); }
.btn-submit-m:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

/* Alerts */
.f-alert {
    border-radius: var(--radius-sm); padding: 0.75rem 1rem;
    margin-bottom: 1rem; display: flex; align-items: flex-start;
    gap: 0.55rem; font-size: 0.84rem;
}
.f-alert.success { background: #f0fdf4; color: #166534; border-left: 3.5px solid var(--green); }
.f-alert.error   { background: #fef2f2; color: #991b1b; border-left: 3.5px solid var(--danger); }
.f-alert.info    { background: #eff6ff; color: #1e40af; border-left: 3.5px solid #3b82f6; }

@media (max-width: 768px) {
    .ph-top     { flex-direction: column; }
    .ph-action  { width: 100%; }
    .ph-name    { font-size: 1.3rem; }
    .ph-ava     { width: 85px; height: 85px; }
    .sc-body    { padding: 1rem; }
}
</style>

{{-- ══ HERO ══ --}}
<section class="ph-hero">
<div class="container">
    <a href="{{ route('patient.doctors') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Doctors
    </a>

    <div class="ph-card">
        <div class="ph-top">

            {{-- Avatar --}}
            @php
                $profImg   = $doctor->profile_image ? asset('storage/'.$doctor->profile_image) : asset('images/default-avatar.png');
                $rating    = $doctor->rating ?? 0;
                $fullS     = floor($rating);
                $halfS     = ($rating - $fullS) >= 0.5;
                $emptyS    = 5 - $fullS - ($halfS ? 1 : 0);
                $isActive  = $doctor->user && $doctor->user->status === 'active';
            @endphp
            <div class="ph-ava-wrap">
                <img src="{{ $profImg }}" class="ph-ava" alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                @if($isActive)
                <div class="ph-ava-badge"><i class="fas fa-check"></i></div>
                @endif
            </div>

            {{-- Info --}}
            <div class="ph-info">
                <h1 class="ph-name">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h1>
                <div class="ph-spec">{{ $doctor->specialization ?? 'General Practitioner' }}</div>

                <div class="ph-badges">
                    @if($doctor->status === 'approved')
                        <span class="badge-pill bp-green"><i class="fas fa-shield-alt"></i> Verified</span>
                    @endif
                    @if($isActive)
                        <span class="badge-pill bp-blue"><i class="fas fa-circle" style="font-size:.5rem;"></i> Available</span>
                    @endif
                    @if($doctor->slmc_number)
                        <span class="badge-pill bp-gray"><i class="fas fa-id-badge"></i> SLMC: {{ $doctor->slmc_number }}</span>
                    @endif
                </div>

                <div class="ph-stats">
                    @if($doctor->experience_years)
                        <div class="ph-stat"><i class="fas fa-briefcase-medical"></i> <strong>{{ $doctor->experience_years }}</strong> yrs exp.</div>
                    @endif
                    @if($totalAppointments > 0)
                        <div class="ph-stat"><i class="fas fa-calendar-check"></i> <strong>{{ $totalAppointments }}</strong> appointments</div>
                    @endif
                    @if($workplaces->count() > 0)
                        <div class="ph-stat"><i class="fas fa-hospital"></i> <strong>{{ $workplaces->count() }}</strong> location{{ $workplaces->count() > 1 ? 's' : '' }}</div>
                    @endif
                    @if($doctor->total_ratings > 0)
                        <div class="ph-stat"><i class="fas fa-star"></i> <strong>{{ $doctor->total_ratings }}</strong> reviews</div>
                    @endif
                </div>

                <div class="stars-row">
                    @for($i=0;$i<$fullS;$i++)<i class="fas fa-star star"></i>@endfor
                    @if($halfS)<i class="fas fa-star-half-alt star"></i>@endif
                    @for($i=0;$i<$emptyS;$i++)<i class="far fa-star star off"></i>@endfor
                    <span class="star-lbl">{{ number_format($rating,1) }} / 5</span>
                </div>
            </div>

            {{-- Fee + Book --}}
            <div class="ph-action">
                <div class="fee-box">
                    <div class="fee-lbl">Consultation Fee</div>
                    <div class="fee-amt">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                    <div class="fee-cur">Sri Lankan Rupees</div>
                </div>
                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                   class="btn-book">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>

        </div>

        {{-- Tabs --}}
        <div class="ph-tabs" id="profileTabs">
            <button class="ph-tab active" onclick="switchTab('about')">
                <i class="fas fa-user-circle"></i> About
            </button>
            <button class="ph-tab" onclick="switchTab('schedule')">
                <i class="fas fa-calendar-alt"></i> Schedule
            </button>
            <button class="ph-tab" onclick="switchTab('locations')">
                <i class="fas fa-hospital"></i> Locations
            </button>
            <button class="ph-tab" onclick="switchTab('reviews')">
                <i class="fas fa-star"></i> Reviews
                @if($doctor->total_ratings > 0)
                    <span class="badge-pill bp-green" style="padding:.1rem .45rem;font-size:.62rem;">{{ $doctor->total_ratings }}</span>
                @endif
            </button>
        </div>
    </div>
</div>
</section>

{{-- ══ BODY ══ --}}
<section class="ph-body">
<div class="container">

    {{-- Flash alerts --}}
    @foreach(['success'=>'success','error'=>'error','info'=>'info'] as $sk=>$st)
        @if(session($sk))
            <div class="f-alert {{$st}}">
                <i class="fas fa-{{ $st==='success'?'check-circle':($st==='error'?'times-circle':'info-circle') }}"></i>
                <span>{{ session($sk) }}</span>
            </div>
        @endif
    @endforeach

    <div class="row g-3">

    {{-- ══ LEFT ══ --}}
    <div class="col-lg-8">

        {{-- ── ABOUT TAB ── --}}
        <div id="tab-about">

            @if($doctor->bio)
            <div class="sc">
                <div class="sc-head">
                    <h2 class="sc-title"><i class="fas fa-user-circle"></i> About</h2>
                </div>
                <div class="sc-body">
                    <p class="about-text">{{ $doctor->bio }}</p>
                </div>
            </div>
            @endif

            @if($doctor->qualifications)
            <div class="sc">
                <div class="sc-head">
                    <h2 class="sc-title"><i class="fas fa-graduation-cap"></i> Education & Qualifications</h2>
                </div>
                <div class="sc-body">
                    <ul class="qual-list">
                        @foreach(explode(',', $doctor->qualifications) as $q)
                        <li class="qual-item">
                            <i class="fas fa-certificate"></i>
                            <span>{{ trim($q) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

        </div>

        {{-- ── SCHEDULE TAB ── --}}
        <div id="tab-schedule" style="display:none;">
            <div class="sc">
                <div class="sc-head">
                    <h2 class="sc-title"><i class="fas fa-calendar-alt"></i> Weekly Schedule</h2>
                    <span style="font-size:.72rem;color:var(--muted);">Showing approved schedules</span>
                </div>
                <div class="sc-body" style="padding:0;">
                    @php
                        $schedules = \Illuminate\Support\Facades\DB::table('doctor_schedules')
                            ->where('doctor_id', $doctor->id)
                            ->where('is_active', 1)
                            ->orderByRaw("FIELD(day_of_week,'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")
                            ->orderBy('start_time')
                            ->get();

                        $dayOrder = ['monday'=>'Mon','tuesday'=>'Tue','wednesday'=>'Wed','thursday'=>'Thu',
                                     'friday'=>'Fri','saturday'=>'Sat','sunday'=>'Sun'];

                        // Get place names
                        $hospitals = \Illuminate\Support\Facades\DB::table('hospitals')
                            ->pluck('name','id');
                        $mcs = \Illuminate\Support\Facades\DB::table('medical_centres')
                            ->pluck('name','id');
                    @endphp

                    @if($schedules->count() > 0)
                    <div class="sched-wrap">
                        <table class="sched-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Location</th>
                                    <th>Time</th>
                                    <th>Max Apts</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $sch)
                                @php
                                    $locName = 'Private Clinic';
                                    $locClass = '';
                                    if ($sch->workplace_type === 'hospital') {
                                        $locName = $hospitals[$sch->workplace_id] ?? 'Hospital';
                                        $locClass = 'lt-hosp';
                                    } elseif ($sch->workplace_type === 'medical_centre') {
                                        $locName = $mcs[$sch->workplace_id] ?? 'Medical Centre';
                                        $locClass = 'lt-mc';
                                    }
                                    $startFmt = \Carbon\Carbon::createFromTimeString($sch->start_time)->format('h:i A');
                                    $endFmt   = \Carbon\Carbon::createFromTimeString($sch->end_time)->format('h:i A');
                                @endphp
                                <tr>
                                    <td>
                                        <span class="day-tag">{{ $dayOrder[$sch->day_of_week] ?? ucfirst($sch->day_of_week) }}</span>
                                    </td>
                                    <td>
                                        @if($sch->workplace_type !== 'private')
                                        <span class="loc-tag {{ $locClass }}">
                                            <i class="fas fa-{{ $sch->workplace_type==='hospital'?'hospital':'clinic-medical' }}" style="font-size:.65rem;"></i>
                                            {{ \Illuminate\Support\Str::limit($locName, 30) }}
                                        </span>
                                        @else
                                        <span class="loc-tag" style="background:#f0f2f5;color:var(--muted);">
                                            <i class="fas fa-user-md" style="font-size:.65rem;"></i>
                                            Private Clinic
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="time-range">{{ $startFmt }} – {{ $endFmt }}</span>
                                    </td>
                                    <td>
                                        <span class="max-apts">{{ $sch->max_appointments }} slots</span>
                                    </td>
                                    <td>
                                        @if($sch->consultation_fee)
                                            <span class="fee-cell">Rs. {{ number_format($sch->consultation_fee, 0) }}</span>
                                        @else
                                            <span class="max-apts">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-data" style="padding:2rem;">
                        <i class="fas fa-calendar-times"></i>
                        <p>No schedule available at the moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── LOCATIONS TAB ── --}}
        <div id="tab-locations" style="display:none;">
            <div class="sc">
                <div class="sc-head">
                    <h2 class="sc-title"><i class="fas fa-hospital"></i> Practice Locations</h2>
                    <span style="font-size:.72rem;color:var(--muted);">{{ $workplaces->count() }} approved location{{ $workplaces->count() != 1 ? 's' : '' }}</span>
                </div>
                <div class="sc-body">
                    @if($workplaces->count() > 0)
                        @foreach($workplaces as $wp)
                        @php
                            $wName  = 'Unknown'; $wAddr = ''; $wCity = '';
                            $wPhone = ''; $wLink = null;
                            $wType  = ucwords(str_replace('_',' ',$wp->workplace_type));
                            $wClass = $wp->workplace_type === 'hospital' ? 'wtb-h' : 'wtb-mc';

                            if ($wp->workplace_type === 'hospital' && $wp->hospital) {
                                $wName  = $wp->hospital->name;
                                $wAddr  = $wp->hospital->address ?? '';
                                $wCity  = $wp->hospital->city ?? '';
                                $wPhone = $wp->hospital->phone ?? '';
                                $wLink  = route('patient.hospitals.show', $wp->hospital->id);
                            } elseif ($wp->workplace_type === 'medical_centre' && $wp->medicalCentre) {
                                $wName  = $wp->medicalCentre->name;
                                $wAddr  = $wp->medicalCentre->address ?? '';
                                $wCity  = $wp->medicalCentre->city ?? '';
                                $wPhone = $wp->medicalCentre->phone ?? '';
                                $wLink  = route('patient.medical-centres.show', $wp->medicalCentre->id);
                            }
                        @endphp
                        <div class="wp-card">
                            <div class="wp-top">
                                <div class="wp-name">{{ $wName }}</div>
                                <span class="wp-type-badge {{ $wClass }}">{{ $wType }}</span>
                            </div>
                            <div class="wp-meta-row">
                                @if($wAddr)
                                    <div class="wp-meta"><i class="fas fa-map-marker-alt"></i> {{ $wAddr }}</div>
                                @endif
                                @if($wCity)
                                    <div class="wp-meta"><i class="fas fa-city"></i> {{ $wCity }}</div>
                                @endif
                                @if($wPhone)
                                    <div class="wp-meta"><i class="fas fa-phone"></i> {{ $wPhone }}</div>
                                @endif
                                @if($wp->employment_type)
                                    <div class="wp-meta"><i class="fas fa-briefcase"></i> {{ ucfirst($wp->employment_type) }}</div>
                                @endif
                            </div>
                            @if($wLink)
                                <a href="{{ $wLink }}" class="wp-link">View Details <i class="fas fa-arrow-right"></i></a>
                            @endif
                        </div>
                        @endforeach
                    @else
                    <div class="no-data">
                        <i class="fas fa-hospital-alt"></i>
                        <p>No approved practice locations found.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── REVIEWS TAB ── --}}
        <div id="tab-reviews" style="display:none;">
            <div class="sc">
                <div class="sc-head">
                    <h2 class="sc-title">
                        <i class="fas fa-comments"></i> Patient Reviews
                        @if($doctor->total_ratings > 0)
                            <span class="badge-pill bp-green" style="font-size:.65rem;">({{ $doctor->total_ratings }})</span>
                        @endif
                    </h2>

                    @auth
                        @if($canReview)
                            <button class="btn-review" onclick="openReviewModal()">
                                <i class="fas fa-star"></i> Write a Review
                            </button>
                        @elseif($alreadyReviewed)
                            <span style="font-size:.76rem;color:var(--green);font-weight:600;">
                                <i class="fas fa-check-circle"></i> You've reviewed
                            </span>
                        @else
                            <span style="font-size:.73rem;color:var(--muted);font-style:italic;">
                                Complete appointment to review
                            </span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" style="font-size:.76rem;color:var(--green);font-weight:600;text-decoration:none;">
                            <i class="fas fa-sign-in-alt"></i> Login to review
                        </a>
                    @endauth
                </div>
                <div class="sc-body">
                    @if($reviews->count() > 0)
                        @foreach($reviews as $rv)
                        @php
                            $rvImg  = asset('images/default-avatar.png');
                            $rvName = 'Anonymous Patient';
                            if ($rv->patient && $rv->patient->user) {
                                $rvName = trim(($rv->patient->first_name ?? '') . ' ' . ($rv->patient->last_name ?? ''));
                                if ($rvName === '') $rvName = 'Anonymous Patient';
                            }
                        @endphp
                        <div class="rv-card">
                            <div class="rv-top">
                                <div class="rv-user">
                                    <div class="rv-ava">
                                        <img src="{{ $rvImg }}" alt="{{ $rvName }}"
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div>
                                        <div class="rv-name">{{ $rvName }}</div>
                                        <div class="rv-date">
                                            <i class="far fa-clock" style="font-size:.65rem;"></i>
                                            {{ $rv->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="stars-row">
                                    @for($i=1;$i<=5;$i++)
                                        <i class="{{ $i<=$rv->rating ? 'fas' : 'far' }} fa-star star{{ $i>$rv->rating?' off':'' }}" style="font-size:.82rem;"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rv->review)
                                <p class="rv-text">{{ $rv->review }}</p>
                            @endif
                        </div>
                        @endforeach

                        @if($reviews->hasPages())
                        <div style="margin-top:1rem;">{{ $reviews->links() }}</div>
                        @endif
                    @else
                    <div class="no-data">
                        <i class="far fa-comment-dots"></i>
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>{{-- /col-lg-8 --}}

    {{-- ══ SIDEBAR ══ --}}
    <div class="col-lg-4">

        {{-- Professional Info --}}
        <div class="sidebar-card">
            <div class="sc-head" style="padding:.85rem 1.1rem;">
                <h2 class="sc-title" style="font-size:.83rem;"><i class="fas fa-id-card"></i> Professional Info</h2>
            </div>
            <div class="info-list">
                @if($doctor->slmc_number)
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-id-badge"></i></div>
                    <div>
                        <div class="info-lbl">SLMC Registration</div>
                        <div class="info-val">{{ $doctor->slmc_number }}</div>
                    </div>
                </div>
                @endif
                @if($doctor->specialization)
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-stethoscope"></i></div>
                    <div>
                        <div class="info-lbl">Specialization</div>
                        <div class="info-val">{{ $doctor->specialization }}</div>
                    </div>
                </div>
                @endif
                @if($doctor->experience_years)
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <div class="info-lbl">Experience</div>
                        <div class="info-val">{{ $doctor->experience_years }} {{ Str::plural('year', $doctor->experience_years) }}</div>
                    </div>
                </div>
                @endif
                @if($doctor->phone)
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="info-lbl">Contact</div>
                        <div class="info-val">{{ $doctor->phone }}</div>
                    </div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-circle" style="font-size:.7rem;"></i></div>
                    <div>
                        <div class="info-lbl">Status</div>
                        <div class="info-val {{ $isActive ? 'av' : 'una' }}">
                            {{ $isActive ? '● Available' : '● Unavailable' }}
                        </div>
                    </div>
                </div>
                @if($completedCount > 0)
                <div class="info-item">
                    <div class="info-ico"><i class="fas fa-flag-checkered"></i></div>
                    <div>
                        <div class="info-lbl">Completed Consultations</div>
                        <div class="info-val">{{ $completedCount }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Rating Overview --}}
        @if($doctor->total_ratings > 0)
        @php
            $ratingsData = \Illuminate\Support\Facades\DB::table('ratings')
                ->where('ratable_type','doctor')->where('ratable_id',$doctor->id)
                ->selectRaw('rating, COUNT(*) as cnt')
                ->groupBy('rating')->pluck('cnt','rating');
        @endphp
        <div class="sidebar-card">
            <div class="sc-head" style="padding:.85rem 1.1rem;">
                <h2 class="sc-title" style="font-size:.83rem;"><i class="fas fa-star"></i> Rating Overview</h2>
            </div>
            <div class="rating-sum">
                <div class="rating-big">{{ number_format($doctor->rating,1) }}</div>
                <div class="rating-stars-row">
                    @for($i=1;$i<=5;$i++)
                        <i class="{{ $i<=round($doctor->rating)?'fas':'far' }} fa-star star{{ $i>round($doctor->rating)?' off':'' }}" style="font-size:.88rem;"></i>
                    @endfor
                </div>
                <div class="rating-sub">Based on {{ $doctor->total_ratings }} {{ Str::plural('review',$doctor->total_ratings) }}</div>
            </div>
            <div class="rating-bar">
                @foreach([5,4,3,2,1] as $star)
                @php
                    $cnt = $ratingsData[$star] ?? 0;
                    $pct = $doctor->total_ratings > 0 ? round(($cnt / $doctor->total_ratings) * 100) : 0;
                @endphp
                <div class="rb-row">
                    <span class="rb-lbl">{{ $star }}★</span>
                    <div class="rb-bar"><div class="rb-fill" style="width:{{ $pct }}%;"></div></div>
                    <span class="rb-cnt">{{ $cnt }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Book CTA --}}
        <div class="sidebar-card">
            <div class="book-sidebar">
                <h2 class="sc-title" style="font-size:.88rem;margin-bottom:.7rem;"><i class="fas fa-calendar-check"></i> Book Appointment</h2>
                <p>Get professional medical consultation from Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.</p>
                <a href="{{ route('patient.appointments.create', ['doctor_id' => $doctor->id]) }}"
                   class="btn-book">
                    <i class="fas fa-calendar-plus"></i> Book Now
                </a>
            </div>
        </div>

    </div>

    </div>{{-- /row --}}
</div>
</section>

{{-- ══ REVIEW MODAL ══ --}}
@auth
@if($canReview)
<div class="modal-overlay" id="reviewModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeReviewModal()"><i class="fas fa-times"></i></button>
        <div class="modal-title"><i class="fas fa-star" style="color:var(--gold);"></i> Leave a Review</div>
        <div class="modal-sub">Share your experience with Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>

        <form action="{{ route('patient.doctors.review.store', $doctor->id) }}" method="POST" id="rvForm">
            @csrf
            <div style="margin-bottom:1rem;">
                <label class="m-label">Your Rating <span class="req">*</span></label>
                <div class="star-picker" id="starPicker">
                    @for($s=1;$s<=5;$s++)
                        <i class="far fa-star" data-val="{{ $s }}"
                           onmouseover="hStar({{ $s }})" onmouseout="rStars()"
                           onclick="sStar({{ $s }})"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
                <div class="err-msg" id="starErr">Please select a star rating.</div>
                <div id="ratingLabel" style="font-size:.76rem;color:var(--gold);font-weight:600;margin-top:.25rem;min-height:1rem;"></div>
            </div>
            <div style="margin-bottom:.5rem;">
                <label class="m-label" for="rvText">
                    Your Review
                    <span style="color:var(--muted);font-weight:400;">(optional)</span>
                </label>
                <textarea name="review" id="rvText" class="m-textarea"
                    placeholder="Describe your experience — consultation quality, behaviour, waiting time..."
                    maxlength="1000"></textarea>
                <div style="font-size:.68rem;color:var(--muted);text-align:right;margin-top:.2rem;">
                    <span id="charCnt">0</span> / 1000
                </div>
            </div>
            <div class="m-footer">
                <button type="button" class="btn-cancel-m" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn-submit-m" id="rvSubmit">
                    <i class="fas fa-paper-plane me-1"></i> Submit Review
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth

@include('partials.footer')

<script>
/* ── TABS ─────────────────────────────────── */
function switchTab(name) {
    ['about','schedule','locations','reviews'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === name ? '' : 'none';
    });
    document.querySelectorAll('.ph-tab').forEach((btn, i) => {
        const tabs = ['about','schedule','locations','reviews'];
        btn.classList.toggle('active', tabs[i] === name);
    });
    // Persist in URL hash
    history.replaceState(null, '', '#' + name);
}

// Init from hash
window.addEventListener('DOMContentLoaded', () => {
    const hash = location.hash.replace('#','') || 'about';
    const valid = ['about','schedule','locations','reviews'];
    switchTab(valid.includes(hash) ? hash : 'about');
});

/* ── REVIEW MODAL ─────────────────────────── */
function openReviewModal() {
    document.getElementById('reviewModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeReviewModal() {
    document.getElementById('reviewModal')?.classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('reviewModal')?.addEventListener('click', e => {
    if (e.target === e.currentTarget) closeReviewModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReviewModal(); });

/* ── STAR PICKER ──────────────────────────── */
let selRating = 0;
const labels = ['','Poor','Fair','Good','Very Good','Excellent'];
function hStar(v) {
    document.querySelectorAll('#starPicker i').forEach((s,i) => {
        s.className = i < v ? 'fas fa-star lit' : 'far fa-star';
    });
    document.getElementById('ratingLabel').textContent = labels[v] || '';
}
function rStars() {
    document.querySelectorAll('#starPicker i').forEach((s,i) => {
        s.className = i < selRating ? 'fas fa-star lit' : 'far fa-star';
    });
    document.getElementById('ratingLabel').textContent = labels[selRating] || '';
}
function sStar(v) {
    selRating = v;
    document.getElementById('ratingInput').value = v;
    document.getElementById('starErr').style.display = 'none';
    rStars();
}

/* ── CHAR COUNTER ─────────────────────────── */
document.getElementById('rvText')?.addEventListener('input', function() {
    document.getElementById('charCnt').textContent = this.value.length;
});

/* ── FORM VALIDATION ──────────────────────── */
document.getElementById('rvForm')?.addEventListener('submit', function(e) {
    if (selRating === 0) {
        e.preventDefault();
        document.getElementById('starErr').style.display = 'block';
        document.getElementById('starPicker').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    const btn = document.getElementById('rvSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting…';
});

/* ── AUTO-OPEN MODAL ON VALIDATION ERROR ─── */
@if($errors->has('rating'))
    window.addEventListener('DOMContentLoaded', () => {
        switchTab('reviews');
        openReviewModal();
    });
@endif

/* ── AUTO-DISMISS ALERTS ─────────────────── */
setTimeout(() => {
    document.querySelectorAll('.f-alert').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
