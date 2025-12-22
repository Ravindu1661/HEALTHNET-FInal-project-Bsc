@extends('doctor.layouts.master')

@section('title', 'Add Workplace')
@section('page-title', 'Add New Workplace')

@section('content')
<div class="create-workplace-container">
    {{-- Back Button --}}
    <div class="mb-3">
        <a href="{{ route('doctor.workplaces.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Workplaces
        </a>
    </div>

    {{-- Page Header --}}
    <div class="page-header-card mb-4">
        <h4 class="page-heading">
            <i class="fas fa-plus-circle me-2"></i>
            Add New Workplace
        </h4>
        <p class="page-subheading">Associate yourself with a hospital or medical centre</p>
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
                <form action="{{ route('doctor.workplaces.store') }}" method="POST" id="workplaceForm">
                    @csrf

                    {{-- Step 1: Select Workplace Type --}}
                    <div class="form-section">
                        <h5 class="form-section-title">
                            <span class="step-number">1</span>
                            Select Workplace Type
                        </h5>

                        <div class="workplace-type-selector">
                            <label class="type-option" for="type-hospital">
                                <input type="radio"
                                       name="workplace_type"
                                       id="type-hospital"
                                       value="hospital"
                                       {{ old('workplace_type') == 'hospital' ? 'checked' : '' }}
                                       required>
                                <div class="type-option-content">
                                    <div class="type-icon">
                                        <i class="fas fa-hospital"></i>
                                    </div>
                                    <div class="type-details">
                                        <h6>Hospital</h6>
                                        <p>Large healthcare facility with multiple departments</p>
                                    </div>
                                </div>
                            </label>

                            <label class="type-option" for="type-medical-centre">
                                <input type="radio"
                                       name="workplace_type"
                                       id="type-medical-centre"
                                       value="medical_centre"
                                       {{ old('workplace_type') == 'medical_centre' ? 'checked' : '' }}
                                       required>
                                <div class="type-option-content">
                                    <div class="type-icon">
                                        <i class="fas fa-clinic-medical"></i>
                                    </div>
                                    <div class="type-details">
                                        <h6>Medical Centre</h6>
                                        <p>Specialized clinic or medical center</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Step 2: Select Specific Workplace --}}
                    <div class="form-section">
                        <h5 class="form-section-title">
                            <span class="step-number">2</span>
                            Select Workplace
                        </h5>

                        {{-- Hospitals List --}}
                        <div id="hospitals-section" class="workplace-selection" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-hospital me-2"></i>Select Hospital *
                                </label>
                                <input type="text"
                                       id="hospital-search"
                                       class="form-control mb-3"
                                       placeholder="Search hospitals...">

                                <div class="workplace-list" id="hospital-list">
                                    @if($hospitals->count() > 0)
                                        @foreach($hospitals as $hospital)
                                            <label class="workplace-item">
                                                <input type="radio"
                                                       name="workplace_id"
                                                       value="{{ $hospital->id }}"
                                                       data-name="{{ $hospital->name }}"
                                                       {{ old('workplace_id') == $hospital->id ? 'checked' : '' }}>
                                                <div class="workplace-item-content">
                                                    <div class="workplace-item-icon">
                                                        <i class="fas fa-hospital"></i>
                                                    </div>
                                                    <div class="workplace-item-details">
                                                        <h6>{{ $hospital->name }}</h6>
                                                        <p>
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            {{ $hospital->city }}
                                                            @if($hospital->type)
                                                                <span class="badge bg-info ms-2">{{ ucfirst($hospital->type) }}</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-muted small mb-0">{{ Str::limit($hospital->address, 60) }}</p>
                                                    </div>
                                                    <div class="workplace-item-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-hospital fa-2x mb-2"></i>
                                            <p>No hospitals available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Medical Centres List --}}
                        <div id="medical-centres-section" class="workplace-selection" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clinic-medical me-2"></i>Select Medical Centre *
                                </label>
                                <input type="text"
                                       id="centre-search"
                                       class="form-control mb-3"
                                       placeholder="Search medical centres...">

                                <div class="workplace-list" id="centre-list">
                                    @if($medicalCentres->count() > 0)
                                        @foreach($medicalCentres as $centre)
                                            <label class="workplace-item">
                                                <input type="radio"
                                                       name="workplace_id"
                                                       value="{{ $centre->id }}"
                                                       data-name="{{ $centre->name }}"
                                                       {{ old('workplace_id') == $centre->id ? 'checked' : '' }}>
                                                <div class="workplace-item-content">
                                                    <div class="workplace-item-icon">
                                                        <i class="fas fa-clinic-medical"></i>
                                                    </div>
                                                    <div class="workplace-item-details">
                                                        <h6>{{ $centre->name }}</h6>
                                                        <p>
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            {{ $centre->city }}
                                                        </p>
                                                        <p class="text-muted small mb-0">{{ Str::limit($centre->address, 60) }}</p>
                                                    </div>
                                                    <div class="workplace-item-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-clinic-medical fa-2x mb-2"></i>
                                            <p>No medical centres available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Employment Type --}}
                    <div class="form-section">
                        <h5 class="form-section-title">
                            <span class="step-number">3</span>
                            Employment Type
                        </h5>

                        <div class="employment-type-selector">
                            <label class="employment-option" for="emp-permanent">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-permanent"
                                       value="permanent"
                                       {{ old('employment_type') == 'permanent' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Permanent</span>
                                </div>
                            </label>

                            <label class="employment-option" for="emp-temporary">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-temporary"
                                       value="temporary"
                                       {{ old('employment_type') == 'temporary' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Temporary</span>
                                </div>
                            </label>

                            <label class="employment-option" for="emp-visiting">
                                <input type="radio"
                                       name="employment_type"
                                       id="emp-visiting"
                                       value="visiting"
                                       {{ old('employment_type') == 'visiting' ? 'checked' : '' }}
                                       required>
                                <div class="employment-option-content">
                                    <i class="fas fa-user-clock"></i>
                                    <span>Visiting</span>
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
                            <i class="fas fa-check me-2"></i>Add Workplace
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Sidebar --}}
        <div class="col-lg-4">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h5>Important Information</h5>
                <ul class="info-list">
                    <li>
                        <i class="fas fa-check-circle text-success"></i>
                        Your request will be sent for admin approval
                    </li>
                    <li>
                        <i class="fas fa-clock text-warning"></i>
                        Approval usually takes 1-2 business days
                    </li>
                    <li>
                        <i class="fas fa-bell text-info"></i>
                        You'll be notified once approved
                    </li>
                    <li>
                        <i class="fas fa-shield-alt text-primary"></i>
                        Only approved workplaces are visible to patients
                    </li>
                </ul>
            </div>

            <div class="help-card">
                <h5><i class="fas fa-question-circle me-2"></i>Need Help?</h5>
                <p>If you can't find your workplace in the list:</p>
                <ul>
                    <li>Contact the hospital/medical centre to register on HealthNet</li>
                    <li>Or contact admin support for assistance</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.create-workplace-container {
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

.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 700;
}

/* Workplace Type Selector */
.workplace-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.type-option {
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    background: white;
}

.type-option:hover {
    border-color: #2969bf;
    box-shadow: 0 4px 15px rgba(41, 105, 191, 0.1);
}

.type-option input[type="radio"] {
    display: none;
}

.type-option input[type="radio"]:checked + .type-option-content {
    border-color: #2969bf;
}

.type-option input[type="radio"]:checked ~ .type-option-content .type-icon {
    background: linear-gradient(135deg, #2969bf, #1a4a8a);
}

.type-option-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.type-icon {
    width: 60px;
    height: 60px;
    background: #f0f0f0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.type-option input[type="radio"]:checked ~ .type-option-content {
    border-color: #2969bf;
}

.type-option input[type="radio"]:checked ~ .type-option-content .type-icon {
    background: linear-gradient(135deg, #2969bf, #1a4a8a);
    color: white;
}

.type-details h6 {
    font-size: 1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.3rem;
}

.type-details p {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0;
}

/* Workplace List */
.workplace-list {
    max-height: 450px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.workplace-list::-webkit-scrollbar {
    width: 6px;
}

.workplace-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.workplace-list::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.workplace-item {
    display: block;
    cursor: pointer;
    margin-bottom: 0.8rem;
}

.workplace-item input[type="radio"] {
    display: none;
}

.workplace-item-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: white;
}

.workplace-item:hover .workplace-item-content {
    border-color: #2969bf;
    box-shadow: 0 3px 12px rgba(41, 105, 191, 0.1);
}

.workplace-item input[type="radio"]:checked ~ .workplace-item-content {
    border-color: #2969bf;
    background: rgba(41, 105, 191, 0.03);
}

.workplace-item-icon {
    width: 45px;
    height: 45px;
    background: #f0f0f0;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #6c757d;
    flex-shrink: 0;
}

.workplace-item input[type="radio"]:checked ~ .workplace-item-content .workplace-item-icon {
    background: linear-gradient(135deg, #2969bf, #1a4a8a);
    color: white;
}

.workplace-item-details {
    flex: 1;
}

.workplace-item-details h6 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.3rem;
}

.workplace-item-details p {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.2rem;
}

.workplace-item-check {
    font-size: 1.5rem;
    color: #e9ecef;
    transition: all 0.3s ease;
}

.workplace-item input[type="radio"]:checked ~ .workplace-item-content .workplace-item-check {
    color: #42a649;
}

/* Employment Type Selector */
.employment-type-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
    gap: 0.8rem;
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
    font-size: 2rem;
    color: #6c757d;
}

.employment-option input[type="radio"]:checked ~ .employment-option-content i {
    color: #2969bf;
}

.employment-option-content span {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
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

/* Info Cards */
.info-card,
.help-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

.info-card .info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #42a649, #2d7a32);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
}

.info-card h5,
.help-card h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 1rem;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    display: flex;
    align-items: start;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
    font-size: 0.85rem;
    color: #495057;
    line-height: 1.5;
}

.info-list li i {
    font-size: 1rem;
    margin-top: 0.1rem;
}

.help-card p {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.8rem;
}

.help-card ul {
    font-size: 0.85rem;
    color: #495057;
    padding-left: 1.2rem;
}

.help-card ul li {
    margin-bottom: 0.5rem;
}

/* Form Elements */
.form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    padding: 0.7rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: #2969bf;
    box-shadow: 0 0 0 3px rgba(41, 105, 191, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .workplace-type-selector,
    .employment-type-selector {
        grid-template-columns: 1fr;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="workplace_type"]');
    const hospitalsSection = document.getElementById('hospitals-section');
    const centresSection = document.getElementById('medical-centres-section');
    const hospitalSearch = document.getElementById('hospital-search');
    const centreSearch = document.getElementById('centre-search');

    // Show/hide sections based on workplace type
    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'hospital') {
                hospitalsSection.style.display = 'block';
                centresSection.style.display = 'none';
                // Clear medical centre selection
                document.querySelectorAll('#medical-centres-section input[type="radio"]').forEach(r => r.checked = false);
            } else {
                hospitalsSection.style.display = 'none';
                centresSection.style.display = 'block';
                // Clear hospital selection
                document.querySelectorAll('#hospitals-section input[type="radio"]').forEach(r => r.checked = false);
            }
        });

        // Trigger change on page load if selected
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });

    // Search functionality for hospitals
    if (hospitalSearch) {
        hospitalSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('#hospital-list .workplace-item');

            items.forEach(item => {
                const name = item.querySelector('input').dataset.name.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Search functionality for medical centres
    if (centreSearch) {
        centreSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('#centre-list .workplace-item');

            items.forEach(item => {
                const name = item.querySelector('input').dataset.name.toLowerCase();
                if (name.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Form validation
    document.getElementById('workplaceForm').addEventListener('submit', function(e) {
        const workplaceType = document.querySelector('input[name="workplace_type"]:checked');
        const workplaceId = document.querySelector('input[name="workplace_id"]:checked');
        const employmentType = document.querySelector('input[name="employment_type"]:checked');

        if (!workplaceType || !workplaceId || !employmentType) {
            e.preventDefault();
            alert('Please complete all steps before submitting.');
            return false;
        }
    });
});
</script>
@endpush
