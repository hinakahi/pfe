<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Résidence Si Ouakli')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8f9fa;
            --bg-sidebar: linear-gradient(180deg, #1a3c5e 0%, #2d6a9f 100%);
            --bg-card: #ffffff;
            --bg-topbar: #ffffff;
            --text-main: #212529;
            --text-muted: #6c757d;
            --shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        [data-theme="dark"] {
            --bg-body: #1a1d23;
            --bg-sidebar: linear-gradient(180deg, #0d1f33 0%, #1a3c5e 100%);
            --bg-card: #242830;
            --bg-topbar: #242830;
            --text-main: #e9ecef;
            --text-muted: #adb5bd;
            --shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-sidebar);
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            padding-top: 20px;
            z-index: 100;
            transition: width 0.3s ease, transform 0.3s ease;
            overflow: hidden;
        }
        .sidebar.collapsed { 
            width: 80px; 
        }
        .sidebar.collapsed span { 
            display: none; 
        }
        .sidebar.collapsed .sidebar-brand { 
            justify-content: center; 
            padding: 0 8px 20px;
            gap: 0;
        }
        .sidebar.collapsed .nav-link { 
            padding: 10px 8px ;
            justify-content: center;
            overflow: hidden;
        }
        .sidebar.collapsed .nav-link i { 
            margin-right: 0 ;
            font-size: 1.3rem;
            width: 100%;
            text-align: center;
        }
        .sidebar.collapsed hr {
            margin: 8px 5px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .sidebar .nav-link i { 
            margin-right: 8px;
            min-width: 20px;
            text-align: center;
        }
        .sidebar-brand {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px 20px 20px 50px;
            transition: margin-left 0.3s;
        }
        .main-content.expanded { 
            margin-left: 80px; 
        }
        .topbar {
            background: var(--bg-topbar);
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card {
            background: var(--bg-card);
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            color: var(--text-main);
        }
        .table { 
            color: var(--text-main); 
        }
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            margin-bottom: 20px;
        }
        .stat-card .number { 
            font-size: 2rem; 
            font-weight: 700; 
        }
        .stat-card .label { 
            font-size: 0.85rem; 
            opacity: 0.85; 
        }
        .theme-toggle {
            background: rgba(255,255,255,0.15);
            border: none;
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .theme-toggle:hover { 
            background: rgba(255,255,255,0.3); 
        }
        .collapse-btn {
            background: rgba(255,255,255,0.15);
            border: none; 
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer; 
            font-size: 1.2rem;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .collapse-btn:hover { 
            background: rgba(255,255,255,0.3); 
        }
        .collapse-btn i {
            font-size: 1.2rem;
        }
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-main);
            cursor: pointer;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 200;
                width: 250px ;
            }
            .sidebar.open { 
                transform: translateX(0); 
            }
            .sidebar.collapsed {
                width: 80px ;
                transform: translateX(0);
            }
            .sidebar-overlay.open { 
                display: block; 
            }
            .main-content { 
                margin-left: 0 ; 
            }
            .hamburger { 
                display: block; 
            }
        }
        [data-theme="dark"] .table-light {
            --bs-table-bg: #2d3139;
            color: var(--text-main);
        }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #2d3139;
            border-color: #444;
            color: var(--text-main);
        }
        [data-theme="dark"] .input-group-text {
            background-color: #2d3139;
            border-color: #444;
            color: var(--text-main);
        }
        [data-theme="dark"] .table {
            --bs-table-bg: var(--bg-card);
            --bs-table-color: var(--text-main);
            --bs-table-striped-bg: #2d3139;
            --bs-table-hover-bg: #2d3139;
            --bs-table-border-color: #444;
            color: var(--text-main);
        }
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        [data-theme="dark"] .card,
        [data-theme="dark"] .modal-content,
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            background-color: var(--bg-card) !important;
            color: var(--text-main);
            border-color: #444 !important;
        }
        [data-theme="dark"] .bg-white,
        [data-theme="dark"] .bg-light {
            background-color: #2d3139 !important;
            color: var(--text-main) !important;
            border-color: #444 !important;
        }
        [data-theme="dark"] .alert-info,
        [data-theme="dark"] .alert-light {
            background-color: #1f3a4d !important;
            color: var(--text-main) !important;
            border-color: #2d6a9f !important;
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1);
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo"
             style="width:36px; height:36px; border-radius:8px; object-fit:cover; flex-shrink:0;">
        <span>Si Ouakli</span>
        <div class="d-flex gap-1 ms-auto">
            <button class="collapse-btn" id="collapseBtn" title="Réduire">
                <i class="bi bi-chevron-left" id="collapseIcon"></i>
            </button>
            <button class="theme-toggle" id="themeToggle" title="Mode jour/nuit">
                <i class="bi bi-moon-fill" id="themeIcon"></i>
            </button>
        </div>
    </div>
    <nav class="nav flex-column">
        @php
            $role = auth()->user()?->role;
        @endphp

        @if($role === 'admin')
            @include('admin.partials._sidebar')
        @elseif($role === 'etudiante')
            @include('etudiante.partials._sidebar')
        @elseif($role === 'resp_hebergement')
            @include('hebergement.partials._sidebar')
        @elseif($role === 'technicien')
            @include('technicien.partials._sidebar')
        @elseif($role === 'resp_foyer')
            @include('foyer.partials._sidebar')
        @endif
    </nav>
    <div style="position:absolute; bottom:20px; width:100%; padding:0 10px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link btn btn-link w-100 text-start">
                <i class="bi bi-box-arrow-left"></i> <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger" id="hamburger">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0">@yield('page-title')</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            @php
    $role = auth()->user()->role;
    $notifRoute = match($role) {
        'technicien' => 'technicien.notifications',
        'admin'            => 'admin.notifications',
        'resp_hebergement' => 'hebergement.notifications',
        'etudiante'        => 'etudiante.notifications',
        'resp_foyer'       => 'foyer.notifications',
        default            => null,
    };
    $nbNotifs = auth()->user()->unreadNotifications->count();
@endphp
@if($notifRoute)
<a href="{{ route($notifRoute) }}" class="btn btn-sm position-relative p-0 me-2">
    <i class="bi bi-bell-fill fs-5 text-warning"></i>
    @if($nbNotifs > 0)
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $nbNotifs }}
    </span>
    @endif
</a>
@endif
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                     alt="Photo" 
                     width="35" 
                     height="35" 
                     class="rounded-circle me-1" 
                     style="object-fit:cover; border: 1px solid #ccc;">
            @else
                <i class="bi bi-person-circle me-1 fs-4"></i>
            @endif
            <strong>{{ auth()->user()->name }}</strong>
            <span class="badge bg-secondary ms-1">{{ auth()->user()->role }}</span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const html = document.documentElement;
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    // ── Mode jour/nuit ──
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeToggle.addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateThemeIcon(next);
    });
    
    function updateThemeIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }

    // ── Collapse sidebar ──
    const collapseBtn = document.getElementById('collapseBtn');
    const collapseIcon = document.getElementById('collapseIcon');

    if (localStorage.getItem('sidebar') === 'collapsed') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
        collapseIcon.className = 'bi bi-chevron-right';
    }

    collapseBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        const collapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'open');
        collapseIcon.className = collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
    });

    // ── Mobile sidebar ──
    const hamburger = document.getElementById('hamburger');
    const overlay = document.getElementById('sidebarOverlay');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    });
    
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });
</script>
@yield('scripts')

</body>
</html>