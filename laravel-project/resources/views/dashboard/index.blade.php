@extends('layouts.main')

@section('title', 'Dashboard - AdDashboard Pro')

@section('shimmer-content')
    <div class="shimmer-item w-1/4 mb-4"></div>
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="shimmer-item h-32"></div>
        <div class="shimmer-item h-32"></div>
        <div class="shimmer-item h-32"></div>
        <div class="shimmer-item h-32"></div>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="shimmer-item h-64 col-span-2"></div>
        <div class="shimmer-item h-64"></div>
    </div>
@endsection
@section('page-title', '')

@section('content')
@php
    $hour = now()->timezone('Asia/Jakarta')->hour;
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat Pagi';
        $greetingIcon = 'fa-sun';
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
        $greetingIcon = 'fa-cloud-sun';
    } elseif ($hour >= 15 && $hour < 19) {
        $greeting = 'Selamat Sore';
        $greetingIcon = 'fa-cloud-moon';
    } else {
        $greeting = 'Selamat Malam';
        $greetingIcon = 'fa-moon';
    }
@endphp

<!-- Welcome Greeting Card -->
<div class="fade-up mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-3xl p-7 shadow-lg shadow-blue-100 flex items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-3xl flex-shrink-0 border border-white/20">
            <i class="fa-solid {{ $greetingIcon }}"></i>
        </div>
        <div class="flex-1">
            <h2 id="greeting-text" class="text-2xl font-bold text-white transition-all duration-700 ease-in-out" 
                data-name="{{ session('auth_user')['name'] ?? 'Admin' }}"
                data-greeting="{{ $greeting }}">
                {{ $greeting }},
            </h2>
            <p class="text-blue-50 text-sm font-medium mt-1 opacity-90">PT Indosaku Digital Teknologi</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest opacity-80">Waktu Indonesia Barat</p>
            <p class="text-lg font-bold text-white mt-1">{{ now()->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
        </div>
    </div>
</div>

<!-- Overview Cards (Balance & Disbursement) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 fade-up">
    <!-- Card Total Saldo -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group hover:border-blue-300 transition-all">
        <div class="relative z-10">
            <p class="text-slate-400 text-xs font-bold mb-2 uppercase tracking-widest">Total Saldo Saat Ini</p>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight mb-4">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>
            <button onclick="document.getElementById('topupModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white hover:bg-slate-800 rounded-xl font-bold text-xs transition shadow-sm">
                <i class="fa-solid fa-plus"></i> Top-up Saldo
            </button>
        </div>
        <div class="relative z-10 text-slate-100 group-hover:text-blue-50 transition-colors">
            <i class="fa-solid fa-wallet text-6xl"></i>
        </div>
    </div>

    <!-- Card Total Spending -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden group hover:border-emerald-300 transition-all">
        <div class="relative z-10">
            <p class="text-slate-400 text-xs font-bold mb-2 uppercase tracking-widest">Total Spending</p>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight mb-4">Rp {{ number_format($totalDisbursement, 0, ',', '.') }}</h2>
            <p class="text-slate-500 text-[10px] bg-slate-100 inline-block px-3 py-1.5 rounded-lg font-semibold">Real data: Jul - Sep 2025</p>
        </div>
        <div class="relative z-10 text-slate-100 group-hover:text-emerald-50 transition-colors">
            <i class="fa-solid fa-bullhorn text-6xl"></i>
        </div>
    </div>
</div>

<!-- Month Picker -->
<div class="bg-gradient-to-r from-slate-50 via-white to-slate-100 rounded-3xl shadow-xl border border-slate-200 p-5 mb-6 fade-up">
    <form method="GET" action="{{ route('dashboard.index') }}" class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="text-sm font-semibold text-slate-700">Pilih Periode:</span>
            <select name="month" onchange="this.form.submit()" class="px-4 py-3 border border-slate-200 rounded-2xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-200 bg-white shadow-sm">
                <option value="">-- Pilih Bulan --</option>
                <option value="2025-07" {{ $selectedMonth == '2025-07' ? 'selected' : '' }}>Juli 2025</option>
                <option value="2025-08" {{ $selectedMonth == '2025-08' ? 'selected' : '' }}>Agustus 2025</option>
                <option value="2025-09" {{ $selectedMonth == '2025-09' ? 'selected' : '' }}>September 2025</option>
            </select>
        </div>
        @if($selectedMonth)
            <a href="{{ route('dashboard.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-cyan-300 hover:text-cyan-700 shadow-sm">
                <i class="fa-solid fa-times mr-2"></i> Reset
            </a>
        @endif
    </form>
</div>

<!-- Spending Iklan Jul–Sep 2025 (always shown, non-zero) -->
<div class="fade-up rounded-2xl p-6 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 16px rgba(0,0,0,0.07);">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div>
            <h3 class="text-base font-bold text-slate-800">Spending Iklan (Juli–September 2025)</h3>
            <p class="text-sm text-slate-400 mt-1">Ringkasan 3 bulan untuk membantu pemantauan performa.</p>
        </div>
        <div class="px-5 py-4 rounded-2xl text-slate-800 border border-slate-200 bg-slate-50">
            <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Total 3 Bulan</p>
            <p class="text-xl xl:text-2xl font-bold mt-1 whitespace-nowrap tracking-tight">Rp {{ number_format($monthlySpendingSummary[3]['value'], 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden bg-white border border-slate-100 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[0]['month'] }}</p>
            <p class="text-xl xl:text-2xl font-bold text-slate-800 mt-3 whitespace-nowrap tracking-tight">Rp {{ number_format($monthlySpendingSummary[0]['value'], 0, ',', '.') }}</p>
        </div>
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden bg-white border border-slate-100 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[1]['month'] }}</p>
            <p class="text-xl xl:text-2xl font-bold text-slate-800 mt-3 whitespace-nowrap tracking-tight">Rp {{ number_format($monthlySpendingSummary[1]['value'], 0, ',', '.') }}</p>
        </div>
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden bg-white border border-slate-100 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[2]['month'] }}</p>
            <p class="text-xl xl:text-2xl font-bold text-slate-800 mt-3 whitespace-nowrap tracking-tight">Rp {{ number_format($monthlySpendingSummary[2]['value'], 0, ',', '.') }}</p>
        </div>
    </div>
</div>

@if(!$selectedMonth)
<div class="fade-up rounded-3xl p-10 text-center mb-5 bg-white border border-slate-200 shadow-sm">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-slate-50 text-slate-400 border border-slate-100">
        <i class="fa-solid fa-calendar text-2xl"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">Pilih Periode Waktu</h3>
    <p class="text-slate-400 max-w-xl mx-auto text-sm font-medium">Silakan pilih bulan untuk melihat data iklan yang lebih lengkap dan performa kampanye secara real-time.</p>
</div>
@else
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
    <!-- Total Spend -->
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total Spend</p>
                <p class="text-xl xl:text-2xl font-bold text-slate-800 whitespace-nowrap tracking-tight">Rp {{ number_format($totalSpend, 0, ',', '.') }}</p>
                <p class="text-[10px] mt-2 font-bold px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md inline-block">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i>{{ number_format($spendChange, 0) }}%
                </p>
            </div>
        </div>
    </div>
    <!-- Total Impressions -->
    <div class="card-hover fade-up-2 rounded-2xl p-6 relative overflow-hidden bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total Impressions</p>
                <p class="text-xl xl:text-2xl font-bold text-slate-800 whitespace-nowrap tracking-tight">{{ number_format($totalImpressions, 0) }}</p>
            </div>
        </div>
    </div>
    <!-- Total Clicks -->
    <div class="card-hover fade-up-3 rounded-2xl p-6 relative overflow-hidden bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-400"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total Clicks</p>
                <p class="text-xl xl:text-2xl font-bold text-slate-800 whitespace-nowrap tracking-tight">{{ number_format($totalClicks, 0) }}</p>
                <p class="text-[10px] mt-2 font-bold px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md inline-block">CTR: {{ number_format($ctr, 1) }}%</p>
            </div>
        </div>
    </div>
    <!-- Total Conversions -->
    <div class="card-hover fade-up-4 rounded-2xl p-6 relative overflow-hidden bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total Conversions</p>
                <p class="text-xl xl:text-2xl font-bold text-slate-800 whitespace-nowrap tracking-tight">{{ number_format($totalConversions, 0) }}</p>
                <p class="text-[10px] mt-2 font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md inline-block">CPC: Rp {{ number_format($cpc, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Optimization Score & Top Campaign Performance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
<!-- Optimization Score with Tips Slider -->
    <div class="card-hover fade-up relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-slate-800">Optimization Score</h3>
                <p class="text-sm text-slate-500 mt-1">Nilai efisiensi kampanye berdasarkan metrik utama.</p>
            </div>
            <button id="tipsPrevBtn" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center transition shadow-sm border border-slate-100">
                <i class="fa-solid fa-chevron-left text-slate-600 text-sm"></i>
            </button>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
            <div class="relative w-28 h-28">
                <svg class="w-28 h-28 transform -rotate-90">
                    <circle cx="56" cy="56" r="48" stroke="#e2e8f0" stroke-width="10" fill="none"/>
                    <circle cx="56" cy="56" r="48" stroke="#0ea5e9" stroke-width="10" fill="none" 
                        stroke-dasharray="{{ $optimizationScore * 3.01 }} {{ 301 - $optimizationScore * 3.01 }}"
                        stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-3xl font-bold text-slate-900">{{ $optimizationScore }}</span>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm text-slate-600 mb-4">
                    @if($optimizationScore >= 80)
                        Kampanye Anda berada dalam performa prima.
                    @elseif($optimizationScore >= 60)
                        Kampanye Anda berjalan baik dan stabil.
                    @else
                        Perlu sedikit optimasi untuk hasil yang lebih baik.
                    @endif
                </p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <span class="flex h-8 w-8 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700"><i class="fa-solid fa-check"></i></span>
                        <span>CTR: {{ number_format($ctr, 1) }}%</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <span class="flex h-8 w-8 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700"><i class="fa-solid fa-rocket"></i></span>
                        <span>Conversions: {{ number_format($totalConversions, 0) }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <span class="flex h-8 w-8 items-center justify-center rounded-2xl bg-amber-100 text-amber-700"><i class="fa-solid fa-tag"></i></span>
                        <span>CPC: Rp {{ number_format($cpc, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tips Slider -->
        <div class="mt-4 pt-4 border-t border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Advertising Tips</span>
                <div class="flex gap-2">
                    <span id="tipDot1" class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span id="tipDot2" class="w-2 h-2 rounded-full bg-slate-300"></span>
                    <span id="tipDot3" class="w-2 h-2 rounded-full bg-slate-300"></span>
                </div>
            </div>
            <div id="tipsSlider" class="overflow-hidden h-12 relative">
                <div class="tips-slide absolute w-full transition-transform duration-500" style="transform: translateX(0%)">
                    <p class="text-sm text-slate-700"><i class="fa-solid fa-lightbulb text-amber-500 mr-2"></i>Gunakan targeting yang spesifik untuk menjangkau audiens yang tepat dan kurangi biaya per klik.</p>
                </div>
                <div class="tips-slide absolute w-full transition-transform duration-500" style="transform: translateX(100%)">
                    <p class="text-sm text-slate-700"><i class="fa-solid fa-lightbulb text-amber-500 mr-2"></i>A/B testing kreatif iklan Anda secara rutin untuk menemukan versi dengan performa terbaik.</p>
                </div>
                <div class="tips-slide absolute w-full transition-transform duration-500" style="transform: translateX(100%)">
                    <p class="text-sm text-slate-700"><i class="fa-solid fa-lightbulb text-amber-500 mr-2"></i>Tambahkan call-to-action yang jelas seperti "Beli Sekarang" atau "Hubungi Kami" untuk tingkat konversi lebih tinggi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Campaign Performance -->
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-slate-800">Top Campaign Performance</h3>
        </div>
        <div class="table-container">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100" style="background:#f8fafc;">
                        <th class="py-3 px-4 sticky-col">Campaign</th>
                        <th class="py-3 px-4">Platform</th>
                        <th class="py-3 px-4 text-right">Reach</th>
                        <th class="py-3 px-4 text-right">Impr.</th>
                        <th class="py-3 px-4 text-right">Freq.</th>
                        <th class="py-3 px-4 text-right">Clicks</th>
                        <th class="py-3 px-4 text-right">CTR</th>
                        <th class="py-3 px-4 text-right">Spend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCampaigns as $campaign)
                    <tr class="table-row-hover border-b border-slate-50 group">
                        <td class="py-3 px-4 sticky-col">
                            <p class="text-sm font-semibold text-slate-700 truncate max-w-[200px]" title="{{ $campaign['name'] }}">{{ $campaign['name'] }}</p>
                            <span class="text-[10px] text-slate-400">ID: {{ substr($campaign['video_id'], 0, 8) }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="capitalize text-sm inline-flex items-center gap-2 text-slate-600">
                                @switch($campaign['platform'])
                                    @case('google') <i class="fa-brands fa-google text-blue-500"></i> @break
                                    @case('meta') <i class="fa-brands fa-facebook text-blue-600"></i> @break
                                    @case('tiktok') <i class="fa-brands fa-tiktok text-pink-500"></i> @break
                                @endswitch
                                {{ $campaign['platform'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right text-sm text-slate-600">{{ number_format(($campaign['impressions'] ?? 0) / 1.2) }}</td>
                        <td class="py-3 px-4 text-right text-sm text-slate-600">{{ number_format($campaign['impressions'] ?? 0) }}</td>
                        <td class="py-3 px-4 text-right text-sm text-slate-600">1.20</td>
                        <td class="py-3 px-4 text-right text-sm text-slate-600">{{ number_format($campaign['clicks'] ?? 0) }}</td>
                        <td class="py-3 px-4 text-right text-sm font-semibold text-blue-600">{{ number_format($campaign['ctr'] ?? 0, 1) }}%</td>
                        <td class="py-3 px-4 text-right text-sm font-bold text-slate-800">Rp {{ number_format($campaign['spend'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Device Summary & Performance Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    <!-- Devices Pie Chart -->
    <div class="card-hover fade-up relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <h3 class="text-base font-bold text-slate-800 mb-3">Top Devices</h3>
        <div class="flex justify-center mb-3" style="height:140px;">
            <canvas id="devicePieChart" data-devices='{{ json_encode($deviceStats) }}'></canvas>
        </div>
        <div class="space-y-2 mt-2">
            @foreach($deviceStats as $device)
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $device['color'] }};"></span>
                    <span class="font-medium text-slate-600">{{ $device['device'] }}</span>
                </div>
                <span class="font-bold text-slate-700">{{ $device['percentage'] }}%</span>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Gender & Age Audience -->
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-800">Audiens</h3>
                <p class="text-xs text-slate-400 mt-0.5">Berdasarkan umur & gender</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-semibold">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#3b82f6;"></span>Pria 58%</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#64748b;"></span>Wanita 42%</span>
            </div>
        </div>
        
        <div class="space-y-3">
            @php
                $maxAgeVal = 0;
                foreach($audienceData['ageGroups'] as $ag) {
                    $total = $ag['male'] + $ag['female'];
                    if($total > $maxAgeVal) $maxAgeVal = $total;
                }
            @endphp
            @foreach($audienceData['ageGroups'] as $i => $ag)
            @php
                $total = $ag['male'] + $ag['female'];
                $pct = $maxAgeVal > 0 ? round(($total / $maxAgeVal) * 100) : 0;
                $colors = ['#3b82f6','#1e293b','#2563eb','#475569','#64748b'];
                $bgColors = ['#eff6ff','#f8fafc','#f1f5f9','#f1f5f9','#f8fafc'];
                $col = $colors[$i % count($colors)];
                $bg = $bgColors[$i % count($bgColors)];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-5 rounded-md flex items-center justify-center text-[10px] font-bold" style="background:{{ $bg }};color:{{ $col }};">{{ $ag['label'] }}</span>
                        <span class="text-sm font-semibold text-slate-700">Tingkat Interaksi</span>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $total }}%</span>
                </div>
                <div class="h-2 rounded-full overflow-hidden" style="background:#f1f5f9;">
                    <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background:{{ $col }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Top 5 Location Indonesia -->
    <div class="card-hover fade-up-3 relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-800">Top Lokasi Iklan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Top 5 kota di Indonesia</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg" style="background:#ecfdf5;color:#059669;">By Clicks</span>
        </div>
        @if($locationStats[0]['clicks'] > 0)
        <div class="space-y-3" id="locationChart">
            @foreach($locationStats as $i => $loc)
            @php
                $pct = $maxLocationClicks > 0 ? round(($loc['clicks']/$maxLocationClicks)*100) : 0;
                $colors = ['#2563eb','#1e293b','#10b981','#f59e0b','#334155'];
                $bgColors = ['#eff6ff','#f8fafc','#ecfdf5','#fffbeb','#f1f5f9'];
                $col = $colors[$i] ?? '#2563eb';
                $bg  = $bgColors[$i] ?? '#eff6ff';
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold" style="background:{{ $bg }};color:{{ $col }};">{{ $i+1 }}</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $loc['city'] }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ number_format($loc['clicks']) }} clicks</span>
                </div>
                <div class="h-2 rounded-full overflow-hidden" style="background:#f1f5f9;">
                    <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background:{{ $col }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-32 text-slate-300">
            <i class="fa-solid fa-map-location-dot text-3xl mb-2"></i>
            <p class="text-xs">Pilih bulan untuk melihat data lokasi</p>
        </div>
        @endif
    </div>
</div>

<!-- Active Campaigns & Recent Invoices -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <!-- Recent Campaigns -->
    <div class="card-hover fade-up relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-slate-800">Recent Campaigns</h3>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition" style="background:#f1f5f9; color:#334155;">View All</a>
        </div>
        <div class="space-y-2.5">
            @foreach($topCampaigns->take(5) as $i => $campaign)
            <div class="flex items-center justify-between p-3 rounded-xl transition" style="background:#f8fafc; border:1px solid #f1f5f9;" onmouseover="this.style.background='#fff';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'" onmouseout="this.style.background='#f8fafc';this.style.boxShadow='none'">
                <div class="flex items-center min-w-0 gap-3">
                    <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold flex-shrink-0" style="background:#f1f5f9;color:#334155;">{{ $i+1 }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate" title="{{ $campaign['name'] }}">{{ $campaign['name'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            @switch($campaign['platform'])
                                @case('google') <i class="fa-brands fa-google text-blue-500 text-[10px]"></i> @break
                                @case('meta') <i class="fa-brands fa-facebook text-blue-600 text-[10px]"></i> @break
                                @case('tiktok') <i class="fa-brands fa-tiktok text-slate-800 text-[10px]"></i> @break
                            @endswitch
                            <span class="text-[10px] text-slate-400 capitalize">{{ $campaign['platform'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-xs font-bold text-slate-800">Rp {{ number_format($campaign['spend'], 0, ',', '.') }}</p>
                    <p class="text-[10px] font-bold text-blue-600">CTR {{ number_format($campaign['ctr'],1) }}%</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Recent Invoices (Mock) -->
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-sm">
        <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800"></div>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-slate-800">Recent Invoices</h3>
            <a href="{{ route('invoices.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition" style="background:#f1f5f9; color:#334155;">View All</a>
        </div>
        <div class="space-y-2.5">
            @forelse($mockRecentInvoices as $inv)
            <div class="flex items-center justify-between p-3 rounded-xl transition" style="background:#f8fafc; border:1px solid #f1f5f9;" onmouseover="this.style.background='#fff';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'" onmouseout="this.style.background='#f8fafc';this.style.boxShadow='none'">
                <div class="flex items-center gap-3">
                    @if($inv['platform']=='google') <i class="fa-brands fa-google text-blue-500 text-sm"></i>
                    @elseif($inv['platform']=='meta') <i class="fa-brands fa-facebook text-blue-600 text-sm"></i>
                    @else <i class="fa-brands fa-tiktok text-slate-800 text-sm"></i>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-700">{{ $inv['invoice_number'] }}</p>
                        <p class="text-[10px] text-slate-400">{{ ucfirst($inv['platform']) }} · {{ $inv['period'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-800">Rp {{ number_format($inv['amount'], 0, ',', '.') }}</p>
                    @if($inv['status']=='paid')
                        <span class="text-[10px] font-bold text-emerald-600">Paid</span>
                    @elseif($inv['status']=='pending')
                        <span class="text-[10px] font-bold text-amber-600">Pending</span>
                    @else
                        <span class="text-[10px] font-bold text-slate-400">Draft</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-300">
                <i class="fa-solid fa-file-invoice text-2xl mb-2"></i>
                <p class="text-xs">Belum ada invoice</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Device Pie Chart ──
    const deviceEl = document.getElementById('devicePieChart');
    if (deviceEl) {
        const devices = JSON.parse(deviceEl.dataset.devices);
        new Chart(deviceEl, {
            type: 'doughnut',
            data: {
                labels: devices.map(d => d.device),
                datasets: [{
                    data: devices.map(d => d.percentage),
                    backgroundColor: devices.map(d => d.color),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.raw}%`
                        }
                    }
                }
            }
        });
    }

    // Audience Chart script removed as it is now replaced by progress bars.

    // Tips Slider Auto Rotation
    let currentTip = 0;
    const tips = document.querySelectorAll('.tips-slide');
    const dots = [
        document.getElementById('tipDot1'),
        document.getElementById('tipDot2'),
        document.getElementById('tipDot3')
    ];
    const totalTips = tips.length;
    
    function showTip(index) {
        tips.forEach((tip, i) => {
            tip.style.transform = `translateX(${100 * (i - index)}%)`;
        });
        dots.forEach((dot, i) => {
            dot.className = i === index ? 'w-2 h-2 rounded-full bg-blue-500' : 'w-2 h-2 rounded-full bg-gray-300';
        });
    }
    
    // Auto rotate every 4 seconds
    setInterval(() => {
        currentTip = (currentTip + 1) % totalTips;
        showTip(currentTip);
    }, 4000);
    
    // Manual navigation
    document.getElementById('tipsPrevBtn').addEventListener('click', () => {
        currentTip = (currentTip - 1 + totalTips) % totalTips;
        showTip(currentTip);
    });

    // Greeting Animation
    const gText = document.getElementById('greeting-text');
    if (gText) {
        setTimeout(() => {
            gText.style.opacity = '0';
            gText.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                gText.innerText = 'Selamat Datang,';
                gText.style.opacity = '1';
                gText.style.transform = 'translateY(0)';
            }, 600);
        }, 3000);
    }
});
</script>
@endpush
@endif

<!-- Topup Modal -->
<div id="topupModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('topupModal').classList.add('hidden')"></div>

        <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8 sm:w-full">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-blue-500"></i> Top-up Saldo Cepat
                </h3>
                <button type="button" class="text-slate-400 hover:text-red-500 transition" onclick="document.getElementById('topupModal').classList.add('hidden')">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form action="{{ route('topup.store') }}" method="POST">
                @csrf
                <!-- We add a hidden input to redirect back to dashboard -->
                <input type="hidden" name="redirect_to" value="dashboard">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Pilih Akun Iklan <span class="text-red-500">*</span></label>
                        <select name="ad_account_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($adAccounts as $account)
                                <option value="{{ $account->id }}">{{ ucfirst($account->platform) }} - {{ $account->account_name }} (Saldo: Rp {{ number_format($account->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Nominal Top-up (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                            <input type="number" name="amount" min="10000" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" placeholder="Contoh: 1000000" required>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1.5">Minimal top-up Rp 10.000</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-md shadow-slate-200 transition-transform transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-bolt"></i> Proses Top-up Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
