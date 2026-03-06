{{-- resources/views/pharmacy/patients/index.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Patients')
@section('page-title', 'Patients')

@push('styles')
<style>
.patient-card {
    border-radius:12px; border:1px solid #e5e7eb;
    transition:all .2s; cursor:pointer;
    background:#fff;
}
.patient-card:hover {
    border-color:#2563eb; box-shadow:0 4px 20px rgba(37,99,235,.1);
    transform:translateY(-2px);
}
.avatar-lg {
    width:52px; height:52px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:1.1rem; flex-shrink:0;
}
</style>
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Patients</h5>
        <small class="text-muted">Patients who have ordered from your pharmacy</small>
    </div>
</div>

{{-- ── Summary Stats ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.8rem;font-weight:700;color:#2563eb">{{ $totalPatients }}</div>
            <div class="text-muted" style="font-size:.8rem">Total Patients</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.8rem;font-weight:700;color:#16a34a">{{ $totalOrdersAll }}</div>
            <div class="text-muted" style="font-size:.8rem">Total Orders</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="dashboard-card text-center py-3">
            <div style="font-size:1.8rem;font-weight:700;color:#7c3aed">
                Rs. {{ number_format($totalRevenue, 0) }}
            </div>
            <div class="text-muted" style="font-size:.8rem">Total Revenue (Paid)</div>
        </div>
    </div>
</div>

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.patients.index') }}" method="GET"
              class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, phone, NIC, city…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Gender</label>
                <select name="gender" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="male"   {{ request('gender')=='male'   ? 'selected':'' }}>Male</option>
                    <option value="female" {{ request('gender')=='female' ? 'selected':'' }}>Female</option>
                    <option value="other"  {{ request('gender')=='other'  ? 'selected':'' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Blood Group</label>
                <select name="blood_group" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group')==$bg ? 'selected':'' }}>
                        {{ $bg }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Sort By</label>
                <select name="sort_by" class="form-select form-select-sm">
                    <option value="created_at" {{ request('sort_by')=='created_at' ? 'selected':'' }}>Newest</option>
                    <option value="first_name" {{ request('sort_by')=='first_name'  ? 'selected':'' }}>Name A–Z</option>
                    <option value="city"       {{ request('sort_by')=='city'        ? 'selected':'' }}>City</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.patients.index') }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Patients Grid ── --}}
@if($patients->count() > 0)
<div class="row g-3">
    @foreach($patients as $patient)
    @php
        $stats   = $statsMap[$patient->id] ?? null;
        $initials = strtoupper(substr($patient->first_name,0,1).substr($patient->last_name,0,1));
        $colors  = ['#2563eb','#7c3aed','#db2777','#16a34a','#d97706','#0891b2'];
        $color   = $colors[$patient->id % count($colors)];
    @endphp
    <div class="col-sm-6 col-xl-4">
        <div class="patient-card p-3"
             onclick="window.location='{{ route('pharmacy.patients.show', $patient->id) }}'">
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($patient->profile_image)
                    <img src="{{ asset('storage/'.$patient->profile_image) }}"
                         class="avatar-lg" style="object-fit:cover" alt="patient">
                @else
                    <div class="avatar-lg"
                         style="background:{{ $color }}20;color:{{ $color }}">
                        {{ $initials }}
                    </div>
                @endif
                <div>
                    <div class="fw-semibold">
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </div>
                    <small class="text-muted">
                        @if($patient->phone)
                        <i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                        @elseif($patient->user?->email)
                        <i class="fas fa-envelope me-1"></i>{{ $patient->user->email }}
                        @endif
                    </small>
                </div>
            </div>

            {{-- Patient Badges --}}
            <div class="d-flex flex-wrap gap-1 mb-3">
                @if($patient->gender)
                <span class="badge bg-light text-dark border" style="font-size:.7rem">
                    {{ ucfirst($patient->gender) }}
                </span>
                @endif
                @if($patient->blood_group)
                <span class="badge bg-danger bg-opacity-15 text-danger" style="font-size:.7rem">
                    {{ $patient->blood_group }}
                </span>
                @endif
                @if($patient->city)
                <span class="badge bg-info bg-opacity-15 text-info" style="font-size:.7rem">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $patient->city }}
                </span>
                @endif
            </div>

            {{-- Stats Row --}}
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="fw-bold" style="font-size:1rem;color:#2563eb">
                        {{ $stats?->total_orders ?? 0 }}
                    </div>
                    <div class="text-muted" style="font-size:.7rem">Orders</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold" style="font-size:.9rem;color:#16a34a">
                        Rs. {{ number_format($stats?->total_spent ?? 0, 0) }}
                    </div>
                    <div class="text-muted" style="font-size:.7rem">Spent</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold" style="font-size:.75rem;color:#6b7280">
                        @if($stats?->last_order_date)
                            {{ \Carbon\Carbon::parse($stats->last_order_date)->diffForHumans() }}
                        @else
                            –
                        @endif
                    </div>
                    <div class="text-muted" style="font-size:.7rem">Last Order</div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-2 mt-3 pt-3 border-top">
                <a href="{{ route('pharmacy.patients.show', $patient->id) }}"
                   class="btn btn-sm btn-outline-primary rounded-pill flex-fill"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-user me-1"></i>Profile
                </a>
                <a href="{{ route('pharmacy.patients.orders', $patient->id) }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill flex-fill"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-shopping-bag me-1"></i>Orders
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center mt-4">
    <small class="text-muted">
        Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }}
        of {{ $patients->total() }} patients
    </small>
    {{ $patients->links() }}
</div>

@else
<div class="dashboard-card">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-users fa-3x mb-3 d-block opacity-40"></i>
        <h6 class="fw-semibold">No Patients Found</h6>
        <p class="small">
            {{ request()->hasAny(['search','gender','blood_group'])
                ? 'No patients match your filters.'
                : 'Patients who order from your pharmacy will appear here.' }}
        </p>
        @if(request()->hasAny(['search','gender','blood_group']))
        <a href="{{ route('pharmacy.patients.index') }}"
           class="btn btn-outline-primary rounded-pill px-4">
            Clear Filters
        </a>
        @endif
    </div>
</div>
@endif

@endsection
