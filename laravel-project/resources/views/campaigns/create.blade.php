@extends('layouts.main')

@section('title', 'Pasang Iklan - AdDashboard Pro')

@section('content')
<div class="max-w-5xl mx-auto pb-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Pasang Iklan Baru</h1>
        <p class="text-sm text-slate-500 mt-1">Buat kampanye iklan dan distribusikan saldo top-up Anda ke berbagai publisher terkemuka.</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-semibold text-red-800">Gagal Memasang Iklan</h4>
            <p class="text-sm text-red-600 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <form action="{{ route('campaigns.store') }}" method="POST" id="campaign-form" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Form Input (Kiri) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Dasar -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">1</span>
                        Detail Kampanye
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Nama Kampanye <span class="text-red-500">*</span></label>
                            <input type="text" name="campaign_name" value="{{ old('campaign_name') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" placeholder="Contoh: Promo Akhir Tahun 2026" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Target Iklan (Objective) <span class="text-red-500">*</span></label>
                            <select name="objective" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors appearance-none" required>
                                <option value="" disabled selected>Pilih Target Iklan...</option>
                                <option value="download">Download Aplikasi</option>
                                <option value="followers">Menambah Followers</option>
                                <option value="views">Jumlah Penonton Iklan (Views)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Tanggal Berakhir <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pemilihan Publisher -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">2</span>
                        Platform & Publisher
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-3 uppercase tracking-wider">Pilih Akun Iklan (Sumber Saldo) <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($adAccounts as $account)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="ad_account_id" value="{{ $account->id }}" class="peer sr-only" required onchange="updateBalance({{ $account->balance }})">
                                    <div class="p-4 border-2 border-slate-100 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all hover:border-slate-300">
                                        <div class="flex items-center gap-3">
                                            @if($account->platform == 'google')
                                                <i class="fa-brands fa-google text-2xl text-blue-500"></i>
                                            @elseif($account->platform == 'meta')
                                                <i class="fa-brands fa-meta text-2xl text-blue-600"></i>
                                            @else
                                                <i class="fa-brands fa-tiktok text-2xl text-slate-800"></i>
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800 leading-none">{{ $account->account_name }}</p>
                                                <p class="text-[11px] text-slate-500 mt-1">Saldo: Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-blue-500">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-3 uppercase tracking-wider">Jasa Publisher <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($publishers as $pub)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="publisher" value="{{ $pub }}" class="peer sr-only" required onchange="updatePublisherSummary('{{ $pub }}')">
                                    <div class="p-3 text-center border-2 border-slate-100 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all hover:border-slate-300">
                                        <div class="w-10 h-10 mx-auto rounded-full bg-white shadow-sm flex items-center justify-center mb-2">
                                            <i class="fa-solid fa-bullhorn text-blue-500"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700">{{ $pub }}</p>
                                    </div>
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-blue-500 text-xs">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disbursement -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">3</span>
                        Disbursement Iklan
                    </h3>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Jumlah Potongan Saldo (Rp) <span class="text-red-500">*</span></label>
                        <p class="text-[11px] text-slate-500 mb-3">Jumlah ini akan dipotong langsung dari saldo akun iklan yang Anda pilih di atas.</p>
                        
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                            <input type="number" name="disbursement" id="disbursement" value="{{ old('disbursement') }}" min="10000" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" placeholder="0" required onkeyup="updateSummary()">
                        </div>
                    </div>
                </div>

                <!-- Materi Iklan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">4</span>
                        Materi Iklan (Asset)
                    </h3>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Upload Video / Gambar <span class="text-red-500">*</span></label>
                        
                        <div class="relative flex justify-center items-center w-full h-40 border-2 border-dashed border-slate-300 bg-slate-50 rounded-xl hover:bg-slate-100 hover:border-blue-400 transition-colors cursor-pointer group" id="drop-zone">
                            <input type="file" name="ad_asset" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*,video/*" required id="file-input">
                            <div class="text-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-white shadow-sm flex items-center justify-center">
                                    <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Drag & Drop file di sini</p>
                                <p class="text-[11px] text-slate-500 mt-1">atau klik untuk memilih (JPG, PNG, MP4)</p>
                            </div>
                        </div>
                        
                        <!-- File preview -->
                        <div id="file-preview" class="hidden mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-file-image text-blue-500 text-xl" id="file-icon"></i>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700" id="file-name">filename.png</p>
                                    <p class="text-[10px] text-slate-500" id="file-size">2.5 MB</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-400 hover:text-red-500 transition-colors p-1" onclick="removeFile()">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan (Kanan) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wide border-b border-slate-100 pb-3">Ringkasan Pemasangan</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Sisa Saldo Akun</span>
                            <span class="text-sm font-semibold text-slate-800" id="summary-balance">Pilih Akun</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Publisher</span>
                            <span class="text-sm font-semibold text-slate-800" id="summary-publisher">-</span>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-semibold text-slate-600">Total Spending</span>
                                <span class="text-xl font-bold text-blue-600" id="summary-amount">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg mb-6">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-xs"></i>
                            <p class="text-[11px] text-blue-800 leading-relaxed">
                                Saldo akan dipotong seketika saat kampanye dibuat. Pastikan saldo Anda mencukupi untuk total disbursement.
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-xl font-semibold shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Tayangkan Iklan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let currentBalance = 0;

    function updateBalance(balance) {
        currentBalance = balance;
        document.getElementById('summary-balance').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(balance);
        checkSufficient();
    }

    function updatePublisherSummary(pub) {
        document.getElementById('summary-publisher').innerText = pub;
    }

    function updateSummary() {
        const val = document.getElementById('disbursement').value || 0;
        document.getElementById('summary-amount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        checkSufficient();
    }

    function checkSufficient() {
        const val = parseInt(document.getElementById('disbursement').value || 0);
        const balEl = document.getElementById('summary-balance');
        
        if (val > currentBalance && currentBalance > 0) {
            balEl.classList.remove('text-slate-800');
            balEl.classList.add('text-red-500');
        } else {
            balEl.classList.add('text-slate-800');
            balEl.classList.remove('text-red-500');
        }
    }

    // Drag and Drop Logic
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileIcon = document.getElementById('file-icon');

    fileInput.addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            showFile(this.files[0]);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-blue-50', 'border-blue-400');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-blue-50', 'border-blue-400');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-blue-50', 'border-blue-400');
        
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showFile(e.dataTransfer.files[0]);
        }
    });

    function showFile(file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        
        if (file.type.startsWith('video/')) {
            fileIcon.className = 'fa-solid fa-file-video text-blue-500 text-xl';
        } else {
            fileIcon.className = 'fa-solid fa-file-image text-blue-500 text-xl';
        }
        
        filePreview.classList.remove('hidden');
        dropZone.classList.add('hidden');
    }

    function removeFile() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    }
</script>
@endsection
