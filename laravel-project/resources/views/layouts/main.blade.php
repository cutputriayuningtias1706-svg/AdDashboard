<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ad Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family:'Inter',sans-serif; }

        /* ── Sidebar ── */
        .sidebar { background:#fff; border-right:1px solid #e8ecf0; }
        .nav-item {
            display:flex; align-items:center; gap:10px;
            padding:9px 12px; border-radius:10px;
            font-size:.875rem; font-weight:500; color:#64748b;
            transition:all .18s; cursor:pointer; position:relative;
        }
        .nav-item:hover { background:#f1f5f9; color:#334155; }
        .nav-item.active {
            background:linear-gradient(135deg,#eef2ff,#f5f3ff);
            color:#4f46e5; font-weight:600;
        }
        .nav-item.active::before {
            content:''; position:absolute; left:0; top:50%;
            transform:translateY(-50%);
            width:3px; height:60%; background:#6366f1; border-radius:0 3px 3px 0;
        }

        /* ── Cards ── */
        .card-hover { transition:transform .2s ease,box-shadow .2s ease; }
        .card-hover:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,.09); }

        /* ── Glow ── */
        .icon-glow-blue   { box-shadow:0 4px 16px rgba(59,130,246,.3); }
        .icon-glow-purple { box-shadow:0 4px 16px rgba(99,102,241,.3); }
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
            background: #fff; z-index: 9999; display: flex; flex-direction: column;
            transition: opacity 0.4s ease, visibility 0.4s;
        }
        .shimmer-overlay.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .shimmer-bar {
            height: 4px; width: 100%;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
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
            height: 20px; background: #f0f0f0; border-radius: 4px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer-loading 1.5s infinite;
        }

        /* ── Table Freeze & Scroll ── */
        .table-container { width: 100%; overflow-x: auto; position: relative; border-radius: 12px; }
        .sticky-col { 
            position: sticky; left: 0; background: #fff; z-index: 20;
            min-width: 80px;
        }
        .sticky-col-2 {
            position: sticky; left: 80px; background: #fff; z-index: 20;
            min-width: 200px;
            box-shadow: 4px 0 8px rgba(0,0,0,0.05);
        }
        thead th.sticky-col, thead th.sticky-col-2 { background: #f8fafc; z-index: 30; }
        tr:hover .sticky-col, tr:hover .sticky-col-2 { background: #f1f5f9; }
        
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
    </style>
</head>
<body style="background:#f5f7fb; min-height:100vh;">
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
    <aside class="sidebar w-60 flex-shrink-0 flex flex-col h-screen overflow-y-auto">

        <!-- Logo -->
        <div class="h-16 flex items-center px-5 border-b border-slate-100 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="fa-solid fa-chart-line text-white text-xs"></i>
            </div>
            <div>
                <span class="font-bold text-slate-800 text-sm">Ad Dashboard</span>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">Advertising Platform</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-0.5">

            <a href="{{ route('dashboard.index') }}"
               class="nav-item {{ request()->routeIs('dashboard.index') || request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house w-4 text-center text-sm"></i>
                <span>Dashboard</span>
            </a>

            <div class="px-3 pt-5 pb-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Platforms</p>
            </div>

            <a href="{{ route('dashboard.google', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.google') ? 'active' : '' }}">
                <i class="fa-brands fa-google w-4 text-center text-sm"></i>
                <span>Google Ads</span>
            </a>

            <a href="{{ route('dashboard.meta', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.meta') ? 'active' : '' }}">
                <i class="fa-brands fa-facebook w-4 text-center text-sm"></i>
                <span>Meta Ads</span>
            </a>

            <a href="{{ route('dashboard.tiktok', ['month' => request()->get('month')]) }}"
               class="nav-item {{ request()->routeIs('dashboard.tiktok') ? 'active' : '' }}">
                <i class="fa-brands fa-tiktok w-4 text-center text-sm"></i>
                <span>TikTok Ads</span>
            </a>

            <div class="px-3 pt-5 pb-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Management</p>
            </div>

            <a href="{{ route('reports.index') }}"
               class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar w-4 text-center text-sm"></i>
                <span>Reports</span>
            </a>

            <a href="{{ route('invoices.index') }}"
               class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice w-4 text-center text-sm"></i>
                <span>Invoices</span>
            </a>

            <a href="{{ route('topup.index') }}"
               class="nav-item {{ request()->routeIs('topup.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet w-4 text-center text-sm"></i>
                <span>Top-up</span>
            </a>

            <div class="px-3 pt-5 pb-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Personalization</p>
            </div>

            <a href="{{ route('settings.profile') }}"
               class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear w-4 text-center text-sm"></i>
                <span>Settings</span>
            </a>
        </nav>

        <!-- User + Logout -->
        <div class="px-3 py-4 border-t border-slate-100 flex-shrink-0">
            @php $authUser = session('auth_user'); @endphp
            <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#f8fafc;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    {{ strtoupper(substr($authUser['name'] ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $authUser['name'] ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-400">{{ $authUser['role'] ?? 'Admin' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
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
        <header class="h-14 flex items-center justify-between px-6 flex-shrink-0"
                style="background:#fff; border-bottom:1px solid #e8ecf0; box-shadow:0 1px 4px rgba(0,0,0,.04);">
            <div>
                <h1 class="text-base font-bold text-slate-800">@yield('page-title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs text-slate-500 font-medium"
                     style="background:#f1f5f9; border:1px solid #e2e8f0;">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
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
                <p id="modal-campaign-platform" class="text-xs text-indigo-600 font-semibold uppercase mt-1">Platform</p>
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
    </script>
    @stack('scripts')
</body>
</html>
