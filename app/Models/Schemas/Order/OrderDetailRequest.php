<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderDetailRequest",
 *      title="Order Detail Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderDetailRequest
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $dish_id;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $quantity;

}
