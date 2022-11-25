<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'status',
        'phone',
        'note',
        'location_id',
        'total'
    ];

    public static function booted()
    {
        static::creating(function (Order $order) {
            $order->status = 1;
            $order->user_id = auth()->id ?? null;
            $order->code = generate_order_code();
        });
    }

    /**
     * logs
     *
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class);
    }

    /**
     * location
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


    /**
     * location
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'phone', 'phone');
    }


    /**
     * dishes
     *
     * @return BelongsToMany
     */
    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dishes::class, 'dish_order', 'order_id', 'dish_id')
            ->withPivot(['quantity', 'price']);
    }
}
