<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroWeddingMessage extends Model
{
    use HasFactory;

    protected $table = "HeroWeddingMessage";

    protected $fillable = [
        'name',
        'phone',
        'message',
        'confirm',
        'side',
        'quantity'
    ];
}
