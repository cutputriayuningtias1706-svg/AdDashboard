@extends('layouts.main')

@section('title', ucfirst($platform) . ' Ads - AdDashboard Pro')
@section('page-title', ucfirst($platform) . ' Ads Dashboard')

@section('content')
<!-- Date Range Picker -->
<div class="fade-up rounded-2xl p-5 mb-5 bg-primary-card" style="box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <form method="GET" action="{{ request()->url() }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom ?? '2025-07-01' }}"
                   min="2025-07-01" max="2025-09-30"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo ?? '2025-09-30' }}"
                   min="2025-07-01" max="2025-09-30"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Bulan</label>
            <select name="month" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">-- Semua --</option>
                <option value="2025-07" {{ ($selectedMonth??'')==='2025-07'?'selected':'' }}>Juli 2025</option>
                <option value="2025-08" {{ ($selectedMonth??'')==='2025-08'?'selected':'' }}>Agustus 2025</option>
                <option value="2025-09" {{ ($selectedMonth??'')==='2025-09'?'selected':'' }}>September 2025</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Pencarian</label>
            <div class="deep-search-container">
                <input type="text" name="search" value="{{ request('search') }}" class="deep-search-input" placeholder="Cari kampanye...">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Tampilkan
            </button>
            <a href="{{ request()->url() }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition" style="background:#f1f5f9;">
                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Platform Header & Tips Side-by-Side -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5 fade-up">
    <!-- Account Info Card -->
    <div class="lg:col-span-2 rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4 h-full bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="flex items-center gap-4">
            @switch($platform)
                @case('google')
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-blue" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                        <i class="fa-brands fa-google text-white text-xl"></i>
                    </div>
                    @break
                @case('meta')
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-blue" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
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

    <!-- Platform Tips Card -->
    <div class="rounded-2xl p-6 relative overflow-hidden h-full flex flex-col justify-center bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#f59e0b,#f97316);"></div>
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#f59e0b,#f97316); box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                <i class="fa-solid fa-lightbulb text-white"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 mb-1">{{ ucfirst($platform) }} Ads Tips</h4>
                @switch($platform)
                    @case('google')
                        <p class="text-[11px] leading-relaxed text-slate-500">Optimalkan Quality Score dengan meningkatkan relevansi iklan dan pengalaman landing page. Gunakan responsive search ads untuk jangkauan yang lebih luas.</p>
                        @break
                    @case('meta')
                        <p class="text-[11px] leading-relaxed text-slate-500">Gunakan Carousel ads untuk menampilkan berbagai produk sekaligus. Uji berbagai format kreatif dan targetkan lookalike audiences untuk hasil terbaik.</p>
                        @break
                    @case('tiktok')
                        <p class="text-[11px] leading-relaxed text-slate-500">Buat konten autentik yang relevan dengan audiens muda. Manfaatkan trending sounds dan hashtag populer untuk meningkatkan visibilitas iklan Anda.</p>
                        @break
                @endswitch
            </div>
        </div>
    </div>
</div>

@if($lastMonthSpend)
<!-- Last Month Spend Comparison -->
<div class="fade-up mb-5 p-4 rounded-2xl flex items-center gap-4" style="background:linear-gradient(135deg,#eff6ff,#f0f7ff);border:1px solid #dbeafe;">
    <i class="fa-solid fa-circle-info text-blue-400 text-lg flex-shrink-0"></i>
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
@php
    $allMetrics = [
        ['key'=>'spend',          'label'=>'Total Spend',     'value'=>'Rp '.number_format($totalSpend, 0, ',', '.'), 'color'=>'#3b82f6', 'icon'=>'fa-wallet',       'bg'=>'rgba(59,130,246,0.1)', 'class'=>'metric-card-blue'],
        ['key'=>'impressions',    'label'=>'Impressions',     'value'=>number_format($totalImpressions),              'color'=>'#64748b', 'icon'=>'fa-eye',          'bg'=>'rgba(100,116,139,0.1)', 'class'=>'metric-card-slate'],
        ['key'=>'clicks',         'label'=>'Clicks',          'value'=>number_format($totalClicks),                   'color'=>'#10b981', 'icon'=>'fa-mouse-pointer', 'bg'=>'rgba(16,185,129,0.1)', 'class'=>'metric-card-emerald'],
        ['key'=>'conversions',    'label'=>'Conversions',     'value'=>number_format($totalConversions),              'color'=>'#f59e0b', 'icon'=>'fa-check-circle', 'bg'=>'rgba(245,158,11,0.1)', 'class'=>'metric-card-amber'],
        ['key'=>'ctr',            'label'=>'CTR',             'value'=>number_format($ctr,2).'%',                     'color'=>'#3b82f6', 'icon'=>'fa-bullseye',     'bg'=>'rgba(59,130,246,0.1)', 'class'=>'metric-card-blue'],
        ['key'=>'cpc',            'label'=>'CPC',             'value'=>'Rp '.number_format($cpc,0,',','.'),           'color'=>'#2563eb', 'icon'=>'fa-coins',        'bg'=>'rgba(37,99,235,0.1)', 'class'=>'metric-card-blue'],
        ['key'=>'cpm',            'label'=>'CPM',             'value'=>'Rp '.number_format($cpm,0,',','.'),           'color'=>'#10b981', 'icon'=>'fa-layer-group',  'bg'=>'rgba(16,185,129,0.1)', 'class'=>'metric-card-emerald'],
        ['key'=>'conversionRate', 'label'=>'Conv. Rate',      'value'=>number_format($conversionRate,2).'%',          'color'=>'#f59e0b', 'icon'=>'fa-filter',       'bg'=>'rgba(245,158,11,0.1)', 'class'=>'metric-card-amber'],
        ['key'=>'frequency',      'label'=>'Frequency',       'value'=>number_format($frequency,1),                   'color'=>'#ec4899', 'icon'=>'fa-repeat',       'bg'=>'rgba(236,72,153,0.1)', 'class'=>'metric-card-pink'],
        ['key'=>'roas',           'label'=>'ROAS',            'value'=>number_format($roas,1).'x',                    'color'=>'#8b5cf6', 'icon'=>'fa-trophy',       'bg'=>'rgba(139,92,246,0.1)', 'class'=>'metric-card-purple'],
    ];
@endphp

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 fade-up">
    @foreach($allMetrics as $m)
    <div class="group metric-card {{ $m['class'] }} rounded-2xl p-4 metric-tab transition-all duration-300 {{ $m['key'] === 'spend' ? 'active-metric-tab' : '' }}" 
         onclick="switchChartMetric('{{ $m['key'] }}', '{{ $m['label'] }}', '{{ $m['color'] }}')"
         data-metric="{{ $m['key'] }}"
         style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.04); cursor:pointer;">
        <div class="flex items-center gap-3">
            @if($m['key'] !== 'spend')
            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 duration-300 metric-icon-container" 
                 style="background:{{ $m['bg'] }}; color:{{ $m['color'] }};">
                <i class="fa-solid {{ $m['icon'] }} text-sm"></i>
            </div>
            @endif
            <div class="text-left">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">{{ $m['label'] }}</p>
                <p class="text-sm font-extrabold truncate" style="color:{{ $m['color'] }}; max-width:{{ $m['key'] === 'spend' ? '100%' : '110px' }};" title="{{ $m['value'] }}">{{ $m['value'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- 7-Day Performance Line Chart -->
@if(!empty($dailyTrend))
<div class="fade-up rounded-2xl p-6 mb-5 bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-base font-bold text-slate-800" id="currentChartTitle">7-Day Performance: Spend</h3>
        </div>
        <div id="chartLegendContainer" class="flex items-center gap-4">
            <!-- Legend will be updated by JS -->
        </div>
    </div>
    <div class="h-72">
        <canvas id="performanceChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
let performanceChart;
const dailyTrendData = @json($dailyTrend);

function switchChartMetric(key, label, color) {
    // Update Active Tab UI
    document.querySelectorAll('.metric-tab').forEach(tab => {
        tab.classList.remove('active-metric-tab');
        if(tab.dataset.metric === key) tab.classList.add('active-metric-tab');
    });

    document.getElementById('currentChartTitle').innerText = `7-Day Performance: ${label}`;
    
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    const rgb = hexToRgb(color);
    gradient.addColorStop(0, `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.15)`);
    gradient.addColorStop(1, `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0)`);

    const dataset = {
        label: label,
        data: dailyTrendData.map(d => d[key]),
        borderColor: color,
        backgroundColor: gradient,
        fill: true,
        tension: 0.5,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: color,
        pointHoverBorderColor: '#fff',
        pointHoverBorderWidth: 3,
    };

    if (performanceChart) {
        performanceChart.data.datasets = [dataset];
        performanceChart.update();
    } else {
        performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyTrendData.map(d => d.date),
                datasets: [dataset]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#64748b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let val = context.parsed.y;
                                if (key === 'spend' || key === 'cpc' || key === 'cpm') {
                                    return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(val);
                                } else if (key === 'ctr' || key === 'conversionRate') {
                                    return label + ': ' + val.toFixed(2) + '%';
                                } else if (key === 'roas') {
                                    return label + ': ' + val.toFixed(1) + 'x';
                                }
                                return label + ': ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } }
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    }
}

function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : {r: 0, g: 0, b: 0};
}

document.addEventListener('DOMContentLoaded', function() {
    switchChartMetric('spend', 'Total Spend', '#3b82f6');
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
        <a href="{{ route('reports.index', ['platform' => $platform]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background: linear-gradient(135deg,#3b82f6,#2563eb);">
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
                    <td class="px-6 py-4 text-right text-sm font-semibold" style="color:#2563eb;">{{ number_format($campaign['ctr'], 2) }}%</td>
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
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-slate-50 border border-slate-100">
            <i class="fa-solid fa-chart-simple text-slate-300 text-2xl"></i>
        </div>
        <p class="text-slate-400 font-medium">No campaigns found for this platform</p>
    </div>
    @endif
</div>
@endsection
