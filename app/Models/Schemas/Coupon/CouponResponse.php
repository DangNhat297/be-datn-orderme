<?php

namespace App\Models\Schemas\Coupon;
/**
 * @OA\Schema
 *     schema="CouponResponse",
 *     type="object",
 *     title="CouponResponse",
 *     description="Coupon model"
 * )
 */
class CouponResponse
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
    public $created_at;
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
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;
}
