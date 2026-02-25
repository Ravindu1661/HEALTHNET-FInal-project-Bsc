@extends('hospital.layouts.app')
@section('title','Reports & Analytics')
@section('page-title','Reports & Analytics')
@section('page-subtitle','Hospital performance insights')

@section('content')
<div x-data="hospitalReports()" x-init="init()">

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mb-3">
                <i class="fas fa-calendar-check text-teal-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="totals.appointments || 0"></p>
            <p class="text-sm text-gray-500 mt-1">Total Appointments</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="totals.completed || 0"></p>
            <p class="text-sm text-gray-500 mt-1">Completed</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mb-3">
                <i class="fas fa-coins text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">
                Rs.<span x-text="Number(totals.revenue || 0).toLocaleString()"></span>
            </p>
            <p class="text-sm text-gray-500 mt-1">Total Revenue</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mb-3">
                <i class="fas fa-percentage text-purple-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="completionRate + '%'"></p>
            <p class="text-sm text-gray-500 mt-1">Completion Rate</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Monthly Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-teal-500"></i> Monthly Appointment Trend
            </h3>
            <div class="h-56">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Appointment Type Pie -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-teal-500"></i> By Type
            </h3>
            <div class="h-56">
                <canvas id="typeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Consultation Method + Monthly Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Method Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-stethoscope text-teal-500"></i> By Mode
            </h3>
            <div class="h-48">
                <canvas id="modeChart"></canvas>
            </div>
        </div>

        <!-- Monthly Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-table text-teal-500"></i> Monthly Summary
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Month</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Total</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Completed</th>
                            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template x-for="row in monthly" :key="row.month">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-sm font-medium text-gray-800" x-text="row.month"></td>
                                <td class="px-6 py-3 text-sm text-gray-600" x-text="row.total"></td>
                                <td class="px-6 py-3">
                                    <span class="text-sm text-green-600 font-medium" x-text="row.completed"></span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-800">
                                    Rs.<span x-text="Number(row.revenue || 0).toLocaleString()"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="monthly.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalReports() {
    return {
        monthly: [],
        totals: {},
        completionRate: 0,
        loading: true,
        charts: {},

        async init() { await this.load(); },

        async load() {
            try {
                const res  = await fetch('{{ route("hospital.reports.data") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.monthly = data.monthly || [];
                    const totalApt  = this.monthly.reduce((s, m) => s + (m.total || 0), 0);
                    const totalComp = this.monthly.reduce((s, m) => s + (m.completed || 0), 0);
                    const totalRev  = this.monthly.reduce((s, m) => s + Number(m.revenue || 0), 0);
                    this.totals     = { appointments: totalApt, completed: totalComp, revenue: totalRev };
                    this.completionRate = totalApt ? Math.round((totalComp / totalApt) * 100) : 0;

                    this.$nextTick(() => {
                        this.renderTrend(data.monthly || []);
                        this.renderType(data.by_type || []);
                        this.renderMode(data.by_method || []);
                    });
                }
            } catch(e) { console.error(e); }
        },

        renderTrend(monthly) {
            const ctx = document.getElementById('trendChart');
            if (!ctx) return;
            if (this.charts.trend) this.charts.trend.destroy();
            this.charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthly.map(m => m.month),
                    datasets: [
                        {
                            label: 'Total',
                            data: monthly.map(m => m.total),
                            borderColor: '#0D9488',
                            backgroundColor: 'rgba(13,148,136,0.08)',
                            borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4
                        },
                        {
                            label: 'Completed',
                            data: monthly.map(m => m.completed),
                            borderColor: '#10B981',
                            backgroundColor: 'transparent',
                            borderWidth: 2, borderDash: [5,5], tension: 0.4, pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
                    scales: { y: { beginAtZero: true, grid: { color: '#F3F4F6' } }, x: { grid: { display: false } } }
                }
            });
        },

        renderType(byType) {
            const ctx = document.getElementById('typeChart');
            if (!ctx) return;
            if (this.charts.type) this.charts.type.destroy();
            this.charts.type = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: byType.map(t => t.appointment_type || 'Other'),
                    datasets: [{
                        data: byType.map(t => t.count),
                        backgroundColor: ['#DBEAFE','#D1FAE5','#FEF3C7','#EDE9FE','#FCE7F3'],
                        borderColor: ['#3B82F6','#10B981','#F59E0B','#8B5CF6','#EC4899'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } } }
                }
            });
        },

        renderMode(byMethod) {
            const ctx = document.getElementById('modeChart');
            if (!ctx) return;
            if (this.charts.mode) this.charts.mode.destroy();
            this.charts.mode = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: byMethod.map(m => m.consultation_method === 'telemedicine' ? 'Online' : 'In-Person'),
                    datasets: [{
                        label: 'Appointments',
                        data: byMethod.map(m => m.count),
                        backgroundColor: ['#CCFBF1','#DBEAFE'],
                        borderColor: ['#0D9488','#3B82F6'],
                        borderWidth: 2, borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: '#F3F4F6' } }, x: { grid: { display: false } } }
                }
            });
        }
    }
}
</script>
@endpush
