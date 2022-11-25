<?php

namespace App\Models\Schemas\Chat;
/**
 * @OA\Schema
 *     schema="ChatUpdate",
 *     type="object",
 *     title="ChatUpdate",
 *     description="Chat model"
 * )
 */
class ChatUpdate
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $sender_id;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $receiver_id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $content;
}
