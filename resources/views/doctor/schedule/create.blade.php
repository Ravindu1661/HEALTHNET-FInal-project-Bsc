@extends('doctor.layouts.master')

@section('title', 'Add Schedule')
@section('page-title', 'Add Schedule')

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
    <span class="text-muted">Add New</span>
</nav>

<form method="POST" action="{{ route('doctor.schedule.store') }}">
@csrf

<div class="row g-3">

    {{-- ── LEFT ── --}}
    <div class="col-lg-8">

        {{-- Day Selection --}}
        <div class="form-card">
            <div class="fc-title">
                <i class="fas fa-calendar-week"></i>Day of Week *
            </div>

            @php
                $selectedDay = old('day_of_week', '');
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
                   value="{{ old('day_of_week', '') }}">

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
                           value="{{ old('start_time') }}">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="lbl">End Time *</label>
                    <input type="time" name="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old('end_time') }}">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Duration preview --}}
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
                           value="{{ old('max_appointments', 10) }}"
                           min="1" max="100">
                    <p class="tip">Maximum bookings allowed per session</p>
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
                               value="{{ old('consultation_fee', $doctor->consultation_fee ?? '') }}"
                               step="0.01" min="0"
                               placeholder="{{ $doctor->consultation_fee ?? '0.00' }}">
                    </div>
                    <p class="tip">Leave blank to use your default fee</p>
                    @error('consultation_fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
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
                <div class="wp-option {{ old('workplace_id') == $wp->workplace_id ? 'selected':'' }}"
                     onclick="selectWorkplace({{ $wp->workplace_id }}, '{{ $wp->workplace_type }}', this)">
                    <input class="form-check-input" type="radio"
                           name="_wp_radio" id="wp{{ $wp->id }}"
                           {{ old('workplace_id') == $wp->workplace_id ? 'checked':'' }}>
                    <p class="wp-option-name">{{ $wp->name }}</p>
                    <p class="wp-option-sub">
                        <i class="fas fa-{{ $wp->workplace_type === 'hospital' ? 'hospital':'clinic-medical' }}
                           me-1"></i>
                        {{ ucfirst($wp->workplace_type) }}
                    </p>
                </div>
                @endforeach
            @endif

            {{-- Private Clinic --}}
            <div class="wp-option {{ !old('workplace_id') ? 'selected':'' }}"
                 onclick="selectWorkplace('', 'private', this)">
                <input class="form-check-input" type="radio"
                       name="_wp_radio" id="wpPrivate"
                       {{ !old('workplace_id') ? 'checked':'' }}>
                <p class="wp-option-name">Private Clinic</p>
                <p class="wp-option-sub">
                    <i class="fas fa-user-md me-1"></i>Independent practice
                </p>
            </div>

            <input type="hidden" name="workplace_id"
                   id="hiddenWpId"
                   value="{{ old('workplace_id', '') }}">
            <input type="hidden" name="workplace_type"
                   id="hiddenWpType"
                   value="{{ old('workplace_type', 'private') }}">
        </div>

        {{-- Submit --}}
        <div class="form-card">
            <button type="submit" id="submitBtn"
                    class="btn btn-primary w-100 mb-2">
                <i class="fas fa-save me-2"></i>Save Schedule
            </button>
            <a href="{{ route('doctor.schedule.index') }}"
               class="btn btn-outline-secondary w-100">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>

        {{-- Info Box --}}
        <div style="background:#f0f5ff;border-radius:12px;padding:1rem;
             border-left:4px solid #0d6efd">
            <div style="font-size:.75rem;font-weight:700;color:#0d6efd;
                 margin-bottom:.5rem">
                <i class="fas fa-info-circle me-1"></i>Schedule Info
            </div>
            <ul style="font-size:.73rem;color:#555;margin:0;padding-left:1rem;
                line-height:1.8">
                <li>Weekly repeating schedule</li>
                <li>Patients book within your time slot</li>
                <li>Toggle active/inactive anytime</li>
                <li>Fee overrides your default fee</li>
            </ul>
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

    // Radio check
    const radio = el.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
}

// ── Duration Preview ───────────────────────────────
function calcDuration() {
    const s = document.querySelector('[name="start_time"]').value;
    const e = document.querySelector('[name="end_time"]').value;
    if (!s || !e) { document.getElementById('durationPreview').style.display = 'none'; return; }

    const [sh, sm] = s.split(':').map(Number);
    const [eh, em] = e.split(':').map(Number);
    const diff = (eh * 60 + em) - (sh * 60 + sm);

    if (diff <= 0) { document.getElementById('durationPreview').style.display = 'none'; return; }

    const hrs = Math.floor(diff / 60);
    const min = diff % 60;
    const txt = hrs > 0
        ? `${hrs} hr${hrs > 1 ? 's' : ''}${min > 0 ? ` ${min} min` : ''}`
        : `${min} min`;

    document.getElementById('durationText').textContent = txt;
    document.getElementById('durationPreview').style.display = 'block';
}

document.querySelector('[name="start_time"]').addEventListener('change', calcDuration);
document.querySelector('[name="end_time"]').addEventListener('change', calcDuration);

// ── Validate before submit ─────────────────────────
document.querySelector('form').addEventListener('submit', function(e) {
    if (!document.getElementById('dayInput').value) {
        e.preventDefault();
        alert('Please select a day of the week.');
    }
});
</script>
@endpush
