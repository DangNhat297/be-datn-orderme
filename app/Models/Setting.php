<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseModel
{
    protected $fillable = [
        'phone',
        'logo',
        'email',
        'address',
        'name'
    ];
    use HasFactory;
}
