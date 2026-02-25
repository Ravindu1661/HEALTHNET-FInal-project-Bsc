@extends('hospital.layouts.app')
@section('title','Notifications')
@section('page-title','Notifications')
@section('page-subtitle','Stay updated with hospital activities')

@section('content')
<div x-data="hospitalNotifications()" x-init="init()">

    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-2">
            @foreach([['all','All'],['unread','Unread'],['appointment','Appointments'],['general','General']] as [$k,$l])
            <button @click="setFilter('{{ $k }}')"
                    :class="filter==='{{ $k }}' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition border border-gray-200">
                {{ $l }}
            </button>
            @endforeach
        </div>
        <button @click="markAllRead()"
                class="flex items-center gap-2 px-4 py-2 text-sm text-teal-600 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
            <i class="fas fa-check-double"></i> Mark All Read
        </button>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        <template x-for="notif in notifications" :key="notif.id">
            <div @click="markRead(notif)"
                 :class="notif.is_read ? 'bg-white border-gray-100' : 'bg-teal-50 border-teal-200'"
                 class="rounded-xl shadow-sm border px-6 py-4 flex items-start gap-4 cursor-pointer hover:shadow-md transition">

                <!-- Icon -->
                <div :class="{
                        'bg-blue-100':   notif.type === 'appointment',
                        'bg-green-100':  notif.type === 'payment',
                        'bg-yellow-100': notif.type === 'reminder',
                        'bg-purple-100': notif.type === 'general',
                        'bg-red-100':    notif.type === 'cancellation',
                        'bg-teal-100':   !['appointment','payment','reminder','general','cancellation'].includes(notif.type)
                     }"
                     class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0">
                    <i :class="{
                            'fa-calendar-check text-blue-600':   notif.type === 'appointment',
                            'fa-money-bill text-green-600':      notif.type === 'payment',
                            'fa-bell text-yellow-600':           notif.type === 'reminder',
                            'fa-info-circle text-purple-600':    notif.type === 'general',
                            'fa-times-circle text-red-600':      notif.type === 'cancellation',
                            'fa-bell text-teal-600':             !['appointment','payment','reminder','general','cancellation'].includes(notif.type)
                         }"
                       class="fas text-sm"></i>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <p :class="notif.is_read ? 'font-medium' : 'font-semibold'"
                           class="text-sm text-gray-800"
                           x-text="notif.title"></p>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span x-show="!notif.is_read"
                                  class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-400"
                                  x-text="notif.created_at"></span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5 leading-relaxed"
                       x-text="notif.message"></p>
                    <span :class="{
                              'bg-blue-100 text-blue-600':   notif.type === 'appointment',
                              'bg-green-100 text-green-600': notif.type === 'payment',
                              'bg-red-100 text-red-500':     notif.type === 'cancellation',
                              'bg-yellow-100 text-yellow-600': notif.type === 'reminder',
                              'bg-gray-100 text-gray-500':   !['appointment','payment','cancellation','reminder'].includes(notif.type)
                          }"
                          class="inline-block text-xs px-2 py-0.5 rounded-full mt-2 capitalize"
                          x-text="notif.type"></span>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="!loading && notifications.length === 0"
             class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
            <i class="fas fa-bell-slash text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-medium">No notifications</p>
            <p class="text-sm text-gray-300 mt-1">You're all caught up!</p>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="py-16 text-center text-gray-400">
            <i class="fas fa-spinner fa-spin text-3xl"></i>
        </div>
    </div>

    <!-- Pagination -->
    <div x-show="pg.last_page > 1"
         class="flex items-center justify-between mt-6 bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        <p class="text-sm text-gray-500">
            Showing <span x-text="pg.from"></span>–<span x-text="pg.to"></span>
            of <span x-text="pg.total"></span>
        </p>
        <div class="flex gap-2">
            <button @click="changePage(pg.current_page - 1)"
                    :disabled="pg.current_page === 1"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="px-3 py-1.5 text-sm text-gray-600"
                  x-text="pg.current_page + ' / ' + pg.last_page"></span>
            <button @click="changePage(pg.current_page + 1)"
                    :disabled="pg.current_page === pg.last_page"
                    class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalNotifications() {
    return {
        notifications: [],
        loading: true,
        filter: 'all',
        pg: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },

        async init() {
            await this.load();
        },

        // filter button click — reset to page 1
        setFilter(f) {
            this.filter = f;
            this.load(1);
        },

        async load(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ filter: this.filter, page });
                const res = await fetch(`{{ route('hospital.notifications.data') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                const list = data.notifications || [];
                this.notifications = Array.isArray(list) ? list : Object.values(list);
                this.pg = data.pagination ?? {
                    current_page: 1, last_page: 1,
                    from: list.length ? 1 : 0,
                    to: list.length,
                    total: list.length
                };
            } catch (e) {
                console.error('Notifications load error:', e);
            }
            this.loading = false;
        },

        async markRead(notif) {
            if (notif.is_read) return;
            notif.is_read = true; // optimistic UI
            try {
                await fetch(`{{ url('hospital/notifications') }}/${notif.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                // decrement navbar badge if exists
                const badge = document.getElementById('notif-count');
                if (badge) {
                    const count = parseInt(badge.textContent) - 1;
                    count <= 0 ? badge.classList.add('hidden') : badge.textContent = count;
                }
            } catch (e) {
                console.error('Mark read error:', e);
            }
        },

        async markAllRead() {
            try {
                await fetch('{{ route('hospital.notifications.mark-all-read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                this.notifications.forEach(n => n.is_read = true);
                // clear navbar badge
                const badge = document.getElementById('notif-count');
                if (badge) badge.classList.add('hidden');
            } catch (e) {
                console.error('Mark all read error:', e);
            }
        },

        changePage(p) {
            if (p < 1 || p > this.pg.last_page) return;
            this.load(p);
        }
    }
}
</script>
@endpush
