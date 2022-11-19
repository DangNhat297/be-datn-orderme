<?php

namespace App\Models\Schemas\User;

/**
 * @OA\Schema(
 *      schema="UserLogin",
 *      title="User Login Request",
 *      description="User Login request body data",
 *      type="object",
 *      required={"name"},
 * )
 *
 */
class UserLogin
{
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
    public $password;

}
