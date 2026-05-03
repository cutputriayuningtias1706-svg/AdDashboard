<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Ad Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        @keyframes float { 0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)} }
        @keyframes shimmer { 0%{background-position:-200% center}100%{background-position:200% center} }
        .fade-up { animation: fadeUp .5s ease both; }
        .fade-up-2 { animation: fadeUp .5s .1s ease both; }
        .fade-up-3 { animation: fadeUp .5s .2s ease both; }
        .float-icon { animation: float 4s ease-in-out infinite; }
        .input-field {
            width:100%; padding:12px 16px 12px 44px;
            border:1.5px solid #e2e8f0; border-radius:12px;
            font-size:14px; color:#1e293b; background:#fff;
            transition:all .2s; outline:none;
        }
        .input-field:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .input-field::placeholder { color:#94a3b8; }
        .btn-login {
            width:100%; padding:13px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            color:#fff; font-size:15px; font-weight:600; border-radius:12px;
            border:none; cursor:pointer; transition:all .2s;
            box-shadow:0 4px 16px rgba(99,102,241,.35);
        }
        .btn-login:hover { opacity:.92; transform:translateY(-1px); box-shadow:0 8px 24px rgba(99,102,241,.4); }
        .btn-login:active { transform:translateY(0); }
        .credential-chip {
            display:inline-flex; align-items:center; gap:6px;
            padding:6px 12px; border-radius:8px; font-size:12px;
            background:#f8faff; border:1px solid #e0e7ff; color:#4f46e5;
            cursor:pointer; transition:all .15s;
        }
        .credential-chip:hover { background:#e0e7ff; }
        .dot-bg {
            background-color:#f8faff;
            background-image: radial-gradient(#c7d2fe 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="min-h-screen flex dot-bg">

    <!-- Left illustration panel -->
    <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center p-16 relative overflow-hidden" style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 50%,#9333ea 100%);">
        <!-- decorative blobs -->
        <div class="absolute w-72 h-72 rounded-full opacity-20 top-10 -left-16" style="background:#fff;filter:blur(60px);"></div>
        <div class="absolute w-56 h-56 rounded-full opacity-15 bottom-20 -right-10" style="background:#818cf8;filter:blur(50px);"></div>

        <div class="float-icon mb-10 w-full max-w-xs">
            <img src="{{ asset('images/storyset-illustration.png') }}" alt="Ad Dashboard Illustration" class="w-full h-auto drop-shadow-xl">
        </div>

        <h1 class="text-4xl font-bold text-white text-center leading-tight mb-4">Ad Dashboard</h1>
        <p class="text-white/70 text-center text-base max-w-xs leading-relaxed">Platform manajemen iklan terpadu untuk Google, Meta, dan TikTok Ads.</p>

        <!-- Stats badges -->
        <div class="mt-12 grid grid-cols-3 gap-4 w-full max-w-sm">
            @foreach([['Rp 14.2B','Total Spend'],['3 Platform','Terintegrasi'],['68K+','Conversions']] as $s)
            <div class="text-center p-4 rounded-2xl" style="background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);">
                <p class="text-white font-bold text-sm">{{ $s[0] }}</p>
                <p class="text-white/60 text-xs mt-0.5">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right login form -->
    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">

            <!-- Logo mobile -->
            <div class="lg:hidden flex items-center gap-3 mb-8 fade-up">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <i class="fa-solid fa-chart-line text-white"></i>
                </div>
                <span class="text-xl font-bold text-slate-800">Ad Dashboard</span>
            </div>

            <div class="fade-up mb-8">
                <h2 class="text-2xl font-bold text-slate-800">Selamat Datang 👋</h2>
                <p class="text-slate-500 mt-1 text-sm">Masuk untuk mengakses dashboard iklan Anda</p>
            </div>

            @if($errors->any())
            <div class="fade-up mb-5 p-4 rounded-xl flex items-center gap-3" style="background:#fef2f2;border:1px solid #fecaca;">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5 fade-up-2">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                               placeholder="admin@addashboard.id" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" id="passwordInput" class="input-field"
                               placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition text-sm">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login mt-2">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>Masuk ke Dashboard
                </button>
            </form>

            <!-- Demo credentials -->
            <div class="mt-8 fade-up-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">Demo Credentials</p>
                <div class="flex flex-col gap-2">
                    <button onclick="fillCredential('admin@addashboard.id','admin123')" class="credential-chip w-full justify-between">
                        <span><i class="fa-solid fa-user-shield mr-1"></i> Admin</span>
                        <span class="text-slate-400">admin@addashboard.id / admin123</span>
                    </button>
                    <button onclick="fillCredential('demo@addashboard.id','demo123')" class="credential-chip w-full justify-between">
                        <span><i class="fa-solid fa-user mr-1"></i> Demo</span>
                        <span class="text-slate-400">demo@addashboard.id / demo123</span>
                    </button>
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-8 fade-up-3">© 2025 Ad Dashboard · Advertising Platform</p>
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
