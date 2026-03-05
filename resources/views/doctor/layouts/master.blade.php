<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doctor Portal') — HealthNet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 255px;
            --sidebar-col: 68px;
            --topbar-h: 62px;
            --bg: #f2f5f9;
            --primary: #0d6efd;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; overflow-x: hidden; }

        /* ── SIDEBAR ── */
        .doc-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(175deg, #111827 0%, #0d1117 100%);
            z-index: 1040; transition: width .28s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .doc-sidebar.collapsed { width: var(--sidebar-col); }

        .sb-brand {
            height: var(--topbar-h); display: flex; align-items: center;
            padding: 0 1.1rem; gap: .75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sb-logo {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, #0d6efd, #0a4fcf);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .9rem;
        }
        .sb-brand-text { color: #fff; font-weight: 800; font-size: 1rem; white-space: nowrap; transition: opacity .2s; }
        .doc-sidebar.collapsed .sb-brand-text { opacity: 0; width: 0; }

        .sb-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: .6rem 0; }
        .sb-nav::-webkit-scrollbar { width: 3px; }
        .sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

        .sb-section { font-size: .62rem; font-weight: 700; letter-spacing: 1.2px;
            color: rgba(255,255,255,.3); text-transform: uppercase;
            padding: .8rem 1.2rem .25rem; white-space: nowrap; transition: opacity .2s; }
        .doc-sidebar.collapsed .sb-section { opacity: 0; }

        .sb-link {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem 1.1rem; color: rgba(255,255,255,.6);
            text-decoration: none; font-size: .83rem; font-weight: 500;
            transition: all .18s; position: relative; white-space: nowrap;
            overflow: hidden;
        }
        .sb-link:hover { color: #fff; background: rgba(255,255,255,.07); }
        .sb-link.active { color: #fff; background: rgba(13,110,253,.25); }
        .sb-link.active::before {
            content: ''; position: absolute; left: 0; top: 18%; bottom: 18%;
            width: 3px; background: #0d6efd; border-radius: 0 3px 3px 0;
        }
        .sb-link i { width: 22px; font-size: .9rem; text-align: center; flex-shrink: 0; }
        .sb-link-text { transition: opacity .2s; }
        .doc-sidebar.collapsed .sb-link-text { opacity: 0; }

        .sb-badge {
            margin-left: auto; background: #ef4444; color: #fff;
            font-size: .62rem; font-weight: 700; padding: .1rem .42rem;
            border-radius: 10px; min-width: 18px; text-align: center; flex-shrink: 0;
            transition: opacity .2s;
        }
        .doc-sidebar.collapsed .sb-badge { opacity: 0; }

        .sb-user {
            padding: .9rem 1.1rem; border-top: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: .75rem; flex-shrink: 0;
        }
        .sb-user img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(255,255,255,.15); flex-shrink: 0; }
        .sb-user-name { color: #fff; font-size: .8rem; font-weight: 700; white-space: nowrap; }
        .sb-user-role { color: rgba(255,255,255,.45); font-size: .68rem; white-space: nowrap; }
        .doc-sidebar.collapsed .sb-user-info { opacity: 0; width: 0; overflow: hidden; }

        /* ── TOPBAR ── */
        .doc-topbar {
            position: fixed; top: 0; right: 0; left: var(--sidebar-w);
            height: var(--topbar-h); background: #fff;
            border-bottom: 1px solid #eaecf0;
            display: flex; align-items: center; padding: 0 1.4rem; gap: 1rem;
            z-index: 1039; transition: left .28s cubic-bezier(.4,0,.2,1);
        }
        .doc-topbar.expanded { left: var(--sidebar-col); }

        .topbar-toggle {
            width: 36px; height: 36px; border: none;
            background: #f5f7fa; border-radius: 9px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #555; flex-shrink: 0; transition: background .15s;
        }
        .topbar-toggle:hover { background: #e8ecf0; }

        .topbar-title { font-weight: 700; font-size: .97rem; color: #1a1a1a; flex-grow: 1; }

        .topbar-actions { display: flex; align-items: center; gap: .55rem; }

        .tb-icon-btn {
            width: 38px; height: 38px; border: none; background: #f5f7fa;
            border-radius: 9px; cursor: pointer; position: relative;
            display: flex; align-items: center; justify-content: center;
            color: #555; transition: background .15s;
        }
        .tb-icon-btn:hover { background: #e8ecf0; }

        .tb-notif-badge {
            position: absolute; top: 5px; right: 5px;
            background: #ef4444; color: #fff; font-size: .58rem; font-weight: 800;
            width: 16px; height: 16px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff; line-height: 1;
        }

        .tb-avatar-wrap { position: relative; cursor: pointer; }
        .tb-avatar {
            width: 38px; height: 38px; border-radius: 50%; object-fit: cover;
            border: 2px solid #eaecf0; transition: border-color .15s;
        }
        .tb-avatar-wrap:hover .tb-avatar { border-color: #0d6efd; }
        .tb-online {
            position: absolute; bottom: 1px; right: 1px;
            width: 10px; height: 10px; background: #22c55e;
            border-radius: 50%; border: 2px solid #fff;
        }

        /* ── CONTENT ── */
        .doc-content {
            margin-left: var(--sidebar-w); padding-top: var(--topbar-h);
            min-height: 100vh; transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }
        .doc-content.expanded { margin-left: var(--sidebar-col); }
        .doc-inner { padding: 1.4rem; }

        /* ── NOTIFICATION PANEL ── */
        .notif-panel {
            position: absolute; top: calc(100% + 10px); right: -8px;
            width: 360px; background: #fff; border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,.13);
            border: 1px solid #e8edf2; display: none; overflow: hidden;
            animation: npSlide .18s ease;
        }
        .notif-panel.open { display: block; }
        @keyframes npSlide { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
        .np-header {
            padding: .85rem 1.1rem; background: #fafbfc;
            border-bottom: 1px solid #f0f2f5;
            display: flex; align-items: center; justify-content: space-between;
        }
        .np-title { font-size: .88rem; font-weight: 700; color: #1a1a1a; }
        .np-mark-all { border: none; background: none; color: #0d6efd;
            font-size: .75rem; font-weight: 600; cursor: pointer; }
        .np-list { max-height: 320px; overflow-y: auto; }
        .np-item {
            padding: .82rem 1.1rem; border-bottom: 1px solid #f5f7fa;
            cursor: pointer; transition: background .13s; display: flex; gap: .7rem;
        }
        .np-item:last-child { border-bottom: none; }
        .np-item:hover { background: #f8f9fa; }
        .np-item.unread { background: #eff6ff; }
        .np-item.unread:hover { background: #dbeafe; }
        .np-icon {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: .78rem;
        }
        .np-icon.appointment { background: rgba(13,110,253,.1); color: #0d6efd; }
        .np-icon.payment     { background: rgba(25,135,84,.1);  color: #198754; }
        .np-icon.general, .np-icon.system { background: rgba(108,117,125,.1); color: #6c757d; }
        .np-body { flex: 1; min-width: 0; }
        .np-body-title { font-size: .8rem; font-weight: 700; color: #1a1a1a; }
        .np-body-msg { font-size: .73rem; color: #666; margin-top: .1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .np-body-time { font-size: .67rem; color: #aaa; margin-top: .2rem; }
        .np-dot { width: 7px; height: 7px; background: #0d6efd; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
        .np-footer { padding: .65rem; text-align: center; border-top: 1px solid #f0f2f5; background: #fafbfc; }
        .np-footer a { font-size: .78rem; color: #0d6efd; font-weight: 600; text-decoration: none; }
        .np-footer a:hover { text-decoration: underline; }
        .np-empty { padding: 2rem; text-align: center; color: #bbb; }
        .np-empty i { font-size: 1.8rem; display: block; margin-bottom: .4rem; }
        .np-loading { padding: 1.5rem; text-align: center; color: #aaa; font-size: .8rem; }

        /* ── TOPBAR DROPDOWN ── */
        .tb-dropdown { border-radius: 14px !important; box-shadow: 0 8px 28px rgba(0,0,0,.1) !important;
            border: 1px solid #e8edf2 !important; padding: .4rem !important; min-width: 210px !important; }
        .tb-dh { display: flex; gap: .7rem; align-items: center; padding: .6rem .7rem .75rem; }
        .tb-dh img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
            border: 2px solid #e8edf2; flex-shrink: 0; }
        .tb-dh-name { font-size: .86rem; font-weight: 700; color: #1a1a1a; }
        .tb-dh-email { font-size: .7rem; color: #888; word-break: break-all; }
        .tb-dh-badge { font-size: .63rem; font-weight: 700; padding: .1rem .45rem;
            border-radius: 20px; display: inline-block; margin-top: .2rem; }
        .tb-dh-badge.approved { background: #d4edda; color: #155724; }
        .tb-dh-badge.pending  { background: #fff3cd; color: #856404; }
        .tb-menu-item { display: flex !important; align-items: center; gap: .55rem;
            padding: .46rem .7rem !important; border-radius: 8px !important;
            font-size: .82rem !important; font-weight: 500 !important;
            color: #333 !important; transition: background .13s !important; }
        .tb-menu-item i { width: 15px; text-align: center; opacity: .65; }
        .tb-menu-item:hover { background: #eff6ff !important; color: #0d6efd !important; }
        .tb-menu-item:hover i { opacity: 1; }
        .tb-menu-item.logout { color: #dc3545 !important; }
        .tb-menu-item.logout:hover { background: #fff5f5 !important; }

        /* ── OVERLAY (mobile) ── */
        .sb-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1038; display: none; }
        .sb-overlay.show { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .doc-sidebar { left: calc(-1 * var(--sidebar-w)); width: var(--sidebar-w) !important; }
            .doc-sidebar.mob-open { left: 0; }
            .doc-topbar { left: 0 !important; }
            .doc-content { margin-left: 0 !important; }
            .doc-inner { padding: .9rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $layoutUser   = Auth::user();
    $layoutDoctor = $layoutUser?->doctor;
@endphp

<div class="sb-overlay" id="sbOverlay" onclick="closeMobileSb()"></div>

@include('doctor.partials.sidebar')
@include('doctor.partials.topbar')

<div class="doc-content" id="docContent">
    <div class="doc-inner">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // ── Sidebar State ──
    const docSidebar  = document.getElementById('docSidebar');
    const docContent  = document.getElementById('docContent');
    const docTopbar   = document.getElementById('docTopbar');
    let sbCollapsed   = localStorage.getItem('docSbCollapsed') === 'true';

    function applySb() {
        if (window.innerWidth <= 768) return;
        sbCollapsed
            ? (docSidebar.classList.add('collapsed'), docContent.classList.add('expanded'), docTopbar.classList.add('expanded'))
            : (docSidebar.classList.remove('collapsed'), docContent.classList.remove('expanded'), docTopbar.classList.remove('expanded'));
    }

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            docSidebar.classList.toggle('mob-open');
            document.getElementById('sbOverlay').classList.toggle('show');
        } else {
            sbCollapsed = !sbCollapsed;
            localStorage.setItem('docSbCollapsed', sbCollapsed);
            applySb();
        }
    }

    function closeMobileSb() {
        docSidebar.classList.remove('mob-open');
        document.getElementById('sbOverlay').classList.remove('show');
    }

    applySb();
    window.addEventListener('resize', applySb);
</script>
@stack('scripts')
{{-- @include('partials.chatbot-widget') --}}
</body>
</html>
