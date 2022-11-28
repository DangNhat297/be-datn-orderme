<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    public const textLog = [
        ORDER_CANCEL => 'Đã Hủy',
        ORDER_PENDING => 'Chờ Xác Nhận',
        ORDER_COOKING => 'Đang Nấu',
        ORDER_WAIT_FOR_SHIPPING => 'Đang Chờ Giao Hàng',
        ORDER_SHIPPING => 'Đang Giao Hàng',
        ORDER_COMPLETE => 'Hoàn Thành',
    ];

    protected $fillable = [
        'order_id',
        'status',
        'change_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
