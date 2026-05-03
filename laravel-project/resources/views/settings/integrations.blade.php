@extends('layouts.main')

@section('title', 'API & Integrasi – AdDashboard Pro')

@section('shimmer-content')
    <div class="shimmer-item w-1/4 mb-4"></div>
    <div class="shimmer-item w-full h-12 mb-8"></div> <!-- Tab bar -->
    <div class="space-y-6">
        <div class="shimmer-item w-full h-40"></div>
        <div class="shimmer-item w-full h-40"></div>
        <div class="shimmer-item w-full h-40"></div>
    </div>
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
        <a href="{{ route('settings.password') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
            Ubah Password
        </a>
        <a href="{{ route('settings.integrations') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600 whitespace-nowrap">
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

    <div class="space-y-6">
        @foreach($integrations as $key => $platform)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 
                            @if($key == 'google') bg-blue-50 text-blue-600 
                            @elseif($key == 'meta') bg-indigo-50 text-indigo-600 
                            @else bg-pink-50 text-pink-600 @endif">
                            @if($key == 'google')
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.92 3.36-2.12 4.52-1.2 1.2-2.88 2.2-5.72 2.2-4.6 0-8.32-3.72-8.32-8.32s3.72-8.32 8.32-8.32c2.48 0 4.28.96 5.64 2.28l2.32-2.32C18.52 2.28 15.8 1 12.48 1 6.08 1 1 6.08 1 12.48S6.08 23.96 12.48 23.96c3.48 0 6.12-1.12 8.16-3.24 2.08-2.08 2.72-5.04 2.72-7.36 0-.72-.04-1.4-.12-2.04h-10.76z"/></svg>
                            @elseif($key == 'meta')
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.03 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.03-2.86-.31-4.13-1.03-2.28-1.3-3.6-3.89-3.3-6.5.12-1.86 1.02-3.63 2.49-4.78 1.41-1.11 3.22-1.63 5.03-1.47.05 1.58.01 3.2.01 4.81-1.07-.21-2.26.23-3.02 1.09-.6.61-.91 1.48-.85 2.33.02.73.3 1.48.83 2.02.55.53 1.31.81 2.1.8 1.55-.01 2.81-1.2 3.1-2.7.02-.16.03-.31.03-.47l.02-12.45z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $platform['name'] }}</h3>
                            <div class="flex items-center mt-1">
                                <span class="w-2 h-2 rounded-full mr-2 @if($platform['status'] == 'connected') bg-green-500 @else bg-gray-300 @endif"></span>
                                <span class="text-xs font-medium uppercase tracking-wider @if($platform['status'] == 'connected') text-green-600 @else text-gray-500 @endif">
                                    {{ $platform['status'] == 'connected' ? 'Terhubung' : 'Terputus' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($platform['status'] == 'connected')
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Terakhir Sinkronisasi</p>
                            <p class="text-sm font-medium text-gray-700">{{ $platform['last_sync'] }}</p>
                        </div>
                    @endif
                </div>

                <form action="{{ route('settings.integrations.update', $key) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">API Access Token / Key</label>
                            <div class="relative">
                                <input type="password" name="api_key" value="{{ $platform['api_key'] }}" class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan API Key">
                                <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Client ID / App ID</label>
                            <input type="text" name="app_id" value="{{ $platform['client_id'] ?? $platform['app_id'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" placeholder="Masukkan ID Aplikasi">
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <p class="text-xs text-gray-500 italic max-w-md">
                            * Integrasi ini memungkinkan dashboard untuk mengambil data kampanye, biaya, dan performa secara otomatis setiap jam.
                        </p>
                        <button type="submit" class="px-6 py-2 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                            Simpan API
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
