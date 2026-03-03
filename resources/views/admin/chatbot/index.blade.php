@extends('admin.layouts.master')
@section('title', 'Chatbot Management')
@section('page-title', 'Chatbot Management')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Conversations',  'value'=>$stats['total_conversations'],  'icon'=>'comments',       'color'=>'primary'],
        ['label'=>'Support Requests','value'=>$stats['total_contacts'],      'icon'=>'headset',        'color'=>'info'],
        ['label'=>'Pending',        'value'=>$stats['pending_contacts'],     'icon'=>'clock',          'color'=>'warning'],
        ['label'=>'Replied',        'value'=>$stats['replied_contacts'],     'icon'=>'check-circle',   'color'=>'success'],
        ['label'=>'AI Messages',    'value'=>$stats['ai_messages'],          'icon'=>'robot',          'color'=>'secondary'],
        ['label'=>'Today Chats',    'value'=>$stats['today_conversations'],  'icon'=>'calendar-day',   'color'=>'secondary'],
    ] as $s)
    <div class="col-md-2 col-6">
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

{{-- Quick Nav Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('admin.chatbot.contacts', ['status'=>'pending']) }}" class="hn-nav-card hn-nav-warning text-decoration-none">
            <i class="fas fa-headset fa-2x mb-2"></i>
            <div class="fw-bold fs-6">Support Requests</div>
            <small>{{ $stats['pending_contacts'] }} pending</small>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.chatbot.conversations') }}" class="hn-nav-card hn-nav-primary text-decoration-none">
            <i class="fas fa-comments fa-2x mb-2"></i>
            <div class="fw-bold fs-6">All Conversations</div>
            <small>{{ $stats['total_conversations'] }} total</small>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.chatbot.faqs') }}" class="hn-nav-card hn-nav-success text-decoration-none">
            <i class="fas fa-question-circle fa-2x mb-2"></i>
            <div class="fw-bold fs-6">FAQ Management</div>
            <small>{{ $stats['faq_messages'] }} FAQ matches</small>
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Pending Contacts --}}
    <div class="col-md-7">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-exclamation-circle text-warning me-2"></i>Pending Support Requests</h6>
                <a href="{{ route('admin.chatbot.contacts', ['status'=>'pending']) }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($pendingContacts as $c)
                <div class="hn-contact-row">
                    <div class="hn-contact-avatar">{{ strtoupper(substr($c->name ?? 'U', 0, 1)) }}</div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark" style="font-size:.84rem">{{ $c->name }}</div>
                        <div class="text-muted text-truncate" style="font-size:.75rem">{{ $c->subject }}</div>
                        <div class="text-muted" style="font-size:.7rem">
                            <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}
                        </div>
                    </div>
                    <a href="{{ route('admin.chatbot.contacts.show', $c->id) }}" class="btn btn-xs btn-primary ms-2 flex-shrink-0">
                        <i class="fas fa-reply"></i> Reply
                    </a>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-3x text-success mb-2 d-block"></i>
                    <p>No pending requests!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Contacts --}}
    <div class="col-md-5">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Requests</h6>
                <a href="{{ route('admin.chatbot.contacts') }}" class="btn btn-sm btn-outline-secondary">All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentContacts as $c)
                <div class="hn-contact-row">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold" style="font-size:.82rem">{{ $c->name }}</span>
                            <span class="badge bg-{{ $c->status==='pending'?'warning text-dark':($c->status==='replied'?'success':($c->status==='read'?'info':'secondary')) }} ms-1" style="font-size:.65rem">{{ ucfirst($c->status) }}</span>
                        </div>
                        <div class="text-muted text-truncate" style="font-size:.74rem">{{ Str::limit($c->subject, 40) }}</div>
                        <div class="text-muted" style="font-size:.7rem">{{ \Carbon\Carbon::parse($c->created_at)->format('M d, h:i A') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted"><p>No contacts yet.</p></div>
                @endforelse
            </div>
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
.hn-stat-num{font-size:1.3rem;font-weight:700;color:#212121;line-height:1}.hn-stat-label{font-size:.7rem;color:#888;font-weight:500;margin-top:2px}
.hn-nav-card{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem;border-radius:12px;color:#fff;transition:transform .15s,opacity .15s;text-align:center}
.hn-nav-card:hover{transform:translateY(-3px);color:#fff;opacity:.93}
.hn-nav-primary{background:linear-gradient(135deg,#1565c0,#1976d2)}.hn-nav-warning{background:linear-gradient(135deg,#e65100,#f57c00)}.hn-nav-success{background:linear-gradient(135deg,#2e7d32,#388e3c)}
.hn-contact-row{display:flex;align-items:center;padding:.75rem 1rem;border-bottom:1px solid #f5f5f5;gap:10px}
.hn-contact-row:last-child{border-bottom:none}
.hn-contact-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1565c0,#1976d2);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0}
.btn-xs{padding:.2rem .6rem;font-size:.74rem}
</style>
@endpush
