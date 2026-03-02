{{-- resources/views/hospital/reviews.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Reviews & Ratings')
@section('page-title', 'Reviews & Ratings')

@push('styles')
<style>
/* ══════════════════════════════════════════
   PAGE
══════════════════════════════════════════ */
.rev-page { animation: fadeIn .3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }

/* ══════════════════════════════════════════
   RATING OVERVIEW CARD
══════════════════════════════════════════ */
.rating-overview {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 14px rgba(44,62,80,.06);
    padding: 1.5rem 1.8rem;
    margin-bottom: 1.3rem;
    display: flex;
    align-items: center;
    gap: 2.5rem;
    flex-wrap: wrap;
}

/* Big score */
.big-score {
    text-align: center;
    flex-shrink: 0;
}
.big-score-num {
    font-size: 4rem;
    font-weight: 900;
    color: #2c3e50;
    line-height: 1;
    margin-bottom: .3rem;
}
.big-score-stars { display: flex; gap: 3px; justify-content: center; margin-bottom: .3rem; }
.big-score-stars i { font-size: 1.2rem; color: #f39c12; }
.big-score-stars i.empty { color: #e0e0e0; }
.big-score-total { font-size: .78rem; color: #888; }

/* Divider */
.overview-divider {
    width: 1px;
    background: #f0f4f8;
    align-self: stretch;
    min-height: 100px;
    flex-shrink: 0;
}

/* Bar chart */
.rating-bars { flex: 1; min-width: 200px; }
.rating-bar-row {
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: .45rem;
}
.rating-bar-row:last-child { margin-bottom: 0; }
.bar-label {
    font-size: .72rem;
    font-weight: 600;
    color: #555;
    white-space: nowrap;
    min-width: 32px;
    display: flex;
    align-items: center;
    gap: 3px;
}
.bar-label i { font-size: .62rem; color: #f39c12; }
.bar-track {
    flex: 1;
    height: 8px;
    background: #f0f4f8;
    border-radius: 99px;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #f39c12, #f7c04a);
    transition: width .6s cubic-bezier(.4,0,.2,1);
    width: 0;
}
.bar-count {
    font-size: .7rem;
    font-weight: 600;
    color: #888;
    min-width: 24px;
    text-align: right;
}

/* Stat pills */
.overview-stats {
    display: flex;
    flex-direction: column;
    gap: .7rem;
    flex-shrink: 0;
}
.ov-stat {
    display: flex;
    align-items: center;
    gap: .65rem;
    background: #f8fbff;
    border: 1px solid #f0f4f8;
    border-radius: 10px;
    padding: .6rem .9rem;
    min-width: 150px;
}
.ov-stat-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.ov-stat-body h5 { font-size: 1rem; font-weight: 800; margin: 0; color: #2c3e50; }
.ov-stat-body p  { font-size: .68rem; color: #888; margin: 0; }

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
.filter-control { flex: 1; min-width: 160px; display: flex; flex-direction: column; gap: .3rem; }
.filter-control label {
    font-size: .72rem; font-weight: 600;
    color: #555; text-transform: uppercase; letter-spacing: .05em;
}
.filter-control select,
.filter-control input {
    border: 1.5px solid #e5ecf0; border-radius: 9px;
    padding: .5rem .75rem; font-size: .83rem;
    color: #2c3e50; outline: none; background: #fafcff;
    font-family: inherit; width: 100%;
    transition: border-color .2s, box-shadow .2s;
}
.filter-control select:focus,
.filter-control input:focus {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41,105,191,.1);
}
.btn-filter {
    padding: .5rem 1.1rem; border-radius: 9px;
    font-size: .82rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    white-space: nowrap; display: inline-flex; align-items: center; gap: .4rem;
}
.btn-filter.primary { background: #2969bf; color: #fff; }
.btn-filter.primary:hover { background: #1a4f9a; box-shadow: 0 4px 12px rgba(41,105,191,.3); }
.btn-filter.reset { background: #f0f4f8; color: #555; }
.btn-filter.reset:hover { background: #e2e8f0; }

/* Star filter pills */
.star-filters { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .5rem; }
.star-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .3rem .75rem; border-radius: 99px;
    font-size: .75rem; font-weight: 600;
    border: 1.5px solid #e5ecf0; background: #fff;
    cursor: pointer; transition: all .2s; white-space: nowrap;
    color: #555;
}
.star-pill:hover, .star-pill.active {
    background: #fef8e7; border-color: #f39c12; color: #856404;
}
.star-pill.active { box-shadow: 0 2px 8px rgba(243,156,18,.2); }
.star-pill i { color: #f39c12; font-size: .68rem; }
.star-pill.all-pill:hover, .star-pill.all-pill.active {
    background: #e8f0fe; border-color: #2969bf; color: #2969bf;
}

/* ══════════════════════════════════════════
   REVIEW CARDS
══════════════════════════════════════════ */
.section-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f4f8;
    box-shadow: 0 2px 12px rgba(44,62,80,.05);
    overflow: hidden;
}
.section-header {
    padding: .9rem 1.3rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.section-header h6 {
    font-size: .93rem; font-weight: 700;
    color: #2c3e50; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.section-header h6 i { color: #f39c12; }

.reviews-list { padding: 0; }

.review-item {
    padding: 1.2rem 1.4rem;
    border-bottom: 1px solid #f5f7fa;
    transition: background .15s;
    display: flex;
    gap: 1rem;
}
.review-item:last-child { border-bottom: none; }
.review-item:hover { background: #fafcff; }

/* Avatar */
.rev-avatar {
    width: 44px; height: 44px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #f0f4f8;
    flex-shrink: 0;
}
.rev-avatar-fallback {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #2969bf, #5b9bd5);
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}

.rev-body { flex: 1; min-width: 0; }

.rev-top {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: .5rem;
    margin-bottom: .35rem; flex-wrap: wrap;
}
.rev-name {
    font-size: .88rem; font-weight: 700;
    color: #2c3e50; line-height: 1.2;
}
.rev-date {
    font-size: .7rem; color: #aab4be;
    display: flex; align-items: center; gap: .3rem;
    white-space: nowrap;
}

.rev-stars { display: inline-flex; gap: 2px; margin-bottom: .45rem; }
.rev-stars i { font-size: .78rem; color: #f39c12; }
.rev-stars i.empty { color: #e0e0e0; }

.rev-comment {
    font-size: .83rem; color: #555;
    line-height: 1.6; margin: 0 0 .5rem;
}
.rev-comment.truncated {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.rev-read-more {
    background: none; border: none; cursor: pointer;
    font-size: .75rem; font-weight: 600;
    color: #2969bf; padding: 0;
    transition: color .2s;
}
.rev-read-more:hover { color: #1a4f9a; }

/* Rating badge on card */
.rating-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .2rem .6rem; border-radius: 99px;
    font-size: .72rem; font-weight: 700;
    flex-shrink: 0;
}
.rating-5 { background: #d1e7dd; color: #0f5132; }
.rating-4 { background: #e9f7ee; color: #27ae60; }
.rating-3 { background: #fff3cd; color: #856404; }
.rating-2 { background: #ffe5d0; color: #c85000; }
.rating-1 { background: #f8d7da; color: #842029; }

/* Type tag */
.rev-type-tag {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .65rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 6px;
    background: #f0f4f8; color: #666;
    text-transform: capitalize;
}

/* ══════════════════════════════════════════
   EMPTY / LOADING
══════════════════════════════════════════ */
.empty-state {
    text-align: center; padding: 3.5rem 1rem;
}
.empty-state i { font-size: 3rem; color: #d0dae8; margin-bottom: 1rem; display: block; }
.empty-state h6 { color: #888; font-size: .95rem; margin: 0 0 .3rem; }
.empty-state p  { color: #aab4be; font-size: .8rem; margin: 0; }

@keyframes shimmer {
    0%{background-position:-600px 0}100%{background-position:600px 0}
}
.skeleton-line {
    height: 13px; border-radius: 6px;
    background: linear-gradient(90deg,#f0f4f8 25%,#e4eaf0 50%,#f0f4f8 75%);
    background-size: 1200px 100%;
    animation: shimmer 1.4s infinite linear;
}

/* Skeleton review item */
.skeleton-review {
    padding: 1.2rem 1.4rem;
    border-bottom: 1px solid #f5f7fa;
    display: flex; gap: 1rem;
}

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
.btn-page:hover { background: #e8f0fe; border-color: #2969bf; color: #2969bf; }
.btn-page.active { background: #2969bf; border-color: #2969bf; color: #fff; }
.btn-page:disabled { opacity: .45; cursor: not-allowed; }

/* ══════════════════════════════════════════
   MODAL
══════════════════════════════════════════ */
.rev-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(3px);
    z-index: 2000;
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
    opacity: 0; visibility: hidden;
    transition: opacity .25s, visibility .25s;
}
.rev-modal-overlay.show { opacity: 1; visibility: visible; }
.rev-modal {
    background: #fff; border-radius: 16px;
    width: 100%; max-width: 520px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    transform: translateY(-20px) scale(.97);
    transition: transform .25s;
    overflow: hidden;
    max-height: 90vh;
    display: flex; flex-direction: column;
}
.rev-modal-overlay.show .rev-modal { transform: translateY(0) scale(1); }
.rev-modal-header {
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid #f0f4f8;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.rev-modal-header h5 {
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
.rev-modal-body { padding: 1.3rem 1.4rem; overflow-y: auto; flex: 1; }
.rev-modal-footer {
    padding: .9rem 1.4rem;
    border-top: 1px solid #f0f4f8;
    display: flex; justify-content: flex-end; gap: .6rem;
    flex-shrink: 0;
}
.btn-modal {
    padding: .5rem 1.2rem; border-radius: 9px;
    font-size: .83rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .2s;
    display: inline-flex; align-items: center; gap: .4rem;
}
.btn-modal.secondary { background: #f0f4f8; color: #555; }
.btn-modal.secondary:hover { background: #e2e8f0; }

/* ══════════════════════════════════════════
   ACTION BTN
══════════════════════════════════════════ */
.btn-icon-action {
    width: 30px; height: 30px; border-radius: 7px;
    border: none; cursor: pointer; font-size: .75rem;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.btn-icon-action.view { background: #f0f4f8; color: #6c757d; }
.btn-icon-action.view:hover { background: #2969bf; color: #fff; }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 767.98px) {
    .rating-overview { flex-direction: column; gap: 1.2rem; padding: 1.2rem; }
    .overview-divider { width: 100%; height: 1px; min-height: unset; }
    .overview-stats { flex-direction: row; flex-wrap: wrap; }
    .ov-stat { min-width: 140px; flex: 1; }
    .filter-control { min-width: 100%; }
    .review-item { flex-direction: column; gap: .75rem; }
    .rev-avatar, .rev-avatar-fallback { width: 38px; height: 38px; }
    .rev-top { flex-direction: column; gap: .25rem; }
}
@media (max-width: 575.98px) {
    .big-score-num { font-size: 3rem; }
}
</style>
@endpush

@section('content')
<div class="rev-page">

    {{-- ══ RATING OVERVIEW ══ --}}
    <div class="rating-overview" id="ratingOverview">
        {{-- Skeleton --}}
        <div class="text-center" style="flex-shrink:0;">
            <div class="skeleton-line" style="width:80px;height:60px;border-radius:8px;margin:0 auto .5rem;"></div>
            <div class="skeleton-line" style="width:100px;height:18px;margin:0 auto .3rem;"></div>
            <div class="skeleton-line" style="width:70px;height:13px;margin:0 auto;"></div>
        </div>
        <div class="overview-divider"></div>
        <div style="flex:1;">
            @for($i=0;$i<5;$i++)
            <div class="rating-bar-row">
                <div class="skeleton-line" style="width:28px;height:12px;"></div>
                <div style="flex:1;height:8px;border-radius:99px;background:#f0f4f8;"></div>
                <div class="skeleton-line" style="width:20px;height:12px;"></div>
            </div>
            @endfor
        </div>
    </div>

    {{-- ══ STAR FILTER PILLS ══ --}}
    <div class="star-filters mb-3" id="starFilters">
        <button class="star-pill all-pill active" onclick="filterByStar('')" data-star="">
            <i class="fas fa-th-large"></i> All Reviews
        </button>
        @foreach([5,4,3,2,1] as $s)
        <button class="star-pill" onclick="filterByStar({{ $s }})" data-star="{{ $s }}">
            <i class="fas fa-star"></i> {{ $s }} Star
        </button>
        @endforeach
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="filter-card">
        <div class="filter-group">
            <div class="filter-control" style="min-width:220px;flex:2;">
                <label><i class="fas fa-search me-1"></i>Search</label>
                <input type="text" id="filterSearch"
                       placeholder="Patient name, comment..."
                       oninput="debounceLoad()">
            </div>
            <div class="filter-control" style="min-width:100px;flex:0;">
                <label><i class="fas fa-list me-1"></i>Show</label>
                <select id="filterPerPage" onchange="loadReviews()">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div style="display:flex;gap:.5rem;align-items:flex-end;">
                <button class="btn-filter primary" onclick="loadReviews()">
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

    {{-- ══ REVIEWS LIST ══ --}}
    <div class="section-card">
        <div class="section-header">
            <h6>
                <i class="fas fa-star"></i>
                Patient Reviews
                <span class="badge bg-warning text-dark rounded-pill ms-1"
                      id="totalBadge" style="font-size:.65rem;">0</span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                <span id="activeFilterLabel"
                      style="font-size:.75rem;color:#888;display:none;
                             background:#fef8e7;border:1px solid #f39c12;
                             color:#856404;padding:.2rem .65rem;border-radius:99px;
                             font-weight:600;">
                </span>
                <button class="btn-icon-action view" onclick="loadReviews()"
                        title="Refresh" style="width:34px;height:34px;border-radius:9px;">
                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                </button>
            </div>
        </div>

        <div class="reviews-list" id="reviewsList">
            {{-- Skeleton --}}
            @for($i=0;$i<5;$i++)
            <div class="skeleton-review">
                <div class="skeleton-line" style="width:44px;height:44px;border-radius:12px;flex-shrink:0;"></div>
                <div style="flex:1;">
                    <div class="skeleton-line" style="width:40%;margin-bottom:.4rem;"></div>
                    <div class="skeleton-line" style="width:20%;height:12px;margin-bottom:.5rem;"></div>
                    <div class="skeleton-line" style="width:90%;margin-bottom:.3rem;"></div>
                    <div class="skeleton-line" style="width:75%;"></div>
                </div>
            </div>
            @endfor
        </div>

        <div class="apt-pagination" id="paginationWrap">
            <span class="pagination-info" id="paginationInfo">Loading...</span>
            <div class="pagination-btns" id="paginationBtns"></div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     REVIEW DETAIL MODAL
══════════════════════════════════════════════ --}}
<div class="rev-modal-overlay" id="viewModal">
    <div class="rev-modal">
        <div class="rev-modal-header">
            <h5>
                <i class="fas fa-star" style="color:#f39c12;"></i>
                Review Details
            </h5>
            <button class="modal-close-btn" onclick="closeModal('viewModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="rev-modal-body" id="viewModalBody">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x" style="color:#f39c12;"></i>
            </div>
        </div>
        <div class="rev-modal-footer">
            <button class="btn-modal secondary" onclick="closeModal('viewModal')">
                <i class="fas fa-times me-1"></i>Close
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
let activeStar    = '';
let summaryData   = null;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    loadReviews();

    document.querySelectorAll('.rev-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape')
            document.querySelectorAll('.rev-modal-overlay.show')
                    .forEach(m => closeModal(m.id));
    });
});

// ════════════════════════════════════════════════
// LOAD REVIEWS
// ════════════════════════════════════════════════
function loadReviews(page = 1) {
    currentPage = page;

    const search  = document.getElementById('filterSearch').value.trim();
    const perPage = document.getElementById('filterPerPage').value;

    const params = new URLSearchParams();
    if (search)    params.set('search',   search);
    if (activeStar) params.set('rating',  activeStar);
    params.set('per_page', perPage);
    params.set('page',     page);

    const icon = document.getElementById('refreshIcon');
    if (icon) icon.style.animation = 'spin 1s linear infinite';

    apiFetch('{{ route("hospital.reviews.data") }}?' + params, function (data) {
        if (icon) icon.style.animation = '';

        const reviews = data.reviews ?? data;
        summaryData   = data.summary ?? null;

        renderOverview(summaryData);
        renderReviews(reviews);
        renderPagination(reviews);
        setText('totalBadge', reviews.total ?? 0);
    });
}

// ════════════════════════════════════════════════
// RENDER OVERVIEW
// ════════════════════════════════════════════════
function renderOverview(s) {
    const el = document.getElementById('ratingOverview');
    if (!s) { el.innerHTML = '<p class="text-muted" style="font-size:.83rem;">No rating data available.</p>'; return; }

    const avg   = parseFloat(s.avg_rating ?? 0);
    const total = parseInt(s.total ?? 0);
    const stars  = [1,2,3,4,5].map(n =>
        `<i class="fas fa-star ${n <= Math.round(avg) ? '' : 'empty'}"></i>`
    ).join('');

    const counts = {
        5: parseInt(s.five_star  ?? 0),
        4: parseInt(s.four_star  ?? 0),
        3: parseInt(s.three_star ?? 0),
        2: parseInt(s.two_star   ?? 0),
        1: parseInt(s.one_star   ?? 0),
    };

    const barRows = [5,4,3,2,1].map(n => {
        const pct = total > 0 ? Math.round((counts[n]/total)*100) : 0;
        return `
        <div class="rating-bar-row">
            <span class="bar-label">${n} <i class="fas fa-star"></i></span>
            <div class="bar-track">
                <div class="bar-fill" data-width="${pct}" style="width:0%;
                    background:${n>=4?'linear-gradient(90deg,#27ae60,#6fcf97)':
                                 n===3?'linear-gradient(90deg,#f39c12,#f7c04a)':
                                       'linear-gradient(90deg,#e74c3c,#f1948a)'};">
                </div>
            </div>
            <span class="bar-count">${counts[n]}</span>
        </div>`;
    }).join('');

    // Sentiment
    const positive = (counts[5] + counts[4]);
    const posPct   = total > 0 ? Math.round((positive/total)*100) : 0;

    el.innerHTML = `
        <div class="big-score">
            <div class="big-score-num">${avg.toFixed(1)}</div>
            <div class="big-score-stars">${stars}</div>
            <div class="big-score-total">${total.toLocaleString()} reviews</div>
        </div>
        <div class="overview-divider"></div>
        <div class="rating-bars">${barRows}</div>
        <div class="overview-divider d-none d-lg-block"></div>
        <div class="overview-stats">
            <div class="ov-stat">
                <div class="ov-stat-icon" style="background:#e9f7ee;color:#27ae60;">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="ov-stat-body">
                    <h5>${posPct}%</h5>
                    <p>Positive (4–5★)</p>
                </div>
            </div>
            <div class="ov-stat">
                <div class="ov-stat-icon" style="background:#e8f0fe;color:#2969bf;">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="ov-stat-body">
                    <h5>${total.toLocaleString()}</h5>
                    <p>Total Reviews</p>
                </div>
            </div>
            <div class="ov-stat">
                <div class="ov-stat-icon" style="background:#fef8e7;color:#f39c12;">
                    <i class="fas fa-star"></i>
                </div>
                <div class="ov-stat-body">
                    <h5>${avg.toFixed(1)}</h5>
                    <p>Avg Rating</p>
                </div>
            </div>
        </div>`;

    // Animate bars after render
    requestAnimationFrame(() => {
        document.querySelectorAll('.bar-fill[data-width]').forEach(el => {
            setTimeout(() => { el.style.width = el.dataset.width + '%'; }, 100);
        });
    });
}

// ════════════════════════════════════════════════
// RENDER REVIEWS
// ════════════════════════════════════════════════
function renderReviews(data) {
    const container = document.getElementById('reviewsList');
    const items     = data.data ?? [];

    if (!items.length) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-star-half-alt"></i>
                <h6>No reviews found</h6>
                <p>Patients haven't left any reviews yet, or adjust your filters.</p>
            </div>`;
        return;
    }

    container.innerHTML = items.map(rev => {
        const rating  = parseInt(rev.rating ?? 0);
        const inits   = initials(rev.patient_name);
        const stars   = [1,2,3,4,5].map(s =>
            `<i class="fas fa-star ${s <= rating ? '' : 'empty'}"></i>`
        ).join('');
        const rClass  = `rating-${Math.min(Math.max(rating,1),5)}`;
        const comment = (rev.comment ?? '').trim();
        const isLong  = comment.length > 180;
        const date    = formatDate(rev.created_at);
        const id      = `rev-comment-${rev.id}`;

        const avatarHtml = rev.patient_image
            ? `<img src="/storage/${rev.patient_image}"
                    class="rev-avatar"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                    alt="${rev.patient_name ?? ''}">
               <div class="rev-avatar-fallback" style="display:none;">${inits}</div>`
            : `<div class="rev-avatar-fallback">${inits}</div>`;

        const typeTag = rev.related_type
            ? `<span class="rev-type-tag">
                   <i class="fas fa-tag" style="font-size:.55rem;"></i>
                   ${capitalize(rev.related_type)}
               </span>` : '';

        return `
        <div class="review-item" id="review-${rev.id}">
            ${avatarHtml}
            <div class="rev-body">
                <div class="rev-top">
                    <div>
                        <div class="rev-name">${rev.patient_name ?? 'Anonymous'}</div>
                        <div style="display:flex;align-items:center;gap:.5rem;margin-top:.15rem;flex-wrap:wrap;">
                            <div class="rev-stars">${stars}</div>
                            <span class="rating-badge ${rClass}">
                                <i class="fas fa-star" style="font-size:.6rem;"></i>${rating}.0
                            </span>
                            ${typeTag}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span class="rev-date">
                            <i class="far fa-clock"></i>${date}
                        </span>
                        <button class="btn-icon-action view"
                                onclick="viewReview(${rev.id})"
                                title="View Full Review">
                            <i class="fas fa-expand-alt"></i>
                        </button>
                    </div>
                </div>
                ${comment
                    ? `<p class="rev-comment ${isLong ? 'truncated' : ''}" id="${id}">
                           ${escapeHtml(comment)}
                       </p>
                       ${isLong
                           ? `<button class="rev-read-more" onclick="toggleComment('${id}', this)">
                                  Read more <i class="fas fa-chevron-down ms-1" style="font-size:.65rem;"></i>
                              </button>`
                           : ''}`
                    : `<p style="font-size:.8rem;color:#aab4be;font-style:italic;margin:0;">
                           No comment provided.
                       </p>`
                }
            </div>
        </div>`;
    }).join('');
}

// ════════════════════════════════════════════════
// VIEW REVIEW MODAL
// ════════════════════════════════════════════════
function viewReview(id) {
    openModal('viewModal');
    const body = document.getElementById('viewModalBody');
    body.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x" style="color:#f39c12;"></i>
        </div>`;

    const perPage = document.getElementById('filterPerPage').value;
    apiFetch(`{{ route("hospital.reviews.data") }}?per_page=${perPage}&page=${currentPage}`, function (data) {
        const reviews = data.reviews ?? data;
        const rev = (reviews.data ?? []).find(r => r.id == id);
        if (!rev) {
            body.innerHTML = '<p class="text-center text-muted py-3">Review not found.</p>';
            return;
        }

        const rating = parseInt(rev.rating ?? 0);
        const inits  = initials(rev.patient_name);
        const stars  = [1,2,3,4,5].map(s =>
            `<i class="fas fa-star ${s <= rating ? '' : 'empty'}" style="font-size:1rem;color:${s<=rating?'#f39c12':'#e0e0e0'};"></i>`
        ).join('');
        const rClass = `rating-${Math.min(Math.max(rating,1),5)}`;

        const avatarHtml = rev.patient_image
            ? `<img src="/storage/${rev.patient_image}"
                    style="width:56px;height:56px;border-radius:14px;object-fit:cover;border:2px solid #f0f4f8;"
                    onerror="this.style.display='none'">`
            : `<div style="width:56px;height:56px;border-radius:14px;
                           background:linear-gradient(135deg,#2969bf,#5b9bd5);
                           display:flex;align-items:center;justify-content:center;
                           font-size:.95rem;font-weight:700;color:#fff;">
                   ${inits}
               </div>`;

        body.innerHTML = `
            <div style="background:linear-gradient(135deg,#fffdf0,#fef8e7);
                        border-radius:14px;padding:1.2rem;margin-bottom:1.2rem;
                        border:1px solid #fde8a0;">
                <div style="display:flex;align-items:center;gap:1rem;">
                    ${avatarHtml}
                    <div style="flex:1;min-width:0;">
                        <h6 style="margin:0 0 .3rem;font-size:.95rem;font-weight:700;color:#2c3e50;">
                            ${rev.patient_name ?? 'Anonymous'}
                        </h6>
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                            <div style="display:flex;gap:2px;">${stars}</div>
                            <span class="rating-badge ${rClass}">
                                <i class="fas fa-star" style="font-size:.6rem;"></i>${rating}.0
                            </span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:.7rem;color:#aab4be;display:flex;align-items:center;gap:.3rem;">
                            <i class="far fa-clock"></i>${formatDate(rev.created_at)}
                        </div>
                        ${rev.related_type
                            ? `<span class="rev-type-tag mt-1">
                                   <i class="fas fa-tag" style="font-size:.55rem;"></i>
                                   ${capitalize(rev.related_type)}
                               </span>` : ''}
                    </div>
                </div>
            </div>
            ${rev.comment
                ? `<div style="background:#fafcff;border-radius:12px;padding:1.1rem;
                               border:1px solid #f0f4f8;">
                       <p style="font-size:.88rem;color:#374151;line-height:1.7;margin:0;">
                           <i class="fas fa-quote-left" style="color:#f39c12;font-size:.8rem;margin-right:.3rem;"></i>
                           ${escapeHtml(rev.comment ?? '')}
                           <i class="fas fa-quote-right" style="color:#f39c12;font-size:.8rem;margin-left:.3rem;"></i>
                       </p>
                   </div>`
                : `<p style="font-size:.83rem;color:#aab4be;font-style:italic;
                             text-align:center;padding:1rem 0;">
                       No written comment provided.
                   </p>`
            }`;
    });
}

// ════════════════════════════════════════════════
// TOGGLE TRUNCATED COMMENT
// ════════════════════════════════════════════════
function toggleComment(id, btn) {
    const p = document.getElementById(id);
    if (!p) return;
    const isExpanded = !p.classList.contains('truncated');
    if (isExpanded) {
        p.classList.add('truncated');
        btn.innerHTML = 'Read more <i class="fas fa-chevron-down ms-1" style="font-size:.65rem;"></i>';
    } else {
        p.classList.remove('truncated');
        btn.innerHTML = 'Show less <i class="fas fa-chevron-up ms-1" style="font-size:.65rem;"></i>';
    }
}

// ════════════════════════════════════════════════
// STAR FILTER
// ════════════════════════════════════════════════
function filterByStar(star) {
    activeStar = star;

    // Update pill active state
    document.querySelectorAll('.star-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.star == star);
    });

    // Active filter label
    const label = document.getElementById('activeFilterLabel');
    if (star) {
        label.style.display = '';
        label.innerHTML = `<i class="fas fa-star me-1" style="font-size:.65rem;"></i>${star} Star Filter`;
    } else {
        label.style.display = 'none';
    }

    loadReviews(1);
}

// ════════════════════════════════════════════════
// FILTERS
// ════════════════════════════════════════════════
function resetFilters() {
    document.getElementById('filterSearch').value  = '';
    document.getElementById('filterPerPage').value = '10';
    activeStar = '';
    document.querySelectorAll('.star-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.star === '');
    });
    document.getElementById('activeFilterLabel').style.display = 'none';
    loadReviews(1);
}

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadReviews(1), 500);
}

// ════════════════════════════════════════════════
// PAGINATION
// ════════════════════════════════════════════════
function renderPagination(data) {
    totalPages   = data.last_page ?? 1;
    const from   = data.from ?? 0;
    const to     = data.to   ?? 0;
    const total  = data.total ?? 0;

    document.getElementById('paginationInfo').textContent =
        total ? `Showing ${from}–${to} of ${total} reviews` : 'No results';

    const btns = document.getElementById('paginationBtns');
    let html = '';

    html += `<button class="btn-page" onclick="loadReviews(${currentPage-1})"
             ${currentPage<=1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;

    let start = Math.max(1, currentPage-2);
    let end   = Math.min(totalPages, start+4);
    if (end-start<4) start = Math.max(1,end-4);

    if (start>1) {
        html += `<button class="btn-page" onclick="loadReviews(1)">1</button>`;
        if (start>2) html += `<button class="btn-page" disabled>…</button>`;
    }
    for (let p=start;p<=end;p++) {
        html += `<button class="btn-page ${p===currentPage?'active':''}"
                 onclick="loadReviews(${p})">${p}</button>`;
    }
    if (end<totalPages) {
        if (end<totalPages-1) html += `<button class="btn-page" disabled>…</button>`;
        html += `<button class="btn-page" onclick="loadReviews(${totalPages})">${totalPages}</button>`;
    }
    html += `<button class="btn-page" onclick="loadReviews(${currentPage+1})"
             ${currentPage>=totalPages?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;

    btns.innerHTML = html;
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
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF,
        },
        credentials: 'same-origin'
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(cb)
    .catch(err => console.error('API Error:', err));
}

// ════════════════════════════════════════════════
// UTILITIES
// ════════════════════════════════════════════════
function initials(name) {
    return (name || 'P').split(' ').map(w => w[0] || '').join('').slice(0,2).toUpperCase();
}
function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }
function setText(id, v) { const e = document.getElementById(id); if(e) e.textContent = v; }
function formatDate(d) {
    if (!d) return '—';
    const date = new Date(d);
    const diff = Date.now() - date.getTime();
    const days = Math.floor(diff / 86400000);
    if (days === 0) return 'Today';
    if (days === 1) return 'Yesterday';
    if (days < 7)  return `${days} days ago`;
    return date.toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' });
}
function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;')
              .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
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
