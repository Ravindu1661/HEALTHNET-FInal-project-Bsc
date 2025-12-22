@extends('admin.layouts.master')
@section('title', 'Medical Centre Details')
@section('page-title', 'Medical Centre Details')
@section('content')
<div class="row"><div class="col-lg-9 mx-auto">
    <div class="dashboard-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $medicalCentre->profile_image ? asset('storage/' . $medicalCentre->profile_image) : asset('images/default-medical-centre.png') }}" alt="Profile" class="rounded-circle mb-2" style="width:90px;height:90px;object-fit:cover;">
                </div>
                <div class="col-md-6">
                    <h5>{{ $medicalCentre->name }}</h5>
                    <p class="text-muted mb-0">{{ $medicalCentre->city }}, {{ $medicalCentre->province }}</p>
                    <div class="d-flex gap-2 mt-2">
                        <span class="text-warning">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= round($medicalCentre->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <strong>{{ number_format($medicalCentre->rating, 2) }}</strong>
                        <small>({{ $medicalCentre->total_ratings }} ratings)</small>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-{{ 
                            $medicalCentre->status == 'approved' ? 'success' :
                            ($medicalCentre->status == 'pending' ? 'warning' :
                            ($medicalCentre->status == 'suspended' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($medicalCentre->status) }}
                        </span>
                        @if($medicalCentre->user && $medicalCentre->user->email_verified_at)
                            <span class="badge bg-primary">Email Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.medical-centres.edit', $medicalCentre->id) }}" class="btn btn-warning btn-sm w-100 mb-2"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.medical-centres.index') }}" class="btn btn-secondary btn-sm w-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-card">
        <div class="card-body">
            <h6>Registration Number: <span class="ms-2">{{ $medicalCentre->registration_number }}</span></h6>
            <h6>Phone: <span class="ms-2">{{ $medicalCentre->phone }}</span></h6>
            <h6>City: <span class="ms-2">{{ $medicalCentre->city }}</span></h6>
            <h6>Province: <span class="ms-2">{{ $medicalCentre->province }}</span></h6>
            <h6>Operating Hours: <span class="ms-2">{{ $medicalCentre->operating_hours ?? '-' }}</span></h6>
            <h6>Specializations: <span class="ms-2">{{ $medicalCentre->specializations ? str_replace(['[',']','"'],'', $medicalCentre->specializations) : '-' }}</span></h6>
            <h6>Facilities: <span class="ms-2">{{ $medicalCentre->facilities ? str_replace(['[',']','"'],'', $medicalCentre->facilities) : '-' }}</span></h6>
            <h6>Description: <span class="ms-2">{{ $medicalCentre->description ?? '-' }}</span></h6>
            <hr>
            <h6>Document:
                @if($medicalCentre->document_path)
                    <a href="{{ asset('storage/'.$medicalCentre->document_path) }}" target="_blank" class="btn btn-primary btn-sm ms-2"><i class="fas fa-eye"></i> View</a>
                    <a href="{{ asset('storage/'.$medicalCentre->document_path) }}" download class="btn btn-secondary btn-sm ms-2"><i class="fas fa-download"></i> Download</a>
                @else
                    <span class="text-muted ms-2">No document uploaded</span>
                @endif
            </h6>
        </div>
    </div>
</div></div>
@endsection
