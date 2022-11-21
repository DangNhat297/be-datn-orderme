<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseModel extends Model
{
    public function scopeFindByName(Builder $query, Request $request): Builder
    {
        if ($name = $request->search) {
            return $query->where('name', 'like', '%' . $name . '%');
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

    public function scopeFindByCode(Builder $query, Request $request): Builder
    {
        if ($code = $request->search) {
            return $query->where('code', 'like', '%' . $code . '%');
        }

        return $query;
    }
}
