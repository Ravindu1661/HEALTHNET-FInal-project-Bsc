@extends('admin.layouts.master')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">

        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Appointment {{ $appointment->appointment_number }}</h6>
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

                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" id="appointmentForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label required">Patient</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->firstname }} {{ $patient->lastname }} - {{ $patient->user->email ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-select" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                            data-fee="{{ $doctor->consultation_fee }}"
                                            {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->firstname }} {{ $doctor->lastname }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Workplace Type</label>
                            <select name="workplace_type" id="workplace_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="hospital" {{ old('workplace_type', $appointment->workplace_type) === 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="medical_centre" {{ old('workplace_type', $appointment->workplace_type) === 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                                <option value="private" {{ old('workplace_type', $appointment->workplace_type) === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('workplace_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6" id="hospitalField" style="display:none;">
                            <label class="form-label">Hospital</label>
                            <select id="hospital_id" class="form-select">
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}"
                                        {{ (old('workplace_id', $appointment->workplace_id) == $hospital->id && old('workplace_type', $appointment->workplace_type) === 'hospital') ? 'selected' : '' }}>
                                        {{ $hospital->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6" id="medicalCentreField" style="display:none;">
                            <label class="form-label">Medical Centre</label>
                            <select id="medical_centre_id" class="form-select">
                                <option value="">Select Medical Centre</option>
                                @foreach($medicalCentres as $centre)
                                    <option value="{{ $centre->id }}"
                                        {{ (old('workplace_id', $appointment->workplace_id) == $centre->id && old('workplace_type', $appointment->workplace_type) === 'medical_centre') ? 'selected' : '' }}>
                                        {{ $centre->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Appointment Date</label>
                            <input type="date" name="appointment_date" class="form-control"
                                   value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" required>
                            @error('appointment_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Appointment Time</label>
                            <input type="time" name="appointment_time" class="form-control"
                                   value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}" required>
                            @error('appointment_time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['pending','confirmed','completed','cancelled','noshow'] as $st)
                                    <option value="{{ $st }}" {{ old('status', $appointment->status) === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Consultation Fee (LKR)</label>
                            <input type="number" name="consultation_fee" id="consultation_fee"
                                   class="form-control" step="0.01" min="0"
                                   value="{{ old('consultation_fee', $appointment->consultation_fee) }}" required>
                            @error('consultation_fee') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Advance Payment (LKR)</label>
                            <input type="number" name="advance_payment" class="form-control"
                                   step="0.01" min="0"
                                   value="{{ old('advance_payment', $appointment->advance_payment) }}">
                            @error('advance_payment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Reason for Visit</label>
                            <textarea name="reason" class="form-control" rows="3">{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Appointment
                        </button>
                        <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-secondary">
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

        if (workplaceType.value === 'hospital') {
            hospitalField.style.display = 'block';
        } else if (workplaceType.value === 'medical_centre') {
            medicalCentreField.style.display = 'block';
        }
    }

    workplaceType.addEventListener('change', updateWorkplaceFields);
    updateWorkplaceFields();

    document.getElementById('appointmentForm').addEventListener('submit', function () {
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
