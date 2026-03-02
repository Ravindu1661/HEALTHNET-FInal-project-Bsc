{{-- resources/views/admin/payments/show.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Payment Details')

@section('page-title', 'Payment Details')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-2">
                                <i class="fas fa-credit-card text-primary me-2"></i>
                                Payment {{ $payment->payment_number ?? ('#'.$payment->id) }}
                            </h3>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                {{-- Status badge --}}
                                @php $status = $payment->payment_status; @endphp
                                @if($status === 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Completed
                                    </span>
                                @elseif($status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @elseif($status === 'failed')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Failed
                                    </span>
                                @elseif($status === 'refunded')
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-undo me-1"></i> Refunded
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif

                                {{-- Method --}}
                                @if($payment->payment_method)
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-wallet me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-end">
                            <h4 class="mb-1">
                                Total Amount:
                                <span class="text-success">
                                    Rs. {{ number_format($payment->amount, 2) }}
                                </span>
                            </h4>
                            <small class="text-muted">
                                {{ $payment->created_at?->format('d M Y, h:i A') }}
                            </small>
                            <div class="mt-3">
                                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Two-column details --}}
            <div class="row g-3">
                {{-- Left: Payer / Related --}}
                <div class="col-md-6">
                    {{-- Payer Information --}}
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>Payer Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="40%">Name</th>
                                    <td>{{ $payment->payer_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $payment->payer_email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Contact</th>
                                    <td>{{ $payment->payer_phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment ID</th>
                                    <td>#{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Number</th>
                                    <td>
                                        @if($payment->payment_number)
                                            <span class="badge bg-primary">
                                                {{ $payment->payment_number }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Related Entity --}}
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-link me-2"></i>Related Record
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($payment->related_type)
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <th width="40%">Type</th>
                                        <td>{{ ucfirst($payment->related_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference ID</th>
                                        <td>#{{ $payment->related_id }}</td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted mb-0">No related record linked.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Payment Meta --}}
                <div class="col-md-6">
                    {{-- Payment Details --}}
                    <div class="dashboard-card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>Payment Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th width="45%">Amount</th>
                                    <td>
                                        <strong>Rs. {{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Completed
                                            </span>
                                        @elseif($status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i> Pending
                                            </span>
                                        @elseif($status === 'failed')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i> Failed
                                            </span>
                                        @elseif($status === 'refunded')
                                            <span class="badge bg-info text-dark">
                                                <i class="fas fa-undo me-1"></i> Refunded
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Method</th>
                                    <td class="text-capitalize">
                                        {{ $payment->payment_method
                                            ? str_replace('_', ' ', $payment->payment_method)
                                            : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $payment->created_at?->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $payment->updated_at?->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($payment->notes)
                        <div class="dashboard-card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-muted">{{ $payment->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
