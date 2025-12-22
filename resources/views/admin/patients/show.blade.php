@extends('admin.layouts.master')
@section('title', 'Patient Details')
@section('page-title', 'Patient Details')
@section('content')
<div class="row"><div class="col-lg-9 mx-auto">
    <div class="dashboard-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $patient->profile_image ? asset('storage/'.$patient->profile_image) : asset('images/default-avatar.png') }}" alt="Profile" class="rounded-circle mb-2" style="width:90px; height:90px; object-fit: cover;">
                </div>
                <div class="col-md-6">
                    <h5>{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                    <p class="text-muted mb-0">{{ $patient->city ?? '-' }}, {{ $patient->province ?? '-' }}</p>
                    <div class="mt-3">
                        <span class="badge bg-{{ 
                            $patient->user->status == 'active' ? 'success' :
                            ($patient->user->status == 'pending' ? 'warning' :
                            ($patient->user->status == 'suspended' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($patient->user->status) }}
                        </span>
                        @if($patient->user && $patient->user->email_verified_at)
                            <span class="badge bg-primary">Email Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-warning btn-sm w-100 mb-2"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm w-100">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-body">
            <h6>NIC: <span class="ms-2">{{ $patient->nic }}</span></h6>
            <h6>Phone: <span class="ms-2">{{ $patient->phone }}</span></h6>
            <h6>Address: <span class="ms-2">{{ $patient->address ?? '-' }}</span></h6>
            <h6>City: <span class="ms-2">{{ $patient->city ?? '-' }}</span></h6>
            <h6>Province: <span class="ms-2">{{ $patient->province ?? '-' }}</span></h6>
        </div>
    </div>
</div></div>
@endsection
