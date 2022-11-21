<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends BaseModel
{
    use HasFactory;
    protected $fillable = ['user_id'];

    function cart_items()
    {
        return $this->hasMany(CartProduct::class)->with('dish');
    }

    function dishes()
    {
        return $this->belongsToMany(Dishes::class, 'cart_product', 'cart_id', 'dish_id')
                        ->withPivot('quantity');
    }

    function addNewCart($data)
    {
        return Cart::create($data);
    }
}
