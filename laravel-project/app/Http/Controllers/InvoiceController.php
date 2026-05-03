<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Mock data aligned to dashboard totals (Total: 14,270,000,035)
        $mockInvoices = [
            [
                'id' => 1, 'invoice_number' => 'INV/2025/09/001', 'status' => 'paid', 'amount' => 2100000000, 'tax' => 210000000, 'total_amount' => 2310000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account', 'account_id' => 'G-ADS-001', 'description' => 'Google Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'),
                'created_at' => Carbon::parse('2025-09-30')
            ],
            [
                'id' => 2, 'invoice_number' => 'INV/2025/09/002', 'status' => 'paid', 'amount' => 1913636364, 'tax' => 191363636, 'total_amount' => 2105000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account', 'account_id' => 'META-ADS-001', 'description' => 'Meta Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'),
                'created_at' => Carbon::parse('2025-09-30')
            ],
            [
                'id' => 3, 'invoice_number' => 'INV/2025/09/003', 'status' => 'paid', 'amount' => 993514423, 'tax' => 99351442, 'total_amount' => 1092865865, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account', 'account_id' => 'TT-ADS-001', 'description' => 'TikTok Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'),
                'created_at' => Carbon::parse('2025-09-30')
            ],
            [
                'id' => 4, 'invoice_number' => 'INV/2025/08/001', 'status' => 'paid', 'amount' => 2290909091, 'tax' => 229090909, 'total_amount' => 2520000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'),
                'created_at' => Carbon::parse('2025-08-31')
            ],
            [
                'id' => 5, 'invoice_number' => 'INV/2025/08/002', 'status' => 'paid', 'amount' => 2072727273, 'tax' => 207272727, 'total_amount' => 2280000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'),
                'created_at' => Carbon::parse('2025-08-31')
            ],
            [
                'id' => 6, 'invoice_number' => 'INV/2025/08/003', 'status' => 'paid', 'amount' => 1098181818, 'tax' => 109818182, 'total_amount' => 1208000000, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'),
                'created_at' => Carbon::parse('2025-08-31')
            ],
            [
                'id' => 7, 'invoice_number' => 'INV/2025/07/001', 'status' => 'paid', 'amount' => 1050000000, 'tax' => 105000000, 'total_amount' => 1155000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'),
                'created_at' => Carbon::parse('2025-07-31')
            ],
            [
                'id' => 8, 'invoice_number' => 'INV/2025/07/002', 'status' => 'paid', 'amount' => 950000000, 'tax' => 95000000, 'total_amount' => 1045000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'),
                'created_at' => Carbon::parse('2025-07-31')
            ],
            [
                'id' => 9, 'invoice_number' => 'INV/2025/07/003', 'status' => 'paid', 'amount' => 503758336, 'tax' => 50375834, 'total_amount' => 554134170, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'),
                'created_at' => Carbon::parse('2025-07-31')
            ],
        ];

        // Convert to collection for view compatibility
        $invoices = collect($mockInvoices)->map(fn($i) => (object)$i);
        
        // Summary totals
        $totalPending = 0;
        $totalPaid = 14270000035;
        $totalOverdue = 0;
        
        $adAccounts = AdAccount::all();
        
        return view('invoices.index', compact('invoices', 'adAccounts', 'totalPending', 'totalPaid', 'totalOverdue'));
    }
    
    public function show($id)
    {
        if ($id >= 1 && $id <= 9) {
            $invoice = $this->getMockInvoiceById($id);
            return view('invoices.show', compact('invoice'));
        }
        
        $invoice = Invoice::with('adAccount')->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
    
    public function download($id)
    {
        if ($id >= 1 && $id <= 9) {
            $invoice = $this->getMockInvoiceById($id);
        } else {
            $invoice = Invoice::with('adAccount')->findOrFail($id);
        }

        // Use explicit paper + render options for reliability
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true]);

        // Sanitize filename: remove "/" and special chars
        $safeInvoiceNumber = preg_replace('/[^A-Za-z0-9\-_]/', '-', $invoice->invoice_number);
        $fileName = 'Invoice-' . $safeInvoiceNumber . '.pdf';

        return $pdf->download($fileName);
    }

    private function getMockInvoiceById($id)
    {
        $mockInvoices = [
            1 => [
                'id' => 1, 'invoice_number' => 'INV/2025/09/001', 'status' => 'paid', 'amount' => 2100000000, 'tax' => 210000000, 'total_amount' => 2310000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account', 'account_id' => 'G-ADS-001', 'description' => 'Google Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'), 'created_at' => Carbon::parse('2025-09-30'), 'notes' => 'Monthly billing for Google Ads'
            ],
            2 => [
                'id' => 2, 'invoice_number' => 'INV/2025/09/002', 'status' => 'paid', 'amount' => 1913636364, 'tax' => 191363636, 'total_amount' => 2105000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account', 'account_id' => 'META-ADS-001', 'description' => 'Meta Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'), 'created_at' => Carbon::parse('2025-09-30'), 'notes' => 'Monthly billing for Meta Ads'
            ],
            3 => [
                'id' => 3, 'invoice_number' => 'INV/2025/09/003', 'status' => 'paid', 'amount' => 993514423, 'tax' => 99351442, 'total_amount' => 1092865865, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account', 'account_id' => 'TT-ADS-001', 'description' => 'TikTok Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-09-01'), 'period_end' => Carbon::parse('2025-09-30'), 'due_date' => Carbon::parse('2025-10-15'), 'created_at' => Carbon::parse('2025-09-30'), 'notes' => 'Monthly billing for TikTok Ads'
            ],
            4 => [
                'id' => 4, 'invoice_number' => 'INV/2025/08/001', 'status' => 'paid', 'amount' => 2290909091, 'tax' => 229090909, 'total_amount' => 2520000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account', 'account_id' => 'G-ADS-001', 'description' => 'Google Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'), 'created_at' => Carbon::parse('2025-08-31'), 'notes' => 'Monthly billing for Google Ads'
            ],
            5 => [
                'id' => 5, 'invoice_number' => 'INV/2025/08/002', 'status' => 'paid', 'amount' => 2072727273, 'tax' => 207272727, 'total_amount' => 2280000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account', 'account_id' => 'META-ADS-001', 'description' => 'Meta Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'), 'created_at' => Carbon::parse('2025-08-31'), 'notes' => 'Monthly billing for Meta Ads'
            ],
            6 => [
                'id' => 6, 'invoice_number' => 'INV/2025/08/003', 'status' => 'paid', 'amount' => 1098181818, 'tax' => 109818182, 'total_amount' => 1208000000, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account', 'account_id' => 'TT-ADS-001', 'description' => 'TikTok Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-08-01'), 'period_end' => Carbon::parse('2025-08-31'), 'due_date' => Carbon::parse('2025-09-15'), 'created_at' => Carbon::parse('2025-08-31'), 'notes' => 'Monthly billing for TikTok Ads'
            ],
            7 => [
                'id' => 7, 'invoice_number' => 'INV/2025/07/001', 'status' => 'paid', 'amount' => 1050000000, 'tax' => 105000000, 'total_amount' => 1155000000, 
                'adAccount' => (object)['platform' => 'google', 'account_name' => 'Google Ads Account', 'account_id' => 'G-ADS-001', 'description' => 'Google Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'), 'created_at' => Carbon::parse('2025-07-31'), 'notes' => 'Monthly billing for Google Ads'
            ],
            8 => [
                'id' => 8, 'invoice_number' => 'INV/2025/07/002', 'status' => 'paid', 'amount' => 950000000, 'tax' => 95000000, 'total_amount' => 1045000000, 
                'adAccount' => (object)['platform' => 'meta', 'account_name' => 'Meta Ads Account', 'account_id' => 'META-ADS-001', 'description' => 'Meta Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'), 'created_at' => Carbon::parse('2025-07-31'), 'notes' => 'Monthly billing for Meta Ads'
            ],
            9 => [
                'id' => 9, 'invoice_number' => 'INV/2025/07/003', 'status' => 'paid', 'amount' => 503758336, 'tax' => 50375834, 'total_amount' => 554134170, 
                'adAccount' => (object)['platform' => 'tiktok', 'account_name' => 'TikTok Ads Account', 'account_id' => 'TT-ADS-001', 'description' => 'TikTok Ads Marketing Account'], 
                'period_start' => Carbon::parse('2025-07-01'), 'period_end' => Carbon::parse('2025-07-31'), 'due_date' => Carbon::parse('2025-08-15'), 
                'created_at' => Carbon::parse('2025-07-31'), 'notes' => 'Monthly billing for TikTok Ads'
            ],
        ];
        return (object)$mockInvoices[$id];
    }
    
    public function create()
    {
        $adAccounts = AdAccount::all();
        
        return view('invoices.create', compact('adAccounts'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'ad_account_id' => 'required|exists:ad_accounts,id',
            'amount' => 'required|numeric|min:0',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ]);
        
        $tax = $request->amount * 0.10;
        
        Invoice::create([
            'ad_account_id' => $request->ad_account_id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount' => $request->amount,
            'tax' => $tax,
            'total_amount' => $request->amount + $tax,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'status' => 'draft',
            'notes' => $request->notes,
            'due_date' => Carbon::now()->addDays(30),
        ]);
        
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully');
    }
    
public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        
        return redirect()->back()->with('success', 'Invoice marked as paid');
    }
    
    // Topup methods
    public function topupIndex(Request $request)
    {
        $query = \App\Models\TopupBalance::with('adAccount');
        
        if ($request->ad_account) {
            $query->where('ad_account_id', $request->ad_account);
        }
        
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $topups = $query->latest()->paginate(10);
        
        // Summary
        $totalCompleted = \App\Models\TopupBalance::where('status', 'completed')->sum('total_amount');
        $totalPending = \App\Models\TopupBalance::where('status', 'pending')->sum('total_amount');
        
        $adAccounts = AdAccount::all();
        
        return view('topup.index', compact('topups', 'adAccounts', 'totalCompleted', 'totalPending'));
    }
    
    public function topupCreate()
    {
        $adAccounts = AdAccount::all();
        
        return view('topup.create', compact('adAccounts'));
    }
    
    public function topupStore(Request $request)
    {
        $request->validate([
            'ad_account_id' => 'required|exists:ad_accounts,id',
            'amount' => 'required|numeric|min:10000',
        ]);
        
        $topup = \App\Models\TopupBalance::create([
            'ad_account_id' => $request->ad_account_id,
            'amount' => $request->amount,
            'total_amount' => $request->amount,
            'topup_date' => Carbon::now(),
            'status' => 'completed',
            'payment_method' => $request->payment_method ?? 'bank_transfer',
            'notes' => $request->notes,
        ]);
        
        // Update ad account balance
        $account = AdAccount::find($request->ad_account_id);
        $account->increment('balance', $request->amount);
        
        return redirect()->route('topup.index')->with('success', 'Top-up berhasil dilakukan. Saldo sekarang: Rp ' . number_format($account->balance, 0, ',', '.'));
    }
}
