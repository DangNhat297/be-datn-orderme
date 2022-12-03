<?php

namespace App\Models\Schemas\Room;
/**
 * @OA\Schema
 *     schema="RoomResponse",
 *     type="object",
 *     title="RoomResponse",
 *     description="Room model"
 * )
 */
class RoomResponse
{

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;

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
     *     ref="#/components/schemas/ChatResponse"
     * )
     *
     * @var object
     */
    public $messages;


//    /**
//     * @OA\Property()
//     *
//     * @var string
//     */
//    public $created_at;
//
//    /**
//     * @OA\Property()
//     *
//     * @var string
//     */
//    public $updated_at;
}
