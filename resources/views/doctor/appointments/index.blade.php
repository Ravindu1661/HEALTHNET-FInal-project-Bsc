@extends('doctor.layouts.master')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filters --}}
<div class="dashboard-card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0">
            <i class="fas fa-calendar-check me-2"></i>Appointments
        </h6>

        {{-- optional: if doctor can create appointments manually, enable this --}}
        {{-- <a href="{{ route('doctor.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New
        </a> --}}
    </div>

    <div class="card-body">
        <form action="{{ route('doctor.appointments.index') }}" method="GET" class="row g-3 mb-2">

            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by appointment/patient..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status')==='confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="noshow" {{ request('status')==='noshow' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">Payment Status</option>
                    <option value="unpaid" {{ request('payment_status')==='unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status')==='partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status')==='paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date"
                       name="date_from"
                       class="form-control form-control-sm"
                       value="{{ request('date_from') }}">
            </div>

            <div class="col-md-2">
                <input type="date"
                       name="date_to"
                       class="form-control form-control-sm"
                       value="{{ request('date_to') }}">
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Stats (controller එකෙන් $stats pass කලොත්) --}}
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-summary stat-summary-primary">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-details">
                <h6>Total</h6>
                <h4>{{ $stats['total'] ?? $appointments->total() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-summary stat-summary-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-details">
                <h6>Confirmed</h6>
                <h4>{{ $stats['confirmed'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-details">
                <h6>Pending</h6>
                <h4>{{ $stats['pending'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-summary stat-summary-danger">
            <div class="stat-icon"><i class="fas fa-ban"></i></div>
            <div class="stat-details">
                <h6>Cancelled</h6>
                <h4>{{ $stats['cancelled'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table table-hover table">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Appointment</th>
                        <th>Patient</th>
                        <th>Date / Time</th>
                        <th>Workplace</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Fee</th>
                        <th width="170" class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>

                        <td>
                            <span class="badge bg-info">
                                {{ $appointment->appointment_number ?? ('APT-'.$appointment->id) }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2">
                                    <img
                                        src="{{ $appointment->patient?->profile_image ? asset('storage/'.$appointment->patient->profile_image) : asset('images/default-avatar.png') }}"
                                        alt="patient"
                                        width="35" height="35"
                                        class="rounded-circle"
                                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div>
                                    <strong>
                                        {{ $appointment->patient?->first_name }} {{ $appointment->patient?->last_name }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $appointment->patient?->user?->email }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            <br>
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>

                        <td>
                            @if(($appointment->workplace_type ?? null) === 'hospital')
                                <i class="fas fa-hospital me-1"></i>
                                {{ $appointment->hospital?->name ?? 'Hospital' }}
                            @elseif(($appointment->workplace_type ?? null) === 'medicalcentre' || ($appointment->workplace_type ?? null) === 'medical_centre')
                                <i class="fas fa-clinic-medical me-1"></i>
                                {{ $appointment->medicalCentre?->name ?? 'Medical Centre' }}
                            @else
                                <i class="fas fa-user-md me-1"></i>Private
                            @endif
                        </td>

                        <td>
                            @php($st = $appointment->status)
                            @if($st === 'confirmed')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Confirmed</span>
                            @elseif($st === 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($st === 'completed')
                                <span class="badge bg-primary"><i class="fas fa-check"></i> Completed</span>
                            @elseif($st === 'cancelled')
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Cancelled</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user-slash"></i> No Show</span>
                            @endif
                        </td>

                        <td>
                            @php($pay = $appointment->payment_status ?? $appointment->paymentstatus)
                            @if($pay === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($pay === 'partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>

                        <td>
                            <strong>LKR {{ number_format($appointment->consultation_fee ?? $appointment->consultationfee ?? 0, 2) }}</strong>
                        </td>

                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                   class="btn btn-info"
                                   data-bs-toggle="tooltip"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($appointment->status === 'pending')
                                    <button type="button"
                                            onclick="confirmAppointment({{ $appointment->id }})"
                                            class="btn btn-success"
                                            data-bs-toggle="tooltip"
                                            title="Confirm">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button"
                                            onclick="cancelAppointment({{ $appointment->id }})"
                                            class="btn btn-danger"
                                            data-bs-toggle="tooltip"
                                            title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @elseif($appointment->status === 'confirmed')
                                    <button type="button"
                                            onclick="completeAppointment({{ $appointment->id }})"
                                            class="btn btn-primary"
                                            data-bs-toggle="tooltip"
                                            title="Complete">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No appointments found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }}
                of {{ $appointments->total() }} entries
            </div>

            <div>
                {{ $appointments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.stat-summary{display:flex;align-items:center;padding:1rem;background:#fff;border-radius:8px;border-left:4px solid;box-shadow:0 2px 4px rgba(0,0,0,.05)}
.stat-summary-primary{border-color:#4285F4}.stat-summary-success{border-color:#34A853}.stat-summary-warning{border-color:#FBBC05}.stat-summary-danger{border-color:#EA4335}
.stat-icon{font-size:2rem;width:60px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:8px;margin-right:1rem}
.stat-summary-primary .stat-icon{background:rgba(66,133,244,.1);color:#4285F4}
.stat-summary-success .stat-icon{background:rgba(52,168,83,.1);color:#34A853}
.stat-summary-warning .stat-icon{background:rgba(251,188,5,.1);color:#FBBC05}
.stat-summary-danger .stat-icon{background:rgba(234,67,53,.1);color:#EA4335}
.stat-details h6{margin:0;font-size:.85rem;color:#666;font-weight:500}
.stat-details h4{margin:0;font-size:1.5rem;font-weight:700;color:#333}
.user-avatar-sm{width:32px;height:32px;overflow:hidden;border-radius:50%}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true,
});

function confirmAppointment(id){
    Swal.fire({
        title: 'Confirm Appointment?',
        text: 'This will notify the patient.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`{{ url('doctor/appointments') }}/${id}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(r => r.json()).then(data => {
                if(!data.success) throw new Error(data.message || 'Failed');
                return data;
            }).catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then((result) => {
        if(result.isConfirmed){
            Toast.fire({icon:'success', title:'Confirmed!'}).then(()=>location.reload());
        }
    });
}

function cancelAppointment(id){
    Swal.fire({
        title: 'Cancel Appointment?',
        input: 'textarea',
        inputLabel: 'Cancellation Reason',
        inputPlaceholder: 'Enter reason...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Cancel Appointment',
        cancelButtonText: 'Close',
        showLoaderOnConfirm: true,
        preConfirm: (reason) => {
            return fetch(`{{ url('doctor/appointments') }}/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cancellation_reason: reason })
            }).then(r => r.json()).then(data => {
                if(!data.success) throw new Error(data.message || 'Failed');
                return data;
            }).catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then((result) => {
        if(result.isConfirmed){
            Toast.fire({icon:'success', title:'Cancelled!'}).then(()=>location.reload());
        }
    });
}

function completeAppointment(id){
    Swal.fire({
        title: 'Mark as Completed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Complete',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(`{{ url('doctor/appointments') }}/${id}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(r => r.json()).then(data => {
                if(!data.success) throw new Error(data.message || 'Failed');
                return data;
            }).catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then((result) => {
        if(result.isConfirmed){
            Toast.fire({icon:'success', title:'Completed!'}).then(()=>location.reload());
        }
    });
}
</script>
@endpush
