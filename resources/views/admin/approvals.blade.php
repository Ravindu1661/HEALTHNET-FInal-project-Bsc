{{-- resources/views/admin/approvals.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Pending Approvals')
@section('page-title', 'Pending Approvals')

@section('content')
<div class="row">
    <div class="col-lg-12">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-clipboard-check me-2"></i> All Pending Approvals
                </h6>
                <span class="text-muted small">Doctors, Hospitals, Labs, Pharmacies, Medical Centres</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Registration</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pendingApprovals ?? [] as $approval)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $approval->type ?? 'secondary' }}">
                                        {{ ucfirst($approval->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $approval->name ?? 'N/A' }}</td>
                                <td>{{ $approval->registration_number ?? 'N/A' }}</td>
                                <td>{{ optional($approval->created_at)->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-success btn-action"
                                                onclick="approveRequest('{{ $approval->id }}', '{{ $approval->type }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-action"
                                                onclick="rejectRequest('{{ $approval->id }}', '{{ $approval->type }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn btn-info btn-action"
                                                onclick="viewDetails('{{ $approval->id }}', '{{ $approval->type }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No pending approvals</h5>
                                    <p class="text-muted mb-0">All providers are up to date.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
