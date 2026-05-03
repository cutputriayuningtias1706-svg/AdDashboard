<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_account_id',
        'campaign_name',
        'campaign_id',
        'budget_daily',
        'budget_total',
        'status',
        'start_date',
        'end_date',
        'publisher',
    ];

    protected $casts = [
        'budget_daily' => 'decimal:2',
        'budget_total' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function adAccount(): BelongsTo
    {
        return $this->belongsTo(AdAccount::class);
    }

    public function spendingRecords(): HasMany
    {
        return $this->hasMany(AdSpendingRecord::class);
    }

    public function getTotalSpendAttribute()
    {
        return $this->spendingRecords()->sum('spend');
    }

    public function getTotalImpressionsAttribute()
    {
        return $this->spendingRecords()->sum('impressions');
    }

    public function getTotalClicksAttribute()
    {
        return $this->spendingRecords()->sum('clicks');
    }

    public function getTotalConversionsAttribute()
    {
        return $this->spendingRecords()->sum('conversions');
    }

    public function getCtrAttribute()
    {
        $impressions = $this->total_impressions;
        if ($impressions > 0) {
            return ($this->total_clicks / $impressions) * 100;
        }
        return 0;
    }

    public function getCpcAttribute()
    {
        $clicks = $this->total_clicks;
        if ($clicks > 0) {
            return $this->total_spend / $clicks;
        }
        return 0;
    }

    public function getConversionRateAttribute()
    {
        $clicks = $this->total_clicks;
        if ($clicks > 0) {
            return ($this->total_conversions / $clicks) * 100;
        }
        return 0;
    }
}
