<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Program extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'banner',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dishes::class, 'flash_sales', 'program_id', 'dish_id')->withPivot('discount_percent');
    }
}
