@extends('doctor.layouts.master')

@section('title', 'My Workplaces')
@section('page-title', 'My Workplaces')

@section('content')
<div class="workplaces-container">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header with Add Button --}}
    <div class="page-header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-heading">
                    <i class="fas fa-building me-2"></i>
                    My Workplaces
                </h4>
                <p class="page-subheading">Manage your hospital and medical centre associations</p>
            </div>
            <a href="{{ route('doctor.workplaces.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Workplace
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="stat-card stat-card-total">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Total Workplaces</div>
                        <div class="stat-value">{{ $workplaces->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Approved</div>
                        <div class="stat-value">{{ $approvedCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ $pendingCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Rejected</div>
                        <div class="stat-value">{{ $rejectedCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs mb-3">
        <button class="filter-tab active" data-filter="all">
            All ({{ $workplaces->count() }})
        </button>
        <button class="filter-tab" data-filter="hospital">
            Hospitals ({{ $hospitals->count() }})
        </button>
        <button class="filter-tab" data-filter="medical_centre">
            Medical Centres ({{ $medicalCentres->count() }})
        </button>
        <button class="filter-tab" data-filter="approved">
            Approved ({{ $approvedCount }})
        </button>
        <button class="filter-tab" data-filter="pending">
            Pending ({{ $pendingCount }})
        </button>
    </div>

    {{-- Workplaces Grid --}}
    @if($workplaces->count() > 0)
        <div class="workplaces-grid">
            @foreach($workplaces as $workplace)
                @php
                    $workplaceData = null;
                    $workplaceName = 'N/A';
                    $workplaceAddress = 'N/A';
                    $workplaceCity = 'N/A';
                    $workplacePhone = 'N/A';
                    $workplaceImage = asset('images/default-hospital.png');

                    if ($workplace->workplace_type == 'hospital' && $workplace->hospital) {
                        $workplaceData = $workplace->hospital;
                        $workplaceName = $workplaceData->name;
                        $workplaceAddress = $workplaceData->address;
                        $workplaceCity = $workplaceData->city;
                        $workplacePhone = $workplaceData->phone;
                        $workplaceImage = $workplaceData->image_url;
                    } elseif ($workplace->workplace_type == 'medical_centre' && $workplace->medicalCentre) {
                        $workplaceData = $workplace->medicalCentre;
                        $workplaceName = $workplaceData->name;
                        $workplaceAddress = $workplaceData->address;
                        $workplaceCity = $workplaceData->city;
                        $workplacePhone = $workplaceData->phone;
                        $workplaceImage = $workplaceData->image_url;
                    }
                @endphp

                <div class="workplace-card"
                     data-type="{{ $workplace->workplace_type }}"
                     data-status="{{ $workplace->status }}">
                    {{-- Status Badge --}}
                    <div class="workplace-status-badge status-{{ $workplace->status }}">
                        @if($workplace->status == 'approved')
                            <i class="fas fa-check-circle"></i> Approved
                        @elseif($workplace->status == 'pending')
                            <i class="fas fa-clock"></i> Pending
                        @else
                            <i class="fas fa-times-circle"></i> Rejected
                        @endif
                    </div>

                    {{-- Workplace Image --}}
                    <div class="workplace-image">
                        <img src="{{ $workplaceImage }}" alt="{{ $workplaceName }}">
                        <div class="workplace-type-badge">
                            @if($workplace->workplace_type == 'hospital')
                                <i class="fas fa-hospital"></i> Hospital
                            @else
                                <i class="fas fa-clinic-medical"></i> Medical Centre
                            @endif
                        </div>
                    </div>

                    {{-- Workplace Details --}}
                    <div class="workplace-content">
                        <h5 class="workplace-name">{{ $workplaceName }}</h5>

                        <div class="workplace-info-item">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ ucfirst(str_replace('_', ' ', $workplace->employment_type)) }}</span>
                        </div>

                        <div class="workplace-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $workplaceCity }}</span>
                        </div>

                        <div class="workplace-info-item">
                            <i class="fas fa-map-signs"></i>
                            <span>{{ Str::limit($workplaceAddress, 35) }}</span>
                        </div>

                        <div class="workplace-info-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $workplacePhone }}</span>
                        </div>

                        @if($workplace->approved_at)
                            <div class="workplace-info-item">
                                <i class="fas fa-calendar-check"></i>
                                <span>Added: {{ $workplace->approved_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="workplace-actions">
                        @if($workplace->status == 'pending')
                            <a href="{{ route('doctor.workplaces.edit', $workplace->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('doctor.workplaces.destroy', $workplace->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Remove this workplace?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        @elseif($workplace->status == 'rejected')
                            <form action="{{ route('doctor.workplaces.destroy', $workplace->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this workplace?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-check"></i> Active
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-building"></i>
            <h4>No Workplaces Added Yet</h4>
            <p>Start by adding hospitals or medical centres where you practice</p>
            <a href="{{ route('doctor.workplaces.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Your First Workplace
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.workplaces-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Page Header */
.page-header-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
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

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stat-card-inner {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stat-card-total .stat-icon {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.stat-card-success .stat-icon {
    background: linear-gradient(135deg, #42a649, #2d7a32);
}

.stat-card-warning .stat-icon {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.stat-card-danger .stat-icon {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2969bf;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    background: white;
    border: 2px solid #e9ecef;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-tab:hover {
    border-color: #2969bf;
    color: #2969bf;
}

.filter-tab.active {
    background: #2969bf;
    border-color: #2969bf;
    color: white;
}

/* Workplaces Grid */
.workplaces-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.workplace-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
}

.workplace-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.workplace-status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.35rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.workplace-image {
    height: 180px;
    overflow: hidden;
    position: relative;
    background: #f0f0f0;
}

.workplace-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.workplace-card:hover .workplace-image img {
    transform: scale(1.1);
}

.workplace-type-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(41, 105, 191, 0.9);
    color: white;
    padding: 0.35rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.workplace-content {
    padding: 1.2rem;
}

.workplace-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2969bf;
    margin-bottom: 0.8rem;
    line-height: 1.3;
}

.workplace-info-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 0.6rem;
    font-size: 0.85rem;
    color: #555;
}

.workplace-info-item i {
    width: 18px;
    color: #2969bf;
    font-size: 0.8rem;
}

.workplace-actions {
    padding: 0 1.2rem 1.2rem;
    display: flex;
    gap: 0.5rem;
}

.workplace-actions .btn {
    flex: 1;
    font-size: 0.8rem;
    padding: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .workplaces-grid {
        grid-template-columns: 1fr;
    }

    .filter-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const workplaceCards = document.querySelectorAll('.workplace-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const filter = this.dataset.filter;

            workplaceCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    const type = card.dataset.type;
                    const status = card.dataset.status;

                    if (filter === type || filter === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>
@endpush
