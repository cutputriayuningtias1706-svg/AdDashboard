@extends('layouts.main')

@section('title', 'Dashboard - Ad Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Month Picker -->
<div class="bg-gradient-to-r from-slate-50 via-white to-slate-100 rounded-3xl shadow-xl border border-slate-200 p-5 mb-6">
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
        <div class="px-5 py-4 rounded-2xl text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 6px 20px rgba(99,102,241,0.35);">
            <p class="text-xs uppercase tracking-widest" style="color:rgba(199,210,254,0.85);">Total 3 Bulan</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($monthlySpendingSummary[3]['value'], 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden" style="background:linear-gradient(135deg,#eff6ff,#fff); border:1px solid #dbeafe;">
            <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[0]['month'] }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($monthlySpendingSummary[0]['value'], 0, ',', '.') }}</p>
        </div>
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden" style="background:linear-gradient(135deg,#f5f3ff,#fff); border:1px solid #ede9fe;">
            <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#8b5cf6,#a855f7);"></div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[1]['month'] }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($monthlySpendingSummary[1]['value'], 0, ',', '.') }}</p>
        </div>
        <div class="card-hover p-5 rounded-2xl relative overflow-hidden" style="background:linear-gradient(135deg,#ecfdf5,#fff); border:1px solid #d1fae5;">
            <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $monthlySpendingSummary[2]['month'] }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-3">Rp {{ number_format($monthlySpendingSummary[2]['value'], 0, ',', '.') }}</p>
        </div>
    </div>
</div>

@if(!$selectedMonth)
<div class="fade-up rounded-2xl p-10 text-center mb-5" style="background:linear-gradient(135deg,#f8faff,#f3f4f6); border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.05);">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 6px 20px rgba(99,102,241,0.3);">
        <i class="fa-solid fa-calendar text-white text-2xl"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">Pilih Periode Waktu</h3>
    <p class="text-slate-400 max-w-xl mx-auto text-sm">Silakan pilih bulan untuk melihat data iklan yang lebih lengkap dan performa kampanye secara real-time.</p>
</div>
@else
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
    <!-- Total Spend -->
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Spend</p>
                <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalSpend, 0, ',', '.') }}</p>
                <p class="text-xs mt-2 font-semibold" style="color:#10b981;"><i class="fa-solid fa-arrow-trend-up mr-1"></i>{{ number_format($spendChange, 0) }}% vs yesterday</p>
            </div>
            <div class="w-13 h-13 w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-blue" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                <i class="fa-solid fa-dollar-sign text-white text-xl"></i>
            </div>
        </div>
    </div>
    <!-- Total Impressions -->
    <div class="card-hover fade-up-2 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#8b5cf6,#a855f7);"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Impressions</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalImpressions, 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-purple" style="background:linear-gradient(135deg,#8b5cf6,#a855f7);">
                <i class="fa-solid fa-eye text-white text-xl"></i>
            </div>
        </div>
    </div>
    <!-- Total Clicks -->
    <div class="card-hover fade-up-3 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Clicks</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalClicks, 0) }}</p>
                <p class="text-xs mt-2 font-semibold text-blue-500">CTR: {{ number_format($ctr, 1) }}%</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-green" style="background:linear-gradient(135deg,#10b981,#34d399);">
                <i class="fa-solid fa-pointer text-white text-xl"></i>
            </div>
        </div>
    </div>
    <!-- Total Conversions -->
    <div class="card-hover fade-up-4 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#f59e0b,#f97316);"></div>
        <div class="flex items-center justify-between pt-1">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Conversions</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalConversions, 0) }}</p>
                <p class="text-xs mt-2 text-slate-400 font-medium">CPC: Rp {{ number_format($cpc, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-amber" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                <i class="fa-solid fa-bullseye text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Optimization Score & Top Campaign Performance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
<!-- Optimization Score with Tips Slider -->
    <div class="card-hover fade-up relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-slate-800">Optimization Score</h3>
                <p class="text-sm text-slate-500 mt-1">Nilai efisiensi kampanye berdasarkan metrik utama.</p>
            </div>
            <button id="tipsPrevBtn" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition shadow-sm">
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
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#ec4899,#8b5cf6);"></div>
        <h3 class="text-base font-bold text-slate-800 mb-5">Top Campaign Performance</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100" style="background:#f8fafc;">
                        <th class="py-3 px-2">Campaign</th>
                        <th class="py-3 px-2">Platform</th>
                        <th class="py-3 px-2 text-right">Spend</th>
                        <th class="py-3 px-2 text-right">CTR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCampaigns as $campaign)
                    <tr class="table-row-hover border-b border-slate-50">
                        <td class="py-3 px-2">
                            <p class="text-sm font-semibold text-slate-700 truncate max-w-[140px]">{{ $campaign['name'] }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <span class="capitalize text-sm inline-flex items-center gap-2 text-slate-600">
                                @switch($campaign['platform'])
                                    @case('google') <i class="fa-brands fa-google text-blue-500"></i> @break
                                    @case('meta') <i class="fa-brands fa-facebook text-indigo-500"></i> @break
                                    @case('tiktok') <i class="fa-brands fa-tiktok text-pink-500"></i> @break
                                @endswitch
                                {{ $campaign['platform'] }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-right text-sm font-bold text-slate-800">Rp {{ number_format($campaign['spend'], 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-right text-sm font-semibold" style="color:#6366f1;">{{ number_format($campaign['ctr'], 1) }}%</td>
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
    <div class="card-hover fade-up relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
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
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#8b5cf6,#ec4899);"></div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-bold text-slate-800">Audiens</h3>
            <div class="flex items-center gap-3 text-xs font-semibold">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#3b82f6;"></span>Pria 58%</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#ec4899;"></span>Wanita 42%</span>
            </div>
        </div>
        <div style="height:150px;">
            <canvas id="audienceChart" data-audience='{{ json_encode($audienceData) }}'></canvas>
        </div>
    </div>
    <!-- Top 5 Location Indonesia -->
    <div class="card-hover fade-up-3 relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#10b981,#34d399);"></div>
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
                $colors = ['#6366f1','#3b82f6','#10b981','#f59e0b','#ec4899'];
                $bgColors = ['#eef2ff','#eff6ff','#ecfdf5','#fffbeb','#fdf4ff'];
                $col = $colors[$i] ?? '#6366f1';
                $bg  = $bgColors[$i] ?? '#eef2ff';
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
    <div class="card-hover fade-up relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#6366f1,#3b82f6);"></div>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-slate-800">Recent Campaigns</h3>
            <a href="{{ route('reports.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition" style="background:#eff6ff; color:#3b82f6;">View All</a>
        </div>
        <div class="space-y-2.5">
            @foreach($topCampaigns->take(5) as $i => $campaign)
            <div class="flex items-center justify-between p-3 rounded-xl transition" style="background:#f8fafc; border:1px solid #f1f5f9;" onmouseover="this.style.background='#fff';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'" onmouseout="this.style.background='#f8fafc';this.style.boxShadow='none'">
                <div class="flex items-center min-w-0 gap-3">
                    <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold flex-shrink-0" style="background:#eef2ff;color:#6366f1;">{{ $i+1 }}</span>
                    @switch($campaign['platform'])
                        @case('google')
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff;">
                                <i class="fa-brands fa-google text-blue-600 text-xs"></i>
                            </div> @break
                        @case('meta')
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eef2ff;">
                                <i class="fa-brands fa-facebook text-indigo-600 text-xs"></i>
                            </div> @break
                        @case('tiktok')
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf2f8;">
                                <i class="fa-brands fa-tiktok text-pink-600 text-xs"></i>
                            </div> @break
                    @endswitch
                    <p class="text-sm font-semibold text-slate-700 truncate max-w-[150px]">{{ $campaign['name'] }}</p>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p class="text-xs font-bold text-slate-800">Rp {{ number_format($campaign['spend'], 0, ',', '.') }}</p>
                    <p class="text-[10px] font-semibold" style="color:#6366f1;">CTR {{ number_format($campaign['ctr'],1) }}%</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Recent Invoices (Mock) -->
    <div class="card-hover fade-up-2 relative overflow-hidden rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#8b5cf6,#ec4899);"></div>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-bold text-slate-800">Recent Invoices</h3>
            <a href="{{ route('invoices.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition" style="background:#f5f3ff; color:#8b5cf6;">View All</a>
        </div>
        <div class="space-y-2.5">
            @forelse($mockRecentInvoices as $inv)
            <div class="flex items-center justify-between p-3 rounded-xl transition" style="background:#f8fafc; border:1px solid #f1f5f9;" onmouseover="this.style.background='#fff';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'" onmouseout="this.style.background='#f8fafc';this.style.boxShadow='none'">
                <div class="flex items-center gap-3">
                    @if($inv['platform']=='google') <i class="fa-brands fa-google text-blue-500 text-sm"></i>
                    @elseif($inv['platform']=='meta') <i class="fa-brands fa-facebook text-indigo-500 text-sm"></i>
                    @else <i class="fa-brands fa-tiktok text-pink-500 text-sm"></i>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-700">{{ $inv['invoice_number'] }}</p>
                        <p class="text-[10px] text-slate-400">{{ ucfirst($inv['platform']) }} · {{ $inv['period'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-800">Rp {{ number_format($inv['amount'], 0, ',', '.') }}</p>
                    @if($inv['status']=='paid')
                        <span class="text-[10px] font-semibold" style="color:#10b981;">Paid</span>
                    @elseif($inv['status']=='pending')
                        <span class="text-[10px] font-semibold" style="color:#d97706;">Pending</span>
                    @else
                        <span class="text-[10px] font-semibold text-slate-400">Draft</span>
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

    // ── Audience (Gender & Age) Grouped Bar Chart ──
    const audienceEl = document.getElementById('audienceChart');
    if (audienceEl) {
        const aud = JSON.parse(audienceEl.dataset.audience);
        new Chart(audienceEl, {
            type: 'bar',
            data: {
                labels: aud.ageGroups.map(a => a.label),
                datasets: [
                    {
                        label: 'Pria',
                        data: aud.ageGroups.map(a => a.male),
                        backgroundColor: '#3b82f6',
                        borderRadius: 5,
                        borderSkipped: false,
                        barPercentage: 0.55,
                    },
                    {
                        label: 'Wanita',
                        data: aud.ageGroups.map(a => a.female),
                        backgroundColor: '#ec4899',
                        borderRadius: 5,
                        borderSkipped: false,
                        barPercentage: 0.55,
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Make the bar chart horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}%` }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: { color: 'rgba(226,232,240,0.5)' },
                        ticks: { font: { size: 10 }, callback: v => v + '%' },
                        max: 30,
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

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
});
</script>
@endpush
@endif
@endsection
