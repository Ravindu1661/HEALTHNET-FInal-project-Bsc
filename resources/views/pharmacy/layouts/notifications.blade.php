<div class="notif-overlay" id="notifOverlay"></div>
<div class="notif-panel" id="notifPanel">
    <div class="notif-panel-header">
        <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
        <button id="closeNotif" class="btn btn-sm p-0" style="color:#fff;font-size:16px;line-height:1">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-grow-1 overflow-auto">
        @php
            try {
                $__panelNotifs = \DB::table('notifications')
                    ->where('notifiable_id', Auth::id())
                    ->where('notifiable_type', 'App\Models\User')
                    ->orderBy('created_at', 'desc')
                    ->limit(12)->get();
            } catch (\Exception $e) { $__panelNotifs = collect(); }
        @endphp

        @forelse($__panelNotifs as $notif)
            <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                <h6>{{ $notif->title }}</h6>
                <p>{{ \Illuminate\Support\Str::limit($notif->message, 80) }}</p>
                <small class="text-muted" style="font-size:10px">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </small>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                <p style="font-size:12px">No notifications</p>
            </div>
        @endforelse
    </div>

    <div class="p-3 border-top">
        <a href="{{ route('pharmacy.notifications') }}"
           class="btn btn-primary btn-sm w-100" style="font-size:12px">
            View All Notifications
        </a>
    </div>
</div>
