{{-- resources/views/pharmacy/reports/inventory.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title','Inventory Report')
@section('page-title','Reports')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.reports.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <span class="fw-bold fs-5">Inventory Report</span>
    </div>
    <a href="{{ route('pharmacy.reports.export', ['type'=>'inventory']) }}"
       class="btn btn-outline-success btn-sm rounded-pill px-3">
        <i class="fas fa-download me-1"></i>Export CSV
    </a>
</div>

{{-- Filter --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.reports.inventory') }}" method="GET"
              class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>
                        {{ $cat }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Stock Status</label>
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="in_stock"    {{ request('stock_status')=='in_stock'    ?'selected':'' }}>In Stock</option>
                    <option value="low_stock"   {{ request('stock_status')=='low_stock'   ?'selected':'' }}>Low Stock</option>
                    <option value="out_of_stock"{{ request('stock_status')=='out_of_stock'?'selected':'' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill rounded-pill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.reports.inventory') }}"
                   class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    @php
        $kpis = [
            ['label'=>'Total Medicines', 'value'=>$totalMedicines, 'color'=>'#2563eb'],
            ['label'=>'Stock Value',     'value'=>'Rs. '.number_format($totalStockValue,0), 'color'=>'#16a34a'],
            ['label'=>'In Stock',        'value'=>$inStockCount,   'color'=>'#10b981'],
            ['label'=>'Low Stock',       'value'=>$lowStockCount,  'color'=>'#d97706'],
            ['label'=>'Out of Stock',    'value'=>$outOfStockCount,'color'=>'#dc2626'],
            ['label'=>'Active',          'value'=>$activeCount,    'color'=>'#7c3aed'],
        ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-sm-6 col-xl-2">
        <div class="dashboard-card text-center py-3">
            <div class="fw-bold" style="font-size:1.4rem;color:{{ $k['color'] }}">{{ $k['value'] }}</div>
            <small class="text-muted">{{ $k['label'] }}</small>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    {{-- Category Breakdown --}}
    <div class="col-lg-6">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-tags me-2 text-info"></i>Category Breakdown</h6>
            </div>
            <div class="card-body p-0">
                @forelse($categoryBreakdown as $cat => $data)
                <div class="px-3 py-2 {{ !$loop->last?'border-bottom':'' }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold" style="font-size:.82rem">
                            {{ $cat ?: '(Uncategorized)' }}
                        </span>
                        <span class="badge bg-primary bg-opacity-15 text-primary"
                              style="font-size:.7rem">
                            {{ $data['count'] }} items
                        </span>
                    </div>
                    <div class="d-flex gap-3" style="font-size:.75rem">
                        <span class="text-muted">
                            Stock: <strong>{{ $data['total_stock'] }}</strong>
                        </span>
                        <span class="text-muted">
                            Value: <strong>Rs.{{ number_format($data['stock_value'],0) }}</strong>
                        </span>
                        @if($data['low_stock'] > 0)
                        <span class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $data['low_stock'] }} low
                        </span>
                        @endif
                        @if($data['out_stock'] > 0)
                        <span class="text-danger">
                            <i class="fas fa-times-circle me-1"></i>{{ $data['out_stock'] }} out
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted"><small>No data.</small></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Most Dispensed --}}
    <div class="col-lg-6">
        <div class="dashboard-card h-100">
            <div class="card-header">
                <h6><i class="fas fa-fire me-2 text-danger"></i>Most Dispensed (All Orders)</h6>
            </div>
            <div class="card-body p-0">
                @forelse($mostDispensed as $i => $med)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last?'border-bottom':'' }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:24px;height:24px;background:#fef2f2;
                                    color:#dc2626;font-size:.68rem;font-weight:700">
                            {{ $i+1 }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.82rem">
                                {{ $med->medication_name }}
                            </div>
                            <small class="text-muted">
                                Revenue: Rs.{{ number_format($med->total_revenue,0) }}
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-danger bg-opacity-15 text-danger rounded-pill"
                          style="font-size:.72rem;white-space:nowrap">
                        {{ number_format($med->total_dispensed) }} units
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted"><small>No dispensing data.</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Full Medicine Inventory Table --}}
<div class="dashboard-card">
    <div class="card-header">
        <h6><i class="fas fa-table me-2 text-primary"></i>Medicine Inventory</h6>
        <span class="badge bg-light text-dark border">{{ $totalMedicines }}</span>
    </div>
    <div class="card-body p-0">
        @if($medicines->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.73rem;text-transform:uppercase;
                              letter-spacing:.05em;color:#6b7280">
                    <tr>
                        <th class="ps-3 py-3">Name</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Stock Qty</th>
                        <th class="text-end">Stock Value</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Rx</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($medicines as $med)
                <tr>
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:.82rem">{{ $med->name }}</div>
                        @if($med->generic_name)
                        <small class="text-muted">{{ $med->generic_name }}</small>
                        @endif
                    </td>
                    <td style="font-size:.8rem">{{ $med->category ?? '–' }}</td>
                    <td style="font-size:.8rem">{{ $med->manufacturer ?? '–' }}</td>
                    <td class="text-end" style="font-size:.8rem">
                        Rs. {{ number_format($med->price, 2) }}
                    </td>
                    <td class="text-center">
                        <span class="fw-bold {{ $med->stock_quantity == 0 ? 'text-danger' : ($med->stock_quantity < 10 ? 'text-warning' : 'text-success') }}"
                              style="font-size:.85rem">
                            {{ $med->stock_quantity }}
                        </span>
                    </td>
                    <td class="text-end" style="font-size:.8rem">
                        Rs. {{ number_format($med->stock_quantity * $med->price, 0) }}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $med->stock_status_badge }} rounded-pill"
                              style="font-size:.68rem">
                            {{ ucwords(str_replace('_',' ',$med->stock_status)) }}
                        </span>
                    </td>
                    <td class="text-center pe-3">
                        @if($med->requires_prescription)
                        <span class="badge bg-info bg-opacity-15 text-info rounded-pill"
                              style="font-size:.68rem">Rx</span>
                        @else
                        <span class="text-muted" style="font-size:.75rem">–</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot style="background:#f8fafc">
                    <tr>
                        <td colspan="5" class="ps-3 py-2 fw-semibold text-end"
                            style="font-size:.82rem;color:#6b7280">
                            Total Stock Value:
                        </td>
                        <td class="text-end fw-bold" style="color:#16a34a">
                            Rs. {{ number_format($totalStockValue, 0) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-boxes fa-3x mb-3 d-block opacity-40"></i>
            <h6>No medicines found.</h6>
        </div>
        @endif
    </div>
</div>

@endsection
