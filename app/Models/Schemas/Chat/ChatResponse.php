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
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/UserResponse"
     * )
     *
     * @var object
     */
    public $sender;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/UserResponse"
     * )
     *
     * @var object
     */
    public $receiver;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $content;

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
