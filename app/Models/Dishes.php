<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dishes extends Model
{
    use HasFactory;
    protected $fillable=['id','name','slug','description','content','image','quantity','category_id','price'];

    function dishes_category(){
        return $this->belongsTo(Category::class);
    }

}
