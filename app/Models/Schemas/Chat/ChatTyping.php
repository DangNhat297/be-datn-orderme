<?php

namespace App\Models\Schemas\Chat;
/**
 * @OA\Schema
 *     schema="ChatTyping",
 *     type="object",
 *     title="ChatTyping",
 *     description="Chat typing realtime"
 * )
 */
class ChatTyping
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $typing;
}
