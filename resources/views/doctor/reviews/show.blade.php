{{-- ══════════════════════════════════════════════════════
     resources/views/doctor/reviews/show.blade.php
══════════════════════════════════════════════════════ --}}
@extends('doctor.layouts.master')

@section('title', 'Review Detail')
@section('page-title', 'Review Detail')

@push('styles')
<style>
.review-detail-wrap { max-width: 720px; margin: 0 auto; padding: 1.5rem 1rem; }

.detail-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 1.5px solid #f0f3f8; padding: 1.5rem;
    margin-bottom: 1.2rem;
}
.detail-sec-title {
    font-size: .8rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .6rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: .4rem;
}
.detail-sec-title i { color: #f59e0b; }

.pat-avatar-lg {
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; font-weight: 900; color: #fff; flex-shrink: 0;
}

/* Star display */
.stars-lg { font-size: 1.2rem; }
.stars-lg .s-fill  { color: #fbbf24; }
.stars-lg .s-empty { color: #e2e8f0; }

.rating-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .3rem .85rem; border-radius: 20px;
    font-size: .9rem; font-weight: 800;
}
.rp-5 { background:#fef9c3; color:#92400e; }
.rp-4 { background:#fef3c7; color:#b45309; }
.rp-3 { background:#fff7ed; color:#c2410c; }
.rp-2 { background:#fee2e2; color:#dc2626; }
.rp-1 { background:#fecaca; color:#b91c1c; }

.review-body {
    background: #f8f9fb; border-radius: 12px;
    padding: 1rem 1.2rem; font-size: .82rem;
    color: #374151; line-height: 1.75; margin-top: .75rem;
    position: relative;
}
.review-body::before {
    content: '"'; font-size: 3rem; color: #e2e8f0;
    position: absolute; top: -.2rem; left: .7rem;
    line-height: 1; font-family: Georgia, serif;
}
.review-body-text { padding-left: 1.5rem; }

.info-row {
    display: flex; align-items: center; gap: .6rem;
    padding: .55rem 0; border-bottom: 1px solid #f8f9fb;
    font-size: .8rem;
}
.info-row:last-child { border-bottom: none; }
.info-ico {
    width: 30px; height: 30px; border-radius: 8px;
    background: #f0f5ff;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; color: #0d6efd; flex-shrink: 0;
}
.info-lbl { font-size: .68rem; color: #94a3b8; font-weight: 600; }
.info-val { font-size: .8rem; font-weight: 600; color: #1a1a1a; margin-top: .06rem; }

/* Reply area */
.reply-area {
    background: #f8f9fb; border-radius: 12px;
    padding: 1rem; border: 1.5px solid #e2e8f0;
}
.reply-area textarea {
    width: 100%; border: 1.5px solid #e2e8f0;
    border-radius: 8px; font-size: .78rem;
    padding: .65rem .8rem; resize: vertical;
    min-height: 100px; outline: none;
    transition: border-color .2s; font-family: inherit;
}
.reply-area textarea:focus { border-color: #0d6efd; }
</style>
@endpush

@section('content')
<div class="review-detail-wrap">

    {{-- ══ Back ══ --}}
    <div class="mb-3">
        <a href="{{ route('doctor.reviews.index') }}"
           class="btn btn-outline-secondary btn-sm"
           style="font-size:.75rem">
            <i class="fas fa-arrow-left me-1"></i>Back to Reviews
        </a>
    </div>

    {{-- ══ Rating Hero ══ --}}
    <div class="detail-card text-center"
         style="background:linear-gradient(135deg,#fff7ed,#fff);
                border-color:#fde68a">
        <div style="margin-bottom:.75rem">
            <span class="rating-pill rp-{{ $review->rating }}">
                <i class="fas fa-star"></i>
                {{ $review->rating }}.0 / 5.0
            </span>
        </div>
        <div class="stars-lg mb-1">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $review->rating ? 's-fill' : 's-empty' }}"></i>
            @endfor
        </div>
        <div style="font-size:.75rem;color:#94a3b8">
            Rated on {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y, h:i A') }}
        </div>
    </div>

    {{-- ══ Patient Info ══ --}}
    <div class="detail-card">
        <div class="detail-sec-title">
            <i class="fas fa-user-circle"></i>Patient Details
        </div>

        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="pat-avatar-lg">
                {{ strtoupper(substr($review->patient_name, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:.95rem;font-weight:800;color:#1a1a1a">
                    {{ $review->patient_name }}
                </div>
                <div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem">
                    <i class="fas fa-user me-1"></i>Patient
                </div>
            </div>
        </div>

        @if($review->patient_phone)
        <div class="info-row">
            <div class="info-ico"><i class="fas fa-phone"></i></div>
            <div>
                <div class="info-lbl">Phone</div>
                <div class="info-val">{{ $review->patient_phone }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- ══ Review Text ══ --}}
    <div class="detail-card">
        <div class="detail-sec-title">
            <i class="fas fa-comment-alt"></i>Review
        </div>

        @if($review->review)
        <div class="review-body">
            <div class="review-body-text">{{ $review->review }}</div>
        </div>
        @else
        <div style="text-align:center;padding:1.5rem;
                    color:#94a3b8;font-size:.8rem;font-style:italic">
            <i class="fas fa-comment-slash d-block mb-1" style="font-size:1.4rem"></i>
            No written review — rating only.
        </div>
        @endif
    </div>

    {{-- ══ Reply Section ══ --}}
    <div class="detail-card">
        <div class="detail-sec-title">
            <i class="fas fa-reply"></i>
            Send Reply to Patient
        </div>

        <div style="font-size:.75rem;color:#64748b;margin-bottom:.85rem;
                    background:#f0f5ff;border-radius:8px;padding:.6rem .8rem">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            Your reply will be sent as a notification to the patient.
        </div>

        <div class="reply-area">
            <textarea id="replyText"
                      placeholder="Write your reply to {{ $review->patient_name }}…"
                      maxlength="1000"
                      oninput="document.getElementById('replyCount').textContent=
                               this.value.length+' / 1000'"></textarea>
            <div style="font-size:.65rem;color:#94a3b8;text-align:right;margin-top:.2rem"
                 id="replyCount">0 / 1000</div>

            <div id="replyResult" style="font-size:.75rem;font-weight:600;margin-top:.5rem"></div>

            <div class="d-flex justify-content-end mt-2">
                <button type="button"
                        class="btn btn-primary"
                        id="replyBtn"
                        onclick="sendReply({{ $review->id }})">
                    <i class="fas fa-paper-plane me-1"></i>Send Reply
                </button>
            </div>
        </div>
    </div>

</div>{{-- /.review-detail-wrap --}}
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function sendReply(id) {
    const textarea = document.getElementById('replyText');
    const btn      = document.getElementById('replyBtn');
    const result   = document.getElementById('replyResult');

    const reply = textarea.value.trim();
    if (!reply) {
        result.innerHTML =
            '<span style="color:#ef4444">' +
            '<i class="fas fa-exclamation-circle me-1"></i>' +
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
                (data.message || 'Reply sent to patient!') + '</span>';
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Sent';
            btn.classList.replace('btn-primary', 'btn-success');
            textarea.value = '';
            document.getElementById('replyCount').textContent = '0 / 1000';
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
            'Network error. Please try again.</span>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Reply';
    });
}
</script>
@endpush
