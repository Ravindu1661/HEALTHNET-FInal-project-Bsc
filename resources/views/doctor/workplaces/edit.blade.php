@extends('doctor.layouts.master')

@section('title', 'Edit Workplace')
@section('page-title', 'Edit Workplace')

@push('styles')
<style>
/* ══════════════════════════════════════
   EDIT WORKPLACE
══════════════════════════════════════ */
.edit-wrap { max-width: 700px; margin: 0 auto; padding: 1.5rem 1rem; }

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, #fd7e14, #e85d04);
    border-radius: 16px; padding: 1.4rem 1.5rem;
    color: #fff; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.ph-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.ph-title { font-size: 1.05rem; font-weight: 800; }
.ph-sub   { font-size: .78rem; opacity: .82; margin-top: .18rem; }

/* ── Form Card ── */
.form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1.5px solid #f0f3f8;
    padding: 1.4rem;
    margin-bottom: 1.2rem;
}
.form-sec-title {
    font-size: .82rem; font-weight: 700; color: #1a1a1a;
    padding-bottom: .65rem; border-bottom: 1px solid #f0f3f8;
    margin-bottom: 1.1rem;
    display: flex; align-items: center; gap: .4rem;
}
.form-sec-title i { color: #0d6efd; }

/* ── Workplace Info Block (read-only) ── */
.wp-info-block {
    display: flex; align-items: flex-start; gap: 1rem;
    background: #f8f9fb;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.1rem;
}
.wp-info-logo {
    width: 56px; height: 56px; border-radius: 14px;
    background: #e8f0fe;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #0d6efd;
    overflow: hidden; flex-shrink: 0;
    border: 2px solid #e8f0fe;
}
.wp-info-logo img {
    width: 100%; height: 100%;
    object-fit: cover; border-radius: 12px;
}
.wp-info-name { font-size: .92rem; font-weight: 700; color: #1a1a1a; }
.wp-info-meta {
    font-size: .73rem; color: #94a3b8;
    display: flex; align-items: center; gap: .3rem;
    margin-top: .25rem;
}
.wp-info-meta i { font-size: .68rem; }

/* Badges */
.wp-badge {
    display: inline-flex; align-items: center; gap: .2rem;
    padding: .17rem .55rem; border-radius: 20px;
    font-size: .65rem; font-weight: 700;
    white-space: nowrap; margin: .1rem .04rem;
}
.badge-hospital        { background: #e8f4fd; color: #1a6fa8; }
.badge-medical_centre  { background: #e8f8f0; color: #1a7a4a; }
.badge-pending         { background: #fff3cd; color: #856404; }

/* Readonly Notice */
.readonly-notice {
    background: #f8f9fb;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: .65rem .9rem;
    font-size: .74rem; color: #64748b;
    display: flex; align-items: flex-start; gap: .5rem;
    margin-top: .8rem; line-height: 1.5;
}
.readonly-notice i { color: #94a3b8; flex-shrink: 0; margin-top: .1rem; }

/* ── Employment Type Cards ── */
.emp-cards { display: flex; gap: .7rem; flex-wrap: wrap; }
.emp-card {
    flex: 1; min-width: 130px;
    border: 2px solid #e2e8f0; border-radius: 14px;
    padding: 1rem .6rem; text-align: center;
    cursor: pointer; transition: all .2s;
    position: relative;
}
.emp-card:hover    { border-color: #0d6efd; background: #f8faff; }
.emp-card.selected { border-color: #0d6efd; background: #f0f5ff; }
.emp-check {
    position: absolute; top: .5rem; right: .5rem;
    color: #0d6efd; font-size: .82rem; display: none;
}
.emp-card.selected .emp-check { display: block; }
.emp-ico { font-size: 1.4rem; margin-bottom: .3rem; }
.emp-lbl { font-size: .78rem; font-weight: 700; color: #1a1a1a; }
.emp-sub { font-size: .67rem; color: #94a3b8; margin-top: .15rem; }
input.emp-radio { display: none; }

/* ── Change Notice ── */
.change-notice {
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 10px;
    padding: .65rem .9rem;
    font-size: .74rem; color: #92400e;
    display: flex; align-items: flex-start; gap: .5rem;
    margin-bottom: 1.2rem; line-height: 1.5;
}
.change-notice i { flex-shrink: 0; margin-top: .1rem; }

/* ── Current Employment Display ── */
.current-emp {
    display: flex; align-items: center; gap: .6rem;
    background: #f0f5ff;
    border: 1.5px solid #0d6efd22;
    border-radius: 10px;
    padding: .6rem .85rem;
    margin-bottom: .85rem;
    font-size: .78rem; font-weight: 600; color: #1a3fa8;
}
.current-emp i { color: #0d6efd; }
.current-emp span.lbl { color: #94a3b8; font-weight: 500; margin-right: .3rem; }

@media (max-width: 576px) {
    .emp-cards { gap: .4rem; }
    .wp-info-block { flex-direction: column; }
}
</style>
@endpush

@section('content')
<div class="edit-wrap">

    {{-- ══ Page Header ══ --}}
    <div class="page-header">
        <div class="ph-icon"><i class="fas fa-edit"></i></div>
        <div>
            <div class="ph-title">Edit Workplace</div>
            <div class="ph-sub">
                Only the employment type can be changed
                while the request is still pending.
            </div>
        </div>
        <a href="{{ route('doctor.workplaces.index') }}"
           class="btn btn-sm ms-auto"
           style="background:rgba(255,255,255,.2);color:#fff;
                  border:1.5px solid rgba(255,255,255,.35)">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    {{-- ══ Alerts ══ --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"
         style="border-radius:12px;font-size:.82rem" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show"
         style="border-radius:12px;font-size:.8rem" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ══ Warning Notice ══ --}}
    <div class="change-notice">
        <i class="fas fa-info-circle"></i>
        <span>
            The <strong>workplace</strong> and its <strong>type</strong> cannot be changed
            after submission. If you need a different workplace, please remove this request
            from the list and submit a new one.
        </span>
    </div>

    <form action="{{ route('doctor.workplaces.update', $workplace->id) }}"
          method="POST" id="editForm">
        @csrf
        @method('PUT')

        {{-- ══════════════════════════════════════
             WORKPLACE INFO (Read-only)
        ══════════════════════════════════════ --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-hospital-alt"></i>
                Workplace
                <span style="margin-left:auto;font-size:.68rem;
                             color:#94a3b8;font-weight:400">
                    Read-only
                </span>
            </div>

            @php
                $isHosp   = $workplace->workplace_type === 'hospital';
                $typeIcon = $isHosp ? 'fa-hospital' : 'fa-clinic-medical';
                $typeLbl  = $isHosp ? 'Hospital' : 'Medical Centre';
            @endphp

            <div class="wp-info-block">

                {{-- Logo --}}
                <div class="wp-info-logo">
                    @if($place && $place->profile_image)
                        <img src="{{ asset('storage/'.$place->profile_image) }}"
                             alt="{{ $place->name ?? '' }}"
                             onerror="this.parentElement.innerHTML=
                                 '<i class=\'fas {{ $typeIcon }}\'></i>'">
                    @else
                        <i class="fas {{ $typeIcon }}"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-grow-1">
                    <div class="wp-info-name">
                        {{ $place->name ?? 'Workplace #'.$workplace->workplace_id }}
                    </div>

                    @if($place && $place->city)
                    <div class="wp-info-meta">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $place->city }}
                        @if($place->address)
                            — {{ Str::limit($place->address, 45) }}
                        @endif
                    </div>
                    @endif

                    @if($place && $place->phone)
                    <div class="wp-info-meta">
                        <i class="fas fa-phone"></i>
                        {{ $place->phone }}
                    </div>
                    @endif

                    <div class="mt-2">
                        {{-- Workplace Type --}}
                        <span class="wp-badge badge-{{ $workplace->workplace_type }}">
                            <i class="fas {{ $typeIcon }}"></i>
                            {{ $typeLbl }}
                        </span>
                        {{-- Status --}}
                        <span class="wp-badge badge-pending">
                            <i class="fas fa-clock"></i>
                            Pending Review
                        </span>
                    </div>
                </div>

            </div>

            {{-- Readonly notice --}}
            <div class="readonly-notice">
                <i class="fas fa-lock"></i>
                <span>
                    Workplace details are locked after submission.
                    Only the employment type below can be updated.
                </span>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             EMPLOYMENT TYPE
        ══════════════════════════════════════ --}}
        <div class="form-card">
            <div class="form-sec-title">
                <i class="fas fa-briefcase"></i>
                Employment Type
                <span style="margin-left:auto;font-size:.68rem;
                             color:#94a3b8;font-weight:400">
                    Select the appropriate type
                </span>
            </div>

            {{-- Current value display --}}
            <div class="current-emp">
                <i class="fas fa-briefcase"></i>
                <span class="lbl">Current:</span>
                {{ ucfirst($workplace->employment_type) }}
                <i class="fas fa-arrow-right mx-1" style="font-size:.65rem;color:#94a3b8"></i>
                <span style="color:#555;font-weight:500">Select new below</span>
            </div>

            <div class="emp-cards">
                @foreach([
                    ['permanent', 'fa-id-badge',       '#0d6efd', 'Permanent', 'Full-time employee'],
                    ['temporary', 'fa-hourglass-half',  '#fd7e14', 'Temporary', 'Fixed-term contract'],
                    ['visiting',  'fa-car-side',        '#6f42c1', 'Visiting',  'Regular visit basis'],
                ] as [$val, $ico, $clr, $lbl, $sub])
                @php
                    $isSel = old('employment_type', $workplace->employment_type) === $val;
                @endphp
                <label class="emp-card {{ $isSel ? 'selected' : '' }}"
                       id="empCard-{{ $val }}">
                    <input type="radio"
                           name="employment_type"
                           value="{{ $val }}"
                           class="emp-radio"
                           {{ $isSel ? 'checked' : '' }}>
                    <i class="fas fa-check-circle emp-check"></i>
                    <div class="emp-ico" style="color:{{ $clr }}">
                        <i class="fas {{ $ico }}"></i>
                    </div>
                    <div class="emp-lbl">{{ $lbl }}</div>
                    <div class="emp-sub">{{ $sub }}</div>
                </label>
                @endforeach
            </div>

            @error('employment_type')
            <div class="text-danger mt-2" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- ══ Submission Info ══ --}}
        <div class="form-card" style="padding:1rem 1.2rem">
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div style="font-size:.68rem;color:#94a3b8;font-weight:600;
                                text-transform:uppercase;letter-spacing:.04em">
                        Request ID
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:#1a1a1a;margin-top:.2rem">
                        #{{ $workplace->id }}
                    </div>
                </div>
                <div class="col-4">
                    <div style="font-size:.68rem;color:#94a3b8;font-weight:600;
                                text-transform:uppercase;letter-spacing:.04em">
                        Submitted
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:#1a1a1a;margin-top:.2rem">
                        {{ \Carbon\Carbon::parse($workplace->created_at)->format('d M Y') }}
                    </div>
                </div>
                <div class="col-4">
                    <div style="font-size:.68rem;color:#94a3b8;font-weight:600;
                                text-transform:uppercase;letter-spacing:.04em">
                        Last Updated
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:#1a1a1a;margin-top:.2rem">
                        {{ \Carbon\Carbon::parse($workplace->updated_at)->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ Actions ══ --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            {{-- Delete shortcut --}}
            <button type="button"
                    class="btn btn-outline-danger btn-sm"
                    id="btnDeleteShortcut">
                <i class="fas fa-trash me-1"></i>Remove This Request
            </button>

            <div class="d-flex gap-2">
                <a href="{{ route('doctor.workplaces.index') }}"
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-warning btn-sm"
                        id="submitBtn">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>

        </div>

    </form>
</div>

{{-- ══════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content"
             style="border-radius:18px;border:none;
                    box-shadow:0 20px 60px rgba(0,0,0,.15)">
            <div class="modal-body text-center p-4">
                <div style="width:64px;height:64px;border-radius:50%;
                            background:#fef2f2;
                            display:flex;align-items:center;justify-content:center;
                            font-size:1.8rem;color:#dc3545;margin:0 auto 1rem">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h6 class="fw-bold mb-1" style="font-size:.95rem">
                    Remove This Request?
                </h6>
                <p class="text-muted mb-3" style="font-size:.78rem">
                    Are you sure you want to remove
                    <strong>{{ $place->name ?? 'this workplace' }}</strong>?
                    This action cannot be undone.
                </p>
                <form id="deleteForm"
                      action="{{ route('doctor.workplaces.destroy', $workplace->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button"
                                class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i>Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Employment Type Cards ─────────────────────────
    document.querySelectorAll('.emp-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('.emp-card')
                .forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('.emp-radio').checked = true;
        });
    });

    // ── Delete Shortcut ──────────────────────────────
    const deleteModal = new bootstrap.Modal(
        document.getElementById('deleteModal')
    );
    document.getElementById('btnDeleteShortcut')
        .addEventListener('click', function () {
            deleteModal.show();
        });

});
</script>
@endpush
