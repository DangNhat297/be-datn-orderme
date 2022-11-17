<?php

namespace App\Models\Schemas\HeroWedding\Message;

/**
 * @OA\Schema(
 *      schema="MessageCreate",
 *      title="MessageCreate",
 *      description="Message request body data",
 *      type="object",
 * )
 *
 */
class MessageCreate
{

    /**
     * @OA\Property(example="Hero")
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(example="0987654321")
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(example="Thank you for being here!")
     *
     * @var string
     */
    public $message;

    /**
     * @OA\Property(
     *     example=1
     * )
     *
     * @var integer
     */
    public $confirm;

    /**
     * @OA\Property(
     *     example="NT"
     * )
     *
     * @var string
     */
    public $side;

    /**
     * @OA\Property(
     *     example=1
     * )
     *
     * @var integer
     */
    public $quantity;

}
