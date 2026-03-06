@extends('pharmacy.layouts.master')
@section('title', 'Settings')
@section('page-title', 'Settings')

@push('styles')
<style>
.settings-section {
    border: 1.5px solid #f1f5f9;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.settings-header {
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.settings-header h6 {
    margin: 0;
    font-weight: 700;
    font-size: .88rem;
    color: #1e293b;
}
.settings-header .icon-wrap {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
}
.settings-body { padding: 20px; background: #fff; }
.form-label-sm { font-size: .77rem; font-weight: 600; color: #374151; }
.info-row {
    display: flex; gap: 10px; padding: 9px 0;
    border-bottom: 1px solid #f8fafc; font-size: .83rem;
}
.info-row:last-child { border-bottom: none; }
.info-label { min-width: 140px; flex-shrink: 0; color: #6b7280; font-size: .74rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; padding-top: 2px; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Settings</h5>
        <small class="text-muted">Manage your pharmacy settings and preferences</small>
    </div>
    <a href="{{ route('pharmacy.account') }}"
       class="btn btn-outline-primary btn-sm rounded-pill px-3">
        <i class="fas fa-user-circle me-1"></i>Account Settings
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

    {{-- ══ Pharmacy Info (read-only) ══ --}}
    <div class="settings-section">
        <div class="settings-header">
            <div class="icon-wrap" style="background:#eff6ff">
                <i class="fas fa-store" style="color:#2563eb"></i>
            </div>
            <h6>Pharmacy Info</h6>
            <a href="{{ route('pharmacy.profile.edit') }}"
               class="btn btn-outline-primary btn-sm rounded-pill px-3 ms-auto"
               style="font-size:.72rem">
                <i class="fas fa-edit me-1"></i>Edit Profile
            </a>
        </div>
        <div class="settings-body">
            <div class="info-row">
                <div class="info-label">Pharmacy Name</div>
                <div class="fw-semibold">{{ $pharmacy->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration No.</div>
                <div>{{ $pharmacy->registration_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Pharmacist</div>
                <div>{{ $pharmacy->pharmacist_name ?? '–' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">License No.</div>
                <div>{{ $pharmacy->pharmacist_license ?? '–' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone</div>
                <div>{{ $pharmacy->phone ?? '–' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div>{{ $pharmacy->email ?? '–' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">City</div>
                <div>{{ $pharmacy->city ?? '–' }}, {{ $pharmacy->province ?? '–' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div>
                    @php
                        $sc = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','suspended'=>'secondary'];
                        $ic = ['pending'=>'fa-clock','approved'=>'fa-check-circle','rejected'=>'fa-times-circle','suspended'=>'fa-ban'];
                    @endphp
                    <span class="badge bg-{{ $sc[$pharmacy->status] ?? 'secondary' }} bg-opacity-15
                                 text-{{ $sc[$pharmacy->status] ?? 'secondary' }} rounded-pill"
                          style="font-size:.7rem">
                        <i class="fas {{ $ic[$pharmacy->status] ?? 'fa-circle' }} me-1"></i>
                        {{ ucfirst($pharmacy->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Operational Settings ══ --}}
    <div class="settings-section">
        <div class="settings-header">
            <div class="icon-wrap" style="background:#f0fdf4">
                <i class="fas fa-clock" style="color:#16a34a"></i>
            </div>
            <h6>Operational Settings</h6>
        </div>
        <div class="settings-body">
            <form action="{{ route('pharmacy.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Operating Hours --}}
                <div class="mb-4">
                    <label class="form-label form-label-sm mb-1">
                        <i class="fas fa-clock me-1 text-muted"></i>
                        Operating Hours
                    </label>
                    <textarea name="operating_hours"
                              class="form-control @error('operating_hours') is-invalid @enderror"
                              rows="4"
                              placeholder="e.g. Mon–Fri: 8:00 AM – 9:00 PM&#10;Sat: 8:00 AM – 6:00 PM&#10;Sun: Closed"
                              style="font-size:.85rem;resize:vertical">{{ old('operating_hours', $pharmacy->operating_hours) }}</textarea>
                    @error('operating_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text" style="font-size:.71rem">
                        Describe your operating hours. Patients will see this on your profile.
                    </div>
                </div>

                {{-- Delivery --}}
                <div class="mb-4">
                    <label class="form-label form-label-sm mb-2">
                        <i class="fas fa-truck me-1 text-muted"></i>
                        Delivery Service
                    </label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   name="delivery_available" id="deliveryYes" value="1"
                                   {{ old('delivery_available', $pharmacy->delivery_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="deliveryYes"
                                   style="font-size:.85rem">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Delivery Available
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   name="delivery_available" id="deliveryNo" value="0"
                                   {{ !old('delivery_available', $pharmacy->delivery_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="deliveryNo"
                                   style="font-size:.85rem">
                                <i class="fas fa-times-circle text-danger me-1"></i>
                                No Delivery
                            </label>
                        </div>
                    </div>
                    <div class="form-text" style="font-size:.71rem">
                        This controls whether patients can place delivery orders.
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ Change Password ══ --}}
    <div class="settings-section">
        <div class="settings-header">
            <div class="icon-wrap" style="background:#faf5ff">
                <i class="fas fa-lock" style="color:#7c3aed"></i>
            </div>
            <h6>Change Password</h6>
        </div>
        <div class="settings-body">
            <form action="{{ route('pharmacy.settings.change-password') }}"
                  method="POST" id="passwordForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label form-label-sm mb-1">Current Password</label>
                    <div class="input-group input-group-sm">
                        <input type="password" name="current_password" id="currentPwd"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Enter current password">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="currentPwd">
                            <i class="fas fa-eye" style="font-size:.75rem"></i>
                        </button>
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label form-label-sm mb-1">New Password</label>
                    <div class="input-group input-group-sm">
                        <input type="password" name="new_password" id="newPwd"
                               class="form-control @error('new_password') is-invalid @enderror"
                               placeholder="Min. 8 characters">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="newPwd">
                            <i class="fas fa-eye" style="font-size:.75rem"></i>
                        </button>
                        @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label form-label-sm mb-1">Confirm New Password</label>
                    <div class="input-group input-group-sm">
                        <input type="password" name="new_password_confirmation"
                               id="confirmPwd"
                               class="form-control"
                               placeholder="Repeat new password">
                        <button type="button" class="btn btn-outline-secondary toggle-pwd"
                                data-target="confirmPwd">
                            <i class="fas fa-eye" style="font-size:.75rem"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="mt-2">
                        <div style="height:4px;background:#f1f5f9;border-radius:50px;overflow:hidden">
                            <div id="strengthBar"
                                 style="height:4px;width:0%;border-radius:50px;
                                        background:#e5e7eb;transition:all .3s"></div>
                        </div>
                        <small id="strengthText"
                               class="text-muted" style="font-size:.68rem"></small>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-purple btn-sm rounded-pill px-4"
                            style="background:#7c3aed;color:#fff;border:none">
                        <i class="fas fa-key me-1"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ══ Sidebar ══ --}}
<div class="col-lg-4">

    {{-- Quick Links --}}
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>Quick Links
            </h6>
        </div>
        <div class="card-body p-0">
            @php
                $links = [
                    ['label'=>'Edit Profile',    'icon'=>'fas fa-edit',           'color'=>'#2563eb','bg'=>'#eff6ff','route'=>'pharmacy.profile.edit'],
                    ['label'=>'Account Settings','icon'=>'fas fa-user-circle',    'color'=>'#7c3aed','bg'=>'#faf5ff','route'=>'pharmacy.account'],
                    ['label'=>'Notifications',   'icon'=>'fas fa-bell',           'color'=>'#0891b2','bg'=>'#f0f9ff','route'=>'pharmacy.notifications'],
                    ['label'=>'Dashboard',       'icon'=>'fas fa-tachometer-alt', 'color'=>'#16a34a','bg'=>'#f0fdf4','route'=>'pharmacy.dashboard'],
                ];
            @endphp
            @foreach($links as $link)
            <a href="{{ route($link['route']) }}"
               class="d-flex align-items-center gap-3 px-3 py-2 text-decoration-none
                      {{ !$loop->last ? 'border-bottom' : '' }}"
               style="color:#374151;font-size:.84rem;transition:background .15s"
               onmouseover="this.style.background='#f8fafc'"
               onmouseout="this.style.background=''">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:30px;height:30px;background:{{ $link['bg'] }};flex-shrink:0">
                    <i class="{{ $link['icon'] }}"
                       style="color:{{ $link['color'] }};font-size:.72rem"></i>
                </div>
                <span>{{ $link['label'] }}</span>
                <i class="fas fa-chevron-right ms-auto text-muted"
                   style="font-size:.6rem"></i>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Delivery Status Card --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-truck me-2 text-primary"></i>Delivery Status
            </h6>
        </div>
        <div class="card-body text-center py-4">
            @if($pharmacy->delivery_available)
            <div class="rounded-circle d-flex align-items-center justify-content-center
                        mx-auto mb-3"
                 style="width:56px;height:56px;background:#f0fdf4">
                <i class="fas fa-check-circle text-success fa-lg"></i>
            </div>
            <div class="fw-semibold text-success mb-1">Delivery Active</div>
            <small class="text-muted" style="font-size:.77rem">
                Patients can place delivery orders from your pharmacy.
            </small>
            @else
            <div class="rounded-circle d-flex align-items-center justify-content-center
                        mx-auto mb-3"
                 style="width:56px;height:56px;background:#fef2f2">
                <i class="fas fa-times-circle text-danger fa-lg"></i>
            </div>
            <div class="fw-semibold text-danger mb-1">Delivery Disabled</div>
            <small class="text-muted" style="font-size:.77rem">
                Patients cannot place delivery orders. Enable above to allow delivery.
            </small>
            @endif
        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
// Toggle password visibility
document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', function () {
        const target = document.getElementById(this.dataset.target);
        const icon   = this.querySelector('i');
        if (target.type === 'password') {
            target.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            target.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

// Password strength
document.getElementById('newPwd')?.addEventListener('input', function () {
    const val = this.value;
    const bar = document.getElementById('strengthBar');
    const txt = document.getElementById('strengthText');
    let score = 0;
    if (val.length >= 8)               score++;
    if (/[A-Z]/.test(val))             score++;
    if (/[0-9]/.test(val))             score++;
    if (/[^A-Za-z0-9]/.test(val))      score++;
    const levels = [
        { w: '0%',   c: '#e5e7eb', t: '' },
        { w: '25%',  c: '#dc2626', t: 'Weak' },
        { w: '50%',  c: '#f59e0b', t: 'Fair' },
        { w: '75%',  c: '#2563eb', t: 'Good' },
        { w: '100%', c: '#16a34a', t: 'Strong' },
    ];
    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].c;
    txt.textContent      = levels[score].t;
    txt.style.color      = levels[score].c;
});
</script>
@endpush
