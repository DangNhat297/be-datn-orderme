<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroWeddingGuest extends Model
{
    use HasFactory;

    protected $table = 'HeroWeddingGuest';

    protected $fillable = [
        'guest_name',
        'guest_slug',
        'notes',
        'pronoun',
        'prefix',
        'invitation_pronoun',
    ];
}
