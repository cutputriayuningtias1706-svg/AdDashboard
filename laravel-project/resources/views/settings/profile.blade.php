@extends('layouts.main')

@section('title', 'Profil Perusahaan – AdDashboard Pro')

@section('shimmer-content')
    <div class="shimmer-item w-1/4 mb-4"></div>
    <div class="shimmer-item w-full h-12 mb-8"></div> <!-- Tab bar -->
    <div class="shimmer-item w-full h-96"></div> <!-- Form area -->
@endsection

@section('content')
<div class="px-4 py-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-500">Kelola informasi perusahaan dan pengaturan akun Anda.</p>
    </div>

    <!-- Settings Navigation -->
    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto">
        <a href="{{ route('settings.profile') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 whitespace-nowrap">
            Profil Perusahaan
        </a>
        <a href="{{ route('settings.password') }}" class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap">
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <form action="{{ route('settings.profile.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Perusahaan</label>
                        <input type="text" name="name" value="{{ $company->name }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Masukkan nama perusahaan">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Email Bisnis</label>
                        <input type="email" name="email" value="{{ $company->email }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="email@perusahaan.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ $company->phone }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="+62...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Website</label>
                        <input type="text" name="website" value="{{ $company->website }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="https://...">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Jl. ...">{{ $company->address }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">NPWP / Tax ID</label>
                        <input type="text" name="tax_id" value="{{ $company->tax_id }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="00.000.000.0-000.000">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
