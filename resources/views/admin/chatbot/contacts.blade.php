@extends('admin.layouts.master')
@section('title', 'Support Requests')
@section('page-title', 'Support Requests')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total',   'value'=>$stats->total       ?? 0, 'color'=>'primary',   'icon'=>'envelope'],
        ['label'=>'Pending', 'value'=>$stats->pending     ?? 0, 'color'=>'warning',   'icon'=>'clock'],
        ['label'=>'Read',    'value'=>$stats->read_count  ?? 0, 'color'=>'info',      'icon'=>'eye'],
        ['label'=>'Replied', 'value'=>$stats->replied     ?? 0, 'color'=>'success',   'icon'=>'check-circle'],
        ['label'=>'Closed',  'value'=>$stats->closed      ?? 0, 'color'=>'secondary', 'icon'=>'times-circle'],
    ] as $s)
    <div class="col">
        <div class="hn-stat-card hn-stat-{{ $s['color'] }}">
            <div class="hn-stat-icon"><i class="fas fa-{{ $s['icon'] }}"></i></div>
            <div class="hn-stat-body">
                <div class="hn-stat-num">{{ number_format($s['value']) }}</div>
                <div class="hn-stat-label">{{ $s['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.chatbot.contacts') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search name, email, subject..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['pending','read','replied','closed'] as $st)
                    <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button></div>
            <div class="col-auto"><a href="{{ route('admin.chatbot.contacts') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-headset me-2"></i>Support Requests</h6>
        <span class="badge bg-primary">{{ $contacts->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="hn-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>From</th>
                        <th>Subject / Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="130" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $c)
                    <tr>
                        <td class="text-muted small">{{ $c->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="hn-tbl-avatar">{{ strtoupper(substr($c->name??'U',0,1)) }}</div>
                                <div>
                                    <div class="fw-bold" style="font-size:.83rem">{{ $c->name }}</div>
                                    <small class="text-muted">{{ $c->email }}</small>
                                    @if($c->phone)<br><small class="text-muted"><i class="fas fa-phone"></i> {{ $c->phone }}</small>@endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-500" style="font-size:.83rem">{{ Str::limit($c->subject,42) }}</div>
                            <small class="text-muted">{{ Str::limit($c->message,60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $c->status==='pending'?'warning text-dark':($c->status==='read'?'info':($c->status==='replied'?'success':'secondary')) }}">
                                {{ ucfirst($c->status) }}
                            </span>
                            @if($c->replied_at)<br><small class="text-muted">{{ \Carbon\Carbon::parse($c->replied_at)->format('M d h:i A') }}</small>@endif
                        </td>
                        <td>
                            <small>{{ \Carbon\Carbon::parse($c->created_at)->format('M d, Y') }}</small>
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($c->created_at)->format('h:i A') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.chatbot.contacts.show', $c->id) }}" class="btn btn-info" title="View & Reply" data-bs-toggle="tooltip"><i class="fas fa-eye"></i></a>
                                @if($c->status !== 'closed')
                                <button onclick="closeContact({{ $c->id }})" class="btn btn-secondary" title="Close" data-bs-toggle="tooltip"><i class="fas fa-times"></i></button>
                                @endif
                                <button onclick="deleteContact({{ $c->id }},'{{ addslashes($c->name) }}')" class="btn btn-danger" title="Delete" data-bs-toggle="tooltip"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-2 d-block"></i>No support requests found.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2 d-flex justify-content-between align-items-center border-top">
            <small class="text-muted">Showing {{ $contacts->firstItem()??0 }} – {{ $contacts->lastItem()??0 }} of {{ $contacts->total() }}</small>
            {{ $contacts->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hn-stat-card{display:flex;align-items:center;gap:10px;padding:.875rem;background:#fff;border-radius:10px;border-left:4px solid;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.hn-stat-primary{border-color:#1976d2}.hn-stat-info{border-color:#0288d1}.hn-stat-warning{border-color:#f57c00}.hn-stat-success{border-color:#388e3c}.hn-stat-secondary{border-color:#616161}
.hn-stat-icon{width:42px;height:42px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;flex-shrink:0}
.hn-stat-primary .hn-stat-icon{background:#1976d2}.hn-stat-info .hn-stat-icon{background:#0288d1}.hn-stat-warning .hn-stat-icon{background:#f57c00}.hn-stat-success .hn-stat-icon{background:#388e3c}.hn-stat-secondary .hn-stat-icon{background:#616161}
.hn-stat-num{font-size:1.25rem;font-weight:700;color:#212121;line-height:1}.hn-stat-label{font-size:.7rem;color:#888;font-weight:500;margin-top:2px}
.hn-table{width:100%;border-collapse:collapse}
.hn-table thead th{background:#f8f9fa;padding:.75rem 1rem;font-size:.74rem;text-transform:uppercase;font-weight:600;color:#555;border-bottom:2px solid #e0e0e0}
.hn-table tbody td{padding:.75rem 1rem;vertical-align:middle;border-bottom:1px solid #f0f0f0;font-size:.83rem}
.hn-table tbody tr:hover{background:#fafafa}
.hn-tbl-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1565c0,#1976d2);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0}
</style>
@endpush

@push('scripts')
<script>
const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:1800,timerProgressBar:true});
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function closeContact(id){
    Swal.fire({title:'Close Request?',icon:'question',showCancelButton:true,confirmButtonColor:'#616161',confirmButtonText:'Close it'})
    .then(r=>{if(!r.isConfirmed)return;
        fetch(`/admin/chatbot/contacts/${id}/status`,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({status:'closed'})})
        .then(r=>r.json()).then(d=>{if(d.success){Toast.fire({icon:'success',title:'Closed!'});setTimeout(()=>location.reload(),900);}});
    });
}

function deleteContact(id,name){
    Swal.fire({title:'Delete Request?',html:`<small>${name}</small><br><small class="text-danger">Cannot be undone!</small>`,icon:'error',showCancelButton:true,confirmButtonColor:'#dc3545',confirmButtonText:'Delete'})
    .then(r=>{if(!r.isConfirmed)return;
        fetch(`/admin/chatbot/contacts/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf}})
        .then(r=>r.json()).then(d=>{if(d.success){Toast.fire({icon:'success',title:'Deleted!'});setTimeout(()=>location.reload(),900);}});
    });
}
document.addEventListener('DOMContentLoaded',()=>document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>new bootstrap.Tooltip(el)));
</script>
@endpush
