@extends('layouts.main')

@section('title', 'Pengajuan Kerjasama ' . $vendor->name . ' - AdDashboard Pro')

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    <!-- Header Back -->
    <div class="mb-6">
        <a href="{{ route('contracts.index') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition flex items-center gap-2 w-max">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Vendor
        </a>
    </div>

    <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl {{ $vendor->bg_color }} flex items-center justify-center {{ $vendor->color }} text-3xl border {{ $vendor->border_color }} flex-shrink-0">
            <i class="fa-solid {{ $vendor->logo }}"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Form Pengajuan Kerjasama API</h1>
            <p class="text-sm text-slate-500 mt-1">Vendor: <span class="font-bold text-slate-700">{{ $vendor->name }}</span></p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-semibold text-red-800">Terdapat Kesalahan</h4>
            <ul class="list-disc pl-5 mt-1 text-sm text-red-600">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('contracts.store', $vendor->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Section 1: Profil Perusahaan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">1. Profil Perusahaan Pengaju</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Nama Perusahaan Legal <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', 'PT Indosaku Digital Teknologi') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Email Perusahaan <span class="text-red-500">*</span></label>
                    <input type="email" name="company_email" value="{{ old('company_email', 'partnership@addashboard.id') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Nomor NPWP Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="npwp" value="{{ old('npwp') }}" placeholder="00.000.000.0-000.000" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                </div>
            </div>
        </div>

        <!-- Section 2: Upload Legalitas -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">2. Dokumen Legalitas (SIUP/TDP/NIB)</h3>
            
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Upload File (PDF / ZIP) <span class="text-red-500">*</span></label>
                <div class="relative flex justify-center items-center w-full h-32 border-2 border-dashed border-slate-300 bg-slate-50 rounded-xl hover:bg-slate-100 hover:border-blue-400 transition-colors cursor-pointer group">
                    <input type="file" name="legality_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.zip" required>
                    <div class="text-center">
                        <i class="fa-solid fa-file-shield text-slate-400 text-2xl mb-2 group-hover:text-blue-500 transition-colors"></i>
                        <p class="text-sm font-semibold text-slate-700">Pilih dokumen legalitas perusahaan</p>
                        <p class="text-[11px] text-slate-500 mt-1">Gabungkan NIB, NPWP, & SIUP dalam 1 PDF/ZIP (max 10MB)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Integrasi API -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6">3. Kredensial Integrasi API</h3>
            
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl mb-5 flex items-start gap-3">
                <i class="fa-solid fa-key text-blue-500 mt-1"></i>
                <p class="text-sm text-blue-900 leading-relaxed">
                    Dapatkan API Token / Access Token dari dashboard developer <span class="font-bold">{{ $vendor->name }}</span> Anda dan masukkan di bawah ini untuk sinkronisasi otomatis.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Access Token / API Key <span class="text-red-500">*</span></label>
                <input type="password" name="api_token" placeholder="Masukkan token rahasia..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
            </div>
        </div>

        <!-- Section 4: Terms & Submission -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
            <label class="flex items-start gap-3 cursor-pointer p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors mb-6">
                <div class="mt-0.5">
                    <input type="checkbox" name="terms_accepted" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" required>
                </div>
                <div>
                    <span class="text-sm font-bold text-slate-800">Saya menyetujui Syarat dan Ketentuan (T&C)</span>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        Dengan mencentang kotak ini, saya selaku perwakilan sah dari perusahaan setuju dengan seluruh klausul yang terdapat dalam <a href="{{ route('contracts.download_pks', $vendor->id) }}" class="text-blue-600 font-semibold hover:underline" target="_blank">Dokumen Perjanjian Kerja Sama (PKS)</a> dari pihak {{ $vendor->name }}.
                    </p>
                </div>
            </label>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-xl font-bold shadow-md shadow-blue-200 transition-transform transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                <i class="fa-solid fa-paper-plane"></i> Ajukan Integrasi Kerjasama
            </button>
        </div>
    </form>
</div>
@endsection
