<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'account_name',
        'account_id',
        'status',
        'balance',
        'description',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function topupBalances(): HasMany
    {
        return $this->hasMany(TopupBalance::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalSpendAttribute()
    {
        return $this->campaigns()
            ->withSum('spendingRecords', 'spend')
            ->get()
            ->sum('spending_records_sum_spend');
    }

    public function getPlatformColorAttribute()
    {
        return match($this->platform) {
            'google' => '#3B82F6',
            'meta' => '#4267B2',
            'tiktok' => '#000000',
            default => '#6B7280',
        };
    }

    public function getPlatformIconAttribute()
    {
        return match($this->platform) {
            'google' => 'google',
            'meta' => 'facebook',
            'tiktok' => 'tiktok',
            default => 'ad',
        };
    }
}
