@extends('doctor.layouts.master')

@section('title', 'Edit Schedule')
@section('page-title', 'Edit Schedule')

@push('styles')
<style>
.form-card { background:#fff; border-radius:16px; padding:1.6rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }
.fc-title { font-size:.84rem; font-weight:700; color:#1a1a1a;
    padding-bottom:.65rem; border-bottom:1px solid #f0f3f8;
    margin-bottom:1.1rem; display:flex; align-items:center; gap:.4rem; }
.fc-title i { color:#0d6efd; }
.lbl { display:block; font-size:.74rem; font-weight:700; color:#555;
    margin-bottom:.3rem; text-transform:uppercase; letter-spacing:.04em; }
.tip { font-size:.67rem; color:#94a3b8; margin-top:.25rem; }

.wp-option { border:1.5px solid #f0f3f8; border-radius:12px;
    padding:.65rem .9rem .65rem 2.4rem; cursor:pointer;
    margin-bottom:.5rem; transition:all .15s; position:relative; }
.wp-option:hover { border-color:#b8d0ff; background:#f8faff; }
.wp-option.selected { border-color:#0d6efd; background:#f0f5ff; }
.wp-option .form-check-input { position:absolute; left:.75rem; top:50%;
    transform:translateY(-50%); }
.wp-option-name { font-size:.83rem; font-weight:700; color:#1a1a1a;
    margin:0 0 .1rem; }
.wp-option-sub  { font-size:.68rem; color:#888; margin:0; }

.day-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:.4rem; }
.day-btn { border:1.5px solid #e8edf5; border-radius:10px;
    padding:.5rem .2rem; text-align:center; cursor:pointer;
    transition:all .15s; background:#fff; }
.day-btn:hover { border-color:#b8d0ff; background:#f8faff; }
.day-btn.active { border-color:#0d6efd; background:#0d6efd; color:#fff; }
.day-btn .day-short { font-size:.72rem; font-weight:800; display:block; }
.day-btn .day-full  { font-size:.58rem; opacity:.7; display:block; }

/* ── Toggle ── */
.toggle-sw { position:relative; width:44px; height:24px; }
.toggle-sw input { opacity:0; width:0; height:0; position:absolute; }
.toggle-sl { position:absolute; cursor:pointer; inset:0;
    background:#cbd5e1; border-radius:34px; transition:.3s; }
.toggle-sl::before { content:''; position:absolute; height:18px;
    width:18px; left:3px; bottom:3px; background:#fff;
    border-radius:50%; transition:.3s; }
input:checked + .toggle-sl { background:#198754; }
input:checked + .toggle-sl::before { transform:translateX(20px); }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav style="font-size:.78rem;margin-bottom:.8rem">
    <a href="{{ route('doctor.dashboard') }}"
       style="color:#0d6efd;text-decoration:none">Dashboard</a>
    <span class="mx-1 text-muted">/</span>
    <a href="{{ route('doctor.schedule.index') }}"
       style="color:#0d6efd;text-decoration:none">Schedule</a>
    <span class="mx-1 text-muted">/</span>
    <span class="text-muted">Edit</span>
</nav>

{{-- Current Info Banner --}}
<div style="background:linear-gradient(135deg,#0d6efd,#6f42c1);
     border-radius:14px;padding:1rem 1.4rem;color:#fff;
     display:flex;align-items:center;gap:1rem;
     margin-bottom:1.2rem;flex-wrap:wrap">
    <div>
        <div style="font-size:.68rem;opacity:.75;font-weight:600;
             text-transform:uppercase;letter-spacing:.05em">
            Editing Schedule
        </div>
        <div style="font-weight:800;font-size:.98rem;margin:.15rem 0 .2rem">
            {{ ucfirst($schedule->day_of_week) }}s
            &nbsp;•&nbsp;
            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
            –
            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
        </div>
        <div style="font-size:.76rem;opacity:.8">
            Max {{ $schedule->max_appointments }} appointments
            &nbsp;•&nbsp;
            LKR {{ number_format($schedule->consultation_fee ?? 0, 2) }}
        </div>
    </div>
    <div class="ms-auto">
        <span style="background:{{ $schedule->is_active ? 'rgba(25,135,84,.35)':'rgba(255,255,255,.15)' }};
            border-radius:20px;padding:.3rem .9rem;
            font-size:.75rem;font-weight:700;border:1px solid rgba(255,255,255,.3)">
            <i class="fas fa-{{ $schedule->is_active ? 'check-circle':'pause-circle' }} me-1"></i>
            {{ $schedule->is_active ? 'Active':'Inactive' }}
        </span>
    </div>
</div>

<form method="POST"
      action="{{ route('doctor.schedule.update', $schedule->id) }}">
@csrf
@method('PUT')

<div class="row g-3">

    {{-- ── LEFT ── --}}
    <div class="col-lg-8">

        {{-- Day Selection --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-calendar-week"></i>Day of Week *
            </div>

            @php
                $selectedDay = old('day_of_week', $schedule->day_of_week);
                $dayMap = [
                    'monday'    => ['Mon','Monday'],
                    'tuesday'   => ['Tue','Tuesday'],
                    'wednesday' => ['Wed','Wednesday'],
                    'thursday'  => ['Thu','Thursday'],
                    'friday'    => ['Fri','Friday'],
                    'saturday'  => ['Sat','Saturday'],
                    'sunday'    => ['Sun','Sunday'],
                ];
            @endphp

            <div class="day-grid">
                @foreach($dayMap as $val => $labels)
                <div class="day-btn {{ $selectedDay === $val ? 'active':'' }}"
                     onclick="selectDay('{{ $val }}', this)">
                    <span class="day-short">{{ $labels[0] }}</span>
                    <span class="day-full">{{ $labels[1] }}</span>
                </div>
                @endforeach
            </div>

            <input type="hidden" name="day_of_week" id="dayInput"
                   value="{{ old('day_of_week', $schedule->day_of_week) }}">

            @error('day_of_week')
            <div class="text-danger mt-2" style="font-size:.75rem">
                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

        {{-- Time --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-clock"></i>Session Time *
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="lbl">Start Time *</label>
                    <input type="time" name="start_time"
                           class="form-control @error('start_time') is-invalid @enderror"
                           value="{{ old('start_time', $schedule->start_time) }}">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="lbl">End Time *</label>
                    <input type="time" name="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old('end_time', $schedule->end_time) }}">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="durationPreview" style="display:none;margin-top:.75rem;
                 padding:.5rem .8rem;background:#f0f5ff;border-radius:8px;
                 font-size:.78rem;color:#0d6efd;font-weight:600">
                <i class="fas fa-hourglass-half me-1"></i>
                Session duration: <span id="durationText"></span>
            </div>
        </div>

        {{-- Capacity & Fee --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-users"></i>Capacity & Fee
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="lbl">Max Appointments *</label>
                    <input type="number" name="max_appointments"
                           class="form-control @error('max_appointments') is-invalid @enderror"
                           value="{{ old('max_appointments', $schedule->max_appointments) }}"
                           min="1" max="100">
                    @error('max_appointments')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="lbl">Consultation Fee (LKR)</label>
                    <div class="input-group">
                        <span class="input-group-text"
                              style="font-size:.78rem;font-weight:600">LKR</span>
                        <input type="number" name="consultation_fee"
                               class="form-control @error('consultation_fee') is-invalid @enderror"
                               value="{{ old('consultation_fee', $schedule->consultation_fee) }}"
                               step="0.01" min="0">
                    </div>
                    @error('consultation_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-toggle-on"></i>Schedule Status
            </div>
            <div class="d-flex align-items-center gap-3">
                <label class="toggle-sw">
                    <input type="checkbox" id="isActiveCheck"
                           {{ old('is_active', $schedule->is_active) ? 'checked':'' }}
                           onchange="updateActiveInput(this)">
                    <span class="toggle-sl"></span>
                </label>
                <div>
                    <div id="statusLabel"
                         style="font-size:.84rem;font-weight:700;
                                color:{{ $schedule->is_active ? '#198754':'#aaa' }}">
                        {{ $schedule->is_active ? 'Active':'Inactive' }}
                    </div>
                    <div style="font-size:.71rem;color:#888">
                        Inactive schedules won't accept new bookings
                    </div>
                </div>
            </div>
            <input type="hidden" name="is_active" id="isActiveInput"
                   value="{{ old('is_active', $schedule->is_active ? 1 : 0) }}">
        </div>

    </div>

    {{-- ── RIGHT ── --}}
    <div class="col-lg-4">

        {{-- Workplace --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-hospital"></i>Workplace
            </div>

            @if($workplaces->count() > 0)
                @foreach($workplaces as $wp)
                @php
                    $isSelected = old('workplace_id', $schedule->workplace_id) == $wp->workplace_id;
                @endphp
                <div class="wp-option {{ $isSelected ? 'selected':'' }}"
                     onclick="selectWorkplace({{ $wp->workplace_id }}, '{{ $wp->workplace_type }}', this)">
                    <input class="form-check-input" type="radio"
                           name="_wp_radio" id="wp{{ $wp->id }}"
                           {{ $isSelected ? 'checked':'' }}>
                    <p class="wp-option-name">{{ $wp->name }}</p>
                    <p class="wp-option-sub">
                        <i class="fas fa-{{ $wp->workplace_type === 'hospital' ? 'hospital':'clinic-medical' }}
                           me-1"></i>
                        {{ ucfirst($wp->workplace_type) }}
                    </p>
                </div>
                @endforeach
            @endif

            @php $noWp = !old('workplace_id', $schedule->workplace_id); @endphp
            <div class="wp-option {{ $noWp ? 'selected':'' }}"
                 onclick="selectWorkplace('', 'private', this)">
                <input class="form-check-input" type="radio"
                       name="_wp_radio" id="wpPrivate"
                       {{ $noWp ? 'checked':'' }}>
                <p class="wp-option-name">Private Clinic</p>
                <p class="wp-option-sub">
                    <i class="fas fa-user-md me-1"></i>Independent practice
                </p>
            </div>

            <input type="hidden" name="workplace_id"
                   id="hiddenWpId"
                   value="{{ old('workplace_id', $schedule->workplace_id ?? '') }}">
            <input type="hidden" name="workplace_type"
                   id="hiddenWpType"
                   value="{{ old('workplace_type', $schedule->workplace_type ?? 'private') }}">
        </div>

        {{-- Submit --}}
        <div class="form-card">
            <button type="submit" class="btn btn-primary w-100 mb-2">
                <i class="fas fa-save me-2"></i>Update Schedule
            </button>
            <a href="{{ route('doctor.schedule.index') }}"
               class="btn btn-outline-secondary w-100 mb-2">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <hr style="border-color:#f0f3f8">
            <button type="button"
                    onclick="deleteSchedule({{ $schedule->id }})"
                    class="btn btn-outline-danger w-100"
                    style="font-size:.78rem">
                <i class="fas fa-trash me-1"></i>Delete Schedule
            </button>
        </div>

    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
// ── Day Selection ──────────────────────────────────
function selectDay(val, el) {
    document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('dayInput').value = val;
}

// ── Workplace Selection ────────────────────────────
function selectWorkplace(id, type, el) {
    document.querySelectorAll('.wp-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('hiddenWpId').value   = id;
    document.getElementById('hiddenWpType').value = type;
    const radio = el.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
}

// ── Active Toggle ──────────────────────────────────
function updateActiveInput(el) {
    const val = el.checked ? 1 : 0;
    document.getElementById('isActiveInput').value = val;
    const lbl = document.getElementById('statusLabel');
    lbl.textContent = el.checked ? 'Active' : 'Inactive';
    lbl.style.color = el.checked ? '#198754' : '#aaa';
}

// ── Duration Preview ───────────────────────────────
function calcDuration() {
    const s = document.querySelector('[name="start_time"]').value;
    const e = document.querySelector('[name="end_time"]').value;
    if (!s || !e) {
        document.getElementById('durationPreview').style.display = 'none';
        return;
    }
    const [sh, sm] = s.split(':').map(Number);
    const [eh, em] = e.split(':').map(Number);
    const diff = (eh * 60 + em) - (sh * 60 + sm);
    if (diff <= 0) {
        document.getElementById('durationPreview').style.display = 'none';
        return;
    }
    const hrs = Math.floor(diff / 60);
    const min = diff % 60;
    const txt = hrs > 0
        ? `${hrs} hr${hrs > 1 ? 's':''}${min > 0 ? ` ${min} min`:''}`
        : `${min} min`;
    document.getElementById('durationText').textContent = txt;
    document.getElementById('durationPreview').style.display = 'block';
}

document.querySelector('[name="start_time"]').addEventListener('change', calcDuration);
document.querySelector('[name="end_time"]').addEventListener('change', calcDuration);
calcDuration(); // init

// ── Delete ─────────────────────────────────────────
function deleteSchedule(id) {
    if (!confirm('Delete this schedule? This cannot be undone.')) return;
    fetch(`/doctor/schedule/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            window.location.href = '{{ route("doctor.schedule.index") }}';
        } else {
            alert(d.message || 'Cannot delete this schedule.');
        }
    });
}
</script>
@endpush
