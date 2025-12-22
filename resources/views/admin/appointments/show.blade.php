@extends('admin.layouts.master')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')

<div class="row">
    <div class="col-lg-11 mx-auto">
        
        <!-- Appointment Header Card -->
        <div class="dashboard-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-calendar-check text-primary"></i> 
                            Appointment #{{ $appointment->appointment_number }}
                        </h3>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            @if($appointment->status == 'confirmed')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Confirmed</span>
                            @elseif($appointment->status == 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-primary"><i class="fas fa-check"></i> Completed</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Cancelled</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user-slash"></i> No Show</span>
                            @endif
                            
                            @if($appointment->payment_status == 'paid')
                                <span class="badge bg-success"><i class="fas fa-money-check"></i> Paid</span>
                            @elseif($appointment->payment_status == 'partial')
                                <span class="badge bg-warning text-dark"><i class="fas fa-coins"></i> Partial Payment</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Unpaid</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm mb-2 w-100">
                            <i class="fas fa-edit"></i> Edit Appointment
                        </a>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-md-6">
                
                <!-- Patient Information -->
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-user me-2"></i>Patient Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . ($appointment->patient->profileimage ?? 'images/default-avatar.png')) }}" 
                                 alt="{{ $appointment->patient->firstname }}" 
                                 class="rounded-circle me-3" width="60" height="60"
                                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            <div>
                                <h5 class="mb-1">{{ $appointment->patient->firstname }} {{ $appointment->patient->lastname }}</h5>
                                <p class="text-muted mb-0">{{ $appointment->patient->user->email }}</p>
                            </div>
                        </div>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="45%">Patient ID</th>
                                <td>{{ $appointment->patient->id }}</td>
                            </tr>
                            <tr>
                                <th>NIC</th>
                                <td>{{ $appointment->patient->nic ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $appointment->patient->phone }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ ucfirst($appointment->patient->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Blood Group</th>
                                <td>{{ $appointment->patient->blood_group ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Doctor Information -->
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-user-md me-2"></i>Doctor Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . ($appointment->doctor->profileimage ?? 'images/default-avatar.png')) }}" 
                                 alt="Dr. {{ $appointment->doctor->firstname }}" 
                                 class="rounded-circle me-3" width="60" height="60"
                                 onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            <div>
                                <h5 class="mb-1">Dr. {{ $appointment->doctor->firstname }} {{ $appointment->doctor->lastname }}</h5>
                                <p class="text-muted mb-0">{{ $appointment->doctor->specialization }}</p>
                            </div>
                        </div>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="45%">SLMC Number</th>
                                <td><span class="badge bg-info">{{ $appointment->doctor->slmc_number }}</span></td>
                            </tr>
                            <tr>
                                <th>Experience</th>
                                <td>{{ $appointment->doctor->experience_years ?? 0 }} years</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $appointment->doctor->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $appointment->doctor->user->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                
                <!-- Appointment Details -->
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-calendar-alt me-2"></i>Appointment Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="45%">Appointment ID</th>
                                <td>{{ $appointment->id }}</td>
                            </tr>
                            <tr>
                                <th>Appointment Number</th>
                                <td><span class="badge bg-primary">{{ $appointment->appointment_number }}</span></td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td><i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Workplace Type</th>
                                <td>{{ ucfirst($appointment->workplace_type) }}</td>
                            </tr>
                            <tr>
                                <th>Workplace</th>
                                <td>
                                    @if($appointment->workplace_type == 'hospital' && $appointment->hospital)
                                        <i class="fas fa-hospital"></i> {{ $appointment->hospital->name }}
                                    @elseif($appointment->workplace_type == 'medicalcentre' && $appointment->medicalCentre)
                                        <i class="fas fa-clinic-medical"></i> {{ $appointment->medicalCentre->name }}
                                    @else
                                        <i class="fas fa-user-md"></i> Private Practice
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $appointment->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-money-bill-wave me-2"></i>Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="45%">Consultation Fee</th>
                                <td><strong>LKR {{ number_format($appointment->consultation_fee, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Advance Payment</th>
                                <td><strong>LKR {{ number_format($appointment->advance_payment ?? 0, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Remaining Amount</th>
                                <td><strong class="text-danger">LKR {{ number_format($appointment->consultation_fee - ($appointment->advance_payment ?? 0), 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>
                                    @if($appointment->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($appointment->payment_status == 'partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($appointment->reason || $appointment->notes)
                <div class="dashboard-card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                    </div>
                    <div class="card-body">
                        @if($appointment->reason)
                            <h6 class="mb-2">Reason for Visit:</h6>
                            <p class="text-muted">{{ $appointment->reason }}</p>
                        @endif
                        
                        @if($appointment->notes)
                            <h6 class="mb-2 mt-3">Notes:</h6>
                            <p class="text-muted">{{ $appointment->notes }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Cancellation Info -->
                @if($appointment->status == 'cancelled')
                <div class="dashboard-card mb-3">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fas fa-ban me-2"></i>Cancellation Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="45%">Cancelled By</th>
                                <td>{{ $appointment->cancelledBy->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td>{{ $appointment->cancellation_reason ?? 'No reason provided' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

@endsection
