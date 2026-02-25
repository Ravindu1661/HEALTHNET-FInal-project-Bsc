@extends('hospital.layouts.app')
@section('title','Dashboard')
@section('page-title','Hospital Dashboard')
@section('page-subtitle','Overview of hospital activities')

@section('content')
<div x-data="hospitalDashboard()" x-init="init()">

    <!-- STATS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Today Appointments -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-teal-600 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-1 rounded-full">Today</span>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="stats.today_apt ?? '—'">—</p>
            <p class="text-sm text-gray-500 mt-1">Today's Appointments</p>
        </div>

        <!-- Total Doctors -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-md text-blue-600 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Staff</span>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="stats.total_doctors ?? '—'">—</p>
            <p class="text-sm text-gray-500 mt-1">Active Doctors</p>
        </div>

        <!-- Total Patients -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-procedures text-purple-600 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">All Time</span>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="stats.total_patients ?? '—'">—</p>
            <p class="text-sm text-gray-500 mt-1">Total Patients</p>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-star text-orange-500 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-full">Rating</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">
                <span x-text="stats.avg_rating ?? '—'">—</span>
                <span class="text-base text-gray-400">/5</span>
            </p>
            <p class="text-sm text-gray-500 mt-1">Patient Rating</p>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">Action Needed</span>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="stats.apt_stats?.pending ?? '—'">—</p>
            <p class="text-sm text-gray-500 mt-1">Pending Confirmations</p>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">Done</span>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="stats.apt_stats?.completed ?? '—'">—</p>
            <p class="text-sm text-gray-500 mt-1">Completed</p>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-5 shadow-sm hover:shadow-md transition col-span-2">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
                <span class="text-xs font-medium text-teal-100 bg-white/20 px-2 py-1 rounded-full">This Month</span>
            </div>
            <p class="text-2xl font-bold text-white">
                Rs. <span x-text="stats.monthly_revenue ? Number(stats.monthly_revenue).toLocaleString() : '0'">0</span>
            </p>
            <p class="text-sm text-teal-100 mt-1">Monthly Revenue</p>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- TODAY'S APPOINTMENTS -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-teal-500"></i> Today's Appointments
                </h2>
                <a href="{{ route('hospital.appointments') }}" class="text-sm text-teal-600 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                <template x-for="apt in todayApts" :key="apt.id">
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0 font-semibold text-teal-700 text-sm"
                             x-text="apt.patient_name?.charAt(0)?.toUpperCase() || 'P'"></div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate" x-text="apt.patient_name"></p>
                            <p class="text-xs text-gray-400 truncate"
                               x-text="(apt.doctor_name?.trim() || 'No Doctor') + ' • ' + formatTime(apt.appointment_time)"></p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span :class="{
                                'bg-yellow-100 text-yellow-700': apt.status==='pending',
                                'bg-blue-100 text-blue-700':     apt.status==='confirmed',
                                'bg-green-100 text-green-700':   apt.status==='completed',
                                'bg-red-100 text-red-700':       apt.status==='cancelled'
                            }" class="text-xs px-2.5 py-1 rounded-full font-medium capitalize"
                            x-text="apt.status"></span>
                            <button x-show="apt.status==='pending'"
                                    @click="quickConfirm(apt.id)"
                                    class="w-7 h-7 bg-green-100 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-200 transition text-xs"
                                    title="Quick Confirm">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="todayApts.length===0 && !loadingToday"
                     class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-calendar-times text-3xl mb-2 block"></i>
                    <p class="text-sm">No appointments today</p>
                </div>
                <div x-show="loadingToday"
                     class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">

            <!-- APPOINTMENT STATUS DONUT -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-teal-500"></i> Appointment Status
                </h3>
                <div class="h-40">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- RECENT REVIEWS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fas fa-star text-orange-400"></i> Recent Reviews
                    </h3>
                    <a href="{{ route('hospital.reviews') }}" class="text-xs text-teal-600 hover:underline">All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    <template x-for="r in recentReviews" :key="r.id">
                        <div class="px-5 py-3">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-medium text-gray-800 truncate" x-text="r.patient_name"></p>
                                <div class="flex flex-shrink-0">
                                    <template x-for="i in 5" :key="i">
                                        <i :class="i<=r.rating ? 'text-yellow-400' : 'text-gray-200'"
                                           class="fas fa-star text-xs"></i>
                                    </template>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2"
                               x-text="r.review_text || r.review_title || '—'"></p>
                        </div>
                    </template>
                    <div x-show="recentReviews.length===0 && !loadingReviews"
                         class="px-5 py-6 text-center text-gray-400 text-sm">
                        No reviews yet
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MONTHLY TREND CHART -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-teal-500"></i> Monthly Appointment Trend
        </h2>
        <div class="h-52">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalDashboard() {
    return {
        stats:          {},
        todayApts:      [],
        recentReviews:  [],
        loadingToday:   true,
        loadingReviews: true,
        _statusChart:   null,
        _trendChart:    null,

        async init() {
            await Promise.all([
                this.loadStats(),
                this.loadTodayApts(),
                this.loadReviews(),
            ]);
        },

        async loadStats() {
            try {
                const res  = await fetch('{{ route("hospital.stats") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.stats = data;
                    this.$nextTick(() => {
                        this.renderStatusChart();
                        this.renderTrendChart();
                    });
                }
            } catch(e) { console.error('Stats error:', e); }
        },

        async loadTodayApts() {
            try {
                const res  = await fetch('{{ route("hospital.today-appointments") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) this.todayApts = data.appointments;
            } catch(e) { console.error('Today apts error:', e); }
            this.loadingToday = false;
        },

        async loadReviews() {
            try {
                const res  = await fetch('{{ route("hospital.recent-reviews") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) this.recentReviews = data.reviews;
            } catch(e) { console.error('Reviews error:', e); }
            this.loadingReviews = false;
        },

        async quickConfirm(id) {
            try {
                await fetch(`{{ url('hospital/appointments') }}/${id}/confirm`, {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json'
                    }
                });
                await this.loadTodayApts();
            } catch(e) { console.error('Confirm error:', e); }
        },

        renderStatusChart() {
            const ctx = document.getElementById('statusChart');
            if (!ctx || !this.stats.apt_stats) return;
            // Destroy old instance if exists
            if (this._statusChart) this._statusChart.destroy();
            const s = this.stats.apt_stats;
            this._statusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                    datasets: [{
                        data:            [s.pending, s.confirmed, s.completed, s.cancelled],
                        backgroundColor: ['#FEF3C7','#DBEAFE','#D1FAE5','#FEE2E2'],
                        borderColor:     ['#F59E0B','#3B82F6','#10B981','#EF4444'],
                        borderWidth:     2
                    }]
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels:   { font: { size: 11 }, padding: 8 }
                        }
                    }
                }
            });
        },

        renderTrendChart() {
            const ctx = document.getElementById('trendChart');
            if (!ctx || !this.stats.trend) return;
            if (this._trendChart) this._trendChart.destroy();
            this._trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels:   this.stats.trend.map(t => t.month),
                    datasets: [{
                        label:           'Appointments',
                        data:            this.stats.trend.map(t => t.count),
                        borderColor:     '#0D9488',
                        backgroundColor: 'rgba(13,148,136,0.1)',
                        borderWidth:     2.5,
                        fill:            true,
                        tension:         0.4,
                        pointBackgroundColor: '#0D9488',
                        pointRadius:     5
                    }]
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F3F4F6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        },

        formatTime(t) {
            if (!t) return '';
            try {
                return new Date('1970-01-01T' + t).toLocaleTimeString('en-US', {
                    hour: '2-digit', minute: '2-digit'
                });
            } catch { return t; }
        }
    }
}
</script>
@endpush
