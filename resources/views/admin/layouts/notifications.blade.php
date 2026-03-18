<!-- Notification Panel (Topbar Dropdown) -->
<div class="notification-panel" id="notificationPanel">
    <div class="notification-header">
        <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
        <button class="btn-close" id="closeNotifications">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="notification-body">
        @php
            try {
                // ✅ FIX: user_id → notifiable_type + notifiable_id
                $panelNotifs = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                $panelUnread = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('notifiable_type', 'App\Models\User')
                    ->where('notifiable_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

            } catch (\Exception $e) {
                $panelNotifs = collect();
                $panelUnread = 0;
            }

            $iconMap = [
                'appointment'        => 'fa-calendar-check',
                'payment'            => 'fa-money-bill-wave',
                'workplace_request'  => 'fa-hospital',
                'workplace_approved' => 'fa-check-circle',
                'workplace_rejected' => 'fa-times-circle',
                'prescription'       => 'fa-prescription',
                'lab_report'         => 'fa-flask',
                'labreport'          => 'fa-flask',
                'reminder'           => 'fa-bell',
                'announcement'       => 'fa-bullhorn',
                'general'            => 'fa-bell',
            ];
        @endphp

        {{-- Sub-header: unread count + mark all button --}}
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:0.5rem 1rem 0.4rem;border-bottom:1px solid #f0f0f5;">
            <span style="font-size:0.72rem;color:#888;">
                @if($panelUnread > 0)
                    <strong style="color:#3b82f6;">{{ $panelUnread }}</strong> unread
                @else
                    All caught up ✓
                @endif
            </span>
            <div style="display:flex;gap:0.5rem;align-items:center;">
                @if($panelUnread > 0)
                <button onclick="panelMarkAllRead()"
                        style="font-size:0.7rem;color:#3b82f6;background:none;
                               border:none;cursor:pointer;padding:0;font-weight:600;">
                    Mark all read
                </button>
                @endif
                <a href="{{ route('admin.notifications.index') }}"
                   style="font-size:0.7rem;color:#888;text-decoration:none;">
                    View all →
                </a>
            </div>
        </div>

        @forelse($panelNotifs as $notif)
        <div class="notification-item {{ $notif->is_read ? '' : 'unread' }}"
             id="pni-{{ $notif->id }}"
             style="cursor:default;">
            <div class="notification-icon notification-{{ $notif->type ?? 'general' }}">
                <i class="fas {{ $iconMap[$notif->type ?? 'general'] ?? 'fa-bell' }}"></i>
            </div>
            <div class="notification-content" style="flex:1;min-width:0;">
                <h6 style="font-size:0.8rem;font-weight:700;margin-bottom:0.12rem;
                           white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $notif->title ?? 'Notification' }}
                </h6>
                <p style="font-size:0.72rem;color:#555;margin:0 0 0.2rem;
                          display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $notif->message ?? '' }}
                </p>
                <span class="notification-time" style="font-size:0.65rem;color:#aaa;">
                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </span>
            </div>
            @if(!$notif->is_read)
            <button onclick="panelMarkRead({{ $notif->id }}, this)"
                    title="Mark as read"
                    style="width:26px;height:26px;border-radius:6px;
                           border:1.5px solid #dde3ea;background:#fff;
                           color:#888;cursor:pointer;font-size:0.65rem;
                           flex-shrink:0;display:flex;align-items:center;
                           justify-content:center;transition:all .15s;"
                    onmouseover="this.style.borderColor='#42a649';this.style.color='#42a649';"
                    onmouseout="this.style.borderColor='#dde3ea';this.style.color='#888';">
                <i class="fas fa-check"></i>
            </button>
            @endif
        </div>
        @empty
        <div class="text-center py-5" style="color:#c0c8d4;">
            <i class="fas fa-bell-slash fa-2x mb-2" style="display:block;"></i>
            <p style="font-size:0.8rem;margin:0;">No notifications yet</p>
        </div>
        @endforelse
    </div>
</div>

<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Update topbar badge ─────────────────────────────────
    function updateBadge(count) {
        const badge = document.getElementById('notificationCount');
        if (!badge) return;
        if (count > 0) {
            badge.textContent   = count;
            badge.style.display = 'flex';
        } else {
            badge.textContent   = '';
            badge.style.display = 'none';
        }
    }

    // ── Mark single read ────────────────────────────────────
    window.panelMarkRead = function (id, btn) {
        fetch(`{{ url('admin/notifications') }}/${id}/mark-read`, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            const item = document.getElementById('pni-' + id);
            if (item) item.classList.remove('unread');
            if (btn)  btn.remove();

            // Refresh badge count
            fetch('{{ route("admin.notifications.count") }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(d2 => updateBadge(d2.unread_count ?? 0))
            .catch(() => {});
        })
        .catch(() => {});
    };

    // ── Mark all read ───────────────────────────────────────
    window.panelMarkAllRead = function () {
        fetch('{{ route("admin.notifications.markAllRead") }}', {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            document.querySelectorAll('#notificationPanel .notification-item.unread')
                .forEach(el => el.classList.remove('unread'));
            document.querySelectorAll('#notificationPanel button[onclick^="panelMarkRead"]')
                .forEach(b => b.remove());
            updateBadge(0);
            // Dispatch event so topbar script can also react
            window.dispatchEvent(new Event('adminNotifUpdated'));
        })
        .catch(() => {});
    };
})();
</script>
