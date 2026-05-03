<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – AdDashboard Pro</title>
    <meta name="description" content="Platform manajemen iklan terpadu untuk Google, Meta, dan TikTok Ads.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Animations */
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0}to{opacity:1} }
        @keyframes float { 0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)} }
        @keyframes pulse-glow { 0%,100%{box-shadow:0 0 20px rgba(37,99,235,.15)}50%{box-shadow:0 0 40px rgba(37,99,235,.3)} }
        @keyframes slide-in-left { from{opacity:0;transform:translateX(-30px)}to{opacity:1;transform:translateX(0)} }
        @keyframes gradient-shift { 0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%} }

        .fade-up { animation: fadeUp .6s ease both; }
        .fade-up-2 { animation: fadeUp .6s .12s ease both; }
        .fade-up-3 { animation: fadeUp .6s .24s ease both; }
        .fade-up-4 { animation: fadeUp .6s .36s ease both; }
        .fade-in { animation: fadeIn .8s ease both; }
        .float-art { animation: float 5s ease-in-out infinite; }
        .slide-left { animation: slide-in-left .7s .2s ease both; }

        /* Left panel animated gradient */
        .left-panel {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 30%, #3b82f6 65%, #60a5fa 100%);
            background-size: 200% 200%;
            animation: gradient-shift 12s ease infinite;
        }

        /* Form styles */
        .input-field {
            width:100%; padding:14px 16px 14px 46px;
            border:1.5px solid #e2e8f0; border-radius:14px;
            font-size:14px; color:#1e293b; background:#fff;
            transition:all .25s; outline:none;
        }
        .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 4px rgba(59,130,246,.1); background:#f8fafc; }
        .input-field::placeholder { color:#94a3b8; }

        .btn-login {
            width:100%; padding:14px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:#fff; font-size:15px; font-weight:600; border-radius:14px;
            border:none; cursor:pointer; transition:all .25s;
            box-shadow:0 4px 20px rgba(37,99,235,.35);
            position:relative; overflow:hidden;
        }
        .btn-login::before {
            content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
            transition:left .5s;
        }
        .btn-login:hover::before { left:100%; }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 8px 30px rgba(37,99,235,.45); }
        .btn-login:active { transform:translateY(0); }

        .credential-chip {
            display:inline-flex; align-items:center; gap:6px;
            padding:8px 14px; border-radius:10px; font-size:12px;
            background:#f0f7ff; border:1px solid #dbeafe; color:#2563eb;
            cursor:pointer; transition:all .2s;
        }
        .credential-chip:hover { background:#e0f2fe; border-color:#bae6fd; transform:translateY(-1px); }

        /* Glass card */
        .glass-card {
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 20px;
        }

        /* Trust badge */
        .trust-badge {
            display:flex; align-items:center; gap:8px;
            padding:10px 16px; border-radius:14px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            transition:all .3s;
        }
        .trust-badge:hover { background:rgba(255,255,255,.12); transform:translateY(-2px); }

        /* Dot pattern background */
        .dot-bg {
            background-color:#fafbff;
            background-image: radial-gradient(#e0e7ff 0.8px, transparent 0.8px);
            background-size: 24px 24px;
        }

        /* Logo shine effect */
        .logo-shine {
            position:relative; overflow:hidden;
        }
        .logo-shine::after {
            content:''; position:absolute; top:-50%; left:-50%; width:200%; height:200%;
            background:linear-gradient(transparent,rgba(255,255,255,.05),transparent);
            transform:rotate(30deg);
            animation:pulse-glow 4s ease-in-out infinite;
        }

        /* Dark Mode Overrides */
        .dark body { background-color: #0f172a !important; color: #f1f5f9; }
        .dark .dot-bg { background-image: radial-gradient(#1e293b 0.8px, transparent 0.8px) !important; }
        .dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
        .dark .text-slate-800 { color: #f1f5f9 !important; }
        .dark .text-slate-600 { color: #e2e8f0 !important; }
        .dark .text-slate-400 { color: #94a3b8 !important; }
        .dark .text-slate-500 { color: #cbd5e1 !important; }
        .dark .border-slate-100, .dark .border-slate-200 { border-color: #334155 !important; }
        .dark .input-field { background-color: #0f172a; border-color: #334155; color: #f1f5f9; }
        .dark .input-field:focus { background-color: #0f172a; border-color: #3b82f6; }
        .dark .credential-chip { background-color: #334155; border-color: #475569; color: #f1f5f9; }
        .dark .credential-chip:hover { background-color: #475569; }
        .dark .bg-blue-50 { background-color: rgba(59,130,246,0.1) !important; color: #60a5fa !important; }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'enabled' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center dot-bg p-6">

    <!-- Centered Login Card -->
    <div class="w-full max-w-[440px] bg-white p-8 lg:p-12 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 fade-up">
        
        <!-- Logo -->
        <div class="flex flex-col items-center gap-3 mb-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/addashboard-pro-icon.png') }}" alt="AdDashboard Pro Icon" class="h-10 w-auto">
                <span class="text-2xl font-bold text-slate-800 tracking-tight">AdDashboard <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-sm align-middle ml-1 border border-blue-100/50 shadow-sm">Pro</span></span>
            </div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-widest mt-1">Advertising Management Platform</p>
        </div>

        <!-- Error message -->
        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl flex items-center gap-3" style="background:#fef2f2;border:1px solid #fecaca;">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
        </div>
        @endif

        <!-- Login form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Email</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                           placeholder="admin@addashboard.id" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="password" name="password" id="passwordInput" class="input-field"
                           placeholder="••••••••" required>
                    <button type="button" onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition text-sm">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember & forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs text-slate-500">Ingat saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium transition">Lupa password?</a>
            </div>

            <button type="submit" class="btn-login mt-2">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>Masuk ke Dashboard
            </button>
        </form>

        <!-- Divider -->
        <div class="relative mt-8 mb-6">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
            <div class="relative flex justify-center"><span class="bg-white px-4 text-xs text-slate-400 uppercase tracking-wider font-medium">Demo Credentials</span></div>
        </div>

        <!-- Demo credentials -->
        <div class="space-y-2">
            <button onclick="fillCredential('admin@addashboard.id','admin123')" class="credential-chip w-full justify-between">
                <span class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                        <i class="fa-solid fa-user-shield text-white text-[10px]"></i>
                    </span>
                    <span class="font-semibold">Admin</span>
                </span>
                <span class="text-slate-400 text-[11px]">admin@addashboard.id</span>
            </button>
            <button onclick="fillCredential('demo@addashboard.id','demo123')" class="credential-chip w-full justify-between">
                <span class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);">
                        <i class="fa-solid fa-user text-white text-[10px]"></i>
                    </span>
                    <span class="font-semibold">Demo User</span>
                </span>
                <span class="text-slate-400 text-[11px]">demo@addashboard.id</span>
            </button>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10">
            <p class="text-[11px] text-slate-400">© 2025 AdDashboard Pro · PT Indosaku Digital Teknologi</p>
            <div class="flex items-center justify-center gap-4 mt-3">
                <a href="#" class="text-[11px] text-slate-400 hover:text-blue-600 transition">Privacy Policy</a>
                <span class="text-slate-300">·</span>
                <a href="#" class="text-[11px] text-slate-400 hover:text-blue-600 transition">Terms</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const inp = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.classList.replace('fa-eye','fa-eye-slash');
            } else {
                inp.type = 'password';
                icon.classList.replace('fa-eye-slash','fa-eye');
            }
        }
        function fillCredential(email, password) {
            document.querySelector('input[name=email]').value = email;
            document.querySelector('input[name=password]').value = password;
        }
    </script>
</body>
</html>
