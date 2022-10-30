<?php

namespace App\Models\Schemas\Cart;
/**
 * @OA\Schema
 *     schema="CartUpdate",
 *     type="object",
 *     title="CartUpdate",
 *     description="Cart model",
 *     required={"dish_id","quantity","user_id"}
 * )
 */
class CartUpdate
{
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $quantity;
}
