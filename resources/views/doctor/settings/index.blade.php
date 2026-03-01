{{-- ══════════════════════════════════════════════════════
     resources/views/doctor/settings/index.blade.php
══════════════════════════════════════════════════════ --}}
@extends('doctor.layouts.master')

@section('title', 'Settings')
@section('page-title', 'Settings')

@push('styles')
<style>
/* ══════════════════════════════════════
   SETTINGS INDEX
══════════════════════════════════════ */
.settings-wrap { max-width: 820px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #374151, #1f2937);
    border-radius: 16px; padding: 1.3rem 1.5rem;
    color: #fff; margin-bottom: 1.4rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
}
.ph-icon {
    width: 50px; height: 50px; border-radius: 14px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .75; margin-top: .18rem; }

/* ── Settings Card ── */
.settings-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem; margin-bottom: 1.2rem;
}
.settings-sec-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: .4rem;
}
.settings-sec-title i { color: #0d6efd; }

/* ── Form Labels ── */
.form-label {
    font-size: .78rem; font-weight: 700;
    color: #374151; margin-bottom: .35rem;
}
.form-label .req { color: #dc3545; margin-left: .15rem; }

/* ── Focus ── */
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,.12);
}

/* ── Read-only ── */
.readonly-field {
    background: #f8f9fb !important;
    border-color: #e2e8f0 !important;
    color: #64748b !important;
    cursor: not-allowed;
}
.readonly-notice {
    background: #f8f9fb; border: 1.5px solid #e2e8f0;
    border-radius: 10px; padding: .55rem .85rem;
    font-size: .72rem; color: #64748b;
    display: flex; align-items: flex-start; gap: .4rem;
    margin-top: .45rem; line-height: 1.5;
}
.readonly-notice i { color: #94a3b8; flex-shrink: 0; margin-top: .1rem; }

/* ── Input Prefix ── */
.ig-prefix {
    background: #f0f5ff; border-color: #e2e8f0;
    font-size: .78rem; font-weight: 700; color: #0d6efd;
}

/* ── Info Row ── */
.info-row {
    display: flex; align-items: center;
    padding: .6rem 0; border-bottom: 1px solid #f8f9fb;
    font-size: .8rem; gap: .6rem;
}
.info-row:last-child { border-bottom: none; }
.info-ico {
    width: 32px; height: 32px; border-radius: 8px;
    background: #f0f5ff;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; color: #0d6efd; flex-shrink: 0;
}
.info-lbl { font-size: .68rem; color: #94a3b8; font-weight: 600; }
.info-val { font-size: .8rem; font-weight: 600; color: #1a1a1a; margin-top: .06rem; }
.info-val.muted { color: #94a3b8; font-style: italic; font-weight: 400; }

/* ── Status Badge ── */
.status-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .18rem .55rem; border-radius: 20px;
    font-size: .7rem; font-weight: 700;
}
.sb-approved  { background: #d1fae5; color: #065f46; }
.sb-pending   { background: #fef3c7; color: #92400e; }
.sb-rejected  { background: #fee2e2; color: #991b1b; }
.sb-suspended { background: #f1f5f9; color: #475569; }
.sb-active    { background: #d1fae5; color: #065f46; }

/* ── Danger Zone ── */
.danger-zone {
    background: #fff5f5; border: 1.5px solid #fecaca;
    border-radius: 14px; padding: 1.2rem 1.4rem;
}
.danger-title {
    font-size: .82rem; font-weight: 700; color: #dc2626;
    margin-bottom: .85rem;
    display: flex; align-items: center; gap: .4rem;
}

/* ── Save button ── */
.save-bar {
    background: #fff; border-radius: 14px;
    border: 1.5px solid #f0f3f8;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    padding: .9rem 1.2rem;
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: .75rem;
    margin-top: .5rem;
}
</style>
@endpush

@section('content')
<div class="settings-wrap">

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.8rem" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon"><i class="fas fa-cog"></i></div>
        <div>
            <div class="ph-title">Account Settings</div>
            <div class="ph-sub">
                Manage your account email, SLMC number and consultation fee.
            </div>
        </div>
    </div>

    <form action="{{ route('doctor.settings.update') }}"
          method="POST"
          id="settingsForm">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- ══ LEFT COLUMN ══ --}}
            <div class="col-lg-7">

                {{-- ─────────────────────────────────────────
                     SECTION 1 — ACCOUNT SETTINGS
                     (editable: email, slmc_number, consultation_fee)
                ───────────────────────────────────────── --}}
                <div class="settings-card">
                    <div class="settings-sec-title">
                        <i class="fas fa-user-shield"></i>
                        Account Settings
                    </div>

                    <div class="row g-3">

                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label">
                                Email Address <span class="req">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text ig-prefix">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                       name="email"
                                       class="form-control
                                              @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="your@email.com"
                                       required>
                            </div>
                            @error('email')
                            <div class="text-danger mt-1" style="font-size:.73rem">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            @if(!$user->email_verified_at)
                            <div class="readonly-notice" style="border-color:#fde68a;background:#fff7ed">
                                <i class="fas fa-exclamation-triangle" style="color:#d97706"></i>
                                <span style="color:#92400e">
                                    Email not verified. Please check your inbox or
                                    <a href="{{ route('doctor.notifications') }}"
                                       style="color:#d97706;font-weight:700">
                                        resend verification
                                    </a>.
                                </span>
                            </div>
                            @else
                            <div class="readonly-notice"
                                 style="border-color:#bbf7d0;background:#f0fdf4">
                                <i class="fas fa-check-circle" style="color:#16a34a"></i>
                                <span style="color:#15803d">
                                    Email verified on
                                    {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}.
                                </span>
                            </div>
                            @endif
                        </div>

                        {{-- SLMC Number --}}
                        <div class="col-sm-6">
                            <label class="form-label">
                                SLMC Registration No. <span class="req">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text ig-prefix">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text"
                                       name="slmc_number"
                                       class="form-control
                                              @error('slmc_number') is-invalid @enderror"
                                       value="{{ old('slmc_number', $doctor->slmc_number) }}"
                                       placeholder="SLMC number"
                                       maxlength="50"
                                       required>
                            </div>
                            @error('slmc_number')
                            <div class="text-danger mt-1" style="font-size:.73rem">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                            <div class="readonly-notice">
                                <i class="fas fa-info-circle"></i>
                                Changes to SLMC number will require re-verification by admin.
                            </div>
                        </div>

                        {{-- Consultation Fee --}}
                        <div class="col-sm-6">
                            <label class="form-label">Consultation Fee</label>
                            <div class="input-group">
                                <span class="input-group-text ig-prefix">Rs.</span>
                                <input type="number"
                                       name="consultation_fee"
                                       class="form-control
                                              @error('consultation_fee') is-invalid @enderror"
                                       value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                       placeholder="e.g. 2000"
                                       min="0" step="0.01">
                            </div>
                            @error('consultation_fee')
                            <div class="text-danger mt-1" style="font-size:.73rem">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ─────────────────────────────────────────
                     SECTION 2 — READ-ONLY DOCTOR INFO
                ───────────────────────────────────────── --}}
                <div class="settings-card">
                    <div class="settings-sec-title">
                        <i class="fas fa-user-md"></i>
                        Doctor Information
                        <span style="margin-left:auto;font-size:.68rem;
                                     color:#94a3b8;font-weight:400">
                            Read-only — edit from
                            <a href="{{ route('doctor.profile.edit') }}"
                               style="color:#0d6efd;font-weight:600">
                                Profile
                            </a>
                        </span>
                    </div>

                    <div class="row g-2">
                        @foreach([
                            ['fa-user',              'Full Name',        $doctor->first_name.' '.$doctor->last_name],
                            ['fa-stethoscope',       'Specialization',   $doctor->specialization],
                            ['fa-briefcase-medical', 'Experience',       $doctor->experience_years ? $doctor->experience_years.' years' : null],
                            ['fa-phone',             'Phone',            $doctor->phone],
                        ] as [$ico, $lbl, $val])
                        <div class="col-sm-6">
                            <div style="background:#f8f9fb;border-radius:10px;
                                        padding:.65rem .75rem;
                                        display:flex;align-items:center;gap:.55rem">
                                <div class="info-ico">
                                    <i class="fas {{ $ico }}"></i>
                                </div>
                                <div>
                                    <div class="info-lbl">{{ $lbl }}</div>
                                    <div class="info-val {{ !$val ? 'muted' : '' }}">
                                        {{ $val ?? 'Not set' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ══ Save Bar ══ --}}
                <div class="save-bar">
                    <div style="font-size:.75rem;color:#94a3b8">
                        <i class="fas fa-info-circle me-1"></i>
                        Changes to email or SLMC number may require re-verification.
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Settings
                    </button>
                </div>

            </div>

            {{-- ══ RIGHT COLUMN ══ --}}
            <div class="col-lg-5">

                {{-- ── Account Status Card ── --}}
                <div class="settings-card mb-3">
                    <div class="settings-sec-title">
                        <i class="fas fa-shield-alt"></i>
                        Account Status
                    </div>

                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-user-check"></i></div>
                        <div class="flex-grow-1">
                            <div class="info-lbl">Doctor Status</div>
                            <div class="info-val mt-1">
                                <span class="status-badge sb-{{ $doctor->status }}">
                                    <i class="fas fa-{{
                                        $doctor->status === 'approved'  ? 'check-circle'  :
                                        ($doctor->status === 'pending'  ? 'clock'         :
                                        ($doctor->status === 'rejected' ? 'times-circle'  : 'pause-circle'))
                                    }}"></i>
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-circle"></i></div>
                        <div class="flex-grow-1">
                            <div class="info-lbl">Account Status</div>
                            <div class="info-val mt-1">
                                <span class="status-badge sb-{{ $user->status }}">
                                    <i class="fas fa-{{
                                        $user->status === 'active'    ? 'check-circle'  :
                                        ($user->status === 'pending'  ? 'clock'         :
                                        ($user->status === 'rejected' ? 'times-circle'  : 'pause-circle'))
                                    }}"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-envelope-open-text"></i></div>
                        <div class="flex-grow-1">
                            <div class="info-lbl">Email Verified</div>
                            <div class="info-val mt-1">
                                @if($user->email_verified_at)
                                <span class="status-badge sb-approved">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                                @else
                                <span class="status-badge sb-pending">
                                    <i class="fas fa-clock"></i> Not Verified
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-star"></i></div>
                        <div class="flex-grow-1">
                            <div class="info-lbl">Rating</div>
                            <div class="info-val" style="color:#f59e0b">
                                ★ {{ number_format($doctor->rating, 1) }}
                                <span style="color:#94a3b8;font-size:.7rem;font-weight:400">
                                    ({{ $doctor->total_ratings }} reviews)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-calendar"></i></div>
                        <div>
                            <div class="info-lbl">Member Since</div>
                            <div class="info-val">
                                {{ \Carbon\Carbon::parse($doctor->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    @if($doctor->approved_at)
                    <div class="info-row">
                        <div class="info-ico"><i class="fas fa-check-double"></i></div>
                        <div>
                            <div class="info-lbl">Approved On</div>
                            <div class="info-val">
                                {{ \Carbon\Carbon::parse($doctor->approved_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ── Quick Links ── --}}
                <div class="settings-card mb-3">
                    <div class="settings-sec-title">
                        <i class="fas fa-bolt"></i>
                        Quick Links
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.profile.edit') }}"
                           class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Full Profile
                        </a>
                        @if(\Illuminate\Support\Facades\Route::has('doctor.notifications'))
                        <a href="{{ route('doctor.notifications') }}"
                           class="btn btn-outline-secondary btn-sm text-start">
                            <i class="fas fa-bell me-2"></i>
                            Notifications
                        </a>
                        @endif
                        @if(\Illuminate\Support\Facades\Route::has('doctor.reviews.index'))
                        <a href="{{ route('doctor.reviews.index') }}"
                           class="btn btn-outline-warning btn-sm text-start">
                            <i class="fas fa-star me-2"></i>
                            My Reviews
                            @if($doctor->total_ratings > 0)
                            <span class="badge bg-warning text-dark ms-1"
                                  style="font-size:.62rem">
                                {{ $doctor->total_ratings }}
                            </span>
                            @endif
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
                    <div class="d-flex align-items-start
                                justify-content-between flex-wrap gap-2">
                        <div>
                            <div style="font-size:.8rem;font-weight:600;color:#1a1a1a">
                                Deactivate Account
                            </div>
                            <div style="font-size:.72rem;color:#94a3b8;margin-top:.15rem;
                                        line-height:1.45">
                                Contact admin to suspend or delete your account.
                            </div>
                        </div>
                        <a href="mailto:admin@medibridge.lk?subject=Account Deactivation Request"
                           class="btn btn-outline-danger btn-sm"
                           style="font-size:.72rem;white-space:nowrap">
                            <i class="fas fa-envelope me-1"></i>Contact Admin
                        </a>
                    </div>
                </div>

            </div>
        </div>{{-- /row --}}

    </form>
</div>{{-- /.settings-wrap --}}
@endsection

@push('scripts')
<script>
// ── Unsaved changes warning ───────────────────────────
let formDirty = false;
const form    = document.getElementById('settingsForm');

if (form) {
    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', () => { formDirty = true; });
    });
    form.addEventListener('submit', () => { formDirty = false; });
}

window.addEventListener('beforeunload', function (e) {
    if (formDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush
