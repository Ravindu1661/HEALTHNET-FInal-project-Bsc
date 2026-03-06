@extends('pharmacy.layouts.master')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@push('styles')
<style>
.notif-card {
    border: 1.5px solid #f1f5f9;
    border-radius: 14px;
    padding: 16px 20px;
    transition: all .2s;
    position: relative;
}
.notif-card:hover {
    border-color: #dbeafe;
    box-shadow: 0 4px 16px rgba(37,99,235,.07);
}
.notif-card.unread {
    background: #fafbff;
    border-left: 4px solid #2563eb;
}
.notif-card.unread::before {
    content: '';
    position: absolute;
    top: 18px; right: 18px;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #2563eb;
}
.notif-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: .85rem;
}
.notif-time {
    font-size: .72rem;
    color: #9ca3af;
}
.type-badge {
    font-size: .65rem;
    padding: 2px 8px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: capitalize;
}
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">
            Notifications
            @if($unreadCount > 0)
            <span class="badge bg-primary rounded-pill ms-2"
                  style="font-size:.7rem">
                {{ $unreadCount }} new
            </span>
            @endif
        </h5>
        <small class="text-muted">
            All your pharmacy notifications
        </small>
    </div>

    @if($unreadCount > 0)
    <form action="{{ route('pharmacy.notifications.mark-all-read') }}"
          method="POST">
        @csrf
        <button type="submit"
                class="btn btn-outline-primary btn-sm rounded-pill px-4">
            <i class="fas fa-check-double me-1"></i>
            Mark All as Read
        </button>
    </form>
    @endif
</div>

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success border-0 rounded-3 mb-4 alert-dismissible fade show"
     style="background:#f0fdf4">
    <i class="fas fa-check-circle me-2 text-success"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ══ Stats Row ══ --}}
<div class="row g-3 mb-4">
    @php
        $overallStats = [
            ['label' => 'Total',   'val' => $notifications->total(),
             'color' => '#2563eb', 'bg' => '#eff6ff', 'icon' => 'fas fa-bell'],
            ['label' => 'Unread',  'val' => $unreadCount,
             'color' => '#dc2626', 'bg' => '#fef2f2', 'icon' => 'fas fa-envelope'],
            ['label' => 'Orders',  'val' => $typeStats->get('order', 0),
             'color' => '#7c3aed', 'bg' => '#faf5ff', 'icon' => 'fas fa-prescription-bottle-alt'],
            ['label' => 'Payments','val' => $typeStats->get('payment', 0),
             'color' => '#16a34a', 'bg' => '#f0fdf4', 'icon' => 'fas fa-credit-card'],
        ];
    @endphp
    @foreach($overallStats as $os)
    <div class="col-6 col-md-3">
        <div class="dashboard-card text-center py-3 px-2">
            <div class="notif-icon mx-auto mb-2"
                 style="background:{{ $os['bg'] }}">
                <i class="{{ $os['icon'] }}"
                   style="color:{{ $os['color'] }}"></i>
            </div>
            <div class="fw-bold" style="font-size:1.3rem;color:{{ $os['color'] }}">
                {{ $os['val'] }}
            </div>
            <small class="text-muted" style="font-size:.73rem">
                {{ $os['label'] }}
            </small>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ Notifications List ══ --}}
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-bell me-2 text-primary"></i>
            All Notifications
        </h6>
        <span class="badge bg-light text-dark border">
            {{ $notifications->total() }}
        </span>
    </div>

    <div class="card-body pt-3">
        @forelse($notifications as $notif)
        <div class="notif-card mb-3 {{ !$notif->is_read ? 'unread' : '' }}"
             id="notif-{{ $notif->id }}">
            <div class="d-flex align-items-start gap-3">

                {{-- Icon --}}
                <div class="notif-icon"
                     style="background:{{ $notif->bg }}">
                    <i class="{{ $notif->icon }}"
                       style="color:{{ $notif->color }}"></i>
                </div>

                {{-- Content --}}
                <div class="flex-fill">
                    <div class="d-flex justify-content-between
                                align-items-start flex-wrap gap-2 mb-1">
                        <div>
                            <span class="fw-semibold"
                                  style="font-size:.88rem;color:#1e293b">
                                {{ $notif->title }}
                            </span>
                            <span class="type-badge ms-2"
                                  style="background:{{ $notif->bg }};
                                         color:{{ $notif->color }}">
                                {{ $notif->type }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="notif-time">
                                <i class="far fa-clock me-1"></i>
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                            @if(!$notif->is_read)
                            <button type="button"
                                    class="btn btn-outline-primary btn-sm rounded-pill px-2 py-0 mark-read-btn"
                                    style="font-size:.67rem"
                                    data-id="{{ $notif->id }}"
                                    data-url="{{ route('pharmacy.notifications.mark-read', $notif->id) }}">
                                <i class="fas fa-check me-1"></i>Read
                            </button>
                            @else
                            <span style="font-size:.67rem;color:#9ca3af">
                                <i class="fas fa-check-double me-1"></i>
                                Read {{ $notif->read_at?->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <p class="mb-1 text-muted"
                       style="font-size:.83rem;line-height:1.6">
                        {{ $notif->message }}
                    </p>

                    {{-- Related Link --}}
                    @if($notif->related_type && $notif->related_id)
                    @php
                        $relatedUrl = match($notif->related_type) {
                            'prescriptionorder' => route('pharmacy.orders.show', $notif->related_id),
                            'payment'           => route('pharmacy.orders.show', $notif->related_id),
                            default             => null,
                        };
                    @endphp
                    @if($relatedUrl)
                    <a href="{{ $relatedUrl }}"
                       class="btn btn-sm rounded-pill px-3 mt-1"
                       style="font-size:.72rem;background:{{ $notif->bg }};
                              color:{{ $notif->color }};border:1px solid {{ $notif->bg }}">
                        <i class="fas fa-arrow-right me-1"></i>View Details
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-bell-slash fa-3x mb-3 d-block opacity-20"></i>
            <h6 class="fw-semibold">No notifications yet</h6>
            <small>
                New order alerts, payment updates, and system messages will appear here.
            </small>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="card-footer border-0 d-flex justify-content-between
                align-items-center flex-wrap gap-2 py-3 px-4"
         style="background:#fafafa">
        <small class="text-muted">
            Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }}
            of {{ $notifications->total() }}
        </small>
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.mark-read-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id  = this.dataset.id;
        const url = this.dataset.url;
        const btn = this;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Remove unread styling
                const card = document.getElementById('notif-' + id);
                card.classList.remove('unread');

                // Replace button with "read" text
                btn.outerHTML = `<span style="font-size:.67rem;color:#9ca3af">
                    <i class="fas fa-check-double me-1"></i>Just now
                </span>`;

                // Update badge count
                const badge = document.querySelector('.badge.bg-primary.rounded-pill');
                if (badge) {
                    const cur = parseInt(badge.textContent);
                    if (cur <= 1) badge.remove();
                    else badge.textContent = (cur - 1) + ' new';
                }
            }
        })
        .catch(console.error);
    });
});
</script>
@endpush
