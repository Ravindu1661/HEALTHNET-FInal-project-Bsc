@extends('admin.layouts.master')

@section('title', 'Users Management')

@section('page-title', 'Users Management')

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
            <h6><i class="fas fa-users me-2"></i>All Users</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="user_type" class="form-select form-select-sm">
                        <option value="">All User Types</option>
                        <option value="patient" {{ request('user_type') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="doctor" {{ request('user_type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="hospital" {{ request('user_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                        <option value="laboratory" {{ request('user_type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="pharmacy" {{ request('user_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                        <option value="medical_centre" {{ request('user_type') == 'medical_centre' ? 'selected' : '' }}>Medical Centre</option>
                        <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
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
                            <h6>Total Users</h6>
                            <h4>{{ $users->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-success">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-details">
                            <h6>Active Users</h6>
                            <h4>{{ $users->where('status', 'active')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-warning">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-details">
                            <h6>Pending</h6>
                            <h4>{{ $users->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-summary stat-summary-danger">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <div class="stat-details">
                            <h6>Suspended</h6>
                            <h4>{{ $users->where('status', 'suspended')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>Email Verified</th>
                            <th>Registered</th>
                            <th width="200" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">
                                        <img src="{{ $user->profile_image_url }}"
                                            alt="{{ $user->email }}"
                                            class="rounded-circle"
                                            width="30"
                                            height="30"
                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    </div>
                                    <div>
                                        <strong>{{ $user->email }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->user_type }}">
                                    <i class="fas fa-{{
                                        $user->user_type == 'patient' ? 'user-injured' :
                                        ($user->user_type == 'doctor' ? 'user-md' :
                                        ($user->user_type == 'hospital' ? 'hospital' :
                                        ($user->user_type == 'laboratory' ? 'flask' :
                                        ($user->user_type == 'pharmacy' ? 'pills' :
                                        ($user->user_type == 'medical_centre' ? 'clinic-medical' : 'user-shield')))))
                                    }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                                </span>
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                @elseif($user->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($user->status == 'suspended')
                                    <span class="badge bg-danger"><i class="fas fa-ban"></i> Suspended</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                    <span class="text-muted"><i class="fas fa-times-circle"></i> Not Verified</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="text-center">
    <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('admin.users.show', $user->id) }}"
           class="btn btn-info"
           title="View Details"
           data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>

        <a href="{{ route('admin.users.edit', $user->id) }}"
           class="btn btn-warning"
           title="Edit User"
           data-bs-toggle="tooltip">
            <i class="fas fa-edit"></i>
        </a>

        @if($user->id != auth()->id())
            @if($user->status == 'active')
                <button onclick="suspendUser({{ $user->id }}, '{{ $user->email }}')"
                        class="btn btn-secondary"
                        title="Suspend User"
                        data-bs-toggle="tooltip">
                    <i class="fas fa-ban"></i>
                </button>
            @elseif($user->status == 'suspended' || $user->status == 'pending')
                <button onclick="activateUser({{ $user->id }}, '{{ $user->email }}')"
                        class="btn btn-success"
                        title="Activate User"
                        data-bs-toggle="tooltip">
                    <i class="fas fa-check"></i>
                </button>
            @endif

            <button onclick="deleteUser({{ $user->id }}, '{{ $user->email }}')"
                    class="btn btn-danger"
                    title="Delete User"
                    data-bs-toggle="tooltip">
                <i class="fas fa-trash"></i>
            </button>
        @else
            <button class="btn btn-secondary"
                    disabled
                    title="Cannot modify own account"
                    data-bs-toggle="tooltip">
                <i class="fas fa-lock"></i>
            </button>
        @endif
    </div>
</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No users found</h5>
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
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
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

    .badge-patient { background: #e3f2fd; color: #1976d2; }
    .badge-doctor { background: #e8f5e9; color: #388e3c; }
    .badge-hospital { background: #fff3e0; color: #f57c00; }
    .badge-laboratory { background: #f3e5f5; color: #7b1fa2; }
    .badge-pharmacy { background: #e0f2f1; color: #00796b; }
    .badge-medical_centre { background: #fce4ec; color: #c2185b; }
    .badge-admin { background: #e8eaf6; color: #3f51b5; }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .user-avatar-sm {
        width: 32px;
        height: 32px;
        overflow: hidden;
        border-radius: 50%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Compact Toast notifications
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true,
        customClass: {
            popup: 'swal2-toast-compact'
        }
    });

    // Suspend User - Compact
    function suspendUser(userId, userEmail) {
        Swal.fire({
            title: 'Suspend?',
            text: userEmail,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                return fetch(`/admin/users/${userId}/suspend`, {
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
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({
                    icon: 'success',
                    title: 'Suspended!'
                }).then(() => location.reload());
            }
        });
    }

    // Activate User - Compact
    function activateUser(userId, userEmail) {
        Swal.fire({
            title: 'Activate?',
            text: userEmail,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: () => {
                return fetch(`/admin/users/${userId}/activate`, {
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
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({
                    icon: 'success',
                    title: 'Activated!'
                }).then(() => location.reload());
            }
        });
    }

    // Delete User - Compact
    function deleteUser(userId, userEmail) {
        Swal.fire({
            title: 'Delete?',
            html: `<small>${userEmail}</small><br><small class="text-danger">Cannot be undone!</small>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Toast.fire({
                    icon: 'info',
                    title: 'Deleting...'
                });

                const form = document.getElementById('deleteForm');
                form.action = `/admin/users/${userId}`;
                form.submit();
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
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
