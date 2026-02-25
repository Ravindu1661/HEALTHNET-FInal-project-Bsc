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



</head>
<body>
    {{-- Notification Stack --}}
    <div class="notification-stack">
        @auth
            @if(session('verified') || !auth()->user()->hasVerifiedEmail())
            <div class="verify-alert" id="verifyAlert">
                <span class="icon"><i class="fas fa-envelope-circle-check"></i></span>
                <div class="content">
                    <h4>{{ session('verified') ? 'Email Verified!' : 'Verification Email Sent!' }}</h4>
                    <p>{{ session('verified') ? 'Your account is now active.' : 'Check your inbox: ' . auth()->user()->email }}</p>
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

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('patient.dashboard') }}">
                <i class="fas fa-heartbeat me-2"></i>HealthNet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('patient.dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#doctors">Doctors</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="translation-widget me-3">
                        <div class="gtranslate_wrapper"></div>
                    </div>
                    @auth
                        {{-- Notification Bell Icon --}}
                        <div class="notification-bell-wrapper me-3">
                            <button class="notification-bell-btn" id="notificationBell" type="button">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                                @endphp
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

                                {{-- ✅ RESEND EMAIL BUTTON (shows if not verified) --}}
                                @if(!auth()->user()->hasVerifiedEmail())
                                    <div class="resend-email-notification" style="padding: 1rem; background: #fff3cd; border-bottom: 1px solid #ffc107;">
                                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                                            <i class="fas fa-exclamation-triangle" style="color: #ff9800; font-size: 1.2rem;"></i>
                                            <div style="flex: 1;">
                                                <strong style="color: #856404; font-size: 0.9rem;">Email Not Verified</strong>
                                                <p style="margin: 0.2rem 0 0; font-size: 0.8rem; color: #856404;">Please verify your email to access all features.</p>
                                            </div>
                                        </div>
                                        <button onclick="resendVerificationEmail()" class="btn btn-warning btn-sm mt-2 w-100" id="resendBtnInDropdown">
                                            <i class="fas fa-paper-plane me-1"></i> Resend Verification Email
                                        </button>
                                    </div>
                                @endif

                                <div class="notification-list" id="notificationList">
                                    @php
                                        $recentNotifications = auth()->user()->notifications()
                                            ->orderBy('created_at', 'desc')
                                            ->limit(5)
                                            ->get();
                                    @endphp

                                    @forelse($recentNotifications as $notification)
                                        <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                                             data-id="{{ $notification->id }}"
                                             onclick="markAsRead({{ $notification->id }})">
                                            <div class="notification-icon notification-{{ $notification->type }}">
                                                <i class="fas fa-{{
                                                    $notification->type == 'appointment' ? 'calendar-check' :
                                                    ($notification->type == 'payment' ? 'credit-card' :
                                                    ($notification->type == 'prescription' ? 'pills' :
                                                    ($notification->type == 'lab_report' ? 'flask' : 'bell')))
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

                        {{-- User Profile Dropdown --}}
                        <div class="dropdown">
                            <button class="btn btn-profile dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('images/default-avatar.png') }}"
                                     alt="Profile" class="rounded-circle me-2" width="38" height="38">
                                {{ strtok(Auth::user()->email, '@') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="{{ route('patient.dashboard') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-login me-2"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        <a href="{{ route('signup') }}" class="btn-signup"><i class="fas fa-user-plus me-1"></i>Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
