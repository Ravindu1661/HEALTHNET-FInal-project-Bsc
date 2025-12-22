{{-- Include Header --}}
@include('partials.header')

<style>
/* Profile Page Styles */
.profile-header {
    background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%);
    padding: 7rem 0 3rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('https://images.unsplash.com/photo-1581594693702-fbdc51b2763b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
    opacity: 0.1;
    z-index: 0;
}

.profile-content {
    position: relative;
    z-index: 1;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    object-fit: cover;
    margin-bottom: 1rem;
}

.profile-name {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.profile-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.info-card h5 {
    color: #7b1fa2;
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f3e5f5;
}

.info-row {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f5f5f5;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #666;
    min-width: 140px;
    display: flex;
    align-items: center;
}

.info-label i {
    width: 20px;
    margin-right: 0.5rem;
    color: #7b1fa2;
}

.info-value {
    flex: 1;
    color: #333;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.8rem;
    margin-top: 1rem;
}

.service-badge {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    color: #7b1fa2;
    padding: 0.6rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.service-badge:hover {
    background: linear-gradient(135deg, #e1bee7 0%, #ce93d8 100%);
    transform: translateX(5px);
}

.service-badge i {
    margin-right: 0.5rem;
    font-size: 0.9rem;
}

.rating-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-radius: 10px;
    margin-top: 1rem;
}

.rating-stars {
    font-size: 1.5rem;
    color: #ffc107;
}

.rating-info {
    flex: 1;
}

.rating-number {
    font-size: 2rem;
    font-weight: 700;
    color: #7b1fa2;
}

.rating-text {
    color: #666;
    font-size: 0.9rem;
}

.verified-badge {
    background: #28a745;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-left: 1rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    min-width: 200px;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary-action {
    background: #7b1fa2;
    color: white;
}

.btn-primary-action:hover {
    background: #6a1b9a;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(123, 31, 162, 0.3);
}

.btn-secondary-action {
    background: white;
    color: #7b1fa2;
    border: 2px solid #7b1fa2;
}

.btn-secondary-action:hover {
    background: #7b1fa2;
    color: white;
    transform: translateY(-2px);
}

.btn-back {
    background: #6c757d;
    color: white;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .profile-name {
        font-size: 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-action {
        width: 100%;
    }

    .info-row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        min-width: auto;
    }
}
</style>

{{-- Profile Header --}}
<section class="profile-header">
    <div class="container profile-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <img src="{{ $laboratory->profile_image ? asset('storage/' . $laboratory->profile_image) : asset('images/default-lab.png') }}"
                     alt="{{ $laboratory->name }}"
                     class="profile-avatar"
                     onerror="this.src='{{ asset('images/default-lab.png') }}'">

                <h1 class="profile-name">
                    {{ $laboratory->name }}
                    @if($laboratory->status == 'approved')
                        <span class="verified-badge">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @endif
                </h1>

                <p class="profile-subtitle">
                    <i class="fas fa-map-marker-alt me-2"></i>{{ $laboratory->city }}, {{ $laboratory->province }}
                </p>

                {{-- Rating Section --}}
                <div class="rating-section">
                    <div class="rating-stars">
                        @php
                            $rating = $laboratory->rating ?? 0;
                            $fullStars = floor($rating);
                            $halfStar = ($rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp

                        @for($i = 0; $i < $fullStars; $i++)
                            <i class="fas fa-star"></i>
                        @endfor

                        @if($halfStar)
                            <i class="fas fa-star-half-alt"></i>
                        @endif

                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                    <div class="rating-info">
                        <div class="rating-number">{{ number_format($rating, 1) }}</div>
                        <div class="rating-text">Based on {{ $laboratory->total_ratings ?? 0 }} reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Profile Details --}}
<section class="py-5">
    <div class="container">
        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Contact Information --}}
                <div class="info-card">
                    <h5><i class="fas fa-address-card me-2"></i>Contact Information</h5>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-phone"></i> Phone
                        </div>
                        <div class="info-value">{{ $laboratory->phone ?? 'Not Available' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i> Email
                        </div>
                        <div class="info-value">{{ $laboratory->email ?? 'Not Available' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </div>
                        <div class="info-value">
                            {{ $laboratory->address }}, {{ $laboratory->city }}, {{ $laboratory->province }}
                            @if($laboratory->postal_code)
                                - {{ $laboratory->postal_code }}
                            @endif
                        </div>
                    </div>

                    @if($laboratory->operating_hours)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-clock"></i> Operating Hours
                        </div>
                        <div class="info-value">{{ $laboratory->operating_hours }}</div>
                    </div>
                    @endif
                </div>

                {{-- Services Offered --}}
                @if(!empty($services) && count($services) > 0)
                <div class="info-card">
                    <h5><i class="fas fa-flask me-2"></i>Services Offered</h5>
                    <div class="services-grid">
                        @foreach($services as $service)
                            <div class="service-badge">
                                <i class="fas fa-check-circle"></i>
                                {{ trim($service) }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Description --}}
                @if($laboratory->description)
                <div class="info-card">
                    <h5><i class="fas fa-info-circle me-2"></i>About</h5>
                    <p class="mb-0">{{ $laboratory->description }}</p>
                </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                {{-- Registration Details --}}
                <div class="info-card">
                    <h5><i class="fas fa-certificate me-2"></i>Registration Details</h5>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i> Reg. Number
                        </div>
                        <div class="info-value">
                            <span class="badge bg-info">{{ $laboratory->registration_number }}</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-check-circle"></i> Status
                        </div>
                        <div class="info-value">
                            <span class="badge bg-success">{{ ucfirst($laboratory->status) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    @auth
                        @if(auth()->user()->usertype == 'patient')
                            <button class="btn-action btn-primary-action" onclick="bookLabTest()">
                                <i class="fas fa-calendar-plus me-2"></i>Book Lab Test
                            </button>

                            <button class="btn-action btn-secondary-action" onclick="viewReports()">
                                <i class="fas fa-file-medical me-2"></i>View Reports
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-action btn-primary-action">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Book
                        </a>
                    @endauth

                    <a href="{{ route('patient.laboratories') }}" class="btn-action btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function bookLabTest() {
    Swal.fire({
        title: 'Book Lab Test',
        text: 'Lab test booking feature coming soon!',
        icon: 'info',
        confirmButtonColor: '#7b1fa2',
        confirmButtonText: 'OK'
    });
}

function viewReports() {
    Swal.fire({
        title: 'View Reports',
        text: 'Lab reports viewing feature coming soon!',
        icon: 'info',
        confirmButtonColor: '#7b1fa2',
        confirmButtonText: 'OK'
    });
}
</script>

{{-- Include Footer --}}
@include('partials.footer')
