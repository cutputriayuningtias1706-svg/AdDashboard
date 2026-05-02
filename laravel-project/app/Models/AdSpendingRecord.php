<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdSpendingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'record_date',
        'impressions',
        'clicks',
        'conversions',
        'spend',
    ];

    protected $casts = [
        'impressions' => 'integer',
        'clicks' => 'integer',
        'conversions' => 'integer',
        'spend' => 'decimal:2',
        'record_date' => 'date',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getCtrAttribute()
    {
        if ($this->impressions > 0) {
            return ($this->clicks / $this->impressions) * 100;
        }
        return 0;
    }

    public function getCpcAttribute()
    {
        if ($this->clicks > 0) {
            return $this->spend / $this->clicks;
        }
        return 0;
    }

    public function getConversionRateAttribute()
    {
        if ($this->clicks > 0) {
            return ($this->conversions / $this->clicks) * 100;
        }
        return 0;
    }
}
