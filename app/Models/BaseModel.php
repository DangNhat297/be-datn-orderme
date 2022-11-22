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


    public function scopeFindByCategory(Builder $query, Request $request): Builder
    {
        if ($category = $request->category) {
            $cate=Category::query()->where('slug',$category)->first();
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

    public function scopeFindSort(Builder $query, Request $request): Builder
    {
        $desc='-';
        $asc='+';
        if (isset($request->sort)) {
            $sort=$request->sort;
            if (strlen(strstr($sort, $desc)) > 0) {
                $sort= str_replace($desc, '', $request->sort);
                return $query->orderBy("$sort",'desc');
            } else {
                $sort= str_replace($asc, '', $request->sort);
                return $query->orderBy("$sort",'asc');
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

}
