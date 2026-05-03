<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use App\Models\AdSpendingRecord;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    // Mock data aligned with dashboard totals
    private $mockData = [
        '2025-07' => [
            'totalSpend'       => 2754134170,
            'totalImpressions' => 22000000,
            'totalClicks'      => 1100000,
            'totalConversions' => 55000,
            'platforms' => [
                'google' => ['spend'=>1155000000,'impressions'=>9240000, 'clicks'=>462000,'conversions'=>23100],
                'meta'   => ['spend'=>1045000000,'impressions'=>8360000, 'clicks'=>418000,'conversions'=>20900],
                'tiktok' => ['spend'=>554134170, 'impressions'=>4400000, 'clicks'=>220000,'conversions'=>11000],
            ],
        ],
        '2025-08' => [
            'totalSpend'       => 6008000000,
            'totalImpressions' => 28000000,
            'totalClicks'      => 1500000,
            'totalConversions' => 72000,
            'platforms' => [
                'google' => ['spend'=>2520000000,'impressions'=>11760000,'clicks'=>630000,'conversions'=>30240],
                'meta'   => ['spend'=>2280000000,'impressions'=>10640000,'clicks'=>570000,'conversions'=>27360],
                'tiktok' => ['spend'=>1208000000,'impressions'=>5600000, 'clicks'=>300000,'conversions'=>14400],
            ],
        ],
        '2025-09' => [
            'totalSpend'       => 5507865865,
            'totalImpressions' => 26000000,
            'totalClicks'      => 1420000,
            'totalConversions' => 68000,
            'platforms' => [
                'google' => ['spend'=>2310000000,'impressions'=>10920000,'clicks'=>595800,'conversions'=>28560],
                'meta'   => ['spend'=>2105000000,'impressions'=>9880000, 'clicks'=>539800,'conversions'=>25840],
                'tiktok' => ['spend'=>1092865865,'impressions'=>5200000, 'clicks'=>284200,'conversions'=>13600],
            ],
        ],
    ];

    public function index(Request $request)
    {
        $selectedMonth = $request->month;
        $selectedPlatform = $request->platform && $request->platform !== 'all' ? $request->platform : null;
        $campaigns = Campaign::all();

        // Build mock report rows
        $mockRows = $this->buildMockRows($selectedMonth, $selectedPlatform);

        // Filter by campaign if requested
        if ($request->campaign && $request->campaign !== 'all') {
            $mockRows = array_filter($mockRows, fn($r) => $r['campaign_id'] == $request->campaign);
        }

        // Summary from mock
        $totalSpend       = array_sum(array_column($mockRows, 'spend'));
        $totalImpressions = array_sum(array_column($mockRows, 'impressions'));
        $totalClicks      = array_sum(array_column($mockRows, 'clicks'));
        $totalConversions = array_sum(array_column($mockRows, 'conversions'));

        // Paginate manually
        $page     = $request->input('page', 1);
        $perPage  = 20;
        $allRows  = array_values($mockRows);
        $total    = count($allRows);
        $slice    = array_slice($allRows, ($page - 1) * $perPage, $perPage);

        $reports  = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('reports.index', compact(
            'reports', 'campaigns',
            'totalSpend', 'totalImpressions', 'totalClicks', 'totalConversions',
            'selectedMonth', 'selectedPlatform'
        ));
    }

    private function buildMockRows($month = null, $platform = null): array
    {
        $months = $month ? [$month] : ['2025-07','2025-08','2025-09'];
        $platforms = $platform ? [$platform] : ['google','meta','tiktok'];

        $platformNames = [
            'google' => [
                'Search Growth – Performance', 'Brand Protection – Core', 'Remarketing – Dynamic', 'Competitor Conquest – Search', 'Video Leads – YouTube',
                'Discovery Ads – Shopping', 'Local Campaigns – Maps', 'App Install – Android', 'Display Network – Awareness', 'Smart Campaign – Automation',
                'Bidding Test – Target ROAS', 'Holiday Special – Search', 'Product Launch – Video', 'Lead Generation – Web', 'Niche Target – Keywords',
                'Generic Terms – Low CPC', 'High Value Retargeting', 'B2B Professional – Search', 'Weekend Sale – YouTube', 'New Market – Expansion'
            ],
            'meta'   => [
                'Engagement Boost – Feed', 'Sales Campaign – Catalog', 'Retargeting – Website', 'Awareness – Stories', 'Lead Gen – Instant Forms',
                'Video Views – Reels', 'Traffic – Messenger', 'App Install – iOS', 'Conversions – Landing Page', 'Event Promotion – Local',
                'Lookalike Audience – 1%', 'Interest Target – Fashion', 'Dynamic Creative – Multi', 'Post Engagement – Page', 'Messenger Ads – Sales',
                'Brand Awareness – Reach', 'Shop Orders – Direct', 'Interactive Stories – Poll', 'Influencer Collab – Meta', 'Old Customer – Retention'
            ],
            'tiktok' => [
                'Awareness Spark – TopView', 'Video Leads – In-Feed', 'Conversion Wave – Catalog', 'Engagement – Spark Ads', 'App Install – Pulse',
                'Hashtag Challenge – Viral', 'Branded Effect – Filter', 'Collection Ads – Shopping', 'TopFeed – Reach', 'Creator Marketplace – Collab',
                'Bidding – Lowest Cost', 'Retention – Existing', 'New User – acquisition', 'Micro Influencer – Spark', 'Music Focus – Audio',
                'Gen Z Target – Lifestyle', 'Beauty Niche – Tutorial', 'Gaming Promo – Playable', 'Global Reach – Explore', 'Flash Sale – TikTok Shop'
            ],
        ];

        $platformRatios = [
            'google' => [0.15, 0.12, 0.10, 0.08, 0.07, 0.06, 0.05, 0.05, 0.04, 0.04, 0.03, 0.03, 0.03, 0.03, 0.03, 0.02, 0.02, 0.02, 0.01, 0.01],
            'meta'   => [0.14, 0.12, 0.10, 0.09, 0.07, 0.06, 0.06, 0.05, 0.04, 0.04, 0.03, 0.03, 0.03, 0.03, 0.03, 0.02, 0.02, 0.02, 0.02, 0.01],
            'tiktok' => [0.13, 0.12, 0.11, 0.09, 0.08, 0.07, 0.06, 0.05, 0.04, 0.04, 0.03, 0.03, 0.03, 0.02, 0.02, 0.02, 0.02, 0.01, 0.01, 0.01],
        ];

        $rows = [];
        $id = 1;

        foreach ($months as $m) {
            if (!isset($this->mockData[$m])) continue;
            $data = $this->mockData[$m];
            $year = substr($m,0,4);
            $mon  = (int)substr($m,5,2);
            $days = Carbon::create($year,$mon)->daysInMonth;
            $dayRatios = $this->getDayRatios($days);

            foreach ($platforms as $plt) {
                if (!isset($data['platforms'][$plt])) continue;
                $pd = $data['platforms'][$plt];
                $names = $platformNames[$plt];
                $ratios = $platformRatios[$plt];

                $thumbnailIds = [
                    '1izdCJO6VxzUl4rjkqpe4ceSFF9Qr3CGM', '1K8CjaWAmHvRfj_TcWAz7TsWeiruaEBQe',
                    '1xpGmKgPNUEVSrnDVgFQaw2IEE_eT4OAL', '1UvCZSV0Z9QIyWfKYz4UFJWmwfPUXWtBb',
                    '1gNiaKqSUqLVgazjvAKLA3IthrOHewFT_', '15dzn9NGmejp_E7v2mSnwrqK3mIjjhIzQ',
                    '1o53KW2qzslMVUq4SywbPrWjKiKhheXuA', '1DUJ_1QG2cB25zwq9I30ZQ7X07YTEZ6H1',
                    '1U2dvLRwG4unrWD3H__SJQPg3mucogVh8', '1aZ0L33sWPOlB4hciIKIFpO4IPSKWGzj2',
                ];

                foreach ($names as $idx => $name) {
                    $ratio = $ratios[$idx] ?? 0.2;
                    $cSpend = (int)($pd['spend'] * $ratio);
                    $cImpr  = (int)($pd['impressions'] * $ratio);
                    $cClick = (int)($pd['clicks'] * $ratio);
                    $cConv  = (int)($pd['conversions'] * $ratio);
                    $thumbIdx = $idx % count($thumbnailIds);
                    $cThumbId = $thumbnailIds[$thumbIdx];

                    // Spread across month days (show first 10 days per campaign)
                    $daysToShow = min(10, $days);
                    $sumRatios = array_sum(array_slice($dayRatios, 0, $daysToShow)) ?: 1;
                    
                    for ($d = 1; $d <= $daysToShow; $d++) {
                        $dr = $dayRatios[$d - 1] ?? (1 / $daysToShow);
                        $weight = $dr / $sumRatios;
                        
                        $rows[] = [
                            'id'           => $id++,
                            'record_date'  => Carbon::create($year, $mon, $d)->format('M d, Y'),
                            'platform'     => $plt,
                            'campaign_id'  => $idx + 1,
                            'campaign'     => 'YG-度衛星-Indosaku – ' . $name,
                            'impressions'  => (int)($cImpr  * $weight),
                            'clicks'       => (int)($cClick * $weight),
                            'conversions'  => (int)($cConv  * $weight),
                            'spend'        => (int)($cSpend * $weight),
                            'video_id'     => $cThumbId,
                            'thumbnail_url' => 'https://drive.google.com/thumbnail?id=' . $cThumbId . '&sz=w256',
                        ];
                    }
                }
            }
        }

        usort($rows, fn($a,$b) => strcmp($b['record_date'], $a['record_date']));
        return $rows;
    }

    private function getDayRatios(int $days): array
    {
        $base = [.04,.04,.05,.05,.04,.03,.04,.05,.06,.05,.04,.03,.04,.05,.05,.04,.04,.03,.04,.05,.05,.04,.03,.03,.04,.05,.04,.04,.03,.03,.02];
        return array_slice($base, 0, $days);
    }

    public function exportExcel(Request $request)
    {
        $rows = $this->buildMockRows($request->month, ($request->platform && $request->platform !== 'all') ? $request->platform : null);
        $data = collect($rows)->map(fn($r) => [
            'Date'        => $r['record_date'],
            'Platform'    => ucfirst($r['platform']),
            'Campaign'    => $r['campaign'],
            'Impressions' => $r['impressions'],
            'Clicks'      => $r['clicks'],
            'Conversions' => $r['conversions'],
            'Spend'       => $r['spend'],
            'CTR'         => $r['impressions'] > 0 ? round(($r['clicks']/$r['impressions'])*100,2) : 0,
        ]);
        return Excel::download(new ReportExport($data), 'ad-report-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
        $rows = $this->buildMockRows($request->month, ($request->platform && $request->platform !== 'all') ? $request->platform : null);
        $rows = array_slice($rows, 0, 200);
        $reports  = collect($rows);
        $totalSpend       = $reports->sum('spend');
        $totalImpressions = $reports->sum('impressions');
        $totalClicks      = $reports->sum('clicks');
        $totalConversions = $reports->sum('conversions');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('reports','totalSpend','totalImpressions','totalClicks','totalConversions'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true]);
        return $pdf->download('ad-report-'.now()->format('Y-m-d').'.pdf');
    }
}
