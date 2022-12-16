<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends BaseModel
{
    use HasFactory;

    protected $table = 'dish_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'image',
    ];

    function dishes(){
       return $this->hasMany(Dishes::class);
    }

}
