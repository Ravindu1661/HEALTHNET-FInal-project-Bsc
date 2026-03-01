@include('partials.header')

<style>
/* ══ HERO ══ */
.pp-hero{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:5.5rem 0 0;color:#fff;position:relative;overflow:hidden}
.pp-hero::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1800&q=80') center/cover;opacity:.06;z-index:0}
.pp-hero .container{position:relative;z-index:1}
.pp-hero::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f0f4f8;clip-path:ellipse(55% 100% at 50% 100%)}
.pp-avatar-wrap{position:relative;display:inline-block}
.pp-avatar{width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,.85);box-shadow:0 4px 18px rgba(0,0,0,.2)}
.pp-avatar-edit{position:absolute;bottom:2px;right:2px;width:28px;height:28px;border-radius:50%;background:#00796b;border:2px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.2)}

/* ══ BODY ══ */
.pp-body{background:#f0f4f8;padding:2rem 0 3rem}

/* ══ CARDS ══ */
.pp-card{background:#fff;border-radius:14px;padding:1.4rem 1.5rem;box-shadow:0 3px 14px rgba(0,0,0,.06);margin-bottom:1.2rem}
.pp-card-title{font-size:.9rem;font-weight:700;color:#00796b;padding-bottom:.6rem;border-bottom:2px solid #e0f2f1;margin-bottom:1.2rem;display:flex;align-items:center;gap:.5rem}

/* ══ FORM ══ */
.pp-label{display:block;font-size:.78rem;font-weight:600;color:#555;margin-bottom:.35rem}
.pp-input{width:100%;padding:.6rem .85rem;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.85rem;background:#fafafa;transition:border .25s,box-shadow .25s}
.pp-input:focus{border-color:#00796b;outline:none;box-shadow:0 0 0 3px rgba(0,121,107,.08);background:#fff}
.pp-input.error{border-color:#dc2626}
.pp-input[readonly]{background:#f8fafc;color:#888;cursor:not-allowed}

/* ══ STAT CARD ══ */
.stat-mini{background:#f0fdf4;border-radius:11px;padding:.9rem 1rem;text-align:center;border:1.5px solid #a5d6a7}
.stat-mini-num{font-size:1.5rem;font-weight:800;color:#00796b;line-height:1}
.stat-mini-lbl{font-size:.72rem;color:#555;margin-top:.2rem}

/* ══ INFO ROW ══ */
.pp-info-row{display:flex;align-items:center;gap:.7rem;padding:.5rem 0;border-bottom:1px solid #f0f4f0;font-size:.84rem;color:#444}
.pp-info-row:last-child{border-bottom:none}
.pp-info-row i{width:18px;color:#00796b;flex-shrink:0}

/* ══ TABS ══ */
.pp-tabs{display:flex;gap:.4rem;border-bottom:2px solid #e0f2f1;margin-bottom:1.2rem;overflow-x:auto}
.pp-tab{padding:.55rem 1.1rem;font-size:.82rem;font-weight:700;color:#888;border-bottom:3px solid transparent;cursor:pointer;white-space:nowrap;text-decoration:none;transition:all .2s}
.pp-tab:hover{color:#00796b}
.pp-tab.active{color:#00796b;border-bottom-color:#00796b}

/* ══ BADGE ══ */
.status-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:20px;font-size:.73rem;font-weight:700}
.status-active{background:#dcfce7;color:#166534}
.status-pending{background:#fef3c7;color:#92400e}
.status-suspended{background:#fee2e2;color:#991b1b}

/* ══ SUBMIT BTN ══ */
.pp-save-btn{background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:9px;padding:.75rem 1.8rem;font-weight:700;font-size:.88rem;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;transition:all .3s;box-shadow:0 3px 12px rgba(0,121,107,.25)}
.pp-save-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}

/* ══ ALERT ══ */
.pp-alert{border-radius:9px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;font-weight:500}
.pp-alert.success{background:#dcfce7;color:#166534;border-left:3px solid #22c55e}
.pp-alert.error{background:#fee2e2;color:#991b1b;border-left:3px solid #ef4444}

/* ══ PORTFOLIO BTN ══ */
.portfolio-btn{
    display:flex;align-items:center;justify-content:center;gap:.6rem;
    padding:.85rem 1rem;font-size:.88rem;font-weight:800;color:#fff;
    text-decoration:none;border-radius:11px;margin-bottom:1rem;
    background:linear-gradient(135deg,#00796b,#004d40);
    box-shadow:0 4px 16px rgba(0,121,107,.3);
    transition:all .3s;border:none;
}
.portfolio-btn:hover{
    filter:brightness(1.1);transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(0,121,107,.4);color:#fff;
}
.portfolio-btn .pb-icon{
    width:34px;height:34px;border-radius:50%;
    background:rgba(255,255,255,.18);
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;flex-shrink:0;
}
.portfolio-btn .pb-texts{text-align:left}
.portfolio-btn .pb-title{font-size:.88rem;font-weight:800;line-height:1.1}
.portfolio-btn .pb-sub{font-size:.7rem;opacity:.82;font-weight:500}
</style>

@php
    $user    = Auth::user();
    $patient = $user->patient;

    // Stats
    $totalAppointments   = \App\Models\Appointment::where('patient_id', $patient->id ?? 0)->count();
    $completedAppointments = \App\Models\Appointment::where('patient_id', $patient->id ?? 0)->where('status','completed')->count();
    $totalLabOrders      = \App\Models\LabOrder::where('patient_id', $patient->id ?? 0)->count();
    $totalPharmacyOrders = \App\Models\PharmacyOrder::where('patient_id', $patient->id ?? 0)->count();

    $profileImg = $patient?->profile_image
        ? asset('storage/' . $patient->profile_image)
        : asset('images/default-avatar.png');
    $fullName = trim(($patient->firstname ?? '') . ' ' . ($patient->lastname ?? '')) ?: strtok($user->email, '@');
@endphp

{{-- ══ HERO ══ --}}
<section class="pp-hero">
    <div class="container pb-4">
        <div class="d-flex align-items-end gap-4 flex-wrap">

            {{-- Avatar --}}
            <div class="pp-avatar-wrap">
                <img src="{{ $profileImg }}" class="pp-avatar" id="avatarPreview"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                <div class="pp-avatar-edit" onclick="document.getElementById('avatarInput').click()"
                     title="Change photo">
                    <i class="fas fa-camera" style="color:#fff;font-size:.65rem"></i>
                </div>
            </div>

            {{-- Info --}}
           <div class="flex-grow-1 pb-1">
                <h1 style="font-size:1.6rem;font-weight:800;margin:0">
                    {{ trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) ?: strtok($user->email, '@') }}
                </h1>
                <div style="opacity:.82;font-size:.85rem;margin:.3rem 0">
                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                    @if($patient?->phone)
                    &nbsp;&bull;&nbsp;<i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                    @endif
                </div>
                <span class="status-badge status-{{ $user->status ?? 'active' }}">
                    <i class="fas fa-circle" style="font-size:.4rem"></i>
                    {{ ucfirst($user->status ?? 'active') }}
                </span>
                @if(!$user->hasVerifiedEmail())
                <span class="status-badge" style="background:#fef3c7;color:#92400e;margin-left:.4rem">
                    <i class="fas fa-exclamation-triangle" style="font-size:.65rem"></i> Email Not Verified
                </span>
                @endif
            </div>

            {{-- Stats --}}
            <div class="d-flex gap-2 pb-2">
                <div class="stat-mini" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3)">
                    <div class="stat-mini-num" style="color:#fff">{{ $totalAppointments }}</div>
                    <div class="stat-mini-lbl" style="color:rgba(255,255,255,.8)">Appointments</div>
                </div>
                <div class="stat-mini" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3)">
                    <div class="stat-mini-num" style="color:#fff">{{ $totalLabOrders }}</div>
                    <div class="stat-mini-lbl" style="color:rgba(255,255,255,.8)">Lab Orders</div>
                </div>
                <div class="stat-mini" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3)">
                    <div class="stat-mini-num" style="color:#fff">{{ $totalPharmacyOrders }}</div>
                    <div class="stat-mini-lbl" style="color:rgba(255,255,255,.8)">Rx Orders</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section class="pp-body">
    <div class="container">

        {{-- Flash --}}
        @foreach(['success','error'] as $t)
            @if(session($t))
            <div class="pp-alert {{ $t }}">
                <i class="fas fa-{{ $t==='success'?'check-circle':'exclamation-circle' }}" style="flex-shrink:0;margin-top:.1rem"></i>
                <span>{{ session($t) }}</span>
            </div>
            @endif
        @endforeach
        @if($errors->any())
        <div class="pp-alert error">
            <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:.1rem"></i>
            <div>
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        </div>
        @endif

        <div class="row g-3">

            {{-- ══ MAIN ══ --}}
            <div class="col-lg-8">

                {{-- Profile Form --}}
                <div class="pp-card">
                    <div class="pp-card-title"><i class="fas fa-user-edit"></i> Personal Information</div>

                    <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PUT')

                        {{-- Hidden avatar input --}}
                        <input type="file" id="avatarInput" name="profile_image"
                               accept="image/*" class="d-none"
                               onchange="previewAvatar(this)">

                        {{-- Name Row --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="pp-label">First Name <span style="color:#dc2626">*</span></label>
                               <input type="text" name="first_name"
                                value="{{ old('first_name', $patient->first_name ?? '') }}"
                                class="pp-input {{ $errors->has('first_name') ? 'error' : '' }}"
                                placeholder="First name">
                            </div>
                            <div class="col-md-6">
                                <label class="pp-label">Last Name</label>
                                <input type="text" name="last_name"
                                value="{{ old('last_name', $patient->last_name ?? '') }}"
                                class="pp-input"
                                placeholder="Last name">
                            </div>
                        </div>

                        {{-- NIC & DOB --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="pp-label">NIC Number <span style="color:#dc2626">*</span></label>
                                <input type="text" name="nic" class="pp-input {{ $errors->has('nic')?'error':'' }}"
                                       value="{{ old('nic', $patient->nic ?? '') }}" placeholder="e.g. 199012345678">
                            </div>
                            <div class="col-md-6">
                                <label class="pp-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="pp-input"
                                       value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}">
                            </div>
                        </div>

                        {{-- Gender & Blood Group --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="pp-label">Gender</label>
                                <select name="gender" class="pp-input">
                                    <option value="">Select gender</option>
                                    @foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ old('gender',$patient->gender??'') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="pp-label">Blood Group</label>
                                <select name="blood_group" class="pp-input">
                                    <option value="">Select blood group</option>
                                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group',$patient->blood_group??'') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="pp-label">Phone Number <span style="color:#dc2626">*</span></label>
                                <input type="text" name="phone" class="pp-input {{ $errors->has('phone')?'error':'' }}"
                                       value="{{ old('phone', $patient->phone ?? '') }}" placeholder="07X XXX XXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="pp-label">Email Address</label>
                                <input type="email" class="pp-input" value="{{ $user->email }}" readonly>
                                <div style="font-size:.72rem;color:#aaa;margin-top:.25rem">
                                    <i class="fas fa-lock me-1"></i>Contact support to change email
                                </div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mb-3">
                            <label class="pp-label">Address</label>
                            <textarea name="address" class="pp-input" rows="2"
                                      placeholder="Street address...">{{ old('address', $patient->address ?? '') }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="pp-label">City</label>
                                <input type="text" name="city" class="pp-input"
                                       value="{{ old('city', $patient->city ?? '') }}" placeholder="City">
                            </div>
                            <div class="col-md-4">
                                <label class="pp-label">Province</label>
                                <input type="text" name="province" class="pp-input"
                                       value="{{ old('province', $patient->province ?? '') }}" placeholder="Province">
                            </div>
                            <div class="col-md-4">
                                <label class="pp-label">Postal Code</label>
                                <input type="text" name="postal_code" class="pp-input"
                                       value="{{ old('postal_code', $patient->postal_code ?? '') }}" placeholder="10100">
                            </div>
                        </div>

                        {{-- Emergency Contact --}}
                        <div style="background:#fff3e0;border-left:3px solid #f59e0b;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.78rem;color:#92400e;font-weight:600">
                            <i class="fas fa-exclamation-triangle me-1"></i> Emergency Contact
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="pp-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="pp-input"
                                       value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}"
                                       placeholder="Full name">
                            </div>
                            <div class="col-md-6">
                                <label class="pp-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="pp-input"
                                       value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}"
                                       placeholder="07X XXX XXXX">
                            </div>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="pp-save-btn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <span id="savingSpinner" style="display:none;font-size:.82rem;color:#00796b">
                                <i class="fas fa-spinner fa-spin me-1"></i> Saving...
                            </span>
                        </div>
                    </form>
                </div>

                {{-- Change Password --}}
                <div class="pp-card">
                    <div class="pp-card-title"><i class="fas fa-lock"></i> Change Password</div>
                    <form action="{{ route('patient.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="pp-label">Current Password</label>
                                <input type="password" name="current_password" class="pp-input" placeholder="Current password">
                            </div>
                            <div class="col-md-4">
                                <label class="pp-label">New Password</label>
                                <input type="password" name="password" class="pp-input" placeholder="Min 8 characters">
                            </div>
                            <div class="col-md-4">
                                <label class="pp-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="pp-input" placeholder="Repeat new password">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="pp-save-btn">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- ══ SIDEBAR ══ --}}
            <div class="col-lg-4">

                {{-- Profile Summary --}}
                <div class="pp-card" style="text-align:center">
                    <img src="{{ $profileImg }}" id="sideAvatarPreview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e0f2f1;margin-bottom:.7rem"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <div style="font-weight:800;font-size:1rem;color:#1a1a1a">
                        {{ trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) ?: strtok($user->email, '@') }}
                    </div>

                    <div style="font-size:.78rem;color:#888;margin:.2rem 0">{{ $user->email }}</div>
                    <span class="status-badge status-{{ $user->status ?? 'active' }} mt-1">
                        <i class="fas fa-circle" style="font-size:.4rem"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>

                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-num">{{ $completedAppointments }}</div>
                                <div class="stat-mini-lbl">Completed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-num">{{ $totalAppointments }}</div>
                                <div class="stat-mini-lbl">Total Appts</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-num">{{ $totalLabOrders }}</div>
                                <div class="stat-mini-lbl">Lab Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini">
                                <div class="stat-mini-num">{{ $totalPharmacyOrders }}</div>
                                <div class="stat-mini-lbl">Rx Orders</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="pp-card">
                    <div class="pp-card-title"><i class="fas fa-info-circle"></i> Account Info</div>
                    @if($patient?->nic)
                    <div class="pp-info-row"><i class="fas fa-id-card"></i> <span><strong>NIC:</strong> {{ $patient->nic }}</span></div>
                    @endif
                    @if($patient?->date_of_birth)
                    <div class="pp-info-row"><i class="fas fa-birthday-cake"></i> <span><strong>DOB:</strong> {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}</span></div>
                    @endif
                    @if($patient?->blood_group)
                    <div class="pp-info-row"><i class="fas fa-tint"></i> <span><strong>Blood:</strong> {{ $patient->blood_group }}</span></div>
                    @endif
                    @if($patient?->gender)
                    <div class="pp-info-row"><i class="fas fa-venus-mars"></i> <span><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</span></div>
                    @endif
                    @if($patient?->city)
                    <div class="pp-info-row"><i class="fas fa-map-marker-alt"></i>
                        <span>{{ $patient->city }}@if($patient->province), {{ $patient->province }}@endif</span>
                    </div>
                    @endif
                    <div class="pp-info-row">
                        <i class="fas fa-envelope"></i>
                        <span>
                            @if($user->hasVerifiedEmail())
                            <span style="color:#166534;font-weight:600"><i class="fas fa-check-circle me-1"></i>Email Verified</span>
                            @else
                            <span style="color:#dc2626;font-weight:600"><i class="fas fa-times-circle me-1"></i>Not Verified</span>
                            @endif
                        </span>
                    </div>
                    <div class="pp-info-row"><i class="fas fa-clock"></i> <span>Joined {{ optional($user->created_at)->format('M Y') }}</span></div>
                </div>

                {{-- Quick Links --}}
                <div class="pp-card">
                    <div class="pp-card-title"><i class="fas fa-link"></i> Quick Links</div>

                    {{-- ✅ Health Portfolio Button --}}
                    <a href="{{ route('patient.health-portfolio') }}" class="portfolio-btn">
                        <div class="pb-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="pb-texts">
                            <div class="pb-title">My Health Portfolio</div>
                            <div class="pb-sub">BMI · Health Score · Vitals · Tips</div>
                        </div>
                        <i class="fas fa-arrow-right ms-auto" style="font-size:.75rem;opacity:.8"></i>
                    </a>
                    <a href="{{ route('patient.medicine-reminders.index') }}" class="portfolio-btn"
                        style="background:linear-gradient(135deg,#7c3aed,#5b21b6);margin-bottom:.6rem">
                            <div class="pb-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <div class="pb-texts">
                                <div class="pb-title">Medicine Reminders</div>
                                <div class="pb-sub">Set alarms · Track doses · Get notified</div>
                            </div>
                            <i class="fas fa-arrow-right ms-auto" style="font-size:.75rem;opacity:.8"></i>
                    </a>

                    {{-- Other Quick Links --}}
                    @foreach([
                        ['route'=>'patient.appointments.index','icon'=>'calendar-check','label'=>'My Appointments'],
                        ['route'=>'patient.lab-orders.index',  'icon'=>'flask',         'label'=>'My Lab Orders'],
                        ['route'=>'patient.pharmacies',        'icon'=>'pills',         'label'=>'Pharmacy Orders'],
                        ['route'=>'patient.notifications',     'icon'=>'bell',          'label'=>'Notifications'],
                        ['route'=>'patient.dashboard',         'icon'=>'home',          'label'=>'Dashboard'],
                    ] as $link)
                    <a href="{{ route($link['route']) }}"
                       style="display:flex;align-items:center;gap:.6rem;padding:.5rem .3rem;font-size:.83rem;font-weight:600;color:#374151;text-decoration:none;border-bottom:1px solid #f0f4f0;transition:color .2s"
                       onmouseover="this.style.color='#00796b'" onmouseout="this.style.color='#374151'">
                        <i class="fas fa-{{ $link['icon'] }}" style="color:#00796b;width:16px"></i>
                        {{ $link['label'] }}
                        <i class="fas fa-chevron-right ms-auto" style="font-size:.65rem;color:#ccc"></i>
                    </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>

<script>
// Avatar preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').src     = e.target.result;
            document.getElementById('sideAvatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Submit spinner
document.getElementById('profileForm')?.addEventListener('submit', () => {
    document.querySelector('.pp-save-btn').disabled = true;
    document.getElementById('savingSpinner').style.display = 'inline-flex';
});

// Auto-dismiss alerts
document.querySelectorAll('.pp-alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});
</script>

@include('partials.footer')
