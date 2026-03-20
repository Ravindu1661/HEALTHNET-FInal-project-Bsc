@extends('admin.layouts.master')

@section('title', 'Announcement — ' . $announcement->title)
@section('page-title', 'Announcement Detail')

@push('styles')
<style>
:root { --green:#42a649; --navy:#1a3a5c; --border:#e4e8ed; --radius:12px; --muted:#6b7a8d; }
.show-card { background:#fff; border-radius:var(--radius); border:1px solid var(--border); overflow:hidden; margin-bottom:1rem; }
.sc-head { padding:.85rem 1.2rem; border-bottom:1px solid var(--border); background:linear-gradient(to right,rgba(26,58,92,.04),transparent); display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
.sc-title { font-size:.88rem; font-weight:700; color:var(--navy); margin:0; display:flex; align-items:center; gap:.45rem; }
.sc-title i { color:var(--green); }
.sc-body  { padding:1.2rem; }

.type-badge { display:inline-block; padding:.22rem .75rem; border-radius:20px; font-size:.72rem; font-weight:700; }
.tb-health_camp   { background:#dbeafe; color:#1e40af; }
.tb-awareness     { background:#e0f2fe; color:#0369a1; }
.tb-special_offer { background:#fef3c7; color:#92400e; }
.tb-new_service   { background:#d1fae5; color:#065f46; }
.tb-emergency     { background:#fee2e2; color:#991b1b; }
.tb-general       { background:#f3f4f6; color:#374151; }

.status-pill { display:inline-flex; align-items:center; gap:.3rem; padding:.22rem .75rem; border-radius:20px; font-size:.72rem; font-weight:700; }
.sp-active   { background:#d1fae5; color:#065f46; }
.sp-inactive { background:#fee2e2; color:#991b1b; }

.detail-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:.75rem; }
.detail-item { padding:.7rem .8rem; background:#f8f9fb; border-radius:9px; }
.detail-lbl  { font-size:.67rem; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:.2rem; }
.detail-val  { font-size:.85rem; font-weight:600; color:var(--navy); }

.ann-content { font-size:.88rem; color:#4a5568; line-height:1.8; white-space:pre-line; }

.ann-image { width:100%; max-height:300px; object-fit:cover; border-radius:10px; }

/* Action buttons */
.act-row  { display:flex; gap:.6rem; flex-wrap:wrap; }
.btn-act  { padding:.48rem 1rem; border-radius:20px; font-size:.8rem; font-weight:700; cursor:pointer; border:none; font-family:inherit; transition:all .2s; display:inline-flex; align-items:center; gap:.35rem; text-decoration:none; }
.btn-edit { background:var(--navy); color:#fff; }
.btn-edit:hover { background:#12294a; color:#fff; }
.btn-toggle-on  { background:#fef3c7; color:#92400e; border:1.5px solid #fde68a; }
.btn-toggle-off { background:#d1fae5; color:#065f46; border:1.5px solid #6ee7b7; }
.btn-toggle-on:hover, .btn-toggle-off:hover { transform:translateY(-1px); }
.btn-del  { background:#fef2f2; color:#dc2626; border:1.5px solid #fca5a5; }
.btn-del:hover { background:#fee2e2; }
.btn-back { background:#fff; color:var(--muted); border:1.5px solid var(--border); }
.btn-back:hover { border-color:var(--navy); color:var(--navy); }
</style>
@endpush

@section('content')

{{-- Flash --}}
@foreach(['success'=>'success','error'=>'danger'] as $sk=>$sc)
    @if(session($sk))
        <div class="alert alert-{{ $sc }} alert-dismissible fade show mb-3"
             style="border-radius:10px;font-size:.83rem">
            <i class="fas fa-{{ $sc==='success'?'check-circle':'exclamation-circle' }} me-2"></i>
            {{ session($sk) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endforeach

{{-- Header actions --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;">
    <div>
        <h1 style="font-size:1.05rem;font-weight:700;color:var(--navy,#1a3a5c);margin:0;">
            {{ $announcement->title }}
        </h1>
        <div style="display:flex;align-items:center;gap:.5rem;margin-top:.4rem;flex-wrap:wrap;">
            <span class="type-badge tb-{{ $announcement->announcement_type }}">
                {{ ucwords(str_replace('_',' ',$announcement->announcement_type)) }}
            </span>
            <span class="status-pill {{ $announcement->is_active ? 'sp-active' : 'sp-inactive' }}">
                <i class="fas fa-circle" style="font-size:.45rem;"></i>
                {{ $announcement->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="act-row">
        <a href="{{ route('admin.announcements.index') }}" class="btn-act btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn-act btn-edit">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('admin.announcements.toggle', $announcement->id) }}"
              method="POST" style="display:inline;">
            @csrf
            <button type="submit"
                    class="btn-act {{ $announcement->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                <i class="fas fa-{{ $announcement->is_active ? 'pause' : 'play' }}"></i>
                {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        <form action="{{ route('admin.announcements.destroy', $announcement) }}"
              method="POST" style="display:inline;"
              onsubmit="return confirm('Permanently delete this announcement?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-act btn-del">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-3">
<div class="col-lg-8">

    {{-- Image --}}
    @if($announcement->image_path)
    <div class="show-card">
        <div class="sc-body" style="padding:.85rem;">
            <img src="{{ asset('storage/'.$announcement->image_path) }}"
                 alt="{{ $announcement->title }}"
                 class="ann-image"
                 onerror="this.closest('.show-card').style.display='none'">
        </div>
    </div>
    @endif

    {{-- Content --}}
    <div class="show-card">
        <div class="sc-head">
            <h2 class="sc-title"><i class="fas fa-align-left"></i> Content</h2>
        </div>
        <div class="sc-body">
            <p class="ann-content">{{ $announcement->content }}</p>
        </div>
    </div>

</div>
<div class="col-lg-4">

    {{-- Details --}}
    <div class="show-card">
        <div class="sc-head">
            <h2 class="sc-title"><i class="fas fa-info-circle"></i> Details</h2>
        </div>
        <div class="sc-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-lbl">Publisher</div>
                    <div class="detail-val">{{ ucfirst($announcement->publisher_type) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-lbl">Publisher ID</div>
                    <div class="detail-val">#{{ $announcement->publisher_id }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-lbl">Start Date</div>
                    <div class="detail-val">
                        {{ $announcement->start_date
                            ? \Carbon\Carbon::parse($announcement->start_date)->format('d M Y')
                            : '—' }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-lbl">End Date</div>
                    <div class="detail-val">
                        {{ $announcement->end_date
                            ? \Carbon\Carbon::parse($announcement->end_date)->format('d M Y')
                            : '—' }}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-lbl">Created</div>
                    <div class="detail-val">{{ $announcement->created_at->format('d M Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-lbl">Updated</div>
                    <div class="detail-val">{{ $announcement->updated_at->format('d M Y') }}</div>
                </div>
            </div>

            {{-- Active date check --}}
            @php
                $now   = now()->toDateString();
                $start = $announcement->start_date?->toDateString();
                $end   = $announcement->end_date?->toDateString();
                $showing = $announcement->is_active
                    && (!$start || $start <= $now)
                    && (!$end   || $end   >= $now);
            @endphp
            <div style="margin-top:.85rem;padding:.65rem .8rem;border-radius:9px;
                        background:{{ $showing ? '#f0fdf4' : '#fef9e7' }};
                        border-left:3px solid {{ $showing ? '#42a649' : '#f59e0b' }};">
                <div style="font-size:.75rem;font-weight:700;color:{{ $showing ? '#065f46' : '#92400e' }};">
                    <i class="fas fa-{{ $showing ? 'eye' : 'eye-slash' }} me-1"></i>
                    {{ $showing ? 'Currently visible on home page' : 'Not currently visible' }}
                </div>
                @if(!$announcement->is_active)
                    <div style="font-size:.7rem;color:#92400e;margin-top:.2rem;">Inactive status</div>
                @elseif($start && $start > $now)
                    <div style="font-size:.7rem;color:#92400e;margin-top:.2rem;">Starts on {{ \Carbon\Carbon::parse($start)->format('d M Y') }}</div>
                @elseif($end && $end < $now)
                    <div style="font-size:.7rem;color:#92400e;margin-top:.2rem;">Ended on {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</div>
                @endif
            </div>
        </div>
    </div>

</div>
</div>

@endsection
