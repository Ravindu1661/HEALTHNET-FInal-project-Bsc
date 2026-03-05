@extends('admin.layouts.master')

@section('title', 'Conversation #'.$conversation->id)
@section('page-title', 'Live Chat Conversation #'.$conversation->id)

@section('content')

@php
    // Guest නම් පමණයි email target ඇත — logged-in user-ට email නොයවයි
    $isGuest    = !$conversation->user_id;
    $replyEmail = $isGuest ? $conversation->guest_email : null;
@endphp

<div class="row g-4" style="height: calc(100vh - 155px);">

    {{-- LEFT: Conversation Info --}}
    <div class="col-lg-3 d-flex flex-column gap-3">

        {{-- Basic Info --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Conversation Info
                </h6>
            </div>
            <div class="card-body p-0">

                <div class="hn-detail-row">
                    <i class="fas fa-hashtag text-primary"></i>
                    <div>
                        <div class="hn-detail-label">ID</div>
                        <div class="hn-detail-val">{{ $conversation->id }}</div>
                    </div>
                </div>

                <div class="hn-detail-row">
                    <i class="fas fa-user text-primary"></i>
                    <div>
                        <div class="hn-detail-label">User</div>
                        <div class="hn-detail-val">
                            {{ $conversation->display_name }}
                            @if($conversation->user_id)
                                <span class="badge bg-light text-muted border ms-1" style="font-size:10px;">
                                    {{ $conversation->user_type ?? 'user' }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Guest</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $conversation->display_email }}</small>
                        </div>
                    </div>
                </div>

                {{-- Email Reply Target — Guest-ට පමණයි --}}
                @if($isGuest)
                <div class="hn-detail-row">
                    <i class="fas fa-envelope text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Reply Email Target</div>
                        @if($replyEmail)
                            <div class="hn-detail-val">
                                <a href="mailto:{{ $replyEmail }}"
                                   class="text-decoration-none text-success fw-semibold"
                                   style="font-size:.8rem;word-break:break-all;">
                                    <i class="fas fa-check-circle me-1"></i>{{ $replyEmail }}
                                </a>
                            </div>
                        @else
                            <div class="hn-detail-val text-danger" style="font-size:.8rem;">
                                <i class="fas fa-exclamation-triangle me-1"></i>No email — reply stored only
                            </div>
                        @endif
                    </div>
                </div>
                @else
                {{-- Logged-in user — email info row --}}
                <div class="hn-detail-row">
                    <i class="fas fa-envelope text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Reply Delivery</div>
                        <div class="hn-detail-val">
                            <span class="text-primary fw-semibold" style="font-size:.8rem;">
                                <i class="fas fa-bolt me-1"></i>Real-time (in chat)
                            </span>
                            <br>
                            <small class="text-muted">No email sent to logged-in users</small>
                        </div>
                    </div>
                </div>
                @endif

                <div class="hn-detail-row">
                    <i class="fas fa-signal text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Mode</div>
                        <div class="hn-detail-val">
                            <span id="mode-badge"
                                  class="badge bg-{{ $conversation->mode === 'admin' ? 'success' : 'primary' }}">
                                {{ $conversation->mode === 'admin' ? 'Live Chat' : 'Bot Mode' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="hn-detail-row">
                    <i class="fas fa-circle text-{{ $conversation->status === 'active' ? 'success' : 'secondary' }}"></i>
                    <div>
                        <div class="hn-detail-label">Status</div>
                        <div class="hn-detail-val text-capitalize">{{ $conversation->status }}</div>
                    </div>
                </div>

                <div class="hn-detail-row">
                    <i class="fas fa-calendar text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Started</div>
                        <div class="hn-detail-val">
                            {{ \Illuminate\Support\Carbon::parse($conversation->created_at)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="hn-detail-row">
                    <i class="fas fa-clock text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Last Activity</div>
                        <div class="hn-detail-val">
                            {{ $conversation->last_message_at
                                ? \Illuminate\Support\Carbon::parse($conversation->last_message_at)->diffForHumans()
                                : 'N/A' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Email / Delivery Notice --}}
        @if($isGuest)
            @if($replyEmail)
            <div class="alert mb-0 py-2 px-3"
                 style="background:#e8f5e9;border-left:3px solid #388e3c;border-radius:8px;font-size:12px;color:#1b5e20;">
                <i class="fas fa-paper-plane me-1"></i>
                Reply will be <strong>emailed</strong> to:<br>
                <strong>{{ $replyEmail }}</strong>
            </div>
            @else
            <div class="alert mb-0 py-2 px-3"
                 style="background:#fff3e0;border-left:3px solid #f57c00;border-radius:8px;font-size:12px;color:#e65100;">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Guest has <strong>no email</strong>. Reply stored only — no email notification.
            </div>
            @endif
        @else
            <div class="alert mb-0 py-2 px-3"
                 style="background:#e8f0ff;border-left:3px solid #0d6efd;border-radius:8px;font-size:12px;color:#1e3a8a;">
                <i class="fas fa-bolt me-1"></i>
                Logged-in user — replies delivered <strong>real-time</strong> in chat widget. No email sent.
            </div>
        @endif

        {{-- Actions --}}
        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($conversation->status === 'active')
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="HNConv.toggleMode()">
                            <i class="fas fa-exchange-alt me-1"></i>
                            Switch to {{ $conversation->mode === 'admin' ? 'Bot' : 'Admin' }} Mode
                        </button>
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="HNConv.closeConversation()">
                            <i class="fas fa-times-circle me-1"></i> Close Conversation
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-success"
                                onclick="HNConv.reopenConversation()">
                            <i class="fas fa-redo me-1"></i> Reopen Conversation
                        </button>
                    @endif

                    <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="HNConv.deleteConversation()">
                        <i class="fas fa-trash me-1"></i> Delete Conversation
                    </button>

                    <a href="{{ route('admin.chatbot.conversations') }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Conversations
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: WhatsApp-style Chat --}}
    <div class="col-lg-9 d-flex flex-column">
        <div class="dashboard-card d-flex flex-column flex-grow-1">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-comments text-success"></i>
                <h6 class="mb-0">Live Chat</h6>
                <span class="badge bg-light text-muted ms-2">{{ $conversation->display_name }}</span>

                @if($isGuest)
                    <span class="badge bg-warning text-dark ms-1">Guest</span>
                @else
                    <span class="badge bg-primary ms-1" style="font-size:10px;">
                        <i class="fas fa-bolt me-1"></i>Logged-in
                    </span>
                @endif

                {{-- Email sent toast — Guest-ට පමණයි show වේ --}}
                @if($isGuest)
                    <span id="hn-email-sent-badge" class="badge bg-success ms-2 d-none">
                        <i class="fas fa-envelope me-1"></i>Email Sent!
                    </span>
                    <span id="hn-email-fail-badge" class="badge bg-warning text-dark ms-2 d-none">
                        <i class="fas fa-exclamation-triangle me-1"></i>Email not sent
                    </span>
                @endif

                <span class="ms-auto text-muted small" id="hn-typing-label" style="display:none;">
                    <i class="fas fa-ellipsis-h fa-beat-fade me-1"></i> User is typing...
                </span>
            </div>

            {{-- Messages --}}
            <div class="hn-conv-area" id="hn-conv-area">
                @forelse($messages as $msg)
                    @php
                        $rowClass  = $msg->sender_type === 'user'  ? 'hn-wa-user'
                                   : ($msg->sender_type === 'admin' ? 'hn-wa-admin' : 'hn-wa-bot');
                        $iconClass = $msg->sender_type === 'user'  ? 'fas fa-user'
                                   : ($msg->sender_type === 'admin' ? 'fas fa-headset' : 'fas fa-robot');
                        $label     = $msg->sender_type === 'user'  ? 'User'
                                   : ($msg->sender_type === 'admin' ? 'Admin' : 'Bot');
                    @endphp
                    <div class="hn-wa-row {{ $rowClass }}" data-id="{{ $msg->id }}">
                        <div class="hn-wa-icon">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div class="hn-wa-bubble">
                            <div class="hn-wa-text">{!! nl2br(e($msg->message)) !!}</div>
                            <div class="hn-wa-meta">
                                <span class="text-capitalize">{{ $label }}</span> ·
                                {{ \Illuminate\Support\Carbon::parse($msg->created_at)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-comment-slash fa-3x mb-3 d-block opacity-50"></i>
                        <p>No messages yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Reply box --}}
            <div class="card-footer">
                @if($conversation->status === 'active')

                    {{-- Delivery strip — user type අනුව --}}
                    @if($isGuest)
                        @if($replyEmail)
                        <div class="mb-2 px-2 py-1 rounded"
                             style="background:#e8f5e9;border-left:3px solid #388e3c;font-size:12px;color:#2e7d32;">
                            <i class="fas fa-envelope me-1"></i>
                            Reply will be <strong>emailed</strong> to: <strong>{{ $replyEmail }}</strong>
                        </div>
                        @else
                        <div class="mb-2 px-2 py-1 rounded"
                             style="background:#fff3e0;border-left:3px solid #f57c00;font-size:12px;color:#e65100;">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            No email address — reply stored only.
                        </div>
                        @endif
                    @else
                        <div class="mb-2 px-2 py-1 rounded"
                             style="background:#e8f0ff;border-left:3px solid #0d6efd;font-size:12px;color:#1e3a8a;">
                            <i class="fas fa-bolt me-1"></i>
                            Logged-in user — reply delivered <strong>real-time</strong> in chat. No email sent.
                        </div>
                    @endif

                    <form onsubmit="HNConv.sendReply(event)">
                        <div class="d-flex align-items-end gap-2">
                            <div class="flex-grow-1">
                                <textarea id="hn-reply-text"
                                          class="form-control"
                                          rows="1"
                                          placeholder="Type your reply…"
                                          oninput="HNConv.autoResize(this)"></textarea>
                            </div>
                            <button type="submit" id="hn-reply-btn" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i> Send
                                @if($isGuest && $replyEmail)
                                    <small class="d-block" style="font-size:10px;opacity:.85;">+ Email</small>
                                @endif
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>
                            @if($isGuest && $replyEmail)
                                Reply will be stored and emailed to the guest.
                            @elseif($isGuest)
                                Reply stored only — guest has no email.
                            @else
                                Reply delivered in real-time to the logged-in user's chat widget.
                            @endif
                        </small>
                    </form>

                @else
                    <div class="text-center text-muted py-2">
                        <i class="fas fa-lock me-1"></i>
                        Conversation is closed. Reopen to continue chatting.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hn-detail-row {
    display: flex; gap: 12px; padding: .65rem 1rem;
    border-bottom: 1px solid #f5f5f5; align-items: flex-start;
}
.hn-detail-row:last-child { border-bottom: none; }
.hn-detail-row i { width: 18px; margin-top: 3px; flex-shrink: 0; font-size: .85rem; }
.hn-detail-label { font-size: .7rem; color: #999; font-weight: 500; text-transform: uppercase; }
.hn-detail-val   { font-size: .83rem; color: #333; font-weight: 500; }

.hn-conv-area {
    flex: 1; overflow-y: auto; padding: 16px;
    background: #e5ddd5; display: flex;
    flex-direction: column; gap: 8px;
}
.hn-conv-area::-webkit-scrollbar { width: 4px; }
.hn-conv-area::-webkit-scrollbar-thumb { background: #bbb; border-radius: 2px; }

.hn-wa-row { display: flex; gap: 7px; align-items: flex-end; max-width: 78%; }
.hn-wa-user  { align-self: flex-end; flex-direction: row-reverse; margin-left: auto; }
.hn-wa-bot,
.hn-wa-admin { align-self: flex-start; }

.hn-wa-icon {
    width: 28px; height: 28px; border-radius: 50%; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.hn-wa-bubble { display: flex; flex-direction: column; }
.hn-wa-text {
    padding: 8px 12px; border-radius: 12px;
    font-size: .82rem; line-height: 1.5;
    word-wrap: break-word; white-space: pre-wrap;
    box-shadow: 0 1px 2px rgba(0,0,0,.1);
}
.hn-wa-bot   .hn-wa-text { background: #fff;    color: #333;    border-radius: 4px 12px 12px 12px; }
.hn-wa-admin .hn-wa-text { background: #d1f2d3; color: #1b5e20; border-radius: 4px 12px 12px 12px; }
.hn-wa-user  .hn-wa-text { background: #dcf8c6; color: #333;    border-radius: 12px 4px 12px 12px; }
.hn-wa-meta { font-size: .65rem; color: #999; margin-top: 3px; padding: 0 4px; }
.hn-wa-user .hn-wa-meta { text-align: right; }
</style>
@endpush

@push('scripts')
<script>
const HNConv = (function () {
    const CSRF    = '{{ csrf_token() }}';
    const convId  = {{ $conversation->id }};
    const IS_GUEST = {{ $isGuest ? 'true' : 'false' }};
    let lastId    = {{ $messages->last()->id ?? 0 }};
    let pollInt   = null;

    function startPolling() {
        stopPolling();
        pollInt = setInterval(pollNew, 3000);
    }
    function stopPolling() {
        if (pollInt) clearInterval(pollInt);
        pollInt = null;
    }

    async function pollNew() {
        try {
            const res  = await fetch('{{ route('admin.chatbot.conversations.poll', $conversation->id) }}?after=' + lastId);
            const data = await res.json();
            if (!data.ok) return;
            if (data.messages && data.messages.length) {
                data.messages.forEach(m => appendMessage(m));
                lastId = data.messages[data.messages.length - 1].id;
                scrollBottom();
            }
        } catch (e) {}
    }

    function appendMessage(m) {
        const area = document.getElementById('hn-conv-area');
        if (!area) return;

        let rowClass, iconClass, label;
        if (m.sender_type === 'user') {
            rowClass = 'hn-wa-user'; iconClass = 'fas fa-user'; label = 'User';
        } else if (m.sender_type === 'admin') {
            rowClass = 'hn-wa-admin'; iconClass = 'fas fa-headset'; label = 'Admin';
        } else {
            rowClass = 'hn-wa-bot'; iconClass = 'fas fa-robot'; label = 'Bot';
        }

        const wrap = document.createElement('div');
        wrap.className = 'hn-wa-row ' + rowClass;
        wrap.innerHTML = `
            <div class="hn-wa-icon"><i class="${iconClass}"></i></div>
            <div class="hn-wa-bubble">
                <div class="hn-wa-text">${escapeHtml(m.message).replace(/\n/g, '<br>')}</div>
                <div class="hn-wa-meta">${label} · ${formatTime(m.created_at)}</div>
            </div>`;
        area.appendChild(wrap);
    }

    function scrollBottom() {
        const area = document.getElementById('hn-conv-area');
        if (area) area.scrollTop = area.scrollHeight;
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
            .replace(/'/g,'&#039;');
    }

    function formatTime(iso) {
        try { return new Date(iso).toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' }); }
        catch { return ''; }
    }

    async function sendReply(e) {
        e.preventDefault();
        const ta  = document.getElementById('hn-reply-text');
        const btn = document.getElementById('hn-reply-btn');
        const msg = ta.value.trim();
        if (!msg) return;

        btn.disabled  = true;
        const oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending…';

        // Hide previous badges (Guest-ට පමණයි exist වෙනවා)
        const sentBadge = document.getElementById('hn-email-sent-badge');
        const failBadge = document.getElementById('hn-email-fail-badge');
        if (sentBadge) sentBadge.classList.add('d-none');
        if (failBadge) failBadge.classList.add('d-none');

        try {
            const res  = await fetch('{{ route('admin.chatbot.conversations.reply', $conversation->id) }}', {
                method : 'POST',
                headers: {
                    'Content-Type' : 'application/json',
                    'X-CSRF-TOKEN' : CSRF,
                    'Accept'       : 'application/json',
                },
                body: JSON.stringify({ message: msg }),
            });
            const data = await res.json();

            if (data.ok) {
                ta.value = '';
                autoResize(ta);

                // Email badge — Guest-ට පමණයි relevant
                if (IS_GUEST) {
                    if (data.email_sent && sentBadge) {
                        sentBadge.classList.remove('d-none');
                        setTimeout(() => sentBadge.classList.add('d-none'), 4000);
                    } else if (!data.email_sent && failBadge) {
                        failBadge.classList.remove('d-none');
                        setTimeout(() => failBadge.classList.add('d-none'), 4000);
                    }
                }

                pollNew();
            } else {
                alert('Error: ' + (data.error || 'Failed to send reply.'));
            }
        } catch (err) {
            alert('Network error. Please try again.');
        } finally {
            btn.disabled  = false;
            btn.innerHTML = oldHtml;
        }
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    async function closeConversation() {
        if (!confirm('Close this conversation?')) return;
        await post('{{ route('admin.chatbot.conversations.close', $conversation->id) }}');
        location.reload();
    }

    async function reopenConversation() {
        await post('{{ route('admin.chatbot.conversations.reopen', $conversation->id) }}');
        location.reload();
    }

    async function deleteConversation() {
        if (!confirm('Delete this conversation and all its messages permanently?')) return;
        await fetch('{{ route('admin.chatbot.conversations.destroy', $conversation->id) }}', {
            method : 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body   : new URLSearchParams({ _method: 'DELETE' }),
        });
        window.location.href = '{{ route('admin.chatbot.conversations') }}';
    }

    async function post(url) {
        await fetch(url, {
            method : 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
    }

    async function toggleMode() {
        const currentMode = '{{ $conversation->mode }}';
        const newMode     = currentMode === 'admin' ? 'bot' : 'admin';
        const label       = newMode === 'admin' ? 'Admin / Live Chat' : 'Bot Mode';

        if (!confirm(`Switch conversation to ${label}?`)) return;

        const btn = document.querySelector('[onclick="HNConv.toggleMode()"]');
        if (btn) {
            btn.disabled  = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Switching…';
        }

        try {
            const res  = await fetch(`/admin/chatbot/conversations/${convId}/mode`, {
                method : 'POST',
                headers: {
                    'Content-Type' : 'application/json',
                    'X-CSRF-TOKEN' : CSRF,
                    'Accept'       : 'application/json',
                },
                body: JSON.stringify({ mode: newMode }),
            });
            const data = await res.json();
            if (data.ok) {
                location.reload();
            } else {
                alert('Switch failed. Please try again.');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i>Switch Mode'; }
            }
        } catch (e) {
            alert('Network error. Please try again.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-exchange-alt me-1"></i>Switch Mode'; }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        scrollBottom();
        startPolling();
    });

    return { sendReply, autoResize, closeConversation, reopenConversation, deleteConversation, toggleMode };
})();
</script>
@endpush
