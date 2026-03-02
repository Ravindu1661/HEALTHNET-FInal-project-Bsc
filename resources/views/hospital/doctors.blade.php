{{-- resources/views/hospital/doctors.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Doctors')
@section('page-title', 'Doctors Management')

@push('styles')
<style>
/* ══════════════════════════════════════════
   PAGE
══════════════════════════════════════════ */
.doc-page { animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }

/* ══════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════ */
.doc-stat {
    background:#fff; border-radius:14px;
    padding:1.1rem 1.3rem; border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.06);
    display:flex; align-items:center; gap:1rem;
    transition:transform .2s,box-shadow .2s;
    position:relative; overflow:hidden; cursor:pointer;
}
.doc-stat::before {
    content:''; position:absolute;
    top:0;left:0;right:0;height:3px; border-radius:14px 14px 0 0;
}
.doc-stat:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(44,62,80,.1);}
.doc-stat.total::before    {background:linear-gradient(90deg,#2969bf,#5b9bd5);}
.doc-stat.approved::before {background:linear-gradient(90deg,#27ae60,#6fcf97);}
.doc-stat.pending::before  {background:linear-gradient(90deg,#f39c12,#f7c04a);}
.doc-stat.rejected::before {background:linear-gradient(90deg,#e74c3c,#f1948a);}

.doc-stat-icon{
    width:48px;height:48px;border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.2rem;flex-shrink:0;
}
.doc-stat.total .doc-stat-icon    {background:#e8f0fe;color:#2969bf;}
.doc-stat.approved .doc-stat-icon {background:#e9f7ee;color:#27ae60;}
.doc-stat.pending .doc-stat-icon  {background:#fef8e7;color:#f39c12;}
.doc-stat.rejected .doc-stat-icon {background:#fdecea;color:#e74c3c;}
.doc-stat-info h4{font-size:1.6rem;font-weight:800;margin:0;line-height:1;}
.doc-stat-info p {font-size:.75rem;color:#888;margin:0 0 0 0;margin-top:3px;}

/* ══════════════════════════════════════════
   FILTER BAR
══════════════════════════════════════════ */
.filter-card{
    background:#fff;border-radius:14px;
    border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05);
    padding:1rem 1.3rem;margin-bottom:1.3rem;
}
.filter-group{display:flex;flex-wrap:wrap;gap:.65rem;align-items:flex-end;}
.filter-control{flex:1;min-width:160px;display:flex;flex-direction:column;gap:.3rem;}
.filter-control label{
    font-size:.72rem;font-weight:600;color:#555;
    text-transform:uppercase;letter-spacing:.05em;
}
.filter-control select,
.filter-control input{
    border:1.5px solid #e5ecf0;border-radius:9px;
    padding:.5rem .75rem;font-size:.83rem;color:#2c3e50;
    outline:none;background:#fafcff;font-family:inherit;width:100%;
    transition:border-color .2s,box-shadow .2s;
}
.filter-control select:focus,
.filter-control input:focus{
    border-color:#2969bf;box-shadow:0 0 0 3px rgba(41,105,191,.1);
}
.btn-filter{
    padding:.5rem 1.1rem;border-radius:9px;font-size:.82rem;font-weight:600;
    border:none;cursor:pointer;transition:all .2s;white-space:nowrap;
    display:inline-flex;align-items:center;gap:.4rem;
}
.btn-filter.primary{background:#2969bf;color:#fff;}
.btn-filter.primary:hover{background:#1a4f9a;box-shadow:0 4px 12px rgba(41,105,191,.3);}
.btn-filter.success{background:#27ae60;color:#fff;}
.btn-filter.success:hover{background:#1e8449;box-shadow:0 4px 12px rgba(39,174,96,.3);}
.btn-filter.reset{background:#f0f4f8;color:#555;}
.btn-filter.reset:hover{background:#e2e8f0;}

/* ══════════════════════════════════════════
   VIEW TOGGLE
══════════════════════════════════════════ */
.view-toggle{display:flex;gap:.3rem;}
.view-btn{
    width:34px;height:34px;border-radius:8px;
    border:1.5px solid #e5ecf0;background:#fff;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;color:#888;font-size:.85rem;
    transition:all .2s;
}
.view-btn.active{background:#2969bf;border-color:#2969bf;color:#fff;}
.view-btn:hover:not(.active){background:#f0f4f8;}

/* ══════════════════════════════════════════
   SECTION CARD
══════════════════════════════════════════ */
.section-card{
    background:#fff;border-radius:14px;
    border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05);
    overflow:hidden;
}
.section-header{
    padding:.9rem 1.3rem;border-bottom:1px solid #f0f4f8;
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:.5rem;
}
.section-header h6{
    font-size:.93rem;font-weight:700;color:#2c3e50;
    margin:0;display:flex;align-items:center;gap:.5rem;
}
.section-header h6 i{color:#2969bf;}

/* ══════════════════════════════════════════
   DOCTOR GRID
══════════════════════════════════════════ */
.doctors-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(270px,1fr));
    gap:1.1rem;padding:1.3rem;
}
.doc-card{
    border:1.5px solid #f0f4f8;border-radius:14px;
    background:#fff;overflow:hidden;
    transition:transform .2s,box-shadow .2s,border-color .2s;
    position:relative;
}
.doc-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 30px rgba(44,62,80,.1);
    border-color:#d0e4ff;
}
.doc-card-top{
    background:linear-gradient(135deg,#f0f6ff,#e8f0fb);
    padding:1.2rem 1.2rem .9rem;
    display:flex;align-items:center;gap:.9rem;
    border-bottom:1px solid #f0f4f8;
}
.doc-avatar{
    width:56px;height:56px;border-radius:14px;
    object-fit:cover;border:3px solid #fff;
    box-shadow:0 2px 8px rgba(41,105,191,.15);
    flex-shrink:0;
}
.doc-avatar-fallback{
    width:56px;height:56px;border-radius:14px;
    background:linear-gradient(135deg,#2969bf,#5b9bd5);
    display:flex;align-items:center;justify-content:center;
    font-size:1.2rem;font-weight:800;color:#fff;
    border:3px solid #fff;box-shadow:0 2px 8px rgba(41,105,191,.2);
    flex-shrink:0;
}
.doc-card-name{font-size:.88rem;font-weight:700;color:#2c3e50;margin:0 0 .2rem;}
.doc-card-spec{font-size:.73rem;color:#2969bf;margin:0 0 .3rem;font-weight:600;}
.doc-card-slmc{font-size:.68rem;color:#888;margin:0;}

.doc-card-body{padding:.9rem 1.2rem;}
.doc-meta{display:flex;flex-direction:column;gap:.35rem;margin-bottom:.85rem;}
.doc-meta-row{
    display:flex;align-items:center;gap:.5rem;
    font-size:.76rem;color:#555;
}
.doc-meta-row i{width:14px;text-align:center;color:#2969bf;font-size:.72rem;}

.doc-card-footer{
    padding:.75rem 1.2rem;border-top:1px solid #f5f7fa;
    display:flex;align-items:center;justify-content:space-between;gap:.5rem;
}

/* Star Rating */
.stars{display:inline-flex;gap:1px;}
.stars i{font-size:.7rem;color:#f39c12;}
.stars i.empty{color:#ddd;}

/* Employment badge */
.emp-badge{
    font-size:.62rem;font-weight:700;padding:.2rem .55rem;
    border-radius:99px;text-transform:capitalize;
}
.emp-permanent {background:#e9f7ee;color:#27ae60;}
.emp-temporary {background:#fff3cd;color:#856404;}
.emp-visiting  {background:#eaf4fd;color:#2969bf;}

/* Workplace status badge */
.ws-badge{
    font-size:.65rem;font-weight:700;padding:.22rem .6rem;
    border-radius:99px;display:inline-flex;align-items:center;gap:3px;
}
.ws-approved {background:#d1e7dd;color:#0f5132;}
.ws-pending  {background:#fff3cd;color:#856404;}
.ws-rejected {background:#f8d7da;color:#842029;}

/* Card actions */
.card-actions{display:flex;gap:.35rem;}
.btn-card-action{
    width:30px;height:30px;border-radius:7px;
    border:none;cursor:pointer;font-size:.75rem;
    display:inline-flex;align-items:center;justify-content:center;
    transition:all .2s;
}
.btn-card-action.approve{background:#e9f7ee;color:#27ae60;}
.btn-card-action.approve:hover{background:#27ae60;color:#fff;}
.btn-card-action.reject {background:#fdecea;color:#e74c3c;}
.btn-card-action.reject:hover {background:#e74c3c;color:#fff;}
.btn-card-action.view   {background:#f0f4f8;color:#6c757d;}
.btn-card-action.view:hover   {background:#2969bf;color:#fff;}

/* ══════════════════════════════════════════
   LIST VIEW (TABLE)
══════════════════════════════════════════ */
.doc-table{width:100%;border-collapse:collapse;}
.doc-table thead tr{
    background:#f8fbff;border-bottom:2px solid #edf2f7;
}
.doc-table thead th{
    padding:.75rem 1rem;font-size:.72rem;font-weight:700;
    color:#64748b;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;
}
.doc-table tbody tr{
    border-bottom:1px solid #f5f7fa;transition:background .15s;
}
.doc-table tbody tr:last-child{border-bottom:none;}
.doc-table tbody tr:hover{background:#f8fbff;}
.doc-table td{
    padding:.8rem 1rem;font-size:.83rem;color:#374151;vertical-align:middle;
}
.person-cell{display:flex;align-items:center;gap:.6rem;}
.person-avatar{
    width:36px;height:36px;border-radius:9px;
    background:linear-gradient(135deg,#2969bf,#5b9bd5);
    display:flex;align-items:center;justify-content:center;
    font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0;
}
.person-avatar img{
    width:36px;height:36px;border-radius:9px;
    object-fit:cover;border:2px solid #f0f4f8;
}
.person-name{font-weight:600;font-size:.83rem;color:#2c3e50;line-height:1.2;}
.person-sub {font-size:.72rem;color:#888;}

/* ══════════════════════════════════════════
   EMPTY / LOADING
══════════════════════════════════════════ */
.empty-state{text-align:center;padding:3.5rem 1rem;}
.empty-state i{font-size:3rem;color:#d0dae8;margin-bottom:1rem;display:block;}
.empty-state h6{color:#888;font-size:.95rem;margin:0 0 .3rem;}
.empty-state p {color:#aab4be;font-size:.8rem;margin:0;}

@keyframes shimmer{
    0%{background-position:-600px 0}100%{background-position:600px 0}
}
.skeleton-line{
    height:13px;border-radius:6px;
    background:linear-gradient(90deg,#f0f4f8 25%,#e4eaf0 50%,#f0f4f8 75%);
    background-size:1200px 100%;
    animation:shimmer 1.4s infinite linear;
}

/* ══════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════ */
.apt-pagination{
    display:flex;align-items:center;justify-content:space-between;
    padding:.85rem 1.3rem;border-top:1px solid #f0f4f8;
    flex-wrap:wrap;gap:.5rem;
}
.pagination-info{font-size:.78rem;color:#888;}
.pagination-btns{display:flex;gap:.3rem;}
.btn-page{
    min-width:32px;height:32px;border-radius:7px;
    border:1.5px solid #e5ecf0;background:#fff;
    font-size:.78rem;font-weight:600;color:#555;
    cursor:pointer;display:inline-flex;align-items:center;
    justify-content:center;transition:all .2s;padding:0 .4rem;
}
.btn-page:hover{background:#e8f0fe;border-color:#2969bf;color:#2969bf;}
.btn-page.active{background:#2969bf;border-color:#2969bf;color:#fff;}
.btn-page:disabled{opacity:.45;cursor:not-allowed;}

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.doc-modal-overlay{
    position:fixed;inset:0;
    background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px);
    z-index:2000;display:flex;
    align-items:center;justify-content:center;
    padding:1rem;opacity:0;visibility:hidden;
    transition:opacity .25s,visibility .25s;
}
.doc-modal-overlay.show{opacity:1;visibility:visible;}
.doc-modal{
    background:#fff;border-radius:16px;
    width:100%;max-width:560px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    transform:translateY(-20px) scale(.97);
    transition:transform .25s;overflow:hidden;
    max-height:90vh;display:flex;flex-direction:column;
}
.doc-modal-overlay.show .doc-modal{transform:translateY(0) scale(1);}
.doc-modal-header{
    padding:1.1rem 1.4rem;border-bottom:1px solid #f0f4f8;
    display:flex;align-items:center;justify-content:space-between;flex-shrink:0;
}
.doc-modal-header h5{
    font-size:.97rem;font-weight:700;margin:0;color:#2c3e50;
    display:flex;align-items:center;gap:.5rem;
}
.modal-close-btn{
    background:none;border:none;cursor:pointer;
    width:32px;height:32px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    color:#888;font-size:.9rem;transition:background .2s,color .2s;
}
.modal-close-btn:hover{background:#f0f4f8;color:#e74c3c;}
.doc-modal-body{padding:1.3rem 1.4rem;overflow-y:auto;flex:1;}
.doc-modal-footer{
    padding:.9rem 1.4rem;border-top:1px solid #f0f4f8;
    display:flex;justify-content:flex-end;gap:.6rem;flex-shrink:0;
}

/* Add Doctor Modal search */
.search-result-list{
    border:1.5px solid #e5ecf0;border-radius:10px;
    max-height:240px;overflow-y:auto;margin-top:.5rem;
}
.search-result-item{
    display:flex;align-items:center;gap:.75rem;
    padding:.7rem 1rem;border-bottom:1px solid #f5f7fa;
    cursor:pointer;transition:background .15s;
}
.search-result-item:last-child{border-bottom:none;}
.search-result-item:hover,.search-result-item.selected{background:#eef6ff;}
.search-result-item.selected{border-left:3px solid #2969bf;}
.sri-avatar{
    width:38px;height:38px;border-radius:9px;flex-shrink:0;
    background:linear-gradient(135deg,#2969bf,#5b9bd5);
    display:flex;align-items:center;justify-content:center;
    font-size:.78rem;font-weight:700;color:#fff;
}
.sri-avatar img{width:38px;height:38px;border-radius:9px;object-fit:cover;}
.sri-name {font-size:.83rem;font-weight:600;color:#2c3e50;}
.sri-spec {font-size:.72rem;color:#888;}
.sri-slmc {font-size:.68rem;color:#2969bf;font-weight:600;}

/* Form group */
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.9rem;}
.form-group label{
    font-size:.74rem;font-weight:600;color:#555;
    text-transform:uppercase;letter-spacing:.04em;
}
.form-group select,
.form-group input{
    border:1.5px solid #e5ecf0;border-radius:9px;
    padding:.55rem .85rem;font-size:.84rem;
    color:#2c3e50;outline:none;background:#fafcff;
    font-family:inherit;
    transition:border-color .2s,box-shadow .2s;
}
.form-group select:focus,
.form-group input:focus{
    border-color:#2969bf;box-shadow:0 0 0 3px rgba(41,105,191,.1);
}

/* Selected doctor preview */
.selected-doc-preview{
    background:linear-gradient(135deg,#f0f6ff,#e8f0fb);
    border:1.5px solid #d0e4ff;border-radius:12px;
    padding:.85rem 1rem;display:flex;align-items:center;gap:.85rem;
    margin-bottom:.9rem;
}

/* Btn variants */
.btn-modal{
    padding:.5rem 1.2rem;border-radius:9px;
    font-size:.83rem;font-weight:600;border:none;cursor:pointer;
    transition:all .2s;display:inline-flex;align-items:center;gap:.4rem;
}
.btn-modal.secondary{background:#f0f4f8;color:#555;}
.btn-modal.secondary:hover{background:#e2e8f0;}
.btn-modal.primary{background:#2969bf;color:#fff;}
.btn-modal.primary:hover{background:#1a4f9a;box-shadow:0 4px 12px rgba(41,105,191,.3);}
.btn-modal.success{background:#27ae60;color:#fff;}
.btn-modal.success:hover{background:#1e8449;box-shadow:0 4px 12px rgba(39,174,96,.3);}
.btn-modal.danger {background:#e74c3c;color:#fff;}
.btn-modal.danger:hover {background:#c0392b;box-shadow:0 4px 12px rgba(231,76,60,.3);}

/* Detail rows in view modal */
.detail-row{
    display:flex;gap:1rem;padding:.6rem 0;
    border-bottom:1px solid #f5f7fa;font-size:.83rem;
}
.detail-row:last-child{border-bottom:none;}
.detail-label{min-width:130px;color:#888;font-weight:500;flex-shrink:0;}
.detail-value{color:#2c3e50;font-weight:600;}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media(max-width:991.98px){
    .hide-md{display:none!important;}
    .doctors-grid{grid-template-columns:repeat(auto-fill,minmax(240px,1fr));}
}
@media(max-width:767.98px){
    .filter-control{min-width:100%;}
    .doctors-grid{grid-template-columns:1fr 1fr;}
    .doc-table thead{display:none;}
    .doc-table,.doc-table tbody,.doc-table tr,.doc-table td{display:block;width:100%;}
    .doc-table tr{
        margin-bottom:.75rem;border:1px solid #f0f4f8;
        border-radius:12px;overflow:hidden;background:#fff;
    }
    .doc-table td{
        display:flex;align-items:center;justify-content:space-between;
        padding:.55rem 1rem;border-bottom:1px solid #f5f7fa;font-size:.8rem;
    }
    .doc-table td:last-child{border-bottom:none;}
    .doc-table td::before{
        content:attr(data-label);font-size:.68rem;font-weight:700;
        color:#888;text-transform:uppercase;letter-spacing:.05em;min-width:110px;
    }
    .doc-table td.no-label::before{content:none;}
}
@media(max-width:575.98px){
    .doctors-grid{grid-template-columns:1fr;}
    .doc-stat-info h4{font-size:1.3rem;}
}
</style>
@endpush

@section('content')
<div class="doc-page">

    {{-- ══ STAT CARDS ══ --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['total',    'fa-user-md',     'Total Doctors',    ''],
            ['approved', 'fa-check-circle','Active Doctors',   'approved'],
            ['pending',  'fa-clock',       'Pending Approval', 'pending'],
            ['rejected', 'fa-times-circle','Rejected',         'rejected'],
        ] as [$cls, $icon, $label, $filterVal])
        <div class="col-6 col-sm-3">
            <div class="doc-stat {{ $cls }}"
                 onclick="filterByStatus('{{ $filterVal }}')">
                <div class="doc-stat-icon">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="doc-stat-info">
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

            <div class="filter-control" style="min-width:220px;flex:2;">
                <label><i class="fas fa-search me-1"></i>Search</label>
                <input type="text" id="filterSearch"
                       placeholder="Name, specialization, SLMC number..."
                       oninput="debounceLoad()">
            </div>

            <div class="filter-control">
                <label><i class="fas fa-filter me-1"></i>Status</label>
                <select id="filterStatus" onchange="loadDoctors()">
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="filter-control" style="min-width:100px;flex:0;">
                <label><i class="fas fa-list me-1"></i>Show</label>
                <select id="filterPerPage" onchange="loadDoctors()">
                    <option value="12" selected>12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                </select>
            </div>

            <div style="display:flex;gap:.5rem;align-items:flex-end;">
                <button class="btn-filter primary" onclick="loadDoctors()">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-sm-inline">Search</span>
                </button>
                <button class="btn-filter reset" onclick="resetFilters()">
                    <i class="fas fa-undo"></i>
                    <span class="d-none d-sm-inline">Reset</span>
                </button>
                <button class="btn-filter success" onclick="openAddModal()">
                    <i class="fas fa-user-plus"></i>
                    <span class="d-none d-sm-inline">Add Doctor</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MAIN CARD ══ --}}
    <div class="section-card">
        <div class="section-header">
            <h6>
                <i class="fas fa-user-md"></i>
                Hospital Doctors
                <span class="badge bg-primary rounded-pill ms-1"
                      id="totalBadge" style="font-size:.65rem;">0</span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                {{-- View toggle --}}
                <div class="view-toggle">
                    <button class="view-btn active" id="btnGrid"
                            onclick="setView('grid')" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" id="btnList"
                            onclick="setView('list')" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                {{-- Refresh --}}
                <button class="btn-card-action view" onclick="loadDoctors()"
                        title="Refresh"
                        style="width:34px;height:34px;border-radius:9px;">
                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                </button>
            </div>
        </div>

        {{-- GRID VIEW --}}
        <div id="gridView">
            <div class="doctors-grid" id="doctorsGrid">
                {{-- Skeleton --}}
                @for($i=0;$i<8;$i++)
                <div style="border:1.5px solid #f0f4f8;border-radius:14px;overflow:hidden;">
                    <div style="padding:1.2rem;background:#f8fbff;border-bottom:1px solid #f0f4f8;">
                        <div style="display:flex;gap:.9rem;">
                            <div class="skeleton-line" style="width:56px;height:56px;border-radius:14px;flex-shrink:0;"></div>
                            <div style="flex:1;">
                                <div class="skeleton-line" style="width:80%;margin-bottom:.4rem;"></div>
                                <div class="skeleton-line" style="width:60%;"></div>
                            </div>
                        </div>
                    </div>
                    <div style="padding:.9rem 1.2rem;">
                        <div class="skeleton-line" style="width:90%;margin-bottom:.4rem;"></div>
                        <div class="skeleton-line" style="width:70%;margin-bottom:.4rem;"></div>
                        <div class="skeleton-line" style="width:80%;"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- LIST VIEW --}}
        <div id="listView" style="display:none;overflow-x:auto;">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th class="hide-md">SLMC No.</th>
                        <th class="hide-md">Experience</th>
                        <th>Employment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="doctorsList">
                    @for($i=0;$i<6;$i++)
                    <tr>
                        <td><div class="skeleton-line" style="width:24px;"></div></td>
                        <td><div class="skeleton-line" style="width:150px;"></div></td>
                        <td><div class="skeleton-line" style="width:100px;"></div></td>
                        <td><div class="skeleton-line" style="width:80px;"></div></td>
                        <td><div class="skeleton-line" style="width:60px;"></div></td>
                        <td><div class="skeleton-line" style="width:70px;"></div></td>
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
     ADD DOCTOR MODAL
══════════════════════════════════════════════ --}}
<div class="doc-modal-overlay" id="addModal">
    <div class="doc-modal" style="max-width:520px;">
        <div class="doc-modal-header">
            <h5>
                <i class="fas fa-user-plus" style="color:#27ae60;"></i>
                Add Doctor to Hospital
            </h5>
            <button class="modal-close-btn" onclick="closeModal('addModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="doc-modal-body">

            {{-- Search Doctor --}}
            <div class="form-group">
                <label><i class="fas fa-search me-1"></i>Search Doctor</label>
                <input type="text" id="doctorSearchInput"
                       placeholder="Search by name, specialization, SLMC number..."
                       oninput="searchDoctors(this.value)">
            </div>

            {{-- Search Results --}}
            <div id="searchResultsWrap" style="display:none;">
                <div class="search-result-list" id="searchResults"></div>
            </div>

            {{-- Selected Doctor Preview --}}
            <div class="selected-doc-preview" id="selectedPreview" style="display:none;">
                <div class="sri-avatar" id="selAvatar">?</div>
                <div style="flex:1;min-width:0;">
                    <div class="sri-name" id="selName">—</div>
                    <div class="sri-spec" id="selSpec">—</div>
                    <div class="sri-slmc" id="selSlmc">—</div>
                </div>
                <button onclick="clearSelection()"
                        style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:.8rem;"
                        title="Clear selection">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
            <input type="hidden" id="selectedDoctorId">

            {{-- Employment Type --}}
            <div class="form-group">
                <label><i class="fas fa-briefcase me-1"></i>Employment Type</label>
                <select id="employmentType">
                    <option value="">Select Type</option>
                    <option value="permanent">Permanent</option>
                    <option value="temporary">Temporary</option>
                    <option value="visiting">Visiting</option>
                </select>
            </div>

            <p style="font-size:.75rem;color:#888;margin:0;">
                <i class="fas fa-info-circle me-1" style="color:#2969bf;"></i>
                The doctor will be added with <strong>Pending</strong> status
                and must be approved before appearing as active.
            </p>
        </div>
        <div class="doc-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('addModal')">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="btn-modal success" onclick="submitAddDoctor()" id="addDoctorBtn">
                <i class="fas fa-user-plus me-1"></i>Add Doctor
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     VIEW DOCTOR MODAL
══════════════════════════════════════════════ --}}
<div class="doc-modal-overlay" id="viewModal">
    <div class="doc-modal">
        <div class="doc-modal-header">
            <h5>
                <i class="fas fa-user-md" style="color:#2969bf;"></i>
                Doctor Profile
            </h5>
            <button class="modal-close-btn" onclick="closeModal('viewModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="doc-modal-body" id="viewModalBody">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x" style="color:#2969bf;"></i>
            </div>
        </div>
        <div class="doc-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('viewModal')">
                <i class="fas fa-times me-1"></i>Close
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     STATUS UPDATE MODAL
══════════════════════════════════════════════ --}}
<div class="doc-modal-overlay" id="statusModal">
    <div class="doc-modal" style="max-width:400px;">
        <div class="doc-modal-header">
            <h5 id="statusModalTitle">
                <i class="fas fa-user-check"></i> Update Status
            </h5>
            <button class="modal-close-btn" onclick="closeModal('statusModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="doc-modal-body">
            <p id="statusModalMsg" style="font-size:.85rem;color:#555;margin:0;"></p>
            <input type="hidden" id="statusWorkplaceId">
            <input type="hidden" id="statusNewValue">
        </div>
        <div class="doc-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('statusModal')">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="btn-modal success" id="statusConfirmBtn" onclick="submitStatusUpdate()">
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
let currentPage    = 1;
let totalPages     = 1;
let debounceTimer  = null;
let searchTimer    = null;
let currentView    = 'grid';
let doctorsCache   = [];
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    loadDoctors();
    loadStats();

    // Close on overlay click
    document.querySelectorAll('.doc-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });
    // ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape')
            document.querySelectorAll('.doc-modal-overlay.show')
                    .forEach(m => closeModal(m.id));
    });
});

// ════════════════════════════════════════════════
// LOAD STATS
// ════════════════════════════════════════════════
function loadStats() {
    apiFetch('{{ route("hospital.doctors.data") }}?per_page=1', d => {
        setText('stat-total', d.total ?? 0);
    });
    ['approved','pending','rejected'].forEach(s => {
        apiFetch(`{{ route("hospital.doctors.data") }}?per_page=1&status=${s}`, d => {
            setText('stat-' + s, d.total ?? 0);
        });
    });
}

// ════════════════════════════════════════════════
// LOAD DOCTORS
// ════════════════════════════════════════════════
function loadDoctors(page = 1) {
    currentPage = page;
    const search  = document.getElementById('filterSearch').value.trim();
    const status  = document.getElementById('filterStatus').value;
    const perPage = document.getElementById('filterPerPage').value;

    const params = new URLSearchParams();
    if (search)  params.set('search',   search);
    if (status)  params.set('status',   status);
    params.set('per_page', perPage);
    params.set('page',     page);

    const icon = document.getElementById('refreshIcon');
    if (icon) icon.style.animation = 'spin 1s linear infinite';

    apiFetch('{{ route("hospital.doctors.data") }}?' + params, function (data) {
        if (icon) icon.style.animation = '';
        doctorsCache = data.data ?? [];
        currentView === 'grid' ? renderGrid(data) : renderList(data);
        renderPagination(data);
        setText('totalBadge', data.total ?? 0);
        loadStats();
    });
}

// ════════════════════════════════════════════════
// VIEW TOGGLE
// ════════════════════════════════════════════════
function setView(v) {
    currentView = v;
    document.getElementById('gridView').style.display = v === 'grid' ? '' : 'none';
    document.getElementById('listView').style.display = v === 'list' ? '' : 'none';
    document.getElementById('btnGrid').classList.toggle('active', v === 'grid');
    document.getElementById('btnList').classList.toggle('active', v === 'list');

    if (doctorsCache.length) {
        const fakeData = { data: doctorsCache, from: 1, to: doctorsCache.length, total: doctorsCache.length };
        v === 'grid' ? renderGrid(fakeData) : renderList(fakeData);
    }
}

// ════════════════════════════════════════════════
// RENDER GRID
// ════════════════════════════════════════════════
function renderGrid(data) {
    const grid  = document.getElementById('doctorsGrid');
    const items = data.data ?? [];

    if (!items.length) {
        grid.innerHTML = `
            <div style="grid-column:1/-1;">
                <div class="empty-state">
                    <i class="fas fa-user-md"></i>
                    <h6>No doctors found</h6>
                    <p>Add doctors to your hospital or adjust your filters.</p>
                </div>
            </div>`;
        return;
    }

    grid.innerHTML = items.map(doc => {
        const inits     = initials(doc.first_name + ' ' + doc.last_name);
        const fullName  = `Dr. ${doc.first_name} ${doc.last_name}`;
        const rating    = parseFloat(doc.rating ?? 0);
        const empClass  = `emp-${doc.employment_type ?? 'visiting'}`;
        const wsClass   = `ws-${doc.workplace_status ?? 'pending'}`;
        const wsLabel   = capitalize(doc.workplace_status ?? 'pending');
        const wsIcon    = { approved:'fa-check-circle', pending:'fa-clock', rejected:'fa-times-circle' }[doc.workplace_status] ?? 'fa-circle';

        const avatarHtml = doc.profile_image
            ? `<img src="/storage/${doc.profile_image}" class="doc-avatar"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
               <div class="doc-avatar-fallback" style="display:none;">${inits}</div>`
            : `<div class="doc-avatar-fallback">${inits}</div>`;

        const stars = [1,2,3,4,5].map(s =>
            `<i class="fas fa-star ${s <= Math.round(rating) ? '' : 'empty'}"></i>`
        ).join('');

        // Action buttons based on workplace status
        let actionBtns = `
            <button class="btn-card-action view" onclick="viewDoctor(${doc.id}, ${doc.workplace_id})"
                    title="View Details">
                <i class="fas fa-eye"></i>
            </button>`;

        if (doc.workplace_status === 'pending') {
            actionBtns += `
            <button class="btn-card-action approve"
                    onclick="openStatusModal(${doc.workplace_id}, 'approved', '${fullName}')"
                    title="Approve">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn-card-action reject"
                    onclick="openStatusModal(${doc.workplace_id}, 'rejected', '${fullName}')"
                    title="Reject">
                <i class="fas fa-times"></i>
            </button>`;
        } else if (doc.workplace_status === 'approved') {
            actionBtns += `
            <button class="btn-card-action reject"
                    onclick="openStatusModal(${doc.workplace_id}, 'rejected', '${fullName}')"
                    title="Remove / Reject">
                <i class="fas fa-user-times"></i>
            </button>`;
        } else if (doc.workplace_status === 'rejected') {
            actionBtns += `
            <button class="btn-card-action approve"
                    onclick="openStatusModal(${doc.workplace_id}, 'approved', '${fullName}')"
                    title="Re-approve">
                <i class="fas fa-user-check"></i>
            </button>`;
        }

        return `
        <div class="doc-card">
            <div class="doc-card-top">
                ${avatarHtml}
                <div style="min-width:0;">
                    <p class="doc-card-name">${fullName}</p>
                    <p class="doc-card-spec">${doc.specialization ?? '—'}</p>
                    <p class="doc-card-slmc">
                        <i class="fas fa-id-card me-1"></i>${doc.slmc_number ?? '—'}
                    </p>
                </div>
            </div>
            <div class="doc-card-body">
                <div class="doc-meta">
                    <div class="doc-meta-row">
                        <i class="fas fa-briefcase"></i>
                        ${doc.experience_years ?? 0} yrs experience
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-money-bill-wave"></i>
                        LKR ${Number(doc.consultation_fee ?? 0).toLocaleString()} / visit
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-star" style="color:#f39c12;"></i>
                        <span class="stars">${stars}</span>
                        <span style="font-size:.7rem;color:#888;">(${doc.total_ratings ?? 0})</span>
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-envelope"></i>
                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            ${doc.email ?? '—'}
                        </span>
                    </div>
                </div>
            </div>
            <div class="doc-card-footer">
                <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;">
                    <span class="emp-badge ${empClass}">${capitalize(doc.employment_type ?? 'visiting')}</span>
                    <span class="ws-badge ${wsClass}">
                        <i class="fas ${wsIcon}" style="font-size:.5rem;"></i>${wsLabel}
                    </span>
                </div>
                <div class="card-actions">${actionBtns}</div>
            </div>
        </div>`;
    }).join('');
}

// ════════════════════════════════════════════════
// RENDER LIST
// ════════════════════════════════════════════════
function renderList(data) {
    const tbody = document.getElementById('doctorsList');
    const items = data.data ?? [];
    const from  = data.from ?? 1;

    if (!items.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="no-label" style="padding:0;">
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h6>No doctors found</h6>
                        <p>Add doctors to your hospital or adjust filters.</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = items.map((doc, i) => {
        const inits    = initials(doc.first_name + ' ' + doc.last_name);
        const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
        const wsClass  = `ws-${doc.workplace_status ?? 'pending'}`;
        const wsLabel  = capitalize(doc.workplace_status ?? 'pending');
        const empClass = `emp-${doc.employment_type ?? 'visiting'}`;

        const avatarHtml = doc.profile_image
            ? `<img src="/storage/${doc.profile_image}"
                    onerror="this.style.display='none'">`
            : `<span style="font-size:.72rem;font-weight:700;color:#fff;">${inits}</span>`;

        let actionBtns = `
            <button class="btn-card-action view"
                    onclick="viewDoctor(${doc.id}, ${doc.workplace_id})" title="View">
                <i class="fas fa-eye"></i>
            </button>`;

        if (doc.workplace_status === 'pending') {
            actionBtns += `
            <button class="btn-card-action approve"
                    onclick="openStatusModal(${doc.workplace_id},'approved','${fullName}')"
                    title="Approve">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn-card-action reject"
                    onclick="openStatusModal(${doc.workplace_id},'rejected','${fullName}')"
                    title="Reject">
                <i class="fas fa-times"></i>
            </button>`;
        } else if (doc.workplace_status === 'approved') {
            actionBtns += `
            <button class="btn-card-action reject"
                    onclick="openStatusModal(${doc.workplace_id},'rejected','${fullName}')"
                    title="Reject">
                <i class="fas fa-user-times"></i>
            </button>`;
        } else {
            actionBtns += `
            <button class="btn-card-action approve"
                    onclick="openStatusModal(${doc.workplace_id},'approved','${fullName}')"
                    title="Re-approve">
                <i class="fas fa-user-check"></i>
            </button>`;
        }

        return `
        <tr>
            <td data-label="#">${from + i}</td>
            <td data-label="Doctor">
                <div class="person-cell">
                    <div class="person-avatar">${avatarHtml}</div>
                    <div>
                        <div class="person-name">${fullName}</div>
                        <div class="person-sub">${doc.email ?? ''}</div>
                    </div>
                </div>
            </td>
            <td data-label="Specialization">${doc.specialization ?? '—'}</td>
            <td data-label="SLMC No." class="hide-md">
                <span style="font-size:.75rem;font-family:monospace;
                             background:#eef6ff;color:#2969bf;
                             padding:.15rem .45rem;border-radius:5px;">
                    ${doc.slmc_number ?? '—'}
                </span>
            </td>
            <td data-label="Experience" class="hide-md">
                ${doc.experience_years ?? 0} yrs
            </td>
            <td data-label="Employment">
                <span class="emp-badge ${empClass}">
                    ${capitalize(doc.employment_type ?? 'visiting')}
                </span>
            </td>
            <td data-label="Status">
                <span class="ws-badge ${wsClass}">${wsLabel}</span>
            </td>
            <td data-label="Actions" class="no-label">
                <div style="display:flex;gap:.35rem;">${actionBtns}</div>
            </td>
        </tr>`;
    }).join('');
}

// ════════════════════════════════════════════════
// VIEW DOCTOR
// ════════════════════════════════════════════════
function viewDoctor(doctorId, workplaceId) {
    openModal('viewModal');
    document.getElementById('viewModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x" style="color:#2969bf;"></i>
        </div>`;

    apiFetch(`{{ route("hospital.doctors.data") }}?per_page=200`, function (data) {
        const doc = (data.data ?? []).find(d => d.id == doctorId);
        if (!doc) {
            document.getElementById('viewModalBody').innerHTML =
                '<p class="text-center text-muted py-3">Doctor not found.</p>';
            return;
        }

        const inits    = initials(doc.first_name + ' ' + doc.last_name);
        const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
        const rating   = parseFloat(doc.rating ?? 0);
        const stars    = [1,2,3,4,5].map(s =>
            `<i class="fas fa-star ${s <= Math.round(rating) ? '' : 'empty'}"></i>`
        ).join('');

        const wsClass = `ws-${doc.workplace_status ?? 'pending'}`;
        const empClass= `emp-${doc.employment_type ?? 'visiting'}`;

        const avatarSrc = doc.profile_image
            ? `/storage/${doc.profile_image}` : null;

        document.getElementById('viewModalBody').innerHTML = `
            <div style="background:linear-gradient(135deg,#f0f6ff,#e8f0fb);
                        border-radius:14px;padding:1.2rem;margin-bottom:1.2rem;
                        display:flex;align-items:center;gap:1rem;">
                ${avatarSrc
                    ? `<img src="${avatarSrc}" style="width:64px;height:64px;border-radius:14px;
                               object-fit:cover;border:3px solid #fff;
                               box-shadow:0 2px 8px rgba(41,105,191,.15);"
                           onerror="this.style.display='none'">`
                    : `<div style="width:64px;height:64px;border-radius:14px;
                                   background:linear-gradient(135deg,#2969bf,#5b9bd5);
                                   display:flex;align-items:center;justify-content:center;
                                   font-size:1.3rem;font-weight:800;color:#fff;
                                   border:3px solid #fff;">${inits}</div>`
                }
                <div style="flex:1;min-width:0;">
                    <h6 style="margin:0 0 .2rem;font-size:1rem;font-weight:700;color:#2c3e50;">
                        ${fullName}
                    </h6>
                    <p style="margin:0 0 .4rem;font-size:.78rem;font-weight:600;color:#2969bf;">
                        ${doc.specialization ?? '—'}
                    </p>
                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;align-items:center;">
                        <span class="ws-badge ${wsClass}">
                            ${capitalize(doc.workplace_status ?? 'pending')}
                        </span>
                        <span class="emp-badge ${empClass}">
                            ${capitalize(doc.employment_type ?? 'visiting')}
                        </span>
                    </div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.2rem;font-weight:800;color:#f39c12;">${rating.toFixed(1)}</div>
                    <div class="stars">${stars}</div>
                    <div style="font-size:.65rem;color:#888;">${doc.total_ratings ?? 0} reviews</div>
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-id-card me-2"></i>SLMC Number</span>
                <span class="detail-value" style="font-family:monospace;">${doc.slmc_number ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-envelope me-2"></i>Email</span>
                <span class="detail-value">${doc.email ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-phone me-2"></i>Phone</span>
                <span class="detail-value">${doc.phone ?? '—'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-briefcase me-2"></i>Experience</span>
                <span class="detail-value">${doc.experience_years ?? 0} years</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-money-bill me-2"></i>Consultation Fee</span>
                <span class="detail-value">LKR ${Number(doc.consultation_fee ?? 0).toLocaleString()}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar me-2"></i>Joined</span>
                <span class="detail-value">${formatDate(doc.joined_at)}</span>
            </div>
            ${doc.workplace_status === 'pending' ? `
            <div style="display:flex;gap:.6rem;margin-top:1rem;">
                <button class="btn-modal success" style="flex:1;"
                        onclick="closeModal('viewModal');
                                 openStatusModal(${doc.workplace_id},'approved','${fullName}')">
                    <i class="fas fa-check me-1"></i>Approve
                </button>
                <button class="btn-modal danger" style="flex:1;"
                        onclick="closeModal('viewModal');
                                 openStatusModal(${doc.workplace_id},'rejected','${fullName}')">
                    <i class="fas fa-times me-1"></i>Reject
                </button>
            </div>` : ''}`;
    });
}

// ════════════════════════════════════════════════
// STATUS MODAL
// ════════════════════════════════════════════════
function openStatusModal(workplaceId, newStatus, doctorName) {
    document.getElementById('statusWorkplaceId').value = workplaceId;
    document.getElementById('statusNewValue').value    = newStatus;

    const isApprove = newStatus === 'approved';
    const title = document.getElementById('statusModalTitle');
    const msg   = document.getElementById('statusModalMsg');
    const btn   = document.getElementById('statusConfirmBtn');

    title.innerHTML = isApprove
        ? `<i class="fas fa-user-check" style="color:#27ae60;"></i> Approve Doctor`
        : `<i class="fas fa-user-times" style="color:#e74c3c;"></i> Reject Doctor`;

    msg.textContent = isApprove
        ? `Approve ${doctorName} as an active doctor in your hospital?`
        : `Are you sure you want to reject ${doctorName}? They will be removed from active doctors.`;

    btn.className = isApprove ? 'btn-modal success' : 'btn-modal danger';
    btn.innerHTML = isApprove
        ? '<i class="fas fa-check me-1"></i>Approve'
        : '<i class="fas fa-times me-1"></i>Reject';

    openModal('statusModal');
}

function submitStatusUpdate() {
    const workplaceId = document.getElementById('statusWorkplaceId').value;
    const status      = document.getElementById('statusNewValue').value;
    closeModal('statusModal');

    postAction(
        `/hospital/doctors/${workplaceId}/status`,
        { status },
        `Doctor status updated to ${status}.`
    );
}

// ════════════════════════════════════════════════
// ADD DOCTOR MODAL
// ════════════════════════════════════════════════
function openAddModal() {
    clearSelection();
    document.getElementById('doctorSearchInput').value = '';
    document.getElementById('employmentType').value    = '';
    document.getElementById('searchResultsWrap').style.display = 'none';
    document.getElementById('searchResults').innerHTML = '';
    openModal('addModal');
    setTimeout(() => document.getElementById('doctorSearchInput').focus(), 300);
}

let selectedDoctor = null;

function searchDoctors(q) {
    clearTimeout(searchTimer);
    const wrap = document.getElementById('searchResultsWrap');
    const list = document.getElementById('searchResults');

    if (!q.trim()) {
        wrap.style.display = 'none';
        return;
    }

    searchTimer = setTimeout(() => {
        list.innerHTML = `
            <div style="padding:1rem;text-align:center;color:#888;font-size:.8rem;">
                <i class="fas fa-spinner fa-spin me-1"></i>Searching...
            </div>`;
        wrap.style.display = '';

        apiFetch(`{{ route("hospital.doctors.search") }}?q=${encodeURIComponent(q)}`, function (data) {
            const docs = data.doctors ?? [];
            if (!docs.length) {
                list.innerHTML = `
                    <div style="padding:1rem;text-align:center;color:#888;font-size:.8rem;">
                        <i class="fas fa-user-slash me-1"></i>No available doctors found.
                    </div>`;
                return;
            }

            list.innerHTML = docs.map(d => {
                const inits    = initials(d.first_name + ' ' + d.last_name);
                const fullName = `Dr. ${d.first_name} ${d.last_name}`;
                const avatarHtml = d.profile_image
                    ? `<img src="/storage/${d.profile_image}"
                             onerror="this.style.display='none'"
                             style="width:38px;height:38px;border-radius:9px;object-fit:cover;">`
                    : inits;

                return `
                <div class="search-result-item" onclick="selectDoctor(${JSON.stringify(d).replace(/"/g,'&quot;')})">
                    <div class="sri-avatar">${avatarHtml}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="sri-name">${fullName}</div>
                        <div class="sri-spec">${d.specialization ?? '—'}</div>
                        <div class="sri-slmc">
                            <i class="fas fa-id-card me-1"></i>${d.slmc_number ?? '—'}
                            &nbsp;·&nbsp;
                            <i class="fas fa-star me-1" style="color:#f39c12;"></i>
                            ${parseFloat(d.rating ?? 0).toFixed(1)}
                            &nbsp;·&nbsp;
                            ${d.experience_years ?? 0} yrs exp
                        </div>
                    </div>
                    <i class="fas fa-plus-circle" style="color:#27ae60;font-size:1rem;"></i>
                </div>`;
            }).join('');
        });
    }, 400);
}

function selectDoctor(doc) {
    selectedDoctor = doc;
    document.getElementById('selectedDoctorId').value = doc.id;

    const inits    = initials(doc.first_name + ' ' + doc.last_name);
    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
    const avatarEl = document.getElementById('selAvatar');

    if (doc.profile_image) {
        avatarEl.innerHTML = `<img src="/storage/${doc.profile_image}"
                                   style="width:38px;height:38px;border-radius:9px;object-fit:cover;"
                                   onerror="this.outerHTML='${inits}'">`;
    } else {
        avatarEl.textContent = inits;
    }

    document.getElementById('selName').textContent = fullName;
    document.getElementById('selSpec').textContent = doc.specialization ?? '—';
    document.getElementById('selSlmc').textContent = `SLMC: ${doc.slmc_number ?? '—'} · ${doc.experience_years ?? 0} yrs exp`;

    document.getElementById('selectedPreview').style.display = 'flex';
    document.getElementById('searchResultsWrap').style.display = 'none';
    document.getElementById('doctorSearchInput').value = '';
}

function clearSelection() {
    selectedDoctor = null;
    document.getElementById('selectedDoctorId').value = '';
    document.getElementById('selectedPreview').style.display = 'none';
    document.getElementById('doctorSearchInput').value = '';
    document.getElementById('searchResultsWrap').style.display = 'none';
}

function submitAddDoctor() {
    const doctorId = document.getElementById('selectedDoctorId').value;
    const empType  = document.getElementById('employmentType').value;

    if (!doctorId) {
        showToast('Please search and select a doctor first.', 'error');
        document.getElementById('doctorSearchInput').focus();
        return;
    }
    if (!empType) {
        showToast('Please select an employment type.', 'error');
        document.getElementById('employmentType').focus();
        return;
    }

    const btn = document.getElementById('addDoctorBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';

    fetch('{{ route("hospital.doctors.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  CSRF,
        },
        credentials: 'same-origin',
        body: JSON.stringify({ doctor_id: doctorId, employment_type: empType }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-user-plus me-1"></i>Add Doctor';

        if (data.success) {
            closeModal('addModal');
            showToast(data.message ?? 'Doctor added successfully!', 'success');
            loadDoctors(currentPage);
        } else {
            showToast(data.message ?? 'Failed to add doctor.', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-user-plus me-1"></i>Add Doctor';
        console.error(err);
        showToast('Request failed. Please try again.', 'error');
    });
}

// ════════════════════════════════════════════════
// FILTERS
// ════════════════════════════════════════════════
function filterByStatus(status) {
    document.getElementById('filterStatus').value = status;
    loadDoctors(1);
}

function resetFilters() {
    document.getElementById('filterSearch').value  = '';
    document.getElementById('filterStatus').value  = '';
    document.getElementById('filterPerPage').value = '12';
    loadDoctors(1);
}

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadDoctors(1), 500);
}

// ════════════════════════════════════════════════
// PAGINATION
// ════════════════════════════════════════════════
function renderPagination(data) {
    totalPages = data.last_page ?? 1;
    const from = data.from ?? 0, to = data.to ?? 0, total = data.total ?? 0;

    document.getElementById('paginationInfo').textContent =
        total ? `Showing ${from}–${to} of ${total} doctors` : 'No results';

    const btns = document.getElementById('paginationBtns');
    let html = '';

    html += `<button class="btn-page" onclick="loadDoctors(${currentPage-1})"
             ${currentPage<=1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;

    let start = Math.max(1, currentPage-2);
    let end   = Math.min(totalPages, start+4);
    if (end-start<4) start = Math.max(1,end-4);

    if (start>1) {
        html += `<button class="btn-page" onclick="loadDoctors(1)">1</button>`;
        if (start>2) html += `<button class="btn-page" disabled>…</button>`;
    }
    for (let p=start;p<=end;p++) {
        html += `<button class="btn-page ${p===currentPage?'active':''}"
                 onclick="loadDoctors(${p})">${p}</button>`;
    }
    if (end<totalPages) {
        if (end<totalPages-1) html += `<button class="btn-page" disabled>…</button>`;
        html += `<button class="btn-page" onclick="loadDoctors(${totalPages})">${totalPages}</button>`;
    }
    html += `<button class="btn-page" onclick="loadDoctors(${currentPage+1})"
             ${currentPage>=totalPages?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;

    btns.innerHTML = html;
}

// ════════════════════════════════════════════════
// POST ACTION
// ════════════════════════════════════════════════
function postAction(url, body={}, successMsg='Done!') {
    fetch(url, {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN':CSRF,
        },
        credentials:'same-origin',
        body:JSON.stringify(body),
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.success){
            showToast(successMsg,'success');
            loadDoctors(currentPage);
        } else {
            showToast(data.message??'Something went wrong.','error');
        }
    })
    .catch(err=>{
        console.error(err);
        showToast('Request failed. Please try again.','error');
    });
}

// ════════════════════════════════════════════════
// MODAL HELPERS
// ════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

// ════════════════════════════════════════════════
// API FETCH
// ════════════════════════════════════════════════
function apiFetch(url, cb) {
    fetch(url, {
        headers:{
            'Accept':'application/json',
            'X-Requested-With':'XMLHttpRequest',
            'X-CSRF-TOKEN':CSRF,
        },
        credentials:'same-origin'
    })
    .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
    .then(cb)
    .catch(err=>console.error('API Error:',err));
}

// ════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════
function showToast(msg, type='success') {
    const ex = document.getElementById('docToast');
    if(ex) ex.remove();

    const c = {
        success:{bg:'#d1e7dd',color:'#0f5132',icon:'fa-check-circle'},
        error:  {bg:'#f8d7da',color:'#842029',icon:'fa-exclamation-circle'},
        info:   {bg:'#cfe2ff',color:'#084298',icon:'fa-info-circle'},
    }[type] ?? {bg:'#cfe2ff',color:'#084298',icon:'fa-info-circle'};

    const t = document.createElement('div');
    t.id = 'docToast';
    t.style.cssText = `
        position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${c.bg};color:${c.color};
        border-radius:12px;padding:.8rem 1.2rem;
        display:flex;align-items:center;gap:.6rem;
        font-size:.83rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        animation:slideUp .3s ease;max-width:320px;
        border:1px solid ${c.color}33;
    `;
    t.innerHTML = `<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(()=>t.remove(), 3500);
}

// ════════════════════════════════════════════════
// UTILITIES
// ════════════════════════════════════════════════
function initials(name){
    return(name||'D').split(' ').map(w=>w[0]||'').join('').slice(0,2).toUpperCase();
}
function capitalize(s){ return s?s.charAt(0).toUpperCase()+s.slice(1):s; }
function setText(id,v){ const e=document.getElementById(id);if(e)e.textContent=v; }
function formatDate(d){
    if(!d) return '—';
    return new Date(d).toLocaleDateString('en-US',{day:'numeric',month:'short',year:'numeric'});
}

// Inject animations
const s = document.createElement('style');
s.textContent = `
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes slideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
`;
document.head.appendChild(s);
</script>
@endpush
