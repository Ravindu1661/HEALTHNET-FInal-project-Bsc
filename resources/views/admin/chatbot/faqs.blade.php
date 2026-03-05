@extends('admin.layouts.master')

@section('title', 'Chatbot Dashboard')
@section('page-title', 'Chatbot Dashboard')

@section('content')
<div class="container-fluid py-3">

    {{-- Top stats --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="hn-stat-card hn-stat-primary">
                <div class="hn-stat-icon"><i class="fas fa-comments"></i></div>
                <div class="hn-stat-body">
                    <div class="hn-stat-num">{{ $stats['total'] ?? 0 }}</div>
                    <div class="hn-stat-label">Total Conversations</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="hn-stat-card hn-stat-success">
                <div class="hn-stat-icon"><i class="fas fa-signal"></i></div>
                <div class="hn-stat-body">
                    <div class="hn-stat-num">{{ $stats['active'] ?? 0 }}</div>
                    <div class="hn-stat-label">Active Conversations</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="hn-stat-card hn-stat-info">
                <div class="hn-stat-icon"><i class="fas fa-headset"></i></div>
                <div class="hn-stat-body">
                    <div class="hn-stat-num">{{ $stats['admin_mode'] ?? 0 }}</div>
                    <div class="hn-stat-label">In Admin Live Chat</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="hn-stat-card hn-stat-warning">
                <div class="hn-stat-icon"><i class="fas fa-envelope-open-text"></i></div>
                <div class="hn-stat-body">
                    <div class="hn-stat-num">{{ $stats['unread'] ?? 0 }}</div>
                    <div class="hn-stat-label">Unread User Messages</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-comments me-2 text-success"></i>
                    <h6 class="mb-0">Conversations</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        View and reply to all chatbot conversations in WhatsApp-style interface.
                    </p>
                    <a href="{{ route('admin.chatbot.conversations') }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-comments me-1"></i> Open Conversations
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-question-circle me-2 text-primary"></i>
                    <h6 class="mb-0">FAQs</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Manage common questions and answers shown in the chatbot FAQ tab.
                    </p>
                    <a href="{{ route('admin.chatbot.faqs') }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-list me-1"></i> Manage FAQs
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-link me-2 text-info"></i>
                    <h6 class="mb-0">Quick Links</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Configure navigation links (e.g. /patient/doctors) for chatbot “Links” tab.
                    </p>
                    <a href="{{ route('admin.chatbot.links') }}"
                       class="btn btn-info btn-sm text-white">
                        <i class="fas fa-link me-1"></i> Manage Quick Links
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent conversations (mini list) --}}
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-clock me-2 text-success"></i>Recent Conversations
            </h6>
            <a href="{{ route('admin.chatbot.conversations') }}"
               class="btn btn-outline-secondary btn-sm">
                View All
            </a>
        </div>
        <div class="card-body p-0">
            @if($conversations->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-comment-slash fa-2x mb-2 d-block opacity-50"></i>
                    No conversations yet.
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($conversations as $c)
                        <a href="{{ route('admin.chatbot.conversations.show', $c->id) }}"
                           class="list-group-item list-group-item-action">
                            <div class="d-flex">
                                <div class="hn-chat-avatar me-3">
                                    <span>{{ strtoupper(substr($c->display_name,0,1)) }}</span>
                                    @if(($c->unread_count ?? 0) > 0)
                                        <span class="hn-chat-badge">{{ $c->unread_count }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="fw-semibold hn-chat-name">
                                            {{ $c->display_name }}
                                        </div>
                                        <div class="ms-auto text-muted small">
                                            @if($c->last_message_at)
                                                {{ \Illuminate\Support\Carbon::parse($c->last_message_at)->diffForHumans() }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="hn-chat-last text-muted">
                                            @if($c->last_message)
                                                {{ \Illuminate\Support\Str::limit($c->last_message, 70) }}
                                            @else
                                                <em>No messages yet</em>
                                            @endif
                                        </div>
                                        <div class="ms-auto text-end">
                                            <span class="badge rounded-pill
                                                {{ $c->mode === 'admin' ? 'bg-success' : 'bg-primary' }} me-1"
                                                  style="font-size: 10px;">
                                                {{ $c->mode === 'admin' ? 'Admin' : 'Bot' }}
                                            </span>
                                            <span class="badge rounded-pill
                                                {{ $c->status === 'active' ? 'bg-info' : 'bg-secondary' }}"
                                                  style="font-size: 10px;">
                                                {{ ucfirst($c->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hn-stat-card{
    display:flex;align-items:center;gap:10px;
    padding:.875rem;background:#fff;border-radius:10px;
    border-left:4px solid #1976d2;box-shadow:0 2px 8px rgba(0,0,0,.06);
}
.hn-stat-primary{border-color:#1976d2;}
.hn-stat-success{border-color:#388e3c;}
.hn-stat-info{border-color:#0288d1;}
.hn-stat-warning{border-color:#f57c00;}
.hn-stat-icon{
    width:42px;height:42px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;color:#fff;flex-shrink:0;
}
.hn-stat-primary .hn-stat-icon{background:#1976d2;}
.hn-stat-success .hn-stat-icon{background:#388e3c;}
.hn-stat-info .hn-stat-icon{background:#0288d1;}
.hn-stat-warning .hn-stat-icon{background:#f57c00;}
.hn-stat-num{font-size:1.25rem;font-weight:700;color:#212121;line-height:1;}
.hn-stat-label{font-size:.7rem;color:#888;font-weight:500;margin-top:2px;}

.hn-chat-avatar{
    position:relative;width:38px;height:38px;border-radius:50%;
    background:linear-gradient(135deg,#4caf50,#66bb6a);
    color:#fff;display:flex;align-items:center;justify-content:center;
    font-weight:700;font-size:.85rem;flex-shrink:0;
}
.hn-chat-badge{
    position:absolute;top:-3px;right:-3px;
    background:#d32f2f;color:#fff;border-radius:50%;
    font-size:10px;width:18px;height:18px;
    display:flex;align-items:center;justify-content:center;
}
.hn-chat-name{font-size:.88rem;}
.hn-chat-last{font-size:.8rem;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
</style>
@endpush
