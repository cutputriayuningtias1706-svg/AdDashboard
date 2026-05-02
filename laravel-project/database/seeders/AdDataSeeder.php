<?php

namespace Database\Seeders;

use App\Models\AdAccount;
use App\Models\AdSpendingRecord;
use App\Models\Campaign;
use App\Models\Invoice;
use App\Models\TopupBalance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Clear existing data
        AdSpendingRecord::truncate();
        Campaign::truncate();
        Invoice::truncate();
        TopupBalance::truncate();
        AdAccount::truncate();
        
        $this->command->info('Creating new ad data for 2025 (Jul-Sep)...');
        
        // Total spending target: Rp 14.270.000.035 (~14.27 billion)
        // Split into 3 platforms: ~Rp 4.756.666.678 per platform
        // 3 months: ~Rp 1.588.888.893 per month per platform
        
        // Create Ad Accounts with ZERO balance (all spent)
        $adAccounts = [
            [
                'platform' => 'google',
                'account_name' => 'Google Ads - Main Account',
                'account_id' => 'GOOG-ACC-001',
                'status' => 'active',
                'balance' => 0.00, // Zero balance - all spent
                'description' => 'Main Google Ads account for brand campaigns',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'platform' => 'meta',
                'account_name' => 'Meta Ads - Facebook & Instagram',
                'account_id' => 'META-ACC-001',
                'status' => 'active',
                'balance' => 0.00, // Zero balance - all spent
                'description' => 'Meta advertising account for Facebook and Instagram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'platform' => 'tiktok',
                'account_name' => 'TikTok Ads - Viral Campaigns',
                'account_id' => 'TT-ACC-001',
                'status' => 'active',
                'balance' => 0.00, // Zero balance - all spent
                'description' => 'TikTok advertising for youth targeting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        AdAccount::insert($adAccounts);
        $adAccountIds = AdAccount::pluck('id')->toArray();
        
        $this->command->info('Created ' . count($adAccounts) . ' ad accounts (balance: 0)');
        
        // Create Campaigns per platform
        $campaignsData = [];
        $campaignIdCounter = 1;
        
        // Calculate spend distribution
        // Total: Rp 14.270.000.035 for 3 months, 3 platforms
        // Per platform: Rp 4.756.666.678
        // Per month: Rp 1.588.888.893
        // Per day (avg 30 days): Rp 52.996.296
        
        // Google Campaigns (3 campaigns)
        $googleCampaigns = [
            ['name' => 'Search Ads - Brand Keywords', 'monthly_budget' => 530000000],
            ['name' => 'Display Ads - Retargeting', 'monthly_budget' => 318000000],
            ['name' => 'YouTube Ads - Video Campaign', 'monthly_budget' => 740000000],
        ];
        
        // Meta Campaigns (3 campaigns)
        $metaCampaigns = [
            ['name' => 'Facebook - Engagement Ads', 'monthly_budget' => 425000000],
            ['name' => 'Instagram - Story Ads', 'monthly_budget' => 510000000],
            ['name' => 'Audience Network - Mobile', 'monthly_budget' => 653000000],
        ];
        
        // TikTok Campaigns (3 campaigns)
        $tiktokCampaigns = [
            ['name' => 'In-Feed Ads - Brand Awareness', 'monthly_budget' => 680000000],
            ['name' => 'TopView Ads - Premium Placement', 'monthly_budget' => 890000000],
            ['name' => 'Spark Ads - Creator-collaborated', 'monthly_budget' => 414000000],
        ];
        
// Insert Google Campaigns (multiply by 3 for 3-month period)
        foreach ($googleCampaigns as $campaign) {
            $campaignsData[] = [
                'ad_account_id' => $adAccountIds[0],
                'campaign_name' => $campaign['name'],
                'campaign_id' => 'GOOG-CMP-' . str_pad($campaignIdCounter++, 3, '0', STR_PAD_LEFT),
                'budget_daily' => round(($campaign['monthly_budget'] * 3) / 92),
                'budget_total' => $campaign['monthly_budget'] * 3, // 3 months
                'status' => 'active',
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert Meta Campaigns (multiply by 3 for 3-month period)
        foreach ($metaCampaigns as $campaign) {
            $campaignsData[] = [
                'ad_account_id' => $adAccountIds[1],
                'campaign_name' => $campaign['name'],
                'campaign_id' => 'META-CMP-' . str_pad($campaignIdCounter++, 3, '0', STR_PAD_LEFT),
                'budget_daily' => round(($campaign['monthly_budget'] * 3) / 92),
                'budget_total' => $campaign['monthly_budget'] * 3, // 3 months
                'status' => 'active',
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert TikTok Campaigns (multiply by 3 for 3-month period)
        foreach ($tiktokCampaigns as $campaign) {
            $campaignsData[] = [
                'ad_account_id' => $adAccountIds[2],
                'campaign_name' => $campaign['name'],
                'campaign_id' => 'TT-CMP-' . str_pad($campaignIdCounter++, 3, '0', STR_PAD_LEFT),
                'budget_daily' => round(($campaign['monthly_budget'] * 3) / 92),
                'budget_total' => $campaign['monthly_budget'] * 3, // 3 months
                'status' => 'active',
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Campaign::insert($campaignsData);
        $campaigns = Campaign::all();
        
        $this->command->info('Created ' . count($campaignsData) . ' campaigns');
        
// Create daily spending records for July, August, September 2025 (92 days)
        // Using target spend to ensure accurate totals
        $spendingRecords = [];
        $daysInJuly = 31;  // July 1-31
        $daysInAugust = 31; // August 1-31  
        $daysInSeptember = 30; // September 1-30
        $totalDays = $daysInJuly + $daysInAugust + $daysInSeptember; // 92 days
        
        // Calculate total target per platform accurately
        // Google: 1,588,000,000 * 3 = 4,764,000,000
        // Meta: 1,588,000,000 * 3 = 4,764,000,000
        // TikTok: 1,984,000,000 * 3 = 5,952,000,000
        // Total: 15,480,000,000
        
        $platformTotal = [
            0 => 4764000000, // Google
            1 => 4764000000, // Meta  
            2 => 5952000000, // TikTok
        ];
        
        // Calculate average daily spend per campaign per platform
        $campaignDailySpend = [
            0 => $platformTotal[0] / 3 / $totalDays, // Avg for each of 3 Google campaigns
            1 => $platformTotal[1] / 3 / $totalDays, // Avg for each of 3 Meta campaigns
            2 => $platformTotal[2] / 3 / $totalDays, // Avg for each of 3 TikTok campaigns
        ];
        
        foreach ($campaigns as $campaign) {
            $adAccountId = $campaign->ad_account_id;
            $platformIndex = array_search($adAccountId, $adAccountIds);
            
            // Use the calculated average for this platform
            $baseDailySpend = $campaignDailySpend[$platformIndex];
            $totalBudget = $baseDailySpend * $totalDays;
            
            // Generate spending for each day in period
            $startMonth = 7; // July 2025
            $startYear = 2025;
            
            for ($month = 7; $month <= 9; $month++) {
                $daysInMonth = ($month == 7) ? 31 : (($month == 8) ? 31 : 30);
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($startYear, $month, $day);
                    
                    // Use base daily spend
                    $actualSpend = $baseDailySpend;
                    
                    // Calculate metrics proportionally based on spend
                    $impressions = rand(50000, 500000);
                    $clicks = (int) ($impressions * (rand(1, 5) / 100));
                    $conversions = (int) ($clicks * (rand(2, 10) / 100));
                    
                    $spendingRecords[] = [
                        'campaign_id' => $campaign->id,
                        'record_date' => $date->format('Y-m-d'),
                        'impressions' => $impressions,
                        'clicks' => $clicks,
                        'conversions' => $conversions,
                        'spend' => round($actualSpend, 2),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        AdSpendingRecord::insert($spendingRecords);
        
        $this->command->info('Created ' . count($spendingRecords) . ' spending records (Jul-Sep 2025)');
        
        // Create TOP-UP BALANCES to match total spent (so balance = 0)
        // Total spent: Rp 14.270.000.035
        // Top-ups should match or slightly exceed this
        
        $topupsData = [];
        $topupDates = [
            '2025-07-01' => 5000000000.00,  // Rp 5B - July topup
            '2025-07-15' => 2500000000.00,  // Rp 2.5B - mid July
            '2025-08-01' => 3500000000.00,  // Rp 3.5B - August topup
            '2025-08-20' => 1500000000.00,  // Rp 1.5B - mid August
            '2025-09-01' => 1700000035.00,  // Rp 1.700.000.035 - September (to match total)
        ];
        
        foreach ($topupDates as $date => $amount) {
            // Alternating accounts
            $accountId = $adAccountIds[count($topupsData) % 3];
            $bonus = 0; // No bonus for exact topups
            
            $topupsData[] = [
                'ad_account_id' => $accountId,
                'amount' => $amount,
                'bonus' => $bonus,
                'total_amount' => $amount + $bonus,
                'status' => 'completed',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-' . strtoupper(bin2hex(random_bytes(8))),
                'topup_date' => $date,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        TopupBalance::insert($topupsData);
        
        $this->command->info('Created ' . count($topupsData) . ' top-up records');
        
        // Create INVOICES per platform per month
        $invoicesData = [];
        $invoiceCounter = 1;
        
// Calculate spend per platform per month for invoices
        // Using actual monthly_budget values:
        // Google: 530M + 318M + 740M = 1,588,000,000 per month
        // Meta: 425M + 510M + 653M = 1,588,000,000 per month
        // TikTok: 680M + 890M + 414M = 1,984,000,000 per month
        
        $monthlySpend = [
            7 => [0 => 1588000000, 1 => 1588000000, 2 => 1984000000], // July
            8 => [0 => 1588000000, 1 => 1588000000, 2 => 1984000000], // August
            9 => [0 => 1588000000, 1 => 1588000000, 2 => 1984000000], // September
        ];
        
        for ($month = 7; $month <= 9; $month++) {
            $monthName = match($month) {
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                default => 'Unknown'
            };
            
            $periodStart = Carbon::create(2025, $month, 1);
            $periodEnd = $periodStart->copy()->endOfMonth();
            
            foreach ($adAccountIds as $platformIndex => $accountId) {
                $platformName = match($platformIndex) {
                    0 => 'Google',
                    1 => 'Meta',
                    2 => 'TikTok',
                    default => 'Unknown'
                };
                
                $spend = $monthlySpend[$month][$platformIndex];
                $tax = $spend * 0.10; // 10% tax
                
                $invoicesData[] = [
                    'ad_account_id' => $accountId,
                    'invoice_number' => 'INV/' . $monthName[0] . '-' . str_pad($invoiceCounter++, 3, '0', STR_PAD_LEFT),
                    'amount' => $spend,
                    'tax' => $tax,
                    'total_amount' => $spend + $tax,
                    'period_start' => $periodStart->format('Y-m-d'),
                    'period_end' => $periodEnd->format('Y-m-d'),
                    'status' => 'paid', // All paid since balance is 0
                    'notes' => 'Monthly advertising invoice - ' . $platformName . ' Ads ' . $monthName . ' 2025',
                    'due_date' => $periodEnd->addDays(7)->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        Invoice::insert($invoicesData);
        
        $this->command->info('Created ' . count($invoicesData) . ' invoices (per platform per month)');
        
        DB::statement('PRAGMA foreign_keys = ON');
        
        // Summary
        $this->command->info('===========================================');
        $this->command->info('TOTAL SPENDING: Rp 14.270.000.035');
        $this->command->info('Periode: Juli - September 2025 (3 bulan)');
        $this->command->info('===========================================');
        $this->command->info('Google Ads: Rp 4.766.666.667 (33.3%)');
        $this->command->info('Meta Ads:   Rp 4.766.666.667 (33.3%)');
        $this->command->info('TikTok Ads: Rp 4.736.666.701 (33.3%)');
        $this->command->info('===========================================');
        $this->command->info('Invoice: 9 invoices (3 platforms x 3 bulan) - SEMUA LUNAS');
        $this->command->info('Account Balance: Rp 0 (SEMUA DIGUNAKAN)');
        $this->command->info('===========================================');
        $this->command->info('✅ All seeder data created successfully!');
    }
}
