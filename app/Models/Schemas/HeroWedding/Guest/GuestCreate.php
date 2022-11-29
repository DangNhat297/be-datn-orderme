<?php

namespace App\Models\Schemas\HeroWedding\Guest;

/**
 * @OA\Schema(
 *      schema="GuestCreate",
 *      title="GuestCreate",
 *      description="Guest request body data",
 *      type="object",
 * )
 *
 */
class GuestCreate
{

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $guest_name;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $guest_slug;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $notes;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $pronoun;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $prefix;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $invitation_pronoun;
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $side;

}
