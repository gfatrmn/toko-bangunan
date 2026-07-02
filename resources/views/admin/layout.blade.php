<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - BuildNest')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f5;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand h3 {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            color: #fff;
            margin: 0;
        }
        .sidebar-brand small {
            color: #94a3b8;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .sidebar-menu {
            padding: 16px 12px;
        }
        .sidebar-menu .menu-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            padding: 12px 12px 6px;
            font-weight: 600;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.25s;
            margin-bottom: 2px;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 4px 15px rgba(37,99,235,0.3);
        }
        .sidebar-menu a i { font-size: 1.2rem; width: 22px; text-align: center; }

        /* ─── Main Content ─── */
        .main-content {
            margin-left: 270px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .topbar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar-left h5 {
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .admin-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }
        .page-content {
            padding: 28px;
        }

        /* ─── Cards ─── */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0;
            flex-shrink: 0;
        }
        .stat-card .stat-info { flex: 1; }
        .stat-card h3 { font-size: 1.3rem; font-weight: 800; margin-bottom: 0; line-height: 1.2; display: flex; align-items: baseline; gap: 4px; }
        .stat-card h3 .currency { font-size: 0.75rem; font-weight: 600; color: #64748b; }
        .stat-card p { color: #64748b; font-size: 0.75rem; font-weight: 500; margin: 0; line-height: 1.3; }

        /* ─── Tables ─── */
        .card-dashboard {
            background: #fff;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .card-dashboard .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-dashboard .card-body { padding: 0; }
        .card-dashboard table {
            margin: 0;
            font-size: 0.85rem;
        }
        .card-dashboard table th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 20px;
        }
        .card-dashboard table td {
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .card-dashboard table tr:last-child td { border-bottom: none; }

        .badge-status {
            padding: 4px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-lunas { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        /* ─── Toggle button (mobile) ─── */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1e293b;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .page-content { padding: 16px; }
            .stat-card { padding: 10px 14px; gap: 10px; }
            .stat-icon { width: 36px; height: 36px; font-size: 1rem; }
            .stat-card h3 { font-size: 1.1rem; }
            .stat-card p { font-size: 0.7rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Overlay for mobile --}}
    <div id="sidebarOverlay" onclick="toggleSidebar()" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:999;"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h3>🏗️ BuildNest</h3>
            <small>Admin Panel</small>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="{{ route('admin.konfirmasi') }}" class="{{ request()->routeIs('admin.konfirmasi') ? 'active' : '' }}">
                <i class="bi bi-check-circle-fill"></i> Konfirmasi
            </a>
            <a href="#">
                <i class="bi bi-box-seam-fill"></i> Produk
            </a>
            <a href="#">
                <i class="bi bi-people-fill"></i> Pengguna
            </a>
            <a href="#">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>
            <div class="menu-label" style="margin-top:12px;">Akun</div>
            <a href="{{ route('home') }}">
                <i class="bi bi-house-fill"></i> Ke Website
            </a>
            <a href="{{ route('logout') }}" style="color:#f87171;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5>@yield('page_title', 'Dashboard')</h5>
            </div>
            <div class="topbar-right">
                <span style="font-weight:600;font-size:0.9rem;color:#475569;">
                    <i class="bi bi-person-circle me-1"></i> {{ session('nama') ?? 'Admin' }}
                </span>
                <div class="admin-avatar">
                    {{ strtoupper(substr(session('nama') ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }
    </script>
    @stack('scripts')
</body>
</html>
