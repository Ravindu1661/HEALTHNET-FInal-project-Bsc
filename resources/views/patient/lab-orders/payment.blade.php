@include('partials.header')

<style>
/* ══════════════════════════════════════════
   LAB ORDER PAYMENT — Teal + Stripe Theme
══════════════════════════════════════════ */
.lop-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3.5rem; color: white;
    position: relative; overflow: hidden;
}
.lop-header::before {
    content:''; position:absolute; inset:0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.06;
}
.lop-header .container { position:relative; z-index:1; }
.lop-header::after {
    content:''; position:absolute; bottom:-1px; left:0; right:0;
    height:45px; background:#f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.lop-main { background:#f4f6f9; padding:2rem 0 4rem; min-height:600px; }

/* Summary Card */
.lop-summary-card {
    background: white; border-radius: 14px;
    box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    overflow: hidden; margin-bottom: 1.5rem;
}
.lop-card-header {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color:white; padding:1.1rem 1.5rem;
    display:flex; align-items:center; gap:0.6rem;
    font-weight:700; font-size:1rem;
}
.lop-card-body { padding:1.5rem; }

/* Info Rows */
.lop-info-row {
    display:flex; justify-content:space-between; align-items:flex-start;
    padding:0.65rem 0; border-bottom:1px solid #f0f9ff; font-size:0.9rem;
}
.lop-info-row:last-child { border-bottom:none; }
.lop-info-label { color:#888; display:flex; align-items:center; gap:0.5rem; white-space:nowrap; }
.lop-info-label i { color:#0891b2; width:16px; }
.lop-info-value { font-weight:600; color:#333; text-align:right; max-width:60%; }

/* Items mini list */
.lop-items-list { margin-top:0.5rem; }
.lop-item-row {
    display:flex; justify-content:space-between;
    padding:0.5rem 0; border-bottom:1px dashed #e0f2fe;
    font-size:0.82rem;
}
.lop-item-row:last-child { border-bottom:none; }
.lop-item-name { color:#555; }
.lop-item-price { font-weight:700; color:#0891b2; }

/* Total Box */
.lop-total-box {
    background: linear-gradient(135deg,rgba(8,145,178,0.08),rgba(8,145,178,0.15));
    border: 2px solid rgba(8,145,178,0.25);
    border-radius:12px; padding:1.2rem;
    text-align:center; margin-top:1.2rem;
}
.lop-total-label  { font-size:0.85rem; color:#666; margin-bottom:0.3rem; }
.lop-total-amount { font-size:2.5rem; font-weight:800; color:#0891b2; line-height:1; }
.lop-total-sub    { font-size:0.75rem; color:#aaa; margin-top:0.3rem; }

/* Payment Form Card */
.lop-form-card {
    background: white; border-radius:14px;
    box-shadow:0 6px 24px rgba(0,0,0,0.08);
    overflow:hidden;
}
.lop-form-header {
    background: linear-gradient(135deg,#059669,#047857);
    color:white; padding:1.1rem 1.5rem;
    display:flex; align-items:center; gap:0.6rem;
    font-weight:700; font-size:1rem;
}
.lop-form-body { padding:1.8rem; }

/* Test Mode Banner */
.test-banner {
    background:#fff3cd; border:1px solid #ffc107;
    border-radius:10px; padding:0.9rem 1rem;
    margin-bottom:1.3rem; font-size:0.85rem; color:#856404;
}
.test-banner code {
    background:rgba(0,0,0,0.07); padding:0.15rem 0.5rem;
    border-radius:4px; font-size:0.9em; letter-spacing:1px;
}

/* Form Labels + Inputs */
.lop-label {
    display:block; font-size:0.88rem; font-weight:700;
    color:#0c4a6e; margin-bottom:0.45rem;
}
.lop-input {
    width:100%; padding:0.72rem 1rem;
    border:2px solid #e9ecef; border-radius:10px;
    font-size:0.9rem; color:#333; transition:all 0.3s;
    background:white;
}
.lop-input:focus {
    border-color:#0891b2; outline:none;
    box-shadow:0 0 0 3px rgba(8,145,178,0.1);
}

/* Stripe Card Element */
.stripe-card-wrap {
    border:2px solid #e9ecef; border-radius:10px;
    padding:0.88rem 1rem; background:white;
    transition:border-color 0.3s; min-height:48px;
}
.stripe-card-wrap.focused {
    border-color:#0891b2;
    box-shadow:0 0 0 3px rgba(8,145,178,0.1);
}
.stripe-card-wrap.StripeElement--invalid {
    border-color:#dc2626;
    box-shadow:0 0 0 3px rgba(220,38,38,0.1);
}

/* Error Box */
.card-error-box {
    background:#fee2e2; color:#991b1b;
    border-left:4px solid #dc2626;
    padding:0.75rem 1rem; border-radius:8px;
    font-size:0.88rem; margin-top:0.8rem;
    display:none; align-items:center; gap:0.5rem;
}
.card-error-box.show { display:flex; }

/* Submit Button */
.lop-pay-btn {
    background:linear-gradient(135deg,#059669,#047857);
    color:white; border:none;
    padding:1rem 2rem; border-radius:12px;
    font-size:1.05rem; font-weight:700;
    cursor:pointer; transition:all 0.3s; width:100%;
    display:flex; align-items:center; justify-content:center; gap:0.6rem;
    box-shadow:0 4px 15px rgba(5,150,105,0.35); margin-top:1.5rem;
}
.lop-pay-btn:hover:not(:disabled) {
    transform:translateY(-2px);
    box-shadow:0 6px 22px rgba(5,150,105,0.45);
    filter:brightness(1.05);
}
.lop-pay-btn:disabled { opacity:0.75; cursor:not-allowed; transform:none; }

/* Spinner */
.pay-spinner {
    width:20px; height:20px;
    border:3px solid rgba(255,255,255,0.4);
    border-top-color:white; border-radius:50%;
    animation:spin 0.8s linear infinite;
    display:none; flex-shrink:0;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* Alert */
.lop-alert {
    border-radius:12px; padding:1rem 1.3rem;
    margin-bottom:1.3rem;
    display:flex; align-items:flex-start; gap:0.8rem;
    font-size:0.9rem;
}
.lop-alert.error   { background:#fee2e2; color:#991b1b; border-left:5px solid #dc2626; }
.lop-alert.success { background:#dcfce7; color:#166534; border-left:5px solid #059669; }
.lop-alert.info    { background:#e0f2fe; color:#0c4a6e; border-left:5px solid #0891b2; }

/* Security Row */
.security-row {
    display:flex; justify-content:center; gap:1.5rem;
    margin-top:1.2rem; flex-wrap:wrap;
}
.security-badge { display:flex; align-items:center; gap:0.4rem; font-size:0.78rem; color:#aaa; }
.security-badge i { color:#059669; }

/* Divider */
.or-divider {
    display:flex; align-items:center; gap:0.8rem;
    margin:1.5rem 0; font-size:0.82rem; color:#aaa;
}
.or-divider::before, .or-divider::after {
    content:''; flex:1; border-top:1px solid #e9ecef;
}

/* Back Link */
.back-lnk {
    color:rgba(255,255,255,0.9); text-decoration:none;
    font-size:0.88rem; display:inline-flex;
    align-items:center; gap:0.5rem; margin-bottom:1rem; transition:all 0.3s;
}
.back-lnk:hover { color:white; transform:translateX(-4px); }

@media (max-width:768px) {
    .lop-header { padding:5rem 0 2.5rem; }
    .lop-total-amount { font-size:2rem; }
}
</style>

{{-- ═══════════════════════════
     PAGE HEADER
═══════════════════════════ --}}
<section class="lop-header">
    <div class="container">
        <a href="{{ route('patient.lab-orders.show', $order->id) }}" class="back-lnk">
            <i class="fas fa-arrow-left"></i> Back to Order
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-credit-card me-2" style="opacity:0.85;"></i>
                    Complete Payment
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">
                    Secure payment for Lab Order
                    <strong style="background:rgba(255,255,255,0.2);padding:0.15rem 0.6rem;border-radius:8px;">
                        {{ $order->reference_number }}
                    </strong>
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════
     MAIN
═══════════════════════════ --}}
<section class="lop-main">
    <div class="container">

        @if(session('error'))
        <div class="lop-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if(session('success'))
        <div class="lop-alert success">
            <i class="fas fa-check-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="lop-alert info">
            <i class="fas fa-info-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        <div class="row g-4 justify-content-center">

            {{-- ══ LEFT: Order Summary ══ --}}
            <div class="col-lg-5">
                <div class="lop-summary-card">
                    <div class="lop-card-header">
                        <i class="fas fa-flask"></i> Order Summary
                    </div>
                    <div class="lop-card-body">

                        {{-- Lab Info --}}
                        <div style="display:flex;align-items:center;gap:0.9rem;padding-bottom:1rem;
                                    border-bottom:2px solid #f0f9ff;margin-bottom:1rem;">
                            <div style="width:52px;height:52px;border-radius:12px;
                                        background:linear-gradient(135deg,#0891b2,#0c4a6e);
                                        display:flex;align-items:center;justify-content:center;
                                        color:white;font-size:1.4rem;flex-shrink:0;">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#0c4a6e;font-size:1rem;">
                                    {{ $order->laboratory->name ?? 'Laboratory' }}
                                </div>
                                @if($order->laboratory->city)
                                <div style="font-size:0.78rem;color:#888;">
                                    <i class="fas fa-map-marker-alt me-1" style="color:#0891b2;"></i>
                                    {{ $order->laboratory->city }}
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Order Details --}}
                        <div class="lop-info-row">
                            <span class="lop-info-label"><i class="fas fa-hashtag"></i> Reference</span>
                            <span class="lop-info-value">{{ $order->reference_number }}</span>
                        </div>
                        <div class="lop-info-row">
                            <span class="lop-info-label"><i class="fas fa-calendar"></i> Order Date</span>
                            <span class="lop-info-value">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                            </span>
                        </div>
                        @if($order->collection_date)
                        <div class="lop-info-row">
                            <span class="lop-info-label"><i class="fas fa-calendar-check"></i> Collection</span>
                            <span class="lop-info-value">
                                {{ \Carbon\Carbon::parse($order->collection_date)->format('d M Y') }}
                            </span>
                        </div>
                        @endif
                        <div class="lop-info-row">
                            <span class="lop-info-label"><i class="fas fa-truck"></i> Type</span>
                            <span class="lop-info-value">
                                @if($order->home_collection)
                                    <span style="background:#e0f2fe;color:#0369a1;padding:0.15rem 0.5rem;border-radius:6px;font-size:0.78rem;font-weight:700;">
                                        <i class="fas fa-home me-1"></i>Home Collection
                                    </span>
                                @else
                                    <span style="background:#dcfce7;color:#166534;padding:0.15rem 0.5rem;border-radius:6px;font-size:0.78rem;font-weight:700;">
                                        <i class="fas fa-walking me-1"></i>Walk-In
                                    </span>
                                @endif
                            </span>
                        </div>

                        {{-- Items --}}
                        <div style="margin-top:1rem;">
                            <div style="font-size:0.8rem;font-weight:700;color:#0891b2;margin-bottom:0.5rem;">
                                <i class="fas fa-list-alt me-1"></i> Ordered Items
                            </div>
                            <div class="lop-items-list">
                                @foreach($order->items as $item)
                                <div class="lop-item-row">
                                    <span class="lop-item-name">{{ $item->item_name }}</span>
                                    <span class="lop-item-price">Rs. {{ number_format($item->price, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="lop-total-box">
                            <div class="lop-total-label">Total Amount Due</div>
                            <div class="lop-total-amount">
                                Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                            </div>
                            <div class="lop-total-sub">Sri Lankan Rupees (LKR)</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ══ RIGHT: Stripe Payment Form ══ --}}
            <div class="col-lg-6">
                <div class="lop-form-card">
                    <div class="lop-form-header">
                        <i class="fas fa-lock"></i> Secure Card Payment
                    </div>
                    <div class="lop-form-body">

                        {{-- Test Mode Banner --}}
                        <div class="test-banner">
                            <strong><i class="fas fa-vial me-1"></i> Test Mode Active</strong><br>
                            Use test card: <code>4242 4242 4242 4242</code><br>
                            Expiry: <code>12/26</code> &nbsp; CVC: <code>123</code> &nbsp; ZIP: <code>12345</code>
                        </div>

                        {{-- Payment Form --}}
                        <form id="labPaymentForm"
                              action="{{ route('patient.lab-orders.pay', $order->id) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" id="payment_method_id">
                            <input type="hidden" name="cardholder_name"   id="cardholder_name_hidden">

                            {{-- Cardholder Name --}}
                            <div style="margin-bottom:1.2rem;">
                                <label class="lop-label">
                                    Cardholder Name <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text"
                                       id="cardholderName"
                                       class="lop-input"
                                       placeholder="Full name on card"
                                       autocomplete="cc-name">
                                <div id="name-error"
                                     style="color:#dc2626;font-size:0.82rem;margin-top:0.3rem;display:none;">
                                    Please enter the cardholder name.
                                </div>
                            </div>

                            {{-- Stripe Card Element --}}
                            <div style="margin-bottom:0.5rem;">
                                <label class="lop-label">
                                    Card Details <span style="color:#dc2626;">*</span>
                                </label>
                                <div id="card-element" class="stripe-card-wrap">
                                    {{-- Stripe injects here --}}
                                </div>
                            </div>

                            {{-- Card Error --}}
                            <div id="card-errors" class="card-error-box" role="alert">
                                <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                                <span id="card-errors-msg"></span>
                            </div>

                            {{-- Divider --}}
                            <div class="or-divider">
                                <span>What happens next?</span>
                            </div>

                            {{-- Steps --}}
                            <div style="display:flex;flex-direction:column;gap:0.6rem;margin-bottom:1rem;">
                                @foreach([
                                    ['lock','Your payment is encrypted and secure'],
                                    ['check-circle','Order confirmed immediately after payment'],
                                    ['bell','You will receive a notification confirmation'],
                                    ['file-medical-alt','Download your report once ready'],
                                ] as [$ic, $txt])
                                <div style="display:flex;align-items:center;gap:0.7rem;font-size:0.82rem;color:#555;">
                                    <div style="width:28px;height:28px;border-radius:50%;
                                                background:linear-gradient(135deg,#0891b2,#0c4a6e);
                                                display:flex;align-items:center;justify-content:center;
                                                color:white;font-size:0.7rem;flex-shrink:0;">
                                        <i class="fas fa-{{ $ic }}"></i>
                                    </div>
                                    {{ $txt }}
                                </div>
                                @endforeach
                            </div>

                            {{-- Submit --}}
                            <button type="submit" id="payBtn" class="lop-pay-btn" disabled>
                                <div class="pay-spinner" id="paySpinner"></div>
                                <i class="fas fa-lock" id="payIcon"></i>
                                <span id="payBtnText">
                                    Pay Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                                </span>
                            </button>
                        </form>

                        {{-- Security Badges --}}
                        <div class="security-row">
                            <div class="security-badge"><i class="fas fa-shield-alt"></i> SSL Secured</div>
                            <div class="security-badge"><i class="fab fa-stripe"></i> Powered by Stripe</div>
                            <div class="security-badge"><i class="fas fa-lock"></i> PCI Compliant</div>
                        </div>

                        {{-- Pay Later --}}
                        <div style="text-align:center;margin-top:1rem;">
                            <a href="{{ route('patient.lab-orders.show', $order->id) }}"
                               style="color:#aaa;font-size:0.83rem;text-decoration:none;
                                      display:inline-flex;align-items:center;gap:0.4rem;">
                                <i class="fas fa-clock"></i> Pay later
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('partials.footer')

{{-- ═══════════════════════════
     STRIPE JS
═══════════════════════════ --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    var stripeKey = '{{ $stripeKey ?? "" }}';

    if (!stripeKey) {
        document.getElementById('card-errors').classList.add('show');
        document.getElementById('card-errors-msg').textContent =
            'Stripe configuration error. Please contact support.';
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
                color:     '#dc2626',
                iconColor: '#dc2626',
            },
        },
    });

    cardElement.mount('#card-element');

    // ── Enable Pay button once card is complete ──
    cardElement.on('change', function (event) {
        var errDiv = document.getElementById('card-errors');
        var errMsg = document.getElementById('card-errors-msg');
        var payBtn = document.getElementById('payBtn');

        if (event.error) {
            errMsg.textContent = event.error.message;
            errDiv.classList.add('show');
        } else {
            errDiv.classList.remove('show');
            errMsg.textContent = '';
        }

        // Enable button only when card is complete
        if (event.complete) {
            payBtn.disabled = false;
            payBtn.style.opacity = '1';
        } else {
            payBtn.disabled = true;
            payBtn.style.opacity = '0.75';
        }
    });

    // ── Focus / Blur ──
    cardElement.on('focus', function () {
        document.getElementById('card-element').classList.add('focused');
    });
    cardElement.on('blur', function () {
        document.getElementById('card-element').classList.remove('focused');
    });

    // ── Form Submit ──
    var form    = document.getElementById('labPaymentForm');
    var payBtn  = document.getElementById('payBtn');
    var spinner = document.getElementById('paySpinner');
    var payIcon = document.getElementById('payIcon');
    var btnText = document.getElementById('payBtnText');
    var amount  = 'Rs. {{ number_format($order->total_amount ?? 0, 2) }}';

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        var name      = document.getElementById('cardholderName').value.trim();
        var nameError = document.getElementById('name-error');

        if (!name) {
            nameError.style.display = 'block';
            document.getElementById('cardholderName').focus();
            return;
        }
        nameError.style.display = 'none';

        // ── Show loading ──
        payBtn.disabled       = true;
        spinner.style.display = 'block';
        payIcon.style.display = 'none';
        btnText.textContent   = 'Processing...';

        try {
            var result = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: { name: name },
            });

            if (result.error) {
                var errDiv = document.getElementById('card-errors');
                var errMsg = document.getElementById('card-errors-msg');
                errMsg.textContent = result.error.message;
                errDiv.classList.add('show');

                payBtn.disabled       = false;
                spinner.style.display = 'none';
                payIcon.style.display = 'inline';
                btnText.textContent   = 'Pay ' + amount;
                return;
            }

            // ── Set hidden fields ──
            document.getElementById('payment_method_id').value    = result.paymentMethod.id;
            document.getElementById('cardholder_name_hidden').value = name;

            // ── Submit to server ──
            form.submit();

        } catch (err) {
            console.error('Stripe JS error:', err);
            payBtn.disabled       = false;
            spinner.style.display = 'none';
            payIcon.style.display = 'inline';
            btnText.textContent   = 'Pay ' + amount;

            var errDiv = document.getElementById('card-errors');
            var errMsg = document.getElementById('card-errors-msg');
            errMsg.textContent = 'An unexpected error occurred. Please try again.';
            errDiv.classList.add('show');
        }
    });

    // ── Name input — clear error on type ──
    document.getElementById('cardholderName').addEventListener('input', function () {
        document.getElementById('name-error').style.display = 'none';
    });

    // ── Auto dismiss alerts ──
    setTimeout(() => {
        document.querySelectorAll('.lop-alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 6000);

});
</script>
