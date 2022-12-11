<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'discount_percent',
        'start_date',
        'end_date',
    ];

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dishes::class, 'flash_sales', 'program_id', 'dish_id');
    }
}
