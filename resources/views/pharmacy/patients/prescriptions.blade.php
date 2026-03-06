{{-- resources/views/pharmacy/patients/prescriptions.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', $patient->first_name.' Prescriptions')
@section('page-title', 'Patients')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.patients.show', $patient->id) }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fw-bold fs-5">
            {{ $patient->first_name }} {{ $patient->last_name }} — Prescriptions
        </span>
    </div>
    <a href="{{ route('pharmacy.patients.orders', $patient->id) }}"
       class="btn btn-outline-primary btn-sm rounded-pill px-3">
        <i class="fas fa-shopping-bag me-1"></i> All Orders
    </a>
</div>

{{-- Count Banner --}}
<div class="alert border-0 mb-4 d-flex align-items-center gap-3"
     style="background:#eff6ff;border-radius:10px">
    <i class="fas fa-file-prescription fa-2x text-info"></i>
    <div>
        <strong>{{ $prescriptionCount }}</strong>
        prescription file{{ $prescriptionCount != 1 ? 's' : '' }} found for this patient.
    </div>
</div>

{{-- ── Filters ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pharmacy.patients.prescriptions', $patient->id) }}"
              method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Order Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['pending','verified','processing','ready','dispatched','delivered','cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status')==$st ? 'selected':'' }}>
                        {{ ucfirst($st) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-info btn-sm flex-fill text-white">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('pharmacy.patients.prescriptions', $patient->id) }}"
                   class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Prescription Cards ── --}}
@if($prescriptions->count() > 0)
<div class="row g-3">
    @foreach($prescriptions as $order)
    @php
        $ext = pathinfo($order->prescription_file, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($ext), ['jpg','jpeg','png']);
        $bdg = match($order->status) {
            'pending'    => 'warning',
            'verified'   => 'info',
            'processing' => 'primary',
            'ready'      => 'success',
            'dispatched' => 'secondary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    @endphp
    <div class="col-sm-6 col-xl-4">
        <div class="dashboard-card h-100">
            {{-- Preview --}}
            <div class="text-center p-3 border-bottom"
                 style="background:#f8fafc;border-radius:12px 12px 0 0;min-height:160px;
                        display:flex;align-items:center;justify-content:center">
                @if($isImage)
                    <img src="{{ asset('storage/'.$order->prescription_file) }}"
                         class="img-fluid rounded" style="max-height:140px;object-fit:cover"
                         alt="Prescription">
                @else
                    <div class="text-center">
                        <i class="fas fa-file-pdf fa-4x text-danger opacity-70 mb-2"></i>
                        <div class="text-muted" style="font-size:.78rem">PDF Document</div>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-semibold" style="font-size:.85rem">
                            {{ $order->order_number }}
                        </div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $bdg }} rounded-pill" style="font-size:.68rem">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between py-1 border-top mt-2">
                    <small class="text-muted">Amount</small>
                    <small class="fw-semibold text-primary">
                        Rs. {{ number_format($order->total_amount, 2) }}
                    </small>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <small class="text-muted">Payment</small>
                    <span class="badge bg-{{ $order->payment_status==='paid'?'success':'danger' }}
                          rounded-pill" style="font-size:.65rem">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                       class="btn btn-sm btn-outline-primary rounded-pill flex-fill">
                        <i class="fas fa-eye me-1"></i>Order
                    </a>
                    <a href="{{ route('pharmacy.orders.download-prescription', $order->id) }}"
                       class="btn btn-sm btn-outline-info rounded-pill flex-fill">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center mt-4">
    <small class="text-muted">
        Showing {{ $prescriptions->firstItem() }}–{{ $prescriptions->lastItem() }}
        of {{ $prescriptions->total() }}
    </small>
    {{ $prescriptions->links() }}
</div>

@else
<div class="dashboard-card">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-file-prescription fa-3x mb-3 d-block opacity-40"></i>
        <h6 class="fw-semibold">No Prescriptions Found</h6>
        <p class="small">No prescription files match the selected filters.</p>
    </div>
</div>
@endif

@endsection
