<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderUpdateClient",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderUpdateClient
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
    public $code;
}
