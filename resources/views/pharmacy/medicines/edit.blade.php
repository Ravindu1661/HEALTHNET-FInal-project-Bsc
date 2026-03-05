@extends('pharmacy.layouts.master')

@section('title', 'Edit Medicine')
@section('page-title', 'Edit Medicine')
@section('page-subtitle', 'Update medicine details')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-edit me-2 text-primary"></i>Edit Medicine
        </h6>
        <small class="text-muted">{{ $medicine->name ?? '' }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
           class="btn btn-outline-info btn-sm" style="font-size:12px">
            <i class="fas fa-eye me-1"></i>View
        </a>
        <a href="{{ route('pharmacy.medicines.index') }}"
           class="btn btn-outline-secondary btn-sm" style="font-size:12px">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<form action="{{ route('pharmacy.medicines.update', $medicine->id) }}"
      method="POST" id="editMedicineForm">
    @csrf
    @method('PUT')
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
                            <input type="text" name="name"
                                   value="{{ old('name', $medicine->name) }}"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="Medicine name" style="font-size:12px">
                            @error('name')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- category --}}
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:12px;font-weight:600">
                                Category <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="category"
                                   value="{{ old('category', $medicine->category) }}"
                                   class="form-control form-control-sm @error('category') is-invalid @enderror"
                                   placeholder="Category" style="font-size:12px"
                                   list="categoryList">
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
                            <input type="text" name="generic_name"
                                   value="{{ old('generic_name', $medicine->generic_name) }}"
                                   class="form-control form-control-sm @error('generic_name') is-invalid @enderror"
                                   placeholder="Generic name" style="font-size:12px">
                            @error('generic_name')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- manufacturer --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">Manufacturer</label>
                            <input type="text" name="manufacturer"
                                   value="{{ old('manufacturer', $medicine->manufacturer) }}"
                                   class="form-control form-control-sm @error('manufacturer') is-invalid @enderror"
                                   placeholder="Manufacturer" style="font-size:12px">
                            @error('manufacturer')
                            <div class="invalid-feedback" style="font-size:11px">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- dosage --}}
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:12px;font-weight:600">Dosage</label>
                            <input type="text" name="dosage"
                                   value="{{ old('dosage', $medicine->dosage) }}"
                                   class="form-control form-control-sm @error('dosage') is-invalid @enderror"
                                   placeholder="e.g. 500mg, twice daily" style="font-size:12px">
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
                                <input type="number" name="price"
                                       value="{{ old('price', $medicine->price) }}"
                                       step="0.01" min="0"
                                       class="form-control form-control-sm @error('price') is-invalid @enderror"
                                       style="font-size:12px">
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
                                       value="{{ old('stock_quantity', $medicine->stock_quantity) }}"
                                       min="0"
                                       class="form-control form-control-sm @error('stock_quantity') is-invalid @enderror"
                                       style="font-size:12px"
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
                                      placeholder="Medicine description..." style="font-size:12px">{{ old('description', $medicine->description) }}</textarea>
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

            {{-- Current Stock --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-boxes me-2 text-warning"></i>Stock Status</h6>
                </div>
                <div class="card-body text-center py-3">
                    @php
                        $scMap = [
                            'in_stock'    => ['success', 'In Stock'],
                            'low_stock'   => ['warning', 'Low Stock'],
                            'out_of_stock'=> ['danger',  'Out of Stock'],
                        ];
                        [$scBg, $scLabel] = $scMap[$medicine->stock_status] ?? ['secondary', 'Unknown'];
                    @endphp
                    <div class="mb-2">
                        <span class="fw-bold" style="font-size:22px;color:#1a3c5e">
                            {{ $medicine->stock_quantity }}
                        </span>
                        <span class="text-muted" style="font-size:12px"> units current</span>
                    </div>
                    <span id="stockStatusBadge" class="badge bg-{{ $scBg }} px-3 py-1" style="font-size:11px">
                        {{ $scLabel }}
                    </span>
                    <div class="mt-2" id="stockPreviewNew" style="display:none">
                        <small class="text-muted" style="font-size:10px">After update</small><br>
                        <span id="newStockQty" class="fw-bold text-primary" style="font-size:14px"></span>
                        <span class="text-muted" style="font-size:10px"> units</span><br>
                        <span id="newStockBadge" class="badge mt-1" style="font-size:10px"></span>
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size:10px">
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
                            <div class="text-muted" style="font-size:11px">Patient needs a prescription</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="requires_prescription" id="rxSwitch"
                                   value="1" style="width:36px;height:18px"
                                   {{ old('requires_prescription', $medicine->requires_prescription) ? 'checked' : '' }}>
                        </div>
                    </div>
                    {{-- is_active --}}
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <div>
                            <div class="fw-semibold" style="font-size:12px">Active / Visible</div>
                            <div class="text-muted" style="font-size:11px">Visible to patients</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_active" id="activeSwitch"
                                   value="1" style="width:36px;height:18px"
                                   {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="card mb-3">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" style="font-size:11px">
                        <li class="list-group-item d-flex justify-content-between py-2 px-3">
                            <span class="text-muted">Created</span>
                            <span>{{ isset($medicine->created_at) ? $medicine->created_at->format('d M Y') : '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2 px-3">
                            <span class="text-muted">Last Updated</span>
                            <span>{{ isset($medicine->updated_at) ? $medicine->updated_at->diffForHumans() : '—' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-body d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"
                            style="font-size:12px" id="saveBtn">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                    <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
                       class="btn btn-outline-info btn-sm w-100" style="font-size:12px">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <form action="{{ route('pharmacy.medicines.destroy', $medicine->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-outline-danger btn-sm w-100" style="font-size:12px"
                                onclick="return confirm('Delete {{ addslashes($medicine->name) }} permanently?')">
                            <i class="fas fa-trash me-1"></i>Delete Medicine
                        </button>
                    </form>
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
    const newSection = document.getElementById('stockPreviewNew');
    const newQtyEl   = document.getElementById('newStockQty');
    const newBadge   = document.getElementById('newStockBadge');
    newSection.style.display = 'block';
    newQtyEl.textContent = qty;
    if (qty <= 0) {
        newBadge.className   = 'badge bg-danger mt-1';
        newBadge.textContent = 'Out of Stock';
    } else if (qty < 10) {
        newBadge.className   = 'badge bg-warning text-dark mt-1';
        newBadge.textContent = 'Low Stock';
    } else {
        newBadge.className   = 'badge bg-success mt-1';
        newBadge.textContent = 'In Stock';
    }
}

let formChanged = false;
document.getElementById('editMedicineForm').addEventListener('change', () => { formChanged = true; });
window.addEventListener('beforeunload', e => {
    if (formChanged) { e.preventDefault(); e.returnValue = ''; }
});
document.getElementById('editMedicineForm').addEventListener('submit', function() {
    formChanged = false;
    document.getElementById('saveBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    document.getElementById('saveBtn').disabled  = true;
});
</script>
@endpush
