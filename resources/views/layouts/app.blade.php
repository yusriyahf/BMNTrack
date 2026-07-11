<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BMNTrack') – Sistem Inventaris BMN Kampus</title>
    <meta name="description" content="BMNTrack – Aplikasi inventaris Barang Milik Negara (BMN) untuk pengelolaan aset kampus secara modern dan efisien.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary:        #1a4fba;
            --primary-dark:   #0f3591;
            --primary-light:  #2563eb;
            --primary-xlight: #dbeafe;
            --primary-ultra:  #eff6ff;
            --accent:         #f59e0b;
            --accent-dark:    #d97706;
            --success:        #10b981;
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
            --warning:        #f59e0b;
            --warning-light:  #fef3c7;
            --sidebar-w:      260px;
            --sidebar-bg:     #0c2461;
            --sidebar-hover:  #1a4fba;
            --topbar-h:       64px;
            --text-dark:      #0f172a;
            --text-mid:       #475569;
            --text-light:     #94a3b8;
            --bg-body:        #f0f4f8;
            --bg-card:        #ffffff;
            --border:         #e2e8f0;
            --radius:         12px;
            --radius-sm:      8px;
            --shadow:         0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
            --shadow-md:      0 4px 24px rgba(26,79,186,.12);
            --transition:     all .2s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── SIDEBAR ─────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 2px; }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,.4);
            flex-shrink: 0;
        }
        .sidebar-brand-text h2 {
            font-size: 17px; font-weight: 700; color: #fff;
            line-height: 1;
        }
        .sidebar-brand-text p {
            font-size: 10px; color: rgba(255,255,255,.5);
            margin-top: 3px; line-height: 1;
        }

        .sidebar-user {
            margin: 16px 12px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius-sm);
            padding: 12px;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #f97316);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-info { overflow: hidden; }
        .sidebar-user-info strong {
            display: block; font-size: 12px; color: #fff;
            font-weight: 600; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user-info span {
            font-size: 10px; color: rgba(255,255,255,.5);
        }

        .sidebar-nav { padding: 8px 12px; flex: 1; }

        .nav-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.2px;
            color: rgba(255,255,255,.3); text-transform: uppercase;
            padding: 16px 8px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }
        .nav-item.active {
            background: var(--primary-light);
            color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,.4);
        }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer form button {
            width: 100%;
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.2);
            border-radius: var(--radius-sm);
            color: #fca5a5;
            font-size: 13px; font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }
        .sidebar-footer form button:hover {
            background: rgba(239,68,68,.25);
            color: #fee2e2;
        }

        /* ─── TOPBAR ───────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 24px;
            z-index: 900;
            gap: 16px;
            box-shadow: 0 1px 8px rgba(0,0,0,.05);
        }
        .topbar-toggle {
            display: none;
            background: none; border: none;
            font-size: 20px; color: var(--text-mid);
            cursor: pointer; padding: 4px;
        }
        .topbar-title {
            flex: 1;
            font-size: 18px; font-weight: 700;
            color: var(--text-dark);
        }
        .topbar-title span {
            font-size: 12px; font-weight: 400;
            color: var(--text-light); display: block;
        }
        .topbar-badge {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 14px;
            background: var(--primary-ultra);
            border: 1px solid var(--primary-xlight);
            border-radius: 20px;
            font-size: 12px; font-weight: 600;
            color: var(--primary);
        }

        /* ─── MAIN ─────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }
        .main-content {
            padding: 28px 28px;
        }

        /* ─── SIDEBAR OVERLAY (mobile) ──────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 999;
        }

        /* ─── ALERTS ───────────────────────────────────── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 13.5px; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown {
            from { opacity:0; transform: translateY(-8px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger  { background: var(--danger-light); color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: var(--warning-light); color: #92400e; border: 1px solid #fcd34d; }

        /* ─── CARDS ────────────────────────────────────── */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }
        .card-header h5 {
            font-size: 15px; font-weight: 700;
            color: var(--text-dark);
            display: flex; align-items: center; gap: 8px;
        }
        .card-body { padding: 24px; }

        /* ─── BUTTONS ──────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            border: none; transition: var(--transition);
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--primary-light);
            color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,.3);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 16px rgba(37,99,235,.4);
            transform: translateY(-1px);
        }
        .btn-success {
            background: var(--success);
            color: #fff;
            box-shadow: 0 2px 8px rgba(16,185,129,.3);
        }
        .btn-success:hover { background: #059669; transform: translateY(-1px); }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning {
            background: var(--accent);
            color: #fff;
        }
        .btn-warning:hover { background: var(--accent-dark); }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text-mid);
        }
        .btn-outline:hover {
            border-color: var(--primary-light);
            color: var(--primary-light);
            background: var(--primary-ultra);
        }
        .btn-sm { padding: 6px 13px; font-size: 12px; }
        .btn-icon { padding: 8px; border-radius: var(--radius-sm); }

        /* ─── FORM ─────────────────────────────────────── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .form-label .required { color: var(--danger); margin-left: 2px; }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            color: var(--text-dark);
            background: #fff;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        .form-error {
            font-size: 12px; color: var(--danger);
            margin-top: 4px; display: flex; align-items: center; gap: 4px;
        }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* ─── TABLE ────────────────────────────────────── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr {
            background: var(--primary-ultra);
            border-bottom: 2px solid var(--primary-xlight);
        }
        th {
            padding: 12px 16px;
            font-size: 12px; font-weight: 700;
            color: var(--primary);
            text-transform: uppercase; letter-spacing: .5px;
            text-align: left; white-space: nowrap;
        }
        td {
            padding: 13px 16px;
            font-size: 13.5px;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr { transition: background .15s; }
        tbody tr:hover { background: #fafbff; }

        /* ─── BADGES ───────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px; font-weight: 700;
            gap: 4px;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: var(--danger-light); color: #991b1b; }
        .badge-primary { background: var(--primary-xlight); color: var(--primary-dark); }
        .badge-warning { background: var(--warning-light); color: #92400e; }
        .badge-gray    { background: #f1f5f9; color: #64748b; }

        /* ─── PAGINATION ───────────────────────────────── */
        .pagination-wrapper {
            display: flex; justify-content: flex-end; padding: 16px 24px;
            border-top: 1px solid var(--border);
        }
        .pagination-wrapper nav { display: flex; gap: 4px; }
        .pagination-wrapper nav .page-link {
            padding: 6px 12px; border-radius: 6px;
            font-size: 13px; font-weight: 500;
            color: var(--text-mid);
            border: 1px solid var(--border);
            text-decoration: none;
            transition: var(--transition);
        }
        .pagination-wrapper nav .page-link:hover {
            background: var(--primary-ultra);
            color: var(--primary);
            border-color: var(--primary-xlight);
        }
        .pagination-wrapper nav .page-link.active {
            background: var(--primary-light);
            color: #fff; border-color: var(--primary-light);
        }

        /* ─── PAGE HEADER ──────────────────────────────── */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: 22px; font-weight: 800;
            color: var(--text-dark);
        }
        .page-header .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12.5px; color: var(--text-light);
            margin-top: 4px;
        }
        .page-header .breadcrumb a { color: var(--primary); text-decoration: none; }
        .page-header .breadcrumb a:hover { text-decoration: underline; }

        /* ─── RESPONSIVE ───────────────────────────────── */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.visible { display: block; }
            .main-wrapper { margin-left: 0; }
            .topbar { left: 0; }
            .topbar-toggle { display: block; }
            .main-content { padding: 20px 16px; }
        }
        @media (max-width: 640px) {
            .main-content { padding: 16px 12px; }
            .card-body { padding: 16px; }
            .card-header { padding: 14px 16px; }
        }

        /* ─── MISC ─────────────────────────────────────── */
        .text-muted { color: var(--text-light); }
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .fw-bold { font-weight: 700; }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .flex-wrap { flex-wrap: wrap; }
        .mt-3 { margin-top: 12px; }
        .mb-0 { margin-bottom: 0; }
        .img-thumbnail {
            width: 54px; height: 54px;
            object-fit: cover; border-radius: 8px;
            border: 2px solid var(--border);
        }
        .img-placeholder {
            width: 54px; height: 54px;
            background: var(--primary-ultra);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 18px;
            border: 2px solid var(--primary-xlight);
        }
        .empty-state {
            text-align: center; padding: 60px 20px;
            color: var(--text-light);
        }
        .empty-state i { font-size: 52px; opacity: .3; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="sidebar-brand-text">
            <h2>BMNTrack</h2>
            <p>Inventaris Aset Kampus</p>
        </div>
    </div>

    @auth
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
        <div class="sidebar-user-info">
            <strong>{{ auth()->user()->nama }}</strong>
            <span>{{ ucfirst(auth()->user()->role) }}</span>
        </div>
    </div>
    @endauth

    <nav class="sidebar-nav">
        <div class="nav-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-label">Manajemen Aset</div>
        <a href="{{ route('gedung.index') }}" class="nav-item {{ request()->routeIs('gedung.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i> Data Gedung
        </a>
        <a href="{{ route('ruangan.index') }}" class="nav-item {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
            <i class="fas fa-door-open"></i> Data Ruangan
        </a>
        <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> Data Barang
        </a>
    </nav>

    <div class="sidebar-footer">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i class="fas fa-right-from-bracket"></i> Keluar
            </button>
        </form>
        @endauth
    </div>
</aside>

<!-- Topbar -->
<header class="topbar">
    <button class="topbar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">
        @yield('page-title', 'Dashboard')
        <span>@yield('page-subtitle', '')</span>
    </div>
    <div class="topbar-badge">
        <i class="fas fa-calendar-day"></i>
        {{ now()->locale('id')->translatedFormat('d M Y') }}
    </div>
</header>

<!-- Main -->
<main class="main-wrapper">
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
    const toggle  = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('visible');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
    }
    toggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);

    // Auto-close alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.style.transition = 'opacity .4s';
            a.style.opacity = '0';
            setTimeout(() => a.remove(), 400);
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
