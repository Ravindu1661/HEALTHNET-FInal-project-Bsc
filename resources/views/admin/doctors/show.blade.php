@extends('admin.layouts.master')

@section('title', 'Doctor Details')

@section('page-title', 'Doctor Details')

@section('content')
    <div class="row">
        <div class="col-lg-11 mx-auto">

            <!-- Doctor Header Card -->
            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ asset('storage/' . $doctor->profile_image) }}"
                            alt="Dr. {{ $doctor->full_name }}"
                            class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;"
                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        </div>
                        <div class="col-md-7">
                            <h3 class="mb-2">Dr. {{ $doctor->full_name }}</h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-stethoscope"></i> {{ $doctor->specialization }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope"></i> {{ $doctor->user->email }}
                                @if($doctor->phone)
                                    | <i class="fas fa-phone"></i> {{ $doctor->phone }}
                                @endif
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-info">SLMC: {{ $doctor->slmc_number }}</span>
                                @if($doctor->status == 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($doctor->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($doctor->status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif

                                @if($doctor->user->email_verified_at)
                                    <span class="badge bg-primary"><i class="fas fa-envelope-circle-check"></i> Email Verified</span>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="mt-2">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $doctor->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <strong>{{ number_format($doctor->rating, 2) }}</strong>
                                <small class="text-muted">({{ $doctor->total_ratings }} ratings)</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm mb-2 w-100">
                                <i class="fas fa-edit"></i> Edit Doctor
                            </a>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-md-6">
                    <!-- Account Information -->
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6><i class="fas fa-user-circle me-2"></i>Account Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="45%">Doctor ID:</th>
                                    <td>{{ $doctor->id }}</td>
                                </tr>
                                <tr>
                                    <th>User ID:</th>
                                    <td>{{ $doctor->user_id }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $doctor->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Account Status:</th>
                                    <td>
                                        @if($doctor->user->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($doctor->user->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($doctor->user->status == 'suspended')
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($doctor->user->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Profile Status:</th>
                                    <td>
                                        @if($doctor->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($doctor->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($doctor->status == 'suspended')
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-secondary">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email Verified:</th>
                                    <td>
                                        @if($doctor->user->email_verified_at)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $doctor->user->email_verified_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-clock"></i> Not Verified
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Member Since:</th>
                                    <td>{{ $doctor->created_at->format('M d, Y') }} ({{ $doctor->created_at->diffForHumans() }})</td>
                                </tr>
                                @if($doctor->approved_at)
                                <tr>
                                    <th>Approved At:</th>
                                    <td>{{ $doctor->approved_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                @if($doctor->approvedBy)
                                <tr>
                                    <th>Approved By:</th>
                                    <td>{{ $doctor->approvedBy->email }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Professional Details -->
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6><i class="fas fa-briefcase me-2"></i>Professional Details</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="45%">SLMC Number:</th>
                                    <td><strong class="text-primary">{{ $doctor->slmc_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Specialization:</th>
                                    <td>{{ $doctor->specialization }}</td>
                                </tr>
                                <tr>
                                    <th>Experience:</th>
                                    <td>{{ $doctor->experience_years ?? 0 }} years</td>
                                </tr>
                                <tr>
                                    <th>Consultation Fee:</th>
                                    <td><strong>LKR {{ number_format($doctor->consultation_fee ?? 0, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $doctor->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>

                            @if($doctor->qualifications)
                            <div class="mt-3">
                                <h6 class="mb-2">Qualifications:</h6>
                                <p class="text-muted mb-0">{{ $doctor->qualifications }}</p>
                            </div>
                            @endif

                            @if($doctor->bio)
                            <div class="mt-3">
                                <h6 class="mb-2">About:</h6>
                                <p class="text-muted mb-0">{{ $doctor->bio }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <!-- Statistics -->
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-box text-center p-3 bg-primary bg-opacity-10 rounded">
                                        <h4 class="mb-0 text-primary">{{ $doctor->appointments_count ?? 0 }}</h4>
                                        <small class="text-muted">Total Appointments</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box text-center p-3 bg-success bg-opacity-10 rounded">
                                        <h4 class="mb-0 text-success">{{ $doctor->workplaces_count ?? 0 }}</h4>
                                        <small class="text-muted">Workplaces</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box text-center p-3 bg-warning bg-opacity-10 rounded">
                                        <h4 class="mb-0 text-warning">{{ number_format($doctor->rating, 1) }}</h4>
                                        <small class="text-muted">Average Rating</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box text-center p-3 bg-info bg-opacity-10 rounded">
                                        <h4 class="mb-0 text-info">{{ $doctor->total_ratings }}</h4>
                                        <small class="text-muted">Total Reviews</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6><i class="fas fa-file-alt me-2"></i>Documents</h6>
                        </div>
                        <div class="card-body">
                            @if($doctor->document_path)
                                <div class="document-item p-3 border rounded mb-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                            <strong>SLMC Certificate / License</strong>
                                        </div>
                                        <div>
                                            <a href="{{ Storage::url($doctor->document_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ Storage::url($doctor->document_path) }}"
                                               download
                                               class="btn btn-sm btn-secondary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Uploaded: {{ \Carbon\Carbon::parse($doctor->created_at)->format('M d, Y') }}
                                    </small>
                                </div>
                            @else
                                <p class="text-muted text-center py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                    No documents uploaded
                                </p>
                            @endif
                        </div>
                    </div>
 <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6>
                            <i class="fas fa-building me-2"></i>
                            Workplaces ({{ $workplaces->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($workplaces->count() > 0)
                            <div class="workplaces-list">
                                @foreach($workplaces as $workplace)
                                    @php
                                        $workplaceData = null;
                                        $workplaceName = 'N/A';
                                        $workplaceCity = 'N/A';
                                        $workplaceType = ucfirst(str_replace('_', ' ', $workplace->workplace_type));

                                        if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                                            $workplaceData = $workplace->hospital;
                                            $workplaceName = $workplaceData->name;
                                            $workplaceCity = $workplaceData->city;
                                        } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                                            $workplaceData = $workplace->medicalCentre;
                                            $workplaceName = $workplaceData->name;
                                            $workplaceCity = $workplaceData->city;
                                        }
                                    @endphp

                                    <div class="workplace-item" id="workplace-{{ $workplace->id }}">
                                        <div class="workplace-header">
                                            <div class="workplace-info">
                                                <h6 class="workplace-name">{{ $workplaceName }}</h6>
                                                <div class="workplace-meta">
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-{{ $workplace->workplace_type == 'hospital' ? 'hospital' : 'clinic-medical' }}"></i>
                                                        {{ $workplaceType }}
                                                    </span>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-briefcase"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $workplace->employment_type)) }}
                                                    </span>
                                                    @if($workplace->status == 'approved')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle"></i> Approved
                                                        </span>
                                                    @elseif($workplace->status == 'pending')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle"></i> Rejected
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="workplace-location mb-0">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $workplaceCity }}
                                                </p>
                                                <small class="text-muted">
                                                    Added: {{ $workplace->created_at->format('M d, Y') }}
                                                    @if($workplace->approved_at)
                                                        | Approved: {{ $workplace->approved_at->format('M d, Y') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div class="workplace-actions">
                                            @if($workplace->status == 'pending')
                                                <button onclick="approveWorkplace({{ $workplace->id }})"
                                                        class="btn btn-sm btn-success"
                                                        title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button onclick="rejectWorkplace({{ $workplace->id }})"
                                                        class="btn btn-sm btn-danger"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($workplace->status == 'approved')
                                                <button class="btn btn-sm btn-success" disabled>
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @else
                                                <button onclick="deleteWorkplace({{ $workplace->id }})"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-building fa-2x mb-2 d-block"></i>
                                <p class="mb-0">No workplaces added yet</p>
                            </div>
                        @endif
                    </div>
                </div>
                    <!-- Quick Actions -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($doctor->status == 'pending')
                                    <button onclick="approveDoctorConfirm({{ $doctor->id }})" class="btn btn-success">
                                        <i class="fas fa-check"></i> Approve Doctor
                                    </button>
                                    <button onclick="rejectDoctorConfirm({{ $doctor->id }})" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Reject Doctor
                                    </button>
                                @elseif($doctor->status == 'approved')
                                    <button onclick="suspendDoctorConfirm({{ $doctor->id }})" class="btn btn-warning">
                                        <i class="fas fa-ban"></i> Suspend Doctor
                                    </button>
                                @elseif($doctor->status == 'suspended')
                                    <button onclick="activateDoctorConfirm({{ $doctor->id }})" class="btn btn-success">
                                        <i class="fas fa-check"></i> Activate Doctor
                                    </button>
                                @endif

                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Doctor
                                </a>

                                <button onclick="deleteDoctorConfirm({{ $doctor->id }})" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Doctor
                                </button>

                                <button onclick="window.print()" class="btn btn-secondary">
                                    <i class="fas fa-print"></i> Print Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteDoctorForm" method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
@push('styles')
<style>
/* ✅ NEW: Workplaces Styles */
.workplaces-list {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.workplaces-list::-webkit-scrollbar {
    width: 5px;
}

.workplaces-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.workplaces-list::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.workplace-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 0.8rem;
    transition: all 0.3s ease;
    background: white;
}

.workplace-header {
    flex: 1;
}

.workplace-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.workplace-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.3rem;
}

.workplace-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.3rem;
}

.workplace-meta .badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.workplace-location {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.3rem;
}

.workplace-actions {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.workplace-actions .btn {
    padding: 0.4rem 0.6rem;
    font-size: 0.8rem;
}

/* Responsive */
@media (max-width: 576px) {
    .workplace-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .workplace-actions {
        flex-direction: row;
        width: 100%;
        margin-top: 0.5rem;
    }

    .workplace-actions .btn {
        flex: 1;
    }
}
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function approveDoctorConfirm(doctorId) {
        Swal.fire({
            title: 'Approve Doctor?',
            text: "This doctor will be able to manage appointments.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/doctors/${doctorId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Approved!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function rejectDoctorConfirm(doctorId) {
        Swal.fire({
            title: 'Reject Doctor?',
            text: "This action can be reversed later.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/doctors/${doctorId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Rejected!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function suspendDoctorConfirm(doctorId) {
        Swal.fire({
            title: 'Suspend?',
            text: "Doctor will not be able to access the system.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            confirmButtonText: 'Suspend'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/doctors/${doctorId}/suspend`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Suspended!', data.message, 'success')
                            .then(() => location.reload());
                    }
                });
            }
        });
    }

    function activateDoctorConfirm(doctorId) {
        Swal.fire({
            title: 'Activate?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Activate'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/doctors/${doctorId}/activate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Activated!', data.message, 'success')
                            .then(() => location.reload());
                    }
                });
            }
        });
    }

    function deleteDoctorConfirm(doctorId) {
        Swal.fire({
            title: 'Delete Doctor?',
            html: '<small class="text-danger">This action cannot be undone!</small>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteDoctorForm').submit();
            }
        });
    }
    // ✅ NEW: Workplace Management Functions
function approveWorkplace(workplaceId) {
    Swal.fire({
        title: 'Approve Workplace?',
        text: 'This doctor will be associated with this workplace.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/doctors/workplaces/${workplaceId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approved!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Failed to approve workplace', 'error');
            });
        }
    });
}

function rejectWorkplace(workplaceId) {
    Swal.fire({
        title: 'Reject Workplace?',
        text: 'This workplace association will be rejected.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/doctors/workplaces/${workplaceId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejected!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Failed to reject workplace', 'error');
            });
        }
    });
}

function deleteWorkplace(workplaceId) {
    Swal.fire({
        title: 'Delete Workplace?',
        html: '<small class="text-danger">This action cannot be undone!</small>',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/doctors/workplaces/${workplaceId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Failed to delete workplace', 'error');
            });
        }
    });
}
</script>
@endpush
