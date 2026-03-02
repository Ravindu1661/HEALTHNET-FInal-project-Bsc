{{-- resources/views/admin/announcements/create.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Create Announcement')

@section('page-title', 'Create Announcement')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-bullhorn me-2"></i>Announcement Information
                    </h6>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle"></i> Validation Errors</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.announcements.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            {{-- Title --}}
                            <div class="col-md-8">
                                <label class="form-label required">Title</label>
                                <input type="text"
                                       name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}"
                                       placeholder="Enter title"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="col-md-4">
                                <label class="form-label required">Type</label>
                                <select name="announcementtype"
                                        class="form-select @error('announcementtype') is-invalid @enderror"
                                        required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t }}"
                                            {{ old('announcementtype') === $t ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $t)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('announcementtype')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Content --}}
                            <div class="col-12">
                                <label class="form-label required">Content</label>
                                <textarea name="content"
                                          rows="6"
                                          class="form-control @error('content') is-invalid @enderror"
                                          placeholder="Write announcement details..."
                                          required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Dates with helper & min constraint --}}
                            <div class="col-md-4">
                                <label class="form-label">Start date</label>
                                <input type="date"
                                       id="startdate"
                                       name="startdate"
                                       class="form-control @error('startdate') is-invalid @enderror"
                                       value="{{ old('startdate') }}">
                                @error('startdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Optional. If you set a start date, the end date cannot be before it.
                                </small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">End date</label>
                                <input type="date"
                                       id="enddate"
                                       name="enddate"
                                       class="form-control @error('enddate') is-invalid @enderror"
                                       value="{{ old('enddate') }}">
                                @error('enddate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Optional. Must be the same or after the start date.
                                </small>
                            </div>

                            {{-- Active --}}
                            <div class="col-md-4">
                                <label class="form-label">Active</label>
                                <select name="isactive"
                                        class="form-select @error('isactive') is-invalid @enderror">
                                    <option value="1" {{ old('isactive', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('isactive') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('isactive')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Image --}}
                            <div class="col-12">
                                <label class="form-label">Image (optional)</label>
                                <input type="file"
                                       name="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    JPG/PNG recommended, max 2MB.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-label.required::after {
            content: '*';
            color: #dc3545;
            margin-left: 3px;
            font-size: 0.85em;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startInput = document.getElementById('startdate');
        const endInput   = document.getElementById('enddate');

        function syncEndMin() {
            if (startInput.value) {
                endInput.min = startInput.value;
            } else {
                endInput.removeAttribute('min');
            }
        }

        startInput.addEventListener('change', function () {
            syncEndMin();
            if (endInput.value && endInput.value < startInput.value) {
                endInput.value = startInput.value;
            }
        });

        // initial state (for old() values after validation error)
        syncEndMin();
    });
</script>
@endpush
