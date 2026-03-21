@include('partials.header')

<style>
/* ══════════════════════════════════════════════════
   BOOK LAB TEST — Teal Theme (with Smart Test Picker)
══════════════════════════════════════════════════ */

/* ── Base ── */
.loc-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3rem; color: white;
    position: relative; overflow: hidden;
}
.loc-header::before {
    content:''; position:absolute; inset:0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.06;
}
.loc-header .container { position:relative; z-index:1; }
.loc-header::after {
    content:''; position:absolute; bottom:-1px; left:0; right:0;
    height:45px; background:#f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.loc-main { background:#f4f6f9; padding:2rem 0 4rem; }

/* ── Lab Info Box ── */
.lab-info-box {
    background: linear-gradient(135deg,rgba(8,145,178,0.08),rgba(8,145,178,0.15));
    border: 2px solid rgba(8,145,178,0.25);
    border-radius: 14px; padding: 1.4rem;
    display: flex; gap: 1.2rem; align-items: center;
    margin-bottom: 1.5rem;
}
.lab-info-box .lab-avatar {
    width: 70px; height: 70px; border-radius: 12px;
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.8rem; flex-shrink: 0;
}
.lab-info-box .lab-name { font-size: 1.15rem; font-weight: 700; color: #0c4a6e; }
.lab-info-box .lab-meta { font-size: 0.82rem; color: #666; margin-top: 0.3rem; }

/* ── Form Card ── */
.loc-card {
    background: white; border-radius: 14px;
    padding: 1.6rem; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.loc-section-title {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 1.1rem; padding-bottom: 0.7rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.loc-section-title i { color: #0891b2; }

/* ════════════════════════════════════════════
   SMART TEST PICKER (New Component)
════════════════════════════════════════════ */

/* ── Picker Toolbar ── */
.picker-toolbar {
    display: flex; gap: 0.7rem; align-items: center;
    flex-wrap: wrap; margin-bottom: 1rem;
}
.picker-search-wrap {
    position: relative; flex: 1; min-width: 200px;
}
.picker-search-wrap i {
    position: absolute; left: 0.85rem; top: 50%;
    transform: translateY(-50%); color: #0891b2;
    font-size: 0.85rem; pointer-events: none;
}
.picker-search {
    width: 100%; padding: 0.62rem 1rem 0.62rem 2.4rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.87rem; color: #333;
    transition: all 0.25s; background: #fafafa;
    font-family: inherit;
}
.picker-search:focus {
    border-color: #0891b2; outline: none;
    background: white;
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
}
.picker-search::placeholder { color: #bbb; }

/* ── Category Filter Pills ── */
.cat-pills {
    display: flex; gap: 0.45rem; flex-wrap: wrap;
    margin-bottom: 1rem;
}
.cat-pill {
    padding: 0.32rem 0.85rem;
    border: 1.5px solid #e2e8f0; border-radius: 99px;
    font-size: 0.75rem; font-weight: 700; color: #64748b;
    cursor: pointer; transition: all 0.2s; background: white;
    white-space: nowrap; user-select: none;
}
.cat-pill:hover { border-color: #0891b2; color: #0891b2; background: #f0f9ff; }
.cat-pill.active {
    background: #0891b2; border-color: #0891b2;
    color: white; box-shadow: 0 2px 8px rgba(8,145,178,0.3);
}
.cat-pill .pill-count {
    background: rgba(255,255,255,0.25); border-radius: 99px;
    padding: 0 0.35rem; font-size: 0.68rem; margin-left: 0.2rem;
}
.cat-pill:not(.active) .pill-count {
    background: #f1f5f9; color: #94a3b8;
}

/* ── Test List (compact row style) ── */
.test-list { display: flex; flex-direction: column; gap: 0; }

.test-row {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.7rem 0.85rem;
    border: 1.5px solid transparent;
    border-radius: 10px; cursor: pointer;
    transition: all 0.18s; user-select: none;
    margin-bottom: 0.35rem;
    background: #f8fafc;
}
.test-row:hover {
    background: #f0f9ff; border-color: #bae6fd;
}
.test-row.selected {
    background: #f0f9ff; border-color: #0891b2;
    box-shadow: 0 2px 10px rgba(8,145,178,0.12);
}

/* Custom checkbox */
.tr-check {
    width: 20px; height: 20px; flex-shrink: 0;
    border: 2px solid #cbd5e1; border-radius: 5px;
    background: white; display: flex; align-items: center;
    justify-content: center; transition: all 0.18s;
}
.test-row.selected .tr-check {
    background: #0891b2; border-color: #0891b2;
}
.tr-check i { font-size: 0.6rem; color: transparent; transition: color 0.1s; }
.test-row.selected .tr-check i { color: white; }

/* Test row info */
.tr-info { flex: 1; min-width: 0; }
.tr-name  { font-weight: 700; color: #0c4a6e; font-size: 0.85rem; line-height: 1.3; }
.tr-meta  { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 0.18rem; }
.tr-badge {
    font-size: 0.67rem; font-weight: 600; padding: 0.1rem 0.45rem;
    border-radius: 5px; white-space: nowrap;
}
.tr-badge.cat   { background: #e0f2fe; color: #0369a1; }
.tr-badge.dur   { background: #ede9fe; color: #6d28d9; }
.tr-badge.req   { background: #fef3c7; color: #92400e; }

.tr-price {
    font-weight: 800; color: #0891b2; font-size: 0.9rem;
    flex-shrink: 0; white-space: nowrap;
}

/* ── Accordion Sections ── */
.acc-section { margin-bottom: 0.5rem; }
.acc-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.8rem 1rem; background: #f0f9ff;
    border: 1.5px solid #bae6fd; border-radius: 10px;
    cursor: pointer; transition: all 0.2s; user-select: none;
    gap: 0.6rem;
}
.acc-header:hover { background: #e0f2fe; border-color: #7dd3fc; }
.acc-header.open  { border-radius: 10px 10px 0 0; border-bottom-color: transparent; }
.acc-title {
    font-weight: 700; color: #0c4a6e; font-size: 0.88rem;
    flex: 1;
}
.acc-count {
    background: #0891b2; color: white; font-size: 0.68rem;
    font-weight: 700; padding: 0.12rem 0.55rem; border-radius: 99px;
}
.acc-count.selected-count { background: #16a34a; }
.acc-arrow {
    color: #0891b2; font-size: 0.78rem;
    transition: transform 0.25s;
}
.acc-header.open .acc-arrow { transform: rotate(180deg); }

.acc-body {
    border: 1.5px solid #bae6fd; border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 0.75rem 0.75rem 0.4rem;
    display: none;
    background: white;
}
.acc-body.open { display: block; animation: accOpen 0.22s ease; }
@keyframes accOpen {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Package Items (grid layout) ── */
.pkg-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 0.8rem;
}
.pkg-card {
    border: 2px solid #e9ecef; border-radius: 12px;
    padding: 1rem; cursor: pointer; transition: all 0.2s;
    user-select: none; background: #fafafa;
    display: flex; flex-direction: column; gap: 0.5rem;
}
.pkg-card:hover  { border-color: #0891b2; background: #f0f9ff; }
.pkg-card.selected {
    border-color: #0891b2; background: #f0f9ff;
    box-shadow: 0 3px 12px rgba(8,145,178,0.15);
}
.pkg-card-top { display: flex; align-items: flex-start; gap: 0.6rem; }
.pkg-check {
    width: 20px; height: 20px; flex-shrink: 0;
    border: 2px solid #cbd5e1; border-radius: 5px;
    background: white; display: flex; align-items: center;
    justify-content: center; transition: all 0.18s; margin-top: 2px;
}
.pkg-card.selected .pkg-check { background: #0891b2; border-color: #0891b2; }
.pkg-check i { font-size: 0.6rem; color: transparent; transition: color 0.1s; }
.pkg-card.selected .pkg-check i { color: white; }

.pkg-name  { font-weight: 700; color: #0c4a6e; font-size: 0.87rem; flex: 1; line-height: 1.3; }
.pkg-tests {
    font-size: 0.73rem; color: #666; line-height: 1.6;
    padding: 0.4rem 0.65rem; background: #f0f9ff;
    border-radius: 7px; border-left: 3px solid #0891b2;
}
.pkg-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto;
}
.pkg-price    { font-weight: 800; color: #0891b2; font-size: 0.95rem; }
.pkg-orig     { text-decoration: line-through; color: #aaa; font-size: 0.73rem; margin-right: 0.3rem; }
.pkg-discount {
    background: #dcfce7; color: #166534;
    padding: 0.1rem 0.45rem; border-radius: 6px;
    font-size: 0.68rem; font-weight: 700;
}

/* ── No Results ── */
.no-results {
    text-align: center; padding: 2rem 1rem;
    color: #94a3b8; font-size: 0.85rem;
}
.no-results i { font-size: 2rem; display: block; margin-bottom: 0.5rem; color: #bae6fd; }

/* ── Selected Banner (floating pill) ── */
.selected-banner {
    display: none; align-items: center; gap: 0.5rem;
    padding: 0.5rem 0.85rem; background: #0891b2;
    color: white; border-radius: 99px; font-size: 0.78rem;
    font-weight: 700; margin-bottom: 1rem;
    animation: bannerIn 0.3s ease;
}
@keyframes bannerIn {
    from { opacity:0; transform: scale(0.95); }
    to   { opacity:1; transform: scale(1); }
}
.selected-banner.show { display: inline-flex; }
.clear-all-btn {
    background: rgba(255,255,255,0.25); border: none; border-radius: 99px;
    padding: 0.12rem 0.6rem; font-size: 0.72rem; font-weight: 700;
    color: white; cursor: pointer; margin-left: 0.3rem; transition: all 0.15s;
}
.clear-all-btn:hover { background: rgba(255,255,255,0.4); }

/* ── Form Controls ── */
.loc-label { display:block; font-size:0.88rem; font-weight:700; color:#0c4a6e; margin-bottom:0.45rem; }
.loc-input, .loc-select, .loc-textarea {
    width:100%; padding:0.72rem 1rem;
    border:2px solid #e9ecef; border-radius:10px;
    font-size:0.9rem; color:#333; transition:all 0.3s;
    background: white; font-family: inherit;
}
.loc-input:focus, .loc-select:focus, .loc-textarea:focus {
    border-color:#0891b2; outline:none;
    box-shadow:0 0 0 3px rgba(8,145,178,0.1);
}
.loc-textarea { resize:vertical; min-height:90px; }

/* ── Collection Type Cards ── */
.col-type-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0.8rem; margin-bottom: 0.5rem;
}
@media (max-width: 500px) { .col-type-grid { grid-template-columns: 1fr; } }
.col-type-card {
    border: 2px solid #e9ecef; border-radius: 12px;
    padding: 1.1rem 1rem; cursor: pointer; transition: all 0.3s;
    display: flex; align-items: center; gap: 0.8rem;
    background: #f8f9fa; user-select: none;
}
.col-type-card:hover { border-color: #0891b2; background: white; }
.col-type-card.active {
    border-color: #0891b2; background: #f0f9ff;
    box-shadow: 0 4px 12px rgba(8,145,178,0.15);
}
.col-type-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: #e0f2fe; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
    font-size: 1.1rem; color: #0891b2; transition: all 0.3s;
}
.col-type-card.active .col-type-icon {
    background: linear-gradient(135deg,#0891b2,#0c4a6e); color: white;
}
.col-type-title { font-weight: 700; color: #0c4a6e; font-size: 0.88rem; }
.col-type-sub   { font-size: 0.72rem; color: #888; }

/* Home address reveal */
#homeAddressSection {
    background: #f0f9ff; border: 1.5px solid #bae6fd;
    border-radius: 12px; padding: 1.1rem;
    margin-top: 1rem; display: none;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Summary Box ── */
.order-summary {
    background: white; border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    overflow: hidden; position: sticky; top: 80px;
}
.summary-header {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white; padding: 1.1rem 1.4rem;
    font-weight: 700; font-size: 0.95rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.summary-body { padding: 1.2rem 1.4rem; max-height: 350px; overflow-y: auto; }
.summary-item {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 0.6rem 0; border-bottom: 1px solid #f0f9ff; font-size: 0.84rem;
}
.summary-item:last-child { border-bottom: none; }
.summary-name  { color: #555; flex: 1; padding-right: 0.5rem; line-height: 1.4; }
.summary-price { font-weight: 700; color: #0891b2; flex-shrink: 0; }
.summary-remove {
    width: 18px; height: 18px; border-radius: 50%;
    background: #fee2e2; color: #dc2626; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.6rem; cursor: pointer; flex-shrink: 0;
    margin-left: 0.4rem; transition: all 0.15s;
}
.summary-remove:hover { background: #dc2626; color: white; }
.summary-total {
    background: #e0f2fe; padding: 1rem 1.4rem;
    display: flex; justify-content: space-between; align-items: center;
}
.summary-empty {
    text-align: center; padding: 1.5rem 0;
    color: #bbb; font-size: 0.85rem;
}
.summary-empty i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }

/* ── Submit ── */
.loc-submit-btn {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white; border: none;
    padding: 1rem 2rem; border-radius: 12px;
    font-size: 0.95rem; font-weight: 700;
    cursor: pointer; transition: all 0.3s; width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    box-shadow: 0 4px 14px rgba(8,145,178,0.3);
}
.loc-submit-btn:hover:not(:disabled) {
    filter: brightness(1.1); transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(8,145,178,0.4);
}
.loc-submit-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* ── Alerts ── */
.loc-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: flex-start; gap: 0.8rem; font-size: 0.9rem;
}
.loc-alert.error   { background:#fee2e2; color:#991b1b; border-left:5px solid #dc2626; }
.loc-alert.warning { background:#fef3c7; color:#92400e; border-left:5px solid #f59e0b; }
.loc-alert.info    { background:#e0f2fe; color:#0c4a6e; border-left:5px solid #0891b2; }

/* ── Tab switcher (Tests / Packages) ── */
.picker-tabs {
    display: flex; gap: 0; margin-bottom: 1rem;
    border: 2px solid #e0f2fe; border-radius: 10px; overflow: hidden;
}
.picker-tab {
    flex: 1; padding: 0.6rem 1rem; border: none;
    font-size: 0.84rem; font-weight: 700; cursor: pointer;
    transition: all 0.2s; background: white; color: #94a3b8;
    display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    font-family: inherit;
}
.picker-tab:first-child { border-right: 1px solid #e0f2fe; }
.picker-tab.active { background: #0891b2; color: white; }
.picker-tab .tab-badge {
    background: rgba(255,255,255,0.25); color: inherit;
    padding: 0.08rem 0.45rem; border-radius: 99px;
    font-size: 0.68rem;
}
.picker-tab:not(.active) .tab-badge { background: #f1f5f9; color: #0891b2; }

.tab-panel { display: none; }
.tab-panel.active { display: block; }

@media (max-width:768px) {
    .loc-header { padding: 5rem 0 2.5rem; }
    .lab-info-box { flex-direction: column; text-align: center; }
    .order-summary { position: static; margin-top: 1.5rem; }
    .pkg-grid { grid-template-columns: 1fr; }
}
</style>

{{-- ══ PAGE HEADER ══ --}}
<section class="loc-header">
    <div class="container">
        <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
           style="color:rgba(255,255,255,0.9);text-decoration:none;font-size:0.88rem;
                  display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1rem;
                  transition:all 0.3s;">
            <i class="fas fa-arrow-left"></i> Back to Lab Profile
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-flask me-2" style="opacity:0.85;"></i> Book Lab Test
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">
                    Select tests, choose collection method and submit your order
                </p>
            </div>
        </div>
    </div>
</section>

<section class="loc-main">
    <div class="container">

        {{-- Alerts --}}
        @if(session('error'))
        <div class="loc-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="loc-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('patient.lab-orders.store', $laboratory->id) }}"
              method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf
            <input type="hidden" name="collection_type" id="collectionTypeInput" value="walk_in">

            <div class="row g-4">

                {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
                <div class="col-lg-8">

                    {{-- Lab Info --}}
                    <div class="lab-info-box">
                        @if($laboratory->profile_image)
                            <img src="{{ asset('storage/'.$laboratory->profile_image) }}"
                                 alt="{{ $laboratory->name }}"
                                 style="width:70px;height:70px;border-radius:12px;object-fit:cover;
                                        border:3px solid rgba(8,145,178,0.3);">
                        @else
                            <div class="lab-avatar"><i class="fas fa-flask"></i></div>
                        @endif
                        <div>
                            <div class="lab-name">{{ $laboratory->name }}</div>
                            <div class="lab-meta">
                                @if($laboratory->city)
                                <span><i class="fas fa-map-marker-alt me-1" style="color:#0891b2;"></i>{{ $laboratory->city }}</span>
                                @endif
                                @if($laboratory->phone)
                                <span class="ms-2"><i class="fas fa-phone me-1" style="color:#0891b2;"></i>{{ $laboratory->phone }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         SMART TEST & PACKAGE PICKER
                    ══════════════════════════════════════════ --}}
                    @if($labTests->count() > 0 || $labPackages->count() > 0)
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-search-plus"></i> Select Tests &amp; Packages
                            <span id="selectedBanner" class="selected-banner ms-auto">
                                <i class="fas fa-check-circle"></i>
                                <span id="selectedBannerText">0 selected</span>
                                <button type="button" class="clear-all-btn" onclick="clearAllSelections()">
                                    Clear All
                                </button>
                            </span>
                        </div>

                        {{-- Tab Switcher --}}
                        <div class="picker-tabs" id="pickerTabs">
                            @if($labTests->count() > 0)
                            <button type="button" class="picker-tab active" data-tab="tests"
                                    onclick="switchTab('tests')">
                                <i class="fas fa-vial"></i>
                                Individual Tests
                                <span class="tab-badge">{{ $labTests->count() }}</span>
                            </button>
                            @endif
                            @if($labPackages->count() > 0)
                            <button type="button" class="picker-tab {{ $labTests->count() === 0 ? 'active' : '' }}"
                                    data-tab="packages" onclick="switchTab('packages')">
                                <i class="fas fa-box-open"></i>
                                Test Packages
                                <span class="tab-badge">{{ $labPackages->count() }}</span>
                            </button>
                            @endif
                        </div>

                        {{-- ── TESTS TAB ── --}}
                        @if($labTests->count() > 0)
                        <div class="tab-panel {{ $labTests->count() > 0 ? 'active' : '' }}" id="tab-tests">

                            {{-- Search + Category Pills --}}
                            <div class="picker-toolbar">
                                <div class="picker-search-wrap">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="testSearch"
                                           class="picker-search"
                                           placeholder="Search tests by name, category..."
                                           oninput="filterTests()">
                                </div>
                                <button type="button"
                                        style="padding:0.6rem 0.9rem;border:2px solid #e9ecef;
                                               border-radius:10px;background:white;color:#64748b;
                                               font-size:0.78rem;cursor:pointer;font-weight:700;
                                               white-space:nowrap;transition:all 0.2s;font-family:inherit;"
                                        onclick="collapseAllAcc()">
                                    <i class="fas fa-compress-alt me-1"></i> Collapse All
                                </button>
                            </div>

                            {{-- Category Filter Pills --}}
                            @php $testCats = $labTests->groupBy('test_category'); @endphp
                            <div class="cat-pills" id="catPills">
                                <span class="cat-pill active" data-cat="all"
                                      onclick="filterByCat('all', this)">
                                    <i class="fas fa-th-list me-1"></i> All
                                    <span class="pill-count">{{ $labTests->count() }}</span>
                                </span>
                                @foreach($testCats as $cat => $tests)
                                @if($cat)
                                <span class="cat-pill" data-cat="{{ $cat }}"
                                      onclick="filterByCat('{{ $cat }}', this)">
                                    {{ $cat }}
                                    <span class="pill-count">{{ $tests->count() }}</span>
                                </span>
                                @endif
                                @endforeach
                            </div>

                            {{-- Tests by Category (Accordion) --}}
                            <div id="testAccordion">
                                @foreach($testCats as $cat => $tests)
                                <div class="acc-section" data-cat="{{ $cat }}">
                                    <div class="acc-header open" onclick="toggleAcc(this)">
                                        <i class="fas fa-layer-group" style="color:#0891b2;font-size:0.8rem;flex-shrink:0;"></i>
                                        <span class="acc-title">{{ $cat ?: 'General' }}</span>
                                        <span class="acc-count" id="acc-count-{{ Str::slug($cat) }}">
                                            {{ $tests->count() }}
                                        </span>
                                        <span class="acc-count selected-count" id="acc-sel-{{ Str::slug($cat) }}"
                                              style="display:none;">0 ✓</span>
                                        <i class="fas fa-chevron-down acc-arrow"></i>
                                    </div>
                                    <div class="acc-body open" id="acc-body-{{ Str::slug($cat) }}">
                                        <div class="test-list">
                                            @foreach($tests as $test)
                                            <div class="test-row"
                                                 data-value="test_{{ $test->id }}"
                                                 data-price="{{ $test->price ?? 0 }}"
                                                 data-name="{{ $test->test_name }}"
                                                 data-cat="{{ $cat }}"
                                                 data-search="{{ strtolower($test->test_name . ' ' . $cat . ' ' . $test->description) }}">
                                                <div class="tr-check">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="tr-info">
                                                    <div class="tr-name">{{ $test->test_name }}</div>
                                                    <div class="tr-meta">
                                                        @if($cat)
                                                        <span class="tr-badge cat">{{ $cat }}</span>
                                                        @endif
                                                        @if($test->duration_hours)
                                                        <span class="tr-badge dur">
                                                            <i class="fas fa-clock me-1"></i>{{ $test->duration_hours }}h
                                                        </span>
                                                        @endif
                                                        @if($test->requirements)
                                                        <span class="tr-badge req" title="{{ $test->requirements }}">
                                                            <i class="fas fa-info-circle me-1"></i>{{ Str::limit($test->requirements, 45) }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="tr-price">Rs.&nbsp;{{ number_format($test->price ?? 0, 2) }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                {{-- No search results --}}
                                <div class="no-results" id="noTestResults" style="display:none;">
                                    <i class="fas fa-search"></i>
                                    No tests match your search. Try a different keyword or category.
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- ── PACKAGES TAB ── --}}
                        @if($labPackages->count() > 0)
                        <div class="tab-panel {{ $labTests->count() === 0 ? 'active' : '' }}" id="tab-packages">

                            <div class="picker-toolbar">
                                <div class="picker-search-wrap">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="pkgSearch"
                                           class="picker-search"
                                           placeholder="Search packages..."
                                           oninput="filterPackages()">
                                </div>
                            </div>

                            <div class="pkg-grid" id="pkgGrid">
                                @foreach($labPackages as $pkg)
                                @php
                                    $finalPrice = $pkg->discount_percentage
                                        ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                                        : $pkg->price;
                                @endphp
                                <div class="pkg-card"
                                     data-value="package_{{ $pkg->id }}"
                                     data-price="{{ $finalPrice }}"
                                     data-name="{{ $pkg->package_name }}"
                                     data-search="{{ strtolower($pkg->package_name . ' ' . $pkg->description) }}">
                                    <div class="pkg-card-top">
                                        <div class="pkg-check">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pkg-name">{{ $pkg->package_name }}</div>
                                    </div>
                                    @if($pkg->tests->count() > 0)
                                    <div class="pkg-tests">
                                        <i class="fas fa-list me-1" style="color:#0891b2;"></i>
                                        <strong>{{ $pkg->tests->count() }} tests:</strong>
                                        @foreach($pkg->tests->take(4) as $pt)
                                            {{ $pt->test_name }}@if(!$loop->last), @endif
                                        @endforeach
                                        @if($pkg->tests->count() > 4)
                                            <span style="color:#0891b2;font-weight:700;">
                                                +{{ $pkg->tests->count() - 4 }} more
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="pkg-footer">
                                        <div>
                                            @if($pkg->discount_percentage)
                                            <span class="pkg-orig">Rs.&nbsp;{{ number_format($pkg->price, 2) }}</span>
                                            <span class="pkg-discount">{{ $pkg->discount_percentage }}% OFF</span>
                                            @endif
                                        </div>
                                        <div class="pkg-price">Rs.&nbsp;{{ number_format($finalPrice, 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="no-results" id="noPkgResults" style="display:none;">
                                <i class="fas fa-box-open"></i>
                                No packages match your search.
                            </div>
                        </div>
                        @endif

                    </div>
                    @endif
                    {{-- END SMART PICKER --}}

                    {{-- ── Collection Type ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-truck"></i> Collection Type
                        </div>
                        <div class="col-type-grid">
                            <div class="col-type-card active" data-col="walk_in" id="card-walk_in">
                                <div class="col-type-icon"><i class="fas fa-walking"></i></div>
                                <div>
                                    <div class="col-type-title">Walk-In</div>
                                    <div class="col-type-sub">Visit the laboratory in person</div>
                                </div>
                            </div>
                            <div class="col-type-card" data-col="home" id="card-home">
                                <div class="col-type-icon"><i class="fas fa-home"></i></div>
                                <div>
                                    <div class="col-type-title">Home Collection</div>
                                    <div class="col-type-sub">Technician visits your home</div>
                                </div>
                            </div>
                        </div>
                        <div id="homeAddressSection">
                            <label class="loc-label" style="color:#0369a1;">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Collection Address <span style="color:#dc2626;">*</span>
                            </label>
                            <textarea name="collection_address" id="collectionAddress"
                                      class="loc-textarea" rows="2"
                                      placeholder="House No., Street, City, Postal Code...">{{ old('collection_address') }}</textarea>
                        </div>
                    </div>

                    {{-- ── Date & Time ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-calendar-alt"></i> Preferred Date &amp; Time
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="loc-label">Collection Date <span style="color:#dc2626;">*</span></label>
                                <input type="date" name="collection_date" class="loc-input"
                                       value="{{ old('collection_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="loc-label">
                                    Preferred Time
                                    <span style="color:#888;font-weight:400;">(optional)</span>
                                </label>
                                <input type="time" name="collection_time" class="loc-input"
                                       value="{{ old('collection_time') }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── Prescription ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-file-medical"></i> Prescription
                            <span style="font-weight:400;font-size:0.82rem;color:#aaa;">(optional)</span>
                        </div>
                        @if($referralNote)
                        <div class="loc-alert info" style="margin-bottom:1rem;">
                            <i class="fas fa-notes-medical" style="flex-shrink:0;"></i>
                            <span>Doctor referral note: <strong>{{ $referralNote }}</strong></span>
                        </div>
                        @endif
                        <label class="loc-label">Upload Prescription / Doctor's Note</label>
                        <input type="file" name="prescription_file" class="loc-input"
                               accept=".pdf,.jpg,.jpeg,.png"
                               style="padding:0.5rem 1rem;cursor:pointer;">
                        <div style="font-size:0.75rem;color:#aaa;margin-top:0.4rem;">
                            <i class="fas fa-paperclip me-1"></i>
                            Accepted: PDF, JPG, PNG &middot; Max: 5MB
                        </div>
                    </div>

                    {{-- ── Notes ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-sticky-note"></i> Additional Notes
                            <span style="font-weight:400;font-size:0.82rem;color:#aaa;">(optional)</span>
                        </div>
                        <textarea name="notes" class="loc-textarea"
                                  placeholder="Special instructions, medical conditions, fasting status..."
                                  rows="3">{{ old('notes') }}</textarea>
                    </div>

                </div>
                {{-- END LEFT --}}

                {{-- ═══════════════ RIGHT: SUMMARY ═══════════════ --}}
                <div class="col-lg-4">
                    <div class="order-summary">
                        <div class="summary-header">
                            <i class="fas fa-receipt"></i> Order Summary
                            <span id="summaryCount"
                                  style="margin-left:auto;background:rgba(255,255,255,0.25);
                                         padding:0.1rem 0.55rem;border-radius:10px;
                                         font-size:0.78rem;">0 items</span>
                        </div>

                        <div class="summary-body" id="summaryBody">
                            <div class="summary-empty" id="summaryEmpty">
                                <i class="fas fa-vial"></i>
                                Select tests or packages to see summary
                            </div>
                        </div>

                        <div class="summary-total" id="summaryTotal" style="display:none;">
                            <span style="font-weight:700;color:#0c4a6e;font-size:0.95rem;">
                                <i class="fas fa-coins me-1" style="color:#0891b2;"></i> Total
                            </span>
                            <span style="font-size:1.4rem;font-weight:800;color:#0891b2;"
                                  id="totalDisplay">Rs. 0.00</span>
                        </div>

                        <div style="padding:1.2rem 1.4rem;border-top:1px solid #e0f2fe;">
                            <div style="font-size:0.75rem;color:#888;margin-bottom:1rem;line-height:1.6;">
                                <i class="fas fa-info-circle me-1" style="color:#0891b2;"></i>
                                Payment can be made online after order submission.
                            </div>
                            <button type="submit" class="loc-submit-btn" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane"></i> Submit Order
                            </button>
                            <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                                      margin-top:0.8rem;padding:0.7rem;border-radius:10px;
                                      background:#f4f6f9;color:#666;text-decoration:none;
                                      font-size:0.85rem;font-weight:600;transition:all 0.3s;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ═══════════════════════════════════════════
       STATE
    ═══════════════════════════════════════════ */
    const selected = new Map(); // Map<value, {name, price, type}>
    const form     = document.getElementById('orderForm');

    /* ═══════════════════════════════════════════
       TAB SWITCHING
    ═══════════════════════════════════════════ */
    window.switchTab = function (tabName) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.picker-tab').forEach(t => t.classList.remove('active'));

        const panel = document.getElementById('tab-' + tabName);
        if (panel) panel.classList.add('active');

        const btn = document.querySelector(`.picker-tab[data-tab="${tabName}"]`);
        if (btn) btn.classList.add('active');
    };

    /* ═══════════════════════════════════════════
       ACCORDION TOGGLE
    ═══════════════════════════════════════════ */
    window.toggleAcc = function (header) {
        const isOpen = header.classList.contains('open');
        const body   = header.nextElementSibling;
        header.classList.toggle('open', !isOpen);
        body.classList.toggle('open', !isOpen);
    };

    window.collapseAllAcc = function () {
        document.querySelectorAll('.acc-header').forEach(h => {
            h.classList.remove('open');
            h.nextElementSibling.classList.remove('open');
        });
    };

    /* ═══════════════════════════════════════════
       CATEGORY FILTER
    ═══════════════════════════════════════════ */
    window.filterByCat = function (cat, pillEl) {
        // Update pills
        document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
        pillEl.classList.add('active');

        // Clear search
        const searchEl = document.getElementById('testSearch');
        if (searchEl) searchEl.value = '';

        const sections = document.querySelectorAll('#testAccordion .acc-section');

        if (cat === 'all') {
            sections.forEach(sec => {
                sec.style.display = '';
                // Open all
                const h = sec.querySelector('.acc-header');
                const b = sec.querySelector('.acc-body');
                h.classList.add('open');
                b.classList.add('open');
                // Show all rows
                sec.querySelectorAll('.test-row').forEach(r => r.style.display = '');
            });
        } else {
            sections.forEach(sec => {
                if (sec.dataset.cat === cat) {
                    sec.style.display = '';
                    const h = sec.querySelector('.acc-header');
                    const b = sec.querySelector('.acc-body');
                    h.classList.add('open');
                    b.classList.add('open');
                    sec.querySelectorAll('.test-row').forEach(r => r.style.display = '');
                } else {
                    sec.style.display = 'none';
                }
            });
        }

        document.getElementById('noTestResults').style.display = 'none';
    };

    /* ═══════════════════════════════════════════
       SEARCH FILTER — TESTS
    ═══════════════════════════════════════════ */
    window.filterTests = function () {
        const q = document.getElementById('testSearch').value.trim().toLowerCase();

        // Reset category pills to "All"
        document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
        const allPill = document.querySelector('.cat-pill[data-cat="all"]');
        if (allPill) allPill.classList.add('active');

        let anyVisible = false;
        const sections = document.querySelectorAll('#testAccordion .acc-section');

        sections.forEach(sec => {
            let secVisible = false;
            const rows = sec.querySelectorAll('.test-row');

            rows.forEach(row => {
                const match = !q || row.dataset.search.includes(q) ||
                              row.querySelector('.tr-name').textContent.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                if (match) secVisible = true;
            });

            if (q) {
                // If section has results, open it; else hide
                sec.style.display = secVisible ? '' : 'none';
                const h = sec.querySelector('.acc-header');
                const b = sec.querySelector('.acc-body');
                if (secVisible) {
                    h.classList.add('open');
                    b.classList.add('open');
                    anyVisible = true;
                }
            } else {
                sec.style.display = '';
            }
        });

        document.getElementById('noTestResults').style.display =
            (q && !anyVisible) ? 'block' : 'none';
    };

    /* ═══════════════════════════════════════════
       SEARCH FILTER — PACKAGES
    ═══════════════════════════════════════════ */
    window.filterPackages = function () {
        const q = document.getElementById('pkgSearch').value.trim().toLowerCase();
        let anyVisible = false;

        document.querySelectorAll('.pkg-card').forEach(card => {
            const match = !q || card.dataset.search.includes(q) ||
                          card.querySelector('.pkg-name').textContent.toLowerCase().includes(q);
            card.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });

        const noPkg = document.getElementById('noPkgResults');
        if (noPkg) noPkg.style.display = (q && !anyVisible) ? 'block' : 'none';
    };

    /* ═══════════════════════════════════════════
       ITEM SELECTION (tests + packages)
    ═══════════════════════════════════════════ */
    function attachItemClick (cards, type) {
        cards.forEach(card => {
            card.addEventListener('click', function () {
                const value = this.dataset.value;
                const price = parseFloat(this.dataset.price) || 0;
                const name  = this.dataset.name;

                if (selected.has(value)) {
                    selected.delete(value);
                    this.classList.remove('selected');
                } else {
                    selected.set(value, { name, price, type });
                    this.classList.add('selected');
                }

                updateAccordionBadges();
                renderSummary();
            });
        });
    }

    attachItemClick(document.querySelectorAll('.test-row'),  'test');
    attachItemClick(document.querySelectorAll('.pkg-card'),  'package');

    /* ── Accordion selected count badges ── */
    function updateAccordionBadges () {
        document.querySelectorAll('.acc-section').forEach(sec => {
            const cat     = sec.dataset.cat;
            const slug    = cat ? cat.toLowerCase().replace(/[^a-z0-9]+/g, '-') : '';
            const selEl   = document.getElementById('acc-sel-' + slug);
            if (!selEl) return;

            const selCount = sec.querySelectorAll('.test-row.selected').length;
            if (selCount > 0) {
                selEl.textContent = selCount + ' ✓';
                selEl.style.display = 'inline-flex';
            } else {
                selEl.style.display = 'none';
            }
        });
    }

    /* ═══════════════════════════════════════════
       RENDER SUMMARY
    ═══════════════════════════════════════════ */
    function renderSummary () {
        // Remove old hidden inputs
        form.querySelectorAll('input[name="selected_items[]"]').forEach(el => el.remove());

        const summaryBody   = document.getElementById('summaryBody');
        const summaryEmpty  = document.getElementById('summaryEmpty');
        const summaryTotal  = document.getElementById('summaryTotal');
        const summaryCount  = document.getElementById('summaryCount');
        const totalDisplay  = document.getElementById('totalDisplay');
        const submitBtn     = document.getElementById('submitBtn');
        const banner        = document.getElementById('selectedBanner');
        const bannerText    = document.getElementById('selectedBannerText');

        if (selected.size === 0) {
            summaryBody.innerHTML = '';
            summaryBody.appendChild(summaryEmpty);
            summaryEmpty.style.display = 'block';
            summaryTotal.style.display = 'none';
            summaryCount.textContent   = '0 items';
            submitBtn.disabled         = true;
            banner.classList.remove('show');
            return;
        }

        let total = 0;
        let html  = '';

        selected.forEach((item, value) => {
            total += item.price;
            html  += `<div class="summary-item">
                        <span class="summary-name">${item.name}</span>
                        <span class="summary-price">Rs. ${item.price.toFixed(2)}</span>
                        <button type="button" class="summary-remove"
                                title="Remove" onclick="removeItem('${value}')">
                            <i class="fas fa-times"></i>
                        </button>
                      </div>`;

            const inp  = document.createElement('input');
            inp.type   = 'hidden';
            inp.name   = 'selected_items[]';
            inp.value  = value;
            form.appendChild(inp);
        });

        summaryBody.innerHTML      = html;
        summaryTotal.style.display = 'flex';
        totalDisplay.textContent   = 'Rs. ' + total.toFixed(2);

        const cnt = selected.size;
        summaryCount.textContent   = cnt + ' item' + (cnt > 1 ? 's' : '');
        submitBtn.disabled         = false;

        // Selected banner
        bannerText.textContent = cnt + ' item' + (cnt > 1 ? 's' : '') + ' selected';
        banner.classList.add('show');
    }

    /* ── Remove item from summary / deselect card ── */
    window.removeItem = function (value) {
        selected.delete(value);

        // Deselect the corresponding card (test-row or pkg-card)
        const card = document.querySelector(
            `.test-row[data-value="${value}"],
             .pkg-card[data-value="${value}"]`
        );
        if (card) card.classList.remove('selected');

        updateAccordionBadges();
        renderSummary();
    };

    /* ── Clear all ── */
    window.clearAllSelections = function () {
        selected.clear();
        document.querySelectorAll('.test-row.selected, .pkg-card.selected')
                .forEach(c => c.classList.remove('selected'));
        updateAccordionBadges();
        renderSummary();
    };

    /* ═══════════════════════════════════════════
       COLLECTION TYPE TOGGLE
    ═══════════════════════════════════════════ */
    const colCards          = document.querySelectorAll('.col-type-card');
    const colTypeInput      = document.getElementById('collectionTypeInput');
    const homeSection       = document.getElementById('homeAddressSection');
    const collectionAddress = document.getElementById('collectionAddress');

    const oldColType = '{{ old("collection_type", "walk_in") }}';
    colTypeInput.value = oldColType;

    colCards.forEach(card => {
        if (card.dataset.col === oldColType) card.classList.add('active');
        else card.classList.remove('active');

        card.addEventListener('click', function () {
            const val = this.dataset.col;
            colCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            colTypeInput.value = val;

            if (val === 'home') {
                homeSection.style.display  = 'block';
                collectionAddress.required = true;
            } else {
                homeSection.style.display  = 'none';
                collectionAddress.required = false;
                collectionAddress.value    = '';
            }
        });
    });

    if (oldColType === 'home') {
        homeSection.style.display  = 'block';
        collectionAddress.required = true;
    }

    /* ═══════════════════════════════════════════
       FORM SUBMIT GUARD
    ═══════════════════════════════════════════ */
    form.addEventListener('submit', function (e) {
        if (colTypeInput.value === 'home' && !collectionAddress.value.trim()) {
            e.preventDefault();
            collectionAddress.focus();
            collectionAddress.style.borderColor = '#dc2626';
            collectionAddress.style.boxShadow   = '0 0 0 3px rgba(220,38,38,0.1)';
            if (!document.getElementById('addr-err')) {
                const errDiv = document.createElement('div');
                errDiv.id = 'addr-err';
                errDiv.style.cssText = 'color:#dc2626;font-size:0.82rem;margin-top:0.4rem;font-weight:600;';
                errDiv.textContent = '⚠ Please enter your collection address.';
                homeSection.appendChild(errDiv);
            }
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled   = true;
        submitBtn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.style.opacity = '0.85';
    });

    collectionAddress.addEventListener('input', function () {
        this.style.borderColor = '#e9ecef';
        this.style.boxShadow   = 'none';
        const err = document.getElementById('addr-err');
        if (err) err.remove();
    });

});
</script>
