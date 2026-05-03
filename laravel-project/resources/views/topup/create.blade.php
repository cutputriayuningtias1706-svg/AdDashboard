@extends('layouts.main')

@section('title', 'Top-up Baru - AdDashboard Pro')
@section('page-title', 'Top-up Saldo Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Top-up Saldo</h2>
        
        <form action="{{ route('topup.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Platform Akun Iklan</label>
                <select name="ad_account_id" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Platform --</option>
                    @foreach($adAccounts as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->account_name }} ({{ ucfirst($account->platform) }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Top-up (Rp)</label>
                <input type="number" name="amount" required min="10000" step="1000" 
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Minimal Rp 10.000">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="payment_method" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Catatan tambahan..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fa-solid fa-check mr-2"></i> Proses Top-up
                </button>
                <a href="{{ route('topup.index') }}" class="px-6 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-600">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
