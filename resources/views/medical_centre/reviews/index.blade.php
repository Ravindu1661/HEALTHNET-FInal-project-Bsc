{{-- resources/views/medical_centre/reviews/index.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Reviews & Ratings')
@section('page-title', 'Reviews & Ratings')

@section('content')
<style>
.mc-page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.mc-page-title { font-size:1.25rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.mc-page-sub   { font-size:.82rem; color:var(--text-muted); margin:0; }

/* Stats */
.rev-stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
@media(max-width:900px){ .rev-stats-row { grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .rev-stats-row { grid-template-columns:1fr 1fr; } }
.rev-stat-card { background:#fff; border-radius:12px; border:1px solid var(--border); padding:1rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:var(--shadow-sm); }
.rev-stat-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.rev-stat-body h6 { font-size:.7rem; font-weight:700; color:var(--text-muted); margin:0 0 .15rem; text-transform:uppercase; letter-spacing:.05em; }
.rev-stat-body span { font-size:1.4rem; font-weight:800; color:var(--text-dark); line-height:1; }

/* Rating Overview */
.rev-overview-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); padding:1.25rem; margin-bottom:1.25rem; display:grid; grid-template-columns:160px 1fr; gap:1.5rem; align-items:center; }
@media(max-width:600px){ .rev-overview-card { grid-template-columns:1fr; } }
.rev-big-rating { text-align:center; }
.rev-big-rating .score { font-size:3.5rem; font-weight:900; color:var(--text-dark); line-height:1; }
.rev-big-rating .stars { color:#f59e0b; font-size:1.1rem; letter-spacing:.1rem; margin:.3rem 0; }
.rev-big-rating small { font-size:.75rem; color:var(--text-muted); font-weight:600; }
.rev-bar-row { display:flex; align-items:center; gap:.6rem; margin-bottom:.45rem; }
.rev-bar-row:last-child { margin-bottom:0; }
.rev-bar-label { font-size:.72rem; font-weight:700; color:var(--text-muted); width:35px; text-align:right; flex-shrink:0; }
.rev-bar-track { flex:1; height:7px; background:#f0f0f0; border-radius:99px; overflow:hidden; }
.rev-bar-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg,#f59e0b,#fbbf24); transition:width .5s; }
.rev-bar-count { font-size:.7rem; font-weight:700; color:var(--text-muted); width:28px; }

/* Filter */
.rev-filter-card { background:#fff; border-radius:12px; border:1px solid var(--border); padding:.85rem 1rem; margin-bottom:1.25rem; box-shadow:var(--shadow-sm); display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
.rev-filter-card input,
.rev-filter-card select { border:1.5px solid var(--border); border-radius:8px; padding:.42rem .8rem; font-size:.8rem; font-weight:600; color:var(--text-dark); background:#f8fbff; font-family:inherit; outline:none; }
.rev-filter-card input { flex:1; min-width:180px; }
.rev-filter-card input:focus,
.rev-filter-card select:focus { border-color:var(--mc-primary); }
.rev-filter-btn { padding:.45rem 1rem; border-radius:8px; border:none; font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); display:inline-flex; align-items:center; gap:.4rem; text-decoration:none; }
.rev-filter-primary { background:var(--mc-primary); color:#fff; }
.rev-filter-primary:hover { background:var(--mc-secondary); }
.rev-filter-clear { background:#f4f7fb; color:var(--text-muted); }
.rev-filter-clear:hover { background:#e9ecef; color:var(--text-dark); }

/* Review Cards */
.rev-grid { display:flex; flex-direction:column; gap:1rem; margin-bottom:1.25rem; }
.rev-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); padding:1.1rem 1.25rem; transition:box-shadow .2s; }
.rev-card:hover { box-shadow:var(--shadow-md); }
.rev-card-top { display:flex; align-items:flex-start; gap:.85rem; margin-bottom:.75rem; }
.rev-avatar { width:42px; height:42px; border-radius:50%; object-fit:cover; flex-shrink:0; background:linear-gradient(135deg,var(--mc-primary),var(--mc-secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:.9rem; font-weight:800; }
.rev-avatar img { width:42px; height:42px; border-radius:50%; object-fit:cover; }
.rev-info { flex:1; min-width:0; }
.rev-name { font-size:.88rem; font-weight:800; color:var(--text-dark); margin:0 0 .15rem; }
.rev-meta { font-size:.7rem; color:var(--text-muted); font-weight:600; }
.rev-stars { color:#f59e0b; font-size:.8rem; letter-spacing:.05rem; }
.rev-stars-empty { color:#e5e7eb; }
.rev-badge { display:inline-flex; align-items:center; gap:.25rem; padding:.15rem .5rem; border-radius:99px; font-size:.62rem; font-weight:800; margin-left:.4rem; }
.badge-replied { background:#d1fae5; color:#065f46; }
.badge-pending { background:#fff3cd; color:#92400e; }

.rev-text { font-size:.82rem; color:var(--text-muted); line-height:1.7; margin:0 0 .75rem; }
.rev-reply-box { background:#f8fbff; border-left:3px solid var(--mc-primary); border-radius:0 8px 8px 0; padding:.6rem .85rem; margin-bottom:.5rem; }
.rev-reply-box span { font-size:.72rem; font-weight:800; color:var(--mc-primary); display:block; margin-bottom:.2rem; }
.rev-reply-box p { font-size:.8rem; color:var(--text-muted); margin:0; line-height:1.6; }
.rev-card-footer { display:flex; align-items:center; justify-content:flex-end; }
.rev-view-btn { display:inline-flex; align-items:center; gap:.3rem; padding:.35rem .85rem; border-radius:7px; background:#e8f0fe; color:#2969bf; font-size:.75rem; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:var(--transition); }
.rev-view-btn:hover { background:#2969bf; color:#fff; }

/* Empty */
.rev-empty { padding:3.5rem 1rem; text-align:center; color:var(--text-muted); background:#fff; border-radius:14px; border:1px solid var(--border); }
.rev-empty i { font-size:3rem; display:block; margin-bottom:.75rem; opacity:.2; }
.rev-empty h5 { font-size:1rem; font-weight:700; margin-bottom:.35rem; }
.rev-empty p  { font-size:.82rem; }

/* Pagination */
.rev-pagination { display:flex; justify-content:space-between; align-items:center; background:#fff; border-radius:12px; border:1px solid var(--border); padding:.85rem 1.1rem; font-size:.78rem; color:var(--text-muted); flex-wrap:wrap; gap:.5rem; box-shadow:var(--shadow-sm); }
.rev-pagination .pagination { margin:0; }
.rev-pagination .page-link { font-size:.75rem; padding:.3rem .6rem; color:var(--text-dark); border-color:var(--border); }
.rev-pagination .page-item.active .page-link { background:var(--mc-primary); border-color:var(--mc-primary); }
</style>

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-star me-2" style="color:#f59e0b;"></i>Reviews & Ratings
        </h4>
        <p class="mc-page-sub">Patient reviews for {{ $mc->name }}</p>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stats --}}
<div class="rev-stats-row">
    <div class="rev-stat-card">
        <div class="rev-stat-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-star"></i></div>
        <div class="rev-stat-body"><h6>Avg Rating</h6><span>{{ $stats['average'] }}</span></div>
    </div>
    <div class="rev-stat-card">
        <div class="rev-stat-icon" style="background:#e8f0fe;color:#2969bf;"><i class="fas fa-comments"></i></div>
        <div class="rev-stat-body"><h6>Total</h6><span>{{ $stats['total'] }}</span></div>
    </div>
    <div class="rev-stat-card">
        <div class="rev-stat-icon" style="background:#d1fae5;color:#065f46;"><i class="fas fa-award"></i></div>
        <div class="rev-stat-body"><h6>5 Star</h6><span>{{ $stats['five_star'] }}</span></div>
    </div>
    <div class="rev-stat-card">
        <div class="rev-stat-icon" style="background:#f3f4f6;color:#6b7280;"><i class="fas fa-reply"></i></div>
        <div class="rev-stat-body"><h6>Replied</h6><span>{{ $stats['replied'] }}</span></div>
    </div>
</div>

{{-- Rating Overview --}}
@if($stats['total'] > 0)
<div class="rev-overview-card">
    <div class="rev-big-rating">
        <div class="score">{{ $stats['average'] }}</div>
        <div class="stars">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= round($stats['average']))
                    <i class="fas fa-star"></i>
                @else
                    <i class="far fa-star rev-stars-empty"></i>
                @endif
            @endfor
        </div>
        <small>Based on {{ $stats['total'] }} review{{ $stats['total'] > 1 ? 's' : '' }}</small>
    </div>
    <div>
        @foreach($distribution as $star => $data)
            <div class="rev-bar-row">
                <span class="rev-bar-label">{{ $star }} <i class="fas fa-star" style="color:#f59e0b;font-size:.6rem;"></i></span>
                <div class="rev-bar-track">
                    <div class="rev-bar-fill" style="width:{{ $data['percent'] }}%;"></div>
                </div>
                <span class="rev-bar-count">{{ $data['count'] }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Filters --}}
<form action="{{ route('medical_centre.reviews') }}" method="GET" class="rev-filter-card">
    <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or review...">
    <select name="rating">
        <option value="">All Ratings</option>
        @for($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" {{ $rating == $i ? 'selected' : '' }}>
                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
            </option>
        @endfor
    </select>
    <select name="status">
        <option value="">All Status</option>
        <option value="replied" {{ $status === 'replied' ? 'selected' : '' }}>Replied</option>
        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending Reply</option>
    </select>
    <button type="submit" class="rev-filter-btn rev-filter-primary">
        <i class="fas fa-search"></i> Filter
    </button>
    @if($search || $rating || $status)
        <a href="{{ route('medical_centre.reviews') }}" class="rev-filter-btn rev-filter-clear">
            <i class="fas fa-times"></i> Clear
        </a>
    @endif
</form>

{{-- Review List --}}
@if($reviews->count())
    <div class="rev-grid">
        @foreach($reviews as $rev)
            @php
                $name = trim(($rev->first_name ?? '') . ' ' . ($rev->last_name ?? ''));
                $initial = strtoupper(substr($name ?: 'P', 0, 1));
                $hasReply = !empty($rev->reply ?? null);
            @endphp
            <div class="rev-card">
                <div class="rev-card-top">
                    <div class="rev-avatar">
                        @if(!empty($rev->reviewer_photo))
                            <img src="{{ asset('storage/' . $rev->reviewer_photo) }}" alt="{{ $name }}">
                        @else
                            {{ $initial }}
                        @endif
                    </div>
                    <div class="rev-info">
                        <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;">
                            <p class="rev-name">{{ $name ?: 'Anonymous Patient' }}</p>
                            <span class="rev-badge {{ $hasReply ? 'badge-replied' : 'badge-pending' }}">
                                <i class="fas {{ $hasReply ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                {{ $hasReply ? 'Replied' : 'Pending Reply' }}
                            </span>
                        </div>
                        <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                            <span class="rev-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star {{ $i <= $rev->rating ? '' : 'rev-stars-empty' }}"></i>
                                @endfor
                            </span>
                            <span class="rev-meta">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($rev->created_at)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                @if(!empty($rev->review))
                    <p class="rev-text">{{ $rev->review }}</p>
                @else
                    <p class="rev-text" style="font-style:italic;opacity:.6;">No written review provided.</p>
                @endif

                @if($hasReply)
                    <div class="rev-reply-box">
                        <span><i class="fas fa-reply me-1"></i>Your Reply</span>
                        <p>{{ $rev->reply }}</p>
                    </div>
                @endif

                <div class="rev-card-footer">
                    <a href="{{ route('medical_centre.reviews.show', $rev->id) }}" class="rev-view-btn">
                        <i class="fas fa-eye"></i>
                        {{ $hasReply ? 'View / Edit Reply' : 'View & Reply' }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($reviews->hasPages())
        <div class="rev-pagination">
            <span>Showing {{ $reviews->firstItem() }} – {{ $reviews->lastItem() }} of {{ $reviews->total() }}</span>
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    @endif
@else
    <div class="rev-empty">
        <i class="fas fa-star"></i>
        <h5>No reviews found</h5>
        <p>
            @if($search || $rating || $status)
                No reviews match your filter.
                <a href="{{ route('medical_centre.reviews') }}" style="color:var(--mc-primary);font-weight:700;">Clear filters</a>
            @else
                Patient reviews will appear here once submitted.
            @endif
        </p>
    </div>
@endif

@endsection
