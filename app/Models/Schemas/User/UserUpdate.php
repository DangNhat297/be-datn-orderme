<?php

namespace App\Models\Schemas\User;

/**
 * @OA\Schema(
 *      schema="UserUpdate",
 *      title="User Update Request",
 *      description="User Update request body data",
 *      type="object",
 *      required={"name"},
 * )
 *
 */
class UserUpdate
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $role;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $status;

}
