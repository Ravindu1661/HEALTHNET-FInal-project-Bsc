<!-- Notification Panel -->
<div class="notification-panel" id="notificationPanel">
    <div class="notification-header">
        <h6>Notifications</h6>
        <button class="btn-close" id="closeNotifications"><i class="fas fa-times"></i></button>
    </div>
    <div class="notification-body">
        @php
            try {
                $notifications = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                $notifications = collect();
            }
        @endphp
        
        @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->is_read ? '' : 'unread' }}">
            <div class="notification-icon notification-{{ $notification->type ?? 'general' }}">
                <i class="fas fa-{{ $notification->icon ?? 'bell' }}"></i>
            </div>
            <div class="notification-content">
                <h6>{{ $notification->title ?? 'Notification' }}</h6>
                <p>{{ $notification->message ?? '' }}</p>
                <span class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <p>No notifications</p>
        </div>
        @endforelse
    </div>
</div>
