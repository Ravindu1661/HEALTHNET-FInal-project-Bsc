@extends('admin.layouts.master')

@section('title', 'Lab Order #' . $order->order_number)
@section('page-title', 'Lab Order Details')

@section('content')
@php
    $statusMap = [
        'pending'          => ['bg-warning text-dark',  'fa-clock',        'Pending'],
        'sample_collected' => ['bg-info text-white',    'fa-vial',         'Sample Collected'],
        'processing'       => ['bg-primary text-white', 'fa-microscope',   'Processing'],
        'completed'        => ['bg-success text-white', 'fa-check-circle', 'Completed'],
        'cancelled'        => ['bg-danger text-white',  'fa-times-circle', 'Cancelled'],
    ];
    $payMap = [
        'paid'   => ['bg-success text-white', 'Paid'],
        'unpaid' => ['bg-danger text-white',  'Unpaid'],
    ];
    [$stClass, $stIcon, $stLabel] = $statusMap[$order->status]      ?? ['bg-secondary text-white','fa-circle','Unknown'];
    [$pyClass, $pyLabel]          = $payMap[$order->payment_status] ?? ['bg-secondary text-white','Unknown'];
    $patient = $order->patient;
    $pName   = $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'N/A';
    $pInit   = strtoupper(substr($pName, 0, 1));
@endphp

{{-- Alerts --}}
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

{{-- ── Page Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-1 fw-bold">
            <i class="fas fa-flask me-2 text-primary"></i>
            Lab Order — <span class="text-primary">{{ $order->order_number }}</span>
        </h5>
        <small class="text-muted">
            Ref: <strong>{{ $order->reference_number }}</strong>
            &nbsp;·&nbsp;
            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y · h:i A') }}
        </small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($order->report_file)
            <a href="{{ asset('storage/' . $order->report_file) }}"
               target="_blank" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Download Report
            </a>
        @endif
        <a href="{{ route('admin.lab-orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

{{-- ── Status Bar ── --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3 border-end">
                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Order Status</div>
                <span class="badge {{ $stClass }} fs-6 px-3">
                    <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                </span>
            </div>
            <div class="col-6 col-md-3 border-end">
                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Payment</div>
                <span class="badge {{ $pyClass }} fs-6 px-3">{{ $pyLabel }}</span>
            </div>
            <div class="col-6 col-md-3 border-end">
                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Total Amount</div>
                <strong class="text-dark fs-5">LKR {{ number_format($order->total_amount, 2) }}</strong>
            </div>
            <div class="col-6 col-md-3">
                <div class="text-muted mb-1" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Collection Type</div>
                @if($order->home_collection)
                    <span class="badge bg-primary"><i class="fas fa-home me-1"></i>Home Collection</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-hospital me-1"></i>Walk-in</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Main Grid ── --}}
<div class="row g-4">

{{-- ════ LEFT COL ════ --}}
<div class="col-lg-8">

    {{-- Order Info --}}
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Order Information</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0" style="font-size:.85rem;">
                <tbody>
                    <tr>
                        <td class="fw-bold text-muted bg-light" style="width:35%;">Order Number</td>
                        <td><span class="badge bg-info fs-6">{{ $order->order_number }}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted bg-light">Reference Number</td>
                        <td><code>{{ $order->reference_number }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted bg-light">Order Status</td>
                        <td>
                            <span class="badge {{ $stClass }}">
                                <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted bg-light">Payment Status</td>
                        <td>
                            <span class="badge {{ $pyClass }}">{{ $pyLabel }}</span>
                            @if($order->payment_method)
                                <span class="text-muted ms-2" style="font-size:.78rem;">via {{ ucfirst($order->payment_method) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted bg-light">Home Collection</td>
                        <td>
                            @if($order->home_collection)
                                <span class="badge bg-primary"><i class="fas fa-home me-1"></i>Yes</span>
                            @else
                                <span class="text-muted">No — Walk-in</span>
                            @endif
                        </td>
                    </tr>
                    @if($order->home_collection && $order->collection_date)
                    <tr>
                        <td class="fw-bold text-muted bg-light">Collection Date & Time</td>
                        <td>
                            <i class="fas fa-calendar me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($order->collection_date)->format('M d, Y') }}
                            @if($order->collection_time)
                                &nbsp;·&nbsp;
                                <i class="fas fa-clock me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($order->collection_time)->format('h:i A') }}
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($order->home_collection && $order->collection_address)
                    <tr>
                        <td class="fw-bold text-muted bg-light">Collection Address</td>
                        <td><i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $order->collection_address }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold text-muted bg-light">Order Placed</td>
                        <td>
                            <i class="fas fa-calendar me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y · h:i A') }}
                        </td>
                    </tr>
                    @if($order->report_uploaded_at)
                    <tr>
                        <td class="fw-bold text-muted bg-light">Report Uploaded</td>
                        <td>
                            <i class="fas fa-upload me-1 text-success"></i>
                            {{ \Carbon\Carbon::parse($order->report_uploaded_at)->format('M d, Y · h:i A') }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold text-muted bg-light">Total Amount</td>
                        <td><strong class="text-success fs-6">LKR {{ number_format($order->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Test Items --}}
    <div class="dashboard-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-vials me-2 text-primary"></i>Ordered Tests / Packages</h6>
            <span class="badge bg-secondary">{{ $order->items->count() }} item(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Item Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th class="text-end">Price (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $i => $item)
                        <tr>
                            <td class="text-muted fw-bold">{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $item->item_name }}</strong>
                                @if($item->test && $item->test->requirements)
                                    <br><small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>{{ Str::limit($item->test->requirements, 60) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($item->package_id)
                                    <span class="badge bg-purple" style="background:#7c3aed!important;">
                                        <i class="fas fa-box me-1"></i>Package
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-vial me-1"></i>Test
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $item->test->test_category ?? ($item->package ? 'Package' : '—') }}
                                </small>
                            </td>
                            <td class="text-end fw-bold">{{ number_format($item->price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No items found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold text-muted">TOTAL</td>
                            <td class="text-end fw-bold text-success fs-6">
                                {{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Prescription --}}
    @if($order->prescription_file)
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-prescription me-2 text-primary"></i>Prescription</h6>
        </div>
        <div class="card-body">
            <a href="{{ asset('storage/' . $order->prescription_file) }}"
               target="_blank" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> View Prescription
            </a>
        </div>
    </div>
    @endif

    {{-- Report --}}
    @if($order->report_file)
    <div class="dashboard-card">
        <div class="card-header" style="background:#f0fdf4;border-color:#a7f3d0;">
            <h6 class="mb-0 text-success">
                <i class="fas fa-file-medical me-2"></i>Lab Report Available
            </h6>
        </div>
        <div class="card-body" style="background:#f0fdf4;">
            <p class="text-muted mb-3" style="font-size:.85rem;">
                <i class="fas fa-check-circle text-success me-1"></i>
                Report uploaded on
                <strong>{{ $order->report_uploaded_at
                    ? \Carbon\Carbon::parse($order->report_uploaded_at)->format('M d, Y · h:i A')
                    : 'N/A' }}</strong>
            </p>
            <a href="{{ asset('storage/' . $order->report_file) }}"
               target="_blank" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i> Download Report (PDF)
            </a>
        </div>
    </div>
    @endif

</div>
{{-- END LEFT --}}

{{-- ════ RIGHT COL ════ --}}
<div class="col-lg-4">

    {{-- Patient Info --}}
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Patient</h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-light">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:48px;height:48px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:1.1rem;font-weight:800;">
                    {{ $pInit }}
                </div>
                <div>
                    <strong class="d-block">{{ $pName }}</strong>
                    @if($patient && $patient->user)
                        <small class="text-muted">{{ $patient->user->email }}</small>
                    @endif
                    @if($patient && $patient->phone)
                        <br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $patient->phone }}</small>
                    @endif
                </div>
            </div>
            @if($patient)
            <table class="table table-sm table-bordered mb-0" style="font-size:.82rem;">
                <tr>
                    <td class="fw-bold text-muted bg-light">Gender</td>
                    <td>{{ ucfirst($patient->gender ?? '—') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted bg-light">Blood Group</td>
                    <td>{{ $patient->blood_group ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted bg-light">NIC</td>
                    <td>{{ $patient->nic ?? '—' }}</td>
                </tr>
                @if($patient->city)
                <tr>
                    <td class="fw-bold text-muted bg-light">City</td>
                    <td>{{ $patient->city }}</td>
                </tr>
                @endif
            </table>
            <div class="mt-3">
                <a href="{{ route('admin.patients.show', $patient->id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-external-link-alt me-1"></i> View Full Profile
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Laboratory Info --}}
    @if($order->laboratory)
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-flask me-2 text-primary"></i>Laboratory</h6>
        </div>
        <div class="card-body">
            <strong class="d-block mb-3 fs-6">{{ $order->laboratory->name }}</strong>
            <table class="table table-sm table-bordered mb-0" style="font-size:.82rem;">
                @if($order->laboratory->phone)
                <tr>
                    <td class="fw-bold text-muted bg-light">Phone</td>
                    <td>{{ $order->laboratory->phone }}</td>
                </tr>
                @endif
                @if($order->laboratory->email)
                <tr>
                    <td class="fw-bold text-muted bg-light">Email</td>
                    <td style="font-size:.75rem;">{{ $order->laboratory->email }}</td>
                </tr>
                @endif
                @if($order->laboratory->city)
                <tr>
                    <td class="fw-bold text-muted bg-light">City</td>
                    <td>{{ $order->laboratory->city }}</td>
                </tr>
                @endif
                <tr>
                    <td class="fw-bold text-muted bg-light">Reg. No</td>
                    <td><code style="font-size:.75rem;">{{ $order->laboratory->registration_number }}</code></td>
                </tr>
            </table>
            <div class="mt-3">
                <a href="{{ route('admin.laboratories.show', $order->laboratory_id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-external-link-alt me-1"></i> View Laboratory
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Referring Doctor --}}
    @if($order->doctor)
    <div class="dashboard-card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-user-md me-2 text-primary"></i>Referring Doctor</h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:linear-gradient(135deg,#11998e,#38ef7d);color:#fff;font-size:.9rem;font-weight:800;">
                    {{ strtoupper(substr($order->doctor->first_name ?? 'D', 0, 1)) }}
                </div>
                <div>
                    <strong>Dr. {{ $order->doctor->first_name }} {{ $order->doctor->last_name }}</strong>
                    <br>
                    <small class="text-muted">{{ $order->doctor->specialization ?? 'General' }}</small>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.doctors.show', $order->doctor->id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-external-link-alt me-1"></i> View Doctor
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="dashboard-card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h6>
        </div>
        <div class="card-body d-grid gap-2">
            <a href="{{ route('admin.lab-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list me-1"></i> All Lab Orders
            </a>
            @if($order->report_file)
                <a href="{{ asset('storage/' . $order->report_file) }}"
                   target="_blank" class="btn btn-success btn-sm">
                    <i class="fas fa-file-pdf me-1"></i> Download Report
                </a>
            @endif
            @if($order->prescription_file)
                <a href="{{ asset('storage/' . $order->prescription_file) }}"
                   target="_blank" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-prescription me-1"></i> View Prescription
                </a>
            @endif
        </div>
    </div>

</div>
{{-- END RIGHT --}}

</div>
{{-- END GRID --}}

@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
</script>
@endpush
