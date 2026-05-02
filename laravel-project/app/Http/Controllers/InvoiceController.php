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
        $query = Invoice::with('adAccount');
        
        if ($request->ad_account) {
            $query->where('ad_account_id', $request->ad_account);
        }
        
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $invoices = $query->latest()->paginate(10);
        
        // Summary
        $totalPending = Invoice::where('status', 'pending')->sum('total_amount');
        $totalPaid = Invoice::where('status', 'paid')->sum('total_amount');
        $totalOverdue = Invoice::where('status', 'overdue')->sum('total_amount');
        
        $adAccounts = AdAccount::all();
        
        return view('invoices.index', compact('invoices', 'adAccounts', 'totalPending', 'totalPaid', 'totalOverdue'));
    }
    
    public function show(Invoice $invoice)
    {
        $invoice->load('adAccount');
        
        return view('invoices.show', compact('invoice'));
    }
    
    public function download(Invoice $invoice)
    {
        $invoice->load('adAccount');

        // Use explicit paper + render options for reliability
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
                  ->setPaper('a4', 'portrait')
                  ->setOption(['defaultFont' => 'dejavu sans', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true]);

        // Sanitize filename: remove "/" and special chars
        $safeInvoiceNumber = preg_replace('/[^A-Za-z0-9\-_]/', '-', $invoice->invoice_number);
        $fileName = 'Invoice-' . $safeInvoiceNumber . '.pdf';

        return $pdf->download($fileName);
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
