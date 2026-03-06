@extends('pharmacy.layouts.master')

@section('title', 'Medicines')
@section('page-title', 'Medicines')
@section('page-subtitle', 'Manage your pharmacy medicines & stock')

@section('content')

{{-- Flash Messages --}}
@foreach(['success','error','info'] as $t)
    @if(session($t))
    <div class="alert alert-{{ $t === 'error' ? 'danger' : $t }} alert-dismissible fade show border-0 shadow-sm mb-3"
         style="font-size:12px">
        {{ session($t) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
@endforeach

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="bi bi-capsule me-2 text-primary"></i>Medicine List
        </h6>
        <small class="text-muted">
            {{ isset($medicines) ? $medicines->total() . ' medicines found' : '' }}
        </small>
    </div>
    <a href="{{ route('pharmacy.medicines.create') }}"
       class="btn btn-primary btn-sm" style="font-size:12px">
        <i class="bi bi-plus-circle me-1"></i>Add Medicine
    </a>
</div>

{{-- Stats Row --}}
@if(isset($stats))
<div class="row g-2 mb-3">
    @php
    $statCards = [
        ['Total',         $stats['total'],         '#084298', 'primary'],
        ['In Stock',      $stats['in_stock'],       '#198754', 'success'],
        ['Low Stock',     $stats['low_stock'],      '#e6a817', 'warning'],
        ['Out of Stock',  $stats['out_of_stock'],   '#dc3545', 'danger'],
        ['Inactive',      $stats['inactive'] ?? 0,  '#6c757d', 'secondary'],
    ];
    @endphp
    @foreach($statCards as [$lbl, $val, $clr, $bg])
    <div class="col-6 col-md">
        <div class="card border-0 bg-{{ $bg }} bg-opacity-10 py-2 px-3">
            <div style="font-size:11px;color:{{ $clr }};font-weight:600">{{ $lbl }}</div>
            <div style="font-size:20px;font-weight:700;color:{{ $clr }};line-height:1.2">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Filter Card --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('pharmacy.medicines.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search" style="font-size:11px;color:#8898aa"></i>
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
                                    {{ $cat }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1" style="font-size:11px;font-weight:600">Stock Status</label>
                    <select name="stock_status" class="form-select form-select-sm" style="font-size:12px"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Stock</option>
                        <option value="in_stock"     {{ request('stock_status') == 'in_stock'     ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock"    {{ request('stock_status') == 'low_stock'    ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
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
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                    @if(request()->hasAny(['search','category','stock_status','is_active']))
                    <a href="{{ route('pharmacy.medicines.index') }}"
                       class="btn btn-outline-secondary btn-sm" style="font-size:12px" title="Clear">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Medicines Table --}}
<div class="card">
    <div class="card-body p-0">
        @if(isset($medicines) && $medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="font-size:11px;text-transform:uppercase;letter-spacing:.04em;
                              background:#f8fafc;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3" style="width:35px">#</th>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Dosage</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="text-center">Rx</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3" style="width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $index => $medicine)
                    @php
                        $sc = match($medicine->stock_status) {
                            'in_stock'     => ['bg' => 'success', 'label' => 'In Stock'],
                            'low_stock'    => ['bg' => 'warning', 'label' => 'Low Stock'],
                            'out_of_stock' => ['bg' => 'danger',  'label' => 'Out of Stock'],
                            default        => ['bg' => 'secondary','label' => 'Unknown'],
                        };
                    @endphp
                    <tr>
                        {{-- # --}}
                        <td class="ps-3 text-muted" style="font-size:11px">
                            {{ $medicines->firstItem() + $index }}
                        </td>

                        {{-- Medicine --}}
                        <td>
                            <div class="fw-semibold" style="font-size:12px;color:#1a3c5e">
                                {{ $medicine->name }}
                            </div>
                            @if($medicine->generic_name)
                            <div class="text-muted" style="font-size:11px">
                                {{ $medicine->generic_name }}
                            </div>
                            @endif
                            @if($medicine->manufacturer)
                            <div style="font-size:10px;color:#aab0b7">
                                <i class="bi bi-building me-1"></i>{{ $medicine->manufacturer }}
                            </div>
                            @endif
                        </td>

                        {{-- Category --}}
                        <td>
                            <span class="badge bg-light text-dark border"
                                  style="font-size:10px;font-weight:500">
                                {{ $medicine->category ?? '—' }}
                            </span>
                        </td>

                        {{-- Dosage --}}
                        <td style="font-size:12px;color:#4a5568">
                            {{ $medicine->dosage ?? '—' }}
                        </td>

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

                        {{-- Rx --}}
                        <td class="text-center">
                            @if($medicine->requires_prescription)
                                <span class="badge bg-info" style="font-size:10px">
                                    <i class="bi bi-file-medical me-1"></i>Rx
                                </span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:10px">
                                    OTC
                                </span>
                            @endif
                        </td>

                        {{-- Active Status (toggle) --}}
                        <td class="text-center">
                            <form action="{{ route('pharmacy.medicines.toggle-status', $medicine->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="badge border-0 bg-{{ $medicine->is_active ? 'success' : 'secondary' }}"
                                        style="font-size:10px;cursor:pointer;padding:.35rem .6rem"
                                        title="{{ $medicine->is_active ? 'Click to deactivate' : 'Click to activate' }}"
                                        onclick="return confirm('Change status of {{ addslashes($medicine->name) }}?')">
                                    <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>
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
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top flex-wrap gap-2">
            <small class="text-muted" style="font-size:11px">
                Showing {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }}
                of {{ $medicines->total() }} medicines
            </small>
            {{ $medicines->appends(request()->query())->links('pagination.bootstrap-5') }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="text-center py-5 text-muted">
            <i class="bi bi-capsule d-block mb-3" style="font-size:3rem;opacity:.35"></i>
            <h6 style="font-size:14px">No Medicines Found</h6>
            @if(request()->hasAny(['search','category','stock_status','is_active']))
                <p style="font-size:12px">No medicines match your search criteria.</p>
                <a href="{{ route('pharmacy.medicines.index') }}"
                   class="btn btn-outline-secondary btn-sm me-2" style="font-size:12px">
                    <i class="bi bi-x-circle me-1"></i>Clear Filters
                </a>
            @else
                <p style="font-size:12px">You haven't added any medicines yet.</p>
            @endif
            <a href="{{ route('pharmacy.medicines.create') }}"
               class="btn btn-primary btn-sm" style="font-size:12px">
                <i class="bi bi-plus-circle me-1"></i>Add First Medicine
            </a>
        </div>
        @endif
    </div>
</div>

{{-- ===== UPDATE STOCK MODAL ===== --}}
<div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header py-2 px-3 border-0" style="background:#f8fafc">
                <div>
                    <h6 class="modal-title fw-bold mb-0" style="font-size:13px">
                        <i class="bi bi-boxes me-2 text-warning"></i>Update Stock
                    </h6>
                    <small class="text-muted" id="stockMedicineName"></small>
                </div>
                <button type="button" class="btn-close ms-auto"
                        style="font-size:10px" data-bs-dismiss="modal"></button>
            </div>

            <form id="stockUpdateForm" method="POST">
                @csrf
                <div class="modal-body px-3 py-3">

                    {{-- Current Stock Display --}}
                    <div class="p-2 rounded text-center mb-3"
                         style="background:#f0f9ff;border:1px solid #bae6fd">
                        <div class="text-muted" style="font-size:10px">Current Stock</div>
                        <div class="fw-bold text-primary" id="currentStockDisplay"
                             style="font-size:1.6rem;line-height:1.2">0</div>
                        <div class="text-muted" style="font-size:10px">units</div>
                    </div>

                    {{-- Action --}}
                    <div class="mb-3">
                        <label class="form-label mb-1 fw-semibold" style="font-size:11px">
                            Action <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-2">
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="action"
                                       id="act_add" value="add" checked>
                                <label class="btn btn-outline-success w-100 text-center"
                                       for="act_add" style="font-size:11px;padding:.4rem">
                                    <i class="bi bi-plus-circle d-block mb-1"></i>Add
                                </label>
                            </div>
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="action"
                                       id="act_subtract" value="subtract">
                                <label class="btn btn-outline-warning w-100 text-center"
                                       for="act_subtract" style="font-size:11px;padding:.4rem">
                                    <i class="bi bi-dash-circle d-block mb-1"></i>Subtract
                                </label>
                            </div>
                            <div class="flex-fill">
                                <input type="radio" class="btn-check" name="action"
                                       id="act_set" value="set">
                                <label class="btn btn-outline-primary w-100 text-center"
                                       for="act_set" style="font-size:11px;padding:.4rem">
                                    <i class="bi bi-pencil-square d-block mb-1"></i>Set
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="mb-1">
                        <label class="form-label mb-1 fw-semibold" style="font-size:11px">
                            Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="quantity" id="stockQtyInput"
                               class="form-control form-control-sm"
                               min="0" value="0"
                               style="font-size:12px" required>
                        <small class="text-muted" style="font-size:10px">
                            <i class="bi bi-info-circle me-1"></i>
                            0 = Out of Stock &nbsp;|&nbsp; 1–10 = Low Stock &nbsp;|&nbsp; 11+ = In Stock
                        </small>
                    </div>

                </div>
                <div class="modal-footer py-2 px-3 border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                            style="font-size:12px" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold"
                            style="font-size:12px">
                        <i class="bi bi-check-circle me-1"></i>Update Stock
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
    document.getElementById('stockQtyInput').value             = 0;
    document.getElementById('act_add').checked                 = true;
    document.getElementById('stockUpdateForm').action =
        '/pharmacy/medicines/' + medicineId + '/update-stock';
    new bootstrap.Modal(document.getElementById('stockModal')).show();
}
</script>
@endpush
