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
    public $vnp_url;

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
