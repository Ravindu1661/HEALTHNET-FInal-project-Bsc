@include('partials.header')

<style>
.pay-hdr { background:linear-gradient(135deg,#4a148c 0%,#7b1fa2 100%); padding:6rem 0 2.5rem; color:white; position:relative; overflow:hidden; }
.pay-hdr::before { content:''; position:absolute; inset:0; opacity:.07; background:url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?auto=format&fit=crop&w=2070&q=80') center/cover; }
.pay-hdr .container { position:relative; z-index:1; }

.pay-card { background:white; border-radius:14px; box-shadow:0 8px 30px rgba(0,0,0,.08); overflow:hidden; margin-bottom:1.5rem; }
.pay-card-hdr { padding:1.1rem 1.5rem; display:flex; align-items:center; gap:.6rem; font-weight:700; font-size:.95rem; }
.pay-card-body { padding:1.6rem; }

.pay-row { display:flex; justify-content:space-between; padding:.5rem 0; border-bottom:1px solid #f5f5f5; font-size:.88rem; }
.pay-row:last-child { border-bottom:none; }

.f-lbl { display:block; font-size:.82rem; font-weight:600; color:#4a148c; margin-bottom:.4rem; }
.f-in { width:100%; padding:.75rem 1rem; border:2px solid #e9ecef; border-radius:8px; font-size:.9rem; transition:all .3s; }
.f-in:focus { border-color:#7b1fa2; outline:none; box-shadow:0 0 0 3px rgba(123,31,162,.1); }

.stripe-wrap { border:2px solid #e9ecef; border-radius:10px; padding:.9rem 1rem; background:white; transition:border-color .3s; min-height:48px; }
.stripe-wrap.focused { border-color:#7b1fa2; box-shadow:0 0 0 3px rgba(123,31,162,.1); }

.err-box { background:#f8d7da; color:#721c24; border-left:4px solid #dc3545; padding:.75rem 1rem; border-radius:8px; font-size:.85rem; margin-top:.7rem; display:none; align-items:center; gap:.5rem; }
.err-box.show { display:flex; }

.btn-pay { background:linear-gradient(135deg,#7b1fa2,#4a148c); color:white; border:none; padding:1rem 2rem; border-radius:25px; font-size:1rem; font-weight:700; cursor:pointer; transition:all .3s; width:100%; display:flex; align-items:center; justify-content:center; gap:.6rem; box-shadow:0 4px 15px rgba(123,31,162,.35); margin-top:1.5rem; }
.btn-pay:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 6px 22px rgba(123,31,162,.45); }
.btn-pay:disabled { opacity:.75; cursor:not-allowed; transform:none; }

.pay-spinner { width:20px; height:20px; border:3px solid rgba(255,255,255,.4); border-top-color:white; border-radius:50%; animation:spin .8s linear infinite; display:none; flex-shrink:0; }
@keyframes spin { to { transform:rotate(360deg); } }

.fee-box { background:linear-gradient(135deg,rgba(123,31,162,.08),rgba(123,31,162,.15)); border:2px solid rgba(123,31,162,.25); border-radius:12px; padding:1.2rem; text-align:center; margin-top:1rem; }
.fee-amount { font-size:2.2rem; font-weight:700; color:#7b1fa2; }
</style>

<section class="pay-hdr">
    <div class="container">
        <a href="{{ route('patient.lab-orders.show', $order->id) }}"
           style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;display:inline-flex;align-items:center;gap:.4rem;margin-bottom:1rem;">
            <i class="fas fa-arrow-left"></i> Back to Order
        </a>
        <h1 style="font-size:1.8rem;font-weight:700;margin-bottom:.3rem;">
            <i class="fas fa-credit-card me-2"></i> Complete Payment
        </h1>
        <p style="opacity:.85;font-size:.9rem;">Secure payment powered by Stripe</p>
    </div>
</section>

<section style="background:#faf4fc;padding:2.5rem 0;min-height:600px;">
    <div class="container">

        @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
        @endif

        <div class="row g-4 justify-content-center">

            {{-- Summary --}}
            <div class="col-lg-5">
                <div class="pay-card">
                    <div class="pay-card-hdr" style="background:linear-gradient(135deg,#4a148c,#7b1fa2);color:white;">
                        <i class="fas fa-flask"></i> Order Summary
                    </div>
                    <div class="pay-card-body">
                        <div class="pay-row">
                            <span style="color:#888;">Order No.</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="pay-row">
                            <span style="color:#888;">Laboratory</span>
                            <span>{{ $order->laboratory->name ?? 'N/A' }}</span>
                        </div>
                        <div class="pay-row">
                            <span style="color:#888;">Tests</span>
                            <span>{{ $order->items->count() }} item(s)</span>
                        </div>
                        @if($order->collection_date)
                        <div class="pay-row">
                            <span style="color:#888;">Date</span>
                            <span>{{ \Carbon\Carbon::parse($order->collection_date)->format('d M Y') }}</span>
                        </div>
                        @endif
                        <div class="pay-row">
                            <span style="color:#888;">Collection</span>
                            <span>{{ $order->home_collection ? 'Home Collection' : 'Lab Visit' }}</span>
                        </div>

                        <div class="fee-box">
                            <div style="font-size:.82rem;color:#666;margin-bottom:.3rem;">Amount Due</div>
                            <div class="fee-amount">Rs. {{ number_format($order->total_amount ?? 0, 2) }}</div>
                            <div style="font-size:.72rem;color:#999;margin-top:.2rem;">Sri Lankan Rupees (LKR)</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Form --}}
            <div class="col-lg-6">
                <div class="pay-card">
                    <div class="pay-card-hdr" style="background:linear-gradient(135deg,#7b1fa2,#4a148c);color:white;">
                        <i class="fas fa-lock"></i> Secure Card Payment
                    </div>
                    <div class="pay-card-body">

                        {{-- Test mode notice --}}
                        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:.8rem 1rem;margin-bottom:1.2rem;font-size:.82rem;color:#856404;">
                            <strong><i class="fas fa-vial me-1"></i> Test Mode:</strong>
                            Card: <code style="background:rgba(0,0,0,.07);padding:.1rem .4rem;border-radius:4px;">4242 4242 4242 4242</code>
                            · Exp: <code style="background:rgba(0,0,0,.07);padding:.1rem .4rem;border-radius:4px;">12/26</code>
                            · CVC: <code style="background:rgba(0,0,0,.07);padding:.1rem .4rem;border-radius:4px;">123</code>
                        </div>

                        <form id="labPayForm"
                              action="{{ route('patient.lab-orders.pay', $order->id) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" id="pm_id">
                            <input type="hidden" name="cardholder_name"   id="ch_name">

                            <div style="margin-bottom:1.1rem;">
                                <label class="f-lbl">Cardholder Name <span style="color:#dc3545;">*</span></label>
                                <input type="text" id="cardholderName" class="f-in"
                                       placeholder="Full name on card" autocomplete="cc-name">
                                <div id="nameErr" style="color:#dc3545;font-size:.78rem;margin-top:.3rem;display:none;">
                                    Please enter cardholder name.
                                </div>
                            </div>

                            <div style="margin-bottom:.5rem;">
                                <label class="f-lbl">Card Details <span style="color:#dc3545;">*</span></label>
                                <div id="card-element" class="stripe-wrap"></div>
                            </div>

                            <div id="card-errors" class="err-box" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="card-err-msg"></span>
                            </div>

                            <button type="submit" id="payBtn" class="btn-pay">
                                <div class="pay-spinner" id="paySpinner"></div>
                                <i class="fas fa-lock" id="payIcon"></i>
                                <span id="payBtnText">
                                    Pay Rs. {{ number_format($order->total_amount ?? 0, 2) }}
                                </span>
                            </button>
                        </form>

                        <div style="display:flex;justify-content:center;gap:1.5rem;margin-top:1.2rem;flex-wrap:wrap;">
                            <span style="display:flex;align-items:center;gap:.3rem;font-size:.72rem;color:#aaa;">
                                <i class="fas fa-shield-alt" style="color:#43a047;"></i> SSL Secured
                            </span>
                            <span style="display:flex;align-items:center;gap:.3rem;font-size:.72rem;color:#aaa;">
                                <i class="fab fa-stripe" style="color:#635bff;"></i> Stripe
                            </span>
                            <span style="display:flex;align-items:center;gap:.3rem;font-size:.72rem;color:#aaa;">
                                <i class="fas fa-lock" style="color:#7b1fa2;"></i> PCI Compliant
                            </span>
                        </div>

                        <div style="text-align:center;margin-top:1rem;">
                            <a href="{{ route('patient.lab-orders.show', $order->id) }}"
                               style="color:#aaa;font-size:.78rem;text-decoration:none;">
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

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stripeKey = '{{ $stripeKey ?? "" }}';

    if (!stripeKey) {
        const eb = document.getElementById('card-errors');
        const em = document.getElementById('card-err-msg');
        eb.classList.add('show');
        em.textContent = 'Stripe configuration error. Please contact support.';
        document.getElementById('payBtn').disabled = true;
        return;
    }

    const stripe      = Stripe(stripeKey);
    const elements    = stripe.elements();
    const cardElement = elements.create('card', {
        hidePostalCode: false,
        style: {
            base: {
                fontSize: '15px', color: '#333',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
                '::placeholder': { color: '#aab7c4' },
            },
            invalid: { color: '#dc3545', iconColor: '#dc3545' },
        },
    });

    cardElement.mount('#card-element');

    cardElement.on('focus', () => {
        document.getElementById('card-element').classList.add('focused');
    });
    cardElement.on('blur', () => {
        document.getElementById('card-element').classList.remove('focused');
    });
    cardElement.on('change', (event) => {
        const eb = document.getElementById('card-errors');
        const em = document.getElementById('card-err-msg');
        if (event.error) {
            eb.classList.add('show');
            em.textContent = event.error.message;
        } else {
            eb.classList.remove('show');
            em.textContent = '';
        }
    });

    document.getElementById('labPayForm').addEventListener('submit', async function

---

## File 6: `app/Models/LabOrder.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    protected $fillable = [
        'order_number',
        'reference_number',
        'patient_id',
        'laboratory_id',
        'doctor_id',
        'prescription_file',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'home_collection',
        'collection_address',
        'collection_date',
        'collection_time',
        'report_file',
        'report_uploaded_at',
    ];

    protected $casts = [
        'home_collection'    => 'boolean',
        'collection_date'    => 'date',
        'report_uploaded_at' => 'datetime',
        'order_date'         => 'datetime',
    ];

    // ══════════════════════════════════
    // Relationships
    // ══════════════════════════════════

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class)->withDefault();
    }

    public function items()
    {
        return $this->hasMany(LabOrderItem::class, 'order_id');
    }

    // ══════════════════════════════════
    // Helpers
    // ══════════════════════════════════

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function hasReport(): bool
    {
        return !empty($this->report_file);
    }

    public function canDownloadReport(): bool
    {
        return $this->isCompleted() && $this->isPaid() && $this->hasReport();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'          => '<span class="badge" style="background:#fff3e0;color:#e65100;">⏳ Pending</span>',
            'sample_collected' => '<span class="badge" style="background:#e3f2fd;color:#0d47a1;">🧫 Sample Collected</span>',
            'processing'       => '<span class="badge" style="background:#e8eaf6;color:#283593;">🔬 Processing</span>',
            'completed'        => '<span class="badge" style="background:#e8f5e9;color:#1b5e20;">✅ Completed</span>',
            'cancelled'        => '<span class="badge" style="background:#fce4ec;color:#880e4f;">❌ Cancelled</span>',
            default            => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}
