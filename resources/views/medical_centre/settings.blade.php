@extends('medical_centre.layouts.master')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<style>
.mc-page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.mc-page-title{font-size:1.25rem;font-weight:800;color:var(--text-dark);margin:0 0 .2rem}
.mc-page-sub{font-size:.82rem;color:var(--text-muted);margin:0}

.settings-grid{display:grid;grid-template-columns:240px 1fr;gap:1.25rem;align-items:start}
@media(max-width:900px){.settings-grid{grid-template-columns:1fr}}

/* Nav */
.settings-nav{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow-sm);overflow:hidden;position:sticky;top:80px}
.settings-nav-item{display:flex;align-items:center;gap:.65rem;padding:.8rem 1.1rem;font-size:.82rem;font-weight:700;color:var(--text-muted);cursor:pointer;border:none;background:none;width:100%;text-align:left;font-family:inherit;border-left:3px solid transparent;transition:all .15s}
.settings-nav-item:hover{background:#f8fbff;color:var(--mc-primary)}
.settings-nav-item.active{background:#f0f7ff;color:var(--mc-primary);border-left-color:var(--mc-primary)}
.settings-nav-item i{width:16px;text-align:center;font-size:.82rem}
.settings-nav-divider{height:1px;background:var(--border);margin:.25rem 0}

/* Cards */
.settings-card{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:1.25rem}
.settings-card:last-child{margin-bottom:0}
.settings-card-head{padding:.9rem 1.2rem;border-bottom:1px solid var(--border);background:#fafbfc;display:flex;align-items:center;gap:.5rem}
.settings-card-head h6{font-size:.9rem;font-weight:800;color:var(--text-dark);margin:0}
.settings-card-body{padding:1.25rem}

/* Form */
.s-form-group{margin-bottom:1rem}
.s-form-group:last-child{margin-bottom:0}
.s-label{display:block;font-size:.72rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem}
.s-input{width:100%;padding:.55rem .9rem;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-weight:600;color:var(--text-dark);background:#fff;font-family:inherit;outline:none;transition:border-color .2s;box-sizing:border-box}
.s-input:focus{border-color:var(--mc-primary);background:#fafeff}
.s-input.is-invalid{border-color:#e74c3c}
.s-input:disabled{background:#f4f7fb;color:var(--text-muted);cursor:not-allowed}
.s-form-error{font-size:.7rem;color:#e74c3c;margin-top:.3rem;font-weight:600}
.s-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
@media(max-width:600px){.s-grid-2{grid-template-columns:1fr}}

/* Buttons */
.s-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.52rem 1.15rem;border-radius:9px;border:none;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:var(--transition)}
.s-btn-primary{background:var(--mc-primary);color:#fff}
.s-btn-primary:hover{background:var(--mc-secondary)}
.s-btn-warning{background:#fff3cd;color:#92400e;border:1.5px solid #fde68a}
.s-btn-warning:hover{background:#d97706;color:#fff;border-color:#d97706}
.s-btn-danger{background:#fee2e2;color:#991b1b;border:1.5px solid #fca5a5}
.s-btn-danger:hover{background:#e74c3c;color:#fff;border-color:#e74c3c}
.s-btn:disabled{opacity:.6;cursor:not-allowed}

/* Info row */
.s-info-row{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid var(--border);gap:1rem;flex-wrap:wrap}
.s-info-row:last-child{border-bottom:none;padding-bottom:0}
.s-info-row:first-child{padding-top:0}
.s-info-label{font-size:.75rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.2rem}
.s-info-value{font-size:.85rem;font-weight:700;color:var(--text-dark)}

/* Status badges */
.s-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .65rem;border-radius:99px;font-size:.7rem;font-weight:800}
.badge-verified{background:#d1fae5;color:#065f46}
.badge-unverified{background:#fee2e2;color:#991b1b}
.badge-approved{background:#d1fae5;color:#065f46}
.badge-pending{background:#fff3cd;color:#92400e}
.badge-suspended{background:#fee2e2;color:#991b1b}
.badge-rejected{background:#f3f4f6;color:#6b7280}

/* Strength bar */
#pwStrBar{height:4px;border-radius:99px;width:0;background:#e74c3c;transition:width .3s,background .3s}
</style>

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-cog me-2" style="color:var(--mc-primary);"></i>Settings
        </h4>
        <p class="mc-page-sub">Manage your account & security settings</p>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
     style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('info'))
<div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
     style="border-radius:10px;font-size:.83rem;border:none;background:#e0f2fe;color:#0369a1;" role="alert">
    <i class="fas fa-info-circle"></i> {{ session('info') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
     style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="settings-grid">

{{-- ══════════ LEFT NAV ══════════ --}}
<div>
    <div class="settings-nav">
        <button class="settings-nav-item active" onclick="switchSection('account', this)">
            <i class="fas fa-user-circle"></i> Account Info
        </button>
        <button class="settings-nav-item" onclick="switchSection('password', this)">
            <i class="fas fa-lock"></i> Change Password
        </button>
        <button class="settings-nav-item" onclick="switchSection('verification', this)">
            <i class="fas fa-envelope-open-text"></i> Email Verification
        </button>
        <div class="settings-nav-divider"></div>
        <button class="settings-nav-item" onclick="switchSection('danger', this)">
            <i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i>
            <span style="color:#e74c3c;">Danger Zone</span>
        </button>
    </div>
</div>

{{-- ══════════ RIGHT CONTENT ══════════ --}}
<div>

    {{-- ── Account Info ── --}}
    <div id="section-account" class="settings-section">
        <div class="settings-card">
            <div class="settings-card-head">
                <i class="fas fa-user-circle" style="color:var(--mc-primary);font-size:.9rem;"></i>
                <h6>Account Information</h6>
            </div>
            <div class="settings-card-body">

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Medical Centre</div>
                        <div class="s-info-value">{{ $mc->name }}</div>
                    </div>
                </div>

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Registration Number</div>
                        <div class="s-info-value">{{ $mc->registration_number }}</div>
                    </div>
                </div>

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Account Email</div>
                        <div class="s-info-value">{{ $user->email }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                        @if($user->email_verified_at)
                            <span class="s-badge badge-verified">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @else
                            <span class="s-badge badge-unverified">
                                <i class="fas fa-times-circle"></i> Not Verified
                            </span>
                        @endif
                    </div>
                </div>

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Account Status</div>
                        @php
                            $stMap = [
                                'approved'  => ['badge-approved',  'fa-check-circle', 'Approved'],
                                'pending'   => ['badge-pending',   'fa-clock',        'Pending Approval'],
                                'suspended' => ['badge-suspended', 'fa-ban',          'Suspended'],
                                'rejected'  => ['badge-rejected',  'fa-times-circle', 'Rejected'],
                            ];
                            [$stClass, $stIcon, $stLabel] = $stMap[$mc->status] ?? ['badge-pending','fa-clock','Unknown'];
                        @endphp
                        <span class="s-badge {{ $stClass }}">
                            <i class="fas {{ $stIcon }}"></i> {{ $stLabel }}
                        </span>
                    </div>
                </div>

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Member Since</div>
                        <div class="s-info-value">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                <div class="s-info-row">
                    <div>
                        <div class="s-info-label">Last Updated</div>
                        <div class="s-info-value">
                            {{ \Carbon\Carbon::parse($mc->updated_at)->diffForHumans() }}
                        </div>
                    </div>
                    <a href="{{ route('medical_centre.profile') }}"
                       class="s-btn s-btn-primary" style="font-size:.75rem;padding:.4rem .9rem;">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Change Password ── --}}
    <div id="section-password" class="settings-section" style="display:none;">
        <div class="settings-card">
            <div class="settings-card-head">
                <i class="fas fa-lock" style="color:var(--mc-primary);font-size:.9rem;"></i>
                <h6>Change Password</h6>
            </div>
            <div class="settings-card-body">
                <form method="POST"
                      action="{{ route('medical_centre.settings.password') }}">
                    @csrf

                    <div class="s-form-group">
                        <label class="s-label">Current Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="current_password" id="currPw"
                                   class="s-input {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                   placeholder="Enter current password">
                            <button type="button" onclick="togglePw('currPw','currEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="currEye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="s-form-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="s-form-group">
                        <label class="s-label">New Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="newPw"
                                   class="s-input"
                                   placeholder="Min. 8 characters"
                                   oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePw('newPw','newEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="newEye"></i>
                            </button>
                        </div>
                        <div style="margin-top:.4rem;background:#f0f0f0;border-radius:99px;height:4px;overflow:hidden;">
                            <div id="pwStrBar"></div>
                        </div>
                        <div id="pwStrLabel" style="font-size:.68rem;font-weight:700;margin-top:.2rem;min-height:1em;"></div>
                    </div>

                    <div class="s-form-group">
                        <label class="s-label">Confirm New Password <span style="color:#e74c3c">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password_confirmation" id="confPw"
                                   class="s-input"
                                   placeholder="Repeat new password">
                            <button type="button" onclick="togglePw('confPw','confEye')"
                                    style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;">
                                <i class="fas fa-eye" id="confEye"></i>
                            </button>
                        </div>
                    </div>

                    <div style="background:#f8fbff;border-radius:9px;padding:.75rem 1rem;font-size:.78rem;color:var(--text-muted);font-weight:600;margin-bottom:1rem;">
                        <i class="fas fa-shield-alt me-2" style="color:var(--mc-primary);"></i>
                        Use at least 8 characters with uppercase, numbers & symbols.
                    </div>

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="s-btn s-btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Email Verification ── --}}
    <div id="section-verification" class="settings-section" style="display:none;">
        <div class="settings-card">
            <div class="settings-card-head">
                <i class="fas fa-envelope-open-text" style="color:var(--mc-primary);font-size:.9rem;"></i>
                <h6>Email Verification</h6>
            </div>
            <div class="settings-card-body">

                @if($user->email_verified_at)
                    <div style="display:flex;align-items:center;gap:1rem;padding:1.25rem;background:#f0fdf4;border-radius:10px;border:1.5px solid #a7f3d0;margin-bottom:1rem;">
                        <div style="width:48px;height:48px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-check-circle" style="color:#059669;font-size:1.3rem;"></i>
                        </div>
                        <div>
                            <p style="font-size:.88rem;font-weight:800;color:#065f46;margin:0 0 .2rem;">Email Verified</p>
                            <p style="font-size:.75rem;color:#047857;margin:0;font-weight:600;">
                                Verified on {{ \Carbon\Carbon::parse($user->email_verified_at)->format('M d, Y · h:i A') }}
                            </p>
                        </div>
                    </div>
                    <div class="s-info-row" style="border:none;padding:0;">
                        <div>
                            <div class="s-info-label">Verified Email</div>
                            <div class="s-info-value">{{ $user->email }}</div>
                        </div>
                    </div>
                @else
                    <div style="display:flex;align-items:center;gap:1rem;padding:1.25rem;background:#fff3cd;border-radius:10px;border:1.5px solid #fde68a;margin-bottom:1.25rem;">
                        <div style="width:48px;height:48px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-exclamation-triangle" style="color:#d97706;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <p style="font-size:.88rem;font-weight:800;color:#92400e;margin:0 0 .2rem;">Email Not Verified</p>
                            <p style="font-size:.75rem;color:#b45309;margin:0;font-weight:600;">
                                {{ $user->email }} — verification pending
                            </p>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('medical_centre.resend.verification') }}">
                        @csrf
                        <p style="font-size:.82rem;color:var(--text-muted);font-weight:600;margin-bottom:1rem;line-height:1.7;">
                            Click the button below to resend the verification email to
                            <strong style="color:var(--text-dark);">{{ $user->email }}</strong>.
                            Check your inbox and spam folder.
                        </p>
                        <button type="submit" class="s-btn s-btn-warning">
                            <i class="fas fa-paper-plane"></i> Resend Verification Email
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Danger Zone ── --}}
    <div id="section-danger" class="settings-section" style="display:none;">
        <div class="settings-card" style="border-color:#fca5a5;">
            <div class="settings-card-head" style="background:#fff5f5;border-bottom-color:#fca5a5;">
                <i class="fas fa-exclamation-triangle" style="color:#e74c3c;font-size:.9rem;"></i>
                <h6 style="color:#991b1b;">Danger Zone</h6>
            </div>
            <div class="settings-card-body">

                {{-- Logout All Devices --}}
                <div class="s-info-row">
                    <div>
                        <div class="s-info-label" style="color:#991b1b;">Logout All Devices</div>
                        <div style="font-size:.8rem;color:var(--text-muted);font-weight:600;margin-top:.2rem;">
                            Sign out from all other active sessions.
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}"
                          onsubmit="return confirm('Logout from all devices?')">
                        @csrf
                        <button type="submit" class="s-btn s-btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout All
                        </button>
                    </form>
                </div>

                {{-- Deactivate Note --}}
                <div class="s-info-row" style="border:none;padding-bottom:0;">
                    <div>
                        <div class="s-info-label" style="color:#991b1b;">Account Deactivation</div>
                        <div style="font-size:.8rem;color:var(--text-muted);font-weight:600;margin-top:.2rem;">
                            To deactivate or delete your account, please contact admin support.
                        </div>
                    </div>
                    <a href="mailto:support@healthnet.lk"
                       class="s-btn s-btn-danger">
                        <i class="fas fa-envelope"></i> Contact Support
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
{{-- END RIGHT --}}

</div>
{{-- END SETTINGS GRID --}}

<script>
// ── Section Switch ──────────────────────────────────
function switchSection(name, btn) {
    document.querySelectorAll('.settings-section').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.settings-nav-item').forEach(b => b.classList.remove('active'));
    document.getElementById('section-' + name).style.display = 'block';
    btn.classList.add('active');
}

// ── Password Toggle ─────────────────────────────────
function togglePw(id, eyeId) {
    const inp = document.getElementById(id);
    const eye = document.getElementById(eyeId);
    inp.type  = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// ── Password Strength ───────────────────────────────
function checkStrength(val) {
    let s = 0;
    if (val.length >= 8)          s++;
    if (/[A-Z]/.test(val))        s++;
    if (/[0-9]/.test(val))        s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const lvl = [
        {w:'0',   c:'#e74c3c', t:''},
        {w:'25%', c:'#e74c3c', t:'Weak'},
        {w:'50%', c:'#f59e0b', t:'Fair'},
        {w:'75%', c:'#3b82f6', t:'Good'},
        {w:'100%',c:'#059669', t:'Strong'},
    ][s];
    const bar = document.getElementById('pwStrBar');
    bar.style.width      = lvl.w;
    bar.style.background = lvl.c;
    const lbl = document.getElementById('pwStrLabel');
    lbl.textContent = lvl.t;
    lbl.style.color  = lvl.c;
}

// ── Auto open section on error ──────────────────────
@if($errors->hasAny(['current_password','password']))
    switchSection('password', document.querySelectorAll('.settings-nav-item')[1]);
@endif

// ── Submit loading ──────────────────────────────────
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('[type=submit]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
    });
});
</script>
@endsection
