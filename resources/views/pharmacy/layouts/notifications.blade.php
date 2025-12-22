{{-- resources/views/pharmacy/layouts/notifications.blade.php --}}
<div class="notification-panel" id="notificationPanel">
    <div class="notification-header">
        <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
        <button class="close-panel" id="closeNotifications">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="notification-body">
        @php
            try {
                $notifications = \DB::table('notifications')
                    ->where('notifiable_id', auth()->id())
                    ->where('notifiable_type', 'App\Models\User')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                $notifications = collect();
            }
        @endphp

        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? '' : 'unread' }}">
                <div class="notification-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="notification-content">
                    <h6>{{ $notification->title }}</h6>
                    <p>{{ $notification->message }}</p>
                    <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3 d-block"></i>
                <p>No notifications</p>
            </div>
        @endforelse
    </div>

    <div class="notification-footer">
        <a href="{{ route('pharmacy.notifications') }}" class="btn btn-sm btn-primary w-100">
            View All Notifications
        </a>
    </div>
</div>

<div class="notification-overlay" id="notificationOverlay"></div>
