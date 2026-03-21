{{-- resources/views/hospital/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hospital Dashboard') - HealthNet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; }

    :root {
        --sidebar-width:    260px;
        --sidebar-collapsed: 68px;
        --topbar-height:    58px;
        --primary:          #2969bf;
        --primary-dark:     #1a3a6b;
        --accent:           #42a649;
        --sidebar-bg:       #0f2544;
        --sidebar-border:   rgba(255,255,255,.08);
        --transition:       .3s cubic-bezier(.4,0,.2,1);
    }

    html, body {
        margin: 0; padding: 0;
        font-family: 'Poppins', sans-serif;
        background: #f4f7fb;
        overflow-x: hidden;
    }

    /* ── Layout Wrapper ── */
    .layout-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* ── Main Area ── */
    .main-area {
        flex: 1;
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        transition: margin-left var(--transition);
    }
    .main-area.sidebar-collapsed {
        margin-left: var(--sidebar-collapsed);
    }

    /* ── Page Content ── */
    .page-content {
        padding: calc(var(--topbar-height) + 1.5rem) 1.5rem 1.5rem;
        flex: 1;
    }

    /* ── Responsive ── */
    @media (max-width: 991.98px) {
        .main-area { margin-left: 0 !important; }
        .page-content { padding: calc(var(--topbar-height) + 1rem) 1rem 1rem; }
    }
    @media (max-width: 575.98px) {
        .page-content { padding: calc(var(--topbar-height) + .75rem) .75rem .75rem; }
    }
    </style>

    @stack('styles')
</head>
<body>

<div class="layout-wrapper">

    {{-- ══ SIDEBAR ══ --}}
    @include('hospital.layouts.sidebar')

    {{-- ══ MAIN AREA ══ --}}
    <div class="main-area" id="mainArea">

        {{-- Topbar --}}
        @include('hospital.layouts.topbar')

        {{-- Notification Slide Panel --}}
        @include('hospital.layouts.notifications')

        {{-- Page Content --}}
        <div class="page-content">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
@include('../../partials.chatbot-widget')
</body>
</html>
