{{-- resources/views/medical_centre/announcements/show.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', $announcement->title)
@section('page-title', 'Announcement Details')

@section('content')
<style>
.mc-page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.mc-page-title { font-size:1.25rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.mc-page-sub   { font-size:.82rem; color:var(--text-muted); margin:0; }
.mc-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.48rem 1rem; border-radius:9px; border:none; font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); text-decoration:none; }
.mc-btn-back        { background:#f4f7fb; color:var(--text-muted); }
.mc-btn-back:hover  { background:#e9ecef; color:var(--text-dark); }
.mc-btn-edit        { background:#fff3cd; color:#92400e; border:1.5px solid #fde68a; }
.mc-btn-edit:hover  { background:#d97706; color:#fff; }
.mc-btn-del         { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }
.mc-btn-del:hover   { background:#e74c3c; color:#fff; }
.mc-btn-toggle-on   { background:#d1fae5; color:#065f46; border:1.5px solid #a7f3d0; }
.mc-btn-toggle-on:hover  { background:#059669; color:#fff; }
.mc-btn-toggle-off  { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }
.mc-btn-toggle-off:hover { background:#e74c3c; color:#fff; }

.ann-show-grid { display:grid; grid-template-columns:1fr 300px; gap:1.25rem; align-items:start; }
@media(max-width:900px){ .ann-show-grid { grid-template-columns:1fr; } }

.ann-show-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; }
.ann-show-img  { width:100%; height:280px; object-fit:cover; display:block; }
.ann-show-img-placeholder { height:200px; background:linear-gradient(135deg,#f0fdf4,#e8f0fe); display:flex; align-items:center; justify-content:center; }
.ann-show-img-placeholder i { font-size:4rem; color:var(--mc-primary); opacity:.25; }
.ann-show-body { padding:1.5rem; }
.ann-show-body h2 { font-size:1.15rem; font-weight:800; color:var(--text-dark); margin:0 0 .85rem; line-height:1.5; }
.ann-show-body p  { font-size:.85rem; color:var(--text-muted); line-height:1.85; margin:0; white-space:pre-line; }

.ann-type-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.22rem .7rem; border-radius:99px; font-size:.7rem; font-weight:800; white-space:nowrap; }
.type-health_camp   { background:#d1fae5; color:#065f46; }
.type-special_offer { background:#fff3cd; color:#92400e; }
.type-new_service   { background:#dbeafe; color:#1e40af; }
.type-emergency     { background:#fee2e2; color:#991b1b; }
.type-general       { background:#f3f4f6; color:#374151; }

.ann-status-pill { padding:.22rem .65rem; border-radius:99px; font-size:.68rem; font-weight:800; }
.pill-active   { background:#d1fae5; color:#065f46; }
.pill-inactive { background:#fee2e2; color:#991b1b; }

.ann-meta-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; }
.ann-meta-head { padding:.85rem 1rem; border-bottom:1px solid var(--border); background:#fafbfc; }
.ann-meta-head h6 { font-size:.85rem; font-weight:800; color:var(--text-dark); margin:0; }
.ann-meta-body { padding:1rem; display:flex; flex-direction:column; gap:.85rem; }
.ann-meta-item { display:flex; align-items:flex-start; gap:.65rem; }
.ann-meta-icon { width:28px; height:28px; border-radius:7px; background:#f4f7fb; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.ann-meta-icon i { font-size:.72rem; color:var(--mc-primary); }
.ann-meta-text label { font-size:.67rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; display:block; margin-bottom:.1rem; }
.ann-meta-text span  { font-size:.8rem; font-weight:700; color:var(--text-dark); }

.ann-actions-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; margin-top:1rem; }
.ann-actions-head { padding:.85rem 1rem; border-bottom:1px solid var(--border); background:#fafbfc; }
.ann-actions-head h6 { font-size:.85rem; font-weight:800; color:var(--text-dark); margin:0; }
.ann-actions-body { padding:1rem; display:flex; flex-direction:column; gap:.5rem; }
.ann-full-btn { width:100%; padding:.55rem 1rem; border-radius:9px; border:none; font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); display:flex; align-items:center; justify-content:center; gap:.4rem; text-decoration:none; }
.ann-full-edit   { background:#fff3cd; color:#92400e; border:1.5px solid #fde68a; }
.ann-full-edit:hover { background:#d97706; color:#fff; border-color:#d97706; }
.ann-full-ton    { background:#d1fae5; color:#065f46; border:1.5px solid #a7f3d0; }
.ann-full-ton:hover { background:#059669; color:#fff; border-color:#059669; }
.ann-full-toff   { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }
.ann-full-toff:hover { background:#e74c3c; color:#fff; border-color:#e74c3c; }
.ann-full-del    { background:#fff; color:#991b1b; border:1.5px solid #fca5a5; }
.ann-full-del:hover { background:#fee2e2; }
</style>

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-bullhorn me-2" style="color:var(--mc-primary);"></i>Announcement Details
        </h4>
        <p class="mc-page-sub">{{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y · h:i A') }}</p>
    </div>
    <a href="{{ route('medical_centre.announcements') }}" class="mc-btn mc-btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $typeLabels = [
        'health_camp'   => ['Health Camp',   'fa-heartbeat'],
        'special_offer' => ['Special Offer', 'fa-tags'],
        'new_service'   => ['New Service',   'fa-star'],
        'emergency'     => ['Emergency',     'fa-exclamation-triangle'],
        'general'       => ['General',       'fa-info-circle'],
    ];
    [$tLabel, $tIcon] = $typeLabels[$announcement->announcement_type] ?? ['General','fa-info-circle'];
@endphp

<div class="ann-show-grid">

    {{-- Main Content --}}
    <div class="ann-show-card">
        @if($announcement->image_path)
            <img src="{{ asset('storage/' . $announcement->image_path) }}"
                 alt="{{ $announcement->title }}" class="ann-show-img">
        @else
            <div class="ann-show-img-placeholder">
                <i class="fas {{ $tIcon }}"></i>
            </div>
        @endif
        <div class="ann-show-body">
            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.85rem;">
                <span class="ann-type-badge type-{{ $announcement->announcement_type }}">
                    <i class="fas {{ $tIcon }}"></i> {{ $tLabel }}
                </span>
                <span class="ann-status-pill {{ $announcement->is_active ? 'pill-active' : 'pill-inactive' }}">
                    <i class="fas {{ $announcement->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} me-1"></i>
                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <h2>{{ $announcement->title }}</h2>
            <p>{{ $announcement->content }}</p>
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- Meta Info --}}
        <div class="ann-meta-card">
            <div class="ann-meta-head">
                <h6><i class="fas fa-info-circle me-2" style="color:var(--mc-primary);"></i>Details</h6>
            </div>
            <div class="ann-meta-body">

                <div class="ann-meta-item">
                    <div class="ann-meta-icon"><i class="fas fa-tag"></i></div>
                    <div class="ann-meta-text">
                        <label>Type</label>
                        <span>{{ $tLabel }}</span>
                    </div>
                </div>

                <div class="ann-meta-item">
                    <div class="ann-meta-icon">
                        <i class="fas fa-circle" style="font-size:.45rem;color:{{ $announcement->is_active ? '#059669' : '#e74c3c' }};"></i>
                    </div>
                    <div class="ann-meta-text">
                        <label>Status</label>
                        <span>{{ $announcement->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>

                <div class="ann-meta-item">
                    <div class="ann-meta-icon"><i class="fas fa-calendar-plus"></i></div>
                    <div class="ann-meta-text">
                        <label>Created</label>
                        <span>{{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y') }}</span>
                    </div>
                </div>

                @if($announcement->start_date)
                    <div class="ann-meta-item">
                        <div class="ann-meta-icon"><i class="fas fa-play-circle"></i></div>
                        <div class="ann-meta-text">
                            <label>Start Date</label>
                            <span>{{ \Carbon\Carbon::parse($announcement->start_date)->format('M d, Y') }}</span>
                        </div>
                    </div>
                @endif

                @if($announcement->end_date)
                    <div class="ann-meta-item">
                        <div class="ann-meta-icon"><i class="fas fa-stop-circle"></i></div>
                        <div class="ann-meta-text">
                            <label>End Date</label>
                            <span>{{ \Carbon\Carbon::parse($announcement->end_date)->format('M d, Y') }}</span>
                        </div>
                    </div>
                @endif

                @if($announcement->updated_at != $announcement->created_at)
                    <div class="ann-meta-item">
                        <div class="ann-meta-icon"><i class="fas fa-pencil-alt"></i></div>
                        <div class="ann-meta-text">
                            <label>Last Updated</label>
                            <span>{{ \Carbon\Carbon::parse($announcement->updated_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Actions --}}
        <div class="ann-actions-card">
            <div class="ann-actions-head">
                <h6><i class="fas fa-cogs me-2" style="color:var(--mc-primary);"></i>Actions</h6>
            </div>
            <div class="ann-actions-body">

                <a href="{{ route('medical_centre.announcements.edit', $announcement->id) }}"
                   class="ann-full-btn ann-full-edit">
                    <i class="fas fa-edit"></i> Edit Announcement
                </a>

                <form method="POST"
                      action="{{ route('medical_centre.announcements.toggle', $announcement->id) }}"
                      style="margin:0;">
                    @csrf
                    <button type="submit"
                            class="ann-full-btn {{ $announcement->is_active ? 'ann-full-ton' : 'ann-full-toff' }}">
                        <i class="fas {{ $announcement->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('medical_centre.announcements.destroy', $announcement->id) }}"
                      style="margin:0;"
                      onsubmit="return confirm('Delete this announcement permanently? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ann-full-btn ann-full-del">
                        <i class="fas fa-trash-alt"></i> Delete Announcement
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
