@extends('hospital.layouts.app')
@section('title','Settings')
@section('page-title','Account Settings')
@section('page-subtitle','Manage your account preferences')

@section('content')
<div x-data="{ tab: 'password' }">

    <!-- Tab Nav -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        @foreach([
            ['password', 'lock',        'Change Password'],
            ['security', 'shield-alt',  'Security'],
            ['account',  'user-circle', 'Account'],
        ] as [$k, $i, $l])
        <button @click="tab='{{ $k }}'"
                :class="tab==='{{ $k }}' ? 'bg-teal-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium transition border border-gray-200 whitespace-nowrap">
            <i class="fas fa-{{ $i }}"></i> {{ $l }}
        </button>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- TAB 1 — CHANGE PASSWORD                --}}
    {{-- ═══════════════════════════════════════ --}}
    <div x-show="tab === 'password'">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-lock text-teal-500"></i> Change Password
            </h3>

            <form action="{{ route('hospital.settings.password') }}" method="POST"
                  x-data="{ showCur: false, showNew: false, showCon: false }">
                @csrf
                <div class="space-y-4">

                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                        <div class="relative">
                            <input :type="showCur ? 'text' : 'password'"
                                   name="current_password" required
                                   placeholder="Enter current password"
                                   class="w-full border @error('current_password') border-red-400 @else border-gray-200 @enderror
                                          rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="showCur=!showCur"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :class="showCur ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                            </button>
                        </div>
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'"
                                   name="password" required minlength="8"
                                   placeholder="Enter new password"
                                   class="w-full border @error('password') border-red-400 @else border-gray-200 @enderror
                                          rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="showNew=!showNew"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :class="showNew ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <input :type="showCon ? 'text' : 'password'"
                                   name="password_confirmation" required
                                   placeholder="Confirm new password"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 pr-10 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="showCon=!showCon"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :class="showCon ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Rules -->
                    <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500 space-y-1">
                        <p class="font-medium text-gray-600 mb-1">Password Requirements:</p>
                        <p><i class="fas fa-check text-teal-400 mr-1"></i> At least 8 characters</p>
                        <p><i class="fas fa-check text-teal-400 mr-1"></i> Include uppercase &amp; lowercase letters</p>
                        <p><i class="fas fa-check text-teal-400 mr-1"></i> Include at least one number</p>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg
                                   hover:bg-teal-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- TAB 2 — SECURITY                       --}}
    {{-- ═══════════════════════════════════════ --}}
    <div x-show="tab === 'security'" x-cloak>
        <div class="space-y-4 max-w-2xl">

            <!-- Login Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-teal-500"></i> Login Activity
                </h3>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-desktop text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Current Session</p>
                            <p class="text-xs text-gray-500">
                                {{ request()->ip() }} — {{ now()->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">
                        <i class="fas fa-circle text-[8px] mr-1"></i>Active
                    </span>
                </div>
            </div>

            <!-- Logout All Devices -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-sign-out-alt text-red-500"></i> Logout All Devices
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    This will log you out from all active sessions on all devices.
                </p>
                <form method="POST" action="{{ route('logout') }}"
                      onsubmit="return confirm('Logout from all devices?')">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium
                                   rounded-lg hover:bg-red-100 transition flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Logout All Sessions
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- TAB 3 — ACCOUNT INFO                   --}}
    {{-- ═══════════════════════════════════════ --}}
    <div x-show="tab === 'account'" x-cloak>
        <div class="space-y-4 max-w-lg">

            <!-- Account Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-user-circle text-teal-500"></i> Account Information
                </h3>
                <div class="space-y-0 divide-y divide-gray-50">

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Account Name</span>
                        <span class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Email Address</span>
                        <span class="text-sm font-medium text-gray-800">{{ Auth::user()->email }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Account Type</span>
                        <span class="text-sm font-medium text-gray-800 capitalize">
                            {{ Auth::user()->user_type }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Account Status</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ Auth::user()->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst(Auth::user()->status ?? 'active') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Member Since</span>
                        <span class="text-sm font-medium text-gray-800">
                            {{ Auth::user()->created_at?->format('d M Y') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-gray-500">Email Verified</span>
                        @if(Auth::user()->email_verified_at)
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-green-100 text-green-700">
                                <i class="fas fa-check mr-1"></i>Verified
                            </span>
                        @else
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-yellow-100 text-yellow-700">
                                <i class="fas fa-exclamation-circle mr-1"></i>Not Verified
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ✅ EMAIL VERIFICATION RESEND CARD — email verify නොකළ විට පමණ show --}}
            @if(!Auth::user()->email_verified_at)
            <div class="bg-white rounded-xl shadow-sm border border-yellow-200 p-6"
                 x-data="{ sending: false, sent: false, msg: '' }">
                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-envelope text-yellow-500"></i> Email Verification
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Your email address <strong class="text-gray-700">{{ Auth::user()->email }}</strong>
                    has not been verified yet. Please verify to unlock all features.
                </p>

                <!-- Status Messages -->
                <div x-show="sent"
                     class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700
                            text-sm rounded-lg px-4 py-2.5">
                    <i class="fas fa-check-circle"></i>
                    <span x-text="msg"></span>
                </div>

                <div x-show="!sent && msg"
                     class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700
                            text-sm rounded-lg px-4 py-2.5">
                    <i class="fas fa-exclamation-circle"></i>
                    <span x-text="msg"></span>
                </div>

                <!-- Resend Button -->
                <button @click="
                            sending = true;
                            msg = '';
                            fetch('{{ route('hospital.resend.verification') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                sending = false;
                                if (data.success) {
                                    sent = true;
                                    msg = 'Verification email sent to {{ Auth::user()->email }}. Please check your inbox.';
                                } else {
                                    msg = data.message ?? 'Failed to send. Please try again.';
                                }
                            })
                            .catch(() => {
                                sending = false;
                                msg = 'Connection error. Please try again.';
                            })
                        "
                        :disabled="sending || sent"
                        class="flex items-center gap-2 px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium
                               rounded-lg hover:bg-yellow-600 transition disabled:opacity-60 disabled:cursor-not-allowed">
                    <i x-show="!sending && !sent" class="fas fa-paper-plane"></i>
                    <i x-show="sending"           class="fas fa-spinner fa-spin"></i>
                    <i x-show="sent"              class="fas fa-check"></i>
                    <span x-text="sent ? 'Email Sent!' : sending ? 'Sending...' : 'Resend Verification Email'"></span>
                </button>

                <p class="text-xs text-gray-400 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Check your spam folder if you don't see the email within a few minutes.
                </p>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
