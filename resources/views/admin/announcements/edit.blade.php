@extends('admin.layouts.master')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i> Please fix the errors.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="dashboard-card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">
      <i class="fas fa-edit me-2"></i>Edit Announcement #{{ $announcement->id }}
    </h6>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i> View
      </a>

      <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="card-body">

    <form method="POST"
          action="{{ route('admin.announcements.update', $announcement) }}"
          enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Title <span class="text-danger">*</span></label>
          <input type="text"
                 name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title', $announcement->title) }}"
                 required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label">Type <span class="text-danger">*</span></label>
          <select name="announcement_type"
                  class="form-select @error('announcement_type') is-invalid @enderror"
                  required>
            @foreach($types as $t)
              <option value="{{ $t }}" @selected(old('announcement_type', $announcement->announcement_type) === $t)>
                {{ ucfirst($t) }}
              </option>
            @endforeach
          </select>
          @error('announcement_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label">Active</label>
          <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
            <option value="1" @selected((string)old('is_active', (int)$announcement->is_active) === '1')>Active</option>
            <option value="0" @selected((string)old('is_active', (int)$announcement->is_active) === '0')>Inactive</option>
          </select>
          @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label">Start Date</label>
          <input type="date"
                 name="start_date"
                 class="form-control @error('start_date') is-invalid @enderror"
                 value="{{ old('start_date', optional($announcement->start_date)->format('Y-m-d')) }}">
          @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label">End Date</label>
          <input type="date"
                 name="end_date"
                 class="form-control @error('end_date') is-invalid @enderror"
                 value="{{ old('end_date', optional($announcement->end_date)->format('Y-m-d')) }}">
          @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label class="form-label">Content <span class="text-danger">*</span></label>
          <textarea name="content"
                    rows="6"
                    class="form-control @error('content') is-invalid @enderror"
                    required>{{ old('content', $announcement->content) }}</textarea>
          @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Image (optional)</label>
          <input type="file"
                 name="image"
                 accept="image/*"
                 class="form-control @error('image') is-invalid @enderror">
          @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

          @if($announcement->image_path)
            <div class="mt-2">
              <a href="{{ asset('storage/'.$announcement->image_path) }}" target="_blank" class="small">
                <i class="fas fa-image"></i> Current image
              </a>
            </div>
          @endif
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
          </a>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update
          </button>
        </div>
      </div>
    </form>

  </div>
</div>
@endsection
