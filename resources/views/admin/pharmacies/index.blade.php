@extends('admin.layouts.master')

@section('title', 'Pharmacies Management')
@section('page-title', 'Pharmacies Management')

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
            <h6><i class="fas fa-pills me-2"></i>All Pharmacies</h6>
            <a href="{{ route('admin.pharmacies.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Pharmacy
            </a>
        </div>
        <div class="card-body">
            <!-- Filters Form -->
            <form action="{{ route('admin.pharmacies.index') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by name, reg. number, city..." value="{{ request('search') }}">
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
                        <div class="stat-icon"><i class="fas fa-pills"></i></div>
                        <div class="stat-details">
                            <h6>Total Pharmacies</h6>
                            <h4>{{ $pharmacies->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-success">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h6>Approved</h6>
                            <h4>{{ \App\Models\Pharmacy::where('status','approved')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-warning">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-details">
                            <h6>Pending</h6>
                            <h4>{{ \App\Models\Pharmacy::where('status','pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-danger">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <div class="stat-details">
                            <h6>Suspended</h6>
                            <h4>{{ \App\Models\Pharmacy::where('status','suspended')->count() }}</h4>
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
                            <th>Pharmacy</th>
                            <th>Reg. Number</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Rating</th>
                            <th width="200" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $pharmacy->id }}</td>
                            <td>
                                <div class="user-avatar-sm me-2">
                                    <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                                         alt="{{ $pharmacy->name }}" width="35" height="35"
                                         class="rounded-circle" onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
                                    <strong>{{ $pharmacy->name }}</strong><br>
                                    <small class="text-muted">Pharmacist: {{ $pharmacy->pharmacist_name }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $pharmacy->registration_number }}</span>
                                <br>
                                <small class="text-muted">Lic #: {{ $pharmacy->pharmacist_license }}</small>
                            </td>
                            <td>{{ $pharmacy->city }}</td>
                            <td>
                                @if($pharmacy->status == 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($pharmacy->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($pharmacy->status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $pharmacy->phone }}</td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($pharmacy->rating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <small>({{ number_format($pharmacy->rating ?? 0,1) }})</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.pharmacies.show', $pharmacy->id) }}"
                                       class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}"
                                       class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($pharmacy->status == 'pending')
                                        <button onclick="approvePharmacy({{ $pharmacy->id }}, '{{ addslashes($pharmacy->name) }}')"
                                            class="btn btn-success" title="Approve" data-bs-toggle="tooltip">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectPharmacy({{ $pharmacy->id }}, '{{ addslashes($pharmacy->name) }}')"
                                            class="btn btn-danger" title="Reject" data-bs-toggle="tooltip">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($pharmacy->status == 'approved')
                                        <button onclick="suspendPharmacy({{ $pharmacy->id }}, '{{ addslashes($pharmacy->name) }}')"
                                            class="btn btn-secondary" title="Suspend" data-bs-toggle="tooltip">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @elseif($pharmacy->status == 'suspended' || $pharmacy->status == 'rejected')
                                        <button onclick="activatePharmacy({{ $pharmacy->id }}, '{{ addslashes($pharmacy->name) }}')"
                                            class="btn btn-success" title="Activate" data-bs-toggle="tooltip">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                    <button onclick="deletePharmacy({{ $pharmacy->id }}, '{{ addslashes($pharmacy->name) }}')"
                                        class="btn btn-danger" title="Delete" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-pills fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No pharmacies found</h5>
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
                    Showing {{ $pharmacies->firstItem() ?? 0 }} to {{ $pharmacies->lastItem() ?? 0 }} of {{ $pharmacies->total() }} entries
                </div>
                <div>
                    {{ $pharmacies->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete form for JS submit -->
    <form id="deletePharmacyForm" method="POST" style="display: none;">
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

    function approvePharmacy(id, name) {
        Swal.fire({
            title: 'Approve Pharmacy?',
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
                return fetch(`/admin/pharmacies/${id}/approve`, {
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

    function rejectPharmacy(id, name) {
        Swal.fire({
            title: 'Reject Pharmacy?',
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
                return fetch(`/admin/pharmacies/${id}/reject`, {
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
                Toast.fire({ icon: 'success', title: 'Pharmacy Rejected!' }).then(() => location.reload());
            }
        });
    }

    function suspendPharmacy(id, name) {
        Swal.fire({
            title: 'Suspend Pharmacy?',
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
                return fetch(`/admin/pharmacies/${id}/suspend`, {
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
                Toast.fire({ icon: 'success', title: 'Pharmacy Suspended!' }).then(() => location.reload());
            }
        });
    }

    function activatePharmacy(id, name) {
        Swal.fire({
            title: 'Activate Pharmacy?',
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
                return fetch(`/admin/pharmacies/${id}/activate`, {
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
                Toast.fire({ icon: 'success', title: 'Pharmacy Activated!' }).then(() => location.reload());
            }
        });
    }

    function deletePharmacy(id, name) {
        Swal.fire({
            title: 'Delete Pharmacy?',
            html: `<small>${name}</small><br><small class="text-danger">This action cannot be undone!</small>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deletePharmacyForm');
                form.action = `/admin/pharmacies/${id}`;
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
