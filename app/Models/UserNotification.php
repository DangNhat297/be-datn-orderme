<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserNotification extends BaseModel
{
    use HasFactory;

    protected $table = "users_notifications";
    protected $fillable = [
        'notification_id',
        'recipient_id',
        'isSeen'
    ];

    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class, 'id', 'notification_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'recipient_id');
    }
}
