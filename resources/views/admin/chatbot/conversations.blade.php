@extends('admin.layouts.master')

@section('title', 'Chatbot Conversations')
@section('page-title', 'Chatbot Conversations')

@section('content')

{{-- Stats --}}
@if(isset($stats))
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
@endif

{{-- Filter --}}
<div class="dashboard-card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.chatbot.conversations') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search name, email, org..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['active','closed'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="mode" class="form-select form-select-sm">
                    <option value="">All Modes</option>
                    <option value="bot"   {{ request('mode') === 'bot'   ? 'selected' : '' }}>Bot</option>
                    <option value="admin" {{ request('mode') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="user_type" class="form-select form-select-sm">
                    <option value="">All Users</option>
                    <option value="guest"  {{ request('user_type') === 'guest'  ? 'selected' : '' }}>Guest Only</option>
                    <option value="logged" {{ request('user_type') === 'logged' ? 'selected' : '' }}>Logged-in Only</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','status','mode','user_type']))
                    <a href="{{ route('admin.chatbot.conversations') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Conversation List --}}
<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-comments me-2 text-success"></i>All Conversations
        </h6>
        <small class="text-muted">Latest first</small>
    </div>
    <div class="card-body p-0">
        @if($conversations->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-comment-slash fa-3x mb-2 d-block opacity-50"></i>
                <p class="mb-0">No conversations found.</p>
            </div>
        @else
            <div class="list-group list-group-flush hn-chat-list">
                @foreach($conversations as $c)
                    @php
                        $isGuest   = !$c->user_id;
                        $hasEmail  = $isGuest ? $c->guest_email : $c->user_email;
                    @endphp
                    <a href="{{ route('admin.chatbot.conversations.show', $c->id) }}"
                       class="list-group-item list-group-item-action hn-chat-item {{ $isGuest ? 'hn-guest-row' : '' }}">
                        <div class="d-flex">

                            {{-- Avatar --}}
                            <div class="hn-chat-avatar me-3 {{ $isGuest ? 'hn-avatar-guest' : 'hn-avatar-user' }}">
                                <span>{{ strtoupper(substr($c->display_name, 0, 1)) }}</span>
                                @if(($c->unread_count ?? 0) > 0)
                                    <span class="hn-chat-badge">{{ $c->unread_count }}</span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-grow-1 min-width-0">

                                {{-- Row 1: Name + Time --}}
                                <div class="d-flex align-items-center mb-1 gap-1">
                                    <div class="fw-semibold hn-chat-name">
                                        {{ $c->display_name }}
                                    </div>

                                    {{-- User type badge --}}
                                    @if($isGuest)
                                        <span class="badge hn-badge-guest">
                                            <i class="fas fa-user-slash me-1"></i>Guest
                                        </span>
                                    @else
                                        <span class="badge hn-badge-logged text-uppercase"
                                              style="font-size:9px;">
                                            <i class="fas fa-user-check me-1"></i>{{ $c->user_type ?? 'user' }}
                                        </span>
                                    @endif

                                    <div class="ms-auto text-muted small flex-shrink-0">
                                        @if($c->last_message_at)
                                            {{ \Illuminate\Support\Carbon::parse($c->last_message_at)->format('M d, h:i A') }}
                                        @endif
                                    </div>
                                </div>

                                {{-- Row 2: Email --}}
                                <div class="mb-1">
                                    @if($isGuest)
                                        @if($hasEmail)
                                            <span class="hn-email-tag hn-email-guest">
                                                <i class="fas fa-envelope me-1"></i>{{ $hasEmail }}
                                                <span class="ms-1 opacity-75">(email reply)</span>
                                            </span>
                                        @else
                                            <span class="hn-email-tag hn-email-none">
                                                <i class="fas fa-envelope-slash me-1"></i>No email — reply stored only
                                            </span>
                                        @endif
                                    @else
                                        <span class="hn-email-tag hn-email-realtime">
                                            <i class="fas fa-bolt me-1"></i>Real-time delivery
                                            @if($hasEmail)
                                                <span class="ms-1 opacity-75">· {{ $hasEmail }}</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>

                                {{-- Row 3: Last message + Mode/Status badges --}}
                                <div class="d-flex align-items-center gap-1">
                                    <div class="hn-chat-last text-muted flex-grow-1">
                                        @if($c->last_message)
                                            {{ \Illuminate\Support\Str::limit($c->last_message, 70) }}
                                        @else
                                            <em>No messages yet</em>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-1 flex-shrink-0">
                                        <span class="badge rounded-pill
                                            {{ $c->mode === 'admin' ? 'bg-success' : 'bg-primary' }}"
                                              style="font-size:10px;">
                                            {{ $c->mode === 'admin' ? 'Admin' : 'Bot' }}
                                        </span>
                                        <span class="badge rounded-pill
                                            {{ $c->status === 'active' ? 'bg-info' : 'bg-secondary' }}"
                                              style="font-size:10px;">
                                            {{ ucfirst($c->status) }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="px-3 py-2 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Showing {{ $conversations->firstItem() ?? 0 }} –
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
/* ── Stat Cards ── */
.hn-stat-card {
    display: flex; align-items: center; gap: 10px;
    padding: .875rem; background: #fff; border-radius: 10px;
    border-left: 4px solid #1976d2; box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.hn-stat-primary { border-color: #1976d2; }
.hn-stat-success { border-color: #388e3c; }
.hn-stat-info    { border-color: #0288d1; }
.hn-stat-warning { border-color: #f57c00; }
.hn-stat-icon {
    width: 42px; height: 42px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff; flex-shrink: 0;
}
.hn-stat-primary .hn-stat-icon { background: #1976d2; }
.hn-stat-success .hn-stat-icon { background: #388e3c; }
.hn-stat-info    .hn-stat-icon { background: #0288d1; }
.hn-stat-warning .hn-stat-icon { background: #f57c00; }
.hn-stat-num   { font-size: 1.25rem; font-weight: 700; color: #212121; line-height: 1; }
.hn-stat-label { font-size: .7rem; color: #888; font-weight: 500; margin-top: 2px; }

/* ── Chat List ── */
.hn-chat-list { max-height: calc(100vh - 300px); overflow-y: auto; }
.hn-chat-item {
    padding: .75rem 1rem;
    transition: background 0.15s;
}
.hn-chat-item:hover { background: #f8faff; }
.hn-guest-row { border-left: 3px solid #f59e0b !important; }

/* ── Avatar ── */
.hn-chat-avatar {
    position: relative; width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .95rem; flex-shrink: 0;
}
.hn-avatar-user  { background: linear-gradient(135deg, #0d6efd, #0a58ca); color: #fff; }
.hn-avatar-guest { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }

.hn-chat-badge {
    position: absolute; top: -3px; right: -3px;
    background: #d32f2f; color: #fff; border-radius: 50%;
    font-size: 10px; width: 18px; height: 18px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
}

/* ── Name ── */
.hn-chat-name { font-size: .88rem; color: #1e293b; }
.min-width-0  { min-width: 0; }

/* ── User type badges ── */
.hn-badge-guest {
    background: #fef3c7; color: #92400e;
    border: 1px solid #fcd34d;
    font-size: 10px; font-weight: 600;
}
.hn-badge-logged {
    background: #dbeafe; color: #1e40af;
    border: 1px solid #93c5fd;
    font-size: 9px; font-weight: 600;
}

/* ── Email tags ── */
.hn-email-tag {
    display: inline-flex; align-items: center;
    font-size: 11px; font-weight: 500;
    padding: 2px 8px; border-radius: 20px;
}
.hn-email-guest   { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
.hn-email-none    { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
.hn-email-realtime{ background: #e8f0ff; color: #1e3a8a; border: 1px solid #93c5fd; }

/* ── Last message ── */
.hn-chat-last {
    font-size: .78rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    min-width: 0;
}
</style>
@endpush
