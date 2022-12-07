<?php

namespace App\Models\Schemas\Coupon;
/**
 * @OA\Schema
 *     schema="CouponUpdate",
 *     type="object",
 *     title="CouponUpdate",
 *     description="Coupon model"
 * )
 */
class CouponUpdate
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $coupon;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $description;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $type;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $discount_percent;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $discount_price;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $quantity;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $start_date;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $end_date;
}
