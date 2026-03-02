{{-- resources/views/admin/reports/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="row">
    <div class="col-lg-12">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i> Reports
                </h6>
                <small class="text-muted">
                    Type: <strong class="text-uppercase">{{ $type }}</strong>
                </small>
            </div>

            <div class="card-body">
                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Report Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="appointments" {{ $type === 'appointments' ? 'selected' : '' }}>
                                Appointments
                            </option>
                            <option value="payments" {{ $type === 'payments' ? 'selected' : '' }}>
                                Payments
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date"
                               name="date_from"
                               value="{{ $dateFrom }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date"
                               name="date_to"
                               value="{{ $dateTo }}"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status (optional)</label>
                        <input type="text"
                               name="status"
                               value="{{ $status }}"
                               class="form-control form-control-sm"
                               placeholder="{{ $type === 'appointments' ? 'pending, confirmed...' : 'paid, failed...' }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        @if(request()->hasAny(['type','date_from','date_to','status']))
                            <a href="{{ route('admin.reports.index', ['type' => $type]) }}"
                               class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        @endif
                    </div>

                    <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                        {{-- Export buttons --}}
                        <a href="{{ route('admin.reports.export.csv', request()->only('type','date_from','date_to','status')) }}"
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-csv me-1"></i> Export CSV
                        </a>
                        <a href="{{ route('admin.reports.export.pdf', request()->only('type','date_from','date_to','status')) }}"
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </div>
                </form>

                {{-- Summary cards --}}
                @if($type === 'appointments')
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-primary">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Total Appointments</h6>
                                    <h4>{{ $summary['total'] ?? 0 }}</h4>
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
                                    <h4>{{ $summary['pending'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-success">
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Completed</h6>
                                    <h4>{{ $summary['completed'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-danger">
                                <div class="stat-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Cancelled</h6>
                                    <h4>{{ $summary['cancelled'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'payments')
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-primary">
                                <div class="stat-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Total Payments</h6>
                                    <h4>{{ $summary['total'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-success">
                                <div class="stat-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Total Amount</h6>
                                    <h4>LKR {{ number_format($summary['total_amount'] ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-success">
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Paid</h6>
                                    <h4>LKR {{ number_format($summary['paid'] ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-summary stat-summary-danger">
                                <div class="stat-icon">
                                    <i class="fas fa-undo-alt"></i>
                                </div>
                                <div class="stat-details">
                                    <h6>Refunded</h6>
                                    <h4>LKR {{ number_format($summary['refunded'] ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Result info --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        Showing
                        <strong>{{ $data->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $data->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $data->total() }}</strong>
                        entries
                    </small>
                </div>

                {{-- Data tables --}}
                <div class="table-responsive">
                    @if($type === 'appointments')
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Appointment No</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th style="width: 140px;">Date</th>
                                    <th style="width: 100px;">Time</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 150px;">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->appointment_number }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">
                                                    {{ optional($row->patient)->firstname }} {{ optional($row->patient)->lastname }}
                                                </span>
                                                <small class="text-muted">
                                                    ID: {{ optional($row->patient)->id ?? '-' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">
                                                    Dr. {{ optional($row->doctor)->firstname }} {{ optional($row->doctor)->lastname }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ optional($row->doctor)->specialization ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>{{ $row->appointment_date }}</td>
                                        <td>{{ $row->appointment_time }}</td>
                                        <td>
                                            @php $st = $row->status; @endphp
                                            <span class="badge
                                                @if($st === 'completed') bg-success
                                                @elseif($st === 'pending') bg-warning text-dark
                                                @elseif($st === 'confirmed') bg-primary
                                                @elseif($st === 'cancelled') bg-danger
                                                @else bg-secondary
                                                @endif
                                            ">
                                                {{ ucfirst($st) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $row->created_at?->format('Y-m-d H:i') }}</small><br>
                                            @if($row->created_at)
                                                <small class="text-muted">
                                                    {{ $row->created_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                                            <h6 class="text-muted mb-1">No appointments found</h6>
                                            <p class="text-muted small mb-0">
                                                Try changing date range or status filters.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($type === 'payments')
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Reference</th>
                                    <th style="width: 90px;">User ID</th>
                                    <th style="width: 110px;">Appointment</th>
                                    <th style="width: 130px;">Amount (LKR)</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 120px;">Method</th>
                                    <th style="width: 150px;">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->reference ?? '-' }}</td>
                                        <td>{{ $row->user_id ?? '-' }}</td>
                                        <td>{{ $row->appointment_id ?? '-' }}</td>
                                        <td>{{ number_format($row->amount ?? 0, 2) }}</td>
                                        <td>
                                            @php $st = $row->status; @endphp
                                            <span class="badge
                                                @if($st === 'paid') bg-success
                                                @elseif($st === 'pending') bg-warning text-dark
                                                @elseif($st === 'failed') bg-danger
                                                @elseif($st === 'refunded') bg-info text-dark
                                                @else bg-secondary
                                                @endif
                                            ">
                                                {{ ucfirst($st) }}
                                            </span>
                                        </td>
                                        <td>{{ $row->payment_method ?? '-' }}</td>
                                        <td>
                                            <small>{{ $row->created_at?->format('Y-m-d H:i') }}</small><br>
                                            @if($row->created_at)
                                                <small class="text-muted">
                                                    {{ $row->created_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3 d-block"></i>
                                            <h6 class="text-muted mb-1">No payments found</h6>
                                            <p class="text-muted small mb-0">
                                                Try changing date range or status filters.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Pagination --}}
                @if($data->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                        </small>
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-summary {
        display: flex;
        align-items: center;
        padding: 0.9rem 1rem;
        background: #fff;
        border-radius: 8px;
        border-left: 4px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .stat-summary .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        background: #f3f4f6;
        color: #4b5563;
    }
    .stat-summary .stat-details h6 {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }
    .stat-summary .stat-details h4 {
        margin: 0;
        margin-top: 0.15rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: #111827;
    }
    .stat-summary-primary { border-color: #2563eb; }
    .stat-summary-primary .stat-icon { background: #dbeafe; color: #1d4ed8; }
    .stat-summary-success { border-color: #16a34a; }
    .stat-summary-success .stat-icon { background: #dcfce7; color: #15803d; }
    .stat-summary-warning { border-color: #f59e0b; }
    .stat-summary-warning .stat-icon { background: #fffbeb; color: #d97706; }
    .stat-summary-danger { border-color: #dc2626; }
    .stat-summary-danger .stat-icon { background: #fee2e2; color: #b91c1c; }
</style>
@endpush
