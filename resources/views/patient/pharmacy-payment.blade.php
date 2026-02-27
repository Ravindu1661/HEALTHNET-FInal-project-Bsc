@include('partials.header')
<style>
.lop-header{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:6rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.lop-header::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.07;z-index:0}
.lop-header .container{position:relative;z-index:1}
.lop-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.lop-main{background:#f4f6f9;padding:2.2rem 0 3rem}
.lop-summary-card,.lop-form-card{background:#fff;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,.07);overflow:hidden}
.lop-card-header{background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:1rem 1.5rem;font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:.5rem}
.lop-card-body{padding:1.5rem}
.lop-form-header{background:linear-gradient(135deg,#1565c0,#0d47a1);color:#fff;padding:1rem 1.5rem;font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:.5rem}
.lop-form-body{padding:1.5rem}
.lop-label{display:block;font-size:.82rem;font-weight:600;color:#444;margin-bottom:.4rem}
.lop-input{width:100%;padding:.65rem .85rem;border:1.5px solid #e0f2f1;border-radius:9px;font-size:.9rem;transition:border .3s}
.lop-input:focus{border-color:#1565c0;outline:none;box-shadow:0 0 0 3px rgba(21,101,192,.1)}
#card-element{border:1.5px solid #e0f2f1;border-radius:9px;padding:.75rem .85rem;transition:border .3s;background:#fff}
#card-element.focused{border-color:#1565c0;box-shadow:0 0 0 3px rgba(21,101,192,.1)}
.card-errors{display:none;align-items:center;gap:.5rem;background:#fff5f5;border:1px solid #fed7d7;border-radius:8px;padding:.65rem 1rem;margin-top:.6rem;color:#c53030;font-size:.83rem;font-weight:500}
.card-errors.show{display:flex}
.lop-pay-btn{background:linear-gradient(135deg,#1565c0,#0d47a1);color:#fff;border:none;border-radius:10px;padding:1rem 2rem;font-weight:700;font-size:1rem;width:100%;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.7rem;transition:all .3s;box-shadow:0 4px 14px rgba(21,101,192,.3)}
.lop-pay-btn:hover:not(:disabled){filter:brightness(1.1);transform:translateY(-2px)}
.lop-pay-btn:disabled{opacity:.75;cursor:not-allowed}
.pay-spinner{width:18px;height:18px;border:2.5px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none}
@keyframes spin{to{transform:rotate(360deg)}}
.security-row{display:flex;justify-content:center;gap:.8rem;flex-wrap:wrap;margin-top:1rem}
.security-badge{display:flex;align-items:center;gap:.3rem;font-size:.73rem;color:#888;font-weight:600}
.security-badge i{color:#00796b}
.test-banner{background:linear-gradient(135deg,#fef3c7,#fde68a);border:1.5px solid #f59e0b;border-radius:10px;padding:.9rem 1.1rem;margin-bottom:1.2rem;font-size:.82rem;color:#92400e;font-weight:500}
.lop-item-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f0f4f0;font-size:.85rem}
.lop-item-row:last-child{border:none}
.lop-total-box{background:linear-gradient(135deg,#e0f2f1,#b2dfdb);border-radius:10px;padding:1rem 1.2rem;margin-top:1rem}
.lop-total-label{font-size:.78rem;color:#555;font-weight:600}
.lop-total-amount{font-size:1.6rem;font-weight:800;color:#00796b}
.lop-alert{border-radius:10px;padding:.85rem 1.1rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.7rem;font-size:.88rem;font-weight:500}
.lop-alert.error{background:#fff5f5;color:#c53030;border-left:4px solid #fc8181}
.lop-alert.success{background:#f0fff4;color:#276749;border-left:4px solid #68d391}
.lop-alert.info{background:#ebf8ff;color:#2c5282;border-left:4px solid #63b3ed}
.o-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:14px;font-size:.72rem;font-weight:700}
.o-pill.pending{background:#fef3c7;color:#92400e}.o-pill.verified{background:#e0f2fe;color:#0369a1}.o-pill.processing{background:#ede9fe;color:#4c1d95}.o-pill.ready{background:#dcfce7;color:#166534}
</style>

<section class="lop-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.track', $order->pharmacy_id) }}" style="color:rgba(255,255,255,.85);font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none"><i class="fas fa-arrow-left"></i> Back to Orders</a>
        <h1 style="font-size:2rem;font-weight:700;margin-bottom:.4rem"><i class="fas fa-credit-card me-2" style="opacity:.85"></i>Complete Payment</h1>
        <p style="opacity:.9;font-size:.95rem;margin:0">Secure payment for Pharmacy Order <strong style="background:rgba(255,255,255,.2);padding:.15rem .6rem;border-radius:8px">{{ $order->order_number }}</strong></p>
    </div>
</section>

<section class="lop-main">
    <div class="container">
        @foreach(['error','success','info'] as $t)
            @if(session($t))
            <div class="lop-alert {{ $t }}"><i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }} fa-lg" style="flex-shrink:0;margin-top:2px"></i><span>{{ session($t) }}</span></div>
            @endif
        @endforeach

        <div class="row g-4 justify-content-center">
            {{-- LEFT — Order Summary --}}
            <div class="col-lg-5">
                <div class="lop-summary-card">
                    <div class="lop-card-header"><i class="fas fa-pills"></i> Order Summary</div>
                    <div class="lop-card-body">
                        <div style="display:flex;align-items:center;gap:.9rem;padding-bottom:1rem;border-bottom:2px solid #f0fdf4;margin-bottom:1rem">
                            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#00796b,#004d40);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;flex-shrink:0"><i class="fas fa-pills"></i></div>
                            <div>
                                <div style="font-weight:700;color:#004d40;font-size:1rem">{{ $order->pharmacy->name??'Pharmacy' }}</div>
                                @if($order->pharmacy->city) <div style="font-size:.78rem;color:#888"><i class="fas fa-map-marker-alt me-1" style="color:#00796b"></i>{{ $order->pharmacy->city }}</div> @endif
                            </div>
                        </div>
                        <div style="font-size:.8rem;font-weight:700;color:#00796b;margin-bottom:.5rem">Order Details</div>
                        <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:.4rem 0;border-bottom:1px solid #f5f5f5"><span style="color:#666">Order #</span><strong>{{ $order->order_number }}</strong></div>
                        <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:.4rem 0;border-bottom:1px solid #f5f5f5"><span style="color:#666">Date</span><span>{{ optional($order->created_at)->format('d M Y') }}</span></div>
                        <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:.4rem 0;border-bottom:1px solid #f5f5f5"><span style="color:#666">Status</span><span class="o-pill {{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
                        <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:.4rem 0;border-bottom:1px solid #f5f5f5"><span style="color:#666">Delivery</span><span>{{ $order->delivery_address==='PICKUP'?'Pickup at Store':($order->delivery_method?ucfirst($order->delivery_method):'Delivery') }}</span></div>

                        @if($order->items->count())
                        <div style="font-size:.8rem;font-weight:700;color:#00796b;margin:.9rem 0 .5rem"><i class="fas fa-list-alt me-1"></i>Ordered Items</div>
                        @foreach($order->items as $item)
                        <div class="lop-item-row">
                            <span style="color:#333">{{ $item->medication_name }} × {{ $item->quantity }}</span>
                            <span style="font-weight:600">LKR {{ number_format($item->price,2) }}</span>
                        </div>
                        @endforeach
                        @endif

                        <div class="lop-total-box mt-3">
                            <div class="lop-total-label">Total Amount Due</div>
                            <div class="lop-total-amount">LKR {{ number_format($order->total_amount + $order->delivery_fee, 2) }}</div>
                            @if($order->delivery_fee > 0) <div style="font-size:.78rem;color:#555">Includes delivery fee: LKR {{ number_format($order->delivery_fee,2) }}</div> @endif
                            <div style="font-size:.73rem;color:#555">Sri Lankan Rupees (LKR)</div>
                        </div>

                        <div style="margin-top:1rem">
                            @foreach(['lock,Your payment is encrypted and secure','check-circle,Order confirmed immediately after payment','bell,WhatsApp/Email notification sent','prescription,Medicines dispatched after payment'] as $item)
                            @php [$ic,$txt] = explode(',',$item,2) @endphp
                            <div style="display:flex;align-items:center;gap:.7rem;font-size:.82rem;color:#555;margin-bottom:.5rem">
                                <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#00796b,#004d40);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;flex-shrink:0"><i class="fas fa-{{ $ic }}"></i></div>
                                {{ $txt }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT — Stripe Payment Form --}}
            <div class="col-lg-6">
                <div class="lop-form-card">
                    <div class="lop-form-header"><i class="fas fa-lock"></i> Secure Card Payment</div>
                    <div class="lop-form-body">
                        {{-- Test Mode Banner --}}
                        @if(app()->environment('local','testing'))
                        <div class="test-banner">
                            <strong><i class="fas fa-vial me-1"></i>Test Mode Active</strong><br>
                            Use test card: <code>4242 4242 4242 4242</code><br>
                            Expiry: <code>12/26</code> &nbsp; CVC: <code>123</code> &nbsp; ZIP: <code>12345</code>
                        </div>
                        @endif

                        <form id="pharmPaymentForm" action="{{ route('patient.pharmacies.pay', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" id="paymentMethodId">
                            <input type="hidden" name="cardholder_name" id="cardholderNameHidden">

                            <div style="margin-bottom:1.2rem">
                                <label class="lop-label">Cardholder Name <span style="color:#dc2626">*</span></label>
                                <input type="text" id="cardholderName" class="lop-input" placeholder="Full name on card" autocomplete="cc-name">
                                <div id="name-error" style="color:#dc2626;font-size:.82rem;margin-top:.3rem;display:none">Please enter the cardholder name.</div>
                            </div>

                            <div style="margin-bottom:1.2rem">
                                <label class="lop-label">Card Details <span style="color:#dc2626">*</span></label>
                                <div id="card-element"></div>
                                <div class="card-errors" id="card-errors"><i class="fas fa-exclamation-circle"></i><span id="card-errors-msg"></span></div>
                            </div>

                            <button type="submit" class="lop-pay-btn" id="payBtn" disabled>
                                <div class="pay-spinner" id="paySpinner"></div>
                                <i class="fas fa-lock" id="payIcon"></i>
                                <span id="payBtnText">Pay LKR {{ number_format($order->total_amount + $order->delivery_fee, 2) }}</span>
                            </button>
                        </form>

                        <div class="security-row">
                            <span class="security-badge"><i class="fas fa-shield-alt"></i>SSL Secured</span>
                            <span class="security-badge"><i class="fab fa-stripe"></i>Powered by Stripe</span>
                            <span class="security-badge"><i class="fas fa-lock"></i>PCI Compliant</span>
                        </div>

                        <div style="text-align:center;margin-top:1rem">
                            <a href="{{ route('patient.pharmacies.track', $order->pharmacy_id) }}" style="color:#aaa;font-size:.83rem;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem"><i class="fas fa-clock"></i>Pay later</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var stripeKey = '{{ $stripeKey ?? "" }}';
    if (!stripeKey) {
        document.getElementById('card-errors').classList.add('show');
        document.getElementById('card-errors-msg').textContent = 'Stripe configuration error. Please contact support.';
        document.getElementById('payBtn').disabled = true;
        return;
    }

    var stripe   = Stripe(stripeKey);
    var elements = stripe.elements({ fonts: [{cssSrc:'https://fonts.googleapis.com/css?family=Roboto'}] });

    var cardElement = elements.create('card', {
        hidePostalCode: false,
        style: {
            base: { fontSize:'15px', color:'#333333', fontFamily:'-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif', fontSmoothing:'antialiased', '::placeholder':{color:'#aab7c4'} },
            invalid: { color:'#dc2626', iconColor:'#dc2626' }
        }
    });
    cardElement.mount('#card-element');

    cardElement.on('change', function(e) {
        var errDiv = document.getElementById('card-errors');
        var errMsg = document.getElementById('card-errors-msg');
        var payBtn = document.getElementById('payBtn');
        if (e.error) {
            errMsg.textContent = e.error.message;
            errDiv.classList.add('show');
        } else {
            errDiv.classList.remove('show');
            errMsg.textContent = '';
        }
        payBtn.disabled = !e.complete;
        payBtn.style.opacity = e.complete ? '1' : '0.75';
    });

    cardElement.on('focus', function() { document.getElementById('card-element').classList.add('focused'); });
    cardElement.on('blur',  function() { document.getElementById('card-element').classList.remove('focused'); });

    var form    = document.getElementById('pharmPaymentForm');
    var payBtn  = document.getElementById('payBtn');
    var spinner = document.getElementById('paySpinner');
    var payIcon = document.getElementById('payIcon');
    var btnText = document.getElementById('payBtnText');
    var amount  = 'LKR {{ number_format($order->total_amount + $order->delivery_fee, 2) }}';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        var name = document.getElementById('cardholderName').value.trim();
        var nameErr = document.getElementById('name-error');
        if (!name) { nameErr.style.display='block'; document.getElementById('cardholderName').focus(); return; }
        nameErr.style.display = 'none';

        payBtn.disabled = true;
        spinner.style.display = 'block';
        payIcon.style.display = 'none';
        btnText.textContent = 'Processing...';

        try {
            var result = await stripe.createPaymentMethod({
                type: 'card', card: cardElement, billing_details: { name: name }
            });
            if (result.error) {
                var errDiv = document.getElementById('card-errors');
                document.getElementById('card-errors-msg').textContent = result.error.message;
                errDiv.classList.add('show');
                payBtn.disabled = false;
                spinner.style.display = 'none';
                payIcon.style.display = 'inline';
                btnText.textContent = 'Pay ' + amount;
                return;
            }
            document.getElementById('paymentMethodId').value = result.paymentMethod.id;
            document.getElementById('cardholderNameHidden').value = name;
            form.submit();
        } catch(err) {
            console.error('Stripe JS error', err);
            document.getElementById('card-errors-msg').textContent = 'An unexpected error occurred. Please try again.';
            document.getElementById('card-errors').classList.add('show');
            payBtn.disabled = false;
            spinner.style.display = 'none';
            payIcon.style.display = 'inline';
            btnText.textContent = 'Pay ' + amount;
        }
    });

    document.getElementById('cardholderName').addEventListener('input', function() {
        document.getElementById('name-error').style.display = 'none';
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        document.querySelectorAll('.lop-alert').forEach(function(el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        });
    }, 6000);
});
</script>
