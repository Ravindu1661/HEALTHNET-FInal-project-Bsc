@include('partials.header')
<style>
.pt-header{background:linear-gradient(135deg,#004d40 0%,#00796b 100%);padding:6rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.pt-header::before{content:'';position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1576602976047-174e57a47881?auto=format&fit=crop&w=2070&q=80') center/cover;opacity:.07;z-index:0}
.pt-header .container{position:relative;z-index:1}
.pt-header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:40px;background:#f4f6f9;clip-path:ellipse(55% 100% at 50% 100%)}
.pt-body{background:#f4f6f9;padding:2.2rem 0 3rem}
.pt-card{background:#fff;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,.07);overflow:hidden;margin-bottom:1.5rem}
.pt-card-header{padding:1rem 1.5rem;font-weight:700;font-size:.95rem;display:flex;align-items:center;gap:.5rem}
.pt-card-body{padding:1.5rem}
.lo-filter-bar{display:flex;align-items:center;flex-wrap:wrap;gap:.5rem;background:#fff;border-radius:12px;padding:.9rem 1.2rem;box-shadow:0 2px 10px rgba(0,0,0,.06);margin-bottom:1.5rem}
.lo-filter-label{font-size:.82rem;font-weight:700;color:#00796b;display:flex;align-items:center;gap:.4rem;margin-right:.3rem}
.lo-fbtn{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .9rem;border-radius:20px;background:#f0fdf4;color:#555;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .3s;border:1.5px solid transparent}
.lo-fbtn.active,.lo-fbtn:hover{background:#00796b;color:#fff;border-color:#00796b}
.lo-card{background:#fff;border-radius:14px;box-shadow:0 3px 14px rgba(0,0,0,.07);margin-bottom:1rem;overflow:hidden;border-left:4px solid #a5d6a7;transition:all .3s}
.lo-card:hover{box-shadow:0 6px 20px rgba(0,121,107,.12);transform:translateY(-2px)}
.lo-card.status-pending{border-left-color:#fbbf24}
.lo-card.status-verified{border-left-color:#60a5fa}
.lo-card.status-processing{border-left-color:#a78bfa}
.lo-card.status-ready{border-left-color:#34d399}
.lo-card.status-dispatched{border-left-color:#0891b2}
.lo-card.status-delivered{border-left-color:#16a34a}
.lo-card.status-cancelled{border-left-color:#f87171}
.lo-card-body{padding:1.1rem 1.5rem}
.lo-card-footer{background:#f8fafc;padding:.85rem 1.5rem;border-top:1px solid #e0f2f1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.7rem}
.lo-order-num{font-size:.88rem;font-weight:700;color:#004d40}
.lo-lab-name{font-size:.95rem;font-weight:700;color:#1a1a1a;margin:.1rem 0}
.lo-meta{display:flex;flex-wrap:wrap;gap:.4rem .9rem;margin-top:.35rem}
.lo-meta-item{font-size:.78rem;color:#888;display:flex;align-items:center;gap:.35rem}
.lo-meta-item i{color:#00796b}
.lo-amount{font-size:1rem;font-weight:700;color:#00796b}
.lo-amount-label{font-size:.76rem;color:#888}
.lo-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1rem;border-radius:20px;font-size:.8rem;font-weight:700;text-decoration:none;cursor:pointer;border:none;transition:all .3s}
.lo-btn-track{background:linear-gradient(135deg,#00796b,#004d40);color:#fff}
.lo-btn-track:hover{filter:brightness(1.1);color:#fff;transform:translateY(-1px)}
.lo-btn-pay{background:linear-gradient(135deg,#1565c0,#0d47a1);color:#fff}
.lo-btn-pay:hover{filter:brightness(1.1);color:#fff;transform:translateY(-1px)}
.lo-btn-cancel{background:#fff;color:#dc2626;border:1.5px solid #fca5a5 !important}
.lo-btn-cancel:hover{background:#fee2e2;color:#dc2626;border-color:#f87171 !important}
.s-pill,.p-pill{display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .8rem;border-radius:14px;font-size:.72rem;font-weight:700}
.s-pill.pending{background:#fef3c7;color:#92400e}.s-pill.verified{background:#e0f2fe;color:#0369a1}.s-pill.processing{background:#ede9fe;color:#4c1d95}.s-pill.ready{background:#dcfce7;color:#166534}.s-pill.dispatched{background:#e0f2f1;color:#004d40}.s-pill.delivered{background:#bbf7d0;color:#14532d}.s-pill.cancelled{background:#fee2e2;color:#991b1b}
.p-pill.paid{background:#dcfce7;color:#166534}.p-pill.unpaid{background:#fee2e2;color:#991b1b}
.tl{position:relative;padding-left:2.5rem}
.tl::before{content:'';position:absolute;left:12px;top:0;bottom:0;width:2px;background:#e0f2f1}
.tl-step{position:relative;margin-bottom:1.5rem}
.tl-dot{position:absolute;left:-2.5rem;top:2px;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.68rem;color:#fff;z-index:1;box-shadow:0 2px 6px rgba(0,0,0,.1);background:#e0e0e0}
.tl-dot.done{background:linear-gradient(135deg,#00796b,#4db6ac)}
.tl-dot.active{background:#ffa000;box-shadow:0 0 0 5px rgba(255,160,0,.2);animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{box-shadow:0 0 0 4px rgba(255,160,0,.2)}50%{box-shadow:0 0 0 8px rgba(255,160,0,.1)}}
.tl-label{font-weight:700;font-size:.9rem}
.tl-desc{font-size:.8rem;color:#888}
.empty-state{text-align:center;padding:3rem}
.empty-state i{font-size:3rem;color:#b2dfdb;display:block;margin-bottom:1rem}
.empty-state h4{color:#00796b;font-weight:700;margin-bottom:.5rem}
.empty-state p{color:#aaa;font-size:.9rem}
.lo-pagination .page-link{border-radius:8px!important;border:2px solid #e9ecef;color:#00796b;font-weight:600;padding:.45rem .85rem;font-size:.82rem;transition:all .2s}
.lo-pagination .page-link:hover,.lo-pagination .page-item.active .page-link{background:#00796b;border-color:#00796b;color:#fff}
@keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>

{{-- ═══════════════════════════════════════ --}}
{{-- HEADER                                  --}}
{{-- ═══════════════════════════════════════ --}}
<section class="pt-header">
    <div class="container">
        <a href="{{ route('patient.pharmacies.show', $pharmacy->id) }}"
           style="color:rgba(255,255,255,.85);font-size:.85rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.9rem;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $pharmacy->profile_image ? asset('storage/'.$pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                 style="width:60px;height:60px;border-radius:12px;object-fit:cover;border:3px solid rgba(255,255,255,.8)"
                 onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">
            <div>
                <h1 style="font-size:1.8rem;font-weight:700;margin:0">Track Your Orders</h1>
                <p style="opacity:.85;font-size:.9rem;margin:0">
                    <i class="fas fa-store me-1"></i>{{ $pharmacy->name }} &bull; {{ $pharmacy->city ?? '' }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ --}}
{{-- BODY                                    --}}
{{-- ═══════════════════════════════════════ --}}
<section class="pt-body">
    <div class="container">

        {{-- Flash Messages --}}
        @foreach(['success','error','info'] as $t)
            @if(session($t))
            <div style="background:{{ $t==='success'?'#dcfce7':($t==='error'?'#fee2e2':'#e0f2fe') }};border-left:5px solid {{ $t==='success'?'#059669':($t==='error'?'#dc2626':'#0891b2') }};border-radius:10px;padding:.85rem 1.1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.7rem;font-size:.88rem;font-weight:500">
                <i class="fas fa-{{ $t==='success'?'check-circle':($t==='error'?'exclamation-circle':'info-circle') }}" style="flex-shrink:0;font-size:1.1rem"></i>
                {{ session($t) }}
            </div>
            @endif
        @endforeach

        {{-- ═══ FILTER BAR ═══ --}}
        <div class="lo-filter-bar">
            <span class="lo-filter-label"><i class="fas fa-filter"></i> Filter</span>
            <a href="{{ route('patient.pharmacies.track', $pharmacy->id) }}"
               class="lo-fbtn {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> All
            </a>
            @foreach([
                'pending'    => 'clock,Pending',
                'verified'   => 'check,Verified',
                'processing' => 'cog,Processing',
                'ready'      => 'box-open,Ready',
                'dispatched' => 'truck,Dispatched',
                'delivered'  => 'check-circle,Delivered',
                'cancelled'  => 'times-circle,Cancelled',
            ] as $st => $meta)
                @php [$ic, $lbl] = explode(',', $meta) @endphp
                <a href="{{ route('patient.pharmacies.track', ['id' => $pharmacy->id, 'status' => $st]) }}"
                   class="lo-fbtn {{ request('status') === $st ? 'active' : '' }}">
                    <i class="fas fa-{{ $ic }}"></i> {{ $lbl }}
                </a>
            @endforeach
            @if(request('status'))
                <a href="{{ route('patient.pharmacies.track', $pharmacy->id) }}"
                   class="lo-fbtn" style="color:#dc2626;border-color:#dc2626">
                    <i class="fas fa-redo-alt"></i> Reset
                </a>
            @endif
        </div>

        <div class="row g-4">

            {{-- ═══════════════════════════════════════ --}}
            {{-- ORDER LIST                              --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="{{ $orderView ? 'col-lg-5' : 'col-lg-12' }}">

                {{-- Latest orders label --}}
                @if($orders->count() && $orders->currentPage() === 1)
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.8rem;font-size:.8rem;font-weight:700;color:#00796b">
                    <i class="fas fa-clock"></i> Showing latest orders first
                    <span style="background:#e0f2f1;color:#00796b;padding:.15rem .6rem;border-radius:10px;font-size:.72rem">
                        {{ $orders->total() }} total
                    </span>
                </div>
                @endif

                @forelse($orders as $order)
                @php
                    $isLatest    = $loop->first && $orders->currentPage() === 1;
                    $statusIcons = ['pending'=>'clock','verified'=>'check','processing'=>'cog','ready'=>'box-open','dispatched'=>'truck','delivered'=>'check-circle','cancelled'=>'times-circle'];
                    $icon        = $statusIcons[$order->status] ?? 'pills';
                    $label       = ucwords(str_replace('_', ' ', $order->status));
                    $canPay      = $order->payment_status === 'unpaid'
                                && ($order->total_amount ?? 0) > 0
                                && in_array($order->status, ['verified','processing','ready']);
                    $canCancel   = $order->status === 'pending';
                @endphp

                {{-- Latest badge --}}
                @if($isLatest)
                <div style="font-size:.75rem;font-weight:700;color:#fff;background:linear-gradient(135deg,#00796b,#004d40);display:inline-flex;align-items:center;gap:.35rem;padding:.2rem .75rem;border-radius:12px 12px 0 0;margin-bottom:-1px">
                    <i class="fas fa-star"></i> Latest Order
                </div>
                @endif

                <div class="lo-card status-{{ $order->status }}"
                     style="{{ $isLatest ? 'border-top:3px solid #00796b;' : '' }}">
                    <div class="lo-card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="lo-order-num">#{{ $order->order_number }}</div>
                                <div class="lo-lab-name">{{ $pharmacy->name }}</div>
                                <div class="lo-meta">
                                    <span class="lo-meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ optional($order->created_at)->format('d M Y, h:i A') }}
                                    </span>
                                    <span class="lo-meta-item">
                                        <i class="fas fa-{{ $order->delivery_address === 'PICKUP' ? 'store' : 'truck' }}"></i>
                                        {{ $order->delivery_address === 'PICKUP' ? 'Pickup' : 'Home Delivery' }}
                                    </span>
                                    @if($order->payment_method)
                                    <span class="lo-meta-item">
                                        <i class="fas fa-credit-card"></i>
                                        {{ $order->payment_method === 'cash_on_delivery' ? 'Cash on Delivery' : 'Online Pay' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="s-pill {{ $order->status }}">
                                    <i class="fas fa-{{ $icon }}"></i> {{ $label }}
                                </span>
                                <div style="margin-top:.4rem">
                                    <span class="p-pill {{ $order->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                                        <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check-circle' : 'clock' }}"></i>
                                        {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lo-card-footer">
                        <div>
                            <div class="lo-amount-label"><i class="fas fa-receipt me-1"></i>Total</div>
                            <div class="lo-amount">
                                @if(($order->total_amount ?? 0) > 0)
                                    LKR {{ number_format($order->total_amount + $order->delivery_fee, 2) }}
                                @else
                                    <span style="font-size:.78rem;color:#888">Pending confirmation</span>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                            <a href="{{ route('patient.pharmacies.track', ['id' => $pharmacy->id, 'order' => $order->id]) }}"
                               class="lo-btn lo-btn-track">
                                <i class="fas fa-eye"></i> Track
                            </a>
                            @if($canPay)
                            <a href="{{ route('patient.pharmacies.payment', $order->id) }}"
                               class="lo-btn lo-btn-pay">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </a>
                            @endif
                            @if($canCancel)
                            <button type="button"
                                class="lo-btn lo-btn-cancel"
                                onclick="openCancelModal('{{ $order->id }}','{{ $order->order_number }}')">
                                <i class="fas fa-times-circle"></i> Cancel
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                @empty
                <div class="pt-card">
                    <div class="empty-state">
                        <i class="fas fa-prescription-bottle-alt"></i>
                        <h4>No orders found</h4>
                        <p>{{ request('status') ? 'No ' . request('status') . ' orders found.' : 'You have no orders with this pharmacy yet.' }}</p>
                        <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}"
                           style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:.7rem 1.8rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-top:.5rem">
                            <i class="fas fa-prescription"></i> Place New Order
                        </a>
                    </div>
                </div>
                @endforelse

                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    <ul class="pagination lo-pagination">{{ $orders->withQueryString()->links() }}</ul>
                </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════ --}}
            {{-- ORDER DETAIL PANEL                      --}}
            {{-- ═══════════════════════════════════════ --}}
            @if($orderView)
            @php
                $steps = [
                    ['pending',    'clock',        'Order Submitted',       'Prescription uploaded and waiting for pharmacy review'],
                    ['verified',   'check',         'Prescription Verified', 'Pharmacy has validated your prescription and set the price'],
                    ['processing', 'cog',           'Being Processed',       'Pharmacy is preparing your medicines'],
                    ['ready',      'box-open',      'Ready',                 'Your order is packed and ready'],
                    ['dispatched', 'truck',          'Dispatched',            'Your order is on its way'],
                    ['delivered',  'check-circle',  'Delivered',             'Your medicines have been delivered'],
                ];
                $stepOrder  = ['pending','verified','processing','ready','dispatched','delivered'];
                $currentIdx = array_search($orderView->status, $stepOrder);
                $canPay     = $orderView->payment_status === 'unpaid'
                           && ($orderView->total_amount ?? 0) > 0
                           && in_array($orderView->status, ['verified','processing','ready']);
            @endphp
            <div class="col-lg-7">
                <div class="pt-card" style="position:sticky;top:1rem">
                    <div class="pt-card-header"
                         style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff">
                        <i class="fas fa-map-marker-alt"></i>
                        Order #{{ $orderView->order_number }} — Live Tracking
                    </div>
                    <div class="pt-card-body">

                        {{-- Status & Payment pills --}}
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            @php $oi = ['pending'=>'clock','verified'=>'check','processing'=>'cog','ready'=>'box-open','dispatched'=>'truck','delivered'=>'check-circle','cancelled'=>'times-circle']; @endphp
                            <span class="s-pill {{ $orderView->status }}">
                                <i class="fas fa-{{ $oi[$orderView->status] ?? 'pills' }}"></i>
                                {{ ucwords(str_replace('_',' ',$orderView->status)) }}
                            </span>
                            <span class="p-pill {{ $orderView->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                                <i class="fas fa-{{ $orderView->payment_status === 'paid' ? 'check-circle' : 'clock' }}"></i>
                                {{ ucfirst($orderView->payment_status) }}
                            </span>
                        </div>

                        {{-- Amount + Pay --}}
                        @if(($orderView->total_amount ?? 0) > 0)
                        <div style="background:linear-gradient(135deg,#e0f2f1,#b2dfdb);border-radius:12px;padding:1rem 1.3rem;margin-bottom:1.2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem">
                            <div>
                                <div style="font-size:.78rem;color:#555;font-weight:600">Total Amount Due</div>
                                <div style="font-size:1.5rem;font-weight:800;color:#00796b">
                                    LKR {{ number_format($orderView->total_amount + $orderView->delivery_fee, 2) }}
                                </div>
                                @if($orderView->delivery_fee > 0)
                                <div style="font-size:.76rem;color:#555">
                                    Includes delivery: LKR {{ number_format($orderView->delivery_fee, 2) }}
                                </div>
                                @endif
                            </div>
                            @if($canPay)
                            <a href="{{ route('patient.pharmacies.payment', $orderView->id) }}"
                               class="lo-btn lo-btn-pay" style="padding:.6rem 1.4rem;font-size:.9rem">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </a>
                            @elseif($orderView->payment_status === 'paid')
                            <span style="background:#dcfce7;color:#166534;padding:.5rem 1rem;border-radius:20px;font-weight:700;font-size:.85rem">
                                <i class="fas fa-check-circle me-1"></i>Paid
                            </span>
                            @endif
                        </div>
                        @else
                        <div style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;padding:.8rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#92400e;font-weight:500">
                            <i class="fas fa-info-circle me-1"></i>
                            The pharmacy will confirm the amount after reviewing your prescription.
                        </div>
                        @endif

                        {{-- Timeline --}}
                        @if($orderView->status !== 'cancelled')
                        <div style="font-size:.82rem;font-weight:700;color:#00796b;margin-bottom:.9rem">
                            <i class="fas fa-route me-1"></i>Order Progress
                        </div>
                        <div class="tl">
                            @foreach($steps as [$st, $ic, $lbl, $desc])
                            @php
                                $idx    = array_search($st, $stepOrder);
                                $done   = $idx < $currentIdx;
                                $active = $idx === $currentIdx;
                            @endphp
                            <div class="tl-step">
                                <div class="tl-dot {{ $done ? 'done' : ($active ? 'active' : '') }}">
                                    <i class="fas fa-{{ $done ? 'check' : $ic }}"></i>
                                </div>
                                <div class="{{ $done || $active ? '' : 'opacity-50' }}">
                                    <div class="tl-label"
                                         style="{{ $active ? 'color:#00796b' : ($done ? 'color:#333' : 'color:#aaa') }}">
                                        {{ $lbl }}
                                        @if($active)
                                        <span style="font-size:.7rem;background:#ffa000;color:#fff;padding:.1rem .5rem;border-radius:8px;margin-left:.4rem">Current</span>
                                        @endif
                                    </div>
                                    <div class="tl-desc">{{ $desc }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div style="background:#fee2e2;border-left:4px solid #f87171;border-radius:8px;padding:1rem;color:#991b1b;font-weight:600">
                            <i class="fas fa-times-circle me-1"></i>This order has been cancelled.
                            @if($orderView->cancelled_reason)
                            <div style="font-size:.82rem;font-weight:400;margin-top:.4rem;color:#b91c1c">
                                Reason: {{ $orderView->cancelled_reason }}
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Order Items --}}
                        @if($orderView->items->count())
                        <div style="margin-top:1.3rem">
                            <div style="font-size:.82rem;font-weight:700;color:#00796b;margin-bottom:.6rem">
                                <i class="fas fa-list-alt me-1"></i>Ordered Medicines
                            </div>
                            @foreach($orderView->items as $item)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid #f0f4f0;font-size:.84rem">
                                <div>
                                    <div style="font-weight:600">{{ $item->medication_name }}</div>
                                    @if(isset($item->dosage) && $item->dosage)
                                    <div style="font-size:.74rem;color:#888">{{ $item->dosage }}</div>
                                    @endif
                                </div>
                                <div style="text-align:right">
                                    <div style="font-weight:700;color:#00796b">LKR {{ number_format($item->price, 2) }}</div>
                                    <div style="font-size:.74rem;color:#888">Qty: {{ $item->quantity }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Order Details --}}
                        <div style="background:#f8fafc;border-radius:10px;padding:.9rem;margin-top:1rem">
                            <div style="font-size:.82rem;font-weight:700;color:#00796b;margin-bottom:.5rem">
                                <i class="fas fa-info-circle me-1"></i>Order Details
                            </div>
                            <div style="font-size:.83rem;color:#555;line-height:2">
                                <div>
                                    <i class="fas fa-calendar-alt me-2" style="color:#00796b;width:16px"></i>
                                    <strong>Placed:</strong>
                                    {{ optional($orderView->created_at)->format('d M Y, h:i A') }}
                                </div>
                                <div>
                                    <i class="fas fa-{{ $orderView->delivery_address === 'PICKUP' ? 'store' : 'truck' }} me-2" style="color:#00796b;width:16px"></i>
                                    <strong>Delivery:</strong>
                                    {{ $orderView->delivery_address === 'PICKUP' ? 'Pickup at store' : $orderView->delivery_address }}
                                    @if($orderView->delivery_method)
                                        ({{ ucfirst($orderView->delivery_method) }})
                                    @endif
                                </div>
                                @if($orderView->prescription_file)
                                <div>
                                    <i class="fas fa-file-medical me-2" style="color:#00796b;width:16px"></i>
                                    <strong>Prescription:</strong>
                                    <a href="{{ asset('storage/' . $orderView->prescription_file) }}"
                                       target="_blank"
                                       style="color:#00796b;font-weight:600;text-decoration:none">
                                        View File <i class="fas fa-external-link-alt" style="font-size:.7rem"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Cancel button — detail panel --}}
                        @if($orderView->status === 'pending')
                        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f0f4f0">
                            <button type="button"
                                onclick="openCancelModal('{{ $orderView->id }}','{{ $orderView->order_number }}')"
                                style="width:100%;padding:.75rem;background:#fff;border:2px solid #fca5a5;border-radius:10px;color:#dc2626;font-weight:700;font-size:.86rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:all .3s"
                                onmouseover="this.style.background='#fee2e2'"
                                onmouseout="this.style.background='#fff'">
                                <i class="fas fa-times-circle"></i> Cancel This Order
                            </button>
                        </div>
                        @endif

                        {{-- Close panel --}}
                        <div class="text-center mt-3">
                            <a href="{{ route('patient.pharmacies.track', $pharmacy->id) }}"
                               style="color:#aaa;font-size:.83rem;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem">
                                <i class="fas fa-times"></i> Close Detail View
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Place New Order CTA --}}
        <div style="text-align:center;margin-top:1.5rem">
            <a href="{{ route('patient.pharmacies.order.form', $pharmacy->id) }}"
               style="background:linear-gradient(135deg,#00796b,#004d40);color:#fff;padding:.8rem 2.2rem;border-radius:25px;text-decoration:none;font-weight:700;font-size:.92rem;display:inline-flex;align-items:center;gap:.6rem;box-shadow:0 4px 14px rgba(0,121,107,.3);transition:all .3s">
                <i class="fas fa-prescription"></i> Place New Order
            </a>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- CANCEL ORDER MODAL                                      --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div id="cancelModal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:18px;padding:2rem;max-width:440px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);position:relative;animation:slideUp .3s ease">

        {{-- Icon --}}
        <div style="text-align:center;margin-bottom:1.2rem">
            <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .8rem">
                <i class="fas fa-exclamation-triangle" style="font-size:1.6rem;color:#dc2626"></i>
            </div>
            <h5 style="font-weight:800;color:#1a1a1a;margin:0">Cancel Order?</h5>
            <p style="color:#888;font-size:.85rem;margin:.4rem 0 0">
                Order <strong>#<span id="cancelOrderNum"></span></strong> will be permanently cancelled.
            </p>
        </div>

        {{-- Form --}}
        <form id="cancelForm" method="POST">
            @csrf
            <div style="margin-bottom:1.2rem">
                <label style="font-size:.83rem;font-weight:600;color:#444;display:block;margin-bottom:.4rem">
                    Reason for cancellation
                    <span style="color:#aaa;font-weight:400">(optional)</span>
                </label>
                <textarea name="cancelled_reason" rows="3"
                    style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:.7rem .9rem;font-size:.85rem;resize:none;outline:none;transition:border .2s"
                    placeholder="e.g. Found medicines elsewhere, wrong prescription uploaded..."
                    onfocus="this.style.borderColor='#00796b'"
                    onblur="this.style.borderColor='#e5e7eb'"></textarea>
            </div>

            {{-- Warning --}}
            <div style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;padding:.7rem .9rem;font-size:.8rem;color:#92400e;margin-bottom:1.3rem;display:flex;gap:.5rem;align-items:flex-start">
                <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:.15rem"></i>
                <span>This action <strong>cannot be undone</strong>. Only <strong>pending</strong> orders can be cancelled.</span>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:.7rem">
                <button type="button" onclick="closeCancelModal()"
                    style="flex:1;padding:.75rem;border:2px solid #e5e7eb;border-radius:10px;background:#fff;color:#555;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .2s"
                    onmouseover="this.style.borderColor='#aaa'"
                    onmouseout="this.style.borderColor='#e5e7eb'">
                    <i class="fas fa-arrow-left me-1"></i> Keep Order
                </button>
                <button type="submit"
                    style="flex:1;padding:.75rem;border:none;border-radius:10px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .2s"
                    onmouseover="this.style.filter='brightness(1.1)'"
                    onmouseout="this.style.filter='brightness(1)'">
                    <i class="fas fa-times-circle me-1"></i> Yes, Cancel Order
                </button>
            </div>
        </form>

        {{-- Close X --}}
        <button onclick="closeCancelModal()"
            style="position:absolute;top:.8rem;right:.9rem;background:none;border:none;font-size:1.3rem;color:#aaa;cursor:pointer;line-height:1">
            &times;
        </button>
    </div>
</div>

<script>
function openCancelModal(orderId, orderNum) {
    document.getElementById('cancelOrderNum').textContent = orderNum;
    document.getElementById('cancelForm').action =
        '{{ route("patient.pharmacies.order.cancel", ["id" => $pharmacy->id, "orderId" => "__OID__"]) }}'
        .replace('__OID__', orderId);
    const modal = document.getElementById('cancelModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeCancelModal();
});

document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});
</script>

@include('partials.footer')
