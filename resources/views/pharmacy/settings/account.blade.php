@extends('pharmacy.layouts.master')
@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@push('styles')
<style>
.account-section {
    border: 1.5px solid #f1f5f9;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.account-header {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 20px;
    display: flex; align-items: center; gap: 10px;
}
.account-header h6 { margin:0; font-weight:700; font-size:.88rem; color:#1e293b; }
.account-header .icon-wrap {
    width:32px; height:32px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; flex-shrink:0;
}
.account-body { padding:20px; background:#fff; }
.form-label-sm { font-size:.77rem; font-weight:600; color:#374151; }
.badge-user-type {
    font-size:.7rem; padding:3px 10px; border-radius:50px; font-weight:600;
}
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Account Settings</h5>
        <small class="text-muted">Manage your login credentials</small>
    </div>
    <a href="{{ route('pharmacy.settings') }}"
       class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="fas fa-arrow-left me-1"></i>Back to Settings
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4 alert-dismissible fade show"
     style="background:#f0fdf4">
    <i class="fas fa-check-circle me-2 text-success"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger border-0 rounded-3 mb-4 alert-dismissible fade show"
     style="background:#fef2f2">
    <i class="fas fa-exclamation-circle me-2 text-danger"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
<div class="col-lg-8">

    {{-- ══ Account Info ══ --}}
    <div class="account-section">
        <div class="account-header">
            <div class="icon-wrap" style="background:#eff6ff">
                <i class="fas fa-user-circle" style="color:#2563eb"></i>
            </div>
            <h6>Account Information</h6>
        </div>
        <div class="account-body">
            <form action="{{ route('pharmacy.account.update') }}"
                  method="POST">
                @csrf
                @method('PUT')

                {{-- Email --}}
                <div class="mb-4">
                    <label class="form-label form-label-sm mb-1">
                        <i class="fas fa-envelope me-1 text-muted"></i>
                        Email Address
                    </label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}"
                           placeholder="your@email.com">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text" style="font-size:.71rem">
                        This email is used to log in to your account.
                    </div>
                </div>

                {{-- Read-only fields --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label form-label-sm mb-1">
                            <i class="fas fa-id-badge me-1 text-muted"></i>
                            User Type
                        </label>
                        <input type="text" class="form-control form-control-sm"
                               value="{{ ucfirst($user->user_type) }}"
                               readonly style="background:#f8fafc;font-size:.84rem">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-sm mb-1">
                            <i class="fas fa-circle me-1 text-muted"></i>
                            Account Status
                        </label>
                        <input type="text" class="form-control form-control-sm"
                               value="{{ ucfirst($user->status) }}"
                               readonly style="background:#f8fafc;font-size:.84rem">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-sm mb-1">
                            <i class="fas fa-calendar me-1 text-muted"></i>
                            Member Since
                        </label>
                        <input type="text" class="form-control form-control-sm"
                               value="{{ $user->created_at->format('d M Y') }}"
                               readonly style="background:#f8fafc;font-size:.84rem">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-sm mb-1">
                            <i class="fas fa-check-circle me-1 text-muted"></i>
                            Email Verified
                        </label>
                        <input type="text" class="form-control form-control-sm"
                               value="{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not Verified' }}"
                               readonly style="background:#f8fafc;font-size:.84rem">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Update Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ Pharmacy Summary (readonly) ══ --}}
    @if($pharmacy)
    <div class="account-section">
        <div class="account-header">
            <div class="icon-wrap" style="background:#f0fdf4">
                <i class="fas fa-store" style="color:#16a34a"></i>
            </div>
            <h6>Linked Pharmacy</h6>
            <a href="{{ route('pharmacy.profile.edit') }}"
               class="btn btn-outline-success btn-sm rounded-pill px-3 ms-auto"
               style="font-size:.72rem">
                <i class="fas fa-edit me-1"></i>Edit Profile
            </a>
        </div>
        <div class="account-body">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                 style="background:#f8fafc;border:1.5px solid #f1f5f9">
                {{-- Avatar / Logo --}}
                @if($pharmacy->profile_image)
                <img src="{{ asset('storage/' . $pharmacy->profile_image) }}"
                     alt="{{ $pharmacy->name }}"
                     class="rounded-3"
                     style="width:56px;height:56px;object-fit:cover;flex-shrink:0">
                @else
                <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold"
                     style="width:56px;height:56px;background:#dbeafe;
                            color:#2563eb;font-size:1.2rem;flex-shrink:0">
                    {{ strtoupper(substr($pharmacy->name, 0, 1)) }}
                </div>
                @endif
                <div class="flex-fill">
                    <div class="fw-semibold" style="font-size:.9rem;color:#1e293b">
                        {{ $pharmacy->name }}
                    </div>
                    <small class="text-muted" style="font-size:.75rem">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $pharmacy->city }}, {{ $pharmacy->province }}
                    </small>
                    <div class="mt-1">
                        @php
                            $sc = ['pending'=>'warning','approved'=>'success',
                                   'rejected'=>'danger','suspended'=>'secondary'];
                        @endphp
                        <span class="badge bg-{{ $sc[$pharmacy->status] ?? 'secondary' }}
                                     bg-opacity-15
                                     text-{{ $sc[$pharmacy->status] ?? 'secondary' }}
                                     rounded-pill badge-user-type">
                            {{ ucfirst($pharmacy->status) }}
                        </span>
                        @if($pharmacy->delivery_available)
                        <span class="badge bg-success bg-opacity-15 text-success
                                     rounded-pill badge-user-type ms-1">
                            <i class="fas fa-truck me-1"></i>Delivery
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ Danger Zone ══ --}}
    <div class="account-section" style="border-color:#fecaca">
        <div class="account-header" style="background:#fef2f2;border-color:#fecaca">
            <div class="icon-wrap" style="background:#fee2e2">
                <i class="fas fa-exclamation-triangle" style="color:#dc2626"></i>
            </div>
            <h6 style="color:#dc2626">Danger Zone</h6>
        </div>
        <div class="account-body">
            <div class="d-flex align-items-center justify-content-between
                        flex-wrap gap-3">
                <div>
                    <div class="fw-semibold" style="font-size:.85rem;color:#1e293b">
                        Sign Out
                    </div>
                    <small class="text-muted" style="font-size:.77rem">
                        Sign out from all devices.
                    </small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm rounded-pill px-4"
                            style="font-size:.78rem">
                        <i class="fas fa-sign-out-alt me-1"></i>Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ══ Sidebar ══ --}}
<div class="col-lg-4">

    {{-- Account Card --}}
    <div class="dashboard-card mb-4">
        <div class="card-body text-center py-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center
                        fw-bold mx-auto mb-3"
                 style="width:64px;height:64px;
                        background:#eff6ff;color:#2563eb;font-size:1.4rem">
                {{ strtoupper(substr($user->email, 0, 1)) }}
            </div>
            <div class="fw-semibold mb-1" style="font-size:.92rem">
                {{ $user->email }}
            </div>
            <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"
                  style="font-size:.7rem">
                <i class="fas fa-store me-1"></i>{{ ucfirst($user->user_type) }}
            </span>
            <div class="mt-3 pt-3 border-top" style="font-size:.77rem;color:#6b7280">
                <div class="d-flex justify-content-between mb-1">
                    <span>Member since</span>
                    <span class="fw-semibold text-dark">
                        {{ $user->created_at->format('M Y') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Account status</span>
                    <span class="fw-semibold"
                          style="color:{{ $user->status === 'active' ? '#16a34a' : '#dc2626' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tips --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-lightbulb me-2 text-warning"></i>Security Tips
            </h6>
        </div>
        <div class="card-body">
            @php
                $tips = [
                    'Use a strong password with uppercase, numbers and symbols.',
                    'Never share your login credentials with anyone.',
                    'Change your password regularly for better security.',
                    'Keep your email address up to date for account recovery.',
                ];
            @endphp
            @foreach($tips as $tip)
            <div class="d-flex gap-2 mb-2 {{ !$loop->last ? '' : '' }}">
                <i class="fas fa-check-circle text-success mt-1"
                   style="font-size:.65rem;flex-shrink:0"></i>
                <small class="text-muted" style="font-size:.77rem;line-height:1.5">
                    {{ $tip }}
                </small>
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>

@endsection
