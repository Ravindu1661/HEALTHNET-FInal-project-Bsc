@include('partials.header')

<style>
/* ═══════════════════════════════════
   STEP PROGRESS BAR
═══════════════════════════════════ */
.step-bar {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    padding: 1.2rem 1rem;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.step-bar .container { display: flex; align-items: center; justify-content: center; }
.step-item { display: flex; flex-direction: column; align-items: center; }
.step-circle {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2px solid #e0e0e0;
    background: #fff; color: #aaa;
    font-size: 0.85rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.3s; z-index: 1;
}
.step-circle.active {
    background: #42a649; border-color: #42a649;
    color: #fff; box-shadow: 0 4px 12px rgba(66,166,73,0.35);
}
.step-circle.done {
    background: #42a649; border-color: #42a649; color: #fff;
}
.step-label { font-size: 0.7rem; font-weight: 600; margin-top: 0.4rem; color: #aaa; white-space: nowrap; }
.step-label.active { color: #42a649; }
.step-line {
    width: 60px; height: 2px;
    background: #e0e0e0;
    margin: 0 0.4rem 22px;
    transition: background 0.3s;
}
.step-line.done { background: #42a649; }
@media (max-width: 576px) {
    .step-line { width: 25px; }
    .step-label { display: none; }
}

/* ═══════════════════════════════════
   PAGE HEADER
═══════════════════════════════════ */
.appt-hero {
    background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%);
    padding: 80px 0 3.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.appt-hero::after {
    content: '';
    position: absolute; bottom: -1px; left: 0; right: 0; height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.appt-hero .container { position: relative; z-index: 1; }

.back-link {
    color: rgba(255,255,255,0.8); text-decoration: none;
    font-size: 0.85rem; display: inline-flex; align-items: center;
    gap: 0.4rem; margin-bottom: 1rem; transition: color 0.2s;
}
.back-link:hover { color: #fff; }

/* ═══════════════════════════════════
   MAIN BODY
═══════════════════════════════════ */
.appt-body { background: #f4f6f9; padding: 2rem 0 4rem; min-height: 500px; }

/* Card */
.appt-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    overflow: hidden; margin-bottom: 1.5rem;
}

/* Section title inside card */
.sec-title {
    font-size: 1rem; font-weight: 700; color: #1a5276;
    display: flex; align-items: center; gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(66,166,73,0.2);
    margin-bottom: 1.2rem;
}
.sec-title i { color: #42a649; }

/* ── Doctor Summary ── */
.doc-summary {
    display: flex; gap: 1.2rem; align-items: center;
    flex-wrap: wrap;
    background: linear-gradient(135deg, rgba(66,166,73,0.06), rgba(66,166,73,0.12));
    border-bottom: 2px solid rgba(66,166,73,0.15);
    padding: 1.5rem 2rem;
}
.doc-sum-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    overflow: hidden; border: 3px solid #42a649;
    flex-shrink: 0; box-shadow: 0 4px 14px rgba(0,0,0,0.12);
}
.doc-sum-avatar img { width: 100%; height: 100%; object-fit: cover; }
.doc-sum-name { font-size: 1.2rem; font-weight: 700; color: #1a5276; margin-bottom: 0.2rem; }
.doc-sum-spec { color: #42a649; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.5rem; }
.doc-sum-meta { display: flex; gap: 1rem; flex-wrap: wrap; }
.doc-sum-meta span { font-size: 0.8rem; color: #666; display: flex; align-items: center; gap: 0.3rem; }
.doc-sum-meta i { color: #42a649; }
.doc-sum-fee {
    margin-left: auto; text-align: right;
    background: #fff; border-radius: 12px;
    padding: 0.8rem 1.2rem;
    border: 2px solid rgba(66,166,73,0.2);
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}
.fee-lbl { font-size: 0.7rem; color: #999; font-weight: 600; }
.fee-amt { font-size: 1.6rem; font-weight: 800; color: #42a649; line-height: 1.1; }
.fee-sub { font-size: 0.65rem; color: #bbb; }

/* ── Form body ── */
.card-body-pad { padding: 1.8rem 2rem; }

/* Form label */
.f-label {
    display: block; font-size: 0.83rem;
    font-weight: 700; color: #1a5276;
    margin-bottom: 0.45rem;
}
.f-label span { color: #dc3545; }

/* Form control */
.f-control {
    width: 100%; padding: 0.75rem 1rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.9rem; color: #333;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
    appearance: none; -webkit-appearance: none;
}
.f-control:focus {
    border-color: #42a649; outline: none;
    box-shadow: 0 0 0 3px rgba(66,166,73,0.1);
}
.f-control:disabled { background: #f8f8f8; color: #999; cursor: not-allowed; }
.f-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2342a649' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
    cursor: pointer;
}
.f-hint { font-size: 0.75rem; color: #aaa; margin-top: 0.3rem; }
.f-err  { font-size: 0.78rem; color: #dc3545; margin-top: 0.3rem; display: none; }
.f-err.show { display: block; }

/* Workplace cards (select style) */
.wp-options { display: flex; flex-direction: column; gap: 0.75rem; }
.wp-option {
    display: flex; align-items: center; gap: 0.9rem;
    border: 2px solid #e9ecef; border-radius: 12px;
    padding: 1rem 1.2rem; cursor: pointer;
    transition: all 0.2s;
    position: relative;
}
.wp-option:hover { border-color: #42a649; background: rgba(66,166,73,0.03); }
.wp-option input[type="radio"] { display: none; }
.wp-option.selected { border-color: #42a649; background: rgba(66,166,73,0.06); }
.wp-option-dot {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid #ccc; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.wp-option.selected .wp-option-dot {
    background: #42a649; border-color: #42a649;
}
.wp-option.selected .wp-option-dot::after {
    content: ''; width: 8px; height: 8px;
    background: #fff; border-radius: 50%;
}
.wp-icon {
    width: 42px; height: 42px; border-radius: 10px;
    background: linear-gradient(135deg, #1a5276, #2e86c1);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.wp-option.selected .wp-icon { background: linear-gradient(135deg, #42a649, #2d7a32); }
.wp-name  { font-size: 0.92rem; font-weight: 700; color: #1a5276; }
.wp-addr  { font-size: 0.78rem; color: #888; margin-top: 0.15rem; }
.wp-badge {
    margin-left: auto;
    background: #e3f2fd; color: #0d47a1;
    font-size: 0.68rem; font-weight: 700;
    padding: 0.2rem 0.6rem; border-radius: 8px; white-space: nowrap;
}
.wp-option.selected .wp-badge { background: rgba(66,166,73,0.15); color: #2d7a32; }

/* Date + Time grid */
.dt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
@media (max-width: 576px) { .dt-grid { grid-template-columns: 1fr; } }

/* Textarea */
.f-textarea {
    width: 100%; padding: 0.8rem 1rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.9rem; resize: vertical; min-height: 100px;
    font-family: inherit; color: #333;
    transition: border-color 0.2s;
}
.f-textarea:focus { border-color: #42a649; outline: none; box-shadow: 0 0 0 3px rgba(66,166,73,0.1); }

/* Summary sidebar */
.summary-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    overflow: hidden; position: sticky; top: 90px;
}
.summary-header {
    background: linear-gradient(135deg, #1a5276, #2e86c1);
    color: #fff; padding: 1.1rem 1.4rem;
    font-size: 0.95rem; font-weight: 700;
    display: flex; align-items: center; gap: 0.5rem;
}
.summary-body { padding: 1.4rem; }
.sum-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 0.6rem 0; border-bottom: 1px solid #f5f5f5;
    font-size: 0.85rem; gap: 0.5rem;
}
.sum-row:last-child { border: none; }
.sum-lbl { color: #888; display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0; }
.sum-lbl i { color: #42a649; width: 14px; }
.sum-val { color: #333; font-weight: 600; text-align: right; word-break: break-word; }
.sum-fee-box {
    background: linear-gradient(135deg, rgba(66,166,73,0.08), rgba(66,166,73,0.15));
    border: 2px solid rgba(66,166,73,0.25);
    border-radius: 12px; padding: 1rem; text-align: center; margin: 1rem 0;
}
.sum-fee-lbl  { font-size: 0.75rem; color: #777; font-weight: 600; margin-bottom: 0.2rem; }
.sum-fee-amt  { font-size: 1.8rem; font-weight: 800; color: #42a649; line-height: 1; }
.sum-fee-sub  { font-size: 0.65rem; color: #aaa; margin-top: 0.2rem; }

/* Submit button */
.btn-submit-appt {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none; width: 100%;
    padding: 1rem; border-radius: 25px;
    font-size: 1rem; font-weight: 700; cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(66,166,73,0.35);
    margin-top: 0.5rem;
}
.btn-submit-appt:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(66,166,73,0.45); }
.btn-submit-appt:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

/* Alert */
.f-alert {
    border-radius: 10px; padding: 0.9rem 1.1rem;
    margin-bottom: 1.3rem; display: flex;
    align-items: flex-start; gap: 0.7rem; font-size: 0.88rem;
}
.f-alert.error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
.f-alert.success { background: #d4edda; color: #155724; border-left: 4px solid #42a649; }
.f-alert.info    { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }

/* Char counter */
.char-count { font-size: 0.72rem; color: #aaa; text-align: right; margin-top: 0.25rem; }

@media (max-width: 768px) {
    .doc-summary  { flex-direction: column; }
    .doc-sum-fee  { margin-left: 0; width: 100%; text-align: center; }
    .card-body-pad { padding: 1.2rem; }
    .summary-card { position: static; margin-top: 1.5rem; }
}
</style>

{{-- ══ HERO ══ --}}
<section class="appt-hero">
    <div class="container">
        <a href="{{ route('patient.doctors.show', $doctor->id) }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Doctor Profile
        </a>
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.4rem;">
                    <i class="fas fa-calendar-plus me-2" style="opacity: 0.85;"></i>
                    Book Appointment
                </h1>
                <p style="opacity: 0.9; font-size: 0.95rem; margin: 0;">
                    Fill in the details below to schedule your consultation
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section class="appt-body">
    <div class="container">

        {{-- Alerts --}}
        @foreach(['error' => 'error', 'success' => 'success', 'info' => 'info'] as $skey => $stype)
            @if(session($skey))
                <div class="f-alert {{ $stype }}">
                    <i class="fas fa-{{ $stype === 'error' ? 'times-circle' : ($stype === 'success' ? 'check-circle' : 'info-circle') }} fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
                    <span>{{ session($skey) }}</span>
                </div>
            @endif
        @endforeach

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="f-alert error">
                <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 0.4rem 0 0; padding-left: 1.2rem; font-size: 0.85rem;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('patient.appointments.store', $doctor->id) }}"
              method="POST" id="appointmentForm">
            @csrf

            <div class="row g-4">

                {{-- ══ LEFT ══ --}}
                <div class="col-lg-8">

                    {{-- Doctor Summary --}}
                    <div class="appt-card">
                        @php
                            $docImg = $doctor->profile_image
                                ? asset('storage/' . $doctor->profile_image)
                                : asset('images/default-avatar.png');
                        @endphp
                        <div class="doc-summary">
                            <div class="doc-sum-avatar">
                                <img src="{{ $docImg }}"
                                     alt="Dr. {{ $doctor->first_name }}"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                            <div>
                                <div class="doc-sum-name">
                                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                </div>
                                <div class="doc-sum-spec">
                                    {{ $doctor->specialization ?? 'General Practitioner' }}
                                </div>
                                <div class="doc-sum-meta">
                                    @if($doctor->experience_years)
                                        <span>
                                            <i class="fas fa-briefcase-medical"></i>
                                            {{ $doctor->experience_years }} {{ Str::plural('yr', $doctor->experience_years) }} exp.
                                        </span>
                                    @endif
                                    @if($doctor->slmc_number)
                                        <span>
                                            <i class="fas fa-id-badge"></i>
                                            SLMC: {{ $doctor->slmc_number }}
                                        </span>
                                    @endif
                                    @if($doctor->phone)
                                        <span>
                                            <i class="fas fa-phone"></i>
                                            {{ $doctor->phone }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="doc-sum-fee">
                                <div class="fee-lbl">Consultation Fee</div>
                                <div class="fee-amt">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</div>
                                <div class="fee-sub">Sri Lankan Rupees</div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 1: Practice Location --}}
                    <div class="appt-card">
                        <div class="card-body-pad">
                            <div class="sec-title">
                                <i class="fas fa-hospital"></i>
                                Select Practice Location
                                <span style="font-size:0.72rem;color:#dc3545;font-weight:400;">* Required</span>
                            </div>

                            @if($workplaces->count() > 0)
                                <div class="wp-options" id="workplaceOptions">
                                    @foreach($workplaces as $wp)
                                        @php
                                            $wpName  = 'Unknown';
                                            $wpAddr  = '';
                                            $wpCity  = '';
                                            $wpType  = ucwords(str_replace('_', ' ', $wp->workplace_type));
                                            $wpIcon  = $wp->workplace_type === 'hospital' ? 'fa-hospital' : 'fa-clinic-medical';

                                            if ($wp->workplace_type === 'hospital' && $wp->hospital) {
                                                $wpName = $wp->hospital->name;
                                                $wpAddr = $wp->hospital->address ?? '';
                                                $wpCity = $wp->hospital->city    ?? '';
                                            } elseif ($wp->workplace_type === 'medical_centre' && $wp->medicalCentre) {
                                                $wpName = $wp->medicalCentre->name;
                                                $wpAddr = $wp->medicalCentre->address ?? '';
                                                $wpCity = $wp->medicalCentre->city    ?? '';
                                            }

                                            $isSelected = old('workplace_id') == $wp->id || ($loop->first && !old('workplace_id'));
                                        @endphp
                                        <label class="wp-option {{ $isSelected ? 'selected' : '' }}"
                                               for="wp_{{ $wp->id }}"
                                               onclick="selectWorkplace(this, {{ $wp->id }}, '{{ addslashes($wpName) }}', '{{ addslashes($wpType) }}')">
                                            <input type="radio"
                                                   name="workplace_id"
                                                   id="wp_{{ $wp->id }}"
                                                   value="{{ $wp->id }}"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                            <div class="wp-option-dot"></div>
                                            <div class="wp-icon">
                                                <i class="fas {{ $wpIcon }}"></i>
                                            </div>
                                            <div style="flex:1;min-width:0;">
                                                <div class="wp-name">{{ $wpName }}</div>
                                                @if($wpAddr || $wpCity)
                                                    <div class="wp-addr">
                                                        <i class="fas fa-map-marker-alt" style="color:#42a649;font-size:0.7rem;"></i>
                                                        {{ trim($wpAddr . ($wpCity ? ', ' . $wpCity : '')) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="wp-badge">{{ $wpType }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                @error('workplace_id')
                                    <div class="f-err show" style="margin-top:0.5rem;">{{ $message }}</div>
                                @enderror
                            @else
                                <div style="text-align:center;padding:2rem;color:#aaa;">
                                    <i class="fas fa-hospital-alt" style="font-size:2.5rem;display:block;margin-bottom:0.6rem;"></i>
                                    <p style="font-size:0.88rem;margin:0;">No approved practice locations available for this doctor.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Step 2: Date & Time --}}
                    <div class="appt-card">
                        <div class="card-body-pad">
                            <div class="sec-title">
                                <i class="fas fa-calendar-alt"></i>
                                Preferred Date & Time
                            </div>

                            <div class="dt-grid">
                                {{-- Date --}}
                                <div>
                                    <label class="f-label" for="appointment_date">
                                        Appointment Date <span>*</span>
                                    </label>
                                    <input type="date"
                                           name="appointment_date"
                                           id="appointment_date"
                                           class="f-control"
                                           value="{{ old('appointment_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           onchange="updateSummaryDate(this.value)"
                                           required>
                                    @error('appointment_date')
                                        <div class="f-err show">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Time --}}
                                <div>
                                    <label class="f-label" for="appointment_time">
                                        Preferred Time <span>*</span>
                                    </label>
                                    <input type="time"
                                           name="appointment_time"
                                           id="appointment_time"
                                           class="f-control"
                                           value="{{ old('appointment_time', '09:00') }}"
                                           min="08:00"
                                           max="18:00"
                                           onchange="updateSummaryTime(this.value)"
                                           required>
                                    <div class="f-hint">
                                        <i class="fas fa-clock" style="color:#42a649;"></i>
                                        Available: 8:00 AM – 6:00 PM
                                    </div>
                                    @error('appointment_time')
                                        <div class="f-err show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Reason & Notes --}}
                    <div class="appt-card">
                        <div class="card-body-pad">
                            <div class="sec-title">
                                <i class="fas fa-notes-medical"></i>
                                Appointment Details
                            </div>

                            {{-- Reason --}}
                            <div style="margin-bottom: 1.2rem;">
                                <label class="f-label" for="reason">
                                    Reason for Visit <span>*</span>
                                </label>
                                <textarea name="reason"
                                          id="reason"
                                          class="f-textarea"
                                          placeholder="Describe your symptoms or reason for this appointment (e.g., fever for 3 days, chest pain, routine check-up...)"
                                          maxlength="1000"
                                          oninput="countChars('reason', 'reasonCount', 1000)"
                                          required>{{ old('reason') }}</textarea>
                                <div class="char-count"><span id="reasonCount">0</span> / 1000</div>
                                @error('reason')
                                    <div class="f-err show">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="f-label" for="notes">
                                    Additional Notes
                                    <span style="color:#aaa;font-weight:400;">(optional)</span>
                                </label>
                                <textarea name="notes"
                                          id="notes"
                                          class="f-textarea"
                                          style="min-height:80px;"
                                          placeholder="Any other information the doctor should know (e.g., allergies, current medications, past medical history...)"
                                          maxlength="1000"
                                          oninput="countChars('notes', 'notesCount', 1000)">{{ old('notes') }}</textarea>
                                <div class="char-count"><span id="notesCount">0</span> / 1000</div>
                                @error('notes')
                                    <div class="f-err show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="appt-card">
                        <div class="card-body-pad">
                            <div class="sec-title">
                                <i class="fas fa-shield-alt"></i>
                                Terms & Conditions
                            </div>
                            <div style="background:#f8f9fa;border-radius:10px;padding:1rem;font-size:0.83rem;color:#666;line-height:1.7;margin-bottom:1rem;">
                                <ul style="margin:0;padding-left:1.2rem;">
                                    <li>Appointments are subject to doctor availability and confirmation.</li>
                                    <li>A {{ config('app.advance_payment_percent', 50) }}% advance payment is required to confirm your booking.</li>
                                    <li>Cancellations made less than 24 hours before the appointment may not be eligible for a refund.</li>
                                    <li>Please arrive 10 minutes before your scheduled time.</li>
                                    <li>Bring any relevant medical records or prescriptions.</li>
                                </ul>
                            </div>
                            <label style="display:flex;align-items:flex-start;gap:0.7rem;cursor:pointer;">
                                <input type="checkbox" id="agreeTerms"
                                       style="width:18px;height:18px;margin-top:1px;accent-color:#42a649;flex-shrink:0;"
                                       required>
                                <span style="font-size:0.85rem;color:#444;">
                                    I understand and agree to the above terms and conditions.
                                </span>
                            </label>
                            <div class="f-err" id="termsErr">You must agree to the terms before continuing.</div>
                        </div>
                    </div>

                </div>{{-- /col-lg-8 --}}

                {{-- ══ SIDEBAR SUMMARY ══ --}}
                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="summary-header">
                            <i class="fas fa-receipt"></i> Booking Summary
                        </div>
                        <div class="summary-body">

                            {{-- Doctor --}}
                            <div class="sum-row">
                                <span class="sum-lbl"><i class="fas fa-user-md"></i> Doctor</span>
                                <span class="sum-val">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span>
                            </div>
                            <div class="sum-row">
                                <span class="sum-lbl"><i class="fas fa-stethoscope"></i> Specialty</span>
                                <span class="sum-val">{{ $doctor->specialization ?? 'General' }}</span>
                            </div>

                            {{-- Location (dynamic) --}}
                            <div class="sum-row">
                                <span class="sum-lbl"><i class="fas fa-hospital"></i> Location</span>
                                <span class="sum-val" id="sumLocation">
                                    @php
                                        $firstWp = $workplaces->first();
                                        if ($firstWp) {
                                            if ($firstWp->workplace_type === 'hospital' && $firstWp->hospital) {
                                                echo $firstWp->hospital->name;
                                            } elseif ($firstWp->medicalCentre) {
                                                echo $firstWp->medicalCentre->name;
                                            } else {
                                                echo '—';
                                            }
                                        } else { echo '—'; }
                                    @endphp
                                </span>
                            </div>

                            {{-- Date --}}
                            <div class="sum-row">
                                <span class="sum-lbl"><i class="fas fa-calendar"></i> Date</span>
                                <span class="sum-val" id="sumDate">
                                    {{ old('appointment_date') ? \Carbon\Carbon::parse(old('appointment_date'))->format('D, d M Y') : '—' }}
                                </span>
                            </div>

                            {{-- Time --}}
                            <div class="sum-row">
                                <span class="sum-lbl"><i class="fas fa-clock"></i> Time</span>
                                <span class="sum-val" id="sumTime">
                                    {{ old('appointment_time') ? \Carbon\Carbon::parse(old('appointment_time'))->format('h:i A') : '9:00 AM' }}
                                </span>
                            </div>

                            {{-- Fee --}}
                            <div class="sum-fee-box">
                                <div class="sum-fee-lbl">Consultation Fee</div>
                                <div class="sum-fee-amt">
                                    Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}
                                </div>
                                <div class="sum-fee-sub">Sri Lankan Rupees</div>
                            </div>

                            {{-- Advance payment note --}}
                            @if(($doctor->consultation_fee ?? 0) > 0)
                                @php $advance = round(($doctor->consultation_fee ?? 0) * 0.5, 2); @endphp
                                <div style="background:#fff3cd;border-radius:8px;padding:0.7rem 0.9rem;font-size:0.78rem;color:#856404;margin-bottom:1rem;display:flex;gap:0.5rem;align-items:flex-start;">
                                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
                                    <span>
                                        An advance of <strong>Rs. {{ number_format($advance, 2) }}</strong>
                                        (50%) is required to confirm this booking.
                                    </span>
                                </div>
                            @endif

                            <button type="submit" class="btn-submit-appt" id="submitBtn">
                                <i class="fas fa-calendar-check"></i>
                                Confirm Appointment
                            </button>

                            <div style="text-align:center;margin-top:0.8rem;">
                                <a href="{{ route('patient.doctors.show', $doctor->id) }}"
                                   style="font-size:0.8rem;color:#aaa;text-decoration:none;">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Profile
                                </a>
                            </div>

                        </div>
                    </div>
                </div>{{-- /col-lg-4 --}}

            </div>{{-- /row --}}
        </form>
    </div>
</section>

@include('partials.footer')

<script>
/* ═══════════════════════════════════
   WORKPLACE SELECT
═══════════════════════════════════ */
// Workplace name map for summary
const wpNames = {
    @foreach($workplaces as $wp)
    @php
        $n = '';
        if ($wp->workplace_type === 'hospital' && $wp->hospital) $n = $wp->hospital->name;
        elseif ($wp->medicalCentre) $n = $wp->medicalCentre->name;
    @endphp
    {{ $wp->id }}: "{{ addslashes($n) }}",
    @endforeach
};

function selectWorkplace(el, id, name, type) {
    document.querySelectorAll('.wp-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type="radio"]').checked = true;
    document.getElementById('sumLocation').textContent = wpNames[id] || name;
}

/* ═══════════════════════════════════
   DATE / TIME SUMMARY UPDATE
═══════════════════════════════════ */
function updateSummaryDate(val) {
    if (!val) { document.getElementById('sumDate').textContent = '—'; return; }
    const d = new Date(val + 'T00:00:00');
    const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    document.getElementById('sumDate').textContent =
        days[d.getDay()] + ', ' + String(d.getDate()).padStart(2,'0') +
        ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
}

function updateSummaryTime(val) {
    if (!val) { document.getElementById('sumTime').textContent = '—'; return; }
    const [h, m] = val.split(':');
    const hour = parseInt(h);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const h12  = hour % 12 || 12;
    document.getElementById('sumTime').textContent =
        String(h12).padStart(2,'0') + ':' + m + ' ' + ampm;
}

/* ═══════════════════════════════════
   CHAR COUNTER
═══════════════════════════════════ */
function countChars(fieldId, countId, max) {
    const len = document.getElementById(fieldId).value.length;
    const el  = document.getElementById(countId);
    el.textContent = len;
    el.style.color = len > max * 0.9 ? '#dc3545' : '#aaa';
}

// Init on load
window.addEventListener('DOMContentLoaded', () => {
    countChars('reason', 'reasonCount', 1000);
    countChars('notes',  'notesCount',  1000);

    // Set initial time summary
    const t = document.getElementById('appointment_time').value;
    if (t) updateSummaryTime(t);

    // Set initial date summary
    const d = document.getElementById('appointment_date').value;
    if (d) updateSummaryDate(d);
});

/* ═══════════════════════════════════
   FORM VALIDATION + SUBMIT
═══════════════════════════════════ */
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    let valid = true;

    // Terms check
    const terms = document.getElementById('agreeTerms');
    const termsErr = document.getElementById('termsErr');
    if (!terms.checked) {
        termsErr.classList.add('show');
        valid = false;
    } else {
        termsErr.classList.remove('show');
    }

    // Date check
    const dateVal = document.getElementById('appointment_date').value;
    if (!dateVal) {
        valid = false;
    } else {
        const sel = new Date(dateVal);
        const today = new Date(); today.setHours(0,0,0,0);
        if (sel < today) {
            valid = false;
            // Highlight
            document.getElementById('appointment_date').style.borderColor = '#dc3545';
        }
    }

    // Reason check
    const reason = document.getElementById('reason').value.trim();
    if (!reason) { valid = false; }

    if (!valid) {
        e.preventDefault();
        window.scrollTo({ top: 200, behavior: 'smooth' });
        return;
    }

    // Disable submit to prevent double submit
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
});

/* Date input — remove red border on change */
document.getElementById('appointment_date').addEventListener('change', function() {
    this.style.borderColor = '';
});

/* ═══════════════════════════════════
   AUTO-DISMISS ALERTS
═══════════════════════════════════ */
setTimeout(() => {
    document.querySelectorAll('.f-alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 6000);
</script>
