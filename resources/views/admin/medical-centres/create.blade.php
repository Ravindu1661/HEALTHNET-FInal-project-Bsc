@extends('admin.layouts.master')
@section('title', 'Add New Medical Centre')
@section('page-title', 'Add New Medical Centre')
@section('content')
<div class="row"><div class="col-lg-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header">
            <h6><i class="fas fa-clinic-medical me-2"></i>Add New Medical Centre</h6>
            <a href="{{ route('admin.medical-centres.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.medical-centres.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 class="border-bottom pb-2 mb-3">Login Account</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label>Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>Password <span class="text-danger">*</span></label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input name="password_confirmation" type="password" class="form-control" required>
                    </div>
                </div>
                
                <h6 class="border-bottom pb-2 mb-3">Medical Centre Info</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>Name <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3"><label>Registration Number <span class="text-danger">*</span></label>
                        <input name="registration_number" type="text" class="form-control" value="{{ old('registration_number') }}" required>
                    </div>
                    <div class="col-md-3"><label>Phone <span class="text-danger">*</span></label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-4"><label>City <span class="text-danger">*</span></label>
                        <input name="city" type="text" class="form-control" value="{{ old('city') }}" required>
                    </div>
                    <div class="col-md-4"><label>Province <span class="text-danger">*</span></label>
                        <input name="province" type="text" class="form-control" value="{{ old('province') }}" required>
                    </div>
                    <div class="col-md-4"><label>Postal Code</label>
                        <input name="postal_code" type="text" class="form-control" value="{{ old('postal_code') }}">
                    </div>
                    <div class="col-md-6"><label>Operating Hours</label>
                        <input name="operating_hours" type="text" class="form-control" value="{{ old('operating_hours') }}">
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>Specializations <small class="text-muted">(comma separated)</small></label>
                        <input name="specializations" type="text" class="form-control" value="{{ old('specializations') }}">
                    </div>
                    <div class="col-md-6"><label>Facilities <small class="text-muted">(comma separated)</small></label>
                        <input name="facilities" type="text" class="form-control" value="{{ old('facilities') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <h6 class="border-bottom pb-2 mb-3 mt-4">Uploads</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Profile Image</label>
                        <input name="profile_image" type="file" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label>Document (PDF/JPG/PNG)</label>
                        <input name="document" type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.medical-centres.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Medical Centre</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
