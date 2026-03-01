@extends('doctor.layouts.master')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@push('styles')
<style>
/* ══════════════════════════════════════
   DOCTOR PROFILE — edit.blade.php
══════════════════════════════════════ */
.edit-wrap { max-width: 900px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #0d6efd, #6f42c1);
    border-radius: 16px; padding: 1.4rem 1.5rem;
    color: #fff; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.ph-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .82; margin-top: .18rem; }

/* ── Form Card ── */
.form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem;
    margin-bottom: 1.2rem;
}
.form-sec-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.form-sec-title i { color: #0d6efd; }

/* ── Avatar Upload ── */
.avatar-upload-wrap {
    display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
}
.avatar-preview {
    width: 90px; height: 90px; border-radius: 20px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: #0d6efd;
    overflow: hidden; flex-shrink: 0;
    border: 3px solid #e8f0fe;
    position: relative; cursor: pointer;
}
.avatar-preview img  { width:100%; height:100%; object-fit:cover; }
.avatar-overlay {
    position: absolute; inset: 0;
    background: rgba(13,110,253,.55);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s; border-radius: 17px;
}
.avatar-preview:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { color:#fff; font-size:1.2rem; }

.avatar-info { flex:1; min-width:200px; }
.avatar-info-title { font-size:.82rem; font-weight:700; color:#1a1a1a; }
.avatar-info-sub   {
    font-size:.72rem; color:#94a3b8;
    margin-top:.2rem; line-height:1.5;
}
.avatar-btns { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.6rem; }

/* ── Form Labels ── */
.form-label { font-size:.78rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
.form-label .req { color:#dc3545; margin-left:.15rem; }

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
.readonly-notice i { color:#94a3b8; flex-shrink:0; margin-top:.1rem; }

/* ── Input Group Prefix ── */
.ig-prefix {
    background: #f0f5ff; border-color: #e2e8f0;
    font-size: .78rem; font-weight: 700; color: #0d6efd;
}

/* ── Char Counter ── */
.char-counter {
    font-size:.68rem; color:#94a3b8; text-align:right; margin-top:.2rem;
}
.char-counter.warn  { color:#f59e0b; }
.char-counter.limit { color:#dc3545; font-weight:700; }

/* ── Spec Preview Badge ── */
.spec-preview {
    display: inline-flex; align-items: center; gap: .25rem;
    background: #e8f0fe; color: #1a3fa8;
    padding: .2rem .6rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600; margin-top: .4rem;
}

/* ── Document Block ── */
.doc-current {
    display: flex; align-items: center; gap: .85rem;
    background: #f8f9fb; border: 1.5px solid #e2e8f0;
    border-radius: 12px; padding: .85rem 1rem; margin-bottom: .85rem;
}
.doc-ico {
    width: 42px; height: 42px; border-radius: 10px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #0d6efd; flex-shrink: 0;
}

/* ── Upload Drop Zone ── */
.upload-zone {
    border: 2px dashed #e2e8f0; border-radius: 12px;
    padding: 1.8rem 1rem; text-align: center;
    cursor: pointer; transition: all .2s;
}
.upload-zone:hover { border-color: #0d6efd; background: #f8faff; }
.upload-zone.dragover { border-color: #0d6efd; background: #f0f5ff; }

/* ── Password Strength ── */
.pwd-strength {
    height: 4px; border-radius: 2px;
    background: #e2e8f0; margin-top: .4rem; overflow: hidden;
}
.pwd-strength-bar {
    height: 100%; border-radius: 2px;
    transition: width .3s, background .3s; width: 0%;
}

@media (max-width:576px) {
    .avatar-upload-wrap { flex-direction:column; align-items:flex-start; }
}
</style>
@endpush

@section('content')
<div class="edit-wrap">

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon"><i class="fas fa-user-edit"></i></div>
        <div>
            <div class="ph-title">Edit Profile</div>
            <div class="ph-sub">
                Update your personal info, professional details and account security.
            </div>
        </div>
        <a href="{{ route('doctor.profile.show') }}"
           class="btn btn-sm ms-auto"
           style="background:rgba(255,255,255,.2);color:#fff;
                  border:1.5px solid rgba(255,255,255,.35)">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

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

    {{-- ══════════════════════════════════════════════════════════
         MAIN PROFILE FORM
         route: PUT  doctor.profile.update
    ══════════════════════════════════════════════════════════ --}}
    <form action="{{ route('doctor.profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          id="profileForm">
        @csrf
        @method('PUT')

        {{-- ─────────────────────────────────────────
             SECTION 1 — PROFILE IMAGE
        ───────────────────────────────────────── --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-camera"></i>Profile Image
            </div>

            <div class="avatar-upload-wrap">

                {{-- Live preview --}}
                <div class="avatar-preview"
                     id="avatarPreview"
                     onclick="document.getElementById('profileImageInput').click()"
                     title="Click to change photo">
                    @if($doctor->profile_image)
                        <img src="{{ asset('storage/'.$doctor->profile_image) }}"
                             id="avatarImg"
                             alt="{{ $doctor->first_name }}"
                             onerror="this.parentElement.innerHTML=
                                 '<i class=\'fas fa-user-md\'></i>'">
                    @else
                        <i class="fas fa-user-md"></i>
                    @endif
                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>

                <div class="avatar-info">
                    <div class="avatar-info-title">
                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </div>
                    <div class="avatar-info-sub">
                        Upload a clear face photo.<br>
                        Accepted: JPEG, PNG, WebP — Max 2 MB
                    </div>
                    <div class="avatar-btns">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm"
                                onclick="document.getElementById('profileImageInput').click()">
                            <i class="fas fa-upload me-1"></i>Choose Photo
                        </button>
                        @if($doctor->profile_image)
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="confirmRemoveImage()">
                            <i class="fas fa-trash me-1"></i>Remove
                        </button>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Hidden file input --}}
            <input type="file"
                   name="profile_image"
                   id="profileImageInput"
                   accept="image/jpeg,image/png,image/jpg,image/webp"
                   style="display:none"
                   onchange="previewAvatar(this)">

            @error('profile_image')
            <div class="text-danger mt-2" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- ─────────────────────────────────────────
             SECTION 2 — PERSONAL INFORMATION
        ───────────────────────────────────────── --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-user-md"></i>Personal Information
            </div>

            <div class="row g-3">

                {{-- First Name --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        First Name <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="first_name"
                           class="form-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name', $doctor->first_name) }}"
                           placeholder="e.g. Nuwan"
                           maxlength="100"
                           required>
                    @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        Last Name <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="last_name"
                           class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $doctor->last_name) }}"
                           placeholder="e.g. Perera"
                           maxlength="100"
                           required>
                    @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SLMC — Read-only --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        <i class="fas fa-id-card me-1 text-muted"></i>
                        SLMC Registration No.
                    </label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $doctor->slmc_number }}"
                           readonly>
                    <div class="readonly-notice">
                        <i class="fas fa-lock"></i>
                        SLMC number cannot be changed. Contact admin if needed.
                    </div>
                </div>

                {{-- Email — Read-only --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        <i class="fas fa-envelope me-1 text-muted"></i>
                        Email Address
                    </label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $user->email }}"
                           readonly>
                    <div class="readonly-notice">
                        <i class="fas fa-lock"></i>
                        Email is managed by admin. Contact admin to change.
                    </div>
                </div>

                {{-- Phone --}}
                <div class="col-sm-6">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text ig-prefix">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $doctor->phone) }}"
                               placeholder="e.g. 0771234567"
                               maxlength="20">
                    </div>
                    @error('phone')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Experience --}}
                <div class="col-sm-6">
                    <label class="form-label">Years of Experience</label>
                    <div class="input-group">
                        <span class="input-group-text ig-prefix">
                            <i class="fas fa-briefcase-medical"></i>
                        </span>
                        <input type="number"
                               name="experience_years"
                               class="form-control @error('experience_years') is-invalid @enderror"
                               value="{{ old('experience_years', $doctor->experience_years) }}"
                               placeholder="e.g. 5"
                               min="0" max="60">
                        <span class="input-group-text ig-prefix">yrs</span>
                    </div>
                    @error('experience_years')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ─────────────────────────────────────────
             SECTION 3 — PROFESSIONAL DETAILS
        ───────────────────────────────────────── --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-stethoscope"></i>Professional Details
            </div>

            <div class="row g-3">

                {{-- Specialization --}}
                <div class="col-sm-8">
                    <label class="form-label">Specialization</label>
                    <input type="text"
                           name="specialization"
                           id="specInput"
                           class="form-control @error('specialization') is-invalid @enderror"
                           value="{{ old('specialization', $doctor->specialization) }}"
                           placeholder="e.g. Cardiologist, General Practitioner"
                           maxlength="100"
                           oninput="updateSpecPreview(this.value)">
                    {{-- Live badge preview --}}
                    <div id="specPreview" style="min-height:28px;margin-top:.35rem">
                        @if(old('specialization', $doctor->specialization))
                        <span class="spec-preview">
                            <i class="fas fa-stethoscope"></i>
                            {{ old('specialization', $doctor->specialization) }}
                        </span>
                        @endif
                    </div>
                    @error('specialization')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Consultation Fee --}}
                <div class="col-sm-4">
                    <label class="form-label">Consultation Fee</label>
                    <div class="input-group">
                        <span class="input-group-text ig-prefix">Rs.</span>
                        <input type="number"
                               name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                               placeholder="e.g. 2000"
                               min="0" max="99999999" step="0.01">
                    </div>
                    @error('consultation_fee')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Qualifications --}}
                <div class="col-12">
                    <label class="form-label">Qualifications &amp; Training</label>
                    <textarea name="qualifications"
                              id="qualInput"
                              class="form-control @error('qualifications') is-invalid @enderror"
                              rows="4"
                              placeholder="e.g. MBBS (Colombo)&#10;MD — Cardiology&#10;MRCP (UK)"
                              maxlength="1000"
                              oninput="countChars(this,'qualCount',1000)">{{ old('qualifications', $doctor->qualifications) }}</textarea>
                    <div class="char-counter" id="qualCount">
                        {{ strlen(old('qualifications', $doctor->qualifications ?? '')) }} / 1000
                    </div>
                    @error('qualifications')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ─────────────────────────────────────────
             SECTION 4 — BIO
        ───────────────────────────────────────── --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-align-left"></i>About / Bio
                <span style="margin-left:auto;font-size:.68rem;
                             color:#94a3b8;font-weight:400">
                    Visible to patients
                </span>
            </div>

            <textarea name="bio"
                      id="bioInput"
                      class="form-control @error('bio') is-invalid @enderror"
                      rows="5"
                      placeholder="Write a short bio about yourself — your experience, approach to patient care, areas of expertise…"
                      maxlength="1000"
                      oninput="countChars(this,'bioCount',1000)">{{ old('bio', $doctor->bio) }}</textarea>
            <div class="char-counter" id="bioCount">
                {{ strlen(old('bio', $doctor->bio ?? '')) }} / 1000
            </div>
            @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ══ Profile Form Submit ══ --}}
        <div class="d-flex justify-content-between align-items-center
                    flex-wrap gap-2 mb-4">
            <a href="{{ route('doctor.profile.show') }}"
               class="btn btn-secondary btn-sm">
                <i class="fas fa-times me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Save Profile Changes
            </button>
        </div>

    </form>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 5 — VERIFICATION DOCUMENT
         route: POST  doctor.profile.document
    ══════════════════════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-sec-title">
            <i class="fas fa-file-medical"></i>
            Verification Document (SLMC)
        </div>

        {{-- Current document --}}
        @if($doctor->document_path)
        @php $ext = strtolower(pathinfo($doctor->document_path, PATHINFO_EXTENSION)); @endphp
        <div class="doc-current">
            <div class="doc-ico">
                <i class="fas {{ $ext === 'pdf' ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
            </div>
            <div class="flex-grow-1">
                <div style="font-size:.82rem;font-weight:700;color:#1a1a1a">
                    Current Document
                </div>
                <div style="font-size:.7rem;color:#94a3b8;margin-top:.1rem">
                    {{ strtoupper($ext) }} &nbsp;·&nbsp;
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
        @endif

        <form action="{{ route('doctor.profile.document') }}"
              method="POST"
              enctype="multipart/form-data"
              id="documentForm">
            @csrf

            <div class="upload-zone" id="uploadZone"
                 onclick="document.getElementById('docFileInput').click()">
                <i class="fas fa-cloud-upload-alt"
                   style="font-size:2rem;color:#0d6efd;
                          display:block;margin-bottom:.5rem"></i>
                <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">
                    Click or drag &amp; drop to upload
                </div>
                <div style="font-size:.72rem;color:#94a3b8;margin-top:.25rem">
                    Accepted: PDF, JPEG, PNG — Max 5 MB
                </div>
                <div id="docFileName"
                     style="font-size:.75rem;color:#0d6efd;font-weight:600;
                            margin-top:.6rem;display:none"></div>
            </div>

            <input type="file"
                   name="document"
                   id="docFileInput"
                   accept=".pdf,.jpg,.jpeg,.png"
                   style="display:none"
                   onchange="showDocName(this)">

            @error('document')
            <div class="text-danger mt-1" style="font-size:.73rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-upload me-1"></i>Upload Document
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 6 — CHANGE PASSWORD
         route: POST  doctor.profile.password
    ══════════════════════════════════════════════════════════ --}}
    <div class="form-card">
        <div class="form-sec-title">
            <i class="fas fa-lock"></i>Change Password
        </div>

        <form action="{{ route('doctor.profile.password') }}"
              method="POST"
              id="passwordForm">
            @csrf

            <div class="row g-3">

                {{-- Current Password --}}
                <div class="col-12">
                    <label class="form-label">
                        Current Password <span class="req">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="current_password"
                               id="currentPwd"
                               class="form-control
                                      @error('current_password') is-invalid @enderror"
                               placeholder="Enter your current password">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePwd('currentPwd', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        New Password <span class="req">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               id="newPwd"
                               class="form-control
                                      @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters"
                               oninput="checkStrength(this.value)">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePwd('newPwd', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="pwd-strength">
                        <div class="pwd-strength-bar" id="strengthBar"></div>
                    </div>
                    <div style="font-size:.68rem;color:#94a3b8;margin-top:.18rem"
                         id="strengthLabel"></div>
                    @error('password')
                    <div class="text-danger mt-1" style="font-size:.73rem">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="col-sm-6">
                    <label class="form-label">
                        Confirm New Password <span class="req">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="password_confirmation"
                               id="confirmPwd"
                               class="form-control"
                               placeholder="Repeat new password"
                               oninput="checkMatch()">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePwd('confirmPwd', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div style="font-size:.68rem;margin-top:.18rem"
                         id="matchLabel"></div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-warning btn-sm">
                    <i class="fas fa-key me-1"></i>Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 7 — DANGER ZONE
         route: DELETE  doctor.profile.image.delete
    ══════════════════════════════════════════════════════════ --}}
    @if($doctor->profile_image)
    <div style="background:#fff5f5;border:1.5px solid #fecaca;
                border-radius:16px;padding:1.2rem 1.4rem;
                margin-bottom:1.2rem">
        <div style="font-size:.8rem;font-weight:700;color:#dc2626;
                    margin-bottom:.75rem;display:flex;align-items:center;gap:.4rem">
            <i class="fas fa-exclamation-triangle"></i>
            Danger Zone
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div style="font-size:.8rem;font-weight:600;color:#1a1a1a">
                    Remove Profile Image
                </div>
                <div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem">
                    Your profile picture will be permanently deleted.
                </div>
            </div>
            <form action="{{ route('doctor.profile.image.delete') }}"
                  method="POST"
                  id="removeImgForm">
                @csrf
                @method('DELETE')
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="confirmRemoveImage()">
                    <i class="fas fa-user-times me-1"></i>Remove Image
                </button>
            </form>
        </div>
    </div>
    @endif

</div>{{-- /.edit-wrap --}}

{{-- Auto open password section on error --}}
@if($errors->has('current_password') || $errors->has('password'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('passwordForm')
            .scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
</script>
@endif
@endsection

@push('scripts')
<script>
// ── Avatar Live Preview ───────────────────────────────
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    if (file.size > 2 * 1024 * 1024) {
        alert('Image must not exceed 2 MB.');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function (e) {
        const p = document.getElementById('avatarPreview');
        p.innerHTML = `
            <img src="${e.target.result}"
                 style="width:100%;height:100%;object-fit:cover">
            <div class="avatar-overlay">
                <i class="fas fa-camera"></i>
            </div>`;
    };
    reader.readAsDataURL(file);
}

// ── Remove Image Confirm ──────────────────────────────
function confirmRemoveImage() {
    if (confirm('Are you sure you want to remove your profile image?')) {
        document.getElementById('removeImgForm').submit();
    }
}

// ── Specialization Badge Preview ─────────────────────
function updateSpecPreview(val) {
    const div = document.getElementById('specPreview');
    if (val.trim()) {
        div.innerHTML = `
            <span class="spec-preview">
                <i class="fas fa-stethoscope"></i>
                ${escHtml(val.trim())}
            </span>`;
    } else {
        div.innerHTML = '';
    }
}

// ── Char Counter ─────────────────────────────────────
function countChars(el, id, max) {
    const len = el.value.length;
    const el2 = document.getElementById(id);
    if (!el2) return;
    el2.textContent = len + ' / ' + max;
    el2.className   = 'char-counter' +
        (len >= max       ? ' limit' :
        (len >= max * .85 ? ' warn'  : ''));
}

// ── Toggle Password Visibility ───────────────────────
function togglePwd(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type       = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type       = 'password';
        icon.className = 'fas fa-eye';
    }
}

// ── Password Strength ────────────────────────────────
function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    if (!bar) return;

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const levels = [
        { w:'25%',  bg:'#ef4444', lbl:'Weak'     },
        { w:'50%',  bg:'#f59e0b', lbl:'Fair'     },
        { w:'75%',  bg:'#3b82f6', lbl:'Good'     },
        { w:'100%', bg:'#22c55e', lbl:'Strong ✓' },
    ];
    const lvl = val.length ? levels[Math.max(0, score - 1)] : null;
    bar.style.width      = lvl ? lvl.w  : '0%';
    bar.style.background = lvl ? lvl.bg : '';
    label.textContent    = lvl ? lvl.lbl : '';
}

// ── Password Match Check ─────────────────────────────
function checkMatch() {
    const np  = document.getElementById('newPwd').value;
    const cp  = document.getElementById('confirmPwd').value;
    const lbl = document.getElementById('matchLabel');
    if (!cp) { lbl.textContent = ''; return; }
    if (np === cp) {
        lbl.innerHTML = '<span style="color:#22c55e"><i class="fas fa-check me-1"></i>Passwords match</span>';
    } else {
        lbl.innerHTML = '<span style="color:#ef4444"><i class="fas fa-times me-1"></i>Passwords do not match</span>';
    }
}

// ── Document upload — show filename ──────────────────
function showDocName(input) {
    const el = document.getElementById('docFileName');
    if (input.files && input.files[0]) {
        el.textContent   = '📎 ' + input.files[0].name;
        el.style.display = 'block';
    }
}

// ── Drag & Drop ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const zone = document.getElementById('uploadZone');
    if (!zone) return;
    ['dragenter','dragover'].forEach(ev => {
        zone.addEventListener(ev, e => {
            e.preventDefault(); zone.classList.add('dragover');
        });
    });
    ['dragleave','drop'].forEach(ev => {
        zone.addEventListener(ev, e => {
            e.preventDefault(); zone.classList.remove('dragover');
        });
    });
    zone.addEventListener('drop', function (e) {
        e.preventDefault();
        const file  = e.dataTransfer.files[0];
        const input = document.getElementById('docFileInput');
        if (file && input) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            showDocName(input);
        }
    });

    // Init char counters
    const bio  = document.getElementById('bioInput');
    const qual = document.getElementById('qualInput');
    if (bio)  countChars(bio,  'bioCount',  1000);
    if (qual) countChars(qual, 'qualCount', 1000);
});

// ── HTML Escape ───────────────────────────────────────
function escHtml(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
