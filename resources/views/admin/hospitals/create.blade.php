@extends('admin.layouts.master')
@section('title', 'Add New Hospital')
@section('page-title', 'Add New Hospital')
@section('content')
<div class="row"><div class="col-md-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header">
            <h6><i class="fas fa-hospital me-2"></i>Add New Hospital</h6>
            <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hospitals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Account -->
                <h6 class="border-bottom pb-2 mb-3">Hospital Login Account</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <!-- Hospital Details -->
                <h6 class="border-bottom pb-2 mb-3 mt-4">Hospital Information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Hospital Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="government" {{ old('type')=='government'?'selected':'' }}>Government</option>
                            <option value="private" {{ old('type')=='private'?'selected':'' }}>Private</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reg. Number <span class="text-danger">*</span></label>
                        <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province <span class="text-danger">*</span></label>
                        <input type="text" name="province" class="form-control" value="{{ old('province') }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" class="form-control" value="{{ old('website') }}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Specializations <small class="text-muted">(comma separated)</small></label>
                        <input type="text" name="specializations" class="form-control" value="{{ old('specializations') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Facilities <small class="text-muted">(comma separated)</small></label>
                        <input type="text" name="facilities" class="form-control" value="{{ old('facilities') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Uploads -->
                <h6 class="border-bottom pb-2 mb-3 mt-4">Upload</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Document (PDF/JPG/PNG)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Hospital</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
