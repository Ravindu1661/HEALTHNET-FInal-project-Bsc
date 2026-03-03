@extends('admin.layouts.master')
@section('title', 'Conversation #' . $conversation->id)
@section('page-title', 'Live Chat — Conversation #' . $conversation->id)

@section('content')
<div class="row g-4" style="height:calc(100vh - 155px)">

    {{-- ── LEFT: Info Panel ─────────────────────────────── --}}
    <div class="col-lg-3 d-flex flex-column gap-3">

        {{-- Conversation Info --}}
        <div class="dashboard-card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Info</h6></div>
            <div class="card-body p-0">
                <div class="hn-detail-row">
                    <i class="fas fa-hashtag text-primary"></i>
                    <div><div class="hn-detail-label">ID</div><div class="hn-detail-val">#{{ $conversation->id }}</div></div>
                </div>
                <div class="hn-detail-row">
                    <i class="fas fa-user text-primary"></i>
                    <div>
                        <div class="hn-detail-label">User</div>
                        <div class="hn-detail-val">{{ $conversation->user_email ?? ($conversation->guest_name ?? 'Guest') }}</div>
                    </div>
                </div>
                <div class="hn-detail-row">
                    <i class="fas fa-signal text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Mode</div>
                        <div class="hn-detail-val">
                            <span class="badge bg-{{ $conversation->mode==='admin'?'success':'primary' }}" id="mode-badge">
                                {{ $conversation->mode==='admin'?'👨‍⚕️ Live Chat':'🤖 Bot Mode' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="hn-detail-row">
                    <i class="fas fa-calendar text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Started</div>
                        <div class="hn-detail-val">{{ \Carbon\Carbon::parse($conversation->created_at)->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                <div class="hn-detail-row">
                    <i class="fas fa-comment-dots text-primary"></i>
                    <div>
                        <div class="hn-detail-label">Messages</div>
                        <div class="hn-detail-val fw-bold" id="msg-count">{{ count($messages) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Status Indicator --}}
        <div class="dashboard-card">
            <div class="card-body text-center py-3">
                @if($conversation->mode === 'admin')
                <div class="hn-live-badge mb-2">
                    <span class="hn-live-dot"></span> LIVE CHAT ACTIVE
                </div>
                <p class="text-muted small mb-0">Patient is connected. Replies are delivered in real-time.</p>
                @else
                <div class="mb-2">
                    <span class="badge bg-primary fs-6">🤖 Bot Mode</span>
                </div>
                <p class="text-muted small mb-0">Conversation is handled by AI Bot.</p>
                @endif
            </div>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('admin.chatbot.conversations') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to List
        </a>
    </div>

    {{-- ── RIGHT: WhatsApp-style Chat ─────────────────────── --}}
    <div class="col-lg-9 d-flex flex-column">
        <div class="hn-wa-window">

            {{-- WA Header --}}
            <div class="hn-wa-header">
                <div class="hn-wa-header-avatar">
                    {{ strtoupper(substr($conversation->guest_name ?? ($conversation->user_email ?? 'P'), 0, 1)) }}
                </div>
                <div class="hn-wa-header-info">
                    <div class="hn-wa-header-name">
                        {{ $conversation->user_email ?? ($conversation->guest_name ?? 'Guest Patient') }}
                    </div>
                    <div class="hn-wa-header-status" id="wa-status">
                        @if($conversation->mode === 'admin')
                        <span class="hn-online-dot"></span> Connected · Live Chat
                        @else
                        <span style="color:#b2dfdb">●</span> Bot Mode
                        @endif
                    </div>
                </div>
                <div class="hn-wa-header-actions ms-auto d-flex gap-2">
                    <span class="badge bg-light text-dark border" id="conv-mode-pill">
                        {{ $conversation->mode==='admin' ? '👨‍⚕️ Live' : '🤖 Bot' }}
                    </span>
                    <span class="badge bg-light text-dark border">#{{ $conversation->id }}</span>
                </div>
            </div>

            {{-- WA Messages --}}
            <div class="hn-wa-messages" id="waMessages">
                <div class="hn-wa-date-divider">
                    <span>{{ \Carbon\Carbon::parse($conversation->created_at)->format('M d, Y') }}</span>
                </div>

                @foreach($messages as $msg)
                @php
                    $isAdmin = $msg->sender === 'admin';
                    $isUser  = $msg->sender === 'user';
                    $isBot   = $msg->sender === 'bot';
                @endphp
                <div class="hn-wa-msg-row {{ $isAdmin ? 'hn-wa-sent' : 'hn-wa-received' }}" id="msg-{{ $msg->id }}">
                    @if(!$isAdmin)
                    <div class="hn-wa-msg-avatar">{{ $isBot ? '🏥' : '👤' }}</div>
                    @endif
                    <div class="hn-wa-msg-wrap">
                        @if(!$isAdmin)
                        <div class="hn-wa-msg-sender">{{ $isBot ? 'AI Bot' : 'Patient' }}
                            @if($msg->intent)<span class="hn-intent-pill">{{ $msg->intent }}</span>@endif
                        </div>
                        @endif
                        <div class="hn-wa-msg-bubble {{ $isAdmin?'hn-wa-bubble-sent':($isBot?'hn-wa-bubble-bot':'hn-wa-bubble-received') }}">
                            <div class="hn-wa-msg-text">{{ $msg->message }}</div>
                            <div class="hn-wa-msg-time">
                                {{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}
                                @if($isAdmin)<i class="fas fa-check-double ms-1" style="color:#b3e5fc;font-size:.65rem"></i>@endif
                            </div>
                        </div>
                    </div>
                    @if($isAdmin)
                    <div class="hn-wa-msg-avatar hn-admin-avatar">👨‍⚕️</div>
                    @endif
                </div>
                @endforeach

                {{-- New message placeholder --}}
                <div id="wa-new-messages"></div>
            </div>

            {{-- WA Input --}}
            <div class="hn-wa-input-bar" id="wa-input-bar">
                @if($conversation->mode === 'admin')
                <div class="hn-wa-input-inner">
                    <div class="hn-wa-emoji-btn" title="Live chat active"><i class="fas fa-stethoscope text-success"></i></div>
                    <textarea
                        id="wa-input"
                        class="hn-wa-textarea"
                        placeholder="Type a message to patient..."
                        rows="1"
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendAdminMsg();}"
                        oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px';"
                    ></textarea>
                    <button class="hn-wa-send-btn" onclick="sendAdminMsg()" id="wa-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                @else
                <div class="hn-wa-bot-mode-bar">
                    <i class="fas fa-robot me-2 text-primary"></i>
                    <span class="text-muted small">This conversation is in <strong>Bot Mode</strong>. Patient is chatting with the AI assistant.</span>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ── Detail Rows ─────────────────────────────── */
.hn-detail-row{display:flex;gap:12px;padding:.6rem 1rem;border-bottom:1px solid #f5f5f5;align-items:flex-start}
.hn-detail-row:last-child{border-bottom:none}
.hn-detail-row>i{width:18px;margin-top:3px;flex-shrink:0;font-size:.85rem}
.hn-detail-label{font-size:.68rem;color:#aaa;text-transform:uppercase;font-weight:600}
.hn-detail-val{font-size:.83rem;color:#333;font-weight:500}

/* ── Live Badge ──────────────────────────────── */
.hn-live-badge{display:inline-flex;align-items:center;gap:6px;background:#e8f5e9;color:#2e7d32;border-radius:20px;padding:4px 12px;font-size:.75rem;font-weight:700}
.hn-live-dot{width:8px;height:8px;border-radius:50%;background:#4caf50;animation:hn-pulse 1.4s infinite}
.hn-online-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#69f0ae;margin-right:3px;animation:hn-pulse 1.4s infinite}
@keyframes hn-pulse{0%,100%{opacity:1}50%{opacity:.4}}

/* ── WhatsApp Window ─────────────────────────── */
.hn-wa-window{
    flex:1;display:flex;flex-direction:column;
    border-radius:12px;overflow:hidden;
    box-shadow:0 4px 24px rgba(0,0,0,.12);
    height:100%;background:#fff;
}

/* ── WA Header ───────────────────────────────── */
.hn-wa-header{
    background:linear-gradient(135deg,#075e54,#128c7e);
    padding:12px 16px;display:flex;align-items:center;gap:12px;
    flex-shrink:0;
}
.hn-wa-header-avatar{
    width:42px;height:42px;border-radius:50%;
    background:rgba(255,255,255,.25);color:#fff;
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;font-weight:700;flex-shrink:0;
}
.hn-wa-header-name{color:#fff;font-weight:700;font-size:.92rem}
.hn-wa-header-status{color:rgba(255,255,255,.75);font-size:.73rem;margin-top:1px}
.hn-wa-header-actions .badge{font-size:.72rem}

/* ── WA Messages ─────────────────────────────── */
.hn-wa-messages{
    flex:1;overflow-y:auto;
    background:#e5ddd5;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Crect width='60' height='60' fill='%23e5ddd5'/%3E%3C/svg%3E");
    padding:16px 12px;
    display:flex;flex-direction:column;gap:4px;
}
.hn-wa-messages::-webkit-scrollbar{width:4px}
.hn-wa-messages::-webkit-scrollbar-thumb{background:#bbb;border-radius:2px}

/* Date divider */
.hn-wa-date-divider{text-align:center;margin:8px 0}
.hn-wa-date-divider span{background:rgba(255,255,255,.85);color:#666;font-size:.72rem;padding:3px 12px;border-radius:20px;box-shadow:0 1px 2px rgba(0,0,0,.1)}

/* Message row */
.hn-wa-msg-row{display:flex;gap:6px;align-items:flex-end;margin-bottom:2px;max-width:75%}
.hn-wa-received{align-self:flex-start}
.hn-wa-sent{align-self:flex-end;flex-direction:row-reverse;margin-left:auto}

.hn-wa-msg-avatar{
    width:28px;height:28px;border-radius:50%;
    background:#fff;display:flex;align-items:center;
    justify-content:center;font-size:.8rem;flex-shrink:0;
    box-shadow:0 1px 3px rgba(0,0,0,.15);margin-bottom:4px;
}
.hn-admin-avatar{background:#dcf8c6}

.hn-wa-msg-wrap{display:flex;flex-direction:column;max-width:100%}
.hn-wa-msg-sender{font-size:.68rem;color:#128c7e;font-weight:600;margin-bottom:2px;padding:0 4px}
.hn-intent-pill{background:#e3f2fd;color:#1565c0;border-radius:4px;padding:0 4px;font-size:.6rem;margin-left:4px;font-weight:normal}

/* Bubbles */
.hn-wa-msg-bubble{
    padding:7px 11px 4px;border-radius:8px;
    max-width:100%;word-wrap:break-word;
    box-shadow:0 1px 2px rgba(0,0,0,.13);
    position:relative;
}
.hn-wa-bubble-received{background:#fff;border-radius:4px 8px 8px 8px;color:#333}
.hn-wa-bubble-bot{background:#fff;border-radius:4px 8px 8px 8px;color:#333;border-left:3px solid #128c7e}
.hn-wa-bubble-sent{background:#dcf8c6;border-radius:8px 4px 8px 8px;color:#333}

.hn-wa-msg-text{font-size:.84rem;line-height:1.55;white-space:pre-wrap}
.hn-wa-msg-time{font-size:.64rem;color:#aaa;text-align:right;margin-top:3px;display:flex;align-items:center;justify-content:flex-end;gap:3px}
.hn-wa-bubble-sent .hn-wa-msg-time{color:#7a9f6b}

/* ── WA Input Bar ────────────────────────────── */
.hn-wa-input-bar{
    background:#f0f0f0;padding:10px 12px;
    border-top:1px solid #e0e0e0;flex-shrink:0;
}
.hn-wa-input-inner{
    display:flex;align-items:flex-end;gap:8px;
    background:#fff;border-radius:24px;
    padding:6px 6px 6px 12px;
    box-shadow:0 1px 4px rgba(0,0,0,.08);
}
.hn-wa-emoji-btn{
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;flex-shrink:0;cursor:default;
}
.hn-wa-textarea{
    flex:1;border:none;outline:none;resize:none;
    font-size:.87rem;line-height:1.4;background:transparent;
    font-family:inherit;max-height:120px;min-height:22px;
    padding:5px 0;
}
.hn-wa-send-btn{
    width:42px;height:42px;border-radius:50%;flex-shrink:0;
    background:linear-gradient(135deg,#075e54,#128c7e);
    color:#fff;border:none;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    font-size:.95rem;transition:transform .15s,opacity .15s;
}
.hn-wa-send-btn:hover{transform:scale(1.08)}
.hn-wa-send-btn:disabled{opacity:.6;cursor:not-allowed;transform:none}

.hn-wa-bot-mode-bar{
    display:flex;align-items:center;
    background:#fff;border-radius:10px;
    padding:10px 14px;
    border:1px dashed #bbb;
}

/* ── Typing indicator ────────────────────────── */
.hn-typing-row{align-self:flex-start;display:flex;gap:6px;align-items:flex-end}
.hn-typing-bubble{background:#fff;border-radius:4px 12px 12px 12px;padding:10px 16px;box-shadow:0 1px 2px rgba(0,0,0,.1)}
.hn-typing-dots{display:flex;gap:4px;align-items:center}
.hn-typing-dots span{width:7px;height:7px;border-radius:50%;background:#aaa;animation:hn-dot-bounce .9s infinite;display:inline-block}
.hn-typing-dots span:nth-child(2){animation-delay:.15s}
.hn-typing-dots span:nth-child(3){animation-delay:.30s}
@keyframes hn-dot-bounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-6px)}}

/* New msg flash */
@keyframes hn-msg-in{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
.hn-wa-msg-new{animation:hn-msg-in .2s ease}
</style>
@endpush

@push('scripts')
<script>
const CONV_ID   = {{ $conversation->id }};
const CONV_MODE = '{{ $conversation->mode }}';
const csrf      = document.querySelector('meta[name="csrf-token"]').content;
let lastMsgId   = {{ $messages->last()?->id ?? 0 }};
let pollTimer   = null;
let msgCount    = {{ count($messages) }};

// ── Send Admin Message ───────────────────────
function sendAdminMsg() {
    const input = document.getElementById('wa-input');
    const msg   = input.value.trim();
    if (!msg) return;

    const sendBtn = document.getElementById('wa-send-btn');
    sendBtn.disabled = true;
    input.value = '';
    input.style.height = 'auto';

    // Optimistic UI — add message immediately
    appendAdminMsg(msg, hnNow(), true);
    scrollToBottom();

    fetch('/admin/chatbot/live-reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ conv_id: CONV_ID, message: msg })
    })
    .then(r => r.json())
    .then(d => { sendBtn.disabled = false; if (!d.success) console.error('Send failed'); })
    .catch(() => { sendBtn.disabled = false; });
}

// ── Append Sent (admin) Bubble ───────────────
function appendAdminMsg(text, time, isNew) {
    const container = document.getElementById('wa-new-messages');
    const row = document.createElement('div');
    row.className = `hn-wa-msg-row hn-wa-sent${isNew?' hn-wa-msg-new':''}`;
    row.innerHTML = `
        <div class="hn-wa-msg-wrap">
            <div class="hn-wa-msg-bubble hn-wa-bubble-sent">
                <div class="hn-wa-msg-text">${escHtml(text)}</div>
                <div class="hn-wa-msg-time">${time} <i class="fas fa-check-double" style="color:#7a9f6b;font-size:.65rem"></i></div>
            </div>
        </div>
        <div class="hn-wa-msg-avatar hn-admin-avatar">👨‍⚕️</div>`;
    container.appendChild(row);
    msgCount++;
    document.getElementById('msg-count').textContent = msgCount;
}

// ── Append Received (patient/bot) Bubble ─────
function appendReceivedMsg(sender, text, time, intent) {
    const container = document.getElementById('wa-new-messages');
    const isBot  = sender === 'bot';
    const icon   = isBot ? '🏥' : '👤';
    const label  = isBot ? 'AI Bot' : 'Patient';
    const bubble = isBot ? 'hn-wa-bubble-bot' : 'hn-wa-bubble-received';
    const intentPill = intent ? `<span class="hn-intent-pill">${intent}</span>` : '';
    const row = document.createElement('div');
    row.className = 'hn-wa-msg-row hn-wa-received hn-wa-msg-new';
    row.innerHTML = `
        <div class="hn-wa-msg-avatar">${icon}</div>
        <div class="hn-wa-msg-wrap">
            <div class="hn-wa-msg-sender">${label}${intentPill}</div>
            <div class="hn-wa-msg-bubble ${bubble}">
                <div class="hn-wa-msg-text">${escHtml(text)}</div>
                <div class="hn-wa-msg-time">${time}</div>
            </div>
        </div>`;
    container.appendChild(row);
    msgCount++;
    document.getElementById('msg-count').textContent = msgCount;
}

// ── Typing Indicator ─────────────────────────
function showTyping() {
    let t = document.getElementById('wa-typing');
    if (t) return;
    const container = document.getElementById('wa-new-messages');
    t = document.createElement('div');
    t.id = 'wa-typing';
    t.className = 'hn-wa-msg-row hn-wa-received hn-typing-row';
    t.innerHTML = `<div class="hn-wa-msg-avatar">👤</div><div class="hn-typing-bubble"><div class="hn-typing-dots"><span></span><span></span><span></span></div></div>`;
    container.appendChild(t);
    scrollToBottom();
}
function hideTyping() {
    const t = document.getElementById('wa-typing');
    if (t) t.remove();
}

// ── Poll New Messages ─────────────────────────
function startPolling() {
    if (pollTimer) return;
    pollTimer = setInterval(() => {
        fetch(`/chatbot/messages/${CONV_ID}?last_id=${lastMsgId}`)
        .then(r => r.json())
        .then(d => {
            hideTyping();
            if (d.messages && d.messages.length > 0) {
                d.messages.forEach(m => {
                    lastMsgId = Math.max(lastMsgId, m.id);
                    // Only show non-admin messages (admin messages are shown optimistically)
                    if (m.sender !== 'admin') {
                        appendReceivedMsg(m.sender, m.message, m.time, m.intent ?? null);
                    }
                });
                scrollToBottom();
            }
            // Update mode badge if changed
            updateModeBadge(d.mode);
        });
    }, 3500);
}

// ── Update Mode Badge ─────────────────────────
function updateModeBadge(mode) {
    const badge = document.getElementById('conv-mode-pill');
    const modeBadge = document.getElementById('mode-badge');
    const waStatus  = document.getElementById('wa-status');
    if (!mode) return;
    if (mode === 'admin') {
        if (badge) badge.textContent = '👨‍⚕️ Live';
        if (modeBadge) { modeBadge.textContent = '👨‍⚕️ Live Chat'; modeBadge.className = 'badge bg-success'; }
        if (waStatus) waStatus.innerHTML = '<span class="hn-online-dot"></span> Connected · Live Chat';
    } else {
        if (badge) badge.textContent = '🤖 Bot';
        if (modeBadge) { modeBadge.textContent = '🤖 Bot Mode'; modeBadge.className = 'badge bg-primary'; }
        if (waStatus) waStatus.innerHTML = '<span style="color:#b2dfdb">●</span> Bot Mode';
    }
}

// ── Helpers ───────────────────────────────────
function scrollToBottom() {
    const m = document.getElementById('waMessages');
    if (m) m.scrollTop = m.scrollHeight;
}
function hnNow() {
    return new Date().toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
}
function escHtml(t) {
    return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
}

// ── Init ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom();
    // Poll regardless of mode to keep updated
    startPolling();

    // Send on Enter for desktop
    const input = document.getElementById('wa-input');
    if (input) {
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendAdminMsg();
            }
        });
    }
});
</script>
@endpush
