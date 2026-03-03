@php
    $isLoggedIn = Auth::check();
    $userName   = '';
    $userEmail  = '';
    if ($isLoggedIn) {
        $patient = DB::table('patients')->where('user_id', Auth::id())->first();
        $userName  = $patient
            ? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''))
            : '';
        $userEmail = Auth::user()->email ?? '';
        if (!$userName) $userName = explode('@', $userEmail)[0];
    }
@endphp

{{-- ══════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════ --}}
<style>
/* ── Reset inside widget ─────────────────────────── */
#hn-chat-launcher,
#hn-chat-window,
#hn-chat-window * {
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* ── Launcher ────────────────────────────────────── */
#hn-chat-launcher {
    position: fixed; bottom: 24px; right: 24px; z-index: 99999;
    width: 58px; height: 58px; border-radius: 50%;
    background: linear-gradient(135deg, #1976d2, #0d47a1);
    color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 20px rgba(25,118,210,.5);
    transition: transform .2s, box-shadow .2s;
    outline: none;
}
#hn-chat-launcher:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(25,118,210,.6); }
#hn-chat-launcher svg  { width: 26px; height: 26px; fill: #fff; pointer-events: none; }

#hn-chat-badge {
    position: absolute; top: -2px; right: -2px;
    background: #e53935; color: #fff;
    width: 20px; height: 20px; border-radius: 50%;
    font-size: .62rem; font-weight: 700;
    display: none; align-items: center; justify-content: center;
    border: 2px solid #fff;
}

/* ── Chat Window ─────────────────────────────────── */
#hn-chat-window {
    position: fixed; bottom: 94px; right: 24px; z-index: 99998;
    width: 360px;
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 12px 48px rgba(0,0,0,.22);
    display: none; flex-direction: column;
    background: #fff;
    max-height: 580px;
}
#hn-chat-window.hn-open {
    display: flex;
    animation: hn-slide-up .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes hn-slide-up {
    from { opacity:0; transform: translateY(24px) scale(.96); }
    to   { opacity:1; transform: translateY(0)   scale(1);    }
}
@media (max-width: 420px) {
    #hn-chat-window { width: calc(100vw - 16px); right: 8px; bottom: 82px; }
}

/* ── Header ──────────────────────────────────────── */
.hn-header {
    background: linear-gradient(135deg, #1565c0, #1976d2);
    padding: 13px 14px; display: flex; align-items: center; gap: 10px;
    flex-shrink: 0;
}
.hn-header-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.hn-header-title   { color: #fff; font-weight: 700; font-size: .9rem; line-height: 1.2; }
.hn-header-status  { color: rgba(255,255,255,.8); font-size: .72rem; display: flex; align-items: center; gap: 4px; }
.hn-status-dot     { width: 7px; height: 7px; border-radius: 50%; background: #69f0ae; animation: hn-pulse 1.5s infinite; }
@keyframes hn-pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
.hn-mode-pill {
    background: rgba(255,255,255,.2); border-radius: 20px;
    padding: 2px 8px; font-size: .68rem; color: #fff;
    margin-left: 4px; font-weight: 600;
}
.hn-close-btn {
    margin-left: auto; background: rgba(255,255,255,.15);
    border: none; color: #fff; cursor: pointer;
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0; transition: background .15s;
}
.hn-close-btn:hover { background: rgba(255,255,255,.3); }

/* ── Mode Bar ────────────────────────────────────── */
.hn-mode-bar {
    background: #f0f4f8; padding: 7px 12px;
    display: flex; gap: 6px; align-items: center;
    border-bottom: 1px solid #e3e8ef; flex-shrink: 0;
    font-size: .73rem;
}
.hn-mode-bar span { color: #666; font-weight: 500; }
.hn-mode-btn {
    border-radius: 20px; padding: 4px 11px;
    font-size: .73rem; cursor: pointer; border: none;
    font-weight: 600; transition: all .15s; outline: none;
}
.hn-btn-contact-admin { background: #1976d2; color: #fff; }
.hn-btn-contact-admin:hover { background: #1565c0; }
.hn-btn-back-bot { background: #fff; color: #1976d2; border: 1px solid #1976d2 !important; }
.hn-btn-back-bot:hover { background: #e3f2fd; }

/* ── Guest Name Form ─────────────────────────────── */
#hn-guest-form {
    padding: 24px 20px; display: flex; flex-direction: column; gap: 12px;
    background: #fff; flex-shrink: 0;
}
#hn-guest-form .hn-gf-icon { font-size: 2rem; text-align: center; }
#hn-guest-form h6 {
    margin: 0; font-size: .95rem; font-weight: 700;
    color: #1565c0; text-align: center;
}
#hn-guest-form p { margin: 0; font-size: .8rem; color: #888; text-align: center; }
#hn-guest-name-input {
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    padding: 10px 14px; font-size: .88rem; outline: none;
    transition: border-color .15s; width: 100%;
}
#hn-guest-name-input:focus { border-color: #1976d2; }
#hn-guest-start-btn {
    background: linear-gradient(135deg, #1976d2, #1565c0);
    color: #fff; border: none; border-radius: 10px;
    padding: 11px; font-size: .88rem; font-weight: 700;
    cursor: pointer; transition: opacity .15s; outline: none;
}
#hn-guest-start-btn:hover { opacity: .9; }
.hn-gf-divider {
    display: flex; align-items: center; gap: 8px;
    font-size: .72rem; color: #bbb;
}
.hn-gf-divider::before, .hn-gf-divider::after {
    content: ''; flex: 1; height: 1px; background: #eee;
}

/* ── Messages Area ───────────────────────────────── */
#hn-messages {
    flex: 1; overflow-y: auto; padding: 12px 10px;
    background: #f0f4f8; display: flex;
    flex-direction: column; gap: 8px;
    min-height: 200px; max-height: 310px;
}
#hn-messages::-webkit-scrollbar { width: 3px; }
#hn-messages::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }

/* Bubbles */
.hn-msg-row { display: flex; gap: 7px; align-items: flex-end; max-width: 85%; }
.hn-msg-row.hn-user  { flex-direction: row-reverse; margin-left: auto; }
.hn-msg-row.hn-bot   { margin-right: auto; }
.hn-msg-row.hn-admin { margin-right: auto; }

.hn-msg-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; flex-shrink: 0; margin-bottom: 2px;
    background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.1);
}

.hn-msg-body { display: flex; flex-direction: column; }
.hn-msg-name { font-size: .65rem; color: #1976d2; font-weight: 600; margin-bottom: 2px; padding: 0 3px; }
.hn-msg-row.hn-user .hn-msg-name { text-align: right; color: #7c4dff; }

.hn-bubble {
    padding: 8px 12px; border-radius: 14px;
    font-size: .83rem; line-height: 1.55;
    word-wrap: break-word; white-space: pre-wrap;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.hn-bot   .hn-bubble { background: #fff; color: #333; border: 1px solid #e8ecf0; border-radius: 4px 14px 14px 14px; }
.hn-admin .hn-bubble { background: #e8f5e9; color: #1b5e20; border-radius: 4px 14px 14px 14px; }
.hn-user  .hn-bubble { background: linear-gradient(135deg, #1976d2, #1565c0); color: #fff; border-radius: 14px 4px 14px 14px; }

.hn-msg-time { font-size: .62rem; color: #bbb; margin-top: 3px; padding: 0 3px; }
.hn-user .hn-msg-time { text-align: right; }

/* Link button inside bubble */
.hn-action-link {
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: 7px; background: #1976d2; color: #fff !important;
    padding: 5px 12px; border-radius: 20px;
    font-size: .75rem; text-decoration: none; font-weight: 600;
    transition: background .15s;
}
.hn-action-link:hover { background: #1565c0; color: #fff; }

/* ── FAQ Chips ───────────────────────────────────── */
#hn-faq-chips {
    padding: 8px 10px; background: #fff;
    border-top: 1px solid #eee; display: flex;
    flex-wrap: wrap; gap: 5px; flex-shrink: 0;
}
.hn-chip {
    background: #e3f2fd; color: #1565c0;
    border: 1px solid #bbdefb; border-radius: 20px;
    padding: 4px 10px; font-size: .72rem; cursor: pointer;
    transition: all .15s; white-space: nowrap; font-weight: 500;
    outline: none;
}
.hn-chip:hover { background: #1976d2; color: #fff; border-color: #1976d2; }

/* ── Typing Indicator ────────────────────────────── */
#hn-typing {
    display: none; align-items: center; gap: 8px;
    padding: 6px 10px; font-size: .74rem; color: #999;
    flex-shrink: 0; background: #f0f4f8;
}
.hn-dots { display: flex; gap: 3px; }
.hn-dots span {
    width: 6px; height: 6px; border-radius: 50%;
    background: #bbb; display: inline-block;
    animation: hn-bounce .9s infinite;
}
.hn-dots span:nth-child(2) { animation-delay: .15s; }
.hn-dots span:nth-child(3) { animation-delay: .30s; }
@keyframes hn-bounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-5px)} }

/* ── Contact Form ────────────────────────────────── */
#hn-contact-form {
    padding: 14px; background: #fff;
    border-top: 1px solid #eee;
    display: none; flex-direction: column; gap: 8px;
    flex-shrink: 0;
}
.hn-cf-title { font-size: .82rem; font-weight: 700; color: #1565c0; }
.hn-cf-input {
    border: 1.5px solid #e0e0e0; border-radius: 8px;
    padding: 8px 11px; font-size: .8rem; width: 100%; outline: none;
    transition: border-color .15s; font-family: inherit;
}
.hn-cf-input:focus { border-color: #1976d2; }
.hn-cf-submit {
    background: #1976d2; color: #fff; border: none;
    border-radius: 8px; padding: 9px; cursor: pointer;
    font-size: .82rem; font-weight: 700; transition: background .15s;
}
.hn-cf-submit:hover { background: #1565c0; }
.hn-cf-cancel {
    background: none; border: none; color: #aaa;
    font-size: .75rem; cursor: pointer; padding: 2px;
    text-align: center;
}
.hn-cf-cancel:hover { color: #666; }

/* ── Input Area ──────────────────────────────────── */
#hn-input-area {
    padding: 10px 10px; background: #fff;
    border-top: 1px solid #eee;
    display: flex; gap: 8px; align-items: flex-end;
    flex-shrink: 0;
}
#hn-msg-input {
    flex: 1; border: 1.5px solid #e0e0e0; border-radius: 20px;
    padding: 9px 14px; font-size: .83rem; resize: none;
    max-height: 90px; min-height: 38px; outline: none;
    line-height: 1.4; font-family: inherit; transition: border-color .15s;
}
#hn-msg-input:focus { border-color: #1976d2; }
#hn-send-btn {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #1976d2, #1565c0);
    color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: transform .15s, opacity .15s;
    outline: none;
}
#hn-send-btn:hover:not(:disabled) { transform: scale(1.08); }
#hn-send-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
#hn-send-btn svg { width: 18px; height: 18px; fill: #fff; }

/* ── Admin Mode Bar (live) ───────────────────────── */
.hn-live-bar {
    background: #e8f5e9; padding: 5px 12px;
    display: flex; align-items: center; gap: 6px;
    font-size: .72rem; color: #2e7d32; flex-shrink: 0;
    border-top: 1px solid #c8e6c9;
}
.hn-live-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #4caf50; animation: hn-pulse 1.2s infinite;
    flex-shrink: 0;
}

/* ── New session button ──────────────────────────── */
.hn-new-session-btn {
    background: none; border: 1px solid #1976d2; color: #1976d2;
    border-radius: 20px; padding: 4px 12px;
    font-size: .72rem; cursor: pointer; margin-left: auto;
    transition: all .15s; font-weight: 600;
}
.hn-new-session-btn:hover { background: #1976d2; color: #fff; }

/* ── Empty state ─────────────────────────────────── */
.hn-empty-msg {
    text-align: center; padding: 30px 20px; color: #bbb;
    font-size: .83rem;
}
.hn-empty-msg .hn-empty-icon { font-size: 2rem; margin-bottom: 8px; }
</style>

{{-- ══════════════════════════════════════════════════
     HTML
══════════════════════════════════════════════════ --}}

{{-- Launcher --}}
<button id="hn-chat-launcher" title="Chat with HEALTHNET" aria-label="Open Chat">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
    </svg>
    <span id="hn-chat-badge"></span>
</button>

{{-- Chat Window --}}
<div id="hn-chat-window" role="dialog" aria-label="HEALTHNET Chat">

    {{-- Header --}}
    <div class="hn-header">
        <div class="hn-header-icon">🏥</div>
        <div style="flex:1;min-width:0">
            <div class="hn-header-title">
                HEALTHNET Assistant
                <span class="hn-mode-pill" id="hn-mode-pill">🤖 AI Bot</span>
            </div>
            <div class="hn-header-status">
                <span class="hn-status-dot"></span>
                <span id="hn-status-text">Online · Replies instantly</span>
            </div>
        </div>
        <button class="hn-close-btn" onclick="hnClose()" aria-label="Close chat">✕</button>
    </div>

    {{-- ── SCREEN 1: Guest Name Entry (not logged in, no session) ── --}}
    <div id="hn-guest-form">
        <div class="hn-gf-icon">👋</div>
        <h6>Welcome to HEALTHNET!</h6>
        <p>Please enter your name to start chatting with our AI assistant or support team.</p>
        <input
            type="text"
            id="hn-guest-name-input"
            placeholder="Your full name..."
            maxlength="60"
            autocomplete="name"
            onkeydown="if(event.key==='Enter') hnStartGuest()"
        >
        <button id="hn-guest-start-btn" onclick="hnStartGuest()">
            Start Chatting →
        </button>
        <div class="hn-gf-divider">or</div>
        <p style="font-size:.75rem;color:#aaa;text-align:center">
            <a href="/login" style="color:#1976d2;font-weight:600">Login</a> to chat with your profile details
        </p>
    </div>

    {{-- ── SCREEN 2: Chat Interface ── --}}
    <div id="hn-chat-ui" style="display:none;flex-direction:column;flex:1;overflow:hidden">

        {{-- Mode Switch Bar --}}
        <div class="hn-mode-bar" id="hn-mode-bar">
            <span>Mode:</span>
            <button class="hn-mode-btn hn-btn-contact-admin" id="hn-admin-btn" onclick="hnSwitchAdmin()">
                💬 Contact Admin
            </button>
            <button class="hn-mode-btn hn-btn-back-bot" id="hn-bot-btn" onclick="hnSwitchBot()" style="display:none">
                🤖 Back to AI Bot
            </button>
            <button class="hn-new-session-btn" onclick="hnNewSession()" title="Start new chat">↺ New</button>
        </div>

        {{-- Live bar (admin mode indicator) --}}
        <div id="hn-live-bar" style="display:none" class="hn-live-bar">
            <span class="hn-live-dot"></span>
            <span>Live chat with Support Team — We'll reply shortly</span>
        </div>

        {{-- Messages --}}
        <div id="hn-messages"></div>

        {{-- Typing --}}
        <div id="hn-typing">
            <span>Assistant is typing</span>
            <div class="hn-dots"><span></span><span></span><span></span></div>
        </div>

        {{-- FAQ Chips --}}
        <div id="hn-faq-chips"></div>

        {{-- Contact Admin Form --}}
        <div id="hn-contact-form">
            <div class="hn-cf-title">📨 Send message to Support Team</div>
            <input type="text"  id="cf-subject" class="hn-cf-input" placeholder="Subject (optional)">
            <textarea id="cf-message" class="hn-cf-input" rows="3" placeholder="Describe your issue..."></textarea>
            @if(!$isLoggedIn)
            <input type="text"  id="cf-name"  class="hn-cf-input" placeholder="Your name *">
            <input type="email" id="cf-email" class="hn-cf-input" placeholder="Email address">
            @endif
            <button class="hn-cf-submit" onclick="hnSubmitContact()">
                ✉ Send Message
            </button>
            <button class="hn-cf-cancel" onclick="hnHideContactForm()">Cancel</button>
        </div>

        {{-- Input --}}
        <div id="hn-input-area">
            <textarea
                id="hn-msg-input"
                placeholder="Type your message..."
                rows="1"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();hnSend();}"
                oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,90)+'px';"
            ></textarea>
            <button id="hn-send-btn" onclick="hnSend()" aria-label="Send message">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════ --}}
<script>
(function () {
    /* ── State ──────────────────────────────────────── */
    const S = {
        isOpen:     false,
        convId:     null,
        sessionId:  localStorage.getItem('hn_session') || null,
        mode:       'bot',
        lastMsgId:  0,
        pollTimer:  null,
        ready:      false,          // true after session initialized
        isLoggedIn: {{ $isLoggedIn ? 'true' : 'false' }},
        userName:   @json($userName),
        userEmail:  @json($userEmail),
        csrf:       document.querySelector('meta[name="csrf-token"]')?.content || '',
    };

    /* ── Launcher ───────────────────────────────────── */
    document.getElementById('hn-chat-launcher').addEventListener('click', hnToggle);

    function hnToggle() { S.isOpen ? hnClose() : hnOpen(); }

    window.hnClose = function () {
        const win = document.getElementById('hn-chat-window');
        win.classList.remove('hn-open');
        win.style.display = 'none';
        S.isOpen = false;
        clearInterval(S.pollTimer);
    };

    function hnOpen() {
        const win = document.getElementById('hn-chat-window');
        win.style.display = 'flex';
        win.classList.add('hn-open');
        S.isOpen = true;
        hideBadge();

        if (S.ready) {
            // Already initialized — just focus input
            focusInput();
            return;
        }

        if (S.isLoggedIn) {
            // Logged in — skip guest form, init session directly
            showChatUI();
            hnInitSession(S.userName);
        } else if (S.sessionId) {
            // Guest with saved session — try resume
            showChatUI();
            hnInitSession(null);
        } else {
            // Fresh guest — show name form
            showGuestForm();
        }
    }

    /* ── Guest Start ────────────────────────────────── */
    window.hnStartGuest = function () {
        const nameEl = document.getElementById('hn-guest-name-input');
        const name   = nameEl.value.trim();
        if (!name) {
            nameEl.style.borderColor = '#e53935';
            nameEl.placeholder = 'Please enter your name!';
            nameEl.focus();
            return;
        }
        nameEl.style.borderColor = '';
        S.userName = name;
        showChatUI();
        hnInitSession(name);
    };

    /* ── Init Session ───────────────────────────────── */
    function hnInitSession(guestName) {
        setLoading(true);

        fetch('/chatbot/session/start', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': S.csrf },
            body:    JSON.stringify({ session_id: S.sessionId, guest_name: guestName }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { setLoading(false); return; }

            S.convId    = data.conv_id;
            S.sessionId = data.session_id;
            S.mode      = data.mode;
            S.ready     = true;
            localStorage.setItem('hn_session', data.session_id);

            if (data.guest_name && !S.userName) S.userName = data.guest_name;

            setLoading(false);
            updateModeUI(data.mode);

            // Render history
            clearMessages();
            if (data.messages && data.messages.length) {
                data.messages.forEach(m => appendMsg(m.sender, m.message, m.time, null, null));
                S.lastMsgId = data.messages.length; // will be updated by poll
            }
            scrollBottom();
            loadChips();
            enableInput();
            focusInput();

            if (data.mode === 'admin') startPoll();
        })
        .catch(() => { setLoading(false); showError('Connection failed. Please refresh.'); });
    }

    /* ── Send Message ───────────────────────────────── */
    window.hnSend = function () {
        if (!S.ready || !S.convId) return;
        const input = document.getElementById('hn-msg-input');
        const msg   = input.value.trim();
        if (!msg) return;

        input.value = '';
        input.style.height = 'auto';
        disableInput();

        appendMsg('user', msg, nowFmt(), null, null);
        scrollBottom();
        showTyping();

        fetch('/chatbot/message/send', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': S.csrf },
            body:    JSON.stringify({ conv_id: S.convId, message: msg }),
        })
        .then(r => r.json())
        .then(data => {
            hideTyping();
            enableInput();
            focusInput();

            if (data.success && data.mode === 'bot' && data.reply) {
                appendMsg('bot', data.reply, data.time, data.route_name, data.route_label);
                scrollBottom();
            }
            // If admin mode, start polling
            if (data.mode === 'admin' && !S.pollTimer) startPoll();
        })
        .catch(() => { hideTyping(); enableInput(); });
    };

    /* ── FAQ Chip Click ─────────────────────────────── */
    window.hnChipSend = function (question) {
        if (!S.ready) return;
        document.getElementById('hn-msg-input').value = question;
        hnSend();
    };

    /* ── Load FAQ Chips ─────────────────────────────── */
    function loadChips() {
        fetch('/chatbot/faqs')
        .then(r => r.json())
        .then(data => {
            const c = document.getElementById('hn-faq-chips');
            c.innerHTML = '';
            (data.faqs || []).slice(0, 5).forEach(faq => {
                const btn = document.createElement('button');
                btn.className   = 'hn-chip';
                btn.textContent = faq.question.length > 34
                    ? faq.question.substring(0, 32) + '…'
                    : faq.question;
                btn.onclick = () => hnChipSend(faq.question);
                c.appendChild(btn);
            });
        });
    }

    /* ── Switch to Admin ────────────────────────────── */
    window.hnSwitchAdmin = function () {
        if (!S.ready || !S.convId) return;
        disableInput();

        fetch('/chatbot/switch-to-admin', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': S.csrf },
            body:    JSON.stringify({ conv_id: S.convId }),
        })
        .then(r => r.json())
        .then(data => {
            enableInput();
            if (data.success) {
                S.mode = 'admin';
                updateModeUI('admin');
                appendMsg('bot', data.message, data.time, null, null);
                scrollBottom();
                startPoll();
            }
        })
        .catch(() => enableInput());
    };

    /* ── Switch to Bot ──────────────────────────────── */
    window.hnSwitchBot = function () {
        if (!S.ready || !S.convId) return;

        fetch('/chatbot/switch-to-bot', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': S.csrf },
            body:    JSON.stringify({ conv_id: S.convId }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                S.mode = 'bot';
                updateModeUI('bot');
                appendMsg('bot', data.message, nowFmt(), null, null);
                scrollBottom();
                stopPoll();
                hideContactForm();
            }
        });
    };

    /* ── New Session ────────────────────────────────── */
    window.hnNewSession = function () {
        if (!confirm('Start a new chat? Current conversation will be saved.')) return;
        localStorage.removeItem('hn_session');
        S.sessionId = null;
        S.convId    = null;
        S.mode      = 'bot';
        S.ready     = false;
        S.lastMsgId = 0;
        stopPoll();
        clearMessages();

        if (S.isLoggedIn) {
            hnInitSession(S.userName);
        } else {
            showGuestForm();
        }
    };

    /* ── Polling ────────────────────────────────────── */
    function startPoll() {
        stopPoll();
        S.pollTimer = setInterval(() => {
            if (!S.convId || !S.isOpen) return;
            fetch(`/chatbot/messages/${S.convId}?last_id=${S.lastMsgId}`)
            .then(r => r.json())
            .then(data => {
                if (data.messages && data.messages.length) {
                    data.messages.forEach(m => {
                        if (m.sender !== 'user') {
                            appendMsg(m.sender, m.message, m.time, null, null, true);
                        }
                        S.lastMsgId = Math.max(S.lastMsgId, m.id || 0);
                    });
                    scrollBottom();
                    if (!S.isOpen) showBadge();
                }
                if (data.mode && data.mode !== S.mode) {
                    S.mode = data.mode;
                    updateModeUI(data.mode);
                }
            });
        }, 4000);
    }

    function stopPoll() {
        clearInterval(S.pollTimer);
        S.pollTimer = null;
    }

    /* ── Contact Form ───────────────────────────────── */
    window.hnShowContactForm = function () {
        document.getElementById('hn-contact-form').style.display = 'flex';
        document.getElementById('hn-input-area').style.display   = 'none';
        document.getElementById('hn-faq-chips').style.display    = 'none';
    };
    window.hnHideContactForm = function () {
        document.getElementById('hn-contact-form').style.display = 'none';
        document.getElementById('hn-input-area').style.display   = 'flex';
        document.getElementById('hn-faq-chips').style.display    = 'flex';
    };

    window.hnSubmitContact = function () {
        const msg = document.getElementById('cf-message').value.trim();
        if (!msg) { document.getElementById('cf-message').focus(); return; }

        const name  = S.isLoggedIn ? S.userName  : (document.getElementById('cf-name')?.value  || 'Guest');
        const email = S.isLoggedIn ? S.userEmail : (document.getElementById('cf-email')?.value || '');

        fetch('/chatbot/contact-admin', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': S.csrf },
            body:    JSON.stringify({
                conv_id: S.convId,
                name, email,
                subject: document.getElementById('cf-subject')?.value || 'Support Request',
                message: msg,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                hnHideContactForm();
                appendMsg('bot', data.message, nowFmt(), null, null);
                scrollBottom();
                document.getElementById('cf-message').value = '';
                document.getElementById('cf-subject').value = '';
            }
        });
    };

    /* ── Append Message ─────────────────────────────── */
    function appendMsg(sender, text, time, routeName, routeLabel, animate) {
        const wrap = document.getElementById('hn-messages');

        // Remove empty state
        const empty = wrap.querySelector('.hn-empty-msg');
        if (empty) empty.remove();

        const row = document.createElement('div');
        row.className = `hn-msg-row hn-${sender}`;
        if (animate) row.style.animation = 'hn-slide-up .2s ease';

        const icons = { bot: '🏥', admin: '👨‍⚕️', user: '👤' };
        const names = { bot: 'Assistant', admin: 'Support', user: S.userName || 'You' };

        const avatar = document.createElement('div');
        avatar.className   = 'hn-msg-avatar';
        avatar.textContent = icons[sender] || '💬';

        const body = document.createElement('div');
        body.className = 'hn-msg-body';

        // Sender label
        const nameEl = document.createElement('div');
        nameEl.className   = 'hn-msg-name';
        nameEl.textContent = names[sender] || sender;

        const bubble = document.createElement('div');
        bubble.className = 'hn-bubble';

        // Format bold markdown **text**
        const htmlText = escHtml(text)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        bubble.innerHTML = htmlText;

        // Action link button
        if (routeName && routeLabel) {
            const a = document.createElement('a');
            a.href      = `/chatbot/redirect?route=${encodeURIComponent(routeName)}`;
            a.className = 'hn-action-link';
            a.target    = '_blank';
            a.innerHTML = `<span>→</span> ${escHtml(routeLabel)}`;
            bubble.appendChild(document.createElement('br'));
            bubble.appendChild(a);
        }

        const timeEl = document.createElement('div');
        timeEl.className   = 'hn-msg-time';
        timeEl.textContent = time || nowFmt();

        body.appendChild(nameEl);
        body.appendChild(bubble);
        body.appendChild(timeEl);

        row.appendChild(avatar);
        row.appendChild(body);
        wrap.appendChild(row);

        // Update lastMsgId tracking
        if (animate && wrap.children.length) {
            // will be updated by poll
        }
    }

    /* ── Mode UI ────────────────────────────────────── */
    function updateModeUI(mode) {
        const pill     = document.getElementById('hn-mode-pill');
        const status   = document.getElementById('hn-status-text');
        const adminBtn = document.getElementById('hn-admin-btn');
        const botBtn   = document.getElementById('hn-bot-btn');
        const liveBar  = document.getElementById('hn-live-bar');
        const chips    = document.getElementById('hn-faq-chips');
        const cfBtn    = document.getElementById('hn-cf-mode-btn');

        if (mode === 'admin') {
            pill.textContent       = '👨‍⚕️ Live Support';
            status.textContent     = 'Connected to Support Team';
            adminBtn.style.display = 'none';
            botBtn.style.display   = 'inline-flex';
            liveBar.style.display  = 'flex';
            chips.style.display    = 'none';

            // Add contact form button if not exists
            if (!document.getElementById('hn-cf-mode-btn')) {
                const bar = document.getElementById('hn-mode-bar');
                const cfb = document.createElement('button');
                cfb.id        = 'hn-cf-mode-btn';
                cfb.className = 'hn-mode-btn hn-btn-back-bot';
                cfb.innerHTML = '📨 Contact Form';
                cfb.onclick   = hnShowContactForm;
                bar.insertBefore(cfb, document.querySelector('.hn-new-session-btn'));
            }
        } else {
            pill.textContent       = '🤖 AI Bot';
            status.textContent     = 'Online · Replies instantly';
            adminBtn.style.display = 'inline-flex';
            botBtn.style.display   = 'none';
            liveBar.style.display  = 'none';
            chips.style.display    = 'flex';

            const cfb = document.getElementById('hn-cf-mode-btn');
            if (cfb) cfb.remove();
        }
    }

    /* ── UI Helpers ─────────────────────────────────── */
    function showGuestForm() {
        document.getElementById('hn-guest-form').style.display = 'flex';
        document.getElementById('hn-chat-ui').style.display    = 'none';
        setTimeout(() => document.getElementById('hn-guest-name-input')?.focus(), 100);
    }

    function showChatUI() {
        document.getElementById('hn-guest-form').style.display = 'none';
        document.getElementById('hn-chat-ui').style.display    = 'flex';
    }

    function setLoading(on) {
        const msgs  = document.getElementById('hn-messages');
        const input = document.getElementById('hn-send-btn');
        if (on) {
            msgs.innerHTML = `
                <div class="hn-empty-msg">
                    <div class="hn-empty-icon">⏳</div>
                    <div>Connecting...</div>
                </div>`;
            if (input) input.disabled = true;
        } else {
            if (input) input.disabled = false;
        }
    }

    function showError(msg) {
        const msgs = document.getElementById('hn-messages');
        msgs.innerHTML = `<div class="hn-empty-msg"><div class="hn-empty-icon">⚠️</div><div>${msg}</div></div>`;
    }

    function clearMessages() {
        document.getElementById('hn-messages').innerHTML = '';
    }

    function enableInput() {
        const btn   = document.getElementById('hn-send-btn');
        const input = document.getElementById('hn-msg-input');
        if (btn)   btn.disabled   = false;
        if (input) input.disabled = false;
    }

    function disableInput() {
        const btn   = document.getElementById('hn-send-btn');
        const input = document.getElementById('hn-msg-input');
        if (btn)   btn.disabled   = true;
        if (input) input.disabled = true;
    }

    function focusInput() {
        setTimeout(() => document.getElementById('hn-msg-input')?.focus(), 100);
    }

    function showTyping() {
        document.getElementById('hn-typing').style.display = 'flex';
        scrollBottom();
    }

    function hideTyping() {
        document.getElementById('hn-typing').style.display = 'none';
    }

    function scrollBottom() {
        const m = document.getElementById('hn-messages');
        if (m) requestAnimationFrame(() => { m.scrollTop = m.scrollHeight; });
    }

    function showBadge() {
        const b = document.getElementById('hn-chat-badge');
        b.style.display = 'flex';
    }

    function hideBadge() {
        const b = document.getElementById('hn-chat-badge');
        b.style.display = 'none';
    }

    function nowFmt() {
        return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function escHtml(t) {
        const d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
    }

})();
</script>
