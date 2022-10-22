<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'dish_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'parent_id',
        'is_deleted'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeFindByName($query, $request)
    {
        if ($name = $request->search) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    public function scopeFindByStatus($query, $request)
    {
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        return $query;
    }
}
