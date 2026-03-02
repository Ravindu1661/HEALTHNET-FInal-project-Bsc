@extends('admin.layouts.master')

@section('title', 'Lab Orders Management')
@section('page-title', 'Lab Orders Management')

@section('content')
@php
    $statusMap = [
        'pending'          => ['bg-warning text-dark',  'fa-clock',        'Pending'],
        'sample_collected' => ['bg-info text-white',    'fa-vial',         'Sample Collected'],
        'processing'       => ['bg-primary text-white', 'fa-microscope',   'Processing'],
        'completed'        => ['bg-success text-white', 'fa-check-circle', 'Completed'],
        'cancelled'        => ['bg-danger text-white',  'fa-times-circle', 'Cancelled'],
    ];
    $payMap = [
        'paid'   => ['bg-success text-white', 'Paid'],
        'unpaid' => ['bg-danger text-white',  'Unpaid'],
    ];
@endphp

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'All Orders',       'count'=>$counts['all'],             'icon'=>'fa-list',            'bg'=>'bg-info bg-opacity-10',    'ic'=>'text-info',    'q'=>['status'=>'']],
            ['label'=>'Pending',          'count'=>$counts['pending'],         'icon'=>'fa-clock',           'bg'=>'bg-warning bg-opacity-10', 'ic'=>'text-warning', 'q'=>['status'=>'pending']],
            ['label'=>'Sample Collected', 'count'=>$counts['sample_collected'],'icon'=>'fa-vial',            'bg'=>'bg-info bg-opacity-10',    'ic'=>'text-info',    'q'=>['status'=>'sample_collected']],
            ['label'=>'Processing',       'count'=>$counts['processing'],      'icon'=>'fa-microscope',      'bg'=>'bg-primary bg-opacity-10', 'ic'=>'text-primary', 'q'=>['status'=>'processing']],
            ['label'=>'Completed',        'count'=>$counts['completed'],       'icon'=>'fa-check-circle',    'bg'=>'bg-success bg-opacity-10', 'ic'=>'text-success', 'q'=>['status'=>'completed']],
            ['label'=>'Cancelled',        'count'=>$counts['cancelled'],       'icon'=>'fa-times-circle',    'bg'=>'bg-danger bg-opacity-10',  'ic'=>'text-danger',  'q'=>['status'=>'cancelled']],
            ['label'=>'Home Collection',  'count'=>$counts['home_collection'], 'icon'=>'fa-home',            'bg'=>'bg-secondary bg-opacity-10','ic'=>'text-secondary','q'=>['home'=>'1','status'=>'']],
            ['label'=>'Unpaid',           'count'=>$counts['unpaid'],          'icon'=>'fa-exclamation-circle','bg'=>'bg-danger bg-opacity-10','ic'=>'text-danger',  'q'=>['payment'=>'unpaid','status'=>'']],
        ];
    @endphp

    @foreach($statCards as $card)
    @php
        $isActive = false;
        if (isset($card['q']['status']) && $card['q']['status'] !== '' && request('status') === $card['q']['status']) $isActive = true;
        if (isset($card['q']['status']) && $card['q']['status'] === '' && !request('status') && !request('home') && !request('payment')) $isActive = true;
        if (isset($card['q']['home']) && request('home')) $isActive = true;
        if (isset($card['q']['payment']) && request('payment') === 'unpaid') $isActive = true;
    @endphp
    <div class="col-6 col-md-3">
        <a href="{{ request()->fullUrlWithQuery($card['q']) }}"
           class="dashboard-card text-decoration-none d-block p-3 {{ $isActive ? 'border-primary' : '' }}"
           style="{{ $isActive ? 'border:2px solid var(--admin-primary,#0d6efd)!important;' : '' }}">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 {{ $card['bg'] }}">
                    <i class="fas {{ $card['icon'] }} {{ $card['ic'] }} fs-5"></i>
                </div>
                <div>
                    <div class="fw-900 fs-5 lh-1 text-dark">{{ $card['count'] }}</div>
                    <div class="text-muted" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                        {{ $card['label'] }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- ── Filters Card ── --}}
<div class="dashboard-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-flask me-2"></i>All Lab Orders</h6>
        <span class="badge bg-secondary">{{ $orders->total() }} records</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.lab-orders.index') }}" method="GET" class="row g-2 mb-0">

            {{-- Search --}}
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Order no / Patient / Lab..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Laboratory --}}
            <div class="col-md-2">
                <select name="lab" class="form-select form-select-sm">
                    <option value="">All Laboratories</option>
                    @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}" {{ request('lab') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending"          {{ request('status') === 'pending'          ? 'selected' : '' }}>Pending</option>
                    <option value="sample_collected" {{ request('status') === 'sample_collected' ? 'selected' : '' }}>Sample Collected</option>
                    <option value="processing"       {{ request('status') === 'processing'       ? 'selected' : '' }}>Processing</option>
                    <option value="completed"        {{ request('status') === 'completed'        ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled"        {{ request('status') === 'cancelled'        ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Payment --}}
            <div class="col-md-1">
                <select name="payment" class="form-select form-select-sm">
                    <option value="">Payment</option>
                    <option value="paid"   {{ request('payment') === 'paid'   ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('payment') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>

            {{-- Date From --}}
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}" title="From">
            </div>

            {{-- Date To --}}
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}" title="To">
            </div>

            {{-- Buttons --}}
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.lab-orders.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>

        </form>
    </div>
</div>

{{-- ── Table Card ── --}}
<div class="dashboard-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Order Details</th>
                        <th>Patient</th>
                        <th>Laboratory</th>
                        <th>Tests / Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                @php
                    [$stClass, $stIcon, $stLabel] = $statusMap[$order->status]       ?? ['bg-secondary text-white','fa-circle','Unknown'];
                    [$pyClass, $pyLabel]           = $payMap[$order->payment_status]  ?? ['bg-secondary text-white','Unknown'];
                    $patient = $order->patient;
                    $pName   = $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'N/A';
                    $pInit   = strtoupper(substr($pName, 0, 1));
                @endphp
                <tr>
                    {{-- # --}}
                    <td class="text-muted fw-bold" style="font-size:.78rem;">
                        {{ $orders->firstItem() + $loop->index }}
                    </td>

                    {{-- Order Details --}}
                    <td>
                        <span class="badge bg-info mb-1">{{ $order->order_number }}</span><br>
                        <small class="text-muted">Ref: {{ $order->reference_number }}</small>
                        @if($order->home_collection)
                            <br><span class="badge bg-primary bg-opacity-10 text-primary mt-1" style="font-size:.65rem;">
                                <i class="fas fa-home"></i> Home Collection
                            </span>
                        @endif
                    </td>

                    {{-- Patient --}}
                <td>
                    <div class="d-flex align-items-center">
                        @php
                            $patientImg = ($patient && $patient->profile_image)
                                ? asset('storage/' . $patient->profile_image)
                                : null;
                        @endphp

                        @if($patientImg)
                            <img src="{{ $patientImg }}"
                                class="rounded-circle me-2 flex-shrink-0"
                                width="35" height="35"
                                style="object-fit:cover;"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                                alt="{{ $pName }}">
                            {{-- Fallback avatar (hidden unless image fails) --}}
                            <div class="rounded-circle d-none align-items-center justify-content-center me-2 flex-shrink-0"
                                style="width:35px;height:35px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:.78rem;font-weight:800;">
                                {{ $pInit }}
                            </div>
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                style="width:35px;height:35px;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:.78rem;font-weight:800;">
                                {{ $pInit }}
                            </div>
                        @endif

                        <div>
                            <strong>{{ $pName }}</strong><br>
                            @if($patient && $patient->user)
                                <small class="text-muted">{{ $patient->user->email }}</small>
                            @endif
                        </div>
                    </div>
                </td>


                    {{-- Laboratory --}}
                    <td>
                        @if($order->laboratory)
                            <strong>{{ $order->laboratory->name }}</strong><br>
                            <small class="text-muted">{{ $order->laboratory->city ?? '' }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Tests --}}
                    <td>
                        <strong style="font-size:.82rem;">{{ $order->items->count() }} item(s)</strong>
                        @foreach($order->items->take(2) as $item)
                            <br><small class="text-muted" style="max-width:160px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                • {{ $item->item_name }}
                            </small>
                        @endforeach
                        @if($order->items->count() > 2)
                            <br><small class="text-primary fw-bold">+{{ $order->items->count() - 2 }} more</small>
                        @endif
                    </td>

                    {{-- Amount --}}
                    <td>
                        <strong>LKR {{ number_format($order->total_amount, 2) }}</strong>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge {{ $stClass }}">
                            <i class="fas {{ $stIcon }} me-1"></i>{{ $stLabel }}
                        </span>
                    </td>

                    {{-- Payment --}}
                    <td>
                        <span class="badge {{ $pyClass }}">{{ $pyLabel }}</span>
                    </td>

                    {{-- Date --}}
                    <td>
                        <i class="fas fa-calendar me-1 text-muted"></i>
                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}<br>
                        <i class="fas fa-clock me-1 text-muted"></i>
                        <small>{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</small>
                    </td>

                    {{-- Actions --}}
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.lab-orders.show', $order->id) }}"
                               class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->report_file)
                                <a href="{{ asset('storage/' . $order->report_file) }}"
                                   target="_blank"
                                   class="btn btn-success" title="Download Report" data-bs-toggle="tooltip">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <h5 class="text-muted mb-1">
                            <i class="fas fa-flask opacity-25 d-block mb-2" style="font-size:2rem;"></i>
                            No lab orders found
                        </h5>
                        <p class="text-muted mb-2">Try changing filters or search text.</p>
                        @if(request()->anyFilled(['search','status','payment','lab','date_from','date_to','home']))
                            <a href="{{ route('admin.lab-orders.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        @endif
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 px-3 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted" style="font-size:.82rem;">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }}
                of {{ $orders->total() }} entries
            </div>
            <div>{{ $orders->withQueryString()->links() }}</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tooltip init
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
</script>
@endpush
