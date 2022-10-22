<?php

namespace App\Models\Schemas\Location;
/**
 * @OA\Schema
 *     schema="LocationResponse",
 *     type="object",
 *     title="LocationResponse",
 *     description="Location model"
 * )
 */
class LocationResponse
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $address;

    /**
     * @OA\Property()
     *
     * @var number
     */
    public $distance;

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
