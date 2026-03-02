{{-- resources/views/admin/announcements/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Announcements')

@section('page-title', 'Announcements Management')

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

    {{-- Header + quick stats (similar height to payments filters card) --}}
    <div class="dashboard-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-bullhorn me-2"></i>All Announcements
            </h6>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Announcement
            </a>
        </div>
        <div class="card-body">
            {{-- Filters Row (same compact form style used in payments) --}}
            <form action="{{ route('admin.announcements.index') }}" method="GET" class="row g-3 mb-2">
                <div class="col-md-5">
                    <label class="form-label form-label-sm mb-1">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by title or content..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $t)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Status</label>
                    <select name="active" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100 me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table card (same style as admin payments table) --}}
    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table table-hover">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Active</th>
                            <th>Start</th>
                            <th>End</th>
                            <th width="220" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>
                                    <strong>{{ $a->title }}</strong>
                                    <div class="text-muted small">
                                        Publisher: {{ $a->publisher_type }} #{{ $a->publisher_id }}
                                    </div>
                                    @if($a->image_path)
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/'.$a->image_path) }}"
                                               target="_blank"
                                               class="small">
                                                <i class="fas fa-image"></i> View image
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $a->announcement_type)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($a->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-ban"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>{{ optional($a->start_date)->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ optional($a->end_date)->format('Y-m-d') ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.announcements.show', $a) }}"
                                           class="btn btn-info"
                                           title="View"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.announcements.edit', $a) }}"
                                           class="btn btn-warning"
                                           title="Edit"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form method="POST"
                                              action="{{ url('admin/announcements/'.$a->id.'/toggle') }}"
                                              class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-secondary"
                                                    title="Toggle Active"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.announcements.destroy', $a) }}"
                                              class="d-inline"
                                              onsubmit="return confirmDelete('{{ addslashes($a->title) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    title="Delete"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-bullhorn fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted mb-1">No announcements found</h5>
                                    <p class="text-muted mb-0">
                                        Try changing filters or search text.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($announcements->hasPages())
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $announcements->firstItem() ?? 0 }}
                        to {{ $announcements->lastItem() ?? 0 }}
                        of {{ $announcements->total() }} entries
                    </div>
                    <div>
                        {{ $announcements->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .data-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.75rem;
            vertical-align: middle;
        }
        .data-table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
<script>
    function confirmDelete(name) {
        return confirm('Delete announcement "' + name + '" ?');
    }
</script>
@endpush
