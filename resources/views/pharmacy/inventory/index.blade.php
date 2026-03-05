{{-- resources/views/pharmacy/inventory/index.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', 'Inventory')
@section('page-title', 'Inventory')

@push('styles')
<style>
.stat-card { border-radius:12px; padding:20px; border:none; position:relative; overflow:hidden; }
.stat-card::before {
    content:''; position:absolute; right:-20px; top:-20px;
    width:80px; height:80px; border-radius:50%;
    opacity:.12; background:currentColor;
}
.inventory-row:hover { background:#f8fafc; }
.stock-bar { height:6px; border-radius:3px; background:#e5e7eb; overflow:hidden; }
.stock-bar-fill { height:100%; border-radius:3px; transition:width .4s; }
.filter-chip {
    display:inline-flex; align-items:center; gap:4px;
    padding:3px 10px; border-radius:20px; font-size:.75rem;
    font-weight:600; border:1.5px solid transparent; cursor:pointer;
    transition:all .15s;
}
.filter-chip:hover, .filter-chip.active { border-color:currentColor; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Inventory Management</h5>
        <small class="text-muted">medicines table (medications) overview</small>
    </div>
    <div class="d-flex gap-2">
        {{-- ✅ route: pharmacy.medicines.create --}}
        <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus-circle me-1"></i> Add Medicine
        </a>
        <a href="{{ route('pharmacy.inventory.low-stock') }}" class="btn btn-outline-warning rounded-pill px-3">
            <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
        </a>
        <a href="{{ route('pharmacy.inventory.out-of-stock') }}" class="btn btn-outline-danger rounded-pill px-3">
            <i class="fas fa-times-circle me-1"></i> Out of Stock
        </a>
    </div>
</div>

{{-- ── Stats Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#eff6ff; color:#2563eb">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;
                                letter-spacing:.05em;opacity:.7">Total Medicines</div>
                    <div style="font-size:2rem;font-weight:700;line-height:1.1">{{ $totalMedicines }}</div>
                </div>
                <i class="fas fa-pills fa-2x opacity-30"></i>
            </div>
            <div class="mt-2" style="font-size:.78rem;opacity:.75">
                Value: <strong>Rs. {{ number_format($totalStockValue, 2) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#f0fdf4; color:#16a34a">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;opacity:.7">In Stock</div>
                    <div style="font-size:2rem;font-weight:700;line-height:1.1">{{ $inStock }}</div>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-30"></i>
            </div>
            <div class="mt-2">
                <div class="stock-bar">
                    <div class="stock-bar-fill" style="background:#16a34a;
                         width:{{ $totalMedicines > 0 ? round($inStock/$totalMedicines*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#fffbeb; color:#d97706">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;opacity:.7">Low Stock</div>
                    <div style="font-size:2rem;font-weight:700;line-height:1.1">{{ $lowStock }}</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x opacity-30"></i>
            </div>
            <div class="mt-2">
                <a href="{{ route('pharmacy.inventory.low-stock') }}"
                   style="font-size:.78rem;color:inherit;opacity:.8;text-decoration:none">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#fef2f2; color:#dc2626">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;opacity:.7">Out of Stock</div>
                    <div style="font-size:2rem;font-weight:700;line-height:1.1">{{ $outOfStock }}</div>
                </div>
                <i class="fas fa-times-circle fa-2x opacity-30"></i>
            </div>
            <div class="mt-2">
                <a href="{{ route('pharmacy.inventory.out-of-stock') }}"
                   style="font-size:.78rem;color:inherit;opacity:.8;text-decoration:none">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Category Overview ── --}}
@if($categoryStats->count() > 0)
<div class="dashboard-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-layer-group me-2 text-primary"></i>By Category</h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($categoryStats->take(8) as $cat)
            <div class="col-sm-6 col-md-3">
                <a href="{{ route('pharmacy.inventory.index', ['category' => $cat->category]) }}"
                   class="d-block p-3 rounded border text-decoration-none"
                   style="background:#f8fafc; transition:all .15s"
                   onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#2563eb'"
                   onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e5e7eb'">
                    <div class="fw-semibold" style="font-size:.85rem">{{ $cat->category }}</div>
                    <div class="text-muted" style="font-size:.75rem">
                        {{ $cat->total }} medicine{{ $cat->total != 1 ? 's' : '' }}
                    </div>
                    <div class="text-primary fw-semibold mt-1" style="font-size:.8rem">
                        Rs. {{ number_format($cat->total_value ?? 0, 0) }}
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.inventory.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, generic, manufacturer…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
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
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Stock Status</label>
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="in_stock"    {{ request('stock_status')=='in_stock'    ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock"   {{ request('stock_status')=='low_stock'   ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock"{{ request('stock_status')=='out_of_stock'? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Sort By</label>
                <select name="sort_by" class="form-select form-select-sm">
                    <option value="created_at"    {{ request('sort_by')=='created_at'    ? 'selected' : '' }}>Newest</option>
                    <option value="name"           {{ request('sort_by')=='name'           ? 'selected' : '' }}>Name</option>
                    <option value="stock_quantity" {{ request('sort_by')=='stock_quantity' ? 'selected' : '' }}>Stock Qty</option>
                    <option value="price"          {{ request('sort_by')=='price'          ? 'selected' : '' }}>Price</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Medicines Table ── --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-list me-2 text-primary"></i>All Medicines</h6>
        <span class="badge bg-light text-dark border">{{ $medicines->total() }} found</span>
    </div>
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">Medicine</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Rx</th>
                        <th class="text-center pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($medicines as $med)
                @php
                    $stockPct = $med->stock_quantity > 0 ? min(100, $med->stock_quantity / 100 * 100) : 0;
                    $stockColor = match($med->stock_status) {
                        'in_stock'    => '#16a34a',
                        'low_stock'   => '#d97706',
                        'out_of_stock'=> '#dc2626',
                        default       => '#6b7280',
                    };
                    $statusBadge = match($med->stock_status) {
                        'in_stock'    => ['success', 'In Stock'],
                        'low_stock'   => ['warning', 'Low Stock'],
                        'out_of_stock'=> ['danger',  'Out of Stock'],
                        default       => ['secondary', ucfirst($med->stock_status)],
                    };
                @endphp
                <tr class="inventory-row">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:.87rem">{{ $med->name }}</div>
                        @if($med->generic_name)
                        <small class="text-muted">{{ $med->generic_name }}</small>
                        @endif
                        @if($med->manufacturer)
                        <small class="d-block text-muted" style="font-size:.7rem">
                            <i class="fas fa-industry me-1"></i>{{ $med->manufacturer }}
                        </small>
                        @endif
                    </td>
                    <td>
                        @if($med->category)
                        <span class="badge bg-light text-dark border" style="font-size:.72rem">
                            {{ $med->category }}
                        </span>
                        @else
                        <span class="text-muted">–</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $med->dosage ?? '–' }}</small>
                    </td>
                    <td class="text-center" style="min-width:90px">
                        <div class="fw-bold" style="font-size:.9rem;color:{{ $stockColor }}">
                            {{ $med->stock_quantity }}
                        </div>
                        <div class="stock-bar mx-auto mt-1" style="width:60px">
                            <div class="stock-bar-fill"
                                 style="background:{{ $stockColor }};
                                        width:{{ min(100, $med->stock_quantity) }}%">
                            </div>
                        </div>
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.88rem">
                        Rs. {{ number_format($med->price, 2) }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $statusBadge[0] }} rounded-pill"
                              style="font-size:.7rem">
                            {{ $statusBadge[1] }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($med->requires_prescription)
                        <span class="badge bg-info bg-opacity-15 text-info"
                              style="font-size:.7rem">Rx</span>
                        @else
                        <span class="badge bg-light text-muted"
                              style="font-size:.7rem">OTC</span>
                        @endif
                    </td>
                    <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">
                            {{-- Stock History --}}
                            <a href="{{ route('pharmacy.inventory.stock-history', $med->id) }}"
                               class="btn btn-sm btn-outline-info rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Stock History">
                                <i class="fas fa-history" style="font-size:.7rem"></i>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('pharmacy.medicines.edit', $med->id) }}"
                               class="btn btn-sm btn-outline-warning rounded-circle"
                               style="width:30px;height:30px;padding:0;line-height:28px"
                               data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit" style="font-size:.7rem"></i>
                            </a>
                            {{-- Quick Stock Update --}}
                            <button class="btn btn-sm btn-outline-primary rounded-circle"
                                    style="width:30px;height:30px;padding:0;line-height:28px"
                                    data-bs-toggle="tooltip" title="Update Stock"
                                    onclick="openStockModal({{ $med->id }}, '{{ addslashes($med->name) }}', {{ $med->stock_quantity }})">
                                <i class="fas fa-boxes" style="font-size:.7rem"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <small class="text-muted">
                Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }}
                of {{ $medicines->total() }} medicines
            </small>
            {{ $medicines->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-box-open fa-3x mb-3 d-block opacity-50"></i>
            <h6 class="fw-semibold">No medicines found</h6>
            <p class="small mb-3">Try adjusting filters or add a new medicine.</p>
            <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus-circle me-1"></i> Add Medicine
            </a>
        </div>
        @endif
    </div>
</div>

{{-- ── Quick Stock Update Modal ── --}}
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background:#f8fafc">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-boxes me-2 text-primary"></i>Update Stock
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Medicine: <strong id="stockMedicineName"></strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            New Stock Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_quantity" id="stockQtyInput"
                               class="form-control text-center fw-bold"
                               min="0" required style="font-size:1.2rem">
                        <div class="form-text">Current: <strong id="currentStockDisplay"></strong></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));

function openStockModal(id, name, currentQty) {
    document.getElementById('stockMedicineName').textContent = name;
    document.getElementById('stockQtyInput').value           = currentQty;
    document.getElementById('currentStockDisplay').textContent = currentQty;
    // ✅ points to pharmacy.medicines.update-stock route
    document.getElementById('stockForm').action = `/pharmacy/medicines/${id}/update-stock`;
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}
</script>
@endpush
