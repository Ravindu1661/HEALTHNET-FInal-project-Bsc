@include('partials.header')

<style>
:root{--teal:#00796b;--teal-dark:#004d40;--teal-light:#e0f2f1}

/* ══ HERO ══ */
.hp-hero{background:linear-gradient(135deg,#003d33 0%,#00695c 45%,#00897b 100%);
         padding:6.5rem 0 0;color:#fff;position:relative;overflow:hidden;min-height:440px}
.hp-hero::before{content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse at 80% 40%,rgba(255,255,255,.07) 0%,transparent 55%),
               radial-gradient(ellipse at 10% 80%,rgba(0,0,0,.15) 0%,transparent 50%)}
.hp-hero::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:52px;
    background:#f0f4f8;clip-path:ellipse(55% 100% at 50% 100%)}
.hp-hero .container{position:relative;z-index:1}

/* ══ SCORE RING ══ */
.score-ring-wrap{position:relative;width:155px;height:155px;margin:0 auto}
.score-ring-wrap svg{transform:rotate(-90deg)}
.score-ring-center{position:absolute;inset:0;display:flex;flex-direction:column;
                   align-items:center;justify-content:center}
.score-num{font-size:2.5rem;font-weight:900;line-height:1}
.score-lbl{font-size:.68rem;opacity:.85;font-weight:700;text-transform:uppercase;letter-spacing:.06em}

/* ══ BODY ══ */
.hp-body{background:#f0f4f8;padding:2rem 0 3rem}

/* ══ CARDS ══ */
.hp-card{background:#fff;border-radius:16px;padding:1.5rem 1.6rem;
         box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:1.2rem}
.hp-card-title{font-size:.88rem;font-weight:700;color:var(--teal);
               padding-bottom:.65rem;border-bottom:2px solid var(--teal-light);
               margin-bottom:1.15rem;display:flex;align-items:center;gap:.5rem}

/* ══ FORM ══ */
.hf-label{display:block;font-size:.73rem;font-weight:700;color:#4b5563;margin-bottom:.28rem;
          text-transform:uppercase;letter-spacing:.04em}
.hf-input{width:100%;padding:.58rem .85rem;border:1.5px solid #e2e8f0;border-radius:9px;
          font-size:.85rem;background:#fafafa;transition:all .2s}
.hf-input:focus{border-color:var(--teal);outline:none;
                box-shadow:0 0 0 3px rgba(0,121,107,.08);background:#fff}
.hf-section{background:#f8fafc;border-radius:11px;padding:1rem 1.1rem;margin-bottom:1.1rem;
            border:1px solid #e4eee4}
.hf-section-title{font-size:.76rem;font-weight:800;color:var(--teal);
                  text-transform:uppercase;letter-spacing:.06em;margin-bottom:.85rem;
                  display:flex;align-items:center;gap:.45rem}

/* ══ BMI TRACK ══ */
.bmi-track{height:20px;border-radius:20px;overflow:hidden;position:relative;
           background:linear-gradient(to right,#3b82f6 0%,#22c55e 28%,#f59e0b 58%,#ef4444 82%)}
.bmi-needle{position:absolute;top:-5px;width:5px;height:30px;background:#111;
            border-radius:3px;transform:translateX(-50%);
            box-shadow:0 2px 8px rgba(0,0,0,.35);
            transition:left 1.2s cubic-bezier(.34,1.56,.64,1)}
.bmi-zones{display:flex;justify-content:space-between;font-size:.63rem;color:#888;margin-top:.35rem}

/* ══ VITAL BADGE ══ */
.vital-status{display:inline-flex;align-items:center;gap:.25rem;padding:.18rem .55rem;
              border-radius:20px;font-size:.65rem;font-weight:800;margin-top:.2rem}
.vs-normal {background:#dcfce7;color:#166534}
.vs-warning{background:#fef3c7;color:#92400e}
.vs-danger {background:#fee2e2;color:#991b1b}
.vs-info   {background:#dbeafe;color:#1e40af}

/* ══ SCORE BREAKDOWN ══ */
.sb-row{margin-bottom:.8rem}
.sb-meta{display:flex;justify-content:space-between;font-size:.77rem;
         font-weight:600;color:#374151;margin-bottom:.28rem}
.sb-track{height:10px;background:#e0f2f1;border-radius:20px;overflow:hidden}
.sb-fill{height:100%;border-radius:20px;
         background:linear-gradient(to right,var(--teal-dark),var(--teal));
         transition:width 1.3s ease}

/* ══ REC CARDS ══ */
.rec-card{border-radius:12px;padding:.9rem 1.1rem;margin-bottom:.8rem;
          display:flex;gap:.8rem;font-size:.82rem;border-left:4px solid}
.rec-success{background:#f0fdf4;color:#166534;border-color:#22c55e}
.rec-warning{background:#fffbeb;color:#92400e;border-color:#f59e0b}
.rec-danger {background:#fef2f2;color:#991b1b;border-color:#ef4444}
.rec-info   {background:#eff6ff;color:#1e40af;border-color:#3b82f6}
.rec-card strong{display:block;margin-bottom:.3rem;font-size:.84rem}

/* ══ SEASONAL ══ */
.seasonal-wrap{border-radius:16px;padding:1.3rem 1.4rem;color:#fff;
               position:relative;overflow:hidden;margin-bottom:1.2rem}
.seasonal-wrap::after{content:'';position:absolute;right:-25px;bottom:-25px;
    width:110px;height:110px;border-radius:50%;background:rgba(255,255,255,.1)}

/* ══ DOCTOR CARD ══ */
.doc-item{display:flex;align-items:center;gap:.75rem;padding:.75rem .5rem;
          border-bottom:1px solid #f0f4f0;border-radius:8px;transition:background .2s}
.doc-item:hover{background:#f0fdf4}
.doc-img{width:46px;height:46px;border-radius:50%;object-fit:cover;
         border:2px solid var(--teal-light);flex-shrink:0}

/* ══ TOGGLE ══ */
.toggle-wrap{display:flex;align-items:center;justify-content:space-between;
             padding:.42rem 0;border-bottom:1px solid #f0f0f0;font-size:.82rem;color:#374151}
.toggle-wrap:last-child{border-bottom:none}
.toggle-switch{position:relative;width:40px;height:22px;flex-shrink:0}
.toggle-switch input{opacity:0;width:0;height:0;position:absolute}
.toggle-slider{position:absolute;cursor:pointer;inset:0;background:#cbd5e1;
              border-radius:34px;transition:.3s}
.toggle-slider:before{content:'';position:absolute;height:16px;width:16px;
                     left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
input:checked+.toggle-slider{background:var(--teal)}
input:checked+.toggle-slider:before{transform:translateX(18px)}

/* ══ SAVE BTN ══ */
.hp-save-btn{background:linear-gradient(135deg,var(--teal),var(--teal-dark));color:#fff;
             border:none;border-radius:10px;padding:.75rem 2rem;font-weight:700;font-size:.88rem;
             cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;
             transition:all .3s;box-shadow:0 3px 12px rgba(0,121,107,.25)}
.hp-save-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}

/* ══ ALERT ══ */
.hp-alert{border-radius:9px;padding:.75rem 1rem;margin-bottom:1rem;
          display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;font-weight:500}
.hp-alert.success{background:#dcfce7;color:#166534;border-left:3px solid #22c55e}
.hp-alert.error  {background:#fee2e2;color:#991b1b;border-left:3px solid #ef4444}

/* ══ CHART TABS ══ */
.hp-tab{padding:.45rem 1rem;font-size:.78rem;font-weight:700;color:#888;
        border-bottom:3px solid transparent;cursor:pointer;white-space:nowrap;
        text-decoration:none;transition:all .2s;display:inline-block}
.hp-tab:hover{color:var(--teal)}
.hp-tab.active{color:var(--teal);border-bottom-color:var(--teal)}
</style>

@php
    use Carbon\Carbon;
    $user     = Auth::user();
    $patient  = $user->patient;
    $fullName = trim(($patient->firstname??'').' '.($patient->lastname??'')) ?: strtok($user->email,'@');
    $profileImg = $patient?->profile_image
        ? asset('storage/'.$patient->profile_image)
        : asset('images/default-avatar.png');
    $hd = $healthData;

    // Current date — Sri Lanka timezone
    $today = Carbon::now('Asia/Colombo');

    // Vitals
    $sys = $hd?->blood_pressure_systolic  ?? $latestMetric?->blood_pressure_systolic  ?? null;
    $dia = $hd?->blood_pressure_diastolic ?? $latestMetric?->blood_pressure_diastolic ?? null;
    $hr  = $hd?->heart_rate               ?? $latestMetric?->heart_rate               ?? null;
    $bs  = $hd?->blood_sugar              ?? $latestMetric?->blood_sugar              ?? null;
    $spo = $hd?->oxygen_saturation        ?? null;
    $tmp = $hd?->temperature              ?? $latestMetric?->temperature              ?? null;
    $cho = $hd?->cholesterol_total        ?? null;

    // Vital status helper
    $vStatus = function($type, $val) {
        if($val === null) return ['label'=>'No Data','class'=>'vs-info'];
        return match($type) {
            'bp_sys' => match(true) {
                $val < 90  => ['label'=>'Low BP',       'class'=>'vs-danger'],
                $val < 120 => ['label'=>'Normal',        'class'=>'vs-normal'],
                $val < 130 => ['label'=>'Elevated',      'class'=>'vs-warning'],
                $val < 140 => ['label'=>'High — Stage 1','class'=>'vs-warning'],
                default    => ['label'=>'High — Stage 2','class'=>'vs-danger'],
            },
            'hr' => match(true) {
                $val < 60   => ['label'=>'Low (Bradycardia)', 'class'=>'vs-warning'],
                $val <= 100 => ['label'=>'Normal',             'class'=>'vs-normal'],
                default     => ['label'=>'High (Tachycardia)','class'=>'vs-danger'],
            },
            'bs' => match(true) {
                $val < 70  => ['label'=>'Too Low',      'class'=>'vs-danger'],
                $val < 100 => ['label'=>'Normal',        'class'=>'vs-normal'],
                $val < 126 => ['label'=>'Pre-Diabetic',  'class'=>'vs-warning'],
                default    => ['label'=>'Diabetic Range','class'=>'vs-danger'],
            },
            'spo2' => match(true) {
                $val >= 95 => ['label'=>'Normal',    'class'=>'vs-normal'],
                $val >= 90 => ['label'=>'Low',       'class'=>'vs-warning'],
                default    => ['label'=>'Critical',  'class'=>'vs-danger'],
            },
            'temp' => match(true) {
                $val < 36.1  => ['label'=>'Hypothermia',    'class'=>'vs-danger'],
                $val <= 37.2 => ['label'=>'Normal',          'class'=>'vs-normal'],
                $val <= 38.0 => ['label'=>'Mild Fever',      'class'=>'vs-warning'],
                default      => ['label'=>'Fever',           'class'=>'vs-danger'],
            },
            'chol' => match(true) {
                $val < 200 => ['label'=>'Desirable',       'class'=>'vs-normal'],
                $val < 240 => ['label'=>'Borderline High', 'class'=>'vs-warning'],
                default    => ['label'=>'High',            'class'=>'vs-danger'],
            },
            default => ['label'=>'—','class'=>'vs-info'],
        };
    };
@endphp

{{-- ══ HERO ══ --}}
<section class="hp-hero">
<div class="container pb-5">
<div class="row align-items-center g-4 pt-2">

    {{-- Col 1: Identity --}}
    <div class="col-lg-4">
        <a href="{{ route('patient.profile') }}"
           style="color:rgba(255,255,255,.6);font-size:.78rem;text-decoration:none;
                  display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.8rem">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
        <div class="d-flex align-items-center gap-3 mb-2">
            <img src="{{ $profileImg }}"
                 style="width:62px;height:62px;border-radius:50%;object-fit:cover;
                        border:3px solid rgba(255,255,255,.7)"
                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
            <div>
                <h2 style="font-size:1.35rem;font-weight:900;margin:0">{{ $fullName }}</h2>
                <div style="font-size:.77rem;opacity:.75;margin-top:.2rem">
                    @if($age)<i class="fas fa-birthday-cake me-1"></i>Age {{ $age }} • @endif
                    @if($patient->gender){{ ucfirst($patient->gender) }} • @endif
                    @if($patient->blood_group)
                        <i class="fas fa-tint me-1"></i>{{ $patient->blood_group }}
                    @endif
                </div>
                <div style="font-size:.72rem;opacity:.65;margin-top:.15rem">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $today->format('D, d M Y') }}
                    @if($patient->city) • <i class="fas fa-map-marker-alt me-1"></i>{{ $patient->city }} @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap mt-3">
            @foreach([
                ['n'=>$completedAppointments,'l'=>'Doctor Visits','icon'=>'fa-calendar-check'],
                ['n'=>$labOrders->count(),   'l'=>'Lab Tests',    'icon'=>'fa-flask'],
                ['n'=>$medicalHistory->count(),'l'=>'Conditions', 'icon'=>'fa-notes-medical'],
            ] as $s)
            <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
                        border-radius:11px;padding:.55rem .9rem;text-align:center;flex:1;min-width:70px">
                <i class="fas {{ $s['icon'] }}" style="font-size:.8rem;opacity:.7"></i>
                <div style="font-size:1.25rem;font-weight:900;margin:.1rem 0">{{ $s['n'] }}</div>
                <div style="font-size:.62rem;opacity:.72">{{ $s['l'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Col 2: Score Ring --}}
    <div class="col-lg-4 text-center">
        <div style="font-size:.7rem;font-weight:700;opacity:.7;text-transform:uppercase;
                    letter-spacing:.07em;margin-bottom:.6rem">
            <i class="fas fa-heartbeat me-1" style="color:#a7f3d0"></i>
            Overall Health Score
        </div>
        <div class="score-ring-wrap">
            <svg width="155" height="155" viewBox="0 0 155 155">
                <circle cx="77.5" cy="77.5" r="64" fill="none"
                        stroke="rgba(255,255,255,.12)" stroke-width="14"/>
                <circle cx="77.5" cy="77.5" r="64" fill="none"
                        stroke="{{ $healthScore['color'] }}" stroke-width="14"
                        stroke-linecap="round"
                        stroke-dasharray="{{ round(2*M_PI*64) }}"
                        stroke-dashoffset="{{ round((1-$healthScore['score']/100)*2*M_PI*64) }}"
                        style="transition:stroke-dashoffset 1.5s ease"/>
            </svg>
            <div class="score-ring-center">
                <div class="score-num" style="color:{{ $healthScore['color'] }}">
                    {{ $healthScore['score'] }}
                </div>
                <div class="score-lbl" style="color:{{ $healthScore['color'] }}">
                    {{ $healthScore['level'] }}
                </div>
                <div style="font-size:.6rem;opacity:.6;margin-top:.2rem">/ 100 pts</div>
            </div>
        </div>

        {{-- Score mini detail badges --}}
        <div class="d-flex justify-content-center gap-1 flex-wrap mt-3">
            @php
            $detLabels=[
                'profile'   =>'Profile',
                'bmi'       =>'BMI',
                'bp'        =>'Blood Pressure',
                'sugar'     =>'Blood Sugar',
                'lifestyle' =>'Lifestyle',
                'conditions'=>'Conditions',
            ];
            @endphp
            @foreach($healthScore['details'] as $k=>$det)
            <div style="background:rgba(255,255,255,.11);border-radius:8px;
                        padding:.3rem .6rem;text-align:center;font-size:.66rem">
                <div style="font-weight:900;font-size:.85rem">{{ $det['score'] }}/{{ $det['max'] }}</div>
                <div style="opacity:.72">{{ $detLabels[$k] ?? $det['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Col 3: BMI + Vitals --}}
    <div class="col-lg-4">
        {{-- BMI --}}
        @if($bmi)
        <div style="background:rgba(255,255,255,.12);border-radius:14px;
                    padding:1rem 1.2rem;margin-bottom:.8rem">
            <div style="font-size:.68rem;font-weight:700;opacity:.7;text-transform:uppercase;
                        letter-spacing:.06em;margin-bottom:.45rem">
                Body Mass Index (BMI)
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span style="font-size:2rem;font-weight:900;color:{{ $bmiColor }}">{{ $bmi }}</span>
                <div>
                    <div style="font-weight:800;color:{{ $bmiColor }};font-size:.88rem">
                        {{ $bmiCategory }}
                    </div>
                    <div style="font-size:.7rem;opacity:.72">
                        {{ $weight }} kg • {{ $height }} cm
                        @if($hd?->waist && $hd?->hip)
                        • WHR: {{ round($hd->waist / $hd->hip, 2) }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="bmi-track">
                <div class="bmi-needle" id="bmiNeedle" style="left:{{ $bmiPct }}%"></div>
            </div>
            <div class="bmi-zones">
                <span>Under<br><18.5</span>
                <span>Normal<br>18.5–24.9</span>
                <span>Over<br>25–29.9</span>
                <span>Obese<br>≥30</span>
            </div>
        </div>
        @else
        <div style="background:rgba(255,255,255,.1);border-radius:14px;padding:1.2rem;
                    text-align:center;margin-bottom:.8rem;opacity:.8">
            <i class="fas fa-ruler" style="font-size:1.6rem;display:block;margin-bottom:.4rem"></i>
            <div style="font-size:.8rem;line-height:1.5">
                Enter your <strong>weight & height</strong> below<br>to calculate your BMI
            </div>
        </div>
        @endif

        {{-- Vitals Summary --}}
        <div class="row g-2">
            @foreach([
                ['v'=>$sys?"$sys / $dia mmHg":'—','u'=>'Blood Pressure',
                 'c'=>$sys>=140?'#ef4444':($sys>=130?'#f59e0b':'#22c55e'),
                 's'=>$vStatus('bp_sys',$sys)],
                ['v'=>$hr?"$hr bpm":'—','u'=>'Heart Rate',
                 'c'=>($hr&&($hr>100||$hr<60))?'#f59e0b':'#22c55e',
                 's'=>$vStatus('hr',$hr)],
                ['v'=>$bs?"$bs mg/dL":'—','u'=>'Blood Sugar',
                 'c'=>$bs>=126?'#ef4444':($bs>=100?'#f59e0b':'#22c55e'),
                 's'=>$vStatus('bs',$bs)],
                ['v'=>$spo?"$spo% SpO₂":'—','u'=>'Oxygen Level',
                 'c'=>($spo&&$spo<95)?'#ef4444':'#22c55e',
                 's'=>$vStatus('spo2',$spo)],
            ] as $v)
            <div class="col-6">
                <div style="background:rgba(255,255,255,.12);border-radius:10px;
                            padding:.65rem;text-align:center">
                    <div style="font-size:1rem;font-weight:900;color:{{ $v['c'] }}">
                        {{ $v['v'] }}
                    </div>
                    <div style="font-size:.63rem;opacity:.72;margin:.1rem 0">{{ $v['u'] }}</div>
                    <span style="display:inline-flex;align-items:center;padding:.12rem .45rem;
                          border-radius:20px;font-size:.62rem;font-weight:800;
                          background:rgba(255,255,255,.18);color:#fff">
                        {{ $v['s']['label'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
</div>
</section>

{{-- ══ BODY ══ --}}
<section class="hp-body">
<div class="container">

    @foreach(['success','error'] as $t)
        @if(session($t))
        <div class="hp-alert {{ $t }}">
            <i class="fas fa-{{ $t==='success'?'check-circle':'exclamation-circle' }}"
               style="flex-shrink:0;margin-top:.1rem"></i>
            <span>{{ session($t) }}</span>
        </div>
        @endif
    @endforeach

<div class="row g-3">

{{-- ══ MAIN ══ --}}
<div class="col-lg-8">

    {{-- ── Health Data Entry Form ── --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-clipboard-list"></i> Update Your Health Data
            <span style="margin-left:auto;font-size:.7rem;color:#999;font-weight:500">
                @if($hd)
                    <i class="fas fa-check-circle" style="color:#22c55e"></i>
                    Last saved: {{ $hd->recorded_date->format('d M Y') }}
                @else
                    <i class="fas fa-exclamation-circle" style="color:#f59e0b"></i>
                    Not recorded yet
                @endif
            </span>
        </div>

        <div style="background:#fffbeb;border-left:3px solid #f59e0b;border-radius:8px;
                    padding:.65rem .9rem;margin-bottom:1.1rem;font-size:.78rem;color:#78350f">
            <i class="fas fa-info-circle me-1"></i>
            Fill in as many fields as possible — more data = more accurate health score &
            better specialist recommendations.
        </div>

        <form action="{{ route('patient.health-data.save') }}" method="POST" id="healthForm">
            @csrf
            {{-- Body Measurements --}}
            <div class="hf-section">
                <div class="hf-section-title">
                    <i class="fas fa-weight-scale"></i> Body Measurements
                    <span style="font-size:.68rem;font-weight:500;color:#888;text-transform:none;margin-left:.3rem">
                        — used to calculate BMI & body composition
                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Weight (kg)</label>
                        <input type="number" name="weight" step="0.1" min="20" max="300"
                               class="hf-input" id="inpWeight"
                               value="{{ old('weight',$hd?->weight) }}" placeholder="e.g. 70.5">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Height (cm)</label>
                        <input type="number" name="height" step="0.1" min="50" max="250"
                               class="hf-input" id="inpHeight"
                               value="{{ old('height',$hd?->height) }}" placeholder="e.g. 170">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Waist (cm)</label>
                        <input type="number" name="waist" step="0.1" class="hf-input"
                               value="{{ old('waist',$hd?->waist) }}" placeholder="e.g. 80">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Hip (cm)</label>
                        <input type="number" name="hip" step="0.1" class="hf-input"
                               value="{{ old('hip',$hd?->hip) }}" placeholder="e.g. 95">
                    </div>
                </div>
                {{-- Live BMI Calculator --}}
                <div id="bmiPreview" style="display:none;margin-top:.8rem;padding:.65rem .9rem;
                     background:#f0fdf4;border-radius:9px;border:1.5px solid #a7f3d0;
                     font-size:.82rem;font-weight:600;color:#166534">
                    <i class="fas fa-calculator me-1"></i>
                    Your BMI: <span id="bmiVal">—</span>
                    <span id="bmiCatLbl" style="margin-left:.4rem;padding:.15rem .5rem;
                          border-radius:10px;font-size:.72rem"></span>
                    <span id="bmiAdvice" style="margin-left:.4rem;font-weight:400;font-size:.78rem;
                          color:#555"></span>
                </div>
            </div>

            {{-- Vital Signs --}}
            <div class="hf-section">
                <div class="hf-section-title">
                    <i class="fas fa-heart-pulse"></i> Vital Signs
                    <span style="font-size:.68rem;font-weight:500;color:#888;text-transform:none;margin-left:.3rem">
                        — measures heart & organ health
                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Systolic BP (mmHg)
                            <span style="font-size:.62rem;font-weight:400;text-transform:none">(upper number)</span>
                        </label>
                        <input type="number" name="blood_pressure_systolic" min="60" max="250"
                               class="hf-input"
                               value="{{ old('blood_pressure_systolic',$hd?->blood_pressure_systolic) }}"
                               placeholder="e.g. 120">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 90–119</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Diastolic BP (mmHg)
                            <span style="font-size:.62rem;font-weight:400;text-transform:none">(lower number)</span>
                        </label>
                        <input type="number" name="blood_pressure_diastolic" min="40" max="150"
                               class="hf-input"
                               value="{{ old('blood_pressure_diastolic',$hd?->blood_pressure_diastolic) }}"
                               placeholder="e.g. 80">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 60–79</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Heart Rate (bpm)</label>
                        <input type="number" name="heart_rate" min="30" max="220"
                               class="hf-input"
                               value="{{ old('heart_rate',$hd?->heart_rate) }}"
                               placeholder="e.g. 72">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 60–100</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Body Temperature (°C)</label>
                        <input type="number" name="temperature" step="0.1" min="34" max="42"
                               class="hf-input"
                               value="{{ old('temperature',$hd?->temperature) }}"
                               placeholder="e.g. 36.6">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 36.1–37.2</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Fasting Blood Sugar (mg/dL)</label>
                        <input type="number" name="blood_sugar" step="0.1" class="hf-input"
                               value="{{ old('blood_sugar',$hd?->blood_sugar) }}"
                               placeholder="Before eating">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 70–99</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Post-Meal Sugar (mg/dL)
                            <span style="font-size:.62rem;font-weight:400;text-transform:none">(2 hrs after)</span>
                        </label>
                        <input type="number" name="blood_sugar_pp" step="0.1" class="hf-input"
                               value="{{ old('blood_sugar_pp',$hd?->blood_sugar_pp) }}"
                               placeholder="After eating">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: <140</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Total Cholesterol (mg/dL)</label>
                        <input type="number" name="cholesterol_total" step="0.1" class="hf-input"
                               value="{{ old('cholesterol_total',$hd?->cholesterol_total) }}"
                               placeholder="e.g. 180">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: <200</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="hf-label">Oxygen Saturation (SpO₂ %)</label>
                        <input type="number" name="oxygen_saturation" min="50" max="100"
                               class="hf-input"
                               value="{{ old('oxygen_saturation',$hd?->oxygen_saturation) }}"
                               placeholder="e.g. 98">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Normal: 95–100%</div>
                    </div>
                </div>
            </div>

            {{-- Lifestyle --}}
            <div class="hf-section">
                <div class="hf-section-title">
                    <i class="fas fa-person-running"></i> Lifestyle Habits
                    <span style="font-size:.68rem;font-weight:500;color:#888;text-transform:none;margin-left:.3rem">
                        — directly affects your health score
                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Smoking</label>
                        <select name="smoking_status" class="hf-input">
                            <option value="">Select</option>
                            @foreach(['never'=>'Never smoked','former'=>'Ex-smoker','current'=>'Currently smoking'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('smoking_status',$hd?->smoking_status)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Alcohol Use</label>
                        <select name="alcohol_consumption" class="hf-input">
                            <option value="">Select</option>
                            @foreach(['none'=>'None','occasional'=>'Occasionally','moderate'=>'Moderately','heavy'=>'Heavily (daily)'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('alcohol_consumption',$hd?->alcohol_consumption)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Exercise (per week)</label>
                        <select name="exercise_frequency" class="hf-input">
                            <option value="">Select</option>
                            @foreach(['none'=>'No exercise','1-2/week'=>'1–2 days','3-4/week'=>'3–4 days','5+/week'=>'5+ days (very active)'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('exercise_frequency',$hd?->exercise_frequency)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Sleep Duration (hours/night)</label>
                        <input type="number" name="sleep_hours" min="1" max="24" class="hf-input"
                               value="{{ old('sleep_hours',$hd?->sleep_hours) }}" placeholder="e.g. 7">
                        <div style="font-size:.65rem;color:#94a3b8;margin-top:.2rem">Recommended: 7–9 hrs</div>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Diet Type</label>
                        <select name="diet_type" class="hf-input">
                            <option value="">Select</option>
                            @foreach(['omnivore'=>'Mixed (meat & veg)','vegetarian'=>'Vegetarian','vegan'=>'Vegan','other'=>'Other'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('diet_type',$hd?->diet_type)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label class="hf-label">Stress Level</label>
                        <select name="stress_level" class="hf-input">
                            <option value="">Select</option>
                            @foreach(['low'=>'Low — calm & relaxed','moderate'=>'Moderate — manageable','high'=>'High — often stressed','very_high'=>'Very High — overwhelmed'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('stress_level',$hd?->stress_level)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Medical Conditions --}}
            <div class="hf-section">
                <div class="hf-section-title">
                    <i class="fas fa-notes-medical"></i> Your Medical Conditions
                </div>
                <p style="font-size:.75rem;color:#888;margin-bottom:.75rem">
                    Toggle ON for any condition diagnosed by a doctor
                </p>
                <div class="row g-2">
                    @foreach([
                        ['name'=>'has_diabetes',       'label'=>'Diabetes',            'icon'=>'fa-droplet'],
                        ['name'=>'has_hypertension',   'label'=>'High Blood Pressure', 'icon'=>'fa-heart-pulse'],
                        ['name'=>'has_heart_disease',  'label'=>'Heart Disease',       'icon'=>'fa-heart'],
                        ['name'=>'has_asthma',         'label'=>'Asthma',              'icon'=>'fa-lungs'],
                        ['name'=>'has_kidney_disease', 'label'=>'Kidney Disease',      'icon'=>'fa-shield-virus'],
                        ['name'=>'has_thyroid',        'label'=>'Thyroid Problem',     'icon'=>'fa-virus'],
                    ] as $cond)
                    <div class="col-md-4 col-6">
                        <div class="toggle-wrap">
                            <span style="font-size:.81rem">
                                <i class="fas {{ $cond['icon'] }}" style="color:var(--teal);width:15px;margin-right:.3rem"></i>
                                {{ $cond['label'] }}
                            </span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="{{ $cond['name'] }}" value="1"
                                       {{ old($cond['name'], $hd?->{$cond['name']}) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="hf-label">Other Conditions</label>
                        <textarea name="other_conditions" class="hf-input" rows="2"
                                  placeholder="e.g. Migraine, PCOS, Anemia...">{{ old('other_conditions',$hd?->other_conditions) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="hf-label">Current Medications</label>
                        <textarea name="current_medications" class="hf-input" rows="2"
                                  placeholder="e.g. Metformin 500mg daily...">{{ old('current_medications',$hd?->current_medications) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="hf-label">Known Allergies</label>
                        <input type="text" name="allergies" class="hf-input"
                               value="{{ old('allergies',$hd?->allergies) }}"
                               placeholder="e.g. Penicillin, Pollen, Shellfish">
                    </div>
                </div>
            </div>

            {{-- Family History --}}
            <div class="hf-section">
                <div class="hf-section-title">
                    <i class="fas fa-people-group"></i> Family Medical History
                    <span style="font-size:.68rem;font-weight:500;color:#888;text-transform:none;margin-left:.3rem">
                        — helps identify your genetic risk factors
                    </span>
                </div>
                <p style="font-size:.75rem;color:#888;margin-bottom:.75rem">
                    Toggle ON if a parent or sibling has been diagnosed with any of these conditions
                </p>
                <div class="row g-2">
                    @foreach([
                        ['name'=>'family_diabetes',      'label'=>'Diabetes in family'],
                        ['name'=>'family_heart_disease', 'label'=>'Heart Disease in family'],
                        ['name'=>'family_hypertension',  'label'=>'High Blood Pressure in family'],
                        ['name'=>'family_cancer',        'label'=>'Cancer in family'],
                    ] as $fam)
                    <div class="col-md-3 col-6">
                        <div class="toggle-wrap">
                            <span style="font-size:.81rem">{{ $fam['label'] }}</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="{{ $fam['name'] }}" value="1"
                                       {{ old($fam['name'], $hd?->{$fam['name']}) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Additional Notes --}}
            <div class="mb-3">
                <label class="hf-label">Additional Notes</label>
                <textarea name="notes" class="hf-input" rows="2"
                          placeholder="Anything else your doctor should know...">{{ old('notes',$hd?->notes) }}</textarea>
            </div>

            <div class="d-flex gap-2 align-items-center flex-wrap">
                <button type="submit" class="hp-save-btn" id="saveBtn">
                    <i class="fas fa-save"></i> Save Health Data
                </button>
                <span id="saveSpinner" style="display:none;font-size:.82rem;color:var(--teal)">
                    <i class="fas fa-spinner fa-spin me-1"></i> Saving...
                </span>
                <span style="font-size:.72rem;color:#94a3b8">
                    <i class="fas fa-shield-halved me-1"></i> Your data is private & secure
                </span>
            </div>
        </form>
    </div>

    {{-- ── Vitals Analysis ── --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-stethoscope"></i> Vitals Analysis
        </div>
        <div class="row g-3">
            @php
            $vitalsDetail = [
                [
                    'label'  => 'Blood Pressure',
                    'value'  => $sys ? "$sys / $dia mmHg" : 'No data',
                    'status' => $vStatus('bp_sys', $sys),
                    'normal' => '90–119 / 60–79 mmHg',
                    'icon'   => 'fa-heart-pulse',
                    'color'  => '#ef4444',
                    'explain'=> 'High BP increases risk of stroke, heart attack and kidney damage. Normal is below 120/80 mmHg.',
                ],
                [
                    'label'  => 'Heart Rate',
                    'value'  => $hr ? "$hr bpm" : 'No data',
                    'status' => $vStatus('hr', $hr),
                    'normal' => '60–100 bpm',
                    'icon'   => 'fa-wave-square',
                    'color'  => '#f59e0b',
                    'explain'=> 'Heart rate below 60 or above 100 may indicate an underlying condition. Athletes can have naturally lower rates.',
                ],
                [
                    'label'  => 'Fasting Blood Sugar',
                    'value'  => $bs ? "$bs mg/dL" : 'No data',
                    'status' => $vStatus('bs', $bs),
                    'normal' => '70–99 mg/dL',
                    'icon'   => 'fa-droplet',
                    'color'  => '#3b82f6',
                    'explain'=> '100–125 is pre-diabetic. 126+ may indicate diabetes. Consult an Endocrinologist for confirmation.',
                ],
                [
                    'label'  => 'Body Temperature',
                    'value'  => $tmp ? "$tmp °C" : 'No data',
                    'status' => $vStatus('temp', $tmp),
                    'normal' => '36.1–37.2 °C',
                    'icon'   => 'fa-thermometer-half',
                    'color'  => '#f59e0b',
                    'explain'=> 'Above 38°C is a fever. 37.3–38°C is a mild fever. Paracetamol and rest are usually sufficient.',
                ],
                [
                    'label'  => 'Oxygen Saturation',
                    'value'  => $spo ? "$spo %" : 'No data',
                    'status' => $vStatus('spo2', $spo),
                    'normal' => '95–100%',
                    'icon'   => 'fa-lungs',
                    'color'  => '#22c55e',
                    'explain'=> 'Below 95% may indicate a breathing problem. Below 90% is critical and requires immediate attention.',
                ],
                [
                    'label'  => 'Total Cholesterol',
                    'value'  => $cho ? "$cho mg/dL" : 'No data',
                    'status' => $vStatus('chol', $cho),
                    'normal' => '< 200 mg/dL',
                    'icon'   => 'fa-vial',
                    'color'  => '#8b5cf6',
                    'explain'=> '200–239 is borderline high. 240+ significantly raises your risk of heart disease and stroke.',
                ],
            ];
            @endphp
            @foreach($vitalsDetail as $vd)
            <div class="col-md-6">
                <div style="border:1.5px solid #f0f4f0;border-radius:12px;padding:.9rem 1rem;
                            transition:border-color .2s" onmouseover="this.style.borderColor='{{ $vd['color'] }}44'"
                     onmouseout="this.style.borderColor='#f0f4f0'">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fas {{ $vd['icon'] }}" style="color:{{ $vd['color'] }};width:16px"></i>
                        <span style="font-weight:700;font-size:.82rem">{{ $vd['label'] }}</span>
                        <span class="vital-status {{ $vd['status']['class'] }} ms-auto">
                            {{ $vd['status']['label'] }}
                        </span>
                    </div>
                    <div style="font-size:1.15rem;font-weight:900;color:#1a1a1a;margin:.3rem 0">
                        {{ $vd['value'] }}
                    </div>
                    <div style="font-size:.7rem;color:#94a3b8">
                        <i class="fas fa-info-circle me-1"></i>Normal: {{ $vd['normal'] }}
                    </div>
                    <div style="font-size:.72rem;color:#555;margin-top:.3rem;
                                border-top:1px solid #f5f5f5;padding-top:.3rem;line-height:1.5">
                        {{ $vd['explain'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>{{-- end main col --}}

{{-- ══ SIDEBAR ══ --}}
<div class="col-lg-4">

    {{-- ── Seasonal / Monthly Health Tips ── --}}
    <div class="seasonal-wrap"
         style="background:linear-gradient(135deg,{{ $seasonalTips['color'] }},{{ $seasonalTips['color'] }}bb)">
        <div style="position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.65rem">
                <i class="fas {{ $seasonalTips['icon'] }}" style="font-size:1.3rem"></i>
                <div>
                    <div style="font-weight:800;font-size:.85rem">
                        {{ $today->format('F Y') }} — Health Tips
                    </div>
                    <div style="font-size:.7rem;opacity:.85">
                        {{ $seasonalTips['festival'] }}
                    </div>
                </div>
            </div>
            <div style="background:rgba(0,0,0,.2);border-radius:8px;padding:.6rem .8rem;
                        margin-bottom:.7rem;font-size:.76rem;line-height:1.5">
                <div style="font-weight:700;margin-bottom:.15rem">
                    <i class="fas fa-utensils me-1"></i> Food Advisory
                </div>
                {{ $seasonalTips['food_alert'] }}
            </div>
            @foreach($seasonalTips['tips'] as $tip)
            <div style="display:flex;align-items:flex-start;gap:.4rem;
                        margin-bottom:.38rem;font-size:.76rem;line-height:1.5">
                <i class="fas fa-check-circle" style="margin-top:.15rem;flex-shrink:0"></i>
                {{ $tip }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Recommended Specialists ── --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-user-md"></i> Recommended Specialists
        </div>

        @php
        $neededSpecs = collect();
        $neededSpecs->push('Family Medicine');
        $neededSpecs->push('Internal Medicine');

        if ($hd?->has_diabetes || ($bs ?? 0) >= 100)
            $neededSpecs->push('Endocrinology');
        if ($hd?->has_hypertension || ($sys ?? 0) >= 130)
            $neededSpecs->push('Cardiology');
        if ($hd?->has_heart_disease || ($cho ?? 0) >= 240)
            $neededSpecs->push('Cardiology');
        if ($hd?->has_asthma || ($spo !== null && $spo < 95))
            $neededSpecs->push('Pulmonology');
        if ($hd?->has_kidney_disease)
            $neededSpecs->push('Nephrology');
        if ($hd?->has_thyroid)
            $neededSpecs->push('Endocrinology');
        if (in_array($bmiCategory, ['Overweight','Obese'])) {
            $neededSpecs->push('Endocrinology');
            $neededSpecs->push('Gastroenterology');
        }
        if (in_array($hd?->stress_level, ['high','very_high']))
            $neededSpecs->push('Psychiatry');
        if ($hd?->family_cancer)
            $neededSpecs->push('Oncology');
        if ($hd?->family_heart_disease || $hd?->family_hypertension)
            $neededSpecs->push('Cardiology');
        if ($age && $age < 18)
            $neededSpecs->push('Pediatrics');
        if ($patient->gender === 'female')
            $neededSpecs->push('Gynecology');

        foreach ($medicalHistory as $mh) {
            $c = strtolower($mh->condition_name ?? '');
            if (str_contains($c,'bone') || str_contains($c,'joint') || str_contains($c,'arthrit'))
                $neededSpecs->push('Orthopedics');
            if (str_contains($c,'skin') || str_contains($c,'dermat'))
                $neededSpecs->push('Dermatology');
            if (str_contains($c,'eye') || str_contains($c,'vision') || str_contains($c,'glaucom'))
                $neededSpecs->push('Ophthalmology');
            if (str_contains($c,'gastro') || str_contains($c,'stomach') || str_contains($c,'liver'))
                $neededSpecs->push('Gastroenterology');
            if (str_contains($c,'neuro') || str_contains($c,'migrain') || str_contains($c,'seizure'))
                $neededSpecs->push('Neurology');
            if (str_contains($c,'urin') || str_contains($c,'bladder') || str_contains($c,'prostat'))
                $neededSpecs->push('Urology');
            if (str_contains($c,'rheumat') || str_contains($c,'lupus'))
                $neededSpecs->push('Rheumatology');
            if (str_contains($c,'ear') || str_contains($c,'throat') || str_contains($c,'sinus'))
                $neededSpecs->push('Otolaryngology (ENT)');
            if (str_contains($c,'blood') || str_contains($c,'anemia') || str_contains($c,'anaemia'))
                $neededSpecs->push('Hematology');
            if (str_contains($c,'cancer') || str_contains($c,'tumor'))
                $neededSpecs->push('Oncology');
        }

        $neededSpecs  = $neededSpecs->unique()->values();
        $suggestedDoctors = \App\Models\Doctor::whereIn('specialization', $neededSpecs->toArray())
            ->where('status','approved')
            ->orderBy('rating','desc')
            ->limit(6)
            ->get();
        @endphp

        <p style="font-size:.74rem;color:#888;margin-bottom:.7rem;line-height:1.5">
            Based on your health data recorded on
            <strong>{{ $today->format('d M Y') }}</strong>:
        </p>

        @if($neededSpecs->count() > 0)
        <div style="margin-bottom:.85rem">
            <div style="font-size:.7rem;color:#94a3b8;margin-bottom:.35rem;font-weight:600">
                Recommended specializations for you:
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:.3rem">
                @foreach($neededSpecs->take(7) as $sp)
                <span style="background:#e0f2f1;color:var(--teal);padding:.15rem .55rem;
                      border-radius:20px;font-size:.67rem;font-weight:700">{{ $sp }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @forelse($suggestedDoctors as $doc)
        @php
            $docImg = $doc->profile_image
                ? asset('storage/'.$doc->profile_image)
                : asset('images/default-avatar.png');
            $rating  = round($doc->rating ?? 0);
        @endphp
        <div class="doc-item">
            <img src="{{ $docImg }}" class="doc-img"
                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
            <div style="flex:1;min-width:0">
                <div style="font-weight:700;font-size:.82rem;
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    Dr. {{ $doc->firstname }} {{ $doc->lastname }}
                </div>
                <div style="font-size:.72rem;color:var(--teal);font-weight:600">
                    {{ $doc->specialization }}
                </div>
                <div style="color:#f59e0b;font-size:.62rem;margin-top:.1rem">
                    @for($i=1;$i<=5;$i++)
                    <i class="fas fa-star" style="color:{{ $i<=$rating?'#f59e0b':'#e2e8f0' }}"></i>
                    @endfor
                    <span style="color:#94a3b8;margin-left:.2rem;font-size:.62rem">
                        {{ number_format($doc->rating ?? 0, 1) }}
                    </span>
                    @if($doc->consultation_fee)
                    <span style="color:#94a3b8;margin-left:.3rem">
                        • Rs. {{ number_format($doc->consultation_fee) }}
                    </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('patient.doctor.profile', $doc->id) }}"
               style="background:linear-gradient(135deg,var(--teal),var(--teal-dark));
                     color:#fff;border-radius:9px;padding:.4rem .75rem;font-size:.7rem;
                     font-weight:700;text-decoration:none;white-space:nowrap;
                     display:inline-flex;align-items:center;gap:.3rem;
                     box-shadow:0 2px 8px rgba(0,121,107,.25);
                     transition:all .2s;flex-shrink:0"
               onmouseover="this.style.filter='brightness(1.1)'"
               onmouseout="this.style.filter='brightness(1)'">
                <i class="fas fa-calendar-plus" style="font-size:.65rem"></i>
                Book
            </a>
        </div>
        @empty
        <div style="text-align:center;padding:1.5rem;color:#bbb">
            <i class="fas fa-user-md" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#e2e8f0"></i>
            <p style="font-size:.78rem;color:#94a3b8">
                Add your medical conditions above to get specialist recommendations.
            </p>
        </div>
        @endforelse
    </div>

    {{-- ── Things to Avoid ── --}}
    <div class="hp-card">
        <div class="hp-card-title"><i class="fas fa-ban"></i> Things to Avoid</div>
        @php
        $avoid = [];
        if (in_array($bmiCategory, ['Overweight','Obese'])) {
            $avoid[] = ['icon'=>'fa-burger',       'text'=>'Fried & fast food','reason'=>'Very high in calories'];
            $avoid[] = ['icon'=>'fa-candy-cane',   'text'=>'Sugary snacks & soft drinks','reason'=>'Raises blood sugar & weight'];
            $avoid[] = ['icon'=>'fa-couch',        'text'=>'Sitting for long periods','reason'=>'Slows metabolism'];
            $avoid[] = ['icon'=>'fa-bread-slice',  'text'=>'Large portions of white rice/bread','reason'=>'High glycaemic index'];
        }
        if (($sys ?? 0) >= 130) {
            $avoid[] = ['icon'=>'fa-shaker',       'text'=>'High-salt foods (pickles, chips)','reason'=>'Raises BP'];
            $avoid[] = ['icon'=>'fa-mug-hot',      'text'=>'Excess coffee/tea (>2 cups/day)','reason'=>'Caffeine elevates BP'];
            $avoid[] = ['icon'=>'fa-face-tired',   'text'=>'Sleep deprivation','reason'=>'Significantly raises blood pressure'];
        }
        if (($bs ?? 0) >= 100) {
            $avoid[] = ['icon'=>'fa-bread-slice',  'text'=>'Refined carbs (white rice, pastries)','reason'=>'Causes blood sugar spikes'];
            $avoid[] = ['icon'=>'fa-wine-glass',   'text'=>'Alcohol & sweetened drinks','reason'=>'Disrupts blood sugar control'];
            $avoid[] = ['icon'=>'fa-clock',        'text'=>'Skipping meals','reason'=>'Causes dangerous sugar fluctuations'];
        }
        if ($hd?->smoking_status === 'current')
            $avoid[] = ['icon'=>'fa-smoking',      'text'=>'Smoking — quit immediately','reason'=>'Raises cancer & heart risk by 3–4×'];
        if (in_array($hd?->stress_level, ['high','very_high']))
            $avoid[] = ['icon'=>'fa-brain',        'text'=>'Ignoring mental health','reason'=>'Chronic stress damages heart & immunity'];
        if ($hd?->sleep_hours && $hd->sleep_hours < 6)
            $avoid[] = ['icon'=>'fa-moon',         'text'=>'Less than 6 hours of sleep','reason'=>'Linked to obesity, diabetes & weak immunity'];
        if (in_array($hd?->alcohol_consumption, ['moderate','heavy']))
            $avoid[] = ['icon'=>'fa-wine-bottle',  'text'=>'Heavy alcohol consumption','reason'=>'Damages liver, raises BP & cancer risk'];

        if (empty($avoid)) {
            $avoid = [
                ['icon'=>'fa-smoking-ban',  'text'=>'Smoking of any kind',         'reason'=>'Major cause of cancer & heart disease'],
                ['icon'=>'fa-wine-glass',   'text'=>'Excessive alcohol',            'reason'=>'Liver damage & blood pressure'],
                ['icon'=>'fa-burger',       'text'=>'Ultra-processed food daily',   'reason'=>'Obesity, BP, blood sugar'],
                ['icon'=>'fa-couch',        'text'=>'Sedentary lifestyle',          'reason'=>'Metabolic syndrome risk'],
                ['icon'=>'fa-clock',        'text'=>'Irregular sleep patterns',     'reason'=>'Hormonal imbalance & fatigue'],
            ];
        }
        @endphp
        @foreach($avoid as $a)
        <div style="display:flex;align-items:center;gap:.7rem;padding:.5rem 0;
                    border-bottom:1px solid #fef2f2">
            <i class="fas {{ $a['icon'] }}" style="color:#ef4444;width:18px;flex-shrink:0"></i>
            <div style="flex:1">
                <div style="font-size:.81rem;color:#991b1b;font-weight:600">{{ $a['text'] }}</div>
                @if(isset($a['reason']))
                <div style="font-size:.68rem;color:#fca5a5">{{ $a['reason'] }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Health Trends — Last 6 Months ── --}}
    @if($chartMetrics->count() > 0)
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-chart-line"></i> Health Trends — Last 6 Months
        </div>
        <div style="display:flex;gap:.3rem;border-bottom:2px solid var(--teal-light);
                    margin-bottom:.9rem;overflow-x:auto">
            @foreach(['weight'=>'Weight','bp'=>'Blood Pressure','hr'=>'Heart Rate','bs'=>'Blood Sugar'] as $k=>$l)
            <a class="hp-tab {{ $k==='weight'?'active':'' }}"
               onclick="showSideChart('{{ $k }}',this);return false;"
               href="#">{{ $l }}</a>
            @endforeach
        </div>
        <div style="position:relative;height:180px">
            <canvas id="sideMetricsChart"></canvas>
        </div>
        <div style="font-size:.7rem;color:#aaa;text-align:center;margin-top:.5rem">
            {{ Carbon::now()->subMonths(6)->format('d M Y') }}
            — {{ Carbon::now()->format('d M Y') }}
        </div>
    </div>

    {{-- Health Score Breakdown --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-chart-pie"></i> Health Score Breakdown
        </div>
        <p style="font-size:.78rem;color:#888;margin-bottom:1rem">
            Your overall score of
            <strong style="color:{{ $healthScore['color'] }}">{{ $healthScore['score'] }}/100</strong>
            is rated <strong>{{ $healthScore['level'] }}</strong>.
            Scores are calculated across 6 categories:
        </p>
        @php
        $detDesc = [
            'profile'    => 'How complete is your profile?',
            'bmi'        => 'Is your BMI within the healthy range (18.5–24.9)?',
            'bp'         => 'Is your blood pressure below 120/80 mmHg?',
            'sugar'      => 'Is your fasting blood sugar below 100 mg/dL?',
            'lifestyle'  => 'Exercise, sleep quality, smoking & alcohol habits',
            'conditions' => 'Number of active chronic conditions',
        ];
        @endphp
        @foreach($healthScore['details'] as $k => $det)
        @php
            $pct      = $det['max'] > 0 ? round(($det['score'] / $det['max']) * 100) : 0;
            $barColor = $pct >= 75 ? '#22c55e' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
        @endphp
        <div class="sb-row">
            <div class="sb-meta">
                <span>
                    <strong>{{ $detLabels[$k] ?? $det['label'] }}</strong>
                    <span style="font-size:.7rem;color:#aaa;margin-left:.4rem">
                        {{ $detDesc[$k] ?? '' }}
                    </span>
                </span>
                <span style="color:{{ $barColor }};font-weight:800">
                    {{ $det['score'] }} / {{ $det['max'] }} pts
                </span>
            </div>
            <div class="sb-track">
                <div class="sb-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
            </div>
            @if($pct < 60)
            <div style="font-size:.68rem;color:#94a3b8;margin-top:.2rem">
                <i class="fas fa-arrow-up me-1"></i>
                {{ match($k) {
                    'profile'    => 'Complete all profile fields to earn maximum points',
                    'bmi'        => 'Aim for BMI between 18.5 and 24.9 for full score',
                    'bp'         => 'Keep BP below 120/80 through diet and exercise',
                    'sugar'      => 'Keep fasting blood sugar below 100 mg/dL',
                    'lifestyle'  => 'Exercise 3–4×/week, sleep 7–9 hrs, quit smoking',
                    'conditions' => 'Manage chronic conditions with regular medical care',
                    default      => 'Improve this area for a better score',
                } }}
            </div>
            @endif
        </div>
        @endforeach

        <div style="margin-top:1rem;padding:.8rem 1rem;background:#f0f9ff;border-radius:10px;
                    font-size:.78rem;color:#1e40af;border-left:3px solid #3b82f6">
            <strong>Your Score: {{ $healthScore['score'] }}/100 — {{ $healthScore['level'] }}</strong><br>
            @if($healthScore['score'] < 40)
                ⚠️ Score is very low. Please enter health data and consult a doctor.
            @elseif($healthScore['score'] < 60)
                ↗️ Score can be improved. Focus on lifestyle changes and filling in more data.
            @elseif($healthScore['score'] < 80)
                ✅ Good score. Maintain regular exercise and a balanced diet.
            @else
                🌟 Excellent health! Keep up your healthy habits.
            @endif
        </div>
    </div>

    {{-- Personalized Recommendations --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-lightbulb"></i> Personalized Health Recommendations
        </div>
        @forelse($recommendations as $rec)
        <div class="rec-card rec-{{ $rec['type'] }}">
            <i class="fas {{ $rec['icon'] }}"
               style="font-size:1.15rem;flex-shrink:0;margin-top:.1rem"></i>
            <div style="flex:1">
                <strong>{{ $rec['title'] }}</strong>
                <div style="line-height:1.65">{{ $rec['text'] }}</div>
                @if(!empty($rec['details']))
                <ul style="margin:.5rem 0 0;padding-left:1.2rem;font-size:.78rem;line-height:1.7">
                    @foreach($rec['details'] as $d)
                    <li>{{ $d }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:#bbb">
            <i class="fas fa-notes-medical"
               style="font-size:2.5rem;display:block;margin-bottom:.6rem;color:#e2e8f0"></i>
            <p style="font-size:.84rem;color:#94a3b8">
                Complete the health form above to receive personalised recommendations.
            </p>
        </div>
        @endforelse
    </div>

    {{-- Medical History (moved here as requested) --}}
    <div class="hp-card">
        <div class="hp-card-title">
            <i class="fas fa-notes-medical"></i> Medical History
        </div>
        @forelse($medicalHistory as $hist)
        <div style="display:flex;align-items:center;gap:.8rem;padding:.6rem 0;
                    border-bottom:1px solid #f0f4f0">
            <i class="fas fa-file-medical" style="color:var(--teal);flex-shrink:0"></i>
            <div style="flex:1">
                <div style="font-weight:700;font-size:.84rem">{{ $hist->condition_name }}</div>
                @if($hist->diagnosed_date)
                <div style="font-size:.7rem;color:#888">
                    <i class="fas fa-calendar me-1"></i>
                    Diagnosed: {{ Carbon::parse($hist->diagnosed_date)->format('d M Y') }}
                    ({{ Carbon::parse($hist->diagnosed_date)->diffForHumans() }})
                </div>
                @endif
                @if($hist->notes)
                <div style="font-size:.72rem;color:#666;margin-top:.2rem;font-style:italic">
                    {{ $hist->notes }}
                </div>
                @endif
            </div>
            <span style="padding:.2rem .75rem;border-radius:20px;font-size:.7rem;font-weight:700;
                  background:{{ $hist->status==='chronic'?'#fef3c7':($hist->status==='resolved'?'#dcfce7':'#fee2e2') }};
                  color:{{ $hist->status==='chronic'?'#92400e':($hist->status==='resolved'?'#166534':'#991b1b') }};
                  white-space:nowrap">
                {{ ucfirst($hist->status ?? 'Active') }}
            </span>
        </div>
        @empty
        <div style="text-align:center;padding:1.5rem;color:#ccc">
            <i class="fas fa-clipboard-check" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
            <p style="font-size:.82rem">No medical history on record.</p>
        </div>
        @endforelse
    </div>

    @else
    <div class="hp-card" style="text-align:center;padding:1.5rem">
        <i class="fas fa-chart-line" style="font-size:2rem;color:#e2e8f0;
                                             display:block;margin-bottom:.6rem"></i>
        <p style="font-size:.78rem;color:#bbb">
            Health trend charts will appear here after you save data on multiple dates.
        </p>
    </div>
    @endif

</div>{{-- end sidebar --}}

</div>{{-- end row --}}
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ══ Metrics data ══
const MD = {
    labels: {!! json_encode($chartMetrics->pluck('metric_date')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('d M'))->toArray()) !!},
    weight: {!! json_encode($chartMetrics->pluck('weight')->map(fn($v)=>$v?(float)$v:null)->toArray()) !!},
    bpSys:  {!! json_encode($chartMetrics->pluck('blood_pressure_systolic')->map(fn($v)=>$v?(int)$v:null)->toArray()) !!},
    bpDia:  {!! json_encode($chartMetrics->pluck('blood_pressure_diastolic')->map(fn($v)=>$v?(int)$v:null)->toArray()) !!},
    hr:     {!! json_encode($chartMetrics->pluck('heart_rate')->map(fn($v)=>$v?(int)$v:null)->toArray()) !!},
    bs:     {!! json_encode($chartMetrics->pluck('blood_sugar')->map(fn($v)=>$v?(float)$v:null)->toArray()) !!},
};

const chartDefs = {
    weight:{ds:[{label:'Weight (kg)',  data:null,borderColor:'#00796b',backgroundColor:'rgba(0,121,107,.08)',tension:.4,fill:true,pointRadius:4,pointBackgroundColor:'#00796b'}]},
    bp:    {ds:[{label:'Systolic',     data:null,borderColor:'#ef4444',backgroundColor:'rgba(239,68,68,.05)',tension:.4,fill:false,pointRadius:4},
                {label:'Diastolic',   data:null,borderColor:'#f59e0b',backgroundColor:'transparent',tension:.4,pointRadius:4}]},
    hr:    {ds:[{label:'Heart Rate (bpm)',data:null,borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,.08)',tension:.4,fill:true,pointRadius:4,pointBackgroundColor:'#f59e0b'}]},
    bs:    {ds:[{label:'Blood Sugar (mg/dL)',data:null,borderColor:'#3b82f6',backgroundColor:'rgba(59,130,246,.08)',tension:.4,fill:true,pointRadius:4,pointBackgroundColor:'#3b82f6'}]},
};

function makeDatasets(type) {
    const d = JSON.parse(JSON.stringify(chartDefs[type].ds));
    if (type==='weight') { d[0].data = MD.weight; }
    else if (type==='bp')  { d[0].data = MD.bpSys; d[1].data = MD.bpDia; }
    else if (type==='hr')  { d[0].data = MD.hr; }
    else if (type==='bs')  { d[0].data = MD.bs; }
    return d;
}

let sideChart = null;
function showSideChart(type, el) {
    const tabs = el.closest('.hp-card').querySelectorAll('.hp-tab');
    tabs.forEach(t => { t.classList.remove('active'); t.style.color='#888'; });
    el.classList.add('active'); el.style.color='#00796b';
    if (sideChart) sideChart.destroy();
    const ctx = document.getElementById('sideMetricsChart').getContext('2d');
    sideChart = new Chart(ctx, {
        type: 'line',
        data: { labels: MD.labels, datasets: makeDatasets(type) },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ display: type==='bp', labels:{ font:{size:10} } } },
            scales:{
                y:{ grid:{ color:'rgba(0,121,107,.06)' }, ticks:{ font:{size:10} } },
                x:{ grid:{ display:false }, ticks:{ font:{size:10} } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const firstTab = document.querySelector('#sideMetricsChart')
        ?.closest('.hp-card')
        ?.querySelector('.hp-tab');
    if (firstTab) showSideChart('weight', firstTab);
});

// ══ Live BMI Calculator ══
const wIn = document.getElementById('inpWeight');
const hIn = document.getElementById('inpHeight');

function calcBMI() {
    const w = parseFloat(wIn?.value), h = parseFloat(hIn?.value);
    if (!w || !h || h < 50) {
        document.getElementById('bmiPreview').style.display = 'none';
        return;
    }
    const bmi = +(w / ((h / 100) ** 2)).toFixed(1);
    const cats = [
        { max:18.5, label:'Underweight',   bg:'#dbeafe', color:'#1e40af', advice:'Eat more nutritious, calorie-dense foods' },
        { max:25,   label:'Normal ✅',      bg:'#dcfce7', color:'#166534', advice:'Great! Maintain your current habits' },
        { max:30,   label:'Overweight ⚠️', bg:'#fef3c7', color:'#92400e', advice:'Exercise daily & reduce refined carbs' },
        { max:999,  label:'Obese ❗',       bg:'#fee2e2', color:'#991b1b', advice:'Consult a doctor for a weight management plan' },
    ];
    const cat = cats.find(c => bmi < c.max);
    document.getElementById('bmiVal').textContent = bmi;
    const lbl = document.getElementById('bmiCatLbl');
    lbl.textContent = cat.label;
    lbl.style.background = cat.bg;
    lbl.style.color = cat.color;
    document.getElementById('bmiAdvice').textContent = '→ ' + cat.advice;
    document.getElementById('bmiPreview').style.display = 'block';
}
wIn?.addEventListener('input', calcBMI);
hIn?.addEventListener('input', calcBMI);
calcBMI();

// ══ Save spinner ══
document.getElementById('healthForm')?.addEventListener('submit', () => {
    document.getElementById('saveBtn').disabled = true;
    document.getElementById('saveSpinner').style.display = 'inline-flex';
});

// ══ Auto-dismiss alerts ══
document.querySelectorAll('.hp-alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity    = '0';
        setTimeout(() => el.remove(), 500);
    }, 5000);
});
</script>

@include('partials.footer')
