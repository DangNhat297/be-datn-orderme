<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderUpdate",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderUpdate
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $phone;
}
