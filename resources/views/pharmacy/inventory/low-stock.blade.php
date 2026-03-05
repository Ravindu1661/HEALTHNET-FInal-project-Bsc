{{-- resources/views/pharmacy/inventory/low-stock.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Low Stock')
@section('page-title', 'Inventory')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Low Stock Medicines
        </h5>
        <small class="text-muted">
            Stock quantity between 1–10 | Total:
            <strong class="text-warning">{{ $lowStockCount }}</strong> medicines
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.inventory.index') }}"
           class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
        <a href="{{ route('pharmacy.inventory.out-of-stock') }}"
           class="btn btn-outline-danger btn-sm rounded-pill">
            <i class="fas fa-times-circle me-1"></i> Out of Stock
        </a>
    </div>
</div>

{{-- Alert Banner --}}
@if($lowStockCount > 0)
<div class="alert border-0 mb-4 d-flex align-items-center gap-3"
     style="background:#fffbeb; border-left:4px solid #f59e0b !important; border-radius:10px">
    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
    <div>
        <strong>{{ $lowStockCount }} medicine{{ $lowStockCount != 1 ? 's' : '' }}</strong>
        running low on stock. Restock soon to avoid interruptions.
    </div>
</div>
@endif

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.inventory.low-stock') }}" method="GET"
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
                <button type="submit" class="btn btn-warning btn-sm flex-fill text-white">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.inventory.low-stock') }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Table ── --}}
<div class="dashboard-card">
    <div class="card-header" style="background:#fffbeb;border-bottom:1px solid #fde68a">
        <h6 class="text-warning fw-bold">
            <i class="fas fa-exclamation-triangle me-2"></i>Low Stock List
        </h6>
        <span class="badge bg-warning text-white">{{ $medicines->total() }} medicines</span>
    </div>
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#fffbeb;font-size:.75rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#92400e">
                    <tr>
                        <th class="ps-3 py-3">Medicine</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th class="text-center">
                            Stock Qty <small class="fw-normal">(Asc ↑)</small>
                        </th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Stock Value</th>
                        <th class="text-center pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($medicines as $med)
                @php
                    $urgency = $med->stock_quantity <= 3 ? 'danger' : 'warning';
                @endphp
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:34px;height:34px;background:#fffbeb;
                                        border:2px solid #f59e0b;font-size:.75rem;
                                        font-weight:700;color:#d97706;flex-shrink:0">
                                {{ $med->stock_quantity }}
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
                    <td class="text-center">
                        <span class="badge bg-{{ $urgency }} rounded-pill"
                              style="font-size:.78rem;min-width:40px">
                            {{ $med->stock_quantity }}
                        </span>
                        @if($med->stock_quantity <= 3)
                        <div style="font-size:.65rem;color:#dc2626;font-weight:600">CRITICAL</div>
                        @endif
                    </td>
                    <td class="text-end" style="font-size:.85rem">
                        Rs. {{ number_format($med->price, 2) }}
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.85rem">
                        Rs. {{ number_format($med->price * $med->stock_quantity, 2) }}
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
                               class="btn btn-sm btn-outline-warning rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Restock">
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
            <h6 class="fw-semibold text-success">No Low Stock Medicines!</h6>
            <p class="small">All medicines have adequate stock levels.</p>
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
