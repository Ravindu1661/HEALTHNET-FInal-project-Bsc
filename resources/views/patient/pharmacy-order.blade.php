@include('partials.header')
<style>
.po-header{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:6rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.po-header::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.07;z-index:0}
.po-header .container{position:relative;z-index:1}
.po-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.po-body{background:#f0f4f8;padding:1.8rem 0 2.5rem}

/* Cards */
.po-card{background:#fff;border-radius:12px;padding:1.2rem 1.4rem;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:1.1rem}
.po-card-title{font-size:.88rem;font-weight:700;color:#00796b;padding-bottom:.55rem;border-bottom:1.5px solid #e0f2f1;margin-bottom:1rem;display:flex;align-items:center;gap:.45rem}

/* Inputs */
.po-label{display:block;font-size:.78rem;font-weight:600;color:#555;margin-bottom:.35rem}
.po-input{width:100%;padding:.55rem .8rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.84rem;transition:border .25s;background:#fafafa}
.po-input:focus{border-color:#00796b;outline:none;box-shadow:0 0 0 3px rgba(0,121,107,.08);background:#fff}
.po-input.error{border-color:#dc2626}

/* Delivery Type */
.delivery-opt{border:2px solid #e8e8e8;border-radius:9px;padding:.75rem .6rem;cursor:pointer;transition:all .25s;text-align:center}
.delivery-opt:hover,.delivery-opt.sel{border-color:#00796b;background:#e0f2f1}
.delivery-opt i{font-size:1.2rem;color:#00796b;display:block;margin-bottom:.3rem}
.delivery-opt span{font-size:.78rem;font-weight:600;color:#333}

/* Delivery Service */
.dsvc-card{border:2px solid #e8e8e8;border-radius:9px;padding:.6rem .4rem;cursor:pointer;transition:all .25s;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.25rem}
.dsvc-card:hover,.dsvc-card.sel{border-color:#00796b;background:#e0f2f1;box-shadow:0 2px 8px rgba(0,121,107,.1)}
.dsvc-card img{width:36px;height:36px;object-fit:contain}
.dsvc-card .dsvc-name{font-weight:700;font-size:.76rem;color:#333}
.dsvc-card .dsvc-desc{font-size:.67rem;color:#999}

/* Payment */
.pay-opt{border:2px solid #e8e8e8;border-radius:9px;padding:.8rem 1rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;gap:.7rem;width:100%}
.pay-opt:hover,.pay-opt.sel{border-color:#00796b;background:#e0f2f1}
.pay-logos{display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;margin-top:.35rem}
.pay-logo-img{height:18px;object-fit:contain;border-radius:3px;border:1px solid #e5e7eb;padding:1px 4px;background:#fff}

/* Dropzone */
.dropzone-area{border:2px dashed #a5d6a7;border-radius:10px;padding:1.4rem 1rem;text-align:center;cursor:pointer;background:#f0fdf4;transition:all .25s}
.dropzone-area:hover,.dropzone-area.drag{background:#e0f2f1;border-color:#00796b}

/* Alerts */
.po-alert{border-radius:8px;padding:.7rem .9rem;margin-bottom:.9rem;display:flex;align-items:flex-start;gap:.6rem;font-size:.8rem;font-weight:500}
.po-alert.info{background:#e0f2fe;color:#0c4a6e;border-left:3px solid #0891b2}
.po-alert.warn{background:#fef3c7;color:#92400e;border-left:3px solid #f59e0b}
.po-alert.success{background:#dcfce7;color:#166534;border-left:3px solid #22c55e}

/* Submit */
.po-submit-btn{background:linear-gradient(135deg,#00796b,#004d40);color:#fff;border:none;border-radius:9px;padding:.85rem 1.5rem;font-weight:700;font-size:.92rem;width:100%;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.55rem;transition:all .3s;box-shadow:0 3px 12px rgba(0,121,107,.25)}
.po-submit-btn:hover{filter:brightness(1.08);transform:translateY(-1px)}

/* Sidebar */
.sidebar-card{background:#fff;border-radius:12px;padding:1.2rem 1.3rem;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:1rem}
.sidebar-title{font-size:.82rem;font-weight:700;color:#00796b;padding-bottom:.5rem;border-bottom:1.5px solid #e0f2f1;margin-bottom:.9rem;display:flex;align-items:center;gap:.4rem}
.how-step{display:flex;align-items:flex-start;gap:.55rem;margin-bottom:.55rem}
.how-num{width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#00796b,#004d40);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0}
</style>

{{-- ══════════ HEADER ══════════ --}}
<section class="po-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
           style="color:rgba(255,255,255,.8);font-size:.82rem;display:inline-flex;align-items:center;gap:.35rem;margin-bottom:.8rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                 style="width:52px;height:52px;border-radius:10px;object-fit:cover;border:3px solid rgba(255,255,255,.75)"
                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
            <div>
                <h1 style="font-size:1.55rem;font-weight:700;margin:0">Place Prescription Order</h1>
                <p style="opacity:.82;font-size:.84rem;margin:0">
                    <i class="fas fa-store me-1"></i>{{ $pharmacy->name }} &bull; {{ $pharmacy->city ?? '' }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══════════ BODY ══════════ --}}
<section class="po-body">
    <div class="container">

        {{-- Flash --}}
        @foreach(['success','error'] as $t)
            @if(session($t))
            <div class="po-alert {{ $t==='success'?'success':'warn' }}">
                <i class="fas fa-{{ $t==='success'?'check-circle':'exclamation-circle' }}" style="flex-shrink:0;margin-top:.1rem"></i>
                <span>{{ session($t) }}</span>
            </div>
            @endif
        @endforeach

        <div class="row g-3 justify-content-center">
            {{-- ══ FORM ══ --}}
            <div class="col-lg-8">
                <form action="{{ route('patient.pharmacies.order', $pharmacy->id) }}"
                      method="POST" enctype="multipart/form-data" id="orderForm">
                    @csrf

                    {{-- 1. PRESCRIPTION --}}
                    <div class="po-card">
                        <div class="po-card-title"><i class="fas fa-file-medical"></i> Prescription Upload</div>
                        <div class="po-alert info" style="margin-bottom:.8rem">
                            <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:.1rem"></i>
                            <span>The pharmacy will verify drug availability after reviewing your prescription.</span>
                        </div>
                        <div class="dropzone-area" id="dropZone"
                             onclick="document.getElementById('prescFile').click()">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#00796b;display:block;margin-bottom:.4rem"></i>
                            <div id="dropText" style="font-weight:700;color:#00796b;font-size:.88rem">Click or drag & drop prescription here</div>
                            <div style="font-size:.73rem;color:#999;margin-top:.25rem">JPG · PNG · PDF &nbsp;|&nbsp; Max 5MB</div>
                        </div>
                        <input type="file" id="prescFile" name="prescription_file"
                               accept=".jpg,.jpeg,.png,.pdf" class="d-none" required
                               onchange="onFileSelect(this)">
                        @error('prescription_file')
                            <div style="color:#dc2626;font-size:.78rem;margin-top:.3rem"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- 2. DELIVERY --}}
                    <div class="po-card">
                        <div class="po-card-title"><i class="fas fa-box"></i> Delivery Options</div>

                        {{-- Type --}}
                        <div class="row g-2 mb-3">
                            @if($pharmacy->delivery_available)
                            <div class="col-6">
                                <div class="delivery-opt sel" id="opt-delivery" onclick="pickDelivery('delivery')">
                                    <i class="fas fa-truck"></i>
                                    <span>Home Delivery</span>
                                </div>
                                <input type="radio" name="delivery_type" value="delivery" id="rd-delivery" class="d-none" checked>
                            </div>
                            @endif
                            <div class="col-6">
                                <div class="delivery-opt {{ !$pharmacy->delivery_available ? 'sel' : '' }}" id="opt-pickup" onclick="pickDelivery('pickup')">
                                    <i class="fas fa-store"></i>
                                    <span>Pickup at Store</span>
                                </div>
                                <input type="radio" name="delivery_type" value="pickup" id="rd-pickup" class="d-none" {{ !$pharmacy->delivery_available ? 'checked' : '' }}>
                            </div>
                        </div>

                        {{-- Delivery fields --}}
                        <div id="deliverySection" class="{{ !$pharmacy->delivery_available ? 'd-none' : '' }}">
                            <div class="mb-2">
                                <label class="po-label">
                                    <i class="fas fa-map-marker-alt" style="color:#00796b;margin-right:.3rem"></i>
                                    Delivery Address <span style="color:#dc2626">*</span>
                                </label>
                                <textarea name="delivery_address"
                                    class="po-input {{ $errors->has('delivery_address') ? 'error' : '' }}"
                                    rows="2"
                                    placeholder="Enter your full delivery address...">{{ old('delivery_address', $patient->address ?? '') }}</textarea>
                                @error('delivery_address')
                                    <div style="color:#dc2626;font-size:.78rem;margin-top:.25rem">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror

                                {{-- ✅ Delivery Address Tips --}}
                                <div style="background:#f0fdf4;border:1px solid #a5d6a7;border-radius:7px;padding:.55rem .8rem;margin-top:.5rem;font-size:.75rem;color:#374151;line-height:1.7">
                                    <div style="font-weight:700;color:#00796b;margin-bottom:.2rem">
                                        <i class="fas fa-lightbulb me-1"></i> Tips for faster delivery:
                                    </div>
                                    <div><i class="fas fa-phone-alt me-1" style="color:#00796b;width:13px"></i>Include <strong>2 phone numbers</strong> (e.g. your mobile + an alternative contact)</div>
                                    <div><i class="fas fa-map-marker-alt me-1" style="color:#dc2626;width:13px"></i>Paste your <strong>Google Maps location link</strong> for accurate delivery</div>
                                    <div style="margin-top:.3rem">
                                        <a href="https://maps.google.com" target="_blank"
                                        style="color:#00796b;font-weight:600;font-size:.73rem;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem">
                                            <i class="fas fa-external-link-alt"></i> Open Google Maps to copy your location link
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Service --}}
                            <label class="po-label mt-2">
                                <i class="fas fa-shipping-fast" style="color:#00796b;margin-right:.3rem"></i>
                                Delivery Service
                            </label>
                            <div class="row g-2">
                                {{-- PickMe --}}
                                <div class="col-4">
                                    <div class="dsvc-card sel" id="dsvc-pickme" onclick="pickService('pickme')">
                                        <img src="{{ asset('images/pick_me.png') }}" alt="PickMe"
                                            onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                                        <i class="fas fa-motorcycle" style="font-size:1.2rem;color:#00796b;display:none"></i>
                                        <div class="dsvc-name">PickMe</div>
                                        <div class="dsvc-desc">Fast & reliable</div>
                                    </div>
                                    <input type="radio" name="delivery_method" value="pickme" id="dsvc-rd-pickme" class="d-none" checked>
                                </div>
                                {{-- Uber --}}
                                <div class="col-4">
                                    <div class="dsvc-card" id="dsvc-uber" onclick="pickService('uber')">
                                        <img src="{{ asset('images/Uber.png') }}" alt="Uber"
                                            onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                                        <i class="fas fa-car" style="font-size:1.2rem;color:#000;display:none"></i>
                                        <div class="dsvc-name">Uber</div>
                                        <div class="dsvc-desc">Convenient</div>
                                    </div>
                                    <input type="radio" name="delivery_method" value="uber" id="dsvc-rd-uber" class="d-none">
                                </div>
                                {{-- Own --}}
                                <div class="col-4">
                                    <div class="dsvc-card" id="dsvc-own_delivery" onclick="pickService('own_delivery')">
                                        <i class="fas fa-truck" style="font-size:1.2rem;color:#00796b"></i>
                                        <div class="dsvc-name">Own Delivery</div>
                                        <div class="dsvc-desc">By pharmacy</div>
                                    </div>
                                    <input type="radio" name="delivery_method" value="own_delivery" id="dsvc-rd-own_delivery" class="d-none">
                                </div>
                            </div>
                        </div>

                        {{-- Pickup note --}}
                        <div id="pickupNote" class="{{ $pharmacy->delivery_available ? 'd-none' : '' }}">
                            <div class="po-alert info" style="margin-bottom:0">
                                <i class="fas fa-store" style="flex-shrink:0;margin-top:.1rem"></i>
                                <span>Your order will be ready for pickup. We'll notify you when it's ready.</span>
                            </div>
                        </div>
                    </div>


                   {{-- 3. PAYMENT --}}
                    <div class="po-card">
                        <div class="po-card-title"><i class="fas fa-credit-card"></i> Payment Method</div>
                        <div class="row g-2 mb-2">

                            {{-- COD --}}
                            <div class="col-6">
                                <div class="pay-opt sel" id="pay-cod" onclick="pickPayment('cod')">
                                    <input type="radio" name="payment_method"
                                        value="cash_on_delivery"  {{-- ✅ DB enum match --}}
                                        id="rd-cod" class="d-none" checked>
                                    <i class="fas fa-money-bill-wave" style="color:#43a047;font-size:1.2rem;flex-shrink:0"></i>
                                    <div>
                                        <div style="font-weight:700;font-size:.82rem">Cash on Delivery</div>
                                        <div style="font-size:.72rem;color:#888">Pay when received</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Online --}}
                            <div class="col-6">
                                <div class="pay-opt" id="pay-online" onclick="pickPayment('online')">
                                    <input type="radio" name="payment_method"
                                        value="online"
                                        id="rd-online" class="d-none">
                                    <i class="fas fa-credit-card" style="color:#1565c0;font-size:1.2rem;flex-shrink:0"></i>
                                    <div>
                                        <div style="font-weight:700;font-size:.82rem">Online Payment</div>
                                        <div style="font-size:.72rem;color:#888">Card via Stripe</div>
                                        <div class="pay-logos">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg"
                                                alt="Stripe" class="pay-logo-img" style="height:14px">
                                            <img src="https://img.icons8.com/?size=100&id=13608&format=png&color=000000"
                                                alt="Visa" class="pay-logo-img" style="height:24px">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                                alt="MC" class="pay-logo-img" style="height:16px">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="po-alert warn" style="margin-bottom:0">
                            <i class="fas fa-clock" style="flex-shrink:0;margin-top:.1rem"></i>
                            <span>Final amount confirmed by pharmacy after prescription review. You'll be notified before any charge.</span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="po-submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit Prescription & Place Order
                    </button>

                </form>
            </div>

            {{-- ══ SIDEBAR ══ --}}
            <div class="col-lg-4">

                {{-- Pharmacy Info --}}
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fas fa-store"></i> {{ Str::limit($pharmacy->name, 22) }}</div>
                    <div style="font-size:.8rem;color:#555;line-height:1.9">
                        @if($pharmacy->phone)
                        <div><i class="fas fa-phone me-2" style="color:#00796b;width:14px"></i>
                            <a href="tel:{{ $pharmacy->phone }}" style="color:#00796b;text-decoration:none">{{ $pharmacy->phone }}</a>
                        </div>
                        @endif
                        @if($pharmacy->email)
                        <div><i class="fas fa-envelope me-2" style="color:#00796b;width:14px"></i>{{ Str::limit($pharmacy->email, 26) }}</div>
                        @endif
                        @if($pharmacy->address)
                        <div><i class="fas fa-map-pin me-2" style="color:#00796b;width:14px"></i>{{ Str::limit($pharmacy->address, 40) }}</div>
                        @endif
                        @if($pharmacy->operating_hours)
                        <div><i class="fas fa-clock me-2" style="color:#00796b;width:14px"></i>{{ $pharmacy->operating_hours }}</div>
                        @endif
                    </div>
                </div>

                {{-- How it works --}}
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fas fa-list-ol"></i> How It Works</div>
                    @foreach([
                        'Upload your valid prescription',
                        'Pharmacy reviews & validates',
                        'Pharmacy confirms price',
                        'Choose delivery or pickup',
                        'Receive your medicines!'
                    ] as $si => $step)
                    <div class="how-step">
                        <div class="how-num">{{ $si + 1 }}</div>
                        <div style="font-size:.78rem;color:#555;line-height:1.4">{{ $step }}</div>
                    </div>
                    @endforeach
                </div>

                {{-- Secure Payment --}}
                <div class="sidebar-card" style="background:linear-gradient(135deg,#f0fdf4,#e0f2f1)">
                    <div class="sidebar-title"><i class="fas fa-shield-alt"></i> Secure Payments</div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:.6rem;flex-wrap:wrap;padding:.3rem 0">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg"
                             alt="Stripe" style="height:18px;object-fit:contain">
                        <img src="https://img.icons8.com/?size=100&id=13608&format=png&color=000000"
                             alt="Visa" style="height:16px;object-fit:contain">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                             alt="Mastercard" style="height:18px;object-fit:contain">
                    </div>
                    <div style="text-align:center;font-size:.72rem;color:#555;margin-top:.5rem">
                        <i class="fas fa-lock me-1" style="color:#00796b"></i>256-bit SSL encrypted
                    </div>
                </div>

                {{-- Browse medicines CTA --}}
                <a href="{{ route('patient.pharmacies.medicines', $pharmacy->id) }}"
                   style="display:flex;align-items:center;justify-content:center;gap:.5rem;background:#fff;border:2px solid #a5d6a7;border-radius:10px;padding:.75rem;color:#00796b;font-weight:700;font-size:.83rem;text-decoration:none;transition:all .25s"
                   onmouseover="this.style.background='#e0f2f1'"
                   onmouseout="this.style.background='#fff'">
                    <i class="fas fa-pills"></i> Browse Available Medicines
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// ── Delivery Type ─────────────────────────────────────
function pickDelivery(type) {
    document.querySelectorAll('.delivery-opt').forEach(o => o.classList.remove('sel'));
    document.getElementById('opt-' + type)?.classList.add('sel');
    document.getElementById('rd-' + type).checked = true;

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

// ── Delivery Service ──────────────────────────────────
let currentService = 'pickme';
function pickService(svc) {
    currentService = svc;
    document.querySelectorAll('.dsvc-card').forEach(c => c.classList.remove('sel'));
    document.getElementById('dsvc-' + svc)?.classList.add('sel');
    const rd = document.getElementById('dsvc-rd-' + svc);
    if (rd) rd.checked = true;
}

// ── Payment Method ────────────────────────────────────
function pickPayment(method) {
    document.querySelectorAll('.pay-opt').forEach(o => o.classList.remove('sel'));
    document.getElementById('pay-' + method)?.classList.add('sel');
    document.getElementById(method === 'cod' ? 'rd-cod' : 'rd-online').checked = true;
    // ✅ Delivery service state unchanged
}

// ── Dropzone ──────────────────────────────────────────
const dz = document.getElementById('dropZone');
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
function onFileSelect(input) {
    const f = input.files[0];
    if (!f) return;
    const t = document.getElementById('dropText');
    t.innerHTML = '<i class="fas fa-check-circle" style="color:#16a34a;margin-right:.35rem"></i>'
                + f.name + ' <span style="color:#888;font-weight:400">('
                + (f.size / 1024 / 1024).toFixed(2) + 'MB)</span>';
    document.getElementById('dropZone').style.background = '#dcfce7';
    document.getElementById('dropZone').style.borderColor = '#16a34a';
}

// ── Init ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    pickService('pickme');
    document.getElementById('pay-cod')?.classList.add('sel');
});
</script>

@include('partials.footer')
