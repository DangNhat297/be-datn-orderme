<?php
namespace App\Models\Schemas\Notification;

/**
 * @OA\Schema(
 *      schema="UserNotificationResponse",
 *      title="UserNotification Response",
 *      description="Notification model",
 *      type="object",
 * )
 *
 */
class UserNotificationResponse
{
    /**
     * @OA\Property()
     *
     * @var boolean
     */
    public $isSeen;

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
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/NotificationResponse"
     * )
     *
     * @var object
     */
    public $notification;

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
