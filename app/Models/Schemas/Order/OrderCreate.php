<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderCreate",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderCreate
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $phone;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $name;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $note;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $location_id;

    /**
     * @OA\Property()
     *
     * @var number
     */
    public $total;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $location_detail;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/OrderDetailRequest")
     * )
     *
     * @var array
     */
    public $dishes;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $payment_method;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $price_sale;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $price_none_sale;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $coupon_id;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $payment_status;
}
