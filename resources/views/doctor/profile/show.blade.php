@extends('doctor.layouts.master')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<style>
/* ══════════════════════════════════════
   DOCTOR PROFILE — show.blade.php
══════════════════════════════════════ */
.profile-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Hero Card ── */
.hero-card {
    background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%);
    border-radius: 20px;
    padding: 2rem 2rem 1.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.hero-card::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.hero-inner {
    display: flex; align-items: flex-start;
    gap: 1.5rem; flex-wrap: wrap;
    position: relative; z-index: 1;
}

/* Avatar */
.hero-avatar {
    width: 96px; height: 96px; border-radius: 22px;
    background: rgba(255,255,255,.2);
    border: 3px solid rgba(255,255,255,.4);
    overflow: hidden; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: rgba(255,255,255,.8);
    position: relative;
}
.hero-avatar img { width:100%; height:100%; object-fit:cover; }
.avatar-status {
    position: absolute; bottom: 4px; right: 4px;
    width: 14px; height: 14px; border-radius: 50%;
    border: 2px solid #fff;
}
.status-approved  { background: #22c55e; }
.status-pending   { background: #f59e0b; }
.status-rejected  { background: #ef4444; }
.status-suspended { background: #94a3b8; }

/* Hero Info */
.hero-name  { font-size: 1.3rem; font-weight: 800; line-height: 1.2; }
.hero-slmc  { font-size: .78rem; opacity: .82; margin-top: .25rem; }
.hero-spec  { font-size: .82rem; opacity: .9; margin-top: .2rem; }
.hero-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .7rem; border-radius: 20px;
    font-size: .7rem; font-weight: 700;
    margin-top: .5rem;
    backdrop-filter: blur(6px);
}
.badge-approved  { background: rgba(34,197,94,.25);  color: #d1fae5; border:1px solid rgba(34,197,94,.4); }
.badge-pending   { background: rgba(245,158,11,.25); color: #fef3c7; border:1px solid rgba(245,158,11,.4); }
.badge-rejected  { background: rgba(239,68,68,.25);  color: #fee2e2; border:1px solid rgba(239,68,68,.4); }
.badge-suspended { background: rgba(148,163,184,.25);color: #f1f5f9; border:1px solid rgba(148,163,184,.4); }

/* Hero Actions */
.hero-actions {
    margin-left: auto; display: flex; gap: .5rem;
    flex-wrap: wrap; align-items: flex-start;
}

/* Rating stars */
.star-row { display: flex; align-items: center; gap: .3rem; margin-top: .4rem; }
.star { color: #fbbf24; font-size: .85rem; }
.star.empty { color: rgba(255,255,255,.3); }
.star-val { font-size: .8rem; opacity: .85; }

/* ── Stat Cards ── */
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1rem 1.2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; align-items: center; gap: .9rem;
    height: 100%;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.stat-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-num { font-size: 1.35rem; font-weight: 800; line-height: 1; }
.stat-lbl {
    font-size: .68rem; color: #94a3b8; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; margin-top: .18rem;
}

/* ── Info Card ── */
.info-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem;
    margin-bottom: 1.2rem;

}
.info-card-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.info-card-title i { color: #0d6efd; }

/* Info Row */
.info-row {
    display: flex; align-items: flex-start;
    gap: .6rem; padding: .6rem 0;
    border-bottom: 1px solid #f8f9fb;
    font-size: .8rem;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-ico {
    width: 30px; height: 30px; border-radius: 8px;
    background: #f0f5ff;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; color: #0d6efd; flex-shrink: 0;
}
.info-lbl  { font-size: .68rem; color: #94a3b8; font-weight: 600; }
.info-val  { font-size: .82rem; color: #1a1a1a; font-weight: 600; margin-top: .08rem; }
.info-val.muted { color: #94a3b8; font-weight: 400; font-style: italic; }

/* ── Bio / Qualifications ── */
.bio-text {
    font-size: .8rem; color: #555; line-height: 1.7;
    background: #f8f9fb; border-radius: 10px;
    padding: .9rem 1rem; margin-top: .3rem;
}
.bio-text.empty {
    color: #94a3b8; font-style: italic;
    text-align: center; padding: 1.5rem;
}

/* ── Document Card ── */
.doc-block {
    display: flex; align-items: center; gap: .9rem;
    background: #f8f9fb;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: .9rem 1rem;
}
.doc-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #0d6efd; flex-shrink: 0;
}
.doc-name  { font-size: .82rem; font-weight: 600; color: #1a1a1a; }
.doc-sub   { font-size: .7rem; color: #94a3b8; margin-top: .1rem; }
.doc-empty {
    text-align: center; padding: 1.5rem;
    color: #94a3b8; font-size: .78rem; font-style: italic;
}

/* ── Account Info ── */
.account-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .65rem 0; border-bottom: 1px solid #f0f3f8;
    font-size: .8rem; flex-wrap: wrap; gap: .3rem;
}
.account-row:last-child { border-bottom: none; padding-bottom: 0; }
.account-lbl { color: #94a3b8; font-size: .72rem; font-weight: 600; text-transform: uppercase; }
.account-val { font-weight: 600; color: #1a1a1a; }

/* ── Danger Zone ── */
.danger-zone {
    background: #fff5f5;
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    padding: 1rem 1.2rem;
}
.danger-title {
    font-size: .8rem; font-weight: 700; color: #dc2626;
    margin-bottom: .75rem;
    display: flex; align-items: center; gap: .4rem;
}

/* ── Password Modal ── */
.pwd-strength {
    height: 4px; border-radius: 2px;
    background: #e2e8f0; margin-top: .4rem; overflow: hidden;
}
.pwd-strength-bar {
    height: 100%; border-radius: 2px;
    transition: width .3s, background .3s;
    width: 0%;
}

/* ── Document Upload Modal ── */
.upload-zone {
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    padding: 2rem 1rem; text-align: center;
    cursor: pointer; transition: all .2s;
}
.upload-zone:hover { border-color: #0d6efd; background: #f8faff; }
.upload-zone.dragover { border-color: #0d6efd; background: #f0f5ff; }

@media (max-width: 768px) {
    .hero-inner { flex-direction: column; }
    .hero-actions { margin-left: 0; }
    .hero-avatar { width: 72px; height: 72px; font-size: 1.8rem; }
    .hero-name { font-size: 1.1rem; }
}
</style>
@endpush

@section('content')
<div class="profile-wrap">

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══════════════════════════════════════
         HERO CARD
    ══════════════════════════════════════ --}}
    <div class="hero-card">
        <div class="hero-inner">

            {{-- Avatar --}}
            <div class="hero-avatar">
                @if($doctor->profile_image)
                    <img src="{{ asset('storage/'.$doctor->profile_image) }}"
                         alt="{{ $doctor->first_name }}"
                         onerror="this.parentElement.innerHTML=
                             '<i class=\'fas fa-user-md\'></i>'">
                @else
                    <i class="fas fa-user-md"></i>
                @endif
                <span class="avatar-status status-{{ $doctor->status }}"></span>
            </div>

            {{-- Info --}}
            <div>
                <div class="hero-name">
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                </div>
                <div class="hero-slmc">
                    <i class="fas fa-id-card me-1"></i>
                    SLMC Reg. No: {{ $doctor->slmc_number }}
                </div>
                @if($doctor->specialization)
                <div class="hero-spec">
                    <i class="fas fa-stethoscope me-1"></i>
                    {{ $doctor->specialization }}
                    @if($doctor->experience_years)
                        &nbsp;·&nbsp; {{ $doctor->experience_years }} yrs exp.
                    @endif
                </div>
                @endif

                {{-- Star Rating --}}
                @if($doctor->total_ratings > 0)
                <div class="star-row">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star star {{ $i <= round($doctor->rating) ? '' : 'empty' }}"></i>
                    @endfor
                    <span class="star-val">
                        {{ number_format($doctor->rating, 1) }}
                        ({{ $doctor->total_ratings }} ratings)
                    </span>
                </div>
                @endif

                <div>
                    <span class="hero-badge badge-{{ $doctor->status }}">
                        <i class="fas fa-{{
                            $doctor->status === 'approved'  ? 'check-circle'  :
                            ($doctor->status === 'pending'  ? 'clock'         :
                            ($doctor->status === 'rejected' ? 'times-circle'  : 'pause-circle'))
                        }}"></i>
                        {{ ucfirst($doctor->status) }}
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="hero-actions">
                <a href="{{ route('doctor.profile.edit') }}"
                   class="btn btn-sm"
                   style="background:rgba(255,255,255,.2);color:#fff;
                          border:1.5px solid rgba(255,255,255,.35)">
                    <i class="fas fa-edit me-1"></i>Edit Profile
                </a>
                <button type="button"
                        class="btn btn-sm"
                        style="background:rgba(255,255,255,.2);color:#fff;
                               border:1.5px solid rgba(255,255,255,.35)"
                        data-bs-toggle="modal"
                        data-bs-target="#passwordModal">
                    <i class="fas fa-lock me-1"></i>Change Password
                </button>
            </div>

        </div>

        {{-- Approved at strip --}}
        @if($doctor->approved_at)
        <div style="margin-top:1rem;padding-top:.75rem;
                    border-top:1px solid rgba(255,255,255,.15);
                    font-size:.73rem;opacity:.75;
                    position:relative;z-index:1">
            <i class="fas fa-check-double me-1"></i>
            Account approved on
            {{ \Carbon\Carbon::parse($doctor->approved_at)->format('d M Y') }}
        </div>
        @endif
    </div>

    {{-- ══ Stats Row ══ --}}
    <div class="row g-3 mb-3">
        @foreach([
            ['Total Appt.',    $totalAppointments,     '#0d6efd', 'fa-calendar-check',  'linear-gradient(135deg,#0d6efd22,#0d6efd55)'],
            ['Completed',      $completedAppointments, '#198754', 'fa-check-circle',    'linear-gradient(135deg,#19875422,#19875455)'],
            ['Pending',        $pendingAppointments,   '#fd7e14', 'fa-clock',           'linear-gradient(135deg,#fd7e1422,#fd7e1455)'],
            ['Workplaces',     $totalWorkplaces,       '#6f42c1', 'fa-hospital-alt',    'linear-gradient(135deg,#6f42c122,#6f42c155)'],
            ['Active Sched.',  $totalSchedules,        '#0dcaf0', 'fa-calendar-alt',    'linear-gradient(135deg,#0dcaf022,#0dcaf055)'],
            ['Rating',         number_format($doctor->rating,1).' ★', '#ffc107', 'fa-star', 'linear-gradient(135deg,#ffc10722,#ffc10755)'],
        ] as [$lbl, $val, $clr, $ico, $bg])
        <div class="col-6 col-sm-4 col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $bg }}">
                    <i class="fas {{ $ico }}" style="color:{{ $clr }}"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:{{ $clr }}">{{ $val }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ Main Content Row ══ --}}
    <div class="row g-3">

        {{-- ── LEFT COLUMN ── --}}
        <div class="col-lg-7">

            {{-- ── Personal Information ── --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="fas fa-user-md"></i>
                    Personal Information
                    <a href="{{ route('doctor.profile.edit') }}"
                       class="btn btn-outline-primary btn-sm ms-auto"
                       style="font-size:.7rem;padding:.2rem .6rem">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>

                <div class="row g-2">
                    @foreach([
                        ['fa-user',            'First Name',       $doctor->first_name],
                        ['fa-user',            'Last Name',        $doctor->last_name],
                        ['fa-stethoscope',     'Specialization',   $doctor->specialization],
                        ['fa-phone',           'Phone',            $doctor->phone],
                        ['fa-briefcase-medical','Experience',      $doctor->experience_years ? $doctor->experience_years.' years' : null],
                        ['fa-money-bill-wave', 'Consultation Fee', $doctor->consultation_fee ? 'Rs. '.number_format($doctor->consultation_fee,2) : null],
                    ] as [$ico, $lbl, $val])
                    <div class="col-sm-6">
                        <div class="info-row" style="border-bottom:none;
                             background:#f8f9fb;border-radius:10px;padding:.65rem .75rem">
                            <div class="info-ico">
                                <i class="fas {{ $ico }}"></i>
                            </div>
                            <div>
                                <div class="info-lbl">{{ $lbl }}</div>
                                <div class="info-val {{ !$val ? 'muted' : '' }}">
                                    {{ $val ?? 'Not provided' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Bio ── --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="fas fa-align-left"></i>
                    About / Bio
                </div>
                @if($doctor->bio)
                    <div class="bio-text">{{ $doctor->bio }}</div>
                @else
                    <div class="bio-text empty">
                        <i class="fas fa-pen-alt d-block mb-1" style="font-size:1.2rem"></i>
                        No bio added yet.
                        <a href="{{ route('doctor.profile.edit') }}">Add one now</a>
                    </div>
                @endif
            </div>

            {{-- ── Qualifications ── --}}
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-graduation-cap"></i>
                    Qualifications &amp; Training
                </div>
                @if($doctor->qualifications)
                    <div class="bio-text" style="white-space:pre-line">{{ $doctor->qualifications }}</div>
                @else
                    <div class="bio-text empty">
                        <i class="fas fa-graduation-cap d-block mb-1" style="font-size:1.2rem"></i>
                        No qualifications added yet.
                        <a href="{{ route('doctor.profile.edit') }}">Add now</a>
                    </div>
                @endif
            </div>

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="col-lg-5">

            {{-- ── Account Info ── --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Account Details
                </div>

                <div class="account-row">
                    <span class="account-lbl">Email</span>
                    <span class="account-val">{{ $user->email }}</span>
                </div>
                <div class="account-row">
                    <span class="account-lbl">User Type</span>
                    <span class="account-val">
                        <span class="badge bg-primary" style="font-size:.68rem">
                            Doctor
                        </span>
                    </span>
                </div>
                <div class="account-row">
                    <span class="account-lbl">Account Status</span>
                    <span class="account-val">
                        <span class="badge bg-{{
                            $user->status === 'active'    ? 'success' :
                            ($user->status === 'pending'  ? 'warning' :
                            ($user->status === 'rejected' ? 'danger'  : 'secondary'))
                        }}" style="font-size:.68rem">
                            {{ ucfirst($user->status) }}
                        </span>
                    </span>
                </div>
                <div class="account-row">
                    <span class="account-lbl">Email Verified</span>
                    <span class="account-val">
                        @if($user->email_verified_at)
                            <span class="text-success" style="font-size:.78rem">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-warning" style="font-size:.78rem">
                                <i class="fas fa-exclamation-circle me-1"></i>Not verified
                            </span>
                        @endif
                    </span>
                </div>
                <div class="account-row">
                    <span class="account-lbl">Member Since</span>
                    <span class="account-val" style="font-size:.78rem">
                        {{ \Carbon\Carbon::parse($doctor->created_at)->format('d M Y') }}
                    </span>
                </div>
                @if($doctor->approved_at)
                <div class="account-row">
                    <span class="account-lbl">Approved On</span>
                    <span class="account-val" style="font-size:.78rem">
                        {{ \Carbon\Carbon::parse($doctor->approved_at)->format('d M Y') }}
                    </span>
                </div>
                @endif
            </div>

            {{-- ── SLMC Document ── --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="fas fa-file-medical"></i>
                    Verification Document
                    <button type="button"
                            class="btn btn-outline-primary btn-sm ms-auto"
                            style="font-size:.7rem;padding:.2rem .6rem"
                            data-bs-toggle="modal"
                            data-bs-target="#documentModal">
                        <i class="fas fa-upload me-1"></i>Update
                    </button>
                </div>

                @if($doctor->document_path)
                <div class="doc-block">
                    <div class="doc-icon">
                        @php
                            $ext = strtolower(pathinfo($doctor->document_path, PATHINFO_EXTENSION));
                        @endphp
                        <i class="fas {{ $ext === 'pdf' ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="doc-name">
                            SLMC Verification Document
                        </div>
                        <div class="doc-sub">
                            {{ strtoupper($ext) }} file
                            &nbsp;·&nbsp;
                            Uploaded {{ \Carbon\Carbon::parse($doctor->updated_at)->format('d M Y') }}
                        </div>
                    </div>
                    <a href="{{ asset('storage/'.$doctor->document_path) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary"
                       style="font-size:.7rem">
                        <i class="fas fa-eye me-1"></i>View
                    </a>
                </div>
                @else
                <div class="doc-empty">
                    <i class="fas fa-file-upload d-block mb-1" style="font-size:1.5rem;color:#c0c8d4"></i>
                    No document uploaded yet.
                </div>
                @endif
            </div>

            {{-- ── Quick Links ── --}}
            <div class="info-card mb-3">
                <div class="info-card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('doctor.workplaces.index') }}"
                       class="btn btn-outline-primary btn-sm text-start">
                        <i class="fas fa-hospital-alt me-2"></i>
                        Manage Workplaces
                        <span class="badge bg-primary ms-1" style="font-size:.65rem">
                            {{ $totalWorkplaces }}
                        </span>
                    </a>
                    @if(\Illuminate\Support\Facades\Route::has('doctor.schedules.index'))
                    <a href="{{ route('doctor.schedules.index') }}"
                       class="btn btn-outline-success btn-sm text-start">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Manage Schedules
                        <span class="badge bg-success ms-1" style="font-size:.65rem">
                            {{ $totalSchedules }}
                        </span>
                    </a>
                    @endif
                    @if(\Illuminate\Support\Facades\Route::has('doctor.appointments.index'))
                    <a href="{{ route('doctor.appointments.index') }}"
                       class="btn btn-outline-info btn-sm text-start">
                        <i class="fas fa-calendar-check me-2"></i>
                        View Appointments
                        <span class="badge bg-info ms-1" style="font-size:.65rem">
                            {{ $totalAppointments }}
                        </span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- ── Danger Zone ── --}}
            <div class="danger-zone">
                <div class="danger-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </div>
                <div class="d-flex flex-column gap-2">
                    {{-- Remove Profile Image --}}
                    @if($doctor->profile_image)
                    <form action="{{ route('doctor.profile.image.delete') }}"
                          method="POST"
                          id="deleteImageForm">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                class="btn btn-outline-danger btn-sm w-100 text-start"
                                onclick="confirmDeleteImage()">
                            <i class="fas fa-user-times me-2"></i>
                            Remove Profile Image
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>{{-- /row --}}

</div>{{-- /.profile-wrap --}}


{{-- ══════════════════════════════════════
     CHANGE PASSWORD MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
             style="border-radius:18px;border:none;
                    box-shadow:0 20px 60px rgba(0,0,0,.15)">

            <div class="modal-header"
                 style="border-radius:18px 18px 0 0;
                        background:linear-gradient(135deg,#0d6efd,#6f42c1);
                        color:#fff;border:none;padding:1.1rem 1.4rem">
                <h6 class="modal-title mb-0 fw-bold">
                    <i class="fas fa-lock me-2"></i>Change Password
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('doctor.profile.password') }}"
                      method="POST" id="passwordForm">
                    @csrf

                    {{-- Current Password --}}
                    <div class="mb-3">
                        <label class="form-label"
                               style="font-size:.78rem;font-weight:700">
                            Current Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   name="current_password"
                                   id="currentPassword"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Enter current password">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="togglePwd('currentPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                        <div class="text-danger mt-1" style="font-size:.73rem">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label class="form-label"
                               style="font-size:.78rem;font-weight:700">
                            New Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   name="password"
                                   id="newPassword"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 characters"
                                   oninput="checkStrength(this.value)">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="togglePwd('newPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="pwd-strength mt-1">
                            <div class="pwd-strength-bar" id="strengthBar"></div>
                        </div>
                        <div style="font-size:.68rem;color:#94a3b8;margin-top:.2rem"
                             id="strengthLabel"></div>
                        @error('password')
                        <div class="text-danger mt-1" style="font-size:.73rem">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label class="form-label"
                               style="font-size:.78rem;font-weight:700">
                            Confirm New Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   name="password_confirmation"
                                   id="confirmPassword"
                                   class="form-control"
                                   placeholder="Repeat new password">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    onclick="togglePwd('confirmPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     DOCUMENT UPLOAD MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
             style="border-radius:18px;border:none;
                    box-shadow:0 20px 60px rgba(0,0,0,.15)">

            <div class="modal-header"
                 style="border-radius:18px 18px 0 0;
                        background:linear-gradient(135deg,#198754,#0d6efd);
                        color:#fff;border:none;padding:1.1rem 1.4rem">
                <h6 class="modal-title mb-0 fw-bold">
                    <i class="fas fa-file-upload me-2"></i>Update Verification Document
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('doctor.profile.document') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="documentForm">
                    @csrf

                    <div class="upload-zone" id="uploadZone"
                         onclick="document.getElementById('docFile').click()">
                        <i class="fas fa-cloud-upload-alt"
                           style="font-size:2rem;color:#0d6efd;
                                  display:block;margin-bottom:.5rem"></i>
                        <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">
                            Click or drag &amp; drop to upload
                        </div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem">
                            Supported: PDF, JPEG, PNG (max 5 MB)
                        </div>
                        <div id="fileNameDisplay"
                             style="font-size:.75rem;color:#0d6efd;
                                    margin-top:.6rem;font-weight:600;
                                    display:none"></div>
                    </div>

                    <input type="file"
                           name="document"
                           id="docFile"
                           accept=".pdf,.jpg,.jpeg,.png"
                           style="display:none"
                           onchange="showFileName(this)">

                    @error('document')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror

                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-upload me-1"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══ Auto-open password modal on validation error ══ --}}
@if($errors->has('current_password') || $errors->has('password'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('passwordModal')).show();
    });
</script>
@endif
@endsection

@push('scripts')
<script>
// ── Toggle Password Visibility ──────────────────────
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type    = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type    = 'password';
        icon.className = 'fas fa-eye';
    }
}

// ── Password Strength ────────────────────────────────
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!bar) return;

    let score = 0;
    if (val.length >= 8)                   score++;
    if (/[A-Z]/.test(val))                 score++;
    if (/[0-9]/.test(val))                 score++;
    if (/[^A-Za-z0-9]/.test(val))          score++;

    const levels = [
        { w: '25%',  bg: '#ef4444', lbl: 'Weak'      },
        { w: '50%',  bg: '#f59e0b', lbl: 'Fair'      },
        { w: '75%',  bg: '#3b82f6', lbl: 'Good'      },
        { w: '100%', bg: '#22c55e', lbl: 'Strong ✓'  },
    ];
    const lvl = levels[Math.max(0, score - 1)] || levels[0];

    bar.style.width      = val.length ? lvl.w  : '0%';
    bar.style.background = val.length ? lvl.bg : '';
    label.textContent    = val.length ? lvl.lbl : '';
}

// ── Document Upload — Show filename ─────────────────
function showFileName(input) {
    const display = document.getElementById('fileNameDisplay');
    if (input.files && input.files[0]) {
        display.textContent    = '📎 ' + input.files[0].name;
        display.style.display  = 'block';
    }
}

// ── Drag & Drop ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const zone = document.getElementById('uploadZone');
    if (!zone) return;

    ['dragenter','dragover'].forEach(e => {
        zone.addEventListener(e, function (ev) {
            ev.preventDefault();
            zone.classList.add('dragover');
        });
    });
    ['dragleave','drop'].forEach(e => {
        zone.addEventListener(e, function (ev) {
            ev.preventDefault();
            zone.classList.remove('dragover');
        });
    });
    zone.addEventListener('drop', function (ev) {
        ev.preventDefault();
        const file  = ev.dataTransfer.files[0];
        const input = document.getElementById('docFile');
        if (file && input) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showFileName(input);
        }
    });
});

// ── Confirm Delete Profile Image ─────────────────────
function confirmDeleteImage() {
    if (confirm('Are you sure you want to remove your profile image?')) {
        document.getElementById('deleteImageForm').submit();
    }
}
</script>
@endpush
