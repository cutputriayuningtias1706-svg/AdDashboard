<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AdDashboard Pro')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family:'Inter',sans-serif; }
        
        /* ── Dark Mode Base ── */
        :root {
            --bg-page: #f5f7fb;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e8ecf0;
            --bg-hover: #f8fafc;
        }

        .dark {
            --bg-page: #0f172a;
            --bg-card: #1e293b;
            --bg-sidebar: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --bg-hover: #1e293b;
        }

        body { background: var(--bg-page); color: var(--text-main); transition: background 0.3s, color 0.3s; }

        /* ── Sidebar ── */
        .sidebar { 
            background: var(--bg-sidebar); border-right: 1px solid var(--border-color); 
            width: 80px; 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s, border-color 0.3s; 
            overflow: hidden;
            white-space: nowrap;
            z-index: 1000;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .sidebar:hover { width: 260px; }

        .nav-item {
            display:flex; align-items:center; 
            height: 46px;
            padding: 0;
            margin: 2px 10px;
            border-radius: 12px;
            font-size:.875rem; font-weight:500; color: var(--text-muted);
            transition:all .2s; cursor:pointer; position:relative;
            overflow: hidden;
        }
        .nav-item i { 
            width: 60px; 
            min-width: 60px;
            height: 46px;
            display: flex; 
            align-items: center;
            justify-content: center; 
            font-size: 1.15rem; 
            transition: color 0.2s;
        }
        .nav-item span { 
            opacity: 0; 
            transform: translateX(-10px);
            transition: opacity 0.2s, transform 0.2s; 
            pointer-events: none;
        }
        .sidebar:hover .nav-item span { 
            opacity: 1; 
            transform: translateX(0);
            pointer-events: auto;
        }

        .nav-item:hover { background: var(--bg-hover); color:#2563eb; }
        .nav-item.active {
            background: var(--bg-hover);
            color:#2563eb; font-weight: 600;
            border: 1px solid var(--border-color);
        }
        .nav-item.active i { color: #2563eb; }
        .nav-item.active::before { 
            content:''; position:absolute; left:0; top:25%;
            width:4px; height:50%; background:#2563eb; border-radius:0 4px 4px 0;
        }

        .logo-text, .section-label, .user-info-text { 
            opacity: 0; 
            transform: translateX(-10px);
            transition: opacity 0.2s, transform 0.2s; 
            pointer-events: none;
        }
        .sidebar:hover .logo-text, .sidebar:hover .section-label, .sidebar:hover .user-info-text { 
            opacity: 1; 
            transform: translateX(0);
            pointer-events: auto;
        }

        /* ── Cards ── */
        .card-hover { transition:transform .2s ease,box-shadow .2s ease; }
        .card-hover:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,.09); }

        /* ── Glow ── */
        .icon-glow-blue   { box-shadow:0 4px 16px rgba(59,130,246,.3); }
        .icon-glow-blue { box-shadow:0 4px 16px rgba(37,99,235,.3); }
        .icon-glow-green  { box-shadow:0 4px 16px rgba(16,185,129,.3); }
        .icon-glow-amber  { box-shadow:0 4px 16px rgba(245,158,11,.3); }

        /* ── Animations ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)} }
        @keyframes statusPulse { 0%,100%{opacity:1}50%{opacity:.5} }
        .fade-up   { animation:fadeUp .38s ease both; }
        .fade-up-2 { animation:fadeUp .38s .08s ease both; }
        .fade-up-3 { animation:fadeUp .38s .16s ease both; }
        .fade-up-4 { animation:fadeUp .38s .24s ease both; }
        .badge-active { animation:statusPulse 2.4s ease-in-out infinite; }

        /* ── Table ── */
        .table-row-hover { transition:background .12s; }
        .table-row-hover:hover { background:#f8fafc; }

        /* ── Shimmer Effect ── */
        .shimmer-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: var(--bg-page); z-index: 9999; display: flex; flex-direction: column;
            transition: opacity 0.4s ease, visibility 0.4s;
        }
        .shimmer-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .shimmer-bar {
            height: 4px; width: 100%;
            background: linear-gradient(90deg, var(--bg-page) 25%, var(--border-color) 50%, var(--bg-page) 75%);
            background-size: 200% 100%;
            animation: shimmer-loading 1.5s infinite;
        }
        
        @keyframes shimmer-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .shimmer-content {
            flex: 1; padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem;
        }
        .shimmer-item {
            height: 20px; background: var(--border-color); border-radius: 4px;
            background: linear-gradient(90deg, var(--border-color) 25%, var(--bg-hover) 50%, var(--border-color) 75%);
            background-size: 200% 100%;
            animation: shimmer-loading 1.5s infinite;
        }

        /* ── Table Freeze & Scroll ── */
        .table-container { width: 100%; overflow-x: auto; position: relative; border-radius: 12px; }
        .sticky-col { 
            position: sticky; left: 0; background: var(--bg-card); z-index: 20;
            min-width: 80px;
        }
        .sticky-col-2 {
            position: sticky; left: 80px; background: var(--bg-card); z-index: 20;
            min-width: 200px;
            box-shadow: 4px 0 8px rgba(0,0,0,0.05);
        }
        thead th.sticky-col, thead th.sticky-col-2 { background: var(--bg-hover); z-index: 30; }
        tr:hover .sticky-col, tr:hover .sticky-col-2 { background: var(--bg-hover); }
        .dark thead th { border-color: var(--border-color); }
        
        /* ── Ad Asset Styles ── */
        .ad-asset-thumb {
            width: 48px; height: 64px; border-radius: 6px; 
            object-fit: cover; cursor: pointer; transition: transform 0.2s;
            background: #f1f5f9; border: 1px solid #e2e8f0;
        }
        .ad-asset-thumb:hover { transform: scale(1.05); }

        /* ── Video Modal ── */
        .modal-video-mobile {
            width: 320px; height: 568px; background: #000; border-radius: 32px;
            border: 8px solid #333; position: relative; overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6); 
            z-index: 100; display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width:4px; height:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

        /* ── Custom Pagination ── */
        .pagination-container nav div:first-child { display: none; } /* Hide mobile pagination summary if default */
        .pagination-container nav div:last-child { width: 100%; display: flex; justify-content: center; }
        
        .pagination-container a, .pagination-container span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 12px;
            margin: 0 4px; border-radius: 10px; font-size: 0.875rem; font-weight: 600;
            transition: all 0.2s; border: 1px solid #e2e8f0; background: #fff; color: #64748b;
        }
        .pagination-container a:hover {
            border-color: #3b82f6; color: #2563eb; background: #eff6ff;
            transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.1);
        }
        .pagination-container .active-page {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff; border-color: transparent;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }

        /* ── Pencarian Input ── */
        .deep-search-container {
            position: relative; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;
            display: flex; items-center: center; overflow: hidden;
        }
        .deep-search-container:focus-within {
            background: #fff; border-color: #3b82f6; 
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1), 0 8px 20px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }
        .deep-search-input {
            background: transparent; border: none; outline: none;
            padding: 10px 16px; width: 100%; font-size: 0.875rem;
            color: #1e293b;
        }
        .deep-search-input::placeholder { color: #94a3b8; font-weight: 500; }
        .deep-search-icon {
            position: absolute; left: 14px; color: #94a3b8; font-size: 0.875rem;
            transition: color 0.2s;
        }
        .deep-search-container:focus-within .deep-search-icon { color: #2563eb; }
        /* ── Metric Card Interactions ── */
        .metric-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .metric-card:hover {
            transform: translateY(-4px);
            background: #fff !important;
        }
        .metric-card-blue:hover { box-shadow: 0 10px 25px -5px rgba(59,130,246,0.15), 0 8px 10px -6px rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.3) !important; }
        .metric-card-emerald:hover { box-shadow: 0 10px 25px -5px rgba(16,185,129,0.15), 0 8px 10px -6px rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3) !important; }
        .metric-card-amber:hover { box-shadow: 0 10px 25px -5px rgba(245,158,11,0.15), 0 8px 10px -6px rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3) !important; }
        .metric-card-pink:hover { box-shadow: 0 10px 25px -5px rgba(236,72,153,0.15), 0 8px 10px -6px rgba(236,72,153,0.1); border-color: rgba(236,72,153,0.3) !important; }
        .metric-card-purple:hover { box-shadow: 0 10px 25px -5px rgba(139,92,246,0.15), 0 8px 10px -6px rgba(139,92,246,0.1); border-color: rgba(139,92,246,0.3) !important; }
        .active-metric-tab {
            border-color: #3b82f6 !important;
            background: #eff6ff !important;
            box-shadow: 0 8px 20px -6px rgba(59,130,246,0.15) !important;
            transform: translateY(-2px);
        }
        .dark .active-metric-tab {
            background: rgba(59,130,246,0.1) !important;
            border-color: #3b82f6 !important;
        }
        .active-metric-tab .metric-icon-container {
            transform: scale(1.1);
        }

        /* ── Helper Classes ── */
        .bg-primary-card { background: var(--bg-card) !important; border: 1px solid var(--border-color) !important; }
        .text-main { color: var(--text-main) !important; }
        .text-muted { color: var(--text-muted) !important; }

        /* ── Dark Mode Toggle Button ── */
        .dark-toggle {
            position: fixed; bottom: 24px; right: 24px;
            width: 50px; height: 50px; border-radius: 25px;
            background: #2563eb; color: #fff;
            display: flex; items-center: center; justify-content: center;
            cursor: pointer; z-index: 2000;
            box-shadow: 0 4px 15px rgba(37,99,235,0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255,255,255,0.1);
        }
        .dark-toggle:hover { transform: scale(1.1) rotate(15deg); box-shadow: 0 6px 20px rgba(37,99,235,0.5); }
        .dark .dark-toggle { background: #f59e0b; box-shadow: 0 4px 15px rgba(245,158,11,0.4); }

        /* ── Override Tailwind for Dark Mode (Class based) ── */
        .dark body { background: #0f172a; }
        .dark .bg-white { background-color: #1e293b !important; }
        .dark .bg-slate-50 { background-color: #0f172a !important; }
        .dark .bg-slate-100 { background-color: #334155 !important; }
        .dark .bg-slate-200 { background-color: #1e293b !important; }
        .dark .bg-slate-900 { background-color: #f1f5f9 !important; color: #0f172a !important; }
        
        .dark .text-slate-900 { color: #f8fafc !important; }
        .dark .text-slate-800 { color: #f1f5f9 !important; }
        .dark .text-slate-700 { color: #e2e8f0 !important; }
        .dark .text-slate-600 { color: #cbd5e1 !important; }
        .dark .text-slate-500 { color: #94a3b8 !important; }
        .dark .text-slate-400 { color: #64748b !important; }
        
        .dark .border-slate-50 { border-color: #1e293b !important; }
        .dark .border-slate-100 { border-color: #334155 !important; }
        .dark .border-slate-200 { border-color: #334155 !important; }
        
        .dark .shadow-sm { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.2) !important; }
        .dark .shadow-md, .dark .shadow-lg, .dark .shadow-xl { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3) !important; }
        
        .dark input, .dark select, .dark textarea { 
            background-color: #0f172a !important; 
            border-color: #334155 !important; 
            color: #f1f5f9 !important; 
        }
        .dark input::placeholder { color: #475569 !important; }
        
        .dark .modal-overlay { background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); }
        .dark .modal-video-mobile { border-color: #475569; }
        
        .dark .pagination-container a, .dark .pagination-container span {
            background-color: #1e293b; border-color: #334155; color: #94a3b8;
        }
        .dark .pagination-container a:hover { background-color: #334155; color: #fff; }

        .dark .bg-gradient-to-r.from-slate-50 { 
            background-image: linear-gradient(to right, #1e293b, #0f172a, #1e293b) !important;
            border-color: #334155 !important;
        }

        .dark .bg-primary-card:hover {
            background: #232f4a !important;
            border-color: #3b82f6 !important;
        }
    </style>
</head>
<body class="transition-colors duration-300">
    <script>
        // Apply dark mode immediately to prevent flash
        if (localStorage.getItem('darkMode') === 'enabled' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <!-- Shimmer Loader Overlay -->
    <div id="page-shimmer" class="shimmer-overlay">
        <div class="shimmer-bar"></div>
        <div class="shimmer-content">
            @if(View::hasSection('shimmer-content'))
                @yield('shimmer-content')
            @else
                <!-- Default Shimmer (Dashboard Style) -->
                <div class="shimmer-item w-1/4 mb-4"></div>
                <div class="grid grid-cols-4 gap-4 mb-8">
                    <div class="shimmer-item h-32"></div>
                    <div class="shimmer-item h-32"></div>
                    <div class="shimmer-item h-32"></div>
                    <div class="shimmer-item h-32"></div>
                </div>
                <div class="shimmer-item w-full h-64"></div>
            @endif
        </div>
    </div>
<div class="flex h-screen overflow-hidden">

    <!-- ══════════ Sidebar ══════════ -->
    <aside class="sidebar flex-shrink-0 flex flex-col">

        <!-- Logo -->
        <div class="h-16 flex items-center px-4 border-b border-slate-100 flex-shrink-0 overflow-hidden">
            <div class="w-11 flex justify-center flex-shrink-0">
                <img src="{{ asset('images/addashboard-pro-icon.png') }}" alt="AdDashboard Pro Icon" class="h-8 w-auto">
            </div>
            <div class="logo-text ml-3">
                <span class="font-bold text-slate-800 text-sm tracking-tight whitespace-nowrap">AdDashboard <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded text-[10px] align-middle border border-blue-100/50">Pro</span></span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-0 py-4 space-y-0.5 overflow-hidden">

            <a href="{{ route('dashboard.index') }}"
               class="nav-item {{ request()->routeIs('dashboard.index') || request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <div class="px-7 pt-5 pb-2 overflow-hidden">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 section-label">Platforms</p>
            </div>

            <a href="{{ route('dashboard.google', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.google') ? 'active' : '' }}">
                <i class="fa-brands fa-google"></i>
                <span>Google Ads</span>
            </a>

            <a href="{{ route('dashboard.meta', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.meta') ? 'active' : '' }}">
                <i class="fa-brands fa-facebook"></i>
                <span>Meta Ads</span>
            </a>

            <a href="{{ route('dashboard.tiktok', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.tiktok') ? 'active' : '' }}">
                <i class="fa-brands fa-tiktok"></i>
                <span>TikTok Ads</span>
            </a>

            <div class="px-5 pt-5 pb-2 overflow-hidden">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 section-label">Management</p>
            </div>

            <a href="{{ route('reports.index') }}"
               class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Reports</span>
            </a>

            <a href="{{ route('campaigns.create') }}"
               class="nav-item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Pasang Iklan</span>
            </a>

            <a href="{{ route('contracts.index') }}"
               class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Kontrak Kerjasama</span>
            </a>

            <a href="{{ route('invoices.index') }}"
               class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Invoices</span>
            </a>

            <a href="{{ route('topup.index') }}"
               class="nav-item {{ request()->routeIs('topup.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i>
                <span>Top-up</span>
            </a>

            <div class="px-7 pt-5 pb-2 overflow-hidden">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 section-label">Personalization</p>
            </div>

            <a href="{{ route('settings.profile') }}"
               class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- User + Logout -->
        <div class="px-2 py-4 border-t border-slate-100 flex-shrink-0 overflow-hidden">
            @php $authUser = session('auth_user'); @endphp
            <div class="flex items-center gap-3 p-2 rounded-xl bg-primary-card">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background: #334155;">
                    {{ strtoupper(substr($authUser['name'] ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 user-info-text">
                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $authUser['name'] ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400">{{ $authUser['role'] ?? 'Admin' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="user-info-text">
                    @csrf
                    <button type="submit" title="Logout"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ══════════ Main Content ══════════ -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Header -->
        <header class="h-16 flex items-center justify-between px-8 flex-shrink-0 bg-transparent">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('page-title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs text-slate-500 font-bold bg-white border border-slate-200 shadow-sm">
                    <i class="fa-regular fa-calendar text-blue-500"></i>
                    {{ \Carbon\Carbon::now()->format('D, d M Y') }}
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-5">
            @yield('content')
        </main>
    </div>
</div>

    <!-- Ad Preview Modal -->
    <div id="ad-preview-modal" class="modal-overlay" onclick="closeAdModal(event)">
        <div class="relative flex flex-col items-center">
            <button onclick="closeAdModal(null)" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
            <div class="modal-video-mobile" onclick="event.stopPropagation()" style="display:flex; align-items:center; justify-content:center; background:#000;">
                <img id="ad-video-frame" 
                     style="width: 100%; height: 100%; object-fit: contain;" 
                     src="" 
                     alt="Ad Creative">
            </div>
            <div class="mt-6 bg-white rounded-2xl p-6 w-full max-w-[320px] shadow-2xl" onclick="event.stopPropagation()">
                <h3 id="modal-campaign-name" class="font-bold text-gray-900 truncate">Campaign Name</h3>
                <p id="modal-campaign-platform" class="text-xs text-blue-600 font-semibold uppercase mt-1">Platform</p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase">Spend</p>
                        <p id="modal-spend" class="text-sm font-bold text-gray-800">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase">Conv.</p>
                        <p id="modal-conv" class="text-sm font-bold text-gray-800">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAdModal(videoId, name, platform, spend, conv) {
            const modal = document.getElementById('ad-preview-modal');
            const frame = document.getElementById('ad-video-frame');
            
            // Set data
            document.getElementById('modal-campaign-name').innerText = name;
            document.getElementById('modal-campaign-platform').innerText = platform;
            document.getElementById('modal-spend').innerText = spend;
            document.getElementById('modal-conv').innerText = conv;
            
            // Set Google Drive thumbnail image (larger size for modal)
            frame.src = `https://drive.google.com/thumbnail?id=${videoId}&sz=w800`;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeAdModal(e) {
            if (e && e.target !== document.getElementById('ad-preview-modal') && e !== null) return;
            
            const modal = document.getElementById('ad-preview-modal');
            const frame = document.getElementById('ad-video-frame');
            
            frame.src = "";
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Hide shimmer when page is fully loaded
        window.addEventListener('load', function() {
            const shimmer = document.getElementById('page-shimmer');
            if (shimmer) {
                setTimeout(() => {
                    shimmer.classList.add('hidden');
                }, 300);
            }
        });

        // Show shimmer when clicking links
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = this.getAttribute('target');
                
                if (href && 
                    !href.startsWith('#') && 
                    !href.includes('javascript:void(0)') &&
                    !href.includes('download') && 
                    !href.includes('logout') &&
                    target !== '_blank' &&
                    !e.metaKey && !e.ctrlKey) {
                    
                    const shimmer = document.getElementById('page-shimmer');
                    if (shimmer) {
                        shimmer.classList.remove('hidden');
                    }
                }
            });
        });

        // Chart.js Global Defaults for Dark Mode
        function updateChartDefaults() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            if (window.Chart) {
                Chart.defaults.color = textColor;
                
                Object.values(Chart.instances).forEach(chart => {
                    if (chart.options.scales) {
                        if (chart.options.scales.x && chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = textColor;
                        if (chart.options.scales.y && chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = textColor;
                    }
                    chart.update();
                });
            }
        }

        // Dark Mode Toggle Logic
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
            
            // Update icon
            const icon = document.querySelector('.dark-toggle i');
            if (isDark) {
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
            }

            // Update charts
            updateChartDefaults();
        }

        // Initialize toggle icon and charts
        document.addEventListener('DOMContentLoaded', () => {
            const icon = document.querySelector('.dark-toggle i');
            if (document.documentElement.classList.contains('dark')) {
                icon.classList.replace('fa-moon', 'fa-sun');
            }
            updateChartDefaults();
        });
    </script>
    @stack('scripts')
    
    <!-- Dark Mode Toggle Button -->
    <div class="dark-toggle shadow-lg" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="fa-solid fa-moon text-xl"></i>
    </div>
</body>
</html>
