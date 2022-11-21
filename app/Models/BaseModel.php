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

    public function scopeFindByDateRange(Builder $query, Request $request): Builder
    {

        if (!$request->start_date && !$request->end_date) return $query;

        $startDate = convert_date($request->start_date);
        $endDate = convert_end_date($request->end_date);
        $query->whereBetween('created_at', [$startDate, $endDate]);

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
