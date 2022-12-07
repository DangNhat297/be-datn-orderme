<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(
        protected Coupon $coupon
    ) {
    }

    public function show(Request $request)
    {
        $coupon = $this->coupon
                        ->newQuery()
                        ->where('coupon', $request->coupon)
                        ->where('status', ENABLE)
                        ->where('quantity', '>', 0)
                        ->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now())
                        ->first();

        return $this->sendSuccess($coupon);
    }
}
