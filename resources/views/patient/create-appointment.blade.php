{{-- Include Header --}}
@include('partials.header')

<style>
/* Appointment Page Styles */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

.back-btn {
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.back-btn:hover {
    color: white;
    transform: translateX(-5px);
}

.appointment-container {
    padding: 2.5rem 0;
    min-height: 600px;
}

.appointment-form-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    margin-top: -2.5rem;
}

.doctor-summary {
    background: linear-gradient(135deg, rgba(66, 166, 73, 0.05) 0%, rgba(66, 166, 73, 0.1) 100%);
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 2px solid rgba(66, 166, 73, 0.2);
}

.doctor-summary-header {
    display: flex;
    gap: 1.2rem;
    align-items: center;
}

.doctor-summary-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--accent-color);
    flex-shrink: 0;
}

.doctor-summary-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-summary-info h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.3rem;
}

.doctor-summary-info p {
    color: var(--accent-color);
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.doctor-summary-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    font-size: 0.85rem;
    color: #666;
}

.doctor-summary-meta span {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.doctor-summary-meta i {
    color: var(--accent-color);
}

.form-section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid rgba(66, 166, 73, 0.2);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: ' *';
    color: #dc3545;
}

.form-control,
.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(66, 166, 73, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.workplace-select-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.workplace-option {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.workplace-option:hover {
    border-color: var(--accent-color);
    background: rgba(66, 166, 73, 0.05);
}

.workplace-option input[type="radio"] {
    margin-right: 0.7rem;
}

.workplace-option.selected {
    border-color: var(--accent-color);
    background: rgba(66, 166, 73, 0.1);
}

.workplace-name {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 0.95rem;
    margin-bottom: 0.3rem;
}

.workplace-details {
    font-size: 0.8rem;
    color: #666;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.date-time-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.appointment-summary {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-top: 2rem;
    border-left: 4px solid var(--accent-color);
}

.summary-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.6rem 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-label {
    color: #666;
    font-size: 0.9rem;
}

.summary-value {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 0.9rem;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    margin-top: 0.5rem;
    border-top: 2px solid var(--accent-color);
}

.summary-total .summary-label {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
}

.summary-total .summary-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--accent-color);
}

.btn-submit {
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(66, 166, 73, 0.3);
}

.btn-submit:hover {
    background: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66, 166, 73, 0.4);
}

.btn-submit:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-cancel:hover {
    background: #5a6268;
    color: white;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

/* Responsive */
@media (max-width: 768px) {
    .doctor-summary-header {
        flex-direction: column;
        text-align: center;
    }

    .workplace-select-group,
    .date-time-group {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
        justify-content: center;
    }
}
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="container">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="page-title">Book Appointment</h1>
                <p class="page-subtitle">Schedule your consultation with our medical professionals</p>
            </div>
        </div>
    </div>
</section>

{{-- Appointment Form --}}
<section class="appointment-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="appointment-form-card">
                    {{-- Display Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                            <ul style="margin: 0.5rem 0 0 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Doctor Summary --}}
                    @if(isset($doctor))
                        <div class="doctor-summary">
                            <div class="doctor-summary-header">
                                <div class="doctor-summary-avatar">
                                    @php
                                        $profileImage = $doctor->profile_image
                                            ? asset('storage/' . $doctor->profile_image)
                                            : asset('images/default-avatar.png');
                                    @endphp
                                    <img src="{{ $profileImage }}"
                                         alt="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div class="doctor-summary-info">
                                    <h3>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h3>
                                    <p>{{ $doctor->specialization ?? 'General Practitioner' }}</p>
                                    <div class="doctor-summary-meta">
                                        @if($doctor->experience_years)
                                            <span>
                                                <i class="fas fa-briefcase"></i>
                                                {{ $doctor->experience_years }} years experience
                                            </span>
                                        @endif
                                        <span>
                                            <i class="fas fa-money-bill-wave"></i>
                                            Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Appointment Form --}}
                    <form method="POST" action="{{ route('patient.appointments.store', ['doctor_id' => $doctor->id ?? 0]) }}" id="appointmentForm">
                        @csrf

                        {{-- Select Workplace --}}
                        @if(isset($workplaces) && $workplaces->count() > 0)
                            <div class="form-section">
                                <h3 class="form-section-title">
                                    <i class="fas fa-hospital"></i>
                                    Select Location
                                </h3>

                                <div class="workplace-select-group">
                                    @foreach($workplaces as $workplace)
                                        @php
                                            $workplaceName = 'Not Available';
                                            $workplaceAddress = 'Address not available';
                                            $workplaceType = $workplace->workplace_type;

                                            if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                                                $workplaceName = $workplace->hospital->name;
                                                $workplaceAddress = $workplace->hospital->address ?? 'Address not available';
                                            } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                                                $workplaceName = $workplace->medicalCentre->name;
                                                $workplaceAddress = $workplace->medicalCentre->address ?? 'Address not available';
                                            }
                                        @endphp

                                        <label class="workplace-option">
                                            <input type="radio"
                                                   name="workplace_id"
                                                   value="{{ $workplace->id }}"
                                                   required
                                                   {{ old('workplace_id') == $workplace->id ? 'checked' : '' }}>
                                            <div class="workplace-name">{{ $workplaceName }}</div>
                                            <div class="workplace-details">
                                                <span><i class="fas fa-building"></i> {{ str_replace('_', ' ', ucfirst($workplaceType)) }}</span>
                                                <span><i class="fas fa-map-marker-alt"></i> {{ Str::limit($workplaceAddress, 50) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Appointment Date & Time --}}
                        <div class="form-section" style="margin-top: 2rem;">
                            <h3 class="form-section-title">
                                <i class="fas fa-calendar-alt"></i>
                                Select Date & Time
                            </h3>

                            <div class="date-time-group">
                                <div class="form-group">
                                    <label class="form-label required">Appointment Date</label>
                                    <input type="date"
                                           class="form-control"
                                           name="appointment_date"
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           value="{{ old('appointment_date') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Appointment Time</label>
                                    <input type="time"
                                           class="form-control"
                                           name="appointment_time"
                                           required
                                           value="{{ old('appointment_time') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Appointment Details --}}
                        <div class="form-section" style="margin-top: 2rem;">
                            <h3 class="form-section-title">
                                <i class="fas fa-file-medical"></i>
                                Appointment Details
                            </h3>

                            <div class="form-group">
                                <label class="form-label required">Reason for Visit</label>
                                <textarea class="form-control"
                                          name="reason"
                                          rows="4"
                                          placeholder="Please describe your symptoms or reason for consultation"
                                          required>{{ old('reason') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control"
                                          name="notes"
                                          rows="3"
                                          placeholder="Any additional information you'd like the doctor to know">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Appointment Summary --}}
                        <div class="appointment-summary">
                            <div class="summary-title">
                                <i class="fas fa-receipt me-2"></i>
                                Appointment Summary
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Doctor</span>
                                <span class="summary-value">Dr. {{ $doctor->first_name ?? '' }} {{ $doctor->last_name ?? '' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Specialization</span>
                                <span class="summary-value">{{ $doctor->specialization ?? 'General' }}</span>
                            </div>
                            <div class="summary-total">
                                <span class="summary-label">Consultation Fee</span>
                                <span class="summary-value">Rs. {{ number_format($doctor->consultation_fee ?? 0, 2) }}</span>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="form-actions">
                            <a href="{{ url()->previous() }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-check-circle"></i>
                                Confirm Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Workplace option selection
    const workplaceOptions = document.querySelectorAll('.workplace-option');
    workplaceOptions.forEach(option => {
        option.addEventListener('click', function() {
            workplaceOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });

        // Check if already selected
        if (option.querySelector('input[type="radio"]').checked) {
            option.classList.add('selected');
        }
    });

    // Form validation
    const form = document.getElementById('appointmentForm');
    form.addEventListener('submit', function(e) {
        const date = document.querySelector('input[name="appointment_date"]').value;
        const time = document.querySelector('input[name="appointment_time"]').value;
        const workplace = document.querySelector('input[name="workplace_id"]:checked');

        if (!date || !time || !workplace) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
    });
});
</script>

{{-- Include Footer --}}
@include('partials.footer')
