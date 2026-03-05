@extends('pharmacy.layouts.master')

@section('title', 'Edit Pharmacy Profile')
@section('page-title', 'Edit Profile')
@section('page-subtitle', 'Update your pharmacy information')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-edit me-2 text-primary"></i>Edit Pharmacy Profile
        </h6>
        <small class="text-muted">Update your pharmacy details below</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.profile.index') }}" class="btn btn-outline-secondary btn-sm" style="font-size:12px">
            <i class="fas fa-arrow-left me-1"></i>Back to Profile
        </a>
    </div>
</div>

<form action="{{ route('pharmacy.profile.update') }}" method="POST" id="editProfileForm">
    @csrf
    @method('PUT')

    <div class="row g-3">

        {{-- ── Left Column ── --}}
        <div class="col-lg-4">

            {{-- Profile Image Card --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-camera me-2 text-primary"></i>Profile Photo</h6>
                </div>
                <div class="card-body text-center py-3">
                    @php
                        $profileImg = isset($pharmacy) && $pharmacy->profile_image
                            ? asset('storage/' . $pharmacy->profile_image)
                            : asset('images/default-doctor.png');
                    @endphp
                    <img id="profilePreview" src="{{ $profileImg }}"
                         class="rounded-circle border border-2 border-primary mb-3"
                         style="width:90px;height:90px;object-fit:cover"
                         onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                    <p class="text-muted mb-2" style="font-size:11px">
                        Change photo separately using the button below
                    </p>
                    <a href="{{ route('pharmacy.profile.index') }}"
                       class="btn btn-outline-primary btn-sm w-100 mb-2" style="font-size:11px">
                        <i class="fas fa-camera me-1"></i>Change Photo on Profile Page
                    </a>
                    <div class="border-top pt-2">
                        <div class="d-flex gap-1 align-items-center justify-content-center">
                            @php
                                $statusColors = ['approved'=>'success','pending'=>'warning','rejected'=>'danger','suspended'=>'dark'];
                                $sc = $statusColors[$pharmacy->status ?? 'pending'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $sc }}" style="font-size:11px">
                                {{ ucfirst($pharmacy->status ?? 'pending') }}
                            </span>
                            <small class="text-muted" style="font-size:10px">Account Status</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Document Section --}}
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-file-alt me-2 text-secondary"></i>Registration Document</h6>
                </div>
                <div class="card-body">
                    @if(isset($pharmacy) && $pharmacy->document_path)
                        <div class="alert alert-light border py-2 mb-2" style="font-size:11px">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Document already uploaded
                        </div>
                        <a href="{{ asset('storage/' . $pharmacy->document_path) }}" target="_blank"
                           class="btn btn-outline-secondary btn-sm w-100 mb-2" style="font-size:11px">
                            <i class="fas fa-eye me-1"></i>View Current Document
                        </a>
                    @else
                        <div class="alert alert-warning py-2" style="font-size:11px">
                            <i class="fas fa-exclamation-triangle me-1"></i>No document uploaded
                        </div>
                    @endif
                    <small class="text-muted" style="font-size:10px">
                        <i class="fas fa-info-circle me-1"></i>
                        To replace document, contact admin support.
                    </small>
                </div>
            </div>

        </div>

        {{-- ── Right Column ── --}}
        <div class="col-lg-8">

            {{-- Pharmacy Basic Info --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-building me-2 text-primary"></i>Pharmacy Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Pharmacy Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   value="{{ old('name', $pharmacy->name ?? '') }}"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="Pharmacy name" style="font-size:12px">
                            @error('name')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">Registration No.</label>
                            <input type="text"
                                   value="{{ $pharmacy->registration_number ?? '' }}"
                                   class="form-control form-control-sm bg-light"
                                   style="font-size:12px" readonly
                                   title="Registration number cannot be changed">
                            <small class="text-muted" style="font-size:10px">Cannot be changed</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Pharmacist Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pharmacist_name"
                                   value="{{ old('pharmacist_name', $pharmacy->pharmacist_name ?? '') }}"
                                   class="form-control form-control-sm @error('pharmacist_name') is-invalid @enderror"
                                   placeholder="Pharmacist full name" style="font-size:12px">
                            @error('pharmacist_name')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Pharmacist License <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pharmacist_license"
                                   value="{{ old('pharmacist_license', $pharmacy->pharmacist_license ?? '') }}"
                                   class="form-control form-control-sm @error('pharmacist_license') is-invalid @enderror"
                                   placeholder="License number" style="font-size:12px">
                            @error('pharmacist_license')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Phone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light" style="font-size:11px">
                                    <i class="fas fa-phone text-success"></i>
                                </span>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $pharmacy->phone ?? '') }}"
                                       class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                       placeholder="07X XXXXXXX" style="font-size:12px">
                            </div>
                            @error('phone')
                                <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light" style="font-size:11px">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" name="email"
                                       value="{{ old('email', $pharmacy->email ?? '') }}"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       placeholder="email@example.com" style="font-size:12px">
                            </div>
                            @error('email')
                                <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-map-marker-alt me-2 text-danger"></i>Location</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Address <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" rows="2"
                                      class="form-control form-control-sm @error('address') is-invalid @enderror"
                                      placeholder="Street address" style="font-size:12px">{{ old('address', $pharmacy->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city"
                                   value="{{ old('city', $pharmacy->city ?? '') }}"
                                   class="form-control form-control-sm @error('city') is-invalid @enderror"
                                   placeholder="City" style="font-size:12px">
                            @error('city')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Province <span class="text-danger">*</span>
                            </label>
                            <select name="province"
                                    class="form-select form-select-sm @error('province') is-invalid @enderror"
                                    style="font-size:12px">
                                <option value="">Select Province</option>
                                @foreach(['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'] as $prov)
                                    @php $val = strtolower(str_replace(' ','-',$prov)); @endphp
                                    <option value="{{ $val }}"
                                        {{ old('province', $pharmacy->province ?? '') === $val ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" style="font-size:12px;font-weight:600">Postal Code</label>
                            <input type="text" name="postal_code"
                                   value="{{ old('postal_code', $pharmacy->postal_code ?? '') }}"
                                   class="form-control form-control-sm @error('postal_code') is-invalid @enderror"
                                   placeholder="00100" style="font-size:12px">
                            @error('postal_code')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Operating Hours & Delivery --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-clock me-2 text-warning"></i>Operating Hours & Delivery</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" style="font-size:12px;font-weight:600">Operating Hours</label>
                            <input type="text" name="operating_hours"
                                   value="{{ old('operating_hours', $pharmacy->operating_hours ?? '') }}"
                                   class="form-control form-control-sm @error('operating_hours') is-invalid @enderror"
                                   placeholder="e.g. Mon-Fri 08:00-20:00, Sat 08:00-18:00"
                                   style="font-size:12px">
                            <small class="text-muted" style="font-size:10px">Separate multiple schedules with commas</small>
                            @error('operating_hours')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">Home Delivery</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="delivery_available"
                                       id="deliverySwitch" value="1" style="width:40px;height:20px"
                                       {{ old('delivery_available', $pharmacy->delivery_available ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="deliverySwitch"
                                       style="font-size:12px">Enable Delivery</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <small class="text-muted" style="font-size:11px">
                        <i class="fas fa-info-circle me-1 text-primary"></i>
                        Last updated: {{ isset($pharmacy->updated_at) ? $pharmacy->updated_at->diffForHumans() : '—' }}
                    </small>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pharmacy.profile.index') }}"
                           class="btn btn-outline-secondary btn-sm" style="font-size:12px">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm" style="font-size:12px"
                                id="saveBtn">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Confirm before leaving with unsaved changes
    let formChanged = false;
    document.getElementById('editProfileForm').addEventListener('change', () => formChanged = true);
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    // Clear flag on submit
    document.getElementById('editProfileForm').addEventListener('submit', function() {
        formChanged = false;
        document.getElementById('saveBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        document.getElementById('saveBtn').disabled = true;
    });
</script>
@endpush
