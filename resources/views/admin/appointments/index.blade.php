@extends('admin.layouts.master')

@section('title', 'Appointments Management')
@section('page-title', 'Appointments Management')

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

<div class="dashboard-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>All Appointments</h6>
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Appointment
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.appointments.index') }}" method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by appointment#, patient, doctor..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending"   {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="noshow"    {{ request('status') === 'noshow' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">Payment Status</option>
                    <option value="unpaid"  {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid"    {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="workplace_type" class="form-select form-select-sm">
                    <option value="">Workplace Type</option>
                    <option value="hospital"       {{ request('workplace_type') === 'hospital' ? 'selected' : '' }}>Hospital</option>
                    <option value="medical_centre" {{ request('workplace_type') === 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                    <option value="private"        {{ request('workplace_type') === 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="col-md-1">
                <input type="date" name="datefrom" class="form-control form-control-sm"
                       value="{{ request('datefrom') }}">
            </div>

            <div class="col-md-1">
                <input type="date" name="dateto" class="form-control form-control-sm"
                       value="{{ request('dateto') }}">
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Appointment</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date / Time</th>
                        <th>Workplace</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Fee</th>
                        <th width="200" class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($appointments as $appointment)
                    @php
                        $patientImg = ($appointment->patient && $appointment->patient->profileimage)
                            ? asset('storage/' . $appointment->patient->profileimage)
                            : asset('images/default-avatar.png');
                    @endphp

                    <tr>
                        <td>{{ $appointment->id }}</td>

                        <td><span class="badge bg-info">{{ $appointment->appointment_number }}</span></td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2">
                                    <img src="{{ $patientImg }}"
                                         class="rounded-circle" width="35" height="35"
                                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div>
                                    <strong>{{ $appointment->patient->firstname ?? '' }} {{ $appointment->patient->lastname ?? '' }}</strong><br>
                                    <small class="text-muted">{{ $appointment->patient->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <strong>Dr. {{ $appointment->doctor->firstname ?? '' }} {{ $appointment->doctor->lastname ?? '' }}</strong><br>
                            <small class="text-muted">{{ $appointment->doctor->specialization ?? '' }}</small>
                        </td>

                        <td>
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}<br>
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>

                        <td>
                            @if($appointment->workplace_type === 'hospital' && $appointment->hospital)
                                <i class="fas fa-hospital"></i> {{ $appointment->hospital->name }}
                            @elseif($appointment->workplace_type === 'medical_centre' && $appointment->medicalCentre)
                                <i class="fas fa-clinic-medical"></i> {{ $appointment->medicalCentre->name }}
                            @else
                                <i class="fas fa-user-md"></i> Private
                            @endif
                        </td>

                        <td>
                            @if($appointment->status === 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($appointment->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($appointment->status === 'completed')
                                <span class="badge bg-primary">Completed</span>
                            @elseif($appointment->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">No Show</span>
                            @endif
                        </td>

                        <td>
                            @if($appointment->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($appointment->payment_status === 'partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>

                        <td><strong>LKR {{ number_format((float)$appointment->consultation_fee, 2) }}</strong></td>

                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                   class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                   class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($appointment->status === 'pending')
                                    <button type="button"
                                            onclick="confirmAppointment('{{ route('admin.appointments.confirm', $appointment->id) }}')"
                                            class="btn btn-success" title="Confirm" data-bs-toggle="tooltip">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button"
                                            onclick="cancelAppointment('{{ route('admin.appointments.cancel', $appointment->id) }}')"
                                            class="btn btn-danger" title="Cancel" data-bs-toggle="tooltip">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @elseif($appointment->status === 'confirmed')
                                    <button type="button"
                                            onclick="completeAppointment('{{ route('admin.appointments.complete', $appointment->id) }}')"
                                            class="btn btn-primary" title="Complete" data-bs-toggle="tooltip">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                @endif

                                <button type="button"
                                        onclick="deleteAppointment('{{ route('admin.appointments.destroy', $appointment->id) }}')"
                                        class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <h5 class="text-muted mb-1">No appointments found</h5>
                            <p class="text-muted mb-0">Try changing filters or search text.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }}
                of {{ $appointments->total() }} entries
            </div>
            <div>{{ $appointments->links() }}</div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function csrfHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        };
    }

    function confirmAppointment(url) {
        Swal.fire({
            title: 'Confirm Appointment?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: () => fetch(url, { method: 'POST', headers: csrfHeaders() })
                .then(r => r.json())
                .then(d => { if (!d.success) throw new Error(d.message || 'Failed'); return d; })
                .catch(e => Swal.showValidationMessage(e.message))
        }).then((r) => { if (r.isConfirmed) location.reload(); });
    }

    function cancelAppointment(url) {
        Swal.fire({
            title: 'Cancel Appointment?',
            input: 'textarea',
            inputLabel: 'Cancellation Reason',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Cancel Appointment',
            showLoaderOnConfirm: true,
            preConfirm: (reason) => fetch(url, {
                method: 'POST',
                headers: csrfHeaders(),
                body: JSON.stringify({ cancellation_reason: reason })
            })
            .then(r => r.json())
            .then(d => { if (!d.success) throw new Error(d.message || 'Failed'); return d; })
            .catch(e => Swal.showValidationMessage(e.message))
        }).then((r) => { if (r.isConfirmed) location.reload(); });
    }

    function completeAppointment(url) {
        Swal.fire({
            title: 'Mark as Completed?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Complete',
            showLoaderOnConfirm: true,
            preConfirm: () => fetch(url, { method: 'POST', headers: csrfHeaders() })
                .then(r => r.json())
                .then(d => { if (!d.success) throw new Error(d.message || 'Failed'); return d; })
                .catch(e => Swal.showValidationMessage(e.message))
        }).then((r) => { if (r.isConfirmed) location.reload(); });
    }

    function deleteAppointment(url) {
        Swal.fire({
            title: 'Delete Appointment?',
            text: 'This cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Delete'
        }).then((r) => {
            if (r.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush
