@extends('layouts.main')

@section('title', ucfirst($platform) . ' Ads - AdDashboard Pro')
@section('page-title', ucfirst($platform) . ' Ads Dashboard')

@section('content')
<!-- Date Range Picker -->
<div class="fade-up rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <form method="GET" action="{{ request()->url() }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom ?? '2025-07-01' }}"
                   min="2025-07-01" max="2025-09-30"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo ?? '2025-09-30' }}"
                   min="2025-07-01" max="2025-09-30"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Bulan</label>
            <select name="month" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">-- Semua --</option>
                <option value="2025-07" {{ ($selectedMonth??'')==='2025-07'?'selected':'' }}>Juli 2025</option>
                <option value="2025-08" {{ ($selectedMonth??'')==='2025-08'?'selected':'' }}>Agustus 2025</option>
                <option value="2025-09" {{ ($selectedMonth??'')==='2025-09'?'selected':'' }}>September 2025</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Tampilkan
            </button>
            <a href="{{ request()->url() }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition" style="background:#f1f5f9;">
                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Platform Header (Light) -->
<div class="mb-5 fade-up">
    <div class="rounded-2xl p-5 flex flex-wrap items-center justify-between gap-4" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div class="flex items-center gap-4">
            @switch($platform)
                @case('google')
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-blue" style="background:linear-gradient(135deg,#3b82f6,#6366f1);">
                        <i class="fa-brands fa-google text-white text-xl"></i>
                    </div>
                    @break
                @case('meta')
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-purple" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <i class="fa-brands fa-facebook text-white text-xl"></i>
                    </div>
                    @break
                @case('tiktok')
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-rose" style="background:linear-gradient(135deg,#ec4899,#f43f5e);">
                        <i class="fa-brands fa-tiktok text-white text-xl"></i>
                    </div>
                    @break
            @endswitch
            <div>
                <h2 class="text-base font-bold text-slate-800">{{ $account->account_name }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">ID: {{ $account->account_id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-5">
            <div class="text-right">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Account Balance</p>
                <p class="text-xl font-bold text-slate-800 mt-0.5">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full capitalize" style="background:rgba(16,185,129,0.1);color:#059669;border:1px solid rgba(16,185,129,0.25);">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 badge-active"></span>{{ $account->status }}
            </span>
        </div>
    </div>
</div>

@if($lastMonthSpend)
<!-- Last Month Spend Comparison -->
<div class="fade-up mb-5 p-4 rounded-2xl flex items-center gap-4" style="background:linear-gradient(135deg,#eff6ff,#f5f3ff);border:1px solid #e0e7ff;">
    <i class="fa-solid fa-circle-info text-indigo-400 text-lg flex-shrink-0"></i>
    <p class="text-sm text-slate-600">
        <span class="font-semibold text-slate-800">Total Spend Bulan Lalu:</span>
        Rp {{ number_format($lastMonthSpend, 0, ',', '.') }} —
        @php $diff = $totalSpend - $lastMonthSpend; $pct = $lastMonthSpend > 0 ? abs($diff/$lastMonthSpend*100) : 0; @endphp
        @if($diff >= 0)
            <span class="font-semibold text-emerald-600"><i class="fa-solid fa-arrow-up text-xs mr-1"></i>+{{ number_format($pct,1) }}% dari bulan lalu</span>
        @else
            <span class="font-semibold text-red-500"><i class="fa-solid fa-arrow-down text-xs mr-1"></i>-{{ number_format($pct,1) }}% dari bulan lalu</span>
        @endif
    </p>
</div>
@endif


<!-- Primary Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
    <!-- Total Spend -->
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg,#3b82f6,#6366f1);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Spend</p>
                <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalSpend, 0, ',', '.') }}</p>
            </div>

        </div>
    </div>

    <!-- Impressions -->
    <div class="card-hover fade-up-2 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg,#8b5cf6,#a855f7);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Impressions</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalImpressions) }}</p>
            </div>

        </div>
    </div>

    <!-- Clicks -->
    <div class="card-hover fade-up-3 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg,#10b981,#34d399);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Clicks</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalClicks) }}</p>
            </div>

        </div>
    </div>

    <!-- Conversions -->
    <div class="card-hover fade-up-4 rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg,#f59e0b,#f97316);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Conversions</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalConversions) }}</p>
            </div>

        </div>
    </div>
</div>

<!-- Secondary Performance Metrics -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-5 fade-up">
    @php
    $metrics = [
        ['label'=>'CTR',         'value'=> number_format($ctr,2).'%',                         'color'=>'#3b82f6'],
        ['label'=>'CPC',         'value'=>'Rp '.number_format($cpc,0,',','.'),                'color'=>'#8b5cf6'],
        ['label'=>'CPM',         'value'=>'Rp '.number_format($cpm,0,',','.'),                'color'=>'#10b981'],
        ['label'=>'Conv. Rate',  'value'=> number_format($conversionRate,2).'%',              'color'=>'#f59e0b'],
        ['label'=>'Frequency',   'value'=> number_format($frequency,1),                       'color'=>'#ec4899'],
        ['label'=>'ROAS',        'value'=> number_format($roas,1).'x',                       'color'=>'#6366f1'],
    ];
    @endphp
    @foreach($metrics as $m)
    <div class="card-hover rounded-2xl p-4 text-center" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">{{ $m['label'] }}</p>
        <p class="text-xl font-bold" style="color:{{ $m['color'] }};">{{ $m['value'] }}</p>
    </div>
    @endforeach
</div>

<!-- 7-Day Performance Line Chart -->
@if(!empty($dailyTrend))
<div class="fade-up rounded-2xl p-6 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-base font-bold text-slate-800">7-Day Performance Trend</h3>
            <p class="text-sm text-slate-400 mt-0.5">Spend, Impressions, Clicks & Conversions</p>
        </div>
    </div>
    <div class="h-72">
        <canvas id="performanceChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const dailyTrend = @json($dailyTrend);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyTrend.map(d => d.date),
            datasets: [
                { label: 'Spend (Rp)', data: dailyTrend.map(d => d.spend), borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.08)', fill: true, tension: 0.4, yAxisID: 'y' },
                { label: 'Impressions', data: dailyTrend.map(d => d.impressions), borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.05)', fill: false, tension: 0.4, yAxisID: 'y1' },
                { label: 'Clicks', data: dailyTrend.map(d => d.clicks), borderColor: '#10b981', fill: false, tension: 0.4, yAxisID: 'y1' },
                { label: 'Conversions', data: dailyTrend.map(d => d.conversions), borderColor: '#f59e0b', fill: false, tension: 0.4, yAxisID: 'y1' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { grid: { display: false } },
                y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Spend (Rp)' }, grid: { color: 'rgba(226,232,240,0.6)' } },
                y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Count' }, grid: { drawOnChartArea: false } }
            }
        }
    });
});
</script>
@endpush
@endif

<!-- Campaign Performance Table -->
<div class="fade-up rounded-2xl overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
            <h3 class="text-base font-bold text-slate-800">Campaign Performance</h3>
            <p class="text-xs text-slate-400 mt-0.5">All campaigns for {{ ucfirst($platform) }}</p>
        </div>
        <a href="{{ route('reports.index', ['platform' => $platform]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
            <i class="fa-solid fa-chart-bar text-xs"></i> View Full Report
        </a>
    </div>
    <div class="table-container">
        <table class="w-full min-w-[1200px]">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100" style="background:#f8fafc;">
                    <th class="px-6 py-4 font-semibold sticky-col">Ad Asset</th>
                    <th class="px-6 py-4 font-semibold sticky-col-2">Campaign</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Reach</th>
                    <th class="px-6 py-4 font-semibold text-right">Impr.</th>
                    <th class="px-6 py-4 font-semibold text-right">Freq.</th>
                    <th class="px-6 py-4 font-semibold text-right">Clicks</th>
                    <th class="px-6 py-4 font-semibold text-right">CTR</th>
                    <th class="px-6 py-4 font-semibold text-right">CPM</th>
                    <th class="px-6 py-4 font-semibold text-right">CPC</th>
                    <th class="px-6 py-4 font-semibold text-right">Spend</th>
                    <th class="px-6 py-4 font-semibold text-right">Conv.</th>
                    <th class="px-6 py-4 font-semibold text-right">Cost/Conv.</th>
                    <th class="px-6 py-4 font-semibold text-right">Conv. Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $campaign)
                <tr class="table-row-hover border-b border-slate-50 group">
                    <td class="px-6 py-4 sticky-col">
                        <img src="{{ $campaign['thumbnail_url'] }}" 
                             class="ad-asset-thumb" 
                             alt="Ad Asset"
                             onerror="this.src='https://via.placeholder.com/128x170?text=Ad+Asset'"
                             onclick="openAdModal('{{ $campaign['video_id'] }}', '{{ $campaign['name'] }}', '{{ ucfirst($platform) }}', 'Rp {{ number_format($campaign['spend'], 0, ',', '.') }}', '{{ number_format($campaign['conversions']) }}')">
                    </td>
                    <td class="px-6 py-4 sticky-col-2">
                        <p class="text-sm font-semibold text-slate-800 truncate max-w-[180px]" title="{{ $campaign['name'] }}">{{ $campaign['name'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">ID: {{ $campaign['id'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($campaign['status'] == 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(16,185,129,0.1); color:#059669;">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 badge-active"></span>Active
                            </span>
                        @elseif($campaign['status'] == 'completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(59,130,246,0.1); color:#2563eb;">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Completed
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize" style="background:#f1f5f9; color:#64748b;">{{ $campaign['status'] }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($campaign['reach']) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($campaign['impressions']) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($campaign['frequency'], 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($campaign['clicks']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold" style="color:#3b82f6;">{{ number_format($campaign['ctr'], 2) }}%</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">Rp {{ number_format($campaign['cpm'] ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">Rp {{ number_format($campaign['cpc'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-800">Rp {{ number_format($campaign['spend'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">{{ number_format($campaign['conversions']) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">Rp {{ number_format($campaign['cost_per_conv'] ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold" style="color:#10b981;">{{ number_format($campaign['conversionRate'], 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($campaigns->isEmpty())
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f1f5f9;">
            <i class="fa-solid fa-chart-simple text-slate-300 text-2xl"></i>
        </div>
        <p class="text-slate-400 font-medium">No campaigns found for this platform</p>
    </div>
    @endif
</div>

<!-- Platform Tips -->
<div class="mt-5 fade-up rounded-2xl p-6" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#f59e0b,#f97316);"></div>
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#f59e0b,#f97316); box-shadow:0 4px 12px rgba(245,158,11,0.3);">
            <i class="fa-solid fa-lightbulb text-white"></i>
        </div>
        <div>
            <h4 class="font-bold text-slate-800 mb-1">{{ ucfirst($platform) }} Ads Tips</h4>
            @switch($platform)
                @case('google')
                    <p class="text-sm text-slate-500">Optimalkan Quality Score dengan meningkatkan relevansi iklan dan pengalaman landing page. Gunakan responsive search ads untuk jangkauan yang lebih luas.</p>
                    @break
                @case('meta')
                    <p class="text-sm text-slate-500">Gunakan Carousel ads untuk menampilkan berbagai produk sekaligus. Uji berbagai format kreatif dan targetkan lookalike audiences untuk hasil terbaik.</p>
                    @break
                @case('tiktok')
                    <p class="text-sm text-slate-500">Buat konten autentik yang relevan dengan audiens muda. Manfaatkan trending sounds dan hashtag populer untuk meningkatkan visibilitas iklan Anda.</p>
                    @break
            @endswitch
        </div>
    </div>
</div>
@endsection
