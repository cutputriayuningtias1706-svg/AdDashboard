@extends('layouts.main')

@section('title', 'Invoice ' . $invoice->invoice_number . ' - Ad Dashboard')
@section('page-title', 'Invoice Details')

@section('content')
<div class="max-w-4xl">
    <!-- Invoice Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $invoice->adAccount->account_name }}</p>
            </div>
            <div class="flex items-center gap-3">
                @switch($invoice->status)
                    @case('paid')
                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-medium">Paid</span>
                        @break
                    @case('pending')
                        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                        @break
                    @case('overdue')
                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium">Overdue</span>
                        @break
                    @default
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium">Draft</span>
                @endswitch
                <a href="{{ route('invoices.download', $invoice->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    <i class="fa-solid fa-download mr-2"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500 mb-4">Billing Period</h3>
            <p class="text-lg font-semibold text-gray-800">
                {{ $invoice->period_start->format('M d, Y') }} - {{ $invoice->period_end->format('M d, Y') }}
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500 mb-4">Due Date</h3>
            <p class="text-lg font-semibold text-gray-800">
                {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Amount Breakdown -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-4">Amount Details</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Subtotal</span>
<span class="font-medium text-gray-800">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600">Tax (10%)</span>
<span class="font-medium text-gray-800">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between py-3">
                <span class="text-lg font-semibold text-gray-800">Total</span>
<span class="text-lg font-bold text-gray-800">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        @if($invoice->status != 'paid')
        <div class="mt-6 pt-6 border-t border-gray-100">
            <form action="{{ route('invoices.markPaid', $invoice->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                    <i class="fa-solid fa-check mr-2"></i> Mark as Paid
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Notes -->
    @if($invoice->notes)
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mt-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
        <p class="text-gray-600">{{ $invoice->notes }}</p>
    </div>
    @endif
    
    <div class="mt-6">
        <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Invoices
        </a>
    </div>
</div>
@endsection
