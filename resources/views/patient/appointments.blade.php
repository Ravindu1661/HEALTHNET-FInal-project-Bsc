{{-- Include Header --}}
@include('partials.header')

<style>
/* ══════════════════════════════════════════
   MY APPOINTMENTS — Doctor Profile Style Match
══════════════════════════════════════════ */

/* Page Header */
.appts-page-header {
    background: linear-gradient(135deg, var(--primary-color, #1a5276) 0%, var(--secondary-color, #2e86c1) 100%);
    padding: 7rem 0 3.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.appts-page-header::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 40px;
    background: #f4f6f9;
    clip-path: ellipse(55% 100% at 50% 100%);
}
.appts-page-header h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}
.appts-page-header p {
    opacity: 0.9;
    font-size: 1rem;
    margin: 0;
}

/* Main BG */
.appts-main {
    background: #f4f6f9;
    padding: 2rem 0 4rem;
    min-height: 600px;
}

/* Stat Cards — matches doctor profile stat style */
.appt-stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    display: flex;
    align-items: center;
    gap: 1.2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 1.5rem;
}
.appt-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.appt-stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.appt-stat-icon.total    { background: linear-gradient(135deg, #1a5276, #2e86c1); }
.appt-stat-icon.pending  { background: linear-gradient(135deg, #f39c12, #e67e22); }
.appt-stat-icon.confirmed{ background: linear-gradient(135deg, #42a649, #2d7a32); }
.appt-stat-icon.completed{ background: linear-gradient(135deg, #8e44ad, #6c3483); }
.appt-stat-label {
    font-size: 0.82rem;
    color: #888;
    font-weight: 500;
    margin-bottom: 0.2rem;
}
.appt-stat-value {
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    line-height: 1;
}

/* Filter Bar */
.filter-card {
    background: white;
    border-radius: 15px;
    padding: 1.2rem 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    flex-wrap: wrap;
}
.filter-btn {
    padding: 0.5rem 1.2rem;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: white;
    font-size: 0.85rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
}
.filter-btn:hover,
.filter-btn.active {
    border-color: var(--accent-color, #42a649);
    color: var(--accent-color, #42a649);
    background: rgba(66,166,73,0.06);
}
.filter-btn.active {
    background: var(--accent-color, #42a649);
    color: white;
    border-color: var(--accent-color, #42a649);
}
.filter-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    margin-right: 0.3rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

/* Appointment Card — matches section-card from doctor profile */
.appt-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 5px solid transparent;
}
.appt-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.appt-card.status-pending   { border-left-color: #f39c12; }
.appt-card.status-confirmed { border-left-color: #42a649; }
.appt-card.status-completed { border-left-color: #8e44ad; }
.appt-card.status-cancelled { border-left-color: #e74c3c; }
.appt-card.status-no_show   { border-left-color: #95a5a6; }

/* Card Top */
.appt-card-top {
    padding: 1.4rem 1.5rem;
    display: flex;
    gap: 1.2rem;
    align-items: flex-start;
}
.appt-doctor-avatar {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--accent-color, #42a649);
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.appt-doctor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.appt-card-info {
    flex: 1;
    min-width: 0;
}
.appt-number {
    font-size: 0.75rem;
    color: #999;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 0.2rem;
}
.appt-doctor-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    margin-bottom: 0.15rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.appt-doctor-spec {
    font-size: 0.85rem;
    color: var(--accent-color, #42a649);
    font-weight: 600;
    margin-bottom: 0.6rem;
}
.appt-meta-row {
    display: flex;
    gap: 1.2rem;
    flex-wrap: wrap;
}
.appt-meta-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.82rem;
    color: #666;
}
.appt-meta-item i {
    color: var(--accent-color, #42a649);
    font-size: 0.8rem;
}
.appt-card-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.6rem;
    flex-shrink: 0;
}

/* Status & Payment Badges — matches profile page badge style */
.badge-status {
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
}
.badge-status.pending   { background: #fff3cd; color: #856404; }
.badge-status.confirmed { background: #d4edda; color: #155724; }
.badge-status.completed { background: #e8d5f5; color: #6c3483; }
.badge-status.cancelled { background: #f8d7da; color: #721c24; }
.badge-status.no_show   { background: #e2e3e5; color: #383d41; }
.badge-status.inprogress{ background: #d1ecf1; color: #0c5460; }

.badge-payment {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    white-space: nowrap;
}
.badge-payment.paid    { background: #d4edda; color: #155724; }
.badge-payment.unpaid  { background: #f8d7da; color: #721c24; }
.badge-payment.partial { background: #fff3cd; color: #856404; }
.badge-payment.refunded{ background: #d1ecf1; color: #0c5460; }

/* Card Footer */
.appt-card-footer {
    background: #f8f9fa;
    padding: 0.9rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.8rem;
    border-top: 1px solid #e9ecef;
}
.appt-fee-box {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.appt-fee-label {
    font-size: 0.8rem;
    color: #888;
}
.appt-fee-amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--accent-color, #42a649);
}
.appt-action-btns {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
}

/* Action Buttons */
.btn-appt-view {
    background: var(--primary-color, #1a5276);
    color: white;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-appt-view:hover {
    background: var(--accent-color, #42a649);
    color: white;
    transform: translateY(-2px);
}
.btn-appt-pay {
    background: var(--accent-color, #42a649);
    color: white;
    border: none;
    padding: 0.5rem 1.3rem;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    box-shadow: 0 3px 10px rgba(66,166,73,0.3);
    animation: payPulse 2s infinite;
}
@keyframes payPulse {
    0%, 100% { box-shadow: 0 3px 10px rgba(66,166,73,0.3); }
    50%       { box-shadow: 0 3px 18px rgba(66,166,73,0.55); }
}
.btn-appt-pay:hover {
    background: #2d7a32;
    color: white;
    transform: translateY(-2px);
}
.btn-appt-cancel {
    background: white;
    color: #e74c3c;
    border: 2px solid #e74c3c;
    padding: 0.45rem 1rem;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-appt-cancel:hover {
    background: #e74c3c;
    color: white;
}
.btn-appt-review {
    background: white;
    color: #f39c12;
    border: 2px solid #f39c12;
    padding: 0.45rem 1rem;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.btn-appt-review:hover {
    background: #f39c12;
    color: white;
}

/* Date Badge */
.appt-date-badge {
    background: linear-gradient(135deg, var(--primary-color, #1a5276), var(--secondary-color, #2e86c1));
    color: white;
    border-radius: 10px;
    padding: 0.5rem 0.9rem;
    text-align: center;
    min-width: 60px;
    flex-shrink: 0;
}
.appt-date-day {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1;
}
.appt-date-month {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    opacity: 0.9;
}

/* Workplace Pill */
.wp-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(66,166,73,0.08);
    border: 1px solid rgba(66,166,73,0.2);
    border-radius: 12px;
    padding: 0.25rem 0.7rem;
    font-size: 0.78rem;
    color: var(--accent-color, #42a649);
    font-weight: 600;
}

/* Empty State */
.appt-empty {
    background: white;
    border-radius: 15px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
}
.appt-empty i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
    display: block;
}
.appt-empty h4 {
    color: #aaa;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.appt-empty p {
    color: #bbb;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

/* Book New Appointment Button */
.btn-book-new {
    background: var(--accent-color, #42a649);
    color: white;
    border: none;
    padding: 0.9rem 2rem;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(66,166,73,0.3);
}
.btn-book-new:hover {
    background: var(--primary-color, #1a5276);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(66,166,73,0.4);
}

/* Alert Messages */
.appt-alert {
    border-radius: 12px;
    padding: 1rem 1.3rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 0.9rem;
    font-weight: 500;
}
.appt-alert.success {
    background: #d4edda;
    color: #155724;
    border-left: 5px solid #42a649;
}
.appt-alert.error {
    background: #f8d7da;
    color: #721c24;
    border-left: 5px solid #e74c3c;
}
.appt-alert.info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 5px solid #17a2b8;
}

/* Pagination */
.appt-pagination {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}
.appt-pagination .pagination {
    gap: 0.3rem;
}
.appt-pagination .page-link {
    border-radius: 8px !important;
    border: 2px solid #e9ecef;
    color: var(--primary-color, #1a5276);
    font-weight: 600;
    padding: 0.5rem 0.9rem;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
.appt-pagination .page-link:hover,
.appt-pagination .page-item.active .page-link {
    background: var(--accent-color, #42a649);
    border-color: var(--accent-color, #42a649);
    color: white;
}

/* Cancel Modal */
.cancel-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.cancel-modal-overlay.show {
    display: flex;
}
.cancel-modal-box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    max-width: 440px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: modalPop 0.3s ease;
}
@keyframes modalPop {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}
.cancel-modal-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color, #1a5276);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.cancel-modal-title i {
    color: #e74c3c;
}
.cancel-modal-desc {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1.3rem;
    line-height: 1.6;
}
.cancel-modal-footer {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
}
.btn-modal-keep {
    background: white;
    color: var(--primary-color, #1a5276);
    border: 2px solid #e9ecef;
    padding: 0.6rem 1.4rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-modal-keep:hover {
    border-color: var(--primary-color, #1a5276);
}
.btn-modal-confirm {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 0.6rem 1.6rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-modal-confirm:hover {
    background: #c0392b;
}

/* Responsive */
@media (max-width: 768px) {
    .appts-page-header { padding: 5rem 0 2.5rem; }
    .appts-page-header h1 { font-size: 1.6rem; }
    .appt-card-top { flex-direction: column; }
    .appt-card-right { flex-direction: row; align-items: center; }
    .appt-meta-row { gap: 0.7rem; }
    .filter-card { gap: 0.5rem; }
    .appt-card-footer { justify-content: flex-end; }
}
</style>

{{-- ════════════ PAGE HEADER ════════════ --}}
<section class="appts-page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1>
                    <i class="fas fa-calendar-check me-2" style="opacity:0.85;"></i>
                    My Appointments
                </h1>
                <p>Track and manage all your medical appointments in one place</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('patient.doctors') }}" class="btn-book-new">
                    <i class="fas fa-calendar-plus"></i>
                    Book New Appointment
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ════════════ MAIN CONTENT ════════════ --}}
<section class="appts-main">
    <div class="container">

        {{-- Session Alerts --}}
        @if(session('success'))
        <div class="appt-alert success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="appt-alert error">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('info'))
        <div class="appt-alert info">
            <i class="fas fa-info-circle fa-lg"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        {{-- ════ STATS CARDS ════ --}}
        <div class="row g-3 mb-1">
            <div class="col-6 col-md-3">
                <div class="appt-stat-card">
                    <div class="appt-stat-icon total">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="appt-stat-label">Total</div>
                        <div class="appt-stat-value">{{ $appointments->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="appt-stat-card">
                    <div class="appt-stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="appt-stat-label">Pending</div>
                        <div class="appt-stat-value">{{ $statusCounts['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="appt-stat-card">
                    <div class="appt-stat-icon confirmed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="appt-stat-label">Confirmed</div>
                        <div class="appt-stat-value">{{ $statusCounts['confirmed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="appt-stat-card">
                    <div class="appt-stat-icon completed">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="appt-stat-label">Completed</div>
                        <div class="appt-stat-value">{{ $statusCounts['completed'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════ FILTER BAR ════ --}}
        <div class="filter-card">
            <span class="filter-label">
                <i class="fas fa-filter"></i> Filter:
            </span>
            <a href="{{ route('patient.appointments.index') }}"
               class="filter-btn {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> All
            </a>
            <a href="{{ route('patient.appointments.index', ['status' => 'pending']) }}"
               class="filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Pending
            </a>
            <a href="{{ route('patient.appointments.index', ['status' => 'confirmed']) }}"
               class="filter-btn {{ request('status') == 'confirmed' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i> Confirmed
            </a>
            <a href="{{ route('patient.appointments.index', ['status' => 'completed']) }}"
               class="filter-btn {{ request('status') == 'completed' ? 'active' : '' }}">
                <i class="fas fa-flag-checkered"></i> Completed
            </a>
            <a href="{{ route('patient.appointments.index', ['status' => 'cancelled']) }}"
               class="filter-btn {{ request('status') == 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i> Cancelled
            </a>

            {{-- Payment Filter --}}
            <span class="filter-label ms-auto">
                <i class="fas fa-credit-card"></i> Payment:
            </span>
            <a href="{{ route('patient.appointments.index', array_merge(request()->query(), ['payment' => 'unpaid'])) }}"
               class="filter-btn {{ request('payment') == 'unpaid' ? 'active' : '' }}"
               style="{{ request('payment') == 'unpaid' ? 'background:#f8d7da;color:#721c24;border-color:#f5c6cb;' : '' }}">
                <i class="fas fa-exclamation-circle"></i> Unpaid
            </a>
            <a href="{{ route('patient.appointments.index', array_merge(request()->query(), ['payment' => 'paid'])) }}"
               class="filter-btn {{ request('payment') == 'paid' ? 'active' : '' }}">
                <i class="fas fa-check"></i> Paid
            </a>
            @if(request('payment') || request('status'))
            <a href="{{ route('patient.appointments.index') }}"
               class="filter-btn"
               style="color:#e74c3c;border-color:#e74c3c;">
                <i class="fas fa-redo-alt"></i> Reset
            </a>
            @endif
        </div>

        {{-- ════ APPOINTMENT CARDS ════ --}}
        @forelse($appointments as $appointment)
        @php
            $doctor      = $appointment->doctor;
            $profileImg  = $doctor && $doctor->profile_image
                ? asset('storage/' . $doctor->profile_image)
                : asset('images/default-avatar.png');

            $apptDate    = \Carbon\Carbon::parse($appointment->appointment_date);
            $apptTime    = \Carbon\Carbon::parse($appointment->appointment_time);

            // Workplace name
            $wpName = 'Not Available';
            $wpIcon = 'fas fa-clinic-medical';
            if ($appointment->workplace_type == 'hospital' && $appointment->hospital) {
                $wpName = $appointment->hospital->name;
                $wpIcon = 'fas fa-hospital';
            } elseif ($appointment->workplace_type == 'medical_centre' && $appointment->medicalCentre) {
                $wpName = $appointment->medicalCentre->name;
                $wpIcon = 'fas fa-clinic-medical';
            } elseif ($appointment->workplace_type == 'private') {
                $wpName = 'Private Practice';
                $wpIcon = 'fas fa-user-md';
            }

            $statusClass = match($appointment->status) {
                'pending'    => 'pending',
                'confirmed'  => 'confirmed',
                'completed'  => 'completed',
                'cancelled'  => 'cancelled',
                'no_show'    => 'no_show',
                'inprogress' => 'inprogress',
                default      => 'pending',
            };
            $statusIcon = match($appointment->status) {
                'pending'    => 'fas fa-clock',
                'confirmed'  => 'fas fa-check-circle',
                'completed'  => 'fas fa-flag-checkered',
                'cancelled'  => 'fas fa-times-circle',
                'no_show'    => 'fas fa-user-slash',
                'inprogress' => 'fas fa-spinner',
                default      => 'fas fa-clock',
            };
            $statusLabel = ucfirst(str_replace('_', ' ', $appointment->status));

            $paymentClass = match($appointment->payment_status) {
                'paid'     => 'paid',
                'partial'  => 'partial',
                'refunded' => 'refunded',
                default    => 'unpaid',
            };
            $paymentIcon = match($appointment->payment_status) {
                'paid'    => 'fas fa-check',
                'partial' => 'fas fa-adjust',
                default   => 'fas fa-times',
            };
            $paymentLabel = ucfirst($appointment->payment_status ?? 'Unpaid');

            $canCancel = in_array($appointment->status, ['pending', 'confirmed']);
            $canPay    = in_array($appointment->status, ['pending', 'confirmed'])
                      && $appointment->payment_status !== 'paid';
            $canReview = $appointment->status === 'completed';
        @endphp

        <div class="appt-card status-{{ $statusClass }}">
            {{-- Card Top --}}
            <div class="appt-card-top">

                {{-- Date Badge --}}
                <div class="appt-date-badge">
                    <div class="appt-date-day">{{ $apptDate->format('d') }}</div>
                    <div class="appt-date-month">{{ $apptDate->format('M') }}</div>
                    <div style="font-size:0.65rem;opacity:0.8;">{{ $apptDate->format('Y') }}</div>
                </div>

                {{-- Doctor Avatar --}}
                <div class="appt-doctor-avatar">
                    <img src="{{ $profileImg }}"
                         alt="Doctor"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                </div>

                {{-- Info --}}
                <div class="appt-card-info">
                    <div class="appt-number">
                        #{{ $appointment->appointment_number ?? 'APT-' . str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="appt-doctor-name">
                        Dr. {{ $doctor->first_name ?? 'Unknown' }} {{ $doctor->last_name ?? 'Doctor' }}
                    </div>
                    <div class="appt-doctor-spec">
                        {{ $doctor->specialization ?? 'General Practitioner' }}
                    </div>
                    <div class="appt-meta-row">
                        <div class="appt-meta-item">
                            <i class="fas fa-clock"></i>
                            {{ $apptTime->format('h:i A') }}
                        </div>
                        <div class="appt-meta-item">
                            <i class="{{ $wpIcon }}"></i>
                            {{ Str::limit($wpName, 30) }}
                        </div>
                        @if($appointment->reason)
                        <div class="appt-meta-item">
                            <i class="fas fa-notes-medical"></i>
                            {{ Str::limit($appointment->reason, 35) }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Right Badges --}}
                <div class="appt-card-right">
                    <span class="badge-status {{ $statusClass }}">
                        <i class="{{ $statusIcon }}"></i>
                        {{ $statusLabel }}
                    </span>
                    <span class="badge-payment {{ $paymentClass }}">
                        <i class="{{ $paymentIcon }}"></i>
                        {{ $paymentLabel }}
                    </span>
                    @if($appointment->appointment_type)
                    <span class="wp-pill">
                        <i class="fas fa-stethoscope"></i>
                        {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}
                    </span>
                    @endif
                </div>

            </div>

            {{-- Card Footer --}}
            <div class="appt-card-footer">
                {{-- Fee --}}
                <div class="appt-fee-box">
                    <span class="appt-fee-label">
                        <i class="fas fa-receipt me-1"></i> Consultation Fee:
                    </span>
                    <span class="appt-fee-amount">
                        Rs. {{ number_format($appointment->consultation_fee ?? 0, 2) }}
                    </span>
                    @if($appointment->payment_status === 'partial' && $appointment->advance_payment)
                    <span style="font-size:0.78rem;color:#856404;margin-left:0.5rem;">
                        (Paid: Rs. {{ number_format($appointment->advance_payment, 2) }})
                    </span>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="appt-action-btns">
                    {{-- Pay Now --}}
                    @if($canPay)
                    <a href="{{ route('patient.appointments.payment', $appointment->id) }}"
                       class="btn-appt-pay">
                        <i class="fas fa-credit-card"></i>
                        Pay Now
                    </a>
                    @endif

                    {{-- View Doctor Profile --}}
                    @if($doctor)
                    <a href="{{ route('patient.doctors.show', $doctor->id) }}"
                       class="btn-appt-view">
                        <i class="fas fa-user-md"></i>
                        Doctor Profile
                    </a>
                    @endif

                    {{-- Leave Review --}}
                    @if($canReview)
                    <a href="#reviewModal{{ $appointment->id }}"
                       class="btn-appt-review"
                       onclick="openReviewModal({{ $appointment->id }})">
                        <i class="fas fa-star"></i>
                        Review
                    </a>
                    @endif

                    {{-- Cancel --}}
                    @if($canCancel)
                    <button class="btn-appt-cancel"
                            onclick="openCancelModal({{ $appointment->id }}, '{{ $doctor->first_name ?? 'Doctor' }}', '{{ $apptDate->format('d M Y') }}')">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="appt-empty">
            <i class="fas fa-calendar-times"></i>
            <h4>No appointments found</h4>
            <p>
                @if(request('status') || request('payment'))
                    No appointments match your current filter. Try a different filter.
                @else
                    You haven't booked any appointments yet. Find a doctor and book your first appointment!
                @endif
            </p>
            <a href="{{ route('patient.doctors') }}" class="btn-book-new">
                <i class="fas fa-search"></i>
                Find a Doctor
            </a>
        </div>
        @endforelse

        {{-- ════ PAGINATION ════ --}}
        @if($appointments->hasPages())
        <div class="appt-pagination">
            {{ $appointments->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</section>

{{-- ════ CANCEL MODAL ════ --}}
<div class="cancel-modal-overlay" id="cancelModal">
    <div class="cancel-modal-box">
        <div class="cancel-modal-title">
            <i class="fas fa-exclamation-triangle"></i>
            Cancel Appointment
        </div>
        <div class="cancel-modal-desc" id="cancelModalDesc">
            Are you sure you want to cancel this appointment?
            This action cannot be undone.
        </div>
        <div class="cancel-modal-footer">
            <button class="btn-modal-keep" onclick="closeCancelModal()">
                <i class="fas fa-arrow-left"></i> Keep It
            </button>
            <form id="cancelForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-confirm">
                    <i class="fas fa-times"></i> Yes, Cancel
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ════ REVIEW MODAL ════ --}}
<div class="cancel-modal-overlay" id="reviewModalOverlay">
    <div class="cancel-modal-box" style="max-width:500px;">
        <div class="cancel-modal-title" style="color:var(--accent-color,#42a649);">
            <i class="fas fa-star" style="color:#f39c12;"></i>
            Leave a Review
        </div>
        <form id="reviewForm" method="POST" action="{{ route('patient.appointments.index') }}">
            @csrf
            <input type="hidden" name="appointment_id" id="reviewAppointmentId">
            <input type="hidden" name="doctor_id" id="reviewDoctorId">

            {{-- Star Rating --}}
            <div style="margin-bottom:1.2rem;">
                <label style="font-size:0.88rem;font-weight:600;color:var(--primary-color,#1a5276);display:block;margin-bottom:0.5rem;">
                    Your Rating <span style="color:#dc3545;">*</span>
                </label>
                <div class="star-rating-input" id="starRating">
                    @for($s = 1; $s <= 5; $s++)
                    <i class="far fa-star"
                       data-value="{{ $s }}"
                       style="font-size:2rem;color:#ddd;cursor:pointer;transition:color 0.2s;"
                       onmouseover="hoverStar({{ $s }})"
                       onmouseout="resetStars()"
                       onclick="selectStar({{ $s }})"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
            </div>

            {{-- Review Text --}}
            <div style="margin-bottom:1.3rem;">
                <label style="font-size:0.88rem;font-weight:600;color:var(--primary-color,#1a5276);display:block;margin-bottom:0.45rem;">
                    Your Review (optional)
                </label>
                <textarea name="review"
                          rows="3"
                          style="width:100%;padding:0.7rem 1rem;border:2px solid #e9ecef;border-radius:8px;font-size:0.9rem;resize:vertical;"
                          placeholder="Share your experience with this doctor..."></textarea>
            </div>

            <div class="cancel-modal-footer">
                <button type="button" class="btn-modal-keep" onclick="closeReviewModal()">
                    Cancel
                </button>
                <button type="submit" class="btn-modal-confirm"
                        style="background:var(--accent-color,#42a649);">
                    <i class="fas fa-paper-plane"></i> Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

<script>
// ══ Cancel Modal ══
function openCancelModal(id, doctorName, date) {
    document.getElementById('cancelModalDesc').innerHTML =
        `Are you sure you want to cancel your appointment with <strong>Dr. ${doctorName}</strong> on <strong>${date}</strong>?<br><br>
         <span style="color:#e74c3c;font-size:0.85rem;">⚠ This action cannot be undone.</span>`;
    document.getElementById('cancelForm').action =
        `{{ url('patient/appointments') }}/${id}`;
    document.getElementById('cancelModal').classList.add('show');
}
function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('show');
}
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

// ══ Review Modal ══
function openReviewModal(appointmentId) {
    document.getElementById('reviewAppointmentId').value = appointmentId;
    document.getElementById('reviewModalOverlay').classList.add('show');
}
function closeReviewModal() {
    document.getElementById('reviewModalOverlay').classList.remove('show');
    resetStars();
    document.getElementById('ratingInput').value = 0;
}
document.getElementById('reviewModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeReviewModal();
});

// ══ Star Rating ══
let selectedRating = 0;
function hoverStar(val) {
    const stars = document.querySelectorAll('#starRating i');
    stars.forEach((s, i) => {
        s.className = i < val ? 'fas fa-star' : 'far fa-star';
        s.style.color = i < val ? '#f39c12' : '#ddd';
    });
}
function resetStars() {
    const stars = document.querySelectorAll('#starRating i');
    stars.forEach((s, i) => {
        s.className = i < selectedRating ? 'fas fa-star' : 'far fa-star';
        s.style.color = i < selectedRating ? '#f39c12' : '#ddd';
    });
}
function selectStar(val) {
    selectedRating = val;
    document.getElementById('ratingInput').value = val;
    resetStars();
}

// ══ Auto-close alerts ══
setTimeout(() => {
    document.querySelectorAll('.appt-alert').forEach(el => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
