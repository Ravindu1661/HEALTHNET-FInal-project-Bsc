<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Hospital Dashboard') — HealthNet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>[x-cloak]{display:none!important}</style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: true, mobileSidebar: false }">

<!-- Mobile Overlay -->
<div x-show="mobileSidebar" x-cloak @click="mobileSidebar=false"
     class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

<!-- ═══════════ SIDEBAR ═══════════ -->
<aside :class="sidebarOpen ? 'w-64' : 'w-20'"
       class="fixed top-0 left-0 h-full bg-gradient-to-b from-teal-900 to-teal-800 text-white z-50 transition-all duration-300 hidden lg:flex flex-col shadow-2xl">

    <!-- Logo -->
    <div class="flex items-center justify-between px-4 py-5 border-b border-teal-700">
        <div x-show="sidebarOpen" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center">
                <i class="fas fa-hospital text-teal-700 text-lg"></i>
            </div>
            <span class="font-bold text-lg">HealthNet</span>
        </div>
        <div x-show="!sidebarOpen" class="w-9 h-9 rounded-lg bg-white flex items-center justify-center mx-auto">
            <i class="fas fa-hospital text-teal-700 text-lg"></i>
        </div>
        <button @click="sidebarOpen=!sidebarOpen" class="text-teal-200 hover:text-white ml-2 transition">
            <i :class="sidebarOpen?'fa-chevron-left':'fa-chevron-right'" class="fas text-sm"></i>
        </button>
    </div>

    <!-- Hospital Mini Profile -->
    <div x-show="sidebarOpen" class="px-4 py-4 border-b border-teal-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if(Auth::user()->hospital?->profile_image)
                    <img src="{{ asset('storage/'.Auth::user()->hospital->profile_image) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-hospital text-white"></i>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->hospital?->name ?? Auth::user()->name }}</p>
                <p class="text-xs text-teal-200 truncate capitalize">{{ Auth::user()->hospital?->type ?? 'Hospital' }}</p>
                @php $hStatus = Auth::user()->hospital?->status ?? 'pending'; @endphp
                <span class="text-xs {{ $hStatus==='approved' ? 'text-green-300' : 'text-yellow-300' }}">
                    <i class="fas fa-circle text-[8px]"></i>
                    {{ $hStatus==='approved' ? 'Verified' : 'Pending' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        @php
        $navItems = [
            ['route'=>'hospital.dashboard',      'icon'=>'th-large',       'label'=>'Dashboard'],
            ['route'=>'hospital.appointments',   'icon'=>'calendar-check', 'label'=>'Appointments'],
            ['route'=>'hospital.doctors',        'icon'=>'user-md',        'label'=>'Doctors'],
            ['route'=>'hospital.reviews',        'icon'=>'star',           'label'=>'Reviews'],
            ['route'=>'hospital.reports',        'icon'=>'chart-bar',      'label'=>'Reports & Analytics'],
            ['route'=>'hospital.profile',        'icon'=>'building',       'label'=>'Hospital Profile'],
            ['route'=>'hospital.notifications',  'icon'=>'bell',           'label'=>'Notifications'],
            ['route'=>'hospital.settings',       'icon'=>'cog',            'label'=>'Settings'],
        ];
        @endphp
        @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-teal-700 transition group
                  {{ request()->routeIs($item['route']) ? 'bg-teal-700 text-white' : 'text-teal-100' }}">
            <i class="fas fa-{{ $item['icon'] }} w-5 text-center text-teal-200 group-hover:text-white"></i>
            <span x-show="sidebarOpen" class="text-sm font-medium">{{ $item['label'] }}</span>
            @if($item['route']==='hospital.appointments')
            <span x-show="sidebarOpen" id="pending-apt-badge"
                  class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
            @endif
            @if($item['route']==='hospital.notifications')
            <span x-show="sidebarOpen" id="sidebar-notif-badge"
                  class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
            @endif
        </a>
        @endforeach
    </nav>

    <!-- Logout -->
    <div class="p-3 border-t border-teal-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-600 transition text-teal-200 hover:text-white">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Sidebar -->
<aside x-show="mobileSidebar" x-cloak
       class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-teal-900 to-teal-800 text-white z-50 flex flex-col shadow-2xl lg:hidden">
    <div class="flex items-center justify-between px-4 py-5 border-b border-teal-700">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center">
                <i class="fas fa-hospital text-teal-700"></i>
            </div>
            <span class="font-bold text-lg">HealthNet</span>
        </div>
        <button @click="mobileSidebar=false"><i class="fas fa-times text-teal-200"></i></button>
    </div>
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-teal-700 transition text-teal-100">
            <i class="fas fa-{{ $item['icon'] }} w-5 text-center"></i>
            <span class="text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>
</aside>

<!-- ═══════════ MAIN ═══════════ -->
<div :class="sidebarOpen?'lg:ml-64':'lg:ml-20'" class="transition-all duration-300 min-h-screen flex flex-col">

    <!-- Top Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center justify-between px-4 sm:px-6 h-16">
            <div class="flex items-center gap-4">
                <button @click="mobileSidebar=true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="hidden sm:block">
                    <h1 class="text-lg font-semibold text-gray-800">@yield('page-title','Dashboard')</h1>
                    <p class="text-xs text-gray-500">@yield('page-subtitle', now()->format('l, d F Y'))</p>
                </div>
            </div>

            <div class="flex items-center gap-3">

                <!-- ══ NOTIFICATIONS BELL ══ -->
                <div class="relative" x-data="notificationsPanel()" x-init="init()">

                    <button @click="open=!open"
                            class="relative w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-600 transition">
                        <i class="fas fa-bell text-lg"></i>
                        <span x-show="totalBadge > 0"
                              x-text="totalBadge > 99 ? '99+' : totalBadge"
                              class="absolute top-1 right-1 min-w-[16px] h-4 bg-red-500 rounded-full text-white text-[10px] flex items-center justify-center font-bold px-0.5">
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-cloak @click.away="open=false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-12 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                         style="width:340px">

                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="font-semibold text-gray-800 text-sm">Notifications</span>
                            <a href="{{ route('hospital.notifications') }}"
                               class="text-xs text-teal-600 hover:underline font-medium">View all</a>
                        </div>

                        <!-- ✅ EMAIL VERIFICATION ALERT — සෑම විටම show, dismiss කළාම panel reopen කළාම නැවත show -->
                        @if(!auth()->user()->hasVerifiedEmail())
                        <div x-show="showEmailCard"
                             class="mx-3 mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-blue-800">Email Not Verified</p>
                                    <p class="text-xs text-blue-600 mt-0.5 break-all">{{ auth()->user()->email }}</p>
                                    <button onclick="resendVerificationEmail(this)"
                                            class="mt-2 text-xs bg-blue-600 text-white px-3 py-1 rounded-lg
                                                   hover:bg-blue-700 transition font-medium flex items-center gap-1.5">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                        Resend Verification Email
                                    </button>
                                </div>
                                <!-- Dismiss — panel reopen කළාම නැවත show -->
                                <button @click="dismissEmailCard()"
                                        class="w-5 h-5 flex items-center justify-center rounded-full bg-blue-200
                                               hover:bg-blue-300 text-blue-700 text-xs flex-shrink-0 transition mt-0.5">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Notifications List -->
                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-50 py-1">
                            <template x-if="loading">
                                <div class="px-4 py-6 text-center text-gray-400 text-sm">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                                </div>
                            </template>
                            <template x-if="!loading && notifications.length === 0">
                                <div class="px-4 py-6 text-center text-gray-400 text-sm">
                                    <i class="fas fa-bell-slash text-2xl mb-1 block"></i>
                                    No new notifications
                                </div>
                            </template>
                            <template x-for="n in notifications" :key="n.id">
                                <div :class="n.is_read ? 'bg-white' : 'bg-teal-50'"
                                     class="px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                    <p class="text-sm font-medium text-gray-800" x-text="n.title"></p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed" x-text="n.message"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open=!open"
                            class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center overflow-hidden">
                            @if(Auth::user()->hospital?->profile_image)
                                <img src="{{ asset('storage/'.Auth::user()->hospital->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-hospital text-white text-sm"></i>
                            @endif
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-700 truncate max-w-32">
                            {{ Auth::user()->hospital?->name ?? Auth::user()->name }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 hidden sm:block"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open=false"
                         x-transition
                         class="absolute right-0 top-12 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                        <a href="{{ route('hospital.profile') }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 text-sm text-gray-700">
                            <i class="fas fa-building text-teal-500 w-4"></i> Hospital Profile
                        </a>
                        <a href="{{ route('hospital.settings') }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 text-sm text-gray-700">
                            <i class="fas fa-cog text-gray-500 w-4"></i> Settings
                        </a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 text-sm text-red-600 w-full text-left">
                                <i class="fas fa-sign-out-alt w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ✅ EMAIL VERIFICATION FLOATING BANNER -->
    {{-- sessionStorage: first page load වූ විට පමණ show — sidebar navigation කළාම නෑ --}}
    @if(!auth()->user()->hasVerifiedEmail())
    <div x-data="verifyBanner()"
         x-init="init()"
         x-show="visible"
         x-cloak
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-x-16"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-16"
         class="fixed top-20 right-5 z-40 w-80 max-w-[calc(100vw-2rem)]">

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl shadow-2xl p-4 relative">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-6 translate-x-6 pointer-events-none"></div>

            <button @click="dismiss()"
                    class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-white/20
                           flex items-center justify-center hover:bg-white/30 transition text-xs">
                <i class="fas fa-times"></i>
            </button>

            <div class="flex items-start gap-3 pr-4">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm">Verify Your Email</p>
                    <p class="text-blue-100 text-xs mt-0.5 leading-relaxed">
                        Please verify <strong class="text-white">{{ auth()->user()->email }}</strong>
                        to unlock full access.
                    </p>

                    <button @click="resend()"
                            :disabled="resending || resent"
                            class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white
                                   text-blue-700 text-xs font-semibold rounded-lg
                                   hover:bg-blue-50 transition disabled:opacity-60 disabled:cursor-not-allowed">
                        <i x-show="!resending && !resent" class="fas fa-paper-plane text-xs"></i>
                        <i x-show="resending"             class="fas fa-spinner fa-spin text-xs"></i>
                        <i x-show="resent"                class="fas fa-check text-xs"></i>
                        <span x-text="resent ? 'Email Sent!' : resending ? 'Sending...' : 'Resend Email'"></span>
                    </button>

                    <p x-show="feedbackMsg"
                       x-text="feedbackMsg"
                       class="mt-2 text-xs text-blue-100 bg-white/10 rounded-lg px-2 py-1"></p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Alerts -->
    <main class="flex-1 p-4 sm:p-6">
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center justify-between gap-2">
            <span class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
            </span>
            <button @click="show=false" class="text-green-400 hover:text-green-600">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show"
             class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center justify-between gap-2">
            <span class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
            </span>
            <button @click="show=false" class="text-red-400 hover:text-red-600">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 px-6 py-4 text-center text-sm text-gray-400">
        © {{ date('Y') }} HealthNet — Hospital Portal
    </footer>
</div>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ═══ Notifications Panel Alpine Component ═══
function notificationsPanel() {
    return {
        open:          false,
        loading:       true,
        notifications: [],
        count:         0,
        totalBadge:    0,
        showEmailCard: false,

        async init() {
            // ✅ Email card — සෑම විටම show, panel reopen කළාම නැවත show
            @if(!auth()->user()->hasVerifiedEmail())
            this.showEmailCard = true;

            // Panel reopen වූ විට නැවත show කරන්න
            this.$watch('open', val => {
                if (val) this.showEmailCard = true;
            });
            @endif

            await this.load();
            setInterval(() => this.load(), 60000);
        },

        dismissEmailCard() {
            this.showEmailCard = false;
        },

        async load() {
            try {
                const res  = await fetch('{{ route("hospital.notifications.data") }}', {
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                const data = await res.json();

                const list         = data.notifications ?? [];
                this.notifications = Array.isArray(list) ? list : Object.values(list);
                this.count         = this.notifications.filter(n => !n.is_read).length;

                const emailUnverified = {{ auth()->user()->hasVerifiedEmail() ? 'false' : 'true' }};
                this.totalBadge = this.count + (emailUnverified ? 1 : 0);

                // Pending appointments sidebar badge
                if ((data.pending_appointments ?? 0) > 0) {
                    const b = document.getElementById('pending-apt-badge');
                    if (b) { b.textContent = data.pending_appointments; b.classList.remove('hidden'); }
                }

                // Sidebar notifications badge
                const sb = document.getElementById('sidebar-notif-badge');
                if (sb) {
                    if (this.totalBadge > 0) {
                        sb.textContent = this.totalBadge;
                        sb.classList.remove('hidden');
                    } else {
                        sb.classList.add('hidden');
                    }
                }

            } catch(e) { console.error('Notifications load error:', e); }
            this.loading = false;
        }
    }
}

// ═══ Email Verify Banner Alpine Component ═══
// ✅ sessionStorage: fresh session/browser open = show, sidebar navigate = නෑ
function verifyBanner() {
    return {
        visible:     false,
        resending:   false,
        resent:      false,
        feedbackMsg: '',

        init() {
            const key = 'verifyBannerShown_{{ auth()->id() }}';

            if (sessionStorage.getItem(key)) {
                this.visible = false;
                return;
            }

            this.visible = true;
            sessionStorage.setItem(key, '1');
        },

        dismiss() {
            this.visible = false;
        },

        async resend() {
            this.resending   = true;
            this.feedbackMsg = '';
            try {
                const res  = await fetch('{{ route("hospital.resend.verification") }}', {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept':       'application/json',
                        'Content-Type': 'application/json',
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.resent      = true;
                    this.feedbackMsg = '✓ Sent to {{ auth()->user()->email }}';
                } else {
                    this.feedbackMsg = data.message ?? 'Failed. Please try again.';
                }
            } catch(e) {
                this.feedbackMsg = 'Connection error. Please try again.';
            }
            this.resending = false;
        }
    }
}

// ═══ Resend from Notifications Panel ═══
async function resendVerificationEmail(btn) {
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Sending...';
    try {
        const res  = await fetch('{{ route("hospital.resend.verification") }}', {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            }
        });
        const data = await res.json();
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check text-xs"></i> Email Sent!';
            btn.classList.replace('bg-blue-600', 'bg-green-600');
        } else {
            btn.innerHTML = 'Failed. Retry';
            btn.disabled  = false;
        }
    } catch(e) {
        btn.innerHTML = 'Error. Retry';
        btn.disabled  = false;
    }
}
</script>

@stack('scripts')
</body>
</html>
