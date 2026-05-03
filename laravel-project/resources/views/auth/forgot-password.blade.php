<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password – AdDashboard Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp .6s ease both; }
        .input-field {
            width:100%; padding:14px 16px 14px 46px;
            border:1.5px solid #e2e8f0; border-radius:14px;
            font-size:14px; color:#1e293b; background:#fff;
            transition:all .25s; outline:none;
        }
        .input-field:focus { border-color:#3b82f6; box-shadow:0 0 0 4px rgba(59,130,246,.1); background:#f8fafc; }
        .btn-primary {
            width:100%; padding:14px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:#fff; font-size:15px; font-weight:600; border-radius:14px;
            border:none; cursor:pointer; transition:all .25s;
            box-shadow:0 4px 20px rgba(37,99,235,.35);
        }
    </style>
</head>
<body class="bg-[#fafbff] min-h-screen flex items-center justify-center p-6 bg-[radial-gradient(#e0e7ff_0.8px,transparent_0.8px)] [background-size:24px_24px]">
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-2xl shadow-slate-200/50 p-8 md:p-10 border border-slate-100 fade-up">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 mb-4">
                <i class="fa-solid fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Lupa Password?</h1>
            <p class="text-slate-500 text-sm mt-2 text-center">Masukkan email Anda untuk menerima instruksi pengaturan ulang password.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3 text-green-700 text-sm font-medium">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Email Alamat</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="email" name="email" class="input-field" placeholder="nama@perusahaan.id" required>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Link Reset
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10">
            <p class="text-[11px] text-slate-400">© 2025 AdDashboard Pro · PT Indosaku Digital Teknologi</p>
        </div>
    </div>
</body>
</html>
