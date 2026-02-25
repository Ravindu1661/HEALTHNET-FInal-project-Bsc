@include('partials.header')

<style>
.show-hdr { background:linear-gradient(135deg,#4a148c 0%,#7b1fa2 100%); padding:6rem 0 2.5rem; color:white; position:relative; overflow:hidden; }
.show-hdr::before { content:''; position:absolute; inset:0; opacity:.07; background:url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=2070&q=80') center/cover; }
.show-hdr .container { position:relative; z-index:1; }
.d-card { background:white; border-radius:14px; padding:1.5rem; box-shadow:0 4px 16px rgba(0,0,0,.07); margin-bottom:1.5rem; }
.d-card h5 { color:#7b1fa2; font-weight:700; font-size:.95rem; border-bottom:2px solid #f3e5f5; padding-bottom:.6rem; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
.d-row { display:flex; padding:.6rem 0; border-bottom:1px solid #f7f7f7; font-size:.85rem; }
.d-row:last-child { border-bottom:none; }
.d-lbl { font-weight:600; color:#888; min-width:130px; font-size:.83rem; }
.d-val { flex:1; color:#333; }

/* Timeline */
.tl { display:flex; margin:.5rem 0 1rem; }
.tl-s { flex:1; text-align:center; position:relative; }
.tl-s::before { content:''; position:absolute; top:17px; left:-50%; right:50%; height:3px; background:#e0e0e0; z-index:0; }
.tl-s:first-child::before { display:none; }
.tl-s.done::before { background:#7b1fa2; }
.tl-c { width:34px; height:34px; border-radius:50%; background:#e0e0e0; color:#999; display:flex; align-items:center; justify-content:center; margin:0 auto .4rem; font-size:.8rem; position:relative; z-index:1; transition:all .3s; }
.tl-s.done   .tl-c { background:#7b1fa2; color:white; }
.tl-s.active .tl-c { background:#ffa726; color:white; box-shadow:0 0 0 4px rgba(255,167,38,.2); animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{box-shadow:0 0 0 4px rgba(255,167,38,.2)} 50%{box-shadow:0 0 0 8px rgba(255,167,38,.1)} }
.tl-l { font-size:.66rem; color:#999; font-weight:600; }
.tl-s.done .tl-l,.tl-s.active .tl-l { color:#4a148c; }

/* Report boxes */
.rpt-ready   { background:linear-gradient(135deg,#e8f5e9,#c8e6c9); border-radius:12px; padding:1.5rem; text-align:center; }
.rpt-waiting { background:linear-gradient(135deg,#f3e5f5,#e1bee7); border-radius:12px; padding:1.5rem; text-align:center; }
.rpt-unpaid  { background:linear-gradient(135deg,#fff3e0,#ffe0b2); border-radius:12px; padding:1.5rem; text-align:center; }

.btn-dl { background:linear-gradient(135deg,#1565c0,#0d47a1); color:white; border:none; padding:.8rem 2rem; border-radius:25px; font-size:.95rem; font-weight:700; cursor:pointer; transition:all .3s; text-decoration:none; display:inline-flex; align-items:center; gap:.6rem; box-shadow:0 4px 15px rgba(21,101,192,.3); }
.btn-dl:hover { transform:translateY(-2px); color:white; }

.contact-btn { display:flex; align-items:center; gap:.8rem; padding:.85rem 1.1rem; border-radius:10px; text-decoration:none; font-weight:600; font-size:.85rem; transition:all .3s; margin-bottom:.6rem; }
.cwa  { background:#e8f5e9; color:#1b5e20; border:1.5px solid #a5d6a7; }
.cwa:hover  { background:#25D366; color:white; border-color:#25D366; }
.cph  { background:#f3e5f5; color:#4a148c; border:1.5px solid #ce93d8; }
.cph:hover  { background:#7b1fa2; color:white; border-color:#7b1fa2; }
.cem  { background:#e3f2fd; color:#0d47a1; border:1.5px solid #90caf9; }
.cem:hover  { background:#1565c0; color:white; border-color:#1565c0; }

@keyframes spin { to { transform:rotate(360deg); } }
.spin { animation:spin 2s linear infinite; display:inline-block; }
</style>

<section class="show-hdr">
    <div class="container">
        <a href="{{ route('patient.lab-orders.index') }}"
           style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:1rem;">
            <i class="fas fa-arrow-left"></i> My Lab Orders
        </a>
        <h1 style="font-size:1.8rem;font-weight:700;margin-bottom:.3rem;">
            <i class="fas fa-flask me-2"></i> {{ $order->order_number }}
        </h1>
        <p style="opacity:.85;font-size:.9rem;margin:0;">
            {{ $order->laboratory->name ?? 'Laboratory' }} ·
            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
        </p>
    </div>
</section>

<section style="background:#faf4fc;padding:2.5rem 0;">
    <div class="container">

        @foreach(['success','error','info'] as $t)
        @if(session($t))
        <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 rounded-3 mb-3">
            {{ session($t) }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @endforeach

        <div class="row g-4">
            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Progress Timeline --}}
                <div class="d-card">
                    <h5><i class="fas fa-tasks"></i> Order Progress</h5>
                    @php
                        $steps   = ['pending','sample_collected','processing','completed'];
                        $stepDef = [
                            'pending'          => ['fas fa-clock',        'Pending'],
                            'sample_collected' => ['fas fa-vials',        'Sample Collected'],
                            'processing'       => ['fas fa-microscope',   'Processing'],
                            'completed'        => ['fas fa-check-circle', 'Completed'],
                        ];
                        $curIdx  = array_search($order->status, $steps);
                        if ($curIdx === false) $curIdx = -1;
                    @endphp
                    <div class="tl">
                        @foreach($stepDef as $key => [$icon, $label])
                        @php
                            $idx = array_search($key, $steps);
                            $cls = $idx < $curIdx ? 'done' : ($idx === $curIdx ? 'active' : '');
                        @endphp
                        <div class="tl-s {{ $cls }}">
                            <div class="tl-c">
                                <i class="{{ $icon }} {{ $cls === 'active' ? 'spin' : '' }}"></i>
                            </div>
                            <div class="tl-l">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="d-card">
                    <h5><i class="fas fa-info-circle"></i> Order Details</h5>
                    <div class="d-row">
                        <div class="d-lbl">Order No.</div>
                        <div class="d-val"><strong>{{ $order->order_number }}</strong></div>
                    </div>
                    <div class="d-row">
                        <div class="d-lbl">Reference</div>
                        <div class="d-val" style="font-size:.78rem;color:#888;">{{ $order->reference_number }}</div>
                    </div>
                    <div class="d-row">
                        <div class="d-lbl">Laboratory</div>
                        <div class="d-val">{{ $order->laboratory->name ?? 'N/A' }}</div>
                    </div>
                    <div class="d-row">
                        <div class="d-lbl">Collection</div>
                        <div class="d-val">
                            @if($order->home_collection)
                                <span class="badge" style="background:#e3f2fd;color:#0d47a1;">
                                    <i class="fas fa-home me-1"></i>Home Collection
                                </span>
                            @else
                                <span class="badge" style="background:#e8f5e9;color:#1b5e20;">
                                    <i class="fas fa-building me-1"></i>Lab Visit
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($order->collection_date)
                    <div class="d-row">
                        <div class="d-lbl">Date</div>
                        <div class="d-val">
                            {{ \Carbon\Carbon::parse($order->collection_date)->format('D, d M Y') }}
                            @if($order->collection_time)
                             at {{ \Carbon\Carbon::parse($order->collection_time)->format('h:i A') }}
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($order->collection_address)
                    <div class="d-row">
                        <div class="d-lbl">Address</div>
                        <div class="d-val">{{ $order->collection_address }}</div>
                    </div>
                    @endif
                    <div class="d-row">
                        <div class="d-lbl">Payment</div>
                        <div class="d-val">
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Paid</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-clock me-1"></i>Unpaid</span>
                            @endif
                        </div>
                    </div>
                    @if($order->prescription_file)
                    <div class="d-row">
                        <div class="d-lbl">Prescription</div>
                        <div class="d-val">
                            <a href="{{ asset('storage/'.$order->prescription_file) }}" target="_blank"
                               style="color:#7b1fa2;font-size:.82rem;font-weight:600;text-decoration:none;">
                                <i class="fas fa-file-prescription me-1"></i>View Prescription
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Tests Table --}}
                <div class="d-card">
                    <h5><i class="fas fa-vials"></i> Tests Ordered</h5>
                    <table class="table table-hover mb-0" style="font-size:.85rem;">
                        <thead style="background:#f3e5f5;">
                            <tr>
                                <th style="color:#7b1fa2;border:none;">Item</th>
                                <th style="color:#7b1fa2;border:none;text-align:right;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->item_name }}
                                    @if($item->package_id)
                                    <span class="badge" style="background:#f3e5f5;color:#7b1fa2;font-size:.65rem;">Package</span>
                                    @endif
                                </td>
                                <td style="text-align:right;font-weight:600;">
                                    @if(($item->price ?? 0) > 0)
                                        Rs. {{ number_format($item->price, 2) }}
                                    @else
                                        <span style="color:#888;font-size:.78rem;">To confirm</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f3e5f5;">
                                <td style="font-weight:700;color:#4a148c;">Total</td>
                                <td style="text-align:right;font-weight:700;color:#2e7d32;font-size:1rem;">
                                    @if(($order->total_amount ?? 0) > 0)
                                        Rs. {{ number_format($order->total_amount, 2) }}
                                    @else
                                        <span style="font-size:.82rem;color:#888;">Lab will confirm</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- Report Section --}}
                <div class="d-card">
                    <h5><i class="fas fa-file-medical-alt"></i> Lab Report</h5>

                    @if($order->status === 'completed' && $order->report_file && $order->payment_status === 'paid')
                    {{-- Ready --}}
                    <div class="rpt-ready">
                        <i class="fas fa-file-pdf" style="font-size:2.5rem;color:#1565c0;display:block;margin-bottom:.7rem;"></i>
                        <p style="font-size:.85rem;color:#1b5e20;font-weight:600;margin-bottom:1rem;">
                            ✅ Your report is ready!
                        </p>
                        <a href="{{ route('patient.lab-orders.report', $order->id) }}" class="btn-dl">
                            <i class="fas fa-download"></i> Download PDF Report
                        </a>
                        @if($order->report_uploaded_at)
                        <div style="font-size:.7rem;color:#888;margin-top:.8rem;">
                            Uploaded: {{ \Carbon\Carbon::parse($order->report_uploaded_at)->format('d M Y') }}
                        </div>
                        @endif
                    </div>

                    @elseif($order->status === 'completed' && !$order->report_file)
                    {{-- Completed no file --}}
                    <div class="rpt-waiting">
                        <i class="fas fa-hourglass-half" style="font-size:2rem;color:#7b1fa2;display:block;margin-bottom:.7rem;"></i>
                        <p style="font-size:.82rem;color:#4a148c;font-weight:600;margin-bottom:.4rem;">Tests Complete</p>
                        <p style="font-size:.78rem;color:#666;">Lab is uploading your report. Please check back soon or contact the lab.</p>
                    </div>

                    @elseif($order->payment_status === 'unpaid' && ($order->total_amount ?? 0) > 0)
                    {{-- Needs payment --}}
                    <div class="rpt-unpaid">
                        <i class="fas fa-credit-card" style="font-size:2rem;color:#e65100;display:block;margin-bottom:.7rem;"></i>
                        <p style="font-size:.82rem;color:#bf360c;font-weight:600;margin-bottom:.8rem;">
                            Complete payment to proceed
                        </p>
                        <a href="{{ route('patient.lab-orders.payment', $order->id) }}"
                           style="background:#e65100;color:white;padding:.7rem 1.5rem;border-radius:20px;text-decoration:none;font-weight:700;font-size:.88rem;display:inline-flex;align-items:center;gap:.5rem;box-shadow:0 4px 12px rgba(230,81,0,.3);">
                            <i class="fas fa-credit-card"></i>
                            Pay Rs. {{ number_format($order->total_amount, 2) }}
                        </a>
                    </div>

                    @else
                    {{-- Processing --}}
                    <div class="rpt-waiting">
                        <i class="fas fa-microscope spin" style="font-size:2rem;color:#7b1fa2;display:block;margin-bottom:.7rem;"></i>
                        <p style="font-size:.82rem;color:#4a148c;font-weight:600;margin-bottom:.3rem;">Processing Samples</p>
                        <p style="font-size:.75rem;color:#666;">The lab is working on your samples. You'll be notified when the report is ready.</p>
                    </div>
                    @endif
                </div>

                {{-- Contact Lab --}}
                <div class="d-card">
                    <h5><i class="fas fa-headset"></i> Contact Lab</h5>
                    <p style="font-size:.75rem;color:#888;margin-bottom:1rem;">
                        Request your report via WhatsApp/Email or call the lab.
                    </p>
                    @php
                        $labPhone = $order->laboratory->phone ?? null;
                        $labEmail = $order->laboratory->email ?? null;
                        $labName  = $order->laboratory->name  ?? 'Lab';
                        $waPhone  = '';
                        if ($labPhone) {
                            $raw     = preg_replace('/[^0-9]/', '', $labPhone);
                            $waPhone = str_starts_with($raw,'0') ? '94'.substr($raw,1) : $raw;
                        }
                        $waMsg = urlencode(
                            "Hello {$labName}, I am a HealthNet patient. I would like to inquire about my Lab Order #{$order->order_number}."
                        );
                        $emailSub  = urlencode("Lab Report Request – Order #{$order->order_number}");
                        $emailBody = urlencode("Hello {$labName},\n\nI am a HealthNet patient and would like to request my lab report.\n\nOrder: {$order->order_number}\n\nThank you.");
                    @endphp

                    @if($labPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="contact-btn cwa">
                        <i class="fab fa-whatsapp" style="font-size:1.2rem;color:#25D366;"></i>
                        <div>
                            <div>WhatsApp Lab</div>
                            <div style="font-size:.7rem;opacity:.75;">Request report via WhatsApp</div>
                        </div>
                    </a>
                    <a href="tel:{{ $labPhone }}" class="contact-btn cph">
                        <i class="fas fa-phone"></i>
                        <div>
                            <div>Call Lab</div>
                            <div style="font-size:.7rem;opacity:.75;">{{ $labPhone }}</div>
                        </div>
                    </a>
                    @endif

                    @if($labEmail)
                    <a href="mailto:{{ $labEmail }}?subject={{ $emailSub }}&body={{ $emailBody }}"
                       class="contact-btn cem">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <div>Email Lab</div>
                            <div style="font-size:.7rem;opacity:.75;">{{ Str::limit($labEmail, 28) }}</div>
                        </div>
                    </a>
                    @endif

                    <div style="margin-top:.8rem;padding-top:.8rem;border-top:1px solid #f3e5f5;">
                        <a href="{{ route('patient.laboratories.show', $order->laboratory_id) }}"
                           style="font-size:.78rem;color:#7b1fa2;text-decoration:none;">
                            <i class="fas fa-external-link-alt me-1"></i>View Lab Profile
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@include('partials.footer')
