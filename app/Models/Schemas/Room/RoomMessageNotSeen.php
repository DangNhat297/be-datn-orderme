<?php

namespace App\Models\Schemas\Room;
/**
 * @OA\Schema
 *     schema="RoomMessageNotSeen",
 *     type="object",
 *     title="RoomMessageNotSeen",
 *     description="Room model"
 * )
 */
class RoomMessageNotSeen
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $messageNotSeen;

}
