<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'payment_method',
        'amount',
        'transaction_no',
        'transaction_status',
        'message',
        'bank_code',
        'card_type'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_code', 'code');
    }
}
