@extends('pharmacy.layouts.master')
@section('title', 'Ratings & Reviews')
@section('page-title', 'Ratings & Reviews')

@push('styles')
<style>
.star-filled { color: #f59e0b; }
.star-empty  { color: #d1d5db; }
.bar-wrap { flex:1; background:#f1f5f9; border-radius:50px; overflow:hidden; height:8px; }
.bar-fill { height:8px; border-radius:50px; background:linear-gradient(90deg,#f59e0b,#fbbf24); transition:width .5s; }
.review-card { border:1.5px solid #f1f5f9; border-radius:14px; padding:20px; transition:all .2s; }
.review-card:hover { border-color:#dbeafe; box-shadow:0 4px 20px rgba(37,99,235,.07); }
.avatar-circle { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; flex-shrink:0; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Ratings & Reviews</h5>
        <small class="text-muted">Patients' feedback on your pharmacy</small>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4 alert-dismissible fade show"
     style="background:#f0fdf4">
    <i class="fas fa-check-circle me-2 text-success"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ══ Summary Cards ══ --}}
<div class="row g-3 mb-4">

    {{-- Overall Rating --}}
    <div class="col-md-4">
        <div class="dashboard-card h-100 text-center py-4 px-3">
            <div style="font-size:3.8rem;font-weight:800;color:#f59e0b;line-height:1">
                {{ number_format($averageRating, 1) }}
            </div>
            <div class="my-2">
                @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= round($averageRating) ? 'star-filled' : 'star-empty' }}"
                   style="font-size:1.2rem"></i>
                @endfor
            </div>
            <div class="fw-semibold" style="color:#374151">Overall Rating</div>
            <small class="text-muted">
                Based on {{ $totalRatings }} review{{ $totalRatings != 1 ? 's' : '' }}
            </small>
        </div>
    </div>

    {{-- Star Distribution --}}
    <div class="col-md-4">
        <div class="dashboard-card h-100 py-3 px-4">
            <div class="text-uppercase fw-semibold mb-3"
                 style="font-size:.73rem;color:#6b7280;letter-spacing:.05em">
                Star Distribution
            </div>
            @foreach($starData as $starNum => $starCount)
            @php $pct = $totalRatings > 0 ? round(($starCount / $totalRatings) * 100) : 0; @endphp
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="fw-semibold"
                      style="width:12px;font-size:.77rem;text-align:right">
                    {{ $starNum }}
                </span>
                <i class="fas fa-star star-filled" style="font-size:.63rem"></i>
                <div class="bar-wrap">
                    <div class="bar-fill" style="width:{{ $pct }}%"></div>
                </div>
                <span style="width:26px;font-size:.73rem;color:#6b7280;text-align:right">
                    {{ $starCount }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Stats --}}
    <div class="col-md-4">
        <div class="dashboard-card h-100 py-1">
            @php
                $stats = [
                    ['label'=>'Total Reviews',    'val'=>$totalRatings,    'color'=>'#2563eb','bg'=>'#eff6ff','icon'=>'fas fa-comments'],
                    ['label'=>'With Written Review','val'=>$withReviewCount,'color'=>'#7c3aed','bg'=>'#faf5ff','icon'=>'fas fa-pen-alt'],
                    ['label'=>'5 Star Reviews',   'val'=>$starData[5] ?? 0,'color'=>'#16a34a','bg'=>'#f0fdf4','icon'=>'fas fa-star'],
                    ['label'=>'Replied',          'val'=>$repliedCount,    'color'=>'#0891b2','bg'=>'#f0f9ff','icon'=>'fas fa-reply'],
                    ['label'=>'Awaiting Reply',   'val'=>$notRepliedCount, 'color'=>'#d97706','bg'=>'#fffbeb','icon'=>'fas fa-hourglass-half'],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="d-flex align-items-center gap-3 px-3 py-2
                        {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:34px;height:34px;background:{{ $stat['bg'] }};flex-shrink:0">
                    <i class="{{ $stat['icon'] }}"
                       style="color:{{ $stat['color'] }};font-size:.78rem"></i>
                </div>
                <div class="flex-fill">
                    <small class="text-muted" style="font-size:.73rem">
                        {{ $stat['label'] }}
                    </small>
                </div>
                <span class="fw-bold"
                      style="color:{{ $stat['color'] }};font-size:1rem">
                    {{ $stat['val'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Monthly Trend --}}
@if($monthlyTrend->count() > 1)
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-chart-line me-2 text-primary"></i>
            Monthly Trend (Last 6 Months)
        </h6>
    </div>
    <div class="card-body">
        <canvas id="trendChart" height="80"></canvas>
    </div>
</div>
@endif

{{-- ══ Filters ══ --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.ratings.index') }}" method="GET">
            <div class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Patient name or review…"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Stars</label>
                    <select name="rating" class="form-select form-select-sm">
                        <option value="">All Stars</option>
                        @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}"
                                {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                        </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Reply</label>
                    <select name="reply_status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="replied"
                                {{ request('reply_status')==='replied' ? 'selected' : '' }}>
                            Replied
                        </option>
                        <option value="not_replied"
                                {{ request('reply_status')==='not_replied' ? 'selected' : '' }}>
                            Not Replied
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">Order Type</label>
                    <select name="related_type" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="prescriptionorder"
                                {{ request('related_type')==='prescriptionorder' ? 'selected' : '' }}>
                            Rx Order
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2 align-items-center flex-wrap">
                    <button type="submit"
                            class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('pharmacy.ratings.index') }}"
                       class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-times"></i>
                    </a>
                    {{-- Quick star buttons — variable name: $starBtn --}}
                    <div class="d-flex gap-1 ms-auto">
                        @foreach([5,4,3,2,1] as $starBtn)
                        <a href="{{ route('pharmacy.ratings.index', ['rating'=>$starBtn]) }}"
                           class="btn btn-sm rounded-pill px-2
                                  {{ request('rating') == $starBtn ? 'btn-warning' : 'btn-outline-secondary' }}"
                           style="font-size:.68rem;line-height:1.5">
                            {{ $starBtn }}★
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ══ Reviews List ══ --}}
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-star me-2 text-warning"></i>
            Reviews
            @if(request()->hasAny(['rating','reply_status','related_type','search']))
            <small class="text-muted fw-normal">— filtered</small>
            @endif
        </h6>
        <span class="badge bg-light text-dark border">{{ $ratings->total() }}</span>
    </div>

    <div class="card-body pt-3">
        @forelse($ratings as $rating)
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

        <div class="review-card mb-3">
            <div class="d-flex align-items-start gap-3 flex-wrap">

                {{-- Avatar --}}
                <div class="avatar-circle"
                     style="background:{{ $clr }}18;color:{{ $clr }}">
                    {{ $initials }}
                </div>

                <div class="flex-fill">
                    {{-- Top Row --}}
                    <div class="d-flex justify-content-between align-items-start
                                flex-wrap gap-2 mb-1">
                        <div>
                            <a href="{{ route('pharmacy.ratings.show', $rating->id) }}"
                               class="fw-semibold text-decoration-none"
                               style="color:#1e293b;font-size:.9rem">
                                {{ $name }}
                            </a>
                            <small class="text-muted ms-2" style="font-size:.73rem">
                                <i class="far fa-clock me-1"></i>
                                {{ $rating->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">
                            @if($rating->reply)
                            <span class="badge bg-info bg-opacity-15 text-info rounded-pill"
                                  style="font-size:.67rem">
                                <i class="fas fa-reply me-1"></i>Replied
                            </span>
                            @else
                            <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill"
                                  style="font-size:.67rem">
                                <i class="fas fa-clock me-1"></i>Awaiting Reply
                            </span>
                            @endif
                            @if($rating->related_type === 'prescriptionorder')
                            <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"
                                  style="font-size:.67rem">Rx Order</span>
                            @endif
                        </div>
                    </div>

                    {{-- Stars --}}
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star
                           {{ $i <= $rating->rating ? 'star-filled' : 'star-empty' }}"
                           style="font-size:.8rem"></i>
                        @endfor
                        <span class="fw-semibold ms-1"
                              style="font-size:.77rem;color:#f59e0b">
                            {{ $rating->rating }}/5
                        </span>
                    </div>

                    {{-- Review --}}
                    @if($rating->review)
                    <p class="mb-2 text-muted"
                       style="font-size:.84rem;line-height:1.65;
                              border-left:3px solid #e2e8f0;padding-left:10px">
                        "{{ $rating->review }}"
                    </p>
                    @else
                    <p class="mb-2 text-muted fst-italic" style="font-size:.79rem">
                        <i class="fas fa-minus me-1"></i>No written review.
                    </p>
                    @endif

                    {{-- Existing Reply --}}
                    @if($rating->reply)
                    <div class="rounded-3 p-3 mb-2"
                         style="background:#f0fdf4;border-left:3px solid #16a34a">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-reply text-success" style="font-size:.7rem"></i>
                            <span class="fw-semibold text-success" style="font-size:.73rem">
                                Your Reply
                                <span class="text-muted fw-normal">
                                    · {{ $rating->replied_at?->diffForHumans() }}
                                </span>
                            </span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.5">
                            {{ $rating->reply }}
                        </p>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-2 flex-wrap">
                        <a href="{{ route('pharmacy.ratings.show', $rating->id) }}"
                           class="btn btn-outline-primary btn-sm rounded-pill px-3"
                           style="font-size:.73rem">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        <button type="button"
                                class="btn {{ $rating->reply ? 'btn-outline-secondary' : 'btn-outline-success' }}
                                       btn-sm rounded-pill px-3"
                                style="font-size:.73rem"
                                data-bs-toggle="modal"
                                data-bs-target="#replyModal{{ $rating->id }}">
                            <i class="fas fa-{{ $rating->reply ? 'edit' : 'reply' }} me-1"></i>
                            {{ $rating->reply ? 'Edit Reply' : 'Reply' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reply Modal --}}
        <div class="modal fade" id="replyModal{{ $rating->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <form action="{{ route('pharmacy.ratings.reply', $rating->id) }}"
                          method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h6 class="modal-title fw-bold">
                                {{ $rating->reply ? 'Edit Reply — ' : 'Reply to ' }}{{ $name }}
                            </h6>
                            <button type="button" class="btn-close"
                                    data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Review Recap --}}
                            <div class="rounded-3 p-3 mb-3" style="background:#f8fafc">
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star
                                       {{ $i <= $rating->rating ? 'star-filled' : 'star-empty' }}"
                                       style="font-size:.7rem"></i>
                                    @endfor
                                    <span class="ms-1 fw-semibold"
                                          style="font-size:.74rem;color:#f59e0b">
                                        {{ $rating->rating }}/5
                                    </span>
                                </div>
                                @if($rating->review)
                                <p class="mb-0 text-muted fst-italic"
                                   style="font-size:.79rem;line-height:1.5">
                                    "{{ $rating->review }}"
                                </p>
                                @else
                                <p class="mb-0 text-muted fst-italic" style="font-size:.77rem">
                                    No written review.
                                </p>
                                @endif
                            </div>
                            <div>
                                <label class="form-label form-label-sm">
                                    {{ $rating->reply ? 'Update Reply' : 'Your Reply' }}
                                </label>
                                <textarea name="reply" rows="4"
                                          class="form-control form-control-sm"
                                          placeholder="Write a professional reply…"
                                          maxlength="1000"
                                          required>{{ $rating->reply }}</textarea>
                                <div class="form-text" style="font-size:.71rem">
                                    Max 1000 characters.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm rounded-pill"
                                    data-bs-dismiss="modal">Cancel</button>
                            <button type="submit"
                                    class="btn btn-success btn-sm rounded-pill px-4">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ $rating->reply ? 'Update' : 'Send Reply' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-star fa-3x mb-3 d-block opacity-20"></i>
            <h6 class="fw-semibold">No reviews found</h6>
            <small>
                @if(request()->hasAny(['rating','reply_status','related_type','search']))
                    Try clearing the filters.
                @else
                    Patient reviews will appear here once they rate your pharmacy.
                @endif
            </small>
            @if(request()->hasAny(['rating','reply_status','related_type','search']))
            <div class="mt-3">
                <a href="{{ route('pharmacy.ratings.index') }}"
                   class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            </div>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($ratings->hasPages())
    <div class="card-footer border-0 d-flex justify-content-between align-items-center
                flex-wrap gap-2 py-3 px-4" style="background:#fafafa">
        <small class="text-muted">
            Showing {{ $ratings->firstItem() }}–{{ $ratings->lastItem() }}
            of {{ $ratings->total() }} reviews
        </small>
        {{ $ratings->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
@php
    $tLabels = $monthlyTrend->map(fn($r) =>
        \Carbon\Carbon::create($r->year, $r->month)->format('M Y'))->toArray();
    $tCounts = $monthlyTrend->pluck('count')->toArray();
    $tAvg    = $monthlyTrend->map(fn($r) => round($r->avg_rating, 2))->toArray();
@endphp
const tLabels = @json($tLabels);
const tCounts = @json($tCounts);
const tAvg    = @json($tAvg);

const trendEl = document.getElementById('trendChart');
if (trendEl && tLabels.length > 0) {
    new Chart(trendEl, {
        type: 'line',
        data: {
            labels: tLabels,
            datasets: [
                {
                    label: 'Reviews',
                    data: tCounts,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,.1)',
                    borderWidth: 2, pointRadius: 4, fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Avg Rating',
                    data: tAvg,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,.05)',
                    borderWidth: 2, pointRadius: 4, fill: false,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y:  { beginAtZero: true, position: 'left',
                      title: { display: true, text: 'Count' } },
                y1: { min: 1, max: 5, position: 'right',
                      grid: { drawOnChartArea: false },
                      title: { display: true, text: 'Stars' } }
            }
        }
    });
}
</script>
@endpush
