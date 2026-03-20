@extends('admin.layouts.master')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@push('styles')
<style>
:root {
    --green:#42a649; --navy:#1a3a5c; --border:#e4e8ed;
    --bg:#f4f6f9; --card:#fff; --muted:#6b7a8d; --radius:12px;
}
.an-header {
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.75rem; margin-bottom:1.2rem;
}
.an-title  { font-size:1.1rem; font-weight:700; color:var(--navy); margin:0; }
.btn-create {
    display:inline-flex; align-items:center; gap:.4rem;
    background:linear-gradient(135deg,var(--green),#2d7a32);
    color:#fff; border:none; padding:.5rem 1.1rem;
    border-radius:25px; font-size:.83rem; font-weight:700;
    text-decoration:none; transition:all .2s;
    box-shadow:0 3px 10px rgba(66,166,73,.3);
}
.btn-create:hover { color:#fff; transform:translateY(-1px); }

/* Filter bar */
.filter-bar {
    background:var(--card); border-radius:var(--radius);
    border:1px solid var(--border); padding:.85rem 1rem;
    margin-bottom:1rem; display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-end;
}
.filter-bar .f-group { display:flex; flex-direction:column; gap:.25rem; }
.filter-bar label { font-size:.7rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.04em; }
.f-ctrl {
    padding:.42rem .75rem; border:1.5px solid var(--border);
    border-radius:8px; font-size:.82rem; color:var(--navy);
    background:#fff; min-width:140px; transition:border-color .2s;
}
.f-ctrl:focus { border-color:var(--green); outline:none; }
.btn-filter {
    padding:.42rem .9rem; border-radius:8px;
    font-size:.8rem; font-weight:600; cursor:pointer;
    border:1.5px solid var(--border); background:#fff; color:var(--navy);
    display:inline-flex; align-items:center; gap:.3rem; transition:all .18s;
}
.btn-filter:hover { border-color:var(--navy); }
.btn-filter.primary { background:var(--navy); border-color:var(--navy); color:#fff; }
.btn-filter.primary:hover { background:#12294a; }

/* Stats row */
.an-stats {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr));
    gap:.75rem; margin-bottom:1rem;
}
.an-stat {
    background:var(--card); border-radius:var(--radius);
    border:1px solid var(--border); padding:.9rem 1rem;
    text-align:center;
}
.an-stat-num  { font-size:1.5rem; font-weight:800; color:var(--navy); line-height:1; }
.an-stat-lbl  { font-size:.7rem; color:var(--muted); font-weight:600; margin-top:.2rem; }

/* Table card */
.an-card {
    background:var(--card); border-radius:var(--radius);
    border:1px solid var(--border); overflow:hidden;
}
.an-table { width:100%; border-collapse:collapse; }
.an-table thead tr { background:var(--bg); }
.an-table th {
    padding:.65rem .9rem; text-align:left;
    font-size:.7rem; font-weight:700; color:var(--muted);
    text-transform:uppercase; letter-spacing:.05em;
    border-bottom:1px solid var(--border); white-space:nowrap;
}
.an-table td {
    padding:.75rem .9rem; font-size:.82rem;
    border-bottom:1px solid #f5f7fa; vertical-align:middle;
}
.an-table tr:last-child td { border-bottom:none; }
.an-table tr:hover td { background:#fafbfc; }

/* Type badge */
.type-badge {
    display:inline-block; padding:.18rem .6rem;
    border-radius:20px; font-size:.66rem; font-weight:700;
    white-space:nowrap;
}
.tb-health_camp   { background:#dbeafe; color:#1e40af; }
.tb-awareness     { background:#e0f2fe; color:#0369a1; }
.tb-special_offer { background:#fef3c7; color:#92400e; }
.tb-new_service   { background:#d1fae5; color:#065f46; }
.tb-emergency     { background:#fee2e2; color:#991b1b; }
.tb-general       { background:#f3f4f6; color:#374151; }

.status-pill {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.18rem .6rem; border-radius:20px;
    font-size:.66rem; font-weight:700;
}
.sp-active   { background:#d1fae5; color:#065f46; }
.sp-inactive { background:#fee2e2; color:#991b1b; }

.an-title-cell { font-weight:600; color:var(--navy); max-width:200px; }
.an-title-cell a { color:var(--navy); text-decoration:none; }
.an-title-cell a:hover { color:var(--green); }

.date-range { font-size:.75rem; color:var(--muted); white-space:nowrap; }

/* Actions */
.actions { display:flex; gap:.3rem; }
.act-btn {
    width:30px; height:30px; border-radius:7px;
    border:1.5px solid var(--border); background:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem; cursor:pointer; text-decoration:none;
    color:var(--muted); transition:all .15s;
}
.act-btn:hover       { border-color:var(--navy); color:var(--navy); }
.act-btn.edit:hover  { border-color:var(--green); color:var(--green); }
.act-btn.del:hover   { border-color:#dc2626; color:#dc2626; background:#fef2f2; }
.act-btn.toggle:hover{ border-color:#f59e0b; color:#f59e0b; }

/* Empty */
.an-empty { text-align:center; padding:3rem 1rem; color:#c0c8d4; }
.an-empty i { font-size:2.5rem; display:block; margin-bottom:.6rem; }
.an-empty p { font-size:.85rem; margin:0; }

/* Pagination */
.an-pagination { padding:.8rem 1rem; border-top:1px solid var(--border); }
</style>
@endpush

@section('content')

{{-- Flash --}}
@foreach(['success'=>'success','error'=>'danger'] as $sk=>$sc)
    @if(session($sk))
        <div class="alert alert-{{ $sc }} alert-dismissible fade show mb-3"
             style="border-radius:10px;font-size:.83rem">
            <i class="fas fa-{{ $sc==='success'?'check-circle':'exclamation-circle' }} me-2"></i>
            {{ session($sk) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

{{-- Header --}}
<div class="an-header">
    <h1 class="an-title"><i class="fas fa-bullhorn me-2" style="color:var(--green);"></i>Announcements</h1>
    <a href="{{ route('admin.announcements.create') }}" class="btn-create">
        <i class="fas fa-plus"></i> New Announcement
    </a>
</div>

{{-- Stats --}}
@php
    $total    = \App\Models\Announcement::count();
    $active   = \App\Models\Announcement::where('is_active',1)->count();
    $inactive = $total - $active;
    $today    = \App\Models\Announcement::whereDate('created_at', today())->count();
@endphp
<div class="an-stats">
    <div class="an-stat">
        <div class="an-stat-num">{{ $total }}</div>
        <div class="an-stat-lbl">Total</div>
    </div>
    <div class="an-stat">
        <div class="an-stat-num" style="color:var(--green);">{{ $active }}</div>
        <div class="an-stat-lbl">Active</div>
    </div>
    <div class="an-stat">
        <div class="an-stat-num" style="color:#dc2626;">{{ $inactive }}</div>
        <div class="an-stat-lbl">Inactive</div>
    </div>
    <div class="an-stat">
        <div class="an-stat-num" style="color:#f59e0b;">{{ $today }}</div>
        <div class="an-stat-lbl">Today</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.announcements.index') }}">
<div class="filter-bar">
    <div class="f-group" style="flex:1;min-width:180px;">
        <label>Search</label>
        <input type="text" name="search" class="f-ctrl"
               placeholder="Title or content…"
               value="{{ request('search') }}">
    </div>
    <div class="f-group">
        <label>Type</label>
        <select name="type" class="f-ctrl">
            <option value="">All Types</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>
                    {{ ucwords(str_replace('_',' ',$t)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="f-group">
        <label>Status</label>
        <select name="active" class="f-ctrl">
            <option value="">All</option>
            <option value="1" {{ request('active')==='1'?'selected':'' }}>Active</option>
            <option value="0" {{ request('active')==='0'?'selected':'' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn-filter primary">
        <i class="fas fa-search"></i> Filter
    </button>
    <a href="{{ route('admin.announcements.index') }}" class="btn-filter">
        <i class="fas fa-times"></i> Clear
    </a>
</div>
</form>

{{-- Table --}}
<div class="an-card">
    @if($announcements->count() > 0)
    <div style="overflow-x:auto;">
        <table class="an-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Date Range</th>
                    <th>Status</th>
                    <th>Publisher</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $ann)
                <tr>
                    <td style="color:var(--muted);font-size:.75rem;">{{ $ann->id }}</td>
                    <td class="an-title-cell">
                        <a href="{{ route('admin.announcements.show', $ann) }}">
                            {{ \Illuminate\Support\Str::limit($ann->title, 45) }}
                        </a>
                        @if($ann->image_path)
                            <i class="fas fa-image" style="color:var(--muted);font-size:.65rem;margin-left:.3rem;" title="Has image"></i>
                        @endif
                    </td>
                    <td>
                        <span class="type-badge tb-{{ $ann->announcement_type }}">
                            {{ ucwords(str_replace('_',' ',$ann->announcement_type)) }}
                        </span>
                    </td>
                    <td class="date-range">
                        @if($ann->start_date || $ann->end_date)
                            {{ $ann->start_date ? \Carbon\Carbon::parse($ann->start_date)->format('d M Y') : '—' }}
                            →
                            {{ $ann->end_date ? \Carbon\Carbon::parse($ann->end_date)->format('d M Y') : '—' }}
                        @else
                            <span style="color:#bbb;">No limit</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-pill {{ $ann->is_active ? 'sp-active' : 'sp-inactive' }}">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i>
                            {{ $ann->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="font-size:.75rem;color:var(--muted);">
                        {{ ucfirst($ann->publisher_type) }}
                    </td>
                    <td style="font-size:.75rem;color:var(--muted);">
                        {{ $ann->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.announcements.show', $ann) }}"
                               class="act-btn" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.announcements.edit', $ann) }}"
                               class="act-btn edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.announcements.toggle', $ann) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn toggle"
                                        title="{{ $ann->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ $ann->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.announcements.destroy', $ann) }}"
                                  method="POST" style="display:inline;"
                                  onsubmit="return confirm('Delete this announcement?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn del" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($announcements->hasPages())
    <div class="an-pagination">
        {{ $announcements->links() }}
    </div>
    @endif

    @else
    <div class="an-empty">
        <i class="fas fa-bullhorn"></i>
        <p>No announcements found.</p>
        <a href="{{ route('admin.announcements.create') }}"
           style="font-size:.8rem;color:var(--green);text-decoration:none;display:block;margin-top:.5rem;">
            + Create your first announcement
        </a>
    </div>
    @endif
</div>

@endsection
