@extends('medical_centre.layouts.master')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
@php
    $operatinghours  = $mc->operatinghours ?? '';
    $description     = $mc->description   ?? '';
    $address         = $mc->address       ?? '';
    $city            = $mc->city          ?? '';
    $province        = $mc->province      ?? '';
    $postal_code     = $mc->postal_code   ?? '';
    $phone           = $mc->phone         ?? '';
    $mcEmail         = $mc->email         ?? '';
    $latitude        = $mc->latitude      ?? '';
    $longitude       = $mc->longitude     ?? '';

    // JSON decode — safely handle null / invalid JSON / plain string
    $rawSpec = $mc->specializations ?? '[]';
    $rawFac  = $mc->facilities      ?? '[]';

    $specializations = is_array($rawSpec)
        ? $rawSpec
        : (json_decode($rawSpec, true) ?? []);

    $facilities = is_array($rawFac)
        ? $rawFac
        : (json_decode($rawFac, true) ?? []);

    // Make sure they are arrays (extra safety)
    if (!is_array($specializations)) $specializations = [];
    if (!is_array($facilities))      $facilities      = [];

    $pillMap = [
        'approved'  => ['pill-approved',  'fa-check-circle', 'Approved'],
        'pending'   => ['pill-pending',   'fa-clock',        'Pending Approval'],
        'suspended' => ['pill-suspended', 'fa-ban',          'Suspended'],
        'rejected'  => ['pill-rejected',  'fa-times-circle', 'Rejected'],
    ];
    [$pillClass, $pillIcon, $pillLabel] = $pillMap[$mc->status] ?? ['pill-pending','fa-clock','Unknown'];
@endphp


<style>
/* ── Layout ── */
.mc-page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.mc-page-title{font-size:1.25rem;font-weight:800;color:var(--text-dark);margin:0 0 .2rem}
.mc-page-sub{font-size:.82rem;color:var(--text-muted);margin:0}
.prof-grid{display:grid;grid-template-columns:290px 1fr;gap:1.25rem;align-items:start}
@media(max-width:1000px){.prof-grid{grid-template-columns:1fr}}

/* ── Cards ── */
.prof-card{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:1.25rem}
.prof-card:last-child{margin-bottom:0}
.prof-card-head{padding:.9rem 1.15rem;border-bottom:1px solid var(--border);background:#fafbfc;display:flex;align-items:center;justify-content:space-between}
.prof-card-head h6{font-size:.88rem;font-weight:800;color:var(--text-dark);margin:0;display:flex;align-items:center;gap:.4rem}
.prof-card-body{padding:1.25rem}

/* ── Sidebar Avatar ── */
.prof-avatar-wrap{display:flex;flex-direction:column;align-items:center;padding:1.75rem 1rem 1.25rem;text-align:center}
.prof-avatar-ring{position:relative;display:inline-block;margin-bottom:1rem}
.prof-avatar-img{width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid var(--mc-primary);display:block}
.prof-avatar-placeholder{width:110px;height:110px;border-radius:50%;background:linear-gradient(135deg,var(--mc-primary),var(--mc-secondary));display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:900;color:#fff;border:3px solid var(--mc-primary)}
.prof-avatar-edit{position:absolute;bottom:4px;right:4px;width:30px;height:30px;border-radius:50%;background:var(--mc-primary);color:#fff;border:2px solid #fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;cursor:pointer;transition:var(--transition)}
.prof-avatar-edit:hover{background:var(--mc-secondary)}
.prof-name{font-size:1rem;font-weight:800;color:var(--text-dark);margin:0 0 .2rem}
.prof-reg{font-size:.72rem;color:var(--text-muted);font-weight:600;margin:0 0 .5rem}
.prof-status-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.22rem .75rem;border-radius:99px;font-size:.7rem;font-weight:800;margin-bottom:.85rem}
.pill-approved{background:#d1fae5;color:#065f46}
.pill-pending{background:#fff3cd;color:#92400e}
.pill-suspended{background:#fee2e2;color:#991b1b}
.pill-rejected{background:#f3f4f6;color:#6b7280}
.prof-stat-row{display:grid;grid-template-columns:repeat(2,1fr);gap:.6rem;width:100%;margin-bottom:.5rem}
.prof-stat-box{background:#f8fbff;border-radius:9px;padding:.6rem .5rem;text-align:center;border:1px solid var(--border)}
.prof-stat-box .num{font-size:1.2rem;font-weight:900;color:var(--text-dark);line-height:1}
.prof-stat-box .lbl{font-size:.62rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-top:.2rem}

/* ── Tabs ── */
.prof-tabs{display:flex;gap:.25rem;border-bottom:2px solid var(--border);margin-bottom:1.25rem;overflow-x:auto;flex-wrap:nowrap;padding-bottom:0}
.prof-tab{padding:.6rem 1.1rem;font-size:.78rem;font-weight:700;color:var(--text-muted);border:none;background:none;cursor:pointer;font-family:inherit;border-bottom:2px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:color .15s;display:flex;align-items:center;gap:.35rem}
.prof-tab.active,.prof-tab:hover{color:var(--mc-primary);border-bottom-color:var(--mc-primary)}

/* ── Form ── */
.prof-form-group{margin-bottom:1rem}
.prof-form-group:last-child{margin-bottom:0}
.prof-label{display:block;font-size:.72rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem}
.prof-input,.prof-select,.prof-textarea{width:100%;padding:.55rem .9rem;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-weight:600;color:var(--text-dark);background:#fff;font-family:inherit;outline:none;transition:border-color .2s;box-sizing:border-box}
.prof-input:focus,.prof-select:focus,.prof-textarea:focus{border-color:var(--mc-primary);background:#fafeff}
.prof-input.is-invalid,.prof-textarea.is-invalid{border-color:#e74c3c}
.prof-form-error{font-size:.7rem;color:#e74c3c;margin-top:.3rem;font-weight:600}
.prof-input:disabled{background:#f4f7fb;color:var(--text-muted);cursor:not-allowed}
.prof-textarea{resize:vertical;min-height:90px}
.prof-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
.prof-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.85rem}
@media(max-width:600px){.prof-grid-2{grid-template-columns:1fr}.prof-grid-3{grid-template-columns:1fr 1fr}}

/* ── Buttons ── */
.prof-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.52rem 1.15rem;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:var(--transition)}
.prof-btn-primary{background:var(--mc-primary);color:#fff}
.prof-btn-primary:hover{background:var(--mc-secondary)}
.prof-btn-danger{background:#fee2e2;color:#991b1b;border:1.5px solid #fca5a5}
.prof-btn-danger:hover{background:#e74c3c;color:#fff;border-color:#e74c3c}
.prof-btn:disabled{opacity:.6;cursor:not-allowed}

/* ── Tag Input ── */
.tag-wrap{display:flex;flex-wrap:wrap;gap:.4rem;padding:.5rem .75rem;border:1.5px solid var(--border);border-radius:9px;background:#fff;min-height:42px;cursor:text;transition:border-color .2s}
.tag-wrap:focus-within{border-color:var(--mc-primary)}
.tag-item{display:inline-flex;align-items:center;gap:.3rem;background:#e8f0fe;color:#2969bf;padding:.18rem .6rem;border-radius:99px;font-size:.72rem;font-weight:700}
.tag-item button{background:none;border:none;color:#2969bf;cursor:pointer;padding:0;font-size:.85rem;line-height:1}
.tag-input-field{border:none;outline:none;font-size:.8rem;font-weight:600;color:var(--text-dark);background:transparent;min-width:120px;font-family:inherit;flex:1}

/* ── Upload Area ── */
.upload-area{border:2px dashed var(--border);border-radius:12px;padding:1.5rem;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s,background .2s;position:relative;overflow:hidden}
.upload-area:hover,.upload-area.drag-over{border-color:var(--mc-primary);background:#f0fdf4}
.upload-area i{font-size:2rem;color:var(--mc-primary);opacity:.4;display:block;margin-bottom:.5rem}
.upload-area p{font-size:.8rem;color:var(--text-muted);margin:0;font-weight:600}
.upload-area small{font-size:.7rem;color:var(--text-muted)}
.upload-area input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}

/* ── Document ── */
.doc-existing{display:flex;align-items:center;gap:.65rem;background:#f8fbff;border:1.5px solid var(--border);border-radius:9px;padding:.65rem .9rem;margin-bottom:.85rem}
.doc-existing i{font-size:1.2rem;color:#e74c3c}
.doc-existing span{font-size:.8rem;font-weight:700;color:var(--text-dark);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

/* ── Map ── */
#profileMap{height:230px;border-radius:10px;border:1.5px solid var(--border);margin-bottom:.85rem}

/* ── Quick info ── */
.qi-row{display:flex;gap:.5rem;align-items:flex-start;margin-bottom:.6rem}
.qi-row:last-child{margin-bottom:0}
.qi-icon{width:16px;flex-shrink:0;color:var(--mc-primary);font-size:.78rem;margin-top:.1rem}
.qi-text{font-size:.8rem;color:var(--text-muted);font-weight:600;word-break:break-word}

/* ── Owner doc ── */
.owner-doc-card{display:flex;align-items:center;gap:.85rem;background:#f8fbff;border-radius:10px;border:1.5px solid var(--border);padding:.85rem 1rem;margin-top:.4rem}
.owner-doc-avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;font-weight:800;flex-shrink:0}

/* ── Stars ── */
.star-display{color:#f59e0b;font-size:.78rem;letter-spacing:.04rem}
.star-empty{color:#e5e7eb}
</style>

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-user-circle me-2" style="color:var(--mc-primary);"></i>My Profile
        </h4>
        <p class="mc-page-sub">Manage your medical centre information</p>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
     style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
     style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="prof-grid">

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<div>

    {{-- Avatar + Stats --}}
    <div class="prof-card">
        <div class="prof-avatar-wrap">
            <div class="prof-avatar-ring">
                @if($mc->profile_image)
                    <img src="{{ asset('storage/' . $mc->profile_image) }}"
                         alt="{{ $mc->name }}" class="prof-avatar-img">
                @else
                    <div class="prof-avatar-placeholder">
                        {{ strtoupper(substr($mc->name, 0, 1)) }}
                    </div>
                @endif
                <label for="quickPhotoInput" class="prof-avatar-edit" title="Change photo">
                    <i class="fas fa-camera"></i>
                </label>
            </div>

            <p class="prof-name">{{ $mc->name }}</p>
            <p class="prof-reg"><i class="fas fa-id-card me-1"></i>{{ $mc->registration_number }}</p>

            <span class="prof-status-pill {{ $pillClass }}">
                <i class="fas {{ $pillIcon }}"></i> {{ $pillLabel }}
            </span>

            <div class="prof-stat-row">
                <div class="prof-stat-box">
                    <div class="num">{{ $stats['total_appointments'] }}</div>
                    <div class="lbl">Apts</div>
                </div>
                <div class="prof-stat-box">
                    <div class="num">{{ $stats['total_doctors'] }}</div>
                    <div class="lbl">Doctors</div>
                </div>
                <div class="prof-stat-box">
                    <div class="num">{{ $stats['avg_rating'] }}</div>
                    <div class="lbl">Rating</div>
                </div>
                <div class="prof-stat-box">
                    <div class="num">{{ $stats['total_reviews'] }}</div>
                    <div class="lbl">Reviews</div>
                </div>
            </div>

            {{-- Quick photo form --}}
            <form method="POST"
                  action="{{ route('medical_centre.profile.update_photo') }}"
                  enctype="multipart/form-data"
                  id="quickPhotoForm"
                  style="display:none;">
                @csrf
                <input type="file" id="quickPhotoInput" name="profile_image"
                       accept="image/*" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    {{-- Quick Info --}}
    <div class="prof-card">
        <div class="prof-card-head">
            <h6><i class="fas fa-info-circle" style="color:var(--mc-primary);"></i> Quick Info</h6>
        </div>
        <div class="prof-card-body">

            @if($city || $province)
            <div class="qi-row">
                <i class="fas fa-map-marker-alt qi-icon"></i>
                <span class="qi-text">{{ collect([$city, $province])->filter()->implode(', ') }}</span>
            </div>
            @endif

            @if($phone)
            <div class="qi-row">
                <i class="fas fa-phone qi-icon"></i>
                <span class="qi-text">{{ $phone }}</span>
            </div>
            @endif

            @if($mcEmail)
            <div class="qi-row">
                <i class="fas fa-envelope qi-icon"></i>
                <span class="qi-text">{{ $mcEmail }}</span>
            </div>
            @endif

            @if($operatinghours)
            <div class="qi-row">
                <i class="fas fa-clock qi-icon"></i>
                <span class="qi-text" style="white-space:pre-line;">{{ $operatinghours }}</span>
            </div>
            @endif

            @if($mc->rating > 0)
            <div class="qi-row" style="margin-top:.25rem;">
                <i class="fas fa-star qi-icon" style="color:#f59e0b;"></i>
                <span>
                    <span class="star-display">
                        @for($i=1;$i<=5;$i++)
                            <i class="{{ $i <= round($mc->rating) ? 'fas' : 'far' }} fa-star {{ $i > round($mc->rating) ? 'star-empty' : '' }}"></i>
                        @endfor
                    </span>
                    <span style="font-size:.72rem;color:var(--text-muted);font-weight:700;margin-left:.3rem;">
                        {{ $mc->rating }} ({{ $mc->total_ratings }})
                    </span>
                </span>
            </div>
            @endif

            @if($ownerDoctor)
            <div style="margin-top:.75rem;">
                <p style="font-size:.68rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;">Owner Doctor</p>
                <div class="owner-doc-card">
                    <div class="owner-doc-avatar">
                        {{ strtoupper(substr($ownerDoctor->first_name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:.85rem;font-weight:800;color:var(--text-dark);margin:0 0 .1rem;">
                            Dr. {{ $ownerDoctor->first_name ?? '' }} {{ $ownerDoctor->last_name ?? '' }}
                        </p>
                        <p style="font-size:.72rem;color:var(--text-muted);font-weight:600;margin:0;">
                            {{ $ownerDoctor->specialization ?? 'General' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($mc->document_path)
            <div style="margin-top:.85rem;">
                <a href="{{ asset('storage/' . $mc->document_path) }}"
                   target="_blank"
                   style="display:inline-flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:700;color:var(--mc-primary);text-decoration:none;">
                    <i class="fas fa-file-pdf"></i> View Registration Document
                </a>
            </div>
            @endif

        </div>
    </div>

</div>
{{-- END SIDEBAR --}}

{{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
<div>

    {{-- Tabs --}}
    <div class="prof-tabs">
        <button class="prof-tab active" onclick="switchTab('info',this)">
            <i class="fas fa-edit"></i> Basic Info
        </button>
        <button class="prof-tab" onclick="switchTab('services',this)">
            <i class="fas fa-stethoscope"></i> Services
        </button>
        <button class="prof-tab" onclick="switchTab('location',this)">
            <i class="fas fa-map-marker-alt"></i> Location
        </button>
        <button class="prof-tab" onclick="switchTab('photo',this)">
            <i class="fas fa-camera"></i> Photo
        </button>
        <button class="prof-tab" onclick="switchTab('document',this)">
            <i class="fas fa-file-alt"></i> Document
        </button>
        <button class="prof-tab" onclick="switchTab('security',this)">
            <i class="fas fa-lock"></i> Security
        </button>
    </div>

    {{-- ══ TAB: Basic Info ══ --}}
    <div id="tab-info" class="tab-panel">
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-building" style="color:var(--mc-primary);"></i> Basic Information</h6>
            </div>
            <div class="prof-card-body">
                <form method="POST" action="{{ route('medical_centre.profile.update_info') }}">
                    @csrf

                    <div class="prof-grid-2">
                        <div class="prof-form-group">
                            <label class="prof-label">Centre Name <span style="color:#e74c3c">*</span></label>
                            <input type="text" name="name"
                                   class="prof-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $mc->name) }}" required>
                            @error('name')<div class="prof-form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Registration Number</label>
                            <input type="text" class="prof-input"
                                   value="{{ $mc->registration_number }}" disabled>
                            <small style="font-size:.68rem;color:var(--text-muted);">Contact admin to change.</small>
                        </div>
                    </div>

                    <div class="prof-grid-2">
                        <div class="prof-form-group">
                            <label class="prof-label">Phone</label>
                            <input type="text" name="phone" class="prof-input"
                                   value="{{ old('phone', $phone) }}"
                                   placeholder="+94 XX XXX XXXX">
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Contact Email</label>
                            <input type="email" name="email" class="prof-input"
                                   value="{{ old('email', $mcEmail) }}"
                                   placeholder="centre@example.com">
                        </div>
                    </div>

                    <div class="prof-form-group">
                        <label class="prof-label">Address</label>
                        <input type="text" name="address" class="prof-input"
                               value="{{ old('address', $address) }}"
                               placeholder="Street address">
                    </div>

                    <div class="prof-grid-3">
                        <div class="prof-form-group">
                            <label class="prof-label">City</label>
                            <input type="text" name="city" class="prof-input"
                                   value="{{ old('city', $city) }}"
                                   placeholder="Colombo">
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Province</label>
                            <select name="province" class="prof-select">
                                <option value="">Select Province</option>
                                @foreach(['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'] as $prov)
                                    <option value="{{ $prov }}"
                                        {{ old('province', $province) === $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Postal Code</label>
                            <input type="text" name="postal_code" class="prof-input"
                                   value="{{ old('postal_code', $postal_code) }}"
                                   placeholder="00100">
                        </div>
                    </div>

                    <div class="prof-form-group">
                        <label class="prof-label">Description</label>
                        <textarea name="description" class="prof-textarea" rows="4"
                                  placeholder="Describe your medical centre...">{{ old('description', $description) }}</textarea>
                    </div>

                    <div class="prof-form-group">
                        <label class="prof-label">Operating Hours</label>
                        <textarea name="operatinghours" class="prof-textarea" rows="3"
                                  placeholder="Mon–Fri: 8:00 AM – 8:00 PM&#10;Sat: 8:00 AM – 4:00 PM&#10;Sun: Closed">{{ old('operatinghours', $operatinghours) }}</textarea>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Services ══ --}}
    <div id="tab-services" class="tab-panel" style="display:none;">
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-stethoscope" style="color:var(--mc-primary);"></i> Specializations & Facilities</h6>
            </div>
            <div class="prof-card-body">
                <form method="POST" action="{{ route('medical_centre.profile.update_services') }}"
                      id="servicesForm">
                    @csrf

                    {{-- Specializations --}}
                    <div class="prof-form-group">
                        <label class="prof-label"><i class="fas fa-heartbeat me-1"></i> Specializations</label>
                        <div class="tag-wrap" id="specTagWrap"
                             onclick="document.getElementById('specInput').focus()">
                            @foreach($specializations as $spec)
                                <span class="tag-item">
                                    {{ $spec }}
                                    <button type="button" onclick="removeTag(this)">×</button>
                                    <input type="hidden" name="specializations[]" value="{{ $spec }}">
                                </span>
                            @endforeach
                            <input type="text" id="specInput" class="tag-input-field"
                                   placeholder="Type & press Enter...">
                        </div>
                        <small style="font-size:.7rem;color:var(--text-muted);display:block;margin-top:.3rem;">
                            e.g. Cardiology, Pediatrics, General Medicine
                        </small>
                    </div>

                    {{-- Facilities --}}
                    <div class="prof-form-group">
                        <label class="prof-label"><i class="fas fa-hospital me-1"></i> Facilities</label>
                        <div class="tag-wrap" id="facTagWrap"
                             onclick="document.getElementById('facInput').focus()">
                            @foreach($facilities as $fac)
                                <span class="tag-item">
                                    {{ $fac }}
                                    <button type="button" onclick="removeTag(this)">×</button>
                                    <input type="hidden" name="facilities[]" value="{{ $fac }}">
                                </span>
                            @endforeach
                            <input type="text" id="facInput" class="tag-input-field"
                                   placeholder="Type & press Enter...">
                        </div>
                        <small style="font-size:.7rem;color:var(--text-muted);display:block;margin-top:.3rem;">
                            e.g. X-Ray, Pharmacy, Laboratory, ICU, Parking
                        </small>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary">
                            <i class="fas fa-save"></i> Save Services
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Location ══ --}}
    <div id="tab-location" class="tab-panel" style="display:none;">
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-map-marker-alt" style="color:var(--mc-primary);"></i> Location & Map</h6>
            </div>
            <div class="prof-card-body">
                <form method="POST" action="{{ route('medical_centre.profile.update_location') }}">
                    @csrf

                    <div id="profileMap"></div>
                    <small style="font-size:.7rem;color:var(--text-muted);display:block;margin-bottom:.85rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        Click map or drag the marker to set location.
                    </small>

                    <div class="prof-grid-2">
                        <div class="prof-form-group">
                            <label class="prof-label">Latitude</label>
                            <input type="text" name="latitude" id="latInput"
                                   class="prof-input"
                                   value="{{ old('latitude', $latitude) }}"
                                   placeholder="6.9271">
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Longitude</label>
                            <input type="text" name="longitude" id="lngInput"
                                   class="prof-input"
                                   value="{{ old('longitude', $longitude) }}"
                                   placeholder="79.8612">
                        </div>
                    </div>

                    <div class="prof-form-group">
                        <label class="prof-label">Street Address</label>
                        <input type="text" name="address" class="prof-input"
                               value="{{ old('address', $address) }}"
                               placeholder="Street address">
                    </div>

                    <div class="prof-grid-3">
                        <div class="prof-form-group">
                            <label class="prof-label">City</label>
                            <input type="text" name="city" class="prof-input"
                                   value="{{ old('city', $city) }}">
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Province</label>
                            <select name="province" class="prof-select">
                                <option value="">Select</option>
                                @foreach(['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'] as $prov)
                                    <option value="{{ $prov }}"
                                        {{ old('province', $province) === $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="prof-form-group">
                            <label class="prof-label">Postal Code</label>
                            <input type="text" name="postal_code" class="prof-input"
                                   value="{{ old('postal_code', $postal_code) }}">
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary">
                            <i class="fas fa-save"></i> Save Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Photo ══ --}}
    <div id="tab-photo" class="tab-panel" style="display:none;">
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-camera" style="color:var(--mc-primary);"></i> Profile Photo</h6>
            </div>
            <div class="prof-card-body">

                @if($mc->profile_image)
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;padding:1rem;background:#f8fbff;border-radius:10px;border:1.5px solid var(--border);">
                    <img src="{{ asset('storage/' . $mc->profile_image) }}"
                         style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:2px solid var(--border);">
                    <div>
                        <p style="font-size:.82rem;font-weight:700;color:var(--text-dark);margin:0 0 .5rem;">Current Photo</p>
                        <form method="POST"
                              action="{{ route('medical_centre.profile.delete_photo') }}"
                              onsubmit="return confirm('Remove profile photo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="prof-btn prof-btn-danger" style="font-size:.75rem;padding:.38rem .85rem;">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <form method="POST"
                      action="{{ route('medical_centre.profile.update_photo') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @error('profile_image')
                    <div style="font-size:.75rem;color:#e74c3c;font-weight:600;margin-bottom:.75rem;">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror

                    <div class="upload-area"
                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                         ondragleave="this.classList.remove('drag-over')"
                         ondrop="handleDrop(event,'photoFileInput')">
                        <input type="file" name="profile_image" id="photoFileInput"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               onchange="previewPhoto(this)">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click or drag & drop to upload</p>
                        <small>JPEG, PNG, WebP — Max 5MB</small>
                    </div>

                    <div id="photoPreviewWrap" style="display:none;margin-top:.85rem;text-align:center;">
                        <img id="photoPreviewImg" src="" alt="Preview"
                             style="width:90px;height:90px;border-radius:10px;object-fit:cover;border:2px solid var(--border);">
                        <p id="photoFileName" style="font-size:.72rem;color:var(--text-muted);font-weight:600;margin:.35rem 0 0;"></p>
                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:.85rem;">
                        <button type="submit" class="prof-btn prof-btn-primary"
                                id="photoUploadBtn" disabled>
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Document ══ --}}
    <div id="tab-document" class="tab-panel" style="display:none;">
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-file-alt" style="color:var(--mc-primary);"></i> Registration Document</h6>
            </div>
            <div class="prof-card-body">

                @if($mc->document_path)
                <div class="doc-existing">
                    <i class="fas fa-file-pdf"></i>
                    <span>{{ basename($mc->document_path) }}</span>
                    <a href="{{ asset('storage/' . $mc->document_path) }}"
                       target="_blank"
                       style="font-size:.75rem;font-weight:700;color:var(--mc-primary);text-decoration:none;flex-shrink:0;">
                        <i class="fas fa-eye me-1"></i>View
                    </a>
                </div>
                @else
                <div style="background:#fff3cd;border-radius:9px;padding:.75rem 1rem;font-size:.8rem;color:#92400e;font-weight:600;margin-bottom:.85rem;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No registration document uploaded yet.
                </div>
                @endif

                <form method="POST"
                      action="{{ route('medical_centre.profile.upload_document') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @error('document')
                    <div style="font-size:.75rem;color:#e74c3c;font-weight:600;margin-bottom:.75rem;">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror

                    <label class="prof-label">
                        {{ $mc->document_path ? 'Replace Document' : 'Upload Document' }}
                    </label>
                    <div class="upload-area" style="margin-bottom:.75rem;">
                        <input type="file" name="document" id="docFileInput"
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="showDocName(this)">
                        <i class="fas fa-file-upload"></i>
                        <p>Click to select document</p>
                        <small>PDF, JPG, PNG — Max 10MB</small>
                    </div>

                    <div id="docFileNameWrap" style="display:none;font-size:.78rem;font-weight:600;color:var(--mc-primary);margin-bottom:.75rem;">
                        <i class="fas fa-paperclip me-1"></i><span id="docFileName"></span>
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary"
                                id="docUploadBtn" disabled>
                            <i class="fas fa-upload"></i>
                            {{ $mc->document_path ? 'Replace Document' : 'Upload Document' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ TAB: Security ══ --}}
    <div id="tab-security" class="tab-panel" style="display:none;">

        {{-- Change Email --}}
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-envelope" style="color:var(--mc-primary);"></i> Account Email</h6>
            </div>
            <div class="prof-card-body">
                <form method="POST" action="{{ route('medical_centre.profile.update_email') }}">
                    @csrf
                    <div style="background:#f8fbff;border-radius:9px;padding:.65rem .9rem;margin-bottom:1rem;font-size:.8rem;color:var(--text-muted);font-weight:600;">
                        <i class="fas fa-info-circle me-1" style="color:var(--mc-primary);"></i>
                        Current: <strong style="color:var(--text-dark);">{{ $user->email }}</strong>
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-label">New Email <span style="color:#e74c3c">*</span></label>
                        <input type="email" name="email"
                               class="prof-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}" placeholder="new@example.com">
                        @error('email')<div class="prof-form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-label">Confirm with Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="emailPw"
                                   class="prof-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Current password">
                            <button type="button" onclick="togglePw('emailPw','emailPwEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="emailPwEye"></i>
                            </button>
                        </div>
                        @error('password')<div class="prof-form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary">
                            <i class="fas fa-envelope"></i> Update Email
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="prof-card">
            <div class="prof-card-head">
                <h6><i class="fas fa-lock" style="color:var(--mc-primary);"></i> Change Password</h6>
            </div>
            <div class="prof-card-body">
                <form method="POST" action="{{ route('medical_centre.profile.change_password') }}">
                    @csrf
                    <div class="prof-form-group">
                        <label class="prof-label">Current Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="current_password" id="currPw"
                                   class="prof-input {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                   placeholder="Current password">
                            <button type="button" onclick="togglePw('currPw','currPwEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="currPwEye"></i>
                            </button>
                        </div>
                        @error('current_password')<div class="prof-form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-label">New Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="newPw"
                                   class="prof-input"
                                   placeholder="Min. 8 characters"
                                   oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePw('newPw','newPwEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="newPwEye"></i>
                            </button>
                        </div>
                        <div style="margin-top:.35rem;height:4px;border-radius:99px;background:#f0f0f0;overflow:hidden;">
                            <div id="pwStrBar" style="height:100%;width:0;border-radius:99px;transition:width .3s,background .3s;"></div>
                        </div>
                        <div id="pwStrLabel" style="font-size:.68rem;font-weight:700;margin-top:.2rem;min-height:1em;"></div>
                    </div>
                    <div class="prof-form-group">
                        <label class="prof-label">Confirm New Password <span style="color:#e74c3c">*</span></label>
                        <input type="password" name="password_confirmation"
                               class="prof-input" placeholder="Repeat new password">
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="prof-btn prof-btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    {{-- END Security --}}

</div>
{{-- END MAIN --}}

</div>
{{-- END PROF GRID --}}

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ── Tab Switch ──────────────────────────────────────
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.prof-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('active');
    if (name === 'location') setTimeout(initMap, 50);
}

// ── Leaflet Map ─────────────────────────────────────
let map = null, marker = null;
function initMap() {
    if (map) { map.invalidateSize(); return; }
    const lat = parseFloat(document.getElementById('latInput').value) || 6.9271;
    const lng = parseFloat(document.getElementById('lngInput').value) || 79.8612;
    map = L.map('profileMap').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    marker.on('dragend', e => {
        document.getElementById('latInput').value = e.target.getLatLng().lat.toFixed(7);
        document.getElementById('lngInput').value = e.target.getLatLng().lng.toFixed(7);
    });
    map.on('click', e => {
        marker.setLatLng(e.latlng);
        document.getElementById('latInput').value = e.latlng.lat.toFixed(7);
        document.getElementById('lngInput').value = e.latlng.lng.toFixed(7);
    });
}

// ── Tag Input ───────────────────────────────────────
function addTag(input, wrapId, nameAttr) {
    const val = input.value.trim();
    if (!val) return;
    const wrap = document.getElementById(wrapId);
    const span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML =
        val +
        `<button type="button" onclick="removeTag(this)">×</button>` +
        `<input type="hidden" name="${nameAttr}[]" value="${val}">`;
    wrap.insertBefore(span, input);
    input.value = '';
}
function removeTag(btn) {
    btn.closest('.tag-item').remove();
}
document.getElementById('specInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); addTag(this,'specTagWrap','specializations'); }
});
document.getElementById('facInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); addTag(this,'facTagWrap','facilities'); }
});

// ── Photo Preview ───────────────────────────────────
function previewPhoto(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreviewImg').src = e.target.result;
        document.getElementById('photoPreviewWrap').style.display = 'block';
        document.getElementById('photoFileName').textContent = file.name;
        document.getElementById('photoUploadBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}
function handleDrop(e, inputId) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById(inputId);
    input.files = dt.files;
    if (inputId === 'photoFileInput') previewPhoto(input);
}

// ── Document Name ───────────────────────────────────
function showDocName(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('docFileName').textContent = file.name;
    document.getElementById('docFileNameWrap').style.display = 'block';
    document.getElementById('docUploadBtn').disabled = false;
}

// ── Password Toggle ─────────────────────────────────
function togglePw(id, eyeId) {
    const inp = document.getElementById(id);
    const eye = document.getElementById(eyeId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// ── Password Strength ───────────────────────────────
function checkStrength(val) {
    let s = 0;
    if (val.length >= 8)           s++;
    if (/[A-Z]/.test(val))         s++;
    if (/[0-9]/.test(val))         s++;
    if (/[^A-Za-z0-9]/.test(val))  s++;
    const lvl = [
        {w:'0',   c:'#e74c3c', t:''},
        {w:'25%', c:'#e74c3c', t:'Weak'},
        {w:'50%', c:'#f59e0b', t:'Fair'},
        {w:'75%', c:'#3b82f6', t:'Good'},
        {w:'100%',c:'#059669', t:'Strong'},
    ][s];
    document.getElementById('pwStrBar').style.cssText = `width:${lvl.w};background:${lvl.c}`;
    const lbl = document.getElementById('pwStrLabel');
    lbl.textContent = lvl.t;
    lbl.style.color = lvl.c;
}

// ── Auto open tab on validation error ──────────────
@if($errors->hasAny(['name','phone','address','city','province','description','operatinghours']))
    switchTab('info', document.querySelectorAll('.prof-tab')[0]);
@elseif($errors->hasAny(['specializations','facilities']))
    switchTab('services', document.querySelectorAll('.prof-tab')[1]);
@elseif($errors->hasAny(['latitude','longitude']))
    switchTab('location', document.querySelectorAll('.prof-tab')[2]);
@elseif($errors->has('profile_image'))
    switchTab('photo', document.querySelectorAll('.prof-tab')[3]);
@elseif($errors->has('document'))
    switchTab('document', document.querySelectorAll('.prof-tab')[4]);
@elseif($errors->hasAny(['current_password','password','email']))
    switchTab('security', document.querySelectorAll('.prof-tab')[5]);
@endif

// ── Submit loading ──────────────────────────────────
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('[type=submit]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
    });
});
</script>
@endsection
