<?php

namespace App\Models\Schemas\Chat;
/**
 * @OA\Schema
 *     schema="ChatResponse",
 *     type="object",
 *     title="ChatResponse",
 *     description="Chat model"
 * )
 */
class ChatResponse
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
    public $sender;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $content;

//    /**
//     * @OA\Property(
//     *     type="object",
//     *     ref="#/components/schemas/RoomResponse"
//     * )
//     *
//     * @var object
//     */
//    public $room;


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
