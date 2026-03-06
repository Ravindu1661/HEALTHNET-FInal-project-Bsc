@include('partials.header')

<style>
.po-header{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:6rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.po-header::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.07;z-index:0}
.po-header .container{position:relative;z-index:1}
.po-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.po-body{background:#f0f4f8;padding:1.8rem 0 2.5rem}

.po-card{background:#fff;border-radius:12px;padding:1.2rem 1.4rem;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:1.1rem}
.po-card-title{font-size:.88rem;font-weight:700;color:#00796b;padding-bottom:.55rem;border-bottom:1.5px solid #e0f2f1;margin-bottom:1rem;display:flex;align-items:center;gap:.45rem}

.po-label{display:block;font-size:.78rem;font-weight:600;color:#555;margin-bottom:.35rem}
.po-input{width:100%;padding:.55rem .8rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.84rem;transition:border .25s;background:#fafafa}
.po-input:focus{border-color:#00796b;outline:none;box-shadow:0 0 0 3px rgba(0,121,107,.08);background:#fff}
.po-input.error{border-color:#dc2626}

.delivery-opt{border:2px solid #e8e8e8;border-radius:9px;padding:.75rem .6rem;cursor:pointer;transition:all .25s;text-align:center}
.delivery-opt:hover,.delivery-opt.sel{border-color:#00796b;background:#e0f2f1}
.delivery-opt i{font-size:1.2rem;color:#00796b;display:block;margin-bottom:.3rem}
.delivery-opt span{font-size:.78rem;font-weight:600;color:#333}

.dsvc-card{border:2px solid #e8e8e8;border-radius:9px;padding:.6rem .4rem;cursor:pointer;transition:all .25s;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.25rem}
.dsvc-card:hover,.dsvc-card.sel{border-color:#00796b;background:#e0f2f1;box-shadow:0 2px 8px rgba(0,121,107,.1)}
.dsvc-card img{width:36px;height:36px;object-fit:contain}
.dsvc-card .dsvc-name{font-weight:700;font-size:.76rem;color:#333}
.dsvc-card .dsvc-desc{font-size:.67rem;color:#999}

.pay-opt{border:2px solid #e8e8e8;border-radius:9px;padding:.8rem 1rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;gap:.7rem;width:100%}
.pay-opt:hover,.pay-opt.sel{border-color:#00796b;background:#e0f2f1}
.pay-logos{display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;margin-top:.35rem}
.pay-logo-img{height:18px;object-fit:contain;border-radius:3px;border:1px solid #e5e7eb;padding:1px 4px;background:#fff}

.dropzone-area{border:2px dashed #a5d6a7;border-radius:10px;padding:1.4rem 1rem;text-align:center;cursor:pointer;background:#f0fdf4;transition:all .25s}
.dropzone-area:hover,.dropzone-area.drag{background:#e0f2f1;border-color:#00796b}
.dropzone-area.file-ok{background:#dcfce7;border-color:#16a34a;border-style:solid}

.po-alert{border-radius:8px;padding:.7rem .9rem;margin-bottom:.9rem;display:flex;align-items:flex-start;gap:.6rem;font-size:.8rem;font-weight:500}
.po-alert.info{background:#e0f2fe;color:#0c4a6e;border-left:3px solid #0891b2}
.po-alert.warn{background:#fef3c7;color:#92400e;border-left:3px solid #f59e0b}
.po-alert.success{background:#dcfce7;color:#166534;border-left:3px solid #22c55e}
.po-alert.danger{background:#fee2e2;color:#991b1b;border-left:3px solid #dc2626}

.po-submit-btn{background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:9px;padding:.85rem 1.5rem;font-weight:700;font-size:.92rem;width:100%;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.55rem;transition:all .3s;box-shadow:0 3px 12px rgba(0,121,107,.25)}
.po-submit-btn:hover:not(:disabled){filter:brightness(1.08);transform:translateY(-1px)}
.po-submit-btn:disabled{opacity:.7;cursor:not-allowed}

.sidebar-card{background:#fff;border-radius:12px;padding:1.2rem 1.3rem;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:1rem}
.sidebar-title{font-size:.82rem;font-weight:700;color:#00796b;padding-bottom:.5rem;border-bottom:1.5px solid #e0f2f1;margin-bottom:.9rem;display:flex;align-items:center;gap:.4rem}
.how-step{display:flex;align-items:flex-start;gap:.55rem;margin-bottom:.55rem}
.how-num{width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#00796b,#004d40);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0}
.cart-summary-item{display:flex;align-items:flex-start;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid #f0f4f0;font-size:.81rem}
.cart-summary-item:last-child{border-bottom:none}

.order-type-badge{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .85rem;border-radius:20px;font-size:.76rem;font-weight:700}
.otb-rx{background:#fff3e0;color:#e65100;border:1.5px solid #ffcc80}
.otb-otc{background:#e8f5e9;color:#2e7d32;border:1.5px solid #a5d6a7}
.otb-presc{background:#e0f2fe;color:#0c4a6e;border:1.5px solid #7dd3fc}

/* Stripe inline */
#card-element{border:1.5px solid #e2e8f0;border-radius:8px;padding:.65rem .85rem;background:#fafafa;transition:border .25s}
#card-element.focused{border-color:#00796b;box-shadow:0 0 0 3px rgba(0,121,107,.08)}
.stripe-error-box{display:none;align-items:center;gap:.5rem;background:#fff5f5;border:1px solid #fed7d7;border-radius:7px;padding:.55rem .8rem;margin-top:.5rem;color:#c53030;font-size:.78rem;font-weight:500}
.stripe-error-box.show{display:flex}
</style>

{{-- ══ HEADER ══ --}}
<section class="po-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
           style="color:rgba(255,255,255,.8);font-size:.82rem;display:inline-flex;
                  align-items:center;gap:.35rem;margin-bottom:.8rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Medicines
        </a>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <img src="{{ $pharmacy->profile_image
                            ? asset('storage/'.$pharmacy->profile_image)
                            : asset('images/default-pharmacy.png') }}"
                 style="width:52px;height:52px;border-radius:10px;object-fit:cover;
                        border:3px solid rgba(255,255,255,.75)"
                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
            <div>
                <h1 style="font-size:1.55rem;font-weight:700;margin:0" id="pageTitle">
                    Place Order
                </h1>
                <p style="opacity:.82;font-size:.84rem;margin:.3rem 0 0">
                    <i class="fas fa-store me-1"></i>{{ $pharmacy->name }}
                    @if($pharmacy->city) &bull; {{ $pharmacy->city }} @endif
                </p>
                <div style="margin-top:.45rem" id="orderTypeBadgeWrap"></div>
            </div>
        </div>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section class="po-body">
    <div class="container">

        @foreach(['success','error'] as $t)
            @if(session($t))
            <div class="po-alert {{ $t==='success'?'success':'danger' }}">
                <i class="fas fa-{{ $t==='success'?'check-circle':'exclamation-circle' }}"
                   style="flex-shrink:0;margin-top:.1rem"></i>
                <span>{{ session($t) }}</span>
            </div>
            @endif
        @endforeach

        <div class="row g-3 justify-content-center">

            {{-- ══════════ FORM ══════════ --}}
            <div class="col-lg-8">
                <form action="{{ route('patient.pharmacies.order', $pharmacy->id) }}"
                      method="POST" enctype="multipart/form-data" id="orderForm">
                    @csrf
                    <input type="hidden" name="cart_data"          id="cartDataInput">
                    <input type="hidden" name="order_type"         id="orderTypeInput">
                    <input type="hidden" name="payment_method_id"  id="stripePaymentMethodId">
                    <input type="hidden" name="cardholder_name"    id="cardholderNameHidden">

                    {{-- ══ CART SUMMARY ══ --}}
                    <div class="po-card" id="cartSummaryCard" style="display:none">
                        <div class="po-card-title">
                            <i class="fas fa-shopping-basket"></i>
                            Selected Medicines
                            <span id="cartItemCount"
                                  style="background:#e0f2f1;color:#00796b;border-radius:20px;
                                         padding:.1rem .65rem;font-size:.72rem;font-weight:700;
                                         margin-left:auto"></span>
                        </div>
                        <div id="cartSummaryItems"></div>
                        <div style="border-top:1.5px solid #e0f2f1;margin-top:.7rem;
                                    padding-top:.7rem;display:flex;justify-content:space-between;
                                    align-items:center">
                            <span style="font-size:.82rem;color:#555;font-weight:600">
                                Estimated Subtotal
                            </span>
                            <span id="cartSummaryTotal"
                                  style="font-size:1.15rem;font-weight:800;color:#00796b"></span>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:.5rem">
                            <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                               style="font-size:.75rem;color:#00796b;text-decoration:none;
                                      display:inline-flex;align-items:center;gap:.3rem;
                                      font-weight:600">
                                <i class="fas fa-edit"></i> Edit Cart
                            </a>
                        </div>
                    </div>

                    {{-- ══ PRESCRIPTION UPLOAD ══ --}}
                    <div class="po-card" id="prescCard" style="display:none">
                        <div class="po-card-title">
                            <i class="fas fa-file-medical"></i>
                            Prescription Upload
                            <span id="prescRequiredBadge"
                                  style="display:none;margin-left:auto;background:#fee2e2;
                                         color:#dc2626;border-radius:20px;padding:.1rem .55rem;
                                         font-size:.68rem;font-weight:700">Required</span>
                        </div>
                        <div class="po-alert" id="prescAlertMsg" style="margin-bottom:.8rem"></div>
                        <div class="dropzone-area" id="dropZone"
                             onclick="document.getElementById('prescFile').click()">
                            <i class="fas fa-cloud-upload-alt"
                               style="font-size:2rem;color:#00796b;display:block;margin-bottom:.4rem"></i>
                            <div id="dropText"
                                 style="font-weight:700;color:#00796b;font-size:.88rem">
                                Click or drag &amp; drop prescription here
                            </div>
                            <div style="font-size:.73rem;color:#999;margin-top:.25rem">
                                JPG &middot; PNG &middot; PDF &nbsp;|&nbsp; Max 5MB
                            </div>
                        </div>
                        <input type="file" id="prescFile" name="prescription_file"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none"
                               onchange="onFileSelect(this)">
                        @error('prescription_file')
                            <div style="color:#dc2626;font-size:.78rem;margin-top:.3rem">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ══ DELIVERY OPTIONS ══ --}}
                    <div class="po-card">
                        <div class="po-card-title">
                            <i class="fas fa-box"></i> Delivery Options
                        </div>
                        <div class="row g-2 mb-3">
                            @if($pharmacy->delivery_available)
                            <div class="col-6">
                                <div class="delivery-opt sel" id="opt-delivery"
                                     onclick="pickDelivery('delivery')">
                                    <i class="fas fa-truck"></i>
                                    <span>Home Delivery</span>
                                </div>
                                <input type="radio" name="delivery_type" value="delivery"
                                       id="rd-delivery" class="d-none" checked>
                            </div>
                            @endif
                            <div class="{{ $pharmacy->delivery_available ? 'col-6' : 'col-12' }}">
                                <div class="delivery-opt {{ !$pharmacy->delivery_available ? 'sel' : '' }}"
                                     id="opt-pickup" onclick="pickDelivery('pickup')">
                                    <i class="fas fa-store"></i>
                                    <span>Pickup at Store</span>
                                </div>
                                <input type="radio" name="delivery_type" value="pickup"
                                       id="rd-pickup" class="d-none"
                                       {{ !$pharmacy->delivery_available ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div id="deliverySection"
                             class="{{ !$pharmacy->delivery_available ? 'd-none' : '' }}">
                            <div class="mb-2">
                                <label class="po-label">
                                    <i class="fas fa-map-marker-alt"
                                       style="color:#00796b;margin-right:.3rem"></i>
                                    Delivery Address <span style="color:#dc2626">*</span>
                                </label>
                                <textarea name="delivery_address"
                                          class="po-input {{ $errors->has('delivery_address') ? 'error' : '' }}"
                                          rows="2"
                                          placeholder="House no, street, city... Include phone & Google Maps link">{{ old('delivery_address', $patient->address ?? '') }}</textarea>
                                @error('delivery_address')
                                    <div style="color:#dc2626;font-size:.78rem;margin-top:.25rem">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div style="background:#f0fdf4;border:1px solid #a5d6a7;
                                            border-radius:7px;padding:.55rem .8rem;margin-top:.5rem;
                                            font-size:.75rem;color:#374151;line-height:1.75">
                                    <div style="font-weight:700;color:#00796b;margin-bottom:.2rem">
                                        <i class="fas fa-lightbulb me-1"></i> Tips for faster delivery:
                                    </div>
                                    <div><i class="fas fa-phone-alt me-1"
                                            style="color:#00796b;width:13px"></i>
                                        Include <strong>2 phone numbers</strong></div>
                                    <div><i class="fas fa-map-marker-alt me-1"
                                            style="color:#dc2626;width:13px"></i>
                                        Paste your <strong>Google Maps location link</strong></div>
                                    <div style="margin-top:.25rem">
                                        <a href="https://maps.google.com" target="_blank"
                                           style="color:#00796b;font-weight:600;font-size:.73rem;
                                                  text-decoration:none;display:inline-flex;
                                                  align-items:center;gap:.3rem">
                                            <i class="fas fa-external-link-alt"></i>
                                            Open Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <label class="po-label mt-2">
                                <i class="fas fa-shipping-fast"
                                   style="color:#00796b;margin-right:.3rem"></i>
                                Delivery Service
                            </label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="dsvc-card sel" id="dsvc-pickme"
                                         onclick="pickService('pickme')">
                                        <img src="{{ asset('images/pick_me.png') }}" alt="PickMe"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                                        <i class="fas fa-motorcycle"
                                           style="font-size:1.2rem;color:#00796b;display:none"></i>
                                        <div class="dsvc-name">PickMe</div>
                                        <div class="dsvc-desc">Fast & reliable</div>
                                    </div>
                                    <input type="radio" name="delivery_method" value="pickme"
                                           id="dsvc-rd-pickme" class="d-none" checked>
                                </div>
                                <div class="col-4">
                                    <div class="dsvc-card" id="dsvc-uber"
                                         onclick="pickService('uber')">
                                        <img src="{{ asset('images/Uber.png') }}" alt="Uber"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                                        <i class="fas fa-car"
                                           style="font-size:1.2rem;color:#000;display:none"></i>
                                        <div class="dsvc-name">Uber</div>
                                        <div class="dsvc-desc">Convenient</div>
                                    </div>
                                    <input type="radio" name="delivery_method" value="uber"
                                           id="dsvc-rd-uber" class="d-none">
                                </div>
                                <div class="col-4">
                                    <div class="dsvc-card" id="dsvc-own_delivery"
                                         onclick="pickService('own_delivery')">
                                        <i class="fas fa-truck"
                                           style="font-size:1.2rem;color:#00796b"></i>
                                        <div class="dsvc-name">Own Delivery</div>
                                        <div class="dsvc-desc">By pharmacy</div>
                                    </div>
                                    <input type="radio" name="delivery_method"
                                           value="own_delivery"
                                           id="dsvc-rd-own_delivery" class="d-none">
                                </div>
                            </div>
                        </div>

                        <div id="pickupNote"
                             class="{{ $pharmacy->delivery_available ? 'd-none' : '' }}">
                            <div class="po-alert info" style="margin-bottom:0">
                                <i class="fas fa-store" style="flex-shrink:0;margin-top:.1rem"></i>
                                <span>Your order will be ready for pickup.
                                      We'll notify you when it's ready.</span>
                            </div>
                        </div>
                    </div>

                    {{-- ══ PAYMENT METHOD ══ --}}
                    <div class="po-card">
                        <div class="po-card-title">
                            <i class="fas fa-credit-card"></i> Payment Method
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="pay-opt sel" id="pay-cod"
                                     onclick="pickPayment('cod')">
                                    <input type="radio" name="payment_method"
                                           value="cash_on_delivery"
                                           id="rd-cod" class="d-none" checked>
                                    <i class="fas fa-money-bill-wave"
                                       style="color:#43a047;font-size:1.2rem;flex-shrink:0"></i>
                                    <div>
                                        <div style="font-weight:700;font-size:.82rem">
                                            Cash on Delivery
                                        </div>
                                        <div style="font-size:.72rem;color:#888">
                                            Pay when received
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="pay-opt" id="pay-online"
                                     onclick="pickPayment('online')">
                                    <input type="radio" name="payment_method"
                                           value="online"
                                           id="rd-online" class="d-none">
                                    <i class="fas fa-credit-card"
                                       style="color:#1565c0;font-size:1.2rem;flex-shrink:0"></i>
                                    <div>
                                        <div style="font-weight:700;font-size:.82rem">
                                            Online Payment
                                        </div>
                                        <div style="font-size:.72rem;color:#888">
                                            Card via Stripe
                                        </div>
                                        <div class="pay-logos">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg"
                                                 alt="Stripe" class="pay-logo-img"
                                                 style="height:14px">
                                            <img src="https://img.icons8.com/?size=100&id=13608&format=png&color=000000"
                                                 alt="Visa" class="pay-logo-img"
                                                 style="height:22px">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                                 alt="MC" class="pay-logo-img"
                                                 style="height:16px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- OTC — Stripe Inline Form --}}
                        <div id="otcStripeSection" style="display:none;margin-top:.9rem">

                            @if(app()->environment('local','testing'))
                            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);
                                        border:1.5px solid #f59e0b;border-radius:9px;
                                        padding:.75rem 1rem;margin-bottom:.9rem;
                                        font-size:.78rem;color:#92400e;font-weight:500">
                                <strong><i class="fas fa-vial me-1"></i>Test Mode</strong><br>
                                Card: <code>4242 4242 4242 4242</code> &nbsp;
                                Expiry: <code>12/26</code> &nbsp;
                                CVC: <code>123</code>
                            </div>
                            @endif

                            <div style="margin-bottom:.9rem">
                                <label class="po-label">
                                    Cardholder Name
                                    <span style="color:#dc2626">*</span>
                                </label>
                                <input type="text" id="cardholderName"
                                       class="po-input"
                                       placeholder="Full name on card"
                                       autocomplete="cc-name">
                                <div id="nameError"
                                     style="color:#dc2626;font-size:.78rem;
                                            margin-top:.25rem;display:none">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Please enter the cardholder name.
                                </div>
                            </div>

                            <div style="margin-bottom:.9rem">
                                <label class="po-label">
                                    Card Details
                                    <span style="color:#dc2626">*</span>
                                </label>
                                <div id="card-element"></div>
                                <div class="stripe-error-box" id="card-errors">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="card-errors-msg"></span>
                                </div>
                            </div>

                            <div style="background:#e8f5e9;border:1px solid #a5d6a7;
                                        border-radius:8px;padding:.6rem .85rem;
                                        font-size:.75rem;color:#1b5e20;
                                        display:flex;align-items:center;gap:.5rem">
                                <i class="fas fa-check-circle" style="color:#2e7d32"></i>
                                <span>
                                    <strong>OTC Order</strong> — Total confirmed.
                                    Card charged
                                    <strong id="otcAmountDisplay">LKR —</strong>
                                    immediately on order placement.
                                </span>
                            </div>
                        </div>

                        {{-- Rx/Presc-only online notice --}}
                        <div id="rxOnlineNotice"
                             style="display:none;margin-top:.9rem;background:#ebf8ff;
                                    border:1px solid #90cdf4;border-radius:8px;
                                    padding:.65rem .9rem;font-size:.78rem;color:#2c5282">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Online payment</strong> will be available after the
                            pharmacist verifies your prescription and confirms the amount.
                            You'll receive an email with the payment link.
                        </div>

                        {{-- Default COD notice --}}
                        <div class="po-alert warn" id="payNoticeWarn"
                             style="margin-bottom:0;margin-top:.75rem">
                            <i class="fas fa-clock"
                               style="flex-shrink:0;margin-top:.1rem"></i>
                            <span>Final amount confirmed by pharmacy after prescription review.
                                  You'll be notified before any charge.</span>
                        </div>
                    </div>

                    {{-- ══ SUBMIT ══ --}}
                    <button type="submit" class="po-submit-btn"
                            id="submitBtn">
                        <i class="fas fa-paper-plane" id="submitIcon"></i>
                        <div id="submitSpinner"
                             style="width:16px;height:16px;border:2.5px solid rgba(255,255,255,.3);
                                    border-top-color:#fff;border-radius:50%;
                                    animation:spin .7s linear infinite;display:none"></div>
                        <span id="submitBtnText">Submit Order</span>
                    </button>
                    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>

                </form>
            </div>

            {{-- ══════════ SIDEBAR ══════════ --}}
            <div class="col-lg-4">

                <div class="sidebar-card" id="sidebarCartSummary" style="display:none">
                    <div class="sidebar-title">
                        <i class="fas fa-receipt"></i> Order Summary
                    </div>
                    <div id="sidebarCartItems" style="margin-bottom:.65rem"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;
                                padding-top:.55rem;border-top:1.5px solid #e0f2f1">
                        <span style="font-size:.8rem;font-weight:600;color:#555">
                            Estimated Total
                        </span>
                        <span id="sidebarCartTotal"
                              style="font-size:1.05rem;font-weight:800;color:#00796b"></span>
                    </div>
                </div>

                <div class="sidebar-card">
                    <div class="sidebar-title">
                        <i class="fas fa-store"></i> {{ Str::limit($pharmacy->name, 22) }}
                    </div>
                    <div style="font-size:.8rem;color:#555;line-height:1.9">
                        @if($pharmacy->phone)
                        <div>
                            <i class="fas fa-phone me-2"
                               style="color:#00796b;width:14px"></i>
                            <a href="tel:{{ $pharmacy->phone }}"
                               style="color:#00796b;text-decoration:none">
                                {{ $pharmacy->phone }}
                            </a>
                        </div>
                        @endif
                        @if($pharmacy->email)
                        <div>
                            <i class="fas fa-envelope me-2"
                               style="color:#00796b;width:14px"></i>
                            {{ Str::limit($pharmacy->email, 26) }}
                        </div>
                        @endif
                        @if($pharmacy->address)
                        <div>
                            <i class="fas fa-map-pin me-2"
                               style="color:#00796b;width:14px"></i>
                            {{ Str::limit($pharmacy->address, 40) }}
                        </div>
                        @endif
                        @if($pharmacy->operating_hours)
                        <div>
                            <i class="fas fa-clock me-2"
                               style="color:#00796b;width:14px"></i>
                            {{ $pharmacy->operating_hours }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="sidebar-card">
                    <div class="sidebar-title">
                        <i class="fas fa-list-ol"></i> How It Works
                    </div>
                    <div id="howItWorks"></div>
                </div>

                <div class="sidebar-card"
                     style="background:linear-gradient(135deg,#f0fdf4,#e0f2f1)">
                    <div class="sidebar-title">
                        <i class="fas fa-shield-alt"></i> Secure Payments
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;
                                gap:.6rem;flex-wrap:wrap;padding:.3rem 0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg"
                             alt="Stripe" style="height:18px;object-fit:contain">
                        <img src="https://img.icons8.com/?size=100&id=13608&format=png&color=000000"
                             alt="Visa" style="height:16px;object-fit:contain">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                             alt="Mastercard" style="height:18px;object-fit:contain">
                    </div>
                    <div style="text-align:center;font-size:.72rem;color:#555;margin-top:.5rem">
                        <i class="fas fa-lock me-1" style="color:#00796b"></i>
                        256-bit SSL encrypted
                    </div>
                </div>

                <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                   style="display:flex;align-items:center;justify-content:center;gap:.5rem;
                          background:#fff;border:2px solid #a5d6a7;border-radius:10px;
                          padding:.75rem;color:#00796b;font-weight:700;font-size:.83rem;
                          text-decoration:none;transition:all .25s"
                   onmouseover="this.style.background='#e0f2f1'"
                   onmouseout="this.style.background='#fff'">
                    <i class="fas fa-pills"></i> Browse Available Medicines
                </a>

            </div>
        </div>
    </div>
</section>

<script src="https://js.stripe.com/v3/"></script>
<script>
const PHARMACY_ID = '{{ $pharmacy->id }}';
const CART_KEY    = 'phCart_' + PHARMACY_ID;
const STRIPE_KEY  = '{{ config("services.stripe.key") }}';

// ── Stripe state ──────────────────────────────────────────────────────────────
let stripe      = null;
let cardElement = null;
let stripeReady = false;
let cardComplete = false;

function initStripe() {
    if (stripeReady || !STRIPE_KEY) return;
    stripeReady = true;
    stripe      = Stripe(STRIPE_KEY);
    const elems = stripe.elements();
    cardElement = elems.create('card', {
        hidePostalCode: false,
        style: {
            base: {
                fontSize: '14px', color: '#333',
                fontFamily: '-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif',
                fontSmoothing: 'antialiased',
                '::placeholder': { color: '#aab7c4' }
            },
            invalid: { color: '#dc2626', iconColor: '#dc2626' }
        }
    });
    cardElement.mount('#card-element');

    cardElement.on('focus', () => {
        document.getElementById('card-element').classList.add('focused');
    });
    cardElement.on('blur', () => {
        document.getElementById('card-element').classList.remove('focused');
    });
    cardElement.on('change', (e) => {
        cardComplete = e.complete;
        const errDiv = document.getElementById('card-errors');
        const errMsg = document.getElementById('card-errors-msg');
        if (e.error) {
            errMsg.textContent = e.error.message;
            errDiv.classList.add('show');
        } else {
            errDiv.classList.remove('show');
        }
    });
}

// ── How It Works ──────────────────────────────────────────────────────────────
const HOW_STEPS = {
    cart_otc: [
        'Medicines selected from catalogue',
        'Pharmacy prepares your order',
        'Choose delivery or pickup',
        'Pay on delivery or pay online now',
        'Receive your medicines!'
    ],
    cart_rx: [
        'Medicines selected from catalogue',
        'Upload your valid prescription',
        'Pharmacy verifies Rx medicines',
        'Pharmacy confirms final price',
        'Choose delivery or pickup',
        'Receive your medicines!'
    ],
    prescription_only: [
        'Upload your valid prescription',
        'Pharmacy reviews & validates',
        'Pharmacy prepares & confirms price',
        'Choose delivery or pickup',
        'Receive your medicines!'
    ]
};

function renderHowSteps(type) {
    const steps = HOW_STEPS[type] || HOW_STEPS['prescription_only'];
    document.getElementById('howItWorks').innerHTML = steps.map((s, i) => `
        <div style="display:flex;align-items:flex-start;gap:.55rem;margin-bottom:.55rem">
            <div style="width:20px;height:20px;border-radius:50%;
                        background:linear-gradient(135deg,#00796b,#004d40);
                        color:#fff;display:flex;align-items:center;justify-content:center;
                        font-size:.65rem;font-weight:700;flex-shrink:0">${i + 1}</div>
            <div style="font-size:.78rem;color:#555;line-height:1.4;padding-top:.2rem">${s}</div>
        </div>
    `).join('');
}

function setBadge(label, cls) {
    document.getElementById('orderTypeBadgeWrap').innerHTML =
        `<span class="order-type-badge ${cls}">
            <i class="fas fa-tag me-1"></i>${label}
         </span>`;
}

// ── Global order type ─────────────────────────────────────────────────────────
window._orderType = 'prescription_only';

// ── DOMContentLoaded ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    pickService('pickme');

    const cart   = JSON.parse(sessionStorage.getItem(CART_KEY) || '[]');
    const hasRx  = cart.some(i => i.requiresRx);
    const hasOtc = cart.some(i => !i.requiresRx);
    const isCart = cart.length > 0;

    // ── SCENARIO 1: OTC Cart ─────────────────────────────────────────────────
    if (isCart && !hasRx) {
        window._orderType = 'cart_otc';
        setupCartOrder(cart);
        setupPrescriptionSection('hidden');
        renderHowSteps('cart_otc');
        document.getElementById('orderTypeInput').value   = 'cart_otc';
        document.getElementById('submitBtnText').textContent = 'Confirm & Place Order';
        setBadge('OTC Order — No Prescription Required', 'otb-otc');
        document.getElementById('pageTitle').textContent  = 'Confirm Your Order';

        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        document.getElementById('otcAmountDisplay').textContent =
            'LKR ' + total.toLocaleString('en-LK',{minimumFractionDigits:2});

    // ── SCENARIO 2: Rx Cart ──────────────────────────────────────────────────
    } else if (isCart && hasRx) {
        window._orderType = 'cart_rx';
        setupCartOrder(cart);
        setupPrescriptionSection('required');
        renderHowSteps('cart_rx');
        document.getElementById('orderTypeInput').value   = 'cart_rx';
        document.getElementById('submitBtnText').textContent = 'Submit Prescription & Confirm Order';
        setBadge('Contains Rx Medicines — Prescription Required', 'otb-rx');
        document.getElementById('pageTitle').textContent  = 'Confirm Your Order';

    // ── SCENARIO 3: Prescription Only ────────────────────────────────────────
    } else {
        window._orderType = 'prescription_only';
        setupPrescriptionSection('required');
        renderHowSteps('prescription_only');
        document.getElementById('orderTypeInput').value   = 'prescription_only';
        document.getElementById('submitBtnText').textContent = 'Submit Prescription & Place Order';
        setBadge('Prescription Order', 'otb-presc');
        document.getElementById('pageTitle').textContent  = 'Place Prescription Order';
    }
});

// ── Cart Summary ──────────────────────────────────────────────────────────────
function setupCartOrder(cart) {
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const count = cart.reduce((s, i) => s + i.qty, 0);
    const fmt   = v => v.toLocaleString('en-LK',{minimumFractionDigits:2,maximumFractionDigits:2});

    document.getElementById('cartSummaryCard').style.display  = 'block';
    document.getElementById('sidebarCartSummary').style.display = 'block';
    document.getElementById('cartDataInput').value = JSON.stringify(cart);
    document.getElementById('cartItemCount').textContent =
        count + ' item' + (count !== 1 ? 's' : '');
    document.getElementById('cartSummaryTotal').textContent  = 'LKR ' + fmt(total);
    document.getElementById('sidebarCartTotal').textContent  = 'LKR ' + fmt(total);

    document.getElementById('cartSummaryItems').innerHTML = cart.map(item => `
        <div class="cart-summary-item">
            <div style="flex:1;min-width:0">
                <span style="font-weight:600;color:#1a1a1a">${item.name}</span>
                ${item.requiresRx
                    ? '<span style="font-size:.63rem;background:#fff3e0;color:#e65100;padding:.1rem .4rem;border-radius:5px;font-weight:700;margin-left:.3rem">Rx</span>'
                    : '<span style="font-size:.63rem;background:#e8f5e9;color:#2e7d32;padding:.1rem .4rem;border-radius:5px;font-weight:700;margin-left:.3rem">OTC</span>'}
                <div style="font-size:.71rem;color:#888;margin-top:.05rem">
                    LKR ${fmt(item.price)} &times; ${item.qty}
                </div>
            </div>
            <span style="font-weight:700;color:#00796b;flex-shrink:0;
                         margin-left:.75rem;font-size:.85rem">
                LKR ${fmt(item.price * item.qty)}
            </span>
        </div>
    `).join('');

    document.getElementById('sidebarCartItems').innerHTML = cart.map(item => `
        <div style="display:flex;justify-content:space-between;font-size:.76rem;
                    padding:.2rem 0;color:#555">
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                ${item.name} <span style="color:#aaa">&times;${item.qty}</span>
            </span>
            <span style="font-weight:700;color:#00796b;margin-left:.5rem;flex-shrink:0">
                LKR ${fmt(item.price * item.qty)}
            </span>
        </div>
    `).join('');
}

// ── Prescription Section ──────────────────────────────────────────────────────
function setupPrescriptionSection(mode) {
    const card      = document.getElementById('prescCard');
    const alertEl   = document.getElementById('prescAlertMsg');
    const reqBadge  = document.getElementById('prescRequiredBadge');
    const fileInput = document.getElementById('prescFile');

    if (mode === 'hidden') {
        card.style.display = 'none';
        fileInput.removeAttribute('required');
        return;
    }
    card.style.display = 'block';
    if (mode === 'required') {
        fileInput.setAttribute('required', '');
        reqBadge.style.display = 'inline-flex';
        alertEl.className  = 'po-alert warn';
        alertEl.innerHTML  = `<i class="fas fa-exclamation-triangle"
                                  style="flex-shrink:0;margin-top:.1rem"></i>
            <span>A valid prescription is <strong>required</strong>.
                  The pharmacy will verify before dispensing.</span>`;
    } else {
        fileInput.removeAttribute('required');
        reqBadge.style.display = 'none';
        alertEl.className  = 'po-alert info';
        alertEl.innerHTML  = `<i class="fas fa-info-circle"
                                  style="flex-shrink:0;margin-top:.1rem"></i>
            <span>Prescription is <strong>optional</strong> for this order.</span>`;
    }
}

// ── Payment Method ────────────────────────────────────────────────────────────
function pickPayment(method) {
    document.querySelectorAll('.pay-opt').forEach(o => o.classList.remove('sel'));
    document.getElementById('pay-' + method)?.classList.add('sel');
    document.getElementById(method === 'cod' ? 'rd-cod' : 'rd-online').checked = true;

    const isOtc      = (window._orderType === 'cart_otc');
    const otcStripe  = document.getElementById('otcStripeSection');
    const rxNotice   = document.getElementById('rxOnlineNotice');
    const warnNotice = document.getElementById('payNoticeWarn');

    if (method === 'online') {
        warnNotice.style.display = 'none';
        if (isOtc) {
            otcStripe.style.display = 'block';
            rxNotice.style.display  = 'none';
            initStripe();
        } else {
            otcStripe.style.display = 'none';
            rxNotice.style.display  = 'block';
        }
    } else {
        // COD
        otcStripe.style.display  = 'none';
        rxNotice.style.display   = 'none';
        warnNotice.style.display = 'flex';
    }
}

// ── Delivery Type ─────────────────────────────────────────────────────────────
function pickDelivery(type) {
    document.querySelectorAll('.delivery-opt').forEach(o => o.classList.remove('sel'));
    document.getElementById('opt-' + type)?.classList.add('sel');
    const rd = document.getElementById('rd-' + type);
    if (rd) rd.checked = true;

    const ds = document.getElementById('deliverySection');
    const pn = document.getElementById('pickupNote');
    const da = document.querySelector('textarea[name="delivery_address"]');

    if (type === 'delivery') {
        ds.classList.remove('d-none');
        pn.classList.add('d-none');
        if (da) da.setAttribute('required', '');
        pickService(currentService);
    } else {
        ds.classList.add('d-none');
        pn.classList.remove('d-none');
        if (da) da.removeAttribute('required');
        document.querySelectorAll('input[name="delivery_method"]').forEach(r => r.checked = false);
    }
}

// ── Delivery Service ──────────────────────────────────────────────────────────
let currentService = 'pickme';
function pickService(svc) {
    currentService = svc;
    document.querySelectorAll('.dsvc-card').forEach(c => c.classList.remove('sel'));
    document.getElementById('dsvc-' + svc)?.classList.add('sel');
    const rd = document.getElementById('dsvc-rd-' + svc);
    if (rd) rd.checked = true;
}

// ── Dropzone ──────────────────────────────────────────────────────────────────
const dz = document.getElementById('dropZone');
if (dz) {
    ['dragenter','dragover'].forEach(e =>
        dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.add('drag'); })
    );
    ['dragleave','drop'].forEach(e =>
        dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.remove('drag'); })
    );
    dz.addEventListener('drop', ev => {
        const f = ev.dataTransfer.files[0];
        if (!f) return;
        const dt = new DataTransfer();
        dt.items.add(f);
        document.getElementById('prescFile').files = dt.files;
        onFileSelect(document.getElementById('prescFile'));
    });
}

function onFileSelect(input) {
    const f = input.files[0];
    if (!f) return;
    document.getElementById('dropText').innerHTML =
        '<i class="fas fa-check-circle" style="color:#16a34a;margin-right:.35rem"></i>'
        + f.name
        + ' <span style="color:#888;font-weight:400">('
        + (f.size/1024/1024).toFixed(2) + ' MB)</span>';
    document.getElementById('dropZone').classList.add('file-ok');
}

// ── Form Submit ───────────────────────────────────────────────────────────────
document.getElementById('orderForm').addEventListener('submit', async function(e) {
    const isOtc    = (window._orderType === 'cart_otc');
    const isOnline = document.getElementById('rd-online').checked;

    if (isOtc && isOnline) {
        e.preventDefault();

        // Name validation
        const name    = document.getElementById('cardholderName').value.trim();
        const nameErr = document.getElementById('nameError');
        if (!name) {
            nameErr.style.display = 'block';
            document.getElementById('cardholderName').focus();
            return;
        }
        nameErr.style.display = 'none';

        // UI loading
        const btn     = document.getElementById('submitBtn');
        const icon    = document.getElementById('submitIcon');
        const spinner = document.getElementById('submitSpinner');
        const txt     = document.getElementById('submitBtnText');
        btn.disabled        = true;
        icon.style.display  = 'none';
        spinner.style.display = 'block';
        txt.textContent     = 'Processing payment...';

        try {
            const result = await stripe.createPaymentMethod({
                type: 'card', card: cardElement,
                billing_details: { name }
            });

            if (result.error) {
                document.getElementById('card-errors-msg').textContent = result.error.message;
                document.getElementById('card-errors').classList.add('show');
                btn.disabled        = false;
                icon.style.display  = 'inline';
                spinner.style.display = 'none';
                txt.textContent     = 'Confirm & Place Order';
                return;
            }

            document.getElementById('stripePaymentMethodId').value = result.paymentMethod.id;
            document.getElementById('cardholderNameHidden').value  = name;
            sessionStorage.removeItem(CART_KEY);
            this.submit();

        } catch(err) {
            document.getElementById('card-errors-msg').textContent =
                'Unexpected error. Please try again.';
            document.getElementById('card-errors').classList.add('show');
            btn.disabled        = false;
            icon.style.display  = 'inline';
            spinner.style.display = 'none';
            txt.textContent     = 'Confirm & Place Order';
        }

    } else {
        // COD or non-OTC online
        sessionStorage.removeItem(CART_KEY);
    }
});

// Cardholder name error clear
document.getElementById('cardholderName')?.addEventListener('input', () => {
    document.getElementById('nameError').style.display = 'none';
});

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.po-alert.success, .po-alert.danger').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity    = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 6000);
</script>

@include('partials.footer')
