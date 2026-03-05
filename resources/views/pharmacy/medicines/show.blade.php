@extends('pharmacy.layouts.master')

@section('title', ($medicine->name ?? 'Medicine') . ' — Details')
@section('page-title', 'Medicine Details')
@section('page-subtitle', $medicine->name ?? '')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-pills me-2 text-primary"></i>Medicine Details
        </h6>
        <small class="text-muted">{{ $medicine->name ?? '' }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
           class="btn btn-primary btn-sm" style="font-size:12px">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <button type="button" class="btn btn-outline-warning btn-sm"
                style="font-size:12px" onclick="openStockModal()">
            <i class="fas fa-boxes me-1"></i>Update Stock
        </button>
        <a href="{{ route('pharmacy.medicines.index') }}"
           class="btn btn-outline-secondary btn-sm" style="font-size:12px">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- Left Column --}}
    <div class="col-lg-4">

        {{-- Status Card --}}
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                @php
                    $stockBgMap = [
                        'in_stock'    => 'success',
                        'low_stock'   => 'warning',
                        'out_of_stock'=> 'danger',
                    ];
                    $stockBg = $stockBgMap[$medicine->stock_status] ?? 'secondary';
                @endphp
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle
                                bg-primary bg-opacity-10"
                         style="width:70px;height:70px">
                        <i class="fas fa-pills text-primary" style="font-size:28px"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1" style="font-size:14px">{{ $medicine->name }}</h6>
                @if($medicine->generic_name)
                <p class="text-muted mb-1" style="font-size:11px">{{ $medicine->generic_name }}</p>
                @endif
                <div class="d-flex justify-content-center gap-2 mt-2 flex-wrap">
                    <span class="badge bg-{{ $medicine->is_active ? 'success' : 'secondary' }}"
                          style="font-size:10px">
                        <i class="fas fa-circle me-1" style="font-size:8px"></i>
                        {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($medicine->requires_prescription)
                        <span class="badge bg-info" style="font-size:10px">
                            <i class="fas fa-prescription me-1"></i>Prescription Required
                        </span>
                    @else
                        <span class="badge bg-light text-dark border" style="font-size:10px">OTC</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stock Card --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-warehouse me-2 text-warning"></i>Stock Info</h6>
            </div>
            <div class="card-body text-center py-3">
                @php
                    $stockLabels = [
                        'in_stock'    => 'In Stock',
                        'low_stock'   => 'Low Stock',
                        'out_of_stock'=> 'Out of Stock',
                    ];
                    $sl = $stockLabels[$medicine->stock_status] ?? 'Unknown';
                @endphp
                <div class="mb-2">
                    <span class="fw-bold" style="font-size:36px;color:#1a3c5e;line-height:1">
                        {{ $medicine->stock_quantity }}
                    </span>
                    <span class="text-muted d-block" style="font-size:12px">units available</span>
                </div>
                <span class="badge bg-{{ $stockBg }} px-3 py-1" style="font-size:12px">
                    {{ $sl }}
                </span>

                @if($medicine->stock_quantity > 0 && $medicine->stock_quantity <= 9)
                <div class="alert alert-warning d-flex align-items-center gap-2 mt-3 py-2"
                     style="font-size:11px">
                    <i class="fas fa-exclamation-triangle flex-shrink-0"></i>
                    <span>Low stock! Update stock soon.</span>
                </div>
                @elseif($medicine->stock_quantity <= 0)
                <div class="alert alert-danger d-flex align-items-center gap-2 mt-3 py-2"
                     style="font-size:11px">
                    <i class="fas fa-times-circle flex-shrink-0"></i>
                    <span>Out of stock! Restock immediately.</span>
                </div>
                @endif

                <button type="button" onclick="openStockModal()"
                        class="btn btn-outline-warning btn-sm w-100 mt-2" style="font-size:12px">
                    <i class="fas fa-plus-circle me-1"></i>Update Stock
                </button>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-bolt me-2 text-warning"></i>Actions</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
                   class="btn btn-primary btn-sm w-100" style="font-size:12px">
                    <i class="fas fa-edit me-1"></i>Edit Medicine
                </a>
                <form action="{{ route('pharmacy.medicines.toggle-status', $medicine->id) }}"
                      method="POST">
                    @csrf
                    <button type="submit"
                            class="btn btn-{{ $medicine->is_active ? 'outline-secondary' : 'outline-success' }} btn-sm w-100"
                            style="font-size:12px"
                            onclick="return confirm('Change medicine status?')">
                        <i class="fas fa-{{ $medicine->is_active ? 'pause' : 'play' }} me-1"></i>
                        {{ $medicine->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form action="{{ route('pharmacy.medicines.destroy', $medicine->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm w-100" style="font-size:12px"
                            onclick="return confirm('Delete {{ addslashes($medicine->name) }} permanently?')">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Right Column --}}
    <div class="col-lg-8">

        {{-- Details Card --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Medicine Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">MEDICINE NAME</label>
                        <p class="mb-0 fw-semibold" style="font-size:13px">{{ $medicine->name }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">GENERIC NAME</label>
                        <p class="mb-0" style="font-size:13px">{{ $medicine->generic_name ?? '—' }}</p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">CATEGORY</label>
                        <p class="mb-0">
                            <span class="badge bg-light text-dark border" style="font-size:11px">
                                {{ ucfirst($medicine->category ?? '—') }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">MANUFACTURER</label>
                        <p class="mb-0" style="font-size:13px">{{ $medicine->manufacturer ?? '—' }}</p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">DOSAGE</label>
                        <p class="mb-0" style="font-size:13px">{{ $medicine->dosage ?? '—' }}</p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PRICE</label>
                        <p class="mb-0 fw-bold text-primary" style="font-size:15px">
                            Rs.{{ number_format($medicine->price, 2) }}
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PRESCRIPTION</label>
                        <p class="mb-0">
                            @if($medicine->requires_prescription)
                                <span class="badge bg-info" style="font-size:11px">Required</span>
                            @else
                                <span class="badge bg-light text-dark border" style="font-size:11px">OTC — Not Required</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">VISIBILITY</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $medicine->is_active ? 'success' : 'secondary' }}"
                                  style="font-size:11px">
                                {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>

                    @if($medicine->description)
                    <div class="col-12">
                        <label class="text-muted" style="font-size:11px;font-weight:600">DESCRIPTION</label>
                        <p class="mb-0 text-dark" style="font-size:12px;line-height:1.6">
                            {{ $medicine->description }}
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Record Info --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-history me-2 text-secondary"></i>Record Info</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">ADDED ON</label>
                        <p class="mb-0" style="font-size:12px">
                            {{ isset($medicine->created_at) ? $medicine->created_at->format('d M Y, h:i A') : '—' }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">LAST UPDATED</label>
                        <p class="mb-0" style="font-size:12px">
                            {{ isset($medicine->updated_at) ? $medicine->updated_at->diffForHumans() : '—' }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">MEDICINE ID</label>
                        <p class="mb-0" style="font-size:12px">#{{ $medicine->id }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Update Stock Modal --}}
<div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2 px-3">
                <h6 class="modal-title" style="font-size:13px">
                    <i class="fas fa-boxes me-2 text-warning"></i>Update Stock
                </h6>
                <button type="button" class="btn-close" style="font-size:10px"
                        data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pharmacy.medicines.update-stock', $medicine->id) }}" method="POST">
                @csrf
                <div class="modal-body px-3 py-3">
                    <p class="text-muted mb-3" style="font-size:12px">
                        Medicine: <strong>{{ $medicine->name }}</strong>
                    </p>
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size:11px;font-weight:600">
                            Current Stock:
                            <span class="text-primary">{{ $medicine->stock_quantity }}</span> units
                        </label>
                    </div>
                    <div>
                        <label class="form-label mb-1" style="font-size:11px;font-weight:600">
                            New Stock Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_quantity"
                               class="form-control form-control-sm" min="0"
                               value="{{ $medicine->stock_quantity }}"
                               style="font-size:12px" required>
                        <small class="text-muted" style="font-size:10px">
                            Enter the new total stock quantity
                        </small>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                            style="font-size:12px" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm" style="font-size:12px">
                        <i class="fas fa-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openStockModal() {
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}
</script>
@endpush
