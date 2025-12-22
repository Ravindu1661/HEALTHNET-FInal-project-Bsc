@extends('admin.layouts.master')
@section('title', 'Laboratory Details')
@section('page-title', 'Laboratory Details')
@section('content')
<div class="row"><div class="col-lg-9 mx-auto">
    <div class="dashboard-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $lab->profile_image ? asset('storage/' . $lab->profile_image) : asset('images/default-lab.png') }}" alt="Profile" class="rounded-circle mb-2" style="width:90px;height:90px;object-fit:cover;">
                </div>
                <div class="col-md-6">
                    <h5>{{ $lab->name }}</h5>
                    <p class="text-muted mb-0">{{ $lab->city }}, {{ $lab->province }}</p>
                    <div class="d-flex gap-2 mt-2">
                        <span class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($lab->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <strong>{{ number_format($lab->rating,2) }}</strong>
                        <small>({{ $lab->total_ratings }} ratings)</small>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-{{ 
                            $lab->status == 'approved' ? 'success' :
                            ($lab->status == 'pending' ? 'warning' :
                            ($lab->status == 'suspended' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($lab->status) }}
                        </span>
                        @if($lab->user && $lab->user->email_verified_at)
                            <span class="badge bg-primary">Email Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.laboratories.edit', $lab->id) }}" class="btn btn-warning btn-sm w-100 mb-2"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('admin.laboratories.index') }}" class="btn btn-secondary btn-sm w-100">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-card">
        <div class="card-body">
            <h6>Registration Number: <span class="ms-2">{{ $lab->registration_number }}</span></h6>
            <h6>Phone: <span class="ms-2">{{ $lab->phone }}</span></h6>
            <h6>City: <span class="ms-2">{{ $lab->city }}</span></h6>
            <h6>Province: <span class="ms-2">{{ $lab->province }}</span></h6>
            <h6>Operating Hours: <span class="ms-2">{{ $lab->operating_hours ?? '-' }}</span></h6>
            <h6>Email: <span class="ms-2">{{ $lab->email }}</span></h6>
            <h6>Address: <span class="ms-2">{{ $lab->address }}</span></h6>
            <h6>Services: <span class="ms-2">{{ $lab->services ? str_replace(['[',']','"'],'', $lab->services) : '-' }}</span></h6>
            <h6>Description: <span class="ms-2">{{ $lab->description ?? '-' }}</span></h6>
            <hr>
            <h6>Document:
                @if($lab->document_path)
                    <a href="{{ asset('storage/'.$lab->document_path) }}" target="_blank" class="btn btn-primary btn-sm ms-2"><i class="fas fa-eye"></i> View</a>
                    <a href="{{ asset('storage/'.$lab->document_path) }}" download class="btn btn-secondary btn-sm ms-2"><i class="fas fa-download"></i> Download</a>
                @else
                    <span class="text-muted ms-2">No document uploaded</span>
                @endif
            </h6>
        </div>
    </div>
</div></div>
@endsection
