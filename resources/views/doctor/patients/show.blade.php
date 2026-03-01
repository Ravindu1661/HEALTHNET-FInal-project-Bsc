@extends('doctor.layouts.master')

@section('title', 'Patient Profile')
@section('page-title', 'Patient Profile')

@push('styles')
<style>
.detail-card { background:#fff; border-radius:16px; padding:1.4rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem; }
.dc-title { font-size:.82rem; font-weight:700; color:#1a1a1a;
    padding-bottom:.65rem; border-bottom:1px solid #f0f3f8;
    margin-bottom:1rem; display:flex; align-items:center; gap:.4rem; }
.dc-title i { color:#0d6efd; }

.info-row { display:flex; align-items:flex-start; gap:.5rem;
    padding:.5rem 0; border-bottom:1px solid #f8f9fb; font-size:.83rem; }
.info-row:last-child { border-bottom:none; }
.info-lbl { width:150px; flex-shrink:0; color:#888; font-weight:600; font-size:.75rem; }
.info-val { color:#1a1a1a; font-weight:500; }

.sp { display:inline-flex; align-items:center; padding:.22rem .7rem;
    border-radius:20px; font-size:.72rem; font-weight:700; gap:.3rem; }
.sp.pending   { background:#fff3cd; color:#856404; }
.sp.confirmed { background:#d1ecf1; color:#0c5460; }
.sp.completed { background:#d4edda; color:#155724; }
.sp.cancelled { background:#f8d7da; color:#721c24; }
.sp.no-show   { background:#f0f0f0; color:#555; }

.apt-row { padding:.65rem .8rem; border-radius:10px; border:1px solid #f0f3f8;
    margin-bottom:.5rem; font-size:.82rem; transition:all .15s; }
.apt-row:hover { background:#f8faff; border-color:#d0e0ff; }
.apt-row:last-child { margin-bottom:0; }

.stat-mini { background:#f8f9fb; border-radius:12px; padding:.8rem 1rem;
    text-align:center; }
.stat-mini .num { font-size:1.4rem; font-weight:800; color:#0d6efd; }
.stat-mini .lbl { font-size:.7rem; color:#888; font-weight:600; margin-top:.1rem; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav style="font-size:.78rem;margin-bottom:.8rem">
    <a href="{{ route('doctor.dashboard') }}" style="color:#0d6efd;text-decoration:none">Dashboard</a>
    <span class="mx-1 text-muted">/</span>
    <a href="{{ route('doctor.patients.index') }}" style="color:#0d6efd;text-decoration:none">My Patients</a>
    <span class="mx-1 text-muted">/</span>
    <span class="text-muted">{{ $patient->first_name }} {{ $patient->last_name }}</span>
</nav>

{{-- Back Button --}}
<div class="mb-3">
    <a href="{{ route('doctor.patients.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Patients
    </a>
    <a href="{{ route('doctor.patients.history', $patient->id) }}" class="btn btn-outline-primary btn-sm ms-2">
        <i class="fas fa-history me-1"></i>Full History
    </a>
</div>

<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-lg-4">

        {{-- Profile Card --}}
        <div class="detail-card text-center">
            @php
                $img = $patient->profile_image ?? null;
            @endphp

            @if($img)
                <img src="{{ asset('storage/' . $img) }}"
                     alt="{{ $patient->first_name }}"
                     class="rounded-circle mb-3"
                     style="width:90px;height:90px;object-fit:cover;border:4px solid #e8edf5"
                     onerror="this.style.display='none';
                              document.getElementById('pt-avatar').style.display='flex'">
                <div id="pt-avatar" style="width:90px;height:90px;border-radius:50%;
                    background:linear-gradient(135deg,#0d6efd,#6f42c1);
                    display:none;align-items:center;justify-content:center;
                    color:#fff;font-size:2rem;font-weight:800;margin:0 auto 1rem">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
            @else
                <div style="width:90px;height:90px;border-radius:50%;
                    background:linear-gradient(135deg,#0d6efd,#6f42c1);
                    display:flex;align-items:center;justify-content:center;
                    color:#fff;font-size:2rem;font-weight:800;margin:0 auto 1rem">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
            @endif

            <h5 style="font-weight:800;margin-bottom:.2rem">
                {{ $patient->first_name }} {{ $patient->last_name }}
            </h5>
            <p style="font-size:.78rem;color:#888;margin-bottom:.8rem">
                {{ $patient->email }}
            </p>

            {{-- Account Status --}}
            @php
                $statusColors = ['active'=>'success','pending'=>'warning','suspended'=>'danger','rejected'=>'secondary'];
                $sc = $statusColors[$patient->account_status] ?? 'secondary';
            @endphp
            <span class="badge bg-{{ $sc }} mb-3">
                {{ ucfirst($patient->account_status) }}
            </span>

            {{-- Quick Stats --}}
            <div class="row g-2 mt-1">
                <div class="col-4">
                    <div class="stat-mini">
                        <div class="num">{{ $appointments->count() }}</div>
                        <div class="lbl">Total</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-mini">
                        <div class="num" style="color:#198754">
                            {{ $appointments->where('status','completed')->count() }}
                        </div>
                        <div class="lbl">Done</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-mini">
                        <div class="num" style="color:#dc3545">
                            {{ $appointments->where('status','cancelled')->count() }}
                        </div>
                        <div class="lbl">Cancelled</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal Info --}}
        <div class="detail-card">
            <div class="dc-title"><i class="fas fa-user"></i>Personal Info</div>

            @if($patient->date_of_birth)
            <div class="info-row">
                <span class="info-lbl">Age</span>
                <span class="info-val">
                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years
                    <span style="color:#aaa;font-size:.72rem">
                        ({{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }})
                    </span>
                </span>
            </div>
            @endif

            @if($patient->gender)
            <div class="info-row">
                <span class="info-lbl">Gender</span>
                <span class="info-val">{{ ucfirst($patient->gender) }}</span>
            </div>
            @endif

            @if($patient->blood_group)
            <div class="info-row">
                <span class="info-lbl">Blood Group</span>
                <span class="info-val">
                    <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                </span>
            </div>
            @endif

            @if($patient->nic)
            <div class="info-row">
                <span class="info-lbl">NIC</span>
                <span class="info-val">{{ $patient->nic }}</span>
            </div>
            @endif

            @if($patient->phone)
            <div class="info-row">
                <span class="info-lbl">Phone</span>
                <span class="info-val">
                    <a href="tel:{{ $patient->phone }}" style="color:#0d6efd;text-decoration:none">
                        <i class="fas fa-phone me-1" style="font-size:.7rem"></i>{{ $patient->phone }}
                    </a>
                </span>
            </div>
            @endif

            @if($patient->address || $patient->city)
            <div class="info-row">
                <span class="info-lbl">Address</span>
                <span class="info-val">
                    {{ $patient->address ?? '' }}
                    @if($patient->city)
                        @if($patient->address), @endif
                        {{ $patient->city }}
                    @endif
                    @if($patient->province), {{ $patient->province }}@endif
                </span>
            </div>
            @endif

            <div class="info-row">
                <span class="info-lbl">Member Since</span>
                <span class="info-val">
                    {{ \Carbon\Carbon::parse($patient->created_at)->format('d M Y') }}
                </span>
            </div>
        </div>

    </div>

    {{-- RIGHT — Appointment History --}}
    <div class="col-lg-8">
        <div class="detail-card">
            <div class="dc-title">
                <i class="fas fa-calendar-check"></i>
                Appointment History with You
                <span class="ms-auto badge bg-primary" style="font-size:.65rem">
                    {{ $appointments->count() }} appointments
                </span>
            </div>

            @forelse($appointments as $apt)
            <div class="apt-row">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-weight:700;font-size:.83rem;color:#1a1a1a">
                            {{ \Carbon\Carbon::parse($apt->appointment_date)->format('d M Y') }}
                            <span style="color:#888;font-weight:400">
                                {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}
                            </span>
                        </div>
                        <div style="font-size:.75rem;color:#888;margin-top:.15rem">
                            <i class="fas fa-map-marker-alt me-1" style="font-size:.65rem"></i>
                            {{ $apt->location }}
                        </div>
                        @if($apt->reason)
                        <div style="font-size:.75rem;color:#666;margin-top:.2rem">
                            <i class="fas fa-notes-medical me-1" style="font-size:.65rem;color:#0d6efd"></i>
                            {{ $apt->reason }}
                        </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="sp {{ $apt->status }}">
                            {{ ucfirst(str_replace('_',' ',$apt->status)) }}
                        </span>
                        <span style="font-size:.72rem;font-weight:700;color:#198754">
                            LKR {{ number_format($apt->consultation_fee, 2) }}
                        </span>
                        <a href="{{ route('doctor.appointments.show', $apt->id) }}"
                           style="font-size:.7rem;color:#0d6efd;text-decoration:none">
                            View <i class="fas fa-arrow-right" style="font-size:.6rem"></i>
                        </a>
                    </div>
                </div>

                @if($apt->notes)
                <div style="margin-top:.5rem;padding:.4rem .6rem;background:#f8f9fb;
                    border-radius:8px;font-size:.74rem;color:#555;border-left:3px solid #0d6efd">
                    <i class="fas fa-sticky-note me-1" style="color:#0d6efd;font-size:.65rem"></i>
                    {{ $apt->notes }}
                </div>
                @endif
            </div>
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:#c0c8d4">
                <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                <p style="font-size:.85rem;font-weight:600">No appointments found</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
