{{-- Include Header --}}
@include('partials.header')

<style>
/* Profile Page Styles - Pharmacy Theme */
.profile-header {
    background: linear-gradient(135deg, #00796b 0%, #00897b 100%);
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
    background: url('https://images.unsplash.com/photo-1576602976047-174e57a47881?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
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
    color: #00796b;
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e0f2f1;
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
    color: #00796b;
}

.info-value {
    flex: 1;
    color: #333;
}

.delivery-badge {
    background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
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
    color: #00796b;
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

.pharmacist-card {
    background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
    padding: 1.2rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.pharmacist-card h6 {
    color: #00796b;
    font-weight: 700;
    margin-bottom: 0.5rem;
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
    background: #00796b;
    color: white;
}

.btn-primary-action:hover {
    background: #00695c;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 121, 107, 0.3);
}

.btn-secondary-action {
    background: white;
    color: #00796b;
    border: 2px solid #00796b;
}

.btn-secondary-action:hover {
    background: #00796b;
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
                <img src="{{ $pharmacy->profile_image ? asset('storage/' . $pharmacy->profile_image) : asset('images/default-pharmacy.png') }}"
                     alt="{{ $pharmacy->name }}"
                     class="profile-avatar"
                     onerror="this.src='{{ asset('images/default-pharmacy.png') }}'">

                <h1 class="profile-name">
                    {{ $pharmacy->name }}
                    @if($pharmacy->status == 'approved')
                        <span class="verified-badge">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @endif
                </h1>

                <p class="profile-subtitle">
                    <i class="fas fa-map-marker-alt me-2"></i>{{ $pharmacy->city }}, {{ $pharmacy->province }}
                </p>

                @if($pharmacy->delivery_available)
                    <div class="delivery-badge">
                        <i class="fas fa-truck"></i> Home Delivery Available
                    </div>
                @endif

                {{-- Rating Section --}}
                <div class="rating-section">
                    <div class="rating-stars">
                        @php
                            $rating = $pharmacy->rating ?? 0;
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
                        <div class="rating-text">Based on {{ $pharmacy->total_ratings ?? 0 }} reviews</div>
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
                        <div class="info-value">{{ $pharmacy->phone ?? 'Not Available' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i> Email
                        </div>
                        <div class="info-value">{{ $pharmacy->email ?? 'Not Available' }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> Address
                        </div>
                        <div class="info-value">
                            {{ $pharmacy->address }}, {{ $pharmacy->city }}, {{ $pharmacy->province }}
                            @if($pharmacy->postal_code)
                                - {{ $pharmacy->postal_code }}
                            @endif
                        </div>
                    </div>

                    @if($pharmacy->operating_hours)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-clock"></i> Operating Hours
                        </div>
                        <div class="info-value">{{ $pharmacy->operating_hours }}</div>
                    </div>
                    @endif
                </div>

                {{-- Pharmacist Information --}}
                @if($pharmacy->pharmacist_name)
                <div class="info-card">
                    <h5><i class="fas fa-user-md me-2"></i>Pharmacist Information</h5>
                    <div class="pharmacist-card">
                        <h6>{{ $pharmacy->pharmacist_name }}</h6>
                        @if($pharmacy->pharmacist_license)
                            <p class="mb-0">
                                <i class="fas fa-id-badge me-2"></i>
                                License No: <strong>{{ $pharmacy->pharmacist_license }}</strong>
                            </p>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Description --}}
                @if($pharmacy->description)
                <div class="info-card">
                    <h5><i class="fas fa-info-circle me-2"></i>About</h5>
                    <p class="mb-0">{{ $pharmacy->description }}</p>
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
                            <span class="badge bg-info">{{ $pharmacy->registration_number }}</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-check-circle"></i> Status
                        </div>
                        <div class="info-value">
                            <span class="badge bg-success">{{ ucfirst($pharmacy->status) }}</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-truck"></i> Delivery
                        </div>
                        <div class="info-value">
                            @if($pharmacy->delivery_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Not Available</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    @auth
                        @if(auth()->user()->usertype == 'patient')
                            <button class="btn-action btn-primary-action" onclick="orderMedicine()">
                                <i class="fas fa-pills me-2"></i>Order Medicine
                            </button>

                            <button class="btn-action btn-secondary-action" onclick="uploadPrescription()">
                                <i class="fas fa-prescription me-2"></i>Upload Prescription
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-action btn-primary-action">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Order
                        </a>
                    @endauth

                    <a href="{{ route('patient.pharmacies') }}" class="btn-action btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function orderMedicine() {
    Swal.fire({
        title: 'Order Medicine',
        text: 'Medicine ordering feature coming soon!',
        icon: 'info',
        confirmButtonColor: '#00796b',
        confirmButtonText: 'OK'
    });
}

function uploadPrescription() {
    Swal.fire({
        title: 'Upload Prescription',
        text: 'Prescription upload feature coming soon!',
        icon: 'info',
        confirmButtonColor: '#00796b',
        confirmButtonText: 'OK'
    });
}
</script>

{{-- Include Footer --}}
@include('partials.footer')
