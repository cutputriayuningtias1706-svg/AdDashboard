<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopupBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_account_id',
        'amount',
        'bonus',
        'total_amount',
        'status',
        'payment_method',
        'transaction_id',
        'topup_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'topup_date' => 'datetime',
    ];

    public function adAccount(): BelongsTo
    {
        return $this->belongsTo(AdAccount::class);
    }
}
