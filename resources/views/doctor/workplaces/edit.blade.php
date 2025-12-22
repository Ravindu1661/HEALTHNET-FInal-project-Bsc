@extends('doctor.layouts.master')

@section('title', 'Edit Workplace')
@section('page-title', 'Edit Workplace')

@section('content')
<div class="edit-workplace-container">
    {{-- Back Button --}}
    <div class="mb-3">
        <a href="{{ route('doctor.workplaces.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Workplaces
        </a>
    </div>

    {{-- Page Header --}}
    <div class="page-header-card mb-4">
        <h4 class="page-heading">
            <i class="fas fa-edit me-2"></i>
            Edit Workplace
        </h4>
        <p class="page-subheading">Update your workplace association details</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Form Section --}}
        <div class="col-lg-8">
            <div class="form-card">
                @php
                    $workplaceData = null;
                    $workplaceName = 'N/A';
                    $workplaceAddress = 'N/A';
                    $workplaceCity = 'N/A';
                    $workplaceImage = asset('images/default-hospital.png');

                    if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                        $workplaceData = $workplace->hospital;
                        $workplaceName = $workplaceData->name;
                        $workplaceAddress = $workplaceData->address;
                        $workplaceCity = $workplaceData->city;
                        $workplaceImage = $workplaceData->image_url;
                    } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                        $workplaceData = $workplace->medicalCentre;
                        $workplaceName = $workplaceData->name;
                        $workplaceAddress = $workplaceData->address;
                        $workplaceCity = $workplaceData->city;
                        $workplaceImage = $workplaceData->image_url;
                    }
                @endphp

                {{-- Current Workplace Info --}}
                <div class="current-workplace-card">
                    <h5 class="mb-3">
                        <i class="fas fa-building me-2 text-primary"></i>
                        Current Workplace
                    </h5>
                    <div class="workplace-preview">
                        <div class="workplace-preview-image">
                            <img src="{{ $workplaceImage }}" alt="{{ $workplaceName }}">
                        </div>
                        <div class="workplace-preview-details">
                            <h6>{{ $workplaceName }}</h6>
                            <p>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $workplace->workplace_type)) }}
                                </span>
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $workplaceCity }}
                            </p>
                            <p class="text-muted small mb-0">
                                {{ $workplaceAddress }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Edit Form --}}
                <form action="{{ route('doctor.workplaces.update', $workplace->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h5 class="form-section-title">
                            <i class="fas fa-briefcase me-2"></i>
                            Update Employment Type
                        </h5>

                        <div class="employment-type-selector">
                            <label class="employment-option" for="emp-permanent">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-permanent"
                                       value="permanent"
                                       {{ $workplace->employment_type == 'permanent' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Permanent</span>
                                    <small class="text-muted">Full-time employment</small>
                                </div>
                            </label>

                            <label class="employment-option" for="emp-temporary">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-temporary"
                                       value="temporary"
                                       {{ $workplace->employment_type == 'temporary' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Temporary</span>
                                    <small class="text-muted">Fixed-term contract</small>
                                </div>
                            </label>

                            <label class="employment-option" for="emp-visiting">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-visiting"
                                       value="visiting"
                                       {{ $workplace->employment_type == 'visiting' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-user-clock"></i>
                                    <span>Visiting</span>
                                    <small class="text-muted">Consultant/visiting doctor</small>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="form-actions">
                        <a href="{{ route('doctor.workplaces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Workplace
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Sidebar --}}
        <div class="col-lg-4">
            {{-- Status Info --}}
            <div class="status-card">
                <h5>
                    <i class="fas fa-info-circle me-2"></i>
                    Status Information
                </h5>
                <div class="status-badge-large status-{{ $workplace->status }}">
                    @if($workplace->status == 'approved')
                        <i class="fas fa-check-circle"></i>
                        <span>Approved</span>
                    @elseif($workplace->status == 'pending')
                        <i class="fas fa-clock"></i>
                        <span>Pending Approval</span>
                    @else
                        <i class="fas fa-times-circle"></i>
                        <span>Rejected</span>
                    @endif
                </div>

                <div class="status-info mt-3">
                    @if($workplace->status == 'pending')
                        <p class="text-muted small">
                            Your workplace association is pending admin approval. You can still edit the employment type.
                        </p>
                    @endif

                    <p class="text-muted small mb-1">
                        <strong>Added:</strong> {{ $workplace->created_at->format('M d, Y') }}
                    </p>

                    @if($workplace->approved_at)
                        <p class="text-muted small mb-0">
                            <strong>Approved:</strong> {{ $workplace->approved_at->format('M d, Y') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Help Card --}}
            <div class="help-card">
                <h5><i class="fas fa-question-circle me-2"></i>Need Help?</h5>
                <p class="small">You can only edit pending workplace associations.</p>
                <p class="small mb-0">For approved workplaces, please contact admin support to make changes.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.edit-workplace-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Page Header */
.page-header-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.page-heading {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.3rem;
}

.page-subheading {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Current Workplace Card */
.current-workplace-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 2px solid #e9ecef;
}

.workplace-preview {
    display: flex;
    gap: 1.2rem;
    align-items: center;
}

.workplace-preview-image {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid #2969bf;
}

.workplace-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.workplace-preview-details h6 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.5rem;
}

/* Form Section */
.form-section {
    margin-bottom: 2rem;
}

.form-section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 1.5rem;
}

/* Employment Type Selector */
.employment-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.employment-option {
    cursor: pointer;
}

.employment-option input[type="radio"] {
    display: none;
}

.employment-option-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.6rem;
    padding: 1.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: white;
}

.employment-option:hover .employment-option-content {
    border-color: #2969bf;
    box-shadow: 0 3px 12px rgba(41, 105, 191, 0.1);
}

.employment-option input[type="radio"]:checked ~ .employment-option-content {
    border-color: #2969bf;
    background: rgba(41, 105, 191, 0.05);
}

.employment-option-content i {
    font-size: 2.2rem;
    color: #6c757d;
}

.employment-option input[type="radio"]:checked ~ .employment-option-content i {
    color: #2969bf;
}

.employment-option-content span {
    font-size: 1rem;
    font-weight: 700;
    color: #495057;
}

.employment-option-content small {
    font-size: 0.75rem;
    text-align: center;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 2px solid #f0f0f0;
}

.form-actions .btn {
    padding: 0.7rem 2rem;
    font-weight: 600;
}

/* Status Card */
.status-card,
.help-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

.status-card h5,
.help-card h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 1rem;
}

.status-badge-large {
    padding: 1rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.status-badge-large i {
    font-size: 1.5rem;
}

.status-badge-large.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-badge-large.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge-large.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.status-info {
    padding-top: 1rem;
    border-top: 2px solid #f0f0f0;
}

.help-card p {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .employment-type-selector {
        grid-template-columns: 1fr;
    }

    .workplace-preview {
        flex-direction: column;
        text-align: center;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
    }
}
</style>
@endpush
@endsection
