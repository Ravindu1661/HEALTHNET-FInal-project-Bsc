@extends('pharmacy.layouts.master')
@section('title', 'Review Detail')
@section('page-title', 'Ratings & Reviews')

@push('styles')
<style>
.star-filled { color: #f59e0b; }
.star-empty  { color: #d1d5db; }
.info-row {
    display:flex; gap:10px; padding:10px 0;
    border-bottom:1px solid #f1f5f9; font-size:.84rem;
}
.info-row:last-child { border-bottom:none; }
.info-label {
    min-width:120px; flex-shrink:0;
    font-size:.72rem; font-weight:600;
    color:#6b7280; text-transform:uppercase;
    letter-spacing:.04em; padding-top:2px;
}
</style>
@endpush

@section('content')

<div class="mb-4">
    <a href="{{ route('pharmacy.ratings.index') }}"
       class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-1"></i>Back to Reviews
    </a>
</div>

@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4 alert-dismissible fade show"
     style="background:#f0fdf4">
    <i class="fas fa-check-circle me-2 text-success"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@php
    $patient  = $rating->patient;
    $name     = trim(optional($patient)->first_name . ' ' . optional($patient)->last_name)
                ?: 'Unknown Patient';
    $initials = strtoupper(
        substr(optional($patient)->first_name ?? 'U', 0, 1) .
        substr(optional($patient)->last_name  ?? '',  0, 1)
    );
    $palette = ['#2563eb','#16a34a','#7c3aed','#d97706','#0891b2','#e11d48'];
    $clr     = $palette[$rating->id % count($palette)];
@endphp

<div class="row g-4">

    {{-- ══ Main ══ --}}
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>Review Detail
                </h6>
                @if($rating->reply)
                <span class="badge bg-info rounded-pill" style="font-size:.7rem">
                    <i class="fas fa-reply me-1"></i>Replied
                </span>
                @else
                <span class="badge bg-warning rounded-pill" style="font-size:.7rem">
                    <i class="fas fa-clock me-1"></i>Awaiting Reply
                </span>
                @endif
            </div>
            <div class="card-body">

                {{-- Patient + Stars --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center
                                fw-bold"
                         style="width:56px;height:56px;flex-shrink:0;
                                background:{{ $clr }}18;color:{{ $clr }};font-size:1.1rem">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.95rem;color:#1e293b">
                            {{ $name }}
                        </div>
                        <div class="my-1">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star
                               {{ $i <= $rating->rating ? 'star-filled' : 'star-empty' }}"
                               style="font-size:.92rem"></i>
                            @endfor
                            <span class="fw-bold ms-1"
                                  style="font-size:.85rem;color:#f59e0b">
                                {{ $rating->rating }} / 5
                            </span>
                        </div>
                        <small class="text-muted" style="font-size:.74rem">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $rating->created_at->format('d F Y, h:i A') }}
                            · {{ $rating->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                {{-- Review --}}
                <div class="mb-4">
                    <div class="text-uppercase fw-semibold mb-2"
                         style="font-size:.72rem;color:#6b7280;letter-spacing:.05em">
                        <i class="fas fa-comment-alt me-1"></i>Patient Review
                    </div>
                    @if($rating->review)
                    <div class="p-4 rounded-3"
                         style="background:#f8fafc;border-left:4px solid #f59e0b;
                                font-size:.9rem;line-height:1.7;color:#374151">
                        "{{ $rating->review }}"
                    </div>
                    @else
                    <div class="p-4 rounded-3 text-center text-muted"
                         style="background:#f8fafc;border:1.5px dashed #e2e8f0">
                        <i class="far fa-comment-dots fa-2x mb-2 d-block opacity-30"></i>
                        <small class="fst-italic">No written review provided.</small>
                    </div>
                    @endif
                </div>

                {{-- Related Order --}}
                @if($rating->related_type === 'prescriptionorder' && $relatedOrder)
                <div class="mb-4">
                    <div class="text-uppercase fw-semibold mb-2"
                         style="font-size:.72rem;color:#6b7280;letter-spacing:.05em">
                        <i class="fas fa-prescription-bottle-alt me-1"></i>
                        Related Prescription Order
                    </div>
                    <div class="rounded-3 p-3 d-flex justify-content-between
                                align-items-center flex-wrap gap-2"
                         style="background:#eff6ff;border:1.5px solid #dbeafe">
                        <div>
                            <div class="fw-semibold" style="font-size:.85rem">
                                {{ $relatedOrder->order_number }}
                            </div>
                            <small class="text-muted" style="font-size:.77rem">
                                {{ $relatedOrder->created_at->format('d M Y') }}
                                · {{ $relatedOrder->items->count() }} item(s)
                                · Rs. {{ number_format($relatedOrder->total_amount, 2) }}
                            </small>
                        </div>
                        <a href="{{ route('pharmacy.orders.show', $relatedOrder->id) }}"
                           class="btn btn-primary btn-sm rounded-pill px-3"
                           style="font-size:.74rem">
                            <i class="fas fa-eye me-1"></i>View Order
                        </a>
                    </div>
                </div>
                @elseif($rating->related_type === 'prescriptionorder' && $rating->related_id)
                <div class="mb-4">
                    <div class="p-3 rounded-3 text-muted"
                         style="background:#f8fafc;font-size:.82rem">
                        Linked to Prescription Order #{{ $rating->related_id }}
                        (no longer available)
                    </div>
                </div>
                @endif

                {{-- Current Reply (if exists) --}}
                @if($rating->reply)
                <div class="mb-4">
                    <div class="text-uppercase fw-semibold mb-2"
                         style="font-size:.72rem;color:#16a34a;letter-spacing:.05em">
                        <i class="fas fa-check-circle me-1"></i>Your Current Reply
                    </div>
                    <div class="p-4 rounded-3"
                         style="background:#f0fdf4;border-left:4px solid #16a34a;
                                font-size:.88rem;line-height:1.7;color:#374151">
                        {{ $rating->reply }}
                    </div>
                    <small class="text-muted" style="font-size:.73rem">
                        <i class="far fa-clock me-1"></i>
                        {{ $rating->replied_at?->format('d M Y, h:i A') }}
                        ({{ $rating->replied_at?->diffForHumans() }})
                    </small>
                </div>
                @endif

                {{-- Reply / Edit Form --}}
                <div>
                    <div class="text-uppercase fw-semibold mb-2"
                         style="font-size:.72rem;
                                color:{{ $rating->reply ? '#0891b2' : '#16a34a' }};
                                letter-spacing:.05em">
                        <i class="fas fa-{{ $rating->reply ? 'edit' : 'reply' }} me-1"></i>
                        {{ $rating->reply ? 'Edit Your Reply' : 'Reply to This Review' }}
                    </div>
                    <form action="{{ route('pharmacy.ratings.reply', $rating->id) }}"
                          method="POST">
                        @csrf
                        @error('reply')
                        <div class="alert alert-danger py-2 mb-2 border-0 rounded-3"
                             style="font-size:.8rem">{{ $message }}</div>
                        @enderror
                        <textarea name="reply" rows="4"
                                  class="form-control mb-2
                                         @error('reply') is-invalid @enderror"
                                  placeholder="Write a professional, helpful reply…"
                                  maxlength="1000"
                                  style="font-size:.85rem;resize:vertical">{{ old('reply', $rating->reply) }}</textarea>
                        <div class="form-text mb-3" style="font-size:.71rem">
                            <i class="fas fa-info-circle me-1 text-info"></i>
                            {{ $rating->reply
                                ? 'This will overwrite your previous reply.'
                                : 'Your reply is visible to the patient.' }}
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('pharmacy.ratings.index') }}"
                               class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-success btn-sm rounded-pill px-4">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ $rating->reply ? 'Update Reply' : 'Send Reply' }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ══ Sidebar ══ --}}
    <div class="col-lg-4">

        {{-- Review Meta --}}
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-info"></i>Review Info
                </h6>
            </div>
            <div class="card-body p-0 px-3 py-1">
                <div class="info-row">
                    <div class="info-label">Review ID</div>
                    <div class="text-muted">#{{ $rating->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Submitted</div>
                    <div>
                        {{ $rating->created_at->format('d M Y') }}<br>
                        <small class="text-muted">{{ $rating->created_at->format('h:i A') }}</small>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rating</div>
                    <div class="d-flex align-items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star
                           {{ $i <= $rating->rating ? 'star-filled' : 'star-empty' }}"
                           style="font-size:.7rem"></i>
                        @endfor
                        <span class="fw-semibold ms-1"
                              style="font-size:.77rem;color:#f59e0b">
                            {{ $rating->rating }}/5
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Has Review</div>
                    <div>
                        @if($rating->review)
                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill"
                              style="font-size:.67rem">Yes</span>
                        @else
                        <span class="badge bg-secondary bg-opacity-15 text-secondary rounded-pill"
                              style="font-size:.67rem">No</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Reply Status</div>
                    <div>
                        @if($rating->reply)
                        <span class="badge bg-info bg-opacity-15 text-info rounded-pill"
                              style="font-size:.67rem">
                            <i class="fas fa-check me-1"></i>Replied
                        </span>
                        @else
                        <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill"
                              style="font-size:.67rem">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                        @endif
                    </div>
                </div>
                @if($rating->replied_at)
                <div class="info-row">
                    <div class="info-label">Replied At</div>
                    <div>
                        {{ $rating->replied_at->format('d M Y') }}<br>
                        <small class="text-muted">{{ $rating->replied_at->format('h:i A') }}</small>
                    </div>
                </div>
                @endif
                @if($rating->related_type)
                <div class="info-row">
                    <div class="info-label">Linked To</div>
                    <div>
                        <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"
                              style="font-size:.67rem">
                            {{ $rating->related_type === 'prescriptionorder'
                               ? 'Prescription Order' : ucfirst($rating->related_type) }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Patient Info --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>Patient
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center
                                fw-bold"
                         style="width:46px;height:46px;flex-shrink:0;
                                background:{{ $clr }}18;color:{{ $clr }};font-size:.95rem">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.88rem">{{ $name }}</div>
                        <small class="text-muted" style="font-size:.73rem">
                            {{ optional(optional($patient)->user)->email ?? '–' }}
                        </small>
                    </div>
                </div>

                @if($patient)
                <div style="font-size:.8rem">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Phone</span>
                        <span>{{ $patient->phone ?? '–' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">City</span>
                        <span>{{ $patient->city ?? '–' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Gender</span>
                        <span>{{ ucfirst($patient->gender ?? '–') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Blood Group</span>
                        <span>{{ $patient->blood_group ?? '–' }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('pharmacy.patients.show', $patient->id) }}"
                       class="btn btn-outline-primary btn-sm w-100 rounded-pill"
                       style="font-size:.76rem">
                        <i class="fas fa-user me-1"></i>View Patient Profile
                    </a>
                </div>
                @else
                <p class="text-muted mb-0" style="font-size:.8rem">
                    Patient details not available.
                </p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
