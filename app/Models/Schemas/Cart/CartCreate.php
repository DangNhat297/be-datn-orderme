<?php

namespace App\Models\Schemas\Cart;
/**
 * @OA\Schema
 *     schema="CartCreate",
 *     type="object",
 *     title="CartCreate",
 *     description="Cart model",
 *     required={"dish_id","quantity","user_id"}
 * )
 */
class CartCreate
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
     * @OA\Property()
     *
     * @var integer
     */
    private $user_id;
}
