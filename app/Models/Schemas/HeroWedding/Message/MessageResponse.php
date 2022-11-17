<?php

namespace App\Models\Schemas\HeroWedding\Message;

/**
 * @OA\Schema(
 *      schema="MessageResponse",
 *      title="MessageResponse",
 *      description="Message response data",
 *      type="object",
 * )
 *
 */
class MessageResponse
{

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $message;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $confirm;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $side;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $quantity;

}
