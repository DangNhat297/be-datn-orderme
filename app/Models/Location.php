<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Location extends BaseModel
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'address',
        'distance',
    ];
}
