<?php

namespace App\Models\Schemas\User;
/**
 * @OA\Schema
 *     schema="UserLogin",
 *     type="object",
 *     title="UserLogin",
 *     description="User model"
 * )
 */
class UserLogin
{
    /**
     * @OA\Property()
     *
     * @var number
     */
    public $phone;
}
