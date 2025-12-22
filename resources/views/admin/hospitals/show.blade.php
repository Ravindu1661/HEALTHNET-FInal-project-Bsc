@extends('admin.layouts.master')
@section('title', 'Hospital Details')
@section('page-title', 'Hospital Details')
@section('content')
<div class="row"><div class="col-lg-9 mx-auto">
    <div class="dashboard-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $hospital->profile_image ? asset('storage/' . $hospital->profile_image) : asset('images/default-hospital.png') }}" alt="Profile" class="rounded-circle mb-2" style="width:90px; height:90px; object-fit:cover;">
                </div>
                <div class="col-md-6">
                    <h5>{{ $hospital->name }}</h5>
                    <p class="text-muted mb-0">{{ $hospital->city }}, {{ $hospital->province }}</p>
                    <div class="d-flex gap-2 mt-3">
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($hospital->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <strong>{{ number_format($hospital->rating, 2) }}</strong>
                        <small>({{ $hospital->total_ratings }} ratings)</small>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-{{ $hospital->status == 'approved' ? 'success' : ($hospital->status == 'pending' ? 'warning' : ($hospital->status == 'suspended' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($hospital->status) }}
                        </span>
                        @if ($hospital->user && $hospital->user->email_verified_at)
                            <span class="badge bg-primary">Email Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn btn-warning btn-sm w-100 mb-2"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary btn-sm w-100"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-card">
        <div class="card-body">
            <h6>Registration Number: <span class="ms-2">{{ $hospital->registration_number }}</span></h6>
            <h6>Phone: <span class="ms-2">{{ $hospital->phone }}</span></h6>
            <h6>Address: <span class="ms-2">{{ $hospital->address }}, {{ $hospital->city }}, {{ $hospital->province }}</span></h6>
            <h6>Website: <span class="ms-2">{{ $hospital->website ?? '-' }}</span></h6>
            <h6>Specializations: 
                <span class="ms-2">{{ $hospital->specializations ? str_replace(['[',']','"'], '', $hospital->specializations) : '-' }}</span>
            </h6>
            <h6>Facilities: 
                <span class="ms-2">{{ $hospital->facilities ? str_replace(['[',']','"'], '', $hospital->facilities) : '-' }}</span>
            </h6>
            <h6>Description: <span class="ms-2">{{ $hospital->description ?? '-' }}</span></h6>
            <hr>
            <h6>Document:
                @if ($hospital->document_path)
                    <a href="{{ asset('storage/' . $hospital->document_path) }}" target="_blank" class="btn btn-primary btn-sm ms-2"><i class="fas fa-eye"></i> View</a>
                    <a href="{{ asset('storage/' . $hospital->document_path) }}" download class="btn btn-secondary btn-sm ms-2"><i class="fas fa-download"></i> Download</a>
                @else
                    <span class="text-muted ms-2">No document uploaded</span>
                @endif
            </h6>
        </div>
    </div>
</div></div>
@endsection
