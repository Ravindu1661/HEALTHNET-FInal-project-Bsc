{{-- resources/views/admin/profile.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i> Profile Details
                </h6>
            </div>
            <div class="card-body">
                {{-- Form submit route = profile (same URL), method POST + @method("PUT") if you add it --}}
                <form action="{{ route('admin.profile') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', Auth::user()->email) }}"
                            required
                        >
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                            >
                            <small class="text-muted">Leave blank to keep current password.</small>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                            >
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
