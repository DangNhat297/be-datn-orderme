<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_phone',
        'message_template',
        'redirect_url',
        'type',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_notifications', 'notification_id', 'recipient_id')->withPivot('isSeen');
    }

//    public function user(): BelongsToMany
//    {
//        return $this->belongsTo(User::class, 'users_notifications', 'notification_id', 'recipient_id');
//    }

    /**
     * User
     *
     * @return HasOne
     */
    public function actor(): HasOne
    {
        return $this->hasOne(User::class, 'phone', 'user_phone');
    }
}
