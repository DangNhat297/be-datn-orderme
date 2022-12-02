<?php

namespace App\Models\Schemas\Room;
/**
 * @OA\Schema
 *     schema="RoomCreate",
 *     type="object",
 *     title="RoomCreate",
 *     description="Room model"
 * )
 */
class RoomCreate
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $userId;

}
