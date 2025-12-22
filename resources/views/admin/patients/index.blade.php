@extends('admin.layouts.master')

@section('title', 'Patients Management')
@section('page-title', 'Patients Management')

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
        <div class="card-header">
            <h6><i class="fas fa-users me-2"></i>All Patients</h6>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Patient
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.index') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, NIC, phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="city" class="form-control form-control-sm"
                           placeholder="City" value="{{ request('city') }}">
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
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-details">
                            <h6>Total Patients</h6>
                            <h4>{{ $patients->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-success">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h6>Active</h6>
                            <h4>{{ \App\Models\User::where('user_type', 'patient')->where('status', 'active')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-warning">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-details">
                            <h6>Pending</h6>
                            <h4>{{ \App\Models\User::where('user_type', 'patient')->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-danger">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <div class="stat-details">
                            <h6>Suspended</h6>
                            <h4>{{ \App\Models\User::where('user_type', 'patient')->where('status', 'suspended')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table-hover">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>City</th>
                            <th>Registered</th>
                            <th width="180" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>
                                <div class="user-avatar-sm me-2">
                                    <img src="{{ $patient->profile_image ? asset('storage/'.$patient->profile_image) : asset('images/default-avatar.png') }}"
                                         alt="{{ $patient->first_name }}"
                                         class="rounded-circle" width="35" height="35"
                                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                            </td>
                            <td>
                                <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong><br>
                                <small class="text-muted">{{ $patient->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $patient->nic }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td>
                                @php $status = $patient->user->status ?? 'pending'; @endphp
                                @if($status == 'active')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                @elseif($status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $patient->city ?? '-' }}</td>
                            <td><small>{{ $patient->created_at->format('Y-m-d') }}</small></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.patients.show', $patient->id) }}"
                                        class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}"
                                        class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($status == 'pending')
                                        <button onclick="approvePatient({{ $patient->id }}, '{{ addslashes($patient->first_name) }}')"
                                            class="btn btn-success" title="Approve" data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectPatient({{ $patient->id }}, '{{ addslashes($patient->first_name) }}')"
                                            class="btn btn-danger" title="Reject" data-bs-toggle="tooltip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($status == 'active')
                                        <button onclick="suspendPatient({{ $patient->id }}, '{{ addslashes($patient->first_name) }}')"
                                            class="btn btn-secondary" title="Suspend" data-bs-toggle="tooltip">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @elseif($status == 'suspended' || $status == 'rejected')
                                        <button onclick="activatePatient({{ $patient->id }}, '{{ addslashes($patient->first_name) }}')"
                                            class="btn btn-success" title="Activate" data-bs-toggle="tooltip">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                    <button onclick="deletePatient({{ $patient->id }}, '{{ addslashes($patient->first_name) }}')"
                                        class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No patients found</h5>
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
                    Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} entries
                </div>
                <div>
                    {{ $patients->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete form for JS submit -->
    <form id="deletePatientForm" method="POST" style="display: none;">
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
    .stat-summary-primary .stat-icon { background: rgba(66, 133, 244, .1); color: #4285F4; }
    .stat-summary-success .stat-icon { background: rgba(52, 168, 83, .1); color: #34A853; }
    .stat-summary-warning .stat-icon { background: rgba(251, 188, 5, .1); color: #FBBC05; }
    .stat-summary-danger .stat-icon { background: rgba(234, 67, 53, .1); color: #EA4335; }
    .stat-summary .stat-details h6 { margin: 0; font-size: 0.875rem; color: #666; font-weight: 500; }
    .stat-summary .stat-details h4 { margin: 0; font-size: 1.75rem; font-weight: 700; color: #333; }
    .table-responsive { overflow-x: auto; }
    .data-table { width: 100%; margin-bottom: 1rem; background-color: transparent; }
    .data-table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 0.75rem; vertical-align: middle; }
    .data-table tbody td { padding: 0.75rem; vertical-align: middle; border-top: 1px solid #dee2e6; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }

    /* SweetAlert2 Responsive Styles */
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

    function approvePatient(id, name) {
        Swal.fire({
            title: 'Approve Patient?',
            text: name,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                return fetch(`/admin/patients/${id}/approve`, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    if (!data.success) throw new Error(data.message || 'Approval failed');
                    return data;
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({ icon: 'success', title: 'Approved!' }).then(() => location.reload());
            }
        });
    }

    function rejectPatient(id, name) {
        Swal.fire({
            title: 'Reject Patient?',
            text: name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                return fetch(`/admin/patients/${id}/reject`, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    if (!data.success) throw new Error(data.message || 'Reject failed');
                    return data;
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({ icon: 'success', title: 'Patient Rejected!' }).then(() => location.reload());
            }
        });
    }

    function suspendPatient(id, name) {
        Swal.fire({
            title: 'Suspend Patient?',
            text: name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Suspend',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                return fetch(`/admin/patients/${id}/suspend`, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    if (!data.success) throw new Error(data.message || 'Suspend failed');
                    return data;
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({ icon: 'success', title: 'Patient Suspended!' }).then(() => location.reload());
            }
        });
    }

    function activatePatient(id, name) {
        Swal.fire({
            title: 'Activate Patient?',
            text: name,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Activate',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                return fetch(`/admin/patients/${id}/activate`, {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    if (!data.success) throw new Error(data.message || 'Activate failed');
                    return data;
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({ icon: 'success', title: 'Patient Activated!' }).then(() => location.reload());
            }
        });
    }

    function deletePatient(id, name) {
        Swal.fire({
            title: 'Delete Patient?',
            html: `<small>${name}</small><br><small class="text-danger">This action cannot be undone!</small>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deletePatientForm');
                form.action = `/admin/patients/${id}`;
                form.submit();
            }
        });
    }

    // Tooltip initialization
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
