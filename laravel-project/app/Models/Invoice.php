<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_account_id',
        'invoice_number',
        'amount',
        'tax',
        'total_amount',
        'period_start',
        'period_end',
        'status',
        'notes',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'due_date' => 'datetime',
    ];

    public function adAccount(): BelongsTo
    {
        return $this->belongsTo(AdAccount::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('INV/%s/%s/%04d', $year, $month, $count);
    }
}
