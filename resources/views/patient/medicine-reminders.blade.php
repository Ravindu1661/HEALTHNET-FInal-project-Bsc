@include('partials.header')
@php
    // Safety check
    $active   = collect($active ?? []);
    $inactive = collect($inactive ?? []);
@endphp
<style>
:root {
    --mp: #7c3aed;
    --mp-dark: #5b21b6;
    --mp-light: #ede9fe;
    --mp-bg: #f5f3ff;
}

/* ══ HERO ══ */
.mr-hero {
    background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 60%, #8b5cf6 100%);
    padding: 5.5rem 0 0;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.mr-hero::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 48px;
    background: #f0f4f8;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.mr-hero .container { position: relative; z-index: 1; }


/* ══ BODY ══ */
.mr-body { background: #f0f4f8; padding: 2rem 0 3rem; }

/* ══ CARD ══ */
.mr-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem 1.6rem;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    margin-bottom: 1.2rem;
}
.mr-card-title {
    font-size: .88rem;
    font-weight: 700;
    color: var(--mp);
    padding-bottom: .65rem;
    border-bottom: 2px solid var(--mp-light);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* ══ FORM INPUTS ══ */
.mr-label {
    display: block;
    font-size: .73rem;
    font-weight: 700;
    color: #4b5563;
    margin-bottom: .28rem;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.mr-input {
    width: 100%;
    padding: .58rem .85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    font-size: .85rem;
    background: #fafafa;
    transition: border .2s, box-shadow .2s;
}
.mr-input:focus {
    border-color: var(--mp);
    outline: none;
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
    background: #fff;
}
.mr-input.is-error { border-color: #dc2626; }

/* ══ TIME SLOTS ══ */
.time-slots-wrap { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .4rem; }
.time-slot {
    display: flex;
    align-items: center;
    gap: .35rem;
    background: var(--mp-light);
    border-radius: 9px;
    padding: .35rem .5rem .35rem .7rem;
    font-size: .78rem;
    font-weight: 700;
    color: var(--mp-dark);
}
.time-slot input[type="time"] {
    border: none;
    background: transparent;
    font-size: .78rem;
    font-weight: 700;
    color: var(--mp-dark);
    padding: 0;
    outline: none;
    width: 80px;
}
.time-slot .rm-slot {
    background: none;
    border: none;
    color: #a78bfa;
    cursor: pointer;
    font-size: .7rem;
    padding: 0 .15rem;
    line-height: 1;
    transition: color .2s;
}
.time-slot .rm-slot:hover { color: #dc2626; }
.add-slot-btn {
    background: var(--mp-light);
    color: var(--mp);
    border: 1.5px dashed #c4b5fd;
    border-radius: 9px;
    padding: .35rem .75rem;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
}
.add-slot-btn:hover { background: var(--mp); color: #fff; border-style: solid; }

/* ══ SAVE BTN ══ */
.mr-btn {
    background: linear-gradient(135deg, var(--mp), var(--mp-dark));
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: .7rem 1.6rem;
    font-weight: 700;
    font-size: .86rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: all .3s;
    box-shadow: 0 3px 12px rgba(124,58,237,.25);
}
.mr-btn:hover { filter: brightness(1.08); transform: translateY(-1px); }

/* ══ ALERT ══ */
.mr-alert {
    border-radius: 9px;
    padding: .75rem 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    font-size: .82rem;
    font-weight: 500;
}
.mr-alert.success { background: #f0fdf4; color: #166534; border-left: 3px solid #22c55e; }
.mr-alert.error   { background: #fee2e2; color: #991b1b; border-left: 3px solid #ef4444; }

/* ══ REMINDER ITEM ══ */
.reminder-item {
    border: 1.5px solid #f0f0f8;
    border-radius: 14px;
    padding: 1rem 1.1rem;
    margin-bottom: .85rem;
    transition: border-color .2s, box-shadow .2s;
    position: relative;
}
.reminder-item:hover {
    border-color: var(--mp);
    box-shadow: 0 2px 12px rgba(124,58,237,.08);
}
.reminder-item.is-inactive {
    opacity: .58;
    background: #fafafa;
}
.reminder-item.is-inactive:hover { opacity: .75; }

/* ══ TIME PILL ══ */
.time-pill {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 800;
    background: var(--mp-light);
    color: var(--mp-dark);
    margin: .12rem .08rem;
}
.time-pill.is-now {
    background: var(--mp);
    color: #fff;
    animation: pillPulse 1.6s ease-in-out infinite;
}
@keyframes pillPulse {
    0%, 100% { transform: scale(1); }
    50%       { transform: scale(1.07); }
}

/* ══ FREQ BADGE ══ */
.freq-badge {
    display: inline-flex;
    align-items: center;
    gap: .22rem;
    padding: .16rem .55rem;
    border-radius: 20px;
    font-size: .67rem;
    font-weight: 700;
    background: var(--mp-light);
    color: var(--mp-dark);
}

/* ══ STATUS BADGE ══ */
.r-active   { background: #dcfce7; color: #166534; }
.r-inactive { background: #f1f5f9; color: #64748b; }
.r-expired  { background: #fef3c7; color: #92400e; }
.r-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
}

/* ══ SIDEBAR STAT ══ */
.mr-stat {
    background: var(--mp-bg);
    border-radius: 12px;
    padding: .9rem 1rem;
    text-align: center;
    border: 1.5px solid #ddd6fe;
}
.mr-stat-num { font-size: 1.6rem; font-weight: 800; color: var(--mp); line-height: 1; }
.mr-stat-lbl { font-size: .7rem; color: #64748b; margin-top: .2rem; }

/* ══ EMPTY ══ */
.mr-empty {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #c4b5fd;
}
.mr-empty i { font-size: 2.6rem; display: block; margin-bottom: .6rem; }
.mr-empty p { font-size: .82rem; color: #94a3b8; margin: 0; }

/* ══ MODAL ══ */
.mr-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 9990;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.mr-overlay.open { display: flex; }
.mr-modal {
    background: #fff;
    border-radius: 18px;
    padding: 1.6rem 1.7rem;
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 16px 50px rgba(124,58,237,.2);
    animation: modalPop .3s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalPop {
    from { transform: scale(.88); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}
.mr-modal-title {
    font-size: 1rem;
    font-weight: 800;
    color: var(--mp-dark);
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* ══ ALARM PERMISSION BANNER ══ */
.alarm-perm-bar {
    background: linear-gradient(135deg, var(--mp-light), #fff);
    border: 1.5px solid #c4b5fd;
    border-radius: 12px;
    padding: .85rem 1rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    font-size: .78rem;
    margin-bottom: 1.2rem;
}
.alarm-perm-bar .perm-icon {
    font-size: 1.4rem;
    flex-shrink: 0;
}
.perm-enable-btn {
    background: var(--mp);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .45rem 1rem;
    font-size: .75rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: filter .2s;
    margin-left: auto;
    flex-shrink: 0;
}
.perm-enable-btn:hover { filter: brightness(1.1); }
</style>

@php
    $patient = Auth::user()->patient;
    $nowTime = \Carbon\Carbon::now('Asia/Colombo')->format('H:i');
    $totalActive   = $active->count();
    $totalInactive = $inactive->count();
    $totalAll      = $totalActive + $totalInactive;
    $todayCount    = $active->filter(fn($r) =>
        collect($r->times ?? [])->contains(fn($t) => $t >= $nowTime)
    )->count();
@endphp
@php
    use Carbon\Carbon;
    $user    = Auth::user();
    $patient = $user->patient;
    $nowTime = Carbon::now('Asia/Colombo')->format('H:i');
@endphp
<!-- HERO -->
<section class="mr-hero">
    <div class="container pb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('patient.profile') }}"
                   style="color:rgba(255,255,255,.65);font-size:.78rem;text-decoration:none;
                          display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.7rem">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
        </div>

        <div class="d-flex align-items-center gap-3 pb-3 flex-wrap">
            <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,.18);
                        display:flex;align-items:center;justify-content:center;font-size:1.5rem;
                        border:2px solid rgba(255,255,255,.4)">
                💊
            </div>
            <div>
                <h1 style="font-size:1.5rem;font-weight:900;margin:0">Medicine Reminders</h1>
                <div style="font-size:.8rem;opacity:.78;margin-top:.2rem">
                    <i class="fas fa-bell me-1"></i>
                    {{ $active->count() }} active reminder{{ $active->count() != 1 ? 's' : '' }} &bull;
                    <i class="fas fa-clock me-1"></i> {{ Carbon::now('Asia/Colombo')->format('h:i A') }} (Sri Lanka Time)
                </div>
            </div>

            <div class="ms-auto d-flex gap-2">
                <div style="background:rgba(255,255,255,.12);border-radius:12px;padding:.5rem 1rem;
                            text-align:center;border:1px solid rgba(255,255,255,.2)">
                    <div style="font-size:1.3rem;font-weight:900">{{ $active->count() }}</div>
                    <div style="font-size:.65rem;opacity:.8">Active</div>
                </div>
                <div style="background:rgba(255,255,255,.12);border-radius:12px;padding:.5rem 1rem;
                            text-align:center;border:1px solid rgba(255,255,255,.2)">
                    <div style="font-size:1.3rem;font-weight:900">
                        {{ $active->sum(fn($r) => count($r->times ?? [])) }}
                    </div>
                    <div style="font-size:.65rem;opacity:.8">Daily Doses</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section class="mr-body">
    <div class="container">

        {{-- Flash --}}
        @foreach(['success','error'] as $t)
            @if(session($t))
            <div class="mr-alert {{ $t }}">
                <i class="fas fa-{{ $t === 'success' ? 'check-circle' : 'exclamation-circle' }}"
                   style="flex-shrink:0;margin-top:.1rem"></i>
                <span>{{ session($t) }}</span>
            </div>
            @endif
        @endforeach
        @if($errors->any())
        <div class="mr-alert error">
            <i class="fas fa-exclamation-circle" style="flex-shrink:0;margin-top:.1rem"></i>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        {{-- Alarm permission banner (shown if not yet granted) --}}
        <div class="alarm-perm-bar" id="permBar" style="display:none">
            <span class="perm-icon">🔔</span>
            <div>
                <div style="font-weight:700;color:#4c1d95;font-size:.8rem">Enable Browser Alarm Notifications</div>
                <div style="color:#64748b;margin-top:.1rem">
                    Get a sound alarm popup on every page when it's medicine time.
                </div>
            </div>
            <button class="perm-enable-btn" onclick="mrEnablePerm()">
                Enable
            </button>
        </div>

        <div class="row g-3">

            {{-- ══ MAIN ══ --}}
            <div class="col-lg-8">

                {{-- ── ADD REMINDER FORM ── --}}
                <div class="mr-card">
                    <div class="mr-card-title">
                        <i class="fas fa-plus-circle"></i> Add New Reminder
                    </div>

                    <form action="{{ route('patient.medicine-reminders.store') }}"
                          method="POST" id="addForm">
                        @csrf

                        {{-- Medicine name + Dosage --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label class="mr-label">
                                    Medicine Name <span style="color:#dc2626">*</span>
                                </label>
                                <input type="text" name="medicine_name"
                                       class="mr-input {{ $errors->has('medicine_name') ? 'is-error' : '' }}"
                                       value="{{ old('medicine_name') }}"
                                       placeholder="e.g. Metformin">
                            </div>
                            <div class="col-md-5">
                                <label class="mr-label">Dosage</label>
                                <input type="text" name="dosage"
                                       class="mr-input"
                                       value="{{ old('dosage') }}"
                                       placeholder="e.g. 500mg, 1 tablet">
                            </div>
                        </div>

                        {{-- Frequency --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="mr-label">
                                    Frequency <span style="color:#dc2626">*</span>
                                </label>
                                <select name="frequency" class="mr-input" id="freqSel"
                                        onchange="mrAutoTimes(this.value)">
                                    <option value="">Select frequency</option>
                                    @foreach([
                                        'once_daily'       => 'Once Daily',
                                        'twice_daily'      => 'Twice Daily',
                                        'thrice_daily'     => 'Three Times Daily',
                                        'four_times_daily' => 'Four Times Daily',
                                        'custom'           => 'Custom',
                                    ] as $val => $lbl)
                                    <option value="{{ $val }}"
                                        {{ old('frequency') === $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="mr-label">
                                    Start Date <span style="color:#dc2626">*</span>
                                </label>
                                <input type="date" name="start_date" class="mr-input"
                                       value="{{ old('start_date', today()->format('Y-m-d')) }}"
                                       min="{{ today()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="mr-label">End Date</label>
                                <input type="date" name="end_date" class="mr-input"
                                       value="{{ old('end_date') }}">
                            </div>
                        </div>

                        {{-- Times --}}
                        <div class="mb-3">
                            <label class="mr-label">
                                Alarm Times <span style="color:#dc2626">*</span>
                            </label>
                            <div class="time-slots-wrap" id="timeSlotsWrap">
                                {{-- Pre-fill old times on validation fail --}}
                                @if(old('times'))
                                    @foreach(old('times') as $ot)
                                    <div class="time-slot">
                                        <i class="fas fa-clock" style="font-size:.7rem;opacity:.6"></i>
                                        <input type="time" name="times[]" value="{{ $ot }}" required>
                                        <button type="button" class="rm-slot"
                                                onclick="mrRemoveSlot(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="add-slot-btn mt-2" onclick="mrAddSlot()">
                                <i class="fas fa-plus me-1"></i> Add Time
                            </button>
                            <div style="font-size:.68rem;color:#94a3b8;margin-top:.4rem">
                                <i class="fas fa-info-circle me-1"></i>
                                Alarm will appear on every page at these exact times.
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="mr-label">Notes (optional)</label>
                            <textarea name="notes" class="mr-input" rows="2"
                                      placeholder="e.g. Take after meal, with water...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <button type="submit" class="mr-btn">
                                <i class="fas fa-bell"></i> Set Reminder
                            </button>
                            <span style="font-size:.7rem;color:#94a3b8">
                                <i class="fas fa-shield-halved me-1"></i>
                                Notification will be saved to your account
                            </span>
                        </div>
                    </form>
                </div>

                {{-- ── ACTIVE REMINDERS ── --}}
                <div class="mr-card">
                    <div class="mr-card-title">
                        <i class="fas fa-bell"></i> Active Reminders
                        <span style="margin-left:auto;font-size:.72rem;color:#94a3b8;font-weight:500">
                            {{ $totalActive }} reminder{{ $totalActive !== 1 ? 's' : '' }}
                        </span>
                    </div>

                    @forelse($active as $r)
                    @php
                        $isExpired = $r->end_date && $r->end_date->isPast();
                    @endphp
                    <div class="reminder-item {{ $isExpired ? 'is-inactive' : '' }}">
                        <div class="d-flex align-items-start gap-2 flex-wrap">

                            {{-- Icon --}}
                            <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;
                                        background:linear-gradient(135deg,#7c3aed,#5b21b6);
                                        display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-pills" style="color:#fff;font-size:.85rem"></i>
                            </div>

                            {{-- Info --}}
                            <div class="flex-grow-1 min-width-0">
                                <div style="font-weight:800;font-size:.92rem;color:#1a1a1a">
                                    {{ $r->medicine_name }}
                                    @if($r->dosage)
                                    <span style="font-size:.72rem;color:#7c3aed;font-weight:600;
                                                 background:#ede9fe;padding:.1rem .45rem;
                                                 border-radius:20px;margin-left:.3rem">
                                        {{ $r->dosage }}
                                    </span>
                                    @endif
                                </div>

                                {{-- Frequency --}}
                                <div class="mt-1">
                                    <span class="freq-badge">
                                        <i class="fas fa-rotate" style="font-size:.6rem"></i>
                                        {{ $r->frequency_label }}
                                    </span>
                                    @if($isExpired)
                                    <span class="r-badge r-expired ms-1">
                                        <i class="fas fa-clock" style="font-size:.55rem"></i> Expired
                                    </span>
                                    @else
                                    <span class="r-badge r-active ms-1">
                                        <i class="fas fa-circle" style="font-size:.4rem"></i> Active
                                    </span>
                                    @endif
                                </div>

                                {{-- Time pills --}}
                                <div class="mt-2">
                                    @foreach($r->times ?? [] as $t)
                                    <span class="time-pill {{ $t === $nowTime ? 'is-now' : '' }}">
                                        <i class="fas fa-bell" style="font-size:.58rem"></i>
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $t)->format('h:i A') }}
                                        @if($t === $nowTime)
                                        <span style="font-size:.6rem;font-weight:900">NOW!</span>
                                        @endif
                                    </span>
                                    @endforeach
                                </div>

                                {{-- Dates --}}
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:.4rem">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    {{ $r->start_date->format('d M Y') }}
                                    @if($r->end_date)
                                    → {{ $r->end_date->format('d M Y') }}
                                    @else
                                    → <em>Ongoing</em>
                                    @endif
                                </div>

                                @if($r->notes)
                                <div style="font-size:.72rem;color:#64748b;margin-top:.3rem;
                                            font-style:italic">
                                    <i class="fas fa-note-sticky me-1"></i>{{ $r->notes }}
                                </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-1 flex-shrink-0 flex-wrap">
                                {{-- Edit --}}
                                <button onclick="mrOpenEdit({{ $r->id }},
                                    '{{ addslashes($r->medicine_name) }}',
                                    '{{ addslashes($r->dosage ?? '') }}',
                                    '{{ $r->frequency }}',
                                    {{ json_encode($r->times ?? []) }},
                                    '{{ $r->start_date->format('Y-m-d') }}',
                                    '{{ $r->end_date?->format('Y-m-d') ?? '' }}',
                                    '{{ addslashes($r->notes ?? '') }}')"
                                    style="background:#ede9fe;color:#5b21b6;border:none;
                                           border-radius:8px;padding:.38rem .65rem;
                                           font-size:.72rem;font-weight:700;cursor:pointer">
                                    <i class="fas fa-pen"></i>
                                </button>

                                {{-- Pause --}}
                                <form action="{{ route('patient.medicine-reminders.toggle', $r->id) }}"
                                      method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            style="background:#fef3c7;color:#92400e;border:none;
                                                   border-radius:8px;padding:.38rem .65rem;
                                                   font-size:.72rem;font-weight:700;cursor:pointer"
                                            title="Pause reminder">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form action="{{ route('patient.medicine-reminders.destroy', $r->id) }}"
                                      method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete reminder for \'{{ addslashes($r->medicine_name) }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:#fee2e2;color:#991b1b;border:none;
                                                   border-radius:8px;padding:.38rem .65rem;
                                                   font-size:.72rem;font-weight:700;cursor:pointer"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="mr-empty">
                        <i class="fas fa-bell-slash"></i>
                        <p>No active reminders. Add one above to get started.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ── PAUSED / INACTIVE REMINDERS ── --}}
                @if($inactive->count() > 0)
                <div class="mr-card">
                    <div class="mr-card-title" style="color:#64748b;border-bottom-color:#f1f5f9">
                        <i class="fas fa-pause-circle"></i> Paused / Expired Reminders
                        <span style="margin-left:auto;font-size:.72rem;color:#94a3b8;font-weight:500">
                            {{ $totalInactive }}
                        </span>
                    </div>

                    @foreach($inactive as $r)
                    @php
                        $isExpired = $r->end_date && $r->end_date->isPast();
                    @endphp
                    <div class="reminder-item is-inactive">
                        <div class="d-flex align-items-start gap-2 flex-wrap">
                            <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;
                                        background:#e2e8f0;
                                        display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-pills" style="color:#94a3b8;font-size:.85rem"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div style="font-weight:800;font-size:.9rem;color:#64748b">
                                    {{ $r->medicine_name }}
                                    @if($r->dosage)
                                    <span style="font-size:.7rem;color:#94a3b8;background:#f1f5f9;
                                                 padding:.1rem .4rem;border-radius:20px;margin-left:.3rem">
                                        {{ $r->dosage }}
                                    </span>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <span class="freq-badge" style="background:#f1f5f9;color:#64748b">
                                        {{ $r->frequency_label }}
                                    </span>
                                    <span class="r-badge {{ $isExpired ? 'r-expired' : 'r-inactive' }} ms-1">
                                        <i class="fas fa-circle" style="font-size:.4rem"></i>
                                        {{ $isExpired ? 'Expired' : 'Paused' }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    @foreach($r->times ?? [] as $t)
                                    <span class="time-pill"
                                          style="background:#f1f5f9;color:#94a3b8">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $t)->format('h:i A') }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Re-activate + Delete --}}
                            <div class="d-flex gap-1 flex-shrink-0">
                                @if(!$isExpired)
                                <form action="{{ route('patient.medicine-reminders.toggle', $r->id) }}"
                                      method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            style="background:#dcfce7;color:#166534;border:none;
                                                   border-radius:8px;padding:.38rem .65rem;
                                                   font-size:.72rem;font-weight:700;cursor:pointer"
                                            title="Activate">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('patient.medicine-reminders.destroy', $r->id) }}"
                                      method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this reminder?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:#fee2e2;color:#991b1b;border:none;
                                                   border-radius:8px;padding:.38rem .65rem;
                                                   font-size:.72rem;font-weight:700;cursor:pointer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>{{-- end main col --}}

            {{-- ══ SIDEBAR ══ --}}
            <div class="col-lg-4">

                {{-- Stats --}}
                <div class="mr-card">
                    <div class="mr-card-title">
                        <i class="fas fa-chart-pie"></i> Summary
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="mr-stat">
                                <div class="mr-stat-num">{{ $totalActive }}</div>
                                <div class="mr-stat-lbl">Active</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mr-stat">
                                <div class="mr-stat-num">{{ $totalInactive }}</div>
                                <div class="mr-stat-lbl">Paused</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mr-stat">
                                <div class="mr-stat-num">{{ $todayCount }}</div>
                                <div class="mr-stat-lbl">Due Today</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mr-stat">
                                <div class="mr-stat-num">{{ $totalAll }}</div>
                                <div class="mr-stat-lbl">Total Set</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- How it works --}}
                <div class="mr-card">
                    <div class="mr-card-title">
                        <i class="fas fa-circle-info"></i> How Reminders Work
                    </div>
                    @foreach([
                        ['icon' => 'fa-plus-circle',  'color' => '#7c3aed',
                         'text' => 'Set a reminder with medicine name, dosage, frequency, and alarm times.'],
                        ['icon' => 'fa-bell',         'color' => '#2563eb',
                         'text' => 'A notification is saved to your account when the reminder is created.'],
                        ['icon' => 'fa-tower-broadcast', 'color' => '#059669',
                         'text' => 'At the exact alarm time, a popup toast appears on every page with a sound alert.'],
                        ['icon' => 'fa-list-check',   'color' => '#d97706',
                         'text' => 'View all your medication alerts in Notifications → Reminders tab.'],
                    ] as $s)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;
                                padding:.55rem 0;border-bottom:1px solid #f0f4f0;font-size:.78rem;color:#374151">
                        <i class="fas {{ $s['icon'] }}"
                           style="color:{{ $s['color'] }};width:16px;margin-top:.15rem;flex-shrink:0"></i>
                        <span>{{ $s['text'] }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Quick links --}}
                <div class="mr-card">
                    <div class="mr-card-title"><i class="fas fa-link"></i> Quick Links</div>
                    @foreach([
                        ['route' => 'patient.notifications',        'icon' => 'bell',         'label' => 'Notifications'],
                        ['route' => 'patient.appointments.index',   'icon' => 'calendar-check','label' => 'My Appointments'],
                        ['route' => 'patient.health-portfolio',     'icon' => 'heartbeat',    'label' => 'Health Portfolio'],
                        ['route' => 'patient.dashboard',            'icon' => 'home',         'label' => 'Dashboard'],
                    ] as $lnk)
                    <a href="{{ route($lnk['route']) }}"
                       style="display:flex;align-items:center;gap:.6rem;padding:.5rem .2rem;
                              font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;
                              border-bottom:1px solid #f0f4f0;transition:color .2s"
                       onmouseover="this.style.color='#7c3aed'"
                       onmouseout="this.style.color='#374151'">
                        <i class="fas fa-{{ $lnk['icon'] }}" style="color:#7c3aed;width:16px"></i>
                        {{ $lnk['label'] }}
                        <i class="fas fa-chevron-right ms-auto" style="font-size:.62rem;color:#ccc"></i>
                    </a>
                    @endforeach
                </div>

            </div>{{-- end sidebar --}}
        </div>
    </div>
</section>

{{-- ══ EDIT MODAL ══ --}}
<div class="mr-overlay" id="editOverlay" onclick="if(event.target===this)mrCloseEdit()">
    <div class="mr-modal">
        <div class="mr-modal-title">
            <i class="fas fa-pen-to-square" style="color:#7c3aed"></i> Edit Reminder
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-7">
                    <label class="mr-label">Medicine Name <span style="color:#dc2626">*</span></label>
                    <input type="text" name="medicine_name" id="eMedName" class="mr-input" required>
                </div>
                <div class="col-md-5">
                    <label class="mr-label">Dosage</label>
                    <input type="text" name="dosage" id="eDosage" class="mr-input">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="mr-label">Frequency <span style="color:#dc2626">*</span></label>
                    <select name="frequency" id="eFreq" class="mr-input">
                        @foreach([
                            'once_daily'       => 'Once Daily',
                            'twice_daily'      => 'Twice Daily',
                            'thrice_daily'     => 'Three Times Daily',
                            'four_times_daily' => 'Four Times Daily',
                            'custom'           => 'Custom',
                        ] as $val => $lbl)
                        <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mr-label">Start Date <span style="color:#dc2626">*</span></label>
                    <input type="date" name="start_date" id="eStart" class="mr-input" required>
                </div>
                <div class="col-md-3">
                    <label class="mr-label">End Date</label>
                    <input type="date" name="end_date" id="eEnd" class="mr-input">
                </div>
            </div>

            <div class="mb-3">
                <label class="mr-label">Alarm Times <span style="color:#dc2626">*</span></label>
                <div class="time-slots-wrap" id="eTimeSlotsWrap"></div>
                <button type="button" class="add-slot-btn mt-2" onclick="mrAddSlot('eTimeSlotsWrap')">
                    <i class="fas fa-plus me-1"></i> Add Time
                </button>
            </div>

            <div class="mb-4">
                <label class="mr-label">Notes</label>
                <textarea name="notes" id="eNotes" class="mr-input" rows="2"
                          placeholder="e.g. Take after meal..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="mr-btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <button type="button" onclick="mrCloseEdit()"
                        style="background:#f1f5f9;color:#374151;border:none;border-radius:10px;
                               padding:.7rem 1.2rem;font-weight:700;font-size:.85rem;cursor:pointer">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Time slot helpers ─────────────────────────────────────────────
function mrAddSlot(wrapId, value) {
    const wrap = document.getElementById(wrapId || 'timeSlotsWrap');
    const slot = document.createElement('div');
    slot.className = 'time-slot';
    slot.innerHTML = `<i class="fas fa-clock" style="font-size:.7rem;opacity:.6"></i>
        <input type="time" name="times[]" value="${value || ''}" required>
        <button type="button" class="rm-slot" onclick="mrRemoveSlot(this)">
            <i class="fas fa-times"></i>
        </button>`;
    wrap.appendChild(slot);
}

function mrRemoveSlot(btn) {
    const wrap = btn.closest('.time-slots-wrap');
    if (wrap.querySelectorAll('.time-slot').length <= 1) {
        alert('At least one alarm time is required.');
        return;
    }
    btn.closest('.time-slot').remove();
}

// ── Auto-fill times based on frequency ───────────────────────────
const freqDefaults = {
    once_daily:       ['08:00'],
    twice_daily:      ['08:00', '20:00'],
    thrice_daily:     ['08:00', '14:00', '20:00'],
    four_times_daily: ['08:00', '12:00', '16:00', '20:00'],
    custom:           ['08:00'],
};

function mrAutoTimes(freq, wrapId) {
    const wrap = document.getElementById(wrapId || 'timeSlotsWrap');
    wrap.innerHTML = '';
    (freqDefaults[freq] || ['08:00']).forEach(t => mrAddSlot(wrapId || 'timeSlotsWrap', t));
}

// ── Edit modal ───────────────────────────────────────────────────
function mrOpenEdit(id, name, dosage, freq, times, start, end, notes) {
    document.getElementById('editForm').action =
        `{{ url('patient/medicine-reminders') }}/${id}`;
    document.getElementById('eMedName').value = name;
    document.getElementById('eDosage').value  = dosage;
    document.getElementById('eFreq').value    = freq;
    document.getElementById('eStart').value   = start;
    document.getElementById('eEnd').value     = end;
    document.getElementById('eNotes').value   = notes;

    const wrap = document.getElementById('eTimeSlotsWrap');
    wrap.innerHTML = '';
    (times || []).forEach(t => mrAddSlot('eTimeSlotsWrap', t));

    document.getElementById('editOverlay').classList.add('open');
}

function mrCloseEdit() {
    document.getElementById('editOverlay').classList.remove('open');
}

// ── Browser notification permission ──────────────────────────────
function mrEnablePerm() {
    if (!('Notification' in window)) return;
    Notification.requestPermission().then(p => {
        const bar = document.getElementById('permBar');
        if (p === 'granted') {
            bar.innerHTML = '<span style="color:#166534;font-weight:700;font-size:.82rem">' +
                '<i class="fas fa-check-circle me-2"></i>Browser alarm notifications enabled!</span>';
            bar.style.background = '#f0fdf4';
            bar.style.borderColor = '#86efac';
            setTimeout(() => bar.style.display = 'none', 3000);
        }
    });
}

// ── Show permission bar if not granted ───────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if ('Notification' in window && Notification.permission === 'default') {
        document.getElementById('permBar').style.display = 'flex';
    }
});

// ── Auto-dismiss alerts ──────────────────────────────────────────
document.querySelectorAll('.mr-alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity    = '0';
        setTimeout(() => el.remove(), 500);
    }, 5000);
});
</script>

@include('partials.footer')
