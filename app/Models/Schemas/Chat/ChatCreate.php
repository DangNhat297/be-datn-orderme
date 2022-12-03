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
     * @var string
     */
    public $message;
}
