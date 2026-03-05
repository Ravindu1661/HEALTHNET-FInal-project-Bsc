{{-- resources/views/pharmacy/orders/create.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'New Order')
@section('page-title', 'Create New Order')

@push('styles')
<style>
.medicine-row {
    background:#f8fafc; border-radius:8px;
    padding:.75rem; margin-bottom:.5rem;
    border:1px solid #e5e7eb;
}
.medicine-row:hover { border-color:#2563eb; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
<div class="col-xl-10">

<div class="d-flex align-items-center mb-4 gap-2">
    {{-- ✅ route: orders.index --}}
    <a href="{{ route('pharmacy.orders.index') }}"
       class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <h5 class="fw-bold mb-0">Create New Prescription Order</h5>
</div>

{{-- ✅ route: orders.store (POST /orders/store) --}}
<form action="{{ route('pharmacy.orders.store') }}"
      method="POST" enctype="multipart/form-data" id="orderForm">
@csrf

<div class="row g-3">

    {{-- ── Left Panel ── --}}
    <div class="col-lg-8">

        {{-- Patient --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-user me-2 text-primary"></i>Patient Selection</h6>
            </div>
            <div class="card-body">
                <select name="patient_id"
                        class="form-select @error('patient_id') is-invalid @enderror"
                        required id="patientSelect">
                    <option value="">— Select Patient —</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}"
                            {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->first_name }} {{ $p->last_name }}
                        ({{ $p->phone ?? $p->email }})
                    </option>
                    @endforeach
                </select>
                @error('patient_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Prescription Upload --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-file-prescription me-2 text-info"></i>Prescription File</h6>
            </div>
            <div class="card-body">
                <input type="file" name="prescription_file"
                       class="form-control @error('prescription_file') is-invalid @enderror"
                       accept=".pdf,.jpg,.jpeg,.png" required id="prescFile">
                <div class="form-text">Accepted: PDF, JPG, PNG — Max 5MB</div>
                @error('prescription_file')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div id="prescPreview" class="mt-2" style="display:none">
                    <img id="prescImg" src="" class="img-fluid rounded"
                         style="max-height:150px" alt="preview">
                </div>
            </div>
        </div>

        {{-- Medicines --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-pills me-2 text-primary"></i>Medicines</h6>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill"
                        onclick="addRow()">
                    <i class="fas fa-plus me-1"></i> Add Row
                </button>
            </div>
            <div class="card-body">
                <div id="medicines-container">
                    <div class="medicine-row" id="row-0">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label form-label-sm mb-1">
                                    Medicine <span class="text-danger">*</span>
                                </label>
                                <select name="items[0][medication_id]"
                                        class="form-select form-select-sm med-select"
                                        required onchange="fillPrice(this, 0)">
                                    <option value="">— Select —</option>
                                    @foreach($medicines as $med)
                                    <option value="{{ $med->id }}"
                                            data-price="{{ $med->price }}"
                                            data-stock="{{ $med->stock_quantity }}">
                                        {{ $med->name }} (Stock: {{ $med->stock_quantity }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label form-label-sm mb-1">Qty</label>
                                <input type="number" name="items[0][quantity]"
                                       class="form-control form-control-sm qty-input"
                                       min="1" value="1" required onchange="calcRow(0)">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm mb-1">
                                    Unit Price (Rs.)
                                </label>
                                <input type="number" name="items[0][price]"
                                       class="form-control form-control-sm price-input"
                                       min="0" step="0.01" value="0" required
                                       onchange="calcRow(0)">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label form-label-sm mb-1">Total</label>
                                <div class="fw-semibold text-primary" id="sub-0"
                                     style="font-size:.85rem;padding:.375rem 0">0.00</div>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill remove-row"
                                        onclick="removeRow('row-0')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-end">
                    <span class="text-muted" style="font-size:.85rem">Medicines Total:</span>
                    <span class="fw-bold ms-2" id="grandTotal"
                          style="font-size:1rem">Rs. 0.00</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Right Panel ── --}}
    <div class="col-lg-4">

        {{-- Delivery --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-truck me-2 text-secondary"></i>Delivery</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold form-label-sm">
                        Delivery Address <span class="text-danger">*</span>
                    </label>
                    <textarea name="delivery_address"
                              class="form-control form-control-sm
                                     @error('delivery_address') is-invalid @enderror"
                              rows="2" placeholder="Full delivery address…"
                              required>{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold form-label-sm">
                        Delivery Method
                    </label>
                    <select name="delivery_method" class="form-select form-select-sm">
                        <option value="">— None / Pickup —</option>
                        <option value="uber"
                            {{ old('delivery_method')=='uber' ? 'selected':'' }}>Uber</option>
                        <option value="pickme"
                            {{ old('delivery_method')=='pickme' ? 'selected':'' }}>PickMe</option>
                        <option value="own_delivery"
                            {{ old('delivery_method')=='own_delivery' ? 'selected':'' }}>
                            Own Delivery
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold form-label-sm">
                        Delivery Fee (Rs.)
                    </label>
                    <input type="number" name="delivery_fee" id="deliveryFee"
                           class="form-control form-control-sm"
                           min="0" step="0.01"
                           value="{{ old('delivery_fee', 0) }}"
                           onchange="updateOrderTotal()">
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-credit-card me-2 text-success"></i>Payment</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold form-label-sm">
                        Payment Method <span class="text-danger">*</span>
                    </label>
                    <select name="payment_method"
                            class="form-select form-select-sm
                                   @error('payment_method') is-invalid @enderror" required>
                        <option value="cash_on_delivery"
                            {{ old('payment_method','cash_on_delivery')=='cash_on_delivery' ? 'selected':'' }}>
                            Cash on Delivery
                        </option>
                        <option value="online"
                            {{ old('payment_method')=='online' ? 'selected':'' }}>Online</option>
                    </select>
                    @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-sticky-note me-2 text-warning"></i>Pharmacist Notes</h6>
            </div>
            <div class="card-body">
                <textarea name="pharmacist_notes" class="form-control form-control-sm"
                          rows="3" placeholder="Optional internal notes…">
                    {{ old('pharmacist_notes') }}
                </textarea>
            </div>
        </div>

        {{-- Summary --}}
        <div class="dashboard-card mb-3" style="border-left:4px solid #2563eb">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order Summary</h6>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:.85rem">Medicines</span>
                    <span id="summaryMeds" class="fw-semibold" style="font-size:.85rem">
                        Rs. 0.00
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted" style="font-size:.85rem">Delivery</span>
                    <span id="summaryDel" class="fw-semibold" style="font-size:.85rem">
                        Rs. 0.00
                    </span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total</span>
                    <span id="summaryTotal" class="fw-bold text-primary"
                          style="font-size:1.05rem">Rs. 0.00</span>
                </div>
            </div>
        </div>

        {{-- ✅ route: orders.store --}}
        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
            <i class="fas fa-save me-1"></i> Create Order
        </button>
        {{-- ✅ route: orders.index --}}
        <a href="{{ route('pharmacy.orders.index') }}"
           class="btn btn-outline-secondary w-100 rounded-pill py-2 mt-2">
            Cancel
        </a>

    </div>
</div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
let rowCount = 1;

function addRow() {
    const idx        = rowCount++;
    const medOptions = document.querySelector('.med-select').innerHTML;
    const html = `
    <div class="medicine-row" id="row-${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label form-label-sm mb-1">Medicine <span class="text-danger">*</span></label>
                <select name="items[${idx}][medication_id]"
                        class="form-select form-select-sm med-select"
                        required onchange="fillPrice(this, ${idx})">
                    ${medOptions}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Qty</label>
                <input type="number" name="items[${idx}][quantity]"
                       class="form-control form-control-sm qty-input"
                       min="1" value="1" required onchange="calcRow(${idx})">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Unit Price (Rs.)</label>
                <input type="number" name="items[${idx}][price]"
                       class="form-control form-control-sm price-input"
                       min="0" step="0.01" value="0" required onchange="calcRow(${idx})">
            </div>
            <div class="col-md-1">
                <label class="form-label form-label-sm mb-1">Total</label>
                <div class="fw-semibold text-primary" id="sub-${idx}"
                     style="font-size:.85rem;padding:.375rem 0">0.00</div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button"
                        class="btn btn-sm btn-outline-danger rounded-pill remove-row"
                        onclick="removeRow('row-${idx}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>`;
    document.getElementById('medicines-container').insertAdjacentHTML('beforeend', html);
}

function removeRow(id) {
    const rows = document.querySelectorAll('#medicines-container .medicine-row');
    if (rows.length <= 1) return;
    document.getElementById(id).remove();
    updateOrderTotal();
}

function fillPrice(select, idx) {
    const opt   = select.options[select.selectedIndex];
    const price = opt?.dataset.price ?? 0;
    const row   = document.getElementById('row-' + idx);
    row.querySelector('.price-input').value = parseFloat(price).toFixed(2);
    calcRow(idx);
}

function calcRow(idx) {
    const row = document.getElementById('row-' + idx);
    if (!row) return;
    const qty   = parseFloat(row.querySelector('.qty-input').value)   || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const sub   = qty * price;
    const el    = document.getElementById('sub-' + idx);
    if (el) el.textContent = sub.toFixed(2);
    updateOrderTotal();
}

function updateOrderTotal() {
    let total = 0;
    document.querySelectorAll('#medicines-container .medicine-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('.qty-input')?.value)   || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        total += qty * price;
    });
    const del = parseFloat(document.getElementById('deliveryFee').value) || 0;
    document.getElementById('grandTotal').textContent   = 'Rs. ' + total.toFixed(2);
    document.getElementById('summaryMeds').textContent  = 'Rs. ' + total.toFixed(2);
    document.getElementById('summaryDel').textContent   = 'Rs. ' + del.toFixed(2);
    document.getElementById('summaryTotal').textContent = 'Rs. ' + (total + del).toFixed(2);
}

// Prescription image preview
document.getElementById('prescFile').addEventListener('change', function () {
    const file    = this.files[0];
    if (!file) return;
    const preview = document.getElementById('prescPreview');
    const img     = document.getElementById('prescImg');
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.style.display = ''; };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
