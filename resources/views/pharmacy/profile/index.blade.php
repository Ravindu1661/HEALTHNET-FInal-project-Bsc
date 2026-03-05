@extends('pharmacy.layouts.master')

@section('title', 'My Pharmacy Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Pharmacy details & information')

@section('content')

{{-- ── Header Action Row ── --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size:14px">
            <i class="fas fa-building me-2 text-primary"></i>Pharmacy Profile
        </h6>
        <small class="text-muted">View and manage your pharmacy information</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pharmacy.profile.edit') }}" class="btn btn-primary btn-sm" style="font-size:12px">
            <i class="fas fa-edit me-1"></i>Edit Profile
        </a>
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-outline-secondary btn-sm" style="font-size:12px">
            <i class="fas fa-arrow-left me-1"></i>Dashboard
        </a>
    </div>
</div>

{{-- ── Status Alert ── --}}
@if(isset($pharmacy))
    @if($pharmacy->status === 'pending')
        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3" style="font-size:12px">
            <i class="fas fa-clock"></i>
            <div>
                <strong>Approval Pending</strong> — Your pharmacy profile is under review by admin. You will be notified once approved.
            </div>
        </div>
    @elseif($pharmacy->status === 'rejected')
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:12px">
            <i class="fas fa-times-circle"></i>
            <div>
                <strong>Profile Rejected</strong> — Your pharmacy profile was rejected. Please update your information and resubmit.
            </div>
        </div>
    @elseif($pharmacy->status === 'suspended')
        <div class="alert alert-dark d-flex align-items-center gap-2 py-2 mb-3" style="font-size:12px">
            <i class="fas fa-ban"></i>
            <div>
                <strong>Account Suspended</strong> — Your account has been suspended. Contact admin for more details.
            </div>
        </div>
    @elseif($pharmacy->status === 'approved')
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="font-size:12px">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Profile Approved</strong> — Your pharmacy is active and visible to patients.
            </div>
        </div>
    @endif
@endif

<div class="row g-3">

    {{-- ── Left Column ── --}}
    <div class="col-lg-4">

        {{-- Profile Image Card --}}
        <div class="card mb-3">
            <div class="card-body text-center py-4">
                @php
                    $profileImg = isset($pharmacy) && $pharmacy->profile_image
                        ? asset('storage/' . $pharmacy->profile_image)
                        : asset('images/default-doctor.png');
                @endphp
                <div class="position-relative d-inline-block mb-3">
                    <img src="{{ $profileImg }}"
                         alt="{{ $pharmacy->name ?? 'Pharmacy' }}"
                         class="rounded-circle border border-3 border-primary"
                         style="width:100px;height:100px;object-fit:cover"
                         onerror="this.src='{{ asset('images/default-doctor.png') }}'">
                    @php
                        $statusColors = ['approved'=>'success','pending'=>'warning','rejected'=>'danger','suspended'=>'dark'];
                        $sc = $statusColors[$pharmacy->status ?? 'pending'] ?? 'secondary';
                    @endphp
                    <span class="position-absolute bottom-0 end-0 badge bg-{{ $sc }} border border-white"
                          style="font-size:10px">
                        {{ ucfirst($pharmacy->status ?? 'pending') }}
                    </span>
                </div>

                <h6 class="fw-bold mb-0" style="font-size:14px">{{ $pharmacy->name ?? '—' }}</h6>
                <p class="text-muted mb-2" style="font-size:11px">{{ $pharmacy->registration_number ?? '' }}</p>

                {{-- Rating --}}
                <div class="d-flex justify-content-center align-items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($pharmacy->rating ?? 0) ? 'text-warning' : 'text-muted' }}"
                           style="font-size:12px"></i>
                    @endfor
                    <span class="text-muted ms-1" style="font-size:11px">
                        {{ number_format($pharmacy->rating ?? 0, 1) }}
                        ({{ $pharmacy->total_ratings ?? 0 }} reviews)
                    </span>
                </div>

                {{-- Image Upload Form --}}
                <form action="{{ route('pharmacy.profile.upload-image') }}" method="POST"
                      enctype="multipart/form-data" id="imageUploadForm">
                    @csrf
                    <label for="profileImageInput"
                           class="btn btn-outline-primary btn-sm w-100 mb-2" style="font-size:11px;cursor:pointer">
                        <i class="fas fa-camera me-1"></i>Change Photo
                        <input type="file" id="profileImageInput" name="profile_image"
                               accept="image/*" class="d-none"
                               onchange="document.getElementById('imageUploadForm').submit()">
                    </label>
                </form>
                @if(isset($pharmacy) && $pharmacy->profile_image)
                    <form action="{{ route('pharmacy.profile.delete-image') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                style="font-size:11px"
                                onclick="return confirm('Remove profile photo?')">
                            <i class="fas fa-trash me-1"></i>Remove Photo
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-chart-bar me-2 text-info"></i>Quick Stats</h6>
            </div>
            <div class="card-body p-0">
                @php
                    try {
                        $__totalOrders = \App\Models\PharmacyOrder::where('pharmacy_id', $pharmacy->id ?? 0)->count();
                        $__pendingOrders = \App\Models\PharmacyOrder::where('pharmacy_id', $pharmacy->id ?? 0)->where('status','pending')->count();
                        $__totalMeds = \App\Models\Medicine::where('pharmacy_id', $pharmacy->id ?? 0)->count();
                        $__revenue = \App\Models\PharmacyOrder::where('pharmacy_id', $pharmacy->id ?? 0)->where('payment_status','paid')->sum('total_amount');
                    } catch (\Exception $e) {
                        $__totalOrders = $__pendingOrders = $__totalMeds = $__revenue = 0;
                    }
                @endphp
                <ul class="list-group list-group-flush" style="font-size:12px">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span><i class="fas fa-shopping-cart text-primary me-2"></i>Total Orders</span>
                        <span class="badge bg-primary">{{ $__totalOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span><i class="fas fa-clock text-warning me-2"></i>Pending Orders</span>
                        <span class="badge bg-warning text-dark">{{ $__pendingOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span><i class="fas fa-pills text-success me-2"></i>Total Medicines</span>
                        <span class="badge bg-success">{{ $__totalMeds }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span><i class="fas fa-coins text-info me-2"></i>Total Revenue</span>
                        <span class="fw-semibold text-info" style="font-size:11px">Rs. {{ number_format($__revenue, 2) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Document --}}
        @if(isset($pharmacy) && $pharmacy->document_path)
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-file-alt me-2 text-secondary"></i>Documents</h6>
            </div>
            <div class="card-body">
                <a href="{{ asset('storage/' . $pharmacy->document_path) }}" target="_blank"
                   class="btn btn-outline-secondary btn-sm w-100" style="font-size:11px">
                    <i class="fas fa-eye me-1"></i>View Registration Document
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Right Column ── --}}
    <div class="col-lg-8">

        {{-- Basic Information --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i>Basic Information</h6>
                <a href="{{ route('pharmacy.profile.edit') }}"
                   class="btn btn-outline-primary btn-sm" style="font-size:11px">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PHARMACY NAME</label>
                        <p class="mb-0 fw-semibold" style="font-size:13px">{{ $pharmacy->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">REGISTRATION NUMBER</label>
                        <p class="mb-0 fw-semibold" style="font-size:13px">{{ $pharmacy->registration_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PHARMACIST NAME</label>
                        <p class="mb-0" style="font-size:13px">{{ $pharmacy->pharmacist_name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PHARMACIST LICENSE</label>
                        <p class="mb-0" style="font-size:13px">{{ $pharmacy->pharmacist_license ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">PHONE</label>
                        <p class="mb-0" style="font-size:13px">
                            <a href="tel:{{ $pharmacy->phone ?? '' }}" class="text-decoration-none">
                                {{ $pharmacy->phone ?? '—' }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted" style="font-size:11px;font-weight:600">EMAIL</label>
                        <p class="mb-0" style="font-size:13px">
                            <a href="mailto:{{ $pharmacy->email ?? '' }}" class="text-decoration-none">
                                {{ $pharmacy->email ?? '—' }}
                            </a>
                        </p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted" style="font-size:11px;font-weight:600">ADDRESS</label>
                        <p class="mb-0" style="font-size:13px">
                            {{ $pharmacy->address ?? '—' }}
                            @if(isset($pharmacy->city))
                                , {{ $pharmacy->city }}
                            @endif
                            @if(isset($pharmacy->province))
                                , {{ $pharmacy->province }}
                            @endif
                            @if(isset($pharmacy->postal_code))
                                — {{ $pharmacy->postal_code }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Operating Hours & Delivery --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-clock me-2 text-warning"></i>Operating Hours & Delivery</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="text-muted" style="font-size:11px;font-weight:600">OPERATING HOURS</label>
                        @if(isset($pharmacy->operating_hours) && $pharmacy->operating_hours)
                            @php
                                $hours = explode(',', $pharmacy->operating_hours);
                            @endphp
                            <div class="mt-1">
                                @foreach($hours as $hour)
                                    @if(trim($hour))
                                    <span class="badge bg-light text-dark border me-1 mb-1" style="font-size:11px;font-weight:500">
                                        <i class="fas fa-clock text-warning me-1"></i>{{ trim($hour) }}
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="mb-0 text-muted" style="font-size:13px">Not specified</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">HOME DELIVERY</label>
                        <p class="mb-0 mt-1">
                            @if(isset($pharmacy->delivery_available) && $pharmacy->delivery_available)
                                <span class="badge bg-success" style="font-size:11px">
                                    <i class="fas fa-check me-1"></i>Available
                                </span>
                            @else
                                <span class="badge bg-secondary" style="font-size:11px">
                                    <i class="fas fa-times me-1"></i>Not Available
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6><i class="fas fa-user-circle me-2 text-secondary"></i>Account Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">ACCOUNT EMAIL</label>
                        <p class="mb-0" style="font-size:13px">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">MEMBER SINCE</label>
                        <p class="mb-0" style="font-size:13px">
                            {{ isset($pharmacy->created_at) ? $pharmacy->created_at->format('d M Y') : '—' }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">LAST UPDATED</label>
                        <p class="mb-0" style="font-size:13px">
                            {{ isset($pharmacy->updated_at) ? $pharmacy->updated_at->diffForHumans() : '—' }}
                        </p>
                    </div>
                    @if(isset($pharmacy->approved_at) && $pharmacy->approved_at)
                    <div class="col-md-4">
                        <label class="text-muted" style="font-size:11px;font-weight:600">APPROVED ON</label>
                        <p class="mb-0" style="font-size:13px">
                            {{ \Carbon\Carbon::parse($pharmacy->approved_at)->format('d M Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.medicines.index') }}"
                           class="btn btn-outline-primary btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-pills fa-lg"></i>Medicines
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.orders.index') }}"
                           class="btn btn-outline-success btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-shopping-cart fa-lg"></i>Orders
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.inventory.index') }}"
                           class="btn btn-outline-warning btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-warehouse fa-lg"></i>Inventory
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.ratings.index') }}"
                           class="btn btn-outline-info btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-star fa-lg"></i>Reviews
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.reports.index') }}"
                           class="btn btn-outline-secondary btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-chart-bar fa-lg"></i>Reports
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.settings') }}"
                           class="btn btn-outline-dark btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-cog fa-lg"></i>Settings
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.patients.index') }}"
                           class="btn btn-outline-primary btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-users fa-lg"></i>Patients
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pharmacy.profile.edit') }}"
                           class="btn btn-primary btn-sm w-100 py-2 d-flex flex-column align-items-center gap-1"
                           style="font-size:11px">
                            <i class="fas fa-edit fa-lg"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
