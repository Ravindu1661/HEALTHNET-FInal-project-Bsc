{{-- resources/views/medical_centre/doctors/show.blade.php --}}
@extends('medical_centre.layouts.master')

@section('title', 'Dr. ' . $doctor->name)
@section('page-title', 'Doctor Details')

@section('content')
<style>
.mc-page-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
}
.mc-page-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin: 0 0 .2rem; }
.mc-page-sub   { font-size: .82rem; color: var(--text-muted); margin: 0; }

.mc-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .48rem 1rem; border-radius: 9px; border: none;
    font-size: .8rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: var(--transition); text-decoration: none;
}
.mc-btn-back    { background: #f4f7fb; color: var(--text-muted); }
.mc-btn-back:hover { background: #e9ecef; color: var(--text-dark); }
.mc-btn-approve { background: #d1fae5; color: #065f46; border: 1.5px solid #a7f3d0; }
.mc-btn-approve:hover { background: #059669; color: #fff; }
.mc-btn-reject  { background: #fff3cd; color: #92400e; border: 1.5px solid #fde68a; }
.mc-btn-reject:hover  { background: #d97706; color: #fff; }
.mc-btn-remove  { background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5; }
.mc-btn-remove:hover  { background: #e74c3c; color: #fff; }

/* ── Layout Grid ── */
.doc-show-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 900px) { .doc-show-grid { grid-template-columns: 1fr; } }

/* ── Profile Card ── */
.doc-profile-card {
    background: #fff; border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.doc-profile-banner {
    height: 80px;
    background: linear-gradient(135deg, var(--mc-primary), var(--mc-secondary));
}
.doc-profile-body { padding: 0 1.25rem 1.25rem; }
.doc-profile-avatar-wrap {
    margin-top: -36px; margin-bottom: .75rem;
}
.doc-profile-avatar {
    width: 72px; height: 72px; border-radius: 16px;
    border: 3px solid #fff; box-shadow: var(--shadow-md);
    background: linear-gradient(135deg, var(--mc-primary), var(--mc-secondary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.6rem; font-weight: 800;
    overflow: hidden;
}
.doc-profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.doc-profile-name { font-size: 1rem; font-weight: 800; color: var(--text-dark); margin: 0 0 .15rem; }
.doc-profile-spec { font-size: .78rem; color: var(--mc-primary); font-weight: 700; margin: 0 0 .5rem; }

.doc-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 99px;
    font-size: .68rem; font-weight: 800; white-space: nowrap;
}
.badge-approved  { background: #d1fae5; color: #065f46; }
.badge-pending   { background: #fff3cd; color: #92400e; }
.badge-rejected  { background: #fee2e2; color: #991b1b; }
.badge-permanent { background: #e0e7ff; color: #3730a3; }
.badge-visiting  { background: #f0fdf4; color: #166534; }
.badge-temporary { background: #fff7ed; color: #9a3412; }

.doc-profile-divider { height: 1px; background: var(--border); margin: .9rem 0; }

.doc-profile-meta { display: flex; flex-direction: column; gap: .55rem; }
.doc-meta-row {
    display: flex; align-items: flex-start; gap: .6rem;
    font-size: .78rem;
}
.doc-meta-row i {
    width: 16px; text-align: center; color: var(--mc-primary);
    font-size: .75rem; margin-top: 2px; flex-shrink: 0;
}
.doc-meta-row span { color: var(--text-muted); flex: 1; }
.doc-meta-row strong { color: var(--text-dark); font-weight: 700; }

.doc-rating-row {
    display: flex; align-items: center; gap: .4rem;
    font-size: .82rem; color: #f59e0b; font-weight: 800;
}
.doc-rating-row span { color: var(--text-muted); font-size: .72rem; font-weight: 600; }

/* ── Info Card (right column) ── */
.doc-info-card {
    background: #fff; border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden; margin-bottom: 1.25rem;
}
.doc-info-card-head {
    padding: .85rem 1.1rem; border-bottom: 1px solid var(--border);
    background: #fafbfc;
    display: flex; align-items: center; gap: .5rem;
}
.doc-info-card-head h6 {
    font-size: .88rem; font-weight: 800; color: var(--text-dark); margin: 0;
}
.doc-info-card-head i { color: var(--mc-primary); font-size: .85rem; }
.doc-info-card-body { padding: 1rem 1.1rem; }

/* Stats mini row */
.doc-mini-stats {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: .85rem;
}
.doc-mini-stat {
    background: #f8fbff; border-radius: 10px;
    padding: .75rem; text-align: center;
    border: 1px solid var(--border);
}
.doc-mini-stat .val { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
.doc-mini-stat .lbl { font-size: .68rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-top: .25rem; }

/* Detail grid */
.doc-detail-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: .75rem 1.5rem;
}
@media (max-width: 600px) { .doc-detail-grid { grid-template-columns: 1fr; } }
.doc-detail-item label {
    font-size: .68rem; font-weight: 800; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: .2rem;
}
.doc-detail-item span {
    font-size: .82rem; font-weight: 600; color: var(--text-dark);
}

/* Schedule table */
.schedule-table { width: 100%; border-collapse: collapse; }
.schedule-table th {
    font-size: .68rem; font-weight: 800; text-transform: uppercase;
    color: var(--text-muted); padding: .55rem .75rem;
    border-bottom: 1px solid var(--border); background: #fafbfc;
}
.schedule-table td {
    font-size: .78rem; padding: .6rem .75rem;
    border-bottom: 1px solid #f5f7fa; color: var(--text-dark); font-weight: 600;
}
.schedule-table tr:last-child td { border-bottom: none; }

/* Appointments table */
.apt-table { width: 100%; border-collapse: collapse; }
.apt-table th {
    font-size: .68rem; font-weight: 800; text-transform: uppercase;
    color: var(--text-muted); padding: .55rem .85rem;
    border-bottom: 1px solid var(--border); background: #fafbfc;
    white-space: nowrap;
}
.apt-table td { font-size: .78rem; padding: .7rem .85rem; border-bottom: 1px solid #f5f7fa; vertical-align: middle; }
.apt-table tr:last-child td { border-bottom: none; }
.apt-table tr:hover td { background: #f8fbff; }

.apt-status-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .2rem .55rem; border-radius: 99px;
    font-size: .67rem; font-weight: 800;
}
.apt-pending   { background: #fff3cd; color: #92400e; }
.apt-confirmed { background: #dbeafe; color: #1e40af; }
.apt-completed { background: #d1fae5; color: #065f46; }
.apt-cancelled { background: #fee2e2; color: #991b1b; }
.apt-no-show   { background: #f3f4f6; color: #6b7280; }

.apt-pay-badge {
    font-size: .67rem; font-weight: 800;
    padding: .18rem .5rem; border-radius: 99px;
}
.pay-paid    { background: #d1fae5; color: #065f46; }
.pay-unpaid  { background: #fee2e2; color: #991b1b; }
.pay-partial { background: #fff3cd; color: #92400e; }

/* Empty state */
.doc-empty-sm {
    padding: 2rem 1rem; text-align: center; color: var(--text-muted);
}
.doc-empty-sm i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .25; }
.doc-empty-sm p { font-size: .8rem; margin: 0; }
</style>

{{-- ── Page Header ── --}}
<div class="mc-page-header">
    <div>
        <h4 class="mc-page-title">
            <i class="fas fa-user-md me-2" style="color:var(--mc-primary);"></i>
            Dr. {{ $doctor->name }}
        </h4>
        <p class="mc-page-sub">{{ $doctor->specialization ?? 'Doctor' }} · {{ ucfirst($doctor->employment_type) }}</p>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
        <a href="{{ route('medical_centre.doctors') }}" class="mc-btn mc-btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        @if($doctor->workplace_status === 'pending')
            <form method="POST"
                  action="{{ route('medical_centre.doctors.approve', $doctor->workplace_id) }}"
                  style="margin:0;"
                  onsubmit="return confirm('Approve Dr. {{ addslashes($doctor->name) }}?')">
                @csrf
                <button type="submit" class="mc-btn mc-btn-approve">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form method="POST"
                  action="{{ route('medical_centre.doctors.reject', $doctor->workplace_id) }}"
                  style="margin:0;"
                  onsubmit="return confirm('Reject Dr. {{ addslashes($doctor->name) }}?')">
                @csrf
                <button type="submit" class="mc-btn mc-btn-reject">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
        @elseif($doctor->workplace_status === 'approved')
            <form method="POST"
                  action="{{ route('medical_centre.doctors.reject', $doctor->workplace_id) }}"
                  style="margin:0;"
                  onsubmit="return confirm('Reject Dr. {{ addslashes($doctor->name) }}?')">
                @csrf
                <button type="submit" class="mc-btn mc-btn-reject">
                    <i class="fas fa-ban"></i> Reject
                </button>
            </form>
        @endif

        <form method="POST"
              action="{{ route('medical_centre.doctors.remove', $doctor->workplace_id) }}"
              style="margin:0;"
              onsubmit="return confirm('Remove Dr. {{ addslashes($doctor->name) }} from your medical centre?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="mc-btn mc-btn-remove">
                <i class="fas fa-trash-alt"></i> Remove
            </button>
        </form>
    </div>
</div>

{{-- ── Alerts ── --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#d1fae5;color:#065f46;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert"
         style="border-radius:10px;font-size:.83rem;border:none;background:#fee2e2;color:#991b1b;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Main Grid ── --}}
<div class="doc-show-grid">

    {{-- ── LEFT: Profile Card ── --}}
    <div>
        <div class="doc-profile-card">
            <div class="doc-profile-banner"></div>
            <div class="doc-profile-body">
                <div class="doc-profile-avatar-wrap">
                    <div class="doc-profile-avatar">
                        @if($doctor->profile_image)
                            <img src="{{ asset('storage/' . $doctor->profile_image) }}"
                                 alt="Dr. {{ $doctor->name }}">
                        @else
                            {{ strtoupper(substr($doctor->name, 0, 1)) }}
                        @endif
                    </div>
                </div>

                <p class="doc-profile-name">Dr. {{ $doctor->name }}</p>
                <p class="doc-profile-spec">{{ $doctor->specialization ?? '—' }}</p>

                <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.75rem;">
                    <span class="doc-badge badge-{{ $doctor->workplace_status }}">
                        @if($doctor->workplace_status === 'approved') <i class="fas fa-check-circle"></i>
                        @elseif($doctor->workplace_status === 'pending') <i class="fas fa-clock"></i>
                        @else <i class="fas fa-times-circle"></i>
                        @endif
                        {{ ucfirst($doctor->workplace_status) }}
                    </span>
                    <span class="doc-badge badge-{{ $doctor->employment_type }}">
                        {{ ucfirst($doctor->employment_type) }}
                    </span>
                </div>

                <div class="doc-rating-row">
                    <i class="fas fa-star"></i>
                    {{ number_format($doctor->rating, 1) }}
                    <span>({{ $doctor->total_ratings }} ratings)</span>
                </div>

                <div class="doc-profile-divider"></div>

                <div class="doc-profile-meta">
                    <div class="doc-meta-row">
                        <i class="fas fa-id-card"></i>
                        <span>SLMC: <strong>{{ $doctor->slmc_number }}</strong></span>
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-phone"></i>
                        <span>{{ $doctor->phone ?? '—' }}</span>
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-briefcase"></i>
                        <span><strong>{{ $doctor->experience_years ?? 0 }}</strong> years experience</span>
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Fee: <strong>LKR {{ $doctor->consultation_fee ? number_format($doctor->consultation_fee) : '—' }}</strong></span>
                    </div>
                    <div class="doc-meta-row">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Joined: <strong>{{ \Carbon\Carbon::parse($doctor->joined_at)->format('M d, Y') }}</strong></span>
                    </div>
                    @if($doctor->approved_at)
                        <div class="doc-meta-row">
                            <i class="fas fa-check"></i>
                            <span>Approved: <strong>{{ \Carbon\Carbon::parse($doctor->approved_at)->format('M d, Y') }}</strong></span>
                        </div>
                    @endif
                </div>

                @if($doctor->bio)
                    <div class="doc-profile-divider"></div>
                    <p style="font-size:.78rem;color:var(--text-muted);line-height:1.6;margin:0;">
                        {{ $doctor->bio }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Details ── --}}
    <div>

        {{-- Appointment Stats --}}
        <div class="doc-info-card">
            <div class="doc-info-card-head">
                <i class="fas fa-chart-bar"></i>
                <h6>Appointment Statistics</h6>
            </div>
            <div class="doc-info-card-body">
                <div class="doc-mini-stats">
                    <div class="doc-mini-stat">
                        <div class="val">{{ $appointmentStats['total'] }}</div>
                        <div class="lbl">Total</div>
                    </div>
                    <div class="doc-mini-stat">
                        <div class="val" style="color:#f59e0b;">{{ $appointmentStats['pending'] }}</div>
                        <div class="lbl">Pending</div>
                    </div>
                    <div class="doc-mini-stat">
                        <div class="val" style="color:#059669;">{{ $appointmentStats['completed'] }}</div>
                        <div class="lbl">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Qualifications --}}
        @if($doctor->qualifications)
            <div class="doc-info-card">
                <div class="doc-info-card-head">
                    <i class="fas fa-graduation-cap"></i>
                    <h6>Qualifications</h6>
                </div>
                <div class="doc-info-card-body">
                    <p style="font-size:.82rem;color:var(--text-dark);line-height:1.7;margin:0;">
                        {{ $doctor->qualifications }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Schedules --}}
        <div class="doc-info-card">
            <div class="doc-info-card-head">
                <i class="fas fa-calendar-alt"></i>
                <h6>Weekly Schedule</h6>
            </div>
            <div class="doc-info-card-body" style="padding:0;">
                @if($schedules->count())
                    <div style="overflow-x:auto;">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Max Apts</th>
                                    <th>Fee (LKR)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $sch)
                                    <tr>
                                        <td><strong>{{ ucfirst($sch->day_of_week) }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}</td>
                                        <td>{{ $sch->max_appointments }}</td>
                                        <td>{{ $sch->consultation_fee ? number_format($sch->consultation_fee) : '—' }}</td>
                                        <td>
                                            @if($sch->is_active)
                                                <span class="doc-badge badge-approved" style="font-size:.63rem;">Active</span>
                                            @else
                                                <span class="doc-badge badge-rejected" style="font-size:.63rem;">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="doc-empty-sm">
                        <i class="fas fa-calendar-times"></i>
                        <p>No schedules found for this medical centre.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Appointments --}}
        <div class="doc-info-card">
            <div class="doc-info-card-head">
                <i class="fas fa-calendar-check"></i>
                <h6>Recent Appointments
                    <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;margin-left:.4rem;">
                        (latest 10)
                    </span>
                </h6>
            </div>
            <div class="doc-info-card-body" style="padding:0;">
                @if($appointments->count())
                    <div style="overflow-x:auto;">
                        <table class="apt-table">
                            <thead>
                                <tr>
                                    <th>Apt No.</th>
                                    <th>Patient</th>
                                    <th>Date & Time</th>
                                    <th>Fee</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $apt)
                                    <tr>
                                        <td>
                                            <span style="font-size:.72rem;font-weight:700;color:var(--mc-primary);">
                                                {{ $apt->appointment_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight:700;color:var(--text-dark);">{{ $apt->patient_name }}</span><br>
                                            <span style="font-size:.68rem;color:var(--text-muted);">{{ $apt->patient_phone }}</span>
                                        </td>
                                        <td>
                                            <span style="font-weight:600;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</span><br>
                                            <span style="font-size:.7rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</span>
                                        </td>
                                        <td>
                                            <span style="font-weight:700;">LKR {{ $apt->consultation_fee ? number_format($apt->consultation_fee) : '—' }}</span>
                                        </td>
                                        <td>
                                            <span class="apt-pay-badge pay-{{ $apt->payment_status }}">
                                                {{ ucfirst($apt->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php $s = str_replace('-', '', $apt->status); @endphp
                                            <span class="apt-status-badge apt-{{ $s }}">
                                                {{ ucfirst($apt->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="doc-empty-sm">
                        <i class="fas fa-calendar-times"></i>
                        <p>No appointments found at this medical centre.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
