{{-- resources/views/pharmacy/patients/show.blade.php --}}
@extends('pharmacy.layouts.master')
@section('title', $patient->first_name.' '.$patient->last_name)
@section('page-title', 'Patients')

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('pharmacy.patients.index') }}"
           class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fw-bold fs-5">Patient Profile</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.patients.orders', $patient->id) }}"
           class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="fas fa-shopping-bag me-1"></i>Orders
        </a>
        <a href="{{ route('pharmacy.patients.prescriptions', $patient->id) }}"
           class="btn btn-outline-info btn-sm rounded-pill px-3">
            <i class="fas fa-file-prescription me-1"></i>Prescriptions
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- ── Left: Patient Info ── --}}
    <div class="col-lg-4">

        {{-- Profile Card --}}
        <div class="dashboard-card mb-3">
            <div class="card-body text-center pt-4 pb-3">
                @php
                    $initials = strtoupper(substr($patient->first_name,0,1).substr($patient->last_name,0,1));
                    $colors   = ['#2563eb','#7c3aed','#db2777','#16a34a','#d97706','#0891b2'];
                    $color    = $colors[$patient->id % count($colors)];
                @endphp
                @if($patient->profile_image)
                    <img src="{{ asset('storage/'.$patient->profile_image) }}"
                         class="rounded-circle mb-3"
                         style="width:80px;height:80px;object-fit:cover;
                                border:3px solid {{ $color }}"
                         alt="patient">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;background:{{ $color }}20;
                                color:{{ $color }};font-size:1.6rem;font-weight:700;
                                border:3px solid {{ $color }}20">
                        {{ $initials }}
                    </div>
                @endif
                <h6 class="fw-bold mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h6>
                @if($patient->user?->email)
                <small class="text-muted">{{ $patient->user->email }}</small>
                @endif
                <div class="d-flex justify-content-center gap-2 mt-2">
                    @if($patient->blood_group)
                    <span class="badge bg-danger bg-opacity-15 text-danger rounded-pill">
                        {{ $patient->blood_group }}
                    </span>
                    @endif
                    @if($patient->gender)
                    <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill">
                        {{ ucfirst($patient->gender) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-id-card me-2 text-primary"></i>Personal Details</h6>
            </div>
            <div class="card-body">
                @if($patient->nic)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">NIC</small>
                    <small class="fw-semibold">{{ $patient->nic }}</small>
                </div>
                @endif
                @if($patient->date_of_birth)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Date of Birth</small>
                    <small class="fw-semibold">
                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}
                        <span class="text-muted">
                            ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} yrs)
                        </span>
                    </small>
                </div>
                @endif
                @if($patient->phone)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <small class="text-muted">Phone</small>
                    <small class="fw-semibold">{{ $patient->phone }}</small>
                </div>
                @endif
                @if($patient->address)
                <div class="py-2 border-bottom">
                    <small class="text-muted d-block mb-1">Address</small>
                    <small class="fw-semibold">
                        {{ $patient->address }}
                        @if($patient->city), {{ $patient->city }}@endif
                        @if($patient->province), {{ $patient->province }}@endif
                    </small>
                </div>
                @endif
                @if($patient->emergency_contact_name)
                <div class="py-2">
                    <small class="text-muted d-block mb-1">Emergency Contact</small>
                    <small class="fw-semibold">{{ $patient->emergency_contact_name }}</small>
                    @if($patient->emergency_contact_phone)
                    <small class="d-block text-muted">{{ $patient->emergency_contact_phone }}</small>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Top Medicines --}}
        @if($topMedicines->count() > 0)
        <div class="dashboard-card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-pills me-2 text-warning"></i>Most Ordered</h6>
            </div>
            <div class="card-body p-0">
                @foreach($topMedicines as $i => $med)
                <div class="d-flex justify-content-between align-items-center
                            px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:24px;height:24px;background:#eff6ff;
                                    color:#2563eb;font-size:.7rem;font-weight:700">
                            {{ $i+1 }}
                        </div>
                        <small class="fw-semibold" style="font-size:.82rem">
                            {{ $med->medication_name }}
                        </small>
                    </div>
                    <span class="badge bg-light text-dark border" style="font-size:.7rem">
                        {{ $med->total_qty }} units
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ── Right: Stats + Recent Orders ── --}}
    <div class="col-lg-8">

        {{-- Stats Cards --}}
        <div class="row g-3 mb-3">
            <div class="col-sm-3">
                <div class="dashboard-card text-center py-3">
                    <div style="font-size:1.6rem;font-weight:700;color:#2563eb">{{ $ordersCount }}</div>
                    <div class="text-muted" style="font-size:.75rem">Total Orders</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-card text-center py-3">
                    <div style="font-size:1.4rem;font-weight:700;color:#16a34a">
                        Rs. {{ number_format($totalSpent, 0) }}
                    </div>
                    <div class="text-muted" style="font-size:.75rem">Total Spent</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-card text-center py-3">
                    <div style="font-size:1.6rem;font-weight:700;color:#16a34a">{{ $deliveredOrders }}</div>
                    <div class="text-muted" style="font-size:.75rem">Delivered</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="dashboard-card text-center py-3">
                    <div style="font-size:1.6rem;font-weight:700;color:#dc2626">{{ $cancelledOrders }}</div>
                    <div class="text-muted" style="font-size:.75rem">Cancelled</div>
                </div>
            </div>
        </div>

        {{-- Last Order Info --}}
        @if($lastOrder)
        <div class="alert border-0 d-flex align-items-center gap-3 mb-3"
             style="background:#eff6ff;border-radius:10px">
            <i class="fas fa-clock text-primary fa-lg"></i>
            <div>
                <strong>Last Order:</strong>
                {{ $lastOrder->order_number }} —
                <span class="badge bg-{{ match($lastOrder->status) {
                    'pending'    => 'warning',
                    'verified'   => 'info',
                    'processing' => 'primary',
                    'ready'      => 'success',
                    'dispatched' => 'secondary',
                    'delivered'  => 'success',
                    'cancelled'  => 'danger',
                    default      => 'secondary',
                } }} rounded-pill ms-1" style="font-size:.72rem">
                    {{ ucfirst($lastOrder->status) }}
                </span>
                <span class="text-muted ms-2" style="font-size:.82rem">
                    {{ \Carbon\Carbon::parse($lastOrder->created_at)->diffForHumans() }}
                </span>
            </div>
            <a href="{{ route('pharmacy.orders.show', $lastOrder->id) }}"
               class="btn btn-sm btn-outline-primary rounded-pill ms-auto">
                View <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        @endif

        {{-- Recent Orders Table --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6><i class="fas fa-history me-2 text-primary"></i>Recent Orders</h6>
                <a href="{{ route('pharmacy.patients.orders', $patient->id) }}"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8fafc;font-size:.74rem;text-transform:uppercase;
                                      letter-spacing:.05em;color:#6b7280">
                            <tr>
                                <th class="ps-3 py-3">Order #</th>
                                <th>Date</th>
                                <th class="text-center">Items</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-3">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($recentOrders as $order)
                        @php
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
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('pharmacy.orders.show', $order->id) }}"
                                   class="fw-semibold text-primary text-decoration-none"
                                   style="font-size:.83rem">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div style="font-size:.8rem">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border" style="font-size:.72rem">
                                    {{ $order->items->count() }}
                                </span>
                            </td>
                            <td class="text-end fw-semibold" style="font-size:.85rem">
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $bdg }} rounded-pill" style="font-size:.7rem">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'danger' }}
                                      rounded-pill" style="font-size:.7rem">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-40"></i>
                    <small>No orders found.</small>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
