@extends('layouts.main')

@section('title', 'Create Invoice - Ad Dashboard')
@section('page-title', 'Create New Invoice')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ad Account</label>
                <select name="ad_account_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Ad Account</option>
                    @foreach($adAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_name }} ({{ ucfirst($account->platform) }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" name="amount" step="0.01" min="0" required class="w-full pl-8 border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period Start</label>
                    <input type="date" name="period_start" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period End</label>
                    <input type="date" name="period_end" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <input type="text" name="notes" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Invoice notes">
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <p class="text-sm text-gray-600">A 10% tax will be automatically added to the amount.</p>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('invoices.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
