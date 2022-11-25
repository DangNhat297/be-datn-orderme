<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderDetailResponse",
 *      title="Order Detail Response",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderDetailResponse
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/OrderDishItem")
     * )
     *
     * @var array
     */
    public $dishes;
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/LogResponse")
     * )
     *
     * @var array
     */
    public $logs;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $total;
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
    public $updated_at;

}
