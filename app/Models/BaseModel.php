<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseModel extends Model
{
    public function scopeFindByTitle(Builder $query, Request $request): Builder
    {
        if ($name = $request->keyword) {
            return $query->where('title', 'like', '%' . $name . '%');
        }

        return $query;
    }

    public function scopeFindByName(Builder $query, Request $request): Builder
    {
        if ($name = $request->keyword) {
            return $query->where('name', 'like', '%' . $name . '%');
        }

        return $query;
    }

    public function scopeFindByCoupon(Builder $query, Request $request): Builder
    {
        if ($name = $request->keyword) {
            return $query->where('coupon', 'like', '%' . $name . '%');
        }

        return $query;
    }

    public function scopeFindByType(Builder $query, Request $request): Builder
    {
        if ($name = $request->type) {
            return $query->where('type', $name);
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

    public function scopeFindByDate(Builder $query, Request $request): Builder
    {
        if (!$request->start_date && !$request->end_date) return $query;
        $startDate = convert_date($request->start_date);
        $endDate = convert_end_date($request->end_date);
        $query->whereBetween('start_date', [$startDate, $endDate]);

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
        if ($code = $request->keyword) {
            return $query->where('code', 'like', '%' . $code . '%');
        }

        return $query;
    }


    public function scopeFindByCategory(Builder $query, Request $request): Builder
    {
        if ($category = $request->category) {
            $cate = Category::query()->where('slug', $category)->first();
            return $query->where('category_id', $cate->id);
        }

        return $query;
    }


    public function scopeFindBySlug(Builder $query, Request $request): Builder
    {
        if ($slug = $request->slug) {
            return $query->where('slug', $slug);
        }

        return $query;
    }

    public function scopeFindByHeroSlug(Builder $query, Request $request): Builder
    {
        if ($slug = $request->keyword) {
            return $query->where('guest_slug', 'like', '%' . $slug . '%');
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


    public function scopeFindByPriceRange(Builder $query, Request $request): Builder
    {
        if (!$request->start_price && !$request->end_price) return $query;
        $priceStart = $request->start_price;
        $priceEnd = $request->end_price;
        return $query->whereBetween('price', [$priceStart, $priceEnd]);
    }


    function scopeFindByLocation(Builder $query, Request $request)
    {
        if ($search = $request->keyword) {
            return $query->where('address', 'like', '%' . $search . '%')
                ->orWhere('distance', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function scopeFindByDay(Builder $query, Request $request): Builder
    {
        if (!$request->day) return $query;
        $day = $request->day;
        return $query->whereDay('created', $day);
    }

    public function scopeFindByMonth(Builder $query, Request $request): Builder
    {
        if (!$request->month) return $query;
        $month = $request->month;
        return $query->whereMoth('created', $month);
    }

    public function scopeFindByYear(Builder $query, Request $request): Builder
    {
        if (!$request->year) return $query;
        $year = $request->year;
        return $query->whereYear('created', $year);
    }

    public function scopeFindByMultiple(Builder $query, Request $request): Builder
    {
        if ($keyword = $request->keyword) {
            return $query->where('code', 'like', '%' . $keyword . '%')
                ->orWhere('phone', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function scopeFetchTypeData(Builder $query, Request $request) 
    {
        if ($request->paginate) {
            return $query->paginate($request->limit ?? PAGE_SIZE_DEFAULT);
        }

        return $query->get();
    }
}
