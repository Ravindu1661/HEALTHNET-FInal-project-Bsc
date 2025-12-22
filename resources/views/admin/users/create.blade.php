@extends('admin.layouts.master')

@section('title', 'Create New User')

@section('page-title', 'Create New User')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6><i class="fas fa-user-plus me-2"></i>Add New User</h6>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                        @csrf
                        
                        <!-- Account Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Account Information</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">User Type <span class="text-danger">*</span></label>
                                    <select name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                        <option value="">Select User Type</option>
                                        <option value="patient" {{ old('user_type') == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="doctor" {{ old('user_type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="hospital" {{ old('user_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                        <option value="laboratory" {{ old('user_type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                        <option value="pharmacy" {{ old('user_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                        <option value="medical_centre" {{ old('user_type') == 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Account Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information (for patient/doctor) -->
                        <div class="mb-4" id="personalInfoSection" style="display: none;">
                            <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Show/hide personal info based on user type
    document.querySelector('select[name="user_type"]').addEventListener('change', function() {
        const personalSection = document.getElementById('personalInfoSection');
        if (this.value === 'patient' || this.value === 'doctor') {
            personalSection.style.display = 'block';
        } else {
            personalSection.style.display = 'none';
        }
    });

    // Trigger on page load if old input exists
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.querySelector('select[name="user_type"]');
        if (userTypeSelect.value === 'patient' || userTypeSelect.value === 'doctor') {
            document.getElementById('personalInfoSection').style.display = 'block';
        }
    });
</script>
@endpush
@push('styles')
<style>
    /* Compact SweetAlert2 Styling */
    .swal2-popup {
        font-size: 0.875rem !important;
        padding: 1rem !important;
        width: 320px !important;
        max-width: 90vw !important;
    }
    
    .swal2-title {
        font-size: 1.1rem !important;
        padding: 0.5rem 0 !important;
        margin: 0 !important;
    }
    
    .swal2-html-container {
        font-size: 0.8rem !important;
        margin: 0.5rem 0 !important;
        padding: 0 !important;
    }
    
    .swal2-icon {
        width: 50px !important;
        height: 50px !important;
        margin: 0.5rem auto !important;
    }
    
    .swal2-icon.swal2-warning {
        border-color: #f39c12 !important;
        color: #f39c12 !important;
    }
    
    .swal2-actions {
        margin: 0.75rem 0 0 0 !important;
        padding: 0 !important;
    }
    
    .swal2-styled {
        font-size: 0.8rem !important;
        padding: 0.4rem 1rem !important;
        margin: 0 0.25rem !important;
    }
    
    .swal2-loader {
        width: 30px !important;
        height: 30px !important;
    }
    
    /* Toast notifications - smaller */
    .swal2-toast {
        font-size: 0.8rem !important;
        padding: 0.5rem 1rem !important;
    }
    
    .swal2-toast .swal2-title {
        font-size: 0.85rem !important;
        margin: 0 !important;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 480px) {
        .swal2-popup {
            width: 280px !important;
            font-size: 0.8rem !important;
            padding: 0.75rem !important;
        }
        
        .swal2-title {
            font-size: 1rem !important;
        }
        
        .swal2-html-container {
            font-size: 0.75rem !important;
        }
        
        .swal2-icon {
            width: 40px !important;
            height: 40px !important;
        }
        
        .swal2-styled {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.75rem !important;
        }
    }
</style>
@endpush