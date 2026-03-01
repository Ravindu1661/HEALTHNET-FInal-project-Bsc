@extends('doctor.layouts.master')

@section('title', 'My Patients')
@section('page-title', 'My Patients')

@push('styles')
<style>
/* ── Filter Card ── */
.filter-card { background:#fff; border-radius:14px; padding:1rem 1.2rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }

/* ── Stat Badges ── */
.stat-badges { display:flex; gap:.6rem; flex-wrap:wrap; margin-bottom:1.2rem; }
.sb-item { background:#fff; border-radius:10px; padding:.55rem 1rem;
    box-shadow:0 2px 8px rgba(0,0,0,.06); display:flex;
    align-items:center; gap:.45rem; }
.sb-item .sb-num { font-size:1.1rem; font-weight:800; color:#0d6efd; }
.sb-item .sb-lbl { font-size:.7rem; color:#888; font-weight:600; }

/* ── Patient Cards Grid ── */
.patients-grid { display:grid;
    grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));
    gap:1rem; }

.pt-card { background:#fff; border-radius:16px; padding:1.2rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); border:1px solid #f0f3f8;
    transition:all .2s; }
.pt-card:hover { transform:translateY(-3px);
    box-shadow:0 6px 20px rgba(13,110,253,.1);
    border-color:#d0e0ff; }

.pt-card-top { display:flex; align-items:center; gap:.8rem; margin-bottom:.9rem; }

.pt-avatar { width:52px; height:52px; border-radius:50%; flex-shrink:0;
    object-fit:cover; border:2px solid #e8edf5; }
.pt-avatar-init { width:52px; height:52px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#0d6efd,#6f42c1);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:1.1rem; font-weight:800; }

.pt-name { font-size:.88rem; font-weight:800; color:#1a1a1a;
    margin:0 0 .1rem; white-space:nowrap; overflow:hidden;
    text-overflow:ellipsis; }
.pt-email { font-size:.72rem; color:#888; margin:0;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

.pt-meta { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:.9rem; }
.pt-badge { background:#f0f5ff; color:#0d6efd; border-radius:8px;
    padding:.18rem .55rem; font-size:.68rem; font-weight:700;
    display:flex; align-items:center; gap:.25rem; }
.pt-badge.green { background:#f0fdf4; color:#198754; }
.pt-badge.grey  { background:#f8f9fa; color:#666; }
.pt-badge.red   { background:#fff5f5; color:#dc3545; }

.pt-stats { display:flex; gap:.5rem; margin-bottom:.9rem; }
.pt-stat { flex:1; background:#f8f9fb; border-radius:10px;
    padding:.45rem .4rem; text-align:center; }
.pt-stat .n { font-size:.95rem; font-weight:800; color:#0d6efd; }
.pt-stat .l { font-size:.62rem; color:#aaa; font-weight:600; }
.pt-stat.green .n { color:#198754; }
.pt-stat.red   .n { color:#dc3545; }

.pt-last-visit { font-size:.72rem; color:#aaa; margin-bottom:.9rem;
    display:flex; align-items:center; gap:.3rem; }

.pt-actions { display:flex; gap:.5rem; }
.pt-btn { flex:1; padding:.35rem .5rem; border-radius:9px; border:none;
    font-size:.74rem; font-weight:700; cursor:pointer;
    transition:all .15s; text-decoration:none;
    display:flex; align-items:center; justify-content:center; gap:.3rem; }
.pt-btn.primary { background:#0d6efd; color:#fff; }
.pt-btn.primary:hover { background:#0b5ed7; color:#fff; }
.pt-btn.outline { background:#fff; color:#0d6efd;
    border:1px solid #d0e0ff; }
.pt-btn.outline:hover { background:#f0f5ff; }

/* ── Empty ── */
.empty-state { text-align:center; padding:4rem 1rem;
    color:#c0c8d4; background:#fff; border-radius:16px;
    box-shadow:0 2px 10px rgba(0,0,0,.05); }
.empty-state i { font-size:3rem; display:block; margin-bottom:.8rem; }
</style>
@endpush

@section('content')

{{-- Stat Badges --}}
<div class="stat-badges">
    <div class="sb-item">
        <i class="fas fa-users" style="color:#0d6efd;font-size:.9rem"></i>
        <div>
            <div class="sb-num">{{ $patients->total() }}</div>
            <div class="sb-lbl">Total Patients</div>
        </div>
    </div>
    <div class="sb-item">
        <i class="fas fa-calendar-check" style="color:#198754;font-size:.9rem"></i>
        <div>
            <div class="sb-num" style="color:#198754">
                {{ $patients->sum('completed_count') }}
            </div>
            <div class="sb-lbl">Completed Visits</div>
        </div>
    </div>
    <div class="sb-item">
        <i class="fas fa-calendar-day" style="color:#fd7e14;font-size:.9rem"></i>
        <div>
            <div class="sb-num" style="color:#fd7e14">
                {{ $patients->where('last_visit', '>=', now()->toDateString())->count() }}
            </div>
            <div class="sb-lbl">Visited Today</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="filter-card">
    <form method="GET" action="{{ route('doctor.patients.index') }}"
          class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label mb-1"
                   style="font-size:.75rem;font-weight:600;color:#555">Search</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" class="form-control"
                       placeholder="Name, phone, email..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1"
                   style="font-size:.75rem;font-weight:600;color:#555">Gender</label>
            <select name="gender" class="form-select form-select-sm">
                <option value="">All Genders</option>
                <option value="male"   {{ request('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other"  {{ request('gender') === 'other'  ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('doctor.patients.index') }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Patient Cards --}}
@if($patients->count() > 0)
<div class="patients-grid">
    @foreach($patients as $pt)
    @php
        $ptImg       = $pt->profile_image ?? null;
        $ptFirstName = $pt->firstname ?? $pt->first_name ?? '';
        $ptLastName  = $pt->lastname  ?? $pt->last_name  ?? '';
        $ptFullName  = trim($ptFirstName . ' ' . $ptLastName);
        $ptDob       = $pt->date_of_birth ?? $pt->dateofbirth ?? null;
        $ptGender    = $pt->gender ?? null;
        $lastVisit   = $pt->last_visit
                        ? \Carbon\Carbon::parse($pt->last_visit)->format('d M Y')
                        : 'No visits';
    @endphp

    <div class="pt-card">
        {{-- Top --}}
            @php
            $ptImg       = $pt->profile_image ?? null;
            $ptFullName  = $pt->name ?? '';
            $ptFirstName = explode(' ', trim($ptFullName))[0] ?? 'P';
        @endphp

        <div class="pt-card-top">
            @if($ptImg)
                <img src="{{ asset('storage/' . $ptImg) }}"
                    alt="{{ $ptFullName }}"
                    class="pt-avatar"
                    onerror="this.style.display='none';
                            this.nextElementSibling.style.display='flex'">
                <div class="pt-avatar-init" style="display:none">
                    {{ strtoupper(substr($ptFirstName, 0, 1)) }}
                </div>
            @else
                <div class="pt-avatar-init">
                    {{ strtoupper(substr($ptFirstName, 0, 1)) }}
                </div>
            @endif

            <div style="min-width:0">
                <p class="pt-name">{{ $ptFullName }}</p>
                <p class="pt-email">{{ $pt->phone ?? '' }}</p>
            </div>
        </div>


        {{-- Meta Badges --}}
        <div class="pt-meta">
            @if($ptGender)
            <span class="pt-badge grey">
                <i class="fas fa-{{ $ptGender === 'female' ? 'venus' : 'mars' }}"
                   style="font-size:.65rem"></i>
                {{ ucfirst($ptGender) }}
            </span>
            @endif
            @if($ptDob)
            <span class="pt-badge grey">
                <i class="fas fa-birthday-cake" style="font-size:.62rem"></i>
                {{ \Carbon\Carbon::parse($ptDob)->age }} yrs
            </span>
            @endif
            @if(!empty($pt->city))
            <span class="pt-badge grey">
                <i class="fas fa-map-marker-alt" style="font-size:.62rem"></i>
                {{ $pt->city }}
            </span>
            @endif
        </div>

        {{-- Visit Stats --}}
        <div class="pt-stats">
            <div class="pt-stat">
                <div class="n">{{ $pt->total_appointments ?? 0 }}</div>
                <div class="l">Total</div>
            </div>
            <div class="pt-stat green">
                <div class="n">{{ $pt->completed_count ?? 0 }}</div>
                <div class="l">Completed</div>
            </div>
            <div class="pt-stat">
                <div class="n" style="color:#fd7e14">
                    {{ ($pt->total_appointments ?? 0) - ($pt->completed_count ?? 0) }}
                </div>
                <div class="l">Other</div>
            </div>
        </div>

        {{-- Last Visit --}}
        <div class="pt-last-visit">
            <i class="fas fa-calendar-alt" style="font-size:.65rem"></i>
            Last visit: <strong>{{ $lastVisit }}</strong>
        </div>

        {{-- Actions --}}
        <div class="pt-actions">
            <a href="{{ route('doctor.patients.show', $pt->id) }}"
               class="pt-btn primary">
                <i class="fas fa-user"></i>Profile
            </a>
            <a href="{{ route('doctor.patients.history', $pt->id) }}"
               class="pt-btn outline">
                <i class="fas fa-history"></i>History
            </a>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($patients->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3
            px-1">
    <div style="font-size:.75rem;color:#888">
        Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }}
        of {{ $patients->total() }} patients
    </div>
    {{ $patients->appends(request()->query())->links() }}
</div>
@endif

@else
{{-- Empty State --}}
<div class="empty-state">
    <i class="fas fa-user-injured"></i>
    <h5 style="font-size:.95rem;font-weight:700;color:#555;margin:.3rem 0 .3rem">
        No patients found
    </h5>
    <p style="font-size:.8rem;margin:0">
        @if(request('search') || request('gender'))
            Try adjusting your search filters
        @else
            Patients who have appointments with you will appear here
        @endif
    </p>
    @if(request('search') || request('gender'))
    <a href="{{ route('doctor.patients.index') }}"
       class="btn btn-outline-primary btn-sm mt-3">
        <i class="fas fa-times me-1"></i>Clear Filters
    </a>
    @endif
</div>
@endif

@endsection
