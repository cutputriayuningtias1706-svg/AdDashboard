@extends('layouts.main')

@section('title', 'Ubah Password – AdDashboard Pro')

@section('shimmer-content')
    <div class="shimmer-item w-1/4 mb-4"></div>
    <div class="shimmer-item w-full h-12 mb-8"></div> <!-- Tab bar -->
    <div class="shimmer-item w-1/2 h-80"></div> <!-- Form area -->
@endsection

@section('content')
<div class="px-4 py-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-500">Kelola informasi perusahaan dan pengaturan akun Anda.</p>
    </div>

    <!-- Settings Navigation -->
    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto">
        <a href="{{ route('settings.profile') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
            Profil Perusahaan
        </a>
        <a href="{{ route('settings.password') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600 whitespace-nowrap">
            Ubah Password
        </a>
        <a href="{{ route('settings.integrations') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
            API & Integrasi
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
        <div class="p-8">
            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="••••••••">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Password Baru</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Minimal 8 karakter">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-200">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
