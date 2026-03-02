{{-- resources/views/medical_centre/doctors/index.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Doctors')
@section('page-title', 'Doctors')

@section('content')
<style>
.mc-page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
}
.mc-page-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin: 0 0 .2rem; }
.mc-page-sub   { font-size: .82rem; color: var(--text-muted); margin: 0; }

/* ── Stats Row ── */
.doc-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 767px) { .doc-stats-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .doc-stats-row { grid-template-columns: 1fr 1fr; } }

.doc-stat-card {
    background: #fff; border-radius: 12px;
    border: 1px solid var(--border);
    padding: 1rem 1.1rem;
    display: flex; align-items: center; gap: .85rem;
    box-shadow: var(--shadow-sm);
}
.doc-stat-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
}
.doc-stat-body h6 { font-size: .72rem; font-weight: 700; color: var(--text-muted); margin: 0 0 .15rem; text-transform: uppercase; letter-spacing: .05em; }
.doc-stat-body span { font-size: 1.4rem; font-weight: 800; color: var(--text-dark); line-height: 1; }

/* ── Filter Card ── */
.doc-filter-card {
    background: #fff; border-radius: 12px;
    border: 1px solid var(--border);
    padding: .85rem 1rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
    display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
}
.doc-filter-card input,
.doc-filter-card select {
    border: 1.5px solid var(--border); border-radius: 8px;
    padding: .42rem .8rem; font-size: .8rem; font-weight: 600;
    color: var(--text-dark); background: #f8fbff;
    font-family: inherit; outline: none;
    transition: border-color .2s;
}
.doc-filter-card input { flex: 1; min-width: 180px; }
.doc-filter-card input:focus,
.doc-filter-card select:focus { border-color: var(--mc-primary); }
.doc-filter-card select { cursor: pointer; }
.doc-filter-btn {
    padding: .45rem 1rem; border-radius: 8px; border: none;
    font-size: .8rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: var(--transition);
    display: inline-flex; align-items: center; gap: .4rem;
}
.doc-filter-btn-primary { background: var(--mc-primary); color: #fff; }
.doc-filter-btn-primary:hover { background: var(--mc-secondary); }
.doc-filter-btn-clear { background: #f4f7fb; color: var(--text-muted); }
.doc-filter-btn-clear:hover { background: #e9ecef; color: var(--text-dark); }

/* ── Doctor Table Card ── */
.doc-table-card {
    background: #fff; border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.doc-table-head {
    padding: .9rem 1.1rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    background: #fafbfc;
}
.doc-table-head h6 { font-size: .9rem; font-weight: 800; color: var(--text-dark); margin: 0; }
.doc-table-head span { font-size: .75rem; color: var(--text-muted); font-weight: 600; }

.doc-table { width: 100%; border-collapse: collapse; }
.doc-table thead th {
    font-size: .68rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .06em; color: var(--text-muted);
    padding: .7rem 1rem; border-bottom: 1px solid var(--border);
    background: #fafbfc; white-space: nowrap;
}
.doc-table tbody tr { border-bottom: 1px solid #f5f7fa; transition: background .15s; }
.doc-table tbody tr:last-child { border-bottom: none; }
.doc-table tbody tr:hover { background: #f8fbff; }
.doc-table tbody td { padding: .85rem 1rem; vertical-align: middle; }

/* Doctor Info Cell */
.doc-info { display: flex; align-items: center; gap: .75rem; }
.doc-avatar {
    width: 40px; height: 40px; border-radius: 10px;
    object-fit: cover; flex-shrink: 0;
    background: linear-gradient(135deg, var(--mc-primary), var(--mc-secondary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .85rem; font-weight: 800;
    overflow: hidden;
}
.doc-avatar img { width: 100%; height: 100%; object-fit: cover; }
.doc-info-text h6 { font-size: .83rem; font-weight: 700; color: var(--text-dark); margin: 0 0 .15rem; }
.doc-info-text span { font-size: .72rem; color: var(--text-muted); }

/* Badges */
.doc-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 99px;
    font-size: .68rem; font-weight: 800; white-space: nowrap;
}
.badge-approved  { background: #d1fae5; color: #065f46; }
.badge-pending   { background: #fff3cd; color: #92400e; }
.badge-rejected  { background: #fee2e2; color: #991b1b; }
.badge-permanent { background: #e0e7ff; color: #3730a3; }
.badge-visiting  { background: #f0fdf4; color: #166534; }
.badge-temporary { background: #fff7ed; color: #9a3412; }

/* Rating Stars */
.doc-rating {
    display: flex; align-items: center; gap: .3rem;
    font-size: .75rem; color: #f59e0b; font-weight: 700;
}
.doc-rating span { color: var(--text-muted); font-weight: 600; font-size: .7rem; }

/* Action Buttons */
.doc-actions { display: flex; align-items: center; gap: .4rem; }
.doc-action-btn {
    width: 30px; height: 30px; border-radius: 7px;
    border: none; cursor: pointer; font-size: .75rem;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition); font-family: inherit;
    text-decoration: none;
}
.btn-view     { background: #e8f0fe; color: #2969bf; }
.btn-view:hover { background: #2969bf; color: #fff; }
.btn-approve  { background: #d1fae5; color: #065f46; }
.btn-approve:hover { background: #059669; color: #fff; }
.btn-reject   { background: #fff3cd; color: #92400e; }
.btn-reject:hover { background: #d97706; color: #fff; }
.btn-remove   { background: #fee2e2; color: #991b1b; }
.btn-remove:hover { background: #e74c3c; color: #fff; }

/* Empty State */
.doc-empty {
    padding: 3.5rem 1rem; text-align: center; color: var(--text-muted);
}
.doc-empty i { font-size: 3rem; display: block; margin-bottom: .75rem; opacity: .25; }
.doc-empty h5 { font-size: 1rem; font-weight: 700; margin-bottom: .35rem; }
.doc-empty p  { font-size: .82rem; }

/* Pagination */
.doc-pagination {
    display: flex; justify-content: space-between; align-items: center;
    padding: .85rem 1.1rem; border-top: 1px solid var(--border);
    font-size: .78rem; color: var(--text-muted); flex-wrap: wrap; gap: .5rem;
}
.doc-pagination .pagination { margin: 0; }
.doc-pagination .page-link {
    font-size: .75rem; padding: .3rem .6rem;
    color: var(--text-dark); border-color: var(--border);
}
.doc-pagination .page-item.active .page-link {
    background: var(--mc-primary); border-color: var(--mc-primary);
}

/* Responsive table */
@media (max-width: 767px) {
    .doc-table thead { display: none; }
    .doc-table tbody tr { display: block; padding: .75rem; margin-bottom: .5rem; border: 1px solid var(--border); border-radius: 10px; }
    .doc-table tbody td { display: flex; justify-content: space-between; align-items: center; padding: .3rem .25rem; border: none; font-size: .8rem; }
    .doc-table tbody td::before { content: attr(data-label); font-size: .68rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
}
</style>

{{-- ── Page Header ── --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-user-md me-2" style="color:var(--mc-primary);"></i>
            Doctors
        </h4>
        <p class="mc-page-sub">Manage doctors at {{ $mc->name }}</p>
    </div>
</div>

{{-- ── Alerts ── --}}
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

{{-- ── Stats Row ── --}}
<div class="doc-stats-row">
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#e8f0fe;color:#2969bf;">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="doc-stat-body">
            <h6>Total</h6>
            <span>{{ $stats['total'] }}</span>
        </div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#d1fae5;color:#065f46;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="doc-stat-body">
            <h6>Approved</h6>
            <span>{{ $stats['approved'] }}</span>
        </div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#fff3cd;color:#92400e;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="doc-stat-body">
            <h6>Pending</h6>
            <span>{{ $stats['pending'] }}</span>
        </div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#fee2e2;color:#991b1b;">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="doc-stat-body">
            <h6>Rejected</h6>
            <span>{{ $stats['rejected'] }}</span>
        </div>
    </div>
</div>

{{-- ── Filters ── --}}
<form action="{{ route('medical_centre.doctors') }}" method="GET" class="doc-filter-card">
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Search by name, specialization, SLMC no...">

    <select name="status">
        <option value="">All Status</option>
        <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>

    <select name="type">
        <option value="">All Types</option>
        <option value="permanent" {{ $type === 'permanent' ? 'selected' : '' }}>Permanent</option>
        <option value="visiting"  {{ $type === 'visiting'  ? 'selected' : '' }}>Visiting</option>
        <option value="temporary" {{ $type === 'temporary' ? 'selected' : '' }}>Temporary</option>
    </select>

    <button type="submit" class="doc-filter-btn doc-filter-btn-primary">
        <i class="fas fa-search"></i> Filter
    </button>
    @if($search || $status || $type)
        <a href="{{ route('medical_centre.doctors') }}" class="doc-filter-btn doc-filter-btn-clear">
            <i class="fas fa-times"></i> Clear
        </a>
    @endif
</form>

{{-- ── Doctors Table ── --}}
<div class="doc-table-card">
    <div class="doc-table-head">
        <h6><i class="fas fa-user-md me-2" style="color:var(--mc-primary);"></i>Doctors List</h6>
        <span>{{ $doctors->total() }} {{ Str::plural('doctor', $doctors->total()) }} found</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="doc-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>SLMC No.</th>
                    <th>Type</th>
                    <th>Rating</th>
                    <th>Fee (LKR)</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $index => $doc)
                    <tr>
                        {{-- # --}}
                        <td data-label="#">
                            <span style="font-size:.75rem;color:var(--text-muted);font-weight:700;">
                                {{ $doctors->firstItem() + $index }}
                            </span>
                        </td>

                        {{-- Doctor Info --}}
                        <td data-label="Doctor">
                            <div class="doc-info">
                                <div class="doc-avatar">
                                    @if($doc->profile_image)
                                        <img src="{{ asset('storage/' . $doc->profile_image) }}"
                                             alt="{{ $doc->name }}">
                                    @else
                                        {{ strtoupper(substr($doc->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="doc-info-text">
                                    <h6>Dr. {{ $doc->name }}</h6>
                                    <span>{{ $doc->phone ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Specialization --}}
                        <td data-label="Specialization">
                            <span style="font-size:.78rem;font-weight:600;color:var(--text-dark);">
                                {{ $doc->specialization ?? '—' }}
                            </span>
                        </td>

                        {{-- SLMC --}}
                        <td data-label="SLMC No.">
                            <span style="font-size:.75rem;color:var(--text-muted);font-weight:600;">
                                {{ $doc->slmc_number }}
                            </span>
                        </td>

                        {{-- Employment Type --}}
                        <td data-label="Type">
                            <span class="doc-badge badge-{{ $doc->employment_type }}">
                                {{ ucfirst($doc->employment_type) }}
                            </span>
                        </td>

                        {{-- Rating --}}
                        <td data-label="Rating">
                            <div class="doc-rating">
                                <i class="fas fa-star"></i>
                                {{ number_format($doc->rating, 1) }}
                                <span>({{ $doc->total_ratings }})</span>
                            </div>
                        </td>

                        {{-- Fee --}}
                        <td data-label="Fee">
                            <span style="font-size:.78rem;font-weight:700;color:var(--text-dark);">
                                {{ $doc->consultation_fee ? number_format($doc->consultation_fee) : '—' }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td data-label="Status">
                            <span class="doc-badge badge-{{ $doc->workplace_status }}">
                                @if($doc->workplace_status === 'approved')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($doc->workplace_status === 'pending')
                                    <i class="fas fa-clock"></i>
                                @else
                                    <i class="fas fa-times-circle"></i>
                                @endif
                                {{ ucfirst($doc->workplace_status) }}
                            </span>
                        </td>

                        {{-- Joined --}}
                        <td data-label="Joined">
                            <span style="font-size:.73rem;color:var(--text-muted);font-weight:600;">
                                {{ \Carbon\Carbon::parse($doc->joined_at)->format('M d, Y') }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td data-label="Actions">
                            <div class="doc-actions" style="justify-content:center;">

                                {{-- View --}}
                                <a href="{{ route('medical_centre.doctors.show', $doc->workplace_id) }}"
                                   class="doc-action-btn btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Approve (only if pending) --}}
                                @if($doc->workplace_status === 'pending')
                                    <form method="POST"
                                          action="{{ route('medical_centre.doctors.approve', $doc->workplace_id) }}"
                                          style="margin:0;"
                                          onsubmit="return confirm('Approve Dr. {{ addslashes($doc->name) }}?')">
                                        @csrf
                                        <button type="submit" class="doc-action-btn btn-approve" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('medical_centre.doctors.reject', $doc->workplace_id) }}"
                                          style="margin:0;"
                                          onsubmit="return confirm('Reject Dr. {{ addslashes($doc->name) }}?')">
                                        @csrf
                                        <button type="submit" class="doc-action-btn btn-reject" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Reject approved doctor --}}
                                @if($doc->workplace_status === 'approved')
                                    <form method="POST"
                                          action="{{ route('medical_centre.doctors.reject', $doc->workplace_id) }}"
                                          style="margin:0;"
                                          onsubmit="return confirm('Reject Dr. {{ addslashes($doc->name) }}?')">
                                        @csrf
                                        <button type="submit" class="doc-action-btn btn-reject" title="Reject">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Remove --}}
                                <form method="POST"
                                      action="{{ route('medical_centre.doctors.remove', $doc->workplace_id) }}"
                                      style="margin:0;"
                                      onsubmit="return confirm('Remove Dr. {{ addslashes($doc->name) }} from your medical centre?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="doc-action-btn btn-remove" title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="doc-empty">
                                <i class="fas fa-user-md"></i>
                                <h5>No doctors found</h5>
                                <p>
                                    @if($search || $status || $type)
                                        No doctors match your filter criteria.
                                        <a href="{{ route('medical_centre.doctors') }}"
                                           style="color:var(--mc-primary);font-weight:700;">Clear filters</a>
                                    @else
                                        No doctors have joined your medical centre yet.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($doctors->hasPages())
        <div class="doc-pagination">
            <span>
                Showing {{ $doctors->firstItem() }} – {{ $doctors->lastItem() }}
                of {{ $doctors->total() }} doctors
            </span>
            {{ $doctors->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
