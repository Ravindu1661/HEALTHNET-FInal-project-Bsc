@extends('admin.layouts.master')

@section('title', 'User Details')

@section('page-title', 'User Details')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <!-- User Header Card -->
            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="{{ $user->profile_image ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $user->email }}" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="col-md-7">
                            <h4 class="mb-2">
                                @if($profile && isset($profile->first_name))
                                    {{ $profile->first_name }} {{ $profile->last_name }}
                                @elseif($profile && isset($profile->name))
                                    {{ $profile->name }}
                                @else
                                    {{ $user->email }}
                                @endif
                            </h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                            </p>
                            <div class="d-flex gap-2">
                                <span class="badge badge-{{ $user->user_type }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                                </span>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($user->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($user->status == 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                @endif
                                
                                @if($user->email_verified_at)
                                    <span class="badge bg-info">Email Verified</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm mb-2 w-100">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Account Information -->
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h6><i class="fas fa-user-circle me-2"></i>Account Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th width="40%">User ID:</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>User Type:</th>
                                    <td>
                                        <span class="badge badge-{{ $user->user_type }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($user->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($user->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($user->status == 'suspended')
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email Verified:</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> 
                                                {{ $user->email_verified_at->format('M d, Y h:i A') }}
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
                                    <td>{{ $user->created_at->format('M d, Y') }} ({{ $user->created_at->diffForHumans() }})</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h6><i class="fas fa-id-card me-2"></i>Profile Information</h6>
                        </div>
                        <div class="card-body">
                            @if($profile)
                                <table class="table table-borderless mb-0">
                                    @if(isset($profile->first_name))
                                        <tr>
                                            <th width="40%">Full Name:</th>
                                            <td>{{ $profile->first_name }} {{ $profile->last_name }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->name))
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $profile->name }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->phone))
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $profile->phone }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->nic))
                                        <tr>
                                            <th>NIC:</th>
                                            <td>{{ $profile->nic }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->slmc_number))
                                        <tr>
                                            <th>SLMC Number:</th>
                                            <td><strong>{{ $profile->slmc_number }}</strong></td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->registration_number))
                                        <tr>
                                            <th>Registration #:</th>
                                            <td><strong>{{ $profile->registration_number }}</strong></td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->specialization))
                                        <tr>
                                            <th>Specialization:</th>
                                            <td>{{ $profile->specialization }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->gender))
                                        <tr>
                                            <th>Gender:</th>
                                            <td>{{ ucfirst($profile->gender) }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->date_of_birth))
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td>{{ \Carbon\Carbon::parse($profile->date_of_birth)->format('M d, Y') }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->blood_group))
                                        <tr>
                                            <th>Blood Group:</th>
                                            <td><span class="badge bg-danger">{{ $profile->blood_group }}</span></td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->address))
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $profile->address }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->city))
                                        <tr>
                                            <th>City:</th>
                                            <td>{{ $profile->city }}</td>
                                        </tr>
                                    @endif
                                    
                                    @if(isset($profile->rating))
                                        <tr>
                                            <th>Rating:</th>
                                            <td>
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $profile->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                                ({{ number_format($profile->rating, 2) }})
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            @else
                                <p class="text-muted text-center py-4">
                                    <i class="fas fa-info-circle"></i> No profile information available
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-md-12">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                @if($user->id != auth()->id())
                                    @if($user->status == 'active')
                                        <button onclick="suspendUserConfirm({{ $user->id }})" class="btn btn-warning btn-sm">
                                            <i class="fas fa-ban"></i> Suspend Account
                                        </button>
                                    @else
                                        <button onclick="activateUserConfirm({{ $user->id }})" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Activate Account
                                        </button>
                                    @endif
                                    
                                    <button onclick="deleteUserConfirm({{ $user->id }})" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit User
                                </a>
                                
                                <button onclick="window.print()" class="btn btn-secondary btn-sm">
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
    <form id="deleteUserForm" method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function suspendUserConfirm(userId) {
        Swal.fire({
            title: 'Suspend User?',
            text: "This user will not be able to access the system.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, suspend!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}/suspend`, {
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
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function activateUserConfirm(userId) {
        Swal.fire({
            title: 'Activate User?',
            text: "This user will be able to access the system.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, activate!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${userId}/activate`, {
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
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    }

    function deleteUserConfirm(userId) {
        Swal.fire({
            title: 'Delete User?',
            text: "This action cannot be undone!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteUserForm').submit();
            }
        });
    }
</script>
@endpush
