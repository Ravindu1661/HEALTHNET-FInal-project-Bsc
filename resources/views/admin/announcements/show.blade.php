@extends('admin.layouts.master')

@section('title', 'Announcement Details')
@section('page-title', 'Announcement Details')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="dashboard-card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">
      <i class="fas fa-bullhorn me-2"></i>Announcement #{{ $announcement->id }}
    </h6>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i> Edit
      </a>

      <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="row g-3">
      {{-- Left: Main details --}}
      <div class="col-lg-8">
        <div class="mb-3">
          <h4 class="mb-1">{{ $announcement->title }}</h4>

          <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-info">{{ ucfirst($announcement->announcement_type) }}</span>

            @if($announcement->is_active)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-secondary">Inactive</span>
            @endif

            @if($announcement->start_date || $announcement->end_date)
              <span class="badge bg-primary">
                {{ optional($announcement->start_date)->format('Y-m-d') ?? '-' }}
                →
                {{ optional($announcement->end_date)->format('Y-m-d') ?? '-' }}
              </span>
            @endif
          </div>
        </div>

        <div class="dashboard-card">
          <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>Content</h6>
          </div>
          <div class="card-body">
            {{-- content HTML allow නම් {!! !!} (ඔයා store කරන එක plain text නම් {{ }} use කරන්න) --}}
            <div class="text-muted" style="white-space: pre-line;">
              {!! nl2br(e($announcement->content)) !!}
            </div>
          </div>
        </div>
      </div>

      {{-- Right: Meta --}}
      <div class="col-lg-4">
        <div class="dashboard-card mb-3">
          <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Details</h6>
          </div>

          <div class="card-body">
            <table class="table table-sm table-borderless mb-0">
              <tr>
                <th width="120">Publisher</th>
                <td>{{ $announcement->publisher_type }} #{{ $announcement->publisher_id }}</td>
              </tr>
              <tr>
                <th>Start</th>
                <td>{{ optional($announcement->start_date)->format('Y-m-d') ?? '-' }}</td>
              </tr>
              <tr>
                <th>End</th>
                <td>{{ optional($announcement->end_date)->format('Y-m-d') ?? '-' }}</td>
              </tr>
              <tr>
                <th>Created</th>
                <td>{{ optional($announcement->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
              </tr>
              <tr>
                <th>Updated</th>
                <td>{{ optional($announcement->updated_at)->format('Y-m-d H:i') ?? '-' }}</td>
              </tr>
            </table>
          </div>
        </div>

        @if($announcement->image_path)
          <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h6 class="mb-0"><i class="fas fa-image me-2"></i>Image</h6>
              <a class="btn btn-primary btn-sm" target="_blank" href="{{ asset('storage/'.$announcement->image_path) }}">
                <i class="fas fa-external-link-alt"></i> Open
              </a>
            </div>
            <div class="card-body text-center">
              <img
                src="{{ asset('storage/'.$announcement->image_path) }}"
                alt="Announcement image"
                class="img-fluid rounded"
                style="max-height: 260px; object-fit: cover;"
                onerror="this.style.display='none';"
              >
            </div>
          </div>
        @endif

        <div class="mt-3 d-flex gap-2">
          <form method="POST" action="{{ url('admin/announcements/'.$announcement->id.'/toggle') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm w-100">
              <i class="fas fa-toggle-on"></i> Toggle Active
            </button>
          </form>

          <form method="POST"
                action="{{ route('admin.announcements.destroy', $announcement) }}"
                onsubmit="return confirm('Delete announcement: {{ addslashes($announcement->title) }} ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm w-100">
              <i class="fas fa-trash"></i> Delete
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
