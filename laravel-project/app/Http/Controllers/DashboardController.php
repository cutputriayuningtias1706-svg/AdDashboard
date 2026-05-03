<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use App\Models\AdSpendingRecord;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\TopupBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get month filter from request (format: YYYY-MM)
        $selectedMonth = $request->month;

        // Mock spending for Jul–Sep 2025 (so dashboard never shows 0 when data exists in reality)
        $mockMonthlySpending = [
            '2025-07' => [
                'spend' => 2754134170,
                'impressions' => 22000000,
                'clicks' => 1100000,
                'conversions' => 55000,
                'spendChange' => 8.6,
            ],
            '2025-08' => [
                'spend' => 6008000000,
                'impressions' => 28000000,
                'clicks' => 1500000,
                'conversions' => 72000,
                'spendChange' => 12.4,
            ],
            '2025-09' => [
                'spend' => 5507865865,
                'impressions' => 26000000,
                'clicks' => 1420000,
                'conversions' => 68000,
                'spendChange' => 7.1,
            ],
        ];

        $monthlySpendingSummary = [
            ['month' => 'Juli 2025', 'value' => 2754134170],
            ['month' => 'Agustus 2025', 'value' => 6008000000],
            ['month' => 'September 2025', 'value' => 5507865865],
            ['month' => 'Total (3 Bulan)', 'value' => 14270000035],
        ];

        // Build query with optional date filter
        $spendingQuery = AdSpendingRecord::query();

        if ($selectedMonth) {
            $spendingQuery->whereMonth('record_date', $selectedMonth)
                ->whereYear('record_date', $selectedMonth);
        }

        // Get stats
        $totalSpend = $selectedMonth ? $spendingQuery->sum('spend') : 0;
        $totalImpressions = $selectedMonth ? (clone $spendingQuery)->sum('impressions') : 0;
        $totalClicks = $selectedMonth ? (clone $spendingQuery)->sum('clicks') : 0;
        $totalConversions = $selectedMonth ? (clone $spendingQuery)->sum('conversions') : 0;

        // If the selected month is in our mock dataset, override metrics to keep dashboard natural (no 0)
        if ($selectedMonth && array_key_exists($selectedMonth, $mockMonthlySpending)) {
            $totalSpend = $mockMonthlySpending[$selectedMonth]['spend'];
            $totalImpressions = $mockMonthlySpending[$selectedMonth]['impressions'];
            $totalClicks = $mockMonthlySpending[$selectedMonth]['clicks'];
            $totalConversions = $mockMonthlySpending[$selectedMonth]['conversions'];
        }

        $ctr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
        $cpc = $totalClicks > 0 ? $totalSpend / $totalClicks : 0;

        // Get date range of data for the selected month
        $spendChange = 0;
        $todaySpend = 0;

        if ($selectedMonth) {
            $monthRecords = clone $spendingQuery;
            $minDate = (clone $monthRecords)->min('record_date');
            $maxDate = (clone $monthRecords)->max('record_date');

            // Compare first and last day of selected month (DB-driven)
            $firstDateSpend = AdSpendingRecord::whereDate('record_date', $minDate)->sum('spend') ?: 0;
            $lastDateSpend = AdSpendingRecord::whereDate('record_date', $maxDate)->sum('spend') ?: 0;
            $spendChange = $firstDateSpend > 0 ? (($lastDateSpend - $firstDateSpend) / $firstDateSpend) * 100 : 0;
            $todaySpend = $lastDateSpend;

            // If we have mock data for this month, use its provided realistic spendChange %
            if (array_key_exists($selectedMonth, $mockMonthlySpending)) {
                $spendChange = $mockMonthlySpending[$selectedMonth]['spendChange'];
                $todaySpend = $mockMonthlySpending[$selectedMonth]['spend'];
            }
        }

        // Platform breakdown - only show if month selected (not critical for current UI, but keep)
        if ($selectedMonth) {
            $platformStats = AdAccount::with([
                'campaigns' => function ($q) use ($selectedMonth) {
                    $q->whereHas('spendingRecords', function ($sq) use ($selectedMonth) {
                        $sq->whereMonth('record_date', $selectedMonth)->whereYear('record_date', $selectedMonth);
                    });
                },
                'campaigns.spendingRecords' => function ($q) use ($selectedMonth) {
                    $q->whereMonth('record_date', $selectedMonth)->whereYear('record_date', $selectedMonth);
                },
            ])->get()->map(function ($account) {
                $spend = $account->campaigns->flatMap->spendingRecords->sum('spend');
                $impressions = $account->campaigns->flatMap->spendingRecords->sum('impressions');
                $clicks = $account->campaigns->flatMap->spendingRecords->sum('clicks');

                return [
                    'platform' => $account->platform,
                    'account_name' => $account->account_name,
                    'spend' => $spend,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'ctr' => $impressions > 0 ? ($clicks / $impressions) * 100 : 0,
                ];
            });
        } else {
            $platformStats = collect([]);
        }

        // Recent campaigns (DB-driven, used in some sections)
        $recentCampaigns = Campaign::with('adAccount')->latest()->take(5)->get();

        // Spending trend - last 7 days of selected month (kept from existing logic)
        $spendingTrend = [];
        if ($selectedMonth) {
            $year = substr($selectedMonth, 0, 4);
            $month = substr($selectedMonth, 5, 2);
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;

            for ($i = 6; $i >= 0; $i--) {
                $day = min($daysInMonth - $i, $daysInMonth);
                $date = Carbon::create($year, $month, $day);
                $spend = AdSpendingRecord::whereDate('record_date', $date)->sum('spend');
                $spendingTrend[] = ['date' => $date->format('M d'), 'spend' => $spend];
            }
        }

        // Recent invoices
        $recentInvoices = Invoice::with('adAccount')->latest()->take(5)->get();

        // Total balance from topups
        $totalTopup = TopupBalance::where('status', 'completed')->sum('total_amount');

        // Active campaigns count
        $activeCampaigns = Campaign::where('status', 'active')->count();

        // Optimization Score (mock calculation based on metrics)
        $optimizationScore = $this->calculateOptimizationScore($totalSpend, $totalClicks, $totalConversions, $ctr);

        // Top Campaign Performance (DB-driven; may be empty in demo)
        if ($selectedMonth) {
            $topCampaigns = Campaign::with([
                'adAccount',
                'spendingRecords' => function ($q) use ($selectedMonth) {
                    $q->whereMonth('record_date', $selectedMonth)->whereYear('record_date', $selectedMonth);
                },
            ])->get()->map(function ($campaign) {
                $spend = $campaign->spendingRecords->sum('spend');
                $clicks = $campaign->spendingRecords->sum('clicks');
                $conversions = $campaign->spendingRecords->sum('conversions');
                $impressions = $campaign->spendingRecords->sum('impressions');

                return [
                    'id' => $campaign->id,
                    'name' => $campaign->campaign_name,
                    'platform' => $campaign->adAccount->platform,
                    'status' => $campaign->status,
                    'spend' => $spend,
                    'clicks' => $clicks,
                    'conversions' => $conversions,
                    'impressions' => $impressions,
                    'ctr' => $impressions > 0 ? ($clicks / $impressions) * 100 : 0,
                    'conversion_rate' => $clicks > 0 ? ($conversions / $clicks) * 100 : 0,
                ];
            })->sortByDesc('spend')->take(5)->values();
        } else {
            $topCampaigns = collect([]);
        }

        // ── Device Pie Chart (mock) ──
        $deviceStats = [
            ['device'=>'Mobile',  'percentage'=>65, 'clicks'=>(int)($totalClicks*0.65), 'color'=>'#6366f1'],
            ['device'=>'Desktop', 'percentage'=>25, 'clicks'=>(int)($totalClicks*0.25), 'color'=>'#3b82f6'],
            ['device'=>'Tablet',  'percentage'=>10, 'clicks'=>(int)($totalClicks*0.10), 'color'=>'#10b981'],
        ];

        // ── Gender & Age Audience (replaces dayOfWeek spend) ──
        $audienceData = [
            'gender' => [
                ['label'=>'Pria',   'value'=>58, 'color'=>'#3b82f6'],
                ['label'=>'Wanita', 'value'=>42, 'color'=>'#ec4899'],
            ],
            'ageGroups' => [
                ['label'=>'18–24', 'male'=>18, 'female'=>14],
                ['label'=>'25–34', 'male'=>22, 'female'=>18],
                ['label'=>'35–44', 'male'=>12, 'female'=>8],
                ['label'=>'45–54', 'male'=>4,  'female'=>2],
                ['label'=>'55+',   'male'=>2,  'female'=>0],
            ],
        ];
        // Keep dayOfWeekPerformance for backward compat (unused now but still passed)
        $dayOfWeekPerformance = [];

        // ── Top 5 Location Performance (Indonesia) ──
        $locationStats = [
            ['city'=>'Jakarta',  'clicks'=>(int)($totalClicks*0.38),'spend'=>(int)($totalSpend*0.38)],
            ['city'=>'Surabaya', 'clicks'=>(int)($totalClicks*0.18),'spend'=>(int)($totalSpend*0.18)],
            ['city'=>'Bandung',  'clicks'=>(int)($totalClicks*0.14),'spend'=>(int)($totalSpend*0.14)],
            ['city'=>'Medan',    'clicks'=>(int)($totalClicks*0.12),'spend'=>(int)($totalSpend*0.12)],
            ['city'=>'Makassar', 'clicks'=>(int)($totalClicks*0.09),'spend'=>(int)($totalSpend*0.09)],
        ];
        $maxLocationClicks = max(array_column($locationStats, 'clicks')) ?: 1;

        // ── Top 5 Campaign Performance (mock – aligned to selected month) ──
        $mockCampaigns5 = [
            '2025-07' => [
                ['name'=>'YG-度衛星-Indosaku – Search Growth – Google',     'platform'=>'google','spend'=>635250000,'impressions'=>5082000,'clicks'=>254100,'ctr'=>5.0, 'video_id' => '7632512609094225173'],
                ['name'=>'YG-度衛星-Indosaku – Engagement Boost – Meta',    'platform'=>'meta',  'spend'=>522500000,'impressions'=>4702000,'clicks'=>176800,'ctr'=>3.8, 'video_id' => '7631151504791014676'],
                ['name'=>'YG-度衛星-Indosaku – Brand Protection – Google',  'platform'=>'google','spend'=>346500000,'impressions'=>2772000,'clicks'=>138600,'ctr'=>5.0, 'video_id' => '7629681163761634581'],
                ['name'=>'YG-度衛星-Indosaku – Awareness Spark – TikTok',   'platform'=>'tiktok','spend'=>249360000,'impressions'=>2991000,'clicks'=>99720,'ctr'=>3.3, 'video_id' => '7628540027043171605'],
                ['name'=>'YG-度衛星-Indosaku – Sales Campaign – Meta',      'platform'=>'meta',  'spend'=>174175000,'impressions'=>1394000,'clicks'=>58200,'ctr'=>4.2, 'video_id' => '7628066934608661768'],
            ],
            '2025-08' => [
                ['name'=>'YG-度衛星-Indosaku – Search Growth – Google',     'platform'=>'google','spend'=>1386000000,'impressions'=>11088000,'clicks'=>554400,'ctr'=>5.0, 'video_id' => '7626959883807542546'],
                ['name'=>'YG-度衛星-Indosaku – Engagement Boost – Meta',    'platform'=>'meta',  'spend'=>1140000000,'impressions'=>10260000,'clicks'=>385400,'ctr'=>3.8, 'video_id' => '7626581001673674005'],
                ['name'=>'YG-度衛星-Indosaku – Brand Protection – Google',  'platform'=>'google','spend'=>756000000, 'impressions'=>6048000, 'clicks'=>302400,'ctr'=>5.0, 'video_id' => '7625303208453360903'],
                ['name'=>'YG-度衛星-Indosaku – Awareness Spark – TikTok',   'platform'=>'tiktok','spend'=>543600000, 'impressions'=>6523000, 'clicks'=>217400,'ctr'=>3.3, 'video_id' => '7623984937242266896'],
                ['name'=>'YG-度衛星-Indosaku – Video Leads – TikTok',       'platform'=>'tiktok','spend'=>423800000, 'impressions'=>4238000, 'clicks'=>152400,'ctr'=>3.6, 'video_id' => '7622886941368782097'],
            ],
            '2025-09' => [
                ['name'=>'YG-度衛星-Indosaku – Search Growth – Google',     'platform'=>'google','spend'=>1270500000,'impressions'=>10164000,'clicks'=>508200,'ctr'=>5.0, 'video_id' => '7632512609094225173'],
                ['name'=>'YG-度衛星-Indosaku – Engagement Boost – Meta',    'platform'=>'meta',  'spend'=>1052500000,'impressions'=>9472500, 'clicks'=>355000,'ctr'=>3.8, 'video_id' => '7631151504791014676'],
                ['name'=>'YG-度衛星-Indosaku – Brand Protection – Google',  'platform'=>'google','spend'=>693000000, 'impressions'=>5544000, 'clicks'=>277200,'ctr'=>5.0, 'video_id' => '7629681163761634581'],
                ['name'=>'YG-度衛星-Indosaku – Awareness Spark – TikTok',   'platform'=>'tiktok','spend'=>491787000, 'impressions'=>5900000, 'clicks'=>196700,'ctr'=>3.3, 'video_id' => '7628540027043171605'],
                ['name'=>'YG-度衛星-Indosaku – Sales Campaign – Meta',      'platform'=>'meta',  'spend'=>350000000, 'impressions'=>2800000, 'clicks'=>117600,'ctr'=>4.2, 'video_id' => '7628066934608661768'],
            ],
        ];
        if ($selectedMonth && isset($mockCampaigns5[$selectedMonth])) {
            $topCampaigns = collect($mockCampaigns5[$selectedMonth]);
        } elseif ($selectedMonth) {
            $topCampaigns = collect([]);
        } else {
            // Show all-time top 5 (Jul–Sep aggregate)
            $topCampaigns = collect([
                ['name'=>'YG-度衛星-Indosaku – Search Growth – Google',    'platform'=>'google','spend'=>3291750000,'impressions'=>26334000,'clicks'=>1316700,'ctr'=>5.0, 'video_id' => '7632512609094225173'],
                ['name'=>'YG-度衛星-Indosaku – Engagement Boost – Meta',   'platform'=>'meta',  'spend'=>2715000000,'impressions'=>24434500,'clicks'=>917200,'ctr'=>3.8, 'video_id' => '7631151504791014676'],
                ['name'=>'YG-度衛星-Indosaku – Brand Protection – Google', 'platform'=>'google','spend'=>1795500000,'impressions'=>14364000,'clicks'=>718200,'ctr'=>5.0, 'video_id' => '7629681163761634581'],
                ['name'=>'YG-度衛星-Indosaku – Awareness Spark – TikTok',  'platform'=>'tiktok','spend'=>1284747000,'impressions'=>15414000,'clicks'=>513820,'ctr'=>3.3, 'video_id' => '7628540027043171605'],
                ['name'=>'YG-度衛星-Indosaku – Video Leads – TikTok',      'platform'=>'tiktok','spend'=>846000000, 'impressions'=>8460000, 'clicks'=>304800,'ctr'=>3.6, 'video_id' => '7628066934608661768'],
            ]);
        }

        // ── Recent 5 Campaigns (mock, realistic) ──
        $recentCampaigns = Campaign::with(['adAccount','spendingRecords'])->latest()->take(5)->get();

        // ── Mock Recent 5 Invoices aligned to month ──
        $mockInvoicesByMonth = [
            '2025-07' => [
                ['invoice_number'=>'INV/2025/07/001','platform'=>'google','amount'=>1155000000,'status'=>'paid', 'period'=>'Jul 2025'],
                ['invoice_number'=>'INV/2025/07/002','platform'=>'meta',  'amount'=>1045000000,'status'=>'paid', 'period'=>'Jul 2025'],
                ['invoice_number'=>'INV/2025/07/003','platform'=>'tiktok','amount'=>554134170, 'status'=>'paid', 'period'=>'Jul 2025'],
            ],
            '2025-08' => [
                ['invoice_number'=>'INV/2025/08/001','platform'=>'google','amount'=>2520000000,'status'=>'paid', 'period'=>'Aug 2025'],
                ['invoice_number'=>'INV/2025/08/002','platform'=>'meta',  'amount'=>2280000000,'status'=>'paid', 'period'=>'Aug 2025'],
                ['invoice_number'=>'INV/2025/08/003','platform'=>'tiktok','amount'=>1208000000,'status'=>'paid', 'period'=>'Aug 2025'],
            ],
            '2025-09' => [
                ['invoice_number'=>'INV/2025/09/001','platform'=>'google','amount'=>2310000000,'status'=>'paid', 'period'=>'Sep 2025'],
                ['invoice_number'=>'INV/2025/09/002','platform'=>'meta',  'amount'=>2105000000,'status'=>'paid', 'period'=>'Sep 2025'],
                ['invoice_number'=>'INV/2025/09/003','platform'=>'tiktok','amount'=>1092865865,'status'=>'paid', 'period'=>'Sep 2025'],
            ],
        ];

        $mockRecentInvoices = [];
        if ($selectedMonth && isset($mockInvoicesByMonth[$selectedMonth])) {
            $mockRecentInvoices = $mockInvoicesByMonth[$selectedMonth];
        } else {
            // Show latest 5 across all months
            foreach (array_reverse($mockInvoicesByMonth) as $m => $inv) {
                foreach ($inv as $i) { $mockRecentInvoices[] = $i; if (count($mockRecentInvoices)>=5) break 2; }
            }
        }
        // Also load DB invoices as fallback
        $recentInvoices = Invoice::with('adAccount')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'selectedMonth',
            'totalSpend',
            'totalImpressions',
            'totalClicks',
            'totalConversions',
            'ctr',
            'cpc',
            'todaySpend',
            'spendChange',
            'platformStats',
            'recentCampaigns',
            'spendingTrend',
            'monthlySpendingSummary',
            'recentInvoices',
            'mockRecentInvoices',
            'totalTopup',
            'activeCampaigns',
            'optimizationScore',
            'topCampaigns',
            'deviceStats',
            'dayOfWeekPerformance',
            'audienceData',
            'locationStats',
            'maxLocationClicks'
        ));
    }

    private function calculateOptimizationScore($spend, $clicks, $conversions, $ctr)
    {
        $score = 50;

        if ($ctr > 2) $score += 15;
        elseif ($ctr > 1) $score += 10;
        else $score += 5;

        if ($conversions > 0) $score += 20;

        if ($spend > 0 && $clicks > 0) {
            $cpc = $spend / $clicks;
            if ($cpc < 1) $score += 15;
            elseif ($cpc < 2) $score += 10;
            else $score += 5;
        }

        return min($score, 100);
    }

    public function googleAds()
    {
        return $this->platformAds('google');
    }

    public function metaAds()
    {
        return $this->platformAds('meta');
    }

    public function tiktokAds()
    {
        return $this->platformAds('tiktok');
    }

    private function platformAds($platform)
    {
        $account = AdAccount::where('platform', $platform)->first();

        if (!$account) {
            return redirect()->route('dashboard.index')->with('error', 'Account not found');
        }

        // Support both ?month= and ?date_from=&date_to= 
        $selectedMonth = request()->get('month');
        $dateFrom      = request()->get('date_from'); // YYYY-MM-DD
        $dateTo        = request()->get('date_to');   // YYYY-MM-DD

        // Derive selectedMonth from date range if provided
        if (!$selectedMonth && $dateFrom) {
            $selectedMonth = substr($dateFrom, 0, 7); // e.g. "2025-07"
        }

        // Deterministic mock so platform pages align with dashboard (non-zero & natural)
        $mockByMonth = [
            '2025-07' => [
                'totalSpend' => 2754134170,
                'google' => ['spend' => 1155000000, 'cpc' => 2500, 'ctrPct' => 2.8, 'convRatePct' => 5.0],
                'meta' => ['spend' => 1045000000, 'cpc' => 2300, 'ctrPct' => 2.2, 'convRatePct' => 4.0],
                'tiktok' => ['spend' => 554134170, 'cpc' => 2700, 'ctrPct' => 1.8, 'convRatePct' => 3.5],
            ],
            '2025-08' => [
                'totalSpend' => 6008000000,
                'google' => ['spend' => 2520000000, 'cpc' => 2500, 'ctrPct' => 2.8, 'convRatePct' => 5.0],
                'meta' => ['spend' => 2280000000, 'cpc' => 2300, 'ctrPct' => 2.2, 'convRatePct' => 4.0],
                'tiktok' => ['spend' => 1208000000, 'cpc' => 2700, 'ctrPct' => 1.8, 'convRatePct' => 3.5],
            ],
            '2025-09' => [
                'totalSpend' => 5507865865,
                'google' => ['spend' => 2310000000, 'cpc' => 2500, 'ctrPct' => 2.8, 'convRatePct' => 5.0],
                'meta' => ['spend' => 2105000000, 'cpc' => 2300, 'ctrPct' => 2.2, 'convRatePct' => 4.0],
                'tiktok' => ['spend' => 1092865865, 'cpc' => 2700, 'ctrPct' => 1.8, 'convRatePct' => 3.5],
            ],
        ];

        $labelsByPlatform = [
            'google' => [
                'Search Growth – Performance', 'Brand Protection – Core', 'Remarketing – Dynamic', 'Competitor Conquest – Search', 'Video Leads – YouTube',
                'Discovery Ads – Shopping', 'Local Campaigns – Maps', 'App Install – Android', 'Display Network – Awareness', 'Smart Campaign – Automation'
            ],
            'meta' => [
                'Engagement Boost – Feed', 'Sales Campaign – Catalog', 'Retargeting – Website', 'Awareness – Stories', 'Lead Gen – Instant Forms',
                'Video Views – Reels', 'Traffic – Messenger', 'App Install – iOS', 'Conversions – Landing Page', 'Event Promotion – Local'
            ],
            'tiktok' => [
                'Awareness Spark – TopView', 'Video Leads – In-Feed', 'Conversion Wave – Catalog', 'Engagement – Spark Ads', 'App Install – Pulse',
                'Hashtag Challenge – Viral', 'Branded Effect – Filter', 'Collection Ads – Shopping', 'TopFeed – Reach', 'Creator Marketplace – Collab'
            ],
        ];

        $ratiosByPlatform = [
            'google' => [0.25, 0.18, 0.15, 0.12, 0.08, 0.06, 0.05, 0.04, 0.04, 0.03],
            'meta'   => [0.22, 0.20, 0.15, 0.13, 0.10, 0.07, 0.05, 0.04, 0.02, 0.02],
            'tiktok' => [0.20, 0.18, 0.16, 0.14, 0.10, 0.08, 0.06, 0.04, 0.02, 0.02],
        ];

        $canMock = $selectedMonth && isset($mockByMonth[$selectedMonth]) && isset($mockByMonth[$selectedMonth][$platform]);

        if ($canMock) {
            $month = $mockByMonth[$selectedMonth];
            $platformCfg = $month[$platform];

            $platformTotalSpend = (int) $platformCfg['spend'];
            $cpcBase = (float) $platformCfg['cpc'];
            $ctrPct = (float) $platformCfg['ctrPct']; // clicks/impressions*100
            $convRatePct = (float) $platformCfg['convRatePct']; // conversions/clicks*100

            $ratios = $ratiosByPlatform[$platform] ?? [1.0];
            $labels = $labelsByPlatform[$platform] ?? ['Campaign'];

            $campaignSpends = [];
            $tempSum = 0;
            foreach ($ratios as $idx => $ratio) {
                $val = (int) floor($platformTotalSpend * $ratio);
                $campaignSpends[$idx] = $val;
                $tempSum += $val;
            }
            $campaignSpends[count($campaignSpends) - 1] += ($platformTotalSpend - $tempSum);

            $computeMetrics = function (int $spend, $idx) use ($cpcBase, $ctrPct, $convRatePct): array {
                $clicks = max(1, (int) floor($spend / max($cpcBase, 0.000001)));
                $impressions = (int) floor(($clicks * 100) / max($ctrPct, 0.000001));
                $conversions = (int) floor($clicks * ($convRatePct / 100));

                $ctr = $impressions > 0 ? ($clicks / $impressions) * 100 : 0;
                $cpc = $clicks > 0 ? $spend / $clicks : 0;
                $cpm = $impressions > 0 ? ($spend / $impressions) * 1000 : 0;
                $conversionRate = $clicks > 0 ? ($conversions / $clicks) * 100 : 0;
                $frequency = 1.1 + ($idx % 5) * 0.1;
                $reach = (int)($impressions / $frequency);
                $costPerConv = $conversions > 0 ? $spend / $conversions : 0;
                
                $thumbnailIds = [
                    '1izdCJO6VxzUl4rjkqpe4ceSFF9Qr3CGM',
                    '1K8CjaWAmHvRfj_TcWAz7TsWeiruaEBQe',
                    '1xpGmKgPNUEVSrnDVgFQaw2IEE_eT4OAL',
                    '1UvCZSV0Z9QIyWfKYz4UFJWmwfPUXWtBb',
                    '1gNiaKqSUqLVgazjvAKLA3IthrOHewFT_',
                    '15dzn9NGmejp_E7v2mSnwrqK3mIjjhIzQ',
                    '1o53KW2qzslMVUq4SywbPrWjKiKhheXuA',
                    '1DUJ_1QG2cB25zwq9I30ZQ7X07YTEZ6H1',
                    '1U2dvLRwG4unrWD3H__SJQPg3mucogVh8',
                    '1aZ0L33sWPOlB4hciIKIFpO4IPSKWGzj2',
                    '1eJz-rvql-R4b1_5-zfHn1vI6LktfwsgM',
                    '1E9Nr3AaZE7i23gtv1Zb5toz4v4JVQT8M',
                    '1zGPiS150zJHlIVVntSxSFeg3n75ahQLu',
                    '1xxPcNxotuc0j5qFmUlj7_BP-tVYVXtGs',
                    '1uNfR-CKxPfHfLg9MW0TCiu1eWBz7M3Ag',
                    '1GIGv7F-TyS3f1HkUL1N7zmYHQurNO3HF',
                    '1TVEXpPRfaS949rrwGCODIMJvgkRrfc2K',
                    '1vrQCaOw4fAk9IZ_RqJtFxzOenBsSUZvf',
                    '1h5dH43yCJxvKRDzUfCJabhIFJIBRgzuB',
                    '1zW30vHZbCdFjjGQ-16HQiC1dmO0XfHve',
                    '1FAHdMVc8-Da4SNQSRW5Q2xtbuVbBi9ty',
                    '1PuAJvEYoh-8pNAFOXUHYKbe04M_e4m0B',
                    '1OIViGBrfYQEP9Q31syQo0I2X5CHxzEvo',
                    '1G9rNou7-H8pHQFhj2ljA2jSDm1nYBIIS',
                    '1X13fWtWVsvK2SA-GObzFUpLF1PvhUZbC',
                    '1aebzTUT8eEysl143Si_0ioYLF7kqpk51',
                    '1bAe27OQs38tIgquUNdHjTQ0OfUkEjGDk',
                    '1_z_j0c2ozvh2PDonei9Dx0hrSGxRzF5F',
                    '1PCEO5LOvpOPlx_0xmOlgPA4VpTVKuKQk',
                    '1VWMjdKpsc1oHvlWVmipG0gJR_6xPzN1c',
                    '1NonLMo-Kgc6VjmXokpdzvj93nIoWEe6W',
                    '1143P1hSzshkqIbD7d3RbmP1M3zgytehS',
                    '1jKm5CnEWZxEtmbsB6bui3zZHUwRx_8rk',
                    '1W7FHJJ6PjMUWG1lRpimOOcKVK7YYLbJh',
                ];
                $thumbIdx = $idx % count($thumbnailIds);
                $thumbnailUrl = 'https://drive.google.com/thumbnail?id=' . $thumbnailIds[$thumbIdx] . '&sz=w256';

                return [
                    'spend' => $spend,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'conversions' => $conversions,
                    'ctr' => $ctr,
                    'cpc' => $cpc,
                    'cpm' => $cpm,
                    'conversionRate' => $conversionRate,
                    'frequency' => $frequency,
                    'reach' => $reach,
                    'cost_per_conv' => $costPerConv,
                    'video_id' => $thumbnailIds[$thumbIdx],
                    'thumbnail_url' => $thumbnailUrl,
                ];
            };

            $campaigns = collect($labels)->map(function ($name, $idx) {
                return [
                    'id' => $idx + 1,
                    'name' => 'YG-度衛星-Indosaku – ' . $name,
                    'status' => 'completed',
                    'budget_daily' => max(500000, 3000000 - ($idx * 150000)),
                    'budget_total' => max(10000000, 90000000 - ($idx * 4000000)),
                    'spendRatioIndex' => $idx,
                ];
            })->map(function ($c) use ($campaignSpends, $computeMetrics) {
                $idx = (int) ($c['spendRatioIndex'] ?? 0);
                $spend = (int) ($campaignSpends[$idx] ?? 0);
                $m = $computeMetrics($spend, $idx);

                return [
                    'id' => $c['id'],
                    'name' => $c['name'],
                    'status' => $c['status'],
                    'budget_daily' => $c['budget_daily'],
                    'budget_total' => $c['budget_total'],
                    'spend' => $m['spend'],
                    'impressions' => $m['impressions'],
                    'clicks' => $m['clicks'],
                    'conversions' => $m['conversions'],
                    'ctr' => $m['ctr'],
                    'cpc' => $m['cpc'],
                    'cpm' => $m['cpm'],
                    'conversionRate' => $m['conversionRate'],
                    'frequency' => $m['frequency'],
                    'reach' => $m['reach'],
                    'cost_per_conv' => $m['cost_per_conv'],
                    'video_id' => $m['video_id'],
                    'thumbnail_url' => $m['thumbnail_url'],
                ];
            });

            $totalSpend = (int) $campaigns->sum('spend');
            $totalImpressions = (int) $campaigns->sum('impressions');
            $totalClicks = (int) $campaigns->sum('clicks');
            $totalConversions = (int) $campaigns->sum('conversions');

            $ctr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
            $cpc = $totalClicks > 0 ? $totalSpend / $totalClicks : 0;
            $cpm = $totalImpressions > 0 ? ($totalSpend / $totalImpressions) * 1000 : 0;
            $conversionRate = $totalClicks > 0 ? ($totalConversions / $totalClicks) * 100 : 0;
            $frequency = $totalClicks > 0 ? $totalImpressions / $totalClicks : 0;
            $roas = $totalConversions > 0 && $totalSpend > 0 ? ($totalConversions * 50000) / $totalSpend : 0;

            // 7-day trend (deterministic)
            $ratioDays = [0.10, 0.13, 0.16, 0.15, 0.12, 0.18, 0.16];
            $daySpends = array_map(fn ($r) => (int) floor($totalSpend * $r), $ratioDays);
            $daySpends[6] += ($totalSpend - array_sum($daySpends));

            $dailyTrend = [];
            foreach (range(0, 6) as $offset) {
                $date = Carbon::now()->subDays(6 - $offset);
                $spend = $daySpends[$offset];
                $m = $computeMetrics($spend, $offset);

                $dailyTrend[] = [
                    'date' => $date->format('M d'),
                    'spend' => $m['spend'],
                    'impressions' => $m['impressions'],
                    'clicks' => $m['clicks'],
                    'conversions' => $m['conversions'],
                ];
            }

            // Last month spend for comparison
            $months = ['2025-07','2025-08','2025-09'];
            $currIdx = array_search($selectedMonth, $months);
            $lastMonthSpend = ($currIdx > 0) ? (int)$mockByMonth[$months[$currIdx-1]][$platform]['spend'] : null;

            return view('dashboard.platform', compact(
                'account',
                'campaigns',
                'totalSpend',
                'totalImpressions',
                'totalClicks',
                'totalConversions',
                'ctr',
                'conversionRate',
                'cpc',
                'cpm',
                'frequency',
                'roas',
                'dailyTrend',
                'platform',
                'selectedMonth',
                'dateFrom',
                'dateTo',
                'lastMonthSpend'
            ));
        }

        // Fallback: DB-driven logic (original behavior) if month isn't mocked
        $thumbnailIds = [
            '1izdCJO6VxzUl4rjkqpe4ceSFF9Qr3CGM', '1K8CjaWAmHvRfj_TcWAz7TsWeiruaEBQe',
            '1xpGmKgPNUEVSrnDVgFQaw2IEE_eT4OAL', '1UvCZSV0Z9QIyWfKYz4UFJWmwfPUXWtBb',
            '1gNiaKqSUqLVgazjvAKLA3IthrOHewFT_', '15dzn9NGmejp_E7v2mSnwrqK3mIjjhIzQ',
            '1o53KW2qzslMVUq4SywbPrWjKiKhheXuA', '1DUJ_1QG2cB25zwq9I30ZQ7X07YTEZ6H1',
            '1U2dvLRwG4unrWD3H__SJQPg3mucogVh8', '1aZ0L33sWPOlB4hciIKIFpO4IPSKWGzj2',
        ];

        $campaigns = Campaign::where('ad_account_id', $account->id)
            ->with('spendingRecords')
            ->get()
            ->map(function ($campaign, $idx) use ($thumbnailIds) {
                $spend = $campaign->spendingRecords->sum('spend');
                $impressions = $campaign->spendingRecords->sum('impressions');
                $clicks = $campaign->spendingRecords->sum('clicks');
                $conversions = $campaign->spendingRecords->sum('conversions');

                $ctr = $impressions > 0 ? ($clicks / $impressions) * 100 : 0;
                $cpc = $clicks > 0 ? $spend / $clicks : 0;
                $cpm = $impressions > 0 ? ($spend / $impressions) * 1000 : 0;
                $conversionRate = $clicks > 0 ? ($conversions / $clicks) * 100 : 0;
                $frequency = $clicks > 0 ? $impressions / $clicks : 1.1;
                $thumbIdx = $idx % count($thumbnailIds);

                return [
                    'id' => $campaign->id,
                    'name' => 'YG-度衛星-Indosaku – ' . $campaign->campaign_name,
                    'status' => 'completed',
                    'budget_daily' => $campaign->budget_daily,
                    'budget_total' => $campaign->budget_total,
                    'spend' => $spend,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'conversions' => $conversions,
                    'ctr' => $ctr,
                    'cpc' => $cpc,
                    'cpm' => $cpm,
                    'conversionRate' => $conversionRate,
                    'frequency' => $frequency,
                    'reach' => (int)($impressions / $frequency),
                    'cost_per_conv' => $conversions > 0 ? $spend / $conversions : 0,
                    'video_id' => $thumbnailIds[$thumbIdx],
                    'thumbnail_url' => 'https://drive.google.com/thumbnail?id=' . $thumbnailIds[$thumbIdx] . '&sz=w256',
                ];
            });

        $totalSpend = (int) $campaigns->sum('spend');
        $totalImpressions = (int) $campaigns->sum('impressions');
        $totalClicks = (int) $campaigns->sum('clicks');
        $totalConversions = (int) $campaigns->sum('conversions');

        $ctr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
        $cpc = $totalClicks > 0 ? $totalSpend / $totalClicks : 0;
        $cpm = $totalImpressions > 0 ? ($totalSpend / $totalImpressions) * 1000 : 0;
        $conversionRate = $totalClicks > 0 ? ($totalConversions / $totalClicks) * 100 : 0;
        $frequency = $totalClicks > 0 ? $totalImpressions / $totalClicks : 0;
        $roas = $totalConversions > 0 && $totalSpend > 0 ? ($totalConversions * 50000) / $totalSpend : 0;

        // Daily trend for last 7 days
        $dailyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayRecords = AdSpendingRecord::whereHas('campaign', function ($q) use ($account) {
                $q->where('ad_account_id', $account->id);
            })->whereDate('record_date', $date)->get();

            $dailyTrend[] = [
                'date' => $date->format('M d'),
                'spend' => $dayRecords->sum('spend'),
                'impressions' => $dayRecords->sum('impressions'),
                'clicks' => $dayRecords->sum('clicks'),
                'conversions' => $dayRecords->sum('conversions'),
            ];
        }

        $lastMonthSpend = null;

        return view('dashboard.platform', compact(
            'account',
            'campaigns',
            'totalSpend',
            'totalImpressions',
            'totalClicks',
            'totalConversions',
            'ctr',
            'conversionRate',
            'cpc',
            'cpm',
            'frequency',
            'roas',
            'dailyTrend',
            'platform',
            'selectedMonth',
            'dateFrom',
            'dateTo',
            'lastMonthSpend'
        ));
    }
}
