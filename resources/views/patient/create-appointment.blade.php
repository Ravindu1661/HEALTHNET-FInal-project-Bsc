{{-- Include Header --}}
@include('partials.header')

<style>
/* ══════════════════════════════════════
   CREATE APPOINTMENT — Doctor Profile Style Match
══════════════════════════════════════ */

/* Step Progress */
.step-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
.step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    background: white;
    color: #aaa;
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 1;
}
.step-circle.active {
    background: var(--accent-color, #42a649);
    border-color: var(--accent-color, #42a649);
    color: white;
    box-shadow: 0 4px 12px rgba(66,166,73,0.35);
}
.step-circle.completed {
    background: var(--accent-color, #42a649);
    border-color: var(--accent-color, #42a649);
    color: white;
}
.step-label {
    font-size: 0.72rem;
    font-weight: 600;
    margin-top: 0.4rem;
    color: #aaa;
    white-space: nowrap;
}
.step-label.active {
    color: var(--accent-color, #42a649);
}
.step-connector {
    width: 50px;
    height: 2px;
    background: #e0e0e0;
    margin: 0 0.3rem;
    margin-bottom: 22px;
    transition: background 0.3s ease;
}
.step-connector.completed {
    background: var(--accent-color, #42a649);
}
@media (max-width: 576px) {
    .step-connector { width: 20px; }
    .step-label { display: none; }
}

/* Page Header */
.appt-page-header {
    background: linear-gradient(135deg, var(--primary-color, #1a5276) 0%, var(--secondary-color, #2e86c1) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.appt-page-header::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}

/* Main Container */
.appt-main {
    background: #f4f6f9;
    padding: 2rem 0 4rem;
    min-height: 600px;
}

/* Form Card */
.appt-form-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-top: -1.5rem;
}

/* Doctor Summary — matches profile page style */
.doctor-summary-box {
    background: linear-gradient(135deg, rgba(66,166,73,0.05) 0%, rgba(66,166,73,0.1) 100%);
    border: 2px solid rgba(66,166,73,0.2);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 0;
}
.doctor-summary-inner {
    display: flex;
    gap: 1.2rem;
    align-items: center;
}
.doctor-summary-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--accent-color, #42a649);
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.doctor-summary-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.doctor-summary-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    margin-bottom: 0.2rem;
}
.doctor-summary-spec {
    color: var(--accent-color, #42a649);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.6rem;
}
.doctor-summary-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    font-size: 0.82rem;
    color: #666;
}
.doctor-summary-meta span {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.doctor-summary-meta i {
    color: var(--accent-color, #42a649);
}

/* Section Title — matches profile page */
.form-section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid rgba(66,166,73,0.2);
}
.form-section-title i {
    color: var(--accent-color, #42a649);
}

/* Workplace Options — matches workplace-card style */
.workplace-option-label {
    display: block;
    cursor: pointer;
}
.workplace-option-inner {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: start;
    gap: 0.8rem;
}
.workplace-option-inner:hover {
    background: white;
    border-color: var(--accent-color, #42a649);
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}
.workplace-option-label input[type="radio"]:checked + .workplace-option-inner {
    background: rgba(66,166,73,0.06);
    border-color: var(--accent-color, #42a649);
    box-shadow: 0 4px 12px rgba(66,166,73,0.15);
}
.workplace-option-label input[type="radio"] {
    display: none;
}
.wp-radio-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
    transition: all 0.2s ease;
}
.workplace-option-label input[type="radio"]:checked ~ * .wp-radio-dot,
.workplace-option-label input[type="radio"]:checked + .workplace-option-inner .wp-radio-dot {
    border-color: var(--accent-color, #42a649);
    background: var(--accent-color, #42a649);
}
.wp-name {
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}
.wp-type-badge {
    font-size: 0.72rem;
    padding: 0.2rem 0.6rem;
    background: var(--accent-color, #42a649);
    color: white;
    border-radius: 10px;
    display: inline-block;
    text-transform: capitalize;
    margin-bottom: 0.4rem;
}
.wp-meta {
    font-size: 0.82rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.2rem;
}
.wp-meta i {
    color: var(--accent-color, #42a649);
    width: 14px;
}

/* Form Controls */
.appt-label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--primary-color, #1a5276);
    margin-bottom: 0.45rem;
}
.appt-label .req {
    color: #dc3545;
}
.appt-input,
.appt-textarea {
    width: 100%;
    padding: 0.72rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
    color: #333;
}
.appt-input:focus,
.appt-textarea:focus {
    border-color: var(--accent-color, #42a649);
    outline: none;
    box-shadow: 0 0 0 3px rgba(66,166,73,0.1);
}
.appt-textarea {
    resize: vertical;
    min-height: 100px;
}

/* Appointment Summary Box */
.appt-summary-box {
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid var(--accent-color, #42a649);
    overflow: hidden;
}
.appt-summary-header {
    background: var(--primary-color, #1a5276);
    color: white;
    padding: 0.8rem 1.2rem;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.appt-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.65rem 1.2rem;
    border-bottom: 1px solid #e9ecef;
    font-size: 0.88rem;
}
.appt-summary-item:last-child { border-bottom: none; }
.appt-summary-label { color: #666; }
.appt-summary-value { font-weight: 600; color: var(--primary-color, #1a5276); }
.appt-summary-total {
    background: rgba(66,166,73,0.08);
    padding: 0.9rem 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid var(--accent-color, #42a649);
}
.appt-summary-total .label {
    font-weight: 700;
    font-size: 1rem;
    color: var(--primary-color, #1a5276);
}
.appt-summary-total .value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--accent-color, #42a649);
}

/* Buttons */
.btn-appt-submit {
    background: var(--accent-color, #42a649);
    color: white;
    border: none;
    padding: 0.9rem 2.5rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(66,166,73,0.3);
    text-decoration: none;
}
.btn-appt-submit:hover {
    background: var(--primary-color, #1a5276);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66,166,73,0.4);
}
.btn-appt-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.9rem 2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-appt-cancel:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
}
.btn-appt-nav {
    background: white;
    color: var(--primary-color, #1a5276);
    border: 2px solid #e9ecef;
    padding: 0.7rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-appt-nav:hover {
    border-color: var(--accent-color, #42a649);
    color: var(--accent-color, #42a649);
}
.btn-appt-next {
    background: var(--accent-color, #42a649);
    color: white;
    border: 2px solid var(--accent-color, #42a649);
    padding: 0.7rem 1.8rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-appt-next:hover {
    background: var(--primary-color, #1a5276);
    border-color: var(--primary-color, #1a5276);
    transform: translateY(-2px);
}

/* Form Step Sections */
.form-step {
    display: none;
    animation: fadeInStep 0.3s ease;
}
.form-step.active {
    display: block;
}
@keyframes fadeInStep {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Alert */
.appt-alert-warning {
    background: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
    padding: 1rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}
.appt-alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
    padding: 0.9rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1.2rem;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

/* Form actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid #e9ecef;
}

/* Responsive */
@media (max-width: 768px) {
    .doctor-summary-inner { flex-direction: column; align-items: center; text-align: center; }
    .doctor-summary-meta { justify-content: center; }
    .form-actions { flex-direction: column-reverse; }
    .btn-appt-submit,
    .btn-appt-cancel,
    .btn-appt-nav,
    .btn-appt-next { width: 100%; justify-content: center; }
    .appt-page-header { padding: 5rem 0 2.5rem; }
}
</style>

{{-- ══════════ PAGE HEADER ══════════ --}}
<section class="appt-page-header">
    <div class="container">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 style="font-size:2.2rem;font-weight:700;margin-bottom:0.5rem;">Book Appointment</h1>
                <p style="opacity:0.9;font-size:1rem;">Schedule your consultation with our medical professionals</p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════ MAIN CONTENT ══════════ --}}
<section class="appt-main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                {{-- Step Progress Bar --}}
                <div class="step-progress mb-0" style="border-radius:15px 15px 0 0;margin-top:-1.5rem;position:relative;z-index:2;">
                    {{-- Step 1 --}}
                    <div class="step-item">
                        <div class="step-circle active" id="circle-1">1</div>
                        <div class="step-label active" id="label-1">Doctor</div>
                    </div>
                    <div class="step-connector" id="connector-1"></div>

                    {{-- Step 2 --}}
                    <div class="step-item">
                        <div class="step-circle" id="circle-2">2</div>
                        <div class="step-label" id="label-2">Location</div>
                    </div>
                    <div class="step-connector" id="connector-2"></div>

                    {{-- Step 3 --}}
                    <div class="step-item">
                        <div class="step-circle" id="circle-3">3</div>
                        <div class="step-label" id="label-3">Date & Time</div>
                    </div>
                    <div class="step-connector" id="connector-3"></div>

                    {{-- Step 4 --}}
                    <div class="step-item">
                        <div class="step-circle" id="circle-4">4</div>
                        <div class="step-label" id="label-4">Details</div>
                    </div>
                    <div class="step-connector" id="connector-4"></div>

                    {{-- Step 5 --}}
                    <div class="step-item">
                        <div class="step-circle" id="circle-5">5</div>
                        <div class="step-label" id="label-5">Review</div>
                    </div>
                </div>

                {{-- Main Form Card --}}
                <div class="appt-form-card" style="border-radius: 0 0 15px 15px; margin-top:0;">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                    <div class="appt-alert-warning">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                        <ul style="margin:0.5rem 0 0 1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST"
                          action="{{ route('patient.appointments.store', ['doctorId' => $doctor->id]) }}"
                          id="appointmentForm">
                        @csrf

                        {{-- ═════ STEP 1 — Doctor Info ═════ --}}
                        <div class="form-step active" id="step-1">

                            <h3 class="form-section-title">
                                <i class="fas fa-user-md"></i> Your Doctor
                            </h3>

                            @isset($doctor)
                            <div class="doctor-summary-box">
                                <div class="doctor-summary-inner">
                                    <div class="doctor-summary-avatar">
                                        @php
                                            $profileImage = $doctor->profile_image
                                                ? asset('storage/' . $doctor->profile_image)
                                                : asset('images/default-avatar.png');
                                        @endphp
                                        <img src="{{ $profileImage }}"
                                             alt="Dr. {{ $doctor->first_name }}"
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div style="flex:1;">
                                        <div class="doctor-summary-name">
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </div>
                                        <div class="doctor-summary-spec">
                                            {{ $doctor->specialization ?? 'General Practitioner' }}
                                        </div>
                                        <div class="doctor-summary-meta">
                                            @if($doctor->experience_years)
                                            <span>
                                                <i class="fas fa-briefcase-medical"></i>
                                                {{ $doctor->experience_years }} yrs experience
                                            </span>
                                            @endif
                                            <span>
                                                <i class="fas fa-money-bill-wave"></i>
                                                Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}
                                            </span>
                                            @if($doctor->status === 'approved')
                                            <span>
                                                <i class="fas fa-check-circle"></i>
                                                Verified Doctor
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endisset

                            <div class="form-actions">
                                <a href="{{ url()->previous() }}" class="btn-appt-cancel">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="button" class="btn-appt-next" onclick="goToStep(2)">
                                    Next <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═════ STEP 2 — Location / Workplace ═════ --}}
                        <div class="form-step" id="step-2">

                            <h3 class="form-section-title">
                                <i class="fas fa-hospital"></i> Select Location
                            </h3>

                            @if(isset($workplaces) && $workplaces->count() > 0)
                            <div class="row g-3">
                                @foreach($workplaces as $workplace)
                                @php
                                    $wpName    = 'Not Available';
                                    $wpAddress = 'Address not available';
                                    $wpCity    = 'N/A';
                                    $wpType    = $workplace->workplace_type;

                                    if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                                        $wpName    = $workplace->hospital->name;
                                        $wpAddress = $workplace->hospital->address ?? 'N/A';
                                        $wpCity    = $workplace->hospital->city ?? 'N/A';
                                    } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                                        $wpName    = $workplace->medicalCentre->name;
                                        $wpAddress = $workplace->medicalCentre->address ?? 'N/A';
                                        $wpCity    = $workplace->medicalCentre->city ?? 'N/A';
                                    }
                                @endphp
                                <div class="col-md-6">
                                    <label class="workplace-option-label w-100">
                                        <input type="radio"
                                               name="workplace_id"
                                               value="{{ $workplace->id }}"
                                               required
                                               {{ old('workplace_id') == $workplace->id ? 'checked' : '' }}>
                                        <div class="workplace-option-inner">
                                            <div class="wp-radio-dot" style="flex-shrink:0;margin-top:3px;"></div>
                                            <div style="flex:1;">
                                                <div class="wp-name">{{ $wpName }}</div>
                                                <div>
                                                    <span class="wp-type-badge">
                                                        {{ str_replace('_', ' ', $wpType) }}
                                                    </span>
                                                </div>
                                                <div class="wp-meta">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ Str::limit($wpAddress, 50) }}
                                                </div>
                                                <div class="wp-meta">
                                                    <i class="fas fa-city"></i>
                                                    {{ $wpCity }}
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4" style="color:#888;">
                                <i class="fas fa-hospital fa-3x mb-3 d-block" style="color:#ddd;"></i>
                                <p>No approved workplaces available for this doctor.</p>
                            </div>
                            @endif

                            @error('workplace_id')
                            <p class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                            @enderror

                            <div class="form-actions">
                                <button type="button" class="btn-appt-nav" onclick="goToStep(1)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn-appt-next" onclick="validateStep2()">
                                    Next <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═════ STEP 3 — Date & Time ═════ --}}
                        <div class="form-step" id="step-3">

                            <h3 class="form-section-title">
                                <i class="fas fa-calendar-alt"></i> Select Date & Time
                            </h3>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="appt-label">
                                            Appointment Date <span class="req">*</span>
                                        </label>
                                        <input type="date"
                                               class="appt-input @error('appointment_date') is-invalid @enderror"
                                               name="appointment_date"
                                               id="appt-date"
                                               required
                                               min="{{ date('Y-m-d') }}"
                                               value="{{ old('appointment_date') }}">
                                        @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="appt-label">
                                            Appointment Time <span class="req">*</span>
                                        </label>
                                        <input type="time"
                                               class="appt-input @error('appointment_time') is-invalid @enderror"
                                               name="appointment_time"
                                               id="appt-time"
                                               required
                                               value="{{ old('appointment_time') }}">
                                        @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-appt-nav" onclick="goToStep(2)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn-appt-next" onclick="validateStep3()">
                                    Next <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═════ STEP 4 — Details ═════ --}}
                        <div class="form-step" id="step-4">

                            <h3 class="form-section-title">
                                <i class="fas fa-file-medical"></i> Appointment Details
                            </h3>

                            <div class="mb-3">
                                <label class="appt-label">
                                    Reason for Visit <span class="req">*</span>
                                </label>
                                <textarea class="appt-textarea @error('reason') is-invalid @enderror"
                                          name="reason"
                                          id="appt-reason"
                                          rows="4"
                                          required
                                          placeholder="Please describe your symptoms or reason for consultation...">{{ old('reason') }}</textarea>
                                @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="appt-label">
                                    Additional Notes
                                    <span style="font-weight:400;color:#888;">(Optional)</span>
                                </label>
                                <textarea class="appt-textarea"
                                          name="notes"
                                          rows="3"
                                          placeholder="Any additional information you'd like the doctor to know...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-appt-nav" onclick="goToStep(3)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn-appt-next" onclick="validateStep4()">
                                    Review <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═════ STEP 5 — Review & Confirm ═════ --}}
                        <div class="form-step" id="step-5">

                            <h3 class="form-section-title">
                                <i class="fas fa-check-circle"></i> Review & Confirm
                            </h3>

                            {{-- Doctor Summary Repeat --}}
                            @isset($doctor)
                            <div class="doctor-summary-box mb-3">
                                <div class="doctor-summary-inner">
                                    <div class="doctor-summary-avatar">
                                        <img src="{{ $profileImage ?? asset('images/default-avatar.png') }}"
                                             alt="Dr."
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div>
                                        <div class="doctor-summary-name">
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </div>
                                        <div class="doctor-summary-spec">
                                            {{ $doctor->specialization ?? 'General Practitioner' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endisset

                            {{-- Summary Table --}}
                            <div class="appt-summary-box mb-3">
                                <div class="appt-summary-header">
                                    <i class="fas fa-receipt"></i> Appointment Summary
                                </div>
                                <div class="appt-summary-item">
                                    <span class="appt-summary-label">Date</span>
                                    <span class="appt-summary-value" id="summary-date">—</span>
                                </div>
                                <div class="appt-summary-item">
                                    <span class="appt-summary-label">Time</span>
                                    <span class="appt-summary-value" id="summary-time">—</span>
                                </div>
                                <div class="appt-summary-item">
                                    <span class="appt-summary-label">Reason</span>
                                    <span class="appt-summary-value" id="summary-reason" style="max-width:65%;text-align:right;">—</span>
                                </div>
                                <div class="appt-summary-total">
                                    <span class="label">Consultation Fee</span>
                                    <span class="value">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</span>
                                </div>
                            </div>

                            {{-- Payment Info --}}
                            <div class="appt-alert-info">
                                <i class="fas fa-credit-card"></i>
                                <div>
                                    <strong>Secure Online Payment</strong> — After confirming, you will be redirected to our secure Stripe payment page to complete the payment.
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-appt-nav" onclick="goToStep(4)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn-appt-submit">
                                    <i class="fas fa-lock"></i>
                                    Confirm & Proceed to Payment
                                </button>
                            </div>
                        </div>

                    </form>
                </div>{{-- end appt-form-card --}}
            </div>
        </div>
    </div>
</section>

<script>
    let currentStep = {{ $errors->any() ? 'old("reason") ? 4 : (old("appointment_date") ? 3 : 1)' : '1' }};

    // ── Step Navigation ──
    function goToStep(step) {
        document.getElementById('step-' + currentStep).classList.remove('active');
        document.getElementById('step-' + step).classList.add('active');

        // Update circles and connectors
        for (let i = 1; i <= 5; i++) {
            const circle    = document.getElementById('circle-' + i);
            const label     = document.getElementById('label-' + i);
            const connector = document.getElementById('connector-' + i);

            circle.classList.remove('active', 'completed');
            label.classList.remove('active');

            if (i < step) {
                circle.classList.add('completed');
                circle.innerHTML = '<i class="fas fa-check" style="font-size:0.75rem;"></i>';
                if (connector) connector.classList.add('completed');
            } else if (i === step) {
                circle.classList.add('active');
                circle.textContent = i;
                label.classList.add('active');
                if (connector) connector.classList.remove('completed');
            } else {
                circle.textContent = i;
                if (connector) connector.classList.remove('completed');
            }
        }

        currentStep = step;
        updateSummary();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── Validations ──
    function validateStep2() {
        const wp = document.querySelector('input[name="workplace_id"]:checked');
        if (!wp) {
            showAlert('Please select a location to continue.');
            return;
        }
        goToStep(3);
    }

    function validateStep3() {
        const date = document.getElementById('appt-date').value;
        const time = document.getElementById('appt-time').value;
        if (!date) { showAlert('Please select an appointment date.'); return; }
        if (!time) { showAlert('Please select an appointment time.'); return; }
        goToStep(4);
    }

    function validateStep4() {
        const reason = document.getElementById('appt-reason').value.trim();
        if (!reason) { showAlert('Please enter the reason for your visit.'); return; }
        goToStep(5);
    }

    function updateSummary() {
        const date   = document.getElementById('appt-date')?.value   || '—';
        const time   = document.getElementById('appt-time')?.value   || '—';
        const reason = document.getElementById('appt-reason')?.value || '—';
        document.getElementById('summary-date').textContent   = date;
        document.getElementById('summary-time').textContent   = time;
        document.getElementById('summary-reason').textContent = reason.length > 60 ? reason.substring(0, 60) + '...' : reason;
    }

    function showAlert(msg) {
        // Use SweetAlert2 if available, else fallback
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Oops!', text: msg, confirmButtonColor: '#42a649' });
        } else {
            alert(msg);
        }
    }

    // Workplace radio visual update
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[name="workplace_id"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('input[name="workplace_id"]').forEach(r => {
                    const dot = r.closest('.workplace-option-label')
                                 ?.querySelector('.wp-radio-dot');
                    if (dot) {
                        dot.style.background      = r.checked ? 'var(--accent-color, #42a649)' : '';
                        dot.style.borderColor     = r.checked ? 'var(--accent-color, #42a649)' : '#ccc';
                    }
                });
            });
            // Init on load
            if (radio.checked) radio.dispatchEvent(new Event('change'));
        });

        // Init step if errors came back
        goToStep(currentStep);
    });
</script>

@include('partials.footer')
