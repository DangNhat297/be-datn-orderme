<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Order extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'user_id',
        'status',
        'phone',
        'note',
        'location_id',
        'location_detail',
        'total',
        'price_sale',
        'price_none_sale',
        'coupon_id',
        'payment_method',
        'payment_status',
        'payment_url'
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
            ->withPivot(['quantity', 'price', 'price_sale']);
    }


    /**
     * coupon
     *
     * @return BelongsTo
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function last_payment()
    {
        return $this->payments()->latest()->first();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_code', 'code');
    }

    public function scopeFindByCode(Builder $query, Request $request): Builder
    {
        if ($search = $request->keyword) {
            return $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function scopeFindByDistance(Builder $query, Request $request): Builder
    {
        if (isset($request->distance) && $request->distance == 0) {
            return $query->whereRelation('location', 'distance', 0);
        } elseif (isset($request->distance) && $request->distance != 0) {
            return $query->whereRelation('location', 'distance', '!=', 0);
        }

        return $query;
    }
}
