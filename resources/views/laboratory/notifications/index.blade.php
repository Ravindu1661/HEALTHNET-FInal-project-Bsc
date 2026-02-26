@extends('laboratory.layouts.app')
@section('title','Notifications')
@section('page-title','Notifications')
@section('page-subtitle','Stay updated with latest activities')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex gap-2">
        <a href="{{ route('laboratory.notifications.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('type') ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }} transition">
           All <span class="ml-1 bg-white/20 text-xs px-1.5 py-0.5 rounded-full">{{ $notifications->total() }}</span>
        </a>
        <a href="{{ route('laboratory.notifications.index', ['type'=>'unread']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('type')=='unread' ? 'bg-teal-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }} transition">
           Unread <span class="ml-1 {{ request('type')=='unread' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600' }} text-xs px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>
        </a>
    </div>
    @if($unreadCount > 0)
    <form method="POST" action="{{ route('laboratory.notifications.mark-all-read') }}">
        @csrf
        <button class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
            <i class="fas fa-check-double mr-1.5"></i> Mark All Read
        </button>
    </form>
    @endif
</div>

<div class="space-y-2">
    @forelse($notifications as $notif)
    <div class="bg-white rounded-xl shadow-sm border {{ $notif->is_read ? 'border-gray-100' : 'border-teal-200 bg-teal-50/30' }} px-5 py-4 flex items-start justify-between gap-4 hover:shadow-md transition"
         id="notif-{{ $notif->id }}">
        <div class="flex items-start gap-4">
            <div class="{{ $notif->is_read ? 'bg-gray-100' : 'bg-teal-100' }} p-2.5 rounded-xl flex-shrink-0">
                @php
                    $icons = ['lab_order'=>'fa-vial text-teal-600','payment'=>'fa-credit-card text-green-600','general'=>'fa-bell text-blue-600'];
                    $icon = $icons[$notif->type] ?? 'fa-bell text-gray-500';
                @endphp
                <i class="fas {{ $icon }}"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm {{ !$notif->is_read ? 'text-teal-800' : '' }}">{{ $notif->title }}</p>
                <p class="text-gray-500 text-sm mt-0.5">{{ $notif->message }}</p>
                <p class="text-xs text-gray-400 mt-1.5">
                    <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            @if(!$notif->is_read)
            <button onclick="markRead({{ $notif->id }})"
                class="text-xs text-teal-600 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition">
                Mark Read
            </button>
            @endif
            <form method="POST" action="{{ route('laboratory.notifications.destroy', $notif->id) }}">
                @csrf @method('DELETE')
                <button class="text-gray-400 hover:text-red-500 p-1.5 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 py-20 text-center">
        <i class="fas fa-bell text-gray-200 text-6xl mb-4 block"></i>
        <p class="text-gray-400 font-medium">All caught up!</p>
        <p class="text-gray-300 text-sm mt-1">No notifications at this time</p>
    </div>
    @endforelse
</div>

@if($notifications->hasPages())
<div class="mt-5">{{ $notifications->withQueryString()->links() }}</div>
@endif
@endsection

@push('scripts')
<script>
function markRead(id) {
    fetch(`/laboratory/notifications/${id}/read`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
    }).then(() => {
        const el = document.getElementById('notif-'+id);
        el.classList.remove('border-teal-200','bg-teal-50/30');
        el.classList.add('border-gray-100');
        el.querySelector('button[onclick]')?.remove();
    });
}
</script>
@endpush
