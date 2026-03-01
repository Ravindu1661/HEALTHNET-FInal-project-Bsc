{{-- resources/views/hospital/appointments.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Appointments')
@section('page-title', 'Appointments')

@push('styles')
<style>
/* ══════════════════════════════════════════
   PAGE LAYOUT
══════════════════════════════════════════ */
.apt-page { animation: fadeIn .3s ease; }
@keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

/* ══════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════ */
.apt-stat {
    background: #fff;
    border-radius: 14px;
    padding: 1.1rem 1.3rem;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.06);
    display: flex; align-items: center; gap: 1rem;
    transition: transform .2s, box-shadow .2s;
    position: relative; overflow: hidden;
}
.apt-stat::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: 14px 14px 0 0;
}
.apt-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(44,62,80,.1); }
.apt-stat.all::before      { background: linear-gradient(90deg,#2969bf,#5b9bd5); }
.apt-stat.pending::before  { background: linear-gradient(90deg,#f39c12,#f7c04a); }
.apt-stat.confirmed::before{ background: linear-gradient(90deg,#3498db,#74b9e7); }
.apt-stat.completed::before{ background: linear-gradient(90deg,#27ae60,#6fcf97); }
.apt-stat.cancelled::before{ background: linear-gradient(90deg,#e74c3c,#f1948a); }

.apt-stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.apt-stat.all .apt-stat-icon      { background:#e8f0fe; color:#2969bf; }
.apt-stat.pending .apt-stat-icon  { background:#fef8e7; color:#f39c12; }
.apt-stat.confirmed .apt-stat-icon{ background:#eaf4fd; color:#3498db; }
.apt-stat.completed .apt-stat-icon{ background:#e9f7ee; color:#27ae60; }
.apt-stat.cancelled .apt-stat-icon{ background:#fdecea; color:#e74c3c; }

.apt-stat-info h4 { font-size:1.6rem; font-weight:800; margin:0; line-height:1; }
.apt-stat-info p  { font-size:.75rem; color:#888; margin:0; margin-top:3px; }

/* ══════════════════════════════════════════
   FILTER BAR
══════════════════════════════════════════ */
.filter-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    padding: 1rem 1.3rem;
    margin-bottom: 1.3rem;
}
.filter-group { display: flex; flex-wrap: wrap; gap: .65rem; align-items: flex-end; }

.filter-control {
    flex: 1; min-width: 160px;
    display: flex; flex-direction: column; gap: .3rem;
}
.filter-control label {
    font-size: .72rem; font-weight: 600;
    color: #555; text-transform: uppercase; letter-spacing: .05em;
}
.filter-control select,
.filter-control input {
    border: 1.5px solid #e5ecf0; border-radius: 9px;
    padding: .5rem .75rem; font-size: .83rem;
    color: #2c3e50; outline: none;
    transition: border-color .2s, box-shadow .2s;
    background: #fafcff;
    font-family: inherit;
    width: 100%;
}
.filter-control select:focus,
.filter-control input:focus {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41,105,191,.1);
}

.btn-filter {
    padding: .5rem 1.1rem; border-radius: 9px;
    font-size: .82rem; font-weight: 600;
    border: none; cursor: pointer;
    transition: all .2s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: .4rem;
}
.btn-filter.primary { background:#2969bf; color:#fff; }
.btn-filter.primary:hover { background:#1a4f9a; box-shadow:0 4px 12px rgba(41,105,191,.3); }
.btn-filter.reset   { background:#f0f4f8; color:#555; }
.btn-filter.reset:hover { background:#e2e8f0; }

/* ══════════════════════════════════════════
   TABLE CARD
══════════════════════════════════════════ */
.table-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    overflow: hidden;
}
.table-card-header {
    padding: .9rem 1.3rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.table-card-header h6 {
    font-size: .93rem; font-weight: 700;
    color: #2c3e50; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.table-card-header h6 i { color: #2969bf; }

/* ══════════════════════════════════════════
   TABLE
══════════════════════════════════════════ */
.apt-table { width: 100%; border-collapse: collapse; }
.apt-table thead tr {
    background: #f8fbff;
    border-bottom: 2px solid #edf2f7;
}
.apt-table thead th {
    padding: .75rem 1rem;
    font-size: .72rem; font-weight: 700;
    color: #64748b; text-transform: uppercase;
    letter-spacing: .06em; white-space: nowrap;
}
.apt-table tbody tr {
    border-bottom: 1px solid #f5f7fa;
    transition: background .15s;
}
.apt-table tbody tr:last-child { border-bottom: none; }
.apt-table tbody tr:hover { background: #f8fbff; }
.apt-table td {
    padding: .8rem 1rem;
    font-size: .83rem; color: #374151;
    vertical-align: middle;
}

/* Apt Number */
.apt-number {
    font-size: .7rem; font-weight: 700;
    color: #2969bf; background: #e8f0fe;
    padding: .2rem .55rem; border-radius: 6px;
    font-family: monospace; letter-spacing: .03em;
}

/* Patient / Doctor cell */
.person-cell { display: flex; align-items: center; gap: .6rem; }
.person-avatar {
    width: 34px; height: 34px; border-radius: 9px;
    background: linear-gradient(135deg,#e8f0fe,#d0e4ff);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; color: #2969bf; flex-shrink: 0;
}
.person-name  { font-weight: 600; font-size: .83rem; color: #2c3e50; line-height:1.2; }
.person-sub   { font-size: .72rem; color: #888; }

/* Date/Time cell */
.datetime-cell .date { font-weight: 600; font-size: .82rem; }
.datetime-cell .time { font-size: .72rem; color: #888; }

/* Status badges */
.status-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: .25rem .7rem; border-radius: 99px;
    font-size: .7rem; font-weight: 700; white-space: nowrap;
}
.status-pill.pending   { background:#fff3cd; color:#856404; }
.status-pill.confirmed { background:#cfe2ff; color:#084298; }
.status-pill.completed { background:#d1e7dd; color:#0f5132; }
.status-pill.cancelled { background:#f8d7da; color:#842029; }

/* Action buttons */
.action-wrap { display: flex; gap: .4rem; flex-wrap: nowrap; }
.btn-action {
    width: 30px; height: 30px; border-radius: 7px;
    border: none; cursor: pointer; font-size: .78rem;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all .2s; flex-shrink: 0;
}
.btn-action.confirm  { background:#e9f7ee; color:#27ae60; }
.btn-action.confirm:hover  { background:#27ae60; color:#fff; }
.btn-action.complete { background:#eaf4fd; color:#2969bf; }
.btn-action.complete:hover { background:#2969bf; color:#fff; }
.btn-action.cancel   { background:#fdecea; color:#e74c3c; }
.btn-action.cancel:hover   { background:#e74c3c; color:#fff; }
.btn-action.view     { background:#f8fbff; color:#6c757d; }
.btn-action.view:hover     { background:#2969bf; color:#fff; }

/* ══════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════ */
.empty-state {
    text-align: center; padding: 3.5rem 1rem;
}
.empty-state i { font-size: 3rem; color: #d0dae8; margin-bottom: 1rem; display: block; }
.empty-state h6 { color: #888; font-size: .95rem; margin: 0 0 .3rem; }
.empty-state p  { color: #aab4be; font-size: .8rem; margin: 0; }

/* ══════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════ */
.apt-pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.3rem; border-top: 1px solid #f0f4f8;
    flex-wrap: wrap; gap: .5rem;
}
.pagination-info { font-size: .78rem; color: #888; }
.pagination-btns { display: flex; gap: .3rem; }
.btn-page {
    min-width: 32px; height: 32px; border-radius: 7px;
    border: 1.5px solid #e5ecf0; background: #fff;
    font-size: .78rem; font-weight: 600; color: #555;
    cursor: pointer; display: inline-flex;
    align-items: center; justify-content: center;
    transition: all .2s; padding: 0 .4rem;
}
.btn-page:hover        { background: #e8f0fe; border-color: #2969bf; color: #2969bf; }
.btn-page.active       { background: #2969bf; border-color: #2969bf; color: #fff; }
.btn-page:disabled     { opacity: .45; cursor: not-allowed; }

/* ══════════════════════════════════════════
   LOADING SKELETON
══════════════════════════════════════════ */
@keyframes shimmer {
    0%   { background-position: -600px 0; }
    100% { background-position: 600px 0; }
}
.skeleton-row td { padding: .85rem 1rem; }
.skeleton-line {
    height: 13px; border-radius: 6px;
    background: linear-gradient(90deg,#f0f4f8 25%,#e4eaf0 50%,#f0f4f8 75%);
    background-size: 1200px 100%;
    animation: shimmer 1.4s infinite linear;
}

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.apt-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(3px);
    z-index: 2000;
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
    opacity: 0; visibility: hidden;
    transition: opacity .25s, visibility .25s;
}
.apt-modal-overlay.show { opacity: 1; visibility: visible; }

.apt-modal {
    background: #fff; border-radius: 16px;
    width: 100%; max-width: 520px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    transform: translateY(-20px) scale(.97);
    transition: transform .25s;
    overflow: hidden;
}
.apt-modal-overlay.show .apt-modal { transform: translateY(0) scale(1); }

.apt-modal-header {
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
}
.apt-modal-header h5 {
    font-size: .97rem; font-weight: 700;
    margin: 0; color: #2c3e50;
    display: flex; align-items: center; gap: .5rem;
}
.modal-close-btn {
    background: none; border: none; cursor: pointer;
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #888; font-size: .9rem;
    transition: background .2s, color .2s;
}
.modal-close-btn:hover { background: #f0f4f8; color: #e74c3c; }

.apt-modal-body { padding: 1.3rem 1.4rem; }
.apt-modal-footer {
    padding: .9rem 1.4rem;
    border-top: 1px solid #f0f4f8;
    display: flex; justify-content: flex-end; gap: .6rem;
}

/* Detail rows */
.detail-row {
    display: flex; gap: 1rem;
    padding: .6rem 0; border-bottom: 1px solid #f5f7fa;
    font-size: .83rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { min-width: 120px; color: #888; font-weight: 500; flex-shrink: 0; }
.detail-value { color: #2c3e50; font-weight: 600; }

/* Cancel reason textarea */
.cancel-reason {
    width: 100%; border: 1.5px solid #e5ecf0;
    border-radius: 9px; padding: .65rem .9rem;
    font-size: .83rem; font-family: inherit;
    resize: vertical; min-height: 90px; outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.cancel-reason:focus {
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231,76,60,.1);
}

/* Btn variants */
.btn-modal {
    padding: .5rem 1.2rem; border-radius: 9px;
    font-size: .83rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: .4rem;
}
.btn-modal.secondary { background:#f0f4f8; color:#555; }
.btn-modal.secondary:hover { background:#e2e8f0; }
.btn-modal.success { background:#27ae60; color:#fff; }
.btn-modal.success:hover { background:#1e8449; box-shadow:0 4px 12px rgba(39,174,96,.3); }
.btn-modal.primary { background:#2969bf; color:#fff; }
.btn-modal.primary:hover { background:#1a4f9a; box-shadow:0 4px 12px rgba(41,105,191,.3); }
.btn-modal.danger  { background:#e74c3c; color:#fff; }
.btn-modal.danger:hover  { background:#c0392b; box-shadow:0 4px 12px rgba(231,76,60,.3); }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 991.98px) {
    .hide-md { display: none !important; }
}
@media (max-width: 767.98px) {
    .apt-table thead { display: none; }
    .apt-table, .apt-table tbody, .apt-table tr, .apt-table td { display: block; width: 100%; }
    .apt-table tr {
        margin-bottom: .75rem;
        border: 1px solid #f0f4f8;
        border-radius: 12px; overflow: hidden;
        background: #fff;
    }
    .apt-table td {
        display: flex; align-items: center; justify-content: space-between;
        padding: .55rem 1rem; border-bottom: 1px solid #f5f7fa;
        font-size: .8rem;
    }
    .apt-table td:last-child { border-bottom: none; }
    .apt-table td::before {
        content: attr(data-label);
        font-size: .68rem; font-weight: 700;
        color: #888; text-transform: uppercase;
        letter-spacing: .05em; min-width: 100px;
    }
    .apt-table td.no-label::before { content: none; }
    .table-responsive-wrap { overflow: visible; }
    .hide-sm { display: none !important; }
}
@media (max-width: 575.98px) {
    .filter-control { min-width: 100%; }
    .apt-stat-info h4 { font-size: 1.3rem; }
}
</style>
@endpush

@section('content')
<div class="apt-page">

    {{-- ══ STAT CARDS ══ --}}
    <div class="row g-3 mb-4" id="statCards">
        @foreach([
            ['all',       'fa-calendar-alt',   'Total',     0],
            ['pending',   'fa-clock',          'Pending',   0],
            ['confirmed', 'fa-check-circle',   'Confirmed', 0],
            ['completed', 'fa-check-double',   'Completed', 0],
            ['cancelled', 'fa-times-circle',   'Cancelled', 0],
        ] as [$cls, $icon, $label, $val])
        <div class="col-6 col-sm-4 col-lg">
            <div class="apt-stat {{ $cls }} cursor-pointer"
                 onclick="filterByStatus('{{ $cls === 'all' ? '' : $cls }}')"
                 style="cursor:pointer;">
                <div class="apt-stat-icon">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="apt-stat-info">
                    <h4 id="stat-{{ $cls }}">—</h4>
                    <p>{{ $label }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="filter-card">
        <div class="filter-group">

            {{-- Search --}}
            <div class="filter-control" style="min-width:220px;flex:2;">
                <label><i class="fas fa-search me-1"></i>Search</label>
                <input type="text" id="filterSearch"
                       placeholder="Patient name, phone, apt number..."
                       oninput="debounceLoad()">
            </div>

            {{-- Status --}}
            <div class="filter-control">
                <label><i class="fas fa-filter me-1"></i>Status</label>
                <select id="filterStatus" onchange="loadAppointments()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            {{-- Date --}}
            <div class="filter-control">
                <label><i class="fas fa-calendar me-1"></i>Date</label>
                <input type="date" id="filterDate" onchange="loadAppointments()">
            </div>

            {{-- Per Page --}}
            <div class="filter-control" style="min-width:100px;flex:0;">
                <label><i class="fas fa-list me-1"></i>Show</label>
                <select id="filterPerPage" onchange="loadAppointments()">
                    <option value="10">10</option>
                    <option value="15" selected>15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:.5rem;align-items:flex-end;">
                <button class="btn-filter primary" onclick="loadAppointments()">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-sm-inline">Search</span>
                </button>
                <button class="btn-filter reset" onclick="resetFilters()">
                    <i class="fas fa-undo"></i>
                    <span class="d-none d-sm-inline">Reset</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ TABLE CARD ══ --}}
    <div class="table-card">
        <div class="table-card-header">
            <h6>
                <i class="fas fa-calendar-check"></i>
                Appointments
                <span class="badge bg-primary rounded-pill ms-1" id="totalBadge"
                      style="font-size:.65rem;">0</span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                {{-- Today filter quick btn --}}
                <button class="btn-filter primary" onclick="filterToday()" style="font-size:.75rem;padding:.4rem .85rem;">
                    <i class="fas fa-calendar-day me-1"></i>Today
                </button>
                {{-- Refresh --}}
                <button class="btn-action view" onclick="loadAppointments()" title="Refresh"
                        style="width:34px;height:34px;border-radius:9px;">
                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive-wrap" style="overflow-x:auto;">
            <table class="apt-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Apt No.</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th class="hide-md">Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsBody">
                    {{-- Skeleton --}}
                    @for($i=0;$i<6;$i++)
                    <tr class="skeleton-row">
                        <td><div class="skeleton-line" style="width:24px;"></div></td>
                        <td><div class="skeleton-line" style="width:80px;"></div></td>
                        <td><div class="skeleton-line" style="width:130px;"></div></td>
                        <td><div class="skeleton-line" style="width:130px;"></div></td>
                        <td><div class="skeleton-line" style="width:100px;"></div></td>
                        <td><div class="skeleton-line" style="width:90px;"></div></td>
                        <td><div class="skeleton-line" style="width:70px;height:22px;border-radius:99px;"></div></td>
                        <td><div class="skeleton-line" style="width:90px;height:28px;border-radius:8px;"></div></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="apt-pagination" id="paginationWrap">
            <span class="pagination-info" id="paginationInfo">Loading...</span>
            <div class="pagination-btns" id="paginationBtns"></div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     VIEW MODAL
══════════════════════════════════════════════ --}}
<div class="apt-modal-overlay" id="viewModal">
    <div class="apt-modal">
        <div class="apt-modal-header">
            <h5><i class="fas fa-calendar-check" style="color:#2969bf;"></i> Appointment Details</h5>
            <button class="modal-close-btn" onclick="closeModal('viewModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="apt-modal-body" id="viewModalBody">
            <div class="text-center py-3">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            </div>
        </div>
        <div class="apt-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('viewModal')">
                <i class="fas fa-times me-1"></i>Close
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     CANCEL MODAL
══════════════════════════════════════════════ --}}
<div class="apt-modal-overlay" id="cancelModal">
    <div class="apt-modal" style="max-width:420px;">
        <div class="apt-modal-header">
            <h5><i class="fas fa-times-circle" style="color:#e74c3c;"></i> Cancel Appointment</h5>
            <button class="modal-close-btn" onclick="closeModal('cancelModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="apt-modal-body">
            <p style="font-size:.85rem;color:#555;margin-bottom:1rem;">
                Are you sure you want to cancel this appointment? Please provide a reason.
            </p>
            <textarea class="cancel-reason" id="cancelReason"
                      placeholder="Enter cancellation reason..."></textarea>
            <input type="hidden" id="cancelAptId">
        </div>
        <div class="apt-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('cancelModal')">
                <i class="fas fa-arrow-left me-1"></i>Go Back
            </button>
            <button class="btn-modal danger" onclick="submitCancel()">
                <i class="fas fa-times me-1"></i>Cancel Appointment
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     CONFIRM ACTION MODAL (generic)
══════════════════════════════════════════════ --}}
<div class="apt-modal-overlay" id="confirmModal">
    <div class="apt-modal" style="max-width:400px;">
        <div class="apt-modal-header">
            <h5 id="confirmModalTitle">
                <i class="fas fa-question-circle"></i> Confirm Action
            </h5>
            <button class="modal-close-btn" onclick="closeModal('confirmModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="apt-modal-body">
            <p id="confirmModalMsg" style="font-size:.85rem;color:#555;margin:0;"></p>
        </div>
        <div class="apt-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('confirmModal')">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="btn-modal success" id="confirmModalBtn" onclick="">
                <i class="fas fa-check me-1"></i>Confirm
            </button>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ════════════════════════════════════════════════
// STATE
// ════════════════════════════════════════════════
let currentPage   = 1;
let totalPages    = 1;
let debounceTimer = null;
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

// ════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    loadAppointments();
    loadStats();

    // Close modals on overlay click
    document.querySelectorAll('.apt-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // ESC key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.apt-modal-overlay.show').forEach(m => {
                closeModal(m.id);
            });
        }
    });
});

// ════════════════════════════════════════════════
// LOAD STATS
// ════════════════════════════════════════════════
function loadStats() {
    apiFetch('{{ route("hospital.appointments.data") }}?per_page=1', function (data) {
        // Total
        setText('stat-all', data.total ?? 0);

        // Per status — separate calls or from meta
        ['pending','confirmed','completed','cancelled'].forEach(status => {
            apiFetch(`{{ route("hospital.appointments.data") }}?per_page=1&status=${status}`, function (d) {
                setText('stat-' + status, d.total ?? 0);
            });
        });
    });
}

// ════════════════════════════════════════════════
// LOAD APPOINTMENTS
// ════════════════════════════════════════════════
function loadAppointments(page = 1) {
    currentPage = page;

    const search  = document.getElementById('filterSearch').value.trim();
    const status  = document.getElementById('filterStatus').value;
    const date    = document.getElementById('filterDate').value;
    const perPage = document.getElementById('filterPerPage').value;

    const params = new URLSearchParams();
    if (search)  params.set('search',   search);
    if (status)  params.set('status',   status);
    if (date)    params.set('date',     date);
    params.set('per_page', perPage);
    params.set('page',     page);

    // Spin refresh icon
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.style.animation = 'spin 1s linear infinite';

    apiFetch('{{ route("hospital.appointments.data") }}?' + params.toString(), function (data) {
        if (icon) icon.style.animation = '';
        renderTable(data);
        renderPagination(data);
        setText('totalBadge', data.total ?? 0);
        loadStats();
    });
}

// ════════════════════════════════════════════════
// RENDER TABLE
// ════════════════════════════════════════════════
function renderTable(data) {
    const tbody = document.getElementById('appointmentsBody');
    const items = data.data ?? [];

    if (!items.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="no-label" style="padding:0;">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h6>No appointments found</h6>
                        <p>Try adjusting your filters or check back later.</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    const from = data.from ?? 1;
    tbody.innerHTML = items.map((apt, i) => {
        const pInitials = initials(apt.patient_name);
        const dInitials = initials(apt.doctor_name);
        const statusMap = {
            pending:   ['pending',   'fa-clock',          'Pending'],
            confirmed: ['confirmed', 'fa-check-circle',   'Confirmed'],
            completed: ['completed', 'fa-check-double',   'Completed'],
            cancelled: ['cancelled', 'fa-times-circle',   'Cancelled'],
        };
        const [cls, icon, label] = statusMap[apt.status] ?? ['pending','fa-clock','Pending'];

        // Action buttons based on status
        let actions = `
            <button class="btn-action view" onclick="viewApt(${apt.id})" title="View Details">
                <i class="fas fa-eye"></i>
            </button>`;

        if (apt.status === 'pending') {
            actions += `
            <button class="btn-action confirm" onclick="confirmApt(${apt.id})" title="Confirm">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn-action cancel" onclick="openCancelModal(${apt.id})" title="Cancel">
                <i class="fas fa-times"></i>
            </button>`;
        } else if (apt.status === 'confirmed') {
            actions += `
            <button class="btn-action complete" onclick="completeApt(${apt.id})" title="Mark Complete">
                <i class="fas fa-check-double"></i>
            </button>
            <button class="btn-action cancel" onclick="openCancelModal(${apt.id})" title="Cancel">
                <i class="fas fa-times"></i>
            </button>`;
        }

        // Format date
        const dateObj = new Date(apt.appointment_date);
        const dateStr = dateObj.toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' });
        const timeStr = apt.appointment_time
            ? new Date('2000-01-01 ' + apt.appointment_time)
                  .toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' })
            : '—';

        return `
        <tr>
            <td data-label="No.">${(from + i)}</td>
            <td data-label="Apt No.">
                <span class="apt-number">${apt.appointment_number ?? '—'}</span>
            </td>
            <td data-label="Patient">
                <div class="person-cell">
                    <div class="person-avatar">${pInitials}</div>
                    <div>
                        <div class="person-name">${apt.patient_name ?? '—'}</div>
                        <div class="person-sub hide-md">${apt.patient_phone ?? ''}</div>
                    </div>
                </div>
            </td>
            <td data-label="Doctor">
                <div class="person-cell">
                    <div class="person-avatar" style="background:linear-gradient(135deg,#e9f7ee,#c8f0d8);color:#27ae60;">
                        ${dInitials}
                    </div>
                    <div>
                        <div class="person-name">${apt.doctor_name ?? '—'}</div>
                        <div class="person-sub">${apt.specialization ?? ''}</div>
                    </div>
                </div>
            </td>
            <td data-label="Date & Time">
                <div class="datetime-cell">
                    <div class="date">${dateStr}</div>
                    <div class="time"><i class="far fa-clock me-1"></i>${timeStr}</div>
                </div>
            </td>
            <td data-label="Phone" class="hide-md">${apt.patient_phone ?? '—'}</td>
            <td data-label="Status">
                <span class="status-pill ${cls}">
                    <i class="fas ${icon}"></i>${label}
                </span>
            </td>
            <td data-label="Actions" class="no-label">
                <div class="action-wrap">${actions}</div>
            </td>
        </tr>`;
    }).join('');
}

// ════════════════════════════════════════════════
// RENDER PAGINATION
// ════════════════════════════════════════════════
function renderPagination(data) {
    totalPages = data.last_page ?? 1;
    const from = data.from ?? 0;
    const to   = data.to   ?? 0;
    const total= data.total ?? 0;

    document.getElementById('paginationInfo').textContent =
        total ? `Showing ${from}–${to} of ${total} appointments` : 'No results';

    const btns = document.getElementById('paginationBtns');
    let html = '';

    html += `<button class="btn-page" onclick="loadAppointments(${currentPage-1})"
             ${currentPage <= 1 ? 'disabled' : ''}>
             <i class="fas fa-chevron-left"></i></button>`;

    let startPage = Math.max(1, currentPage - 2);
    let endPage   = Math.min(totalPages, startPage + 4);
    if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

    if (startPage > 1) {
        html += `<button class="btn-page" onclick="loadAppointments(1)">1</button>`;
        if (startPage > 2) html += `<button class="btn-page" disabled>…</button>`;
    }

    for (let p = startPage; p <= endPage; p++) {
        html += `<button class="btn-page ${p === currentPage ? 'active' : ''}"
                 onclick="loadAppointments(${p})">${p}</button>`;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<button class="btn-page" disabled>…</button>`;
        html += `<button class="btn-page" onclick="loadAppointments(${totalPages})">${totalPages}</button>`;
    }

    html += `<button class="btn-page" onclick="loadAppointments(${currentPage+1})"
             ${currentPage >= totalPages ? 'disabled' : ''}>
             <i class="fas fa-chevron-right"></i></button>`;

    btns.innerHTML = html;
}

// ════════════════════════════════════════════════
// VIEW APPOINTMENT DETAIL
// ════════════════════════════════════════════════
function viewApt(id) {
    openModal('viewModal');
    document.getElementById('viewModalBody').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x" style="color:#2969bf;"></i>
        </div>`;

    apiFetch(`{{ route("hospital.appointments.data") }}?search=${id}&per_page=50`, function (data) {
        const apt = (data.data ?? []).find(a => a.id == id);
        if (!apt) {
            document.getElementById('viewModalBody').innerHTML =
                '<p class="text-center text-muted py-3">Appointment not found.</p>';
            return;
        }

        const dateStr = new Date(apt.appointment_date)
            .toLocaleDateString('en-US', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        const timeStr = apt.appointment_time
            ? new Date('2000-01-01 ' + apt.appointment_time)
                  .toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' })
            : '—';

        const statusMap = {
            pending:   ['#fff3cd','#856404','fa-clock'],
            confirmed: ['#cfe2ff','#084298','fa-check-circle'],
            completed: ['#d1e7dd','#0f5132','fa-check-double'],
            cancelled: ['#f8d7da','#842029','fa-times-circle'],
        };
        const [sbg, scolor, sicon] = statusMap[apt.status] ?? ['#f0f4f8','#555','fa-question'];

        document.getElementById('viewModalBody').innerHTML = `
            <div style="background:linear-gradient(135deg,#f0f6ff,#e8f0fb);
                        border-radius:12px;padding:1rem;margin-bottom:1rem;
                        display:flex;align-items:center;gap:1rem;">
                <div style="width:50px;height:50px;border-radius:12px;
                            background:#e8f0fe;display:flex;align-items:center;
                            justify-content:center;font-size:1.3rem;color:#2969bf;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.95rem;color:#2c3e50;">
                        ${apt.appointment_number ?? 'N/A'}
                    </div>
                    <div style="font-size:.75rem;color:#888;">Appointment Reference</div>
                </div>
                <span class="status-pill ${apt.status} ms-auto">
                    <i class="fas ${sicon}"></i>${apt.status}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user me-2"></i>Patient</span>
                <span class="detail-value">${apt.patient_name ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-phone me-2"></i>Phone</span>
                <span class="detail-value">${apt.patient_phone ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-user-md me-2"></i>Doctor</span>
                <span class="detail-value">${apt.doctor_name ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-stethoscope me-2"></i>Specialization</span>
                <span class="detail-value">${apt.specialization ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar me-2"></i>Date</span>
                <span class="detail-value">${dateStr}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-clock me-2"></i>Time</span>
                <span class="detail-value">${timeStr}</span>
            </div>
            ${apt.notes ? `
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-notes-medical me-2"></i>Notes</span>
                <span class="detail-value">${apt.notes}</span>
            </div>` : ''}
            ${apt.cancellation_reason ? `
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-ban me-2" style="color:#e74c3c;"></i>Cancel Reason</span>
                <span class="detail-value" style="color:#e74c3c;">${apt.cancellation_reason}</span>
            </div>` : ''}`;
    });
}

// ════════════════════════════════════════════════
// CONFIRM APPOINTMENT
// ════════════════════════════════════════════════
function confirmApt(id) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmModalTitle').innerHTML =
        '<i class="fas fa-check-circle" style="color:#27ae60;"></i> Confirm Appointment';
    document.getElementById('confirmModalMsg').textContent =
        'Are you sure you want to confirm this appointment?';
    const btn = document.getElementById('confirmModalBtn');
    btn.className = 'btn-modal success';
    btn.innerHTML = '<i class="fas fa-check me-1"></i>Yes, Confirm';
    btn.onclick = function () {
        closeModal('confirmModal');
        postAction(`/hospital/appointments/${id}/confirm`, {}, 'Appointment confirmed!');
    };
    openModal('confirmModal');
}

// ════════════════════════════════════════════════
// COMPLETE APPOINTMENT
// ════════════════════════════════════════════════
function completeApt(id) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmModalTitle').innerHTML =
        '<i class="fas fa-check-double" style="color:#2969bf;"></i> Complete Appointment';
    document.getElementById('confirmModalMsg').textContent =
        'Mark this appointment as completed?';
    const btn = document.getElementById('confirmModalBtn');
    btn.className = 'btn-modal primary';
    btn.innerHTML = '<i class="fas fa-check-double me-1"></i>Mark Complete';
    btn.onclick = function () {
        closeModal('confirmModal');
        postAction(`/hospital/appointments/${id}/complete`, {}, 'Appointment marked as completed!');
    };
    openModal('confirmModal');
}

// ════════════════════════════════════════════════
// CANCEL APPOINTMENT
// ════════════════════════════════════════════════
function openCancelModal(id) {
    document.getElementById('cancelAptId').value = id;
    document.getElementById('cancelReason').value = '';
    openModal('cancelModal');
}

function submitCancel() {
    const id     = document.getElementById('cancelAptId').value;
    const reason = document.getElementById('cancelReason').value.trim();
    if (!reason) {
        document.getElementById('cancelReason').style.borderColor = '#e74c3c';
        document.getElementById('cancelReason').focus();
        return;
    }
    document.getElementById('cancelReason').style.borderColor = '';
    closeModal('cancelModal');
    postAction(`/hospital/appointments/${id}/cancel`, { reason }, 'Appointment cancelled.');
}

// ════════════════════════════════════════════════
// FILTERS
// ════════════════════════════════════════════════
function filterByStatus(status) {
    document.getElementById('filterStatus').value = status;
    loadAppointments(1);
}

function filterToday() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filterDate').value = today;
    loadAppointments(1);
}

function resetFilters() {
    document.getElementById('filterSearch').value  = '';
    document.getElementById('filterStatus').value  = '';
    document.getElementById('filterDate').value    = '';
    document.getElementById('filterPerPage').value = '15';
    loadAppointments(1);
}

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadAppointments(1), 500);
}

// ════════════════════════════════════════════════
// MODAL HELPERS
// ════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

// ════════════════════════════════════════════════
// HTTP HELPERS
// ════════════════════════════════════════════════
function apiFetch(url, callback) {
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF,
        },
        credentials: 'same-origin'
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(callback)
    .catch(err => console.error('API Error:', err));
}

function postAction(url, body = {}, successMsg = 'Done!') {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  CSRF,
        },
        credentials: 'same-origin',
        body: JSON.stringify(body),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(successMsg, 'success');
            loadAppointments(currentPage);
        } else {
            showToast(data.message ?? 'Something went wrong.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Request failed. Please try again.', 'error');
    });
}

// ════════════════════════════════════════════════
// TOAST NOTIFICATION
// ════════════════════════════════════════════════
function showToast(msg, type = 'success') {
    const existing = document.getElementById('aptToast');
    if (existing) existing.remove();

    const colors = {
        success: { bg:'#d1e7dd', color:'#0f5132', icon:'fa-check-circle' },
        error:   { bg:'#f8d7da', color:'#842029', icon:'fa-exclamation-circle' },
        info:    { bg:'#cfe2ff', color:'#084298', icon:'fa-info-circle' },
    };
    const c = colors[type] ?? colors.info;

    const toast = document.createElement('div');
    toast.id = 'aptToast';
    toast.style.cssText = `
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
        background:${c.bg}; color:${c.color};
        border-radius:12px; padding:.8rem 1.2rem;
        display:flex; align-items:center; gap:.6rem;
        font-size:.83rem; font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        animation:slideUp .3s ease;
        max-width:320px; border:1px solid ${c.color}33;
    `;
    toast.innerHTML = `<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

// ════════════════════════════════════════════════
// UTILITIES
// ════════════════════════════════════════════════
function initials(name) {
    return (name || 'U').split(' ')
        .map(w => w[0] || '')
        .join('').slice(0, 2).toUpperCase();
}
function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}

// CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes slideUp {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }
`;
document.head.appendChild(style);
</script>
@endpush
