<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dishes extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'image',
        'quantity',
        'category_id',
        'price',
        'status'
    ];

    function dishes_category()
    {
        return $this->belongsTo(Category::class);
    }
}
