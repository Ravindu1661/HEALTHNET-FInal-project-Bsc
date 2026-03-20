@extends('admin.layouts.master')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@push('styles')
<style>
:root { --green:#42a649; --navy:#1a3a5c; --border:#e4e8ed; --radius:12px; --muted:#6b7a8d; }
.form-card { background:#fff; border-radius:var(--radius); border:1px solid var(--border); overflow:hidden; margin-bottom:1rem; }
.fc-head { padding:.85rem 1.2rem; border-bottom:1px solid var(--border); background:linear-gradient(to right,rgba(26,58,92,.04),transparent); display:flex; align-items:center; gap:.5rem; }
.fc-title { font-size:.88rem; font-weight:700; color:var(--navy); margin:0; }
.fc-title i { color:var(--green); }
.fc-body { padding:1.2rem; }
.f-label { display:block; font-size:.77rem; font-weight:600; color:var(--navy); margin-bottom:.35rem; }
.f-label .req { color:#dc2626; }
.f-ctrl { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:9px; font-size:.85rem; color:var(--navy); background:#fff; transition:border-color .2s; font-family:inherit; }
.f-ctrl:focus { border-color:var(--green); outline:none; box-shadow:0 0 0 3px rgba(66,166,73,.1); }
.f-ctrl.is-invalid { border-color:#dc2626; background:#fef2f2; }
.f-err  { font-size:.73rem; color:#dc2626; margin-top:.25rem; display:block; }
.f-hint { font-size:.7rem; color:var(--muted); margin-top:.2rem; }
.row-2  { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(max-width:640px){ .row-2 { grid-template-columns:1fr; } }

.type-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:.5rem; }
.type-card { border:1.5px solid var(--border); border-radius:9px; padding:.6rem .5rem; text-align:center; cursor:pointer; transition:all .18s; user-select:none; }
.type-card:hover { border-color:var(--green); background:rgba(66,166,73,.05); }
.type-card.selected { border-color:var(--green); background:rgba(66,166,73,.09); }
.type-card .tc-icon { font-size:1.2rem; margin-bottom:.25rem; }
.type-card .tc-lbl  { font-size:.72rem; font-weight:700; color:var(--navy); }
.type-card input[type="radio"] { display:none; }

.img-preview-wrap { border:2px dashed var(--border); border-radius:10px; padding:1.2rem; text-align:center; cursor:pointer; transition:border-color .2s; position:relative; }
.img-preview-wrap:hover { border-color:var(--green); }
.img-preview-wrap input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.img-current { max-width:100%; max-height:180px; border-radius:8px; }
.img-placeholder { color:var(--muted); }
.img-placeholder i { font-size:2rem; display:block; margin-bottom:.4rem; }

.btn-row { display:flex; gap:.6rem; justify-content:flex-end; flex-wrap:wrap; margin-top:1rem; }
.btn-s { padding:.55rem 1.3rem; border-radius:25px; font-size:.85rem; font-weight:700; cursor:pointer; font-family:inherit; transition:all .2s; display:inline-flex; align-items:center; gap:.4rem; }
.btn-submit { background:linear-gradient(135deg,var(--green),#2d7a32); color:#fff; border:none; box-shadow:0 3px 12px rgba(66,166,73,.3); }
.btn-submit:hover { transform:translateY(-1px); }
.btn-cancel { background:#fff; color:var(--muted); border:1.5px solid var(--border); text-decoration:none; }
.btn-cancel:hover { border-color:var(--navy); color:var(--navy); }
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
    <div>
        <h1 style="font-size:1.05rem;font-weight:700;color:var(--navy,#1a3a5c);margin:0;">
            <i class="fas fa-edit me-2" style="color:#42a649;"></i>Edit Announcement
        </h1>
        <p style="font-size:.78rem;color:#6b7a8d;margin:.2rem 0 0;">
            ID #{{ $announcement->id }} — Created {{ $announcement->created_at->format('d M Y') }}
        </p>
    </div>
    <div style="display:flex;gap:.5rem;">
        <a href="{{ route('admin.announcements.show', $announcement) }}"
           style="font-size:.78rem;color:#6b7a8d;text-decoration:none;">
            <i class="fas fa-eye me-1"></i> View
        </a>
        <span style="color:#ddd;">|</span>
        <a href="{{ route('admin.announcements.index') }}"
           style="font-size:.78rem;color:#6b7a8d;text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger" style="border-radius:10px;font-size:.83rem;border-left:4px solid #dc2626;">
    <i class="fas fa-exclamation-circle me-2"></i>
    <div>
        <strong>Please fix the following:</strong>
        <ul style="margin:.3rem 0 0;padding-left:1.1rem;font-size:.8rem;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('admin.announcements.update', $announcement) }}"
      method="POST" enctype="multipart/form-data" id="annForm">
@csrf @method('PUT')

{{-- ── Title + Type ── --}}
<div class="form-card">
    <div class="fc-head"><h2 class="fc-title"><i class="fas fa-pen"></i> Basic Info</h2></div>
    <div class="fc-body">

        <div style="margin-bottom:1rem;">
            <label class="f-label" for="title">Title <span class="req">*</span></label>
            <input type="text" id="title" name="title"
                   class="f-ctrl @error('title') is-invalid @enderror"
                   value="{{ old('title', $announcement->title) }}"
                   maxlength="255" required>
            @error('title')<span class="f-err">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="f-label">Announcement Type <span class="req">*</span></label>
            @php
                $typeConfig = [
                    'health_camp'   => ['icon'=>'fa-hospital-user',      'label'=>'Health Camp',   'color'=>'#1e40af'],
                    'awareness'     => ['icon'=>'fa-info-circle',         'label'=>'Awareness',     'color'=>'#0369a1'],
                    'special_offer' => ['icon'=>'fa-tag',                 'label'=>'Special Offer', 'color'=>'#92400e'],
                    'new_service'   => ['icon'=>'fa-star',                'label'=>'New Service',   'color'=>'#065f46'],
                    'emergency'     => ['icon'=>'fa-exclamation-triangle','label'=>'Emergency',     'color'=>'#991b1b'],
                    'general'       => ['icon'=>'fa-bullhorn',            'label'=>'General',       'color'=>'#374151'],
                ];
                $currentType = old('announcementtype', $announcement->announcement_type);
            @endphp
            <div class="type-grid">
                @foreach($types as $t)
                @php $cfg = $typeConfig[$t] ?? ['icon'=>'fa-circle','label'=>ucfirst($t),'color'=>'#374151']; @endphp
                <label class="type-card {{ $currentType===$t ? 'selected' : '' }}">
                    <input type="radio" name="announcementtype" value="{{ $t }}"
                           {{ $currentType===$t ? 'checked' : '' }}
                           onchange="document.querySelectorAll('.type-card').forEach(c=>c.classList.remove('selected'));this.closest('.type-card').classList.add('selected');">
                    <div class="tc-icon"><i class="fas {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};"></i></div>
                    <div class="tc-lbl">{{ $cfg['label'] }}</div>
                </label>
                @endforeach
            </div>
            @error('announcementtype')<span class="f-err">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

{{-- ── Content ── --}}
<div class="form-card">
    <div class="fc-head"><h2 class="fc-title"><i class="fas fa-align-left"></i> Content</h2></div>
    <div class="fc-body">
        <label class="f-label" for="content">Content <span class="req">*</span></label>
        <textarea id="content" name="content"
                  class="f-ctrl @error('content') is-invalid @enderror"
                  rows="6" required>{{ old('content', $announcement->content) }}</textarea>
        @error('content')<span class="f-err">{{ $message }}</span>@enderror
    </div>
</div>

{{-- ── Dates + Status ── --}}
<div class="form-card">
    <div class="fc-head"><h2 class="fc-title"><i class="fas fa-calendar-alt"></i> Schedule & Status</h2></div>
    <div class="fc-body">
        <div class="row-2" style="margin-bottom:1rem;">
            <div>
                <label class="f-label" for="startdate">Start Date</label>
                <input type="date" id="startdate" name="startdate"
                       class="f-ctrl @error('startdate') is-invalid @enderror"
                       value="{{ old('startdate', $announcement->start_date?->format('Y-m-d')) }}">
                @error('startdate')<span class="f-err">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="f-label" for="enddate">End Date</label>
                <input type="date" id="enddate" name="enddate"
                       class="f-ctrl @error('enddate') is-invalid @enderror"
                       value="{{ old('enddate', $announcement->end_date?->format('Y-m-d')) }}">
                @error('enddate')<span class="f-err">{{ $message }}</span>@enderror
            </div>
        </div>

        <div>
            <label class="f-label">Status</label>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                @php $isActive = old('isactive', $announcement->is_active ? '1' : '0'); @endphp
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;">
                    <input type="radio" name="isactive" value="1"
                           {{ $isActive==='1' ? 'checked' : '' }}
                           style="accent-color:var(--green,#42a649);">
                    <span><i class="fas fa-circle" style="color:#42a649;font-size:.6rem;"></i> Active</span>
                </label>
                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;">
                    <input type="radio" name="isactive" value="0"
                           {{ $isActive==='0' ? 'checked' : '' }}
                           style="accent-color:#dc2626;">
                    <span><i class="fas fa-circle" style="color:#dc2626;font-size:.6rem;"></i> Inactive (draft)</span>
                </label>
            </div>
        </div>
    </div>
</div>

{{-- ── Image ── --}}
<div class="form-card">
    <div class="fc-head"><h2 class="fc-title"><i class="fas fa-image"></i> Image</h2></div>
    <div class="fc-body">

        @if($announcement->image_path)
        <div style="margin-bottom:1rem;">
            <p style="font-size:.77rem;color:var(--muted);margin-bottom:.5rem;">Current image:</p>
            <img src="{{ asset('storage/'.$announcement->image_path) }}"
                 alt="Current" class="img-current"
                 onerror="this.style.display='none'">
            <p style="font-size:.73rem;color:var(--muted);margin-top:.35rem;">
                Upload a new image below to replace it.
            </p>
        </div>
        @endif

        <div class="img-preview-wrap" id="imgWrap">
            <input type="file" name="image" id="imgInput" accept="image/*"
                   onchange="previewImg(this)">
            <img id="imgPreview" style="max-width:100%;max-height:180px;border-radius:8px;display:none;" alt="Preview">
            <div class="img-placeholder" id="imgPlaceholder">
                <i class="fas fa-cloud-upload-alt"></i>
                <p style="font-size:.78rem;margin:0;">
                    {{ $announcement->image_path ? 'Click to replace image' : 'Click to upload image' }}
                    (JPG, PNG, max 2MB)
                </p>
            </div>
        </div>
        @error('image')<span class="f-err">{{ $message }}</span>@enderror
    </div>
</div>

<div class="btn-row">
    <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn-s btn-cancel">
        <i class="fas fa-times"></i> Cancel
    </a>
    <button type="submit" class="btn-s btn-submit">
        <i class="fas fa-save"></i> Save Changes
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
            document.getElementById('imgPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
