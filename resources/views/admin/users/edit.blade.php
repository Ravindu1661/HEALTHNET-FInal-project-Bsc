@extends('admin.layouts.master')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6><i class="fas fa-user-edit me-2"></i>Edit User</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- User Info Badge -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> 
                            <strong>User ID:</strong> {{ $user->id }} | 
                            <strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}
                        </div>

                        <!-- Account Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Account Information</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">User Type <span class="text-danger">*</span></label>
                                    <select name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                        <option value="patient" {{ old('user_type', $user->user_type) == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="doctor" {{ old('user_type', $user->user_type) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="hospital" {{ old('user_type', $user->user_type) == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                        <option value="laboratory" {{ old('user_type', $user->user_type) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                        <option value="pharmacy" {{ old('user_type', $user->user_type) == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                        <option value="medical_centre" {{ old('user_type', $user->user_type) == 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                                        <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Account Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Verified</label>
                                    <div class="form-control-plaintext">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Not Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Change Password <small class="text-muted">(Leave blank to keep current password)</small></h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information (if profile exists) -->
                        @if($profile && in_array($user->user_type, ['patient', 'doctor']))
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" 
                                           value="{{ old('first_name', $profile->first_name ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" 
                                           value="{{ old('last_name', $profile->last_name ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" 
                                           value="{{ old('phone', $profile->phone ?? '') }}">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
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