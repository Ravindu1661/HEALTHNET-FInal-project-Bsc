@extends('admin.layouts.master')

@section('title', 'Hospitals Management')
@section('page-title', 'Hospitals Management')

@section('content')
    <!-- Alerts (Success/Error) -->
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
            <h6><i class="fas fa-hospital me-2"></i>All Hospitals</h6>
            <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Hospital
            </a>
        </div>
        <div class="card-body">
            <!-- Filters Form -->
            <form action="{{ route('admin.hospitals.index') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, reg#, city..." value="{{ request('search') }}">
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
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="government" {{ request('type') == 'government' ? 'selected' : '' }}>Government</option>
                        <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Stats -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-primary">
                        <div class="stat-icon"><i class="fas fa-hospital"></i></div>
                        <div class="stat-details">
                            <h6>Total Hospitals</h6>
                            <h4>{{ $hospitals->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-success">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h6>Approved</h6>
                            <h4>{{ \App\Models\Hospital::where('status', 'approved')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-warning">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-details">
                            <h6>Pending</h6>
                            <h4>{{ \App\Models\Hospital::where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-danger">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <div class="stat-details">
                            <h6>Suspended</h6>
                            <h4>{{ \App\Models\Hospital::where('status', 'suspended')->count() }}</h4>
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
                            <th width="50">#</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Reg. Number</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Rating</th>
                            <th width="200" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hospitals as $hospital)
                        <tr>
                            <td>{{ $hospital->id }}</td>
                            <td>
                                @if($hospital->profile_image)
                                    <img src="{{ asset('storage/' . $hospital->profile_image) }}"
                                        alt="{{ $hospital->name }}"
                                        class="rounded-circle" width="35" height="35"
                                        onerror="this.src='{{ asset('images/default-hospital.png') }}'">
                                @else
                                    <img src="{{ asset('images/default-hospital.png') }}"
                                        alt="Default"
                                        class="rounded-circle" width="35" height="35">
                                @endif
                            </td>
                            <td>
                                <strong>{{ $hospital->name }}</strong><br>
                                <small class="text-muted">{{ $hospital->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $hospital->registration_number }}</span>
                            </td>
                            <td>{{ $hospital->city }}</td>
                            <td>
                                @if($hospital->status == 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($hospital->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($hospital->status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $hospital->phone }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($hospital->type) }}</span></td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($hospital->rating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <small>({{ number_format($hospital->rating ?? 0, 1) }})</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.hospitals.show', $hospital->id) }}"
                                        class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hospitals.edit', $hospital->id) }}"
                                        class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($hospital->status == 'pending')
                                        <button onclick="approveHospital({{ $hospital->id }}, '{{ addslashes($hospital->name) }}')"
                                            class="btn btn-success" title="Approve" data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectHospital({{ $hospital->id }}, '{{ addslashes($hospital->name) }}')"
                                            class="btn btn-danger" title="Reject" data-bs-toggle="tooltip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($hospital->status == 'approved')
                                        <button onclick="suspendHospital({{ $hospital->id }}, '{{ addslashes($hospital->name) }}')"
                                            class="btn btn-secondary" title="Suspend" data-bs-toggle="tooltip">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @elseif($hospital->status == 'suspended' || $hospital->status == 'rejected')
                                        <button onclick="activateHospital({{ $hospital->id }}, '{{ addslashes($hospital->name) }}')"
                                            class="btn btn-success" title="Activate" data-bs-toggle="tooltip">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteHospital({{ $hospital->id }}, '{{ addslashes($hospital->name) }}')"
                                        class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-hospital fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No hospitals found</h5>
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
                    Showing {{ $hospitals->firstItem() ?? 0 }} to {{ $hospitals->lastItem() ?? 0 }} of {{ $hospitals->total() }} entries
                </div>
                <div>
                    {{ $hospitals->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete form for JS submit -->
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

    function approveHospital(id, name) {
        Swal.fire({
            title: 'Approve Hospital?',
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
                return fetch(`/admin/hospitals/${id}/approve`, {
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

    function rejectHospital(id, name) {
        Swal.fire({
            title: 'Reject Hospital?',
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
                return fetch(`/admin/hospitals/${id}/reject`, {
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
                Toast.fire({ icon: 'success', title: 'Rejected!' }).then(() => location.reload());
            }
        });
    }

    function suspendHospital(id, name) {
        Swal.fire({
            title: 'Suspend Hospital?',
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
                return fetch(`/admin/hospitals/${id}/suspend`, {
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
                Toast.fire({ icon: 'success', title: 'Hospital Suspended!' }).then(() => location.reload());
            }
        });
    }

    function activateHospital(id, name) {
        Swal.fire({
            title: 'Activate Hospital?',
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
                return fetch(`/admin/hospitals/${id}/activate`, {
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
                Toast.fire({ icon: 'success', title: 'Hospital Activated!' }).then(() => location.reload());
            }
        });
    }

    function deleteHospital(id, name) {
        Swal.fire({
            title: 'Delete Hospital?',
            html: `<small>${name}</small><br><small class="text-danger">This action cannot be undone!</small>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/hospitals/${id}`;
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
