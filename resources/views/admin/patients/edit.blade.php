@extends('admin.layouts.master')
@section('title', 'Edit Patient')
@section('page-title', 'Edit Patient')
@section('content')
<div class="row"><div class="col-lg-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6>Edit Patient: {{ $patient->first_name }} {{ $patient->last_name }}</h6>
            <div>
                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <h6 class="border-bottom pb-2 mb-3">Login Account</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $patient->user->email) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>New Password <small>(leave blank to keep current)</small></label>
                        <input name="password" type="password" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="form-control">
                    </div>
                </div>

                <h6 class="border-bottom pb-2 mb-3">Patient Info</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>First Name</label>
                        <input name="first_name" type="text" class="form-control" value="{{ old('first_name', $patient->first_name) }}" required>
                    </div>
                    <div class="col-md-6"><label>Last Name</label>
                        <input name="last_name" type="text" class="form-control" value="{{ old('last_name', $patient->last_name) }}">
                    </div>
                    <div class="col-md-4"><label>NIC</label>
                        <input name="nic" type="text" class="form-control" value="{{ old('nic', $patient->nic) }}" required>
                    </div>
                    <div class="col-md-4"><label>Phone</label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone', $patient->phone) }}" required>
                    </div>
                    <div class="col-md-4"><label>City</label>
                        <input name="city" type="text" class="form-control" value="{{ old('city', $patient->city) }}">
                    </div>
                    <div class="col-md-6"><label>Address</label>
                        <input name="address" type="text" class="form-control" value="{{ old('address', $patient->address) }}">
                    </div>
                    <div class="col-md-6"><label>Province</label>
                        <input name="province" type="text" class="form-control" value="{{ old('province', $patient->province) }}">
                    </div>
                </div>

                <h6 class="border-bottom pb-2 mb-3 mt-4">Uploads</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        @if($patient->profile_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $patient->profile_image) }}" alt="Profile Image" style="max-width:64px; max-height:64px;" class="rounded">
                                <small class="text-muted ms-2">(Current)</small>
                            </div>
                        @endif
                        <label>Profile Image</label>
                        <input name="profile_image" type="file" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Patient</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
