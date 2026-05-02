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
            'google' => ['Search Growth','Brand Protection','Remarketing'],
            'meta'   => ['Engagement Boost','Sales Campaign','Retargeting'],
            'tiktok' => ['Awareness Spark','Video Leads','Conversion Wave'],
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

                foreach ($names as $idx => $name) {
                    $ratio = [0.55, 0.30, 0.15][$idx] ?? 0.33;
                    $cSpend = (int)($pd['spend'] * $ratio);
                    $cImpr  = (int)($pd['impressions'] * $ratio);
                    $cClick = (int)($pd['clicks'] * $ratio);
                    $cConv  = (int)($pd['conversions'] * $ratio);

                    // Spread across month days (show first 10 days per campaign)
                    $daysToShow = min(10, $days);
                    for ($d = 1; $d <= $daysToShow; $d++) {
                        $dr = $dayRatios[$d - 1] ?? 0.1;
                        $rows[] = [
                            'id'           => $id++,
                            'record_date'  => Carbon::create($year, $mon, $d)->format('M d, Y'),
                            'platform'     => $plt,
                            'campaign_id'  => $idx + 1,
                            'campaign'     => $name,
                            'impressions'  => (int)($cImpr  * $dr * 10),
                            'clicks'       => (int)($cClick * $dr * 10),
                            'conversions'  => (int)($cConv  * $dr * 10),
                            'spend'        => (int)($cSpend * $dr * 10),
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
        $rows = $this->buildMockRows($request->month, ($request->platform && $request->platform !== 'all') ? $request->platform : null);
        $reports  = collect($rows);
        $totalSpend       = $reports->sum('spend');
        $totalImpressions = $reports->sum('impressions');
        $totalClicks      = $reports->sum('clicks');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('reports','totalSpend','totalImpressions','totalClicks'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true]);
        return $pdf->download('ad-report-'.now()->format('Y-m-d').'.pdf');
    }
}
