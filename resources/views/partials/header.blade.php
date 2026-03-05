<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HealthNet - Professional Healthcare Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/stylescss') }}" />
    <link rel="stylesheet" href="{{ asset('css/main-style01.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/service-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />

    <style>
    /* ════════════════════════════════════════════════
       MOBILE RESPONSIVE FIX — ≤ 991px
       සියලු existing CSS override කරනවා
    ════════════════════════════════════════════════ */
    @media (max-width: 991.98px) {

        /* Collapse container */
        #navbarNav {
            overflow: visible !important;
        }
        #navbarNav.show,
        #navbarNav.collapsing {
            display: flex !important;
            flex-direction: column !important;
            background: #fff;
            border-top: 1px solid #e8f5e9;
            border-radius: 0 0 14px 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,.09);
            padding: 6px 4px 12px;
        }

        /* ── Nav links — FIRST in order ── */
        #navbarNav .navbar-nav {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            order: 1 !important;
            padding: 0 6px !important;
            gap: 2px !important;
            margin: 0 !important;
        }
        #navbarNav .navbar-nav .nav-item {
            width: 100% !important;
        }
        #navbarNav .navbar-nav .nav-link {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: #374151 !important;
            padding: 10px 14px !important;
            border-radius: 8px !important;
            font-size: .92rem !important;
            font-weight: 500 !important;
            white-space: nowrap !important;
        }
        #navbarNav .navbar-nav .nav-link:hover,
        #navbarNav .navbar-nav .nav-link.active {
            background: #f0fdf9 !important;
            color: #00796b !important;
        }

        /* ── Right controls — SECOND in order ── */
        #navbarNav .d-flex.align-items-center {
            order: 2 !important;
            display: flex !important;
            flex-wrap: wrap !important;
            width: 100% !important;
            padding: 10px 8px 4px !important;
            border-top: 1px solid #f0f4f8 !important;
            margin-top: 6px !important;
            gap: 8px !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        /* Google Translate — compact */
        .translation-widget {
            flex-shrink: 0;
            max-width: 140px;
        }

        /* Bell wrapper */
        .notification-bell-wrapper {
            margin-right: 0 !important;
            flex-shrink: 0;
            position: relative;
        }

        /* Notification dropdown — fixed full width */
        .notification-dropdown {
            position: fixed !important;
            top: 62px !important;
            left: 8px !important;
            right: 8px !important;
            width: auto !important;
            max-width: none !important;
            max-height: 72vh !important;
            overflow-y: auto !important;
            z-index: 9999 !important;
            border-radius: 14px !important;
            box-shadow: 0 8px 32px rgba(0,0,0,.18) !important;
            background: #fff !important;
        }

        /* Profile dropdown — fixed bottom sheet */
        .dropdown .dropdown-menu {
            position: fixed !important;
            top: auto !important;
            left: 8px !important;
            right: 8px !important;
            bottom: 8px !important;
            width: auto !important;
            min-width: unset !important;
            transform: none !important;
            border-radius: 14px !important;
            box-shadow: 0 -4px 32px rgba(0,0,0,.16) !important;
            max-height: 80vh !important;
            overflow-y: auto !important;
        }

        /* Profile button — compact */
        .btn-profile {
            padding: 5px 10px !important;
            font-size: .82rem !important;
        }
        .btn-profile img {
            width: 32px !important;
            height: 32px !important;
        }
        .btn-profile > span {
            max-width: 72px !important;
            font-size: .8rem !important;
        }

        /* Login / Signup */
        .btn-login,
        .btn-signup {
            padding: 7px 14px !important;
            font-size: .82rem !important;
        }

        /* Notification stack — below navbar */
        .notification-stack {
            top: 68px !important;
            right: 8px !important;
        }
    }

    /* ── Extra small: < 400px ── */
    @media (max-width: 399.98px) {
        .navbar-brand {
            font-size: 1rem !important;
        }
        .btn-profile > span {
            display: none !important;
        }
        .btn-profile::after {
            display: none !important;
        }
        .notification-bell-btn {
            font-size: .9rem !important;
            padding: 5px 6px !important;
        }
    }
    </style>
</head>
<body>
    {{-- ══ Notification Stack ══ --}}
    <div class="notification-stack">
        @auth
            @if(session('verified'))
                <div class="verify-alert" id="verifyAlert">
                    <span class="icon"><i class="fas fa-envelope-circle-check"></i></span>
                    <div class="content">
                        <h4>Email Verified!</h4>
                        <p>Your account is now active.</p>
                    </div>
                    <button class="close-btn" onclick="closeAlert('verifyAlert')">&times;</button>
                </div>
            @elseif(session('resend_sent'))
                <div class="verify-alert" id="verifyAlert">
                    <span class="icon"><i class="fas fa-envelope-circle-check"></i></span>
                    <div class="content">
                        <h4>Verification Email Sent!</h4>
                        <p>Check your inbox: {{ auth()->user()->email }}</p>
                    </div>
                    <button class="close-btn" onclick="closeAlert('verifyAlert')">&times;</button>
                </div>
            @endif

            <div class="welcome-alert" id="welcomeAlert" style="display:none;">
                <span class="icon"><i class="fas fa-bolt"></i></span>
                <div class="content">
                    <h4>Welcome!</h4>
                    <p id="welcomeText">Welcome to HealthNet!</p>
                </div>
                <button class="close-btn" onclick="closeAlert('welcomeAlert')">&times;</button>
            </div>

            @if(!auth()->user()->hasVerifiedEmail())
            <div class="resend-widget" id="resendWidget" onclick="handleResendClick()">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </div>
            @endif
        @endauth
    </div>

    {{-- ══ Navbar ══ --}}
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('patient.dashboard') }}">
                <i class="fas fa-heartbeat me-2"></i>HealthNet
            </a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('patient.dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#health-tips">Health Tips</a></li>
                    <li class="nav-item"><a class="nav-link" href="#featured-doctors">Doctors</a></li>
                    <li class="nav-item"><a class="nav-link" href="#emergency-help">Contact</a></li>
                </ul>

                <div class="d-flex align-items-center">
                    {{-- Google Translate --}}
                    <div class="translation-widget me-3">
                        <div class="gtranslate_wrapper"></div>
                    </div>

                    @auth
                    @php
                        $authUser    = Auth::user();
                        $authPatient = $authUser->patient ?? null;

                        $profileImg = $authPatient?->profile_image
                            ? asset('storage/' . $authPatient->profile_image)
                            : ($authUser->profile_image
                                ? asset('storage/' . $authUser->profile_image)
                                : asset('images/default-avatar.png'));

                        $displayName = $authPatient
                            ? trim(($authPatient->firstname ?? '') . ' ' . ($authPatient->lastname ?? ''))
                            : '';
                        $displayName = $displayName ?: strtok($authUser->email, '@');

                        $unreadCount = $authUser->notifications()->where('is_read', false)->count();
                    @endphp

                        {{-- ══ Notification Bell ══ --}}
                        <div class="notification-bell-wrapper me-3">
                            <button class="notification-bell-btn" id="notificationBell" type="button">
                                <i class="fas fa-bell"></i>
                                @if($unreadCount > 0)
                                    <span class="notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </button>

                            {{-- Notification Dropdown --}}
                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
                                    @if($unreadCount > 0)
                                        <button class="mark-all-read" onclick="markAllAsRead()">
                                            <i class="fas fa-check-double"></i> Mark all read
                                        </button>
                                    @endif
                                </div>

                                {{-- Email Not Verified Warning --}}
                                @if(!$authUser->hasVerifiedEmail())
                                <div class="resend-email-notification" style="padding:1rem;background:#fff3cd;border-bottom:1px solid #ffc107">
                                    <div style="display:flex;align-items:center;gap:.8rem">
                                        <i class="fas fa-exclamation-triangle" style="color:#ff9800;font-size:1.2rem"></i>
                                        <div style="flex:1">
                                            <strong style="color:#856404;font-size:.9rem">Email Not Verified</strong>
                                            <p style="margin:.2rem 0 0;font-size:.8rem;color:#856404">Please verify your email to access all features.</p>
                                        </div>
                                    </div>
                                    <button onclick="resendVerificationEmail()"
                                            class="btn btn-warning btn-sm mt-2 w-100"
                                            id="resendBtnInDropdown">
                                        <i class="fas fa-paper-plane me-1"></i> Resend Verification Email
                                    </button>
                                </div>
                                @endif

                                {{-- Notification List --}}
                                <div class="notification-list" id="notificationList">
                                    @php
                                        $recentNotifications = $authUser->notifications()
                                            ->orderBy('created_at', 'desc')
                                            ->limit(5)->get();
                                    @endphp

                                    @forelse($recentNotifications as $notification)
                                    <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                                         data-id="{{ $notification->id }}"
                                         onclick="markAsRead({{ $notification->id }})">
                                        <div class="notification-icon notification-{{ $notification->type }}">
                                            <i class="fas fa-{{
                                                $notification->type == 'appointment' ? 'calendar-check' :
                                                ($notification->type == 'payment'     ? 'credit-card' :
                                                ($notification->type == 'prescription'? 'pills' :
                                                ($notification->type == 'lab_report'  ? 'flask' : 'bell')))
                                            }}"></i>
                                        </div>
                                        <div class="notification-content">
                                            <h6>{{ $notification->title }}</h6>
                                            <p>{{ Str::limit($notification->message, 60) }}</p>
                                            <span class="notification-time">
                                                <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if(!$notification->is_read)
                                            <span class="unread-dot"></span>
                                        @endif
                                    </div>
                                    @empty
                                    <div class="no-notifications">
                                        <i class="fas fa-bell-slash"></i>
                                        <p>No notifications yet</p>
                                    </div>
                                    @endforelse
                                </div>

                                <div class="notification-footer">
                                    <a href="{{ route('patient.notifications') }}" class="view-all-btn">
                                        View All Notifications <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- ══ User Profile Dropdown ══ --}}
                        <div class="dropdown">
                            <button class="btn btn-profile dropdown-toggle d-flex align-items-center"
                                    type="button" id="profileDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ $profileImg }}"
                                     alt="Profile"
                                     class="rounded-circle me-2"
                                     width="38" height="38"
                                     style="object-fit:cover"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                <span style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ Auth::user()->patient->first_name ?? strtok(Auth::user()->email, '@') }}
                                </span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown"
                                style="min-width:220px;border-radius:12px;padding:.5rem;border:1px solid #e0f2f1;box-shadow:0 8px 24px rgba(0,0,0,.1)">

                                {{-- Header Info --}}
                                <li>
                                    <div style="padding:.6rem .85rem .5rem;border-bottom:1px solid #f0f4f8;margin-bottom:.3rem">
                                        <div style="font-weight:700;font-size:.88rem;color:#1a1a1a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ trim((Auth::user()->patient->first_name ?? '') . ' ' . (Auth::user()->patient->last_name ?? '')) ?: strtok(Auth::user()->email, '@') }}
                                        </div>
                                        <div style="font-size:.75rem;color:#888;margin-top:.1rem">{{ $authUser->email }}</div>
                                        @if($authPatient?->nic)
                                        <div style="font-size:.72rem;color:#00796b;margin-top:.15rem">
                                            <i class="fas fa-id-card me-1"></i>{{ $authPatient->nic }}
                                        </div>
                                        @endif
                                    </div>
                                </li>

                                {{-- Menu Items --}}
                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('patient.profile') }}"
                                       style="font-size:.84rem;padding:.5rem .85rem">
                                        <i class="fas fa-user-circle me-2" style="color:#00796b;width:16px"></i>My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('patient.appointments.index') }}"
                                       style="font-size:.84rem;padding:.5rem .85rem">
                                        <i class="fas fa-calendar-check me-2" style="color:#00796b;width:16px"></i>My Appointments
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('patient.lab-orders.index') }}"
                                       style="font-size:.84rem;padding:.5rem .85rem">
                                        <i class="fas fa-flask me-2" style="color:#00796b;width:16px"></i>My Lab Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('patient.pharmacy-orders.index') }}"
                                       style="font-size:.84rem;padding:.5rem .85rem">
                                        <i class="fas fa-pills me-2" style="color:#00796b;width:16px"></i>Pharmacy Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('patient.notifications') }}"
                                       style="font-size:.84rem;padding:.5rem .85rem">
                                        <i class="fas fa-bell me-2" style="color:#00796b;width:16px"></i>
                                        Notifications
                                        @if($unreadCount > 0)
                                        <span style="background:#dc2626;color:#fff;border-radius:10px;padding:.1rem .45rem;font-size:.68rem;margin-left:.3rem">{{ $unreadCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider my-1"></li>

                                <li>
                                    <a class="dropdown-item rounded-2" href="#"
                                       style="font-size:.84rem;padding:.5rem .85rem;color:#dc2626"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                                        <i class="fas fa-sign-out-alt me-2" style="width:16px"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Hidden Logout Form (POST) --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    @else
                        <a href="{{ route('login') }}" class="btn-login me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        <a href="{{ route('signup') }}" class="btn-signup">
                            <i class="fas fa-user-plus me-1"></i>Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @include('partials.medicine-alarm')
