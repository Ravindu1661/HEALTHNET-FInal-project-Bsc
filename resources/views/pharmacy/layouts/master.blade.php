<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Pharmacy Dashboard') - HealthNet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        * { font-family: 'Inter', sans-serif; font-size: 13px; }
        body { background: #f4f6fb; }

        /* ── Sidebar ── */
        .sidebar {
            width: 220px; min-height: 100vh; background: #1a3c5e;
            position: fixed; top: 0; left: 0; z-index: 1040;
            display: flex; flex-direction: column;
            transition: width .3s;
        }
        .sidebar.collapsed { width: 60px; }
        .sidebar-brand {
            padding: 14px 16px; background: #122a42;
            font-weight: 700; font-size: 15px; color: #fff;
            display: flex; align-items: center; gap: 10px;
            white-space: nowrap; overflow: hidden;
        }
        .sidebar-brand i { font-size: 18px; color: #38bdf8; flex-shrink: 0; }
        .sidebar-brand span { transition: opacity .2s; }
        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .nav-text { opacity: 0; width: 0; overflow: hidden; }
        .sidebar-nav { padding: 8px 8px; flex: 1; overflow-y: auto; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            color: #b0c4d8; font-size: 12.5px; font-weight: 500;
            text-decoration: none; white-space: nowrap; overflow: hidden;
            transition: background .2s, color .2s;
        }
        .nav-link i { font-size: 14px; flex-shrink: 0; width: 18px; text-align: center; }
        .nav-link:hover { background: rgba(255,255,255,.1); color: #fff; }
        .nav-link.active { background: #38bdf8; color: #fff; }
        .nav-link .badge { font-size: 10px; margin-left: auto; }
        .sidebar-divider { border-color: rgba(255,255,255,.1); margin: 6px 0; }

        /* ── Topbar ── */
        .topbar {
            height: 52px; background: #fff;
            border-bottom: 1px solid #e5eaf2;
            display: flex; align-items: center;
            padding: 0 16px; gap: 12px;
            position: sticky; top: 0; z-index: 1030;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .topbar .page-title { font-size: 14px; font-weight: 700; color: #1a3c5e; margin: 0; }
        .topbar .page-subtitle { font-size: 11px; color: #8898aa; margin: 0; }

        /* ── Main Content ── */
        .main-content {
            margin-left: 220px; transition: margin-left .3s;
            min-height: 100vh; display: flex; flex-direction: column;
        }
        .main-content.expanded { margin-left: 60px; }
        .content-area { padding: 18px; flex: 1; }

        /* ── Stat Cards ── */
        .stat-card {
            border: none; border-radius: 10px; padding: 14px 16px;
            display: flex; align-items: center; gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .stat-card .stat-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .stat-card .stat-label { font-size: 11px; color: #8898aa; font-weight: 500; margin-bottom: 2px; }
        .stat-card .stat-value { font-size: 20px; font-weight: 700; color: #1a3c5e; line-height: 1; }
        .stat-card .stat-link { font-size: 11px; margin-top: 3px; }

        /* ── Cards ── */
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.07); }
        .card-header {
            background: #fff; border-bottom: 1px solid #f0f2f7;
            padding: 12px 16px; border-radius: 10px 10px 0 0 !important;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h6 { font-size: 13px; font-weight: 700; color: #1a3c5e; margin: 0; }
        .card-body { padding: 14px 16px; }

        /* ── Tables ── */
        .table { font-size: 12px; }
        .table thead th { font-size: 11px; font-weight: 600; color: #8898aa; border-top: none; background: #f8fafc; }
        .table td { vertical-align: middle; color: #4a5568; }

        /* ── Badges ── */
        .badge { font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px; }

        /* ── Notification Panel ── */
        .notif-panel {
            position: fixed; right: -320px; top: 0; width: 320px;
            height: 100vh; background: #fff; z-index: 1050;
            box-shadow: -4px 0 20px rgba(0,0,0,.12);
            transition: right .3s; display: flex; flex-direction: column;
        }
        .notif-panel.open { right: 0; }
        .notif-panel-header {
            padding: 14px 16px; background: #1a3c5e; color: #fff;
            display: flex; align-items: center; justify-content: space-between;
        }
        .notif-panel-header h6 { margin: 0; font-size: 13px; font-weight: 600; }
        .notif-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.3);
            z-index: 1049; display: none;
        }
        .notif-overlay.open { display: block; }
        .notif-item {
            padding: 10px 14px; border-bottom: 1px solid #f0f2f7;
            cursor: pointer; transition: background .2s;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #eff8ff; border-left: 3px solid #38bdf8; }
        .notif-item h6 { font-size: 12px; font-weight: 600; margin-bottom: 2px; color: #1a3c5e; }
        .notif-item p { font-size: 11px; color: #8898aa; margin: 0; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .sidebar .sidebar-brand span,
            .sidebar .nav-text { opacity: 0; width: 0; overflow: hidden; }
            .main-content { margin-left: 60px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
@include('pharmacy.layouts.sidebar')

{{-- Main --}}
<div class="main-content" id="mainContent">
    @include('pharmacy.layouts.topbar')
    <div class="content-area">
        @yield('content')
    </div>
</div>

{{-- Notifications --}}
@include('pharmacy.layouts.notifications')

{{-- SweetAlert Flash --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:18px;right:18px;z-index:9999;min-width:280px;';
        toast.innerHTML = `<div class="alert alert-success shadow d-flex align-items-center gap-2 py-2 px-3" style="font-size:12.5px">
            <i class="fas fa-check-circle text-success"></i>
            <span>{{ addslashes(session('success')) }}</span>
            <button type="button" class="btn-close ms-auto" style="font-size:10px" onclick="this.closest('.alert').remove()"></button>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:18px;right:18px;z-index:9999;min-width:280px;';
        toast.innerHTML = `<div class="alert alert-danger shadow d-flex align-items-center gap-2 py-2 px-3" style="font-size:12.5px">
            <i class="fas fa-exclamation-circle text-danger"></i>
            <span>{{ addslashes(session('error')) }}</span>
            <button type="button" class="btn-close ms-auto" style="font-size:10px" onclick="this.closest('.alert').remove()"></button>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    }
    // Notification panel
    const notifIcon = document.getElementById('notifIcon');
    const notifPanel = document.getElementById('notifPanel');
    const notifOverlay = document.getElementById('notifOverlay');
    const closeNotif = document.getElementById('closeNotif');
    if (notifIcon) {
        notifIcon.addEventListener('click', () => {
            notifPanel.classList.add('open');
            notifOverlay.classList.add('open');
        });
    }
    function closeNotifPanel() {
        notifPanel?.classList.remove('open');
        notifOverlay?.classList.remove('open');
    }
    closeNotif?.addEventListener('click', closeNotifPanel);
    notifOverlay?.addEventListener('click', closeNotifPanel);
</script>
@stack('scripts')
</body>
</html>
