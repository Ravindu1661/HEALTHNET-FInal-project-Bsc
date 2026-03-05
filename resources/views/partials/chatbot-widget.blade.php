@php
    $authUser   = Auth::user();
    $isLoggedIn = !!$authUser;
    $csrfToken  = csrf_token();
    $userId     = $authUser->id ?? null;
@endphp

<!-- Chatbot Widget Container -->
<div id="hn-chatbot-wrap">
    <!-- Toggle Button -->
    <button id="hn-chat-toggle" onclick="HNChat.toggle()" title="HealthNet AI Assistant">
        <i class="fas fa-comment-medical" id="hn-chat-icon-open"></i>
        <i class="fas fa-times d-none" id="hn-chat-icon-close"></i>
        <span id="hn-chat-badge" class="d-none">0</span>
    </button>

    <!-- Chat Window -->
    <div id="hn-chat-window" class="d-none">
        <!-- Header -->
        <div id="hn-chat-header">
            <div class="d-flex align-items-center gap-2">
                <div id="hn-chat-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div id="hn-chat-title">HealthNet Assistant</div>
                    <div id="hn-chat-status"><span class="hn-dot"></span> Online</div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="hn-btn-icon" onclick="HNChat.showTab('faq')" title="FAQs">
                    <i class="fas fa-question-circle"></i>
                </button>
                <button class="hn-btn-icon" onclick="HNChat.showTab('links')" title="Quick Links">
                    <i class="fas fa-link"></i>
                </button>
                <button class="hn-btn-icon" id="hn-mode-toggle" onclick="HNChat.toggleMode()" title="Connect to Admin">
                    <i class="fas fa-headset"></i>
                </button>
                <button class="hn-btn-icon" onclick="HNChat.toggle()" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div id="hn-tabs">
            <button class="hn-tab active" data-tab="chat" onclick="HNChat.showTab('chat')">
                <i class="fas fa-comments"></i> Chat
            </button>
            <button class="hn-tab" data-tab="faq" onclick="HNChat.showTab('faq')">
                <i class="fas fa-question-circle"></i> FAQs
            </button>
            <button class="hn-tab" data-tab="links" onclick="HNChat.showTab('links')">
                <i class="fas fa-link"></i> Links
            </button>
        </div>

        <!-- CHAT TAB -->
        <div id="hn-tab-chat" class="hn-tab-content">

            <!-- ════ GUEST FORM (not logged in only) ════ -->
            @if(!$isLoggedIn)
            <div id="hn-guest-form">
                <div class="hn-guest-title">
                    <i class="fas fa-user-circle"></i>
                    Start your health consultation
                </div>
                <input type="text"
                       id="hn-guest-name"
                       placeholder="Your Full Name *"
                       maxlength="100"
                       autocomplete="name" />
                <input type="email"
                       id="hn-guest-email"
                       placeholder="Your Email * (reply will be sent here)"
                       maxlength="255"
                       autocomplete="email" />
                <button onclick="HNChat.startAsGuest()">
                    Start Chat <i class="fas fa-arrow-right"></i>
                </button>
                <p class="hn-guest-note">
                    <i class="fas fa-envelope me-1"></i>
                    AI replies and admin responses will be sent to your email.
                </p>
            </div>

            <!-- Email confirmation notice (shown after guest submits) -->
            <div id="hn-guest-email-notice" style="display:none">
                <div class="hn-email-notice-inner">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Chat started! Replies will be sent to
                    <strong id="hn-guest-email-display"></strong>
                </div>
            </div>
            @endif

            <!-- Messages body -->
            <div id="hn-chat-body" @if(!$isLoggedIn) style="display:none" @endif>
                <div id="hn-messages"></div>
            </div>

            <!-- Admin waiting indicator -->
            <div id="hn-admin-wait" class="d-none">
                <i class="fas fa-spinner fa-spin"></i>
                Waiting for admin reply...
            </div>

            <!-- Admin mode instruction banner (shown after switching to admin mode) -->
            <div id="hn-admin-mode-banner" class="d-none">
                <div class="hn-admin-banner-inner">
                    <i class="fas fa-headset me-2 text-success"></i>
                    <div>
                        <strong>Connected to Live Support</strong><br>
                        <small>Type your complete message and press Send.
                        Our admin will reply to your email.</small>
                    </div>
                </div>
            </div>

            <!-- Input area -->
            <div id="hn-chat-input-area" @if(!$isLoggedIn) style="display:none" @endif>
                <div id="hn-typing-indicator" class="d-none">
                    <span></span><span></span><span></span>
                </div>
                <div class="hn-input-row">
                    <textarea id="hn-msg-input"
                              placeholder="Ask about health, symptoms, appointments..."
                              rows="1"
                              maxlength="2000"
                              onkeydown="HNChat.handleKey(event)"></textarea>
                    <button id="hn-send-btn" onclick="HNChat.sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="hn-input-footer">
                    <small><i class="fas fa-shield-alt"></i> Health advice only. Always consult a doctor.</small>
                </div>
            </div>
        </div>

        <!-- FAQ TAB -->
        <div id="hn-tab-faq" class="hn-tab-content d-none">
            <div id="hn-faq-search">
                <input type="text" placeholder="Search FAQs..." oninput="HNChat.filterFaqs(this.value)" />
            </div>
            <div id="hn-faq-list">
                <div class="hn-loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>

        <!-- QUICK LINKS TAB -->
        <div id="hn-tab-links" class="hn-tab-content d-none">
            <div id="hn-links-list">
                <div class="hn-loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>

    </div><!-- /chat-window -->
</div><!-- /chatbot-wrap -->


<!-- ══════════════ STYLES ══════════════ -->
<style>
:root {
    --hn-primary: #0d6efd;
    --hn-primary-dark: #0a58ca;
    --hn-success: #198754;
    --hn-bg: #ffffff;
    --hn-header: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    --hn-msg-user: #0d6efd;
    --hn-msg-bot: #f0f4ff;
    --hn-msg-admin: #e8f5e9;
    --hn-radius: 18px;
    --hn-shadow: 0 8px 40px rgba(13,110,253,0.18);
}

#hn-chatbot-wrap {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 99999;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

#hn-chat-toggle {
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: var(--hn-header);
    border: none;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    box-shadow: var(--hn-shadow);
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
#hn-chat-toggle:hover { transform: scale(1.1); box-shadow: 0 12px 50px rgba(13,110,253,0.28); }

#hn-chat-badge {
    position: absolute;
    top: -4px; right: -4px;
    background: #dc3545;
    color: #fff;
    border-radius: 50%;
    width: 22px; height: 22px;
    font-size: 11px;
    display: flex; align-items: center; justify-content: center;
    font-weight: bold;
}

#hn-chat-window {
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 380px;
    max-height: 580px;
    background: var(--hn-bg);
    border-radius: var(--hn-radius);
    box-shadow: var(--hn-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: hnSlideUp 0.25s ease;
}

@keyframes hnSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

#hn-chat-header {
    background: var(--hn-header);
    color: #fff;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    transition: background 0.3s;
}

#hn-chat-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
}

#hn-chat-title { font-weight: 700; font-size: 15px; }
#hn-chat-status { font-size: 11px; opacity: 0.85; display: flex; align-items: center; gap: 4px; }
.hn-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; display: inline-block; }

.hn-btn-icon {
    background: rgba(255,255,255,0.15);
    border: none; color: #fff;
    width: 30px; height: 30px;
    border-radius: 50%; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    transition: background 0.2s;
}
.hn-btn-icon:hover { background: rgba(255,255,255,0.3); }

#hn-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #f8faff;
    flex-shrink: 0;
}
.hn-tab {
    flex: 1; padding: 9px 4px; border: none; background: none;
    font-size: 12px; cursor: pointer; color: #64748b;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 4px;
}
.hn-tab.active { color: var(--hn-primary); border-bottom-color: var(--hn-primary); font-weight: 600; }
.hn-tab:hover  { color: var(--hn-primary); background: #f0f4ff; }

.hn-tab-content { display: flex; flex-direction: column; flex: 1; overflow: hidden; }
.hn-tab-content.d-none { display: none !important; }

/* ── Guest Form ── */
#hn-guest-form {
    padding: 20px 16px;
    display: flex; flex-direction: column; gap: 10px;
    flex-shrink: 0;
}
.hn-guest-title {
    font-size: 14px; font-weight: 600; color: #1e293b;
    margin-bottom: 4px; display: flex; align-items: center; gap: 8px;
}
#hn-guest-form input {
    padding: 10px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
    font-family: inherit;
}
#hn-guest-form input:focus { border-color: var(--hn-primary); }
#hn-guest-form input.hn-field-error { border-color: #dc3545; background: #fff5f5; }
#hn-guest-form button {
    padding: 11px;
    background: var(--hn-primary);
    color: #fff; border: none;
    border-radius: 10px; font-size: 14px;
    font-weight: 600; cursor: pointer;
    transition: background 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
#hn-guest-form button:hover { background: var(--hn-primary-dark); }
.hn-guest-note {
    font-size: 11px; color: #64748b;
    text-align: center; margin: 0;
}

/* ── Email notice strip ── */
#hn-guest-email-notice { flex-shrink: 0; }
.hn-email-notice-inner {
    background: #e8f5e9;
    border-left: 3px solid #198754;
    border-radius: 0;
    padding: 8px 14px;
    font-size: 12px;
    color: #1b5e20;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
}

/* ── Admin mode banner ── */
#hn-admin-mode-banner { flex-shrink: 0; }
.hn-admin-banner-inner {
    background: #e8f5e9;
    border-left: 3px solid #198754;
    padding: 10px 14px;
    font-size: 12px;
    color: #1b5e20;
    display: flex;
    align-items: flex-start;
    gap: 6px;
    line-height: 1.5;
}

/* ── Messages ── */
#hn-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    background: #f8faff;
    min-height: 0;
    scroll-behavior: smooth;
}
#hn-messages { display: flex; flex-direction: column; gap: 10px; }

.hn-msg-wrap { display: flex; align-items: flex-end; gap: 8px; }
.hn-msg-wrap.user { flex-direction: row-reverse; }

.hn-msg-bubble {
    max-width: 78%;
    padding: 10px 13px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
    white-space: pre-wrap;
}
.hn-msg-user  .hn-msg-bubble { background: var(--hn-msg-user); color: #fff; border-bottom-right-radius: 4px; }
.hn-msg-bot   .hn-msg-bubble { background: var(--hn-msg-bot);  color: #1e293b; border-bottom-left-radius: 4px; border: 1px solid #e2e8f0; }
.hn-msg-admin .hn-msg-bubble { background: var(--hn-msg-admin);color: #1e293b; border-bottom-left-radius: 4px; border: 1px solid #bbf7d0; }
.hn-msg-system .hn-msg-bubble {
    background: #fff3cd; color: #856404;
    font-size: 12px; border-radius: 8px;
    border: 1px solid #ffc107;
    margin: 0 auto; max-width: 90%; text-align: center;
}

.hn-msg-avatar {
    width: 28px; height: 28px;
    border-radius: 50%; background: #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.hn-msg-time { font-size: 10px; color: #94a3b8; margin-top: 3px; text-align: right; }

/* ── Typing indicator ── */
#hn-typing-indicator {
    padding: 6px 12px;
    display: flex; gap: 4px; align-items: center;
}
#hn-typing-indicator span {
    width: 7px; height: 7px; border-radius: 50%;
    background: #94a3b8;
    animation: hnBounce 1.2s infinite ease-in-out;
    display: inline-block;
}
#hn-typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
#hn-typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
@keyframes hnBounce {
    0%, 80%, 100% { transform: scale(0); }
    40%           { transform: scale(1); }
}

/* ── Admin wait ── */
#hn-admin-wait {
    padding: 16px; text-align: center;
    color: #64748b; font-size: 13px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    flex-shrink: 0;
}

/* ── Input area ── */
#hn-chat-input-area {
    padding: 10px;
    border-top: 1px solid #e2e8f0;
    background: #fff;
    flex-shrink: 0;
}
.hn-input-row { display: flex; gap: 8px; align-items: flex-end; }
#hn-msg-input {
    flex: 1; padding: 10px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px; font-size: 14px;
    resize: none; outline: none;
    line-height: 1.4;
    max-height: 100px;
    overflow-y: auto;
    transition: border-color 0.2s;
    font-family: inherit;
}
#hn-msg-input:focus { border-color: var(--hn-primary); }
#hn-send-btn {
    width: 40px; height: 40px;
    background: var(--hn-primary); color: #fff;
    border: none; border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
    transition: background 0.2s, transform 0.1s;
}
#hn-send-btn:hover    { background: var(--hn-primary-dark); transform: scale(1.05); }
#hn-send-btn:disabled { background: #94a3b8; cursor: not-allowed; transform: none; }
.hn-input-footer { font-size: 10px; color: #94a3b8; text-align: center; margin-top: 5px; }

/* ── FAQ Tab ── */
#hn-faq-search { padding: 10px; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
#hn-faq-search input {
    width: 100%; padding: 8px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 13px; outline: none; box-sizing: border-box; font-family: inherit;
}
#hn-faq-list { flex: 1; overflow-y: auto; padding: 8px; }

.hn-faq-item { border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 8px; overflow: hidden; transition: border-color 0.2s; }
.hn-faq-item:hover { border-color: var(--hn-primary); }
.hn-faq-q {
    padding: 10px 13px; font-size: 13px; font-weight: 600;
    cursor: pointer; color: #1e293b; background: #f8faff;
    display: flex; justify-content: space-between; align-items: center;
    user-select: none;
}
.hn-faq-q:hover { background: #f0f4ff; color: var(--hn-primary); }
.hn-faq-a {
    padding: 10px 13px; font-size: 13px; color: #475569;
    line-height: 1.5; border-top: 1px solid #e2e8f0;
    background: #fff; display: none;
}
.hn-faq-a.open { display: block; }

/* ── Quick Links Tab ── */
#hn-links-list { flex: 1; overflow-y: auto; padding: 10px; }
.hn-link-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px;
    text-decoration: none; color: #1e293b;
    font-size: 13px; margin-bottom: 6px;
    background: #f8faff; border: 1px solid #e2e8f0;
    transition: all 0.2s;
}
.hn-link-item:hover { background: #f0f4ff; border-color: var(--hn-primary); color: var(--hn-primary); transform: translateX(3px); }
.hn-link-icon { width: 30px; height: 30px; background: var(--hn-primary); color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }

.hn-loading { text-align: center; padding: 20px; color: #94a3b8; font-size: 13px; }
.hn-error   { text-align: center; padding: 20px; color: #dc3545;  font-size: 13px; }
.hn-empty   { text-align: center; padding: 20px; color: #94a3b8;  font-size: 13px; }

/* ── Admin mode header ── */
#hn-chat-header.admin-mode { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }

/* ── Responsive ── */
@media (max-width: 480px) {
    #hn-chat-window { width: calc(100vw - 20px); right: -14px; }
    #hn-chatbot-wrap { bottom: 20px; right: 14px; }
}
</style>


<!-- ══════════════ SCRIPT ══════════════ -->
<script>
window.HNChat = (function () {

    const CSRF         = '{{ $csrfToken }}';
    const IS_LOGGED_IN = {{ $isLoggedIn ? 'true' : 'false' }};
    const USER_ID      = {{ $userId ? (int)$userId : 'null' }};

    const GUEST_KEY = 'hn_session_guest';
    const USER_KEY  = USER_ID ? ('hn_session_user_' + USER_ID) : null;

    function getStoredSessionId() {
        if (IS_LOGGED_IN && USER_KEY) return localStorage.getItem(USER_KEY);
        return localStorage.getItem(GUEST_KEY);
    }
    function storeSessionId(sid) {
        if (IS_LOGGED_IN && USER_KEY) localStorage.setItem(USER_KEY, sid);
        else localStorage.setItem(GUEST_KEY, sid);
    }

    let state = {
        open: false,
        sessionId: getStoredSessionId() || null,
        convId: null,
        mode: 'bot',
        lastMsgId: 0,
        pollTimer: null,
        sending: false,
        faqsLoaded: false,
        linksLoaded: false,
        allFaqs: [],
        quickLinks: [],
        currentTab: 'chat',
        guestEmail: '',   // ← save කරන email track කරන්නට
    };

    // ════════════════════════════
    //  PUBLIC API
    // ════════════════════════════

    function toggle() {
        state.open = !state.open;
        const win   = document.getElementById('hn-chat-window');
        const iconO = document.getElementById('hn-chat-icon-open');
        const iconC = document.getElementById('hn-chat-icon-close');

        if (state.open) {
            win.classList.remove('d-none');
            iconO.classList.add('d-none');
            iconC.classList.remove('d-none');
            if (!state.convId) _init();
        } else {
            win.classList.add('d-none');
            iconO.classList.remove('d-none');
            iconC.classList.add('d-none');
            _stopPoll();
        }
    }

    function showTab(tab) {
        state.currentTab = tab;
        ['chat', 'faq', 'links'].forEach(t => {
            const el  = document.getElementById('hn-tab-' + t);
            const btn = document.querySelector('[data-tab="' + t + '"]');
            if (el)  el.classList.toggle('d-none', t !== tab);
            if (btn) btn.classList.toggle('active', t === tab);
        });
        if (tab === 'faq'   && !state.faqsLoaded)                          _loadFaqs();
        if (tab === 'links' && !state.linksLoaded && state.quickLinks.length > 0) _renderLinks();
    }

    function startAsGuest() {
        const nameEl  = document.getElementById('hn-guest-name');
        const emailEl = document.getElementById('hn-guest-email');

        const name  = nameEl.value.trim();
        const email = emailEl.value.trim();

        // Reset validation styles
        nameEl.classList.remove('hn-field-error');
        emailEl.classList.remove('hn-field-error');

        let valid = true;

        if (!name) {
            nameEl.classList.add('hn-field-error');
            nameEl.placeholder = 'Name is required!';
            nameEl.focus();
            valid = false;
        }

        if (!email || !_isEmail(email)) {
            emailEl.classList.add('hn-field-error');
            emailEl.placeholder = 'Valid email is required!';
            if (valid) emailEl.focus();   // only focus if name was OK
            valid = false;
        }

        if (!valid) return;

        // Save email for notice display
        state.guestEmail = email;

        // Always start fresh guest session
        state.sessionId = null;
        state.convId    = null;
        localStorage.removeItem(GUEST_KEY);

        _init(name, email);
    }

    function sendMessage() {
        if (state.sending) return;
        const input = document.getElementById('hn-msg-input');
        const msg   = input.value.trim();
        if (!msg) return;
        input.value        = '';
        input.style.height = 'auto';
        _doSend(msg);
    }

    function handleKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
        const el = document.getElementById('hn-msg-input');
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 100) + 'px';
    }

    function toggleMode() {
        if (state.mode === 'bot') _switchToAdmin();
        else                      _switchToBot();
    }

    function filterFaqs(query) {
        const q = query.toLowerCase();
        state.allFaqs.forEach(faq => {
            const el = document.getElementById('faq-' + faq.id);
            if (el) {
                const match = faq.question.toLowerCase().includes(q) ||
                              faq.answer.toLowerCase().includes(q);
                el.style.display = match ? '' : 'none';
            }
        });
    }

    // ════════════════════════════
    //  PRIVATE
    // ════════════════════════════

   async function _init(guestName = null, guestEmail = null) {
    if (!state.sessionId) {
        state.sessionId = _uuid();
        storeSessionId(state.sessionId);
    }

    const body = { session_id: state.sessionId };
    if (guestName)  body.guest_name  = guestName;
    if (guestEmail) body.guest_email = guestEmail;

    try {
        const res = await _post('{{ route("chatbot.session.start") }}', body);
        if (res.ok) {
            state.convId     = res.conv_id;
            state.mode       = res.mode;
            state.quickLinks = res.quick_links || [];

            // ─── Guest: hide form, show chat body + input ONLY ───
            if (!IS_LOGGED_IN && guestName) {
                const guestForm = document.getElementById('hn-guest-form');
                if (guestForm) guestForm.style.display = 'none';

                // ❌ email notice এখানে show නොකරනු — admin mode switch-ය-දී show කරනු
                // Show chat body + input only
                const chatBody  = document.getElementById('hn-chat-body');
                const inputArea = document.getElementById('hn-chat-input-area');
                if (chatBody)  chatBody.style.display  = '';
                if (inputArea) inputArea.style.display = '';
            }

            _updateModeUI();
            _renderHistory(res.messages || []);
            if ((res.messages || []).length === 0) _addBotMsg(_welcomeMsg(res.user));
            _startPoll();

            if (!state.linksLoaded && state.quickLinks.length) {
                _renderLinks();
                state.linksLoaded = true;
            }

            _scrollBottom();
        }
    } catch (e) {
        _addBotMsg('❌ Could not connect. Please try again.');
    }
}

    async function _doSend(msg) {
        if (!state.convId) return;
        state.sending = true;
        document.getElementById('hn-send-btn').disabled = true;

        _addUserMsg(msg);

        if (state.mode === 'bot') {
            document.getElementById('hn-typing-indicator').classList.remove('d-none');
        }

        try {
            const res = await _post('{{ route("chatbot.message.send") }}', {
                conv_id:    state.convId,
                session_id: state.sessionId,
                message:    msg,
            });

            document.getElementById('hn-typing-indicator').classList.add('d-none');

            if (res.ok) {
                if (res.mode === 'bot' && res.reply) {
                    _addBotMsg(res.reply);
                } else if (res.mode === 'admin') {
                    // Show waiting + reminder that reply comes via email
                    document.getElementById('hn-admin-wait').classList.remove('d-none');
                }
            } else {
                _addBotMsg('⚠️ ' + (res.error || 'Error occurred.'));
            }
        } catch (e) {
            document.getElementById('hn-typing-indicator').classList.add('d-none');
            _addBotMsg('❌ Network error. Please try again.');
        }

        state.sending = false;
        document.getElementById('hn-send-btn').disabled = false;
        document.getElementById('hn-msg-input').focus();
    }

   async function _switchToAdmin() {
    if (!state.convId) return;
    const res = await _post('{{ route("chatbot.switch.admin") }}', {
        conv_id:    state.convId,
        session_id: state.sessionId,
    });
    if (res.ok) {
        state.mode = 'admin';
        _updateModeUI();

        // Show instruction banner — guest-ට පමණයි email line
        if (!IS_LOGGED_IN) {
            // Guest: banner + email note show කරන්න
            document.getElementById('hn-admin-mode-banner').classList.remove('d-none');

            const emailLine = state.guestEmail
                ? `\n📧 Admin reply will be sent to: ${state.guestEmail}`
                : '';
            _addBotMsg(
                '🎧 You are now connected to Live Support.\n' +
                'Please type your complete message below and press Send.' +
                emailLine
            );
        } else {
            // Logged-in user: banner hide, simple message only
            document.getElementById('hn-admin-mode-banner').classList.add('d-none');
            _addBotMsg(
                '🎧 You are now connected to Live Support.\n' +
                'Please type your complete message and press Send.'
            );
        }

        _startPoll();
        _scrollBottom();
    }
}


    async function _switchToBot() {
        if (!state.convId) return;
        const res = await _post('{{ route("chatbot.switch.bot") }}', {
            conv_id:    state.convId,
            session_id: state.sessionId,
        });
        if (res.ok) {
            state.mode = 'bot';
            document.getElementById('hn-admin-wait').classList.add('d-none');
            document.getElementById('hn-admin-mode-banner').classList.add('d-none');
            _updateModeUI();
        }
    }

    function _startPoll() {
        _stopPoll();
        if (state.mode === 'admin') {
            state.pollTimer = setInterval(_pollMessages, 3000);
        }
    }

    function _stopPoll() {
        if (state.pollTimer) {
            clearInterval(state.pollTimer);
            state.pollTimer = null;
        }
    }

    async function _pollMessages() {
        if (!state.convId || state.mode !== 'admin') return;
        try {
            const url  = `{{ url('/chatbot/messages') }}/${state.convId}?after=${state.lastMsgId}`;
            const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (data.ok && data.messages.length) {
                data.messages.forEach(m => {
                    if (m.id > state.lastMsgId) {
                        state.lastMsgId = m.id;
                        if (m.sender_type === 'admin') {
                            document.getElementById('hn-admin-wait').classList.add('d-none');
                            _addAdminMsg(m.message);
                        }
                    }
                });
                _scrollBottom();
            }
        } catch (e) {}
    }

    async function _loadFaqs() {
        const container = document.getElementById('hn-faq-list');
        try {
            const res  = await fetch('{{ route("chatbot.faqs") }}');
            const data = await res.json();
            state.allFaqs    = data.faqs || [];
            state.faqsLoaded = true;

            if (!state.allFaqs.length) {
                container.innerHTML = '<div class="hn-empty"><i class="fas fa-info-circle"></i> No FAQs available.</div>';
                return;
            }

            container.innerHTML = state.allFaqs.map(faq => `
                <div class="hn-faq-item" id="faq-${faq.id}">
                    <div class="hn-faq-q" onclick="HNChat._toggleFaq(${faq.id})">
                        ${_esc(faq.question)}
                        <i class="fas fa-chevron-down" style="font-size:11px;opacity:0.6"></i>
                    </div>
                    <div class="hn-faq-a" id="faq-a-${faq.id}">${_esc(faq.answer)}</div>
                </div>
            `).join('');
        } catch (e) {
            container.innerHTML = '<div class="hn-error">Failed to load FAQs.</div>';
        }
    }

    function _renderLinks() {
        const container = document.getElementById('hn-links-list');
        if (!state.quickLinks.length) {
            container.innerHTML = '<div class="hn-empty"><i class="fas fa-link"></i> No quick links.</div>';
            return;
        }
        container.innerHTML = state.quickLinks.map(l => `
            <a href="${_esc(l.url)}" class="hn-link-item" target="_blank">
                <div class="hn-link-icon"><i class="${_esc(l.icon)}"></i></div>
                ${_esc(l.label)}
                <i class="fas fa-chevron-right ms-auto" style="font-size:10px;opacity:0.4"></i>
            </a>
        `).join('');
        state.linksLoaded = true;
    }

    function _toggleFaq(id) {
        const el = document.getElementById('faq-a-' + id);
        if (el) el.classList.toggle('open');
    }

   function _updateModeUI() {
    const header  = document.getElementById('hn-chat-header');
    const title   = document.getElementById('hn-chat-title');
    const status  = document.getElementById('hn-chat-status');
    const modeBtn = document.getElementById('hn-mode-toggle');
    const banner  = document.getElementById('hn-admin-mode-banner');

    if (state.mode === 'admin') {
        header.classList.add('admin-mode');
        title.textContent = 'Live Admin Chat';
        status.innerHTML  = '<span class="hn-dot" style="background:#4ade80"></span> Connected to Admin';
        modeBtn.title     = 'Switch back to AI Bot';
        modeBtn.innerHTML = '<i class="fas fa-robot"></i>';
        // Banner — guest-ට පමණයි
        if (banner) banner.classList.toggle('d-none', IS_LOGGED_IN);
        _startPoll();
    } else {
        header.classList.remove('admin-mode');
        title.textContent = 'HealthNet Assistant';
        status.innerHTML  = '<span class="hn-dot"></span> Online';
        modeBtn.title     = 'Connect to Admin';
        modeBtn.innerHTML = '<i class="fas fa-headset"></i>';
        if (banner) banner.classList.add('d-none');
        _stopPoll();
    }
}


    function _welcomeMsg(user) {
        if (user && user.logged_in) {
            const name = user.first_name || user.name || 'there';
            return `👋 Hello ${name}! I'm HealthNet AI Assistant.\n\nHow can I help you today? You can ask me about:\n• Health symptoms & advice\n• Finding doctors or hospitals\n• Your appointments & orders\n• Medicine information\n\nFor urgent medical issues, please call emergency services.`;
        }
        // Guest welcome
        const emailNote = state.guestEmail
            ? `\n\n📧 AI replies will also be sent to: ${state.guestEmail}`
            : '\n\n📧 AI replies will also be sent to your email.';
        return `👋 Hello! I'm HealthNet AI Assistant.\n\nI can help you with health questions, finding doctors, and navigating our platform.${emailNote}`;
    }

    function _renderHistory(messages) {
        (messages || []).forEach(m => {
            if      (m.sender_type === 'user')  _addUserMsg(m.message,  true);
            else if (m.sender_type === 'bot')   _addBotMsg(m.message,   true);
            else if (m.sender_type === 'admin') _addAdminMsg(m.message, true);
        });
    }

    function _addUserMsg(msg, noScroll)  { _appendMsg('user',  msg, noScroll); }
    function _addBotMsg(msg, noScroll)   { _appendMsg('bot',   msg, noScroll); }
    function _addAdminMsg(msg, noScroll) { _appendMsg('admin', msg, noScroll); }

    function _appendMsg(type, msg, noScroll) {
        const container = document.getElementById('hn-messages');
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const icons = {
            user:  '<i class="fas fa-user"></i>',
            bot:   '<i class="fas fa-robot"></i>',
            admin: '<i class="fas fa-user-tie"></i>',
        };

        let html;
        if (type === 'system') {
            html = `<div class="hn-msg-wrap hn-msg-system"><div class="hn-msg-bubble">${_esc(msg)}</div></div>`;
        } else {
            html = `
                <div class="hn-msg-wrap hn-msg-${type} ${type === 'user' ? 'user' : ''}">
                    <div class="hn-msg-avatar">${icons[type] || ''}</div>
                    <div>
                        <div class="hn-msg-bubble">${_esc(msg)}</div>
                        <div class="hn-msg-time">${time}</div>
                    </div>
                </div>`;
        }

        container.insertAdjacentHTML('beforeend', html);
        if (!noScroll) _scrollBottom();
    }

    function _scrollBottom() {
        const body = document.getElementById('hn-chat-body');
        if (body) body.scrollTop = body.scrollHeight;
    }

    async function _post(url, data) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF,
                'Accept':        'application/json',
            },
            body: JSON.stringify(data),
        });
        return res.json();
    }

    function _isEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }
    function _esc(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
    function _uuid() {
        return 'xxxx-xxxx-xxxx'.replace(/x/g, () => Math.random().toString(16)[2]);
    }

    return {
        toggle,
        showTab,
        startAsGuest,
        sendMessage,
        handleKey,
        toggleMode,
        filterFaqs,
        _toggleFaq,
    };
})();

// Textarea auto-resize
document.addEventListener('DOMContentLoaded', () => {
    const inp = document.getElementById('hn-msg-input');
    if (inp) {
        inp.addEventListener('input', () => {
            inp.style.height = 'auto';
            inp.style.height = Math.min(inp.scrollHeight, 100) + 'px';
        });
    }
});
</script>
