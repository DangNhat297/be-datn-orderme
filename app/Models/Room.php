<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_phone',
        'user_name',
    ];

    /**
     * User
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'phone', 'user_phone');
    }

    /**
     * Chat
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Chat::class, 'room_id', 'id');
    }


    public function scopeFindByName(Builder $query, Request $request): Builder
    {
        if ($name = $request->keyword) {
            return $query->where('user_name', 'like', '%' . $name . '%');
//                ->whereHas('user', function ($query) use ($name) {
//                    return $query->where('phone', 'like', '%' . $name . '%')
//                        ->orWhere('phone', 'like', '%' . $name . '%');
//                });
        }

        return $query;
    }

}
