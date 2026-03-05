@extends('admin.layouts.master')
@section('title', 'Chatbot Dashboard')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="fas fa-robot me-2 text-primary"></i>Chatbot Dashboard</h4>
            <small class="text-muted">Manage conversations, FAQs and quick links</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.chatbot.faqs') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-question-circle me-1"></i> FAQs
            </a>
            <a href="{{ route('admin.chatbot.links') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-link me-1"></i> Quick Links
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total Conversations</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-success">{{ $stats['active'] }}</div>
                <div class="text-muted small">Active</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-warning">{{ $stats['admin_mode'] }}</div>
                <div class="text-muted small">Live Admin Chats</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-danger">{{ $stats['unread'] }}</div>
                <div class="text-muted small">Unread Messages</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active"  {{ request('status') === 'active'  ? 'selected' : '' }}>Active</option>
                        <option value="closed"  {{ request('status') === 'closed'  ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="mode" class="form-select form-select-sm">
                        <option value="">All Modes</option>
                        <option value="bot"   {{ request('mode') === 'bot'   ? 'selected' : '' }}>Bot</option>
                        <option value="admin" {{ request('mode') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.chatbot.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Conversations Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th>Last Message</th>
                            <th>Unread</th>
                            <th>Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $conv)
                        <tr>
                            <td class="text-muted small">{{ $conv->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $conv->display_name }}</div>
                                <div class="text-muted small">{{ $conv->display_email }}</div>
                            </td>
                            <td>
                                @if($conv->mode === 'admin')
                                    <span class="badge bg-success">
                                        <i class="fas fa-headset me-1"></i>Admin
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-robot me-1"></i>Bot
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($conv->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Closed</span>
                                @endif
                            </td>
                            <td class="text-muted small" style="max-width:200px;">
                                <div class="text-truncate">{{ $conv->last_message ?? '—' }}</div>
                            </td>
                            <td>
                                @if($conv->unread_count > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $conv->unread_count }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $conv->last_message_at ? \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() : '—' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.chatbot.conversations.show', $conv->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="deleteConversation({{ $conv->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-comments fa-2x mb-2 d-block opacity-25"></i>
                                No conversations found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($conversations->hasPages())
        <div class="card-footer bg-transparent">
            {{ $conversations->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function deleteConversation(id) {
    if (!confirm('Delete this conversation and all its messages?')) return;
    fetch(`/admin/chatbot/conversations/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    }).then(r => r.json()).then(d => {
        if (d.ok) location.reload();
        else alert('Error deleting conversation.');
    });
}
</script>
@endpush
@endsection
