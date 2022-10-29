<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable=['user_id'];

    function cartDetail(){
        return $this->hasMany(CartProduct::class)->with('dish');
    }

    function addNewCart($data){
        return CartProduct::create($data);
    }
}
