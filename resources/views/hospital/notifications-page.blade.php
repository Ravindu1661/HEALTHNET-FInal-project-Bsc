{{-- resources/views/hospital/notifications-page.blade.php --}}
@extends('hospital.layouts.master')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@push('styles')
<style>
/* ══════════════════════════════════════════
   BASE
══════════════════════════════════════════ */
.np { animation: npFade .3s ease; }
@keyframes npFade {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ══════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════ */
.np-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.3rem; }
.np-stat {
    background:#fff; border-radius:12px; border:1px solid #f0f4f8;
    box-shadow:0 2px 8px rgba(44,62,80,.05);
    padding:.85rem 1.1rem; display:flex; align-items:center; gap:.75rem;
    flex:1; min-width:130px; cursor:pointer;
    transition:transform .2s, box-shadow .2s; position:relative; overflow:hidden;
}
.np-stat:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(44,62,80,.09); }
.np-stat::before {
    content:''; position:absolute; top:0; left:0; right:0;
    height:3px; border-radius:12px 12px 0 0;
}
.s-total::before  { background:linear-gradient(90deg,#2969bf,#5b9bd5); }
.s-unread::before { background:linear-gradient(90deg,#e74c3c,#f1948a); }
.s-read::before   { background:linear-gradient(90deg,#27ae60,#6fcf97); }
.s-today::before  { background:linear-gradient(90deg,#f39c12,#f7c04a); }

.np-stat-icon {
    width:38px; height:38px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:.9rem; flex-shrink:0;
}
.s-total  .np-stat-icon { background:#e8f0fe; color:#2969bf; }
.s-unread .np-stat-icon { background:#fdecea; color:#e74c3c; }
.s-read   .np-stat-icon { background:#e9f7ee; color:#27ae60; }
.s-today  .np-stat-icon { background:#fef8e7; color:#f39c12; }
.np-stat-info h5 { font-size:1.2rem; font-weight:800; margin:0; color:#2c3e50; }
.np-stat-info p  { font-size:.68rem; color:#888; margin:0; }

/* ══════════════════════════════════════════
   TOOLBAR
══════════════════════════════════════════ */
.np-toolbar {
    background:#fff; border-radius:14px; border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05);
    padding:.85rem 1.3rem; margin-bottom:1.3rem;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.75rem;
}
.np-toolbar-left { display:flex; align-items:center; gap:.65rem; flex-wrap:wrap; }

/* Filter pills */
.np-pills { display:flex; gap:.3rem; flex-wrap:wrap; }
.np-pill {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.3rem .8rem; border-radius:99px;
    font-size:.75rem; font-weight:600;
    border:1.5px solid #e5ecf0; background:#fff;
    cursor:pointer; transition:all .2s; color:#555; white-space:nowrap;
    font-family:inherit;
}
.np-pill:hover { background:#f0f4f8; }
.np-pill.active {
    background:#2969bf; border-color:#2969bf;
    color:#fff; box-shadow:0 3px 10px rgba(41,105,191,.2);
}
.np-pill .pc {
    background:rgba(255,255,255,.3);
    padding:.04rem .36rem; border-radius:99px;
    font-size:.64rem; font-weight:800; min-width:17px; text-align:center;
}
.np-pill:not(.active) .pc { background:#f0f4f8; color:#888; }

/* Buttons */
.nb { padding:.42rem 1rem; border-radius:9px; font-size:.8rem; font-weight:600;
      border:none; cursor:pointer; transition:all .2s;
      display:inline-flex; align-items:center; gap:.4rem;
      white-space:nowrap; font-family:inherit; }
.nb.outline   { background:#fff; color:#2969bf; border:1.5px solid #2969bf; }
.nb.outline:hover   { background:#e8f0fe; }
.nb.secondary { background:#f0f4f8; color:#555; }
.nb.secondary:hover { background:#e2e8f0; }
.nb.danger    { background:#fdecea; color:#e74c3c; border:1.5px solid #f1948a; }
.nb.danger:hover    { background:#e74c3c; color:#fff; }
.nb:disabled  { opacity:.6; cursor:not-allowed; }

/* ══════════════════════════════════════════
   SEARCH
══════════════════════════════════════════ */
.np-search-wrap { position:relative; margin-bottom:1.3rem; }
.np-search {
    width:100%; border:1.5px solid #e5ecf0; border-radius:12px;
    padding:.62rem 2.6rem .62rem 2.6rem;
    font-size:.84rem; color:#2c3e50; outline:none;
    background:#fff; font-family:inherit;
    box-shadow:0 2px 8px rgba(44,62,80,.04);
    transition:border-color .2s, box-shadow .2s;
}
.np-search:focus { border-color:#2969bf; box-shadow:0 0 0 3px rgba(41,105,191,.1); }
.np-s-icon  { position:absolute; left:.9rem;  top:50%; transform:translateY(-50%); color:#aab4be; font-size:.84rem; pointer-events:none; }
.np-s-clear { position:absolute; right:.9rem; top:50%; transform:translateY(-50%);
              background:none; border:none; cursor:pointer; color:#aab4be;
              font-size:.8rem; padding:0; display:none; transition:color .2s; }
.np-s-clear:hover { color:#e74c3c; }

/* ══════════════════════════════════════════
   CARD
══════════════════════════════════════════ */
.np-card {
    background:#fff; border-radius:14px; border:1px solid #f0f4f8;
    box-shadow:0 2px 12px rgba(44,62,80,.05); overflow:hidden;
}
.np-card-head {
    padding:.9rem 1.3rem; border-bottom:1px solid #f0f4f8;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.5rem;
}
.np-card-head h6 {
    font-size:.9rem; font-weight:700; color:#2c3e50; margin:0;
    display:flex; align-items:center; gap:.5rem;
}
.np-card-head h6 i { color:#2969bf; }

/* ══════════════════════════════════════════
   BULK BAR
══════════════════════════════════════════ */
.bulk-bar {
    display:none; align-items:center; gap:.75rem;
    padding:.7rem 1.3rem;
    background:#e8f0fe; border-bottom:1px solid #c9dcf7;
    flex-wrap:wrap;
}
.bulk-bar.show { display:flex; }
.bulk-txt { font-size:.82rem; font-weight:600; color:#2969bf; }
.np-cb { width:16px; height:16px; border-radius:4px; cursor:pointer;
         accent-color:#2969bf; flex-shrink:0; }

/* ══════════════════════════════════════════
   DATE GROUP HEADER
══════════════════════════════════════════ */
.dg-head {
    padding:.55rem 1.3rem;
    background:#f8fbff; border-bottom:1px solid #f0f4f8;
    font-size:.71rem; font-weight:700; color:#8899aa;
    text-transform:uppercase; letter-spacing:.07em;
    display:flex; align-items:center; gap:.5rem;
}
.dg-head::after { content:''; flex:1; height:1px; background:#edf2f7; }

/* ══════════════════════════════════════════
   NOTIFICATION ITEM
══════════════════════════════════════════ */
.ni {
    display:flex; align-items:flex-start; gap:1rem;
    padding:1rem 1.3rem; border-bottom:1px solid #f5f7fa;
    transition:background .15s; cursor:pointer; position:relative;
}
.ni:last-child { border-bottom:none; }
.ni:hover { background:#fafcff; }
.ni.unread {
    background:linear-gradient(90deg,#f0f6ff 0%,#fff 100%);
    border-left:3px solid #2969bf;
}
.ni.unread:hover { background:#eaf2ff; }

/* Dot */
.ni-dot-u {
    width:8px; height:8px; border-radius:50%;
    background:#2969bf; flex-shrink:0; margin-top:.45rem;
    box-shadow:0 0 0 2px rgba(41,105,191,.2);
    animation:dotPulse 2s infinite;
}
@keyframes dotPulse {
    0%,100%{ box-shadow:0 0 0 2px rgba(41,105,191,.2); }
    50%    { box-shadow:0 0 0 5px rgba(41,105,191,.07); }
}
.ni-dot-r { width:8px; height:8px; border-radius:50%; flex-shrink:0; margin-top:.45rem; }

/* Icon */
.ni-icon {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:.95rem; flex-shrink:0;
}
.ic-appointment { background:#eaf7fb; color:#17a2b8; }
.ic-doctor      { background:#f0ebff; color:#8e44ad; }
.ic-review      { background:#fff8e1; color:#e67e22; }
.ic-system      { background:#f8f9fa; color:#495057; }
.ic-success     { background:#e9f7ee; color:#27ae60; }
.ic-warning     { background:#fef8e7; color:#f39c12; }
.ic-danger      { background:#fdecea; color:#e74c3c; }
.ic-laborder    { background:#e8f8f5; color:#1abc9c; }
.ic-info        { background:#e8f0fe; color:#2969bf; }
.ic-default     { background:#f0f4f8; color:#6c757d; }

/* Body */
.ni-body { flex:1; min-width:0; }
.ni-top  { display:flex; align-items:flex-start; justify-content:space-between; gap:.5rem; margin-bottom:.2rem; flex-wrap:wrap; }
.ni-title { font-size:.86rem; font-weight:700; color:#2c3e50; line-height:1.3; }
.ni.unread .ni-title { color:#1a3a6b; }
.ni-time { font-size:.68rem; color:#aab4be; display:flex; align-items:center; gap:.25rem; white-space:nowrap; flex-shrink:0; }
.ni-msg {
    font-size:.8rem; color:#666; line-height:1.55; margin:0;
    display:-webkit-box; -webkit-line-clamp:2;
    -webkit-box-orient:vertical; overflow:hidden;
}
.ni.unread .ni-msg { color:#444; }

/* Badge */
.ni-badge {
    font-size:.61rem; font-weight:700; padding:.13rem .48rem;
    border-radius:6px; text-transform:uppercase; letter-spacing:.04em;
    display:inline-flex; align-items:center; gap:.25rem; margin-top:.35rem;
}
.bd-appointment { background:#eaf7fb; color:#0a7383; }
.bd-doctor      { background:#f0ebff; color:#6c3483; }
.bd-review      { background:#fff8e1; color:#7d5a00; }
.bd-system      { background:#f0f4f8; color:#495057; }
.bd-success     { background:#e9f7ee; color:#155724; }
.bd-warning     { background:#fef8e7; color:#856404; }
.bd-danger      { background:#fdecea; color:#842029; }
.bd-laborder    { background:#e8f8f5; color:#0e7a6a; }
.bd-info        { background:#e8f0fe; color:#084298; }
.bd-default     { background:#f0f4f8; color:#555; }

/* Hover actions */
.ni-actions { display:flex; gap:.3rem; align-items:center; flex-shrink:0; opacity:0; transition:opacity .2s; }
.ni:hover .ni-actions { opacity:1; }
.na {
    width:28px; height:28px; border-radius:7px; border:none;
    cursor:pointer; font-size:.72rem; background:transparent;
    display:inline-flex; align-items:center; justify-content:center;
    transition:all .2s;
}
.na.rd { color:#2969bf; }
.na.rd:hover { background:#e8f0fe; }
.na.dl { color:#e74c3c; }
.na.dl:hover { background:#fdecea; }

/* ══════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════ */
.np-empty { text-align:center; padding:4rem 1rem; }
.np-empty-ic {
    width:72px; height:72px; border-radius:20px;
    background:linear-gradient(135deg,#f0f6ff,#e8f0fb);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 1rem; font-size:1.8rem; color:#c8d8f0;
}
.np-empty h6 { color:#888; font-size:.93rem; margin:0 0 .35rem; }
.np-empty p  { color:#aab4be; font-size:.8rem; margin:0; }

/* ══════════════════════════════════════════
   SKELETON
══════════════════════════════════════════ */
@keyframes shimmer {
    0%  { background-position:-600px 0; }
    100%{ background-position: 600px 0; }
}
.sk {
    border-radius:6px;
    background:linear-gradient(90deg,#f0f4f8 25%,#e4eaf0 50%,#f0f4f8 75%);
    background-size:1200px 100%; animation:shimmer 1.4s infinite linear;
}

/* ══════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════ */
.np-pagi {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.3rem; border-top:1px solid #f0f4f8;
    flex-wrap:wrap; gap:.5rem;
}
.pagi-info { font-size:.78rem; color:#888; }
.pagi-btns { display:flex; gap:.3rem; }
.pb {
    min-width:32px; height:32px; border-radius:7px;
    border:1.5px solid #e5ecf0; background:#fff;
    font-size:.78rem; font-weight:600; color:#555;
    cursor:pointer; display:inline-flex; align-items:center;
    justify-content:center; transition:all .2s; padding:0 .5rem;
    font-family:inherit;
}
.pb:hover    { background:#e8f0fe; border-color:#2969bf; color:#2969bf; }
.pb.active   { background:#2969bf; border-color:#2969bf; color:#fff; }
.pb:disabled { opacity:.45; cursor:not-allowed; }

/* ══════════════════════════════════════════
   CONFIRM MODAL
══════════════════════════════════════════ */
.np-modal {
    position:fixed; inset:0; background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px); z-index:2000;
    display:flex; align-items:center; justify-content:center; padding:1rem;
    opacity:0; visibility:hidden; transition:opacity .25s, visibility .25s;
}
.np-modal.open { opacity:1; visibility:visible; }
.np-modal-box {
    background:#fff; border-radius:16px; width:100%; max-width:380px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    transform:translateY(-20px) scale(.97); transition:transform .25s; overflow:hidden;
}
.np-modal.open .np-modal-box { transform:translateY(0) scale(1); }
.np-modal-head {
    padding:1.1rem 1.4rem; border-bottom:1px solid #f0f4f8;
    display:flex; align-items:center; justify-content:space-between;
}
.np-modal-head h5 { font-size:.95rem; font-weight:700; margin:0; color:#2c3e50;
                    display:flex; align-items:center; gap:.5rem; }
.np-modal-close { background:none; border:none; cursor:pointer; width:32px; height:32px;
                  border-radius:8px; display:flex; align-items:center; justify-content:center;
                  color:#888; font-size:.9rem; transition:background .2s, color .2s; }
.np-modal-close:hover { background:#f0f4f8; color:#e74c3c; }
.np-modal-body { padding:1.2rem 1.4rem; }
.np-modal-body p { font-size:.84rem; color:#555; margin:0; }
.np-modal-foot { padding:.9rem 1.4rem; border-top:1px solid #f0f4f8;
                 display:flex; justify-content:flex-end; gap:.6rem; }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width:767.98px) {
    .np-toolbar  { flex-direction:column; align-items:flex-start; }
    .ni          { padding:.85rem 1rem; gap:.65rem; }
    .ni-actions  { opacity:1; }
    .np-stat     { min-width:calc(50% - .4rem); }
}
@media (max-width:575.98px) {
    .np-stat   { min-width:100%; }
    .np-pill   { padding:.26rem .6rem; font-size:.7rem; }
}
</style>
@endpush

@section('content')
<div class="np">

    {{-- ══ FLASH ══ --}}
    @if(session('success'))
    <div style="background:#d1e7dd;color:#0f5132;border:1px solid #a3cfbb;border-radius:10px;
                padding:.8rem 1.1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.6rem;
                font-size:.83rem;font-weight:500;cursor:pointer;" onclick="this.remove()">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ══ STAT CARDS ══ --}}
    <div class="np-stats">
        <div class="np-stat s-total" onclick="setFilter('all')" title="All notifications">
            <div class="np-stat-icon"><i class="fas fa-bell"></i></div>
            <div class="np-stat-info">
                <h5 id="s-total">—</h5>
                <p>Total</p>
            </div>
        </div>
        <div class="np-stat s-unread" onclick="setFilter('unread')" title="Unread notifications">
            <div class="np-stat-icon"><i class="fas fa-envelope"></i></div>
            <div class="np-stat-info">
                <h5 id="s-unread">—</h5>
                <p>Unread</p>
            </div>
        </div>
        <div class="np-stat s-read" onclick="setFilter('read')" title="Read notifications">
            <div class="np-stat-icon"><i class="fas fa-envelope-open"></i></div>
            <div class="np-stat-info">
                <h5 id="s-read">—</h5>
                <p>Read</p>
            </div>
        </div>
        <div class="np-stat s-today" title="Today's notifications">
            <div class="np-stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="np-stat-info">
                <h5 id="s-today">—</h5>
                <p>Today</p>
            </div>
        </div>
    </div>

    {{-- ══ TOOLBAR ══ --}}
    <div class="np-toolbar">
        <div class="np-toolbar-left">
            <span style="font-size:.9rem;font-weight:700;color:#2c3e50;
                         display:flex;align-items:center;gap:.45rem;">
                <i class="fas fa-bell" style="color:#2969bf;"></i>
                Notifications
            </span>
            <div class="np-pills" id="npPills">
                <button class="np-pill active" data-f="all">
                    <i class="fas fa-th-large" style="font-size:.6rem;"></i>
                    All <span class="pc" id="pc-all">0</span>
                </button>
                <button class="np-pill" data-f="unread">
                    <i class="fas fa-circle" style="font-size:.5rem;"></i>
                    Unread <span class="pc" id="pc-unread">0</span>
                </button>
                <button class="np-pill" data-f="appointment">
                    <i class="fas fa-calendar-check" style="font-size:.6rem;"></i>
                    Appointments <span class="pc" id="pc-appointment">0</span>
                </button>
                <button class="np-pill" data-f="doctor">
                    <i class="fas fa-user-md" style="font-size:.6rem;"></i>
                    Doctors <span class="pc" id="pc-doctor">0</span>
                </button>
                <button class="np-pill" data-f="review">
                    <i class="fas fa-star" style="font-size:.6rem;"></i>
                    Reviews <span class="pc" id="pc-review">0</span>
                </button>
                <button class="np-pill" data-f="system">
                    <i class="fas fa-cog" style="font-size:.6rem;"></i>
                    System <span class="pc" id="pc-system">0</span>
                </button>
            </div>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <button class="nb outline" id="markAllBtn" onclick="markAllRead()">
                <i class="fas fa-check-double"></i>
                <span class="d-none d-sm-inline">Mark All Read</span>
            </button>
            <button class="nb secondary" onclick="reload()" title="Refresh">
                <i class="fas fa-sync-alt" id="refIco"></i>
            </button>
        </div>
    </div>

    {{-- ══ SEARCH ══ --}}
    <div class="np-search-wrap">
        <i class="fas fa-search np-s-icon"></i>
        <input type="text" id="npSearch" class="np-search"
               placeholder="Search notifications..."
               oninput="onSearch(this)">
        <button class="np-s-clear" id="npSearchClear" onclick="clearSearch()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ══ MAIN CARD ══ --}}
    <div class="np-card">
        <div class="np-card-head">
            <h6>
                <i class="fas fa-bell"></i>
                <span id="listTitle">All Notifications</span>
                <span id="listBadge"
                      style="background:#2969bf;color:#fff;border-radius:99px;
                             font-size:.63rem;font-weight:800;
                             padding:.1rem .45rem;line-height:1.5;">0</span>
            </h6>
            <button class="nb secondary"
                    style="font-size:.74rem;padding:.3rem .75rem;"
                    id="selectBtn" onclick="toggleSelect()">
                <i class="fas fa-check-square me-1"></i>Select
            </button>
        </div>

        {{-- Bulk action bar --}}
        <div class="bulk-bar" id="bulkBar">
            <input type="checkbox" class="np-cb" id="selectAllCb"
                   onchange="toggleSelectAll(this)">
            <span class="bulk-txt" id="bulkTxt">0 selected</span>
            <div style="display:flex;gap:.4rem;margin-left:auto;flex-wrap:wrap;">
                <button class="nb outline"
                        style="font-size:.74rem;padding:.3rem .8rem;"
                        onclick="bulkMarkRead()">
                    <i class="fas fa-check me-1"></i>Mark Read
                </button>
                <button class="nb danger"
                        style="font-size:.74rem;padding:.3rem .8rem;"
                        onclick="bulkDelete()">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
                <button class="nb secondary"
                        style="font-size:.74rem;padding:.3rem .8rem;"
                        onclick="cancelSelect()">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
            </div>
        </div>

        {{-- List --}}
        <div id="npList">
            @for($i=0;$i<6;$i++)
            <div style="display:flex;gap:1rem;padding:1rem 1.3rem;
                        border-bottom:1px solid #f5f7fa;align-items:flex-start;">
                <div class="sk" style="width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:.5rem;"></div>
                <div class="sk" style="width:42px;height:42px;border-radius:12px;flex-shrink:0;"></div>
                <div style="flex:1;">
                    <div class="sk" style="width:50%;height:13px;margin-bottom:.4rem;"></div>
                    <div class="sk" style="width:82%;height:12px;margin-bottom:.35rem;"></div>
                    <div class="sk" style="width:32%;height:10px;"></div>
                </div>
                <div class="sk" style="width:55px;height:10px;flex-shrink:0;margin-top:.25rem;"></div>
            </div>
            @endfor
        </div>

        {{-- Pagination --}}
        <div class="np-pagi" id="npPagi">
            <span class="pagi-info" id="pagiInfo">Loading...</span>
            <div class="pagi-btns" id="pagiBtns"></div>
        </div>
    </div>
</div>

{{-- ══ CONFIRM MODAL ══ --}}
<div class="np-modal" id="npModal">
    <div class="np-modal-box">
        <div class="np-modal-head">
            <h5>
                <i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i>
                <span id="modalTitle">Confirm</span>
            </h5>
            <button class="np-modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="np-modal-body">
            <p id="modalMsg">Are you sure?</p>
        </div>
        <div class="np-modal-foot">
            <button class="nb secondary" onclick="closeModal()">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
            <button class="nb danger" onclick="runAction()">
                <i class="fas fa-check me-1"></i>
                <span id="modalBtn">Confirm</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════
// CONSTANTS — controller routes ට exact match
// ════════════════════════════════════════════════
const _DATA     = '{{ route("hospital.notifications.data") }}';
const _BASE_URL = '{{ url("hospital/notifications") }}';
const _MARK_ALL = '{{ route("hospital.notifications.mark-all-read") }}';
const _CSRF     = document.querySelector('meta[name="csrf-token"]').content;

// ════════════════════════════════════════════════
// STATE
// ════════════════════════════════════════════════
let _filter   = 'all';
let _page     = 1;
let _items    = [];
let _sTimer   = null;
let _selMode  = false;
let _selected = new Set();
let _pending  = null;

// ════════════════════════════════════════════════
// BOOT
// ════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    // Pill onclick
    document.querySelectorAll('.np-pill').forEach(p => {
        p.addEventListener('click', () => setFilter(p.dataset.f));
    });

    fetch(_DATA + '?page=1&per_page=1', {
        headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
        credentials:'same-origin'
    }).then(r=>r.json()).then(()=>{ loadData(1); }).catch(()=>{ loadData(1); });

    document.getElementById('npModal')
        .addEventListener('click', e => { if(e.target.id==='npModal') closeModal(); });
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });
});

// ════════════════════════════════════════════════
// LOAD DATA  — controller notificationsData() ට match
// notificationsData() returns:
//   { notifications: {data:[], from, to, total, last_page,...}, unread_count: N }
// filter 'unread' → is_read=0
// filter 'read'   → is_read=1
// filter other    → type=xxx
// ════════════════════════════════════════════════
function loadData(page = 1) {
    _page = page;

    const q = new URLSearchParams({ page, per_page: 15 });

    if (_filter === 'unread') {
        q.set('is_read', '0');
    } else if (_filter === 'read') {
        q.set('is_read', '1');
    } else if (_filter !== 'all') {
        q.set('type', _filter);
    }

    const s = document.getElementById('npSearch').value.trim();
    if (s) q.set('search', s);

    const ico = document.getElementById('refIco');
    if (ico) ico.style.animation = 'spin 1s linear infinite';

    fetch(_DATA + '?' + q, {
        headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':_CSRF },
        credentials:'same-origin',
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        if (ico) ico.style.animation = '';

        // Controller returns: res.notifications (paginated) + res.unread_count
        const paged      = res.notifications ?? {};
        const unreadCnt  = res.unread_count ?? 0;
        _items = paged.data ?? [];

        renderStats(paged.total ?? 0, unreadCnt);
        renderCounts(_items);
        renderList(_items);
        renderPagi(paged);

        // Navbar badge update (if function exists in master layout)
        if (typeof updateNavBadge === 'function') updateNavBadge(unreadCnt);
    })
    .catch(err => {
        if (ico) ico.style.animation = '';
        console.error('Notification load error:', err);
        showErr();
    });
}

function reload() { loadData(_page); }

// ════════════════════════════════════════════════
// RENDER STATS
// ════════════════════════════════════════════════
function renderStats(total, unread) {
    set('s-total',  total);
    set('s-unread', unread);
    set('s-read',   Math.max(0, total - unread));

    const today = new Date().toDateString();
    const todayN = _items.filter(n => new Date(n.created_at).toDateString() === today).length;
    set('s-today', todayN);
}

function renderCounts(items) {
    const c = { all:0, unread:0, appointment:0, doctor:0, review:0, system:0 };
    items.forEach(n => {
        c.all++;
        if (!n.is_read) c.unread++;
        if (c[n.type] !== undefined) c[n.type]++;
    });
    Object.keys(c).forEach(k => {
        const el = document.getElementById('pc-' + k);
        if (el) el.textContent = c[k];
    });
    set('listBadge', c.all);
}

// ════════════════════════════════════════════════
// RENDER LIST
// ════════════════════════════════════════════════
function renderList(items) {
    const el = document.getElementById('npList');
    if (!items || !items.length) {
        el.innerHTML = `
            <div class="np-empty">
                <div class="np-empty-ic"><i class="fas fa-bell-slash"></i></div>
                <h6>No notifications found</h6>
                <p>${_filter !== 'all'
                    ? 'No ' + _filter + ' notifications yet.'
                    : "You're all caught up!"}</p>
            </div>`;
        return;
    }

    const groups = groupDate(items);
    let html = '';
    groups.forEach(({ label, list }) => {
        html += `<div class="dg-head">
                    <i class="far fa-calendar-alt"></i>${label}
                 </div>`;
        list.forEach(n => { html += buildItem(n); });
    });
    el.innerHTML = html;

    // Click: mark read or select
    el.querySelectorAll('.ni').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('.na') || e.target.closest('.np-cb')) return;
            const id = this.dataset.id;
            if (_selMode) {
                toggleItemSelect(id, this);
            } else if (this.classList.contains('unread')) {
                doMarkRead(id, this);
            }
        });
    });
}

function buildItem(n) {
    const unread  = !n.is_read;
    const ic      = getIcon(n.type);
    const bd      = getBadge(n.type);
    const timeStr = timeAgo(n.created_at);

    const cb = _selMode
        ? `<input type="checkbox" class="np-cb item-cb" data-id="${n.id}"
                  onchange="onCb(this)" ${_selected.has(String(n.id)) ? 'checked' : ''}>`
        : '';

    const rdBtn = unread
        ? `<button class="na rd" title="Mark as read"
                   onclick="doMarkRead(${n.id},this.closest('.ni'));event.stopPropagation();">
               <i class="fas fa-check"></i>
           </button>` : '';

    return `
    <div class="ni ${unread ? 'unread' : ''}" id="ni-${n.id}" data-id="${n.id}">
        ${cb}
        <span class="${unread ? 'ni-dot-u' : 'ni-dot-r'}"></span>
        <div class="ni-icon ${ic.cls}"><i class="fas ${ic.icon}"></i></div>
        <div class="ni-body">
            <div class="ni-top">
                <span class="ni-title">${esc(n.title ?? 'Notification')}</span>
                <span class="ni-time"><i class="far fa-clock"></i>${timeStr}</span>
            </div>
            <p class="ni-msg">${esc(n.message ?? '')}</p>
            <span class="ni-badge ${bd.cls}">
                <i class="fas ${bd.icon}"></i>${bd.label}
            </span>
        </div>
        <div class="ni-actions">
            ${rdBtn}
            <button class="na dl" title="Delete"
                    onclick="askDelete(${n.id});event.stopPropagation();">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>`;
}

function showErr() {
    document.getElementById('npList').innerHTML = `
        <div class="np-empty">
            <div class="np-empty-ic"><i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i></div>
            <h6>Failed to load</h6>
            <p>Could not fetch notifications. <a href="javascript:reload()" style="color:#2969bf;">Try again</a></p>
        </div>`;
}

// ════════════════════════════════════════════════
// MARK READ (single)
// POST /hospital/notifications/{id}/read
// ════════════════════════════════════════════════
function doMarkRead(id, el) {
    fetch(`${_BASE_URL}/${id}/read`, {
        method:'POST',
        headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
        credentials:'same-origin',
    })
    .then(r => r.json())
    .then(d => {
        if (d.success && el) {
            el.classList.remove('unread');
            const dot = el.querySelector('.ni-dot-u');
            if (dot) dot.className = 'ni-dot-r';
            el.querySelector('.na.rd')?.remove();
            loadData(_page); // refresh counts
        }
    })
    .catch(() => toast('Failed to mark as read.', 'error'));
}

// ════════════════════════════════════════════════
// MARK ALL READ
// POST /hospital/notifications/mark-all-read
// ════════════════════════════════════════════════
function markAllRead() {
    const btn = document.getElementById('markAllBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(_MARK_ALL, {
        method:'POST',
        headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
        credentials:'same-origin',
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-double"></i><span class="d-none d-sm-inline"> Mark All Read</span>';
        if (d.success) { toast('All marked as read.', 'success'); loadData(1); }
        else toast(d.message ?? 'Failed.', 'error');
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-double"></i><span class="d-none d-sm-inline"> Mark All Read</span>';
        toast('Request failed.', 'error');
    });
}

// ════════════════════════════════════════════════
// DELETE (single)
// DELETE /hospital/notifications/{id}
// ════════════════════════════════════════════════
function askDelete(id) {
    _pending = () => {
        fetch(`${_BASE_URL}/${id}`, {
            method:'DELETE',
            headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
            credentials:'same-origin',
        })
        .then(r => r.json())
        .then(d => {
            closeModal();
            if (d.success) {
                const row = document.getElementById('ni-' + id);
                if (row) {
                    row.style.transition = 'opacity .3s,transform .3s';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => loadData(_page), 300);
                } else {
                    loadData(_page);
                }
                toast('Notification deleted.', 'success');
            } else {
                toast(d.message ?? 'Delete failed.', 'error');
            }
        })
        .catch(() => { closeModal(); toast('Delete failed.', 'error'); });
    };
    openModal('Delete Notification', 'Delete this notification permanently?', 'Delete');
}

// ════════════════════════════════════════════════
// FILTER
// ════════════════════════════════════════════════
function setFilter(f) {
    _filter = f;
    document.querySelectorAll('.np-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.f === f);
    });
    const titles = {
        all:'All Notifications', unread:'Unread', read:'Read',
        appointment:'Appointments', doctor:'Doctors',
        review:'Reviews', system:'System',
    };
    set('listTitle', titles[f] ?? 'Notifications');
    loadData(1);
}

// ════════════════════════════════════════════════
// SEARCH
// ════════════════════════════════════════════════
function onSearch(inp) {
    document.getElementById('npSearchClear').style.display = inp.value ? '' : 'none';
    clearTimeout(_sTimer);
    _sTimer = setTimeout(() => loadData(1), 450);
}
function clearSearch() {
    document.getElementById('npSearch').value = '';
    document.getElementById('npSearchClear').style.display = 'none';
    loadData(1);
}

// ════════════════════════════════════════════════
// SELECT MODE
// ════════════════════════════════════════════════
function toggleSelect() {
    _selMode = !_selMode;
    _selected.clear();
    if (_selMode) {
        document.getElementById('bulkBar').classList.add('show');
        document.getElementById('selectBtn').innerHTML = '<i class="fas fa-times me-1"></i>Cancel';
        document.getElementById('selectBtn').onclick = cancelSelect;
    } else {
        cancelSelect(); return;
    }
    renderList(_items);
}
function cancelSelect() {
    _selMode = false; _selected.clear();
    document.getElementById('bulkBar').classList.remove('show');
    const b = document.getElementById('selectBtn');
    b.innerHTML = '<i class="fas fa-check-square me-1"></i>Select';
    b.onclick = toggleSelect;
    set('bulkTxt', '0 selected');
    renderList(_items);
}
function onCb(cb) {
    if (cb.checked) _selected.add(cb.dataset.id);
    else _selected.delete(cb.dataset.id);
    set('bulkTxt', _selected.size + ' selected');
}
function toggleSelectAll(cb) {
    document.querySelectorAll('.item-cb').forEach(c => {
        c.checked = cb.checked;
        cb.checked ? _selected.add(c.dataset.id) : _selected.delete(c.dataset.id);
    });
    set('bulkTxt', _selected.size + ' selected');
}
function toggleItemSelect(id, el) {
    const cb = el.querySelector('.item-cb');
    if (!cb) return;
    cb.checked = !cb.checked;
    cb.checked ? _selected.add(String(id)) : _selected.delete(String(id));
    set('bulkTxt', _selected.size + ' selected');
}
function bulkMarkRead() {
    if (!_selected.size) { toast('Select at least one.', 'warning'); return; }
    const ids = [..._selected];
    Promise.all(ids.map(id =>
        fetch(`${_BASE_URL}/${id}/read`, {
            method:'POST',
            headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
            credentials:'same-origin',
        })
    ))
    .then(() => { toast(ids.length + ' marked as read.', 'success'); cancelSelect(); loadData(_page); })
    .catch(() => toast('Some requests failed.', 'error'));
}
function bulkDelete() {
    if (!_selected.size) { toast('Select at least one.', 'warning'); return; }
    const ids = [..._selected];
    _pending = () => {
        Promise.all(ids.map(id =>
            fetch(`${_BASE_URL}/${id}`, {
                method:'DELETE',
                headers:{ 'Accept':'application/json','X-CSRF-TOKEN':_CSRF },
                credentials:'same-origin',
            })
        ))
        .then(() => { closeModal(); toast(ids.length + ' deleted.', 'success'); cancelSelect(); loadData(_page); })
        .catch(() => { closeModal(); toast('Some deletes failed.', 'error'); });
    };
    openModal('Delete Selected', `Delete ${ids.length} notification(s)?`, 'Delete All');
}

// ════════════════════════════════════════════════
// PAGINATION
// ════════════════════════════════════════════════
function renderPagi(d) {
    const last  = d.last_page ?? 1;
    const from  = d.from  ?? 0;
    const to    = d.to    ?? 0;
    const total = d.total ?? 0;

    set('pagiInfo', total ? `Showing ${from}–${to} of ${total}` : 'No results');

    let h = '';
    h += `<button class="pb" onclick="loadData(${_page-1})" ${_page<=1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;

    let s = Math.max(1, _page-2), e = Math.min(last, s+4);
    if (e-s < 4) s = Math.max(1, e-4);
    if (s > 1)  { h += `<button class="pb" onclick="loadData(1)">1</button>`; if(s>2) h += `<button class="pb" disabled>…</button>`; }
    for (let p=s; p<=e; p++) h += `<button class="pb ${p===_page?'active':''}" onclick="loadData(${p})">${p}</button>`;
    if (e < last) { if(e<last-1) h += `<button class="pb" disabled>…</button>`; h += `<button class="pb" onclick="loadData(${last})">${last}</button>`; }
    h += `<button class="pb" onclick="loadData(${_page+1})" ${_page>=last?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;

    document.getElementById('pagiBtns').innerHTML = h;
}

// ════════════════════════════════════════════════
// MODAL
// ════════════════════════════════════════════════
function openModal(title, msg, btn) {
    set('modalTitle', title); set('modalMsg', msg); set('modalBtn', btn);
    document.getElementById('npModal').classList.add('open');
}
function closeModal() { document.getElementById('npModal').classList.remove('open'); }
function runAction()  { if (_pending) { _pending(); _pending = null; } }

// ════════════════════════════════════════════════
// HELPERS
// ════════════════════════════════════════════════
function groupDate(items) {
    const groups = {};
    const today  = new Date(); today.setHours(0,0,0,0);
    const yest   = new Date(today); yest.setDate(yest.getDate()-1);
    const week   = new Date(today); week.setDate(week.getDate()-7);
    items.forEach(n => {
        const d = new Date(n.created_at); d.setHours(0,0,0,0);
        let label;
        if      (d.getTime()===today.getTime()) label='Today';
        else if (d.getTime()===yest.getTime())  label='Yesterday';
        else if (d>=week)                       label='This Week';
        else label = d.toLocaleDateString('en-US',{day:'numeric',month:'long',year:'numeric'});
        if (!groups[label]) groups[label]=[];
        groups[label].push(n);
    });
    return Object.entries(groups).map(([label,list])=>({label,list}));
}

function getIcon(t) {
    return ({
        appointment:{ cls:'ic-appointment', icon:'fa-calendar-check' },
        doctor:     { cls:'ic-doctor',      icon:'fa-user-md' },
        review:     { cls:'ic-review',      icon:'fa-star' },
        success:    { cls:'ic-success',     icon:'fa-check-circle' },
        warning:    { cls:'ic-warning',     icon:'fa-exclamation-triangle' },
        danger:     { cls:'ic-danger',      icon:'fa-times-circle' },
        system:     { cls:'ic-system',      icon:'fa-cog' },
        laborder:   { cls:'ic-laborder',    icon:'fa-flask' },
        info:       { cls:'ic-info',        icon:'fa-info-circle' },
    })[t] ?? { cls:'ic-default', icon:'fa-bell' };
}

function getBadge(t) {
    return ({
        appointment:{ cls:'bd-appointment', icon:'fa-calendar-check', label:'Appointment' },
        doctor:     { cls:'bd-doctor',      icon:'fa-user-md',        label:'Doctor' },
        review:     { cls:'bd-review',      icon:'fa-star',           label:'Review' },
        success:    { cls:'bd-success',     icon:'fa-check-circle',   label:'Success' },
        warning:    { cls:'bd-warning',     icon:'fa-exclamation',    label:'Warning' },
        danger:     { cls:'bd-danger',      icon:'fa-times-circle',   label:'Alert' },
        system:     { cls:'bd-system',      icon:'fa-cog',            label:'System' },
        laborder:   { cls:'bd-laborder',    icon:'fa-flask',          label:'Lab Order' },
        info:       { cls:'bd-info',        icon:'fa-info-circle',    label:'Info' },
    })[t] ?? { cls:'bd-default', icon:'fa-bell', label: t ? t.charAt(0).toUpperCase()+t.slice(1) : 'General' };
}

function timeAgo(d) {
    if (!d) return '—';
    const s = Math.floor((Date.now()-new Date(d).getTime())/1000);
    if (s<60)    return 'Just now';
    if (s<3600)  return Math.floor(s/60)+'m ago';
    if (s<86400) return Math.floor(s/3600)+'h ago';
    if (s<604800)return Math.floor(s/86400)+'d ago';
    return new Date(d).toLocaleDateString('en-US',{day:'numeric',month:'short'});
}

function set(id,v){ const e=document.getElementById(id); if(e) e.textContent=v; }

function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

// ════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════
function toast(msg, type='success') {
    const ex=document.getElementById('_np_toast'); if(ex) ex.remove();
    const c={
        success:{ bg:'#d1e7dd',color:'#0f5132',icon:'fa-check-circle' },
        error:  { bg:'#f8d7da',color:'#842029',icon:'fa-exclamation-circle' },
        warning:{ bg:'#fff3cd',color:'#664d03',icon:'fa-exclamation-triangle' },
        info:   { bg:'#cfe2ff',color:'#084298',icon:'fa-info-circle' },
    }[type] ?? { bg:'#cfe2ff',color:'#084298',icon:'fa-info-circle' };
    const t=document.createElement('div');
    t.id='_np_toast';
    t.style.cssText=`position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${c.bg};color:${c.color};border:1px solid ${c.color}44;
        border-radius:12px;padding:.8rem 1.2rem;
        display:flex;align-items:center;gap:.6rem;
        font-size:.83rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.12);
        max-width:320px;animation:npSlide .3s ease;`;
    t.innerHTML=`<i class="fas ${c.icon}"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(()=>t.remove(),3500);
}

// Inject keyframes
const _ks=document.createElement('style');
_ks.textContent=`
    @keyframes spin   {to{transform:rotate(360deg)}}
    @keyframes npSlide{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
`;
document.head.appendChild(_ks);
</script>
@endpush
