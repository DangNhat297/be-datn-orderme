<?php

namespace App\Models\Schemas\Location;
/**
 * @OA\Schema
 *     schema="LocationCreate",
 *     type="object",
 *     title="LocationCreate",
 *     description="Location model"
 * )
 */
class LocationCreate
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
