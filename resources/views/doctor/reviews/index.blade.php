{{-- ══════════════════════════════════════════════════════
     resources/views/doctor/reviews/index.blade.php
══════════════════════════════════════════════════════ --}}
@extends('doctor.layouts.master')

@section('title', 'My Reviews')
@section('page-title', 'My Reviews')

@push('styles')
<style>
/* ══════════════════════════════════════
   REVIEWS INDEX
══════════════════════════════════════ */
.reviews-wrap { max-width: 960px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    border-radius: 16px; padding: 1.3rem 1.5rem;
    color: #fff; margin-bottom: 1.4rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
}
.ph-icon {
    width: 50px; height: 50px; border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .82; margin-top: .18rem; }

/* ── Overview Card ── */
.overview-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem; margin-bottom: 1.3rem;
}
.ov-title {
    font-size: .8rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.ov-title i { color: #f59e0b; }

/* Big Rating */
.big-rating { text-align: center; }
.big-num {
    font-size: 3.2rem; font-weight: 900; line-height: 1;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.big-stars { font-size: 1.1rem; margin: .3rem 0; }
.big-stars .s-fill  { color: #fbbf24; }
.big-stars .s-empty { color: #e2e8f0; }
.big-total { font-size: .75rem; color: #94a3b8; }

/* Breakdown bars */
.bar-row {
    display: flex; align-items: center; gap: .55rem;
    font-size: .72rem; color: #374151; margin-bottom: .35rem;
}
.bar-label { width: 14px; text-align: right; font-weight: 700; flex-shrink: 0; }
.bar-track {
    flex: 1; height: 8px; border-radius: 4px;
    background: #f0f3f8; overflow: hidden;
}
.bar-fill {
    height: 100%; border-radius: 4px;
    background: linear-gradient(90deg, #f59e0b, #ef4444);
    transition: width .6s ease;
}
.bar-count { width: 24px; text-align: left; color: #94a3b8; flex-shrink: 0; }

/* Stat mini */
.stat-mini {
    background: #fff; border-radius: 14px;
    padding: .85rem 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; align-items: center; gap: .7rem; height: 100%;
}
.smi-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.smi-num { font-size: 1.15rem; font-weight: 800; line-height: 1; }
.smi-lbl {
    font-size: .65rem; color: #94a3b8; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em; margin-top: .12rem;
}

/* ── Filter Bar ── */
.filter-bar {
    background: #fff; border-radius: 14px;
    padding: .8rem 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    display: flex; gap: .45rem; flex-wrap: wrap;
    align-items: center; margin-bottom: 1.1rem;
}
.tab-pill {
    padding: .25rem .72rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600;
    border: 1.5px solid #e2e8f0; background: #fff;
    cursor: pointer; transition: all .15s; color: #64748b;
    text-decoration: none; display: inline-flex;
    align-items: center; gap: .22rem; white-space: nowrap;
}
.tab-pill:hover  { border-color: #f59e0b; color: #d97706; }
.tab-pill.active { background: #f59e0b; border-color: #f59e0b; color: #fff; }

/* ── Review Card ── */
.review-card {
    background: #fff; border-radius: 14px;
    border: 1.5px solid #f0f3f8;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    margin-bottom: .65rem; padding: 1.1rem 1.2rem;
    transition: all .2s;
}
.review-card:hover {
    box-shadow: 0 5px 18px rgba(0,0,0,.09);
    transform: translateY(-1px);
}

/* Rating stars inline */
.star-row { display: flex; gap: .15rem; }
.star-row i { font-size: .82rem; }
.star-row .s-fill  { color: #fbbf24; }
.star-row .s-empty { color: #e2e8f0; }

/* Star badge */
.star-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .2rem .55rem; border-radius: 20px;
    font-size: .72rem; font-weight: 700;
}
.star-5 { background:#fef9c3; color:#92400e; }
.star-4 { background:#fef3c7; color:#b45309; }
.star-3 { background:#fff7ed; color:#c2410c; }
.star-2 { background:#fee2e2; color:#dc2626; }
.star-1 { background:#fecaca; color:#b91c1c; }

/* Patient avatar */
.pat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 800; color: #fff; flex-shrink: 0;
}

/* Reply box */
.reply-toggle {
    font-size: .72rem; font-weight: 600; color: #0d6efd;
    cursor: pointer; border: none; background: none;
    padding: 0; margin-top: .5rem;
    display: inline-flex; align-items: center; gap: .25rem;
}
.reply-toggle:hover { color: #0a3fa8; }
.reply-box {
    background: #f8f9fb; border: 1.5px solid #e2e8f0;
    border-radius: 10px; padding: .75rem;
    margin-top: .65rem; display: none;
}
.reply-box textarea {
    width: 100%; border: 1.5px solid #e2e8f0;
    border-radius: 8px; font-size: .78rem;
    padding: .55rem .7rem; resize: vertical; min-height: 80px;
    outline: none; transition: border-color .2s;
    font-family: inherit;
}
.reply-box textarea:focus { border-color: #0d6efd; }

/* Review text */
.review-text {
    font-size: .8rem; color: #374151; line-height: 1.65;
    margin: .55rem 0 0; background: #f8f9fb;
    border-radius: 10px; padding: .75rem .9rem;
}
.review-text.empty {
    color: #94a3b8; font-style: italic;
}

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 3.5rem 1rem;
}
.es-icon {
    width: 68px; height: 68px; border-radius: 50%;
    background: #fff7ed;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; color: #f59e0b;
    margin: 0 auto .8rem;
}
.empty-state h6 { font-size: .9rem; font-weight: 700; margin-bottom: .3rem; }
.empty-state p  { font-size: .78rem; color: #94a3b8; margin: 0; }

/* ── Pagination ── */
.pagination .page-link {
    border-radius: 8px !important; margin: 0 2px;
    font-size: .75rem; color: #f59e0b; border-color: #e2e8f0;
}
.pagination .page-item.active .page-link {
    background: #f59e0b; border-color: #f59e0b; color: #fff;
}
</style>
@endpush

@section('content')
<div class="reviews-wrap">

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon"><i class="fas fa-star"></i></div>
        <div>
            <div class="ph-title">My Reviews &amp; Ratings</div>
            <div class="ph-sub">
                Patient feedback for Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
            </div>
        </div>
    </div>

    {{-- ══ Overview ══ --}}
    <div class="overview-card">
        <div class="ov-title">
            <i class="fas fa-chart-bar"></i>Rating Overview
        </div>
        <div class="row g-3 align-items-center">

            {{-- Big Rating --}}
            <div class="col-sm-3 col-6">
                <div class="big-rating">
                    <div class="big-num">{{ number_format($avgRating, 1) }}</div>
                    <div class="big-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($avgRating) ? 's-fill' : 's-empty' }}"></i>
                        @endfor
                    </div>
                    <div class="big-total">
                        {{ $totalReviews }} review{{ $totalReviews !== 1 ? 's' : '' }}
                    </div>
                </div>
            </div>

            {{-- Breakdown bars --}}
            <div class="col-sm-5 col-6">
                @for($star = 5; $star >= 1; $star--)
                @php
                    $cnt  = $breakdown->get($star, 0);
                    $pct  = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                @endphp
                <div class="bar-row">
                    <span class="bar-label">{{ $star }}</span>
                    <i class="fas fa-star" style="color:#fbbf24;font-size:.65rem;flex-shrink:0"></i>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:{{ $pct }}%"></div>
                    </div>
                    <span class="bar-count">{{ $cnt }}</span>
                </div>
                @endfor
            </div>

            {{-- Mini stats --}}
            <div class="col-sm-4 col-12">
                <div class="row g-2">
                    @foreach([
                        ['Average',  number_format($avgRating,1).' ★', '#f59e0b','fa-star','linear-gradient(135deg,#f59e0b22,#f59e0b55)'],
                        ['5 Stars',  $breakdown->get(5,0),             '#22c55e','fa-smile','linear-gradient(135deg,#22c55e22,#22c55e55)'],
                        ['1-2 Stars',$breakdown->get(1,0)+$breakdown->get(2,0),'#ef4444','fa-frown','linear-gradient(135deg,#ef444422,#ef444455)'],
                    ] as [$lbl,$val,$clr,$ico,$bg])
                    <div class="col-4">
                        <div class="stat-mini">
                            <div class="smi-icon" style="background:{{ $bg }}">
                                <i class="fas {{ $ico }}" style="color:{{ $clr }}"></i>
                            </div>
                            <div>
                                <div class="smi-num" style="color:{{ $clr }}">{{ $val }}</div>
                                <div class="smi-lbl">{{ $lbl }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ══ Filter Bar ══ --}}
    <div class="filter-bar">
        <span style="font-size:.72rem;font-weight:700;color:#374151">
            Filter by stars:
        </span>
        <a href="{{ route('doctor.reviews.index', request()->except('rating')) }}"
           class="tab-pill {{ !request('rating') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i> All
        </a>
        @for($s = 5; $s >= 1; $s--)
        <a href="{{ route('doctor.reviews.index', array_merge(request()->query(), ['rating' => $s])) }}"
           class="tab-pill {{ request('rating') == $s ? 'active' : '' }}">
            {{ $s }} <i class="fas fa-star" style="font-size:.6rem"></i>
            @if($breakdown->get($s,0) > 0)
            <span style="background:rgba(0,0,0,.08);border-radius:10px;
                         padding:.02rem .3rem;font-size:.6rem">
                {{ $breakdown->get($s,0) }}
            </span>
            @endif
        </a>
        @endfor
        <div class="ms-auto" style="font-size:.7rem;color:#94a3b8">
            {{ $reviews->total() }} review{{ $reviews->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    {{-- ══ Review Cards ══ --}}
    @forelse($reviews as $review)
    <div class="review-card" id="review-{{ $review->id }}">

        <div class="d-flex align-items-flex-start gap-2 flex-wrap">

            {{-- Patient Avatar --}}
            <div class="pat-avatar">
                {{ strtoupper(substr($review->patient_name, 0, 1)) }}
            </div>

            {{-- Header info --}}
            <div class="flex-grow-1" style="min-width:0">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span style="font-size:.82rem;font-weight:700;color:#1a1a1a">
                        {{ $review->patient_name }}
                    </span>
                    <span class="star-badge star-{{ $review->rating }}">
                        <i class="fas fa-star"></i>
                        {{ $review->rating }}.0
                    </span>
                </div>

                {{-- Stars --}}
                <div class="star-row mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? 's-fill' : 's-empty' }}"></i>
                    @endfor
                </div>
            </div>

            {{-- Date + View --}}
            <div class="text-end flex-shrink-0">
                <div style="font-size:.7rem;color:#94a3b8">
                    <i class="fas fa-calendar me-1"></i>{{ $review->date }}
                </div>
                <a href="{{ route('doctor.reviews.show', $review->id) }}"
                   class="btn btn-outline-secondary btn-sm mt-1"
                   style="font-size:.65rem;padding:.2rem .55rem">
                    <i class="fas fa-eye me-1"></i>View
                </a>
            </div>

        </div>

        {{-- Review Text --}}
        @if($review->review)
        <div class="review-text">
            <i class="fas fa-quote-left me-1"
               style="color:#e2e8f0;font-size:.85rem"></i>
            {{ $review->review }}
        </div>
        @else
        <div class="review-text empty">
            <i class="fas fa-comment-slash me-1"></i>No written review.
        </div>
        @endif

        {{-- Reply toggle --}}
        <button class="reply-toggle"
                onclick="toggleReply({{ $review->id }})">
            <i class="fas fa-reply"></i>
            Reply to patient
        </button>

        {{-- Reply box (hidden by default) --}}
        <div class="reply-box" id="replyBox-{{ $review->id }}">
            <div style="font-size:.72rem;font-weight:700;color:#374151;margin-bottom:.45rem">
                <i class="fas fa-reply me-1 text-primary"></i>
                Your reply will be sent as a notification to the patient.
            </div>
            <textarea id="replyText-{{ $review->id }}"
                      placeholder="Write your reply…"
                      maxlength="1000"></textarea>
            <div style="font-size:.65rem;color:#94a3b8;text-align:right;margin-top:.2rem"
                 id="replyCount-{{ $review->id }}">0 / 1000</div>
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                <div id="replyResult-{{ $review->id }}"
                     style="font-size:.72rem;font-weight:600"></div>
                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            style="font-size:.7rem"
                            onclick="toggleReply({{ $review->id }})">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button"
                            class="btn btn-primary btn-sm"
                            style="font-size:.7rem"
                            id="replyBtn-{{ $review->id }}"
                            onclick="sendReply({{ $review->id }})">
                        <i class="fas fa-paper-plane me-1"></i>Send Reply
                    </button>
                </div>
            </div>
        </div>

    </div>
    @empty

    <div class="empty-state">
        <div class="es-icon"><i class="fas fa-star-half-alt"></i></div>
        <h6>No reviews yet</h6>
        <p>
            @if(request('rating'))
                No {{ request('rating') }}-star reviews found.
                <a href="{{ route('doctor.reviews.index') }}">View all</a>
            @else
                Patient reviews will appear here once you start receiving them.
            @endif
        </p>
    </div>

    @endforelse

    {{-- ══ Pagination ══ --}}
    @if($reviews->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $reviews->links() }}
    </div>
    @endif

</div>{{-- /.reviews-wrap --}}
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Toggle Reply Box ──────────────────────────────
function toggleReply(id) {
    const box = document.getElementById('replyBox-' + id);
    if (!box) return;
    const open = box.style.display === 'block';
    box.style.display = open ? 'none' : 'block';
    if (!open) {
        document.getElementById('replyText-' + id)?.focus();
    }
}

// ── Char counter for reply ────────────────────────
document.addEventListener('input', function (e) {
    if (!e.target.id || !e.target.id.startsWith('replyText-')) return;
    const id    = e.target.id.replace('replyText-', '');
    const count = document.getElementById('replyCount-' + id);
    if (count) count.textContent = e.target.value.length + ' / 1000';
});

// ── Send Reply — AJAX ─────────────────────────────
function sendReply(id) {
    const textarea = document.getElementById('replyText-'  + id);
    const btn      = document.getElementById('replyBtn-'   + id);
    const result   = document.getElementById('replyResult-'+ id);
    if (!textarea || !btn) return;

    const reply = textarea.value.trim();
    if (!reply) {
        result.innerHTML =
            '<span style="color:#ef4444"><i class="fas fa-exclamation-circle me-1"></i>' +
            'Please write a reply first.</span>';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending…';
    result.innerHTML = '';

    fetch(`/doctor/reviews/${id}/reply`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reply }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            result.innerHTML =
                '<span style="color:#22c55e">' +
                '<i class="fas fa-check-circle me-1"></i>' +
                (data.message || 'Reply sent!') + '</span>';
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Sent';
            btn.classList.replace('btn-primary', 'btn-success');
            textarea.value = '';
            document.getElementById('replyCount-' + id).textContent = '0 / 1000';

            // Auto-hide reply box after 2s
            setTimeout(() => {
                document.getElementById('replyBox-' + id).style.display = 'none';
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Reply';
                btn.classList.replace('btn-success', 'btn-primary');
                btn.disabled = false;
                result.innerHTML = '';
            }, 2500);
        } else {
            result.innerHTML =
                '<span style="color:#ef4444">' +
                '<i class="fas fa-exclamation-circle me-1"></i>' +
                (data.message || 'Failed. Try again.') + '</span>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Reply';
        }
    })
    .catch(() => {
        result.innerHTML =
            '<span style="color:#ef4444">' +
            '<i class="fas fa-exclamation-circle me-1"></i>' +
            'Network error. Try again.</span>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Reply';
    });
}
</script>
@endpush
