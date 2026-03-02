@extends('admin.layouts.master')

@section('title', 'Payments Management')

@section('page-title', 'Payments Management')

@section('content')
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

    {{-- Summary Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-summary stat-summary-primary">
                <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-details">
                    <h6>Total Revenue</h6>
                    <h4>Rs. {{ number_format($summary['total'] ?? 0, 0) }}</h4>
                    <small class="text-muted">All time completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-summary stat-summary-success">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-details">
                    <h6>This Month</h6>
                    <h4>Rs. {{ number_format($summary['thisMonth'] ?? 0, 0) }}</h4>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-summary stat-summary-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <h6>Pending</h6>
                    <h4>Rs. {{ number_format($summary['pending'] ?? 0, 0) }}</h4>
                    <small class="text-muted">Awaiting confirmation</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-summary stat-summary-danger">
                <div class="stat-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-details">
                    <h6>Refunded</h6>
                    <h4>Rs. {{ number_format($summary['refunded'] ?? 0, 0) }}</h4>
                    <small class="text-muted">Processed refunds</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters Form --}}
    <div class="dashboard-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-money-bill-wave me-2"></i>All Payments
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-3 mb-2">
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Method</label>
                    <select name="method" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">From</label>
                    <input type="date"
                           name="from"
                           value="{{ request('from') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label form-label-sm mb-1">To</label>
                    <input type="date"
                           name="to"
                           value="{{ request('to') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100 me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table table table-hover">
                    <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Payment</th>
                        <th>Patient</th>
                        <th>Related</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <strong>{{ $payment->payment_number ?? 'N/A' }}</strong><br>
                                <small class="text-muted">#{{ $payment->id }}</small>
                            </td>
                            <td>
                                <span>{{ $payment->payer_name ?? 'N/A' }}</span><br>
                                <small class="text-muted">{{ $payment->payer_email ?? '' }}</small>
                            </td>
                            <td>
                                @if($payment->related_type)
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($payment->related_type) }} #{{ $payment->related_id }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <strong>Rs. {{ number_format($payment->amount, 2) }}</strong>
                            </td>
                            <td class="text-capitalize">
                                {{ str_replace('_', ' ', $payment->payment_method) }}
                            </td>
                            <td>
                                {{ $payment->created_at?->format('d M Y') }}
                            </td>
                            <td>
                                @php $status = $payment->payment_status; @endphp
                                @if($status === 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @elseif($status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @elseif($status === 'failed')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Failed
                                    </span>
                                @elseif($status === 'refunded')
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-undo"></i> Refunded
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted" style="max-width: 200px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $payment->notes ?? '' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.payments.show', $payment->id) }}"
                                   class="btn btn-info btn-sm"
                                   title="View"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No payments found</h5>
                                <p class="text-muted">Try changing filters or date range.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }}
                        of {{ $payments->total() }} entries
                    </div>
                    <div>
                        {{ $payments->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-summary-primary { border-color: #4285F4; }
        .stat-summary-success { border-color: #34A853; }
        .stat-summary-warning { border-color: #FBBC05; }
        .stat-summary-danger  { border-color: #EA4335; }

        .stat-summary .stat-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 1rem;
        }
        .stat-summary-primary .stat-icon {
            background: rgba(66, 133, 244, .1);
            color: #4285F4;
        }
        .stat-summary-success .stat-icon {
            background: rgba(52, 168, 83, .1);
            color: #34A853;
        }
        .stat-summary-warning .stat-icon {
            background: rgba(251, 188, 5, .1);
            color: #FBBC05;
        }
        .stat-summary-danger .stat-icon {
            background: rgba(234, 67, 53, .1);
            color: #EA4335;
        }
        .stat-summary .stat-details h6 {
            margin: 0;
            font-size: 0.875rem;
            color: #666;
            font-weight: 500;
        }
        .stat-summary .stat-details h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }
        .data-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.75rem;
            vertical-align: middle;
        }
        .data-table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush
