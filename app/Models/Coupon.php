<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'coupon',
        'description',
        'status',
        'type',
        'discount_percent',
        'price_sale_max',
        'discount_price',
        'quantity',
        'start_date',
        'end_date'
    ];
}
