<?php
namespace App\Models\Schemas\Order;

/**
 * @OA\Schema(
 *      schema="OrderResponse",
 *      title="Order Request",
 *      description="Order model",
 *      type="object",
 * )
 *
 */
class OrderResponse
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;
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
    public $code;

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
    public $phone;

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
    public $note;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/LocationResponse"
     * )
     *
     * @var object
     */
    public $location;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/UserResponse"
     * )
     *
     * @var object
     */
    public $user;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $payment_url;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $location_detail;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $price_sale;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $price_none_sale;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $coupon_id;

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

?>
