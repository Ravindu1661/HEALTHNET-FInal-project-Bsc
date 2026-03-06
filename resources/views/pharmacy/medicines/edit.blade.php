@extends('pharmacy.layouts.master')
@section('title', 'Edit Medicine')
@section('page-title', 'Medicines')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
       class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    <div>
        <h5 class="fw-bold mb-0">Edit Medicine</h5>
        <small class="text-muted">{{ $medicine->name }}</small>
    </div>
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
$allCategories = collect($predefinedCategories)
    ->merge($categories)
    ->unique()
    ->sort()
    ->values();

$currentCategory  = old('category', $medicine->category);
$isCustomCategory = $currentCategory && !$allCategories->contains($currentCategory);
$stockClr = match($medicine->stock_status) {
    'in_stock'     => 'success',
    'low_stock'    => 'warning',
    'out_of_stock' => 'danger',
    default        => 'secondary',
};
@endphp

<form action="{{ route('pharmacy.medicines.update', $medicine->id) }}" method="POST">
    @csrf @method('PUT')

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
                                   value="{{ old('name', $medicine->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Generic Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Generic Name</label>
                            <input type="text" name="generic_name"
                                   class="form-control"
                                   value="{{ old('generic_name', $medicine->generic_name) }}">
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="{{ $isCustomCategory ? '' : 'category' }}"
                                    id="categorySelect"
                                    class="form-select @error('category') is-invalid @enderror"
                                    onchange="handleCategoryChange(this)" required>
                                <option value="">-- Select Category --</option>
                                @foreach($allCategories as $cat)
                                    <option value="{{ $cat }}"
                                        {{ $currentCategory === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                                <option value="__custom__"
                                    {{ $isCustomCategory ? 'selected' : '' }}>
                                    + Enter Custom Category
                                </option>
                            </select>
                            <input type="text"
                                   id="customCategoryInput"
                                   name="{{ $isCustomCategory ? 'category' : '' }}"
                                   class="form-control mt-2 @error('category') is-invalid @enderror"
                                   placeholder="Type custom category..."
                                   value="{{ $isCustomCategory ? $currentCategory : '' }}"
                                   style="display:{{ $isCustomCategory ? 'block' : 'none' }}">
                            @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Manufacturer --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Manufacturer</label>
                            <input type="text" name="manufacturer"
                                   class="form-control"
                                   value="{{ old('manufacturer', $medicine->manufacturer) }}">
                        </div>

                        {{-- Dosage --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">Dosage / Strength</label>
                            <input type="text" name="dosage"
                                   class="form-control"
                                   value="{{ old('dosage', $medicine->dosage) }}"
                                   placeholder="e.g. 500mg, 10ml">
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold form-label-sm">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Optional description...">{{ old('description', $medicine->description) }}</textarea>
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
                                       value="{{ old('price', $medicine->price) }}" required>
                                <span class="input-group-text bg-white text-muted">LKR</span>
                            </div>
                            @error('price')<div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>@enderror
                        </div>

                        {{-- Stock --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold form-label-sm">
                                Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-boxes text-secondary"></i>
                                </span>
                                <input type="number" name="stock_quantity" min="0"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', $medicine->stock_quantity) }}"
                                       oninput="updateStockPreview(this.value)"
                                       required>
                                <span class="input-group-text bg-white text-muted">units</span>
                            </div>
                            @error('stock_quantity')<div class="text-danger mt-1" style="font-size:.8rem">{{ $message }}</div>@enderror

                            {{-- Stock Preview --}}
                            <div class="mt-2 p-2 rounded d-flex align-items-center gap-2"
                                 id="stockPreview"
                                 style="background:#f8fafc;border:1px solid #e5e7eb;font-size:.78rem">
                                <span id="stockPreviewBadge" class="badge bg-{{ $stockClr }}">
                                    {{ ucwords(str_replace('_', ' ', $medicine->stock_status)) }}
                                </span>
                                <span id="stockPreviewText" class="text-muted">
                                    Current: {{ $medicine->stock_quantity }} units
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT ===== --}}
        <div class="col-lg-4">

            {{-- Current Status --}}
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart me-2 text-info"></i>Current Status
                    </h6>
                </div>
                <div class="card-body text-center py-3">
                    <div class="fw-bold text-{{ $stockClr }}" style="font-size:2.8rem;line-height:1">
                        {{ $medicine->stock_quantity }}
                    </div>
                    <div class="text-muted small mb-2">units in stock</div>
                    <span class="badge bg-{{ $stockClr }} bg-opacity-15 text-{{ $stockClr }}"
                          style="font-size:.75rem;padding:.35rem .9rem;border-radius:20px">
                        {{ ucwords(str_replace('_', ' ', $medicine->stock_status)) }}
                    </span>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between" style="font-size:.83rem">
                        <span class="text-muted">Current Price</span>
                        <span class="fw-semibold text-primary">
                            LKR {{ number_format($medicine->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>

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
                                   {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}
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
                                   {{ old('requires_prescription', $medicine->requires_prescription) ? 'checked' : '' }}
                                   style="width:2.5em;height:1.3em">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Category Quick Reference --}}
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-tag me-2 text-info"></i>Quick Select Category
                    </h6>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($predefinedCategories as $cat)
                            @if($cat !== 'Other')
                            <span class="badge border fw-normal"
                                  style="font-size:.72rem;cursor:pointer;padding:.3rem .6rem;
                                         background:{{ $currentCategory===$cat ? '#2563eb' : '#f8fafc' }};
                                         color:{{ $currentCategory===$cat ? '#fff' : '#374151' }}"
                                  id="catbadge-{{ Str::slug($cat) }}"
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
                        <i class="bi bi-check-circle me-1"></i>Save Changes
                    </button>
                    <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
                       class="btn btn-light rounded-pill text-center">Cancel</a>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
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
    var sel = document.getElementById('categorySelect');
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

function updateStockPreview(val) {
    var qty   = parseInt(val) || 0;
    var badge = document.getElementById('stockPreviewBadge');
    var text  = document.getElementById('stockPreviewText');

    if (qty <= 0) {
        badge.className = 'badge bg-danger';
        badge.textContent = 'Out of Stock';
        text.textContent = 'Medicine will be unavailable for orders.';
    } else if (qty <= 10) {
        badge.className = 'badge bg-warning text-dark';
        badge.textContent = 'Low Stock (' + qty + ')';
        text.textContent = 'Low stock warning will be shown.';
    } else {
        badge.className = 'badge bg-success';
        badge.textContent = 'In Stock (' + qty + ')';
        text.textContent = 'Available for patient orders.';
    }
}

updateStockPreview({{ old('stock_quantity', $medicine->stock_quantity) }});
</script>
@endpush
