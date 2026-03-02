{{-- resources/views/medical_centre/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — HealthNet Medical Centre</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
    /* ══════════════════════════════════════════
       CSS VARIABLES
    ══════════════════════════════════════════ */
    :root {
        --mc-primary:       #16a085;
        --mc-primary-dark:  #0e7060;
        --mc-primary-light: #e8f8f5;
        --mc-secondary:     #1abc9c;
        --mc-accent:        #2ecc71;
        --sidebar-w:        260px;
        --sidebar-w-col:    70px;
        --topbar-h:         64px;
        --radius:           12px;
        --shadow-sm:        0 2px 8px rgba(0,0,0,.06);
        --shadow-md:        0 4px 20px rgba(0,0,0,.10);
        --shadow-lg:        0 8px 32px rgba(0,0,0,.14);
        --border:           #eef2f7;
        --text-dark:        #1a2332;
        --text-muted:       #7f8c9a;
        --bg-main:          #f4f7fb;
        --transition:       all .25s cubic-bezier(.4,0,.2,1);
    }

    /* ══════════════════════════════════════════
       RESET & BASE
    ══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 15px; scroll-behavior: smooth; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-main);
        color: var(--text-dark);
        line-height: 1.6;
        overflow-x: hidden;
        min-height: 100vh;
    }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; }
    button { font-family: inherit; }

    /* ══════════════════════════════════════════
       LAYOUT WRAPPER
    ══════════════════════════════════════════ */
    .mc-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* ══════════════════════════════════════════
       SIDEBAR
    ══════════════════════════════════════════ */
    .mc-sidebar {
        width: var(--sidebar-w);
        background: linear-gradient(180deg, #0d3b2e 0%, #16a085 100%);
        position: fixed;
        top: 0; left: 0; bottom: 0;
        z-index: 1040;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        overflow: hidden;
    }
    .mc-sidebar.collapsed { width: var(--sidebar-w-col); }

    /* ══════════════════════════════════════════
       MAIN CONTENT AREA
    ══════════════════════════════════════════ */
    .mc-main {
        flex: 1;
        margin-left: var(--sidebar-w);
        display: flex;
        flex-direction: column;
        min-width: 0;
        transition: margin-left .25s cubic-bezier(.4,0,.2,1);
    }
    .mc-main.expanded { margin-left: var(--sidebar-w-col); }

    .mc-topbar {
        height: var(--topbar-h);
        background: #fff;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 1030;
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .mc-content {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
    }

    /* Page heading */
    .mc-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .mc-page-header h4 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .mc-page-header h4 i {
        color: var(--mc-primary);
        font-size: 1rem;
    }

    /* ══════════════════════════════════════════
       OVERLAY (mobile)
    ══════════════════════════════════════════ */
    .mc-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.55);
        z-index: 1039;
        backdrop-filter: blur(2px);
    }
    .mc-overlay.show { display: block; }

    /* ══════════════════════════════════════════
       SCROLLBAR
    ══════════════════════════════════════════ */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cdd8e3; border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: #aab4be; }

    /* ══════════════════════════════════════════
       COMMON CARD
    ══════════════════════════════════════════ */
    .mc-card {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .mc-card-head {
        padding: .85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .mc-card-head h6 {
        font-size: .88rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        color: var(--text-dark);
    }
    .mc-card-body { padding: 1.25rem; }

    /* ══════════════════════════════════════════
       BADGE / STATUS
    ══════════════════════════════════════════ */
    .mc-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .7rem;
        font-weight: 700;
        padding: .22rem .6rem;
        border-radius: 99px;
    }
    .mc-badge.pending   { background: #fff3cd; color: #856404; }
    .mc-badge.confirmed { background: #cfe2ff; color: #084298; }
    .mc-badge.completed { background: #d1e7dd; color: #0a3622; }
    .mc-badge.cancelled { background: #f8d7da; color: #58151c; }
    .mc-badge.approved  { background: #d1e7dd; color: #0a3622; }
    .mc-badge.rejected  { background: #f8d7da; color: #58151c; }
    .mc-badge.active    { background: #d1e7dd; color: #0a3622; }
    .mc-badge.suspended { background: #fff3cd; color: #856404; }

    /* ══════════════════════════════════════════
       TOAST
    ══════════════════════════════════════════ */
    #mc-toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        pointer-events: none;
    }
    .mc-toast {
        background: #fff;
        border-radius: 12px;
        padding: .8rem 1.1rem;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: .7rem;
        font-size: .82rem;
        font-weight: 600;
        min-width: 260px;
        max-width: 340px;
        pointer-events: all;
        animation: toastIn .3s ease;
        border-left: 4px solid transparent;
    }
    @keyframes toastIn {
        from { opacity:0; transform:translateY(16px) scale(.97); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    .mc-toast.success { border-left-color: #27ae60; color: #0a3622; }
    .mc-toast.error   { border-left-color: #e74c3c; color: #58151c; }
    .mc-toast.info    { border-left-color: #2969bf; color: #084298; }
    .mc-toast.warning { border-left-color: #f39c12; color: #856404; }
    .mc-toast i { font-size: .9rem; }

    /* ══════════════════════════════════════════
       LOADER
    ══════════════════════════════════════════ */
    .mc-loader {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        color: var(--text-muted);
        font-size: .82rem;
        padding: 2rem;
    }
    .mc-spinner {
        width: 20px; height: 20px;
        border: 2.5px solid #e0e7ef;
        border-top-color: var(--mc-primary);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ══════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════ */
    @media (max-width: 991.98px) {
        .mc-sidebar {
            left: calc(-1 * var(--sidebar-w));
            width: var(--sidebar-w);
        }
        .mc-sidebar.mobile-open { left: 0; }
        .mc-main { margin-left: 0 !important; }
        .mc-content { padding: 1rem; }
        .mc-topbar { padding: 0 1rem; }
    }
    @media (max-width: 575.98px) {
        .mc-content { padding: .75rem; }
        .mc-page-header h4 { font-size: 1rem; }
    }
    </style>

    @stack('styles')
</head>
<body>

<div class="mc-wrapper">

    {{-- ══ SIDEBAR ══ --}}
    @include('medical_centre.layouts.partials.sidebar')

    {{-- ══ OVERLAY (mobile) ══ --}}
    <div class="mc-overlay" id="mcOverlay" onclick="closeSidebar()"></div>

    {{-- ══ MAIN ══ --}}
    <div class="mc-main" id="mcMain">

        {{-- ══ TOPBAR ══ --}}
        @include('medical_centre.layouts.partials.topbar')

        {{-- ══ CONTENT ══ --}}
        <main class="mc-content">
            <div class="mc-page-header">
                <h4>
                    <i class="fas fa-@yield('page-icon', 'tachometer-alt')"></i>
                    @yield('page-title', 'Dashboard')
                </h4>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @yield('page-actions')
                </div>
            </div>

            @yield('content')
        </main>

    </div>{{-- end mc-main --}}
</div>

{{-- Toast Container --}}
<div id="mc-toast-container"></div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// ════════════════════════════════════════
// CSRF SETUP
// ════════════════════════════════════════
const MC_CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ════════════════════════════════════════
// SIDEBAR TOGGLE
// ════════════════════════════════════════
const mcSidebar  = document.getElementById('mcSidebar');
const mcMain     = document.getElementById('mcMain');
const mcOverlay  = document.getElementById('mcOverlay');
const COLLAPSED_KEY = 'mc_sidebar_collapsed';

function initSidebar() {
    const isDesktop = window.innerWidth >= 992;
    if (isDesktop && localStorage.getItem(COLLAPSED_KEY) === '1') {
        mcSidebar.classList.add('collapsed');
        mcMain.classList.add('expanded');
    }
}

function toggleSidebar() {
    if (window.innerWidth >= 992) {
        mcSidebar.classList.toggle('collapsed');
        mcMain.classList.toggle('expanded');
        localStorage.setItem(COLLAPSED_KEY,
            mcSidebar.classList.contains('collapsed') ? '1' : '0');
    } else {
        mcSidebar.classList.toggle('mobile-open');
        mcOverlay.classList.toggle('show');
        document.body.style.overflow =
            mcSidebar.classList.contains('mobile-open') ? 'hidden' : '';
    }
}

function closeSidebar() {
    mcSidebar.classList.remove('mobile-open');
    mcOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
        mcSidebar.classList.remove('mobile-open');
        mcOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }
});

// ════════════════════════════════════════
// TOAST
// ════════════════════════════════════════
function mcToast(msg, type = 'success', duration = 3500) {
    const icons = {
        success: 'fa-check-circle',
        error:   'fa-exclamation-circle',
        info:    'fa-info-circle',
        warning: 'fa-exclamation-triangle',
    };
    const t = document.createElement('div');
    t.className = `mc-toast ${type}`;
    t.innerHTML = `<i class="fas ${icons[type] ?? 'fa-bell'}"></i><span>${msg}</span>`;
    document.getElementById('mc-toast-container').appendChild(t);
    setTimeout(() => {
        t.style.transition = 'opacity .3s, transform .3s';
        t.style.opacity = '0';
        t.style.transform = 'translateY(8px)';
        setTimeout(() => t.remove(), 320);
    }, duration);
}

// ════════════════════════════════════════
// AJAX HELPER
// ════════════════════════════════════════
async function mcFetch(url, options = {}) {
    const defaults = {
        headers: {
            'X-CSRF-TOKEN': MC_CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    };
    const res = await fetch(url, { ...defaults, ...options });
    return res.json();
}

// ════════════════════════════════════════
// ACTIVE NAV HIGHLIGHT
// ════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    const path = window.location.pathname;
    document.querySelectorAll('.mc-nav-link').forEach(link => {
        if (link.getAttribute('href') && path.startsWith(link.getAttribute('href'))) {
            link.classList.add('active');
            const group = link.closest('.mc-nav-group');
            if (group) group.classList.add('open');
        }
    });
});
</script>

@stack('scripts')
</body>
</html>
