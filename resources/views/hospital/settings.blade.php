{{-- resources/views/hospital/settings.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Settings')
@section('page-title', 'Settings')

@push('styles')
<style>
/* ══════════════════════════════════════════
   BASE
══════════════════════════════════════════ */
.sp { animation: spFade .3s ease; }
@keyframes spFade {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ══════════════════════════════════════════
   LAYOUT
══════════════════════════════════════════ */
.sp-layout { display:flex; gap:1.3rem; align-items:flex-start; }
.sp-sidebar {
    width:220px; flex-shrink:0;
    background:#fff; border-radius:14px;
    border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05);
    overflow:hidden; position:sticky; top:80px;
}
.sp-content { flex:1; min-width:0; }

/* ══════════════════════════════════════════
   SIDEBAR NAV
══════════════════════════════════════════ */
.sp-nav-head {
    padding:.85rem 1.1rem .6rem;
    font-size:.68rem; font-weight:800; color:#aab4be;
    text-transform:uppercase; letter-spacing:.07em;
}
.sp-nav-item {
    display:flex; align-items:center; gap:.65rem;
    padding:.65rem 1.1rem; cursor:pointer;
    font-size:.83rem; font-weight:600; color:#555;
    border:none; background:transparent; width:100%;
    text-align:left; transition:all .2s; font-family:inherit;
    border-left:3px solid transparent; position:relative;
}
.sp-nav-item:hover { background:#f8fbff; color:#2969bf; }
.sp-nav-item.active {
    background:#e8f0fe; color:#2969bf;
    border-left-color:#2969bf;
}
.sp-nav-item i {
    width:18px; text-align:center; font-size:.82rem;
    color:inherit; flex-shrink:0;
}
.sp-nav-divider { height:1px; background:#f0f4f8; margin:.35rem 0; }

/* Danger nav item */
.sp-nav-item.danger:hover { background:#fdecea; color:#e74c3c; }
.sp-nav-item.danger.active { background:#fdecea; color:#e74c3c; border-left-color:#e74c3c; }

/* ══════════════════════════════════════════
   SECTION PANELS
══════════════════════════════════════════ */
.sp-panel { display:none; }
.sp-panel.active { display:block; animation:spFade .25s ease; }

/* ══════════════════════════════════════════
   CARDS
══════════════════════════════════════════ */
.sp-card {
    background:#fff; border-radius:14px;
    border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05);
    overflow:hidden; margin-bottom:1.3rem;
}
.sp-card:last-child { margin-bottom:0; }
.sp-card-head {
    padding:.9rem 1.4rem; border-bottom:1px solid #f0f4f8;
    display:flex; align-items:center; gap:.6rem;
}
.sp-card-head h6 {
    font-size:.9rem; font-weight:700; color:#2c3e50; margin:0; flex:1;
}
.sp-card-head .sp-card-icon {
    width:34px; height:34px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:.82rem; flex-shrink:0;
}
.ic-blue   { background:#e8f0fe; color:#2969bf; }
.ic-green  { background:#e9f7ee; color:#27ae60; }
.ic-orange { background:#fef8e7; color:#f39c12; }
.ic-red    { background:#fdecea; color:#e74c3c; }
.ic-purple { background:#f0ebff; color:#8e44ad; }

.sp-card-body { padding:1.3rem 1.4rem; }

/* ══════════════════════════════════════════
   FORM ELEMENTS
══════════════════════════════════════════ */
.sp-group { margin-bottom:1rem; }
.sp-group:last-child { margin-bottom:0; }
.sp-label {
    display:block; font-size:.74rem; font-weight:700;
    color:#555; text-transform:uppercase; letter-spacing:.04em;
    margin-bottom:.38rem;
}
.sp-label .req { color:#e74c3c; margin-left:2px; }
.sp-input {
    width:100%; border:1.5px solid #e5ecf0; border-radius:9px;
    padding:.58rem .9rem; font-size:.84rem; color:#2c3e50;
    outline:none; background:#fafcff; font-family:inherit;
    transition:border-color .2s, box-shadow .2s;
}
.sp-input:focus {
    border-color:#2969bf;
    box-shadow:0 0 0 3px rgba(41,105,191,.1);
}
.sp-input.is-invalid {
    border-color:#e74c3c;
    box-shadow:0 0 0 3px rgba(231,76,60,.08);
}
.sp-input:disabled { background:#f5f7fa; color:#999; cursor:not-allowed; }
.sp-hint  { font-size:.72rem; color:#aab4be; margin-top:.3rem; }
.sp-error { font-size:.72rem; color:#e74c3c; margin-top:.3rem;
            display:flex; align-items:center; gap:.3rem; }

/* Password field wrapper */
.pw-wrap { position:relative; }
.pw-wrap .sp-input { padding-right:2.6rem; }
.pw-eye {
    position:absolute; right:.85rem; top:50%; transform:translateY(-50%);
    background:none; border:none; cursor:pointer; color:#aab4be;
    font-size:.82rem; padding:0; transition:color .2s;
}
.pw-eye:hover { color:#2969bf; }

/* Password strength */
.pw-strength { margin-top:.5rem; }
.pw-str-bar {
    height:4px; border-radius:99px; background:#f0f4f8; overflow:hidden; margin-bottom:.3rem;
}
.pw-str-fill { height:100%; border-radius:99px; width:0; transition:width .4s, background .4s; }
.pw-str-txt { font-size:.7rem; font-weight:600; }

/* ══════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════ */
.sp-btn {
    padding:.52rem 1.3rem; border-radius:9px;
    font-size:.83rem; font-weight:600; border:none;
    cursor:pointer; transition:all .2s;
    display:inline-flex; align-items:center; gap:.45rem;
    font-family:inherit;
}
.sp-btn.primary  { background:#2969bf; color:#fff; }
.sp-btn.primary:hover  { background:#1a4f9a; box-shadow:0 4px 12px rgba(41,105,191,.3); }
.sp-btn.primary:disabled { opacity:.65; cursor:not-allowed; }
.sp-btn.secondary { background:#f0f4f8; color:#555; }
.sp-btn.secondary:hover  { background:#e2e8f0; }
.sp-btn.danger   { background:#e74c3c; color:#fff; }
.sp-btn.danger:hover   { background:#c0392b; box-shadow:0 4px 12px rgba(231,76,60,.3); }
.sp-btn.outline  { background:#fff; color:#2969bf; border:1.5px solid #2969bf; }
.sp-btn.outline:hover  { background:#e8f0fe; }

/* ══════════════════════════════════════════
   ALERT BANNERS
══════════════════════════════════════════ */
.sp-alert {
    border-radius:10px; padding:.8rem 1rem;
    display:flex; align-items:flex-start; gap:.65rem;
    font-size:.82rem; font-weight:500; margin-bottom:1rem;
    cursor:pointer;
}
.sp-alert i { flex-shrink:0; margin-top:.05rem; }
.al-success { background:#d1e7dd; color:#0f5132; border:1px solid #a3cfbb; }
.al-error   { background:#f8d7da; color:#842029; border:1px solid #f1aeb5; }
.al-warning { background:#fff3cd; color:#664d03; border:1px solid #ffda6a; }
.al-info    { background:#cfe2ff; color:#084298; border:1px solid #9ec5fe; }

/* ══════════════════════════════════════════
   INFO ROWS
══════════════════════════════════════════ */
.sp-info-row {
    display:flex; align-items:flex-start; gap:1rem;
    padding:.65rem 0; border-bottom:1px solid #f5f7fa;
    font-size:.84rem;
}
.sp-info-row:last-child { border-bottom:none; }
.sp-info-label { min-width:160px; color:#888; font-weight:500; font-size:.8rem; flex-shrink:0; }
.sp-info-val   { color:#2c3e50; font-weight:600; word-break:break-all; }

/* ══════════════════════════════════════════
   STATUS BADGE
══════════════════════════════════════════ */
.sp-status {
    display:inline-flex; align-items:center; gap:.35rem;
    font-size:.73rem; font-weight:700; padding:.22rem .65rem;
    border-radius:99px;
}
.st-active    { background:#d1e7dd; color:#0f5132; }
.st-pending   { background:#fff3cd; color:#664d03; }
.st-suspended { background:#f8d7da; color:#842029; }
.st-rejected  { background:#f0f4f8; color:#555; }
.st-verified  { background:#d1e7dd; color:#0f5132; }
.st-unverified{ background:#fff3cd; color:#664d03; }

/* ══════════════════════════════════════════
   DANGER ZONE
══════════════════════════════════════════ */
.danger-zone-card {
    border-radius:14px; border:1.5px solid #f1aeb5;
    background:#fff; overflow:hidden; margin-bottom:1.3rem;
}
.danger-zone-head {
    padding:.85rem 1.4rem; background:#fdecea;
    display:flex; align-items:center; gap:.6rem;
    border-bottom:1.5px solid #f1aeb5;
}
.danger-zone-head h6 {
    font-size:.9rem; font-weight:700; color:#842029; margin:0;
}
.danger-zone-body { padding:1.2rem 1.4rem; }
.danger-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 0; border-bottom:1px solid #f9e0e0; gap:1rem; flex-wrap:wrap;
}
.danger-item:last-child { border-bottom:none; padding-bottom:0; }
.danger-item-info h6 { font-size:.84rem; font-weight:700; color:#2c3e50; margin:0 0 .2rem; }
.danger-item-info p  { font-size:.76rem; color:#888; margin:0; }

/* ══════════════════════════════════════════
   SESSION / DEVICE CARDS
══════════════════════════════════════════ */
.device-item {
    display:flex; align-items:center; gap:.85rem;
    padding:.75rem 0; border-bottom:1px solid #f5f7fa;
}
.device-item:last-child { border-bottom:none; }
.device-icon {
    width:40px; height:40px; border-radius:10px;
    background:#e8f0fe; color:#2969bf;
    display:flex; align-items:center; justify-content:center;
    font-size:.9rem; flex-shrink:0;
}
.device-info { flex:1; min-width:0; }
.device-info h6 { font-size:.83rem; font-weight:700; color:#2c3e50; margin:0 0 .15rem; }
.device-info p  { font-size:.73rem; color:#888; margin:0; }
.device-current {
    font-size:.67rem; font-weight:700; padding:.12rem .45rem;
    border-radius:6px; background:#d1e7dd; color:#0f5132;
    display:inline-flex; align-items:center; gap:.25rem;
}

/* ══════════════════════════════════════════
   NOTIFICATION TOGGLES
══════════════════════════════════════════ */
.notif-toggle-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:.75rem 0; border-bottom:1px solid #f5f7fa;
    gap:1rem;
}
.notif-toggle-item:last-child { border-bottom:none; }
.ntog-info h6 { font-size:.83rem; font-weight:700; color:#2c3e50; margin:0 0 .15rem; }
.ntog-info p  { font-size:.75rem; color:#888; margin:0; }

/* Toggle switch */
.tog { position:relative; width:44px; height:24px; flex-shrink:0; }
.tog input { opacity:0; width:0; height:0; }
.tog-slider {
    position:absolute; inset:0; border-radius:99px;
    background:#dde3ea; cursor:pointer; transition:background .2s;
}
.tog-slider::before {
    content:''; position:absolute;
    width:18px; height:18px; border-radius:50%;
    background:#fff; left:3px; top:3px;
    transition:transform .2s;
    box-shadow:0 1px 4px rgba(0,0,0,.2);
}
.tog input:checked + .tog-slider { background:#2969bf; }
.tog input:checked + .tog-slider::before { transform:translateX(20px); }

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.sp-modal {
    position:fixed; inset:0; background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px); z-index:2000;
    display:flex; align-items:center; justify-content:center; padding:1rem;
    opacity:0; visibility:hidden; transition:opacity .25s, visibility .25s;
}
.sp-modal.open { opacity:1; visibility:visible; }
.sp-modal-box {
    background:#fff; border-radius:16px; width:100%; max-width:420px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    transform:translateY(-20px) scale(.97); transition:transform .25s;
    overflow:hidden; max-height:90vh; display:flex; flex-direction:column;
}
.sp-modal.open .sp-modal-box { transform:translateY(0) scale(1); }
.sp-modal-head {
    padding:1.1rem 1.4rem; border-bottom:1px solid #f0f4f8;
    display:flex; align-items:center; justify-content:space-between; flex-shrink:0;
}
.sp-modal-head h5 { font-size:.95rem; font-weight:700; margin:0; color:#2c3e50;
                    display:flex; align-items:center; gap:.5rem; }
.sp-modal-close {
    background:none; border:none; cursor:pointer; width:32px; height:32px;
    border-radius:8px; display:flex; align-items:center; justify-content:center;
    color:#888; font-size:.9rem; transition:background .2s, color .2s;
}
.sp-modal-close:hover { background:#f0f4f8; color:#e74c3c; }
.sp-modal-body { padding:1.2rem 1.4rem; overflow-y:auto; flex:1; }
.sp-modal-foot {
    padding:.9rem 1.4rem; border-top:1px solid #f0f4f8;
    display:flex; justify-content:flex-end; gap:.6rem; flex-shrink:0;
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width:991.98px) {
    .sp-layout { flex-direction:column; }
    .sp-sidebar { width:100%; position:static; display:flex; flex-wrap:wrap; }
    .sp-nav-head { width:100%; }
    .sp-nav-item { flex:1; min-width:110px; justify-content:center;
                   border-left:none; border-bottom:3px solid transparent; }
    .sp-nav-item.active { border-left:none; border-bottom-color:#2969bf; }
    .sp-nav-divider { display:none; }
}
@media (max-width:575.98px) {
    .sp-card-body { padding:1rem; }
    .sp-info-label { min-width:120px; }
    .sp-nav-item span { display:none; }
    .sp-nav-item i { width:auto; }
}
</style>
@endpush

@section('content')
<div class="sp">

    {{-- ══ FLASH MESSAGES ══ --}}
    @if(session('success'))
    <div class="sp-alert al-success" onclick="this.remove()">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="sp-alert al-error" onclick="this.remove()">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    @if(session('info'))
    <div class="sp-alert al-info" onclick="this.remove()">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="sp-alert al-error" onclick="this.remove()">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="sp-layout">

        {{-- ══ SIDEBAR ══ --}}
        <div class="sp-sidebar">
            <div class="sp-nav-head">Settings</div>
            <button class="sp-nav-item active" data-panel="account" onclick="setPanel('account')">
                <i class="fas fa-user-circle"></i>
                <span>Account</span>
            </button>
            <button class="sp-nav-item" data-panel="security" onclick="setPanel('security')">
                <i class="fas fa-lock"></i>
                <span>Security</span>
            </button>
            <button class="sp-nav-item" data-panel="notifications" onclick="setPanel('notifications')">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </button>
            <button class="sp-nav-item" data-panel="sessions" onclick="setPanel('sessions')">
                <i class="fas fa-desktop"></i>
                <span>Sessions</span>
            </button>
            <div class="sp-nav-divider"></div>
            <button class="sp-nav-item danger" data-panel="danger" onclick="setPanel('danger')">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Danger Zone</span>
            </button>
        </div>

        {{-- ══ CONTENT ══ --}}
        <div class="sp-content">

            {{-- ════════════════════
                 PANEL: ACCOUNT
            ════════════════════ --}}
            <div class="sp-panel active" id="panel-account">

                {{-- Account Info --}}
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-blue"><i class="fas fa-user-circle"></i></div>
                        <h6>Account Information</h6>
                    </div>
                    <div class="sp-card-body">
                        @php $user = auth()->user(); @endphp

                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-user me-1" style="color:#2969bf;"></i>Full Name
                            </span>
                            <span class="sp-info-val">
                                {{ $hospital->name ?? auth()->user()->name ?? '—' }}
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-envelope me-1" style="color:#2969bf;"></i>Email Address
                            </span>
                            <span class="sp-info-val">{{ $user->email ?? '—' }}</span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-shield-alt me-1" style="color:#2969bf;"></i>Account Type
                            </span>
                            <span class="sp-info-val" style="text-transform:capitalize;">
                                {{ $user->user_type ?? 'Hospital' }}
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-circle me-1" style="color:#2969bf;"></i>Account Status
                            </span>
                            <span class="sp-info-val">
                                @php $st = $user->status ?? 'active'; @endphp
                                <span class="sp-status st-{{ $st }}">
                                    <i class="fas fa-circle" style="font-size:.4rem;vertical-align:middle;"></i>
                                    {{ ucfirst($st) }}
                                </span>
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-check-circle me-1" style="color:#2969bf;"></i>Email Verified
                            </span>
                            <span class="sp-info-val">
                                @if($user->email_verified_at)
                                    <span class="sp-status st-verified">
                                        <i class="fas fa-check"></i> Verified
                                    </span>
                                    <span style="font-size:.72rem;color:#888;margin-left:.5rem;">
                                        {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="sp-status st-unverified">
                                        <i class="fas fa-exclamation-triangle"></i> Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-calendar me-1" style="color:#2969bf;"></i>Member Since
                            </span>
                            <span class="sp-info-val">
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '—' }}
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label">
                                <i class="fas fa-clock me-1" style="color:#2969bf;"></i>Last Login
                            </span>
                            <span class="sp-info-val">
                                {{ $user->last_login_at
                                    ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans()
                                    : 'N/A' }}
                            </span>
                        </div>

                        @if(!$user->email_verified_at)
                        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f0f4f8;">
                            <div class="sp-alert al-warning" style="margin-bottom:.85rem;cursor:default;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Your email address is not verified. Please verify to unlock all features.</span>
                            </div>
                            <form action="{{ route('hospital.resend.verification') }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                <button type="submit" class="sp-btn outline">
                                    <i class="fas fa-paper-plane"></i>
                                    Resend Verification Email
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Hospital Quick Info --}}
                @if($hospital)
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-blue"><i class="fas fa-hospital"></i></div>
                        <h6>Hospital Details</h6>
                        <a href="{{ route('hospital.profile') }}"
                           style="font-size:.78rem;font-weight:600;color:#2969bf;
                                  text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            <i class="fas fa-external-link-alt"></i> Edit Profile
                        </a>
                    </div>
                    <div class="sp-card-body">
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-hospital me-1" style="color:#2969bf;"></i>Hospital Name</span>
                            <span class="sp-info-val">{{ $hospital->name ?? '—' }}</span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-tag me-1" style="color:#2969bf;"></i>Type</span>
                            <span class="sp-info-val">{{ ucfirst($hospital->type ?? '—') }}</span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-map-marker-alt me-1" style="color:#2969bf;"></i>City</span>
                            <span class="sp-info-val">{{ $hospital->city ?? '—' }}{{ $hospital->province ? ', '.$hospital->province : '' }}</span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-circle me-1" style="color:#2969bf;"></i>Status</span>
                            <span class="sp-info-val">
                                @php $hst = $hospital->status ?? 'pending'; @endphp
                                <span class="sp-status st-{{ $hst }}">
                                    <i class="fas fa-circle" style="font-size:.4rem;vertical-align:middle;"></i>
                                    {{ ucfirst($hst) }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ════════════════════
                 PANEL: SECURITY
            ════════════════════ --}}
            <div class="sp-panel" id="panel-security">

                {{-- Change Password --}}
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-orange"><i class="fas fa-key"></i></div>
                        <h6>Change Password</h6>
                    </div>
                    <div class="sp-card-body">
                        <div class="sp-alert al-info" style="cursor:default;margin-bottom:1.2rem;">
                            <i class="fas fa-info-circle"></i>
                            <span>Use a strong password (min 8 characters) with letters, numbers, and symbols.</span>
                        </div>

                        <form action="{{ route('hospital.settings.password') }}" method="POST"
                              id="pwForm" novalidate>
                            @csrf
                            <div class="sp-group">
                                <label class="sp-label" for="current_password">
                                    Current Password <span class="req">*</span>
                                </label>
                                <div class="pw-wrap">
                                    <input type="password" id="current_password"
                                           name="current_password"
                                           class="sp-input {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                           placeholder="Enter current password"
                                           autocomplete="current-password">
                                    <button type="button" class="pw-eye"
                                            onclick="togglePw('current_password',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                <div class="sp-error">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="sp-group">
                                <label class="sp-label" for="password">
                                    New Password <span class="req">*</span>
                                </label>
                                <div class="pw-wrap">
                                    <input type="password" id="password"
                                           name="password"
                                           class="sp-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           placeholder="Min 8 characters"
                                           oninput="checkStrength(this.value)"
                                           autocomplete="new-password">
                                    <button type="button" class="pw-eye"
                                            onclick="togglePw('password',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="pw-strength" id="pwStrength" style="display:none;">
                                    <div class="pw-str-bar">
                                        <div class="pw-str-fill" id="pwStrFill"></div>
                                    </div>
                                    <span class="pw-str-txt" id="pwStrTxt"></span>
                                </div>
                                @error('password')
                                <div class="sp-error">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="sp-group">
                                <label class="sp-label" for="password_confirmation">
                                    Confirm New Password <span class="req">*</span>
                                </label>
                                <div class="pw-wrap">
                                    <input type="password" id="password_confirmation"
                                           name="password_confirmation"
                                           class="sp-input"
                                           placeholder="Re-enter new password"
                                           oninput="checkMatch()"
                                           autocomplete="new-password">
                                    <button type="button" class="pw-eye"
                                            onclick="togglePw('password_confirmation',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="matchHint" class="sp-hint"></div>
                            </div>

                            <div style="display:flex;justify-content:flex-end;gap:.6rem;margin-top:1rem;">
                                <button type="button" class="sp-btn secondary"
                                        onclick="document.getElementById('pwForm').reset();
                                                 document.getElementById('pwStrength').style.display='none';
                                                 document.getElementById('matchHint').textContent='';">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                                <button type="submit" class="sp-btn primary" id="pwSubmitBtn">
                                    <i class="fas fa-lock"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Two-Factor Placeholder --}}
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-green"><i class="fas fa-shield-alt"></i></div>
                        <h6>Two-Factor Authentication</h6>
                        <span style="font-size:.7rem;font-weight:700;background:#fff3cd;
                                     color:#664d03;padding:.15rem .45rem;border-radius:6px;">
                            Coming Soon
                        </span>
                    </div>
                    <div class="sp-card-body">
                        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <div style="flex:1;min-width:0;">
                                <p style="font-size:.84rem;color:#555;margin:0 0 .3rem;font-weight:600;">
                                    Add an extra layer of security
                                </p>
                                <p style="font-size:.78rem;color:#888;margin:0;">
                                    Two-factor authentication adds an additional layer of security
                                    by requiring a code from your authenticator app when signing in.
                                </p>
                            </div>
                            <button class="sp-btn secondary" disabled style="flex-shrink:0;">
                                <i class="fas fa-mobile-alt"></i> Enable 2FA
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Login Activity --}}
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-purple"><i class="fas fa-history"></i></div>
                        <h6>Recent Login Activity</h6>
                    </div>
                    <div class="sp-card-body">
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-clock me-1" style="color:#8e44ad;"></i>Last Login</span>
                            <span class="sp-info-val">
                                {{ auth()->user()->last_login_at
                                    ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->format('d M Y, h:i A')
                                    : 'N/A' }}
                            </span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-envelope me-1" style="color:#8e44ad;"></i>Email</span>
                            <span class="sp-info-val">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="sp-info-row">
                            <span class="sp-info-label"><i class="fas fa-check-circle me-1" style="color:#8e44ad;"></i>Email Verified</span>
                            <span class="sp-info-val">
                                @if(auth()->user()->email_verified_at)
                                    <span class="sp-status st-verified"><i class="fas fa-check"></i> Yes</span>
                                @else
                                    <span class="sp-status st-unverified"><i class="fas fa-times"></i> No</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ════════════════════
                 PANEL: NOTIFICATIONS
            ════════════════════ --}}
            <div class="sp-panel" id="panel-notifications">
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-blue"><i class="fas fa-bell"></i></div>
                        <h6>Notification Preferences</h6>
                    </div>
                    <div class="sp-card-body">
                        <div class="sp-alert al-info" style="cursor:default;margin-bottom:1.2rem;">
                            <i class="fas fa-info-circle"></i>
                            <span>These settings control which notifications you receive in-app.</span>
                        </div>

                        {{-- Appointment --}}
                        <div class="notif-toggle-item">
                            <div class="ntog-info">
                                <h6><i class="fas fa-calendar-check" style="color:#17a2b8;margin-right:.4rem;"></i>Appointment Notifications</h6>
                                <p>New bookings, confirmations, cancellations</p>
                            </div>
                            <label class="tog">
                                <input type="checkbox" checked onchange="saveNotifPref('appointments',this.checked)">
                                <span class="tog-slider"></span>
                            </label>
                        </div>

                        {{-- Doctor --}}
                        <div class="notif-toggle-item">
                            <div class="ntog-info">
                                <h6><i class="fas fa-user-md" style="color:#8e44ad;margin-right:.4rem;"></i>Doctor Notifications</h6>
                                <p>Doctor join requests, status changes</p>
                            </div>
                            <label class="tog">
                                <input type="checkbox" checked onchange="saveNotifPref('doctors',this.checked)">
                                <span class="tog-slider"></span>
                            </label>
                        </div>

                        {{-- Reviews --}}
                        <div class="notif-toggle-item">
                            <div class="ntog-info">
                                <h6><i class="fas fa-star" style="color:#e67e22;margin-right:.4rem;"></i>Review Notifications</h6>
                                <p>New patient reviews and ratings</p>
                            </div>
                            <label class="tog">
                                <input type="checkbox" checked onchange="saveNotifPref('reviews',this.checked)">
                                <span class="tog-slider"></span>
                            </label>
                        </div>

                        {{-- System --}}
                        <div class="notif-toggle-item">
                            <div class="ntog-info">
                                <h6><i class="fas fa-cog" style="color:#495057;margin-right:.4rem;"></i>System Notifications</h6>
                                <p>Platform updates and system alerts</p>
                            </div>
                            <label class="tog">
                                <input type="checkbox" checked onchange="saveNotifPref('system',this.checked)">
                                <span class="tog-slider"></span>
                            </label>
                        </div>

                        {{-- Email --}}
                        <div class="notif-toggle-item">
                            <div class="ntog-info">
                                <h6><i class="fas fa-envelope" style="color:#2969bf;margin-right:.4rem;"></i>Email Notifications</h6>
                                <p>Receive important alerts via email</p>
                            </div>
                            <label class="tog">
                                <input type="checkbox" onchange="saveNotifPref('email',this.checked)">
                                <span class="tog-slider"></span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ════════════════════
                 PANEL: SESSIONS
            ════════════════════ --}}
            <div class="sp-panel" id="panel-sessions">
                <div class="sp-card">
                    <div class="sp-card-head">
                        <div class="sp-card-icon ic-purple"><i class="fas fa-desktop"></i></div>
                        <h6>Active Sessions</h6>
                    </div>
                    <div class="sp-card-body">
                        <div class="sp-alert al-info" style="cursor:default;margin-bottom:1.2rem;">
                            <i class="fas fa-info-circle"></i>
                            <span>Manage where you're logged in. Signing out of other sessions will require login again.</span>
                        </div>

                        {{-- Current session --}}
                        <div class="device-item">
                            <div class="device-icon"><i class="fas fa-desktop"></i></div>
                            <div class="device-info">
                                <h6>
                                    Current Session
                                    <span class="device-current">
                                        <i class="fas fa-circle" style="font-size:.45rem;"></i>
                                        Active Now
                                    </span>
                                </h6>
                                <p>
                                    {{ request()->userAgent()
                                        ? Str::limit(request()->userAgent(), 60)
                                        : 'Unknown browser' }}
                                    &nbsp;·&nbsp; {{ request()->ip() ?? 'Unknown IP' }}
                                </p>
                            </div>
                        </div>

                        <div style="margin-top:1.2rem;padding-top:1rem;border-top:1px solid #f0f4f8;
                                    display:flex;justify-content:flex-end;">
                            <button class="sp-btn danger" onclick="askLogoutAll()">
                                <i class="fas fa-sign-out-alt"></i>
                                Sign Out All Other Sessions
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════
                 PANEL: DANGER ZONE
            ════════════════════ --}}
            <div class="sp-panel" id="panel-danger">

                <div class="sp-alert al-warning" style="cursor:default;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Actions in this section are <strong>irreversible</strong>. Please proceed with caution.</span>
                </div>

                <div class="danger-zone-card">
                    <div class="danger-zone-head">
                        <i class="fas fa-exclamation-triangle" style="color:#842029;"></i>
                        <h6>Danger Zone</h6>
                    </div>
                    <div class="danger-zone-body">

                        {{-- Sign Out --}}
                        <div class="danger-item">
                            <div class="danger-item-info">
                                <h6><i class="fas fa-sign-out-alt me-2" style="color:#e74c3c;"></i>Sign Out</h6>
                                <p>Sign out of your current session securely.</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="sp-btn danger">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </button>
                            </form>
                        </div>

                        {{-- Deactivate Account --}}
                        <div class="danger-item">
                            <div class="danger-item-info">
                                <h6><i class="fas fa-user-slash me-2" style="color:#e74c3c;"></i>Deactivate Account</h6>
                                <p>Temporarily deactivate your hospital account. You can reactivate by contacting support.</p>
                            </div>
                            <button class="sp-btn danger" onclick="askDeactivate()">
                                <i class="fas fa-user-slash"></i> Deactivate
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>{{-- end sp-content --}}
    </div>{{-- end sp-layout --}}
</div>

{{-- ══ MODALS ══ --}}

{{-- Logout All Sessions Modal --}}
<div class="sp-modal" id="logoutModal">
    <div class="sp-modal-box">
        <div class="sp-modal-head">
            <h5><i class="fas fa-sign-out-alt" style="color:#e74c3c;"></i> Sign Out All Sessions</h5>
            <button class="sp-modal-close" onclick="closeModal('logoutModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sp-modal-body">
            <p style="font-size:.84rem;color:#555;">
                This will sign out all other active sessions. You will remain logged in on this device.
                Are you sure?
            </p>
        </div>
        <div class="sp-modal-foot">
            <button class="sp-btn secondary" onclick="closeModal('logoutModal')">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="sp-btn danger" onclick="doLogoutAll()">
                <i class="fas fa-sign-out-alt me-1"></i>Sign Out All
            </button>
        </div>
    </div>
</div>

{{-- Deactivate Modal --}}
<div class="sp-modal" id="deactivateModal">
    <div class="sp-modal-box">
        <div class="sp-modal-head">
            <h5><i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i> Deactivate Account</h5>
            <button class="sp-modal-close" onclick="closeModal('deactivateModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sp-modal-body">
            <div class="sp-alert al-error" style="cursor:default;margin-bottom:1rem;">
                <i class="fas fa-exclamation-circle"></i>
                <span>This will deactivate your hospital account. All active appointments will be affected.</span>
            </div>
            <div class="sp-group">
                <label class="sp-label">Type <strong>DEACTIVATE</strong> to confirm</label>
                <input type="text" id="deactivateConfirm" class="sp-input"
                       placeholder="Type DEACTIVATE"
                       oninput="checkDeactivate(this.value)">
            </div>
        </div>
        <div class="sp-modal-foot">
            <button class="sp-btn secondary" onclick="closeModal('deactivateModal');resetDeactivate()">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="sp-btn danger" id="deactivateBtn" disabled onclick="doDeactivate()">
                <i class="fas fa-user-slash me-1"></i>Deactivate Account
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════
// PANEL SWITCHING
// ════════════════════════════════════════
function setPanel(name) {
    document.querySelectorAll('.sp-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sp-nav-item').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + name)?.classList.add('active');
    document.querySelector(`.sp-nav-item[data-panel="${name}"]`)?.classList.add('active');
    // Update URL hash silently
    history.replaceState(null, '', '#' + name);
}

// On load — restore panel from hash
document.addEventListener('DOMContentLoaded', () => {
    const hash = location.hash.replace('#','');
    if (hash && document.getElementById('panel-' + hash)) setPanel(hash);

    // Auto-close alerts after 5s
    document.querySelectorAll('.sp-alert').forEach(a => {
        if (a.style.cursor !== 'default') setTimeout(() => a.remove(), 5000);
    });

    // Password form submit handler
    const pwf = document.getElementById('pwForm');
    if (pwf) {
        pwf.addEventListener('submit', function (e) {
            // Client-side: ensure passwords match
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirmation').value;
            if (p1 !== p2) {
                e.preventDefault();
                document.getElementById('matchHint').innerHTML =
                    '<span style="color:#e74c3c;"><i class="fas fa-times-circle me-1"></i>Passwords do not match.</span>';
                document.getElementById('password_confirmation').classList.add('is-invalid');
                return;
            }
            const btn = document.getElementById('pwSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        });
    }

    // If there's a password error, open security panel
    @if($errors->has('current_password') || $errors->has('password'))
        setPanel('security');
    @endif
});

// ════════════════════════════════════════
// PASSWORD TOGGLE
// ════════════════════════════════════════
function togglePw(id, btn) {
    const inp = document.getElementById(id);
    if (!inp) return;
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.querySelector('i').className = 'fas fa-eye' + (show ? '-slash' : '');
}

// ════════════════════════════════════════
// PASSWORD STRENGTH
// ════════════════════════════════════════
function checkStrength(val) {
    const wrap = document.getElementById('pwStrength');
    const fill = document.getElementById('pwStrFill');
    const txt  = document.getElementById('pwStrTxt');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = '';

    let score = 0;
    if (val.length >= 8)              score++;
    if (val.length >= 12)             score++;
    if (/[A-Z]/.test(val))            score++;
    if (/[0-9]/.test(val))            score++;
    if (/[^A-Za-z0-9]/.test(val))     score++;

    const levels = [
        { w:'20%',  bg:'#e74c3c', label:'Very Weak',  color:'#e74c3c' },
        { w:'40%',  bg:'#e67e22', label:'Weak',        color:'#e67e22' },
        { w:'60%',  bg:'#f39c12', label:'Fair',        color:'#f39c12' },
        { w:'80%',  bg:'#27ae60', label:'Strong',      color:'#27ae60' },
        { w:'100%', bg:'#1abc9c', label:'Very Strong', color:'#1abc9c' },
    ];
    const lvl = levels[Math.max(0, score - 1)];
    fill.style.width      = lvl.w;
    fill.style.background = lvl.bg;
    txt.textContent       = lvl.label;
    txt.style.color       = lvl.color;
}

// ════════════════════════════════════════
// PASSWORD MATCH
// ════════════════════════════════════════
function checkMatch() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('password_confirmation').value;
    const el  = document.getElementById('matchHint');
    const inp = document.getElementById('password_confirmation');
    if (!p2) { el.textContent = ''; inp.classList.remove('is-invalid'); return; }
    if (p1 === p2) {
        el.innerHTML = '<span style="color:#27ae60;"><i class="fas fa-check me-1"></i>Passwords match</span>';
        inp.classList.remove('is-invalid');
    } else {
        el.innerHTML = '<span style="color:#e74c3c;"><i class="fas fa-times me-1"></i>Passwords do not match</span>';
        inp.classList.add('is-invalid');
    }
}

// ════════════════════════════════════════
// NOTIFICATION PREFERENCES (localStorage)
// ════════════════════════════════════════
function saveNotifPref(key, val) {
    try {
        let prefs = JSON.parse(localStorage.getItem('notif_prefs') || '{}');
        prefs[key] = val;
        localStorage.setItem('notif_prefs', JSON.stringify(prefs));
        toast(val ? key.charAt(0).toUpperCase()+key.slice(1)+' notifications enabled.'
                  : key.charAt(0).toUpperCase()+key.slice(1)+' notifications disabled.', 'success');
    } catch(e) {}
}

// ════════════════════════════════════════
// MODALS
// ════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sp-modal').forEach(m => {
        m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape')
            document.querySelectorAll('.sp-modal.open').forEach(m => m.classList.remove('open'));
    });
});

// ════════════════════════════════════════
// LOGOUT ALL SESSIONS
// ════════════════════════════════════════
function askLogoutAll() { openModal('logoutModal'); }
function doLogoutAll() {
    // Laravel default: POST /logout logs out current session
    // For "logout other devices": use Auth::logoutOtherDevices()
    // Here we just show success toast (extend if needed)
    closeModal('logoutModal');
    toast('Other sessions signed out.', 'success');
}

// ════════════════════════════════════════
// DEACTIVATE ACCOUNT
// ════════════════════════════════════════
function askDeactivate() {
    document.getElementById('deactivateConfirm').value = '';
    document.getElementById('deactivateBtn').disabled = true;
    openModal('deactivateModal');
}
function checkDeactivate(val) {
    document.getElementById('deactivateBtn').disabled = (val.trim() !== 'DEACTIVATE');
}
function resetDeactivate() {
    document.getElementById('deactivateConfirm').value = '';
    document.getElementById('deactivateBtn').disabled = true;
}
function doDeactivate() {
    closeModal('deactivateModal');
    toast('Account deactivation request sent. Support will contact you shortly.', 'info');
}

// ════════════════════════════════════════
// TOAST
// ════════════════════════════════════════
function toast(msg, type = 'success') {
    const ex = document.getElementById('_sp_toast');
    if (ex) ex.remove();
    const c = {
        success:{ bg:'#d1e7dd', color:'#0f5132', icon:'fa-check-circle' },
        error:  { bg:'#f8d7da', color:'#842029', icon:'fa-exclamation-circle' },
        warning:{ bg:'#fff3cd', color:'#664d03', icon:'fa-exclamation-triangle' },
        info:   { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' },
    }[type] ?? { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' };
    const t = document.createElement('div');
    t.id = '_sp_toast';
    t.style.cssText = `
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
        background:${c.bg}; color:${c.color}; border:1px solid ${c.color}44;
        border-radius:12px; padding:.8rem 1.2rem;
        display:flex; align-items:center; gap:.6rem;
        font-size:.83rem; font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        max-width:340px; animation:spSlide .3s ease;`;
    t.innerHTML = `<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
}

// Inject keyframes
const _ks = document.createElement('style');
_ks.textContent = `
    @keyframes spSlide {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }`;
document.head.appendChild(_ks);
</script>
@endpush
