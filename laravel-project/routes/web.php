<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\VendorController;

// Auth routes (public)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['mock.auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Platform-specific routes
    Route::get('/google-ads', [DashboardController::class, 'googleAds'])->name('dashboard.google');
    Route::get('/meta-ads', [DashboardController::class, 'metaAds'])->name('dashboard.meta');
    Route::get('/tiktok-ads', [DashboardController::class, 'tiktokAds'])->name('dashboard.tiktok');

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Invoice routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.markPaid');

    // Topup routes
    Route::get('/topup', [InvoiceController::class, 'topupIndex'])->name('topup.index');
    Route::get('/topup/create', [InvoiceController::class, 'topupCreate'])->name('topup.create');
    Route::post('/topup', [InvoiceController::class, 'topupStore'])->name('topup.store');

    // Campaigns routes
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');

    // Vendor / Contract routes
    Route::get('/contracts', [VendorController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{id}/apply', [VendorController::class, 'apply'])->name('contracts.apply');
    Route::post('/contracts/{id}/store', [VendorController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{id}/download-pks', [VendorController::class, 'downloadPks'])->name('contracts.download_pks');

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\SettingController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\SettingController::class, 'updateProfile'])->name('profile.update');
        
        Route::get('/password', [\App\Http\Controllers\SettingController::class, 'password'])->name('password');
        Route::post('/password', [\App\Http\Controllers\SettingController::class, 'updatePassword'])->name('password.update');
        
        Route::get('/integrations', [\App\Http\Controllers\SettingController::class, 'integrations'])->name('integrations');
        Route::post('/integrations/{platform}', [\App\Http\Controllers\SettingController::class, 'updateIntegration'])->name('integrations.update');
    });
});
