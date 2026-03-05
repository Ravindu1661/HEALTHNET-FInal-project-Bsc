@extends('pharmacy.layouts.master')

@section('title', 'Add Medicine')
@section('page-title', 'Add Medicine')
@section('page-subtitle', 'Add a new medicine to your pharmacy')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-plus-circle me-2 text-primary"></i>Add New Medicine
        </h6>
        <small class="text-muted">Fields marked <span class="text-danger">*</span> are required</small>
    </div>
    <a href="{{ route('pharmacy.medicines.index') }}"
       class="btn btn-outline-secondary btn-sm" style="font-size:12px">
        <i class="fas fa-arrow-left me-1"></i>Back to Medicines
    </a>
</div>

<form action="{{ route('pharmacy.medicines.store') }}" method="POST" id="createMedicineForm">
    @csrf
    <div class="row g-3">

        {{-- Left --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-pills me-2 text-primary"></i>Medicine Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- name --}}
                        <div class="col-md-8">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Medicine Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="e.g. Paracetamol 500mg"
                                   style="font-size:12px" autofocus>
                            @error('name')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- category --}}
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Category <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="category" value="{{ old('category') }}"
                                   class="form-control form-control-sm @error('category') is-invalid @enderror"
                                   placeholder="e.g. Analgesic"
                                   style="font-size:12px" list="categoryList">
                            <datalist id="categoryList">
                                @foreach(['Analgesic','Antibiotic','Antiviral','Antifungal','Antidiabetic',
                                          'Antihypertensive','Antihistamine','Antacid','Vitamins & Supplements',
                                          'Cardiovascular','Respiratory','Gastrointestinal','Dermatology',
                                          'Ophthalmology','Neurology','Oncology','Hormonal','Other'] as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- generic_name --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">Generic Name</label>
                            <input type="text" name="generic_name" value="{{ old('generic_name') }}"
                                   class="form-control form-control-sm @error('generic_name') is-invalid @enderror"
                                   placeholder="e.g. Acetaminophen" style="font-size:12px">
                            @error('generic_name')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- manufacturer --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">Manufacturer</label>
                            <input type="text" name="manufacturer" value="{{ old('manufacturer') }}"
                                   class="form-control form-control-sm @error('manufacturer') is-invalid @enderror"
                                   placeholder="e.g. Hemas Pharmaceuticals" style="font-size:12px">
                            @error('manufacturer')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- dosage --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">Dosage</label>
                            <input type="text" name="dosage" value="{{ old('dosage') }}"
                                   class="form-control form-control-sm @error('dosage') is-invalid @enderror"
                                   placeholder="e.g. 500mg, 1 tablet twice daily"
                                   style="font-size:12px">
                            @error('dosage')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- price --}}
                        <div class="col-md-3">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Price (Rs.) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light" style="font-size:11px">Rs.</span>
                                <input type="number" name="price" value="{{ old('price') }}"
                                       step="0.01" min="0"
                                       class="form-control form-control-sm @error('price') is-invalid @enderror"
                                       placeholder="0.00" style="font-size:12px">
                            </div>
                            @error('price')
                            <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- stock_quantity --}}
                        <div class="col-md-3">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="stock_quantity"
                                       value="{{ old('stock_quantity', 0) }}"
                                       min="0"
                                       class="form-control form-control-sm @error('stock_quantity') is-invalid @enderror"
                                       placeholder="0" style="font-size:12px"
                                       oninput="updateStockPreview(this.value)">
                                <span class="input-group-text bg-light" style="font-size:11px">units</span>
                            </div>
                            @error('stock_quantity')
                            <div class="text-danger mt-1" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- description --}}
                        <div class="col-12">
                            <label class="form-label" style="font-size:12px;font-weight:600">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      placeholder="Medicine description, uses, side effects, storage..."
                                      style="font-size:12px">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="col-lg-4">

            {{-- Stock Preview --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-boxes me-2 text-warning"></i>Stock Preview</h6>
                </div>
                <div class="card-body text-center py-3">
                    <div id="stockPreviewBadge">
                        <span class="badge bg-secondary fs-6 px-3 py-2">
                            <i class="fas fa-box me-1"></i>
                            <span id="stockPreviewQty">0</span> units
                        </span>
                    </div>
                    <div class="mt-2">
                        <span id="stockStatusBadge" class="badge bg-secondary" style="font-size:11px">
                            Unknown
                        </span>
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size:10px">
                        Stock status is auto-calculated<br>
                        0 → Out of Stock &nbsp;|&nbsp; 1–9 → Low Stock &nbsp;|&nbsp; 10+ → In Stock
                    </small>
                </div>
            </div>

            {{-- Options --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-sliders-h me-2 text-info"></i>Options</h6>
                </div>
                <div class="card-body">
                    {{-- requires_prescription --}}
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-semibold" style="font-size:12px">Requires Prescription</div>
                            <div class="text-muted" style="font-size:11px">Patient needs a prescription to buy</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="requires_prescription" id="rxSwitch"
                                   value="1" style="width:36px;height:18px"
                                   {{ old('requires_prescription', true) ? 'checked' : '' }}>
                        </div>
                    </div>
                    {{-- is_active --}}
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <div>
                            <div class="fw-semibold" style="font-size:12px">Active / Visible</div>
                            <div class="text-muted" style="font-size:11px">Medicine visible to patients</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_active" id="activeSwitch"
                                   value="1" style="width:36px;height:18px"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="card">
                <div class="card-body d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"
                            style="font-size:12px" id="submitBtn">
                        <i class="fas fa-plus-circle me-1"></i>Add Medicine
                    </button>
                    <a href="{{ route('pharmacy.medicines.index') }}"
                       class="btn btn-outline-secondary btn-sm w-100" style="font-size:12px">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function updateStockPreview(qty) {
    qty = parseInt(qty) || 0;
    const qtyEl    = document.getElementById('stockPreviewQty');
    const statusEl = document.getElementById('stockStatusBadge');
    qtyEl.textContent = qty;
    // DB enum: out_of_stock | low_stock | in_stock
    if (qty <= 0) {
        statusEl.className   = 'badge bg-danger';
        statusEl.textContent = 'Out of Stock';
    } else if (qty < 10) {
        statusEl.className   = 'badge bg-warning text-dark';
        statusEl.textContent = 'Low Stock';
    } else {
        statusEl.className   = 'badge bg-success';
        statusEl.textContent = 'In Stock';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const qty = document.querySelector('input[name="stock_quantity"]').value;
    updateStockPreview(qty);
});

document.getElementById('createMedicineForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    btn.disabled  = true;
});
</script>
@endpush
