<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Pharmacy Dashboard') - HealthNet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        /* ══ Base ═══════════════════════════════════════ */
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f4f6fb; margin: 0; padding: 0; font-size: 13px; }

        /* ══ Layout ══════════════════════════════════════ */
        :root { --sb-width: 220px; --sb-collapsed: 60px; --topbar-h: 52px; }

        .main-content {
            margin-left: var(--sb-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .3s ease;
        }
        .main-content.expanded { margin-left: var(--sb-collapsed); }

        .content-area {
            padding: 18px;
            flex: 1;
        }

        /* ══ Topbar ══════════════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e5eaf2;
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .topbar-toggle {
            background: none;
            border: none;
            color: #1a3c5e;
            font-size: 15px;
            padding: 5px 7px;
            border-radius: 7px;
            cursor: pointer;
            transition: background .2s;
            flex-shrink: 0;
        }
        .topbar-toggle:hover { background: #eef2f7; }
        .topbar-title { font-size: 13.5px; font-weight: 700; color: #1a3c5e; margin: 0; line-height: 1.2; }
        .topbar-sub   { font-size: 11px; color: #8898aa; margin: 0; line-height: 1; }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

        /* Topbar icon buttons */
        .tb-icon-btn {
            position: relative;
            width: 34px; height: 34px;
            background: #f4f6fb;
            border: none;
            border-radius: 9px;
            color: #4a5568;
            font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .tb-icon-btn:hover { background: #e8edf5; color: #1a3c5e; }
        .tb-badge {
            position: absolute;
            top: -3px; right: -3px;
            min-width: 16px; height: 16px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px;
            border: 2px solid #fff;
        }

        /* Topbar user */
        .tb-user {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 4px 8px;
            border-radius: 9px;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
        }
        .tb-user:hover { background: #f0f4f8; }
        .tb-user-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: #1a3c5e;
            display: flex; align-items: center; justify-content: center;
            color: #38bdf8;
            font-size: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .tb-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .tb-user-name { font-size: 12px; font-weight: 600; color: #1a3c5e; white-space: nowrap; }
        .tb-user-role { font-size: 10px; color: #8898aa; white-space: nowrap; }

        /* ══ Mobile Overlay ═══════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.open { display: block; }

        /* ══ Cards ════════════════════════════════════════ */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f2f7;
            padding: 12px 16px;
            border-radius: 10px 10px 0 0 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h6 { font-size: 13px; font-weight: 700; color: #1a3c5e; margin: 0; }
        .card-body { padding: 14px 16px; }

        /* ══ Stat Cards ═══════════════════════════════════ */
        .stat-card {
            border: none;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .stat-label { font-size: 11px; color: #8898aa; font-weight: 500; margin-bottom: 2px; }
        .stat-value { font-size: 20px; font-weight: 700; color: #1a3c5e; line-height: 1; }
        .stat-link  { font-size: 11px; margin-top: 3px; }

        /* ══ Tables ═══════════════════════════════════════ */
        .table { font-size: 12px; }
        .table thead th {
            font-size: 11px;
            font-weight: 600;
            color: #8898aa;
            border-top: none;
            background: #f8fafc;
        }
        .table td { vertical-align: middle; color: #4a5568; }

        /* ══ Badges ═══════════════════════════════════════ */
        .badge { color: #000 !important; }
        .badge.bg-primary,
        .badge.bg-success,
        .badge.bg-danger,
        .badge.bg-dark,
        .badge.bg-secondary,
        .badge.rounded-pill { color: #fff !important; }
        .badge.bg-warning,
        .badge.bg-light { color: #000 !important; }


        /* ══ Notification Panel ═══════════════════════════ */
        .notif-panel {
            position: fixed;
            right: -320px; top: 0;
            width: 320px; height: 100vh;
            background: #fff;
            z-index: 1050;
            box-shadow: -4px 0 20px rgba(0,0,0,.12);
            transition: right .3s;
            display: flex;
            flex-direction: column;
        }
        .notif-panel.open { right: 0; }
        .notif-panel-header {
            padding: 14px 16px;
            background: #1a3c5e;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .notif-panel-header h6 { margin: 0; font-size: 13px; font-weight: 600; }
        .notif-panel-body { flex: 1; overflow-y: auto; }
        .notif-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.3);
            z-index: 1049;
            display: none;
        }
        .notif-overlay.open { display: block; }
        .notif-item {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f2f7;
            cursor: pointer;
            transition: background .2s;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #eff8ff; border-left: 3px solid #38bdf8; }
        .notif-item h6 { font-size: 12px; font-weight: 600; margin-bottom: 2px; color: #1a3c5e; }
        .notif-item p  { font-size: 11px; color: #8898aa; margin: 0; }

        /* ══ Toast ════════════════════════════════════════ */
        .ph-toast {
            position: fixed;
            top: 16px; right: 16px;
            z-index: 9999;
            min-width: 280px;
            max-width: 360px;
            animation: toastIn .3s ease;
        }
        @keyframes toastIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

        /* ══ Responsive ═══════════════════════════════════ */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%) !important;
                transition: transform .3s ease, width .3s ease !important;
                width: var(--sb-width) !important;
                box-shadow: none;
            }
            .sidebar.mobile-open {
                transform: translateX(0) !important;
                box-shadow: 4px 0 20px rgba(0,0,0,.25);
            }
            .main-content,
            .main-content.expanded {
                margin-left: 0 !important;
            }
            .content-area { padding: 12px !important; }
        }

        @media (max-width: 576px) {
            .content-area { padding: 10px !important; }
            .card-body  { padding: 10px 12px !important; }
            .card-header { padding: 10px 12px !important; }
            .stat-card  { padding: 10px 12px; }
            .stat-value { font-size: 17px; }
            .tb-user-name,
            .tb-user-role { display: none; }
            .notif-panel { width: 100%; right: -100%; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Mobile Overlay ── --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ── Sidebar ── --}}
@include('pharmacy.layouts.sidebar')

{{-- ── Main ── --}}
<div class="main-content" id="mainContent">

    {{-- Topbar --}}
    @include('pharmacy.layouts.topbar')

    {{-- Content --}}
    <div class="content-area">
        @yield('content')
    </div>

</div>

{{-- ── Notification Panel ── --}}
@include('pharmacy.layouts.notifications')

{{-- ── Notification Overlay ── --}}
<div class="notif-overlay" id="notifOverlay"></div>

{{-- ── Flash Toasts ── --}}
@foreach(['success' => ['success','check-circle'], 'error' => ['danger','exclamation-circle'], 'info' => ['info','info-circle']] as $sk => $sv)
@if(session($sk))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const t = document.createElement('div');
    t.className = 'ph-toast';
    t.innerHTML = `<div class="alert alert-{{ $sv[0] }} shadow-sm d-flex align-items-center gap-2 py-2 px-3 mb-0" style="font-size:12.5px;border-radius:10px">
        <i class="fas fa-{{ $sv[1] }}"></i>
        <span>{{ addslashes(session($sk)) }}</span>
        <button type="button" class="btn-close ms-auto" style="font-size:10px" onclick="this.closest('.ph-toast').remove()"></button>
    </div>`;
    document.body.appendChild(t);
    setTimeout(() => t.style.opacity = '0', 3700);
    setTimeout(() => t.remove(), 4000);
});
</script>
@endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ══ Sidebar ══════════════════════════════════════════════
const sidebar        = document.getElementById('sidebar');
const mainContent    = document.getElementById('mainContent');
const sidebarToggle  = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');

const isMobile = () => window.innerWidth < 992;

function openSidebar() {
    if (isMobile()) {
        sidebar.classList.add('mobile-open');
        sidebarOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    }
}
function closeSidebar() {
    sidebar.classList.remove('mobile-open');
    sidebarOverlay.classList.remove('open');
    document.body.style.overflow = '';
}

sidebarToggle?.addEventListener('click', openSidebar);
sidebarOverlay?.addEventListener('click', closeSidebar);

// Mobile-ල nav click — auto close
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', () => { if (isMobile()) closeSidebar(); });
});

// Resize cleanup
window.addEventListener('resize', () => {
    if (!isMobile()) { closeSidebar(); }
});

// ══ Notification Panel ════════════════════════════════════
const notifIcon  = document.getElementById('notifIcon');
const notifPanel = document.getElementById('notifPanel');
const notifOv    = document.getElementById('notifOverlay');
const closeNotif = document.getElementById('closeNotif');

function closeNotifPanel() {
    notifPanel?.classList.remove('open');
    notifOv?.classList.remove('open');
}
notifIcon?.addEventListener('click', () => {
    notifPanel?.classList.add('open');
    notifOv?.classList.add('open');
});
closeNotif?.addEventListener('click', closeNotifPanel);
notifOv?.addEventListener('click', closeNotifPanel);

// ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeSidebar(); closeNotifPanel(); }
});
</script>

@stack('scripts')
</body>
</html>
