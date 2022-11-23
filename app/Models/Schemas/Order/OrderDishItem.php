<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderDishItem",
 *      title="Order Dish Item",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderDishItem
{
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $id;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $image;
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
    public $slug;
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $quantity;
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $price;

}
