@extends('doctor.layouts.master')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@push('styles')
<style>
.filter-card { background:#fff; border-radius:14px; padding:1rem 1.2rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }

/* ── Stat Badges ── */
.stat-badges { display:flex; gap:.6rem; flex-wrap:wrap; margin-bottom:1.2rem; }
.sb-item { background:#fff; border-radius:10px; padding:.55rem 1rem;
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    display:flex; align-items:center; gap:.45rem; }
.sb-item .sb-num { font-size:1.1rem; font-weight:800; color:#0d6efd; }
.sb-item .sb-lbl { font-size:.7rem; color:#888; font-weight:600; }

/* ── Table ── */
.sch-table { width:100%; border-collapse:separate; border-spacing:0 .45rem; }
.sch-table thead th { font-size:.72rem; font-weight:700; color:#888;
    padding:.5rem .8rem; text-transform:uppercase; letter-spacing:.04em;
    border-bottom:2px solid #f0f3f8; background:#fff; }
.sch-table tbody tr { background:#fff; transition:all .15s; }
.sch-table tbody tr:hover { background:#f8faff; }
.sch-table tbody td { padding:.75rem .8rem; font-size:.82rem;
    border-top:1px solid #f0f3f8; border-bottom:1px solid #f0f3f8;
    vertical-align:middle; }
.sch-table tbody td:first-child { border-left:1px solid #f0f3f8;
    border-radius:10px 0 0 10px; }
.sch-table tbody td:last-child  { border-right:1px solid #f0f3f8;
    border-radius:0 10px 10px 0; }

/* ── Day Badge ── */
.day-badge { display:inline-flex; align-items:center; padding:.22rem .65rem;
    border-radius:8px; font-size:.72rem; font-weight:700;
    background:#f0f5ff; color:#0d6efd; }
.day-badge.weekend { background:#fff3cd; color:#856404; }
.day-badge.today   { background:#d1ecf1; color:#0c5460; }

/* ── Capacity Bar ── */
.cap-bar { height:5px; border-radius:10px; background:#e8edf5;
    overflow:hidden; margin-top:.3rem; }
.cap-fill        { height:100%; border-radius:10px; background:#0d6efd; }
.cap-fill.warn   { background:#ffc107; }
.cap-fill.danger { background:#dc3545; }

/* ── Toggle ── */
.toggle-wrap { display:flex; align-items:center; gap:.4rem; }
.toggle-sw { position:relative; width:36px; height:20px; flex-shrink:0; }
.toggle-sw input { opacity:0; width:0; height:0; position:absolute; }
.toggle-slider { position:absolute; cursor:pointer; inset:0;
    background:#cbd5e1; border-radius:34px; transition:.3s; }
.toggle-slider::before { content:''; position:absolute; height:14px;
    width:14px; left:3px; bottom:3px; background:#fff;
    border-radius:50%; transition:.3s; }
input:checked + .toggle-slider { background:#198754; }
input:checked + .toggle-slider::before { transform:translateX(16px); }

/* ── Action Buttons ── */
.action-btn { padding:.28rem .65rem; border-radius:8px; border:none;
    font-size:.72rem; font-weight:600; cursor:pointer;
    transition:all .15s; text-decoration:none;
    display:inline-flex; align-items:center; gap:.25rem; }
.action-btn.edit  { background:#f0f5ff; color:#0d6efd; }
.action-btn.edit:hover  { background:#0d6efd; color:#fff; }
.action-btn.del   { background:#fff5f5; color:#dc3545; }
.action-btn.del:hover   { background:#dc3545; color:#fff; }
</style>
@endpush

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"
     style="border-radius:10px;font-size:.82rem" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show"
     style="border-radius:10px;font-size:.82rem" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stat Badges --}}
<div class="stat-badges">
    <div class="sb-item">
        <i class="fas fa-calendar-alt" style="color:#0d6efd;font-size:.9rem"></i>
        <div>
            <div class="sb-num">{{ $stats['total'] }}</div>
            <div class="sb-lbl">Total</div>
        </div>
    </div>
    <div class="sb-item">
        <i class="fas fa-check-circle" style="color:#198754;font-size:.9rem"></i>
        <div>
            <div class="sb-num" style="color:#198754">{{ $stats['active'] }}</div>
            <div class="sb-lbl">Active</div>
        </div>
    </div>
    <div class="sb-item">
        <i class="fas fa-pause-circle" style="color:#6c757d;font-size:.9rem"></i>
        <div>
            <div class="sb-num" style="color:#6c757d">{{ $stats['inactive'] }}</div>
            <div class="sb-lbl">Inactive</div>
        </div>
    </div>
    <div class="sb-item">
        <i class="fas fa-calendar-day" style="color:#fd7e14;font-size:.9rem"></i>
        <div>
            <div class="sb-num" style="color:#fd7e14">{{ $stats['today'] }}</div>
            <div class="sb-lbl">Today</div>
        </div>
    </div>
    <div class="ms-auto d-flex align-items-center">
        <a href="{{ route('doctor.schedule.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add Schedule
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="filter-card">
    <form method="GET" action="{{ route('doctor.schedule.index') }}"
          class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label mb-1"
                   style="font-size:.74rem;font-weight:600;color:#555">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="active"
                    {{ request('status') === 'active' ? 'selected':'' }}>
                    Active
                </option>
                <option value="inactive"
                    {{ request('status') === 'inactive' ? 'selected':'' }}>
                    Inactive
                </option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1"
                   style="font-size:.74rem;font-weight:600;color:#555">Day</label>
            <select name="day" class="form-select form-select-sm">
                <option value="">All Days</option>
                @foreach(['monday','tuesday','wednesday','thursday',
                          'friday','saturday','sunday'] as $d)
                <option value="{{ $d }}"
                    {{ request('day') === $d ? 'selected':'' }}>
                    {{ ucfirst($d) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1"
                   style="font-size:.74rem;font-weight:600;color:#555">Workplace</label>
            <select name="workplace_type" class="form-select form-select-sm">
                <option value="">All Workplaces</option>
                <option value="hospital"
                    {{ request('workplace_type') === 'hospital' ? 'selected':'' }}>
                    Hospital
                </option>
                <option value="medicalcentre"
                    {{ request('workplace_type') === 'medicalcentre' ? 'selected':'' }}>
                    Medical Centre
                </option>
                <option value="private"
                    {{ request('workplace_type') === 'private' ? 'selected':'' }}>
                    Private
                </option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
            <a href="{{ route('doctor.schedule.index') }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div style="background:#fff;border-radius:16px;padding:1.2rem;
     box-shadow:0 2px 10px rgba(0,0,0,.05);">

    @if($schedules->count() > 0)
    <div class="table-responsive">
        <table class="sch-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Max Appointments</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $sch)
                @php
                    $isWeekend = in_array($sch->day_of_week, ['saturday','sunday']);
                    $isToday   = strtolower(now()->format('l')) === $sch->day_of_week;
                    $maxApt    = $sch->max_appointments ?? 0;
                @endphp
                <tr>
                    {{-- Day --}}
                    <td>
                        <span class="day-badge
                            {{ $isToday ? 'today' : ($isWeekend ? 'weekend' : '') }}">
                            {{ ucfirst($sch->day_of_week) }}
                        </span>
                        @if($isToday)
                        <span class="badge bg-warning text-dark ms-1"
                              style="font-size:.6rem">Today</span>
                        @endif
                    </td>

                    {{-- Time --}}
                    <td>
                        <div style="font-weight:600;color:#1a1a1a">
                            <i class="fas fa-clock text-muted me-1"
                               style="font-size:.7rem"></i>
                            {{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}
                        </div>
                        @php
                            $start = \Carbon\Carbon::parse($sch->start_time);
                            $end   = \Carbon\Carbon::parse($sch->end_time);
                            $mins  = $start->diffInMinutes($end);
                            $dur   = $mins >= 60
                                ? floor($mins/60).'h'.($mins%60 > 0 ? ' '.($mins%60).'m':'')
                                : $mins.'m';
                        @endphp
                        <div style="font-size:.68rem;color:#aaa;margin-top:.1rem">
                            {{ $dur }} session
                        </div>
                    </td>

                    {{-- Location --}}
                    <td>
                        <div style="font-size:.82rem;font-weight:600;color:#1a1a1a">
                            {{ $sch->location }}
                        </div>
                        <div style="font-size:.68rem;color:#aaa">
                            {{ ucfirst($sch->workplace_type ?? 'private') }}
                        </div>
                    </td>

                    {{-- Max Appointments --}}
                    <td>
                        <div style="font-size:.82rem;font-weight:700;color:#0d6efd">
                            {{ $maxApt }}
                            <span style="color:#aaa;font-weight:400;
                                  font-size:.7rem">slots</span>
                        </div>
                    </td>

                    {{-- Fee --}}
                    <td>
                        <span style="font-weight:700;font-size:.82rem;
                              color:#198754">
                            LKR {{ number_format($sch->consultation_fee ?? 0, 2) }}
                        </span>
                    </td>

                    {{-- Status Toggle --}}
                    <td>
                        <div class="toggle-wrap">
                            <label class="toggle-sw">
                                <input type="checkbox"
                                       {{ $sch->is_active ? 'checked':'' }}
                                       onchange="toggleStatus({{ $sch->id }}, this)">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="sch-status-lbl-{{ $sch->id }}"
                                  style="font-size:.72rem;font-weight:700;
                                  color:{{ $sch->is_active ? '#198754':'#aaa' }}">
                                {{ $sch->is_active ? 'Active':'Inactive' }}
                            </span>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('doctor.schedule.edit', $sch->id) }}"
                               class="action-btn edit">
                                <i class="fas fa-edit"></i>Edit
                            </a>
                            <button onclick="deleteSchedule({{ $sch->id }})"
                                    class="action-btn del">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($schedules->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3 px-1">
        <div style="font-size:.75rem;color:#888">
            Showing {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }}
            of {{ $schedules->total() }} schedules
        </div>
        {{ $schedules->appends(request()->query())->links() }}
    </div>
    @endif

    @else
    {{-- Empty --}}
    <div style="text-align:center;padding:3.5rem 1rem;color:#c0c8d4">
        <i class="fas fa-calendar-times"
           style="font-size:2.5rem;display:block;margin-bottom:.6rem"></i>
        <p style="font-size:.85rem;font-weight:600;margin:0">
            No schedules found
        </p>
        <p style="font-size:.76rem;margin:.3rem 0 0">
            @if(request()->hasAny(['status','day','workplace_type']))
                Try adjusting your filters
            @else
                Add your first schedule to start accepting appointments
            @endif
        </p>
        <a href="{{ route('doctor.schedule.create') }}"
           class="btn btn-primary btn-sm mt-3">
            <i class="fas fa-plus me-1"></i>Add Schedule
        </a>
    </div>
    @endif
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none">
            <div class="modal-body text-center p-4">
                <div style="width:52px;height:52px;border-radius:50%;
                    background:#fff5f5;display:flex;align-items:center;
                    justify-content:center;margin:0 auto .8rem">
                    <i class="fas fa-trash"
                       style="color:#dc3545;font-size:1.2rem"></i>
                </div>
                <h6 style="font-weight:800;margin-bottom:.3rem">
                    Delete Schedule?
                </h6>
                <p style="font-size:.78rem;color:#888;margin-bottom:1.2rem">
                    This cannot be undone. Active bookings will be affected.
                </p>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm flex-grow-1"
                            data-bs-dismiss="modal">Cancel</button>
                    <button id="confirmDeleteBtn"
                            class="btn btn-danger btn-sm flex-grow-1">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Toggle Status ──────────────────────────────────
function toggleStatus(id, el) {
    fetch(`/doctor/schedule/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const lbl = document.querySelector(`.sch-status-lbl-${id}`);
            if (d.is_active) {
                lbl.textContent = 'Active';
                lbl.style.color = '#198754';
                el.checked = true;
            } else {
                lbl.textContent = 'Inactive';
                lbl.style.color = '#aaa';
                el.checked = false;
            }
        } else {
            el.checked = !el.checked;
            alert(d.message || 'Failed to update status.');
        }
    })
    .catch(() => { el.checked = !el.checked; });
}

// ── Delete ─────────────────────────────────────────
let deleteId = null;

function deleteSchedule(id) {
    deleteId = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.getElementById('confirmDeleteBtn')
    .addEventListener('click', function () {
        if (!deleteId) return;
        this.innerHTML =
            '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
        this.disabled = true;

        fetch(`/doctor/schedule/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                location.reload();
            } else {
                bootstrap.Modal.getInstance(
                    document.getElementById('deleteModal')
                ).hide();
                alert(d.message || 'Cannot delete this schedule.');
                this.innerHTML =
                    '<i class="fas fa-trash me-1"></i>Delete';
                this.disabled = false;
            }
        })
        .catch(() => location.reload());
    });
</script>
@endpush
