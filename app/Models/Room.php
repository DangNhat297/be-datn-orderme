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
        'userId',
    ];

    /**
     * User
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'userId');
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
            return $query->whereHas('user', function ($query) use ($name) {
                return $query->where('name', 'like', '%' . $name . '%');
            });
        }

        return $query;
    }

}
