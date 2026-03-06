@extends('pharmacy.layouts.master')
@section('title', 'Add Medicine')
@section('page-title', 'Medicines')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('pharmacy.medicines.index') }}"
       class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    <h5 class="fw-bold mb-0">Add New Medicine</h5>
</div>

@if($errors->any())
<div class="alert alert-danger border-0 shadow-sm mb-3">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

@php
$predefinedCategories = [
    'Antibiotics',
    'Analgesics / Pain Relief',
    'Antipyretics',
    'Antihistamines',
    'Antacids / Gastrointestinal',
    'Antidiabetics',
    'Antihypertensives',
    'Cardiovascular',
    'Vitamins & Supplements',
    'Dermatology / Skin Care',
    'Eye & Ear Drops',
    'Respiratory / Asthma',
    'Hormones & Endocrine',
    'Neurological / CNS',
    'Antifungals',
    'Antivirals',
    'Antiseptics',
    'Contraceptives',
    'Oncology',
    'Vaccines & Immunology',
    'Surgical Supplies',
    'Pediatric',
    'Dental',
    'Other',
];
// Merge with pharmacy-specific categories
$allCategories = collect($predefinedCategories)
    ->merge($categories)
    ->unique()
    ->sort()
    ->values();
@endphp

<form action="{{ route('pharmacy.medicines.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        {{-- ===== LEFT ===== --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-capsule me-2 text-primary"></i>Basic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Medicine Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Amoxicillin" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Generic Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Generic Name</label>
                            <input type="text" name="generic_name"
                                   class="form-control @error('generic_name') is-invalid @enderror"
                                   value="{{ old('generic_name') }}"
                                   placeholder="e.g. Amoxicillin Trihydrate">
                            @error('generic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    onchange="handleCategoryChange(this)" required>
                                <option value="">-- Select Category --</option>
                                @foreach($allCategories as $cat)
                                    <option value="{{ $cat }}"
                                        {{ old('category') === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                                <option value="__custom__"
                                    {{ old('category') && !$allCategories->contains(old('category')) ? 'selected' : '' }}>
                                    + Enter Custom Category
                                </option>
                            </select>
                            <input type="text" id="customCategoryInput"
                                   class="form-control mt-2 @error('category') is-invalid @enderror"
                                   placeholder="Type custom category..."
                                   style="display:none"
                                   value="{{ old('category') && !$allCategories->contains(old('category')) ? old('category') : '' }}">
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Manufacturer --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Manufacturer</label>
                            <input type="text" name="manufacturer"
                                   class="form-control"
                                   value="{{ old('manufacturer') }}"
                                   placeholder="e.g. ABC Pharma Ltd">
                        </div>

                        {{-- Dosage --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Dosage / Strength</label>
                            <input type="text" name="dosage"
                                   class="form-control"
                                   value="{{ old('dosage') }}"
                                   placeholder="e.g. 500mg, 10ml, 5mg/5ml">
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold form-label-sm">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Optional description or usage notes...">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Pricing & Stock --}}
            <div class="dashboard-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-boxes me-2 text-warning"></i>Pricing & Stock
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- Price --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Unit Price (LKR) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-currency-exchange text-secondary"></i>
                                </span>
                                <input type="number" name="price" step="0.01" min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', '0.00') }}"
                                       placeholder="0.00" required>
                                <span class="input-group-text bg-white text-muted">LKR</span>
                            </div>
                            @error('price')<div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>@enderror
                        </div>

                        {{-- Stock --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Initial Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-boxes text-secondary"></i>
                                </span>
                                <input type="number" name="stock_quantity" min="0"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', 0) }}"
                                       oninput="updateStockPreview(this.value)"
                                       required>
                                <span class="input-group-text bg-white text-muted">units</span>
                            </div>
                            @error('stock_quantity')<div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>@enderror

                            {{-- Stock Status Preview --}}
                            <div class="mt-2 p-2 rounded d-flex align-items-center gap-2"
                                 id="stockPreview"
                                 style="background:#f8fafc;border:1px solid #e5e7eb;font-size:.78rem">
                                <span id="stockPreviewBadge" class="badge bg-secondary">Out of Stock</span>
                                <span id="stockPreviewText" class="text-muted">
                                    0 = Out of Stock &nbsp;|&nbsp; 1–10 = Low Stock &nbsp;|&nbsp; 11+ = In Stock
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT ===== --}}
        <div class="col-lg-4">

            {{-- Settings --}}
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-toggle-on me-2 text-success"></i>Settings
                    </h6>
                </div>
                <div class="card-body d-flex flex-column gap-3">

                    <div class="d-flex justify-content-between align-items-center p-3 rounded"
                         style="background:#f8fafc;border:1px solid #e5e7eb">
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem">Active</div>
                            <small class="text-muted">Visible to patients</small>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   style="width:2.5em;height:1.3em">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded"
                         style="background:#f8fafc;border:1px solid #e5e7eb">
                        <div>
                            <div class="fw-semibold" style="font-size:.88rem">Requires Prescription</div>
                            <small class="text-muted">Patient must upload Rx</small>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="requires_prescription" value="1"
                                   {{ old('requires_prescription', true) ? 'checked' : '' }}
                                   style="width:2.5em;height:1.3em">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Category Quick Reference --}}
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-tag me-2 text-info"></i>Category Reference
                    </h6>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($predefinedCategories as $cat)
                            @if($cat !== 'Other')
                            <span class="badge bg-light text-dark border"
                                  style="font-size:.72rem;cursor:pointer;padding:.3rem .6rem"
                                  onclick="selectCategory('{{ $cat }}')">
                                {{ $cat }}
                            </span>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-2" style="font-size:.73rem;color:#9ca3af">
                        <i class="bi bi-hand-index me-1"></i>Click a badge to quick-select
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="dashboard-card">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill fw-semibold py-2">
                        <i class="bi bi-plus-circle me-1"></i>Add Medicine
                    </button>
                    <a href="{{ route('pharmacy.medicines.index') }}"
                       class="btn btn-light rounded-pill text-center">Cancel</a>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Category select
function handleCategoryChange(sel) {
    var customInput = document.getElementById('customCategoryInput');
    if (sel.value === '__custom__') {
        customInput.style.display = 'block';
        customInput.setAttribute('name', 'category');
        sel.removeAttribute('name');
        customInput.focus();
    } else {
        customInput.style.display = 'none';
        customInput.removeAttribute('name');
        sel.setAttribute('name', 'category');
    }
}

function selectCategory(cat) {
    var sel = document.querySelector('select[name="category"]');
    if (!sel) {
        sel = document.querySelector('select');
    }
    if (sel) {
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === cat) {
                sel.selectedIndex = i;
                sel.setAttribute('name', 'category');
                document.getElementById('customCategoryInput').style.display = 'none';
                document.getElementById('customCategoryInput').removeAttribute('name');
                break;
            }
        }
    }
}

// Stock preview
function updateStockPreview(val) {
    var qty    = parseInt(val) || 0;
    var badge  = document.getElementById('stockPreviewBadge');
    var text   = document.getElementById('stockPreviewText');

    if (qty <= 0) {
        badge.className = 'badge bg-danger';
        badge.textContent = 'Out of Stock';
        text.textContent = 'This medicine will not be available for orders.';
    } else if (qty <= 10) {
        badge.className = 'badge bg-warning text-dark';
        badge.textContent = 'Low Stock (' + qty + ')';
        text.textContent = 'Low stock warning will be shown.';
    } else {
        badge.className = 'badge bg-success';
        badge.textContent = 'In Stock (' + qty + ')';
        text.textContent = 'Medicine is available for patient orders.';
    }
}

// Init on page load
updateStockPreview({{ old('stock_quantity', 0) }});

// Handle pre-selected custom category on validation fail
(function() {
    var sel = document.querySelector('select[name="category"]');
    if (sel && sel.value === '__custom__') {
        handleCategoryChange(sel);
    }
})();
</script>
@endpush
