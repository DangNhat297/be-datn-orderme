<?php

namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderCreate",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderCreate
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
    public $note;
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $location_id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $total;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/OrderDetailRequest")
     * )
     *
     * @var array
     */
    public $dishes;
}
