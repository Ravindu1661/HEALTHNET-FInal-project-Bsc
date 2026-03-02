{{-- resources/views/hospital/profile.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Hospital Profile')
@section('page-title', 'Hospital Profile')

@push('styles')
<style>
/* ══════════════════════════════════════════
   PAGE
══════════════════════════════════════════ */
.profile-page { animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }

/* ══════════════════════════════════════════
   PROFILE HERO CARD
══════════════════════════════════════════ */
.profile-hero {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 14px rgba(44,62,80,.06);
    overflow: hidden;
    margin-bottom: 1.3rem;
}
.hero-cover {
    height: 120px;
    background: linear-gradient(135deg, #1a3a6b 0%, #2969bf 50%, #5b9bd5 100%);
    position: relative;
}
.hero-cover-pattern {
    position: absolute; inset: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,.08) 0%, transparent 50%),
                      radial-gradient(circle at 80% 20%, rgba(255,255,255,.06) 0%, transparent 40%);
}
.hero-body {
    padding: 0 1.8rem 1.5rem;
    display: flex; align-items: flex-end; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
}
.hero-avatar-wrap {
    position: relative; margin-top: -46px; flex-shrink: 0;
}
.hero-avatar {
    width: 90px; height: 90px;
    border-radius: 18px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 16px rgba(41,105,191,.2);
    display: block;
}
.hero-avatar-fallback {
    width: 90px; height: 90px;
    border-radius: 18px;
    background: linear-gradient(135deg, #2969bf, #5b9bd5);
    border: 4px solid #fff;
    box-shadow: 0 4px 16px rgba(41,105,191,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 800; color: #fff;
}
.photo-upload-btn {
    position: absolute; bottom: -4px; right: -4px;
    width: 28px; height: 28px; border-radius: 8px;
    background: #2969bf; color: #fff; border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer;
    transition: background .2s, transform .2s;
    box-shadow: 0 2px 8px rgba(41,105,191,.3);
}
.photo-upload-btn:hover { background: #1a4f9a; transform: scale(1.1); }

.hero-info { flex: 1; padding-top: .6rem; min-width: 0; }
.hero-name {
    font-size: 1.15rem; font-weight: 800;
    color: #2c3e50; margin: 0 0 .25rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.hero-meta {
    display: flex; align-items: center; gap: .75rem;
    flex-wrap: wrap; margin-bottom: .4rem;
}
.hero-meta span {
    font-size: .75rem; color: #888;
    display: flex; align-items: center; gap: .3rem;
}
.hero-meta i { color: #2969bf; font-size: .7rem; }

.status-pill {
    font-size: .68rem; font-weight: 700;
    padding: .22rem .65rem; border-radius: 99px;
    display: inline-flex; align-items: center; gap: .3rem;
}
.status-approved  { background: #d1e7dd; color: #0f5132; }
.status-pending   { background: #fff3cd; color: #856404; }
.status-suspended { background: #f8d7da; color: #842029; }
.status-rejected  { background: #f0f0f0; color: #777; }

.hero-actions { display: flex; gap: .6rem; align-items: center; padding-top: .4rem; }

/* ══════════════════════════════════════════
   TAB NAVIGATION
══════════════════════════════════════════ */
.profile-tabs {
    display: flex; gap: 0;
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    padding: .35rem;
    margin-bottom: 1.3rem;
    overflow-x: auto;
    scrollbar-width: none;
}
.profile-tabs::-webkit-scrollbar { display: none; }
.tab-btn {
    display: flex; align-items: center; gap: .5rem;
    padding: .55rem 1.1rem; border-radius: 10px;
    border: none; background: transparent;
    font-size: .82rem; font-weight: 600; color: #888;
    cursor: pointer; white-space: nowrap;
    transition: all .2s; font-family: inherit;
}
.tab-btn:hover { background: #f0f4f8; color: #2969bf; }
.tab-btn.active {
    background: #2969bf; color: #fff;
    box-shadow: 0 3px 10px rgba(41,105,191,.25);
}
.tab-btn i { font-size: .82rem; }

/* ══════════════════════════════════════════
   TAB PANELS
══════════════════════════════════════════ */
.tab-panel { display: none; }
.tab-panel.active { display: block; animation: fadeIn .25s ease; }

/* ══════════════════════════════════════════
   FORM CARDS
══════════════════════════════════════════ */
.form-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    overflow: hidden;
    margin-bottom: 1.3rem;
}
.form-card-header {
    padding: .9rem 1.4rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; gap: .6rem;
}
.form-card-header h6 {
    font-size: .9rem; font-weight: 700;
    color: #2c3e50; margin: 0;
}
.form-card-header i { color: #2969bf; font-size: .9rem; }
.form-card-body { padding: 1.3rem 1.4rem; }

/* ══════════════════════════════════════════
   FORM ELEMENTS
══════════════════════════════════════════ */
.form-group { margin-bottom: 1rem; }
.form-label {
    display: block; font-size: .74rem; font-weight: 600;
    color: #555; text-transform: uppercase;
    letter-spacing: .04em; margin-bottom: .35rem;
}
.form-label .req { color: #e74c3c; margin-left: 2px; }

.form-input,
.form-select,
.form-textarea {
    width: 100%; border: 1.5px solid #e5ecf0;
    border-radius: 9px; padding: .58rem .9rem;
    font-size: .84rem; color: #2c3e50;
    outline: none; background: #fafcff;
    font-family: inherit;
    transition: border-color .2s, box-shadow .2s;
}
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41,105,191,.1);
}
.form-input.is-invalid,
.form-select.is-invalid {
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231,76,60,.08);
}
.form-textarea { resize: vertical; min-height: 90px; }
.form-hint { font-size: .72rem; color: #aab4be; margin-top: .3rem; }
.form-error { font-size: .72rem; color: #e74c3c; margin-top: .3rem; }

/* Input with icon */
.input-icon-wrap { position: relative; }
.input-icon-wrap .form-input { padding-left: 2.4rem; }
.input-icon {
    position: absolute; left: .85rem; top: 50%;
    transform: translateY(-50%); color: #aab4be;
    font-size: .82rem; pointer-events: none;
}

/* ══════════════════════════════════════════
   TAG INPUT (specializations / facilities)
══════════════════════════════════════════ */
.tag-input-wrap {
    border: 1.5px solid #e5ecf0; border-radius: 9px;
    padding: .45rem .7rem; background: #fafcff;
    display: flex; flex-wrap: wrap; gap: .4rem; align-items: center;
    cursor: text; transition: border-color .2s, box-shadow .2s;
    min-height: 44px;
}
.tag-input-wrap:focus-within {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41,105,191,.1);
}
.tag-item {
    display: inline-flex; align-items: center; gap: .3rem;
    background: #e8f0fe; color: #2969bf;
    font-size: .73rem; font-weight: 600;
    padding: .22rem .6rem; border-radius: 6px;
}
.tag-remove {
    background: none; border: none; cursor: pointer;
    color: #2969bf; font-size: .65rem; padding: 0;
    display: flex; align-items: center;
    transition: color .2s;
}
.tag-remove:hover { color: #e74c3c; }
.tag-real-input {
    border: none; outline: none; background: transparent;
    font-size: .82rem; font-family: inherit; color: #2c3e50;
    min-width: 140px; flex: 1;
}

/* ══════════════════════════════════════════
   DOCUMENT UPLOAD ZONE
══════════════════════════════════════════ */
.doc-upload-zone {
    border: 2px dashed #c8d8f0; border-radius: 12px;
    padding: 1.8rem; text-align: center;
    background: #f8fbff; cursor: pointer;
    transition: border-color .2s, background .2s;
}
.doc-upload-zone:hover,
.doc-upload-zone.dragover {
    border-color: #2969bf; background: #eef6ff;
}
.doc-upload-zone i { font-size: 2rem; color: #c8d8f0; display: block; margin-bottom: .6rem; }
.doc-upload-zone.dragover i { color: #2969bf; }
.doc-upload-zone p { font-size: .83rem; color: #888; margin: 0 0 .3rem; }
.doc-upload-zone span { font-size: .73rem; color: #aab4be; }

/* Document existing preview */
.doc-preview {
    display: flex; align-items: center; gap: .75rem;
    padding: .85rem 1rem; border-radius: 10px;
    background: #f8fbff; border: 1px solid #f0f4f8;
    margin-top: .75rem;
}
.doc-preview-icon {
    width: 40px; height: 40px; border-radius: 9px;
    background: linear-gradient(135deg,#e8f0fe,#d0e4ff);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #2969bf; flex-shrink: 0;
}
.doc-preview-info { flex: 1; min-width: 0; }
.doc-preview-name { font-size: .82rem; font-weight: 600; color: #2c3e50; }
.doc-preview-sub  { font-size: .7rem; color: #888; }

/* ══════════════════════════════════════════
   PHOTO UPLOAD MODAL ZONE
══════════════════════════════════════════ */
.photo-drop-zone {
    border: 2px dashed #c8d8f0; border-radius: 12px;
    padding: 2rem; text-align: center;
    background: #f8fbff; cursor: pointer;
    transition: all .2s; position: relative; overflow: hidden;
}
.photo-drop-zone:hover, .photo-drop-zone.dragover {
    border-color: #2969bf; background: #eef6ff;
}
.photo-drop-zone .preview-img {
    width: 100px; height: 100px; border-radius: 14px;
    object-fit: cover; margin: 0 auto .6rem; display: block;
    border: 3px solid #fff; box-shadow: 0 3px 12px rgba(41,105,191,.15);
}
.photo-drop-zone .preview-fallback {
    width: 80px; height: 80px; border-radius: 14px;
    background: linear-gradient(135deg,#2969bf,#5b9bd5);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; font-weight: 800; color: #fff;
    margin: 0 auto .6rem;
}

/* ══════════════════════════════════════════
   MAP PLACEHOLDER
══════════════════════════════════════════ */
.map-wrap {
    border-radius: 10px; overflow: hidden;
    border: 1.5px solid #e5ecf0; height: 180px;
    background: linear-gradient(135deg,#f0f6ff,#e8f0fb);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: .5rem; color: #888;
}
.map-wrap i { font-size: 2rem; color: #c8d8f0; }
.map-wrap p { font-size: .78rem; margin: 0; }

/* ══════════════════════════════════════════
   INFO DISPLAY ROWS (view mode)
══════════════════════════════════════════ */
.info-row {
    display: flex; gap: 1rem;
    padding: .65rem 0; border-bottom: 1px solid #f5f7fa;
    font-size: .84rem;
}
.info-row:last-child { border-bottom: none; }
.info-label { min-width: 140px; color: #888; font-weight: 500; flex-shrink: 0; }
.info-value { color: #2c3e50; font-weight: 600; }

/* ══════════════════════════════════════════
   ALERT / SUCCESS BANNER
══════════════════════════════════════════ */
.alert-banner {
    border-radius: 10px; padding: .85rem 1.1rem;
    display: flex; align-items: center; gap: .75rem;
    font-size: .83rem; font-weight: 500; margin-bottom: 1rem;
}
.alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #a3cfbb; }
.alert-error   { background: #f8d7da; color: #842029; border: 1px solid #f1aeb5; }
.alert-info    { background: #cfe2ff; color: #084298; border: 1px solid #9ec5fe; }
.alert-warning { background: #fff3cd; color: #664d03; border: 1px solid #ffda6a; }
.alert-banner i { font-size: 1rem; flex-shrink: 0; }

/* ══════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════ */
.btn-prf {
    padding: .52rem 1.3rem; border-radius: 9px;
    font-size: .83rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: .45rem;
    font-family: inherit;
}
.btn-prf.primary { background: #2969bf; color: #fff; }
.btn-prf.primary:hover { background: #1a4f9a; box-shadow: 0 4px 12px rgba(41,105,191,.3); }
.btn-prf.primary:disabled { opacity: .65; cursor: not-allowed; }
.btn-prf.secondary { background: #f0f4f8; color: #555; }
.btn-prf.secondary:hover { background: #e2e8f0; }
.btn-prf.danger { background: #e74c3c; color: #fff; }
.btn-prf.danger:hover { background: #c0392b; }
.btn-prf.outline {
    background: #fff; color: #2969bf;
    border: 1.5px solid #2969bf;
}
.btn-prf.outline:hover { background: #e8f0fe; }

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.prf-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(3px);
    z-index: 2000;
    display: flex; align-items: center; justify-content: center;
    padding: 1rem; opacity: 0; visibility: hidden;
    transition: opacity .25s, visibility .25s;
}
.prf-modal-overlay.show { opacity: 1; visibility: visible; }
.prf-modal {
    background: #fff; border-radius: 16px;
    width: 100%; max-width: 440px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    transform: translateY(-20px) scale(.97);
    transition: transform .25s; overflow: hidden;
    max-height: 90vh; display: flex; flex-direction: column;
}
.prf-modal-overlay.show .prf-modal { transform: translateY(0) scale(1); }
.prf-modal-header {
    padding: 1.1rem 1.4rem; border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.prf-modal-header h5 {
    font-size: .97rem; font-weight: 700;
    margin: 0; color: #2c3e50;
    display: flex; align-items: center; gap: .5rem;
}
.modal-close-btn {
    background: none; border: none; cursor: pointer;
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #888; font-size: .9rem;
    transition: background .2s, color .2s;
}
.modal-close-btn:hover { background: #f0f4f8; color: #e74c3c; }
.prf-modal-body { padding: 1.3rem 1.4rem; overflow-y: auto; flex: 1; }
.prf-modal-footer {
    padding: .9rem 1.4rem; border-top: 1px solid #f0f4f8;
    display: flex; justify-content: flex-end; gap: .6rem; flex-shrink: 0;
}

/* ══════════════════════════════════════════
   UPLOAD PROGRESS
══════════════════════════════════════════ */
.upload-progress {
    height: 4px; border-radius: 99px;
    background: #f0f4f8; overflow: hidden;
    margin-top: .75rem; display: none;
}
.upload-progress-bar {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg,#2969bf,#5b9bd5);
    width: 0; transition: width .3s;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 767.98px) {
    .hero-body { padding: 0 1.2rem 1.2rem; }
    .hero-name { font-size: 1rem; }
    .form-card-body { padding: 1rem; }
    .info-label { min-width: 110px; font-size: .78rem; }
    .tab-btn { padding: .5rem .85rem; font-size: .78rem; }
}
@media (max-width: 575.98px) {
    .hero-actions { flex-wrap: wrap; }
    .hero-meta { gap: .5rem; }
}
</style>
@endpush

@section('content')
<div class="profile-page">

    {{-- ══ FLASH MESSAGES ══ --}}
    @if(session('success'))
    <div class="alert-banner alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-banner alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert-banner alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ HERO CARD ══ --}}
    <div class="profile-hero">
        <div class="hero-cover">
            <div class="hero-cover-pattern"></div>
        </div>
        <div class="hero-body">
            <div class="hero-avatar-wrap">
                @if($hospital && $hospital->profile_image)
                    <img src="{{ asset('storage/'.$hospital->profile_image) }}"
                         class="hero-avatar" alt="Hospital"
                         id="heroAvatarImg"
                         onerror="this.style.display='none';document.getElementById('heroAvatarFallback').style.display='flex'">
                    <div class="hero-avatar-fallback" id="heroAvatarFallback" style="display:none;">
                        {{ strtoupper(substr($hospital->name ?? 'H', 0, 1)) }}
                    </div>
                @else
                    <div class="hero-avatar-fallback" id="heroAvatarFallback">
                        {{ strtoupper(substr($hospital->name ?? 'H', 0, 1)) }}
                    </div>
                @endif
                <label class="photo-upload-btn" for="quickPhotoInput" title="Change Photo">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="quickPhotoInput" accept="image/*"
                       style="display:none;" onchange="quickPhotoUpload(this)">
            </div>

            <div class="hero-info">
                <h4 class="hero-name">{{ $hospital->name ?? 'Hospital Name' }}</h4>
                <div class="hero-meta">
                    @if($hospital && $hospital->city)
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $hospital->city }}{{ $hospital->province ? ', '.$hospital->province : '' }}
                    </span>
                    @endif
                    @if($hospital && $hospital->phone)
                    <span>
                        <i class="fas fa-phone"></i>
                        {{ $hospital->phone }}
                    </span>
                    @endif
                    @if($hospital && $hospital->type)
                    <span>
                        <i class="fas fa-hospital"></i>
                        {{ ucfirst($hospital->type) }} Hospital
                    </span>
                    @endif
                </div>
                @php $st = $hospital->status ?? 'pending'; @endphp
                <span class="status-pill status-{{ $st }}">
                    <i class="fas fa-circle" style="font-size:.4rem;vertical-align:middle;"></i>
                    {{ ucfirst($st) }}
                </span>
            </div>

            <div class="hero-actions">
                @if($hospital && $hospital->website)
                <a href="{{ $hospital->website }}" target="_blank"
                   class="btn-prf outline" style="text-decoration:none;">
                    <i class="fas fa-globe"></i>
                    <span class="d-none d-sm-inline">Website</span>
                </a>
                @endif
                <button class="btn-prf primary" onclick="setTab('basic')">
                    <i class="fas fa-edit"></i>
                    <span class="d-none d-sm-inline">Edit Profile</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ TABS ══ --}}
    <div class="profile-tabs" id="profileTabs">
        <button class="tab-btn active" onclick="setTab('overview')" id="tab-overview">
            <i class="fas fa-th-large"></i> Overview
        </button>
        <button class="tab-btn" onclick="setTab('basic')" id="tab-basic">
            <i class="fas fa-hospital"></i> Basic Info
        </button>
        <button class="tab-btn" onclick="setTab('location')" id="tab-location">
            <i class="fas fa-map-marker-alt"></i> Location
        </button>
        <button class="tab-btn" onclick="setTab('services')" id="tab-services">
            <i class="fas fa-stethoscope"></i> Services
        </button>
        <button class="tab-btn" onclick="setTab('documents')" id="tab-documents">
            <i class="fas fa-file-alt"></i> Documents
        </button>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: OVERVIEW
    ══════════════════════════════════════════ --}}
    <div class="tab-panel active" id="panel-overview">
        <div class="row g-3">
            {{-- Left: Info --}}
            <div class="col-12 col-lg-7">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h6>Hospital Information</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-hospital me-2"></i>Name</span>
                            <span class="info-value">{{ $hospital->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-tag me-2"></i>Type</span>
                            <span class="info-value">{{ ucfirst($hospital->type ?? '—') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-envelope me-2"></i>Email</span>
                            <span class="info-value">{{ $hospital->email ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-phone me-2"></i>Phone</span>
                            <span class="info-value">{{ $hospital->phone ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-globe me-2"></i>Website</span>
                            <span class="info-value">
                                @if($hospital && $hospital->website)
                                    <a href="{{ $hospital->website }}" target="_blank"
                                       style="color:#2969bf;text-decoration:none;">
                                        {{ $hospital->website }}
                                    </a>
                                @else —
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-clock me-2"></i>Operating Hours</span>
                            <span class="info-value">{{ $hospital->operatinghours ?? '—' }}</span>
                        </div>
                        @if($hospital && $hospital->description)
                        <div class="info-row" style="align-items:flex-start;">
                            <span class="info-label"><i class="fas fa-align-left me-2"></i>Description</span>
                            <span class="info-value" style="font-weight:400;color:#555;line-height:1.6;">
                                {{ $hospital->description }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Location + Status --}}
            <div class="col-12 col-lg-5">
                <div class="form-card mb-3">
                    <div class="form-card-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h6>Location</h6>
                    </div>
                    <div class="form-card-body">
                        <div class="info-row">
                            <span class="info-label">Address</span>
                            <span class="info-value">{{ $hospital->address ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">City</span>
                            <span class="info-value">{{ $hospital->city ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Province</span>
                            <span class="info-value">{{ $hospital->province ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Postal Code</span>
                            <span class="info-value">{{ $hospital->postal_code ?? '—' }}</span>
                        </div>
                        @if($hospital && $hospital->latitude && $hospital->longitude)
                        <div style="margin-top:.75rem;">
                            <div class="map-wrap">
                                <i class="fas fa-map-pin"></i>
                                <p>{{ $hospital->latitude }}, {{ $hospital->longitude }}</p>
                                <a href="https://maps.google.com/?q={{ $hospital->latitude }},{{ $hospital->longitude }}"
                                   target="_blank"
                                   style="font-size:.75rem;color:#2969bf;font-weight:600;text-decoration:none;">
                                    <i class="fas fa-external-link-alt me-1"></i>View on Google Maps
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Specializations --}}
                @if(isset($specializations) && count($specializations))
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-stethoscope"></i>
                        <h6>Specializations</h6>
                    </div>
                    <div class="form-card-body">
                        <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                            @foreach($specializations as $spec)
                            <span style="background:#e8f0fe;color:#2969bf;
                                         font-size:.73rem;font-weight:600;
                                         padding:.25rem .65rem;border-radius:6px;">
                                {{ $spec }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: BASIC INFO EDIT
    ══════════════════════════════════════════ --}}
    <div class="tab-panel" id="panel-basic">
        <form action="{{ route('hospital.profile.update') }}" method="POST"
              id="profileForm" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
                {{-- General Info --}}
                <div class="col-12 col-lg-8">
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-hospital"></i>
                            <h6>General Information</h6>
                        </div>
                        <div class="form-card-body">
                            <div class="row g-3">
                                <div class="col-12 col-sm-8">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Hospital Name <span class="req">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               value="{{ old('name', $hospital->name ?? '') }}"
                                               placeholder="Enter hospital name" required>
                                        @error('name')
                                        <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select">
                                            <option value="">Select type</option>
                                            <option value="government" {{ old('type', $hospital->type ?? '') == 'government' ? 'selected' : '' }}>Government</option>
                                            <option value="private"    {{ old('type', $hospital->type ?? '') == 'private'    ? 'selected' : '' }}>Private</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <div class="input-icon-wrap">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" name="email" class="form-input"
                                                   value="{{ old('email', $hospital->email ?? '') }}"
                                                   placeholder="hospital@email.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <div class="input-icon-wrap">
                                            <i class="fas fa-phone input-icon"></i>
                                            <input type="text" name="phone" class="form-input"
                                                   value="{{ old('phone', $hospital->phone ?? '') }}"
                                                   placeholder="+94 11 234 5678">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                        <div class="input-icon-wrap">
                                            <i class="fas fa-globe input-icon"></i>
                                            <input type="url" name="website" class="form-input"
                                                   value="{{ old('website', $hospital->website ?? '') }}"
                                                   placeholder="https://www.hospital.lk">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Operating Hours</label>
                                        <div class="input-icon-wrap">
                                            <i class="fas fa-clock input-icon"></i>
                                            <input type="text" name="operatinghours"
                                            value="{{ old('operatinghours', $hospital->operatinghours ?? '') }}"
                                            placeholder="e.g. Mon–Fri 8AM–8PM">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-textarea"
                                                  placeholder="Brief description about your hospital..."
                                                  style="min-height:110px;">{{ old('description', $hospital->description ?? '') }}</textarea>
                                        <div class="form-hint">Max 500 characters recommended.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Photo --}}
                <div class="col-12 col-lg-4">
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-camera"></i>
                            <h6>Profile Photo</h6>
                        </div>
                        <div class="form-card-body" style="text-align:center;">
                            <div class="photo-drop-zone" id="photoDropZone"
                                 onclick="document.getElementById('photoFileInput').click()">
                                @if($hospital && $hospital->profile_image)
                                    <img src="{{ asset('storage/'.$hospital->profile_image) }}"
                                         class="preview-img" id="photoPreview"
                                         onerror="this.style.display='none'">
                                @else
                                    <div class="preview-fallback" id="photoFallback">
                                        {{ strtoupper(substr($hospital->name ?? 'H', 0, 1)) }}
                                    </div>
                                @endif
                                <p style="font-size:.8rem;color:#888;margin:.3rem 0 0;">
                                    <i class="fas fa-cloud-upload-alt me-1" style="color:#2969bf;"></i>
                                    Click or drag to upload
                                </p>
                                <span>JPG, PNG, WEBP · Max 2MB</span>
                            </div>
                            <input type="file" id="photoFileInput"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   style="display:none;" onchange="previewPhoto(this)">
                            <div class="upload-progress" id="photoProgress">
                                <div class="upload-progress-bar" id="photoProgressBar"></div>
                            </div>
                            <p style="font-size:.72rem;color:#aab4be;margin:.6rem 0 0;">
                                Square images work best (1:1 ratio)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div style="display:flex;justify-content:flex-end;gap:.6rem;margin-top:.5rem;">
                <button type="button" class="btn-prf secondary" onclick="setTab('overview')">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" class="btn-prf primary" id="saveBasicBtn">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: LOCATION
    ══════════════════════════════════════════ --}}
    <div class="tab-panel" id="panel-location">
        <form action="{{ route('hospital.profile.update') }}" method="POST">
            @csrf @method('PUT')
            {{-- Pass other fields hidden so they don't get wiped --}}
            <input type="hidden" name="name"             value="{{ $hospital->name ?? '' }}">
            <input type="hidden" name="type"             value="{{ $hospital->type ?? '' }}">
            <input type="hidden" name="email"            value="{{ $hospital->email ?? '' }}">
            <input type="hidden" name="phone"            value="{{ $hospital->phone ?? '' }}">
            <input type="hidden" name="website"          value="{{ $hospital->website ?? '' }}">
            <input type="hidden" name="operatinghours"  value="{{ $hospital->operatinghours ?? '' }}">
            <input type="hidden" name="description"      value="{{ $hospital->description ?? '' }}">

            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-map-marker-alt"></i>
                    <h6>Location & Address</h6>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Street Address</label>
                                <textarea name="address" class="form-textarea"
                                          style="min-height:70px;"
                                          placeholder="No. 123, Hospital Road...">{{ old('address', $hospital->address ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-city input-icon"></i>
                                    <input type="text" name="city" class="form-input"
                                           value="{{ old('city', $hospital->city ?? '') }}"
                                           placeholder="Colombo">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label class="form-label">Province</label>
                                <select name="province" class="form-select">
                                    <option value="">Select Province</option>
                                    @foreach(['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'] as $prov)
                                    <option value="{{ $prov }}"
                                        {{ old('province', $hospital->province ?? '') == $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-2">
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-input"
                                       value="{{ old('postal_code', $hospital->postal_code ?? '') }}"
                                       placeholder="00100">
                            </div>
                        </div>

                        {{-- GPS --}}
                        <div class="col-12">
                            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                                <span style="font-size:.8rem;font-weight:700;color:#2c3e50;">
                                    <i class="fas fa-crosshairs me-1" style="color:#2969bf;"></i>
                                    GPS Coordinates
                                </span>
                                <button type="button" class="btn-prf outline"
                                        onclick="detectLocation()"
                                        style="padding:.3rem .75rem;font-size:.75rem;">
                                    <i class="fas fa-location-arrow me-1"></i>Detect My Location
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Latitude</label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-map-pin input-icon"></i>
                                    <input type="number" name="latitude" id="latInput"
                                           class="form-input" step="0.000001"
                                           min="-90" max="90"
                                           value="{{ old('latitude', $hospital->latitude ?? '') }}"
                                           placeholder="6.9271">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Longitude</label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-map-pin input-icon"></i>
                                    <input type="number" name="longitude" id="lngInput"
                                           class="form-input" step="0.000001"
                                           min="-180" max="180"
                                           value="{{ old('longitude', $hospital->longitude ?? '') }}"
                                           placeholder="79.8612">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="map-wrap" id="mapPreview">
                                <i class="fas fa-map"></i>
                                <p>Enter coordinates to preview location</p>
                                @if($hospital && $hospital->latitude && $hospital->longitude)
                                <a href="https://maps.google.com/?q={{ $hospital->latitude }},{{ $hospital->longitude }}"
                                   target="_blank"
                                   style="font-size:.75rem;color:#2969bf;font-weight:600;text-decoration:none;">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    View current location on Google Maps
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.6rem;">
                <button type="button" class="btn-prf secondary" onclick="setTab('overview')">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" class="btn-prf primary">
                    <i class="fas fa-save me-1"></i>Save Location
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: SERVICES
    ══════════════════════════════════════════ --}}
    <div class="tab-panel" id="panel-services">
        <form action="{{ route('hospital.profile.update') }}" method="POST" id="servicesForm">
            @csrf @method('PUT')
            {{-- Hidden passthrough --}}
            <input type="hidden" name="name"            value="{{ $hospital->name ?? '' }}">
            <input type="hidden" name="type"            value="{{ $hospital->type ?? '' }}">
            <input type="hidden" name="email"           value="{{ $hospital->email ?? '' }}">
            <input type="hidden" name="phone"           value="{{ $hospital->phone ?? '' }}">
            <input type="hidden" name="website"         value="{{ $hospital->website ?? '' }}">
            <input type="hidden" name="operatinghours" value="{{ $hospital->operatinghours ?? '' }}">
            <input type="hidden" name="description"     value="{{ $hospital->description ?? '' }}">
            <input type="hidden" name="address"         value="{{ $hospital->address ?? '' }}">
            <input type="hidden" name="city"            value="{{ $hospital->city ?? '' }}">
            <input type="hidden" name="province"        value="{{ $hospital->province ?? '' }}">
            <input type="hidden" name="postal_code"     value="{{ $hospital->postal_code ?? '' }}">
            <input type="hidden" name="latitude"        value="{{ $hospital->latitude ?? '' }}">
            <input type="hidden" name="longitude"       value="{{ $hospital->longitude ?? '' }}">

            <div class="row g-3">
                {{-- Specializations --}}
                <div class="col-12 col-lg-6">
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-stethoscope"></i>
                            <h6>Specializations</h6>
                        </div>
                        <div class="form-card-body">
                            <label class="form-label">
                                Add Specializations
                            </label>
                            <div class="tag-input-wrap" id="specWrap"
                                 onclick="document.getElementById('specInput').focus()">
                                @if(isset($specializations))
                                    @foreach($specializations as $spec)
                                    <span class="tag-item" data-val="{{ $spec }}">
                                        {{ $spec }}
                                        <button type="button" class="tag-remove"
                                                onclick="removeTag(this,'specWrap','specializations[]')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                    <input type="hidden" name="specializations[]" value="{{ $spec }}">
                                    @endforeach
                                @endif
                                <input type="text" id="specInput" class="tag-real-input"
                                       placeholder="Type and press Enter..."
                                       onkeydown="addTag(event,'specWrap','specializations[]')">
                            </div>
                            <div class="form-hint">
                                <i class="fas fa-info-circle me-1"></i>
                                Press <kbd>Enter</kbd> or <kbd>,</kbd> to add a specialization.
                            </div>
                            {{-- Quick suggestions --}}
                            <div style="margin-top:.7rem;">
                                <span style="font-size:.72rem;color:#888;font-weight:600;">Quick add:</span>
                                <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.35rem;">
                                    @foreach(['Cardiology','Neurology','Orthopedics','Pediatrics','Gynecology','Oncology','Radiology','Dermatology','ENT','Ophthalmology'] as $sug)
                                    <button type="button"
                                            onclick="quickAddTag('specWrap','specializations[]','{{ $sug }}')"
                                            style="border:1px solid #e5ecf0;background:#f8fbff;
                                                   color:#555;font-size:.68rem;font-weight:600;
                                                   padding:.18rem .5rem;border-radius:6px;cursor:pointer;
                                                   transition:all .2s;"
                                            onmouseover="this.style.background='#e8f0fe';this.style.color='#2969bf'"
                                            onmouseout="this.style.background='#f8fbff';this.style.color='#555'">
                                        + {{ $sug }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Facilities --}}
                <div class="col-12 col-lg-6">
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas fa-hospital-alt"></i>
                            <h6>Facilities</h6>
                        </div>
                        <div class="form-card-body">
                            <label class="form-label">Add Facilities</label>
                            <div class="tag-input-wrap" id="facWrap"
                                 onclick="document.getElementById('facInput').focus()">
                                @if(isset($facilities))
                                    @foreach($facilities as $fac)
                                    <span class="tag-item" data-val="{{ $fac }}"
                                          style="background:#e9f7ee;color:#27ae60;">
                                        {{ $fac }}
                                        <button type="button" class="tag-remove"
                                                style="color:#27ae60;"
                                                onclick="removeTag(this,'facWrap','facilities[]')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                    <input type="hidden" name="facilities[]" value="{{ $fac }}">
                                    @endforeach
                                @endif
                                <input type="text" id="facInput" class="tag-real-input"
                                       placeholder="Type and press Enter..."
                                       onkeydown="addTag(event,'facWrap','facilities[]')">
                            </div>
                            <div class="form-hint">
                                <i class="fas fa-info-circle me-1"></i>
                                Press <kbd>Enter</kbd> or <kbd>,</kbd> to add a facility.
                            </div>
                            <div style="margin-top:.7rem;">
                                <span style="font-size:.72rem;color:#888;font-weight:600;">Quick add:</span>
                                <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.35rem;">
                                    @foreach(['ICU','Emergency','Laboratory','Pharmacy','Radiology','Blood Bank','Dialysis','Physiotherapy','Ambulance','Cafeteria'] as $sug)
                                    <button type="button"
                                            onclick="quickAddTag('facWrap','facilities[]','{{ $sug }}')"
                                            style="border:1px solid #e5ecf0;background:#f8fbff;
                                                   color:#555;font-size:.68rem;font-weight:600;
                                                   padding:.18rem .5rem;border-radius:6px;cursor:pointer;
                                                   transition:all .2s;"
                                            onmouseover="this.style.background='#e9f7ee';this.style.color='#27ae60'"
                                            onmouseout="this.style.background='#f8fbff';this.style.color='#555'">
                                        + {{ $sug }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.6rem;margin-top:.3rem;">
                <button type="button" class="btn-prf secondary" onclick="setTab('overview')">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" class="btn-prf primary">
                    <i class="fas fa-save me-1"></i>Save Services
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: DOCUMENTS
    ══════════════════════════════════════════ --}}
    <div class="tab-panel" id="panel-documents">
        <div class="form-card">
            <div class="form-card-header">
                <i class="fas fa-file-alt"></i>
                <h6>Hospital Documents</h6>
            </div>
            <div class="form-card-body">

                @if($hospital && $hospital->document_path)
                {{-- Existing doc --}}
                <div class="alert-banner alert-info" style="margin-bottom:1rem;">
                    <i class="fas fa-info-circle"></i>
                    Uploading a new document will replace the existing one.
                </div>
                <div class="doc-preview">
                    <div class="doc-preview-icon">
                        @php
                            $ext = pathinfo($hospital->document_path, PATHINFO_EXTENSION);
                        @endphp
                        <i class="fas fa-{{ in_array($ext,['jpg','jpeg','png']) ? 'image' : 'file-pdf' }}"></i>
                    </div>
                    <div class="doc-preview-info">
                        <div class="doc-preview-name">
                            Current Document ({{ strtoupper($ext) }})
                        </div>
                        <div class="doc-preview-sub">
                            Uploaded document on file
                        </div>
                    </div>
                    <div style="display:flex;gap:.5rem;">
                        <a href="{{ asset('storage/'.$hospital->document_path) }}"
                           target="_blank" class="btn-prf outline"
                           style="font-size:.75rem;padding:.35rem .8rem;text-decoration:none;">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        <a href="{{ asset('storage/'.$hospital->document_path) }}"
                           download class="btn-prf secondary"
                           style="font-size:.75rem;padding:.35rem .8rem;text-decoration:none;">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                </div>
                <div style="margin: 1.2rem 0; text-align:center;
                            font-size:.78rem;color:#aab4be;font-weight:600;">
                    — OR UPLOAD NEW —
                </div>
                @endif

                {{-- Upload zone --}}
                <div class="doc-upload-zone" id="docDropZone"
                     ondragover="event.preventDefault();this.classList.add('dragover')"
                     ondragleave="this.classList.remove('dragover')"
                     ondrop="handleDocDrop(event)"
                     onclick="document.getElementById('docFileInput').click()">
                    <i class="fas fa-cloud-upload-alt" id="docUploadIcon"></i>
                    <p id="docUploadText">
                        Drag & drop your document here, or <strong style="color:#2969bf;">browse</strong>
                    </p>
                    <span>PDF, JPG, PNG · Max 5MB</span>
                </div>
                <input type="file" id="docFileInput"
                       accept=".pdf,.jpg,.jpeg,.png"
                       style="display:none;" onchange="previewDoc(this)">

                <div class="upload-progress" id="docProgress">
                    <div class="upload-progress-bar" id="docProgressBar"></div>
                </div>

                {{-- Selected file preview --}}
                <div id="docSelectedPreview" style="display:none;margin-top:.75rem;">
                    <div class="doc-preview">
                        <div class="doc-preview-icon">
                            <i class="fas fa-file" id="docPreviewIcon"></i>
                        </div>
                        <div class="doc-preview-info">
                            <div class="doc-preview-name" id="docPreviewName">—</div>
                            <div class="doc-preview-sub" id="docPreviewSize">—</div>
                        </div>
                        <button type="button" onclick="clearDocSelection()"
                                style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:.85rem;">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:.6rem;margin-top:1.2rem;">
                    <button type="button" class="btn-prf secondary" onclick="clearDocSelection()">
                        <i class="fas fa-times me-1"></i>Clear
                    </button>
                    <button type="button" class="btn-prf primary"
                            id="uploadDocBtn" onclick="submitDocUpload()" disabled>
                        <i class="fas fa-upload me-1"></i>Upload Document
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     PHOTO UPLOAD CONFIRM MODAL
══════════════════════════════════════════════ --}}
<div class="prf-modal-overlay" id="photoConfirmModal">
    <div class="prf-modal" style="max-width:380px;">
        <div class="prf-modal-header">
            <h5>
                <i class="fas fa-camera" style="color:#2969bf;"></i>
                Update Profile Photo
            </h5>
            <button class="modal-close-btn" onclick="closeModal('photoConfirmModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="prf-modal-body" style="text-align:center;">
            <div style="margin-bottom:1rem;">
                <img id="photoModalPreview"
                     style="width:110px;height:110px;border-radius:18px;object-fit:cover;
                            border:4px solid #f0f4f8;box-shadow:0 4px 16px rgba(41,105,191,.15);"
                     src="" alt="Preview">
            </div>
            <p style="font-size:.84rem;color:#555;margin:0;">
                Upload this photo as your hospital profile picture?
            </p>
            <div class="upload-progress" id="photoUploadProgress" style="margin-top:.75rem;">
                <div class="upload-progress-bar" id="photoUploadProgressBar"></div>
            </div>
        </div>
        <div class="prf-modal-footer">
            <button class="btn-prf secondary" onclick="closeModal('photoConfirmModal')">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="btn-prf primary" id="confirmPhotoBtn" onclick="confirmPhotoUpload()">
                <i class="fas fa-upload me-1"></i>Upload Photo
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════
// STATE
// ════════════════════════════════════════════════
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let pendingPhotoFile = null;
let pendingDocFile   = null;

// ════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    // Open tab from hash
    const hash = window.location.hash.replace('#','');
    if (hash && document.getElementById('panel-' + hash)) setTab(hash);

    // Photo drop zone on basic tab
    const dropZone = document.getElementById('photoDropZone');
    if (dropZone) {
        dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault(); dropZone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) previewPhotoFile(file);
        });
    }

    // Dismiss alerts
    document.querySelectorAll('.alert-banner').forEach(a => {
        a.style.cursor = 'pointer';
        a.addEventListener('click', () => a.remove());
    });

    // Close modals
    document.querySelectorAll('.prf-modal-overlay').forEach(o => {
        o.addEventListener('click', e => { if (e.target === o) closeModal(o.id); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape')
            document.querySelectorAll('.prf-modal-overlay.show')
                    .forEach(m => closeModal(m.id));
    });

    // Profile form submit btn
    const form = document.getElementById('profileForm');
    if (form) {
        form.addEventListener('submit', function () {
            const btn = document.getElementById('saveBasicBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
            }
        });
    }
});

// ════════════════════════════════════════════════
// TAB MANAGEMENT
// ════════════════════════════════════════════════
function setTab(name) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));

    const btn   = document.getElementById('tab-' + name);
    const panel = document.getElementById('panel-' + name);
    if (btn)   btn.classList.add('active');
    if (panel) panel.classList.add('active');

    window.history.replaceState(null, '', '#' + name);
}

// ════════════════════════════════════════════════
// QUICK PHOTO UPLOAD (camera icon)
// ════════════════════════════════════════════════
function quickPhotoUpload(input) {
    const file = input.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) {
        showToast('Please select an image file.', 'error'); return;
    }
    if (file.size > 2 * 1024 * 1024) {
        showToast('Image must be under 2MB.', 'error'); return;
    }
    pendingPhotoFile = file;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoModalPreview').src = e.target.result;
        openModal('photoConfirmModal');
    };
    reader.readAsDataURL(file);
}

// ════════════════════════════════════════════════
// PREVIEW PHOTO (basic tab drag/click)
// ════════════════════════════════════════════════
function previewPhoto(input) {
    const file = input.files[0];
    if (!file) return;
    previewPhotoFile(file);
}
function previewPhotoFile(file) {
    if (!file.type.startsWith('image/')) {
        showToast('Please select an image file.', 'error'); return;
    }
    if (file.size > 2 * 1024 * 1024) {
        showToast('Image must be under 2MB.', 'error'); return;
    }
    // Show preview in drop zone
    const reader = new FileReader();
    reader.onload = e => {
        let img = document.getElementById('photoPreview');
        const fb = document.getElementById('photoFallback');
        if (!img) {
            img = document.createElement('img');
            img.id = 'photoPreview';
            img.className = 'preview-img';
            const zone = document.getElementById('photoDropZone');
            zone.insertBefore(img, zone.firstChild);
        }
        img.src = e.target.result;
        img.style.display = '';
        if (fb) fb.style.display = 'none';
    };
    reader.readAsDataURL(file);

    // Also set as pending for quick upload
    pendingPhotoFile = file;
    document.getElementById('photoModalPreview').src = URL.createObjectURL(file);
    openModal('photoConfirmModal');
}

// ════════════════════════════════════════════════
// CONFIRM & UPLOAD PHOTO
// ════════════════════════════════════════════════
function confirmPhotoUpload() {
    if (!pendingPhotoFile) return;

    const btn = document.getElementById('confirmPhotoBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';

    const prog    = document.getElementById('photoUploadProgress');
    const progBar = document.getElementById('photoUploadProgressBar');
    if (prog) prog.style.display = '';

    const formData = new FormData();
    formData.append('photo', pendingPhotoFile);
    formData.append('_token', CSRF);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route("hospital.profile.photo") }}', true);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);

    xhr.upload.onprogress = e => {
        if (e.lengthComputable && progBar) {
            progBar.style.width = Math.round((e.loaded/e.total)*100) + '%';
        }
    };

    xhr.onload = function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload me-1"></i>Upload Photo';
        if (prog) prog.style.display = 'none';

        try {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                closeModal('photoConfirmModal');
                showToast('Profile photo updated!', 'success');
                // Update all avatar elements
                const newSrc = data.path + '?t=' + Date.now();
                ['heroAvatarImg','photoPreview'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) { el.src = newSrc; el.style.display = ''; }
                });
                const fb = document.getElementById('heroAvatarFallback');
                if (fb) fb.style.display = 'none';
                pendingPhotoFile = null;
            } else {
                showToast(data.message ?? 'Upload failed.', 'error');
            }
        } catch(e) {
            showToast('Upload failed. Please try again.', 'error');
        }
    };
    xhr.onerror = () => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload me-1"></i>Upload Photo';
        showToast('Upload failed. Please try again.', 'error');
    };

    xhr.send(formData);
}

// ════════════════════════════════════════════════
// DOCUMENT UPLOAD
// ════════════════════════════════════════════════
function handleDocDrop(e) {
    e.preventDefault();
    document.getElementById('docDropZone').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) setDocFile(file);
}

function previewDoc(input) {
    const file = input.files[0];
    if (file) setDocFile(file);
}

function setDocFile(file) {
    const allowed = ['application/pdf','image/jpeg','image/jpg','image/png'];
    if (!allowed.includes(file.type)) {
        showToast('Only PDF, JPG, PNG allowed.', 'error'); return;
    }
    if (file.size > 5 * 1024 * 1024) {
        showToast('File must be under 5MB.', 'error'); return;
    }
    pendingDocFile = file;

    const ext = file.name.split('.').pop().toUpperCase();
    const icon = file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-image';

    document.getElementById('docPreviewIcon').className = `fas ${icon}`;
    document.getElementById('docPreviewName').textContent = file.name;
    document.getElementById('docPreviewSize').textContent =
        `${ext} · ${(file.size/1024).toFixed(1)} KB`;
    document.getElementById('docSelectedPreview').style.display = '';
    document.getElementById('uploadDocBtn').disabled = false;

    // Update drop zone text
    document.getElementById('docUploadText').innerHTML =
        `<strong style="color:#27ae60;">${file.name}</strong> selected`;
    document.getElementById('docUploadIcon').style.color = '#27ae60';
}

function clearDocSelection() {
    pendingDocFile = null;
    document.getElementById('docSelectedPreview').style.display = 'none';
    document.getElementById('uploadDocBtn').disabled = true;
    document.getElementById('docFileInput').value = '';
    document.getElementById('docUploadText').innerHTML =
        'Drag & drop your document here, or <strong style="color:#2969bf;">browse</strong>';
    document.getElementById('docUploadIcon').style.color = '';
}

function submitDocUpload() {
    if (!pendingDocFile) return;

    const btn     = document.getElementById('uploadDocBtn');
    const prog    = document.getElementById('docProgress');
    const progBar = document.getElementById('docProgressBar');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';
    prog.style.display = '';

    const formData = new FormData();
    formData.append('document', pendingDocFile);
    formData.append('_token', CSRF);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route("hospital.profile.documents") }}', true);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);

    xhr.upload.onprogress = e => {
        if (e.lengthComputable)
            progBar.style.width = Math.round((e.loaded/e.total)*100) + '%';
    };

    xhr.onload = function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload me-1"></i>Upload Document';
        prog.style.display = 'none';

        try {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                showToast('Document uploaded successfully!', 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message ?? 'Upload failed.', 'error');
            }
        } catch(e) {
            showToast('Upload failed. Please try again.', 'error');
        }
    };
    xhr.onerror = () => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload me-1"></i>Upload Document';
        showToast('Upload failed.', 'error');
    };

    xhr.send(formData);
}

// ════════════════════════════════════════════════
// TAG INPUT (specializations & facilities)
// ════════════════════════════════════════════════
function addTag(e, wrapId, inputName) {
    if (e.key !== 'Enter' && e.key !== ',') return;
    e.preventDefault();
    const input = e.target;
    const val   = input.value.trim().replace(/,$/, '');
    if (!val) return;
    insertTag(wrapId, inputName, val, input);
    input.value = '';
}

function quickAddTag(wrapId, inputName, val) {
    // Check duplicate
    const wrap = document.getElementById(wrapId);
    const existing = [...wrap.querySelectorAll('.tag-item')].map(t => t.dataset.val);
    if (existing.includes(val)) { showToast(`"${val}" already added.`, 'info'); return; }
    const fakeInput = wrap.querySelector('.tag-real-input');
    insertTag(wrapId, inputName, val, fakeInput);
}

function insertTag(wrapId, inputName, val, inputEl) {
    const wrap = document.getElementById(wrapId);
    const existing = [...wrap.querySelectorAll('.tag-item')].map(t => t.dataset.val);
    if (existing.includes(val)) { showToast(`"${val}" already added.`, 'info'); return; }

    const isSpec = inputName.includes('spec');
    const bgColor = isSpec ? '#e8f0fe' : '#e9f7ee';
    const txtColor= isSpec ? '#2969bf' : '#27ae60';

    const tag = document.createElement('span');
    tag.className = 'tag-item';
    tag.dataset.val = val;
    tag.style.background = bgColor;
    tag.style.color = txtColor;
    tag.innerHTML = `${val}
        <button type="button" class="tag-remove" style="color:${txtColor};"
                onclick="removeTag(this,'${wrapId}','${inputName}')">
            <i class="fas fa-times"></i>
        </button>`;

    const hidden = document.createElement('input');
    hidden.type  = 'hidden';
    hidden.name  = inputName;
    hidden.value = val;

    wrap.insertBefore(tag,    inputEl);
    wrap.insertBefore(hidden, inputEl);
}

function removeTag(btn, wrapId, inputName) {
    const tag = btn.closest('.tag-item');
    const val = tag?.dataset.val;
    if (!val) return;
    const wrap = document.getElementById(wrapId);
    // Remove hidden input
    const hiddens = wrap.querySelectorAll(`input[name="${inputName}"]`);
    hiddens.forEach(h => { if (h.value === val) h.remove(); });
    tag.remove();
}

// ════════════════════════════════════════════════
// LOCATION DETECT
// ════════════════════════════════════════════════
function detectLocation() {
    if (!navigator.geolocation) {
        showToast('Geolocation is not supported by your browser.', 'error'); return;
    }
    showToast('Detecting location...', 'info');
    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            const latEl = document.getElementById('latInput');
            const lngEl = document.getElementById('lngInput');
            if (latEl) latEl.value = lat;
            if (lngEl) lngEl.value = lng;
            showToast(`Location detected: ${lat}, ${lng}`, 'success');
            // Update map link
            const mapWrap = document.getElementById('mapPreview');
            if (mapWrap) {
                mapWrap.innerHTML = `
                    <i class="fas fa-map-pin" style="font-size:2rem;color:#2969bf;"></i>
                    <p>${lat}, ${lng}</p>
                    <a href="https://maps.google.com/?q=${lat},${lng}" target="_blank"
                       style="font-size:.75rem;color:#2969bf;font-weight:600;text-decoration:none;">
                        <i class="fas fa-external-link-alt me-1"></i>View on Google Maps
                    </a>`;
            }
        },
        err => {
            showToast('Could not detect location. Please enter manually.', 'error');
        }
    );
}

// ════════════════════════════════════════════════
// MODAL HELPERS
// ════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

// ════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════
function showToast(msg, type = 'success') {
    const ex = document.getElementById('prfToast');
    if (ex) ex.remove();
    const c = {
        success: { bg:'#d1e7dd', color:'#0f5132', icon:'fa-check-circle' },
        error:   { bg:'#f8d7da', color:'#842029', icon:'fa-exclamation-circle' },
        info:    { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' },
        warning: { bg:'#fff3cd', color:'#664d03', icon:'fa-exclamation-triangle' },
    }[type] ?? { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' };

    const t = document.createElement('div');
    t.id = 'prfToast';
    t.style.cssText = `
        position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${c.bg};color:${c.color};
        border-radius:12px;padding:.8rem 1.2rem;
        display:flex;align-items:center;gap:.6rem;
        font-size:.83rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        animation:slideUp .3s ease;max-width:340px;
        border:1px solid ${c.color}33;
    `;
    t.innerHTML = `<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

// Inject animations
const s = document.createElement('style');
s.textContent = `
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes slideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
`;
document.head.appendChild(s);
</script>
@endpush
