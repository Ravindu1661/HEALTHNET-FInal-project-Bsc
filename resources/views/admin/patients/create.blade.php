@extends('admin.layouts.master')
@section('title', 'Add New Patient')
@section('page-title', 'Add New Patient')
@section('content')
<div class="row"><div class="col-lg-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header">
            <h6><i class="fas fa-user-injured me-2"></i>Add New Patient</h6>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data">
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

                <h6 class="border-bottom pb-2 mb-3">Patient Info</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>First Name <span class="text-danger">*</span></label>
                        <input name="first_name" type="text" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-6"><label>Last Name</label>
                        <input name="last_name" type="text" class="form-control" value="{{ old('last_name') }}">
                    </div>
                    <div class="col-md-4"><label>NIC <span class="text-danger">*</span></label>
                        <input name="nic" type="text" class="form-control" value="{{ old('nic') }}" required>
                    </div>
                    <div class="col-md-4"><label>Phone <span class="text-danger">*</span></label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-4"><label>City</label>
                        <input name="city" type="text" class="form-control" value="{{ old('city') }}">
                    </div>
                    <div class="col-md-6"><label>Address</label>
                        <input name="address" type="text" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="col-md-6"><label>Province</label>
                        <input name="province" type="text" class="form-control" value="{{ old('province') }}">
                    </div>
                </div>

                <h6 class="border-bottom pb-2 mb-3 mt-3">Uploads</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label>Profile Image</label>
                        <input name="profile_image" type="file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Patient</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
