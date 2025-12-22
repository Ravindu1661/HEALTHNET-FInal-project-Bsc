@extends('admin.layouts.master')

@section('title', 'Edit Doctor')

@section('page-title', 'Edit Doctor')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6><i class="fas fa-user-edit me-2"></i>Edit Doctor</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Doctor Info Badge -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Doctor ID:</strong> {{ $doctor->id }} | 
                            <strong>SLMC:</strong> {{ $doctor->slmc_number }} | 
                            <strong>Registered:</strong> {{ $doctor->created_at->format('M d, Y') }}
                        </div>

                        <!-- Account Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Account Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $doctor->user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Account Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $doctor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $doctor->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('status', $doctor->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="suspended" {{ old('status', $doctor->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Change Password <small class="text-muted">(Leave blank to keep current)</small></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                           value="{{ old('first_name', $doctor->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                           value="{{ old('last_name', $doctor->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">SLMC Number <span class="text-danger">*</span></label>
                                    <input type="text" name="slmc_number" class="form-control @error('slmc_number') is-invalid @enderror" 
                                           value="{{ old('slmc_number', $doctor->slmc_number) }}" required>
                                    @error('slmc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $doctor->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Professional Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                           value="{{ old('specialization', $doctor->specialization) }}" required>
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Experience (Years)</label>
                                    <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" 
                                           value="{{ old('experience_years', $doctor->experience_years) }}" min="0">
                                    @error('experience_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Consultation Fee (LKR)</label>
                                    <input type="number" step="0.01" name="consultation_fee" class="form-control @error('consultation_fee') is-invalid @enderror" 
                                           value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0">
                                    @error('consultation_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Qualifications</label>
                                    <textarea name="qualifications" class="form-control @error('qualifications') is-invalid @enderror" 
                                              rows="3">{{ old('qualifications', $doctor->qualifications) }}</textarea>
                                    @error('qualifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Bio / About</label>
                                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" 
                                              rows="3">{{ old('bio', $doctor->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Documents Upload -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Documents</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    @if($doctor->document_path)
                                    <div class="alert alert-secondary mb-2">
                                        <i class="fas fa-file"></i> Current Document: 
                                        <a href="{{ Storage::url($doctor->document_path) }}" target="_blank">View Document</a>
                                    </div>
                                    @endif
                                    <label class="form-label">Upload New Document (Optional)</label>
                                    <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Max size: 5MB. Allowed: PDF, JPG, PNG</small>
                                    @error('document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Doctor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
