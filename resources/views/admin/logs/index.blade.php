{{-- resources/views/admin/logs/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'System Activity Logs')
@section('page-title', 'System Activity Logs')

@section('content')
<div class="row">
    <div class="col-lg-12">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i> Activity Logs
                </h6>
                <span class="text-muted small">
                    Total: {{ $logs->total() }}
                </span>
            </div>

            <div class="card-body">
                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.logs.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">User ID</label>
                        <input type="text"
                               name="user_id"
                               value="{{ $userId }}"
                               class="form-control form-control-sm"
                               placeholder="e.g. 1">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Action</label>
                        <input type="text"
                               name="action"
                               value="{{ $action }}"
                               class="form-control form-control-sm"
                               placeholder="login, appointment_created...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Module / Keyword</label>
                        <input type="text"
                               name="module"
                               value="{{ $module }}"
                               class="form-control form-control-sm"
                               placeholder="appointments, payments...">
                    </div>

                    <div class="col-md-3"></div>

                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date"
                               name="date_from"
                               value="{{ $dateFrom }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date"
                               name="date_to"
                               value="{{ $dateTo }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        @if(request()->hasAny(['user_id','action','module','date_from','date_to']))
                            <a href="{{ route('admin.logs.index') }}"
                               class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Result info --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        Showing
                        <strong>{{ $logs->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $logs->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $logs->total() }}</strong>
                        entries
                    </small>
                </div>

                {{-- Logs table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th style="width: 130px;">IP Address</th>
                                <th style="width: 170px;">Date</th>
                                <th style="width: 80px;" class="text-center">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $log->user->email }}</span>
                                                <small class="text-muted">
                                                    ID: {{ $log->user_id }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td style="max-width: 320px;">
                                        <small class="text-muted d-block">
                                            {{ \Illuminate\Support\Str::limit($log->description, 120) }}
                                        </small>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-globe-asia me-1"></i>
                                            {{ \Illuminate\Support\Str::limit($log->user_agent, 80) }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $log->ip_address }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small>{{ $log->created_at?->format('Y-m-d H:i') }}</small>
                                            @if($log->created_at)
                                                <small class="text-muted">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.logs.show', $log->id) }}"
                                           class="btn btn-outline-info btn-sm"
                                           title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3 d-block"></i>
                                        <h6 class="text-muted mb-1">No activity logs found</h6>
                                        <p class="text-muted small mb-0">
                                            Try adjusting filters or check back later after more actions are performed.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                        </small>
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
