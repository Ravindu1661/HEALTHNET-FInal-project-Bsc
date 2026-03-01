@extends('doctor.layouts.master')

@section('title', 'My Workplaces')
@section('page-title', 'My Workplaces')

@push('styles')
<style>
/* ══════════════════════════════════════
   WORKPLACES INDEX
══════════════════════════════════════ */
.wp-page { max-width: 1300px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Stat Cards ── */
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1rem 1.2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; align-items: center; gap: .9rem;
    height: 100%;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.stat-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-num { font-size: 1.35rem; font-weight: 800; line-height: 1; }
.stat-lbl {
    font-size: .68rem; color: #94a3b8; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; margin-top: .18rem;
}

/* ── Filter Bar ── */
.filter-bar {
    background: #fff;
    border-radius: 14px;
    padding: .85rem 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; gap: .6rem; flex-wrap: wrap; align-items: center;
    margin-bottom: 1.2rem;
}
.filter-search { position: relative; flex: 1; min-width: 180px; }
.filter-search input  { padding-left: 2.2rem; }
.filter-search .fs-ico {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; pointer-events: none; font-size: .82rem;
}
.tab-pill {
    padding: .28rem .8rem;
    border-radius: 20px; font-size: .72rem; font-weight: 600;
    border: 1.5px solid #e2e8f0; background: #fff;
    cursor: pointer; transition: all .15s; color: #64748b;
    white-space: nowrap;
}
.tab-pill:hover  { border-color: #0d6efd; color: #0d6efd; }
.tab-pill.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }

/* ── Grid ── */
.wp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

/* ── Workplace Card ── */
.workplace-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column;
    height: 100%;
}
.workplace-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,.1);
}

/* Cover */
.wc-cover {
    width: 100%; height: 88px;
    background: linear-gradient(135deg,#e8f0fe,#f0e8fe);
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: #0d6efd;
}
.wc-cover img { width:100%; height:100%; object-fit:cover; }
.wc-cover-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, transparent 30%, rgba(0,0,0,.3));
}

/* Status Ribbon */
.status-ribbon {
    position: absolute; top: .5rem; right: .5rem;
    display: inline-flex; align-items: center; gap: .22rem;
    padding: .18rem .55rem; border-radius: 20px;
    font-size: .65rem; font-weight: 700;
    backdrop-filter: blur(4px);
    box-shadow: 0 2px 6px rgba(0,0,0,.12);
}
.ribbon-approved { background: #d4edda; color: #155724; }
.ribbon-pending  { background: #fff3cd; color: #856404; }
.ribbon-rejected { background: #f8d7da; color: #721c24; }

/* Header */
.wc-header {
    padding: 1rem 1.1rem .7rem;
    display: flex; align-items: flex-start; gap: .85rem;
    border-bottom: 1px solid #f5f7fa;
}
.wc-logo {
    width: 46px; height: 46px; border-radius: 12px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #0d6efd;
    overflow: hidden; flex-shrink: 0;
    border: 2px solid #e8f0fe;
}
.wc-logo img { width:100%; height:100%; object-fit:cover; border-radius:10px; }
.wc-name { font-size: .88rem; font-weight: 700; color: #1a1a1a; line-height: 1.25; }
.wc-city { font-size: .7rem; color: #94a3b8; margin-top: .15rem; }

/* Badges */
.wp-badge {
    display: inline-flex; align-items: center; gap: .2rem;
    padding: .16rem .5rem; border-radius: 20px;
    font-size: .64rem; font-weight: 700;
    white-space: nowrap; margin: .1rem .04rem;
}
.badge-hospital        { background: #e8f4fd; color: #1a6fa8; }
.badge-medical_centre  { background: #e8f8f0; color: #1a7a4a; }
.badge-permanent  { background: #e8f0fe; color: #1a3fa8; }
.badge-temporary  { background: #fef3e8; color: #a85a1a; }
.badge-visiting   { background: #f3e8fe; color: #6a1aa8; }

/* Body */
.wc-body { padding: .8rem 1.1rem; flex: 1; }
.wc-meta {
    font-size: .73rem; color: #555;
    display: flex; align-items: flex-start;
    gap: .4rem; margin-top: .42rem; line-height: 1.4;
}
.wc-meta i { color: #94a3b8; width: 14px; flex-shrink: 0; margin-top: .1rem; }

/* Notice Strip */
.wc-notice {
    border-radius: 9px; padding: .5rem .75rem;
    font-size: .71rem; font-weight: 500;
    display: flex; align-items: flex-start; gap: .4rem;
    margin-top: .7rem; line-height: 1.4;
}
.notice-pending  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.notice-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.notice-approved { background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0; }

/* Footer */
.wc-footer {
    padding: .75rem 1.1rem;
    border-top: 1px solid #f5f7fa;
    display: flex; gap: .4rem;
    justify-content: flex-end; flex-wrap: wrap;
}

/* Empty State */
.empty-state {
    text-align: center; padding: 4.5rem 1rem;
    grid-column: 1 / -1;
}
.es-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: #f0f5ff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.9rem; color: #0d6efd;
    margin: 0 auto .85rem;
}
.empty-state h6 { font-size: .9rem; font-weight: 700; color: #1a1a1a; margin-bottom: .3rem; }
.empty-state p  { font-size: .78rem; color: #94a3b8; margin: 0; }

/* Delete Modal */
.del-modal-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: #fef2f2;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #dc3545;
    margin: 0 auto 1rem;
}

@media (max-width: 576px) {
    .wp-grid { grid-template-columns: 1fr; }
    .stat-num { font-size: 1.1rem; }
}
</style>
@endpush

@section('content')
<div class="wp-page">

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══ Page Header ══ --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0" style="font-weight:800;color:#1a1a1a">
                <i class="fas fa-hospital-alt me-2 text-primary"></i>My Workplaces
            </h5>
            <div style="font-size:.74rem;color:#94a3b8;margin-top:.15rem">
                Hospitals &amp; Medical Centres you are affiliated with
            </div>
        </div>
        <a href="{{ route('doctor.workplaces.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add Workplace
        </a>
    </div>

    {{-- ══ Stats Row ══ --}}
    <div class="row g-3 mb-3">
        @foreach([
            ['Total',        $total,          '#0d6efd', 'fa-building',       'linear-gradient(135deg,#0d6efd22,#0d6efd55)'],
            ['Approved',     $approved,       '#198754', 'fa-check-circle',   'linear-gradient(135deg,#19875422,#19875455)'],
            ['Pending',      $pending,        '#fd7e14', 'fa-clock',          'linear-gradient(135deg,#fd7e1422,#fd7e1455)'],
            ['Rejected',     $rejected,       '#dc3545', 'fa-times-circle',   'linear-gradient(135deg,#dc354522,#dc354555)'],
            ['Hospitals',    $hospitals,      '#1a6fa8', 'fa-hospital',       'linear-gradient(135deg,#1a6fa822,#1a6fa855)'],
            ['Med. Centres', $medicalCentres, '#1a7a4a', 'fa-clinic-medical', 'linear-gradient(135deg,#1a7a4a22,#1a7a4a55)'],
        ] as [$lbl, $val, $clr, $ico, $bg])
        <div class="col-6 col-sm-4 col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $bg }}">
                    <i class="fas {{ $ico }}" style="color:{{ $clr }}"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:{{ $clr }}">{{ $val }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ Filter Bar ══ --}}
    <div class="filter-bar">

        {{-- Search --}}
        <div class="filter-search">
            <i class="fas fa-search fs-ico"></i>
            <input type="text" id="wpSearch"
                   class="form-control form-control-sm"
                   placeholder="Search by name or city…">
        </div>

        {{-- Status Pills --}}
        <div class="d-flex gap-1 flex-wrap">
            @foreach([
                ['all',      'All'],
                ['approved', 'Approved'],
                ['pending',  'Pending'],
                ['rejected', 'Rejected'],
            ] as [$val, $lbl])
            <button type="button"
                    class="tab-pill {{ $val === 'all' ? 'active' : '' }}"
                    data-filter="{{ $val }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>

        {{-- Type Pills --}}
        <div class="d-flex gap-1 flex-wrap">
            @foreach([
                ['all',            'All Types',    'fa-layer-group'],
                ['hospital',       'Hospital',     'fa-hospital'],
                ['medical_centre', 'Med. Centre',  'fa-clinic-medical'],
            ] as [$val, $lbl, $ico])
            <button type="button"
                    class="tab-pill {{ $val === 'all' ? 'active' : '' }}"
                    data-type="{{ $val }}">
                <i class="fas {{ $ico }} me-1"></i>{{ $lbl }}
            </button>
            @endforeach
        </div>

        <div style="margin-left:auto;font-size:.72rem;color:#94a3b8;white-space:nowrap">
            Showing
            <strong id="visibleCount">{{ $workplaces->count() }}</strong>
            of {{ $workplaces->count() }}
        </div>

    </div>

    {{-- ══ Workplace Grid ══ --}}
    <div class="wp-grid" id="wpGrid">

        @forelse($workplaces as $wp)
        @php
            $place    = $wp->place;
            $isHosp   = $wp->workplace_type === 'hospital';
            $typeIcon = $isHosp ? 'fa-hospital' : 'fa-clinic-medical';
            $typeLbl  = $isHosp ? 'Hospital'    : 'Medical Centre';
        @endphp

        <div class="workplace-card"
             data-name="{{ strtolower($place->name ?? '') }}"
             data-city="{{ strtolower($place->city ?? '') }}"
             data-status="{{ $wp->status }}"
             data-type="{{ $wp->workplace_type }}">

            {{-- Cover --}}
            <div class="wc-cover">
                @if($place && $place->profile_image)
                    <img src="{{ asset('storage/'.$place->profile_image) }}"
                         alt="{{ $place->name ?? '' }}"
                         onerror="this.style.display='none'">
                    <div class="wc-cover-overlay"></div>
                @else
                    <i class="fas {{ $typeIcon }}"></i>
                @endif

                <span class="status-ribbon ribbon-{{ $wp->status }}">
                    <i class="fas fa-{{
                        $wp->status === 'approved' ? 'check-circle' :
                        ($wp->status === 'pending'  ? 'clock'        : 'times-circle')
                    }}"></i>
                    {{ ucfirst($wp->status) }}
                </span>
            </div>

            {{-- Header --}}
            <div class="wc-header">
                <div class="wc-logo">
                    @if($place && $place->profile_image)
                        <img src="{{ asset('storage/'.$place->profile_image) }}"
                             alt="{{ $place->name ?? '' }}"
                             onerror="this.parentElement.innerHTML=
                                 '<i class=\'fas {{ $typeIcon }}\'></i>'">
                    @else
                        <i class="fas {{ $typeIcon }}"></i>
                    @endif
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="wc-name">
                        {{ $place->name ?? 'Workplace #'.$wp->workplace_id }}
                    </div>
                    @if($place && $place->city)
                    <div class="wc-city">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $place->city }}
                    </div>
                    @endif
                    <div class="mt-1">
                        <span class="wp-badge badge-{{ $wp->workplace_type }}">
                            <i class="fas {{ $typeIcon }}"></i>{{ $typeLbl }}
                        </span>
                        <span class="wp-badge badge-{{ $wp->employment_type }}">
                            <i class="fas fa-briefcase"></i>{{ ucfirst($wp->employment_type) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="wc-body">

                @if($place && $place->address)
                <div class="wc-meta">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ Str::limit($place->address, 55) }}</span>
                </div>
                @endif

                @if($place && $place->phone)
                <div class="wc-meta">
                    <i class="fas fa-phone"></i>
                    <span>{{ $place->phone }}</span>
                </div>
                @endif

                <div class="wc-meta">
                    <i class="fas fa-calendar-plus"></i>
                    <span>
                        Added {{ \Carbon\Carbon::parse($wp->created_at)->format('d M Y') }}
                    </span>
                </div>

                @if($wp->approved_at)
                <div class="wc-meta">
                    <i class="fas fa-check-double"></i>
                    <span>
                        Approved {{ \Carbon\Carbon::parse($wp->approved_at)->format('d M Y') }}
                    </span>
                </div>
                @endif

                {{-- Notice --}}
                @if($wp->status === 'pending')
                <div class="wc-notice notice-pending">
                    <i class="fas fa-hourglass-half mt-1 flex-shrink-0"></i>
                    <span>
                        Awaiting admin review. You can edit the employment type
                        while the request is pending.
                    </span>
                </div>
                @elseif($wp->status === 'rejected')
                <div class="wc-notice notice-rejected">
                    <i class="fas fa-times-circle mt-1 flex-shrink-0"></i>
                    <span>
                        This request was rejected by admin.
                        Remove it and resubmit if needed.
                    </span>
                </div>
                @elseif($wp->status === 'approved')
                <div class="wc-notice notice-approved">
                    <i class="fas fa-check-circle mt-1 flex-shrink-0"></i>
                    <span>
                        Active affiliation. You can now create
                        schedules for this workplace.
                    </span>
                </div>
                @endif

            </div>

            {{-- Footer ── Actions based on status ── --}}
            <div class="wc-footer">

                @if($wp->status === 'pending')
                    {{-- Edit employment type --}}
                    <a href="{{ route('doctor.workplaces.edit', $wp->id) }}"
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    {{-- Remove --}}
                    <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="{{ $wp->id }}"
                            data-name="{{ $place->name ?? 'this workplace' }}">
                        <i class="fas fa-trash me-1"></i>Remove
                    </button>

                @elseif($wp->status === 'rejected')
                    {{-- Remove only --}}
                    <button type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="{{ $wp->id }}"
                            data-name="{{ $place->name ?? 'this workplace' }}">
                        <i class="fas fa-trash me-1"></i>Remove
                    </button>

                @elseif($wp->status === 'approved')
                    {{-- Schedules shortcut --}}
                    @if(\Illuminate\Support\Facades\Route::has('doctor.schedules.index'))
                    <a href="{{ route('doctor.schedules.index', ['workplace_id' => $wp->id]) }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-calendar-alt me-1"></i>Schedules
                    </a>
                    @endif
                    {{-- Cannot delete approved --}}
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            disabled
                            title="Contact admin to remove approved workplaces">
                        <i class="fas fa-lock me-1"></i>Locked
                    </button>
                @endif

            </div>

        </div>{{-- /.workplace-card --}}
        @empty

        {{-- ── No workplaces at all ── --}}
        <div class="empty-state">
            <div class="es-icon">
                <i class="fas fa-hospital-alt"></i>
            </div>
            <h6>No workplaces yet</h6>
            <p>You haven't added any hospital or medical centre affiliation.</p>
            <a href="{{ route('doctor.workplaces.create') }}"
               class="btn btn-primary btn-sm mt-2">
                <i class="fas fa-plus me-1"></i>Add Your First Workplace
            </a>
        </div>

        @endforelse

    </div>{{-- /#wpGrid --}}

</div>{{-- /.wp-page --}}

{{-- ══════════════════════════════════════
     DELETE MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content"
             style="border-radius:18px;border:none;
                    box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-body text-center p-4">
                <div class="del-modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h6 class="fw-bold mb-1" style="font-size:.95rem">Remove Workplace?</h6>
                <p class="text-muted mb-3" style="font-size:.78rem" id="deleteModalText">
                    Are you sure?
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i>Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════
    // DELETE MODAL
    // ══════════════════════════════════════════════════
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm  = document.getElementById('deleteForm');
    const deleteText  = document.getElementById('deleteModalText');

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const name = this.dataset.name;
            deleteText.textContent =
                'Are you sure you want to remove "' + name + '"?';
            deleteForm.action = '/doctor/workplaces/' + id;
            deleteModal.show();
        });
    });

    // ══════════════════════════════════════════════════
    // LIVE FILTER
    // ══════════════════════════════════════════════════
    const cards        = document.querySelectorAll('.workplace-card');
    const searchInput  = document.getElementById('wpSearch');
    const statusPills  = document.querySelectorAll('[data-filter]');
    const typePills    = document.querySelectorAll('[data-type]');
    const visibleCount = document.getElementById('visibleCount');
    const wpGrid       = document.getElementById('wpGrid');

    let activeStatus = 'all';
    let activeType   = 'all';
    let searchQuery  = '';

    function applyFilters() {
        let visible = 0;

        cards.forEach(card => {
            const name   = (card.dataset.name   || '');
            const city   = (card.dataset.city   || '');
            const status =  card.dataset.status  || '';
            const type   =  card.dataset.type    || '';

            const matchSearch =
                !searchQuery ||
                name.includes(searchQuery) ||
                city.includes(searchQuery);

            const matchStatus =
                activeStatus === 'all' || status === activeStatus;

            const matchType =
                activeType === 'all' || type === activeType;

            const show = matchSearch && matchStatus && matchType;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (visibleCount) visibleCount.textContent = visible;

        // Dynamic empty state
        let emptyEl = wpGrid.querySelector('.wp-filter-empty');
        if (visible === 0 && cards.length > 0) {
            if (!emptyEl) {
                emptyEl = document.createElement('div');
                emptyEl.className = 'empty-state wp-filter-empty';
                emptyEl.innerHTML = `
                    <div class="es-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h6>No results</h6>
                    <p>No workplaces match your current filter.</p>`;
                wpGrid.appendChild(emptyEl);
            }
            emptyEl.style.display = '';
        } else if (emptyEl) {
            emptyEl.style.display = 'none';
        }
    }

    // Status pills
    statusPills.forEach(pill => {
        pill.addEventListener('click', function () {
            statusPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeStatus = this.dataset.filter;
            applyFilters();
        });
    });

    // Type pills
    typePills.forEach(pill => {
        pill.addEventListener('click', function () {
            typePills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeType = this.dataset.type;
            applyFilters();
        });
    });

    // Search
    searchInput.addEventListener('input', function () {
        searchQuery = this.value.trim().toLowerCase();
        applyFilters();
    });

});
</script>
@endpush
