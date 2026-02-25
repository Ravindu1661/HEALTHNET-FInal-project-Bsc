@extends('hospital.layouts.app')
@section('title','Appointments')
@section('page-title','Appointment Management')
@section('page-subtitle','Manage all hospital appointments')

@section('content')
<div x-data="hospitalApts()" x-init="init()">

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex overflow-x-auto border-b border-gray-100">
            @foreach([
                ['all','All','list'],
                ['pending','Pending','hourglass-half'],
                ['confirmed','Confirmed','calendar-check'],
                ['completed','Completed','check-circle'],
                ['cancelled','Cancelled','times-circle']
            ] as [$key,$lbl,$ico])
            <button @click="tab='{{ $key }}'; load()"
                    :class="tab==='{{ $key }}' ? 'border-b-2 border-teal-600 text-teal-600' : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-2 px-5 py-4 text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-{{ $ico }}"></i> {{ $lbl }}
            </button>
            @endforeach
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row gap-3 p-4">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" @input.debounce.500ms="load()"
                       placeholder="Search patient name or appointment #..."
                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <input type="date" x-model="date" @change="load()"
                   class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <button @click="search=''; date=''; load()"
                    class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition">
                <i class="fas fa-times mr-1"></i> Clear
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Patient</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Doctor</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Date & Time</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Type</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Mode</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Fee</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="apt in apts" :key="apt.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center font-semibold text-teal-700 text-sm flex-shrink-0"
                                         x-text="apt.patient_name?.charAt(0) || 'P'"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800" x-text="apt.patient_name"></p>
                                        <p class="text-xs text-gray-400" x-text="'#' + apt.appointment_number"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"
                                x-text="apt.doctor_name?.trim() || '—'"></td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800" x-text="fmtDate(apt.appointment_date)"></p>
                                <p class="text-xs text-gray-400" x-text="fmtTime(apt.appointment_time)"></p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize"
                                x-text="apt.appointment_type?.replace('_',' ') || '—'"></td>
                            <td class="px-6 py-4">
                                <span :class="apt.consultation_method === 'telemedicine'
                                    ? 'bg-indigo-100 text-indigo-700'
                                    : 'bg-teal-100 text-teal-700'"
                                      class="text-xs px-2 py-1 rounded-full font-medium"
                                      x-text="apt.consultation_method === 'telemedicine' ? 'Online' : 'In-Person'">
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'bg-yellow-100 text-yellow-700': apt.status==='pending',
                                    'bg-blue-100 text-blue-700':    apt.status==='confirmed',
                                    'bg-green-100 text-green-700':  apt.status==='completed',
                                    'bg-red-100 text-red-700':      apt.status==='cancelled',
                                    'bg-gray-100 text-gray-700':    apt.status==='no_show'
                                }" class="text-xs px-2.5 py-1 rounded-full font-medium capitalize"
                                x-text="apt.status?.replace('_',' ')"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                Rs.<span x-text="Number(apt.consultation_fee || 0).toLocaleString()"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View -->
                                    <button @click="viewApt(apt)" title="View Details"
                                            class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition text-sm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Confirm -->
                                    <button x-show="apt.status==='pending'"
                                            @click="confirmApt(apt.id)" title="Confirm"
                                            class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-100 transition text-sm">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <!-- Complete -->
                                    <button x-show="apt.status==='confirmed'"
                                            @click="completeApt(apt.id)" title="Mark Completed"
                                            class="w-8 h-8 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center hover:bg-teal-100 transition text-sm">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                    <!-- Cancel -->
                                    <button x-show="['pending','confirmed'].includes(apt.status)"
                                            @click="openCancel(apt)" title="Cancel"
                                            class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition text-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <tr x-show="apts.length===0 && !loading">
                        <td colspan="8" class="px-6 py-16 text-center">
                            <i class="fas fa-calendar-times text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-400 text-sm mt-2">No appointments found</p>
                        </td>
                    </tr>

                    <!-- Loading State -->
                    <tr x-show="loading">
                        <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-spinner fa-spin text-2xl"></i>
                            <p class="text-sm mt-2">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100"
             x-show="pg.last_page > 1">
            <p class="text-sm text-gray-500">
                Showing <span x-text="pg.from"></span>–<span x-text="pg.to"></span>
                of <span x-text="pg.total"></span> results
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

    <!-- ═══ VIEW DETAILS MODAL ═══ -->
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div @click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b">
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-calendar-check text-teal-500"></i> Appointment Details
                </h3>
                <button @click="showModal=false" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4" x-show="selApt">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Appointment #</p>
                        <p class="text-sm font-semibold text-gray-800" x-text="selApt?.appointment_number"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Status</p>
                        <span class="text-xs px-2 py-1 rounded-full font-medium capitalize"
                              :class="{
                                  'bg-yellow-100 text-yellow-700': selApt?.status==='pending',
                                  'bg-blue-100 text-blue-700': selApt?.status==='confirmed',
                                  'bg-green-100 text-green-700': selApt?.status==='completed',
                                  'bg-red-100 text-red-700': selApt?.status==='cancelled'
                              }"
                              x-text="selApt?.status?.replace('_',' ')"></span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Patient</p>
                        <p class="text-sm font-medium text-gray-800" x-text="selApt?.patient_name"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Doctor</p>
                        <p class="text-sm font-medium text-gray-800" x-text="selApt?.doctor_name?.trim() || '—'"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Date</p>
                        <p class="text-sm font-medium text-gray-800" x-text="fmtDate(selApt?.appointment_date)"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Time</p>
                        <p class="text-sm font-medium text-gray-800" x-text="fmtTime(selApt?.appointment_time)"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Mode</p>
                        <p class="text-sm font-medium text-gray-800 capitalize"
                           x-text="selApt?.consultation_method?.replace('_',' ')"></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-0.5">Consultation Fee</p>
                        <p class="text-sm font-medium text-gray-800">
                            Rs. <span x-text="Number(selApt?.consultation_fee || 0).toLocaleString()"></span>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 col-span-2" x-show="selApt?.chief_complaint">
                        <p class="text-xs text-gray-500 mb-0.5">Patient Notes</p>
                        <p class="text-sm text-gray-700" x-text="selApt?.chief_complaint || '—'"></p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button x-show="selApt?.status==='pending'"
                            @click="confirmApt(selApt.id); showModal=false"
                            class="flex-1 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
                    <button x-show="selApt?.status==='confirmed'"
                            @click="completeApt(selApt.id); showModal=false"
                            class="flex-1 py-2.5 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                        <i class="fas fa-clipboard-check mr-1"></i> Mark Completed
                    </button>
                    <button x-show="['pending','confirmed'].includes(selApt?.status)"
                            @click="showModal=false; openCancel(selApt)"
                            class="flex-1 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ CANCEL MODAL ═══ -->
    <div x-show="showCancel" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div @click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i> Cancel Appointment
                </h3>
                <button @click="showCancel=false">
                    <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-3">
                Appointment <span class="font-semibold text-gray-700" x-text="'#' + selApt?.appointment_number"></span>
                — <span x-text="selApt?.patient_name"></span>
            </p>
            <textarea x-model="cancelReason" rows="3"
                      placeholder="Reason for cancellation (required)..."
                      class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button @click="showCancel=false"
                        class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Go Back
                </button>
                <button @click="cancelApt()"
                        class="flex-1 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">
                    <i class="fas fa-times mr-1"></i> Confirm Cancel
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function hospitalApts() {
    return {
        tab: 'all',
        search: '',
        date: '',
        apts: [],
        loading: true,
        pg: { current_page: 1, last_page: 1, from: 0, to: 0, total: 0 },
        showModal: false,
        showCancel: false,
        selApt: null,
        cancelReason: '',

        async init() {
            await this.load();
        },

        async load(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    status: this.tab,
                    search: this.search,
                    date: this.date,
                    page
                });
                const res  = await fetch(`{{ route('hospital.appointments.data') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.apts = data.appointments.data;
                    const m   = data.appointments;
                    this.pg   = {
                        current_page: m.current_page,
                        last_page: m.last_page,
                        from: m.from || 0,
                        to: m.to || 0,
                        total: m.total
                    };
                }
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        viewApt(a) {
            this.selApt = a;
            this.showModal = true;
        },

        openCancel(a) {
            this.selApt      = a;
            this.cancelReason = '';
            this.showCancel  = true;
        },

        async confirmApt(id) {
            await this.post(`{{ url('hospital/appointments') }}/${id}/confirm`);
        },

        async completeApt(id) {
            await this.post(`{{ url('hospital/appointments') }}/${id}/complete`);
        },

        async cancelApt() {
            if (!this.cancelReason.trim()) {
                alert('Please provide a cancellation reason.');
                return;
            }
            await this.post(
                `{{ url('hospital/appointments') }}/${this.selApt.id}/cancel`,
                { reason: this.cancelReason }
            );
            this.showCancel = false;
        },

        async post(url, body = {}) {
            try {
                const res  = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });
                const data = await res.json();
                this.toast(data.message || 'Done!', data.success ? 'success' : 'error');
                if (data.success) this.load(this.pg.current_page);
            } catch(e) { console.error(e); }
        },

        changePage(p) {
            if (p < 1 || p > this.pg.last_page) return;
            this.load(p);
        },

        fmtDate(d) {
            if (!d) return '';
            return new Date(d).toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
        },

        fmtTime(t) {
            if (!t) return '';
            try {
                return new Date('1970-01-01T' + t).toLocaleTimeString('en-US', {
                    hour: '2-digit', minute: '2-digit'
                });
            } catch { return t; }
        },

        toast(msg, type) {
            const d = document.createElement('div');
            d.className = `fixed top-4 right-4 z-[100] px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium
                           ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            d.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>${msg}`;
            document.body.appendChild(d);
            setTimeout(() => d.remove(), 3000);
        }
    }
}
</script>
@endpush
