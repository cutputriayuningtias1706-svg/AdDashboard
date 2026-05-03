@extends('layouts.main')

@section('title', 'Top-up Saldo - AdDashboard Pro')
@section('page-title', 'Top-up Saldo Iklan')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
    <!-- Completed -->
    <div class="card-hover fade-up rounded-2xl p-6 relative overflow-hidden bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background:linear-gradient(135deg,#10b981,#34d399);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Completed</p>
                <p class="text-2xl font-bold" style="color:#059669;">Rp {{ number_format($totalCompleted, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#34d399); box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                <i class="fa-solid fa-check-circle text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="card-hover fade-up-2 rounded-2xl p-6 relative overflow-hidden bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background:linear-gradient(135deg,#f59e0b,#f97316);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Total Pending</p>
                <p class="text-2xl font-bold" style="color:#d97706;">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,#f59e0b,#f97316); box-shadow:0 4px 14px rgba(245,158,11,0.3);">
                <i class="fa-solid fa-clock text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- New Top-up -->
    <div class="card-hover fade-up-3 rounded-2xl p-6 relative overflow-hidden bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background:linear-gradient(135deg,#3b82f6,#2563eb);"></div>
        <div class="flex items-center justify-between h-full">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">Quick Action</p>
                <a href="{{ route('topup.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition" style="background:linear-gradient(135deg,#3b82f6,#2563eb); box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                    <i class="fa-solid fa-plus text-xs"></i> Top-up Baru
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center icon-glow-blue" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                <i class="fa-solid fa-wallet text-white text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="fade-up rounded-2xl p-5 mb-5 bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <form action="{{ route('topup.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="w-64">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Platform</label>
            <select name="ad_account" onchange="this.form.submit()" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">All Platforms</option>
                @foreach($adAccounts as $account)
                <option value="{{ $account->id }}" {{ request('ad_account')==$account->id?'selected':'' }}>
                    {{ $account->account_name }} ({{ ucfirst($account->platform) }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[280px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Pencarian</label>
            <div class="deep-search-container">
                <input type="text" name="search" value="{{ request('search') }}" class="deep-search-input" placeholder="Cari transaksi atau metode...">
            </div>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
            <i class="fa-solid fa-filter text-xs"></i> Cari
        </button>
    </form>
</div>

<!-- Topup Table -->
<div class="fade-up rounded-2xl overflow-hidden bg-primary-card" style="box-shadow:0 2px 12px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Platform</th>
                    <th class="px-6 py-4">Akun</th>
                    <th class="px-6 py-4 text-right">Jumlah</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topups as $topup)
                <tr class="table-row-hover border-b border-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $topup->topup_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-sm font-semibold text-slate-700 inline-flex items-center gap-2">
                            @if($topup->adAccount->platform=='google') <i class="fa-brands fa-google text-blue-500"></i>
                            @elseif($topup->adAccount->platform=='meta') <i class="fa-brands fa-facebook text-blue-600"></i>
                            @else <i class="fa-brands fa-tiktok text-slate-800"></i>
                            @endif
                            {{ $topup->adAccount->platform }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $topup->adAccount->account_name }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800 text-right">Rp {{ number_format($topup->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $topup->payment_method)) }}</td>
                    <td class="px-6 py-4">
                        @if($topup->status=='completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(16,185,129,0.1);color:#059669;">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Completed
                            </span>
                        @elseif($topup->status=='pending')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(245,158,11,0.1);color:#d97706;">Pending</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(244,63,94,0.1);color:#e11d48;">{{ ucfirst($topup->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f1f5f9;">
                            <i class="fa-solid fa-inbox text-slate-300 text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-medium">Belum ada top-up</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 pagination-container">{{ $topups->links() }}</div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find the active pagination item and add the custom class
        const activeItem = document.querySelector('.pagination-container [aria-current="page"] span');
        if (activeItem) {
            activeItem.parentElement.classList.add('active-page');
            activeItem.style.color = '#fff';
            activeItem.style.background = 'transparent';
        }
    });
</script>
@endpush
@endsection
