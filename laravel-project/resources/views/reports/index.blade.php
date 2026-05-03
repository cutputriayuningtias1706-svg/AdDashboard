@extends('layouts.main')

@section('title', 'Reports - Ad Dashboard')

@section('shimmer-content')
    <div class="shimmer-item w-1/4 mb-4"></div>
    <div class="shimmer-item w-full h-16 mb-6"></div> <!-- Filter Bar -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="shimmer-item h-24"></div>
        <div class="shimmer-item h-24"></div>
        <div class="shimmer-item h-24"></div>
        <div class="shimmer-item h-24"></div>
    </div>
    <div class="shimmer-item w-full h-96"></div> <!-- Table -->
@endsection
@section('page-title', 'Ad Performance Reports')

@section('content')
<!-- Platform Nav Pills -->
<div class="fade-up rounded-2xl p-4 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <nav class="flex flex-wrap gap-2">
        @php $pills = [['key'=>'all','label'=>'All Platforms','icon'=>'fa-layer-group'],['key'=>'google','label'=>'Google Ads','icon'=>'fa-brands fa-google'],['key'=>'meta','label'=>'Meta Ads','icon'=>'fa-brands fa-facebook'],['key'=>'tiktok','label'=>'TikTok Ads','icon'=>'fa-brands fa-tiktok']]; @endphp
        @foreach($pills as $pill)
        @php $active = (request('platform') == $pill['key']) || (!request('platform') && $pill['key']=='all'); @endphp
        <a href="{{ route('reports.index', array_merge(request()->except('platform'), ['platform' => $pill['key']])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition"
           style="{{ $active ? 'background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;box-shadow:0 4px 12px rgba(99,102,241,0.3);' : 'background:#f1f5f9;color:#64748b;' }}">
            <i class="fa-solid {{ $pill['icon'] }} text-xs"></i> {{ $pill['label'] }}
        </a>
        @endforeach
    </nav>
</div>

<!-- Filter Bar -->
<div class="fade-up rounded-2xl p-6 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        @if(request('platform') && request('platform') != 'all')
        <input type="hidden" name="platform" value="{{ request('platform') }}">
        @endif
        <div class="w-44">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Periode Bulan</label>
            <select name="month" onchange="this.form.submit()" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">-- Semua Bulan --</option>
                <option value="2025-07" {{ request('month')=='2025-07'?'selected':'' }}>Juli 2025</option>
                <option value="2025-08" {{ request('month')=='2025-08'?'selected':'' }}>Agustus 2025</option>
                <option value="2025-09" {{ request('month')=='2025-09'?'selected':'' }}>September 2025</option>
            </select>
        </div>
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Campaign</label>
            <select name="campaign" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="all">All Campaigns</option>
                @foreach($campaigns as $campaign)
                <option value="{{ $campaign->id }}" {{ request('campaign')==$campaign->id?'selected':'' }}>{{ $campaign->campaign_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="fa-solid fa-filter text-xs"></i> Filter
            </button>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 transition hover:bg-slate-200" style="background:#f1f5f9;">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
    @php
    $cards = [
        ['label'=>'Total Spend','value'=>'Rp '.number_format($totalSpend,0,',','.'),'icon'=>'fa-dollar-sign','grad'=>'linear-gradient(135deg,#3b82f6,#6366f1)','glow'=>'rgba(59,130,246,0.3)'],
        ['label'=>'Total Impressions','value'=>number_format($totalImpressions),'icon'=>'fa-eye','grad'=>'linear-gradient(135deg,#8b5cf6,#a855f7)','glow'=>'rgba(139,92,246,0.3)'],
        ['label'=>'Total Clicks','value'=>number_format($totalClicks),'icon'=>'fa-pointer','grad'=>'linear-gradient(135deg,#10b981,#34d399)','glow'=>'rgba(16,185,129,0.3)'],
        ['label'=>'Total Conversions','value'=>number_format($totalConversions),'icon'=>'fa-bullseye','grad'=>'linear-gradient(135deg,#f59e0b,#f97316)','glow'=>'rgba(245,158,11,0.3)'],
    ];
    @endphp
    @foreach($cards as $i => $card)
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06); animation-delay:{{ $i*0.08 }}s;">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background:{{ $card['grad'] }};"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">{{ $card['label'] }}</p>
                <p class="text-xl lg:text-2xl font-bold text-slate-800 whitespace-nowrap tracking-tight">{{ $card['value'] }}</p>
            </div>

        </div>
    </div>
    @endforeach
</div>

<!-- Export Buttons -->
<div class="flex justify-end gap-3 mb-5 fade-up">
    <a href="{{ route('reports.export.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:linear-gradient(135deg,#10b981,#059669); box-shadow:0 4px 12px rgba(16,185,129,0.3);">
        <i class="fa-solid fa-file-excel text-xs"></i> Export Excel
    </a>
    <a href="{{ route('reports.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:linear-gradient(135deg,#f43f5e,#e11d48); box-shadow:0 4px 12px rgba(244,63,94,0.3);">
        <i class="fa-solid fa-file-pdf text-xs"></i> Export PDF
    </a>
</div>

<!-- Reports Table -->
<div class="fade-up rounded-2xl overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="table-container">
        <table class="w-full min-w-[1200px]">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100" style="background:#f8fafc;">
                    <th class="px-6 py-4 font-semibold sticky-col">Ad Asset</th>
                    <th class="px-6 py-4 font-semibold sticky-col-2">Campaign</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Platform</th>
                    <th class="px-6 py-4 text-right">Reach</th>
                    <th class="px-6 py-4 text-right">Impr.</th>
                    <th class="px-6 py-4 text-right">Freq.</th>
                    <th class="px-6 py-4 text-right">Clicks</th>
                    <th class="px-6 py-4 text-right">Conversions</th>
                    <th class="px-6 py-4 text-right">Spend</th>
                    <th class="px-6 py-4 text-right">CTR</th>
                    <th class="px-6 py-4 text-right">CPC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr class="table-row-hover border-b border-slate-50 group">
                    <td class="px-6 py-4 sticky-col">
                        <img src="{{ $report['thumbnail_url'] }}" 
                             class="ad-asset-thumb" 
                             alt="Ad Asset"
                             onerror="this.src='https://via.placeholder.com/128x170?text=Ad+Asset'"
                             onclick="openAdModal('{{ $report['video_id'] }}', '{{ $report['campaign'] }}', '{{ ucfirst($report['platform']) }}', 'Rp {{ number_format($report['spend'], 0, ',', '.') }}', '{{ number_format($report['conversions']) }}')">
                    </td>
                    <td class="px-6 py-4 sticky-col-2">
                        <p class="text-sm font-semibold text-slate-700 truncate max-w-[180px]" title="{{ $report['campaign'] }}">{{ $report['campaign'] }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $report['record_date'] }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-sm font-semibold text-slate-700 inline-flex items-center gap-2">
                            @if($report['platform']=='google') <i class="fa-brands fa-google text-blue-500"></i>
                            @elseif($report['platform']=='meta') <i class="fa-brands fa-facebook text-indigo-500"></i>
                            @else <i class="fa-brands fa-tiktok text-pink-500"></i>
                            @endif
                            {{ ucfirst($report['platform']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">{{ number_format($report['impressions'] / 1.15) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">{{ number_format($report['impressions']) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">1.15</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">{{ number_format($report['clicks']) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">{{ number_format($report['conversions']) }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800 text-right">Rp {{ number_format($report['spend'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-right" style="color:#3b82f6;">{{ $report['impressions'] > 0 ? number_format(($report['clicks']/$report['impressions'])*100,2) : 0 }}%</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">Rp {{ $report['clicks'] > 0 ? number_format($report['spend']/$report['clicks'], 0, ',', '.') : 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f1f5f9;">
                            <i class="fa-solid fa-inbox text-slate-300 text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-medium">No reports found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $reports->links() }}</div>
</div>
@endsection

