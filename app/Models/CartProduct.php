<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProduct extends BaseModel
{
    use HasFactory;

    protected $table = 'cart_product';

    protected $fillable = [
        'cart_id',
        'dish_id',
        'quantity'
    ];

    function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    function dish()
    {
        return $this->belongsTo(Dishes::class);
    }

    function addNewCartDetail($data)
    {
        $cart = CartProduct::create($data);
        return $cart;
    }

    function updateCartDetail($data)
    {
        $cart = CartProduct::where('dish_id', $data['dish_id'])
            ->where('cart_id', $data['cart_id'])->first();
        $cart->update($data);
        return $cart;
    }
}
