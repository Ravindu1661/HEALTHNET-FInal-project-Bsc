{{-- resources/views/medical_centre/announcements/edit.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<style>
.mc-page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.mc-page-title { font-size:1.25rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.mc-page-sub   { font-size:.82rem; color:var(--text-muted); margin:0; }
.mc-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.48rem 1rem; border-radius:9px; border:none; font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); text-decoration:none; }
.mc-btn-back { background:#f4f7fb; color:var(--text-muted); }
.mc-btn-back:hover { background:#e9ecef; color:var(--text-dark); }

.ann-form-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; max-width:780px; margin:0 auto; }
.ann-form-head { padding:.9rem 1.25rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; gap:.5rem; }
.ann-form-head h6 { font-size:.9rem; font-weight:800; color:var(--text-dark); margin:0; }
.ann-form-head i  { color:var(--mc-primary); }
.ann-form-body { padding:1.5rem 1.25rem; }
.ann-form-group { margin-bottom:1.1rem; }
.ann-form-label { display:block; font-size:.75rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.4rem; }
.ann-form-label span { color:#e74c3c; margin-left:2px; }
.ann-form-control { width:100%; padding:.55rem .85rem; border-radius:9px; border:1.5px solid var(--border); font-size:.83rem; font-weight:600; color:var(--text-dark); background:#fff; font-family:inherit; outline:none; transition:border-color .2s; box-sizing:border-box; }
.ann-form-control:focus { border-color:var(--mc-primary); }
.ann-form-control.is-invalid { border-color:#e74c3c; }
textarea.ann-form-control { resize:vertical; min-height:130px; }
.ann-form-error { font-size:.72rem; color:#e74c3c; margin-top:.3rem; font-weight:600; }
.ann-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(max-width:600px){ .ann-form-row { grid-template-columns:1fr; } }

.ann-img-upload-wrap { border:2px dashed var(--border); border-radius:10px; background:#f8fbff; overflow:hidden; transition:border-color .2s; margin-top:.4rem; }
.ann-img-upload-wrap:hover { border-color:var(--mc-primary); }
.ann-img-preview { width:100%; height:160px; display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; }
.ann-img-preview img { width:100%; height:100%; object-fit:cover; }
.ann-img-preview-placeholder { text-align:center; color:var(--text-muted); }
.ann-img-preview-placeholder i { font-size:2rem; display:block; margin-bottom:.35rem; opacity:.3; }
.ann-img-preview-placeholder span { font-size:.75rem; font-weight:600; }

.current-img-row { display:flex; align-items:center; gap:.75rem; background:#f8fbff; border-radius:9px; border:1px solid var(--border); padding:.65rem .9rem; margin-bottom:.5rem; }
.current-img-row img { width:50px; height:50px; object-fit:cover; border-radius:8px; border:2px solid var(--border); flex-shrink:0; }
.current-img-row span { font-size:.75rem; color:var(--text-muted); font-weight:600; flex:1; }
.remove-img-label { display:flex; align-items:center; gap:.3rem; font-size:.75rem; font-weight:700; color:#e74c3c; cursor:pointer; white-space:nowrap; }

.ann-toggle-row { display:flex; align-items:center; justify-content:space-between; background:#f8fbff; border-radius:10px; border:1.5px solid var(--border); padding:.75rem 1rem; }
.ann-toggle-label { font-size:.85rem; font-weight:700; color:var(--text-dark); margin:0; cursor:pointer; }
.ann-toggle-sub   { font-size:.72rem; color:var(--text-muted); display:block; margin-top:.1rem; }
.form-check-input { width:2.2rem !important; height:1.15rem; cursor:pointer; flex-shrink:0; }

.ann-form-footer { padding:1rem 1.25rem; border-top:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:flex-end; gap:.75rem; }
.ann-submit-btn { padding:.55rem 1.5rem; border-radius:9px; border:none; font-size:.83rem; font-weight:800; cursor:pointer; font-family:inherit; transition:var(--transition); display:inline-flex; align-items:center; gap:.4rem; text-decoration:none; }
.ann-submit-primary { background:var(--mc-primary); color:#fff; }
.ann-submit-primary:hover { background:var(--mc-secondary); }
.ann-submit-cancel { background:#f4f7fb; color:var(--text-muted); }
.ann-submit-cancel:hover { background:#e9ecef; color:var(--text-dark); }
.ann-char-counter { font-size:.7rem; color:var(--text-muted); font-weight:600; text-align:right; margin-top:.25rem; }
</style>

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-edit me-2" style="color:var(--mc-primary);"></i>Edit Announcement
        </h4>
        <p class="mc-page-sub">{{ $announcement->title }}</p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('medical_centre.announcements.show', $announcement->id) }}" class="mc-btn mc-btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-dismissible fade show d-flex align-items-start gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;" role="alert">
        <i class="fas fa-exclamation-circle mt-1"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin:.3rem 0 0;padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li style="font-size:.78rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="ann-form-card">
    <div class="ann-form-head">
        <i class="fas fa-edit"></i>
        <h6>Edit Announcement Details</h6>
    </div>

    <form method="POST"
          action="{{ route('medical_centre.announcements.update', $announcement->id) }}"
          enctype="multipart/form-data"
          id="editForm">
        @csrf
        @method('PUT')

        <div class="ann-form-body">

            {{-- Title --}}
            <div class="ann-form-group">
                <label class="ann-form-label">Title <span>*</span></label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $announcement->title) }}"
                       maxlength="255"
                       class="ann-form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                       placeholder="Enter announcement title...">
                @error('title')
                    <div class="ann-form-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Type --}}
            <div class="ann-form-group">
                <label class="ann-form-label">Announcement Type <span>*</span></label>
                <select name="announcement_type"
                        class="ann-form-control {{ $errors->has('announcement_type') ? 'is-invalid' : '' }}">
                    <option value="">— Select Type —</option>
                    @foreach([
                        'health_camp'   => '🏥 Health Camp',
                        'special_offer' => '🏷️ Special Offer',
                        'new_service'   => '⭐ New Service',
                        'emergency'     => '🚨 Emergency',
                        'general'       => '📢 General',
                    ] as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('announcement_type', $announcement->announcement_type) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('announcement_type')
                    <div class="ann-form-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Content --}}
            <div class="ann-form-group">
                <label class="ann-form-label">Content <span>*</span></label>
                <textarea name="content"
                          id="contentArea"
                          maxlength="2000"
                          class="ann-form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"
                          placeholder="Write your announcement content here..."
                          oninput="updateCounter(this)">{{ old('content', $announcement->content) }}</textarea>
                <div class="ann-char-counter">
                    <span id="charCount">{{ strlen(old('content', $announcement->content ?? '')) }}</span> / 2000
                </div>
                @error('content')
                    <div class="ann-form-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Dates --}}
            <div class="ann-form-row">
                <div class="ann-form-group">
                    <label class="ann-form-label">Start Date
                        <small style="text-transform:none;font-weight:600;color:var(--text-muted);">(optional)</small>
                    </label>
                    <input type="date"
                           name="start_date"
                           value="{{ old('start_date', $announcement->start_date ? \Carbon\Carbon::parse($announcement->start_date)->format('Y-m-d') : '') }}"
                           class="ann-form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}">
                    @error('start_date') <div class="ann-form-error">{{ $message }}</div> @enderror
                </div>
                <div class="ann-form-group">
                    <label class="ann-form-label">End Date
                        <small style="text-transform:none;font-weight:600;color:var(--text-muted);">(optional)</small>
                    </label>
                    <input type="date"
                           name="end_date"
                           value="{{ old('end_date', $announcement->end_date ? \Carbon\Carbon::parse($announcement->end_date)->format('Y-m-d') : '') }}"
                           class="ann-form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}">
                    @error('end_date') <div class="ann-form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Image --}}
            <div class="ann-form-group">
                <label class="ann-form-label">
                    Banner Image
                    <small style="text-transform:none;font-weight:600;color:var(--text-muted);">
                        (JPG / PNG / WEBP · max 2MB · optional)
                    </small>
                </label>

                @if($announcement->image_path)
                    <div class="current-img-row">
                        <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="Current Image">
                        <span>Current image — upload a new one to replace it</span>
                        <label class="remove-img-label">
                            <input type="checkbox" name="remove_image" value="1"
                                   id="removeImg" onchange="toggleRemove(this)">
                            Remove
                        </label>
                    </div>
                @endif

                <input type="file"
                       name="image"
                       id="imageInput"
                       accept="image/jpeg,image/png,image/webp"
                       class="ann-form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                       onchange="previewImage(this)"
                       style="margin-bottom:.4rem;">
                @error('image') <div class="ann-form-error">{{ $message }}</div> @enderror

                <div class="ann-img-upload-wrap" onclick="document.getElementById('imageInput').click()">
                    <div class="ann-img-preview" id="imgPreview">
                        @if($announcement->image_path)
                            <img src="{{ asset('storage/' . $announcement->image_path) }}"
                                 alt="Preview" id="previewImg" style="display:block;">
                        @else
                            <img src="" alt="Preview" id="previewImg" style="display:none;">
                            <div class="ann-img-preview-placeholder" id="previewPlaceholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to select image</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Toggle --}}
            <div class="ann-form-group" style="margin-bottom:0;">
                <div class="ann-toggle-row">
                    <div>
                        <label for="is_active" class="ann-toggle-label">
                            <i class="fas fa-eye me-1" style="color:var(--mc-primary);"></i>
                            Active / Published
                        </label>
                        <span class="ann-toggle-sub">Announcement visible to patients</span>
                    </div>
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                </div>
            </div>

        </div>

        <div class="ann-form-footer">
            <a href="{{ route('medical_centre.announcements.show', $announcement->id) }}"
               class="ann-submit-btn ann-submit-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ann-submit-btn ann-submit-primary" id="submitBtn">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>

    </form>
</div>

<script>
function previewImage(input) {
    const img         = document.getElementById('previewImg');
    const placeholder = document.getElementById('previewPlaceholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleRemove(cb) {
    const img         = document.getElementById('previewImg');
    const placeholder = document.getElementById('previewPlaceholder');
    if (cb.checked) {
        img.style.display = 'none';
        if (placeholder) {
            placeholder.style.display = 'block';
            placeholder.innerHTML = '<i class="fas fa-trash-alt"></i><span>Image will be removed</span>';
        } else {
            const wrap = img.parentElement;
            wrap.innerHTML = '<div class="ann-img-preview-placeholder"><i class="fas fa-trash-alt"></i><span>Image will be removed</span></div>';
        }
    } else {
        location.reload();
    }
}

function updateCounter(el) {
    document.getElementById('charCount').textContent = el.value.length;
}

document.getElementById('editForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
});
</script>
@endsection
