@include('partials.header')

<style>
/* ══════════════════════════════════════════
   BOOK LAB TEST — Teal Theme
══════════════════════════════════════════ */
.loc-header {
    background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 60%, #06b6d4 100%);
    padding: 7rem 0 3rem; color: white;
    position: relative; overflow: hidden;
}
.loc-header::before {
    content:''; position:absolute; inset:0;
    background: url('https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.06;
}
.loc-header .container { position:relative; z-index:1; }
.loc-header::after {
    content:''; position:absolute; bottom:-1px; left:0; right:0;
    height:45px; background:#f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.loc-main { background:#f4f6f9; padding:2rem 0 4rem; }

/* Lab Info Box */
.lab-info-box {
    background: linear-gradient(135deg,rgba(8,145,178,0.08),rgba(8,145,178,0.15));
    border: 2px solid rgba(8,145,178,0.25);
    border-radius: 14px; padding: 1.4rem;
    display: flex; gap: 1.2rem; align-items: center;
    margin-bottom: 1.5rem;
}
.lab-info-box .lab-avatar {
    width: 70px; height: 70px; border-radius: 12px;
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.8rem; flex-shrink: 0;
}
.lab-info-box .lab-name { font-size: 1.15rem; font-weight: 700; color: #0c4a6e; }
.lab-info-box .lab-meta { font-size: 0.82rem; color: #666; margin-top: 0.3rem; }

/* Form Card */
.loc-card {
    background: white; border-radius: 14px;
    padding: 1.6rem; box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.loc-section-title {
    font-size: 1rem; font-weight: 700; color: #0c4a6e;
    margin-bottom: 1.1rem; padding-bottom: 0.7rem;
    border-bottom: 2px solid #e0f2fe;
    display: flex; align-items: center; gap: 0.5rem;
}
.loc-section-title i { color: #0891b2; }

/* ── Test / Package Items ── */
.test-item, .pkg-item {
    border: 2px solid #e9ecef; border-radius: 10px;
    padding: 1rem; margin-bottom: 0.8rem;
    cursor: pointer; transition: all 0.3s;
    display: flex; align-items: flex-start; gap: 0.9rem;
    user-select: none;
}
.test-item:hover, .pkg-item:hover {
    border-color: #0891b2;
    box-shadow: 0 4px 12px rgba(8,145,178,0.1);
}
.test-item.selected, .pkg-item.selected {
    border-color: #0891b2; background: #f0f9ff;
    box-shadow: 0 4px 14px rgba(8,145,178,0.15);
}

/* hide real checkbox */
.item-cb { display: none !important; }

.item-check {
    width: 22px; height: 22px; border-radius: 6px;
    border: 2px solid #ccc; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; background: white; margin-top: 2px;
}
.test-item.selected .item-check,
.pkg-item.selected  .item-check {
    background: #0891b2; border-color: #0891b2;
}
.item-check-icon { font-size: 0.7rem; color: transparent; transition: color 0.15s; }
.test-item.selected .item-check-icon,
.pkg-item.selected  .item-check-icon { color: white; }

.item-name  { font-weight: 700; color: #0c4a6e; font-size: 0.9rem; margin-bottom: 0.15rem; }
.item-meta  { font-size: 0.75rem; color: #888; line-height: 1.5; }
.item-price { font-weight: 700; color: #0891b2; font-size: 1rem; flex-shrink: 0; margin-left: auto; padding-left: 0.5rem; }

/* Cat Label */
.cat-label {
    font-size: 0.8rem; font-weight: 700; color: #0891b2;
    margin: 1rem 0 0.5rem;
    display: flex; align-items: center; gap: 0.4rem;
}

/* Form Controls */
.loc-label { display:block; font-size:0.88rem; font-weight:700; color:#0c4a6e; margin-bottom:0.45rem; }
.loc-input, .loc-select, .loc-textarea {
    width:100%; padding:0.72rem 1rem;
    border:2px solid #e9ecef; border-radius:10px;
    font-size:0.9rem; color:#333; transition:all 0.3s;
    background: white; font-family: inherit;
}
.loc-input:focus, .loc-select:focus, .loc-textarea:focus {
    border-color:#0891b2; outline:none;
    box-shadow:0 0 0 3px rgba(8,145,178,0.1);
}
.loc-textarea { resize:vertical; min-height:90px; }

/* ── Collection Type Cards ── */
.col-type-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
    margin-bottom: 0.5rem;
}
@media (max-width: 500px) { .col-type-grid { grid-template-columns: 1fr; } }

.col-type-card {
    border: 2px solid #e9ecef;
    border-radius: 12px; padding: 1.1rem 1rem;
    cursor: pointer; transition: all 0.3s;
    display: flex; align-items: center; gap: 0.8rem;
    background: #f8f9fa;
    user-select: none;
}
.col-type-card:hover { border-color: #0891b2; background: white; }
.col-type-card.active {
    border-color: #0891b2; background: #f0f9ff;
    box-shadow: 0 4px 12px rgba(8,145,178,0.15);
}
.col-type-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: #e0f2fe; display: flex;
    align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1.1rem; color: #0891b2;
    transition: all 0.3s;
}
.col-type-card.active .col-type-icon {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white;
}
.col-type-title { font-weight: 700; color: #0c4a6e; font-size: 0.88rem; }
.col-type-sub   { font-size: 0.72rem; color: #888; }

/* Home address reveal */
#homeAddressSection {
    background: #f0f9ff; border: 1.5px solid #bae6fd;
    border-radius: 12px; padding: 1.1rem;
    margin-top: 1rem; display: none;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* Summary Box */
.order-summary {
    background: white; border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.07);
    overflow: hidden; position: sticky; top: 80px;
}
.summary-header {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white; padding: 1.1rem 1.4rem;
    font-weight: 700; font-size: 0.95rem;
    display: flex; align-items: center; gap: 0.5rem;
}
.summary-body { padding: 1.2rem 1.4rem; }
.summary-item {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 0.6rem 0; border-bottom: 1px solid #f0f9ff; font-size: 0.85rem;
}
.summary-item:last-child { border-bottom: none; }
.summary-name  { color: #555; flex: 1; padding-right: 0.5rem; line-height: 1.4; }
.summary-price { font-weight: 700; color: #0891b2; flex-shrink: 0; }
.summary-total {
    background: #e0f2fe; padding: 1rem 1.4rem;
    display: flex; justify-content: space-between; align-items: center;
}
.summary-empty {
    text-align: center; padding: 1.5rem 0;
    color: #bbb; font-size: 0.85rem;
}
.summary-empty i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }

/* Submit */
.loc-submit-btn {
    background: linear-gradient(135deg,#0891b2,#0c4a6e);
    color: white; border: none;
    padding: 1rem 2rem; border-radius: 12px;
    font-size: 0.95rem; font-weight: 700;
    cursor: pointer; transition: all 0.3s; width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    box-shadow: 0 4px 14px rgba(8,145,178,0.3);
}
.loc-submit-btn:hover:not(:disabled) {
    filter: brightness(1.1); transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(8,145,178,0.4);
}
.loc-submit-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* Alerts */
.loc-alert {
    border-radius: 12px; padding: 1rem 1.3rem; margin-bottom: 1.5rem;
    display: flex; align-items: flex-start; gap: 0.8rem; font-size: 0.9rem;
}
.loc-alert.error   { background:#fee2e2; color:#991b1b; border-left:5px solid #dc2626; }
.loc-alert.warning { background:#fef3c7; color:#92400e; border-left:5px solid #f59e0b; }
.loc-alert.info    { background:#e0f2fe; color:#0c4a6e; border-left:5px solid #0891b2; }

@media (max-width:768px) {
    .loc-header { padding: 5rem 0 2.5rem; }
    .lab-info-box { flex-direction: column; text-align: center; }
    .order-summary { position: static; margin-top: 1.5rem; }
}
</style>

{{-- PAGE HEADER --}}
<section class="loc-header">
    <div class="container">
        <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
           style="color:rgba(255,255,255,0.9);text-decoration:none;font-size:0.88rem;
                  display:inline-flex;align-items:center;gap:0.5rem;margin-bottom:1rem;
                  transition:all 0.3s;">
            <i class="fas fa-arrow-left"></i> Back to Lab Profile
        </a>
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 style="font-size:2rem;font-weight:700;margin-bottom:0.4rem;">
                    <i class="fas fa-flask me-2" style="opacity:0.85;"></i> Book Lab Test
                </h1>
                <p style="opacity:0.9;font-size:0.95rem;margin:0;">
                    Select tests, choose collection method and submit your order
                </p>
            </div>
        </div>
    </div>
</section>

<section class="loc-main">
    <div class="container">

        {{-- Alerts --}}
        @if(session('error'))
        <div class="loc-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="loc-alert error">
            <i class="fas fa-exclamation-circle fa-lg" style="flex-shrink:0;margin-top:2px;"></i>
            <div>
                @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('patient.lab-orders.store', $laboratory->id) }}"
              method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf

            {{-- Hidden real radio for collection_type — value set by JS --}}
            <input type="hidden" name="collection_type" id="collectionTypeInput" value="walk_in">

            <div class="row g-4">

                {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
                <div class="col-lg-8">

                    {{-- Lab Info --}}
                    <div class="lab-info-box">
                        @if($laboratory->profile_image)
                            <img src="{{ asset('storage/'.$laboratory->profile_image) }}"
                                 alt="{{ $laboratory->name }}"
                                 style="width:70px;height:70px;border-radius:12px;object-fit:cover;
                                        border:3px solid rgba(8,145,178,0.3);">
                        @else
                            <div class="lab-avatar"><i class="fas fa-flask"></i></div>
                        @endif
                        <div>
                            <div class="lab-name">{{ $laboratory->name }}</div>
                            <div class="lab-meta">
                                @if($laboratory->city)
                                <span>
                                    <i class="fas fa-map-marker-alt me-1" style="color:#0891b2;"></i>
                                    {{ $laboratory->city }}
                                </span>
                                @endif
                                @if($laboratory->phone)
                                <span class="ms-2">
                                    <i class="fas fa-phone me-1" style="color:#0891b2;"></i>
                                    {{ $laboratory->phone }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Individual Tests ── --}}
                    @if($labTests->count() > 0)
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-vial"></i> Individual Tests
                        </div>

                        @php $cats = $labTests->groupBy('test_category'); @endphp

                        @foreach($cats as $cat => $tests)
                            @if($cat)
                            <div class="cat-label">
                                <i class="fas fa-tag"></i> {{ $cat }}
                            </div>
                            @endif

                            @foreach($tests as $test)
                            <div class="test-item" data-value="test_{{ $test->id }}" data-price="{{ $test->price ?? 0 }}" data-name="{{ $test->test_name }}">
                                <div class="item-check">
                                    <i class="fas fa-check item-check-icon"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div class="item-name">{{ $test->test_name }}</div>
                                    <div class="item-meta">
                                        @if($test->test_category)
                                        <span class="me-2">
                                            <i class="fas fa-tag me-1" style="color:#0891b2;"></i>
                                            {{ $test->test_category }}
                                        </span>
                                        @endif
                                        @if($test->duration_hours)
                                        <span class="me-2">
                                            <i class="fas fa-clock me-1" style="color:#7c3aed;"></i>
                                            {{ $test->duration_hours }}h
                                        </span>
                                        @endif
                                        @if($test->requirements)
                                        <span style="color:#d97706;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ Str::limit($test->requirements, 50) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item-price">Rs. {{ number_format($test->price ?? 0, 2) }}</div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                    @endif

                    {{-- ── Packages ── --}}
                    @if($labPackages->count() > 0)
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-box"></i> Test Packages
                        </div>

                        @foreach($labPackages as $pkg)
                        @php
                            $finalPrice = $pkg->discount_percentage
                                ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                                : $pkg->price;
                        @endphp
                        <div class="pkg-item" data-value="package_{{ $pkg->id }}" data-price="{{ $finalPrice }}" data-name="{{ $pkg->package_name }}">
                            <div class="item-check">
                                <i class="fas fa-check item-check-icon"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="item-name">{{ $pkg->package_name }}</div>
                                <div class="item-meta">
                                    @if($pkg->tests->count() > 0)
                                        <i class="fas fa-list me-1" style="color:#0891b2;"></i>
                                        {{ $pkg->tests->count() }} tests:
                                        @foreach($pkg->tests->take(3) as $pt)
                                            {{ $pt->test_name }}@if(!$loop->last), @endif
                                        @endforeach
                                        @if($pkg->tests->count() > 3)
                                            &amp; {{ $pkg->tests->count() - 3 }} more
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;padding-left:0.5rem;">
                                <div class="item-price">Rs. {{ number_format($finalPrice, 2) }}</div>
                                @if($pkg->discount_percentage)
                                <div style="text-decoration:line-through;color:#aaa;font-size:0.75rem;">
                                    Rs. {{ number_format($pkg->price, 2) }}
                                </div>
                                <span style="background:#dcfce7;color:#166534;padding:0.1rem 0.4rem;
                                             border-radius:6px;font-size:0.7rem;font-weight:700;">
                                    {{ $pkg->discount_percentage }}% OFF
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- ── Collection Type ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-truck"></i> Collection Type
                        </div>

                        <div class="col-type-grid">
                            {{-- Walk-In --}}
                            <div class="col-type-card active" data-col="walk_in" id="card-walk_in">
                                <div class="col-type-icon">
                                    <i class="fas fa-walking"></i>
                                </div>
                                <div>
                                    <div class="col-type-title">Walk-In</div>
                                    <div class="col-type-sub">Visit the laboratory in person</div>
                                </div>
                            </div>

                            {{-- Home Collection --}}
                            <div class="col-type-card" data-col="home" id="card-home">
                                <div class="col-type-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <div class="col-type-title">Home Collection</div>
                                    <div class="col-type-sub">Technician visits your home</div>
                                </div>
                            </div>
                        </div>

                        {{-- Home Address (animated reveal) --}}
                        <div id="homeAddressSection">
                            <label class="loc-label" style="color:#0369a1;">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Collection Address <span style="color:#dc2626;">*</span>
                            </label>
                            <textarea name="collection_address"
                                      id="collectionAddress"
                                      class="loc-textarea"
                                      rows="2"
                                      placeholder="House No., Street, City, Postal Code...">{{ old('collection_address') }}</textarea>
                        </div>
                    </div>

                    {{-- ── Date & Time ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-calendar-alt"></i> Preferred Date & Time
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="loc-label">
                                    Collection Date <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="date" name="collection_date" class="loc-input"
                                       value="{{ old('collection_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="loc-label">
                                    Preferred Time
                                    <span style="color:#888;font-weight:400;">(optional)</span>
                                </label>
                                <input type="time" name="collection_time" class="loc-input"
                                       value="{{ old('collection_time') }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── Prescription ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-file-medical"></i> Prescription
                            <span style="font-weight:400;font-size:0.82rem;color:#aaa;">(optional)</span>
                        </div>

                        @if($referralNote)
                        <div class="loc-alert info" style="margin-bottom:1rem;">
                            <i class="fas fa-notes-medical" style="flex-shrink:0;"></i>
                            <span>
                                Doctor referral note: <strong>{{ $referralNote }}</strong>
                            </span>
                        </div>
                        @endif

                        <label class="loc-label">Upload Prescription / Doctor's Note</label>
                        <input type="file" name="prescription_file" class="loc-input"
                               accept=".pdf,.jpg,.jpeg,.png"
                               style="padding:0.5rem 1rem;cursor:pointer;">
                        <div style="font-size:0.75rem;color:#aaa;margin-top:0.4rem;">
                            <i class="fas fa-paperclip me-1"></i>
                            Accepted: PDF, JPG, PNG &middot; Max: 5MB
                        </div>
                    </div>

                    {{-- ── Notes ── --}}
                    <div class="loc-card">
                        <div class="loc-section-title">
                            <i class="fas fa-sticky-note"></i> Additional Notes
                            <span style="font-weight:400;font-size:0.82rem;color:#aaa;">(optional)</span>
                        </div>
                        <textarea name="notes" class="loc-textarea"
                                  placeholder="Special instructions, medical conditions, fasting status..."
                                  rows="3">{{ old('notes') }}</textarea>
                    </div>

                </div>
                {{-- END LEFT --}}

                {{-- ═══════════════ RIGHT: SUMMARY ═══════════════ --}}
                <div class="col-lg-4">
                    <div class="order-summary">
                        <div class="summary-header">
                            <i class="fas fa-receipt"></i> Order Summary
                            <span id="summaryCount"
                                  style="margin-left:auto;background:rgba(255,255,255,0.25);
                                         padding:0.1rem 0.55rem;border-radius:10px;
                                         font-size:0.78rem;">0 items</span>
                        </div>

                        <div class="summary-body" id="summaryBody">
                            <div class="summary-empty" id="summaryEmpty">
                                <i class="fas fa-vial"></i>
                                Select tests or packages to see summary
                            </div>
                        </div>

                        <div class="summary-total" id="summaryTotal" style="display:none;">
                            <span style="font-weight:700;color:#0c4a6e;font-size:0.95rem;">
                                <i class="fas fa-coins me-1" style="color:#0891b2;"></i> Total
                            </span>
                            <span style="font-size:1.4rem;font-weight:800;color:#0891b2;"
                                  id="totalDisplay">Rs. 0.00</span>
                        </div>

                        <div style="padding:1.2rem 1.4rem;border-top:1px solid #e0f2fe;">
                            <div style="font-size:0.75rem;color:#888;margin-bottom:1rem;line-height:1.6;">
                                <i class="fas fa-info-circle me-1" style="color:#0891b2;"></i>
                                Payment can be made online after order submission.
                            </div>

                            <button type="submit" class="loc-submit-btn" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane"></i> Submit Order
                            </button>

                            <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:0.5rem;
                                      margin-top:0.8rem;padding:0.7rem;border-radius:10px;
                                      background:#f4f6f9;color:#666;text-decoration:none;
                                      font-size:0.85rem;font-weight:600;transition:all 0.3s;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════
    // 1. ITEM SELECTION (test / package)
    //    — uses div clicks, no label/checkbox confusion
    // ══════════════════════════════════════
    const itemCards   = document.querySelectorAll('.test-item, .pkg-item');
    const summaryBody = document.getElementById('summaryBody');
    const summaryEmpty= document.getElementById('summaryEmpty');
    const summaryTotal= document.getElementById('summaryTotal');
    const summaryCount= document.getElementById('summaryCount');
    const totalDisplay= document.getElementById('totalDisplay');
    const submitBtn   = document.getElementById('submitBtn');

    // Track selected items: Map<value, {name, price}>
    const selected = new Map();

    // Hidden inputs container (we inject <input type="hidden"> for each selected item)
    const form = document.getElementById('orderForm');

    function renderSummary() {
        // Clear old hidden inputs for selected_items
        form.querySelectorAll('input[name="selected_items[]"]').forEach(el => el.remove());

        if (selected.size === 0) {
            summaryBody.innerHTML = '';
            summaryBody.appendChild(summaryEmpty);
            summaryEmpty.style.display = 'block';
            summaryTotal.style.display = 'none';
            summaryCount.textContent   = '0 items';
            submitBtn.disabled         = true;
            submitBtn.style.opacity    = '0.5';
            return;
        }

        let total = 0;
        let html  = '';

        selected.forEach((item, value) => {
            total += item.price;
            html  += `<div class="summary-item">
                        <span class="summary-name">${item.name}</span>
                        <span class="summary-price">Rs. ${item.price.toFixed(2)}</span>
                      </div>`;

            // Inject hidden input so the form submits correctly
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'selected_items[]';
            inp.value = value;
            form.appendChild(inp);
        });

        summaryBody.innerHTML      = html;
        summaryTotal.style.display = 'flex';
        totalDisplay.textContent   = 'Rs. ' + total.toFixed(2);
        summaryCount.textContent   = selected.size + ' item' + (selected.size > 1 ? 's' : '');
        submitBtn.disabled         = false;
        submitBtn.style.opacity    = '1';
    }

    itemCards.forEach(card => {
        card.addEventListener('click', function () {
            const value = this.dataset.value;
            const price = parseFloat(this.dataset.price) || 0;
            const name  = this.dataset.name;

            if (selected.has(value)) {
                selected.delete(value);
                this.classList.remove('selected');
            } else {
                selected.set(value, { name, price });
                this.classList.add('selected');
            }

            renderSummary();
        });
    });

    // ══════════════════════════════════════
    // 2. COLLECTION TYPE TOGGLE
    //    — card-based UI, sets hidden input value
    // ══════════════════════════════════════
    const colCards          = document.querySelectorAll('.col-type-card');
    const colTypeInput      = document.getElementById('collectionTypeInput');
    const homeSection       = document.getElementById('homeAddressSection');
    const collectionAddress = document.getElementById('collectionAddress');

    // Restore old value on validation error
    const oldColType = '{{ old("collection_type", "walk_in") }}';
    colTypeInput.value = oldColType;

    colCards.forEach(card => {
        // Set initial active state
        if (card.dataset.col === oldColType) {
            card.classList.add('active');
        } else {
            card.classList.remove('active');
        }

        card.addEventListener('click', function () {
            const val = this.dataset.col;

            // Update cards
            colCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            // Update hidden input
            colTypeInput.value = val;

            // Show/hide address section
            if (val === 'home') {
                homeSection.style.display = 'block';
                collectionAddress.required = true;
            } else {
                homeSection.style.display = 'none';
                collectionAddress.required = false;
                collectionAddress.value = '';
            }
        });
    });

    // Initial home section state
    if (oldColType === 'home') {
        homeSection.style.display  = 'block';
        collectionAddress.required = true;
    }

    // ══════════════════════════════════════
    // 3. FORM SUBMIT GUARD
    //    — validate address if home selected
    // ══════════════════════════════════════
    form.addEventListener('submit', function (e) {
        if (colTypeInput.value === 'home' && !collectionAddress.value.trim()) {
            e.preventDefault();
            collectionAddress.focus();
            collectionAddress.style.borderColor = '#dc2626';
            collectionAddress.style.boxShadow   = '0 0 0 3px rgba(220,38,38,0.1)';
            const errDiv = document.createElement('div');
            errDiv.style.cssText = 'color:#dc2626;font-size:0.82rem;margin-top:0.4rem;font-weight:600;';
            errDiv.id = 'addr-err';
            if (!document.getElementById('addr-err')) {
                homeSection.appendChild(errDiv);
            }
            document.getElementById('addr-err').textContent = '⚠ Please enter your collection address.';
            return;
        }

        // Show loading state
        submitBtn.disabled     = true;
        submitBtn.innerHTML    = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.style.opacity = '0.85';
    });

    collectionAddress.addEventListener('input', function () {
        this.style.borderColor = '#e9ecef';
        this.style.boxShadow   = 'none';
        const err = document.getElementById('addr-err');
        if (err) err.remove();
    });

});
</script>
