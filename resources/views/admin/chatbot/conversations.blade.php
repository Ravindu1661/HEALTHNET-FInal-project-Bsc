@extends('admin.layouts.master')

@section('title', 'Chatbot Conversations')
@section('page-title', 'Chatbot Conversations')

@section('content')
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
                <div class="hn-stat-label">Active</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="hn-stat-card hn-stat-info">
            <div class="hn-stat-icon"><i class="fas fa-headset"></i></div>
            <div class="hn-stat-body">
                <div class="hn-stat-num">{{ $stats['admin_mode'] ?? 0 }}</div>
                <div class="hn-stat-label">Live Chat (Admin)</div>
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

{{-- Filter --}}
<div class="dashboard-card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.chatbot.conversations') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search name, email, org..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['active','closed'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="mode" class="form-select form-select-sm">
                    <option value="">All Modes</option>
                    <option value="bot"   {{ request('mode') === 'bot' ? 'selected' : '' }}>Bot</option>
                    <option value="admin" {{ request('mode') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','mode']))
                    <a href="{{ route('admin.chatbot.conversations') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- WhatsApp-style list --}}
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-comments me-2 text-success"></i>All Conversations</h6>
        <small class="text-muted">Latest first</small>
    </div>
    <div class="card-body p-0">
        @if($conversations->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-comment-slash fa-3x mb-2 d-block opacity-50"></i>
                <p class="mb-0">No conversations yet.</p>
            </div>
        @else
            <div class="list-group list-group-flush hn-chat-list">
                @foreach($conversations as $c)
                    <a href="{{ route('admin.chatbot.conversations.show', $c->id) }}"
                       class="list-group-item list-group-item-action hn-chat-item">
                        <div class="d-flex">
                            {{-- Avatar --}}
                            <div class="hn-chat-avatar me-3">
                                <span>
                                    {{ strtoupper(substr($c->display_name,0,1)) }}
                                </span>
                                @if(($c->unread_count ?? 0) > 0)
                                    <span class="hn-chat-badge">{{ $c->unread_count }}</span>
                                @endif
                            </div>

                            {{-- Middle --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fw-semibold hn-chat-name">
                                        {{ $c->display_name }}
                                        @if($c->user_type)
                                            <span class="badge bg-light text-muted border ms-2 text-uppercase"
                                                  style="font-size: 10px;">
                                                {{ $c->user_type }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ms-auto text-muted small">
                                        @if($c->last_message_at)
                                            {{ \Illuminate\Support\Carbon::parse($c->last_message_at)->format('M d, h:i A') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="hn-chat-last text-muted">
                                        @if($c->last_message)
                                            {{ \Illuminate\Support\Str::limit($c->last_message, 80) }}
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

            <div class="px-3 py-2 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $conversations->firstItem() ?? 0 }} -
                    {{ $conversations->lastItem() ?? 0 }} of
                    {{ $conversations->total() }}
                </small>
                {{ $conversations->appends(request()->query())->links() }}
            </div>
        @endif
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

.hn-chat-list{max-height: calc(100vh - 260px); overflow-y:auto;}
.hn-chat-item{padding:.65rem 1rem;}
.hn-chat-avatar{
    position:relative;width:42px;height:42px;border-radius:50%;
    background:linear-gradient(135deg,#4caf50,#66bb6a);
    color:#fff;display:flex;align-items:center;justify-content:center;
    font-weight:700;font-size:.9rem;flex-shrink:0;
}
.hn-chat-badge{
    position:absolute;top:-3px;right:-3px;
    background:#d32f2f;color:#fff;border-radius:50%;
    font-size:10px;width:18px;height:18px;
    display:flex;align-items:center;justify-content:center;
}
.hn-chat-name{font-size:.9rem;}
.hn-chat-last{font-size:.8rem;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
</style>
@endpush
