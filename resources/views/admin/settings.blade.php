{{-- resources/views/admin/settings.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Admin Settings')

@section('page-title', 'Admin Settings')

@section('content')
    @php
        // Simple fallbacks – DB settings එක later එකෙන් add කරන්න
        $supportEmail = old('support_email');
        $supportPhone = old('support_phone');
        $timezone     = old('timezone', config('app.timezone'));

        $mailHost     = old('mail_host');
        $mailPort     = old('mail_port', 587);
        $mailUser     = old('mail_username');
        $mailFromName = old('mail_from_name', config('app.name'));
        $mailFromAddr = old('mail_from_address');
        $mailEnc      = old('mail_encryption', 'tls');
    @endphp

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- General Settings --}}
            <div class="dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>General Settings
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update.general') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">System Name</label>
                                <input type="text"
                                       name="system_name"
                                       class="form-control @error('system_name') is-invalid @enderror"
                                       value="{{ old('system_name', config('app.name')) }}"
                                       placeholder="HealthNet">
                                @error('system_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Support Email</label>
                                <input type="email"
                                       name="support_email"
                                       class="form-control @error('support_email') is-invalid @enderror"
                                       value="{{ $supportEmail }}"
                                       placeholder="support@example.com">
                                @error('support_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Support Phone</label>
                                <input type="text"
                                       name="support_phone"
                                       class="form-control @error('support_phone') is-invalid @enderror"
                                       value="{{ $supportPhone }}"
                                       placeholder="+94 11 234 5678">
                                @error('support_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Default Timezone</label>
                                @php
                                    $currentTz = $timezone;
                                @endphp
                                <select name="timezone"
                                        class="form-select @error('timezone') is-invalid @enderror">
                                    @foreach(timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>
                                            {{ $tz }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save General
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Email Settings --}}
            <div class="dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>Email Settings
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update.mail') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Mail Host</label>
                                <input type="text"
                                       name="mail_host"
                                       class="form-control @error('mail_host') is-invalid @enderror"
                                       value="{{ $mailHost }}"
                                       placeholder="smtp.mailtrap.io">
                                @error('mail_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Port</label>
                                <input type="number"
                                       name="mail_port"
                                       class="form-control @error('mail_port') is-invalid @enderror"
                                       value="{{ $mailPort }}">
                                @error('mail_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                       name="mail_username"
                                       class="form-control @error('mail_username') is-invalid @enderror"
                                       value="{{ $mailUser }}">
                                @error('mail_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">From Name</label>
                                <input type="text"
                                       name="mail_from_name"
                                       class="form-control @error('mail_from_name') is-invalid @enderror"
                                       value="{{ $mailFromName }}">
                                @error('mail_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">From Address</label>
                                <input type="email"
                                       name="mail_from_address"
                                       class="form-control @error('mail_from_address') is-invalid @enderror"
                                       value="{{ $mailFromAddr }}">
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Encryption</label>
                                @php
                                    $enc = $mailEnc;
                                @endphp
                                <select name="mail_encryption"
                                        class="form-select @error('mail_encryption') is-invalid @enderror">
                                    <option value="">None</option>
                                    <option value="tls" {{ $enc === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $enc === 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                                @error('mail_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="mail_password"
                                       class="form-control @error('mail_password') is-invalid @enderror"
                                       placeholder="••••••••">
                                @error('mail_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Leave blank to keep existing password.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Email Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Change password --}}
            <div class="dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Change Admin Password
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.change-password') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Current Password</label>
                                <input type="password"
                                       name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">New Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Minimum 8 characters.
                                </small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
