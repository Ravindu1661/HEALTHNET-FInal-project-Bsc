{{-- resources/views/medical_centre/reviews/show.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Review Details')
@section('page-title', 'Review Details')

@section('content')
<style>
.mc-page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.mc-page-title { font-size:1.25rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.mc-page-sub   { font-size:.82rem; color:var(--text-muted); margin:0; }
.mc-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.48rem 1rem; border-radius:9px; border:none; font-size:.8rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); text-decoration:none; }
.mc-btn-back { background:#f4f7fb; color:var(--text-muted); }
.mc-btn-back:hover { background:#e9ecef; color:var(--text-dark); }

.rev-show-grid { display:grid; grid-template-columns:1fr 300px; gap:1.25rem; align-items:start; }
@media(max-width:900px){ .rev-show-grid { grid-template-columns:1fr; } }

/* Main review card */
.rev-main-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; }
.rev-main-head { padding:1.1rem 1.25rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; gap:.85rem; }
.rev-avatar-lg { width:52px; height:52px; border-radius:50%; object-fit:cover; flex-shrink:0; background:linear-gradient(135deg,var(--mc-primary),var(--mc-secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; font-weight:800; }
.rev-avatar-lg img { width:52px; height:52px; border-radius:50%; object-fit:cover; }
.rev-main-name { font-size:.95rem; font-weight:800; color:var(--text-dark); margin:0 0 .2rem; }
.rev-main-meta { font-size:.72rem; color:var(--text-muted); font-weight:600; }
.rev-stars-lg { color:#f59e0b; font-size:.95rem; letter-spacing:.08rem; }
.rev-stars-empty { color:#e5e7eb; }

.rev-main-body { padding:1.25rem; }
.rev-review-text { font-size:.88rem; color:var(--text-muted); line-height:1.85; background:#f8fbff; border-radius:10px; padding:1rem 1.1rem; border:1px solid var(--border); margin-bottom:1.25rem; white-space:pre-line; }
.rev-review-text.empty { font-style:italic; opacity:.6; }

/* Reply Section */
.rev-reply-section h6 { font-size:.85rem; font-weight:800; color:var(--text-dark); margin-bottom:.75rem; }
.rev-existing-reply { background:#f0fdf4; border:1.5px solid #a7f3d0; border-radius:10px; padding:1rem 1.1rem; margin-bottom:1rem; }
.rev-existing-reply .reply-label { font-size:.7rem; font-weight:800; color:#065f46; text-transform:uppercase; letter-spacing:.05em; display:flex; align-items:center; gap:.3rem; margin-bottom:.4rem; }
.rev-existing-reply .reply-text { font-size:.84rem; color:var(--text-muted); line-height:1.7; margin:0; white-space:pre-line; }
.rev-existing-reply .reply-actions { display:flex; gap:.5rem; margin-top:.75rem; }
.reply-edit-btn { padding:.35rem .85rem; border-radius:7px; background:#fff3cd; color:#92400e; border:1.5px solid #fde68a; font-size:.75rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); display:inline-flex; align-items:center; gap:.3rem; }
.reply-edit-btn:hover { background:#d97706; color:#fff; border-color:#d97706; }
.reply-del-btn { padding:.35rem .85rem; border-radius:7px; background:#fff; color:#991b1b; border:1.5px solid #fca5a5; font-size:.75rem; font-weight:700; cursor:pointer; font-family:inherit; transition:var(--transition); display:inline-flex; align-items:center; gap:.3rem; }
.reply-del-btn:hover { background:#fee2e2; }

.rev-reply-form label { display:block; font-size:.75rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.4rem; }
.rev-reply-form textarea { width:100%; padding:.65rem .9rem; border-radius:9px; border:1.5px solid var(--border); font-size:.83rem; font-weight:600; color:var(--text-dark); background:#fff; font-family:inherit; outline:none; transition:border-color .2s; resize:vertical; min-height:110px; box-sizing:border-box; }
.rev-reply-form textarea:focus { border-color:var(--mc-primary); }
.rev-reply-form textarea.is-invalid { border-color:#e74c3c; }
.rev-form-error { font-size:.72rem; color:#e74c3c; margin-top:.3rem; font-weight:600; }
.rev-char-counter { font-size:.7rem; color:var(--text-muted); font-weight:600; text-align:right; margin-top:.25rem; }
.rev-submit-btn { margin-top:.75rem; padding:.55rem 1.35rem; border-radius:9px; border:none; font-size:.82rem; font-weight:800; cursor:pointer; font-family:inherit; transition:var(--transition); display:inline-flex; align-items:center; gap:.4rem; background:var(--mc-primary); color:#fff; }
.rev-submit-btn:hover { background:var(--mc-secondary); }
.rev-submit-btn:disabled { opacity:.6; cursor:not-allowed; }

/* Sidebar */
.rev-meta-card { background:#fff; border-radius:14px; border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden; }
.rev-meta-head { padding:.85rem 1rem; border-bottom:1px solid var(--border); background:#fafbfc; }
.rev-meta-head h6 { font-size:.85rem; font-weight:800; color:var(--text-dark); margin:0; }
.rev-meta-body { padding:1rem; display:flex; flex-direction:column; gap:.85rem; }
.rev-meta-item { display:flex; align-items:flex-start; gap:.65rem; }
.rev-meta-icon { width:28px; height:28px; border-radius:7px; background:#f4f7fb; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.rev-meta-icon i { font-size:.72rem; color:var(--mc-primary); }
.rev-meta-text label { font-size:.67rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em; display:block; margin-bottom:.1rem; }
.rev-meta-text span  { font-size:.8rem; font-weight:700; color:var(--text-dark); }

.rev-rating-display { display:flex; align-items:center; gap:.4rem; }
.rev-rating-number { font-size:1.4rem; font-weight:900; color:var(--text-dark); line-height:1; }
</style>

@php
    $name     = trim(($review->first_name ?? '') . ' ' . ($review->last_name ?? ''));
    $initial  = strtoupper(substr($name ?: 'P', 0, 1));
    $hasReply = !empty($review->reply ?? null);
    $replyCol = in_array('reply', \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('ratings'));
@endphp

{{-- Header --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-star me-2" style="color:#f59e0b;"></i>Review Details
        </h4>
        <p class="mc-page-sub">{{ $name ?: 'Anonymous Patient' }}</p>
    </div>
    <a href="{{ route('medical_centre.reviews') }}" class="mc-btn mc-btn-back">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="rev-show-grid">

    {{-- Main --}}
    <div>
        <div class="rev-main-card">

            {{-- Reviewer Header --}}
            <div class="rev-main-head">
                <div class="rev-avatar-lg">
                    @if(!empty($review->reviewer_photo))
                        <img src="{{ asset('storage/' . $review->reviewer_photo) }}" alt="{{ $name }}">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <div>
                    <p class="rev-main-name">{{ $name ?: 'Anonymous Patient' }}</p>
                    <div class="rev-stars-lg">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star {{ $i <= $review->rating ? '' : 'rev-stars-empty' }}"></i>
                        @endfor
                        <span style="font-size:.75rem;font-weight:700;color:var(--text-muted);margin-left:.3rem;">
                            {{ $review->rating }}.0 / 5.0
                        </span>
                    </div>
                    <p class="rev-main-meta" style="margin-top:.2rem;">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y · h:i A') }}
                    </p>
                </div>
            </div>

            {{-- Review Body --}}
            <div class="rev-main-body">
                <p class="rev-review-text {{ empty($review->review) ? 'empty' : '' }}">
                    {{ $review->review ?: 'No written review provided.' }}
                </p>

                {{-- Reply Section --}}
                @if($replyCol)
                    <div class="rev-reply-section">
                        <h6><i class="fas fa-reply me-2" style="color:var(--mc-primary);"></i>
                            {{ $hasReply ? 'Your Reply' : 'Reply to this Review' }}
                        </h6>

                        @if($hasReply)
                            <div class="rev-existing-reply" id="replyDisplay">
                                <div class="reply-label">
                                    <i class="fas fa-check-circle"></i> Replied
                                </div>
                                <p class="reply-text">{{ $review->reply }}</p>
                                <div class="reply-actions">
                                    <button type="button" class="reply-edit-btn"
                                            onclick="toggleEditForm()">
                                        <i class="fas fa-edit"></i> Edit Reply
                                    </button>
                                    <form method="POST"
                                          action="{{ route('medical_centre.reviews.delete_reply', $review->id) }}"
                                          style="margin:0;"
                                          onsubmit="return confirm('Remove your reply?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="reply-del-btn">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Reply Form --}}
                        <div id="replyForm" style="{{ $hasReply ? 'display:none;' : '' }}">
                            @if($errors->has('reply'))
                                <div style="font-size:.72rem;color:#e74c3c;font-weight:600;margin-bottom:.5rem;">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('reply') }}
                                </div>
                            @endif
                            <form method="POST"
                                  action="{{ route('medical_centre.reviews.reply', $review->id) }}"
                                  id="replyPostForm">
                                @csrf
                                <div class="rev-reply-form">
                                    <label>
                                        {{ $hasReply ? 'Update Your Reply' : 'Write a Reply' }}
                                        <span style="color:#e74c3c;">*</span>
                                    </label>
                                    <textarea name="reply"
                                              id="replyTextarea"
                                              maxlength="1000"
                                              class="{{ $errors->has('reply') ? 'is-invalid' : '' }}"
                                              placeholder="Write a professional, helpful reply..."
                                              oninput="updateReplyCounter(this)">{{ old('reply', $hasReply ? $review->reply : '') }}</textarea>
                                    <div class="rev-char-counter">
                                        <span id="replyCount">{{ strlen(old('reply', $hasReply ? ($review->reply ?? '') : '')) }}</span> / 1000
                                    </div>
                                    <div style="display:flex;gap:.5rem;align-items:center;margin-top:.5rem;flex-wrap:wrap;">
                                        <button type="submit" class="rev-submit-btn" id="replySubmitBtn">
                                            <i class="fas fa-paper-plane"></i>
                                            {{ $hasReply ? 'Update Reply' : 'Post Reply' }}
                                        </button>
                                        @if($hasReply)
                                            <button type="button"
                                                    onclick="toggleEditForm()"
                                                    style="padding:.5rem 1rem;border-radius:9px;border:none;background:#f4f7fb;color:var(--text-muted);font-size:.8rem;font-weight:700;cursor:pointer;">
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div style="background:#fff3cd;border-radius:9px;padding:.75rem 1rem;font-size:.8rem;color:#92400e;font-weight:600;">
                        <i class="fas fa-info-circle me-2"></i>
                        Reply feature requires a <code>reply</code> column in the <code>ratings</code> table.
                        Run the migration to enable this feature.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="rev-meta-card">
        <div class="rev-meta-head">
            <h6><i class="fas fa-info-circle me-2" style="color:var(--mc-primary);"></i>Details</h6>
        </div>
        <div class="rev-meta-body">

            <div class="rev-meta-item">
                <div class="rev-meta-icon"><i class="fas fa-star"></i></div>
                <div class="rev-meta-text">
                    <label>Rating</label>
                    <div class="rev-rating-display">
                        <span class="rev-rating-number">{{ $review->rating }}</span>
                        <span style="color:#f59e0b;font-size:.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </span>
                    </div>
                </div>
            </div>

            <div class="rev-meta-item">
                <div class="rev-meta-icon"><i class="fas fa-user"></i></div>
                <div class="rev-meta-text">
                    <label>Patient</label>
                    <span>{{ $name ?: 'Anonymous' }}</span>
                </div>
            </div>

            @if(!empty($review->reviewer_email))
                <div class="rev-meta-item">
                    <div class="rev-meta-icon"><i class="fas fa-envelope"></i></div>
                    <div class="rev-meta-text">
                        <label>Email</label>
                        <span style="font-size:.75rem;">{{ $review->reviewer_email }}</span>
                    </div>
                </div>
            @endif

            <div class="rev-meta-item">
                <div class="rev-meta-icon"><i class="fas fa-calendar-plus"></i></div>
                <div class="rev-meta-text">
                    <label>Submitted</label>
                    <span>{{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</span>
                </div>
            </div>

            <div class="rev-meta-item">
                <div class="rev-meta-icon">
                    <i class="fas fa-circle" style="font-size:.45rem;color:{{ $hasReply ? '#059669' : '#d97706' }};"></i>
                </div>
                <div class="rev-meta-text">
                    <label>Reply Status</label>
                    <span style="color:{{ $hasReply ? '#059669' : '#d97706' }};">
                        {{ $hasReply ? 'Replied' : 'Pending Reply' }}
                    </span>
                </div>
            </div>

            @if(!empty($review->related_type))
                <div class="rev-meta-item">
                    <div class="rev-meta-icon"><i class="fas fa-link"></i></div>
                    <div class="rev-meta-text">
                        <label>Related To</label>
                        <span style="text-transform:capitalize;">{{ str_replace('_', ' ', $review->related_type) }}</span>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

<script>
function toggleEditForm() {
    const display = document.getElementById('replyDisplay');
    const form    = document.getElementById('replyForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        if (display) display.style.display = 'none';
    } else {
        form.style.display = 'none';
        if (display) display.style.display = 'block';
    }
}

function updateReplyCounter(el) {
    document.getElementById('replyCount').textContent = el.value.length;
}

document.getElementById('replyPostForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('replySubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
});
</script>
@endsection
