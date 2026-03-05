{{-- resources/views/laboratory/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Laboratory') - HealthNet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 9px 14px; border-radius: 10px;
            font-size: 13.5px; font-weight: 500;
            transition: all 0.2s; color: #ccfbf1;
            text-decoration: none; width: 100%; box-sizing: border-box;
        }
        .sidebar-link:not(.active):hover { background: rgba(255,255,255,0.12); color: #ffffff; }
        .sidebar-link.active {
            background: #ffffff; color: #0f766e;
            font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .sidebar-group-label {
            display: block; padding: 16px 14px 4px;
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase; color: #5eead4;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true }">
<div class="flex h-screen overflow-hidden">

    {{-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 lg:w-16'"
           class="flex-shrink-0 bg-teal-800 transition-all duration-300 overflow-hidden flex flex-col shadow-xl z-40">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 bg-teal-900 flex-shrink-0">
            <div class="bg-white/20 p-2 rounded-lg flex-shrink-0">
                <i class="fas fa-flask text-white text-lg w-5 text-center"></i>
            </div>
            <div x-show="sidebarOpen" x-cloak class="overflow-hidden">
                <p class="text-white font-bold text-base whitespace-nowrap">HealthNet</p>
                <p class="text-teal-300 text-xs whitespace-nowrap">Laboratory Portal</p>
            </div>
        </div>

        {{-- Lab Info --}}
        @php $lab = Auth::user()->laboratory; @endphp
        <div x-show="sidebarOpen" x-cloak class="px-4 py-4 border-b border-teal-700">
            <div class="flex items-center gap-3">
                <img src="{{ $lab && $lab->profile_image ? asset('storage/'.$lab->profile_image) : asset('images/default-lab.png') }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-teal-400 flex-shrink-0"
                     onerror="this.src='{{ asset('images/default-lab.png') }}'">
                <div class="overflow-hidden">
                    <p class="text-white font-semibold text-sm truncate">{{ $lab->name ?? 'My Laboratory' }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        @if($lab && $lab->status === 'approved')
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            <span class="text-green-300 text-xs">Verified</span>
                        @else
                            <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                            <span class="text-yellow-300 text-xs">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5">

            {{-- Overview --}}
            <p class="sidebar-group-label" x-show="sidebarOpen" x-cloak>Overview</p>
            <a href="{{ route('laboratory.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Dashboard</span>
            </a>

            {{-- Management --}}
            <p class="sidebar-group-label" x-show="sidebarOpen" x-cloak>Management</p>

            <a href="{{ route('laboratory.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.orders.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Orders</span>
                @php
                    $pending = \DB::table('lab_orders')
                        ->where('laboratory_id', $lab->id ?? 0)
                        ->where('status', 'pending')
                        ->count();
                @endphp
                @if($pending > 0)
                    <span x-show="sidebarOpen" x-cloak
                          class="ml-auto bg-yellow-400 text-teal-900 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $pending }}
                    </span>
                @endif
            </a>

            <a href="{{ route('laboratory.tests.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.tests.*') ? 'active' : '' }}">
                <i class="fas fa-vials w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Tests</span>
            </a>

            <a href="{{ route('laboratory.packages.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.packages.*') ? 'active' : '' }}">
                <i class="fas fa-box-open w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Packages</span>
            </a>

            {{-- Finance --}}
            <p class="sidebar-group-label" x-show="sidebarOpen" x-cloak>Finance</p>
            <a href="{{ route('laboratory.payments.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Payments</span>
            </a>

            {{-- Engagement --}}
            <p class="sidebar-group-label" x-show="sidebarOpen" x-cloak>Engagement</p>

            <a href="{{ route('laboratory.reviews.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Reviews</span>
            </a>

            {{-- <a href="{{ route('laboratory.announcements.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Announcements</span>
            </a> --}}

            {{-- Chat — table exist check කරලා badge show කරනවා
            <a href="{{ route('laboratory.chat.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.chat.*') ? 'active' : '' }}">
                <i class="fas fa-comments w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Messages</span>
                @php
                    try {
                        $unreadChat = \DB::table('chat_messages')
                            ->where('receiver_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    } catch (\Exception $e) {
                        $unreadChat = 0;
                    }
                @endphp
                @if($unreadChat > 0)
                    <span x-show="sidebarOpen" x-cloak
                          class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $unreadChat }}
                    </span>
                @endif
            </a> --}}

            {{-- Account --}}
            <p class="sidebar-group-label" x-show="sidebarOpen" x-cloak>Account</p>

            <a href="{{ route('laboratory.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.profile.*') ? 'active' : '' }}">
                <i class="fas fa-building w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Lab Profile</span>
            </a>

            <a href="{{ route('laboratory.notifications.index') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Notifications</span>
                @php
                    $unread = \DB::table('notifications')
                        ->where('notifiable_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                @if($unread > 0)
                    <span x-show="sidebarOpen" x-cloak
                          class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $unread }}
                    </span>
                @endif
            </a>

            <a href="{{ route('laboratory.settings') }}"
               class="sidebar-link {{ request()->routeIs('laboratory.settings') ? 'active' : '' }}">
                <i class="fas fa-cog w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak>Settings</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full text-left text-teal-100 hover:bg-red-600/30 hover:text-red-200">
                    <i class="fas fa-sign-out-alt w-5 text-center flex-shrink-0"></i>
                    <span x-show="sidebarOpen" x-cloak>Logout</span>
                </button>
            </form>

        </nav>
    </aside>

    {{-- ══════════════════════════════════════
         MAIN AREA
    ══════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- TOP BAR --}}
        <header class="bg-white border-b border-gray-200 px-6 h-16 flex items-center justify-between flex-shrink-0 shadow-sm z-30">

            {{-- Left --}}
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-teal-700 transition p-1.5 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h1 class="text-base font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400">@yield('page-subtitle', 'Overview of laboratory activities')</p>
                </div>
            </div>

            {{-- Right --}}
            <div class="flex items-center gap-3">

                {{-- Notification Bell --}}
                @php
                    $__notifCount = \DB::table('notifications')
                        ->where('notifiable_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                @endphp
                <a href="{{ route('laboratory.notifications.index') }}"
                   class="relative p-2 text-gray-500 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition">
                    <i class="fas fa-bell text-lg"></i>
                    @if($__notifCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                            {{ $__notifCount > 9 ? '9+' : $__notifCount }}
                        </span>
                    @endif
                </a>

                {{-- Chat Icon — chat_messages table නොමැති නම් error නොදෙනු ඇත --}}
                {{-- @php
                    try {
                        $__chatCount = \DB::table('chat_messages')
                            ->where('receiver_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    } catch (\Exception $e) {
                        $__chatCount = 0;
                    }
                @endphp --}}
                {{-- <a href="{{ route('laboratory.chat.index') }}"
                   class="relative p-2 text-gray-500 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition">
                    <i class="fas fa-comment-dots text-lg"></i>
                    @if($__chatCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                            {{ $__chatCount }}
                        </span>
                    @endif
                </a> --}}

                {{-- Profile Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2 hover:bg-gray-100 rounded-lg px-2 py-1.5 transition">
                        <img src="{{ Auth::user()->laboratory && Auth::user()->laboratory->profile_image ? asset('storage/'.Auth::user()->laboratory->profile_image) : asset('images/default-lab.png') }}"
                             class="w-8 h-8 rounded-full object-cover border-2 border-teal-500"
                             onerror="this.src='{{ asset('images/default-lab.png') }}'">
                        <span class="text-sm font-semibold text-gray-700 hidden md:block max-w-[120px] truncate">
                            {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="font-semibold text-gray-900 text-sm truncate">
                                {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('laboratory.profile.index') }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                            <i class="fas fa-building w-4"></i> Lab Profile
                        </a>
                        <a href="{{ route('laboratory.settings') }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                            <i class="fas fa-cog w-4"></i> Settings
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3 mb-5 shadow-sm">
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    <span class="flex-1 text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-400 hover:text-green-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3 mb-5 shadow-sm">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    <span class="flex-1 text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3 mb-5">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
