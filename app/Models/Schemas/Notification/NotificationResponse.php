<?php
namespace App\Models\Schemas\Notification;

/**
 * @OA\Schema(
 *      schema="NotificationResponse",
 *      title="Notification Request",
 *      description="Notification model",
 *      type="object",
 * )
 *
 */
class NotificationResponse
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
     * @var string
     */
    public $message_template;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $redirect_url;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/UserResponse")
     * )
     *
     * @var object
     */
    public $users;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/UserResponse"
     * )
     *
     * @var object
     */
    public $actor;

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
