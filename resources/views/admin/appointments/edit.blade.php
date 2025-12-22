@extends('admin.layouts.master')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        
        <!-- Form Card -->
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-edit me-2"></i>Edit Appointment #{{ $appointment->appointment_number }}</h6>
            </div>
            
            <div class="card-body">
                
                <!-- Error Messages -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle"></i> Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Edit Form -->
                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST" id="appointmentForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        
                        <!-- Patient Selection -->
                        <div class="col-md-6">
                            <label class="form-label required">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" 
                                        {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->firstname }} {{ $patient->lastname }} - {{ $patient->user->email }}
                                </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Doctor Selection -->
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
                            @error('doctor_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Workplace Type -->
                        <div class="col-md-6">
                            <label class="form-label required">Workplace Type</label>
                            <select name="workplace_type" id="workplace_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="hospital" {{ old('workplace_type', $appointment->workplace_type) == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="medicalcentre" {{ old('workplace_type', $appointment->workplace_type) == 'medicalcentre' ? 'selected' : '' }}>Medical Centre</option>
                                <option value="private" {{ old('workplace_type', $appointment->workplace_type) == 'private' ? 'selected' : '' }}>Private Practice</option>
                            </select>
                            @error('workplace_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Workplace Selection (Hospital) -->
                        <div class="col-md-6" id="hospital_field">
                            <label class="form-label">Hospital</label>
                            <select name="workplace_id_hospital" id="workplace_id_hospital" class="form-select">
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}" 
                                        {{ old('workplace_id', $appointment->workplace_id) == $hospital->id && $appointment->workplace_type == 'hospital' ? 'selected' : '' }}>
                                    {{ $hospital->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Workplace Selection (Medical Centre) -->
                        <div class="col-md-6" id="medicalcentre_field">
                            <label class="form-label">Medical Centre</label>
                            <select name="workplace_id_medicalcentre" id="workplace_id_medicalcentre" class="form-select">
                                <option value="">Select Medical Centre</option>
                                @foreach($medicalCentres as $centre)
                                <option value="{{ $centre->id }}" 
                                        {{ old('workplace_id', $appointment->workplace_id) == $centre->id && $appointment->workplace_type == 'medicalcentre' ? 'selected' : '' }}>
                                    {{ $centre->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Appointment Date -->
                        <div class="col-md-6">
                            <label class="form-label required">Appointment Date</label>
                            <input type="date" name="appointment_date" class="form-control" 
                                   value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                            @error('appointment_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Appointment Time -->
                        <div class="col-md-6">
                            <label class="form-label required">Appointment Time</label>
                            <input type="time" name="appointment_time" class="form-control" 
                                   value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}" required>
                            @error('appointment_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label required">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Consultation Fee -->
                        <div class="col-md-6">
                            <label class="form-label required">Consultation Fee (LKR)</label>
                            <input type="number" name="consultation_fee" id="consultation_fee" 
                                   class="form-control" step="0.01" min="0" 
                                   value="{{ old('consultation_fee', $appointment->consultation_fee) }}" required>
                            @error('consultation_fee')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Advance Payment -->
                        <div class="col-md-6">
                            <label class="form-label">Advance Payment (LKR)</label>
                            <input type="number" name="advance_payment" class="form-control" 
                                   step="0.01" min="0" value="{{ old('advance_payment', $appointment->advance_payment) }}">
                            @error('advance_payment')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Reason for Visit -->
                        <div class="col-md-12">
                            <label class="form-label">Reason for Visit</label>
                            <textarea name="reason" class="form-control" rows="3" 
                                      placeholder="Brief description of the reason...">{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Notes -->
                        <div class="col-md-12">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Any additional information...">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                    
                    <!-- Form Actions -->
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
.required::after {
    content: " *";
    color: red;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const workplaceTypeSelect = document.getElementById('workplace_type');
    const hospitalField = document.getElementById('hospital_field');
    const medicalCentreField = document.getElementById('medicalcentre_field');
    
    function updateWorkplaceFields() {
        const value = workplaceTypeSelect.value;
        
        // Hide all workplace fields first
        hospitalField.style.display = 'none';
        medicalCentreField.style.display = 'none';
        
        // Show relevant field
        if (value === 'hospital') {
            hospitalField.style.display = 'block';
        } else if (value === 'medicalcentre') {
            medicalCentreField.style.display = 'block';
        }
    }
    
    // Initialize on page load
    updateWorkplaceFields();
    
    // Update on change
    workplaceTypeSelect.addEventListener('change', updateWorkplaceFields);
    
    // Form submission handler
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        const workplaceType = workplaceTypeSelect.value;
        let workplaceId = null;
        
        if (workplaceType === 'hospital') {
            workplaceId = document.getElementById('workplace_id_hospital').value;
        } else if (workplaceType === 'medicalcentre') {
            workplaceId = document.getElementById('workplace_id_medicalcentre').value;
        }
        
        // Create hidden input for workplace_id
        if (workplaceId) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'workplace_id';
            hiddenInput.value = workplaceId;
            this.appendChild(hiddenInput);
        }
    });
});
</script>
@endpush
