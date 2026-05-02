@extends('layouts.main')

@section('title', 'Invoices - Ad Dashboard')
@section('page-title', 'Billing & Invoices')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
    @php
    $summaryCards = [
        ['label'=>'Pending','value'=>'Rp '.number_format($totalPending,0,',','.'),'icon'=>'fa-clock','grad'=>'linear-gradient(135deg,#f59e0b,#f97316)','glow'=>'rgba(245,158,11,0.3)','textColor'=>'#d97706'],
        ['label'=>'Paid','value'=>'Rp '.number_format($totalPaid,0,',','.'),'icon'=>'fa-check-circle','grad'=>'linear-gradient(135deg,#10b981,#34d399)','glow'=>'rgba(16,185,129,0.3)','textColor'=>'#059669'],
        ['label'=>'Overdue','value'=>'Rp '.number_format($totalOverdue,0,',','.'),'icon'=>'fa-exclamation-triangle','grad'=>'linear-gradient(135deg,#f43f5e,#e11d48)','glow'=>'rgba(244,63,94,0.3)','textColor'=>'#e11d48'],
    ];
    @endphp
    @foreach($summaryCards as $card)
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background:{{ $card['grad'] }};"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold" style="color:{{ $card['textColor'] }};">{{ $card['value'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:{{ $card['grad'] }}; box-shadow:0 4px 14px {{ $card['glow'] }};">
                <i class="fa-solid {{ $card['icon'] }} text-white text-lg"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filter and Actions -->
<div class="fade-up rounded-2xl p-5 mb-5" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Account</label>
                <select name="ad_account" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All Accounts</option>
                    @foreach($adAccounts as $account)
                    <option value="{{ $account->id }}" {{ request('ad_account')==$account->id?'selected':'' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Status</label>
                <select name="status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="all">All Status</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                    <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Overdue</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <i class="fa-solid fa-filter text-xs"></i> Filter
            </button>
        </form>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition" style="background:linear-gradient(135deg,#10b981,#059669); box-shadow:0 4px 12px rgba(16,185,129,0.3);">
            <i class="fa-solid fa-plus text-xs"></i> Create Invoice
        </a>
    </div>
</div>

<!-- Invoices Table -->
<div class="fade-up rounded-2xl overflow-hidden" style="background:#fff; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100" style="background:#f8fafc;">
                    <th class="px-6 py-4">Invoice #</th>
                    <th class="px-6 py-4">Platform</th>
                    <th class="px-6 py-4">Period</th>
                    <th class="px-6 py-4 text-right">Subtotal</th>
                    <th class="px-6 py-4 text-right">Tax (10%)</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Due Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr class="table-row-hover border-b border-slate-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('invoices.show', $invoice) }}" class="text-sm font-bold hover:underline" style="color:#6366f1;">{{ $invoice->invoice_number }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-sm font-semibold text-slate-700 inline-flex items-center gap-2">
                            @if($invoice->adAccount->platform=='google') <i class="fa-brands fa-google text-blue-500"></i>
                            @elseif($invoice->adAccount->platform=='meta') <i class="fa-brands fa-facebook text-indigo-500"></i>
                            @else <i class="fa-brands fa-tiktok text-pink-500"></i>
                            @endif
                            {{ $invoice->adAccount->platform }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $invoice->period_start->format('M d') }} – {{ $invoice->period_end->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 text-right">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800 text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @switch($invoice->status)
                            @case('paid')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(16,185,129,0.1);color:#059669;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Paid
                                </span>@break
                            @case('pending')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(245,158,11,0.1);color:#d97706;">Pending</span>@break
                            @case('overdue')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(244,63,94,0.1);color:#e11d48;">Overdue</span>@break
                            @default
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background:#f1f5f9;color:#64748b;">Draft</span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '–' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-xl text-slate-400 hover:text-red-500 transition mr-1" style="background:#f8fafc;" title="Download PDF">
                            <i class="fa-solid fa-download text-xs"></i>
                        </a>
                        @if($invoice->status != 'paid')
                        <form action="{{ route('invoices.markPaid', $invoice) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex w-8 h-8 items-center justify-center rounded-xl text-slate-400 hover:text-emerald-600 transition" style="background:#f8fafc;" title="Mark as Paid">
                                <i class="fa-solid fa-check text-xs"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f1f5f9;">
                            <i class="fa-solid fa-file-invoice text-slate-300 text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-medium">No invoices found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $invoices->links() }}</div>
</div>
@endsection
