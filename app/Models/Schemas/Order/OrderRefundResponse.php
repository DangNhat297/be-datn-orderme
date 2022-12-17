<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderRefundResponse",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderRefundResponse
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $message;

}
