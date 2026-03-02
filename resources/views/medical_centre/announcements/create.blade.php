{{-- resources/views/medical_centre/announcements/create.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'New Announcement')
@section('page-title', 'New Announcement')

@section('content')
<style>
.mc-page-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap;
}
.mc-page-title { font-size:1.25rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.mc-page-sub   { font-size:.82rem; color:var(--text-muted); margin:0; }
.mc-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.48rem 1rem; border-radius:9px; border:none;
    font-size:.8rem; font-weight:700; cursor:pointer;
    font-family:inherit; transition:var(--transition); text-decoration:none;
}
.mc-btn-back { background:#f4f7fb; color:var(--text-muted); }
.mc-btn-back:hover { background:#e9ecef; color:var(--text-dark); }

.ann-form-card {
    background:#fff; border-radius:14px; border:1px solid var(--border);
    box-shadow:var(--shadow-sm); overflow:hidden; max-width:780px; margin:0 auto;
}
.ann-form-head {
    padding:.9rem 1.25rem; border-bottom:1px solid var(--border);
    background:#fafbfc; display:flex; align-items:center; gap:.5rem;
}
.ann-form-head h6 { font-size:.9rem; font-weight:800; color:var(--text-dark); margin:0; }
.ann-form-head i  { color:var(--mc-primary); }
.ann-form-body { padding:1.5rem 1.25rem; }

.ann-form-group { margin-bottom:1.1rem; }
.ann-form-label {
    display:block; font-size:.75rem; font-weight:800;
    color:var(--text-muted); text-transform:uppercase;
    letter-spacing:.05em; margin-bottom:.4rem;
}
.ann-form-label span { color:#e74c3c; margin-left:2px; }
.ann-form-control {
    width:100%; padding:.55rem .85rem; border-radius:9px;
    border:1.5px solid var(--border); font-size:.83rem; font-weight:600;
    color:var(--text-dark); background:#fff; font-family:inherit;
    outline:none; transition:border-color .2s; box-sizing:border-box;
}
.ann-form-control:focus { border-color:var(--mc-primary); }
.ann-form-control.is-invalid { border-color:#e74c3c; }
textarea.ann-form-control { resize:vertical; min-height:130px; }
.ann-form-error { font-size:.72rem; color:#e74c3c; margin-top:.3rem; font-weight:600; }

.ann-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(max-width:600px){ .ann-form-row { grid-template-columns:1fr; } }

.ann-img-upload-wrap {
    border:2px dashed var(--border); border-radius:10px;
    background:#f8fbff; overflow:hidden; transition:border-color .2s; margin-top:.4rem;
}
.ann-img-upload-wrap:hover { border-color:var(--mc-primary); }
.ann-img-preview {
    width:100%; height:160px;
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; overflow:hidden;
}
.ann-img-preview img { width:100%; height:100%; object-fit:cover; display:none; }
.ann-img-preview-placeholder { text-align:center; color:var(--text-muted); }
.ann-img-preview-placeholder i { font-size:2rem; display:block; margin-bottom:.35rem; opacity:.3; }
.ann-img-preview-placeholder span { font-size:.75rem; font-weight:600; }

.ann-toggle-row {
    display:flex; align-items:center; justify-content:space-between;
    background:#f8fbff; border-radius:10px; border:1.5px solid var(--border);
    padding:.75rem 1rem;
}
.ann-toggle-label { font-size:.85rem; font-weight:700; color:var(--text-dark); margin:0; cursor:pointer; }
.ann-toggle-sub   { font-size:.72rem; color:var(--text-muted); display:block; margin-top:.1rem; }
.form-check-input { width:2.2rem !important; height:1.15rem; cursor:pointer; flex-shrink:0; }

.ann-form-footer {
    padding:1rem 1.25rem; border-top:1px solid var(--border);
    background:#fafbfc; display:flex; align-items:center;
    justify-content:flex-end; gap:.75rem;
}
.ann-submit-btn {
    padding:.55rem 1.5rem; border-radius:9px; border:none; font-size:.83rem;
    font-weight:800; cursor:pointer; font-family:inherit; transition:var(--transition);
    display:inline-flex; align-items:center; gap:.4rem; text-decoration:none;
}
.ann-submit-primary { background:var(--mc-primary); color:#fff; }
.ann-submit-primary:hover { background:var(--mc-secondary); }
.ann-submit-cancel { background:#f4f7fb; color:var(--text-muted); }
.ann-submit-cancel:hover { background:#e9ecef; color:var(--text-dark); }

/* Char counter */
.ann-char-counter { font-size:.7rem; color:var(--text-muted); font-weight:600; text-align:right; margin-top:.25rem; }
</style>

{{-- Page Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-bullhorn me-2" style="color:var(--mc-primary);"></i>New Announcement
        </h4>
        <p class="mc-page-sub">Create a new announcement for {{ $mc->name }}</p>
    </div>
    <a href="{{ route('medical_centre.announcements') }}" class="mc-btn mc-btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;" role="alert">
        <i class="fas fa-exclamation-circle"></i>
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

{{-- Form Card --}}
<div class="ann-form-card">
    <div class="ann-form-head">
        <i class="fas fa-plus-circle"></i>
        <h6>Announcement Details</h6>
    </div>

    <form method="POST"
          action="{{ route('medical_centre.announcements.store') }}"
          enctype="multipart/form-data"
          id="createForm">
        @csrf

        <div class="ann-form-body">

            {{-- Title --}}
            <div class="ann-form-group">
                <label class="ann-form-label">Title <span>*</span></label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
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
                    <option value="health_camp"
                        {{ old('announcement_type') === 'health_camp' ? 'selected' : '' }}>
                        🏥 Health Camp
                    </option>
                    <option value="special_offer"
                        {{ old('announcement_type') === 'special_offer' ? 'selected' : '' }}>
                        🏷️ Special Offer
                    </option>
                    <option value="new_service"
                        {{ old('announcement_type') === 'new_service' ? 'selected' : '' }}>
                        ⭐ New Service
                    </option>
                    <option value="emergency"
                        {{ old('announcement_type') === 'emergency' ? 'selected' : '' }}>
                        🚨 Emergency
                    </option>
                    <option value="general"
                        {{ old('announcement_type') === 'general' ? 'selected' : '' }}>
                        📢 General
                    </option>
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
                          oninput="updateCounter(this)">{{ old('content') }}</textarea>
                <div class="ann-char-counter">
                    <span id="charCount">{{ strlen(old('content', '')) }}</span> / 2000
                </div>
                @error('content')
                    <div class="ann-form-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Date Range --}}
            <div class="ann-form-row">
                <div class="ann-form-group">
                    <label class="ann-form-label">Start Date
                        <small style="text-transform:none;font-weight:600;color:var(--text-muted);">(optional)</small>
                    </label>
                    <input type="date"
                           name="start_date"
                           value="{{ old('start_date') }}"
                           class="ann-form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}">
                    @error('start_date')
                        <div class="ann-form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="ann-form-group">
                    <label class="ann-form-label">End Date
                        <small style="text-transform:none;font-weight:600;color:var(--text-muted);">(optional)</small>
                    </label>
                    <input type="date"
                           name="end_date"
                           value="{{ old('end_date') }}"
                           class="ann-form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}">
                    @error('end_date')
                        <div class="ann-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="ann-form-group">
                <label class="ann-form-label">
                    Banner Image
                    <small style="text-transform:none;font-weight:600;color:var(--text-muted);">
                        (JPG / PNG / WEBP · max 2MB · optional)
                    </small>
                </label>
                <input type="file"
                       name="image"
                       id="imageInput"
                       accept="image/jpeg,image/png,image/webp"
                       class="ann-form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                       onchange="previewImage(this)"
                       style="margin-bottom:.4rem;">
                @error('image')
                    <div class="ann-form-error">{{ $message }}</div>
                @enderror

                {{-- Preview --}}
                <div class="ann-img-upload-wrap" onclick="document.getElementById('imageInput').click()">
                    <div class="ann-img-preview" id="imgPreview">
                        <img src="" alt="Preview" id="previewImg">
                        <div class="ann-img-preview-placeholder" id="previewPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to select image</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Publish Toggle --}}
            <div class="ann-form-group" style="margin-bottom:0;">
                <div class="ann-toggle-row">
                    <div>
                        <label for="is_active" class="ann-toggle-label">
                            <i class="fas fa-paper-plane me-1" style="color:var(--mc-primary);"></i>
                            Publish Immediately
                        </label>
                        <span class="ann-toggle-sub">Announcement will be visible to patients right away</span>
                    </div>
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="ann-form-footer">
            <a href="{{ route('medical_centre.announcements') }}" class="ann-submit-btn ann-submit-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="ann-submit-btn ann-submit-primary" id="submitBtn">
                <i class="fas fa-paper-plane"></i> Publish Announcement
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
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateCounter(el) {
    document.getElementById('charCount').textContent = el.value.length;
}

// Prevent double submit
document.getElementById('createForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publishing...';
});
</script>
@endsection
