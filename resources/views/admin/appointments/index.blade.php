@extends('admin.layouts.master')

@section('title', 'Appointments Management')
@section('page-title', 'Appointments Management')

@section('content')

<!-- Success/Error Messages -->
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

<!-- Filters and Actions -->
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-calendar-check me-2"></i>All Appointments</h6>
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Appointment
        </a>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.appointments.index') }}" method="GET" class="row g-3 mb-3">
            <!-- Search -->
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by appointment #, patient, doctor..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show" {{ request('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                </select>
            </div>
            
            <!-- Payment Status Filter -->
            <div class="col-md-2">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">Payment Status</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            
            <!-- Date From -->
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" 
                       placeholder="From Date" value="{{ request('date_from') }}">
            </div>
            
            <!-- Date To -->
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" 
                       placeholder="To Date" value="{{ request('date_to') }}">
            </div>
            
            <!-- Filter Button -->
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
        
        <!-- Stats Summary -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="stat-summary stat-summary-primary">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-details">
                        <h6>Total Appointments</h6>
                        <h4>{{ $appointments->total() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-summary stat-summary-success">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-details">
                        <h6>Confirmed</h6>
                        <h4>{{ \App\Models\Appointment::where('status', 'confirmed')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-summary stat-summary-warning">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-details">
                        <h6>Pending</h6>
                        <h4>{{ \App\Models\Appointment::where('status', 'pending')->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-summary stat-summary-danger">
                    <div class="stat-icon"><i class="fas fa-ban"></i></div>
                    <div class="stat-details">
                        <h6>Cancelled</h6>
                        <h4>{{ \App\Models\Appointment::where('status', 'cancelled')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointments Table -->
<div class="dashboard-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Appointment #</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Workplace</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Fee</th>
                        <th width="200" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>
                        <td>
                            <span class="badge bg-info">{{ $appointment->appointment_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2">
                                    <img src="{{ asset('storage/' . ($appointment->patient->profileimage ?? 'images/default-avatar.png')) }}" 
                                         alt="{{ $appointment->patient->firstname }}" 
                                         class="rounded-circle" width="35" height="35"
                                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div>
                                    <strong>{{ $appointment->patient->firstname }} {{ $appointment->patient->lastname }}</strong><br>
                                    <small class="text-muted">{{ $appointment->patient->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>Dr. {{ $appointment->doctor->firstname }} {{ $appointment->doctor->lastname }}</strong><br>
                            <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                        </td>
                        <td>
                            <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}<br>
                            <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </td>
                        <td>
                            @if($appointment->workplace_type == 'hospital' && $appointment->hospital)
                                <i class="fas fa-hospital"></i> {{ $appointment->hospital->name }}
                            @elseif($appointment->workplace_type == 'medicalcentre' && $appointment->medicalCentre)
                                <i class="fas fa-clinic-medical"></i> {{ $appointment->medicalCentre->name }}
                            @else
                                <i class="fas fa-user-md"></i> Private
                            @endif
                        </td>
                        <td>
                            @if($appointment->status == 'confirmed')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Confirmed</span>
                            @elseif($appointment->status == 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-primary"><i class="fas fa-check"></i> Completed</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Cancelled</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user-slash"></i> No Show</span>
                            @endif
                        </td>
                        <td>
                            @if($appointment->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($appointment->payment_status == 'partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>
                        <td><strong>LKR {{ number_format($appointment->consultation_fee, 2) }}</strong></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" 
                                   class="btn btn-info" title="View Details" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" 
                                   class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($appointment->status == 'pending')
                                    <button onclick="confirmAppointment({{ $appointment->id }})" 
                                            class="btn btn-success" title="Confirm" data-bs-toggle="tooltip">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="cancelAppointment({{ $appointment->id }})" 
                                            class="btn btn-danger" title="Cancel" data-bs-toggle="tooltip">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @elseif($appointment->status == 'confirmed')
                                    <button onclick="completeAppointment({{ $appointment->id }})" 
                                            class="btn btn-primary" title="Complete" data-bs-toggle="tooltip">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                @endif
                                
                                <button onclick="deleteAppointment({{ $appointment->id }})" 
                                        class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No appointments found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} entries
            </div>
            <div>
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<style>
.stat-summary {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #fff;
    border-radius: 8px;
    border-left: 4px solid;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.stat-summary-primary { border-color: #4285F4; }
.stat-summary-success { border-color: #34A853; }
.stat-summary-warning { border-color: #FBBC05; }
.stat-summary-danger { border-color: #EA4335; }

.stat-summary .stat-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin-right: 1rem;
}
.stat-summary-primary .stat-icon { background: rgba(66, 133, 244, 0.1); color: #4285F4; }
.stat-summary-success .stat-icon { background: rgba(52, 168, 83, 0.1); color: #34A853; }
.stat-summary-warning .stat-icon { background: rgba(251, 188, 5, 0.1); color: #FBBC05; }
.stat-summary-danger .stat-icon { background: rgba(234, 67, 53, 0.1); color: #EA4335; }

.stat-summary .stat-details h6 {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
    font-weight: 500;
}
.stat-summary .stat-details h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}
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
    timerProgressBar: true
});

function confirmAppointment(id) {
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
            return fetch(`/admin/appointments/${id}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);
                return data;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Toast.fire({
                icon: 'success',
                title: 'Confirmed!'
            }).then(() => location.reload());
        }
    });
}

function cancelAppointment(id) {
    Swal.fire({
        title: 'Cancel Appointment?',
        input: 'textarea',
        inputLabel: 'Cancellation Reason',
        inputPlaceholder: 'Enter reason for cancellation...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Cancel Appointment',
        cancelButtonText: 'Close',
        showLoaderOnConfirm: true,
        preConfirm: (reason) => {
            return fetch(`/admin/appointments/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cancellation_reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);
                return data;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Toast.fire({
                icon: 'success',
                title: 'Cancelled!'
            }).then(() => location.reload());
        }
    });
}

function completeAppointment(id) {
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
            return fetch(`/admin/appointments/${id}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);
                return data;
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Toast.fire({
                icon: 'success',
                title: 'Completed!'
            }).then(() => location.reload());
        }
    });
}

function deleteAppointment(id) {
    Swal.fire({
        title: 'Delete Appointment?',
        html: '<small class="text-danger">Cannot be undone!</small>',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/appointments/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush
