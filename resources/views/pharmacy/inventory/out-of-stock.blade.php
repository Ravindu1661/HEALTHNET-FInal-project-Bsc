{{-- resources/views/pharmacy/inventory/out-of-stock.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Out of Stock')
@section('page-title', 'Inventory')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">
            <i class="fas fa-times-circle text-danger me-2"></i>Out of Stock Medicines
        </h5>
        <small class="text-muted">
            Stock quantity = 0 | Total:
            <strong class="text-danger">{{ $outOfStockCount }}</strong> medicines
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.inventory.index') }}"
           class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
        <a href="{{ route('pharmacy.inventory.low-stock') }}"
           class="btn btn-outline-warning btn-sm rounded-pill">
            <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
        </a>
        <a href="{{ route('pharmacy.medicines.create') }}"
           class="btn btn-primary btn-sm rounded-pill">
            <i class="fas fa-plus-circle me-1"></i> Add Medicine
        </a>
    </div>
</div>

{{-- Alert Banner --}}
@if($outOfStockCount > 0)
<div class="alert border-0 mb-4 d-flex align-items-center gap-3"
     style="background:#fef2f2;border-left:4px solid #dc2626 !important;border-radius:10px">
    <i class="fas fa-times-circle fa-2x text-danger"></i>
    <div>
        <strong>{{ $outOfStockCount }} medicine{{ $outOfStockCount != 1 ? 's' : '' }}</strong>
        are completely out of stock. Immediate restocking required.
    </div>
</div>
@endif

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.inventory.out-of-stock') }}" method="GET"
              class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label form-label-sm mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Medicine name or category…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-danger btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.inventory.out-of-stock') }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Table ── --}}
<div class="dashboard-card">
    <div class="card-header" style="background:#fef2f2;border-bottom:1px solid #fecaca">
        <h6 class="text-danger fw-bold">
            <i class="fas fa-times-circle me-2"></i>Out of Stock List
        </h6>
        <span class="badge bg-danger">{{ $medicines->total() }} medicines</span>
    </div>
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#fef2f2;font-size:.75rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#991b1b">
                    <tr>
                        <th class="ps-3 py-3">Medicine</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th class="text-center">Price</th>
                        <th>Manufacturer</th>
                        <th class="text-center">Rx Required</th>
                        <th class="text-center">Active</th>
                        <th class="text-center pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($medicines as $med)
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:32px;height:32px;background:#fef2f2;
                                        border:2px solid #dc2626;flex-shrink:0">
                                <i class="fas fa-times text-danger" style="font-size:.65rem"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.87rem">
                                    {{ $med->name }}
                                </div>
                                @if($med->generic_name)
                                <small class="text-muted">{{ $med->generic_name }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-size:.72rem">
                            {{ $med->category ?? '–' }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $med->dosage ?? '–' }}</small></td>
                    <td class="text-center fw-semibold" style="font-size:.85rem">
                        Rs. {{ number_format($med->price, 2) }}
                    </td>
                    <td>
                        <small class="text-muted">{{ $med->manufacturer ?? '–' }}</small>
                    </td>
                    <td class="text-center">
                        @if($med->requires_prescription)
                        <span class="badge bg-info bg-opacity-15 text-info" style="font-size:.7rem">Rx</span>
                        @else
                        <span class="badge bg-light text-muted" style="font-size:.7rem">OTC</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($med->is_active)
                        <span class="badge bg-success bg-opacity-15 text-success" style="font-size:.7rem">
                            Active
                        </span>
                        @else
                        <span class="badge bg-secondary bg-opacity-15 text-secondary" style="font-size:.7rem">
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('pharmacy.inventory.stock-history', $med->id) }}"
                               class="btn btn-sm btn-outline-info rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="History">
                                <i class="fas fa-history" style="font-size:.7rem"></i>
                            </a>
                            <a href="{{ route('pharmacy.medicines.edit', $med->id) }}"
                               class="btn btn-sm btn-danger rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Restock Now">
                                <i class="fas fa-plus" style="font-size:.7rem"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }}
                of {{ $medicines->total() }}
            </small>
            {{ $medicines->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-check-circle fa-3x mb-3 d-block text-success opacity-50"></i>
            <h6 class="fw-semibold text-success">No Out-of-Stock Medicines!</h6>
            <p class="small">All medicines are currently available.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
