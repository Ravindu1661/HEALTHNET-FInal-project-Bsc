@extends('admin.layouts.master')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p>Total Users</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> {{ $stats['users_change'] }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_doctors'] }}</h3>
                    <p>Total Doctors</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> {{ $stats['doctors_change'] }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_hospitals'] }}</h3>
                    <p>Hospitals</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> {{ $stats['hospitals_change'] }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_appointments'] }}</h3>
                    <p>Appointments</p>
                    <span class="stat-change negative">
                        <i class="fas fa-arrow-down"></i> {{ $stats['appointments_change'] }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_laboratories'] }}</h3>
                    <p>Laboratories</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-secondary">
                <div class="stat-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_pharmacies'] }}</h3>
                    <p>Pharmacies</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-purple">
                <div class="stat-icon">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_medical_centres'] }}</h3>
                    <p>Medical Centres</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-teal">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <h3>LKR {{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row g-3 mb-4">
        <!-- Order Statistics Chart -->
        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Order Statistics</h6>
                    <button class="btn-refresh"><i class="fas fa-sync-alt"></i></button>
                </div>
                <div class="card-body">
                    <canvas id="orderChart"></canvas>
                    <div class="chart-legend mt-3">
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #4285F4;"></span>
                            <span>Direct ({{ $chartData['direct'] }}%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #34A853;"></span>
                            <span>Social ({{ $chartData['social'] }}%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #FBBC05;"></span>
                            <span>Referral ({{ $chartData['referral'] }}%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background: #EA4335;"></span>
                            <span>Marketing ({{ $chartData['marketing'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Statistics -->
        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Sales Statistics</h6>
                    <button class="btn-refresh"><i class="fas fa-sync-alt"></i></button>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                    <div class="sales-stats mt-3">
                        <div class="stat-item">
                            <div class="stat-label">Revenue</div>
                            <div class="stat-value">LKR {{ number_format($salesStats['revenue']) }}</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Expenses</span>
                            <span class="stat-value">LKR {{ number_format($salesStats['expenses']) }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Payments</span>
                            <span class="stat-value">LKR {{ number_format($salesStats['payments']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Total Revenue</h6>
                    <button class="btn-refresh"><i class="fas fa-sync-alt"></i></button>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                    <div class="revenue-totals mt-3">
                        <div class="total-item">
                            <span class="legend-dot" style="background: #4285F4;"></span>
                            <span>Total Expenses: LKR {{ number_format($revenueData['expenses']) }}</span>
                        </div>
                        <div class="total-item">
                            <span class="legend-dot" style="background: #34A853;"></span>
                            <span>Total Earnings: LKR {{ number_format($revenueData['earnings']) }}</span>
                        </div>
                        <div class="total-item revenue-total">
                            <span class="legend-dot" style="background: #EA4335;"></span>
                            <span>Revenue: LKR {{ number_format($revenueData['revenue']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Pending Approvals -->
    <div class="row g-3 mb-4">
        <!-- Pending Approvals -->
        <div class="col-xl-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Pending Approvals</h6>
                    <a href="{{ route('admin.approvals') }}" class="btn-view-all">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Registration</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingApprovals as $approval)
                                <tr>
                                    <td><span class="badge badge-{{ $approval->type }}">{{ ucfirst($approval->type) }}</span></td>
                                    <td>{{ $approval->name }}</td>
                                    <td>{{ $approval->registration_number }}</td>
                                    <td>{{ $approval->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn-action btn-approve" onclick="approveRequest({{ $approval->id }}, '{{ $approval->type }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action btn-reject" onclick="rejectRequest({{ $approval->id }}, '{{ $approval->type }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button class="btn-action btn-view" onclick="viewDetails({{ $approval->id }}, '{{ $approval->type }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No pending approvals</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-xl-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Recent Users</h6>
                    <a href="{{ route('admin.users.index') }}" class="btn-view-all">View All</a>
                </div>
                <div class="card-body">
                    <div class="user-list">
                        @foreach($recentUsers as $user)
                        <div class="user-item">
                            <div class="user-avatar">
                                <img src="{{ $user->profile_image ?? asset('images/default-avatar.png') }}" alt="{{ $user->email }}">
                            </div>
                            <div class="user-info">
                                <h6>{{ $user->email }}</h6>
                                <p>{{ ucfirst($user->user_type) }}</p>
                            </div>
                            <div class="user-stats">
                                <span class="badge badge-{{ $user->status }}">{{ ucfirst($user->status) }}</span>
                                <p class="user-date">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Satisfaction -->
    <div class="row g-3">
        <div class="col-xl-12">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6>Customer Satisfaction</h6>
                </div>
                <div class="card-body">
                    <div class="satisfaction-stats">
                        <div class="satisfaction-item">
                            <div class="satisfaction-label">Overall Rating</div>
                            <div class="satisfaction-value">{{ number_format($satisfaction['overall'], 1) }}%</div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: {{ $satisfaction['overall'] }}%"></div>
                            </div>
                        </div>
                        <div class="satisfaction-item">
                            <div class="satisfaction-label">Doctor Satisfaction</div>
                            <div class="satisfaction-value">{{ number_format($satisfaction['doctors'], 1) }}%</div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $satisfaction['doctors'] }}%"></div>
                            </div>
                        </div>
                        <div class="satisfaction-item">
                            <div class="satisfaction-label">Hospital Services</div>
                            <div class="satisfaction-value">{{ number_format($satisfaction['hospitals'], 1) }}%</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ $satisfaction['hospitals'] }}%"></div>
                            </div>
                        </div>
                        <div class="satisfaction-item">
                            <div class="satisfaction-label">Laboratory Services</div>
                            <div class="satisfaction-value">{{ number_format($satisfaction['laboratories'], 1) }}%</div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $satisfaction['laboratories'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Chart data from backend
    const chartData = @json($chartData);
</script>
@endpush
