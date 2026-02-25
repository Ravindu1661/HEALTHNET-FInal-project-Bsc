@include('partials.header')

<style>
.create-hdr {
    background:linear-gradient(135deg,#4a148c 0%,#7b1fa2 100%);
    padding:6rem 0 2.5rem; color:white; position:relative; overflow:hidden;
}
.create-hdr::before {
    content:''; position:absolute; inset:0; opacity:.08;
    background:url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=2070&q=80') center/cover;
}
.create-hdr .container { position:relative; z-index:1; }

.f-card { background:white; border-radius:14px; padding:1.8rem; box-shadow:0 4px 16px rgba(0,0,0,.07); margin-bottom:1.5rem; }
.f-card h5 { color:#7b1fa2; font-weight:700; font-size:.95rem; border-bottom:2px solid #f3e5f5; padding-bottom:.6rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:.5rem; }
.f-label { display:block; font-size:.82rem; font-weight:600; color:#4a148c; margin-bottom:.4rem; }
.f-input, .f-select, .f-textarea { width:100%; padding:.7rem .9rem; border:1.5px solid #e0d0f0; border-radius:8px; font-size:.88rem; background:white; transition:all .3s; color:#333; }
.f-input:focus,.f-select:focus,.f-textarea:focus { border-color:#7b1fa2; outline:none; box-shadow:0 0 0 3px rgba(123,31,162,.1); }

/* Test checkboxes */
.test-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:.6rem; }
.test-item {
    border:1.5px solid #e1bee7; border-radius:8px; padding:.7rem .9rem;
    cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:.6rem;
}
.test-item:hover { border-color:#7b1fa2; background:#faf4fc; }
.test-item.selected { border-color:#7b1fa2; background:#f3e5f5; }
.test-item input[type="checkbox"] { accent-color:#7b1fa2; width:15px; height:15px; flex-shrink:0; }

/* Package items */
.pkg-item {
    border:1.5px solid #e1bee7; border-radius:10px; padding:.9rem 1rem;
    cursor:pointer; transition:all .2s; display:flex; align-items:flex-start; gap:.7rem; margin-bottom:.6rem;
}
.pkg-item:hover { border-color:#7b1fa2; background:#faf4fc; }
.pkg-item.selected { border-color:#7b1fa2; background:#f3e5f5; }
.pkg-item input[type="checkbox"] { accent-color:#7b1fa2; width:15px; height:15px; margin-top:3px; flex-shrink:0; }

/* Collection buttons */
.coll-opts { display:flex; gap:.8rem; flex-wrap:wrap; }
.coll-opt { flex:1; min-width:130px; border:2px solid #e1bee7; border-radius:10px; padding:.9rem; text-align:center; cursor:pointer; transition:all .3s; }
.coll-opt:hover { border-color:#7b1fa2; background:#faf4fc; }
.coll-opt.active { border-color:#7b1fa2; background:#f3e5f5; }
.coll-opt input { display:none; }
.coll-opt i { font-size:1.4rem; color:#7b1fa2; margin-bottom:.4rem; display:block; }
.coll-opt span { font-size:.78rem; font-weight:700; color:#4a148c; }
.coll-opt small { display:block; font-size:.68rem; color:#888; margin-top:.2rem; }

/* Upload */
.upload-area { border:2px dashed #ce93d8; border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; transition:all .3s; background:#faf4fc; }
.upload-area:hover { border-color:#7b1fa2; background:#f3e5f5; }
.upload-area.has-file { border-color:#43a047; background:#e8f5e9; }

/* Summary */
.sum-card { background:white; border-radius:14px; padding:1.5rem; box-shadow:0 4px 16px rgba(0,0,0,.07); position:sticky; top:100px; }
.sum-card h5 { color:#7b1fa2; font-weight:700; font-size:.95rem; border-bottom:2px solid #f3e5f5; padding-bottom:.6rem; margin-bottom:1rem; }
.sum-item { display:flex; justify-content:space-between; font-size:.82rem; padding:.4rem 0; border-bottom:1px solid #f7f7f7; color:#555; }
.sum-item:last-child { border-bottom:none; }
.sum-total { display:flex; justify-content:space-between; font-size:1rem; font-weight:700; color:#2e7d32; padding-top:.8rem; margin-top:.4rem; border-top:2px solid #f3e5f5; }

.btn-submit { background:linear-gradient(135deg,#7b1fa2,#4a148c); color:white; border:none; padding:1rem 2rem; border-radius:25px; font-size:1rem; font-weight:700; cursor:pointer; transition:all .3s; width:100%; display:flex; align-items:center; justify-content:center; gap:.6rem; box-shadow:0 4px 15px rgba(123,31,162,.3); }
.btn-submit:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(123,31,162,.4); }
</style>

{{-- Header --}}
<section class="create-hdr">
    <div class="container">
        <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
           style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:1rem;transition:all .3s;">
            <i class="fas fa-arrow-left"></i> {{ $laboratory->name }}
        </a>
        <h1 style="font-size:1.8rem;font-weight:700;margin-bottom:.3rem;">
            <i class="fas fa-calendar-plus me-2"></i> Book Lab Test
        </h1>
        <p style="opacity:.85;font-size:.9rem;">{{ $laboratory->name }} · {{ $laboratory->city }}</p>
    </div>
</section>

<section style="background:#faf4fc;padding:2.5rem 0;min-height:600px;">
    <div class="container">

        @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('patient.lab-orders.store', $laboratory->id) }}"
              method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf

            <div class="row g-4">
                {{-- LEFT --}}
                <div class="col-lg-8">

                    {{-- Lab Info --}}
                    <div class="f-card" style="background:linear-gradient(135deg,#f3e5f5,#fce4ec);border:none;">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $laboratory->profile_image ? asset('storage/'.$laboratory->profile_image) : asset('images/default-lab.png') }}"
                                 style="width:55px;height:55px;border-radius:50%;object-fit:cover;border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,.1);"
                                 onerror="this.src='{{ asset('images/default-lab.png') }}'">
                            <div>
                                <div style="font-weight:700;color:#4a148c;font-size:1rem;">{{ $laboratory->name }}</div>
                                <div style="font-size:.78rem;color:#666;">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $laboratory->city }}, {{ $laboratory->province }}
                                </div>
                                @if($laboratory->operating_hours)
                                <div style="font-size:.72rem;color:#888;">
                                    <i class="fas fa-clock me-1"></i>{{ $laboratory->operating_hours }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Prescription Upload --}}
                    <div class="f-card">
                        <h5><i class="fas fa-file-prescription"></i> Doctor's Prescription (Optional)</h5>
                        <p style="font-size:.8rem;color:#666;margin-bottom:1rem;">
                            Upload your doctor's prescription. Supported: PDF, JPG, PNG (max 5MB).
                        </p>

                        @if($referralNote)
                        <div style="background:#e8f5e9;border-left:4px solid #43a047;border-radius:8px;padding:.8rem 1rem;margin-bottom:1rem;font-size:.82rem;color:#1b5e20;">
                            <i class="fas fa-notes-medical me-2"></i>
                            <strong>Doctor's Note:</strong> {{ $referralNote }}
                        </div>
                        @endif

                        <div class="upload-area" id="uploadArea"
                             onclick="document.getElementById('prescription_file').click()">
                            <input type="file" name="prescription_file" id="prescription_file"
                                   accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                                   onchange="handleFile(this)">
                            <div id="upPlaceholder">
                                <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#ce93d8;margin-bottom:.5rem;display:block;"></i>
                                <div style="font-size:.85rem;font-weight:600;color:#7b1fa2;">Click to upload prescription</div>
                                <div style="font-size:.72rem;color:#999;margin-top:.3rem;">PDF, JPG, PNG · Max 5MB</div>
                            </div>
                            <div id="upPreview" style="display:none;">
                                <i class="fas fa-check-circle" style="font-size:2rem;color:#43a047;margin-bottom:.5rem;display:block;"></i>
                                <div id="upName" style="font-size:.85rem;font-weight:600;color:#2e7d32;"></div>
                                <div style="font-size:.72rem;color:#888;margin-top:.2rem;">Click to change file</div>
                            </div>
                        </div>
                    </div>

                    {{-- Select Tests & Packages --}}
                    @if($labTests->count() > 0 || $labPackages->count() > 0)
                    <div class="f-card">
                        <h5><i class="fas fa-vials"></i> Select Tests / Packages</h5>
                        <p style="font-size:.78rem;color:#888;margin-bottom:1rem;">
                            Select the tests or packages you need. You may leave empty for a general request.
                        </p>

                        @if($labTests->count() > 0)
                        <div style="margin-bottom:1.2rem;">
                            <div style="font-size:.8rem;font-weight:700;color:#7b1fa2;margin-bottom:.6rem;">
                                <i class="fas fa-flask me-1"></i> Individual Tests
                            </div>
                            @php $byCategory = $labTests->groupBy('test_category'); @endphp
                            @foreach($byCategory as $cat => $tests)
                            <div style="margin-bottom:.8rem;">
                                <div style="font-size:.7rem;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:1px;margin-bottom:.4rem;">
                                    {{ $cat ?? 'General' }}
                                </div>
                                <div class="test-grid">
                                    @foreach($tests as $test)
                                    <label class="test-item" id="tl-{{ $test->id }}"
                                           onclick="this.classList.toggle('selected')">
                                        <input type="checkbox" name="selected_items[]"
                                               value="test_{{ $test->id }}"
                                               onchange="calcTotal()"
                                               data-price="{{ $test->price }}"
                                               data-name="{{ $test->test_name }}">
                                        <div>
                                            <div style="font-size:.8rem;font-weight:600;color:#4a148c;">{{ $test->test_name }}</div>
                                            @if($test->requirements)
                                            <div style="font-size:.68rem;color:#888;">{{ Str::limit($test->requirements,40) }}</div>
                                            @endif
                                            <div style="font-size:.72rem;color:#2e7d32;font-weight:700;">
                                                Rs. {{ number_format($test->price, 2) }}
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($labPackages->count() > 0)
                        <div>
                            <div style="font-size:.8rem;font-weight:700;color:#7b1fa2;margin-bottom:.6rem;">
                                <i class="fas fa-box-open me-1"></i> Test Packages
                            </div>
                            @foreach($labPackages as $pkg)
                            @php
                                $pkgPrice = $pkg->discount_percentage
                                    ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                                    : $pkg->price;
                            @endphp
                            <label class="pkg-item" id="pl-{{ $pkg->id }}"
                                   onclick="this.classList.toggle('selected')">
                                <input type="checkbox" name="selected_items[]"
                                       value="package_{{ $pkg->id }}"
                                       onchange="calcTotal()"
                                       data-price="{{ $pkgPrice }}"
                                       data-name="{{ $pkg->package_name }} (Package)">
                                <div style="flex:1;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong style="font-size:.85rem;color:#4a148c;">{{ $pkg->package_name }}</strong>
                                        <div>
                                            @if($pkg->discount_percentage)
                                            <span class="badge bg-danger" style="font-size:.65rem;">
                                                {{ $pkg->discount_percentage }}% OFF
                                            </span>
                                            @endif
                                            <strong style="color:#2e7d32;font-size:.9rem;margin-left:.3rem;">
                                                Rs. {{ number_format($pkgPrice, 2) }}
                                            </strong>
                                            @if($pkg->discount_percentage)
                                            <del style="font-size:.72rem;color:#999;margin-left:.3rem;">
                                                Rs. {{ number_format($pkg->price, 2) }}
                                            </del>
                                            @endif
                                        </div>
                                    </div>
                                    @if($pkg->description)
                                    <div style="font-size:.75rem;color:#666;margin-top:.2rem;">{{ $pkg->description }}</div>
                                    @endif
                                    <div style="font-size:.72rem;color:#7b1fa2;margin-top:.2rem;">
                                        {{ $pkg->tests->count() }} tests included
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="f-card" style="text-align:center;">
                        <i class="fas fa-info-circle" style="font-size:1.5rem;color:#7b1fa2;display:block;margin-bottom:.5rem;"></i>
                        <p style="font-size:.85rem;color:#666;margin:0;">
                            No specific tests listed yet. Your order will be submitted as a <strong>general inquiry</strong> — the lab will contact you.
                        </p>
                    </div>
                    @endif

                    {{-- Collection Type --}}
                    <div class="f-card">
                        <h5><i class="fas fa-map-marker-alt"></i> Collection Method</h5>
                        <div class="coll-opts">
                            <label class="coll-opt active" id="co-walk_in" onclick="setColl('walk_in')">
                                <input type="radio" name="collection_type" value="walk_in" checked>
                                <i class="fas fa-walking"></i>
                                <span>Walk-in</span>
                                <small>Visit the lab directly</small>
                            </label>
                            <label class="coll-opt" id="co-appointment" onclick="setColl('appointment')">
                                <input type="radio" name="collection_type" value="appointment">
                                <i class="fas fa-calendar-check"></i>
                                <span>Appointment</span>
                                <small>Book a time slot</small>
                            </label>
                            @if($laboratory->home_collection ?? false)
                            <label class="coll-opt" id="co-home" onclick="setColl('home')">
                                <input type="radio" name="collection_type" value="home">
                                <i class="fas fa-home"></i>
                                <span>Home Collection</span>
                                <small>Sample at your home</small>
                            </label>
                            @endif
                        </div>
                    </div>

                    {{-- Date & Time --}}
                    <div class="f-card">
                        <h5><i class="fas fa-calendar-alt"></i> Preferred Date & Time</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="f-label">Date <span style="color:#dc3545;">*</span></label>
                                <input type="date" name="collection_date" class="f-input"
                                       min="{{ date('Y-m-d') }}" value="{{ old('collection_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Preferred Time
                                    <span style="font-size:.7rem;color:#999;">(Optional)</span>
                                </label>
                                <input type="time" name="collection_time" class="f-input"
                                       value="{{ old('collection_time') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Home Address (conditional) --}}
                    <div id="homeSection" style="display:none;">
                        <div class="f-card" style="border-left:4px solid #2196F3;">
                            <h5><i class="fas fa-home"></i> Home Collection Address</h5>
                            <label class="f-label">Full Address <span style="color:#dc3545;">*</span></label>
                            <textarea name="collection_address" id="homeAddr" class="f-textarea" rows="3"
                                      placeholder="House No., Street, City, Postal Code">{{ old('collection_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="f-card">
                        <h5><i class="fas fa-sticky-note"></i> Additional Notes (Optional)</h5>
                        <textarea name="notes" class="f-textarea" rows="3"
                                  placeholder="Special instructions, symptoms, doctor's verbal instructions…">{{ old('notes') }}</textarea>
                    </div>

                </div>

                {{-- RIGHT — Summary --}}
                <div class="col-lg-4">
                    <div class="sum-card">
                        <h5><i class="fas fa-receipt me-2"></i>Order Summary</h5>

                        <div style="margin-bottom:.8rem;">
                            <div style="font-size:.72rem;color:#999;font-weight:600;margin-bottom:.3rem;">LABORATORY</div>
                            <div style="font-size:.85rem;font-weight:600;color:#4a148c;">{{ $laboratory->name }}</div>
                            <div style="font-size:.72rem;color:#888;">{{ $laboratory->city }}</div>
                        </div>

                        <div id="sumItems" style="min-height:30px;margin-bottom:.5rem;">
                            <div style="font-size:.78rem;color:#aaa;font-style:italic;">No tests selected yet</div>
                        </div>

                        <div class="sum-total">
                            <span>Total</span>
                            <span id="sumTotal">Rs. 0.00</span>
                        </div>

                        <div id="freeNote" style="font-size:.72rem;color:#666;text-align:center;margin-top:.5rem;display:none;">
                            <i class="fas fa-info-circle me-1"></i>General request — lab will confirm fee
                        </div>

                        <button type="submit" class="btn-submit" style="margin-top:1.2rem;">
                            <i class="fas fa-paper-plane"></i>
                            <span id="submitTxt">Submit Lab Order</span>
                        </button>

                        <div style="text-align:center;margin-top:1rem;">
                            <a href="{{ route('patient.laboratories.show', $laboratory->id) }}"
                               style="font-size:.78rem;color:#aaa;text-decoration:none;">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>

                        <div style="display:flex;justify-content:center;gap:1.2rem;margin-top:1.2rem;flex-wrap:wrap;">
                            <span style="font-size:.7rem;color:#999;display:flex;align-items:center;gap:.3rem;">
                                <i class="fas fa-shield-alt" style="color:#43a047;"></i> Secure
                            </span>
                            <span style="font-size:.7rem;color:#999;display:flex;align-items:center;gap:.3rem;">
                                <i class="fas fa-file-pdf" style="color:#1565c0;"></i> PDF Report
                            </span>
                            <span style="font-size:.7rem;color:#999;display:flex;align-items:center;gap:.3rem;">
                                <i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@include('partials.footer')

<script>
function setColl(type) {
    document.querySelectorAll('.coll-opt').forEach(el => el.classList.remove('active'));
    const el = document.getElementById('co-' + type);
    if (el) el.classList.add('active');

    document.querySelectorAll('input[name="collection_type"]').forEach(r => {
        r.checked = r.value === type;
    });

    const homeSection = document.getElementById('homeSection');
    const homeAddr    = document.getElementById('homeAddr');
    if (type === 'home') {
        homeSection.style.display = 'block';
        if (homeAddr) homeAddr.required = true;
    } else {
        homeSection.style.display = 'none';
        if (homeAddr) homeAddr.required = false;
    }
}

function handleFile(input) {
    const area = document.getElementById('uploadArea');
    const ph   = document.getElementById('upPlaceholder');
    const pv   = document.getElementById('upPreview');
    const nm   = document.getElementById('upName');

    if (input.files && input.files[0]) {
        if (input.files[0].size > 5 * 1024 * 1024) {
            alert('File too large. Max 5MB.');
            input.value = '';
            return;
        }
        area.classList.add('has-file');
        ph.style.display = 'none';
        pv.style.display = 'block';
        nm.textContent   = input.files[0].name;
    } else {
        area.classList.remove('has-file');
        ph.style.display = 'block';
        pv.style.display = 'none';
    }
}

function calcTotal() {
    const checked   = document.querySelectorAll('input[name="selected_items[]"]:checked');
    const sumDiv    = document.getElementById('sumItems');
    const totalEl   = document.getElementById('sumTotal');
    const freeNote  = document.getElementById('freeNote');
    const submitTxt = document.getElementById('submitTxt');

    let total = 0, html = '';

    checked.forEach(cb => {
        const price = parseFloat(cb.dataset.price) || 0;
        total += price;
        html += `<div class="sum-item">
            <span>${cb.dataset.name}</span>
            <span>Rs. ${price.toFixed(2)}</span>
        </div>`;
    });

    sumDiv.innerHTML  = html || '<div style="font-size:.78rem;color:#aaa;font-style:italic;">No tests selected</div>';
    totalEl.textContent = 'Rs. ' + total.toFixed(2);
    freeNote.style.display  = total > 0 ? 'none' : 'block';
    submitTxt.textContent   = total > 0 ? 'Proceed to Payment' : 'Submit Lab Order';
}
</script>
