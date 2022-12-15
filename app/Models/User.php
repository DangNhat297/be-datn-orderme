<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  Notifiable, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'status',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFindByName(Builder $query, Request $request): Builder
    {
        if ($keyword = $request->keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('phone', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function scopeFindByStatus($query, $request): Builder
    {
        if ($status = $request->status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeFindByRole($query, $request): Builder
    {
        if ($role = $request->role) {
            return $query->where('role', $role);
        }

        return $query;
    }

    public function scopeFindOrderBy(Builder $query, Request $request): Builder
    {
        $desc = '-';
        $asc = '+';
        if (isset($request->orderBy)) {
            $orderBy = $request->orderBy;
            if (strlen(strstr($orderBy, $desc)) > 0) {
                $sort = str_replace($desc, '', $orderBy);
                return $query->orderBy("$sort", 'desc');
            } else {
                $sort = str_replace($asc, '', $orderBy);
                return $query->orderBy("$sort", 'asc');
            }

        }
        return $query;
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'phone', 'phone');
    }

//    /**
//     * Get the identifier that will be stored in the subject claim of the JWT.
//     *
//     * @return mixed
//     */
//    public function getJWTIdentifier()
//    {
//        return $this->getKey();
//    }
//
//    /**
//     * Return a key value array, containing any custom claims to be added to the JWT.
//     *
//     * @return array
//     */
//    public function getJWTCustomClaims()
//    {
//        return [];
//    }
}
