

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="dashboard-container">
    {{-- Welcome Banner --}}
    <div class="welcome-banner">
        <div class="welcome-content">
            <div class="welcome-text">
                <h3>Welcome back, Dr. {{ auth()->user()->doctor->first_name }}! 👋</h3>
                <p>Here's what's happening with your practice today</p>
            </div>
            <div class="welcome-date">
                <i class="fas fa-calendar-alt"></i>
                {{ date('l, F d, Y') }}
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Today's Appointments</div>
                        <div class="stat-value" id="todayAppointments">0</div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 12% from yesterday
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #42a649, #2d7a32);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Patients</div>
                        <div class="stat-value" id="totalPatients">0</div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 8% this month
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">This Month Earnings</div>
                        <div class="stat-value" id="monthlyEarnings">Rs. 0</div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 15% increase
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Average Rating</div>
                        <div class="stat-value" id="avgRating">0.0</div>
                        <div class="stat-change text-warning">
                            <i class="fas fa-star"></i> Based on reviews
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Today's Appointments --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Appointments</h6>
                    <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="appointment-timeline" id="todayAppointmentsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Appointment Overview</h6>
                </div>
                <div class="card-body">
                    <div class="quick-stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-badge bg-warning"></div>
                                <span class="text-sm">Pending</span>
                            </div>
                            <span class="text-sm fw-bold" id="pendingCount">0</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" id="pendingProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="quick-stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-badge bg-success"></div>
                                <span class="text-sm">Confirmed</span>
                            </div>
                            <span class="text-sm fw-bold" id="confirmedCount">0</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" id="confirmedProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="quick-stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-badge bg-primary"></div>
                                <span class="text-sm">Completed</span>
                            </div>
                            <span class="text-sm fw-bold" id="completedCount">0</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" id="completedProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="quick-stat-item mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-badge bg-danger"></div>
                                <span class="text-sm">Cancelled</span>
                            </div>
                            <span class="text-sm fw-bold" id="cancelledCount">0</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-danger" id="cancelledProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.schedule.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-clock me-2"></i>Manage Schedule
                        </a>
                        <a href="{{ route('doctor.patients.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-user-friends me-2"></i>View Patients
                        </a>
                        <a href="{{ route('doctor.workplaces.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-building me-2"></i>Workplaces
                        </a>
                        <a href="{{ route('doctor.earnings.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-chart-line me-2"></i>Earnings Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Patients --}}
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Recent Patients</h6>
                    <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recentPatientsTable">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Contact</th>
                                    <th>Last Visit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-star me-2"></i>Recent Reviews</h6>
                    <a href="{{ route('doctor.reviews.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body" id="recentReviewsList">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: white;
        box-shadow: 0 4px 15px rgba(15, 76, 117, 0.2);
    }

    .welcome-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .welcome-text h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 0.3rem 0;
    }

    .welcome-text p {
        font-size: 0.85rem;
        margin: 0;
        opacity: 0.9;
    }

    .welcome-date {
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .welcome-date i {
        margin-right: 0.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.3rem;
    }

    .stat-change {
        font-size: 0.7rem;
        font-weight: 500;
    }

    .stat-change i {
        font-size: 0.65rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .appointment-timeline {
        max-height: 400px;
        overflow-y: auto;
    }

    .appointment-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-left: 3px solid #e9ecef;
        margin-bottom: 0.8rem;
        transition: all 0.3s ease;
        border-radius: 0 8px 8px 0;
    }

    .appointment-item:hover {
        background: #f8f9fa;
        border-left-color: var(--primary);
    }

    .appointment-time {
        min-width: 80px;
    }

    .appointment-time .time {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
    }

    .appointment-time .duration {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .appointment-details h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 0.3rem 0;
    }

    .appointment-details p {
        font-size: 0.75rem;
        color: #6c757d;
        margin: 0;
    }

    .quick-stat-item {
        margin-bottom: 1.2rem;
    }

    .status-badge {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .review-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.5rem;
    }

    .review-header h6 {
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0;
    }

    .review-rating {
        color: #f39c12;
        font-size: 0.75rem;
    }

    .review-text {
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .welcome-text h3 {
            font-size: 1.1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        loadDashboardData();
        loadTodayAppointments();
        loadRecentPatients();
        loadRecentReviews();
    });

    function loadDashboardData() {
        $.ajax({
            url: '/doctor/dashboard/stats',
            method: 'GET',
            success: function(data) {
                $('#todayAppointments').text(data.today_appointments || 0);
                $('#totalPatients').text(data.total_patients || 0);
                $('#monthlyEarnings').text('Rs. ' + (data.monthly_earnings || 0).toLocaleString());
                $('#avgRating').text((data.avg_rating || 0).toFixed(1));

                // Update appointment overview
                const total = data.appointment_stats.total || 1;
                $('#pendingCount').text(data.appointment_stats.pending || 0);
                $('#confirmedCount').text(data.appointment_stats.confirmed || 0);
                $('#completedCount').text(data.appointment_stats.completed || 0);
                $('#cancelledCount').text(data.appointment_stats.cancelled || 0);

                $('#pendingProgress').css('width', ((data.appointment_stats.pending / total) * 100) + '%');
                $('#confirmedProgress').css('width', ((data.appointment_stats.confirmed / total) * 100) + '%');
                $('#completedProgress').css('width', ((data.appointment_stats.completed / total) * 100) + '%');
                $('#cancelledProgress').css('width', ((data.appointment_stats.cancelled / total) * 100) + '%');
            },
            error: function() {
                console.error('Failed to load dashboard stats');
            }
        });
    }

    function loadTodayAppointments() {
        $.ajax({
            url: '/doctor/appointments/today',
            method: 'GET',
            success: function(data) {
                let html = '';
                if(data.appointments && data.appointments.length > 0) {
                    data.appointments.forEach(function(apt) {
                        const statusColors = {
                            'pending': 'warning',
                            'confirmed': 'success',
                            'completed': 'primary',
                            'cancelled': 'danger'
                        };
                        html += `
                            <div class="appointment-item">
                                <div class="appointment-time">
                                    <div class="time">${apt.time}</div>
                                    <div class="duration">${apt.duration} min</div>
                                </div>
                                <div class="appointment-details flex-grow-1">
                                    <h6>${apt.patient_name}</h6>
                                    <p><i class="fas fa-map-marker-alt me-1"></i>${apt.location}</p>
                                </div>
                                <div>
                                    <span class="badge bg-${statusColors[apt.status]}">${apt.status}</span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center py-4 text-muted"><i class="fas fa-calendar-times fa-2x mb-2"></i><p>No appointments scheduled for today</p></div>';
                }
                $('#todayAppointmentsList').html(html);
            }
        });
    }

    function loadRecentPatients() {
        $.ajax({
            url: '/doctor/patients/recent',
            method: 'GET',
            success: function(data) {
                let html = '';
                if(data.patients && data.patients.length > 0) {
                    data.patients.forEach(function(patient) {
                        html += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="${patient.avatar}" alt="" class="rounded-circle" width="32" height="32">
                                        <span class="fw-500">${patient.name}</span>
                                    </div>
                                </td>
                                <td class="text-sm">${patient.phone}</td>
                                <td class="text-sm">${patient.last_visit}</td>
                                <td>
                                    <a href="/doctor/patients/${patient.id}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center py-4 text-muted">No patients found</td></tr>';
                }
                $('#recentPatientsTable tbody').html(html);
            }
        });
    }

    function loadRecentReviews() {
        $.ajax({
            url: '/doctor/reviews/recent',
            method: 'GET',
            success: function(data) {
                let html = '';
                if(data.reviews && data.reviews.length > 0) {
                    data.reviews.forEach(function(review) {
                        let stars = '';
                        for(let i = 0; i < 5; i++) {
                            stars += i < review.rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        html += `
                            <div class="review-item">
                                <div class="review-header">
                                    <h6>${review.patient_name}</h6>
                                    <div class="review-rating">${stars}</div>
                                </div>
                                <p class="review-text">${review.comment}</p>
                                <small class="text-muted">${review.date}</small>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center py-4 text-muted"><i class="fas fa-star-half-alt fa-2x mb-2"></i><p>No reviews yet</p></div>';
                }
                $('#recentReviewsList').html(html);
            }
        });
    }
</script>
@endpush
