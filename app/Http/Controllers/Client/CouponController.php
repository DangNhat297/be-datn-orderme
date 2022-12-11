<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function __construct(
        protected Coupon $coupon
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/client/coupon",
     *      operationId="getCouponClient",
     *      tags={"Coupon Client"},
     *      summary="Get list of Coupon",
     *      description="Returns list of Coupon",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       ),
     *     )
     */
    public function index()
    {
        $coupons = $this->coupon
            ->newQuery()
            ->where('status', ENABLE)
            ->where('quantity', '>', 0)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        return $this->sendSuccess($coupons);
    }

    /**
     * @OA\Get(
     *      path="/client/coupon/{id}",
     *      operationId="getCouponByIdClient",
     *      tags={"Coupon Client"},
     *      summary="Get coupon information",
     *      description="Returns coupon data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CouponResponse")
     *       ),
     * )
     */
    public function show(int $id)
    {
        $coupon = $this->coupon
            ->newQuery()
            ->where('id', $id)
            ->where('status', ENABLE)
            ->where('quantity', '>', 0)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        return $this->sendSuccess($coupon);
    }
}
