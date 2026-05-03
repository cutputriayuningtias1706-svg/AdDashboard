<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdAccount;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function create()
    {
        $adAccounts = AdAccount::all();
        $publishers = [
            'Pluto Network',
            'Sabupp',
            'Fingerads',
            'Yingliang'
        ];

        return view('campaigns.create', compact('adAccounts', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad_account_id' => 'required|exists:ad_accounts,id',
            'campaign_name' => 'required|string|max:255',
            'objective' => 'required|string',
            'publisher' => 'required|string',
            'disbursement' => 'required|numeric|min:10000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'ad_asset' => 'required|file|mimes:jpeg,png,jpg,mp4|max:51200', // max 50MB
        ]);

        $account = AdAccount::findOrFail($request->ad_account_id);

        if ($account->balance < $request->disbursement) {
            return redirect()->back()->withInput()->with('error', 'Saldo tidak mencukupi untuk jumlah disbursement iklan ini. Sisa saldo: Rp ' . number_format($account->balance, 0, ',', '.'));
        }

        // Deduct from balance
        $account->decrement('balance', $request->disbursement);

        // Create campaign
        $campaign = Campaign::create([
            'ad_account_id' => $account->id,
            'campaign_name' => $request->campaign_name,
            'campaign_id' => 'CAMP-' . strtoupper(uniqid()),
            'budget_daily' => $request->disbursement / max(1, \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1),
            'budget_total' => $request->disbursement,
            'publisher' => $request->publisher,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Kampanye Iklan "' . $campaign->campaign_name . '" berhasil dibuat melalui publisher ' . $campaign->publisher . '. Saldo telah terpotong sebesar Rp ' . number_format($request->disbursement, 0, ',', '.'));
    }
}
