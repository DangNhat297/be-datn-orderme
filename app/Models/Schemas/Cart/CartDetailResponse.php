<?php

namespace App\Models\Schemas\Cart;

/**
 * @OA\Schema
 *     schema="CartDetailResponse",
 *     type="object",
 *     title="CartDetailResponse",
 *     description="Cart model",
 *     required={"dish_id","cart_id","quantity","id","dish"}
 * )
 */
class CartDetailResponse
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
    public $cart_id;
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $quantity;
    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/DishesResponse"
     * )
     *
     * @var object
     */
    public $dish;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $id;
}
