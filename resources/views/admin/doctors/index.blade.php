@extends('admin.layouts.master')

@section('title', 'Doctors Management')

@section('page-title', 'Doctors Management')

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
            <h6><i class="fas fa-user-md me-2"></i>All Doctors</h6>
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Doctor
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.doctors.index') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, SLMC..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="specialization" class="form-control form-control-sm"
                           placeholder="Specialization" value="{{ request('specialization') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Stats Summary -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-primary">
                        <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                        <div class="stat-details">
                            <h6>Total Doctors</h6>
                            <h4>{{ $doctors->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-success">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h6>Approved</h6>
                            <h4>{{ $doctors->where('status', 'approved')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-warning">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-details">
                            <h6>Pending</h6>
                            <h4>{{ $doctors->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-danger">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <div class="stat-details">
                            <h6>Suspended</h6>
                            <h4>{{ $doctors->where('status', 'suspended')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctors Table -->
    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Doctor Details</th>
                            <th>SLMC Number</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Experience</th>
                            <th>Fee (LKR)</th>
                            <th>Rating</th>
                            <th width="200" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">
                                        <img src="{{ asset('storage/' . $doctor->profile_image) }}"
                                        alt="Dr. {{ $doctor->full_name }}"
                                        class="rounded-circle" width="35" height="35"  onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div>
                                        <strong>Dr. {{ $doctor->full_name }}</strong><br>
                                        <small class="text-muted">{{ $doctor->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">{{ $doctor->slmc_number }}</span></td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>
                                @if($doctor->status == 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($doctor->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($doctor->status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $doctor->experience_years ?? 0 }} years</td>
                            <td>{{ number_format($doctor->consultation_fee ?? 0, 2) }}</td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $doctor->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <small>({{ number_format($doctor->rating, 1) }})</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                       class="btn btn-info"
                                       title="View Details"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                       class="btn btn-warning"
                                       title="Edit Doctor"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($doctor->status == 'pending')
                                        <button onclick="approveDoctor({{ $doctor->id }}, '{{ $doctor->full_name }}')"
                                                class="btn btn-success"
                                                title="Approve"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectDoctor({{ $doctor->id }}, '{{ $doctor->full_name }}')"
                                                class="btn btn-danger"
                                                title="Reject"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($doctor->status == 'approved')
                                        <button onclick="suspendDoctor({{ $doctor->id }}, '{{ $doctor->full_name }}')"
                                                class="btn btn-secondary"
                                                title="Suspend"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @elseif($doctor->status == 'suspended')
                                        <button onclick="activateDoctor({{ $doctor->id }}, '{{ $doctor->full_name }}')"
                                                class="btn btn-success"
                                                title="Activate"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif

                                    <button onclick="deleteDoctor({{ $doctor->id }}, '{{ $doctor->full_name }}')"
                                            class="btn btn-danger"
                                            title="Delete"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-user-md fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No doctors found</h5>
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
                    Showing {{ $doctors->firstItem() ?? 0 }} to {{ $doctors->lastItem() ?? 0 }} of {{ $doctors->total() }} entries
                </div>
                <div>
                    {{ $doctors->links() }}
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

    /* Compact SweetAlert2 */
    /*
     */
     /* Main popup text size */
.swal2-popup {
    font-size: 0.875rem !important;
}

/* Title size */
.swal2-title {
    font-size: 1.1rem !important;
}

/* Description text */
.swal2-html-container {
    font-size: 0.8rem !important;
}

/* Mobile adjustments */
@media (max-width: 480px) {
    .swal2-popup {
        font-size: 0.8rem !important;
    }
    .swal2-title {
        font-size: 1rem !important;
    }
    .swal2-html-container {
        font-size: 0.75rem !important;
    }
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

    function approveDoctor(doctorId, doctorName) {
        Swal.fire({
            title: 'Approve Doctor?',
            text: 'Dr. ' + doctorName,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/doctors/${doctorId}/approve`, {
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
                Toast.fire({ icon: 'success', title: 'Approved!' }).then(() => location.reload());
            }
        });
    }

    function rejectDoctor(doctorId, doctorName) {
        Swal.fire({
            title: 'Reject Doctor?',
            text: 'Dr. ' + doctorName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/doctors/${doctorId}/reject`, {
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
                Toast.fire({ icon: 'success', title: 'Rejected!' }).then(() => location.reload());
            }
        });
    }

    function suspendDoctor(doctorId, doctorName) {
        Swal.fire({
            title: 'Suspend?',
            text: 'Dr. ' + doctorName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/doctors/${doctorId}/suspend`, {
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
                Toast.fire({ icon: 'success', title: 'Suspended!' }).then(() => location.reload());
            }
        });
    }

    function activateDoctor(doctorId, doctorName) {
        Swal.fire({
            title: 'Activate?',
            text: 'Dr. ' + doctorName,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/admin/doctors/${doctorId}/activate`, {
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
                Toast.fire({ icon: 'success', title: 'Activated!' }).then(() => location.reload());
            }
        });
    }

    function deleteDoctor(doctorId, doctorName) {
        Swal.fire({
            title: 'Delete?',
            html: `<small>Dr. ${doctorName}</small><br><small class="text-danger">Cannot be undone!</small>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/doctors/${doctorId}`;
                form.submit();
            }
        });
    }
</script>
@endpush
