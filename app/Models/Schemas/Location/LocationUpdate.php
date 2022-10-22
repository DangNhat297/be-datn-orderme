<?php

namespace App\Models\Schemas\Location;
/**
 * @OA\Schema
 *     schema="LocationUpdate",
 *     type="object",
 *     title="LocationUpdate",
 *     description="Location model"
 * )
 */
class LocationUpdate
{
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

}
