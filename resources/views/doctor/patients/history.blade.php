@extends('doctor.layouts.master')

@section('title', 'Patient History')
@section('page-title', 'Patient History')

@push('styles')
<style>
.detail-card { background:#fff; border-radius:16px; padding:1.4rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }
.dc-title { font-size:.82rem; font-weight:700; color:#1a1a1a;
    padding-bottom:.65rem; border-bottom:1px solid #f0f3f8;
    margin-bottom:1rem; display:flex; align-items:center; gap:.4rem; }
.dc-title i { color:#0d6efd; }

/* ── Patient Header ── */
.pt-header { background:linear-gradient(135deg,#0d6efd 0%,#6f42c1 100%);
    border-radius:16px; padding:1.4rem; color:#fff; margin-bottom:1.2rem;
    display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
.pt-av-img { width:64px; height:64px; border-radius:50%;
    border:3px solid rgba(255,255,255,.4); object-fit:cover; flex-shrink:0; }
.pt-av-init { width:64px; height:64px; border-radius:50%;
    border:3px solid rgba(255,255,255,.4); background:rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:1.5rem; font-weight:800; flex-shrink:0; }
.pt-header-info h4 { font-size:1rem; font-weight:800; margin:0 0 .2rem; }
.pt-header-info p  { font-size:.78rem; opacity:.8; margin:0 0 .15rem; }
.pt-header-stats { margin-left:auto; display:flex; gap:1.2rem; }
.pt-hs .n { font-size:1.3rem; font-weight:800; line-height:1; }
.pt-hs .l { font-size:.65rem; opacity:.7; font-weight:600; margin-top:.1rem; }

/* ── Status Pills ── */
.sp { display:inline-flex; align-items:center; padding:.22rem .7rem;
    border-radius:20px; font-size:.72rem; font-weight:700; gap:.3rem; }
.sp.pending   { background:#fff3cd; color:#856404; }
.sp.confirmed { background:#d1ecf1; color:#0c5460; }
.sp.completed { background:#d4edda; color:#155724; }
.sp.cancelled { background:#f8d7da; color:#721c24; }
.sp.no-show   { background:#f0f0f0; color:#555; }

/* ── Filter Bar ── */
.filter-bar { background:#fff; border-radius:12px; padding:.75rem 1rem;
    box-shadow:0 2px 8px rgba(0,0,0,.04); margin-bottom:1rem;
    display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
.fb-btn { padding:.28rem .8rem; border-radius:20px; border:1px solid #dee2e6;
    background:#fff; font-size:.74rem; font-weight:600; color:#555;
    cursor:pointer; transition:all .15s; text-decoration:none; }
.fb-btn:hover { background:#0d6efd; border-color:#0d6efd; color:#fff; }
.fb-btn.active { background:#0d6efd; border-color:#0d6efd; color:#fff; }

/* ── Timeline ── */
.history-timeline { position:relative; padding-left:2rem; }
.history-timeline::before { content:''; position:absolute; left:15px;
    top:0; bottom:0; width:2px; background:#e8edf2; }

.ht-item { position:relative; margin-bottom:1rem; }
.ht-item:last-child { margin-bottom:0; }

.ht-dot { position:absolute; left:-2rem; top:14px;
    width:14px; height:14px; border-radius:50%;
    border:2px solid #fff; }
.ht-dot.pending   { background:#ffc107; box-shadow:0 0 0 2px #ffc107; }
.ht-dot.confirmed { background:#0dcaf0; box-shadow:0 0 0 2px #0dcaf0; }
.ht-dot.completed { background:#198754; box-shadow:0 0 0 2px #198754; }
.ht-dot.cancelled { background:#dc3545; box-shadow:0 0 0 2px #dc3545; }
.ht-dot.no-show   { background:#adb5bd; box-shadow:0 0 0 2px #adb5bd; }

.ht-card { background:#fff; border:1px solid #f0f3f8; border-radius:12px;
    padding:.9rem 1rem; transition:all .15s; }
.ht-card:hover { border-color:#d0e0ff; background:#f8faff;
    box-shadow:0 2px 10px rgba(13,110,253,.06); }

.ht-date { font-size:.7rem; color:#aaa; margin-bottom:.4rem;
    display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.ht-body { display:flex; justify-content:space-between;
    align-items:flex-start; gap:.5rem; }
.ht-left h6 { font-size:.83rem; font-weight:700; color:#1a1a1a; margin:0 0 .15rem; }
.ht-left p  { font-size:.74rem; color:#888; margin:0; }
.ht-right   { display:flex; flex-direction:column; align-items:flex-end; gap:.3rem; flex-shrink:0; }

.ht-note { margin-top:.55rem; padding:.4rem .7rem;
    background:#f0f5ff; border-radius:8px; border-left:3px solid #0d6efd;
    font-size:.74rem; color:#444; line-height:1.5; }
.ht-cancel { margin-top:.5rem; padding:.4rem .7rem;
    background:#fff5f5; border-radius:8px; border-left:3px solid #dc3545;
    font-size:.74rem; color:#721c24; line-height:1.5; }

/* ── Empty ── */
.empty-state { text-align:center; padding:3rem 1rem; color:#c0c8d4; }
.empty-state i { font-size:2.5rem; display:block; margin-bottom:.5rem; }
</style>
@endpush

@section('content')

@php
    // ── Safe column resolution ──
    // patients table: firstname / lastname (no underscore)
    $ptFirstName  = $patient->firstname  ?? $patient->first_name  ?? '';
    $ptLastName   = $patient->lastname   ?? $patient->last_name   ?? '';
    $ptFullName   = trim($ptFirstName . ' ' . $ptLastName);
    $ptImg        = $patient->profile_image  ?? $patient->profileimage  ?? null;
    $ptPhone      = $patient->phone          ?? '';
    $ptCity       = $patient->city           ?? '';
    $ptDob        = $patient->date_of_birth  ?? $patient->dateofbirth   ?? null;
    $ptBlood      = $patient->blood_group    ?? $patient->bloodgroup     ?? null;
    $ptGender     = $patient->gender         ?? null;
@endphp

{{-- Breadcrumb --}}
<nav style="font-size:.78rem;margin-bottom:.8rem">
    <a href="{{ route('doctor.dashboard') }}" style="color:#0d6efd;text-decoration:none">Dashboard</a>
    <span class="mx-1 text-muted">/</span>
    <a href="{{ route('doctor.patients.index') }}" style="color:#0d6efd;text-decoration:none">My Patients</a>
    <span class="mx-1 text-muted">/</span>
    <a href="{{ route('doctor.patients.show', $patient->id) }}"
       style="color:#0d6efd;text-decoration:none">{{ $ptFullName }}</a>
    <span class="mx-1 text-muted">/</span>
    <span class="text-muted">History</span>
</nav>

{{-- Patient Header Banner --}}
<div class="pt-header">

    {{-- Avatar --}}
    @if($ptImg)
        <img src="{{ asset('storage/' . $ptImg) }}"
             alt="{{ $ptFullName }}"
             class="pt-av-img"
             onerror="this.style.display='none';
                      this.nextElementSibling.style.display='flex'">
        <div class="pt-av-init" style="display:none">
            {{ strtoupper(substr($ptFirstName, 0, 1)) }}
        </div>
    @else
        <div class="pt-av-init">
            {{ strtoupper(substr($ptFirstName ?: 'P', 0, 1)) }}
        </div>
    @endif

    {{-- Info --}}
    <div class="pt-header-info">
        <h4>{{ $ptFullName }}</h4>
        <p>
            @if($ptDob)
                <i class="fas fa-birthday-cake me-1" style="font-size:.7rem"></i>
                {{ \Carbon\Carbon::parse($ptDob)->age }} yrs
            @endif
            @if($ptGender)
                &nbsp;•&nbsp; {{ ucfirst($ptGender) }}
            @endif
            @if($ptBlood)
                &nbsp;•&nbsp;
                <span style="background:rgba(255,255,255,.2);padding:.1rem .4rem;
                    border-radius:8px;font-size:.7rem">{{ $ptBlood }}</span>
            @endif
        </p>
        @if($ptPhone || $ptCity)
        <p>
            @if($ptPhone)
                <i class="fas fa-phone me-1" style="font-size:.68rem"></i>{{ $ptPhone }}
            @endif
            @if($ptCity)
                &nbsp;•&nbsp;
                <i class="fas fa-map-marker-alt me-1" style="font-size:.68rem"></i>{{ $ptCity }}
            @endif
        </p>
        @endif
    </div>

    {{-- Stats --}}
    <div class="pt-header-stats d-none d-md-flex">
        <div class="pt-hs text-center">
            <div class="n">{{ $appointments->count() }}</div>
            <div class="l">Total</div>
        </div>
        <div class="pt-hs text-center">
            <div class="n">{{ $appointments->where('status','completed')->count() }}</div>
            <div class="l">Completed</div>
        </div>
        <div class="pt-hs text-center">
            <div class="n">{{ $appointments->where('status','cancelled')->count() }}</div>
            <div class="l">Cancelled</div>
        </div>
        <div class="pt-hs text-center">
            <div class="n" style="font-size:.9rem">
                LKR {{ number_format($appointments->where('status','completed')->sum('consultation_fee'), 0) }}
            </div>
            <div class="l">Total Paid</div>
        </div>
    </div>
</div>

{{-- Back Button --}}
<div class="mb-3 d-flex gap-2">
    <a href="{{ route('doctor.patients.show', $patient->id) }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Profile
    </a>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <span style="font-size:.74rem;font-weight:700;color:#555">
        <i class="fas fa-filter me-1"></i>Filter:
    </span>
    <a href="{{ route('doctor.patients.history', $patient->id) }}"
       class="fb-btn {{ !request('status') ? 'active' : '' }}">
        All ({{ $appointments->count() }})
    </a>
    <a href="{{ route('doctor.patients.history', [$patient->id, 'status' => 'pending']) }}"
       class="fb-btn {{ request('status') === 'pending' ? 'active' : '' }}">
        Pending ({{ $appointments->where('status','pending')->count() }})
    </a>
    <a href="{{ route('doctor.patients.history', [$patient->id, 'status' => 'confirmed']) }}"
       class="fb-btn {{ request('status') === 'confirmed' ? 'active' : '' }}">
        Confirmed ({{ $appointments->where('status','confirmed')->count() }})
    </a>
    <a href="{{ route('doctor.patients.history', [$patient->id, 'status' => 'completed']) }}"
       class="fb-btn {{ request('status') === 'completed' ? 'active' : '' }}">
        Completed ({{ $appointments->where('status','completed')->count() }})
    </a>
    <a href="{{ route('doctor.patients.history', [$patient->id, 'status' => 'cancelled']) }}"
       class="fb-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">
        Cancelled ({{ $appointments->where('status','cancelled')->count() }})
    </a>
</div>

{{-- Timeline Card --}}
<div class="detail-card">
    <div class="dc-title">
        <i class="fas fa-history"></i>
        Appointment Timeline
        <span class="ms-auto" style="font-size:.71rem;color:#aaa;font-weight:500">
            Latest first
        </span>
    </div>

    @php
        $filtered = request('status')
            ? $appointments->where('status', request('status'))
            : $appointments;
    @endphp

    @if($filtered->count() > 0)
    <div class="history-timeline">
        @foreach($filtered as $apt)
        <div class="ht-item">
            {{-- Timeline Dot --}}
            <div class="ht-dot {{ $apt->status }}"></div>

            <div class="ht-card">
                {{-- Date Row --}}
                <div class="ht-date">
                    <span>
                        <i class="fas fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($apt->appointment_date)->format('l, d F Y') }}
                    </span>
                    <span>
                        <i class="fas fa-clock me-1"></i>
                        {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}
                    </span>
                    <span style="color:#bbb">{{ $apt->appointment_number }}</span>
                </div>

                {{-- Main Body --}}
                <div class="ht-body">
                    <div class="ht-left">
                        <h6>
                            <i class="fas fa-map-marker-alt text-muted me-1"
                               style="font-size:.7rem"></i>
                            {{ $apt->location }}
                            <span class="badge bg-light text-muted ms-1"
                                  style="font-size:.6rem;font-weight:600">
                                {{ ucfirst($apt->workplace_type ?? '') }}
                            </span>
                        </h6>
                        @if(!empty($apt->reason))
                        <p>
                            <i class="fas fa-notes-medical text-primary me-1"
                               style="font-size:.65rem"></i>
                            {{ $apt->reason }}
                        </p>
                        @endif
                    </div>

                    <div class="ht-right">
                        {{-- Status --}}
                        <span class="sp {{ $apt->status }}">
                            @if($apt->status === 'pending')
                                <i class="fas fa-clock"></i>
                            @elseif($apt->status === 'confirmed')
                                <i class="fas fa-check-circle"></i>
                            @elseif($apt->status === 'completed')
                                <i class="fas fa-check-double"></i>
                            @elseif($apt->status === 'cancelled')
                                <i class="fas fa-times-circle"></i>
                            @else
                                <i class="fas fa-user-times"></i>
                            @endif
                            {{ ucfirst(str_replace('_',' ', $apt->status)) }}
                        </span>

                        {{-- Fee --}}
                        <span style="font-size:.76rem;font-weight:700;color:#198754">
                            LKR {{ number_format($apt->consultation_fee ?? 0, 2) }}
                        </span>

                        {{-- Payment Status --}}
                        @php
                            $pColors = ['unpaid'=>'warning','partial'=>'info','paid'=>'success'];
                            $pc = $pColors[$apt->payment_status ?? 'unpaid'] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $pc }}" style="font-size:.62rem">
                            {{ ucfirst($apt->payment_status ?? 'unpaid') }}
                        </span>

                        {{-- View Link --}}
                        <a href="{{ route('doctor.appointments.show', $apt->id) }}"
                           style="font-size:.71rem;color:#0d6efd;text-decoration:none">
                            View <i class="fas fa-arrow-right" style="font-size:.6rem"></i>
                        </a>
                    </div>
                </div>

                {{-- Notes --}}
                @if(!empty($apt->notes))
                <div class="ht-note">
                    <i class="fas fa-sticky-note me-1" style="color:#0d6efd"></i>
                    <strong>Notes:</strong> {{ $apt->notes }}
                </div>
                @endif

                {{-- Cancellation Reason --}}
                @if($apt->status === 'cancelled' && !empty($apt->cancellation_reason))
                <div class="ht-cancel">
                    <i class="fas fa-times-circle me-1"></i>
                    <strong>Cancel Reason:</strong> {{ $apt->cancellation_reason }}
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <p style="font-size:.85rem;font-weight:600;margin:0">No appointments found</p>
        <p style="font-size:.75rem;margin:.3rem 0 0">
            @if(request('status'))
                No <strong>{{ request('status') }}</strong> appointments with this patient
            @else
                No appointment history with this patient yet
            @endif
        </p>
    </div>
    @endif
</div>

@endsection
