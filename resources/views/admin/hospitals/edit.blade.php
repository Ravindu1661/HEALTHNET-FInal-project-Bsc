@extends('admin.layouts.master')
@section('title', 'Edit Hospital')
@section('page-title', 'Edit Hospital')
@section('content')
<div class="row"><div class="col-md-10 mx-auto">
    <div class="dashboard-card">
        <div class="card-header">
            <h6>Edit Hospital: {{ $hospital->name }}</h6>
            <div>
                <a href="{{ route('admin.hospitals.show', $hospital->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hospitals.update', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <!-- Account -->
                <h6 class="border-bottom pb-2 mb-3">Login Account</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $hospital->user->email) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Change Password <small>(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <!-- Hospital Info -->
                <h6 class="border-bottom pb-2 mb-3 mt-4">Hospital Information</h6>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Hospital Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$hospital->name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="government" {{ old('type',$hospital->type)=='government'?'selected':'' }}>Government</option>
                            <option value="private" {{ old('type',$hospital->type)=='private'?'selected':'' }}>Private</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reg. Number</label>
                        <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number',$hospital->registration_number) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone',$hospital->phone) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city',$hospital->city) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province</label>
                        <input type="text" name="province" class="form-control" value="{{ old('province',$hospital->province) }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address',$hospital->address) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code',$hospital->postal_code) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" class="form-control" value="{{ old('website',$hospital->website) }}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Specializations</label>
                        <input type="text" name="specializations" class="form-control" value="{{ old('specializations', json_decode($hospital->specializations, true) ? implode(', ', json_decode($hospital->specializations, true)) : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Facilities</label>
                        <input type="text" name="facilities" class="form-control" value="{{ old('facilities', json_decode($hospital->facilities, true) ? implode(', ', json_decode($hospital->facilities, true)) : '') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description',$hospital->description) }}</textarea>
                    </div>
                </div>

                <!-- Uploads -->
                <h6 class="border-bottom pb-2 mb-3 mt-4">Uploads</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        @if($hospital->profile_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$hospital->profile_image) }}" style="max-width:64px;max-height:64px" class="rounded">
                            <small class="text-muted ms-2">(Current)</small>
                        </div>
                        @endif
                        <label class="form-label">Profile Image (Optional)</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        @if($hospital->document_path)
                        <div class="mb-2">
                            <a href="{{ asset('storage/'.$hospital->document_path) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-file"></i> View Document</a>
                        </div>
                        @endif
                        <label class="form-label">Document (Optional)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
