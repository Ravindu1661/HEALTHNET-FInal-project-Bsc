@extends('doctor.layouts.master')

@section('title', 'Add Workplace')
@section('page-title', 'Add Workplace')

@push('styles')
<style>
/* ══════════════════════════════════════
   CREATE WORKPLACE
══════════════════════════════════════ */
.create-wrap { max-width: 960px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #0d6efd, #6f42c1);
    border-radius: 16px; padding: 1.4rem 1.5rem;
    color: #fff; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.ph-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .82; margin-top: .18rem; }

/* ── Form Card ── */
.form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem;
    margin-bottom: 1.2rem;
}
.form-sec-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.form-sec-title i { color: #0d6efd; }
.sec-badge {
    margin-left: auto; font-size: .67rem;
    font-weight: 600; color: #94a3b8;
}

/* ── Type Selector ── */
.type-row { display: flex; gap: .8rem; }
.type-btn {
    flex: 1; padding: 1.1rem .8rem;
    border: 2px solid #e2e8f0; border-radius: 14px;
    background: #fff; cursor: pointer;
    text-align: center; transition: all .2s;
    display: flex; flex-direction: column;
    align-items: center; gap: .4rem;
    position: relative;
}
.type-btn:hover    { border-color: #0d6efd; background: #f8faff; }
.type-btn.selected { border-color: #0d6efd; background: #f0f5ff; }
.type-check {
    position: absolute; top: .6rem; right: .6rem;
    color: #0d6efd; font-size: .85rem; display: none;
}
.type-btn.selected .type-check { display: block; }
.type-ico   { font-size: 1.8rem; }
.type-lbl   { font-size: .82rem; font-weight: 700; color: #1a1a1a; }
.type-sub   { font-size: .68rem; color: #94a3b8; }
.type-count {
    font-size: .65rem; font-weight: 700;
    background: #0d6efd; color: #fff;
    padding: .15rem .5rem; border-radius: 20px;
    margin-top: .1rem;
}
input.type-radio { display: none; }

/* ── City Filter Buttons ── */
.city-filters { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .75rem; }
.city-btn {
    padding: .25rem .7rem;
    border-radius: 20px; font-size: .7rem; font-weight: 600;
    border: 1.5px solid #e2e8f0; background: #fff;
    cursor: pointer; transition: all .15s; color: #64748b;
    white-space: nowrap;
}
.city-btn:hover  { border-color: #0d6efd; color: #0d6efd; }
.city-btn.active { background: #0d6efd; border-color: #0d6efd; color: #fff; }

/* ── Search Box ── */
.search-wrap { position: relative; margin-bottom: .75rem; }
.search-wrap input { padding-left: 2.2rem; padding-right: 2.2rem; }
.search-wrap .sw-ico {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; pointer-events: none; font-size: .85rem;
}
.search-wrap .sw-clear {
    position: absolute; right: .65rem; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; cursor: pointer;
    background: none; border: none; padding: 0;
    font-size: .8rem; display: none;
}

/* ── Workplace Grid ── */
.wp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: .65rem;
    max-height: 400px;
    overflow-y: auto;
    padding-right: .2rem;
}
.wp-grid::-webkit-scrollbar { width: 5px; }
.wp-grid::-webkit-scrollbar-track { background: #f0f3f8; border-radius: 10px; }
.wp-grid::-webkit-scrollbar-thumb { background: #c0c8d4; border-radius: 10px; }

/* ── Workplace Card ── */
.wp-card {
    border: 2px solid #e2e8f0; border-radius: 14px;
    background: #fff; cursor: pointer;
    transition: all .2s; overflow: hidden;
    position: relative;
}
.wp-card:hover    { border-color: #0d6efd; box-shadow: 0 4px 14px rgba(13,110,253,.1); }
.wp-card.selected { border-color: #0d6efd; background: #f0f5ff; }
.wp-card.affiliated {
    opacity: .5; cursor: not-allowed;
    border-color: #e2e8f0 !important;
    box-shadow: none !important;
}

/* Card Select Tick */
.wc-select-tick {
    position: absolute; top: .45rem; right: .45rem;
    width: 22px; height: 22px; border-radius: 50%;
    background: #fff; border: 2px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; color: #fff;
    transition: all .2s; flex-shrink: 0;
}
.wp-card.selected .wc-select-tick {
    background: #0d6efd; border-color: #0d6efd;
}

/* Already-added badge */
.wc-already-badge {
    position: absolute; top: .45rem; left: .45rem;
    font-size: .6rem; font-weight: 700;
    background: #fff3cd; color: #856404;
    padding: .1rem .38rem; border-radius: 6px;
    display: none;
}
.wp-card.affiliated .wc-already-badge { display: block; }

/* Card image strip */
.wc-img {
    width: 100%; height: 72px;
    background: linear-gradient(135deg,#e8f0fe,#f0e8fe);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: #0d6efd; overflow: hidden;
}
.wc-img img { width:100%; height:100%; object-fit:cover; }

.wc-body   { padding: .65rem .7rem; }
.wc-name   { font-size: .78rem; font-weight: 700; color: #1a1a1a; line-height: 1.25; }
.wc-city   { font-size: .65rem; color: #94a3b8; margin-top: .18rem; }
.wc-phone  { font-size: .65rem; color: #94a3b8; margin-top: .08rem; }

/* No results */
.no-results {
    text-align: center; padding: 2.5rem 1rem;
    grid-column: 1 / -1; color: #c0c8d4;
}
.no-results i { font-size: 1.8rem; display: block; margin-bottom: .5rem; }
.no-results p { font-size: .78rem; color: #94a3b8; margin: 0; }

/* ── Selected Preview ── */
.sel-preview {
    background: linear-gradient(135deg,#f0f5ff,#f5f0ff);
    border: 1.5px solid #0d6efd44;
    border-radius: 12px; padding: .85rem 1rem;
    display: flex; align-items: center; gap: .8rem;
    margin-top: .85rem; display: none;
}
.sel-logo {
    width: 44px; height: 44px; border-radius: 10px;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #0d6efd;
    overflow: hidden; flex-shrink: 0;
}
.sel-logo img { width:100%; height:100%; object-fit:cover; border-radius:10px; }
.sel-name { font-size: .85rem; font-weight: 700; color: #0d6efd; }
.sel-sub  { font-size: .7rem; color: #555; margin-top: .08rem; }
.sel-remove {
    margin-left: auto; background: none; border: none;
    color: #dc3545; font-size: .9rem; cursor: pointer;
    flex-shrink: 0;
}

/* ── Employment Cards ── */
.emp-cards { display: flex; gap: .7rem; flex-wrap: wrap; }
.emp-card {
    flex: 1; min-width: 130px;
    border: 2px solid #e2e8f0; border-radius: 14px;
    padding: 1rem .6rem; text-align: center;
    cursor: pointer; transition: all .2s;
    position: relative;
}
.emp-card:hover    { border-color: #0d6efd; background: #f8faff; }
.emp-card.selected { border-color: #0d6efd; background: #f0f5ff; }
.emp-check {
    position: absolute; top: .5rem; right: .5rem;
    color: #0d6efd; font-size: .82rem; display: none;
}
.emp-card.selected .emp-check { display: block; }
.emp-ico { font-size: 1.4rem; margin-bottom: .3rem; }
.emp-lbl { font-size: .78rem; font-weight: 700; color: #1a1a1a; }
.emp-sub { font-size: .67rem; color: #94a3b8; margin-top: .15rem; }
input.emp-radio { display: none; }

/* Result count label */
.result-label {
    font-size: .71rem; color: #94a3b8; font-weight: 600;
    margin-bottom: .5rem;
}

@media (max-width: 576px) {
    .type-row   { gap: .5rem; }
    .wp-grid    { grid-template-columns: 1fr 1fr; }
    .emp-cards  { gap: .4rem; }
}
</style>
@endpush

@section('content')
<div class="create-wrap">

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon"><i class="fas fa-hospital-alt"></i></div>
        <div>
            <div class="ph-title">Add New Workplace</div>
            <div class="ph-sub">
                Select a hospital or medical centre and submit an affiliation request.
                Admin will review and approve it.
            </div>
        </div>
        <a href="{{ route('doctor.workplaces.index') }}"
           class="btn btn-sm ms-auto"
           style="background:rgba(255,255,255,.2);color:#fff;
                  border:1.5px solid rgba(255,255,255,.35)">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    {{-- ══ Validation Errors ══ --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.8rem" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('doctor.workplaces.store') }}"
          method="POST" id="workplaceForm">
        @csrf

        {{-- ══════════════════════════════════════
             STEP 1 — WORKPLACE TYPE
        ══════════════════════════════════════ --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-layer-group"></i>
                Step 1 — Select Workplace Type
            </div>

            <div class="type-row">

                {{-- Hospital --}}
                <label class="type-btn {{ old('workplace_type','hospital') === 'hospital' ? 'selected' : '' }}"
                       id="typeBtn-hospital">
                    <input type="radio" name="workplace_type"
                           value="hospital" class="type-radio"
                           {{ old('workplace_type','hospital') === 'hospital' ? 'checked' : '' }}>
                    <i class="fas fa-check-circle type-check"></i>
                    <span class="type-ico" style="color:#1a6fa8">
                        <i class="fas fa-hospital"></i>
                    </span>
                    <span class="type-lbl">Hospital</span>
                    <span class="type-sub">Government or Private</span>
                    <span class="type-count">{{ $hospitals->count() }} available</span>
                </label>

                {{-- Medical Centre --}}
                <label class="type-btn {{ old('workplace_type') === 'medical_centre' ? 'selected' : '' }}"
                       id="typeBtn-medical_centre">
                    <input type="radio" name="workplace_type"
                           value="medical_centre" class="type-radio"
                           {{ old('workplace_type') === 'medical_centre' ? 'checked' : '' }}>
                    <i class="fas fa-check-circle type-check"></i>
                    <span class="type-ico" style="color:#1a7a4a">
                        <i class="fas fa-clinic-medical"></i>
                    </span>
                    <span class="type-lbl">Medical Centre</span>
                    <span class="type-sub">Clinic or Health Centre</span>
                    <span class="type-count">{{ $medicalCentres->count() }} available</span>
                </label>

            </div>
            @error('workplace_type')
            <div class="text-danger mt-1" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- ══════════════════════════════════════
             STEP 2 — BROWSE & SELECT
        ══════════════════════════════════════ --}}
        <div class="form-card" id="browseCard">
            <div class="form-sec-title">
                <i class="fas fa-th-large"></i>
                Step 2 — Browse &amp; Select Workplace
                <span class="sec-badge" id="resultBadge">
                    <span id="resultCount">0</span> workplaces
                </span>
            </div>

            <input type="hidden" name="workplace_id"
                   id="workplaceIdInput"
                   value="{{ old('workplace_id') }}">

            {{-- City Filters --}}
            <div class="city-filters" id="cityFilters"></div>

            {{-- Search --}}
            <div class="search-wrap">
                <i class="fas fa-search sw-ico"></i>
                <input type="text" id="wpSearch"
                       class="form-control form-control-sm"
                       placeholder="Search by name, city or address…">
                <button type="button" class="sw-clear" id="searchClear">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Count label --}}
            <div class="result-label" id="resultLabel">Showing 0 workplaces</div>

            {{-- Grid --}}
            <div class="wp-grid" id="wpGrid"></div>

            {{-- Selected Preview --}}
            <div class="sel-preview" id="selPreview">
                <div class="sel-logo" id="selLogo">
                    <i class="fas fa-hospital"></i>
                </div>
                <div>
                    <div class="sel-name" id="selName">–</div>
                    <div class="sel-sub"  id="selSub">–</div>
                </div>
                <button type="button" class="sel-remove" id="selRemove"
                        title="Clear selection">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            @error('workplace_id')
            <div class="text-danger mt-2" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- ══════════════════════════════════════
             STEP 3 — EMPLOYMENT TYPE
        ══════════════════════════════════════ --}}
        <div class="form-card" id="empCard" style="display:none">
            <div class="form-sec-title">
                <i class="fas fa-briefcase"></i>
                Step 3 — Employment Type
            </div>
            <div class="emp-cards">
                @foreach([
                    ['permanent', 'fa-id-badge',       '#0d6efd', 'Permanent', 'Full-time employee'],
                    ['temporary', 'fa-hourglass-half',  '#fd7e14', 'Temporary', 'Fixed-term contract'],
                    ['visiting',  'fa-car-side',        '#6f42c1', 'Visiting',  'Regular visit basis'],
                ] as [$val, $ico, $clr, $lbl, $sub])
                <label class="emp-card {{ old('employment_type') === $val ? 'selected' : '' }}"
                       id="empCard-{{ $val }}">
                    <input type="radio" name="employment_type"
                           value="{{ $val }}" class="emp-radio"
                           {{ old('employment_type') === $val ? 'checked' : '' }}>
                    <i class="fas fa-check-circle emp-check"></i>
                    <div class="emp-ico" style="color:{{ $clr }}">
                        <i class="fas {{ $ico }}"></i>
                    </div>
                    <div class="emp-lbl">{{ $lbl }}</div>
                    <div class="emp-sub">{{ $sub }}</div>
                </label>
                @endforeach
            </div>
            @error('employment_type')
            <div class="text-danger mt-1" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- ══ Submit ══ --}}
        <div class="d-flex justify-content-end gap-2 mt-1">
            <a href="{{ route('doctor.workplaces.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary"
                    id="submitBtn" disabled>
                <i class="fas fa-paper-plane me-1"></i>Submit Request
            </button>
        </div>

    </form>
</div>

{{-- Pass Laravel data to JS --}}
<script>
const WP_DATA = {
    hospital:       @json($hospitals->values()),
    medical_centre: @json($medicalCentres->values()),
};
const OLD_TYPE  = "{{ old('workplace_type', 'hospital') }}";
const OLD_WP_ID = parseInt("{{ old('workplace_id', 0) }}") || 0;
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── State ────────────────────────────────────────
    let currentType  = OLD_TYPE || 'hospital';
    let selectedId   = OLD_WP_ID || null;
    let activeCity   = 'all';
    let searchQuery  = '';

    // ── DOM refs ─────────────────────────────────────
    const typeButtons  = document.querySelectorAll('.type-btn');
    const browseCard   = document.getElementById('browseCard');
    const empCard      = document.getElementById('empCard');
    const wpGrid       = document.getElementById('wpGrid');
    const wpIdInput    = document.getElementById('workplaceIdInput');
    const submitBtn    = document.getElementById('submitBtn');
    const selPreview   = document.getElementById('selPreview');
    const selLogo      = document.getElementById('selLogo');
    const selName      = document.getElementById('selName');
    const selSub       = document.getElementById('selSub');
    const selRemove    = document.getElementById('selRemove');
    const cityFilters  = document.getElementById('cityFilters');
    const wpSearch     = document.getElementById('wpSearch');
    const searchClear  = document.getElementById('searchClear');
    const resultCount  = document.getElementById('resultCount');
    const resultLabel  = document.getElementById('resultLabel');
    const empCards     = document.querySelectorAll('.emp-card');

    // ══════════════════════════════════════════════════
    // TYPE SELECTION
    // ══════════════════════════════════════════════════
    typeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            typeButtons.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('.type-radio').checked = true;
            currentType = this.querySelector('.type-radio').value;

            // Reset browse
            resetSelection();
            searchQuery  = '';
            activeCity   = 'all';
            wpSearch.value = '';
            searchClear.style.display = 'none';

            buildCityFilters();
            renderGrid();
        });
    });

    // ══════════════════════════════════════════════════
    // CITY FILTER BUTTONS
    // ══════════════════════════════════════════════════
    function buildCityFilters() {
        const data   = WP_DATA[currentType] || [];
        const cities = [...new Set(
            data.map(p => p.city).filter(Boolean)
        )].sort();

        let html = `<button type="button" class="city-btn active" data-city="all">All</button>`;
        cities.slice(0, 9).forEach(c => {
            html += `<button type="button" class="city-btn" data-city="${c}">${c}</button>`;
        });
        cityFilters.innerHTML = html;

        cityFilters.querySelectorAll('.city-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                cityFilters.querySelectorAll('.city-btn')
                    .forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeCity = this.dataset.city;
                renderGrid();
            });
        });
    }

    // ══════════════════════════════════════════════════
    // RENDER GRID
    // ══════════════════════════════════════════════════
    function renderGrid() {
        const data = WP_DATA[currentType] || [];
        const q    = searchQuery.toLowerCase();
        const typeIcon = currentType === 'hospital'
                         ? 'fa-hospital' : 'fa-clinic-medical';

        const filtered = data.filter(p => {
            const mSearch =
                !q ||
                (p.name    && p.name.toLowerCase().includes(q))    ||
                (p.city    && p.city.toLowerCase().includes(q))     ||
                (p.address && p.address.toLowerCase().includes(q));
            const mCity =
                activeCity === 'all' || p.city === activeCity;
            return mSearch && mCity;
        });

        // Update count
        resultCount.textContent = filtered.length;
        resultLabel.textContent =
            'Showing ' + filtered.length + ' workplace' +
            (filtered.length !== 1 ? 's' : '');

        if (filtered.length === 0) {
            wpGrid.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search-minus"></i>
                    <p>No workplaces found</p>
                    <p style="font-size:.7rem;color:#c0c8d4;margin-top:.2rem">
                        Try a different search or city
                    </p>
                </div>`;
            return;
        }

        wpGrid.innerHTML = filtered.map(p => {
            const isSelected   = p.id === selectedId;
            const isAffiliated = p.already_affiliated === true
                              || p.already_affiliated === 1;

            const imgHtml = p.profile_image
                ? `<img src="/storage/${p.profile_image}"
                        alt=""
                        onerror="this.parentElement.innerHTML=
                            '<i class=\\'fas ${typeIcon}\\'></i>'">`
                : `<i class="fas ${typeIcon}"></i>`;

            return `
            <div class="wp-card
                        ${isSelected   ? 'selected'   : ''}
                        ${isAffiliated ? 'affiliated' : ''}"
                 data-id="${p.id}"
                 data-name="${escHtml(p.name)}"
                 data-city="${escHtml(p.city || '')}"
                 data-phone="${escHtml(p.phone || '')}"
                 data-image="${p.profile_image || ''}"
                 data-affiliated="${isAffiliated ? '1' : '0'}">

                <div class="wc-select-tick">
                    <i class="fas fa-check"></i>
                </div>
                <span class="wc-already-badge">
                    <i class="fas fa-link me-1"></i>Added
                </span>

                <div class="wc-img">${imgHtml}</div>

                <div class="wc-body">
                    <div class="wc-name">${escHtml(p.name)}</div>
                    ${p.city  ? `<div class="wc-city"><i class="fas fa-map-marker-alt me-1"></i>${escHtml(p.city)}</div>`  : ''}
                    ${p.phone ? `<div class="wc-phone"><i class="fas fa-phone me-1"></i>${escHtml(p.phone)}</div>` : ''}
                </div>
            </div>`;
        }).join('');

        // Bind clicks — skip affiliated
        wpGrid.querySelectorAll('.wp-card:not(.affiliated)').forEach(card => {
            card.addEventListener('click', function () {
                selectWorkplace({
                    id:    parseInt(this.dataset.id),
                    name:  this.dataset.name,
                    city:  this.dataset.city,
                    phone: this.dataset.phone,
                    image: this.dataset.image,
                });
            });
        });
    }

    // ══════════════════════════════════════════════════
    // SELECT WORKPLACE
    // ══════════════════════════════════════════════════
    function selectWorkplace(item) {
        selectedId         = item.id;
        wpIdInput.value    = item.id;

        // Highlight grid card
        wpGrid.querySelectorAll('.wp-card').forEach(c => {
            c.classList.toggle('selected', parseInt(c.dataset.id) === item.id);
        });

        // Preview
        selName.textContent = item.name;
        selSub.textContent  =
            [item.city  ? '📍 ' + item.city  : '',
             item.phone ? '📞 ' + item.phone : '']
            .filter(Boolean).join('   ') || 'Workplace selected';

        const typeIcon = currentType === 'hospital'
                         ? 'fa-hospital' : 'fa-clinic-medical';
        if (item.image) {
            selLogo.innerHTML =
                `<img src="/storage/${item.image}" alt=""
                      style="width:100%;height:100%;
                             object-fit:cover;border-radius:10px"
                      onerror="this.parentElement.innerHTML=
                          '<i class=\\'fas ${typeIcon}\\'></i>'">`;
        } else {
            selLogo.innerHTML = `<i class="fas ${typeIcon}"></i>`;
        }

        selPreview.style.display = 'flex';
        empCard.style.display    = 'block';

        // Smooth scroll to step 3
        empCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        checkReady();
    }

    // ══════════════════════════════════════════════════
    // RESET SELECTION
    // ══════════════════════════════════════════════════
    function resetSelection() {
        selectedId             = null;
        wpIdInput.value        = '';
        selPreview.style.display = 'none';
        empCard.style.display  = 'none';
        checkReady();
    }

    selRemove.addEventListener('click', function () {
        resetSelection();
        renderGrid(); // remove highlight from cards
    });

    // ══════════════════════════════════════════════════
    // SEARCH
    // ══════════════════════════════════════════════════
    wpSearch.addEventListener('input', function () {
        searchQuery = this.value.trim();
        searchClear.style.display = searchQuery ? 'block' : 'none';
        renderGrid();
    });

    searchClear.addEventListener('click', function () {
        wpSearch.value = '';
        searchQuery    = '';
        this.style.display = 'none';
        renderGrid();
        wpSearch.focus();
    });

    // ══════════════════════════════════════════════════
    // EMPLOYMENT TYPE
    // ══════════════════════════════════════════════════
    empCards.forEach(card => {
        card.addEventListener('click', function () {
            empCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('.emp-radio').checked = true;
            checkReady();
        });
    });

    // ══════════════════════════════════════════════════
    // SUBMIT READY
    // ══════════════════════════════════════════════════
    function checkReady() {
        const hasType = !!currentType;
        const hasWp   = !!selectedId;
        const hasEmp  = !!document.querySelector('.emp-radio:checked');
        submitBtn.disabled = !(hasType && hasWp && hasEmp);
    }

    // ══════════════════════════════════════════════════
    // HTML ESCAPE HELPER
    // ══════════════════════════════════════════════════
    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;')
            .replace(/'/g,'&#39;');
    }

    // ══════════════════════════════════════════════════
    // INIT
    // ══════════════════════════════════════════════════
    function init() {
        // Restore type button
        const activeTypeBtn = document.getElementById('typeBtn-' + currentType);
        if (activeTypeBtn) {
            typeButtons.forEach(b => b.classList.remove('selected'));
            activeTypeBtn.classList.add('selected');
            activeTypeBtn.querySelector('.type-radio').checked = true;
        }

        buildCityFilters();
        renderGrid();

        // Restore old selection after validation fail
        if (OLD_WP_ID) {
            const data  = WP_DATA[currentType] || [];
            const found = data.find(p => p.id === OLD_WP_ID);
            if (found) {
                selectWorkplace({
                    id:    found.id,
                    name:  found.name,
                    city:  found.city          || '',
                    phone: found.phone         || '',
                    image: found.profile_image || '',
                });
            }
        }

        checkReady();
    }

    init();
});
</script>
@endpush
