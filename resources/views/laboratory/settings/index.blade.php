@extends('laboratory.layouts.app')
@section('title','Settings')
@section('page-title','Account & Security')
@section('page-subtitle','Manage account settings')

@section('content')
<div class="max-w-2xl space-y-5">

    {{-- Account Settings --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-user-cog text-teal-600"></i> Account Settings
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('laboratory.settings.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2"></i> Update Account
                </button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-lock text-teal-600"></i> Change Password
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('laboratory.settings.change-password') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="curPwd" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-teal-500 outline-none @error('current_password') border-red-400 @enderror">
                        <button type="button" onclick="togglePwd('curPwd')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="newPwd" required minlength="8"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        <button type="button" onclick="togglePwd('newPwd')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>
                <button type="submit" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition">
                    <i class="fas fa-key mr-2"></i> Change Password
                </button>
            </form>
        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-red-100 bg-red-50">
            <h3 class="font-bold text-red-700 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i> Danger Zone
            </h3>
        </div>
        <div class="p-6 flex items-center justify-between">
            <div>
                <p class="font-semibold text-gray-900">Logout</p>
                <p class="text-sm text-gray-500">Sign out from this session</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-red-700 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
