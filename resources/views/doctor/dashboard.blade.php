@extends('doctor.layouts.master')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="dashboard-container">
    {{-- Welcome Banner --}}
    <div class="welcome-banner mb-3">
        <div class="welcome-content d-flex justify-content-between align-items-center">
            <div class="welcome-text">
                <h3>Welcome back, Dr. {{ auth()->user()->doctor->first_name ?? auth()->user()->email }}! 👋</h3>
                <p>Here's what's happening with your practice today</p>
            </div>
            <div class="welcome-date bg-light rounded px-3 py-2">
                <i class="fas fa-calendar-alt text-primary"></i>
                {{ date('l, F d, Y') }}
            </div>
        </div>
    </div>
  @if(auth()->user()->status !== 'active')
    <div class="alert alert-warning">
      Your account is not approved yet or has been suspended.<br>
      Only profile editing is permitted until approval.
    </div>
    {{-- Show only profile-edit component --}}
    {{-- @include('doctor.profile.edit')
@else --}}
    {{-- Full dashboard blocks --}}
    {{-- @include('doctor.dashboard.blocks') --}}
@endif

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Today's Appointments</div>
                        <div class="stat-value" id="todayAppointments">
                            <span class="counter">0</span>
                        </div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 12% from yesterday
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Total Patients</div>
                        <div class="stat-value" id="totalPatients">
                            <span class="counter">0</span>
                        </div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 8% this month
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Monthly Earnings</div>
                        <div class="stat-value" id="monthlyEarnings">
                            Rs. <span class="counter">0</span>
                        </div>
                        <div class="stat-change text-success">
                            <i class="fas fa-arrow-up"></i> 15% increase
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stat-card stat-card-purple">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">Average Rating</div>
                        <div class="stat-value" id="avgRating">
                            <span class="counter">0.0</span>
                            <i class="fas fa-star text-warning ms-1" style="font-size: 0.9rem;"></i>
                        </div>
                        <div class="stat-change text-muted">
                            <i class="fas fa-chart-line"></i> Based on reviews
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Today's Appointments --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card dashboard-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                            Today's Appointments
                        </h6>
                        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="appointment-timeline" id="todayAppointmentsList">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0 text-sm">Loading appointments...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointment Stats & Quick Actions --}}
        <div class="col-xl-4 col-lg-5">
            {{-- Appointment Overview --}}
            <div class="card dashboard-card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Appointment Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-progress-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-indicator bg-warning"></div>
                                <span class="text-xs fw-500">Pending</span>
                            </div>
                            <span class="text-xs fw-bold text-warning" id="pendingCount">0</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar bg-warning" id="pendingProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="stat-progress-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-indicator bg-success"></div>
                                <span class="text-xs fw-500">Confirmed</span>
                            </div>
                            <span class="text-xs fw-bold text-success" id="confirmedCount">0</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar bg-success" id="confirmedProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="stat-progress-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-indicator bg-primary"></div>
                                <span class="text-xs fw-500">Completed</span>
                            </div>
                            <span class="text-xs fw-bold text-primary" id="completedCount">0</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar bg-primary" id="completedProgress" style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="stat-progress-item mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="status-indicator bg-danger"></div>
                                <span class="text-xs fw-500">Cancelled</span>
                            </div>
                            <span class="text-xs fw-bold text-danger" id="cancelledCount">0</span>
                        </div>
                        <div class="custom-progress">
                            <div class="custom-progress-bar bg-danger" id="cancelledProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card dashboard-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="quick-action-grid">
                        <a href="{{ route('doctor.schedule.index') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span>Schedule</span>
                        </a>
                        <a href="{{ route('doctor.patients.index') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <span>Patients</span>
                        </a>
                        <a href="{{ route('doctor.workplaces.index') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-info">
                                <i class="fas fa-building"></i>
                            </div>
                            <span>Workplaces</span>
                        </a>
                        <a href="{{ route('doctor.earnings.index') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Earnings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Patients --}}
        <div class="col-xl-7">
            <div class="card dashboard-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-user-injured me-2 text-success"></i>
                            Recent Patients
                        </h6>
                        <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-outline-success">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="border-0">Patient</th>
                                    <th class="border-0">Contact</th>
                                    <th class="border-0">Last Visit</th>
                                    <th class="border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="recentPatientsTable">
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0 text-xs">Loading patients...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Reviews --}}
        <div class="col-xl-5">
            <div class="card dashboard-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Recent Reviews
                        </h6>
                        <a href="{{ route('doctor.reviews.index') }}" class="btn btn-sm btn-outline-warning">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" id="recentReviewsList">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 text-xs">Loading reviews...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
   :root {
  --sidebar-width: 220px;
  --sidebar-bg: linear-gradient(135deg, #0f4c75 0%, #1a5c8a 100%);
  --sidebar-radius: 20px;
  --sidebar-primary: #2969bf;
  --sidebar-accent: #1a5c8a;
  --success: #42a649;
  --danger: #e74c3c;
}

/* ------------------------------- SIDEBAR ------------------------------- */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: var(--sidebar-width);
  height: 100vh;
  background: var(--sidebar-bg);
  color: #fff;
  overflow-y: auto;
  z-index: 1000;
  border-radius: 0 var(--sidebar-radius) var(--sidebar-radius) 0;
  box-shadow: 0 8px 24px rgba(44,62,80,0.13);
  transition: transform 0.3s cubic-bezier(.4,2,.5,1), left 0.33s;
}

/* Scrollbar */
.sidebar::-webkit-scrollbar { width: 5px;}
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius:10px;}

/* Header */
.sidebar-header { padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.11);}
.sidebar-header .logo { display: flex; align-items: center; gap: 0.7rem; font-size: 1.18rem; font-weight: 700;}
.sidebar-header .logo i { font-size: 1.45rem; color: var(--success);}
.logo span { letter-spacing: 1px; }
.role-badge {
  display: inline-block; background: rgba(66,166,73,0.17); color: #42a649;
  padding: 0.28rem 0.85rem; border-radius: 13px; font-size: 0.7rem; font-weight: 600; margin-top: 0.5rem;
}

/* User */
.sidebar-user {
  padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.11); display: flex; align-items: center; gap: 1rem;
}
.user-avatar {
  position: relative; width: 50px; height: 50px; border-radius: 50%; overflow: hidden;
  border: 3px solid rgba(66,166,73,0.21);
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover;}
.status-dot {
  position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px;
  background: #42a649; border: 2px solid #0f4c75; border-radius: 50%;
}
.user-info h6 { font-size: 0.91rem; font-weight: 600; margin: 0; color: #fff;}
.user-info p { font-size: 0.74rem; color: rgba(255,255,255,0.75); margin: 0.2rem 0 0 0;}

/* Menu */
.sidebar-nav { padding: 1rem 0;}
.nav-list { list-style: none; padding: 0; margin: 0;}
.nav-item { margin: 0.2rem 0;}
.nav-link {
  display: flex; align-items: center; padding: 0.7rem 1.2rem;
  color: rgba(255,255,255,0.84); text-decoration: none; transition: all 0.3s;
  font-size: 0.86rem; font-weight: 500; border-radius: 12px; position: relative;
}
.nav-link i { width: 20px; margin-right: 0.8rem; font-size: 0.97rem;}
.nav-link:hover {
  background: rgba(255,255,255,0.10);
  color: #fff;
  padding-left: 1.5rem;
}
.nav-link.active {
  background: linear-gradient(90deg, #ffffffc8 45%, #cce3ffd3 100%);
  color: #194f93 !important;
  font-weight: 700;
  border-left: 4px solid #3399ff;
  box-shadow: 0 1px 6px 0 rgba(31,78,121,0.09);
  transition: background 0.12s, color 0.14s;
}
.nav-link.active i { color: #3399ff !important;}
.nav-divider { height: 1px; background: rgba(255,255,255,0.13); margin: 0.8rem 1.2rem;}
.nav-link.text-danger { color: var(--danger) !important;}
.nav-link.text-danger:hover { background: rgba(231,76,60,0.11); color:var(--danger) !important;}
.nav-link.text-danger i { color: var(--danger) !important;}

/* --------------------------- MAIN DASHBOARD CONTAINER --------------------------- */
.dashboard-container {
  max-width: 1530px;
  margin: 0 auto;
  padding: 50px 0 35px 0;
  min-height: 92vh;
}
@media (min-width: 1200px) {
  body .dashboard-container {
    padding-left: 15px;
    padding-right: 18px;
  }
}
.row.g-3, .row.g-4, .row.g-2, .row { margin-left: 0!important; margin-right: 0!important;}
[class*="col-"] { padding-left: 17px!important; padding-right: 17px!important;}
@media (max-width: 1400px) {
  .dashboard-container { max-width: 98vw; padding-left: 8px; padding-right: 8px;}
  [class*="col-"] { padding-left: 7px!important; padding-right: 7px!important;}
}
@media (max-width: 992px) {
  .dashboard-container { max-width: 100vw; padding-left: 0; padding-right: 0;}
}
@media (max-width: 768px) {
  .dashboard-container { max-width: 100vw; padding: 0 0px 26px 0px; }
}

/* Background for separation */
body { background: #f7faff; }

/* ------------------------------ WELCOME BANNER, CARDS, ETC ------------------------------- */
.welcome-banner {
  background: linear-gradient(135deg, #0f4c75 0%, #1a5c8a 50%, #3282b8 100%);
  border-radius: 12px;
  padding: 1.8rem 2rem;
  margin-bottom: 1.5rem;
  color: white;
  box-shadow: 0 4px 20px rgba(15, 76, 117, 0.25);
  position: relative;
  overflow: hidden;
}
.welcome-banner::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 300px; height: 300px;
  background: rgba(255,255,255,0.05);
  border-radius: 50%;
}
.welcome-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; position: relative; z-index: 1;}
.welcome-text h3 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.4rem 0; line-height: 1.3;}
.welcome-text p { font-size: 0.9rem; margin: 0; opacity: 0.95; font-weight: 400;}
.welcome-date {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  padding: 0.6rem 1.2rem;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
  border: 1px solid rgba(255,255,255,0.3);
}
.welcome-date i { margin-right: 0.5rem; font-size: 0.9rem;}

/* Stat Cards */
.stat-card {
  background: white;
  border-radius: 12px;
  padding: 0;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
  height: 100%;
  border: 1px solid rgba(0,0,0,0.04);
  overflow: hidden;
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.11);}
.stat-card-inner { padding: 1.3rem; display: flex; align-items: center; gap: 1rem;}
.stat-icon {
  width: 55px; height: 55px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem; color: white; flex-shrink: 0;
}
.stat-card-primary .stat-icon { background: linear-gradient(135deg, #42a649, #2d7a32);}
.stat-card-info .stat-icon { background: linear-gradient(135deg, #3498db, #2980b9);}
.stat-card-warning .stat-icon { background: linear-gradient(135deg, #f39c12, #e67e22);}
.stat-card-purple .stat-icon { background: linear-gradient(135deg, #9b59b6, #8e44ad);}
.stat-label { font-size: 0.75rem; color: #6c757d; font-weight: 500; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.3px;}
.stat-value { font-size: 2rem; font-weight: 700; color: var(--sidebar-primary); margin-bottom: 0.3rem; line-height: 1;}
.stat-change { font-size: 0.72rem; font-weight: 500; display: flex; align-items: center; gap: 0.3rem;}
.stat-change i { font-size: 0.7rem;}

/* Dashboard Cards, timeline, tables ... (copy your existing card styles for content here) */
/* ... You can keep/add rest of your original dashboard card, review-item, appointment-item css here ... */

/* -------------- Responsive/tweaks for all blocks as before ---------------- */
@media (max-width: 1200px) {
  .stat-value { font-size: 1.6rem; }
}
@media (max-width: 768px) {
  .welcome-banner { padding: 1.2rem 1.3rem; }
  .welcome-text h3 { font-size: 1.2rem;}
  .stat-value { font-size: 1.5rem; }
  .stat-icon { width: 45px; height: 45px; font-size: 1.2rem;}
}
@media (max-width: 576px) {
  .stat-card-inner { padding: 1rem; }
  .dashboard-card .card-body { padding: 1rem; }
}
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.show { transform: translateX(0);}
}


    /* Dashboard Cards */
    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .dashboard-card .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.3rem;
        border-radius: 12px 12px 0 0;
    }

    .dashboard-card .card-header h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
    }

    .dashboard-card .card-body {
        padding: 1.3rem;
    }

    /* Appointment Timeline */
    .appointment-timeline {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .appointment-timeline::-webkit-scrollbar {
        width: 5px;
    }

    .appointment-timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .appointment-timeline::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }

    .appointment-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-left: 3px solid #e9ecef;
        margin-bottom: 0.8rem;
        transition: all 0.3s ease;
        border-radius: 0 8px 8px 0;
        background: #fafbfc;
    }

    .appointment-item:hover {
        background: #f0f7ff;
        border-left-color: var(--primary);
        transform: translateX(5px);
    }

    .appointment-time {
        min-width: 85px;
        text-align: center;
    }

    .appointment-time .time {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--primary);
        display: block;
    }

    .appointment-time .duration {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 0.2rem;
        display: block;
    }

    .appointment-details {
        flex: 1;
    }

    .appointment-details h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 0.3rem 0;
        color: var(--dark);
    }

    .appointment-details p {
        font-size: 0.75rem;
        color: #6c757d;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .appointment-details p i {
        font-size: 0.7rem;
    }

    /* Stats Progress */
    .stat-progress-item {
        margin-bottom: 1.2rem;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .custom-progress {
        height: 6px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .custom-progress-bar {
        height: 100%;
        transition: width 0.6s ease;
        border-radius: 10px;
    }

    /* Quick Actions */
    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        text-decoration: none;
        color: var(--dark);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: transparent;
    }

    .quick-action-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .quick-action-btn span {
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Table Styles */
    .table {
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .table thead th {
        font-weight: 600;
        background: #f8f9fa;
        color: var(--primary);
        font-size: 0.75rem;
        padding: 0.8rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .table tbody td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
    }

    .patient-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    /* Review Items */
    .review-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: background 0.3s ease;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-item:hover {
        background: #f8f9fa;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .review-header h6 {
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0;
        color: var(--dark);
    }

    .review-rating {
        color: #f39c12;
        font-size: 0.75rem;
        display: flex;
        gap: 0.1rem;
    }

    .review-text {
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .review-date {
        font-size: 0.7rem;
        color: #adb5bd;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stat-value {
            font-size: 1.6rem;
        }
    }

    @media (max-width: 768px) {
        .welcome-banner {
            padding: 1.2rem 1.3rem;
        }

        .welcome-text h3 {
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }

        .quick-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .appointment-timeline {
            max-height: 350px;
        }
    }

    @media (max-width: 576px) {
        .stat-card-inner {
            padding: 1rem;
        }

        .dashboard-card .card-body {
            padding: 1rem;
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

    // Load Dashboard Statistics
    function loadDashboardData() {
        $.ajax({
            url: '/doctor/dashboard/stats',
            method: 'GET',
            success: function(data) {
                animateCounter($('#todayAppointments .counter'), data.today_appointments || 0);
                animateCounter($('#totalPatients .counter'), data.total_patients || 0);
                animateCounter($('#monthlyEarnings .counter'), data.monthly_earnings || 0);
                animateCounter($('#avgRating .counter'), (data.avg_rating || 0).toFixed(1), true);

                // Update appointment stats
                const stats = data.appointment_stats || {};
                const total = stats.total || 1;

                updateStat('pending', stats.pending || 0, total);
                updateStat('confirmed', stats.confirmed || 0, total);
                updateStat('completed', stats.completed || 0, total);
                updateStat('cancelled', stats.cancelled || 0, total);
            },
            error: function() {
                console.error('Failed to load dashboard stats');
                showDefaultValues();
            }
        });
    }

    function updateStat(type, count, total) {
        $(`#${type}Count`).text(count);
        const percentage = (count / total) * 100;
        $(`#${type}Progress`).css('width', percentage + '%');
    }

    function animateCounter(element, target, isDecimal = false) {
        const duration = 1000;
        const steps = 50;
        const increment = target / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.text(isDecimal ? current.toFixed(1) : Math.floor(current).toLocaleString());
        }, duration / steps);
    }

    // Load Today's Appointments
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
                                    <span class="time">${apt.time}</span>
                                    <span class="duration">${apt.duration} min</span>
                                </div>
                                <div class="appointment-details">
                                    <h6>${apt.patient_name}</h6>
                                    <p>
                                        <i class="fas fa-map-marker-alt"></i>
                                        ${apt.location}
                                    </p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-${statusColors[apt.status]} text-capitalize">
                                        ${apt.status}
                                    </span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = `
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">No appointments scheduled for today</p>
                            <a href="${'{{ route("doctor.schedule.index") }}'}" class="btn btn-sm btn-outline-primary mt-3">
                                <i class="fas fa-plus me-1"></i>Add Schedule
                            </a>
                        </div>
                    `;
                }
                $('#todayAppointmentsList').html(html);
            },
            error: function() {
                $('#todayAppointmentsList').html('<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-circle"></i> Failed to load appointments</div>');
            }
        });
    }

    // Load Recent Patients
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
                                        <img src="${patient.avatar || '/images/default-avatar.png'}"
                                             alt="${patient.name}"
                                             class="patient-avatar">
                                        <span class="fw-500">${patient.name}</span>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>${patient.phone}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">${patient.last_visit}</small>
                                </td>
                                <td class="text-center">
                                    <a href="/doctor/patients/${patient.id}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="View Patient">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center py-4 text-muted">No patients found</td></tr>';
                }
                $('#recentPatientsTable').html(html);

                // Reinitialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                $('#recentPatientsTable').html('<tr><td colspan="4" class="text-center py-4 text-danger">Failed to load patients</td></tr>');
            }
        });
    }

    // Load Recent Reviews
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
                            stars += i < review.rating
                                ? '<i class="fas fa-star"></i>'
                                : '<i class="far fa-star"></i>';
                        }
                        html += `
                            <div class="review-item">
                                <div class="review-header">
                                    <h6>${review.patient_name}</h6>
                                    <div class="review-rating">${stars}</div>
                                </div>
                                <p class="review-text">${review.comment}</p>
                                <div class="review-date">
                                    <i class="far fa-clock me-1"></i>${review.date}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = `
                        <div class="text-center py-4">
                            <i class="fas fa-star-half-alt fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">No reviews yet</p>
                        </div>
                    `;
                }
                $('#recentReviewsList').html(html);
            },
            error: function() {
                $('#recentReviewsList').html('<div class="text-center py-4 text-danger">Failed to load reviews</div>');
            }
        });
    }

    function showDefaultValues() {
        $('#todayAppointments .counter').text('0');
        $('#totalPatients .counter').text('0');
        $('#monthlyEarnings .counter').text('0');
        $('#avgRating .counter').text('0.0');
    }

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }

/* Dropdown handling, notification functions, etc - same as your original code */

</script>

@endpush
