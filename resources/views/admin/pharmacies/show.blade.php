@extends('admin.layouts.master')
@section('title', 'Pharmacy Details')
@section('page-title', 'Pharmacy Details')
@section('content')
<div class="row"><div class="col-lg-9 mx-auto">
    <div class="dashboard-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $pharmacy->profile_image ? asset('storage/' . $pharmacy->profile_image) : asset('images/default-pharmacy.png') }}" alt="Profile" class="rounded-circle mb-2" style="width:90px;height:90px;object-fit:cover;">
                </div>
                <div class="col-md-6">
                    <h5>{{ $pharmacy->name }}</h5>
                    <p class="mb-0 text-muted">{{ $pharmacy->city }}, {{ $pharmacy->province }}</p>
                    <div class="d-flex gap-2 mt-2">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($pharmacy->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <strong>{{ number_format($pharmacy->rating,2) }}</strong>
                        <small>({{ $pharmacy->total_ratings }} ratings)</small>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-{{ 
                            $pharmacy->status == 'approved' ? 'success' :
                            ($pharmacy->status == 'pending' ? 'warning' :
                            ($pharmacy->status == 'suspended' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($pharmacy->status) }}
                        </span>
                        @if($pharmacy->user && $pharmacy->user->email_verified_at)
                            <span class="badge bg-primary">Email Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}" class="btn btn-warning btn-sm w-100 mb-2"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.pharmacies.index') }}" class="btn btn-secondary btn-sm w-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-card">
        <div class="card-body">
            <h6>Registration Number: <span class="ms-2">{{ $pharmacy->registration_number }}</span></h6>
            <h6>Pharmacist Name: <span class="ms-2">{{ $pharmacy->pharmacist_name }}</span></h6>
            <h6>Pharmacist License: <span class="ms-2">{{ $pharmacy->pharmacist_license }}</span></h6>
            <h6>Phone: <span class="ms-2">{{ $pharmacy->phone }}</span></h6>
            <h6>Email: <span class="ms-2">{{ $pharmacy->email }}</span></h6>
            <h6>City: <span class="ms-2">{{ $pharmacy->city }}</span></h6>
            <h6>Province: <span class="ms-2">{{ $pharmacy->province }}</span></h6>
            <h6>Postal Code: <span class="ms-2">{{ $pharmacy->postal_code ?? '-' }}</span></h6>
            <h6>Operating Hours: <span class="ms-2">{{ $pharmacy->operating_hours ?? '-' }}</span></h6>
            <h6>Delivery Available: <span class="ms-2">{{ $pharmacy->delivery_available ? 'Yes' : 'No' }}</span></h6>
            <h6>Address: <span class="ms-2">{{ $pharmacy->address }}</span></h6>
            <hr>
            <h6>Document:
                @if ($pharmacy->document_path)
                    <a href="{{ asset('storage/'.$pharmacy->document_path) }}" target="_blank" class="btn btn-primary btn-sm ms-2"><i class="fas fa-eye"></i> View</a>
                    <a href="{{ asset('storage/'.$pharmacy->document_path) }}" download class="btn btn-secondary btn-sm ms-2"><i class="fas fa-download"></i> Download</a>
                @else
                    <span class="text-muted ms-2">No document uploaded</span>
                @endif
            </h6>
        </div>
    </div>
</div></div>
@endsection
