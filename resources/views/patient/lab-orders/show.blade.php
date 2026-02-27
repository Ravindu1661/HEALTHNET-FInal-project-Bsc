@include('partials.header')

<style>
/* ══ HEADER ══ */
.show-hdr {
    background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 100%);
    padding: 6rem 0 2.5rem; color: white;
    position: relative; overflow: hidden;
}
.show-hdr::before {
    content: ''; position: absolute; inset: 0; opacity: .07;
    background: url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=2070&q=80') center/cover;
}
.show-hdr .container { position: relative; z-index: 1; }

/* ══ CARDS ══ */
.d-card {
    background: white; border-radius: 14px;
    padding: 1.3rem 1.4rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.07);
    margin-bottom: 1.4rem;
}
.d-card h5 {
    color: #7b1fa2; font-weight: 700; font-size: .9rem;
    border-bottom: 2px solid #f3e5f5;
    padding-bottom: .55rem; margin-bottom: .9rem;
    display: flex; align-items: center; gap: .45rem;
}
.d-row {
    display: flex; padding: .5rem 0;
    border-bottom: 1px solid #f7f7f7; font-size: .83rem;
}
.d-row:last-child { border-bottom: none; }
.d-lbl { font-weight: 600; color: #888; min-width: 120px; font-size: .8rem; }
.d-val { flex: 1; color: #333; }

/* ══ TIMELINE ══ */
.tl { display: flex; margin: .4rem 0 .8rem; }
.tl-s {
    flex: 1; text-align: center; position: relative;
}
/* Connector line BEFORE each step (except first) */
.tl-s::before {
    content: ''; position: absolute;
    top: 15px;          /* half of circle height (30px/2) */
    left: -50%; right: 50%;
    height: 3px; background: #e0e0e0; z-index: 0;
}
.tl-s:first-child::before { display: none; }
.tl-s.done::before  { background: linear-gradient(90deg, #7b1fa2, #9c27b0); }
.tl-s.active::before { background: linear-gradient(90deg, #7b1fa2, #ffa726); }

/* Circle */
.tl-c {
    width: 30px; height: 30px; border-radius: 50%;
    background: #ede7f6; color: #ce93d8;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .35rem; font-size: .75rem;
    position: relative; z-index: 1;
    transition: all .3s; border: 2px solid #e1bee7;
}
.tl-s.done   .tl-c {
    background: linear-gradient(135deg, #7b1fa2, #9c27b0);
    color: white; border-color: #7b1fa2;
    box-shadow: 0 2px 8px rgba(123,31,162,.35);
}
.tl-s.active .tl-c {
    background: linear-gradient(135deg, #ffa726, #fb8c00);
    color: white; border-color: #ffa726;
    box-shadow: 0 0 0 4px rgba(255,167,38,.2);
    animation: tpulse 2s infinite;
}
.tl-s.cancelled .tl-c {
    background: #fce4ec; color: #c62828; border-color: #ef9a9a;
}
@keyframes tpulse {
    0%,100% { box-shadow: 0 0 0 4px rgba(255,167,38,.2); }
    50%      { box-shadow: 0 0 0 8px rgba(255,167,38,.1); }
}

/* Label */
.tl-l { font-size: .62rem; color: #bbb; font-weight: 600; line-height: 1.3; }
.tl-s.done   .tl-l { color: #6a1b9a; }
.tl-s.active .tl-l { color: #fb8c00; font-weight: 700; }

/* ══ REPORT BOXES ══ */
.rpt-ready   { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 12px; padding: 1.3rem; text-align: center; }
.rpt-waiting { background: linear-gradient(135deg, #f3e5f5, #e1bee7); border-radius: 12px; padding: 1.3rem; text-align: center; }
.rpt-unpaid  { background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 12px; padding: 1.3rem; text-align: center; }

.btn-dl {
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    color: white; border: none; padding: .7rem 1.6rem;
    border-radius: 22px; font-size: .88rem; font-weight: 700;
    cursor: pointer; transition: all .3s; text-decoration: none;
    display: inline-flex; align-items: center; gap: .5rem;
    box-shadow: 0 4px 14px rgba(21,101,192,.3);
}
.btn-dl:hover { transform: translateY(-2px); color: white; }

/* ══ ACTION BUTTONS ══ */
.btn-action {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1.1rem; border-radius: 20px;
    font-size: .8rem; font-weight: 600;
    cursor: pointer; transition: all .3s;
    text-decoration: none; border: none;
}
.btn-pay-now {
    background: linear-gradient(135deg, #e65100, #bf360c);
    color: white; box-shadow: 0 3px 10px rgba(230,81,0,.3);
}
.btn-pay-now:hover { transform: translateY(-2px); color: white; }
.btn-cancel {
    background: white; color: #c62828;
    border: 1.5px solid #ef9a9a;
}
.btn-cancel:hover { background: #fce4ec; }
.btn-review {
    background: white; color: #f57f17;
    border: 1.5px solid #ffcc02;
}
.btn-review:hover { background: #fff8e1; }

/* ══ CONTACT BUTTONS ══ */
.contact-btn {
    display: flex; align-items: center; gap: .7rem;
    padding: .75rem 1rem; border-radius: 10px;
    text-decoration: none; font-weight: 600; font-size: .82rem;
    transition: all .3s; margin-bottom: .5rem;
}
.cwa { background: #e8f5e9; color: #1b5e20; border: 1.5px solid #a5d6a7; }
.cwa:hover { background: #25D366; color: white; border-color: #25D366; }
.cph { background: #f3e5f5; color: #4a148c; border: 1.5px solid #ce93d8; }
.cph:hover { background: #7b1fa2; color: white; border-color: #7b1fa2; }
.cem { background: #e3f2fd; color: #0d47a1; border: 1.5px solid #90caf9; }
.cem:hover { background: #1565c0; color: white; border-color: #1565c0; }

/* ══ CANCEL MODAL ══ */
.cm-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); z-index: 9999;
    align-items: center; justify-content: center;
}
.cm-overlay.show { display: flex; }
.cm-box {
    background: white; border-radius: 14px;
    padding: 1.8rem; max-width: 420px; width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    animation: cmPop .3s ease;
}
@keyframes cmPop {
    from { opacity: 0; transform: scale(.9); }
    to   { opacity: 1; transform: scale(1); }
}
.cm-title {
    font-size: 1.05rem; font-weight: 700; color: #4a148c;
    margin-bottom: .4rem; display: flex; align-items: center; gap: .5rem;
}
.cm-title i { color: #c62828; }
.cm-desc { font-size: .85rem; color: #666; margin-bottom: 1.1rem; line-height: 1.6; }
.cm-footer { display: flex; gap: .7rem; justify-content: flex-end; }
.btn-keep {
    background: white; color: #555; border: 1.5px solid #ddd;
    padding: .5rem 1.2rem; border-radius: 18px;
    font-weight: 600; font-size: .85rem; cursor: pointer;
}
.btn-keep:hover { border-color: #aaa; }
.btn-confirm-cancel {
    background: #c62828; color: white; border: none;
    padding: .5rem 1.4rem; border-radius: 18px;
    font-weight: 700; font-size: .85rem; cursor: pointer;
}
.btn-confirm-cancel:hover { background: #b71c1c; }

/* ══ REVIEW MODAL ══ */
.star-inp i {
    font-size: 1.6rem; color: #ddd; cursor: pointer;
    transition: color .2s;
}
.star-inp i.active { color: #ffa726; }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 2s linear infinite; display: inline-block; }
</style>

{{-- ══ HEADER ══ --}}
<section class="show-hdr">
    <div class="container">
        <a href="{{ route('patient.lab-orders.index') }}"
           style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.85rem;
                  display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;">
            <i class="fas fa-arrow-left"></i> My Lab Orders
        </a>
        <h1 style="font-size:1.7rem;font-weight:700;margin-bottom:.3rem;">
            <i class="fas fa-flask me-2"></i> {{ $order->order_number }}
        </h1>
        <p style="opacity:.85;font-size:.88rem;margin:0;">
            {{ $order->laboratory->name ?? 'Laboratory' }} ·
            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
        </p>
    </div>
</section>

{{-- ══ BODY ══ --}}
<section style="background:#faf4fc; padding:2.2rem 0 3rem;">
    <div class="container">

        {{-- Alerts --}}
        @foreach(['success','error','info'] as $t)
            @if(session($t))
            <div class="alert alert-{{ $t==='error'?'danger':$t }} alert-dismissible fade show border-0 rounded-3 mb-3">
                {{ session($t) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        @endforeach

        <div class="row g-4">

            {{-- ══ LEFT ══ --}}
            <div class="col-lg-8">

                {{-- Progress Timeline --}}
                @php
                    $steps   = ['pending','sample_collected','processing','completed'];
                    $stepDef = [
                        'pending'          => ['fas fa-paper-plane', 'Submitted'],
                        'sample_collected' => ['fas fa-vials',       'Sample Collected'],
                        'processing'       => ['fas fa-microscope',  'Processing'],
                        'completed'        => ['fas fa-check-circle','Report Ready'],
                    ];
                    $curIdx  = array_search($order->status, $steps);
                    if ($curIdx === false) $curIdx = -1;
                    $isCancelled = $order->status === 'cancelled';
                @endphp

                <div class="d-card">
                    <h5><i class="fas fa-tasks"></i> Order Progress</h5>

                    @if($isCancelled)
                        <div style="display:flex;flex-direction:column;align-items:center;padding:.5rem 0 .2rem;">
                            <div class="tl-c cancelled" style="width:38px;height:38px;margin-bottom:.5rem;">
                                <i class="fas fa-times"></i>
                            </div>
                            <div style="font-weight:700;color:#880e4f;font-size:.82rem;">Order Cancelled</div>
                        </div>
                    @else
                        <div class="tl">
                            @foreach($stepDef as $key => [$icon, $label])
                                @php
                                    $idx = array_search($key, $steps);
                                    $cls = $idx < $curIdx ? 'done' : ($idx === $curIdx ? 'active' : '');
                                @endphp
                                <div class="tl-s {{ $cls }}">
                                    <div class="tl-c">
                                        <i class="{{ $idx < $curIdx ? 'fas fa-check' : $icon }}
                                                  {{ $cls === 'active' ? 'spin' : '' }}"></i>
                                    </div>
                                    <div class="tl-l">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                        <div class="d-val" style="font-size:.76rem;color:#888;">{{ $order->reference_number }}</div>
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
                        <div class="d-lbl">Status</div>
                        <div class="d-val">{!! $order->status_badge !!}</div>
                    </div>
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
                               style="color:#7b1fa2;font-size:.8rem;font-weight:600;text-decoration:none;">
                                <i class="fas fa-file-prescription me-1"></i>View Prescription
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Tests Table --}}
                <div class="d-card">
                    <h5><i class="fas fa-vials"></i> Tests Ordered</h5>
                    <table class="table table-hover mb-0" style="font-size:.83rem;">
                        <thead style="background:#f3e5f5;">
                            <tr>
                                <th style="color:#7b1fa2;border:none;font-size:.8rem;">Item</th>
                                <th style="color:#7b1fa2;border:none;text-align:right;font-size:.8rem;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->item_name }}
                                    @if($item->package_id)
                                        <span class="badge" style="background:#f3e5f5;color:#7b1fa2;font-size:.62rem;">Package</span>
                                    @endif
                                </td>
                                <td style="text-align:right;font-weight:600;">
                                    @if(($item->price ?? 0) > 0)
                                        Rs. {{ number_format($item->price, 2) }}
                                    @else
                                        <span style="color:#888;font-size:.76rem;">To confirm</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f3e5f5;">
                                <td style="font-weight:700;color:#4a148c;font-size:.85rem;">Total</td>
                                <td style="text-align:right;font-weight:700;color:#2e7d32;font-size:.95rem;">
                                    @if(($order->total_amount ?? 0) > 0)
                                        Rs. {{ number_format($order->total_amount, 2) }}
                                    @else
                                        <span style="font-size:.78rem;color:#888;">Lab will confirm</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Action Buttons --}}
                @php
                    $canCancel = in_array($order->status, ['pending','sample_collected']);
                    $canPay    = $order->payment_status !== 'paid' && ($order->total_amount ?? 0) > 0
                                 && !in_array($order->status, ['cancelled']);
                    $canReview = $order->status === 'completed';
                @endphp
                @if($canCancel || $canPay || $canReview)
                <div class="d-card" style="padding:1rem 1.4rem;">
                    <div style="display:flex;gap:.7rem;flex-wrap:wrap;">
                        @if($canPay)
                        <a href="{{ route('patient.lab-orders.payment', $order->id) }}" class="btn-action btn-pay-now">
                            <i class="fas fa-credit-card"></i>
                            Pay Rs. {{ number_format($order->total_amount, 2) }}
                        </a>
                        @endif
                        @if($canReview)
                        <button class="btn-action btn-review" onclick="openReview()">
                            <i class="fas fa-star"></i> Leave a Review
                        </button>
                        @endif
                        @if($canCancel)
                        <button class="btn-action btn-cancel" onclick="openCancel()">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            {{-- ══ RIGHT ══ --}}
            <div class="col-lg-4">

                {{-- Report Section --}}
                <div class="d-card">
                    <h5><i class="fas fa-file-medical-alt"></i> Lab Report</h5>

                    @if($order->status === 'completed' && $order->report_file && $order->payment_status === 'paid')
                    <div class="rpt-ready">
                        <i class="fas fa-file-pdf" style="font-size:2.2rem;color:#1565c0;display:block;margin-bottom:.6rem;"></i>
                        <p style="font-size:.82rem;color:#1b5e20;font-weight:600;margin-bottom:.9rem;">
                            ✅ Your report is ready!
                        </p>
                        <a href="{{ route('patient.lab-orders.report', $order->id) }}" class="btn-dl">
                            <i class="fas fa-download"></i> Download Report
                        </a>
                        @if($order->report_uploaded_at)
                        <div style="font-size:.68rem;color:#888;margin-top:.7rem;">
                            Uploaded: {{ \Carbon\Carbon::parse($order->report_uploaded_at)->format('d M Y') }}
                        </div>
                        @endif
                    </div>

                    @elseif($order->status === 'completed' && !$order->report_file)
                    <div class="rpt-waiting">
                        <i class="fas fa-hourglass-half" style="font-size:1.8rem;color:#7b1fa2;display:block;margin-bottom:.6rem;"></i>
                        <p style="font-size:.8rem;color:#4a148c;font-weight:600;margin-bottom:.35rem;">Tests Complete</p>
                        <p style="font-size:.75rem;color:#666;margin:0;">Lab is uploading your report. Please check back soon.</p>
                    </div>

                    @elseif($order->payment_status !== 'paid' && ($order->total_amount ?? 0) > 0 && $order->status !== 'cancelled')
                    <div class="rpt-unpaid">
                        <i class="fas fa-credit-card" style="font-size:1.8rem;color:#e65100;display:block;margin-bottom:.6rem;"></i>
                        <p style="font-size:.8rem;color:#bf360c;font-weight:600;margin-bottom:.7rem;">
                            Complete payment to proceed
                        </p>
                        <a href="{{ route('patient.lab-orders.payment', $order->id) }}"
                           style="background:#e65100;color:white;padding:.6rem 1.3rem;border-radius:18px;
                                  text-decoration:none;font-weight:700;font-size:.83rem;
                                  display:inline-flex;align-items:center;gap:.45rem;
                                  box-shadow:0 4px 12px rgba(230,81,0,.3);">
                            <i class="fas fa-credit-card"></i>
                            Pay Rs. {{ number_format($order->total_amount, 2) }}
                        </a>
                    </div>

                    @elseif($order->status === 'cancelled')
                    <div style="background:#fce4ec;border-radius:12px;padding:1.2rem;text-align:center;">
                        <i class="fas fa-times-circle" style="font-size:1.8rem;color:#c62828;display:block;margin-bottom:.6rem;"></i>
                        <p style="font-size:.8rem;color:#880e4f;font-weight:600;margin:0;">Order has been cancelled.</p>
                    </div>

                    @else
                    <div class="rpt-waiting">
                        <i class="fas fa-microscope spin" style="font-size:1.8rem;color:#7b1fa2;display:block;margin-bottom:.6rem;"></i>
                        <p style="font-size:.8rem;color:#4a148c;font-weight:600;margin-bottom:.3rem;">Processing Samples</p>
                        <p style="font-size:.72rem;color:#666;margin:0;">
                            Lab is working on your samples. You'll be notified when the report is ready.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Contact Lab --}}
                <div class="d-card">
                    <h5><i class="fas fa-headset"></i> Contact Lab</h5>
                    <p style="font-size:.73rem;color:#888;margin-bottom:.9rem;">
                        Request your report via WhatsApp, Email, or call the lab directly.
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
                        $waMsg     = urlencode("Hello {$labName}, I am a HealthNet patient. I would like to inquire about my Lab Order #{$order->order_number}.");
                        $emailSub  = urlencode("Lab Report Request – Order #{$order->order_number}");
                        $emailBody = urlencode("Hello {$labName},\n\nI am a HealthNet patient and would like to request my lab report.\n\nOrder: {$order->order_number}\n\nThank you.");
                    @endphp

                    @if($labPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="contact-btn cwa">
                        <i class="fab fa-whatsapp" style="font-size:1.1rem;color:#25D366;"></i>
                        <div>
                            <div>WhatsApp Lab</div>
                            <div style="font-size:.68rem;opacity:.75;">Request report via WhatsApp</div>
                        </div>
                    </a>
                    <a href="tel:{{ $labPhone }}" class="contact-btn cph">
                        <i class="fas fa-phone"></i>
                        <div>
                            <div>Call Lab</div>
                            <div style="font-size:.68rem;opacity:.75;">{{ $labPhone }}</div>
                        </div>
                    </a>
                    @endif

                    @if($labEmail)
                    <a href="mailto:{{ $labEmail }}?subject={{ $emailSub }}&body={{ $emailBody }}" class="contact-btn cem">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <div>Email Lab</div>
                            <div style="font-size:.68rem;opacity:.75;">{{ Str::limit($labEmail, 28) }}</div>
                        </div>
                    </a>
                    @endif

                    <div style="margin-top:.7rem;padding-top:.7rem;border-top:1px solid #f3e5f5;">
                        <a href="{{ route('patient.laboratories.show', $order->laboratory_id) }}"
                           style="font-size:.76rem;color:#7b1fa2;text-decoration:none;">
                            <i class="fas fa-external-link-alt me-1"></i>View Lab Profile
                        </a>
                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</section>

{{-- ══ CANCEL MODAL ══ --}}
@if(in_array($order->status, ['pending','sample_collected']))
<div class="cm-overlay" id="cancelModal">
    <div class="cm-box">
        <div class="cm-title"><i class="fas fa-exclamation-triangle"></i> Cancel Order</div>
        <div class="cm-desc">
            Are you sure you want to cancel order <strong>{{ $order->order_number }}</strong>?
            <br><span style="color:#c62828;font-size:.8rem;">This action cannot be undone.</span>
        </div>
        <div class="cm-footer">
            <button class="btn-keep" onclick="closeCancel()">
                <i class="fas fa-arrow-left me-1"></i> Keep It
            </button>
            <form action="{{ route('patient.lab-orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-confirm-cancel">
                    <i class="fas fa-times me-1"></i> Yes, Cancel
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══ REVIEW MODAL ══ --}}
@if($order->status === 'completed')
<div class="cm-overlay" id="reviewModal">
    <div class="cm-box" style="max-width:460px;">
        <div class="cm-title" style="color:#f57f17;">
            <i class="fas fa-star" style="color:#ffa726;"></i> Leave a Review
        </div>
        <form action="{{ route('patient.lab-orders.review.store', $order->id) }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="font-size:.82rem;font-weight:600;color:#4a148c;display:block;margin-bottom:.5rem;">
                    Your Rating <span style="color:#c62828;">*</span>
                </label>
                <div class="star-inp" id="starInp">
                    @for($s = 1; $s <= 5; $s++)
                    <i class="far fa-star" data-val="{{ $s }}"
                       onmouseover="hoverStar({{ $s }})"
                       onmouseout="resetStars()"
                       onclick="selectStar({{ $s }})"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
            </div>
            <div style="margin-bottom:1.1rem;">
                <label style="font-size:.82rem;font-weight:600;color:#4a148c;display:block;margin-bottom:.4rem;">
                    Your Review <span style="font-weight:400;color:#999;">(optional)</span>
                </label>
                <textarea name="review" rows="3"
                    style="width:100%;padding:.65rem .9rem;border:2px solid #e1bee7;border-radius:8px;
                           font-size:.85rem;resize:vertical;"
                    placeholder="Share your experience with this laboratory..."></textarea>
            </div>
            <div class="cm-footer">
                <button type="button" class="btn-keep" onclick="closeReview()">Cancel</button>
                <button type="submit"
                    style="background:linear-gradient(135deg,#ffa726,#fb8c00);color:white;border:none;
                           padding:.5rem 1.4rem;border-radius:18px;font-weight:700;font-size:.85rem;cursor:pointer;">
                    <i class="fas fa-paper-plane me-1"></i> Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@include('partials.footer')

<script>
/* Cancel modal */
function openCancel()  { document.getElementById('cancelModal').classList.add('show'); }
function closeCancel() { document.getElementById('cancelModal').classList.remove('show'); }
document.getElementById('cancelModal')?.addEventListener('click', function(e){
    if(e.target === this) closeCancel();
});

/* Review modal */
function openReview()  { document.getElementById('reviewModal').classList.add('show'); }
function closeReview() { document.getElementById('reviewModal').classList.remove('show'); resetStars(); }
document.getElementById('reviewModal')?.addEventListener('click', function(e){
    if(e.target === this) closeReview();
});

/* Star rating */
let selectedRating = 0;
function hoverStar(v){
    document.querySelectorAll('#starInp i').forEach((s,i) => {
        s.className = i < v ? 'fas fa-star' : 'far fa-star';
        s.style.color = i < v ? '#ffa726' : '#ddd';
    });
}
function resetStars(){
    document.querySelectorAll('#starInp i').forEach((s,i) => {
        s.className = i < selectedRating ? 'fas fa-star' : 'far fa-star';
        s.style.color = i < selectedRating ? '#ffa726' : '#ddd';
    });
}
function selectStar(v){
    selectedRating = v;
    document.getElementById('ratingInput').value = v;
    resetStars();
}

/* Auto-dismiss alerts */
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
