@include('partials.header')

<style>
.pay-page-header {
    background: linear-gradient(135deg, var(--primary-color, #1a5276) 0%, var(--secondary-color, #2e86c1) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.pay-page-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.pay-main { background: #f4f6f9; padding: 2rem 0 4rem; min-height: 600px; }

/* Summary */
.pay-summary-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}
.pay-summary-header {
    background: linear-gradient(135deg, var(--primary-color,#1a5276), var(--secondary-color,#2e86c1));
    color: white;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 700;
    font-size: 1rem;
}
.pay-summary-body { padding: 1.5rem; }
.pay-doctor-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding-bottom: 1.2rem;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 1.2rem;
}
.pay-doctor-avatar {
    width: 65px; height: 65px;
    border-radius: 50%; overflow: hidden;
    border: 3px solid var(--accent-color,#42a649);
    flex-shrink: 0;
}
.pay-doctor-avatar img { width:100%; height:100%; object-fit:cover; }
.pay-doctor-name { font-size:1.1rem; font-weight:700; color:var(--primary-color,#1a5276); }
.pay-doctor-spec { font-size:0.85rem; color:var(--accent-color,#42a649); font-weight:600; }
.pay-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0;
    border-bottom: 1px solid #f5f5f5;
    font-size: 0.9rem;
}
.pay-info-row:last-child { border-bottom: none; }
.pay-info-label { color:#888; display:flex; align-items:center; gap:0.5rem; }
.pay-info-label i { color:var(--accent-color,#42a649); width:16px; }
.pay-info-value { font-weight:600; color:#333; text-align:right; max-width:55%; }
.pay-fee-total {
    background: linear-gradient(135deg, rgba(66,166,73,0.08), rgba(66,166,73,0.15));
    border: 2px solid rgba(66,166,73,0.3);
    border-radius: 12px;
    padding: 1.2rem;
    text-align: center;
    margin-top: 1.2rem;
}
.pay-fee-label { font-size:0.85rem; color:#666; margin-bottom:0.3rem; }
.pay-fee-amount { font-size:2.2rem; font-weight:700; color:var(--accent-color,#42a649); line-height:1; }

/* Form Card */
.pay-form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    overflow: hidden;
}
.pay-form-header {
    background: linear-gradient(135deg, var(--accent-color,#42a649), #2d7a32);
    color: white;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 700;
    font-size: 1rem;
}
.pay-form-body { padding: 1.8rem; }

/* Stripe Element */
.stripe-card-wrapper {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.9rem 1rem;
    background: white;
    transition: border-color 0.3s ease;
    min-height: 48px;
}
.stripe-card-wrapper.focused {
    border-color: var(--accent-color,#42a649);
    box-shadow: 0 0 0 3px rgba(66,166,73,0.1);
}
.stripe-card-wrapper.StripeElement--invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
}

/* Fields */
.pay-label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--primary-color,#1a5276);
    margin-bottom: 0.45rem;
}
.pay-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
    color: #333;
}
.pay-input:focus {
    border-color: var(--accent-color,#42a649);
    outline: none;
    box-shadow: 0 0 0 3px rgba(66,166,73,0.1);
}

/* Error box */
.card-error-box {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.88rem;
    margin-top: 0.8rem;
    display: none;
    align-items: center;
    gap: 0.5rem;
}
.card-error-box.show { display: flex; }

/* Submit */
.btn-pay-submit {
    background: linear-gradient(135deg, var(--accent-color,#42a649), #2d7a32);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 25px;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    box-shadow: 0 4px 15px rgba(66,166,73,0.35);
    margin-top: 1.5rem;
}
.btn-pay-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(66,166,73,0.45);
}
.btn-pay-submit:disabled { opacity: 0.75; cursor: not-allowed; transform: none; }

/* Spinner */
.pay-spinner {
    width: 20px; height: 20px;
    border: 3px solid rgba(255,255,255,0.4);
    border-top-color: white;
    border-radius: 50%;
    animation: paySpin 0.8s linear infinite;
    display: none;
    flex-shrink: 0;
}
@keyframes paySpin { to { transform: rotate(360deg); } }

/* Test banner */
.test-banner {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 10px;
    padding: 0.9rem 1rem;
    margin-bottom: 1.3rem;
    font-size: 0.85rem;
    color: #856404;
}
.test-banner code {
    background: rgba(0,0,0,0.08);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9em;
    letter-spacing: 1px;
}

/* Alert */
.pay-alert {
    border-radius: 10px;
    padding: 1rem 1.2rem;
    margin-bottom: 1.3rem;
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
    font-size: 0.9rem;
}
.pay-alert.error   { background:#f8d7da; color:#721c24; border-left:4px solid #dc3545; }
.pay-alert.success { background:#d4edda; color:#155724; border-left:4px solid #42a649; }
.pay-alert.info    { background:#d1ecf1; color:#0c5460; border-left:4px solid #17a2b8; }

/* Security badges */
.security-row {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 1.2rem;
    flex-wrap: wrap;
}
.security-badge { display:flex; align-items:center; gap:0.4rem; font-size:0.78rem; color:#999; }
.security-badge i { color:var(--accent-color,#42a649); }

/* Back btn */
.back-btn-pay {
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s;
}
.back-btn-pay:hover { color: white; transform: translateX(-4px); }

@media (max-width:768px) {
    .pay-page-header { padding: 5rem 0 2.5rem; }
    .pay-doctor-row { flex-direction: column; text-align: center; }
}
</style>

{{-- HEADER --}}
<section class="pay-page-header">
    <div class="container">
        <a href="{{ route('patient.appointments.index') }}" class="back-btn-pay">
            <i class="fas fa-arrow-left"></i> My Appointments
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-credit-card me-2" style="opacity:0.85;"></i>
                    Complete Payment
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">Secure payment powered by Stripe</p>
            </div>
        </div>
    </div>
</section>

{{-- MAIN --}}
<section class="pay-main">
    <div class="container">

        @if(session('error'))
        <div class="pay-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('success'))
        <div class="pay-alert success">
            <i class="fas fa-check-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="pay-alert info">
            <i class="fas fa-info-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        <div class="row g-4 justify-content-center">

            {{-- LEFT: Summary --}}
            <div class="col-lg-5">
                <div class="pay-summary-card">
                    <div class="pay-summary-header">
                        <i class="fas fa-calendar-check"></i> Appointment Summary
                    </div>
                    <div class="pay-summary-body">
                        @php
                            $doctor     = $appointment->doctor;
                            $profileImg = ($doctor && $doctor->profile_image)
                                ? asset('storage/' . $doctor->profile_image)
                                : asset('images/default-avatar.png');
                            $apptDate   = \Carbon\Carbon::parse($appointment->appointment_date);
                            $apptTime   = \Carbon\Carbon::parse($appointment->appointment_time);

                            $wpName = 'Not Available';
                            $wpIcon = 'fas fa-clinic-medical';
                            if ($workplace) {
                                if ($workplace->workplace_type === 'hospital' && $workplace->hospital) {
                                    $wpName = $workplace->hospital->name;
                                    $wpIcon = 'fas fa-hospital';
                                } elseif ($workplace->workplace_type === 'medical_centre' && $workplace->medicalCentre) {
                                    $wpName = $workplace->medicalCentre->name;
                                } elseif ($workplace->workplace_type === 'private') {
                                    $wpName = 'Private Practice';
                                    $wpIcon = 'fas fa-user-md';
                                }
                            }
                        @endphp

                        <div class="pay-doctor-row">
                            <div class="pay-doctor-avatar">
                                <img src="{{ $profileImg }}" alt="Doctor"
                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            </div>
                            <div>
                                <div class="pay-doctor-name">
                                    Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? '' }}
                                </div>
                                <div class="pay-doctor-spec">
                                    {{ $doctor->specialization ?? 'General Practitioner' }}
                                </div>
                                @if(($doctor->status ?? '') === 'approved')
                                <span style="font-size:0.75rem;background:#d4edda;color:#155724;padding:0.2rem 0.7rem;border-radius:10px;font-weight:600;display:inline-block;margin-top:0.3rem;">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="pay-info-row">
                            <span class="pay-info-label"><i class="fas fa-hashtag"></i> Ref No.</span>
                            <span class="pay-info-value">
                                {{ $appointment->appointment_number ?? 'APT-' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="pay-info-row">
                            <span class="pay-info-label"><i class="fas fa-calendar"></i> Date</span>
                            <span class="pay-info-value">{{ $apptDate->format('D, d M Y') }}</span>
                        </div>
                        <div class="pay-info-row">
                            <span class="pay-info-label"><i class="fas fa-clock"></i> Time</span>
                            <span class="pay-info-value">{{ $apptTime->format('h:i A') }}</span>
                        </div>
                        <div class="pay-info-row">
                            <span class="pay-info-label"><i class="{{ $wpIcon }}"></i> Location</span>
                            <span class="pay-info-value">{{ $wpName }}</span>
                        </div>
                        @if($appointment->reason)
                        <div class="pay-info-row">
                            <span class="pay-info-label"><i class="fas fa-notes-medical"></i> Reason</span>
                            <span class="pay-info-value">{{ Str::limit($appointment->reason, 35) }}</span>
                        </div>
                        @endif

                        <div class="pay-fee-total">
                            <div class="pay-fee-label">Total Amount Due</div>
                            <div class="pay-fee-amount">
                                Rs. {{ number_format($appointment->consultation_fee ?? 0, 2) }}
                            </div>
                            <div style="font-size:0.75rem;color:#999;margin-top:0.3rem;">
                                Sri Lankan Rupees (LKR)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Payment Form --}}
            <div class="col-lg-6">
                <div class="pay-form-card">
                    <div class="pay-form-header">
                        <i class="fas fa-lock"></i> Secure Card Payment
                    </div>
                    <div class="pay-form-body">

                        {{-- Test Mode Banner --}}
                        <div class="test-banner">
                            <strong><i class="fas fa-vial me-1"></i> Test Mode Active</strong><br>
                            Use test card: <code>4242 4242 4242 4242</code><br>
                            Expiry: <code>12/26</code> &nbsp; CVC: <code>123</code> &nbsp; ZIP: <code>12345</code>
                        </div>

                        <form id="paymentForm"
                              action="{{ route('patient.appointments.pay', $appointment->id) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" id="payment_method_id">
                            <input type="hidden" name="cardholder_name"   id="cardholder_name_hidden">

                            {{-- Cardholder Name --}}
                            <div style="margin-bottom:1.2rem;">
                                <label class="pay-label">
                                    Cardholder Name <span style="color:#dc3545;">*</span>
                                </label>
                                <input type="text"
                                       id="cardholderName"
                                       class="pay-input"
                                       placeholder="Full name on card"
                                       autocomplete="cc-name">
                                <div id="name-error" style="color:#dc3545;font-size:0.82rem;margin-top:0.3rem;display:none;">
                                    Please enter the cardholder name.
                                </div>
                            </div>

                            {{-- Stripe Card Element --}}
                            <div style="margin-bottom:0.5rem;">
                                <label class="pay-label">
                                    Card Details <span style="color:#dc3545;">*</span>
                                </label>
                                <div id="card-element" class="stripe-card-wrapper">
                                    {{-- Stripe mounts here --}}
                                </div>
                            </div>

                            {{-- Card Errors --}}
                            <div id="card-errors" class="card-error-box" role="alert">
                                <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                                <span id="card-errors-msg"></span>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" id="payBtn" class="btn-pay-submit">
                                <div class="pay-spinner" id="paySpinner"></div>
                                <i class="fas fa-lock" id="payIcon"></i>
                                <span id="payBtnText">
                                    Confirm & Pay &nbsp;Rs.&nbsp;{{ number_format($appointment->consultation_fee ?? 0, 2) }}
                                </span>
                            </button>
                        </form>

                        {{-- Security Badges --}}
                        <div class="security-row">
                            <div class="security-badge"><i class="fas fa-shield-alt"></i> SSL Secured</div>
                            <div class="security-badge"><i class="fab fa-stripe"></i> Powered by Stripe</div>
                            <div class="security-badge"><i class="fas fa-lock"></i> PCI Compliant</div>
                        </div>

                        <div style="text-align:center;margin-top:1.2rem;">
                            <a href="{{ route('patient.appointments.index') }}"
                               style="color:#aaa;font-size:0.83rem;text-decoration:none;">
                                <i class="fas fa-clock me-1"></i> Pay later
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

{{-- Stripe JS --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    var stripeKey = '{{ $stripeKey ?? "" }}';

    if (!stripeKey) {
        document.getElementById('card-errors').classList.add('show');
        document.getElementById('card-errors-msg').textContent = 'Stripe configuration error. Please contact support.';
        document.getElementById('payBtn').disabled = true;
        return;
    }

    // ── Init Stripe ──
    var stripe   = Stripe(stripeKey);
    var elements = stripe.elements();

    var cardElement = elements.create('card', {
        hidePostalCode: false,
        style: {
            base: {
                fontSize:       '15px',
                color:          '#333333',
                fontFamily:     '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                fontSmoothing:  'antialiased',
                '::placeholder': { color: '#aab7c4' },
            },
            invalid: {
                color:     '#dc3545',
                iconColor: '#dc3545',
            },
        },
    });

    cardElement.mount('#card-element');

    // ── Focus / Blur ──
    cardElement.on('focus', function () {
        document.getElementById('card-element').classList.add('focused');
    });
    cardElement.on('blur', function () {
        document.getElementById('card-element').classList.remove('focused');
    });

    // ── Real-time validation ──
    cardElement.on('change', function (event) {
        var errDiv = document.getElementById('card-errors');
        var errMsg = document.getElementById('card-errors-msg');

        if (event.error) {
            errMsg.textContent = event.error.message;
            errDiv.classList.add('show');
        } else {
            errDiv.classList.remove('show');
            errMsg.textContent = '';
        }
    });

    // ── Form submit ──
    var form    = document.getElementById('paymentForm');
    var payBtn  = document.getElementById('payBtn');
    var spinner = document.getElementById('paySpinner');
    var payIcon = document.getElementById('payIcon');
    var btnText = document.getElementById('payBtnText');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        var cardholderName = document.getElementById('cardholderName').value.trim();
        var nameError      = document.getElementById('name-error');

        // Name validate
        if (!cardholderName) {
            nameError.style.display = 'block';
            document.getElementById('cardholderName').focus();
            return;
        }
        nameError.style.display = 'none';

        // ── Loading ──
        payBtn.disabled          = true;
        spinner.style.display    = 'block';
        payIcon.style.display    = 'none';
        btnText.textContent      = 'Processing...';

        try {
            // ── Create PaymentMethod ──
            var result = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: { name: cardholderName },
            });

            if (result.error) {
                // Show error
                var errDiv = document.getElementById('card-errors');
                var errMsg = document.getElementById('card-errors-msg');
                errMsg.textContent = result.error.message;
                errDiv.classList.add('show');

                // Reset button
                payBtn.disabled       = false;
                spinner.style.display = 'none';
                payIcon.style.display = 'inline';
                btnText.textContent   = 'Confirm & Pay Rs. {{ number_format($appointment->consultation_fee ?? 0, 2) }}';
                return;
            }

            // ── Set hidden inputs ──
            document.getElementById('payment_method_id').value     = result.paymentMethod.id;
            document.getElementById('cardholder_name_hidden').value = cardholderName;

            // ── Submit to server ──
            form.submit();

        } catch (err) {
            console.error('Stripe JS error:', err);

            payBtn.disabled       = false;
            spinner.style.display = 'none';
            payIcon.style.display = 'inline';
            btnText.textContent   = 'Confirm & Pay Rs. {{ number_format($appointment->consultation_fee ?? 0, 2) }}';

            var errDiv = document.getElementById('card-errors');
            var errMsg = document.getElementById('card-errors-msg');
            errMsg.textContent = 'An unexpected error occurred. Please try again.';
            errDiv.classList.add('show');
        }
    });

    // ── Name field border reset ──
    document.getElementById('cardholderName').addEventListener('input', function () {
        document.getElementById('name-error').style.display = 'none';
        this.style.borderColor = '';
    });

});
</script>
