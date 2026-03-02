{{-- resources/views/medical_centre/announcements/index.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

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
.mc-btn-primary { background:var(--mc-primary); color:#fff; }
.mc-btn-primary:hover { background:var(--mc-secondary); color:#fff; }

.ann-stats-row {
    display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;
}
@media(max-width:600px){ .ann-stats-row { grid-template-columns:1fr 1fr; } }
.ann-stat-card {
    background:#fff; border-radius:12px; border:1px solid var(--border);
    padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem;
    box-shadow:var(--shadow-sm);
}
.ann-stat-icon {
    width:42px; height:42px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:.95rem; flex-shrink:0;
}
.ann-stat-body h6 { font-size:.72rem; font-weight:700; color:var(--text-muted); margin:0 0 .15rem; text-transform:uppercase; letter-spacing:.05em; }
.ann-stat-body span { font-size:1.4rem; font-weight:800; color:var(--text-dark); line-height:1; }

.ann-filter-card {
    background:#fff; border-radius:12px; border:1px solid var(--border);
    padding:.85rem 1rem; margin-bottom:1.25rem; box-shadow:var(--shadow-sm);
    display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
}
.ann-filter-card input,
.ann-filter-card select {
    border:1.5px solid var(--border); border-radius:8px;
    padding:.42rem .8rem; font-size:.8rem; font-weight:600;
    color:var(--text-dark); background:#f8fbff; font-family:inherit; outline:none;
}
.ann-filter-card input { flex:1; min-width:180px; }
.ann-filter-card input:focus,
.ann-filter-card select:focus { border-color:var(--mc-primary); }
.ann-filter-btn {
    padding:.45rem 1rem; border-radius:8px; border:none;
    font-size:.8rem; font-weight:700; cursor:pointer;
    font-family:inherit; transition:var(--transition);
    display:inline-flex; align-items:center; gap:.4rem;
}
.ann-filter-primary { background:var(--mc-primary); color:#fff; }
.ann-filter-primary:hover { background:var(--mc-secondary); }
.ann-filter-clear { background:#f4f7fb; color:var(--text-muted); }
.ann-filter-clear:hover { background:#e9ecef; color:var(--text-dark); }

.ann-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:1.1rem; margin-bottom:1.25rem;
}
@media(max-width:1100px){ .ann-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:600px) { .ann-grid { grid-template-columns:1fr; } }

.ann-card {
    background:#fff; border-radius:14px; border:1px solid var(--border);
    box-shadow:var(--shadow-sm); overflow:hidden;
    display:flex; flex-direction:column; transition:box-shadow .2s;
}
.ann-card:hover { box-shadow:var(--shadow-md); }
.ann-card-img {
    height:140px; overflow:hidden; background:#f4f7fb;
    display:flex; align-items:center; justify-content:center; position:relative;
}
.ann-card-img img { width:100%; height:100%; object-fit:cover; }
.ann-card-img-placeholder { font-size:2.5rem; color:var(--border); }
.ann-card-img-overlay {
    position:absolute; top:.5rem; left:.5rem; right:.5rem;
    display:flex; justify-content:space-between; align-items:flex-start;
}
.ann-type-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.2rem .6rem; border-radius:99px;
    font-size:.62rem; font-weight:800; white-space:nowrap;
}
.type-health_camp    { background:rgba(16,185,129,.15); color:#065f46; border:1px solid rgba(16,185,129,.3); }
.type-special_offer  { background:rgba(245,158,11,.15);  color:#92400e; border:1px solid rgba(245,158,11,.3); }
.type-new_service    { background:rgba(59,130,246,.15);  color:#1e40af; border:1px solid rgba(59,130,246,.3); }
.type-emergency      { background:rgba(239,68,68,.15);   color:#991b1b; border:1px solid rgba(239,68,68,.3); }
.type-general        { background:rgba(107,114,128,.15); color:#374151; border:1px solid rgba(107,114,128,.3); }
.ann-status-pill {
    padding:.18rem .55rem; border-radius:99px; font-size:.62rem; font-weight:800;
}
.pill-active   { background:#d1fae5; color:#065f46; }
.pill-inactive { background:#fee2e2; color:#991b1b; }
.ann-card-body { padding:.9rem 1rem; flex:1; display:flex; flex-direction:column; gap:.5rem; }
.ann-card-title {
    font-size:.9rem; font-weight:800; color:var(--text-dark); margin:0;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.ann-card-excerpt {
    font-size:.75rem; color:var(--text-muted); line-height:1.55; margin:0; flex:1;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.ann-card-meta { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; font-size:.68rem; color:var(--text-muted); font-weight:600; }
.ann-card-meta i { color:var(--mc-primary); }
.ann-card-footer {
    padding:.65rem 1rem; border-top:1px solid var(--border);
    display:flex; align-items:center; gap:.4rem; background:#fafbfc;
}
.ann-action-btn {
    flex:1; padding:.38rem .5rem; border-radius:7px; border:none;
    font-size:.73rem; font-weight:700; cursor:pointer; font-family:inherit;
    transition:var(--transition); display:inline-flex; align-items:center;
    justify-content:center; gap:.3rem; text-decoration:none;
}
.btn-view    { background:#e8f0fe; color:#2969bf; }
.btn-view:hover { background:#2969bf; color:#fff; }
.btn-edit    { background:#fff3cd; color:#92400e; }
.btn-edit:hover { background:#d97706; color:#fff; }
.btn-toggle-on  { background:#d1fae5; color:#065f46; }
.btn-toggle-on:hover { background:#059669; color:#fff; }
.btn-toggle-off { background:#fee2e2; color:#991b1b; }
.btn-toggle-off:hover { background:#e74c3c; color:#fff; }
.btn-del { background:#fee2e2; color:#991b1b; width:30px; flex:0 0 30px; border-radius:7px; height:30px; }
.btn-del:hover { background:#e74c3c; color:#fff; }
.ann-empty { padding:3.5rem 1rem; text-align:center; color:var(--text-muted); }
.ann-empty i { font-size:3rem; display:block; margin-bottom:.75rem; opacity:.25; }
.ann-empty h5 { font-size:1rem; font-weight:700; margin-bottom:.35rem; }
.ann-empty p  { font-size:.82rem; }
.ann-pagination {
    display:flex; justify-content:space-between; align-items:center;
    background:#fff; border-radius:12px; border:1px solid var(--border);
    padding:.85rem 1.1rem; font-size:.78rem; color:var(--text-muted);
    flex-wrap:wrap; gap:.5rem; box-shadow:var(--shadow-sm);
}
.ann-pagination .pagination { margin:0; }
.ann-pagination .page-link { font-size:.75rem; padding:.3rem .6rem; color:var(--text-dark); border-color:var(--border); }
.ann-pagination .page-item.active .page-link { background:var(--mc-primary); border-color:var(--mc-primary); }
</style>

{{-- Page Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-bullhorn me-2" style="color:var(--mc-primary);"></i>Announcements
        </h4>
        <p class="mc-page-sub">Manage announcements for {{ $mc->name }}</p>
    </div>
    <a href="{{ route('medical_centre.announcements.create') }}" class="mc-btn mc-btn-primary">
        <i class="fas fa-plus"></i> New Announcement
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stats --}}
<div class="ann-stats-row">
    <div class="ann-stat-card">
        <div class="ann-stat-icon" style="background:#e8f0fe;color:#2969bf;"><i class="fas fa-bullhorn"></i></div>
        <div class="ann-stat-body"><h6>Total</h6><span>{{ $stats['total'] }}</span></div>
    </div>
    <div class="ann-stat-card">
        <div class="ann-stat-icon" style="background:#d1fae5;color:#065f46;"><i class="fas fa-check-circle"></i></div>
        <div class="ann-stat-body"><h6>Active</h6><span>{{ $stats['active'] }}</span></div>
    </div>
    <div class="ann-stat-card">
        <div class="ann-stat-icon" style="background:#fee2e2;color:#991b1b;"><i class="fas fa-pause-circle"></i></div>
        <div class="ann-stat-body"><h6>Inactive</h6><span>{{ $stats['inactive'] }}</span></div>
    </div>
</div>

{{-- Filters --}}
<form action="{{ route('medical_centre.announcements') }}" method="GET" class="ann-filter-card">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search title or content...">
    <select name="type">
        <option value="">All Types</option>
        <option value="health_camp"   {{ $type === 'health_camp'   ? 'selected' : '' }}>Health Camp</option>
        <option value="special_offer" {{ $type === 'special_offer' ? 'selected' : '' }}>Special Offer</option>
        <option value="new_service"   {{ $type === 'new_service'   ? 'selected' : '' }}>New Service</option>
        <option value="emergency"     {{ $type === 'emergency'     ? 'selected' : '' }}>Emergency</option>
        <option value="general"       {{ $type === 'general'       ? 'selected' : '' }}>General</option>
    </select>
    <select name="status">
        <option value="">All Status</option>
        <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button type="submit" class="ann-filter-btn ann-filter-primary"><i class="fas fa-search"></i> Filter</button>
    @if($search || $type || $status)
        <a href="{{ route('medical_centre.announcements') }}" class="ann-filter-btn ann-filter-clear">
            <i class="fas fa-times"></i> Clear
        </a>
    @endif
</form>

{{-- Grid --}}
@if($announcements->count())
    <div class="ann-grid">
        @foreach($announcements as $ann)
            @php
                $typeLabels = [
                    'health_camp'   => ['label' => 'Health Camp',   'icon' => 'fa-heartbeat'],
                    'special_offer' => ['label' => 'Special Offer', 'icon' => 'fa-tags'],
                    'new_service'   => ['label' => 'New Service',   'icon' => 'fa-star'],
                    'emergency'     => ['label' => 'Emergency',     'icon' => 'fa-exclamation-triangle'],
                    'general'       => ['label' => 'General',       'icon' => 'fa-info-circle'],
                ];
                $t = $typeLabels[$ann->announcement_type] ?? $typeLabels['general'];
            @endphp
            <div class="ann-card">
                <div class="ann-card-img">
                    @if($ann->image_path)
                        <img src="{{ asset('storage/' . $ann->image_path) }}" alt="{{ $ann->title }}">
                    @else
                        <i class="fas {{ $t['icon'] }} ann-card-img-placeholder"></i>
                    @endif
                    <div class="ann-card-img-overlay">
                        <span class="ann-type-badge type-{{ $ann->announcement_type }}">
                            <i class="fas {{ $t['icon'] }}"></i> {{ $t['label'] }}
                        </span>
                        <span class="ann-status-pill {{ $ann->is_active ? 'pill-active' : 'pill-inactive' }}">
                            {{ $ann->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="ann-card-body">
                    <h6 class="ann-card-title">{{ $ann->title }}</h6>
                    <p class="ann-card-excerpt">{{ $ann->content }}</p>
                    <div class="ann-card-meta">
                        <span><i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($ann->created_at)->format('M d, Y') }}
                        </span>
                        @if($ann->start_date)
                            <span><i class="fas fa-play"></i>
                                {{ \Carbon\Carbon::parse($ann->start_date)->format('M d') }}
                                @if($ann->end_date)
                                    – {{ \Carbon\Carbon::parse($ann->end_date)->format('M d, Y') }}
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="ann-card-footer">
                    <a href="{{ route('medical_centre.announcements.show', $ann->id) }}"
                       class="ann-action-btn btn-view">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('medical_centre.announcements.edit', $ann->id) }}"
                       class="ann-action-btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST"
                          action="{{ route('medical_centre.announcements.toggle', $ann->id) }}"
                          style="flex:1;margin:0;">
                        @csrf
                        <button type="submit"
                                class="ann-action-btn w-100 {{ $ann->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                            <i class="fas {{ $ann->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $ann->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('medical_centre.announcements.destroy', $ann->id) }}"
                          style="margin:0;"
                          onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ann-action-btn btn-del" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($announcements->hasPages())
        <div class="ann-pagination">
            <span>Showing {{ $announcements->firstItem() }} – {{ $announcements->lastItem() }} of {{ $announcements->total() }}</span>
            {{ $announcements->links('pagination::bootstrap-5') }}
        </div>
    @endif
@else
    <div class="ann-card" style="border-radius:14px;">
        <div class="ann-empty">
            <i class="fas fa-bullhorn"></i>
            <h5>No announcements found</h5>
            <p>
                @if($search || $type || $status)
                    No announcements match your filter.
                    <a href="{{ route('medical_centre.announcements') }}" style="color:var(--mc-primary);font-weight:700;">Clear filters</a>
                @else
                    Create your first announcement to get started.
                @endif
            </p>
            <a href="{{ route('medical_centre.announcements.create') }}" class="mc-btn mc-btn-primary mt-2" style="display:inline-flex;">
                <i class="fas fa-plus"></i> New Announcement
            </a>
        </div>
    </div>
@endif

@endsection
