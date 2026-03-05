@extends('pharmacy.layouts.master')

@section('title', 'Create Pharmacy Profile')
@section('page-title', 'Create Profile')
@section('page-subtitle', 'Setup your pharmacy profile to get started')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-plus-circle me-2 text-primary"></i>Create Pharmacy Profile
        </h6>
        <small class="text-muted">Fill in all required details. Your profile will be reviewed by admin before activation.</small>
    </div>
</div>

{{-- Info Banner --}}
<div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-3" style="font-size:12px">
    <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
    <div>
        <strong>Note:</strong> After submitting, your profile will be reviewed by the admin. You will receive a notification once approved.
        Fields marked with <span class="text-danger">*</span> are required.
    </div>
</div>

<form action="{{ route('pharmacy.profile.store') }}" method="POST" enctype="multipart/form-data" id="createProfileForm">
    @csrf

    <div class="row g-3">

        {{-- ── Left Column ── --}}
        <div class="col-lg-4">

            {{-- Profile Image --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-camera me-2 text-primary"></i>Profile Photo</h6>
                </div>
                <div class="card-body text-center py-3">
                    <div class="mb-3">
                        <img id="profilePreview"
                             src="{{ asset('images/default-doctor.png') }}"
                             class="rounded-circle border border-2 border-primary"
                             style="width:90px;height:90px;object-fit:cover"
                             onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                    </div>
                    <label for="profileImageInput"
                           class="btn btn-outline-primary btn-sm w-100" style="font-size:12px;cursor:pointer">
                        <i class="fas fa-upload me-1"></i>Upload Photo
                        <input type="file" id="profileImageInput" name="profile_image"
                               accept="image/jpeg,image/png,image/jpg" class="d-none"
                               onchange="previewImage(this)">
                    </label>
                    <small class="text-muted d-block mt-1" style="font-size:10px">JPEG, PNG, JPG — max 2MB (optional)</small>
                    @error('profile_image')
                        <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Registration Document --}}
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-file-alt me-2 text-danger"></i>Registration Document <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div class="border border-2 border-dashed rounded p-3 text-center" id="docDropZone"
                         style="border-color:#dee2e6!important;cursor:pointer"
                         onclick="document.getElementById('documentInput').click()">
                        <i class="fas fa-file-upload fa-2x text-muted mb-2 d-block"></i>
                        <p class="mb-0 text-muted" style="font-size:11px">Click to upload document</p>
                        <small class="text-muted" style="font-size:10px">PDF, JPG, JPEG, PNG — max 5MB</small>
                        <div id="docFileName" class="mt-2 text-primary fw-semibold" style="font-size:11px"></div>
                    </div>
                    <input type="file" id="documentInput" name="document_path"
                           accept=".pdf,.jpg,.jpeg,.png" class="d-none"
                           onchange="showFileName(this,'docFileName')">
                    @error('document_path')
                        <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                    @enderror
                    <small class="text-muted mt-2 d-block" style="font-size:10px">
                        <i class="fas fa-info-circle me-1"></i>Upload pharmacy registration certificate or license
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
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="e.g. City Care Pharmacy" style="font-size:12px">
                            @error('name')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Registration Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="registration_number" value="{{ old('registration_number') }}"
                                   class="form-control form-control-sm @error('registration_number') is-invalid @enderror"
                                   placeholder="e.g. PH-12345" style="font-size:12px">
                            @error('registration_number')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Pharmacist Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pharmacist_name" value="{{ old('pharmacist_name') }}"
                                   class="form-control form-control-sm @error('pharmacist_name') is-invalid @enderror"
                                   placeholder="Full name of pharmacist" style="font-size:12px">
                            @error('pharmacist_name')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Pharmacist License No. <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pharmacist_license" value="{{ old('pharmacist_license') }}"
                                   class="form-control form-control-sm @error('pharmacist_license') is-invalid @enderror"
                                   placeholder="e.g. SPC-78901" style="font-size:12px">
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
                                <input type="text" name="phone" value="{{ old('phone') }}"
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
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       placeholder="pharmacy@example.com" style="font-size:12px">
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
                                      placeholder="Street address" style="font-size:12px">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="form-control form-control-sm @error('city') is-invalid @enderror"
                                   placeholder="Colombo" style="font-size:12px">
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
                                    <option value="{{ strtolower(str_replace(' ','-',$prov)) }}"
                                        {{ old('province') === strtolower(str_replace(' ','-',$prov)) ? 'selected' : '' }}>
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
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
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
                            <input type="text" name="operating_hours" value="{{ old('operating_hours') }}"
                                   class="form-control form-control-sm @error('operating_hours') is-invalid @enderror"
                                   placeholder="e.g. Monday-Friday 08:00-20:00, Saturday 08:00-18:00"
                                   style="font-size:12px">
                            <small class="text-muted" style="font-size:10px">
                                Separate multiple schedules with commas
                            </small>
                            @error('operating_hours')
                                <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">Home Delivery</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="delivery_available"
                                       id="deliverySwitch" value="1" style="width:40px;height:20px"
                                       {{ old('delivery_available') ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="deliverySwitch"
                                       style="font-size:12px">Enable Delivery</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="card">
                <div class="card-body d-flex justify-content-end gap-2">
                    <a href="{{ route('pharmacy.dashboard') }}"
                       class="btn btn-outline-secondary btn-sm" style="font-size:12px">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm" style="font-size:12px">
                        <i class="fas fa-paper-plane me-1"></i>Submit for Approval
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showFileName(input, targetId) {
        const el = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            el.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>' + input.files[0].name;
            document.getElementById('docDropZone').style.borderColor = '#0d6efd';
        }
    }
</script>
@endpush
