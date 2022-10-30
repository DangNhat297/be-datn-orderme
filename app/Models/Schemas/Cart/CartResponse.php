<?php

namespace App\Models\Schemas\Cart;
/**
 * @OA\Schema
 *     schema="CartResponse",
 *     type="object",
 *     title="CartResponse",
 *     description="Cart model",
 *     required={"created_at","updated_at","cart_detail","id","user_id"}
 * )
 */
class CartResponse
{
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
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/CartDetailResponse")
     * )
     *
     * @var array
     */
    public $cart_detail;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $id;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $user_id;
}
