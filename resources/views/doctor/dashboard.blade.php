@extends('doctor.layouts.master')

@section('title', 'Dashboard — HealthNet Doctor')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ── Hero ── */
.dash-hero {
    background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
    border-radius: 18px; padding: 1.6rem 1.8rem; color: #fff;
    position: relative; overflow: hidden; margin-bottom: 1.4rem;
}
.dash-hero::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width:180px; height:180px; background:rgba(255,255,255,.07); border-radius:50%;
}
.dash-hero::after {
    content:''; position:absolute; bottom:-40px; right:80px;
    width:110px; height:110px; background:rgba(255,255,255,.05); border-radius:50%;
}
.dash-hero-content { position:relative; z-index:1; }
.dash-hero h4 { margin:0; font-weight:800; font-size:1.2rem; }
.dash-hero p  { margin:.35rem 0 0; opacity:.85; font-size:.84rem; }

/* ── Stat Cards ── */
.stat-card {
    background:#fff; border-radius:16px; padding:1.25rem 1.3rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    display:flex; align-items:center; gap:.9rem;
    transition:transform .2s, box-shadow .2s; height:100%;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 22px rgba(0,0,0,.1); }
.stat-icon {
    width:52px; height:52px; border-radius:13px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.25rem;
}
.si-blue   { background:rgba(13,110,253,.12);  color:#0d6efd; }
.si-green  { background:rgba(25,135,84,.12);   color:#198754; }
.si-orange { background:rgba(253,126,20,.12);  color:#fd7e14; }
.si-purple { background:rgba(111,66,193,.12);  color:#6f42c1; }
.stat-num  { font-size:1.6rem; font-weight:800; color:#1a1a1a; line-height:1; }
.stat-lbl  { font-size:.73rem; color:#888; margin-top:.22rem; font-weight:500; }

/* ── Dashboard Cards ── */
.dc {
    background:#fff; border-radius:16px; padding:1.25rem 1.3rem;
    box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.2rem;
}
.dc-hd {
    font-size:.87rem; font-weight:700; color:#1a1a1a;
    padding-bottom:.7rem; border-bottom:1px solid #f0f3f8;
    margin-bottom:.95rem; display:flex; align-items:center; gap:.45rem;
}
.dc-hd i { color:#0d6efd; }
.dc-hd a { margin-left:auto; font-size:.73rem; color:#0d6efd; font-weight:600; text-decoration:none; }
.dc-hd a:hover { text-decoration:underline; }

/* ── Appointment Row ── */
.apt-row {
    display:flex; align-items:center; gap:.8rem;
    padding:.75rem 0; border-bottom:1px solid #f5f7fb;
}
.apt-row:last-child { border-bottom:none; padding-bottom:0; }
.apt-time {
    min-width:56px; text-align:center;
    background:rgba(13,110,253,.07); border-radius:9px;
    padding:.3rem .35rem; flex-shrink:0;
}
.apt-time .hr  { font-size:.83rem; font-weight:800; color:#0d6efd; line-height:1.1; }
.apt-time .ampm{ font-size:.58rem; font-weight:700; color:#0d6efd; }
.apt-av {
    width:37px; height:37px; border-radius:50%;
    background:linear-gradient(135deg,#0d6efd,#6f42c1);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:.82rem; font-weight:800; flex-shrink:0;
}
.apt-info { flex:1; min-width:0; }
.apt-name   { font-size:.84rem; font-weight:700; color:#1a1a1a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.apt-detail { font-size:.71rem; color:#888; margin-top:.08rem; }
.apt-fee    { font-size:.8rem; font-weight:700; color:#198754; flex-shrink:0; }

/* ── Status Pills ── */
.sp { display:inline-flex; align-items:center; padding:.18rem .6rem;
    border-radius:20px; font-size:.68rem; font-weight:700; flex-shrink:0; }
.sp.pending   { background:#fff3cd; color:#856404; }
.sp.confirmed { background:#d1ecf1; color:#0c5460; }
.sp.completed { background:#d4edda; color:#155724; }
.sp.cancelled { background:#f8d7da; color:#721c24; }
.sp.noshow    { background:#f0f0f0; color:#555; }

/* ── Empty State ── */
.es { text-align:center; padding:2.2rem 1rem; color:#c0c8d4; }
.es i { font-size:1.8rem; display:block; margin-bottom:.5rem; }
.es p { font-size:.8rem; margin:0; }

/* ── Status Blocks ── */
.sb-block {
    background:#f8f9fa; border-radius:12px; padding:.85rem .9rem;
    text-align:center; border-left:4px solid transparent;
}
.sb-num { font-size:1.45rem; font-weight:800; color:#1a1a1a; }
.sb-lbl { font-size:.7rem; color:#666; font-weight:600; }

/* ── Patient Row ── */
.pt-row {
    display:flex; align-items:center; gap:.7rem;
    padding:.65rem 0; border-bottom:1px solid #f5f7fb;
}
.pt-row:last-child { border-bottom:none; }
.pt-av {
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#198754,#0d6efd);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:.78rem; font-weight:800;
}

/* ── Review Row ── */
.rv-row { padding:.7rem 0; border-bottom:1px solid #f5f7fb; }
.rv-row:last-child { border-bottom:none; }
.rv-star     { color:#ffc107; font-size:.75rem; }
.rv-star.off { color:#e5e8ef; }

/* ── Workplace Row ── */
.wp-row {
    display:flex; align-items:center; gap:.7rem;
    padding:.6rem 0; border-bottom:1px solid #f5f7fb;
}
.wp-row:last-child { border-bottom:none; }
.wp-ico {
    width:36px; height:36px; border-radius:9px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.wp-hospital      { background:rgba(13,110,253,.1); color:#0d6efd; }
.wp-medicalcentre { background:rgba(25,135,84,.1);  color:#198754; }

/* ── Pending Banner ── */
.pend-banner {
    background:linear-gradient(135deg,#fff8e1,#fff3cd);
    border:1px solid #ffc107; border-radius:12px;
    padding:.85rem 1.1rem; margin-bottom:1.2rem;
    display:flex; align-items:center; gap:.75rem;
}
</style>
@endpush

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;font-size:.84rem">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;font-size:.84rem">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Pending Banner --}}
@if($doctor->status === 'pending')
<div class="pend-banner">
    <i class="fas fa-exclamation-triangle fa-lg" style="color:#856404;flex-shrink:0"></i>
    <div>
        <div style="font-size:.87rem;font-weight:700;color:#856404">Account Pending Approval</div>
        <div style="font-size:.76rem;color:#7a5f00;margin-top:.1rem">
            Your account is awaiting admin review. Some features may be restricted until approved.
        </div>
    </div>
</div>
@endif

{{-- Hero --}}
<div class="dash-hero">
    <div class="dash-hero-content">
        @php
            $hr = now('Asia/Colombo')->format('G');
            $gr = $hr < 12 ? 'Good Morning' : ($hr < 17 ? 'Good Afternoon' : 'Good Evening');
        @endphp
        <h4>{{ $gr }}, Dr. {{ $doctor->firstname ?? 'Doctor' }}! 👋</h4>
        <p>
            {{ now('Asia/Colombo')->format('l, d F Y') }}
            &nbsp;|&nbsp;
            @if($todayAppointments > 0)
                <strong>{{ $todayAppointments }}</strong> appointment(s) today
            @else
                No appointments today
            @endif
            @if($pendingCount > 0)
                &nbsp;&bull;&nbsp;<strong>{{ $pendingCount }}</strong> pending
            @endif
        </p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="stat-num" id="s-today">{{ $todayAppointments }}</div>
                <div class="stat-lbl">Today's Appointments</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-num" id="s-patients">{{ $totalPatients }}</div>
                <div class="stat-lbl">Total Patients</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="fas fa-coins"></i></div>
            <div>
                <div class="stat-num" id="s-earn" style="font-size:1.15rem">
                    Rs.{{ number_format($monthlyEarnings, 0) }}
                </div>
                <div class="stat-lbl">This Month's Earnings</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-star"></i></div>
            <div>
                <div class="stat-num" id="s-rating">{{ number_format($avgRating ?? 0, 1) }}</div>
                <div class="stat-lbl">Average Rating</div>
            </div>
        </div>
    </div>
</div>

{{-- Main Grid --}}
<div class="row g-3">

    {{-- ═══ LEFT ═══ --}}
    <div class="col-lg-8">

        {{-- Today's Appointments --}}
        <div class="dc">
            <div class="dc-hd">
                <i class="fas fa-calendar-check"></i> Today's Appointments
                <a href="{{ route('doctor.appointments.index') }}">View All →</a>
            </div>
            @forelse($todayList as $apt)
            <div class="apt-row">
                <div class="apt-time">
                    <div class="hr">{{ \Carbon\Carbon::parse($apt->time)->format('g:i') }}</div>
                    <div class="ampm">{{ \Carbon\Carbon::parse($apt->time)->format('A') }}</div>
                </div>
                <div class="apt-av">{{ strtoupper(substr($apt->patient_name ?? 'P', 0, 1)) }}</div>
                <div class="apt-info">
                    <div class="apt-name">{{ $apt->patient_name }}</div>
                    <div class="apt-detail">
                        <i class="fas fa-map-marker-alt me-1" style="color:#0d6efd;font-size:.68rem"></i>{{ $apt->location }}
                    </div>
                </div>
                <span class="sp {{ $apt->status }}">{{ ucfirst($apt->status) }}</span>
                <div class="apt-fee">Rs.{{ number_format($apt->consultation_fee ?? 0) }}</div>
            </div>
            @empty
            <div class="es">
                <i class="fas fa-calendar-times"></i>
                <p>No appointments scheduled for today</p>
            </div>
            @endforelse
        </div>

        {{-- Trend Chart --}}
        <div class="dc">
            <div class="dc-hd"><i class="fas fa-chart-bar"></i> Monthly Appointment Trend</div>
            <canvas id="trendChart" height="90"></canvas>
        </div>

        {{-- Appointment Status --}}
        <div class="dc">
            <div class="dc-hd"><i class="fas fa-chart-pie"></i> Appointment Status Overview</div>
            <div class="row g-2">
                @foreach([
                    ['label'=>'Pending',   'key'=>'pending',   'color'=>'#ffc107'],
                    ['label'=>'Confirmed', 'key'=>'confirmed', 'color'=>'#0dcaf0'],
                    ['label'=>'Completed', 'key'=>'completed', 'color'=>'#198754'],
                    ['label'=>'Cancelled', 'key'=>'cancelled', 'color'=>'#dc3545'],
                ] as $s)
                <div class="col-6 col-md-3">
                    <div class="sb-block" style="border-left-color:{{ $s['color'] }}">
                        <div class="sb-num">{{ $appointmentStats[$s['key']] }}</div>
                        <div class="sb-lbl">{{ $s['label'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-2 text-end" style="font-size:.76rem;color:#888">
                Total: <strong>{{ $appointmentStats['total'] }}</strong>
            </div>
        </div>

    </div>

    {{-- ═══ RIGHT ═══ --}}
    <div class="col-lg-4">

        {{-- Profile Card --}}
        <div class="dc text-center mb-3">
            @php
                $pImg = $doctor->profile_image
                    ? asset('storage/' . $doctor->profile_image)
                    : asset('images/default-avatar.png');
            @endphp
            <div style="position:relative;display:inline-block;margin-bottom:.7rem">
                <img src="{{ $pImg }}" alt="Profile"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e8f0fe"
                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                <span style="position:absolute;bottom:3px;right:3px;width:13px;height:13px;
                    background:{{ $doctor->status==='approved'?'#22c55e':'#ffc107' }};
                    border-radius:50%;border:2px solid #fff"></span>
            </div>
            <div style="font-weight:800;font-size:1.02rem">
                Dr. {{ $doctor->firstname }} {{ $doctor->lastname }}
            </div>
            <div style="font-size:.79rem;color:#0d6efd;font-weight:600;margin:.2rem 0">
                {{ $doctor->specialization ?? 'General Practitioner' }}
            </div>

            {{-- Stars --}}
            <div style="display:flex;justify-content:center;gap:.18rem;margin:.4rem 0">
                @for($i=1;$i<=5;$i++)
                    <i class="fas fa-star rv-star{{ $i>round($avgRating??0)?' off':'' }}"
                       style="font-size:.8rem"></i>
                @endfor
                <span style="font-size:.74rem;color:#888;margin-left:.2rem">
                    ({{ number_format($avgRating??0,1) }})
                </span>
            </div>

            {{-- Chips --}}
            <div style="display:flex;justify-content:center;gap:.4rem;flex-wrap:wrap;margin:.5rem 0 .8rem">
                @if($doctor->experience_years)
                <span style="background:#f0f4ff;color:#0d6efd;font-size:.7rem;font-weight:700;padding:.18rem .6rem;border-radius:20px">
                    <i class="fas fa-briefcase-medical me-1"></i>{{ $doctor->experience_years }}y exp
                </span>
                @endif
                @if($doctor->consultation_fee)
                <span style="background:#f0fff4;color:#198754;font-size:.7rem;font-weight:700;padding:.18rem .6rem;border-radius:20px">
                    Rs.{{ number_format($doctor->consultation_fee,0) }}/visit
                </span>
                @endif
                <span style="background:{{ $doctor->status==='approved'?'#d4edda':'#fff3cd' }};
                    color:{{ $doctor->status==='approved'?'#155724':'#856404' }};
                    font-size:.7rem;font-weight:700;padding:.18rem .6rem;border-radius:20px">
                    {{ ucfirst($doctor->status) }}
                </span>
            </div>

            <a href="{{ route('doctor.profile.edit') }}"
               class="btn btn-primary btn-sm w-100" style="border-radius:10px;font-size:.81rem">
                <i class="fas fa-user-edit me-1"></i> Edit Profile
            </a>
        </div>

        {{-- Workplaces --}}
        <div class="dc">
            <div class="dc-hd">
                <i class="fas fa-building"></i> My Workplaces
                <a href="{{ route('doctor.workplaces.index') }}">Manage →</a>
            </div>
            @forelse($workplaces as $wp)
            <div class="wp-row">
                <div class="wp-ico wp-{{ $wp->workplace_type }}">
                    <i class="fas fa-{{ $wp->workplace_type==='hospital'?'hospital':'clinic-medical' }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $wp->name }}
                    </div>
                    <div style="font-size:.7rem;color:#888">
                        {{ $wp->city }} &bull; {{ ucfirst(str_replace('_',' ',$wp->employment_type)) }}
                    </div>
                </div>
                <span style="font-size:.68rem;background:#d4edda;color:#155724;padding:.14rem .48rem;border-radius:20px;font-weight:700;flex-shrink:0">
                    Active
                </span>
            </div>
            @empty
            <div class="es" style="padding:1.3rem 0">
                <i class="fas fa-building" style="font-size:1.5rem"></i>
                <p>No approved workplaces</p>
                <a href="{{ route('doctor.workplaces.create') }}"
                   class="btn btn-outline-primary btn-sm mt-1" style="border-radius:8px;font-size:.77rem">
                    + Add Workplace
                </a>
            </div>
            @endforelse
        </div>

        {{-- Recent Patients --}}
        <div class="dc">
            <div class="dc-hd">
                <i class="fas fa-users"></i> Recent Patients
                <a href="{{ route('doctor.patients.index') }}">All →</a>
            </div>
            @forelse($recentPatients as $p)
            <div class="pt-row">
                <div class="pt-av">{{ strtoupper(substr($p->name??'P',0,1)) }}</div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $p->name }}
                    </div>
                    <div style="font-size:.7rem;color:#888">
                        Last: {{ \Carbon\Carbon::parse($p->last_visit)->format('d M Y') }}
                    </div>
                </div>
                <span style="font-size:.73rem;color:#0d6efd;font-weight:700;flex-shrink:0">
                    {{ $p->visit_count }} visits
                </span>
            </div>
            @empty
            <div class="es" style="padding:1.3rem 0">
                <i class="fas fa-users" style="font-size:1.5rem"></i>
                <p>No patients yet</p>
            </div>
            @endforelse
        </div>

        {{-- Recent Reviews --}}
        <div class="dc">
            <div class="dc-hd">
                <i class="fas fa-star"></i> Recent Reviews
                <a href="{{ route('doctor.reviews.index') }}">All →</a>
            </div>
            @forelse($recentReviews as $rv)
            <div class="rv-row">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="font-size:.81rem;font-weight:700">{{ $rv->patient_name }}</div>
                    <div>
                        @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star rv-star{{ $i>$rv->rating?' off':'' }}"></i>
                        @endfor
                    </div>
                </div>
                @if(!empty($rv->review))
                <div style="font-size:.74rem;color:#666;margin-top:.18rem;font-style:italic">
                    "{{ \Illuminate\Support\Str::limit($rv->review, 85) }}"
                </div>
                @endif
                <div style="font-size:.67rem;color:#aaa;margin-top:.22rem">
                    <i class="fas fa-clock me-1"></i>{{ $rv->date }}
                </div>
            </div>
            @empty
            <div class="es" style="padding:1.3rem 0">
                <i class="fas fa-star" style="font-size:1.5rem"></i>
                <p>No reviews yet</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Trend Chart ──
const td = @json($monthlyTrend);

new Chart(document.getElementById('trendChart'), {
    data: {
        labels: td.map(d => d.month),
        datasets: [
            {
                type: 'bar', label: 'Appointments',
                data: td.map(d => d.count),
                backgroundColor: 'rgba(13,110,253,.72)',
                borderRadius: 7, borderSkipped: false, order: 2,
            },
            {
                type: 'line', label: 'Earnings (Rs.)',
                data: td.map(d => d.earnings),
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,.08)',
                fill: true, tension: 0.42,
                pointBackgroundColor: '#198754',
                pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 5,
                yAxisID: 'y1', order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { labels: { font: { size: 11, family: 'Inter' }, boxWidth: 12, usePointStyle: true } },
            tooltip: {
                backgroundColor: '#1e293b',
                callbacks: {
                    label: c => c.dataset.yAxisID === 'y1'
                        ? ' Rs. ' + Number(c.raw).toLocaleString()
                        : ' ' + c.raw + ' appointments'
                }
            }
        },
        scales: {
            x:  { grid: { display: false }, ticks: { font: { size: 11 } } },
            y:  { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, stepSize: 1 } },
            y1: {
                position: 'right', beginAtZero: true,
                grid: { drawOnChartArea: false },
                ticks: { font: { size: 10 }, callback: v => 'Rs.' + (v >= 1000 ? (v/1000).toFixed(1)+'k' : v) }
            }
        }
    }
});

// ── Stats auto-refresh (5 min) ──
setInterval(() => {
    fetch('{{ route("doctor.dashboard.stats") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        document.getElementById('s-today').textContent    = d.today_appointments;
        document.getElementById('s-patients').textContent = d.total_patients;
        document.getElementById('s-earn').textContent     = 'Rs.' + parseInt(String(d.monthly_earnings).replace(/,/g,'')).toLocaleString();
        document.getElementById('s-rating').textContent   = d.avg_rating;
    }).catch(() => {});
}, 300000);

// ── Auto-dismiss alerts ──
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 5000);
});
</script>
@endpush
