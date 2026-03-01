@extends('doctor.layouts.master')

@section('title', 'Appointment #' . $appointment->appointment_number)
@section('page-title', 'Appointment Details')

@push('styles')
<style>
.detail-card { background:#fff; border-radius:16px; padding:1.4rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }
.dc-title { font-size:.82rem; font-weight:700; color:#1a1a1a;
    padding-bottom:.65rem; border-bottom:1px solid #f0f3f8;
    margin-bottom:1rem; display:flex; align-items:center; gap:.4rem; }
.dc-title i { color:#0d6efd; }

.info-row { display:flex; align-items:flex-start; gap:.5rem;
    padding:.5rem 0; border-bottom:1px solid #f8f9fb; font-size:.83rem; }
.info-row:last-child { border-bottom:none; }
.info-lbl { width:150px; flex-shrink:0; color:#888; font-weight:600; font-size:.75rem; }
.info-val { color:#1a1a1a; font-weight:500; }

.sp { display:inline-flex; align-items:center; padding:.22rem .7rem;
    border-radius:20px; font-size:.72rem; font-weight:700; gap:.3rem; }
.sp.pending   { background:#fff3cd; color:#856404; }
.sp.confirmed { background:#d1ecf1; color:#0c5460; }
.sp.completed { background:#d4edda; color:#155724; }
.sp.cancelled { background:#f8d7da; color:#721c24; }
.sp.no-show   { background:#f0f0f0; color:#555; }

.act-bar { background:#fff; border-radius:14px; padding:1rem 1.3rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem;
    display:flex; align-items:center; gap:.7rem; flex-wrap:wrap; }

.timeline { position:relative; padding-left:1.4rem; }
.timeline::before { content:''; position:absolute; left:6px; top:0; bottom:0;
    width:2px; background:#e8edf2; }
.tl-item { position:relative; padding-bottom:1rem; }
.tl-item:last-child { padding-bottom:0; }
.tl-dot { position:absolute; left:-1.4rem; top:3px; width:14px; height:14px;
    border-radius:50%; border:2px solid #fff; box-shadow:0 0 0 2px #0d6efd; background:#0d6efd; }
.tl-dot.grey { box-shadow:0 0 0 2px #adb5bd; background:#adb5bd; }
.tl-time { font-size:.68rem; color:#aaa; margin-bottom:.15rem; }
.tl-text { font-size:.8rem; color:#333; font-weight:500; }

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

{{-- Breadcrumb --}}
<nav style="font-size:.78rem;margin-bottom:.8rem">
    <a href="{{ route('doctor.dashboard') }}" style="color:#0d6efd;text-decoration:none">Dashboard</a>
    <span class="mx-1 text-muted">/</span>
    <a href="{{ route('doctor.appointments.index') }}" style="color:#0d6efd;text-decoration:none">Appointments</a>
    <span class="mx-1 text-muted">/</span>
    <span class="text-muted">{{ $appointment->appointment_number }}</span>
</nav>

{{-- Action Bar --}}
<div class="act-bar">
    <a href="{{ route('doctor.appointments.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>

    <div class="ms-auto d-flex gap-2 flex-wrap">
        @if($appointment->status === 'pending')
        <button class="btn btn-success btn-sm"
            onclick="confirmApt({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-check me-1"></i>Confirm
        </button>
        <button class="btn btn-warning btn-sm"
            onclick="openRescheduleModal({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-calendar-alt me-1"></i>Reschedule
        </button>
        <button class="btn btn-danger btn-sm"
            onclick="openCancelModal({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-times me-1"></i>Cancel
        </button>
        @endif

        @if($appointment->status === 'confirmed')
        <button class="btn btn-primary btn-sm"
            onclick="completeApt({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-check-double me-1"></i>Mark Complete
        </button>
        <button class="btn btn-warning btn-sm"
            onclick="openRescheduleModal({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-calendar-alt me-1"></i>Reschedule
        </button>
        <button class="btn btn-danger btn-sm"
            onclick="openCancelModal({{ $appointment->id }}, '{{ $appointment->patient_name }}')">
            <i class="fas fa-times me-1"></i>Cancel
        </button>
        @endif

        <button class="btn btn-outline-primary btn-sm"
            onclick="openNotesModal({{ $appointment->id }}, '{{ addslashes($appointment->notes ?? '') }}')">
            <i class="fas fa-sticky-note me-1"></i>Notes
        </button>
    </div>
</div>

<div class="row g-3">

    {{-- LEFT: Appointment Info --}}
    <div class="col-lg-8">

        {{-- Appointment Card --}}
        <div class="detail-card">
            <div class="dc-title">
                <i class="fas fa-calendar-check"></i>Appointment Information
                <span class="ms-auto sp {{ $appointment->status }}">
                    @if($appointment->status === 'pending')   <i class="fas fa-clock"></i>
                    @elseif($appointment->status === 'confirmed') <i class="fas fa-check-circle"></i>
                    @elseif($appointment->status === 'completed') <i class="fas fa-check-double"></i>
                    @elseif($appointment->status === 'cancelled') <i class="fas fa-times-circle"></i>
                    @else <i class="fas fa-user-times"></i>
                    @endif
                    {{ ucfirst(str_replace('_',' ',$appointment->status)) }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-lbl">Appointment No.</span>
                <span class="info-val fw-bold text-primary">{{ $appointment->appointment_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Date</span>
                <span class="info-val">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d F Y') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Time</span>
                <span class="info-val">
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Location</span>
                <span class="info-val">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    {{ $appointment->location }}
                    <span class="badge bg-light text-muted ms-1" style="font-size:.65rem">
                        {{ ucfirst($appointment->workplace_type) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Reason</span>
                <span class="info-val">{{ $appointment->reason ?? '—' }}</span>
            </div>
            @if($appointment->notes)
            <div class="info-row">
                <span class="info-lbl">Notes</span>
                <span class="info-val" style="white-space:pre-wrap">{{ $appointment->notes }}</span>
            </div>
            @endif
            @if($appointment->status === 'cancelled')
            <div class="info-row">
                <span class="info-lbl">Cancel Reason</span>
                <span class="info-val text-danger">{{ $appointment->cancellation_reason ?? '—' }}</span>
            </div>
            @endif
        </div>

        {{-- Payment Card --}}
        <div class="detail-card">
            <div class="dc-title"><i class="fas fa-wallet"></i>Payment Details</div>
            <div class="info-row">
                <span class="info-lbl">Consultation Fee</span>
                <span class="info-val fw-bold text-success">
                    LKR {{ number_format($appointment->consultation_fee, 2) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Advance Paid</span>
                <span class="info-val">LKR {{ number_format($appointment->advance_payment, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Balance</span>
                <span class="info-val fw-bold">
                    LKR {{ number_format($appointment->consultation_fee - $appointment->advance_payment, 2) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-lbl">Payment Status</span>
                @php
                    $pColors = ['unpaid'=>'warning','partial'=>'info','paid'=>'success'];
                    $pc = $pColors[$appointment->payment_status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $pc }}">{{ ucfirst($appointment->payment_status) }}</span>
            </div>
        </div>

    </div>

    {{-- RIGHT: Patient Info + Timeline --}}
    <div class="col-lg-4">

        {{-- Patient Card --}}
        <div class="detail-card">
            <div class="dc-title"><i class="fas fa-user-injured"></i>Patient Information</div>

           <div class="text-center mb-3">
            @php
                $patientImg = $appointment->patient_profile_image ?? null;
            @endphp

            @if($patientImg)
                {{-- Image තිබේ නම් --}}
                <img src="{{ asset('storage/' . $patientImg) }}"
                    alt="{{ $appointment->patient_name }}"
                    class="rounded-circle mb-2"
                    style="width:64px;height:64px;object-fit:cover;
                            border:3px solid #e8edf5;display:block;margin:0 auto"
                    onerror="this.style.display='none';
                            document.getElementById('patient-avatar-fallback').style.display='flex'">

                {{-- Image fail වුනොත් fallback --}}
                <div id="patient-avatar-fallback"
                    class="mx-auto mb-2"
                    style="width:64px;height:64px;border-radius:50%;
                            background:linear-gradient(135deg,#0d6efd,#6f42c1);
                            display:none;align-items:center;justify-content:center;
                            color:#fff;font-size:1.3rem;font-weight:800">
                    {{ strtoupper(substr($appointment->patient_name, 0, 1)) }}
                </div>
            @else
                {{-- Image නැත — initial avatar --}}
                <div class="mx-auto mb-2"
                    style="width:64px;height:64px;border-radius:50%;
                            background:linear-gradient(135deg,#0d6efd,#6f42c1);
                            display:flex;align-items:center;justify-content:center;
                            color:#fff;font-size:1.3rem;font-weight:800">
                    {{ strtoupper(substr($appointment->patient_name, 0, 1)) }}
                </div>
            @endif

            <div style="font-weight:700;font-size:.9rem">{{ $appointment->patient_name }}</div>
        </div>


            <div class="info-row">
                <span class="info-lbl">Phone</span>
                <span class="info-val">{{ $appointment->patient_phone ?? '—' }}</span>
            </div>
            @if(!empty($appointment->date_of_birth))
            <div class="info-row">
                <span class="info-lbl">Age</span>
                <span class="info-val">
                    {{ \Carbon\Carbon::parse($appointment->date_of_birth)->age }} years
                </span>
            </div>
            @endif
            @if(!empty($appointment->gender))
            <div class="info-row">
                <span class="info-lbl">Gender</span>
                <span class="info-val">{{ ucfirst($appointment->gender) }}</span>
            </div>
            @endif
            @if(!empty($appointment->blood_group))
            <div class="info-row">
                <span class="info-lbl">Blood Group</span>
                <span class="info-val">
                    <span class="badge bg-danger">{{ $appointment->blood_group }}</span>
                </span>
            </div>
            @endif
            @if(!empty($appointment->city))
            <div class="info-row">
                <span class="info-lbl">City</span>
                <span class="info-val">{{ $appointment->city }}</span>
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('doctor.patients.show', $appointment->patient_id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-user me-1"></i>View Full Profile
                </a>
            </div>
        </div>

        {{-- Timeline Card --}}
        <div class="detail-card">
            <div class="dc-title"><i class="fas fa-history"></i>Timeline</div>
            <div class="timeline">
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <div class="tl-time">
                        {{ \Carbon\Carbon::parse($appointment->created_at)->format('d M Y, h:i A') }}
                    </div>
                    <div class="tl-text">Appointment booked</div>
                </div>

                @if($appointment->status !== 'pending')
                <div class="tl-item">
                    <div class="tl-dot {{ in_array($appointment->status,['cancelled']) ? 'grey' : '' }}"></div>
                    <div class="tl-time">
                        {{ \Carbon\Carbon::parse($appointment->updated_at)->format('d M Y, h:i A') }}
                    </div>
                    <div class="tl-text">Status updated to
                        <strong>{{ ucfirst(str_replace('_',' ',$appointment->status)) }}</strong>
                    </div>
                </div>
                @endif

                @if($appointment->status === 'completed')
                <div class="tl-item">
                    <div class="tl-dot" style="background:#198754;box-shadow:0 0 0 2px #198754"></div>
                    <div class="tl-time">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                    </div>
                    <div class="tl-text">Appointment completed ✅</div>
                </div>
                @endif
            </div>
        </div>

    </div>
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
                <i class="fas fa-sticky-note me-2" style="color:#6f42c1"></i>Appointment Notes
            </div>
            <div class="modal-body p-3">
                <label class="form-label" style="font-size:.8rem;font-weight:600">Notes</label>
                <textarea id="aptNotes" class="form-control form-control-sm" rows="5"
                    placeholder="Add clinical notes, observations, diagnosis..."></textarea>
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
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
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

function confirmApt(id, name) {
    Swal.fire({
        title: 'Confirm Appointment?',
        html: `<span style="font-size:.85rem">Patient: <strong>${name}</strong></span>`,
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#198754', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i>Confirm',
        reverseButtons: true
    }).then(res => {
        if (!res.isConfirmed) return;
        doPost(`/doctor/appointments/${id}/confirm`)
            .then(d => {
                if (d.success) { Toast.fire({ icon:'success', title:'Confirmed!' }); setTimeout(()=>location.reload(),1500); }
                else Swal.fire('Error', d.message, 'error');
            });
    });
}

function completeApt(id, name) {
    Swal.fire({
        title: 'Mark as Completed?', icon: 'info', showCancelButton: true,
        confirmButtonColor: '#0d6efd', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check-double me-1"></i>Complete',
        reverseButtons: true
    }).then(res => {
        if (!res.isConfirmed) return;
        doPost(`/doctor/appointments/${id}/complete`)
            .then(d => {
                if (d.success) { Toast.fire({ icon:'success', title:'Completed!' }); setTimeout(()=>location.reload(),1500); }
                else Swal.fire('Error', d.message, 'error');
            });
    });
}

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
    doPost(`/doctor/appointments/${activeAptId}/cancel`, { cancellation_reason: reason })
        .then(d => {
            bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
            if (d.success) { Toast.fire({ icon:'success', title:'Cancelled.' }); setTimeout(()=>location.reload(),1500); }
            else Swal.fire('Error', d.message, 'error');
        });
}

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
        appointment_date: date, appointment_time: time
    }).then(d => {
        bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
        if (d.success) { Toast.fire({ icon:'success', title:'Rescheduled!' }); setTimeout(()=>location.reload(),1500); }
        else Swal.fire('Error', d.message, 'error');
    });
}

function openNotesModal(id, existingNotes) {
    activeAptId = id;
    document.getElementById('aptNotes').value = existingNotes || '';
    new bootstrap.Modal(document.getElementById('notesModal')).show();
}

function submitNotes() {
    const notes = document.getElementById('aptNotes').value.trim();
    if (!notes) { Swal.fire('Empty', 'Please enter notes.', 'warning'); return; }
    doPost(`/doctor/appointments/${activeAptId}/add-notes`, { notes })
        .then(d => {
            bootstrap.Modal.getInstance(document.getElementById('notesModal')).hide();
            if (d.success) Toast.fire({ icon:'success', title:'Notes saved!' });
            else Swal.fire('Error', d.message, 'error');
        });
}
</script>
@endpush
