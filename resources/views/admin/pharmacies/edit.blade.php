@extends('admin.layouts.master')
@section('title', 'Edit Pharmacy')
@section('page-title', 'Edit Pharmacy')
@section('content')
<div class="row"><div class="col-lg-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6>Edit Pharmacy: {{ $pharmacy->name }}</h6>
            <div>
                <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('admin.pharmacies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pharmacies.update', $pharmacy->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <h6 class="border-bottom pb-2 mb-3">Login Account</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email',$pharmacy->user->email) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>Change Password <small>(leave blank to keep current)</small></label>
                        <input name="password" type="password" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="form-control">
                    </div>
                </div>
                <h6 class="border-bottom pb-2 mb-3 mt-3">Pharmacy Info</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>Name</label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', $pharmacy->name) }}" required>
                    </div>
                    <div class="col-md-3"><label>Registration Number</label>
                        <input name="registration_number" type="text" class="form-control" value="{{ old('registration_number', $pharmacy->registration_number) }}" required>
                    </div>
                    <div class="col-md-3"><label>Phone</label>
                        <input name="phone" type="text" class="form-control" value="{{ old('phone', $pharmacy->phone) }}" required>
                    </div>
                    <div class="col-md-4"><label>City</label>
                        <input name="city" type="text" class="form-control" value="{{ old('city', $pharmacy->city) }}" required>
                    </div>
                    <div class="col-md-4"><label>Province</label>
                        <input name="province" type="text" class="form-control" value="{{ old('province', $pharmacy->province) }}" required>
                    </div>
                    <div class="col-md-4"><label>Postal Code</label>
                        <input name="postal_code" type="text" class="form-control" value="{{ old('postal_code', $pharmacy->postal_code) }}">
                    </div>
                    <div class="col-md-6"><label>Operating Hours</label>
                        <input name="operating_hours" type="text" class="form-control" value="{{ old('operating_hours', $pharmacy->operating_hours) }}">
                    </div>
                    <div class="col-md-6"><label>Address</label>
                        <input name="address" type="text" class="form-control" value="{{ old('address', $pharmacy->address) }}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label>Pharmacist Name</label>
                        <input name="pharmacist_name" type="text" class="form-control" value="{{ old('pharmacist_name', $pharmacy->pharmacist_name) }}" required>
                    </div>
                    <div class="col-md-6"><label>Pharmacist License</label>
                        <input name="pharmacist_license" type="text" class="form-control" value="{{ old('pharmacist_license', $pharmacy->pharmacist_license) }}" required>
                    </div>
                    <div class="col-md-6 d-flex align-items-center gap-2 mt-2">
                        <input name="delivery_available" type="checkbox" value="1" {{ $pharmacy->delivery_available ? 'checked' : '' }}>
                        <label style="margin: 0;">Delivery Available?</label>
                    </div>
                </div>
                <h6 class="border-bottom pb-2 mb-3 mt-4">Uploads</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        @if($pharmacy->profile_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$pharmacy->profile_image) }}" alt="Profile Image" style="max-width:64px;max-height:64px" class="rounded">
                            <small class="text-muted ms-2">(Current)</small>
                        </div>
                        @endif
                        <label>Profile Image</label>
                        <input name="profile_image" type="file" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        @if($pharmacy->document_path)
                        <div class="mb-2">
                            <a href="{{ asset('storage/'.$pharmacy->document_path) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-file"></i> View Document</a>
                        </div>
                        @endif
                        <label>Document (PDF/JPG/PNG)</label>
                        <input name="document" type="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.pharmacies.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Pharmacy</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
