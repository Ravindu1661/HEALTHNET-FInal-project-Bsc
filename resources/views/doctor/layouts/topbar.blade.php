<div class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="page-title">
            <h5>@yield('page-title', 'Dashboard')</h5>
        </div>
    </div>

    <div class="topbar-right">
        {{-- Quick Actions --}}
        <div class="quick-actions">
            <button class="action-btn" data-bs-toggle="tooltip" title="New Appointment">
                <i class="fas fa-calendar-plus"></i>
            </button>
            <button class="action-btn" data-bs-toggle="tooltip" title="Messages">
                <i class="fas fa-envelope"></i>
                <span class="badge-dot">3</span>
            </button>
        </div>

        {{-- Notifications --}}
        <div class="notification-dropdown-wrapper">
            <button class="notification-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                @php
                    $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge-count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>

            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
                    @if($unreadCount > 0)
                        <button class="mark-all-btn" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Mark all
                        </button>
                    @endif
                </div>

                <div class="notification-list">
                    @php
                        $notifications = auth()->user()->notifications()
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @forelse($notifications as $notification)
                        <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}"
                             data-id="{{ $notification->id }}"
                             onclick="markAsRead({{ $notification->id }})">
                            <div class="notification-icon notification-{{ $notification->type }}">
                                <i class="fas fa-{{ $notification->type == 'appointment' ? 'calendar-check' : 'bell' }}"></i>
                            </div>
                            <div class="notification-content">
                                <h6>{{ $notification->title }}</h6>
                                <p>{{ Str::limit($notification->message, 50) }}</p>
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
                            <p>No notifications</p>
                        </div>
                    @endforelse
                </div>

                <div class="notification-footer">
                    <a href="{{ route('doctor.notifications') }}" class="view-all-btn">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- User Profile --}}
        <div class="user-profile-dropdown">
            <button class="profile-btn" id="profileBtn">
                <img src="{{ auth()->user()->doctor->profile_image ? asset('storage/' . auth()->user()->doctor->profile_image) : asset('images/default-doctor.png') }}" alt="Profile">
                <div class="profile-info">
                    <span class="name">Dr. {{ auth()->user()->doctor->first_name }}</span>
                    <span class="role">Doctor</span>
                </div>
                <i class="fas fa-chevron-down"></i>
            </button>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-user-info">
                    <img src="{{ auth()->user()->doctor->profile_image ? asset('storage/' . auth()->user()->doctor->profile_image) : asset('images/default-doctor.png') }}"  alt="Profile">
                    <h6>Dr. {{ auth()->user()->doctor->first_name }} {{ auth()->user()->doctor->last_name }}</h6>
                    <p>{{ auth()->user()->email }}</p>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('doctor.profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </a>
                <a href="{{ route('doctor.settings') }}" class="dropdown-item">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
<style>:root {
  --primary: #2969bf;      /* dark blue */
  --primary-dark: #23497d; /* even darker */
  --success: #2492ee;
  --danger: #e74c3c;
  --warning: #f7b731;
  --info: #3498db;
  --light: #f8f9fa;
  --dark: #1c1e22;
  --sidebar-width: 220px;
  --topbar-height: 58px;
}

/* Main Topbar Styling */
.topbar {
  position: fixed;
  top: 0;
  left: var(--sidebar-width);
  right: 0;
  height: var(--topbar-height);
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2.2rem 0 1.2rem;
  box-shadow: 0 2px 12px rgba(44, 62, 80, 0.07);
  z-index: 1010;
  border-bottom: 1px solid #e5ecee;
}
.topbar-left { display: flex; align-items: center; gap: 1rem; }
.sidebar-toggle {
  background: transparent; border: none; font-size: 1.2rem; color: var(--primary);
  cursor: pointer; padding: 0.5rem; border-radius: 6px;
  transition: background 0.3s, color 0.3s;
}
.sidebar-toggle:hover { background: #e5ecee; color: var(--success); }
.page-title h5 { font-size: 1rem; font-weight: 700; color: var(--primary); letter-spacing: 0.02em; margin: 0; }
.topbar-right { display: flex; align-items: center; gap: 1.2rem; }

/* Quick Actions */
.quick-actions { display: flex; gap: 0.45rem; }
.action-btn {
  position: relative; background: #f6f8fd; border: none; width: 38px; height: 38px;
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: box-shadow 0.25s, background 0.25s, color 0.25s;
  font-size: 1rem; color: var(--primary); outline: none;
  box-shadow: 0 4px 12px rgba(44, 62, 80, 0.048);
}
.action-btn:hover { color: #fff; background: var(--primary); box-shadow: 0 7px 15px rgba(44, 62, 80, 0.079);}
.badge-dot {
  position: absolute; top: 5px; right: 5px; width: 16px; height: 16px;
  background: var(--danger); color: #fff; border-radius: 99px;
  font-size: 0.62rem; font-weight: 600; text-align: center;
  display: flex; align-items: center; justify-content: center; line-height: 1;
}

/* Notification Dropdown */
.notification-dropdown-wrapper {position: relative;}
.notification-btn {
  background: #f6f8fd; border: none; width: 38px; height: 38px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.3s, color 0.3s;
  color: var(--primary); font-size: 1rem; position: relative;
}
.notification-btn:hover { background: var(--primary); color: #fff;}
.badge-count {
  position: absolute; top: -2px; right: -2px; background: var(--danger);
  color: #fff; font-size: 0.62rem; font-weight: bold; border-radius: 8px;
  padding: 0.14rem 0.38rem; min-width: 16px;
}
.notification-dropdown {
  position: absolute; top: 50px; right: 0; width: 330px; max-height: 410px;
  background: #f7fafc; border: 1px solid #e5ecee; border-radius: 12px;
  box-shadow: 0 10px 32px rgba(44, 62, 80, 0.14); opacity: 0; visibility: hidden;
  transform: translateY(-15px); transition: all 0.22s; z-index: 1002;
}
.notification-dropdown.show {opacity: 1; visibility: visible; transform: translateY(0);}
.notification-header {
  padding: 1rem 1.1rem;
  background: linear-gradient(100deg, var(--primary) 70%, var(--primary-dark) 100%);
  color: #fff;
  display: flex; justify-content: space-between; align-items: center;
  border-radius: 12px 12px 0 0;
}
.notification-header h6 {margin:0; font-size: 0.87rem; font-weight: 600;}
.mark-all-btn {
  background: #fff3; border: none; color: #fff; padding: 0.22rem 0.7rem;
  border-radius: 12px; font-size: 0.68rem; cursor: pointer; transition: background 0.2s;
}
.mark-all-btn:hover {background: #fff5;}
.notification-list {max-height: 245px; overflow-y:auto; padding:0.4rem 0; background:#fff;}
.notification-item {
  padding: 0.75rem 1.1rem; border-bottom: 1px solid #f1f1f1; display: flex;
  gap: 0.7rem; align-items: flex-start; cursor:pointer; transition:background 0.18s; position: relative;
}
.notification-item:last-child{border-bottom: none;}
.notification-item:hover {background: #f3f7fd;}
.notification-item.unread {background: #e6eef7;}
.notification-icon {
     width: 32px; height: 32px; border-radius: 50%; display: flex;
     align-items: center; justify-content: center; font-size: 1.1rem; color: #ffffff !important; flex-shrink: 0;
     box-shadow: 0 2px 8px rgba(44,62,80,0.13);
     background: #3498db;
}
.notification-general {
    background: linear-gradient(135deg, #3498db 40%, #2969bf 100%);
}
.notification-info {
    background: linear-gradient(135deg, #429be7, #2969bf 100%);
}
.notification-appointment {
    background: linear-gradient(135deg, #42a649, #22b7c0 100%);
}


.notification-content {flex:1; min-width:0;}
.notification-content h6 { font-size: 0.8rem; font-weight: 600; color: var(--primary); margin: 0 0 0.15rem 0; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.notification-content p { font-size: 0.72rem; margin:0 0 0.3rem 0; color:#444;}
.notification-time { font-size: 0.6rem; color: #6c757d; display:flex; align-items:center; gap:0.15rem;}
.unread-dot { position: absolute; top: 55%; right:0.7rem; transform: translateY(-50%); width: 7px; height: 7px; background: var(--danger); border-radius:99px;}
.no-notifications { text-align:center; padding:2.1rem 1rem; color: #888;}
.no-notifications i { font-size: 2.1rem; margin-bottom: 0.5rem; color: #ccc;}
.no-notifications p { margin: 0; font-size: 0.8rem;}
.notification-footer { padding: 0.7rem 1.1rem; background: #f1f5fa; border-radius:0 0 12px 12px;}
.view-all-btn {
   display: block; text-align: center; color: var(--primary); text-decoration: none; font-weight: 600;
   font-size: 0.75rem; padding: 0.22rem; border-radius:6px; transition: background 0.21s, color 0.21s;
}
.view-all-btn:hover { background: rgba(41, 105, 191, 0.08); color: var(--primary-dark); }

/* Profile Dropdown */
.user-profile-dropdown {position:relative;}
.profile-btn {
  display: flex; align-items: center; gap:1rem; background: transparent; border: none; cursor:pointer;
  padding: 0.38rem 0.8rem; border-radius: 9px; transition:background 0.21s;
}
.profile-btn:hover {background: #f3f7fd;}
.profile-btn img { width: 40px; height: 40px; border-radius: 50%; object-fit:cover; border: 2px solid var(--primary);}
.profile-info { display: flex; flex-direction: column; align-items: flex-start; text-align:left;}
.profile-info .name { font-size: 0.89rem; font-weight:700; color:var(--dark);}
.profile-info .role { font-size:0.74rem; color:#6c757d;}
.profile-btn i { font-size: 0.7rem; color: #999;}

.profile-dropdown {
  position: absolute; top:56px; right:0; width: 240px; background: #fff;
  border:1px solid #e5ecee; border-radius: 12px; box-shadow:0 10px 32px rgba(44,62,80,0.10);
  opacity: 0; visibility:hidden; transform:translateY(-17px); transition:all 0.25s; z-index:1002;
}
.profile-dropdown.show { opacity:1; visibility:visible; transform:translateY(0);}
.dropdown-user-info { padding: 1.1rem; text-align:center;}
.dropdown-user-info img { width: 66px; height: 66px; border-radius: 50%; object-fit:cover; border: 3px solid var(--primary); margin-bottom: 0.45rem;}
.dropdown-user-info h6 { font-size:0.98rem; font-weight:700; color:var(--primary); margin:0 0 0.21rem 0;}
.dropdown-user-info p { font-size: 0.72rem; color:#6c757d; margin:0;}
.dropdown-divider { height: 1px; background: #e5ecef; margin: 0.46rem 0;}
.dropdown-item {
  display: flex; align-items: center; gap: 0.7rem; padding: 0.75rem 1.1rem; color: var(--dark); text-decoration:none;
  font-size:0.88rem; transition:background 0.19s;
}
.dropdown-item:hover { background: #f5f7fc;}
.dropdown-item i { width:18px; font-size:0.98rem;}

@media (max-width: 900px) {
  .topbar {left: 0;}
  .sidebar-toggle {display: block;}
  .page-title h5 {font-size: 0.92rem;}
  .profile-info {display: none;}
  .quick-actions {display: none;}
}


</style>

<script>
    // Toggle Notification Dropdown
    document.getElementById('notificationBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notificationDropdown').classList.toggle('show');
        document.getElementById('profileDropdown').classList.remove('show');
    });

    // Toggle Profile Dropdown
    document.getElementById('profileBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('profileDropdown').classList.toggle('show');
        document.getElementById('notificationDropdown').classList.remove('show');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-dropdown-wrapper')) {
            document.getElementById('notificationDropdown').classList.remove('show');
        }
        if (!e.target.closest('.user-profile-dropdown')) {
            document.getElementById('profileDropdown').classList.remove('show');
        }
    });

    // Mark notification as read
    function markAsRead(id) {
        fetch(`/doctor/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }

    // Mark all as read
    function markAllAsRead() {
        fetch('/doctor/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
