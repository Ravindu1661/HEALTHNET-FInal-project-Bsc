@extends('admin.layouts.master')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

{{-- Alerts --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i> Please fix the errors.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="dashboard-card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">
      <i class="fas fa-bullhorn me-2"></i>All Announcements
    </h6>

    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
      <i class="fas fa-plus"></i> Create
    </a>
  </div>

  <div class="card-body">

    {{-- Filters --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.announcements.index') }}">
      <div class="col-md-5">
        <div class="input-group input-group-sm">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input type="text"
                 name="search"
                 class="form-control"
                 placeholder="Search title/content..."
                 value="{{ request('search') }}">
        </div>
      </div>

      <div class="col-md-3">
        <select name="type" class="form-select form-select-sm">
          <option value="">All Types</option>
          @foreach(\App\Models\Announcement::types() as $t)
            <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <select name="active" class="form-select form-select-sm">
          <option value="">All</option>
          <option value="1" @selected(request('active') === '1')>Active</option>
          <option value="0" @selected(request('active') === '0')>Inactive</option>
        </select>
      </div>

      <div class="col-md-2">
        <button class="btn btn-primary btn-sm w-100">
          <i class="fas fa-filter"></i> Filter
        </button>
      </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="table data-table table-hover">
        <thead>
          <tr>
            <th width="60">#</th>
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
                    <a href="{{ asset('storage/'.$a->image_path) }}" target="_blank" class="small">
                      <i class="fas fa-image"></i> View image
                    </a>
                  </div>
                @endif
              </td>

              <td>
                <span class="badge bg-info">{{ ucfirst($a->announcement_type) }}</span>
              </td>

              <td>
                @if($a->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>

              <td>{{ optional($a->start_date)->format('Y-m-d') ?? '-' }}</td>
              <td>{{ optional($a->end_date)->format('Y-m-d') ?? '-' }}</td>

              <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a class="btn btn-info"
                     title="View"
                     href="{{ route('admin.announcements.show', $a) }}">
                    <i class="fas fa-eye"></i>
                  </a>

                  <a class="btn btn-warning"
                     title="Edit"
                     href="{{ route('admin.announcements.edit', $a) }}">
                    <i class="fas fa-edit"></i>
                  </a>

                  {{-- Toggle Active (redirect based controller method) --}}
                  <form method="POST" action="{{ url('admin/announcements/'.$a->id.'/toggle') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary" title="Toggle Active">
                      <i class="fas fa-toggle-on"></i>
                    </button>
                  </form>

                  {{-- Delete --}}
                  <form method="POST"
                        action="{{ route('admin.announcements.destroy', $a) }}"
                        onsubmit="return confirm('Delete announcement: {{ addslashes($a->title) }} ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">
                No announcements found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div class="text-muted small">
        Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }}
        of {{ $announcements->total() }} entries
      </div>

      <div>
        {{ $announcements->appends(request()->query())->links() }}
      </div>
    </div>

  </div>
</div>
@endsection
