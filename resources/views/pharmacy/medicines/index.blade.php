@extends('pharmacy.layouts.master')

@section('title', 'Medicines')
@section('page-title', 'Medicines')
@section('page-subtitle', 'Manage your pharmacy medicines & stock')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-pills me-2 text-primary"></i>Medicine List
        </h6>
        <small class="text-muted">
            {{ isset($medicines) ? $medicines->total().' medicines found' : '' }}
        </small>
    </div>
    <a href="{{ route('pharmacy.medicines.create') }}"
       class="btn btn-primary btn-sm" style="font-size:12px">
        <i class="fas fa-plus me-1"></i>Add Medicine
    </a>
</div>

{{-- Filter Card --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('pharmacy.medicines.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search" style="font-size:11px;color:#8898aa"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control form-control-sm border-start-0"
                               placeholder="Name, generic name, category..."
                               style="font-size:12px">
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Category</label>
                    <select name="category" class="form-select form-select-sm" style="font-size:12px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}"
                                    {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- stock_status DB enum: in_stock | low_stock | out_of_stock --}}
                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Stock Status</label>
                    <select name="stock_status" class="form-select form-select-sm" style="font-size:12px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Stock</option>
                        <option value="in_stock"    {{ request('stock_status') == 'in_stock'    ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock"   {{ request('stock_status') == 'low_stock'   ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock"{{ request('stock_status') == 'out_of_stock'? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Status</label>
                    <select name="is_active" class="form-select form-select-sm" style="font-size:12px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1" style="font-size:12px">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    @if(request()->hasAny(['search','category','stock_status','is_active']))
                        <a href="{{ route('pharmacy.medicines.index') }}"
                           class="btn btn-outline-secondary btn-sm" style="font-size:12px" title="Clear">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Stats Row (from controller $stats) --}}
@if(isset($stats))
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10 py-2 px-3">
            <div style="font-size:11px;color:#084298;font-weight:600">Total</div>
            <div style="font-size:20px;font-weight:700;color:#084298;line-height:1.2">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-success bg-opacity-10 py-2 px-3">
            <div style="font-size:11px;color:#198754;font-weight:600">In Stock</div>
            <div style="font-size:20px;font-weight:700;color:#198754;line-height:1.2">{{ $stats['in_stock'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10 py-2 px-3">
            <div style="font-size:11px;color:#ffc107;font-weight:600">Low Stock</div>
            <div style="font-size:20px;font-weight:700;color:#e6a817;line-height:1.2">{{ $stats['low_stock'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-danger bg-opacity-10 py-2 px-3">
            <div style="font-size:11px;color:#dc3545;font-weight:600">Out of Stock</div>
            <div style="font-size:20px;font-weight:700;color:#dc3545;line-height:1.2">{{ $stats['out_of_stock'] }}</div>
        </div>
    </div>
</div>
@endif

{{-- Medicines Table --}}
<div class="card">
    <div class="card-body p-0">
        @if(isset($medicines) && $medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width:35px">#</th>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Prescription</th>
                        <th>Status</th>
                        <th class="text-center pe-3" style="width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $index => $medicine)
                    @php
                        $stockColors = [
                            'in_stock'    => ['bg' => 'success', 'label' => 'In Stock'],
                            'low_stock'   => ['bg' => 'warning', 'label' => 'Low Stock'],
                            'out_of_stock'=> ['bg' => 'danger',  'label' => 'Out of Stock'],
                        ];
                        $sc = $stockColors[$medicine->stock_status] ?? ['bg' => 'secondary', 'label' => 'Unknown'];
                    @endphp
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:11px">{{ $medicines->firstItem() + $index }}</td>

                        {{-- Medicine Name --}}
                        <td>
                            <div class="fw-semibold" style="font-size:12px;color:#1a3c5e">
                                {{ $medicine->name }}
                            </div>
                            @if($medicine->generic_name)
                            <div class="text-muted" style="font-size:11px">{{ $medicine->generic_name }}</div>
                            @endif
                            @if($medicine->manufacturer)
                            <div style="font-size:10px;color:#aab0b7">
                                <i class="fas fa-industry me-1"></i>{{ $medicine->manufacturer }}
                            </div>
                            @endif
                        </td>

                        {{-- Category --}}
                        <td>
                            <span class="badge bg-light text-dark border" style="font-size:10px;font-weight:500">
                                {{ ucfirst($medicine->category ?? '—') }}
                            </span>
                        </td>

                        {{-- Dosage --}}
                        <td style="font-size:12px;color:#4a5568">{{ $medicine->dosage ?? '—' }}</td>

                        {{-- Price --}}
                        <td>
                            <span class="fw-semibold" style="font-size:12px;color:#1a3c5e">
                                Rs.{{ number_format($medicine->price, 2) }}
                            </span>
                        </td>

                        {{-- Stock --}}
                        <td>
                            <div>
                                <span class="fw-bold" style="font-size:13px;color:#1a3c5e">
                                    {{ $medicine->stock_quantity }}
                                </span>
                                <span class="text-muted" style="font-size:10px"> units</span>
                            </div>
                            <span class="badge bg-{{ $sc['bg'] }}" style="font-size:9px">
                                {{ $sc['label'] }}
                            </span>
                        </td>

                        {{-- Prescription --}}
                        <td class="text-center">
                            @if($medicine->requires_prescription)
                                <span class="badge bg-info" style="font-size:10px">
                                    <i class="fas fa-prescription me-1"></i>Required
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:10px">OTC</span>
                            @endif
                        </td>

                        {{-- Active Status --}}
                        <td>
                            <form action="{{ route('pharmacy.medicines.toggle-status', $medicine->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="badge border-0 bg-{{ $medicine->is_active ? 'success' : 'secondary' }}"
                                        style="font-size:10px;cursor:pointer"
                                        title="{{ $medicine->is_active ? 'Click to deactivate' : 'Click to activate' }}"
                                        onclick="return confirm('Change medicine status?')">
                                    <i class="fas fa-circle me-1" style="font-size:8px"></i>
                                    {{ $medicine->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>

                        {{-- Actions --}}
                        <td class="text-center pe-3">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('pharmacy.medicines.show', $medicine->id) }}"
                                   class="btn btn-sm btn-outline-info py-0 px-2" style="font-size:11px" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pharmacy.medicines.edit', $medicine->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-warning py-0 px-2"
                                        style="font-size:11px" title="Update Stock"
                                        onclick="openStockModal({{ $medicine->id }}, '{{ addslashes($medicine->name) }}', {{ $medicine->stock_quantity }})">
                                    <i class="fas fa-boxes"></i>
                                </button>
                                <form action="{{ route('pharmacy.medicines.destroy', $medicine->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger py-0 px-2"
                                            style="font-size:11px" title="Delete"
                                            onclick="return confirm('Delete {{ addslashes($medicine->name) }}? This cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($medicines->hasPages())
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
            <small class="text-muted" style="font-size:11px">
                Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }} of {{ $medicines->total() }} medicines
            </small>
            {{ $medicines->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="text-center py-5 text-muted">
            <i class="fas fa-pills fa-3x mb-3 d-block text-muted opacity-50"></i>
            <h6 style="font-size:14px">No Medicines Found</h6>
            @if(request()->hasAny(['search','category','stock_status','is_active']))
                <p style="font-size:12px">No medicines match your search criteria.</p>
                <a href="{{ route('pharmacy.medicines.index') }}"
                   class="btn btn-outline-secondary btn-sm me-2" style="font-size:12px">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            @else
                <p style="font-size:12px">You haven't added any medicines yet.</p>
            @endif
            <a href="{{ route('pharmacy.medicines.create') }}"
               class="btn btn-primary btn-sm" style="font-size:12px">
                <i class="fas fa-plus me-1"></i>Add First Medicine
            </a>
        </div>
        @endif
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
            <form id="stockUpdateForm" method="POST">
                @csrf
                <div class="modal-body px-3 py-3">
                    <p class="text-muted mb-2" style="font-size:12px">
                        Medicine: <strong id="stockMedicineName"></strong>
                    </p>
                    <div class="mb-2">
                        <label class="form-label mb-1" style="font-size:11px;font-weight:600">
                            Current Stock: <span id="currentStockDisplay" class="text-primary"></span> units
                        </label>
                    </div>
                    <div class="mb-1">
                        <label class="form-label mb-1" style="font-size:11px;font-weight:600">
                            New Stock Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_quantity" id="stockQtyInput"
                               class="form-control form-control-sm" min="0"
                               placeholder="Enter new quantity"
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
                        <i class="fas fa-save me-1"></i>Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openStockModal(medicineId, medicineName, currentStock) {
    document.getElementById('stockMedicineName').textContent   = medicineName;
    document.getElementById('currentStockDisplay').textContent = currentStock;
    document.getElementById('stockQtyInput').value             = currentStock;
    // Route: pharmacy/medicines/{id}/update-stock
    document.getElementById('stockUpdateForm').action =
        `/pharmacy/medicines/${medicineId}/update-stock`;
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}
</script>
@endpush
