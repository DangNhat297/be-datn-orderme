<?php
namespace App\Models\Schemas\Notification;

/**
 * @OA\Schema(
 *      schema="NotificationMultipleSeen",
 *      title="UserNotification Response",
 *      description="Notification model",
 *      type="object",
 * )
 *
 */
class NotificationMultipleSeen
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(type="number")
     * )
     *
     * @var array
     */
    public $ids;

}

?>
