@include('partials.header')

<style>
/* ═══════════════════════════════════
   STEP BAR
═══════════════════════════════════ */
.step-bar {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    padding: 1.2rem 1rem;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.step-bar .inner {
    display: flex; align-items: center;
    justify-content: center;
}
.step-item { display: flex; flex-direction: column; align-items: center; }
.step-circle {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2px solid #e0e0e0; background: #fff; color: #aaa;
    font-size: 0.82rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.3s; z-index: 1;
}
.step-circle.done   { background: #42a649; border-color: #42a649; color: #fff; }
.step-circle.active {
    background: #42a649; border-color: #42a649; color: #fff;
    box-shadow: 0 4px 12px rgba(66,166,73,0.4);
}
.step-label { font-size: 0.7rem; font-weight: 600; margin-top: 0.4rem; color: #aaa; white-space: nowrap; }
.step-label.done, .step-label.active { color: #42a649; }
.step-line {
    width: 60px; height: 2px; background: #e0e0e0;
    margin: 0 0.4rem 22px; transition: background 0.3s;
}
.step-line.done { background: #42a649; }
@media (max-width: 576px) {
    .step-line { width: 22px; }
    .step-label { display: none; }
}

/* ═══════════════════════════════════
   PAGE HERO
═══════════════════════════════════ */
.pay-hero {
    background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%);
    padding: 80px 0 3.5rem; color: #fff;
    position: relative; overflow: hidden;
}
.pay-hero::after {
    content: ''; position: absolute;
    bottom: -1px; left: 0; right: 0; height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.pay-hero .container { position: relative; z-index: 1; }
.back-link {
    color: rgba(255,255,255,0.8); text-decoration: none;
    font-size: 0.85rem; display: inline-flex; align-items: center;
    gap: 0.4rem; margin-bottom: 1rem; transition: color 0.2s;
}
.back-link:hover { color: #fff; }

/* ═══════════════════════════════════
   BODY
═══════════════════════════════════ */
.pay-body { background: #f4f6f9; padding: 2rem 0 4rem; min-height: 500px; }

/* ─ Generic card ─ */
.pay-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    overflow: hidden; margin-bottom: 1.5rem;
}
.pay-card-header {
    padding: 1.1rem 1.5rem;
    font-size: 0.97rem; font-weight: 700;
    display: flex; align-items: center; gap: 0.55rem;
    border-bottom: 2px solid rgba(66,166,73,0.15);
    color: #1a5276;
}
.pay-card-header i { color: #42a649; }
.pay-card-body { padding: 1.5rem; }

/* ═══════════════════════════════════
   PAYMENT TYPE TOGGLE
═══════════════════════════════════ */
.pay-type-row {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0.75rem; margin-bottom: 1.4rem;
}
.pay-type-opt {
    border: 2px solid #e9ecef; border-radius: 12px;
    padding: 1rem 1.1rem; cursor: pointer;
    transition: all 0.22s; position: relative;
    background: #fff;
}
.pay-type-opt:hover { border-color: #42a649; background: rgba(66,166,73,0.03); }
.pay-type-opt.selected {
    border-color: #42a649;
    background: rgba(66,166,73,0.06);
    box-shadow: 0 3px 12px rgba(66,166,73,0.15);
}
.pay-type-opt input[type="radio"] { display: none; }
.pay-type-badge {
    position: absolute; top: -9px; right: 10px;
    background: #42a649; color: #fff;
    font-size: 0.62rem; font-weight: 700;
    padding: 0.18rem 0.6rem; border-radius: 8px;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.pay-type-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; margin-bottom: 0.6rem;
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    color: #777; transition: all 0.22s;
}
.pay-type-opt.selected .pay-type-icon {
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff;
}
.pay-type-title { font-size: 0.88rem; font-weight: 700; color: #1a5276; margin-bottom: 0.2rem; }
.pay-type-amt   { font-size: 1.1rem; font-weight: 800; color: #42a649; }
.pay-type-sub   { font-size: 0.72rem; color: #999; margin-top: 0.15rem; }

/* ─ Divider ─ */
.or-divider {
    display: flex; align-items: center; gap: 0.8rem;
    margin: 1.3rem 0; color: #ccc; font-size: 0.8rem; font-weight: 600;
}
.or-divider::before, .or-divider::after {
    content: ''; flex: 1; height: 1px; background: #e9ecef;
}

/* ═══════════════════════════════════
   PAY LATER CARD
═══════════════════════════════════ */
.pay-later-card {
    border: 2px dashed #dee2e6; border-radius: 14px;
    padding: 1.3rem 1.4rem; background: #fafafa;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    transition: all 0.2s; cursor: pointer; text-decoration: none;
    margin-bottom: 0;
}
.pay-later-card:hover {
    border-color: #42a649; background: rgba(66,166,73,0.04);
    transform: translateY(-1px);
}
.pay-later-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: #666; flex-shrink: 0;
    transition: all 0.2s;
}
.pay-later-card:hover .pay-later-icon {
    background: linear-gradient(135deg, rgba(66,166,73,0.1), rgba(66,166,73,0.18));
    color: #42a649;
}
.pay-later-title { font-size: 0.92rem; font-weight: 700; color: #555; margin-bottom: 0.2rem; }
.pay-later-sub   { font-size: 0.78rem; color: #aaa; line-height: 1.4; }
.pay-later-arrow {
    margin-left: auto; color: #ccc; font-size: 1rem;
    transition: all 0.2s;
}
.pay-later-card:hover .pay-later-arrow { color: #42a649; transform: translateX(3px); }

/* ═══════════════════════════════════
   DOCTOR ROW
═══════════════════════════════════ */
.doc-row {
    display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;
    padding-bottom: 1.1rem;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 1.1rem;
}
.doc-avatar-p {
    width: 62px; height: 62px; border-radius: 50%;
    overflow: hidden; border: 3px solid #42a649; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.doc-avatar-p img { width: 100%; height: 100%; object-fit: cover; }
.doc-name-p  { font-size: 1.05rem; font-weight: 700; color: #1a5276; margin-bottom: 0.12rem; }
.doc-spec-p  { font-size: 0.83rem; color: #42a649; font-weight: 600; }
.badge-ver {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #d4edda; color: #155724;
    padding: 0.18rem 0.55rem; border-radius: 8px;
    font-size: 0.7rem; font-weight: 700; margin-top: 0.28rem;
}

/* ─ Info rows ─ */
.info-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 0.5rem; padding: 0.58rem 0;
    border-bottom: 1px solid #f5f5f5; font-size: 0.87rem;
}
.info-row:last-child { border: none; }
.info-lbl { color: #888; display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0; }
.info-lbl i { color: #42a649; width: 14px; text-align: center; }
.info-val { color: #333; font-weight: 600; text-align: right; word-break: break-word; max-width: 58%; }

/* ─ Fee summary box (dynamic) ─ */
.fee-box {
    background: linear-gradient(135deg, rgba(66,166,73,0.07), rgba(66,166,73,0.14));
    border: 2px solid rgba(66,166,73,0.28);
    border-radius: 14px; padding: 1.1rem;
    text-align: center; margin-top: 1.1rem;
}
.fee-box-lbl   { font-size: 0.78rem; color: #777; font-weight: 600; margin-bottom: 0.2rem; }
.fee-box-amt   { font-size: 2.1rem; font-weight: 800; color: #42a649; line-height: 1; }
.fee-box-sub   { font-size: 0.68rem; color: #aaa; margin-top: 0.25rem; }
.fee-box-note  { font-size: 0.75rem; color: #856404; background: #fff3cd; border-radius: 8px; padding: 0.4rem 0.7rem; margin-top: 0.6rem; }

/* ─ Form fields ─ */
.f-label {
    display: block; font-size: 0.83rem; font-weight: 700;
    color: #1a5276; margin-bottom: 0.45rem;
}
.f-label span { color: #dc3545; }
.f-input {
    width: 100%; padding: 0.8rem 1rem;
    border: 2px solid #e9ecef; border-radius: 10px;
    font-size: 0.9rem; color: #333;
    transition: border-color 0.25s, box-shadow 0.25s;
    background: #fff;
}
.f-input:focus {
    border-color: #42a649; outline: none;
    box-shadow: 0 0 0 3px rgba(66,166,73,0.12);
}
.f-err { font-size: 0.78rem; color: #dc3545; margin-top: 0.3rem; display: none; }
.f-err.show { display: block; }

/* ─ Stripe element ─ */
.stripe-wrap {
    border: 2px solid #e9ecef; border-radius: 10px;
    padding: 0.9rem 1rem; background: #fff; min-height: 48px;
    transition: border-color 0.25s, box-shadow 0.25s;
}
.stripe-wrap.focused {
    border-color: #42a649;
    box-shadow: 0 0 0 3px rgba(66,166,73,0.12);
}
.stripe-wrap.stripe-error {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
}

/* ─ Card error ─ */
.card-err-box {
    background: #f8d7da; color: #721c24;
    border-left: 4px solid #dc3545;
    border-radius: 8px; padding: 0.75rem 1rem;
    font-size: 0.87rem; margin-top: 0.7rem;
    display: none; align-items: center; gap: 0.5rem;
}
.card-err-box.show { display: flex; }

/* ─ Submit button ─ */
.btn-pay {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: #fff; border: none; width: 100%;
    padding: 1.05rem; border-radius: 25px;
    font-size: 1rem; font-weight: 700; cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(66,166,73,0.38);
    margin-top: 1.3rem;
}
.btn-pay:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(66,166,73,0.5);
}
.btn-pay:disabled { opacity: 0.72; cursor: not-allowed; transform: none; }

/* ─ Pay spinner ─ */
.pay-spin {
    width: 20px; height: 20px;
    border: 3px solid rgba(255,255,255,0.4);
    border-top-color: #fff; border-radius: 50%;
    animation: doSpin 0.8s linear infinite;
    display: none; flex-shrink: 0;
}
@keyframes doSpin { to { transform: rotate(360deg); } }

/* ─ Security badges ─ */
.sec-row {
    display: flex; justify-content: center;
    gap: 1.3rem; margin-top: 1.1rem; flex-wrap: wrap;
}
.sec-badge {
    display: flex; align-items: center; gap: 0.35rem;
    font-size: 0.75rem; color: #aaa; font-weight: 500;
}
.sec-badge i { color: #42a649; }

/* ─ Test banner ─ */
.test-banner {
    background: #fff3cd; border: 1px solid #ffc107;
    border-radius: 10px; padding: 0.85rem 1rem;
    margin-bottom: 1.3rem; font-size: 0.83rem; color: #856404;
    display: flex; align-items: flex-start; gap: 0.6rem;
}
.test-banner code {
    background: rgba(0,0,0,0.07);
    padding: 0.1rem 0.45rem; border-radius: 4px;
    font-size: 0.9em; letter-spacing: 0.5px;
}

/* ─ Alert ─ */
.pay-alert {
    border-radius: 10px; padding: 1rem 1.1rem; margin-bottom: 1.3rem;
    display: flex; align-items: flex-start; gap: 0.7rem; font-size: 0.9rem;
}
.pay-alert.error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
.pay-alert.success { background: #d4edda; color: #155724; border-left: 4px solid #42a649; }
.pay-alert.info    { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }

/* ─ Appt badge ─ */
.appt-num-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(66,166,73,0.1); color: #2d7a32;
    border: 1px solid rgba(66,166,73,0.25);
    border-radius: 10px; padding: 0.28rem 0.75rem;
    font-size: 0.76rem; font-weight: 700; margin-bottom: 0.85rem;
}

/* ─ Location type badge ─ */
.loc-type {
    display: inline-flex; align-items: center; gap: 0.3rem;
    background: #e3f2fd; color: #0d47a1;
    border-radius: 7px; padding: 0.18rem 0.55rem;
    font-size: 0.7rem; font-weight: 700; margin-top: 0.2rem;
}

/* ─ Sidebar sticky ─ */
.sidebar-sticky { position: sticky; top: 90px; }

@media (max-width: 768px) {
    .pay-card-body  { padding: 1.1rem; }
    .sidebar-sticky { position: static; margin-top: 0; }
    .pay-type-row   { grid-template-columns: 1fr; }
    .fee-box-amt    { font-size: 1.8rem; }
    .pay-later-card { flex-direction: column; align-items: flex-start; }
    .pay-later-arrow { display: none; }
}
</style>


{{-- ══ HERO ══ --}}
<section class="pay-hero">
    <div class="container">
        <a href="{{ route('patient.appointments.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> My Appointments
        </a>
        <div class="row justify-content-center text-center">
            <div class="col-lg-7">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-credit-card me-2" style="opacity:0.85;"></i>
                    Complete Payment
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">
                    Secure payment powered by Stripe — your appointment will be confirmed immediately
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section class="pay-body">
    <div class="container">

        {{-- Alerts --}}
        @foreach(['error' => 'error', 'success' => 'success', 'info' => 'info'] as $skey => $stype)
            @if(session($skey))
                <div class="pay-alert {{ $stype }}">
                    <i class="fas fa-{{ $stype === 'error' ? 'times-circle' : ($stype === 'success' ? 'check-circle' : 'info-circle') }} fa-lg"
                       style="flex-shrink:0;margin-top:1px;"></i>
                    <span>{{ session($skey) }}</span>
                </div>
            @endif
        @endforeach

        @php
            $doctor   = $appointment->doctor;
            $feeTotal = $appointment->consultation_fee ?? 0;
            $feeAdv   = round($feeTotal * 0.5, 2);   // 50% advance

            // Workplace
            $wpName = 'HealthNet Clinic';
            $wpType = 'Clinic';
            $wpIcon = 'fas fa-clinic-medical';
            if ($workplace) {
                if ($workplace->workplace_type === 'hospital' && $workplace->hospital) {
                    $wpName = $workplace->hospital->name;
                    $wpType = 'Hospital';
                    $wpIcon = 'fas fa-hospital';
                } elseif ($workplace->workplace_type === 'medical_centre' && $workplace->medicalCentre) {
                    $wpName = $workplace->medicalCentre->name;
                    $wpType = 'Medical Centre';
                    $wpIcon = 'fas fa-clinic-medical';
                } elseif ($workplace->workplace_type === 'private') {
                    $wpName = 'Private Practice';
                    $wpType = 'Private';
                    $wpIcon = 'fas fa-user-md';
                }
            }

            $docImg   = ($doctor && $doctor->profile_image)
                ? asset('storage/' . $doctor->profile_image)
                : asset('images/default-avatar.png');

            $apptDate = \Carbon\Carbon::parse($appointment->appointment_date);
            $apptTime = \Carbon\Carbon::parse($appointment->appointment_time);
        @endphp

        <div class="row g-4 justify-content-center">

            {{-- ══════════════════
                 LEFT — FORM
            ══════════════════ --}}
            <div class="col-lg-6 col-xl-5">

                {{-- Test Mode Banner --}}
                @if(config('app.env') !== 'production')
                    <div class="test-banner">
                        <i class="fas fa-vial" style="flex-shrink:0;margin-top:1px;"></i>
                        <div>
                            <strong>Test Mode Active</strong><br>
                            Card: <code>4242 4242 4242 4242</code> &nbsp;
                            Expiry: <code>12/26</code> &nbsp;
                            CVC: <code>123</code> &nbsp;
                            ZIP: <code>12345</code>
                        </div>
                    </div>
                @endif

                {{-- Payment Type Selection --}}
                <div class="pay-card">
                    <div class="pay-card-header">
                        <i class="fas fa-wallet"></i> Choose Payment Option
                    </div>
                    <div class="pay-card-body">

                        <div class="pay-type-row" id="payTypeRow">

                            {{-- Full Payment --}}
                            <label class="pay-type-opt selected" for="payFull"
                                   onclick="selectPayType('full')">
                                <input type="radio" id="payFull" name="pay_type" value="full" checked>
                                <div class="pay-type-badge">Recommended</div>
                                <div class="pay-type-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="pay-type-title">Full Payment</div>
                                <div class="pay-type-amt">Rs. {{ number_format($feeTotal, 2) }}</div>
                                <div class="pay-type-sub">Pay entire consultation fee now</div>
                            </label>

                            {{-- Advance (50%) Payment --}}
                            <label class="pay-type-opt" for="payAdv"
                                   onclick="selectPayType('advance')">
                                <input type="radio" id="payAdv" name="pay_type" value="advance">
                                <div class="pay-type-icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <div class="pay-type-title">Advance (50%)</div>
                                <div class="pay-type-amt">Rs. {{ number_format($feeAdv, 2) }}</div>
                                <div class="pay-type-sub">Pay rest at appointment</div>
                            </label>

                        </div>

                        {{-- Advance info note --}}
                        <div id="advNote"
                             style="display:none;background:#fff3cd;border-radius:10px;
                                    padding:0.75rem 1rem;font-size:0.82rem;color:#856404;
                                    margin-bottom:0.5rem;display:none;align-items:flex-start;gap:0.5rem;">
                            <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
                            <span>
                                You are paying a <strong>50% advance</strong> of
                                <strong>Rs. {{ number_format($feeAdv, 2) }}</strong>.
                                The remaining <strong>Rs. {{ number_format($feeTotal - $feeAdv, 2) }}</strong>
                                is due at the time of your appointment.
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Stripe Card Form --}}
                <div class="pay-card">
                    <div class="pay-card-header">
                        <i class="fas fa-lock"></i> Card Details
                        <span style="margin-left:auto;display:flex;gap:0.5rem;align-items:center;">
                            <i class="fab fa-cc-visa"       style="font-size:1.4rem;color:#1a1f71;"></i>
                            <i class="fab fa-cc-mastercard" style="font-size:1.4rem;color:#eb001b;"></i>
                            <i class="fab fa-cc-amex"       style="font-size:1.4rem;color:#2e77bc;"></i>
                        </span>
                    </div>
                    <div class="pay-card-body">

                        <form id="paymentForm"
                              action="{{ route('patient.appointments.pay', $appointment->id) }}"
                              method="POST">
                            @csrf

                            {{-- JS sets these --}}
                            <input type="hidden" name="payment_method_id" id="paymentMethodId">
                            <input type="hidden" name="cardholder_name"   id="cardholderNameHidden">
                            <input type="hidden" name="payment_type"      id="paymentTypeHidden" value="full">
                            <input type="hidden" name="amount"            id="amountHidden"
                                   value="{{ $feeTotal }}">

                            {{-- Cardholder Name --}}
                            <div style="margin-bottom:1.2rem;">
                                <label class="f-label" for="cardholderName">
                                    Cardholder Name <span>*</span>
                                </label>
                                <input type="text"
                                       id="cardholderName"
                                       class="f-input"
                                       placeholder="Full name as it appears on card"
                                       autocomplete="cc-name">
                                <div class="f-err" id="nameErr">Please enter the cardholder name.</div>
                            </div>

                            {{-- Stripe Card Element --}}
                            <div style="margin-bottom:0.5rem;">
                                <label class="f-label">Card Details <span>*</span></label>
                                <div class="stripe-wrap" id="cardElement"></div>
                                <div class="card-err-box" id="cardErrors" role="alert">
                                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                                    <span id="cardErrorMsg"></span>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="btn-pay" id="payBtn">
                                <div class="pay-spin"   id="paySpinner"></div>
                                <i class="fas fa-lock" id="payIcon"></i>
                                <span id="payBtnText">
                                    Pay Full &nbsp;Rs.&nbsp;{{ number_format($feeTotal, 2) }}
                                </span>
                            </button>

                        </form>

                        {{-- Security badges --}}
                        <div class="sec-row">
                            <div class="sec-badge"><i class="fas fa-shield-alt"></i> SSL Secured</div>
                            <div class="sec-badge"><i class="fab fa-stripe"></i> Powered by Stripe</div>
                            <div class="sec-badge"><i class="fas fa-lock"></i> PCI Compliant</div>
                        </div>

                    </div>
                </div>

                {{-- ── OR Divider ── --}}
                <div class="or-divider">or</div>

                {{-- ── Pay Later ── --}}
                <a href="{{ route('patient.appointments.index') }}"
                   class="pay-later-card text-decoration-none"
                   onclick="return confirmPayLater();">
                    <div class="pay-later-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="pay-later-title">Pay Later from My Appointments</div>
                        <div class="pay-later-sub">
                            Skip payment now — you can pay anytime from your
                            Appointments dashboard before the appointment date.
                            <br>
                            <span style="color:#dc3545;font-size:0.75rem;font-weight:600;">
                                <i class="fas fa-exclamation-triangle" style="font-size:0.65rem;"></i>
                                Appointment is not confirmed until payment is made.
                            </span>
                        </div>
                    </div>
                    <div class="pay-later-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>

            </div>{{-- /col left --}}

            {{-- ══════════════════
                 RIGHT — SUMMARY
            ══════════════════ --}}
            <div class="col-lg-5 col-xl-4">
                <div class="sidebar-sticky">

                    {{-- Appointment Summary --}}
                    <div class="pay-card">
                        <div class="pay-card-header">
                            <i class="fas fa-receipt"></i> Appointment Summary
                        </div>
                        <div class="pay-card-body">

                            {{-- Appt Number --}}
                            @if($appointment->appointment_number)
                                <div class="appt-num-badge">
                                    <i class="fas fa-hashtag"></i>
                                    {{ $appointment->appointment_number }}
                                </div>
                            @endif

                            {{-- Doctor Row --}}
                            <div class="doc-row">
                                <div class="doc-avatar-p">
                                    <img src="{{ $docImg }}" alt="Doctor"
                                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div>
                                    <div class="doc-name-p">
                                        Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? '' }}
                                    </div>
                                    <div class="doc-spec-p">
                                        {{ $doctor->specialization ?? 'General Practitioner' }}
                                    </div>
                                    @if($doctor && $doctor->status === 'approved')
                                        <div class="badge-ver">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Rows --}}
                            <div class="info-row">
                                <span class="info-lbl"><i class="fas fa-calendar"></i> Date</span>
                                <span class="info-val">{{ $apptDate->format('D, d M Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-lbl"><i class="fas fa-clock"></i> Time</span>
                                <span class="info-val">{{ $apptTime->format('h:i A') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-lbl"><i class="{{ $wpIcon }}"></i> Location</span>
                                <span class="info-val">
                                    {{ $wpName }}
                                    <span class="loc-type">{{ $wpType }}</span>
                                </span>
                            </div>
                            @if($appointment->reason)
                                <div class="info-row">
                                    <span class="info-lbl"><i class="fas fa-notes-medical"></i> Reason</span>
                                    <span class="info-val">{{ Str::limit($appointment->reason, 40) }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-lbl"><i class="fas fa-tag"></i> Type</span>
                                <span class="info-val">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type ?? 'consultation')) }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-lbl"><i class="fas fa-info-circle"></i> Status</span>
                                <span class="info-val">
                                    <span style="background:#fff3cd;color:#856404;
                                                 padding:0.18rem 0.55rem;border-radius:8px;
                                                 font-size:0.76rem;font-weight:700;">
                                        <i class="fas fa-clock" style="font-size:0.6rem;vertical-align:middle;"></i>
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </span>
                            </div>

                            {{-- Dynamic Fee Box --}}
                            <div class="fee-box">
                                <div class="fee-box-lbl" id="sumFeeLabel">Total Amount Due (Full)</div>
                                <div class="fee-box-amt" id="sumFeeAmt">
                                    Rs. {{ number_format($feeTotal, 2) }}
                                </div>
                                <div class="fee-box-sub">Sri Lankan Rupees (LKR)</div>
                                <div class="fee-box-note" id="sumFeeNote" style="display:none;"></div>
                            </div>

                        </div>
                    </div>

                    {{-- What Happens Next --}}
                    <div class="pay-card">
                        <div class="pay-card-header">
                            <i class="fas fa-list-ol"></i> What Happens Next?
                        </div>
                        <div class="pay-card-body" style="padding:1.1rem 1.4rem;">
                            <ol style="padding-left:1.1rem;margin:0;
                                       font-size:0.83rem;color:#555;line-height:2.1;">
                                <li>Payment processed securely via Stripe</li>
                                <li>Appointment marked as <strong>Paid</strong></li>
                                <li>Doctor receives notification to confirm</li>
                                <li>You receive a confirmation notification</li>
                                <li>Appointment status updated to <strong>Confirmed</strong></li>
                            </ol>
                        </div>
                    </div>

                </div>{{-- /sidebar-sticky --}}
            </div>

        </div>{{-- /row --}}
    </div>
</section>

@include('partials.footer')

{{-- ══ STRIPE JS ══ --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
/* ═══════════════════════════════════
   DATA
═══════════════════════════════════ */
const FEE_FULL = {{ $feeTotal }};
const FEE_ADV  = {{ $feeAdv }};
const FEE_REM  = {{ round($feeTotal - $feeAdv, 2) }};

let currentPayType = 'full';   // 'full' | 'advance'

/* ═══════════════════════════════════
   PAYMENT TYPE SWITCH
═══════════════════════════════════ */
function selectPayType(type) {
    currentPayType = type;

    // Update radio label styling
    document.querySelectorAll('.pay-type-opt').forEach(el => el.classList.remove('selected'));
    const radio = document.getElementById(type === 'full' ? 'payFull' : 'payAdv');
    radio.checked = true;
    radio.closest('.pay-type-opt').classList.add('selected');

    // Update hidden input
    document.getElementById('paymentTypeHidden').value = type;

    const isAdv = type === 'advance';
    const amt   = isAdv ? FEE_ADV : FEE_FULL;

    // Update hidden amount
    document.getElementById('amountHidden').value = amt;

    // Update button text
    const label = isAdv ? 'Pay Advance' : 'Pay Full';
    document.getElementById('payBtnText').textContent =
        label + '\u00a0Rs.\u00a0' + amt.toLocaleString('en-LK', {minimumFractionDigits: 2});

    // Advance note
    const advNote = document.getElementById('advNote');
    if (isAdv) {
        advNote.style.display = 'flex';
    } else {
        advNote.style.display = 'none';
    }

    // Summary sidebar fee
    document.getElementById('sumFeeAmt').textContent =
        'Rs. ' + amt.toLocaleString('en-LK', {minimumFractionDigits: 2});
    document.getElementById('sumFeeLabel').textContent =
        isAdv ? 'Advance Payment (50%)' : 'Total Amount Due (Full)';

    const noteEl = document.getElementById('sumFeeNote');
    if (isAdv) {
        noteEl.innerHTML =
            '<i class="fas fa-info-circle"></i>&nbsp; Balance Rs. ' +
            FEE_REM.toLocaleString('en-LK', {minimumFractionDigits: 2}) +
            ' due at appointment';
        noteEl.style.display = 'block';
    } else {
        noteEl.style.display = 'none';
    }
}

/* ═══════════════════════════════════
   PAY LATER CONFIRM
═══════════════════════════════════ */
function confirmPayLater() {
    return confirm(
        '⚠️ Pay Later Selected\n\n' +
        'Your appointment has been saved but will NOT be confirmed until payment is made.\n\n' +
        'You can pay anytime from My Appointments.\n\nContinue without paying now?'
    );
}

/* ═══════════════════════════════════
   STRIPE INIT
═══════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {

    const stripeKey = @json($stripeKey ?? null);

    if (!stripeKey) {
        showCardError('Stripe configuration error. Please contact support.');
        document.getElementById('payBtn').disabled = true;
        return;
    }

    const stripe   = Stripe(stripeKey);
    const elements = stripe.elements();

    const cardEl = elements.create('card', {
        hidePostalCode: false,
        style: {
            base: {
                fontSize: '15px',
                color: '#333333',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                fontSmoothing: 'antialiased',
                '::placeholder': { color: '#aab7c4' },
            },
            invalid: { color: '#dc3545', iconColor: '#dc3545' },
        },
    });
    cardEl.mount('#cardElement');

    // Focus / blur
    cardEl.on('focus', () => {
        document.getElementById('cardElement').classList.add('focused');
        document.getElementById('cardElement').classList.remove('stripe-error');
    });
    cardEl.on('blur',  () => {
        document.getElementById('cardElement').classList.remove('focused');
    });

    // Real-time validation
    cardEl.on('change', function (event) {
        if (event.error) {
            showCardError(event.error.message);
            document.getElementById('cardElement').classList.add('stripe-error');
        } else {
            hideCardError();
            document.getElementById('cardElement').classList.remove('stripe-error');
        }
    });

    /* ─ Form Submit ─ */
    const form    = document.getElementById('paymentForm');
    const payBtn  = document.getElementById('payBtn');
    const spinner = document.getElementById('paySpinner');
    const payIcon = document.getElementById('payIcon');
    const btnText = document.getElementById('payBtnText');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Name validation
        const name = document.getElementById('cardholderName').value.trim();
        if (!name) {
            document.getElementById('nameErr').classList.add('show');
            document.getElementById('cardholderName').focus();
            return;
        }
        document.getElementById('nameErr').classList.remove('show');

        setLoading(true);
        hideCardError();

        try {
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardEl,
                billing_details: { name },
            });

            if (error) {
                showCardError(error.message);
                document.getElementById('cardElement').classList.add('stripe-error');
                setLoading(false);
                return;
            }

            document.getElementById('paymentMethodId').value    = paymentMethod.id;
            document.getElementById('cardholderNameHidden').value = name;
            form.submit();

        } catch (err) {
            console.error('Stripe JS error:', err);
            showCardError('An unexpected error occurred. Please try again.');
            setLoading(false);
        }
    });

    /* ─ Helpers ─ */
    function setLoading(state) {
        payBtn.disabled       = state;
        spinner.style.display = state ? 'block' : 'none';
        payIcon.style.display = state ? 'none'  : 'inline';

        if (!state) {
            const isAdv = currentPayType === 'advance';
            const amt   = isAdv ? FEE_ADV : FEE_FULL;
            btnText.textContent = (isAdv ? 'Pay Advance' : 'Pay Full') +
                '\u00a0Rs.\u00a0' +
                amt.toLocaleString('en-LK', { minimumFractionDigits: 2 });
        } else {
            btnText.textContent = 'Processing...';
        }
    }

    function showCardError(msg) {
        document.getElementById('cardErrorMsg').textContent = msg;
        document.getElementById('cardErrors').classList.add('show');
    }

    function hideCardError() {
        document.getElementById('cardErrors').classList.remove('show');
    }

    // Clear name error on input
    document.getElementById('cardholderName').addEventListener('input', function () {
        document.getElementById('nameErr').classList.remove('show');
    });

    // Init button text
    selectPayType('full');
});
</script>

{{-- Auto-dismiss alerts --}}
<script>
setTimeout(() => {
    document.querySelectorAll('.pay-alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 6000);
</script>
