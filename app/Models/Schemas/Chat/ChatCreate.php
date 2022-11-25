<?php

namespace App\Models\Schemas\Chat;
/**
 * @OA\Schema
 *     schema="ChatCreate",
 *     type="object",
 *     title="ChatCreate",
 *     description="Chat model"
 * )
 */
class ChatCreate
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
