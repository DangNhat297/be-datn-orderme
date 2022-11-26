<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroWeddingGuest extends BaseModel
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
