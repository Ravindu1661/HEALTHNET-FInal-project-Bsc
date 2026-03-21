@extends('doctor.layouts.master')

@section('title', 'Appointments')
@section('page-title', 'My Appointments')

@push('styles')
<style>
/* ── Filter Card ── */
.filter-card { background:#fff; border-radius:14px; padding:1.1rem 1.3rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }

/* ── Status Pills ── */
.sp { display:inline-flex; align-items:center; padding:.2rem .65rem;
    border-radius:20px; font-size:.7rem; font-weight:700; gap:.3rem; }
.sp.pending   { background:#fff3cd; color:#856404; }
.sp.confirmed { background:#d1ecf1; color:#0c5460; }
.sp.completed { background:#d4edda; color:#155724; }
.sp.cancelled { background:#f8d7da; color:#721c24; }
.sp.no-show   { background:#f0f0f0; color:#555; }

/* ── Stat Badges ── */
.stat-badges { display:flex; gap:.6rem; flex-wrap:wrap; margin-bottom:1.2rem; }
.sb-item { background:#fff; border-radius:10px; padding:.55rem 1rem;
    box-shadow:0 2px 8px rgba(0,0,0,.06); cursor:pointer; transition:all .15s;
    text-decoration:none; display:flex; align-items:center; gap:.45rem; }
.sb-item:hover { transform:translateY(-2px); box-shadow:0 4px 14px rgba(0,0,0,.1); }
.sb-item .sb-num { font-size:1.1rem; font-weight:800; }
.sb-item .sb-lbl { font-size:.7rem; color:#888; font-weight:600; }
.sb-item.all      .sb-num { color:#0d6efd; }
.sb-item.pending  .sb-num { color:#856404; }
.sb-item.confirmed .sb-num { color:#0c5460; }
.sb-item.completed .sb-num { color:#155724; }
.sb-item.cancelled .sb-num { color:#721c24; }

/* ── Table ── */
.apt-table { background:#fff; border-radius:14px; overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,.05); }
.apt-table table { margin:0; }
.apt-table thead th { background:#f8f9fa; font-size:.75rem; font-weight:700;
    color:#555; text-transform:uppercase; letter-spacing:.5px; border:none;
    padding:.85rem 1rem; }
.apt-table tbody td { vertical-align:middle; font-size:.82rem;
    padding:.8rem 1rem; border-bottom:1px solid #f5f7fb; }
.apt-table tbody tr:last-child td { border-bottom:none; }
.apt-table tbody tr:hover { background:#fafbfc; }

/* ── Avatar ── */
.pt-av { width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#0d6efd,#6f42c1);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:.78rem; font-weight:800; }

/* ── Action Btns ── */
.act-btn { width:30px; height:30px; border:none; border-radius:7px;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.72rem; cursor:pointer; transition:all .15s; }
.act-btn:hover { transform:scale(1.08); }
.act-view    { background:rgba(13,110,253,.1); color:#0d6efd; }
.act-confirm { background:rgba(25,135,84,.1); color:#198754; }
.act-cancel  { background:rgba(220,53,69,.1); color:#dc3545; }
.act-complete{ background:rgba(108,117,125,.1); color:#6c757d; }
.act-reschedule { background:rgba(253,126,20,.1); color:#fd7e14; }
.act-notes   { background:rgba(111,66,193,.1); color:#6f42c1; }

/* ── Empty ── */
.empty-state { text-align:center; padding:3.5rem 1rem; color:#c0c8d4; }
.empty-state i { font-size:2.5rem; display:block; margin-bottom:.6rem; }

/* ── Modals ── */
.modal-card { border:none; border-radius:16px; }
.modal-hd { background:#f8f9fa; border-bottom:1px solid #eaecf0;
    border-radius:16px 16px 0 0; padding:1rem 1.3rem;
    font-size:.9rem; font-weight:700; color:#1a1a1a; }
</style>
@endpush

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;font-size:.84rem">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stat Badges --}}
<div class="stat-badges">
    <a href="{{ route('doctor.appointments.index') }}" class="sb-item all">
        <i class="fas fa-calendar-alt" style="color:#0d6efd;font-size:.9rem"></i>
        <div><div class="sb-num">{{ array_sum($stats) }}</div><div class="sb-lbl">All</div></div>
    </a>
    <a href="{{ route('doctor.appointments.index', ['status'=>'pending']) }}" class="sb-item pending">
        <i class="fas fa-clock" style="color:#856404;font-size:.9rem"></i>
        <div><div class="sb-num">{{ $stats['pending'] }}</div><div class="sb-lbl">Pending</div></div>
    </a>
    <a href="{{ route('doctor.appointments.index', ['status'=>'confirmed']) }}" class="sb-item confirmed">
        <i class="fas fa-check-circle" style="color:#0c5460;font-size:.9rem"></i>
        <div><div class="sb-num">{{ $stats['confirmed'] }}</div><div class="sb-lbl">Confirmed</div></div>
    </a>
    <a href="{{ route('doctor.appointments.index', ['status'=>'completed']) }}" class="sb-item completed">
        <i class="fas fa-check-double" style="color:#155724;font-size:.9rem"></i>
        <div><div class="sb-num">{{ $stats['completed'] }}</div><div class="sb-lbl">Completed</div></div>
    </a>
    <a href="{{ route('doctor.appointments.index', ['status'=>'cancelled']) }}" class="sb-item cancelled">
        <i class="fas fa-times-circle" style="color:#721c24;font-size:.9rem"></i>
        <div><div class="sb-num">{{ $stats['cancelled'] }}</div><div class="sb-lbl">Cancelled</div></div>
    </a>
</div>

{{-- Filters --}}
<div class="filter-card">
    <form method="GET" action="{{ route('doctor.appointments.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;color:#555">Search</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                    placeholder="Appointment no, patient name..."
                    value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;color:#555">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','completed','cancelled','no-show'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-',' ',$s)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;color:#555">From</label>
            <input type="date" name="date_from" class="form-control form-control-sm"
                value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1" style="font-size:.75rem;font-weight:600;color:#555">To</label>
            <input type="date" name="date_to" class="form-control form-control-sm"
                value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="apt-table">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Date & Time</th>
                <th>Location</th>
                <th>Fee</th>
                <th>Payment</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($appointments as $apt)
            <tr>
                <td>
                    <span style="font-size:.72rem;color:#888">{{ $apt->appointment_number }}</span>
                </td>
                <td>
                   <div class="d-flex align-items-center gap-2">
                        @php
                            $patientImg = $apt->patient_profile_image ?? null;
                        @endphp

                        @if($patientImg)
                            {{-- Profile Image --}}
                            <img src="{{ asset('storage/' . $patientImg) }}"
                                alt="{{ $apt->patient_name }}"
                                style="width:36px;height:36px;border-radius:50%;
                                        object-fit:cover;flex-shrink:0;border:2px solid #e8edf5"
                                onerror="this.style.display='none';
                                        this.nextElementSibling.style.display='flex'">
                            {{-- Fallback --}}
                            <div class="pt-av" style="display:none">
                                {{ strtoupper(substr($apt->patient_name, 0, 1)) }}
                            </div>
                        @else
                            {{-- Initial Avatar --}}
                            <div class="pt-av">
                                {{ strtoupper(substr($apt->patient_name, 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <div style="font-weight:700;font-size:.83rem">{{ $apt->patient_name }}</div>
                            <div style="font-size:.7rem;color:#888">{{ $apt->patient_phone }}</div>
                        </div>
                    </div>

                </td>
                <td>
                    <div style="font-weight:700;font-size:.83rem">
                        {{ \Carbon\Carbon::parse($apt->appointment_date)->format('d M Y') }}
                    </div>
                    <div style="font-size:.7rem;color:#888">
                        {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}
                    </div>
                </td>
                <td>
                    <div style="font-size:.8rem">
                        <i class="fas fa-map-marker-alt text-muted me-1" style="font-size:.7rem"></i>
                        {{ $apt->location }}
                    </div>
                    <div style="font-size:.68rem;color:#aaa">{{ ucfirst($apt->workplace_type) }}</div>
                </td>
               {{-- Fee column --}}
<td>
    @if($apt->payment_status === 'partial')
        <div style="font-weight:700;color:#198754;font-size:.83rem">
            LKR {{ number_format($apt->consultation_fee ?? 0, 2) }}
        </div>
        <div style="font-size:.7rem;color:#27ae60;">
            <i class="fas fa-check-circle"></i> Adv: LKR {{ number_format($apt->advance_payment ?? 0, 2) }}
        </div>
        <div style="font-size:.7rem;color:#dc3545;">
            <i class="fas fa-hourglass-half"></i> Bal: LKR {{ number_format(($apt->consultation_fee ?? 0) - ($apt->advance_payment ?? 0), 2) }}
        </div>
    @else
        <span style="font-weight:700;color:#198754;font-size:.83rem">
            LKR {{ number_format($apt->consultation_fee ?? 0, 2) }}
        </span>
    @endif
</td>

{{-- Payment column --}}
<td>
    @php
        $pColors  = ['unpaid' => 'warning', 'partial' => 'warning', 'paid' => 'success'];
        $pLabels  = ['unpaid' => 'Unpaid',  'partial' => 'Advance Paid', 'paid' => 'Paid'];
        $pc = $pColors[$apt->payment_status] ?? 'secondary';
        $pl = $pLabels[$apt->payment_status] ?? ucfirst($apt->payment_status ?? 'unpaid');
    @endphp
    <span class="badge bg-{{ $pc }} text-dark" style="font-size:.65rem">
        {{ $pl }}
    </span>
</td>
                <td>
                    <span class="sp {{ $apt->status }}">
                        @if($apt->status === 'pending')   <i class="fas fa-clock"></i>
                        @elseif($apt->status === 'confirmed') <i class="fas fa-check-circle"></i>
                        @elseif($apt->status === 'completed') <i class="fas fa-check-double"></i>
                        @elseif($apt->status === 'cancelled') <i class="fas fa-times-circle"></i>
                        @else <i class="fas fa-user-times"></i>
                        @endif
                        {{ ucfirst(str_replace('_',' ',$apt->status)) }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        {{-- View --}}
                        <a href="{{ route('doctor.appointments.show', $apt->id) }}"
                           class="act-btn act-view" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($apt->status === 'pending')
                        {{-- Confirm --}}
                        <button class="act-btn act-confirm" title="Confirm"
                            onclick="confirmApt({{ $apt->id }}, '{{ $apt->patient_name }}')">
                            <i class="fas fa-check"></i>
                        </button>
                        {{-- Cancel --}}
                        <button class="act-btn act-cancel" title="Cancel"
                            onclick="openCancelModal({{ $apt->id }}, '{{ $apt->patient_name }}')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif

                        @if($apt->status === 'confirmed')
                        {{-- Complete --}}
                        <button class="act-btn act-complete" title="Mark Complete"
                            onclick="completeApt({{ $apt->id }}, '{{ $apt->patient_name }}')">
                            <i class="fas fa-check-double"></i>
                        </button>
                        {{-- Reschedule --}}
                        <button class="act-btn act-reschedule" title="Reschedule"
                            onclick="openRescheduleModal({{ $apt->id }}, '{{ $apt->patient_name }}')">
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                        {{-- Cancel --}}
                        <button class="act-btn act-cancel" title="Cancel"
                            onclick="openCancelModal({{ $apt->id }}, '{{ $apt->patient_name }}')">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif

                        {{-- Notes --}}
                        @if(in_array($apt->status, ['pending','confirmed','completed']))
                        <button class="act-btn act-notes" title="Add Notes"
                            onclick="openNotesModal({{ $apt->id }}, '{{ addslashes($apt->notes ?? '') }}')">
                            <i class="fas fa-sticky-note"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p style="font-size:.85rem;font-weight:600">No appointments found</p>
                        <p style="font-size:.75rem">Try adjusting your filters</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div class="d-flex justify-content-between align-items-center px-3 py-2"
         style="border-top:1px solid #f0f2f5;background:#fafbfc">
        <div style="font-size:.75rem;color:#888">
            Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }}
            of {{ $appointments->total() }} entries
        </div>
        {{ $appointments->appends(request()->query())->links() }}
    </div>
    @endif
</div>

{{-- ══ CANCEL MODAL ══ --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-card">
            <div class="modal-hd">
                <i class="fas fa-times-circle text-danger me-2"></i>Cancel Appointment
            </div>
            <div class="modal-body p-3">
                <p style="font-size:.84rem;color:#555" id="cancelPatientName"></p>
                <label class="form-label" style="font-size:.8rem;font-weight:600">
                    Cancellation Reason <span class="text-danger">*</span>
                </label>
                <textarea id="cancelReason" class="form-control form-control-sm" rows="3"
                    placeholder="Enter reason for cancellation..."></textarea>
                <div id="cancelReasonError" class="text-danger" style="font-size:.73rem;display:none">
                    Please provide a reason.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-3 pb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="submitCancel()">
                    <i class="fas fa-times me-1"></i>Cancel Appointment
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ RESCHEDULE MODAL ══ --}}
<div class="modal fade" id="rescheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-card">
            <div class="modal-hd">
                <i class="fas fa-calendar-alt text-warning me-2"></i>Reschedule Appointment
            </div>
            <div class="modal-body p-3">
                <p style="font-size:.84rem;color:#555" id="reschedulePatientName"></p>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label" style="font-size:.8rem;font-weight:600">
                            New Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="rescheduleDate" class="form-control form-control-sm"
                            min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:.8rem;font-weight:600">
                            New Time <span class="text-danger">*</span>
                        </label>
                        <input type="time" id="rescheduleTime" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-3 pb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning btn-sm" onclick="submitReschedule()">
                    <i class="fas fa-calendar-check me-1"></i>Reschedule
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ NOTES MODAL ══ --}}
<div class="modal fade" id="notesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-card">
            <div class="modal-hd">
                <i class="fas fa-sticky-note text-purple me-2" style="color:#6f42c1"></i>
                Appointment Notes
            </div>
            <div class="modal-body p-3">
                <label class="form-label" style="font-size:.8rem;font-weight:600">Notes</label>
                <textarea id="aptNotes" class="form-control form-control-sm" rows="4"
                    placeholder="Add clinical notes, observations..."></textarea>
            </div>
            <div class="modal-footer border-0 pt-0 px-3 pb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitNotes()">
                    <i class="fas fa-save me-1"></i>Save Notes
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;
let activeAptId = null;

const Toast = Swal.mixin({
    toast: true, position: 'top-end',
    showConfirmButton: false, timer: 2500, timerProgressBar: true
});

function doPost(url, body = {}) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(body)
    }).then(r => r.json());
}

// ── Confirm ──
function confirmApt(id, name) {
    Swal.fire({
        title: 'Confirm Appointment?',
        html: `<span style="font-size:.85rem">Patient: <strong>${name}</strong></span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i>Confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then(res => {
        if (!res.isConfirmed) return;
        doPost(`/doctor/appointments/${id}/confirm`)
            .then(d => {
                if (d.success) { Toast.fire({ icon:'success', title:'Appointment confirmed!' }); setTimeout(()=>location.reload(),1500); }
                else Swal.fire('Error', d.message, 'error');
            });
    });
}

// ── Complete ──
function completeApt(id, name) {
    Swal.fire({
        title: 'Mark as Completed?',
        html: `<span style="font-size:.85rem">Patient: <strong>${name}</strong></span>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check-double me-1"></i>Complete',
        reverseButtons: true
    }).then(res => {
        if (!res.isConfirmed) return;
        doPost(`/doctor/appointments/${id}/complete`)
            .then(d => {
                if (d.success) { Toast.fire({ icon:'success', title:'Marked as completed!' }); setTimeout(()=>location.reload(),1500); }
                else Swal.fire('Error', d.message, 'error');
            });
    });
}

// ── Cancel Modal ──
function openCancelModal(id, name) {
    activeAptId = id;
    document.getElementById('cancelPatientName').textContent = 'Patient: ' + name;
    document.getElementById('cancelReason').value = '';
    document.getElementById('cancelReasonError').style.display = 'none';
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}

function submitCancel() {
    const reason = document.getElementById('cancelReason').value.trim();
    if (!reason) { document.getElementById('cancelReasonError').style.display = 'block'; return; }
    document.getElementById('cancelReasonError').style.display = 'none';

    doPost(`/doctor/appointments/${activeAptId}/cancel`, { cancellation_reason: reason })
        .then(d => {
            bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
            if (d.success) { Toast.fire({ icon:'success', title:'Appointment cancelled.' }); setTimeout(()=>location.reload(),1500); }
            else Swal.fire('Error', d.message, 'error');
        });
}

// ── Reschedule Modal ──
function openRescheduleModal(id, name) {
    activeAptId = id;
    document.getElementById('reschedulePatientName').textContent = 'Patient: ' + name;
    document.getElementById('rescheduleDate').value = '';
    document.getElementById('rescheduleTime').value = '';
    new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
}

function submitReschedule() {
    const date = document.getElementById('rescheduleDate').value;
    const time = document.getElementById('rescheduleTime').value;
    if (!date || !time) { Swal.fire('Missing', 'Please select date and time.', 'warning'); return; }

    doPost(`/doctor/appointments/${activeAptId}/reschedule`, {
        appointment_date: date,
        appointment_time: time
    }).then(d => {
        bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
        if (d.success) { Toast.fire({ icon:'success', title:'Appointment rescheduled!' }); setTimeout(()=>location.reload(),1500); }
        else Swal.fire('Error', d.message, 'error');
    });
}

// ── Notes Modal ──
function openNotesModal(id, existingNotes) {
    activeAptId = id;
    document.getElementById('aptNotes').value = existingNotes || '';
    new bootstrap.Modal(document.getElementById('notesModal')).show();
}

function submitNotes() {
    const notes = document.getElementById('aptNotes').value.trim();
    if (!notes) { Swal.fire('Empty', 'Please enter some notes.', 'warning'); return; }

    doPost(`/doctor/appointments/${activeAptId}/add-notes`, { notes })
        .then(d => {
            bootstrap.Modal.getInstance(document.getElementById('notesModal')).hide();
            if (d.success) Toast.fire({ icon:'success', title:'Notes saved!' });
            else Swal.fire('Error', d.message, 'error');
        });
}
</script>
@endpush
