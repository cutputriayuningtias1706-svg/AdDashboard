@extends('layouts.main')

@section('title', 'Kontrak Kerjasama Vendor - AdDashboard Pro')

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kontrak Kerjasama Vendor</h1>
            <p class="text-sm text-slate-500 mt-1">Integrasi API dengan pihak ketiga (Publisher Iklan) untuk mendistribusikan kampanye iklan Anda.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-semibold text-green-800">Berhasil!</h4>
            <p class="text-sm text-green-600 mt-1">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($vendors as $vendor)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col transition hover:shadow-md">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl {{ $vendor['bg_color'] }} flex items-center justify-center {{ $vendor['color'] }} text-2xl border {{ $vendor['border_color'] }}">
                        <i class="fa-solid {{ $vendor['logo'] }}"></i>
                    </div>
                    
                    @if($vendor['status'] == 'active')
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                        </span>
                    @elseif($vendor['status'] == 'pending')
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                        </span>
                    @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tidak Aktif
                        </span>
                    @endif
                </div>

                <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $vendor['name'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $vendor['description'] }}</p>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
                <a href="{{ route('contracts.download_pks', $vendor['id']) }}" class="text-xs font-semibold text-slate-600 hover:text-indigo-600 transition flex items-center gap-2 px-2 py-1">
                    <i class="fa-solid fa-file-pdf text-red-500"></i> Download PKS
                </a>
                
                @if($vendor['status'] == 'inactive')
                    <a href="{{ route('contracts.apply', $vendor['id']) }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                        Ajukan Kerjasama
                    </a>
                @elseif($vendor['status'] == 'pending')
                    <button disabled class="px-5 py-2 bg-slate-200 text-slate-400 text-xs font-bold rounded-lg cursor-not-allowed">
                        Menunggu Review...
                    </button>
                @else
                    <button disabled class="px-5 py-2 bg-green-100 text-green-700 text-xs font-bold rounded-lg">
                        <i class="fa-solid fa-check mr-1"></i> Terintegrasi
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
