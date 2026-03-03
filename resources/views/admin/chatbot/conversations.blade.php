@extends('admin.layouts.master')
@section('title', 'Conversations')
@section('page-title', 'Chat Conversations')

@section('content')

<div class="dashboard-card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.chatbot.conversations') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Session ID or user email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button></div>
            <div class="col-auto"><a href="{{ route('admin.chatbot.conversations') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
        </form>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-comments me-2"></i>All Conversations</h6>
        <span class="badge bg-primary">{{ $conversations->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="hn-table">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Session / User</th>
                        <th class="text-center">Msgs</th>
                        <th>Mode</th>
                        <th>User Type</th>
                        <th>Started</th>
                        <th width="80" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $conv)
                    <tr>
                        <td class="text-muted">#{{ $conv->id }}</td>
                        <td>
                            <div class="font-monospace text-muted" style="font-size:.74rem">{{ Str::limit($conv->session_id,28) }}</div>
                            @if($conv->user_email)
                            <div class="fw-bold" style="font-size:.82rem"><i class="fas fa-user-check text-success me-1"></i>{{ $conv->user_email }}</div>
                            @else
                            <span class="badge bg-light text-muted border" style="font-size:.7rem">Guest{{ $conv->guest_name ? ' · '.$conv->guest_name : '' }}</span>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge bg-info">{{ $conv->message_count }}</span></td>
                        <td>
                            <span class="badge bg-{{ $conv->mode==='admin'?'success':'primary' }}">
                                {{ $conv->mode==='admin'?'👨‍⚕️ Live':'🤖 Bot' }}
                            </span>
                        </td>
                        <td>{!! $conv->user_role ? '<span class="badge bg-secondary">'.ucfirst($conv->user_role).'</span>' : '—' !!}</td>
                        <td>
                            <small>{{ \Carbon\Carbon::parse($conv->created_at)->format('M d, Y') }}</small>
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($conv->created_at)->format('h:i A') }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.chatbot.conversations.show', $conv->id) }}"
                               class="btn btn-sm btn-info" title="View" data-bs-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">
                        <i class="fas fa-comment-slash fa-3x mb-2 d-block opacity-50"></i>No conversations found.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2 d-flex justify-content-between align-items-center border-top">
            <small class="text-muted">Showing {{ $conversations->firstItem()??0 }} – {{ $conversations->lastItem()??0 }} of {{ $conversations->total() }}</small>
            {{ $conversations->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hn-table{width:100%;border-collapse:collapse}
.hn-table thead th{background:#f8f9fa;padding:.75rem 1rem;font-size:.74rem;text-transform:uppercase;font-weight:600;color:#555;border-bottom:2px solid #e0e0e0}
.hn-table tbody td{padding:.75rem 1rem;vertical-align:middle;border-bottom:1px solid #f0f0f0;font-size:.83rem}
.hn-table tbody tr:hover{background:#fafafa}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>new bootstrap.Tooltip(el)));
</script>
@endpush
