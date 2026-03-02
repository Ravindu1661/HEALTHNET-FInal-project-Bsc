@extends('admin.layouts.master')

@section('title', 'Prescription Orders')
@section('page-title', 'Prescription Orders')

@section('content')
@php
    use App\Models\PharmacyOrder;

    $search   = request('search', '');
    $statusF  = request('status', '');
    $paymentF = request('payment_status', '');
    $dateFrom = request('date_from', '');
    $dateTo   = request('date_to', '');

    $query = PharmacyOrder::with('patient.user', 'pharmacy', 'items')->latest();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('order_number', 'LIKE', "%{$search}%")
              ->orWhereHas('patient', fn($p) => $p
                  ->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%"))
              ->orWhereHas('pharmacy', fn($ph) => $ph
                  ->where('name', 'LIKE', "%{$search}%"));
        });
    }
    if ($statusF)  $query->where('status', $statusF);
    if ($paymentF) $query->where('payment_status', $paymentF);
    if ($dateFrom) $query->whereDate('created_at', '>=', $dateFrom);
    if ($dateTo)   $query->whereDate('created_at', '<=', $dateTo);

    $orders = $query->paginate(15)->appends(request()->query());

    $statusMap = [
        'pending'    => ['bg-warning text-dark', 'fa-clock',         'Pending'],
        'verified'   => ['bg-info text-white',   'fa-check',         'Verified'],
        'processing' => ['bg-primary text-white','fa-cogs',          'Processing'],
        'ready'      => ['bg-success text-white','fa-box-open',      'Ready'],
        'dispatched' => ['bg-dark text-white',   'fa-shipping-fast', 'Dispatched'],
        'delivered'  => ['bg-success text-white','fa-check-double',  'Delivered'],
        'cancelled'  => ['bg-danger text-white', 'fa-times-circle',  'Cancelled'],
    ];
    $payMap = [
        'paid'   => ['bg-success text-white', 'Paid'],
        'unpaid' => ['bg-danger text-white',  'Unpaid'],
    ];
@endphp

{{-- ── Session Messages ── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Stats Summary ── --}}
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-summary stat-summary-primary">
            <div class="stat-icon"><i class="fas fa-prescription"></i></div>
            <div class="stat-details">
                <h6>Total Orders</h6>
                <h4>{{ PharmacyOrder::count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-summary stat-summary-warning">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-details">
                <h6>Pending</h6>
                <h4>{{ PharmacyOrder::where('status', 'pending')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-summary stat-summary-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-details">
                <h6>Paid Orders</h6>
                <h4>{{ PharmacyOrder::where('payment_status', 'paid')->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-summary stat-summary-danger">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-details">
                <h6>Revenue</h6>
                <h4>LKR {{ number_format(PharmacyOrder::where('payment_status','paid')->sum('total_amount'), 2) }}</h4>
            </div>
        </div>
    </div>
</div>

{{-- ── Filters + Table Card ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6><i class="fas fa-prescription me-2"></i>All Prescription Orders</h6>
    </div>
    <div class="card-body">

        {{-- Filters --}}
        <form action="{{ route('admin.prescriptions.index') }}" method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search order #, patient, pharmacy…"
                           value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['pending','verified','processing','ready','dispatched','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($statusF === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All Payments</option>
                    <option value="paid"   @selected($paymentF === 'paid')>Paid</option>
                    <option value="unpaid" @selected($paymentF === 'unpaid')>Unpaid</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ $dateFrom }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ $dateTo }}" placeholder="To">
            </div>
            <div class="col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.prescriptions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="data-table table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Order #</th>
                        <th>Patient</th>
                        <th>Pharmacy</th>
                        <th>Delivery</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th width="130" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    @php
                        $patient  = $order->patient;
                        $pName    = $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'N/A';
                        $pImg     = ($patient && $patient->profile_image)
                                        ? asset('storage/' . $patient->profile_image) : null;
                        [$stClass, $stIcon, $stLabel] = $statusMap[$order->status]
                                        ?? ['bg-secondary text-white', 'fa-circle', 'Unknown'];
                        [$pyClass, $pyLabel] = $payMap[$order->payment_status]
                                        ?? ['bg-secondary text-white', 'Unknown'];
                    @endphp
                    <tr>
                        <td>{{ $orders->firstItem() + $i }}</td>

                        {{-- Order # --}}
                        <td>
                            <span class="badge bg-info">{{ $order->order_number }}</span>
                            @if($order->prescription_file)
                                <br><small class="text-success">
                                    <i class="fas fa-file-medical me-1"></i>Has Rx
                                </small>
                            @endif
                        </td>

                        {{-- Patient --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2">
                                    @if($pImg)
                                        <img src="{{ $pImg }}"
                                             alt="{{ $pName }}"
                                             class="rounded-circle"
                                             width="35" height="35"
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}"
                                             alt="{{ $pName }}"
                                             class="rounded-circle"
                                             width="35" height="35">
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ $pName }}</strong><br>
                                    @if($patient && $patient->user)
                                        <small class="text-muted">{{ $patient->user->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Pharmacy --}}
                        <td>
                            @if($order->pharmacy)
                                <strong>{{ $order->pharmacy->name }}</strong>
                                <br><small class="text-muted">{{ $order->pharmacy->city ?? '' }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Delivery --}}
                        <td>
                            @if($order->delivery_address === 'PICKUP')
                                <span class="badge bg-secondary">
                                    <i class="fas fa-store me-1"></i>Pickup
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="fas fa-truck me-1"></i>Delivery
                                </span>
                            @endif
                        </td>

                        {{-- Payment --}}
                        <td>
                            <span class="badge {{ $pyClass }}">{{ $pyLabel }}</span>
                            @if($order->payment_method)
                                <br><small class="text-muted">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                </small>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="badge {{ $stClass }}">
                                <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                            </span>
                        </td>

                        {{-- Total --}}
                        <td>
                            <strong>
                                LKR {{ number_format(($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2) }}
                            </strong>
                        </td>

                        {{-- Date --}}
                        <td>
                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}<br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                            </small>
                        </td>

                        {{-- Actions --}}
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.prescriptions.show', $order->id) }}"
                                   class="btn btn-info"
                                   title="View Details"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->prescription_file)
                                    <a href="{{ asset('storage/' . $order->prescription_file) }}"
                                       target="_blank"
                                       class="btn btn-danger"
                                       title="View Prescription"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-prescription fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No prescription orders found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }}
                of {{ $orders->total() }} entries
            </div>
            <div>{{ $orders->appends(request()->query())->links() }}</div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .stat-summary {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #fff;
        border-radius: 8px;
        border-left: 4px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,.05);
    }
    .stat-summary-primary { border-color: #4285F4; }
    .stat-summary-success  { border-color: #34A853; }
    .stat-summary-warning  { border-color: #FBBC05; }
    .stat-summary-danger   { border-color: #EA4335; }

    .stat-summary .stat-icon {
        font-size: 2rem;
        width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px;
        margin-right: 1rem;
    }
    .stat-summary-primary .stat-icon { background: rgba(66,133,244,.1); color: #4285F4; }
    .stat-summary-success  .stat-icon { background: rgba(52,168,83,.1);  color: #34A853; }
    .stat-summary-warning  .stat-icon { background: rgba(251,188,5,.1);  color: #FBBC05; }
    .stat-summary-danger   .stat-icon { background: rgba(234,67,53,.1);  color: #EA4335; }

    .stat-summary .stat-details h6 { margin: 0; font-size: .875rem; color: #666; font-weight: 500; }
    .stat-summary .stat-details h4 { margin: 0; font-size: 1.5rem; font-weight: 700; color: #333; }

    .table-responsive { overflow-x: auto; }
    .data-table { width: 100%; margin-bottom: 1rem; background-color: transparent; }
    .data-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: .75rem;
        padding: .75rem;
        vertical-align: middle;
    }
    .data-table tbody td { padding: .75rem; vertical-align: middle; border-top: 1px solid #dee2e6; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }

    /* SweetAlert2 compact */
    .swal2-popup        { font-size: .875rem !important; }
    .swal2-title        { font-size: 1.1rem  !important; }
    .swal2-html-container { font-size: .8rem !important; }
    @media (max-width: 480px) {
        .swal2-popup          { font-size: .8rem  !important; }
        .swal2-title          { font-size: 1rem   !important; }
        .swal2-html-container { font-size: .75rem !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    });
</script>
@endpush
