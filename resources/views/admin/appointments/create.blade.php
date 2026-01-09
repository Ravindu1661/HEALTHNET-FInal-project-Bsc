@extends('admin.layouts.master')

@section('title', 'Create New Appointment')
@section('page-title', 'Create New Appointment')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">

        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Appointment Information</h6>
            </div>

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle"></i> Validation Errors</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.appointments.store') }}" method="POST" id="appointmentForm">
                    @csrf

                    <div class="row g-3">

                        {{-- Patient --}}
                        <div class="col-md-6">
                            <label class="form-label required">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->firstname }} {{ $patient->lastname }} - {{ $patient->user->email ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Doctor --}}
                        <div class="col-md-6">
                            <label class="form-label required">Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-select" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                            data-fee="{{ $doctor->consultation_fee }}"
                                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->firstname }} {{ $doctor->lastname }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Workplace type --}}
                        <div class="col-md-6">
                            <label class="form-label required">Workplace Type</label>
                            <select name="workplace_type" id="workplace_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="hospital" {{ old('workplace_type') === 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="medical_centre" {{ old('workplace_type') === 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                                <option value="private" {{ old('workplace_type') === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('workplace_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Workplace selector (hospital) --}}
                        <div class="col-md-6" id="hospitalField" style="display:none;">
                            <label class="form-label">Hospital</label>
                            <select id="hospital_id" class="form-select">
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Workplace selector (medical centre) --}}
                        <div class="col-md-6" id="medicalCentreField" style="display:none;">
                            <label class="form-label">Medical Centre</label>
                            <select id="medical_centre_id" class="form-select">
                                <option value="">Select Medical Centre</option>
                                @foreach($medicalCentres as $centre)
                                    <option value="{{ $centre->id }}">{{ $centre->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <label class="form-label required">Appointment Date</label>
                            <input type="date" name="appointment_date" class="form-control"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('appointment_date') }}" required>
                            @error('appointment_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Time --}}
                        <div class="col-md-6">
                            <label class="form-label required">Appointment Time</label>
                            <input type="time" name="appointment_time" class="form-control"
                                   value="{{ old('appointment_time') }}" required>
                            @error('appointment_time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Fee --}}
                        <div class="col-md-6">
                            <label class="form-label required">Consultation Fee (LKR)</label>
                            <input type="number" name="consultation_fee" id="consultation_fee"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('consultation_fee') }}" required>
                            @error('consultation_fee') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Advance --}}
                        <div class="col-md-6">
                            <label class="form-label">Advance Payment (LKR)</label>
                            <input type="number" name="advance_payment" class="form-control"
                                   step="0.01" min="0"
                                   value="{{ old('advance_payment', 0) }}">
                            @error('advance_payment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Reason --}}
                        <div class="col-md-12">
                            <label class="form-label">Reason for Visit</label>
                            <textarea name="reason" class="form-control" rows="3"
                                      placeholder="Brief reason...">{{ old('reason') }}</textarea>
                            @error('reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="col-md-12">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Appointment
                        </button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .required::after { content: " *"; color: red; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const doctorSelect = document.getElementById('doctor_id');
    const feeInput = document.getElementById('consultation_fee');

    const workplaceType = document.getElementById('workplace_type');
    const hospitalField = document.getElementById('hospitalField');
    const medicalCentreField = document.getElementById('medicalCentreField');
    const hospitalSelect = document.getElementById('hospital_id');
    const medicalCentreSelect = document.getElementById('medical_centre_id');

    doctorSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const fee = opt.getAttribute('data-fee');
        if (fee) feeInput.value = fee;
    });

    function updateWorkplaceFields() {
        hospitalField.style.display = 'none';
        medicalCentreField.style.display = 'none';
        hospitalSelect.value = '';
        medicalCentreSelect.value = '';

        if (workplaceType.value === 'hospital') {
            hospitalField.style.display = 'block';
        } else if (workplaceType.value === 'medical_centre') {
            medicalCentreField.style.display = 'block';
        }
    }

    workplaceType.addEventListener('change', updateWorkplaceFields);
    updateWorkplaceFields();

    document.getElementById('appointmentForm').addEventListener('submit', function () {
        // Remove previous hidden input if exists
        const old = this.querySelector('input[name="workplace_id"]');
        if (old) old.remove();

        let workplaceId = null;
        if (workplaceType.value === 'hospital') workplaceId = hospitalSelect.value;
        if (workplaceType.value === 'medical_centre') workplaceId = medicalCentreSelect.value;

        if (workplaceId) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'workplace_id';
            input.value = workplaceId;
            this.appendChild(input);
        }
    });
});
</script>
@endpush
